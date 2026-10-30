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
<main class="container lab-page"><div class="crumb"><a href="<?= h(page_url()) ?>">Cửa hàng hoa</a> / LAB 03</div><div class="lab-heading"><p class="eyebrow">LAB 03 / SESSION → STATE CHANGE</p><h1>Tài khoản của tôi <em>(CSRF)</em></h1><p>Thay đổi tên người nhận hoa. So sánh request GET có thể bị giả mạo với POST kèm CSRF token.</p></div>
<div class="lab-columns"><section class="lab-panel"><div class="panel-title"><h2>Thông tin khách hàng</h2><span class="pill <?= LAB_VERSION==='v1'?'bad':'good' ?>"><?= LAB_VERSION==='v1'?'UNPROTECTED GET':'POST + CSRF TOKEN' ?></span></div>
<?php if($error): ?><div class="notice error"><?= h($error) ?></div><?php endif; ?>
<?php if($user): ?>
<div class="account-card"><span class="avatar">C</span><div><small>KHÁCH HÀNG DEMO</small><h3><?= h($user['display_name']) ?></h3><p><?= h($user['email']) ?></p></div></div>
<form class="vertical-form" method="<?= LAB_VERSION==='v1'?'get':'post' ?>" action="<?= h(page_url('update-profile.php')) ?>">
<label for="display_name">Tên người nhận hoa</label><input id="display_name" name="display_name" maxlength="120" required value="<?= h($user['display_name']) ?>">
<?php if(LAB_VERSION==='v2'): ?><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><?php endif; ?>
<button class="btn primary" type="submit">Lưu thay đổi</button></form>
<p class="muted">Sau khi cập nhật, tải lại trang để xác nhận tên đã được lưu trong MySQL.</p>
<?php endif; ?>
<p><a class="underlined" href="<?= h(page_url('logout.php')) ?>">Đăng xuất tài khoản demo</a></p>
</section><aside class="lab-aside"><h3>Kịch bản giả mạo yêu cầu</h3><p>Đang đăng nhập tại <code>localhost</code>, mở trang attacker từ <code>127.0.0.1</code> và bấm nút gửi request.</p><p>V1: GET thay đổi tên mà không có token. V2: GET bị từ chối với HTTP 405; POST phải có token đúng.</p><div class="notice"><b>Trang mô phỏng kẻ tấn công</b><a class="underlined" href="<?= h(base_url('attacker/csrf.html')) ?>" target="_blank" rel="noopener">Mở attacker/csrf.html</a><p>Chỉ chạy thử trên website và tài khoản demo do nhóm quản lý. Dùng liên kết <code>http://127.0.0.1/...</code> để mở trang attacker.</p></div></aside></div></main>
<?php layout_bottom(); ?>