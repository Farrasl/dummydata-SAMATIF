<?php
include '../koneksi.php';

header('Content-Type: application/json');

if(isset($_GET['nim'])) {
    $nim = $_GET['nim'];

    $sql_setoran = "SELECT m.Nama AS Nama_Mahasiswa, s.nama AS nama_surah, i.tanggal, i.kelancaran, i.tajwid, i.makhrajul_huruf
                    FROM setoran rs
                    JOIN setoran i ON rs.id_setoran = i.id_setoran
                    JOIN riwayat_pa pa ON rs.NIM = pa.NIM
                    JOIN surah s ON i.id_surah = s.id_surah
                    JOIN mahasiswa m ON rs.NIM = m.NIM
                    WHERE m.NIM = :nim";

    $stmt_setoran = $conn->prepare($sql_setoran);
    $stmt_setoran->bindParam(':nim', $nim);
    $stmt_setoran->execute();

    $setoran_list = array();

    if ($stmt_setoran->rowCount() > 0) {
        while ($row = $stmt_setoran->fetch(PDO::FETCH_ASSOC)) {
            $setoran_list[] = $row;
        }
    }

    $response = array(
        'NIM' => $nim,
        'setoran' => $setoran_list
    );

    echo json_encode($response);
} else {
    $sql_all_setoran = "SELECT m.Nama AS Nama_Mahasiswa, s.nama AS nama_surah, i.tanggal, i.kelancaran, i.tajwid, i.makhrajul_huruf
                        FROM setoran rs
                        JOIN setoran i ON rs.id_setoran = i.id_setoran
                        JOIN riwayat_pa pa ON rs.NIM = pa.NIM
                        JOIN surah s ON i.id_surah = s.id_surah
                        JOIN mahasiswa m ON rs.NIM = m.NIM";

    $stmt_all_setoran = $conn->prepare($sql_all_setoran);
    $stmt_all_setoran->execute();

    $all_setoran_list = array();

    if ($stmt_all_setoran->rowCount() > 0) {
        while ($row = $stmt_all_setoran->fetch(PDO::FETCH_ASSOC)) {
            $all_setoran_list[] = $row;
        }
    }

    echo json_encode($all_setoran_list);
}

$conn = null;
?>
