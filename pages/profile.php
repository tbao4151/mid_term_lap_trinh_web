<?php
require_once __DIR__ . '/../lib/layout.php';
must_login();
$error=''; $user=null;
try {
    $stmt=db()->prepare('SELECT id,username,display_name,email FROM users WHERE id=:id');
    $stmt->execute(['id'=>(int)$_SESSION['uid']]); $user=$stmt->fetch();
    if(!$user){$error='Không tìm thấy tài khoản demo.';}
} catch(Throwable $e) {$error='Kết nối MySQL không khả dụng.';}
layout_top('Tài khoản khách hàng', 'csrf');
?>
<main class="container lab-page" id="main-content">
  <div class="crumb"><a href="<?= h(page_url()) ?>">Cửa hàng hoa</a> / Bài thực hành 03</div>
  <section class="lab-heading" aria-labelledby="lab-title">
    <div><p class="eyebrow">THỰC HÀNH 03 / CROSS-SITE REQUEST FORGERY</p>
    <h1 id="lab-title">Thông tin người nhận, <em>chỉ bạn được thay đổi.</em></h1>
    <p class="lead">Thay đổi tên người nhận trong tài khoản thử nghiệm, sau đó kiểm tra xem website có ngăn được yêu cầu giả mạo hay không.</p>
    <div class="hero-context"><span>Tài khoản demo</span><span>PHP session</span><span>CSRF token</span></div>
    </div>
    <figure class="lab-visual"><img src="<?= h(photo('cam-tu-cau.png')) ?>" alt="Hoa cẩm tú cầu tươi của CÁ'S HOA" width="420" height="300"><figcaption>Thông tin người nhận hoa</figcaption></figure>
  </section>
<div class="lab-columns"><section class="lab-panel"><div class="panel-title"><h2>Thông tin khách hàng</h2><span class="pill <?= LAB_VERSION==='v1'?'bad':'good' ?>"><?= LAB_VERSION==='v1'?'UNPROTECTED GET':'POST + CSRF TOKEN' ?></span></div>
<?php if($error): ?><div class="notice error"><?= h($error) ?></div><?php endif; ?>
<?php if(isset($_GET['reset']) && $_GET['reset'] === '1' && $user): ?><div class="notice" role="status">Đã khôi phục tên tài khoản demo về “Khách hàng Demo”. Bạn có thể quay lại bài thử CSRF từ đầu.</div><?php endif; ?>
<?php if($user): ?>
<div class="account-card"><span class="avatar">C</span><div><small>KHÁCH HÀNG DEMO</small><h3><?= h($user['display_name']) ?></h3><p><?= h($user['email']) ?></p></div></div>
<form class="vertical-form" method="<?= LAB_VERSION==='v1'?'get':'post' ?>" action="<?= h(page_url('update-profile.php')) ?>">
<label for="display_name">Tên người nhận hoa</label><input id="display_name" name="display_name" maxlength="120" required value="<?= h($user['display_name']) ?>">
<?php if(LAB_VERSION==='v2'): ?><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><?php endif; ?>
<button class="btn primary" type="submit">Lưu thay đổi</button></form>
<p class="muted">Sau khi cập nhật, tải lại trang để xác nhận tên đã được lưu trong MySQL.</p>
<form class="reset-demo-form" method="post" action="<?= h(page_url('reset-demo.php')) ?>">
  <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
  <button class="btn subtle" type="submit">Đặt lại dữ liệu demo</button>
</form>
<p class="muted">Chỉ khôi phục tên tài khoản <?= h(strtoupper(LAB_VERSION)) ?> về “Khách hàng Demo”, không xóa sản phẩm hay database. Đăng xuất không tự đặt lại thông tin đã lưu trong MySQL.</p>
<?php endif; ?>
<p><a class="underlined" href="<?= h(page_url('logout.php')) ?>">Đăng xuất tài khoản demo</a></p>
</section><aside class="lab-aside" aria-label="Hướng dẫn kiểm tra CSRF">
<h3>Quy trình thực nghiệm</h3>
<ol class="explain-steps">
<li><b>Đăng nhập tài khoản thử nghiệm</b>Đảm bảo phiên làm việc trên <code>localhost</code> vẫn đang hoạt động.</li>
<li><b>Mở trang attacker</b>Truy cập cùng ứng dụng bằng <code>127.0.0.1</code>, rồi gửi yêu cầu GET giả mạo.</li>
<li><b>Đối chiếu V1 và V2</b>V1 cho phép GET đổi tên; V2 chỉ chấp nhận POST kèm token hợp lệ.</li>
</ol>
<div class="payload-panel"><span class="payload-title">Bài thử nghiệm request giả mạo</span>
<p><a class="btn subtle" href="<?= h(base_url('attacker/csrf.html')) ?>" target="_blank" rel="noopener noreferrer">Mở trang giả lập attacker</a></p>
<p>Chỉ dùng trên localhost với tài khoản và dữ liệu demo của dự án này.</p>
</div>
</aside></div></main>
<?php layout_bottom(); ?>