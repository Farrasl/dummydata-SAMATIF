<?php
include '../koneksi.php';

header('Content-Type: application/json');

$data_json = array();

function getDosenMahasiswa($conn, $nim = null) {
    if ($nim) {
        // Query untuk mengambil data mahasiswa dan dosen PA berdasarkan NIM
        $query_mahasiswa = "SELECT m.NIM, m.Nama, m.Semester, d.nip, d.nama AS nama_dosen
                            FROM mahasiswa m
                            INNER JOIN riwayat_pa r ON m.NIM = r.NIM
                            INNER JOIN dosen d ON r.NIP = d.nip
                            WHERE m.NIM = :nim";
    } else {
        // Query untuk mengambil semua dosen dan mahasiswa yang mereka bimbing
        $query_dosen = "SELECT nip, nama FROM dosen";
    }

    $data_mahasiswa = array();
    $data_dosen = array();

    try {
        if ($nim) {
            $stmt_mahasiswa = $conn->prepare($query_mahasiswa);
            $stmt_mahasiswa->bindParam(':nim', $nim);
            $stmt_mahasiswa->execute();
            $result_mahasiswa = $stmt_mahasiswa->fetchAll(PDO::FETCH_ASSOC);

            if ($result_mahasiswa) {
                foreach ($result_mahasiswa as $mahasiswa) {
                    $data_mahasiswa[] = array(
                        'Nama Mahasiswa' => $mahasiswa['Nama'],
                        'NIM' => $mahasiswa['NIM'],
                        'Semester' => $mahasiswa['Semester'],
                        'Nama Dosen PA' => $mahasiswa['nama_dosen'],
                        'NIP Dosen PA' => $mahasiswa['nip']
                    );
                }
                return array('status' => 'success', 'mahasiswa' => $data_mahasiswa);
            } else {
                return array('status' => 'error', 'message' => 'Tidak ada data mahasiswa yang ditemukan.');
            }
        } else {
            $stmt_dosen = $conn->prepare($query_dosen);
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
                return array('status' => 'success', 'dosen' => $data_dosen);
            } else {
                return array('status' => 'error', 'message' => 'Tidak ada data dosen yang ditemukan.');
            }
        }
    } catch(PDOException $e) {
        return array('status' => 'error', 'message' => 'Query gagal: ' . $e->getMessage());
    }
}

if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];
    $data_json = getDosenMahasiswa($conn, $nim);
} else {
    $data_json = getDosenMahasiswa($conn);
}

echo json_encode($data_json, JSON_PRETTY_PRINT);

$conn = null;
?>
