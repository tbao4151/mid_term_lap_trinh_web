<?php
declare(strict_types=1);
if (!defined('LAB_VERSION') || !in_array(LAB_VERSION, ['v1','v2'], true)) {
    http_response_code(500); exit('Unknown demo version.');
}
// This intentionally vulnerable teaching application must NEVER run on a public host.
$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
$hostname = explode(':', $host)[0];
if (!in_array($hostname, ['localhost','127.0.0.1','[::1]'], true)) {
    http_response_code(403); exit('This educational security lab is restricted to localhost.');
}
ini_set('display_errors', '0');
ini_set('log_errors', '1');
session_name('CAS_SECURITY_' . strtoupper(LAB_VERSION));
session_set_cookie_params(['lifetime'=>0,'path'=>'/','secure'=>false,'httponly'=>true,'samesite'=>'Lax']);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
header('Content-Type: text/html; charset=UTF-8');
if (LAB_VERSION === 'v2') {
    header("Content-Security-Policy: default-src 'self'; img-src 'self' https://raw.githubusercontent.com data:; script-src 'self'; style-src 'self'; base-uri 'self'; form-action 'self'; object-src 'none'; frame-ancestors 'none'");
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('X-Frame-Options: DENY');
}
function h(mixed $s): string { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function base_url(string $path=''): string {
    $base = str_replace('\\', '/', dirname(dirname((string)($_SERVER['SCRIPT_NAME'] ?? '/mid_term_lap_trinh_web/v2/index.php'))));
    return ($base === '/' ? '' : rtrim($base, '/')) . '/' . ltrim($path, '/');
}
function page_url(string $page='index.php'): string { return base_url(LAB_VERSION . '/' . $page); }
function photo(string $file): string { return base_url('assets/images/' . rawurlencode($file)); }
function price_vnd(int $n): string { return number_format($n, 0, ',', '.') . ' đ'; }
function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $host = getenv('CAS_DB_HOST') ?: '127.0.0.1';
    $port = getenv('CAS_DB_PORT') ?: '3306';
    $name = getenv('CAS_DB_NAME') ?: 'cas_hoa_security_lab';
    $user = getenv('CAS_DB_USER') !== false ? getenv('CAS_DB_USER') : 'root';
    $pass = getenv('CAS_DB_PASSWORD') !== false ? getenv('CAS_DB_PASSWORD') : '';
    $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";
    return $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES=>false,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS=>false
    ]);
}
function fetch_products(): array {
    return db()->query('SELECT id,name,price,image,category,occasion,is_public FROM products WHERE is_public=1 ORDER BY id')->fetchAll();
}
function csrf_token(): string {
    if (!isset($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function validate_csrf(string $token): bool { return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token); }
function logged_in(): bool { return isset($_SESSION['uid']) && is_int($_SESSION['uid']); }
function must_login(): void { if (!logged_in()) {header('Location: '.page_url('login.php')); exit;} }
