<?php
include '../koneksi.php';

header('Access-Control-Allow-Origin: *'); // Izinkan akses dari semua asal
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

$response = array();

try {
    $query = "SELECT id_surah AS id, nama AS name FROM surah";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        $response = $result;
    } else {
        $response = array('status' => 'error', 'message' => 'Tidak ada data surah ditemukan.');
    }
} catch (PDOException $e) {
    $response = array('status' => 'error', 'message' => 'Query gagal: ' . $e->getMessage());
}

echo json_encode($response, JSON_PRETTY_PRINT);

$conn = null;
?>
