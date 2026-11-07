#!/usr/bin/env bash
# Read-only integration smoke checks. Requires Bash and Python 3 (standard library).
# Node.js is optional, used only for the local JavaScript syntax check.
# Bao must first add pages/phishing.php, v1/phishing.php, v2/phishing.php and load
# assets/js/phishing.js with defer. Keep an educational warning in server HTML.
# Usage (Git Bash/WSL/Linux/macOS):
#   bash tests/phishing-checks.sh http://localhost/mid_term_lap_trinh_web
#   bash tests/phishing-checks.sh http://localhost:8000
# These checks inspect HTTP/HTML, not browser interaction or computed CSP behavior.
set -euo pipefail

if [[ "${1:-}" == '--help' || "${1:-}" == '-h' ]]; then
    sed -n '2,9p' "${BASH_SOURCE[0]}"
    exit 0
fi
if (( $# > 1 )); then
    printf 'Usage: bash tests/phishing-checks.sh [http://localhost[:port]/project]\n' >&2
    exit 2
fi

repo_root="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd)"
python_cmd=()
for candidate in python3 python; do
    if command -v "$candidate" >/dev/null 2>&1 &&
        "$candidate" -c 'import sys; sys.exit(sys.version_info.major != 3)' >/dev/null 2>&1; then
        python_cmd=("$candidate")
        break
    fi
done
if (( ${#python_cmd[@]} == 0 )); then
    printf 'ERROR: Python 3 is required for HTTP and HTML checks (no pip packages needed).\n' >&2
    exit 2
fi

if command -v node >/dev/null 2>&1; then
    node --check "$repo_root/assets/js/phishing.js"
    printf 'PASS: local JavaScript syntax\n'
else
    printf 'SKIP: node --check (Node.js is not installed)\n'
fi

"${python_cmd[@]}" - "${1:-http://localhost/mid_term_lap_trinh_web}" <<'PY'
import re
import sys
import unicodedata
from html.parser import HTMLParser
from urllib.error import HTTPError, URLError
from urllib.parse import urljoin, urlsplit
from urllib.request import HTTPRedirectHandler, ProxyHandler, Request, build_opener

base = sys.argv[1].rstrip('/')
try:
    location = urlsplit(base)
    valid = (location.scheme in ('http', 'https')
             and location.hostname in ('localhost', '127.0.0.1', '::1')
             and not location.username and not location.password
             and not location.query and not location.fragment)
    location.port  # Reject malformed port numbers before requesting anything.
except ValueError:
    valid = False
if not valid:
    sys.exit('ERROR: use a localhost base URL without credentials, query or fragment.')

failures = 0


def check(condition, label):
    global failures
    print(('PASS: ' if condition else 'FAIL: ') + label)
    if not condition:
        failures += 1


class NoRedirects(HTTPRedirectHandler):
    def redirect_request(self, request, fp, code, msg, headers, newurl):
        return None


# Do not follow redirects, use proxies, submit forms, retain cookies or send data.
opener = build_opener(ProxyHandler({}), NoRedirects())


def get(url):
    try:
        with opener.open(Request(url, method='GET'), timeout=10) as response:
            body = response.read()
            check(response.status == 200, url + ' returns HTTP 200')
            return body, response.headers
    except (HTTPError, URLError, OSError, ValueError) as error:
        check(False, url + ': ' + str(error))
        return None


class Page(HTMLParser):
    void = {'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input',
            'link', 'meta', 'param', 'source', 'track', 'wbr'}

    def __init__(self, html):
        super().__init__(convert_charrefs=True)
        self.nodes = []
        self.stack = []
        self.visible_text = []
        self.feed(html)

    def handle_starttag(self, tag, attrs):
        node = {'tag': tag, 'attrs': dict(attrs), 'parents': self.stack.copy(), 'text': []}
        self.nodes.append(node)
        if tag not in self.void:
            self.stack.append(len(self.nodes) - 1)

    def handle_startendtag(self, tag, attrs):
        self.handle_starttag(tag, attrs)
        if tag not in self.void:
            self.handle_endtag(tag)

    def handle_endtag(self, tag):
        for index in range(len(self.stack) - 1, -1, -1):
            if self.nodes[self.stack[index]]['tag'] == tag:
                del self.stack[index:]
                break

    def handle_data(self, data):
        for index in self.stack:
            self.nodes[index]['text'].append(data)
        if not any(self.nodes[index]['tag'] in ('script', 'style', 'template')
                   or 'hidden' in self.nodes[index]['attrs']
                   for index in self.stack):
            self.visible_text.append(data)


def normalized(text):
    return ''.join(c for c in unicodedata.normalize('NFD', text.casefold())
                   if unicodedata.category(c) != 'Mn').replace('đ', 'd')


for version in ('v1', 'v2'):
    print('\nChecking ' + version.upper())
    page_url = base + '/' + version + '/phishing.php'
    result = get(page_url)
    if result is None:
        continue
    body, headers = result
    check(headers.get_content_type() == 'text/html', version + ' HTML content type')
    page = Page(body.decode('utf-8', errors='replace'))
    roots = [index for index, node in enumerate(page.nodes)
             if node['attrs'].get('id') == 'phishing-simulator']
    check(len(roots) == 1, version + ' unique #phishing-simulator')
    root = roots[0] if roots else None
    check(root is not None and page.nodes[root]['attrs'].get('data-version') == version,
          version + ' simulator data-version')

    for field in ('phishing-message', 'phishing-feedback'):
        matches = [node for node in page.nodes if node['attrs'].get('id') == field]
        check(len(matches) == 1 and root in matches[0]['parents'],
              version + ' unique #' + field + ' inside simulator')
    for attr, value in (('data-phishing-scenario', 'delivery'),
                        ('data-phishing-action', 'inspect')):
        check(any(node['attrs'].get(attr) == value and root in node['parents']
                  for node in page.nodes), version + ' ' + attr + '=' + value)

    text = ' '.join(normalized(' '.join(page.visible_text)).split())
    check(bool(re.search(r'mo phong.{0,120}(hoc tap|thuc hanh|giao duc)|'
                         r'(hoc tap|thuc hanh|giao duc).{0,120}mo phong|'
                         r'educational simulation', text)),
          version + ' educational simulation warning in server HTML')

    sensitive = []
    for node in page.nodes:
        if node['tag'] not in ('input', 'textarea', 'select'):
            continue
        attrs = node['attrs']
        hints = normalized(' '.join(str(attrs.get(key) or '') for key in
                                    ('name', 'id', 'autocomplete', 'placeholder', 'aria-label')))
        for index, label in enumerate(page.nodes):
            if label['tag'] == 'label' and (
                    (attrs.get('id') and label['attrs'].get('for') == attrs['id'])
                    or index in node['parents']):
                hints += ' ' + normalized(' '.join(label['text']))
        if (str(attrs.get('type', '')).casefold() == 'password'
                or re.search(r'password|passwd|passcode|pwd|otp|one.?time|'
                             r'mat.?khau|verification.?code|ma.?xac.?minh', hints)):
            sensitive.append(attrs.get('name') or attrs.get('id') or node['tag'])
    check(not sensitive, version + ' no password/OTP fields anywhere on page'
          + (': ' + ', '.join(sensitive) if sensitive else ''))

    assets = []
    for node in page.nodes:
        attrs = node['attrs']
        if node['tag'] == 'link' and 'stylesheet' in (attrs.get('rel') or '').lower().split():
            assets.append((attrs.get('href') or '', 'css', attrs))
        if node['tag'] == 'script' and attrs.get('src'):
            assets.append((attrs['src'], 'js', attrs))

    expected = ('assets/css/site.css', 'assets/css/refinement.css',
                'assets/js/site.js', 'assets/js/phishing.js')
    resolved = [(urljoin(page_url, path), kind, attrs) for path, kind, attrs in assets]
    for path in expected:
        check(any(urlsplit(url).path == location.path.rstrip('/') + '/' + path
                  for url, _, _ in resolved), version + ' includes ' + path)

    for url, kind, attrs in resolved:
        asset_location = urlsplit(url)
        if (asset_location.scheme, asset_location.netloc) != (location.scheme, location.netloc):
            check(False, version + ' asset must be same-origin: ' + url)
            continue
        asset = get(url)
        if asset is not None:
            data, asset_headers = asset
            allowed = ('text/css',) if kind == 'css' else (
                'text/javascript', 'application/javascript', 'application/x-javascript',
                'text/ecmascript', 'application/ecmascript')
            check(bool(data.strip()) and asset_headers.get_content_type() in allowed,
                  version + ' nonempty ' + kind + ' with correct MIME: ' + url)

    if version == 'v2':
        for name, value in (('X-Content-Type-Options', 'nosniff'),
                            ('X-Frame-Options', 'deny'),
                            ('Referrer-Policy', 'strict-origin-when-cross-origin')):
            check(headers.get(name, '').strip().casefold() == value,
                  'V2 retains ' + name + ': ' + value)
        policies = headers.get_all('Content-Security-Policy', [])
        directives = {}
        duplicate_directives = False
        for part in (policies[0].split(';') if len(policies) == 1 else []):
            tokens = part.strip().split()
            if tokens:
                if tokens[0].lower() in directives:
                    duplicate_directives = True
                directives[tokens[0].lower()] = tokens[1:]
        check(len(policies) == 1 and not duplicate_directives,
              'V2 has one CSP header without duplicate directives')
        for directive, sources in (
                ('default-src', ["'self'"]), ('script-src', ["'self'"]),
                ('style-src', ["'self'"]), ('base-uri', ["'self'"]),
                ('form-action', ["'self'"]), ('object-src', ["'none'"]),
                ('frame-ancestors', ["'none'"]),
                ('img-src', ["'self'", 'https://raw.githubusercontent.com', 'data:'])):
            check(directives.get(directive) == sources, 'V2 retains CSP ' + directive)

print('\n' + ('FAILED: ' + str(failures) + ' check(s).' if failures else 'All HTTP/HTML checks passed.'))
sys.exit(1 if failures else 0)
PY
