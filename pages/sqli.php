<?php
require_once __DIR__ . '/../lib/layout.php';
$term=isset($_GET['term']) && is_string($_GET['term']) ? substr(trim($_GET['term']),0,120) : '';
$results=[]; $error='';
if($term!=='') {
    try {
        if(LAB_VERSION==='v1') {
            // INTENTIONAL VULNERABILITY: SQL string concatenation; isolated local demo DB only.
            $sql = "SELECT id,name,price,image,category,occasion,is_public FROM products WHERE name LIKE '%$term%' AND is_public = 1 ORDER BY id";
            $results=db()->query($sql)->fetchAll();
        } else {
            $stmt=db()->prepare('SELECT id,name,price,image,category,occasion,is_public FROM products WHERE name LIKE :term AND is_public=1 ORDER BY id');
            $stmt->execute(['term'=>'%'.$term.'%']); $results=$stmt->fetchAll();
        }
    } catch(Throwable $e) {$error='Không thể thực hiện truy vấn. Kiểm tra MySQL hoặc giá trị tìm kiếm.';}
}
layout_top('SQL Injection – tra cứu hoa', 'sqli');
?>
<main class="container lab-page"><div class="crumb"><a href="<?= h(page_url()) ?>">Cửa hàng hoa</a> / LAB 02</div><div class="lab-heading"><p class="eyebrow">LAB 02 / INPUT → SQL QUERY</p><h1>Tra cứu hoa trong MySQL <em>(SQL Injection)</em></h1><p>Tìm sản phẩm đang bán trên cửa hàng. Hai bản demo dùng chung dữ liệu, nhưng khác cách xây dựng câu truy vấn.</p></div>
<div class="lab-columns"><section class="lab-panel"><div class="panel-title"><h2>Tìm tên sản phẩm</h2><span class="pill <?= LAB_VERSION==='v1'?'bad':'good' ?>"><?= LAB_VERSION==='v1'?'DYNAMIC SQL':'PREPARED STATEMENT' ?></span></div>
<form method="get" action="<?= h(page_url('sqli.php')) ?>"><label for="term">Tên hoa bạn muốn tìm</label><div class="form-row"><input id="term" name="term" value="<?= h($term) ?>" maxlength="120" placeholder="Ví dụ: Garden"><button class="btn primary" type="submit">Tìm sản phẩm</button></div></form>
<?php if($error): ?><div class="notice error"><?= h($error) ?></div><?php endif; ?>
<?php if($term!=='' && !$error): ?><div class="result"><small>QUERY RESULT / <?= count($results) ?> RECORD(S)</small><div class="result-products"><?php if(!$results): ?><p>Không tìm thấy sản phẩm nào.</p><?php endif; ?>
<?php foreach($results as $p): ?><article class="search-product"><img src="<?= h(photo($p['image'])) ?>" data-photo="<?= h($p['image']) ?>" alt="<?= h($p['name']) ?>"><div><small class="<?= (int)$p['is_public']===0?'private-text':'' ?>"><?= (int)$p['is_public']===0?'HỒ SƠ ẨN / DEMO':'SẢN PHẨM CÔNG KHAI' ?></small><h3><?= h($p['name']) ?></h3><span><?= h(price_vnd((int)$p['price'])) ?></span></div></article><?php endforeach; ?></div></div><?php endif; ?>
</section><aside class="lab-aside"><h3>Kiểm tra quyền truy cập dữ liệu</h3><p>V1 nối trực tiếp từ khóa tìm kiếm vào SQL. Điều kiện lọc dữ liệu nội bộ có thể bị thay đổi.</p><p>V2 sử dụng <code>PDO::prepare()</code> và tham số <code>:term</code>. Truy vấn chỉ tìm sản phẩm công khai.</p><div class="notice"><b>Payload boolean trên database mẫu</b><code class="break">' OR is_public = 0 #</code><button class="btn subtle copy-payload" type="button" data-payload="' OR is_public = 0 #">Sao chép payload</button><p>V1 sẽ trả cả sản phẩm ẩn; V2 không có kết quả. Chỉ chạy trong database riêng của lab.</p></div></aside></div></main>
<?php layout_bottom(); ?>