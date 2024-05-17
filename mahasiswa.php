<?php
$host       = "localhost";
$username   = "root";
$password   = "";
$database   = "dummy_data2";

$mahasiswa = new mysqli($host, $username, $password, $database);

if ($mahasiswa->connect_error) {
    die("Koneksi gagal: " . $mahasiswa->connect_error);
}

$query = "SHOW TABLES";
$result = $mahasiswa->query($query);

if (!$result) {
    die("Query gagal: " . $mahasiswa->error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tabel</title>
    <style>
        table {
            width: 50%;
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
    <h1>Data dari Tabel 'mahasiswa'</h1>
    <table>
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Semester</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query untuk mendapatkan data dari tabel mahasiswa
            $query_mahasiswa = "SELECT NIM, Nama, Semester FROM mahasiswa";
            $result_mahasiswa = $mahasiswa->query($query_mahasiswa);

            if ($result_mahasiswa) {
                if ($result_mahasiswa->num_rows > 0) {
                    // Menampilkan data mahasiswa
                    while ($row = $result_mahasiswa->fetch_assoc()) {
                        echo "<tr><td>" . $row['NIM'] . "</td><td>" . $row['Nama'] . "</td><td>" . $row['Semester'] . "</td><td>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Tidak ada data ditemukan dalam tabel 'mahasiswa'.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Query gagal: " . $mahasiswa->error . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php
// Menutup koneksi
$mahasiswa->close();
?>
