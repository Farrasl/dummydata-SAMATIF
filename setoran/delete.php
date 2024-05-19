<?php
include '../koneksi.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_setoran = isset($_POST['id_setoran']) ? $_POST['id_setoran'] : '';

    if (empty($id_setoran)) {
        $response = array('status' => 'error', 'message' => 'ID setoran tidak boleh kosong.');
    } else {
        try {
            $query = "DELETE FROM setoran WHERE id_setoran = :id_setoran";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id_setoran', $id_setoran, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $response = array('status' => 'success', 'message' => 'Setoran berhasil dihapus.');
            } else {
                $response = array('status' => 'error', 'message' => 'Gagal menghapus setoran.');
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
