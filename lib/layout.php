<?php
require_once __DIR__ . '/bootstrap.php';

/** Keep the user on the same experiment when switching vulnerable/patched versions. */
function version_page_url(string $version): string {
    $page = basename((string) ($_SERVER['SCRIPT_NAME'] ?? 'index.php'));
    if (!in_array($page, ['index.php', 'xss.php', 'sqli.php', 'profile.php', 'login.php'], true)) {
        $page = 'index.php';
    }
    $params = [];
    if ($page === 'xss.php' && isset($_GET['q']) && is_string($_GET['q'])) $params['q'] = $_GET['q'];
    if ($page === 'sqli.php' && isset($_GET['term']) && is_string($_GET['term'])) $params['term'] = $_GET['term'];
    return base_url($version . '/' . $page) . ($params ? '?' . http_build_query($params) : '');
}

function layout_top(string $title, string $active = ''): void {
    $version = LAB_VERSION;
    ?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#f8f7f2">
<meta name="description" content="CÁ'S HOA — thực hành an toàn Web với XSS, SQL Injection và CSRF bằng PHP, MySQL trên XAMPP.">
<title><?= h($title) ?> | CÁ'S HOA</title>
<link rel="stylesheet" href="<?= h(base_url('assets/css/site.css')) ?>">
<link rel="stylesheet" href="<?= h(base_url('assets/css/refinement.css')) ?>">
<script defer src="<?= h(base_url('assets/js/site.js')) ?>"></script>
</head>
<body class="theme-<?= h($version) ?>">
<a class="skip-link" href="#main-content">Bỏ qua menu, đến nội dung chính</a>
<div class="announcement"><span class="container">CÁ'S HOA <span class="announcement-separator">/</span> WEB SECURITY STUDIO <span class="announcement-note">— MÔI TRƯỜNG THỰC HÀNH LOCALHOST</span></span></div>
<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="<?= h(page_url()) ?>" aria-label="CÁ'S HOA, về trang cửa hàng">
      <span class="brand-symbol" aria-hidden="true">C</span>
      <span class="brand-copy"><strong>CÁ'S HOA</strong><small>Hoa cho những điều khó nói</small></span>
    </a>
    <nav class="nav" aria-label="Điều hướng chính">
      <a class="<?= $active === 'home' ? 'selected' : '' ?>" <?= $active === 'home' ? 'aria-current="page"' : '' ?> href="<?= h(page_url()) ?>">Cửa hàng</a>
      <a class="<?= $active === 'xss' ? 'selected' : '' ?>" <?= $active === 'xss' ? 'aria-current="page"' : '' ?> href="<?= h(page_url('xss.php')) ?>">XSS</a>
      <a class="<?= $active === 'sqli' ? 'selected' : '' ?>" <?= $active === 'sqli' ? 'aria-current="page"' : '' ?> href="<?= h(page_url('sqli.php')) ?>">SQL Injection</a>
      <a class="<?= $active === 'csrf' ? 'selected' : '' ?>" <?= $active === 'csrf' ? 'aria-current="page"' : '' ?> href="<?= h(page_url('profile.php')) ?>">CSRF / Tài khoản</a>
    </nav>
    <div class="version-switch" role="group" aria-label="Chọn phiên bản thực nghiệm">
      <a class="<?= $version === 'v1' ? 'current vulnerable' : '' ?>" <?= $version === 'v1' ? 'aria-current="page"' : '' ?> href="<?= h(version_page_url('v1')) ?>" title="Phiên bản có lỗ hổng">V1 <span>Có lỗi</span></a>
      <a class="<?= $version === 'v2' ? 'current secured' : '' ?>" <?= $version === 'v2' ? 'aria-current="page"' : '' ?> href="<?= h(version_page_url('v2')) ?>" title="Phiên bản đã vá">V2 <span>Đã vá</span></a>
    </div>
  </div>
</header>
<div class="lab-strip <?= $version === 'v1' ? 'danger' : 'safe' ?>">
  <div class="container lab-strip-inner">
    <span class="state-dot" aria-hidden="true"></span>
    <strong><?= $version === 'v1' ? 'Đang xem phiên bản có lỗ hổng' : 'Đang xem phiên bản đã vá' ?></strong>
    <span class="strip-description"><?= $version === 'v1' ? 'Chỉ thử nghiệm với dữ liệu giả trên localhost.' : 'Giữ nguyên giao diện và dữ liệu, bổ sung biện pháp phòng chống.' ?></span>
    <span class="strip-local">PHP / MySQL / XAMPP</span>
  </div>
</div>
<?php
}
function layout_bottom(): void { ?>
<footer class="footer">
  <div class="container footer-grid">
    <div><strong class="footer-brand">CÁ'S HOA</strong><p>Hoa cho những điều khó nói.</p><small>Giao diện lấy cảm hứng từ website cửa hàng hoa cuối kỳ.</small></div>
    <div><strong>Security Studio</strong><p>Reflected XSS · SQL Injection · CSRF</p><small>Hai phiên bản để đối chiếu nguyên nhân và cách vá.</small></div>
    <div><strong>Chỉ dành cho môi trường thử nghiệm</strong><p>PHP · MySQL · Apache (XAMPP)</p><small>Không đưa bản V1 lên máy chủ công khai.</small></div>
  </div>
  <div class="container footer-bottom"><span>© CÁ'S HOA — Web Security Midterm</span><span>Local demonstration only</span></div>
</footer>
</body>
</html>
<?php }