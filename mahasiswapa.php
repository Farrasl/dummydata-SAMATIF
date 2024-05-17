<?php
$host       = "localhost";
$username   = "root";
$password   = "";
$database   = "dummy_data2";

$koneksi = new mysqli($host, $username, $password, $database);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$query_dosen = "SELECT nip, nama FROM dosen";
$result_dosen = $koneksi->query($query_dosen);

if (!$result_dosen) {
    die("Query gagal: " . $koneksi->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa per Dosen PA</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 18px;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Daftar Mahasiswa per Dosen PA</h1>

    <h2>Daftar Dosen</h2>
<?php
if ($result_dosen->num_rows > 0) {
    while ($row_dosen = $result_dosen->fetch_assoc()) {
        echo "<button type='button' onclick=\"window.location.href='?dosen=" . $row_dosen['nip'] . "'\">" . $row_dosen['nama'] . "</button>";
    }
} else {
    echo "<p>Tidak ada dosen yang ditemukan.</p>";
}
?>
    <?php
    if (isset($_GET['dosen'])) {
        $nip = $_GET['dosen'];

        $query_nama_dosen = "SELECT nama FROM dosen WHERE nip = '$nip'";
        $result_nama_dosen = $koneksi->query($query_nama_dosen);
        if ($result_nama_dosen->num_rows > 0) {
            $row_nama_dosen = $result_nama_dosen->fetch_assoc();
            $nama_dosen = $row_nama_dosen['nama'];

            echo "<h2>Data Mahasiswa yang Dibimbing oleh Dosen: $nama_dosen</h2>";

$query_mahasiswa = "SELECT m.NIM, m.Nama, m.Semester
                    FROM mahasiswa m
                    INNER JOIN riwayat_pa r ON m.NIM = r.NIM
                    INNER JOIN dosen d ON r.NIP = d.nip
                    WHERE d.nip = '$nip'";

            $result_mahasiswa = $koneksi->query($query_mahasiswa);

            if ($result_mahasiswa->num_rows > 0) {
                echo "<table>
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Semester</th>
                            </tr>
                        </thead>
                        <tbody>";
                while ($row_mahasiswa = $result_mahasiswa->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row_mahasiswa['NIM'] . "</td>
                            <td>" . $row_mahasiswa['Nama'] . "</td>
                            <td>" . $row_mahasiswa['Semester'] . "</td>
                        </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>Tidak ada mahasiswa ditemukan untuk dosen ini.</p>";
            }
        } else {
            echo "<p>Dosen tidak ditemukan.</p>";
        }
    }
    ?>
</body>
</html>
<?php
$koneksi->close();
?>