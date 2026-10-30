<?php
require_once __DIR__ . '/../lib/bootstrap.php';
$_SESSION=[];
session_regenerate_id(true);
header('Location: '.page_url('login.php'));exit;
