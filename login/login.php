<?php
require '../vendor/autoload.php';
include '../koneksi.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

function getBearerToken()
{
  $headers = getallheaders();
  if (isset($headers['Authorization'])) {
    $matches = [];
    if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
      return $matches[1];
    }
  }
  return null;
}

$token = getBearerToken();
if (!$token) {
  $token = isset($_GET['token']) ? $_GET['token'] : (isset($_POST['token']) ? $_POST['token'] : '');
}

$secretKey = 'your_secret_key';

if ($token) {
  try {
    $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
    echo json_encode([
      'message' => 'Token decoded successfully.',
      'data' => (array) $decoded->data
    ]);
  } catch (Exception $e) {
    echo json_encode([
      'message' => 'Invalid token: ' . $e->getMessage()
    ]);
  }
} else {
  echo json_encode([
    'message' => 'Authorization token not found.'
  ]);
}
?>
