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
<main class="container lab-page" id="main-content">
  <div class="crumb"><a href="<?= h(page_url()) ?>">Cửa hàng hoa</a> / Bài thực hành 02</div>
  <section class="lab-heading" aria-labelledby="lab-title">
    <div><p class="eyebrow">THỰC HÀNH 02 / SQL INJECTION</p>
      <h1 id="lab-title">Tìm bó hoa yêu thích, <em>giữ an toàn dữ liệu.</em></h1>
      <p class="lead">Tìm sản phẩm trong bộ sưu tập hoa. So sánh hai cách viết truy vấn để hiểu vì sao dữ liệu nội bộ có thể bị lộ.</p>
      <div class="hero-context"><span>MySQL</span><span>Truy vấn sản phẩm</span><span>Prepared statements</span></div>
    </div>
    <figure class="lab-visual"><img src="<?= h(photo('garden.png')) ?>" alt="Bó hoa Garden của CÁ'S HOA" width="420" height="300"><figcaption>Garden — Bộ sưu tập hoa tươi</figcaption></figure>
  </section>
<div class="lab-columns"><section class="lab-panel"><div class="panel-title"><h2>Tìm tên sản phẩm</h2><span class="pill <?= LAB_VERSION==='v1'?'bad':'good' ?>"><?= LAB_VERSION==='v1'?'DYNAMIC SQL':'PREPARED STATEMENT' ?></span></div>
<form method="get" action="<?= h(page_url('sqli.php')) ?>"><label for="term">Tìm kiếm trong bộ sưu tập</label><p class="field-help" id="term-help">Bắt đầu bằng “Garden” để thấy kết quả thông thường.</p><div class="form-row"><input id="term" name="term" value="<?= h($term) ?>" aria-describedby="term-help" maxlength="120" placeholder="Ví dụ: Garden"><button class="btn primary" type="submit">Tìm sản phẩm</button></div></form>
<?php if($error): ?><div class="notice error"><?= h($error) ?></div><?php endif; ?>
<?php if($term!=='' && !$error): ?><div class="result"><small>QUERY RESULT / <?= count($results) ?> RECORD(S)</small><div class="result-products"><?php if(!$results): ?><p>Không tìm thấy sản phẩm nào.</p><?php endif; ?>
<?php foreach($results as $p): ?><article class="search-product"><img src="<?= h(photo($p['image'])) ?>" data-photo="<?= h($p['image']) ?>" alt="<?= h($p['name']) ?>"><div><small class="<?= (int)$p['is_public']===0?'private-text':'' ?>"><?= (int)$p['is_public']===0?'HỒ SƠ ẨN / DEMO':'SẢN PHẨM CÔNG KHAI' ?></small><h3><?= h($p['name']) ?></h3><span><?= h(price_vnd((int)$p['price'])) ?></span></div></article><?php endforeach; ?></div></div><?php endif; ?>
</section>
<aside class="lab-aside" aria-label="Giải thích SQL Injection">
<h3>Vì sao kết quả khác nhau?</h3>
<ol class="explain-steps">
  <li><b>Nhập “Garden”</b>Cả hai bản đều trả về mẫu hoa công khai phù hợp.</li>
  <li><b>Thử dữ liệu đặc biệt</b>V1 nối chuỗi vào câu SQL, có thể làm thay đổi điều kiện lọc <code>is_public</code>.</li>
  <li><b>So sánh với V2</b>PDO <code>prepare()</code> coi đầu vào là giá trị tìm kiếm thay vì cú pháp SQL.</li>
</ol>
<div class="payload-panel"><span class="payload-title">Payload thử nghiệm trên dữ liệu giả</span>
<code class="payload-code">' OR is_public = 0 #</code>
<button class="btn subtle copy-payload" type="button" data-payload="' OR is_public = 0 #">Điền payload vào ô tìm kiếm</button>
<p>V1 có thể trả về 2 mẫu ẩn; V2 không trả về mẫu ẩn.</p>
</div>
</aside></div></main>
<?php layout_bottom(); ?>