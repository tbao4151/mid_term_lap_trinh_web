<?php
require_once __DIR__ . '/../lib/bootstrap.php';
must_login();
if(LAB_VERSION==='v1') {
    // INTENTIONAL CSRF VULNERABILITY: changes state via GET, without any CSRF token.
    if($_SERVER['REQUEST_METHOD']!=='GET'){http_response_code(405);exit('GET only in vulnerable demo.');}
    $name=trim((string)($_GET['display_name'] ?? ''));
} else {
    if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);header('Allow: POST');exit('State-changing requests must use POST.');}
    if(!validate_csrf((string)($_POST['csrf_token'] ?? ''))){http_response_code(403);exit('Invalid or missing CSRF token.');}
    $name=trim((string)($_POST['display_name'] ?? ''));
}
if($name==='' || strlen($name)>120){http_response_code(422);exit('Invalid display name.');}
try {
    $stmt=db()->prepare('UPDATE users SET display_name=:name WHERE id=:id');
    $stmt->execute(['name'=>$name,'id'=>(int)$_SESSION['uid']]);
    $_SESSION['display_name']=$name;
    header('Location: '.page_url('profile.php'));exit;
} catch(Throwable $e){http_response_code(500);exit('Unable to update demo profile.');}
