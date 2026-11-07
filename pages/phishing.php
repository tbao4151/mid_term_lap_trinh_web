<?php
require_once __DIR__ . '/../lib/layout.php';
$isProtected = LAB_VERSION === 'v2';
layout_top('Phishing – xác minh thông báo giao hoa', 'phishing');
?>
<!-- Page-scoped stylesheet; no change to the shared layout is required. -->
<link rel="stylesheet" href="<?= h(base_url('assets/css/phishing.css')) ?>">
<main class="container lab-page phishing-page" id="phishing-simulator" data-version="<?= h(LAB_VERSION) ?>" aria-labelledby="phishing-title">
  <div id="main-content" tabindex="-1">
    <div class="crumb"><a href="<?= h(page_url()) ?>">Cửa hàng hoa</a> / Thực hành Phishing</div>
    <div class="phishing-safety"><strong>MÔ PHỎNG HỌC TẬP</strong><span>Dữ liệu giả · Không đăng nhập · Không gửi dữ liệu ra ngoài</span></div>
    <header class="phishing-heading">
      <p class="eyebrow">SECURITY STUDIO / PHISHING</p>
      <h1 id="phishing-title">Một thông báo giao hoa.<br><em>Có nên tin ngay?</em></h1>
      <p class="lead">Quan sát người gửi, nội dung và địa chỉ được nhắc đến trước khi quyết định cách xác minh đơn hàng.</p>
      <div class="hero-context"><span><?= $isProtected ? 'V2 · Có hướng dẫn nhận diện' : 'V1 · Tự quan sát tình huống' ?></span><span>Tình huống giả danh giao hoa</span></div>
    </header>
    <div class="phishing-grid">
      <section class="lab-panel phishing-card" aria-labelledby="scenario-title">
        <div class="phishing-panel-heading"><h2 id="scenario-title">Hộp thư tình huống</h2><span class="phishing-counter">01 / 01</span></div>
        <button class="phishing-scenario" type="button" data-phishing-scenario="delivery" aria-pressed="true" aria-controls="phishing-message"><span aria-hidden="true">✉</span> Thông báo giao hoa <span class="phishing-selected">Đang chọn</span></button>
        <article id="phishing-message" class="phishing-message" aria-labelledby="message-title">
          <div class="phishing-sender"><span class="phishing-avatar" aria-hidden="true">GH</span><div><strong>Giao Hoa Nhanh — đơn vị giả lập</strong><span>thongbao@giao-hoa.example.test</span></div></div>
          <div class="phishing-message-body">
            <p class="eyebrow">TIN NHẮN GIẢ LẬP / 09:41</p>
            <h3 id="message-title">Đơn hoa GH-DEMO-204 cần được xác minh</h3>
            <p>Chào bạn, đơn hoa của bạn đang tạm giữ do thiếu xác nhận địa chỉ. Hãy xác minh trong <strong>15 phút</strong> để tránh hủy giao hàng.</p>
            <p>Vui lòng truy cập địa chỉ bên dưới để cập nhật thông tin và thanh toán lại phí giao hoa <strong>12.000 đ</strong>.</p>
            <div class="phishing-address"><span>Địa chỉ trong tin nhắn — chỉ là văn bản</span><code>https://xac-minh-giao-hoa.example.test/don-hang/GH-DEMO-204</code></div>
            <span class="phishing-mock-cta">Xác minh đơn hàng <small>(minh họa, không thể bấm)</small></span>
          </div>
        </article>
        <div class="phishing-inspect"><button class="btn primary" type="button" data-phishing-action="inspect" aria-controls="phishing-feedback" aria-describedby="inspect-help">Kiểm tra thông báo</button><p id="inspect-help">Thao tác kiểm tra tương tác chưa được bật trong bản xem trước.</p></div>
      </section>
      <aside class="lab-aside phishing-review" aria-labelledby="review-title">
        <p class="eyebrow"><?= $isProtected ? 'V2 / NHẬN DIỆN & XÁC MINH' : 'V1 / QUAN SÁT & SUY NGẪM' ?></p>
        <h2 id="review-title"><?= $isProtected ? 'Chậm một nhịp, kiểm tra kỹ hơn.' : 'Bạn sẽ xử lý thế nào?' ?></h2>
        <div id="phishing-feedback" aria-live="polite" aria-atomic="true">
          <?php if ($isProtected): ?>
          <section aria-labelledby="signals-title"><h3 id="signals-title">Dấu hiệu cần kiểm tra</h3>
            <ul class="phishing-signals"><li><strong>Tên người gửi không chứng minh danh tính.</strong> Nhãn “Giao Hoa Nhanh” có thể do người gửi tự đặt.</li><li><strong>Địa chỉ xác minh khác địa chỉ người gửi.</strong> Không tin một tên miền chỉ vì có các từ “giao hoa”.</li><li><strong>Thúc ép thời gian và yêu cầu trả thêm phí.</strong> Mốc 15 phút tạo áp lực phải hành động ngay.</li></ul>
          </section>
          <section class="phishing-official" aria-labelledby="verify-title"><h3 id="verify-title">Xác minh qua kênh chính thức</h3><ol><li>Dừng lại; không mở địa chỉ trong tin nhắn.</li><li>Tự mở ứng dụng cửa hàng hoặc dùng thông tin liên hệ trên biên nhận đã có từ trước.</li><li>Đối chiếu mã đơn, tình trạng giao hàng và phí với cửa hàng trước khi làm tiếp.</li></ol><p>Trong bài học, <code>cua-hang.example.test</code> là tên miền chính thức <strong>giả định</strong>; không phải website để truy cập.</p></section>
          <?php else: ?>
          <p>Đọc tình huống bên cạnh và tự quyết định bước tiếp theo. Phiên bản này chưa hiển thị phân tích tự động.</p>
          <p class="phishing-reflection">Bạn sẽ xác nhận thông tin bằng cách nào trước khi phản hồi?</p>
          <?php endif; ?>
        </div>
      </aside>
    </div>
    <p class="phishing-footnote">MÔ PHỎNG HỌC TẬP — Tất cả tên, mã đơn và tên miền <code>.example.test</code> đều giả lập. Không nhập hoặc cung cấp mật khẩu, OTP hay thông tin thanh toán.</p>
  </div>
</main>
<?php layout_bottom(); ?>
