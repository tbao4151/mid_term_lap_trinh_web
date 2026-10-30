<?php
require_once __DIR__ . '/../lib/layout.php';
$error='';
if($_SERVER['REQUEST_METHOD']==='POST') {
    $username=(string)($_POST['username'] ?? ''); $password=(string)($_POST['password'] ?? '');
    try{
        $stmt=db()->prepare('SELECT id,username,password_hash,display_name FROM users WHERE username=:username LIMIT 1');
        $stmt->execute(['username'=>$username.'_'.LAB_VERSION]); $user=$stmt->fetch();
        if($username==='student' && $user && password_verify($password,$user['password_hash'])) {
            session_regenerate_id(true); $_SESSION['uid']=(int)$user['id']; $_SESSION['display_name']=$user['display_name'];
            header('Location: '.page_url('profile.php')); exit;
        }
        $error='Thông tin đăng nhập demo không chính xác.';
    } catch(Throwable $e){$error='Chưa kết nối MySQL. Hãy import database/setup.sql.';}
}
layout_top('Đăng nhập tài khoản demo', 'csrf');
?>
<main class="container narrow lab-page"><div class="lab-heading center"><p class="eyebrow">CÁ'S HOA / DEMO ACCOUNT</p><h1>Đăng nhập tài khoản</h1><p>Đăng nhập vào dữ liệu giả lập để thực hành lỗ hổng CSRF.</p></div><section class="lab-panel">
<?php if($error): ?><div class="notice error"><?= h($error) ?></div><?php endif; ?>
<form method="post" action="<?= h(page_url('login.php')) ?>" class="vertical-form"><label for="username">Tên tài khoản</label><input id="username" name="username" required autocomplete="username" value="student"><label for="password">Mật khẩu</label><input id="password" name="password" type="password" required autocomplete="current-password" value="demo12345"><button class="btn primary" type="submit">Đăng nhập demo</button></form>
<div class="notice"><strong>Chỉ sử dụng tài khoản giả:</strong> student / demo12345. Hai phiên bản có người dùng độc lập để kiểm thử không chồng trạng thái.</div></section></main>
<?php layout_bottom(); ?>