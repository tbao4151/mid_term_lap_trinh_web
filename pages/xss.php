<?php
require_once __DIR__ . '/../lib/layout.php';
$q=isset($_GET['q']) && is_string($_GET['q']) ? substr($_GET['q'],0,400) : '';
layout_top('XSS – tìm lời nhắn', 'xss');
?>
<main class="container lab-page"><div class="crumb"><a href="<?= h(page_url()) ?>">Cửa hàng hoa</a> / LAB 01</div><div class="lab-heading"><p class="eyebrow">LAB 01 / INPUT → HTML</p><h1>Tìm lời nhắn cho bó hoa <em>(Reflected XSS)</em></h1><p>Khách hàng nhập lời nhắn khi đặt hoa. Thử cùng một input ở V1 và V2 để quan sát cách trình duyệt xử lý HTML.</p></div>
<div class="lab-columns"><section class="lab-panel"><div class="panel-title"><h2>Thiệp gửi kèm hoa</h2><span class="pill <?= LAB_VERSION==='v1'?'bad':'good' ?>"><?= LAB_VERSION==='v1'?'RAW HTML OUTPUT':'ESCAPED HTML OUTPUT' ?></span></div>
<div class="flow-card"><img src="<?= h(photo('mot-bo-hoa.png')) ?>" data-photo="mot-bo-hoa.png" alt="Một bó hoa màu đỏ"><div><small>CÁ'S HOA / PERSONAL MESSAGE</small><strong>Một bó hoa, một lần được nhớ đến</strong><span>390.000 đ</span></div></div>
<form method="get" action="<?= h(page_url('xss.php')) ?>"><label for="q">Nhập lời nhắn hoặc nội dung cần tìm</label><div class="form-row"><input id="q" name="q" maxlength="400" value="<?= h($q) ?>" placeholder="Ví dụ: Chúc mừng sinh nhật"><button class="btn primary" type="submit">Xem trước</button></div></form>
<div class="result"><small>PREVIEW / KẾT QUẢ HIỂN THỊ</small><p id="xss-status" class="status">Chưa có JavaScript được thực thi</p><div class="message-preview"><?php
if(LAB_VERSION==='v1') {
    // INTENTIONAL VULNERABILITY: untrusted input is written into HTML text context.
    echo $q === '' ? 'Lời nhắn của bạn sẽ xuất hiện tại đây.' : $q;
} else {
    echo $q === '' ? 'Lời nhắn của bạn sẽ xuất hiện tại đây.' : h($q);
}
?></div></div>
</section><aside class="lab-aside"><h3>Quan sát sự khác biệt</h3><p>V1: dữ liệu từ <code>$_GET['q']</code> được xuất thẳng ra HTML, có thể biến thành phần tử và sự kiện JavaScript.</p><p>V2: <code>htmlspecialchars()</code> biến ký tự HTML thành văn bản. CSP cũng giúp hạn chế thực thi script.</p><div class="notice"><b>Payload thử nghiệm vô hại</b><code class="break">&lt;img src=x onerror="document.getElementById('xss-status').textContent='XSS_EXECUTED'"&gt;</code><button class="btn subtle copy-payload" type="button" data-payload="&lt;img src=x onerror=&quot;document.getElementById('xss-status').textContent='XSS_EXECUTED'&quot;&gt;">Sao chép payload</button><p>Chỉ thay đổi nhãn trạng thái của trang demo, không đọc cookie hay gửi dữ liệu.</p></div></aside></div></main>
<?php layout_bottom(); ?>