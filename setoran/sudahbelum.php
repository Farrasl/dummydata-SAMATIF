<?php
include '../koneksi.php';

header('Content-Type: application/json');

$response = array();

if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];

    if (empty($nim)) {
        $response = array('status' => 'error', 'message' => 'NIM tidak boleh kosong.');
    } else {
        try {
            $query_mahasiswa = "SELECT Nama FROM mahasiswa WHERE NIM = :nim";
            $stmt_mahasiswa = $conn->prepare($query_mahasiswa);
            $stmt_mahasiswa->bindParam(':nim', $nim, PDO::PARAM_STR);
            $stmt_mahasiswa->execute();
            $result_mahasiswa = $stmt_mahasiswa->fetch(PDO::FETCH_ASSOC);

            if ($result_mahasiswa) {
                $nama_mahasiswa = $result_mahasiswa['Nama'];

                $query_setoran = "SELECT s.nama AS surah
                                FROM surah s
                                LEFT JOIN setoran i ON s.id_surah = i.id_surah
                                WHERE i.NIM = :nim
                                GROUP BY s.nama";
                $stmt_setoran = $conn->prepare($query_setoran);
                $stmt_setoran->bindParam(':nim', $nim, PDO::PARAM_STR);
                $stmt_setoran->execute();
                $result_setoran = $stmt_setoran->fetchAll(PDO::FETCH_ASSOC);

                $query_surah_belum_setor = "SELECT nama AS surah FROM surah WHERE id_surah NOT IN 
                                            (SELECT id_surah FROM setoran WHERE NIM = :nim)";
                $stmt_surah_belum_setor = $conn->prepare($query_surah_belum_setor);
                $stmt_surah_belum_setor->bindParam(':nim', $nim, PDO::PARAM_STR);
                $stmt_surah_belum_setor->execute();
                $result_surah_belum_setor = $stmt_surah_belum_setor->fetchAll(PDO::FETCH_ASSOC);

                $response = array(
                    'status' => 'success',
                    'Nama' => $nama_mahasiswa,
                    'NIM' => $nim,
                    'surah_sudah_setor' => $result_setoran,
                    'surah_belum_setor' => $result_surah_belum_setor
                );
            } else {
                $response = array('status' => 'error', 'message' => 'Mahasiswa dengan NIM tersebut tidak ditemukan.');
            }
        } catch (PDOException $e) {
            $response = array('status' => 'error', 'message' => 'Query gagal: ' . $e->getMessage());
        }
    }
} else {
    $response = array('status' => 'error', 'message' => 'NIM tidak boleh kosong.');
}

// Mengirim response JSON
echo json_encode($response, JSON_PRETTY_PRINT);

$conn = null;
?>
