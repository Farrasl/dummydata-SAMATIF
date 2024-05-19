<?php
include '../koneksi.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data dari permintaan POST
    $surah = isset($_POST['surah']) ? $_POST['surah'] : '';

    // Validasi data
    if (empty($surah)) {
        $response = array('status' => 'error', 'message' => 'Nama surah harus diisi.');
    } else {
        try {
            // Query untuk menambahkan surah
            $query = "INSERT INTO surah (nama) VALUES (:surah)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':surah', $surah, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $response = array('status' => 'success', 'message' => 'Data surah berhasil ditambahkan.');
            } else {
                $response = array('status' => 'error', 'message' => 'Gagal menambahkan data surah.');
            }
        } catch (PDOException $e) {
            $response = array('status' => 'error', 'message' => 'Query gagal: ' . $e->getMessage());
        }
    }
} else {
    $response = array('status' => 'error', 'message' => 'Hanya metode POST yang diizinkan.');
}

// Mengirim response JSON
echo json_encode($response, JSON_PRETTY_PRINT);

$conn = null;
?>
