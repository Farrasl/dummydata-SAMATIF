<?php
require '../vendor/autoload.php';
include '../koneksi.php';  

use Firebase\JWT\JWT;

header('Content-Type: application/json');

$nip = isset($_GET['nip']) ? $_GET['nip'] : '';

if (empty($nip)) {
  echo json_encode([
    'message' => 'nip is required.'
  ]);
  exit();
}

try {
  $stmt = $conn->prepare("SELECT nip, nama FROM dosen WHERE nip = :nip");
  $stmt->bindParam(':nip', $nip);
  $stmt->execute();
  $dosen = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($dosen) {
    $secretKey = 'your_secret_key';

    $payload = [
      'iss' => '',  
      'aud' => '',  
      'iat' => time(),                
      'nbf' => time(),                
      'exp' => time() + 3600,         
      'data' => [
        'nip' => $dosen['nip'],
        'nama' => $dosen['nama'],
      ]
    ];

    $jwt = JWT::encode($payload, $secretKey, 'HS256');

    echo json_encode([
      'Message' => 'Successful login.',
      'NIP' => $dosen['nip'],
      'Nama' => $dosen['nama'],
      'Token' => $jwt
    ]);
  } else {
    echo json_encode([
      'message' => 'Login failed. nip not found.'
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
