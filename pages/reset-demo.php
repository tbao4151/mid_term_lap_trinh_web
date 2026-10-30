<?php
declare(strict_types=1);
require_once __DIR__ . '/../lib/bootstrap.php';
must_login();

// Explicit authenticated reset for repeated recordings; the vulnerable CSRF lab is unchanged.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Reset demo requires POST.');
}
if (!validate_csrf((string) ($_POST['csrf_token'] ?? ''))) {
    http_response_code(403);
    exit('Invalid or missing reset token.');
}

try {
    $statement = db()->prepare('UPDATE users SET display_name = :name WHERE id = :id AND username = :username');
    $statement->execute([
        'name' => 'Khách hàng Demo',
        'id' => (int) $_SESSION['uid'],
        'username' => 'student_' . LAB_VERSION,
    ]);
    $_SESSION['display_name'] = 'Khách hàng Demo';
    header('Location: ' . page_url('profile.php?reset=1'), true, 303);
    exit;
} catch (Throwable $exception) {
    http_response_code(500);
    exit('Không thể khôi phục tài khoản demo. Kiểm tra MySQL và thử lại.');
}
