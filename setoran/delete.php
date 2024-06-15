<?php
include '../koneksi.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$response = array();

if (isset($_POST['id_setoran'])) {
    $id_setoran = $_POST['id_setoran'];

    try {
        $sql_delete = "DELETE FROM setoran WHERE id_setoran = :id_setoran";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bindParam(':id_setoran', $id_setoran, PDO::PARAM_INT);

        if ($stmt_delete->execute()) {
            $response = array('status' => 'success', 'message' => 'Setoran berhasil dihapus.');
        } else {
            $response = array('status' => 'error', 'message' => 'Gagal menghapus setoran.');
        }
    } catch (PDOException $e) {
        $response = array('status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage());
    }
} else {
    $response = array('status' => 'error', 'message' => 'ID setoran tidak ditemukan.');
}

echo json_encode($response, JSON_PRETTY_PRINT);

$conn = null;
?>
