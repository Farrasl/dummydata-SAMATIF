<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include 'vendor/autoload.php';

$user = new \App\app\User();
//index.php?app=user&action=login
//index.php?app=user&action=get
$action = $_GET['action'];
$user->$action();
?>
