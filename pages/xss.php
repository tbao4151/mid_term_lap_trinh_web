<?php
require_once __DIR__ . '/../lib/layout.php';
$q=isset($_GET['q']) && is_string($_GET['q']) ? substr($_GET['q'],0,400) : '';
layout_top('XSS – tìm lời nhắn', 'xss');
?>
<main class="container lab-page" id="main-content">
  <div class="crumb"><a href="<?= h(page_url()) ?>">Cửa hàng hoa</a> / Bài thực hành 01</div>
  <section class="lab-heading" aria-labelledby="lab-title">
    <div>
      <p class="eyebrow">THỰC HÀNH 01 / REFLECTED XSS</p>
      <h1 id="lab-title">Lời nhắn trên thiệp hoa, <em>hiển thị thế nào cho an toàn?</em></h1>
      <p class="lead">Nhập lời nhắn tặng kèm bó hoa và xem cách website phản hồi. Hãy thử cùng một nội dung trên V1 và V2 để so sánh kết quả.</p>
      <div class="hero-context"><span>Đầu vào từ URL</span><span>HTML output</span><span>Hai phiên bản đối chiếu</span></div>
    </div>
    <figure class="lab-visual">
      <img src="<?= h(photo('mot-bo-hoa.png')) ?>" alt="Bó hoa đỏ của CÁ'S HOA" width="420" height="300">
      <figcaption>Một bó hoa, một lần được nhớ đến</figcaption>
    </figure>
  </section>
<div class="lab-columns"><section class="lab-panel"><div class="panel-title"><h2>Thiệp gửi kèm hoa</h2><span class="pill <?= LAB_VERSION==='v1'?'bad':'good' ?>"><?= LAB_VERSION==='v1'?'RAW HTML OUTPUT':'ESCAPED HTML OUTPUT' ?></span></div>
<div class="product-preview"><img src="<?= h(photo('mot-bo-hoa.png')) ?>" data-photo="mot-bo-hoa.png" alt="Một bó hoa màu đỏ"><div><small>CÁ'S HOA / PERSONAL MESSAGE</small><strong>Một bó hoa, một lần được nhớ đến</strong><span class="product-price">390.000 đ</span></div></div>
<form method="get" action="<?= h(page_url('xss.php')) ?>"><label for="q">Lời nhắn gửi cùng bó hoa</label><p class="field-help" id="q-help">Thử nhập lời chúc thông thường, sau đó dùng payload ở khu vực hướng dẫn.</p><div class="form-row"><input id="q" name="q" maxlength="400" aria-describedby="q-help" value="<?= h($q) ?>" placeholder="Ví dụ: Chúc mừng sinh nhật"><button class="btn primary" type="submit">Xem trước</button></div></form>
<div class="result"><small>PREVIEW / KẾT QUẢ HIỂN THỊ</small><p id="xss-status" class="status" role="status" aria-live="polite">Chưa có JavaScript được thực thi</p><div class="message-preview"><?php
if(LAB_VERSION==='v1') {
    // INTENTIONAL VULNERABILITY: untrusted input is written into HTML text context.
    echo $q === '' ? 'Lời nhắn của bạn sẽ xuất hiện tại đây.' : $q;
} else {
    echo $q === '' ? 'Lời nhắn của bạn sẽ xuất hiện tại đây.' : h($q);
}
?></div></div>
</section>
<aside class="lab-aside" aria-label="Giải thích và thao tác kiểm thử">
  <h3>Quy trình kiểm tra</h3>
  <ol class="explain-steps">
    <li><b>Nhập một lời nhắn</b>Gửi nội dung qua tham số <code>q</code> của URL.</li>
    <li><b>Quan sát cách hiển thị</b>V1 đưa nội dung vào HTML trực tiếp; V2 sử dụng <code>htmlspecialchars()</code>.</li>
    <li><b>Đổi sang phiên bản còn lại</b>Dùng nút V1 / V2 trên thanh điều hướng. Nội dung tìm kiếm được giữ nguyên.</li>
  </ol>
  <div class="payload-panel">
    <span class="payload-title">Payload vô hại để kiểm tra XSS</span>
    <code class="payload-code">&lt;img src=x onerror="document.getElementById('xss-status').textContent='XSS_EXECUTED'"&gt;</code>
    <button class="btn subtle copy-payload" type="button" data-payload="&lt;img src=x onerror=&quot;document.getElementById('xss-status').textContent='XSS_EXECUTED'&quot;&gt;">Điền payload vào ô nhập</button>
    <p>Chỉ đổi dòng trạng thái thành <code>XSS_EXECUTED</code>; không đọc dữ liệu cá nhân.</p>
  </div>
</aside></div></main>
<?php layout_bottom(); ?>