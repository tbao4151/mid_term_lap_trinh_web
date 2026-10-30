<?php
require_once __DIR__ . '/../lib/layout.php';
$products=[]; $error='';
try { $products=fetch_products(); } catch(Throwable $e) {$error='Chưa kết nối được MySQL. Xem README và import database/setup.sql trong phpMyAdmin.';}
layout_top('Bộ sưu tập hoa', 'home');
?>
<main>
<section class="container hero"><div class="hero-copy"><p class="eyebrow">A LITTLE FLOWER, A LOT OF LOVE</p><h1>Hoa cho những điều <em>khó nói.</em></h1><p>Khám phá những bó hoa được chọn từ bộ sưu tập của CÁ'S HOA. Đây là phần giao diện cửa hàng được tái sử dụng cho bài thực hành bảo mật web.</p><div class="actions"><a class="btn primary" href="#collection">Khám phá hoa</a><a class="btn outline" href="<?= h(page_url('xss.php')) ?>">Bắt đầu thực hành XSS</a></div></div><div class="hero-photo"><img src="<?= h(photo('garden.png')) ?>" data-photo="garden.png" alt="Bó hoa Garden của CÁ'S HOA"><div class="hero-photo-label">GARDEN · HOA CHO NGÀY ĐẸP</div></div></section>
<section class="container intro"><p class="eyebrow">WEB SECURITY EXPERIMENTS</p><h2>Ba tình huống bảo mật trên cùng một cửa hàng hoa.</h2><div class="lab-cards">
<a href="<?= h(page_url('xss.php')) ?>" class="lab-card"><small>LAB 01</small><strong>Reflected XSS</strong><span>Tìm lời nhắn cho bó hoa: dữ liệu nhập được hiển thị lại trên trang.</span><b>Thực hành tại đây →</b></a>
<a href="<?= h(page_url('sqli.php')) ?>" class="lab-card"><small>LAB 02</small><strong>SQL Injection</strong><span>Tra cứu sản phẩm trong MySQL; so sánh truy vấn nối chuỗi và prepared statement.</span><b>Thực hành tại đây →</b></a>
<a href="<?= h(page_url('profile.php')) ?>" class="lab-card"><small>LAB 03</small><strong>CSRF</strong><span>Đổi tên người nhận trên tài khoản demo, kiểm tra request giả mạo.</span><b>Thực hành tại đây →</b></a>
</div></section>
<section class="container collection" id="collection"><div class="section-heading"><div><p class="eyebrow">BỘ SƯU TẬP CÁ'S HOA</p><h2>Chọn một bó hoa.</h2></div><p>Ảnh mẫu hoa thật được lấy từ tài nguyên của dự án cuối kỳ.</p></div>
<?php if($error): ?><div class="notice error"><?= h($error) ?></div><?php endif; ?>
<div class="product-grid">
<?php foreach($products as $p): ?>
<article class="product"><div class="product-photo"><img loading="lazy" src="<?= h(photo($p['image'])) ?>" data-photo="<?= h($p['image']) ?>" alt="<?= h($p['name']) ?>"></div><div class="product-info"><small><?= h($p['category']) ?> · <?= h($p['occasion']) ?></small><h3><?= h($p['name']) ?></h3><div class="product-bottom"><strong><?= h(price_vnd((int)$p['price'])) ?></strong><a href="<?= h(page_url('sqli.php')) ?>">Khám phá</a></div></div></article>
<?php endforeach; ?></div></section></main>
<?php layout_bottom(); ?>