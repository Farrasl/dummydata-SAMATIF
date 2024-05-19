<?php
include '../koneksi.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = isset($_POST['nip']) ? $_POST['nip'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';

    if (empty($nip) || empty($nama)) {
        $response = array('status' => 'error', 'message' => 'NIP dan Nama tidak boleh kosong.');
    } else {
        try {
            $query = "UPDATE dosen SET nama = :nama WHERE nip = :nip";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nama', $nama, PDO::PARAM_STR);
            $stmt->bindParam(':nip', $nip, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $response = array('status' => 'success', 'message' => 'Data dosen berhasil diperbarui.');
            } else {
                $response = array('status' => 'error', 'message' => 'Gagal memperbarui data dosen.');
            }
        } catch (PDOException $e) {
            $response = array('status' => 'error', 'message' => 'Query gagal: ' . $e->getMessage());
        }
    }
} else {
    $response = array('status' => 'error', 'message' => 'Hanya metode POST yang diizinkan.');
}

echo json_encode($response, JSON_PRETTY_PRINT);

$conn = null;
?>
