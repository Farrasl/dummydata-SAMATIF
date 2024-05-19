<?php
include '../koneksi.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = isset($_POST['nim']) ? $_POST['nim'] : '';
    $nip = isset($_POST['nip']) ? $_POST['nip'] : '';
    $id_surah = isset($_POST['id_surah']) ? $_POST['id_surah'] : '';
    $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
    $kelancaran = isset($_POST['kelancaran']) ? $_POST['kelancaran'] : '';
    $tajwid = isset($_POST['tajwid']) ? $_POST['tajwid'] : '';
    $makhrajul_huruf = isset($_POST['makhrajul_huruf']) ? $_POST['makhrajul_huruf'] : '';

    if (empty($nim) || empty($nip) || empty($id_surah) || empty($tanggal) || empty($kelancaran) || empty($tajwid) || empty($makhrajul_huruf)) {
        $response = array('status' => 'error', 'message' => 'Semua field harus diisi.');
    } else {
        try {
            $query = "INSERT INTO setoran (NIM, NIP, id_surah, tanggal, kelancaran, tajwid, makhrajul_huruf) 
                      VALUES (:nim, :nip, :id_surah, :tanggal, :kelancaran, :tajwid, :makhrajul_huruf)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nim', $nim, PDO::PARAM_STR);
            $stmt->bindParam(':nip', $nip, PDO::PARAM_STR);
            $stmt->bindParam(':id_surah', $id_surah, PDO::PARAM_INT);
            $stmt->bindParam(':tanggal', $tanggal, PDO::PARAM_STR);
            $stmt->bindParam(':kelancaran', $kelancaran, PDO::PARAM_STR);
            $stmt->bindParam(':tajwid', $tajwid, PDO::PARAM_STR);
            $stmt->bindParam(':makhrajul_huruf', $makhrajul_huruf, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $response = array('status' => 'success', 'message' => 'Data setoran berhasil ditambahkan.');
            } else {
                $response = array('status' => 'error', 'message' => 'Gagal menambahkan data setoran.');
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
