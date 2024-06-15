<?php
require '../vendor/autoload.php';
include '../koneksi.php';  

use Firebase\JWT\JWT;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$username = isset($_GET['username']) ? $_GET['username'] : '';
$password = isset($_GET['password']) ? $_GET['password'] : '';

if (empty($username) || empty($password)) {
  echo json_encode([
    'message' => 'Username and password are required.'
  ]);
  exit();
}

try {
  $stmt = $conn->prepare("SELECT id, username, password, role, email FROM users WHERE username = :username AND password = :password");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':password', $password);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    $secretKey = 'your_secret_key';

    $payload = [
      'iss' => '',  
      'aud' => '',  
      'iat' => time(),               
      'nbf' => time(),                
      'exp' => time() + 3600,         
      'data' => [
        'id' => $user['id'],
        'username' => $user['username'],
        'role' => $user['role'],
        'email' => $user['email']
      ]
    ];

    $jwt = JWT::encode($payload, $secretKey, 'HS256');

    echo json_encode([
      'message' => 'Successful login.',
      'ID' => $user['id'],
      'Username' => $user['username'],
      'Role' => $user['role'],
      'Email' => $user['email'],
      'Token' => $jwt
    ]);
  } else {
    echo json_encode([
      'message' => 'Login failed. Username or password incorrect.'
    ]);
  }
} catch (PDOException $e) {
  echo json_encode([
    'message' => 'Connection failed: ' . $e->getMessage()
  ]);
} catch (Exception $e) {
  echo json_encode([
    'message' => 'Error: ' . $e->getMessage()
  ]);
}
?>
