<?php
include '../koneksi.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data dari permintaan POST
    $nim = isset($_POST['nim']) ? $_POST['nim'] : '';
    $nip_pa_baru = isset($_POST['nip_pa_baru']) ? $_POST['nip_pa_baru'] : '';
    $status_baru = isset($_POST['status_baru']) ? $_POST['status_baru'] : 'Aktif';

    // Validasi data
    if (empty($nim) || empty($nip_pa_baru)) {
        $response = array('status' => 'error', 'message' => 'NIM dan NIP PA baru harus diisi.');
    } else {
        try {
            // Mulai transaksi
            $conn->beginTransaction();

            // Query untuk mengubah status PA sebelumnya menjadi Tidak Aktif
            $query_update_pa_sebelumnya = "UPDATE riwayat_pa SET status = 'Tidak Aktif' WHERE NIM = :nim AND status = 'Aktif'";
            $stmt_update_pa_sebelumnya = $conn->prepare($query_update_pa_sebelumnya);
            $stmt_update_pa_sebelumnya->bindParam(':nim', $nim, PDO::PARAM_STR);
            $stmt_update_pa_sebelumnya->execute();

            // Query untuk menambahkan PA baru dengan status baru
            $query_update_pa_baru = "INSERT INTO riwayat_pa (NIM, NIP, status) VALUES (:nim, :nip_pa_baru, :status_baru)";
            $stmt_update_pa_baru = $conn->prepare($query_update_pa_baru);
            $stmt_update_pa_baru->bindParam(':nim', $nim, PDO::PARAM_STR);
            $stmt_update_pa_baru->bindParam(':nip_pa_baru', $nip_pa_baru, PDO::PARAM_STR);
            $stmt_update_pa_baru->bindParam(':status_baru', $status_baru, PDO::PARAM_STR);

            if ($stmt_update_pa_baru->execute()) {
                // Commit transaksi
                $conn->commit();
                $response = array('status' => 'success', 'message' => 'Dosen PA berhasil diperbarui dan PA sebelumnya dinonaktifkan.');
            } else {
                // Rollback transaksi jika gagal
                $conn->rollBack();
                $response = array('status' => 'error', 'message' => 'Gagal memperbarui dosen PA.');
            }
        } catch (PDOException $e) {
            // Rollback transaksi jika terjadi error
            $conn->rollBack();
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
