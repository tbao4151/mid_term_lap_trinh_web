<?php
require_once __DIR__ . '/bootstrap.php';
function layout_top(string $title, string $active=''): void {
    $version = LAB_VERSION; $other = $version === 'v1' ? 'v2' : 'v1';
    $base = base_url();
    ?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="CÁ'S HOA - Bài thực hành bảo mật XSS, SQLi, CSRF trên XAMPP">
<title><?= h($title) ?> | CÁ'S HOA Security Lab</title>
<link rel="stylesheet" href="<?= h(base_url('assets/css/site.css')) ?>">
<script defer src="<?= h(base_url('assets/js/site.js')) ?>"></script></head>
<body>
<div class="announcement">CÁ'S HOA — WEB SECURITY MIDTERM • CHỈ CHẠY TRÊN LOCALHOST</div>
<header class="site-header"><div class="container header-inner">
<a class="brand" href="<?= h(page_url()) ?>"><span class="brand-symbol">C</span><span><strong>CÁ'S HOA</strong><small>Hoa cho những điều khó nói</small></span></a>
<nav class="nav" aria-label="Điều hướng chính">
<a class="<?= $active==='home'?'selected':'' ?>" href="<?= h(page_url()) ?>">Cửa hàng hoa</a>
<a class="<?= $active==='xss'?'selected':'' ?>" href="<?= h(page_url('xss.php')) ?>">XSS Lab</a>
<a class="<?= $active==='sqli'?'selected':'' ?>" href="<?= h(page_url('sqli.php')) ?>">SQL Injection</a>
<a class="<?= $active==='csrf'?'selected':'' ?>" href="<?= h(page_url('profile.php')) ?>">CSRF / Tài khoản</a>
</nav><a class="version-link <?= $version==='v1'?'is-danger':'is-safe' ?>" href="<?= h(base_url($other.'/index.php')) ?>">Đang xem <?= strtoupper($version) ?> • Đổi <?= strtoupper($other) ?></a>
</div></header>
<div class="lab-strip <?= $version==='v1'?'danger':'safe' ?>"><div class="container"><strong><?= $version==='v1'?'V1 / VULNERABLE':'V2 / SECURED' ?></strong><span><?= $version==='v1'?'Phiên bản cố tình có lỗi, chỉ dùng dữ liệu demo.':'Phiên bản đã vá; dùng lại cùng bộ dữ liệu và payload.' ?></span></div></div>
<?php
}
function layout_bottom(): void { ?>
<footer class="footer"><div class="container footer-grid"><div><strong class="footer-brand">CÁ'S HOA</strong><p>Hoa tươi cho những khoảnh khắc bình dị.</p><small>Giao diện tham khảo từ đồ án cuối kỳ CÁ'S HOA.</small></div><div><strong>Bài thực hành bảo mật</strong><p>XSS · SQL Injection · CSRF</p><small>PHP + MySQL + XAMPP, chỉ chạy trên localhost.</small></div><div><strong>Phiên bản demo</strong><p><a href="<?= h(base_url('v1/')) ?>">V1 có lỗ hổng</a> / <a href="<?= h(base_url('v2/')) ?>">V2 đã vá</a></p><small>Không triển khai V1 lên Internet.</small></div></div></footer>
</body></html>
<?php }