<?php
require '../vendor/autoload.php';
include '../koneksi.php';  

use Firebase\JWT\JWT;

header('Content-Type: application/json');

$nim = isset($_GET['nim']) ? $_GET['nim'] : '';

if (empty($nim)) {
  echo json_encode([
    'message' => 'NIM is required.'
  ]);
  exit();
}

try {
  $stmt = $conn->prepare("SELECT nim, nama, semester FROM mahasiswa WHERE nim = :nim");
  $stmt->bindParam(':nim', $nim);
  $stmt->execute();
  $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($mahasiswa) {
    $secretKey = 'your_secret_key';

    $payload = [
      'iss' => '',  
      'aud' => '',  
      'iat' => time(),               
      'nbf' => time(),                
      'exp' => time() + 3600,         
      'data' => [
        'nim' => $mahasiswa['nim'],
        'nama' => $mahasiswa['nama'],
        'semester' => $mahasiswa['semester']
      ]
    ];

    $jwt = JWT::encode($payload, $secretKey, 'HS256');

    echo json_encode([
      'Message' => 'Successful login.',
      'NIM' => $mahasiswa['nim'],
      'Nama' => $mahasiswa['nama'],
      'Semester' => $mahasiswa['semester'],
      'Token' => $jwt
    ]);
  } else {
    echo json_encode([
      'message' => 'Login failed. NIM not found.'
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
