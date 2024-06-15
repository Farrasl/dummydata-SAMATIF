<?php
include '../koneksi.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Tambahkan baris ini untuk mengizinkan akses lintas domain

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

                $percentages = array();

                // Hitung persentase untuk setiap langkah keberhasilan
                $langkahs = array(
                    "Kerja Praktek" => range(1, 8),
                    "Seminar Kerja Praktek" => range(9, 16),
                    "Judul Tugas Akhir" => range(17, 22),
                    "Seminar Proposal" => range(23, 34),
                    "Sidang Tugas Akhir" => range(35, 37)
                );

                foreach ($langkahs as $langkah => $range) {
                    $query_count = "SELECT COUNT(*) AS count FROM setoran WHERE NIM = :nim AND id_surah IN (" . implode(",", $range) . ")";
                    $stmt_count = $conn->prepare($query_count);
                    $stmt_count->bindParam(':nim', $nim, PDO::PARAM_STR);
                    $stmt_count->execute();
                    $result_count = $stmt_count->fetch(PDO::FETCH_ASSOC);
                    $percent = ($result_count['count'] / count($range)) * 100;

                    $percentages[] = array('lang' => $langkah, 'percent' => $percent);
                }

                $response = array(
                    'status' => 'success',
                    'Nama' => $nama_mahasiswa,
                    'NIM' => $nim,
                    'percentages' => $percentages
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

echo json_encode($response, JSON_PRETTY_PRINT);

$conn = null;
?>
