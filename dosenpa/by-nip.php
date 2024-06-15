<?php
include '../koneksi.php';

header('Content-Type: application/json');

$data_json = array();

function getDosenMahasiswa($conn, $nip = null) {
    if (!$nip) {
        return array('status' => 'error', 'message' => 'Input NIP Terlebih Dahulu');
    }

    $query_dosen = "SELECT nip, nama FROM dosen WHERE nip = :nip";
    
    $data_dosen = array();

    try {
        $stmt_dosen = $conn->prepare($query_dosen);
        
        if ($nip) {
            $stmt_dosen->bindParam(':nip', $nip);
        }
        
        $stmt_dosen->execute();
        $result_dosen = $stmt_dosen->fetchAll(PDO::FETCH_ASSOC);

        if ($result_dosen) {
            foreach ($result_dosen as $dosen) {
                $nip = $dosen['nip'];
                $nama_dosen = $dosen['nama'];

                $query_mahasiswa = "SELECT m.NIM, m.Nama, m.Semester
                                    FROM mahasiswa m
                                    INNER JOIN riwayat_pa r ON m.NIM = r.NIM
                                    INNER JOIN dosen d ON r.NIP = d.nip
                                    WHERE d.nip = :nip";

                $stmt_mahasiswa = $conn->prepare($query_mahasiswa);
                $stmt_mahasiswa->bindParam(':nip', $nip);
                $stmt_mahasiswa->execute();
                $result_mahasiswa = $stmt_mahasiswa->fetchAll(PDO::FETCH_ASSOC);

                $data_dosen[] = array(
                    'Nama' => $nama_dosen,
                    'NIP' => $nip,
                    'Mahasiswa' => $result_mahasiswa
                );
            }
            return array('dosen' => $data_dosen);
        } else {
            return array('status' => 'error', 'message' => 'Tidak ada data dosen yang ditemukan.');
        }
    } catch(PDOException $e) {
        return array('status' => 'error', 'message' => 'Query gagal: ' . $e->getMessage());
    }
}

if (isset($_GET['nip'])) {
    $nip = $_GET['nip'];
    $data_json = getDosenMahasiswa($conn, $nip);
} else {
    $data_json = getDosenMahasiswa($conn);
}

echo json_encode($data_json, JSON_PRETTY_PRINT);

$conn = null;
?>
