<?php
$host       = "localhost";
$username   = "root";
$password   = "";
$database   = "dummy_data2";

// Membuat koneksi
$dosen = new mysqli($host, $username, $password, $database);

// Memeriksa koneksi
if ($dosen->connect_error) {
    die("Koneksi gagal: " . $dosen->connect_error);
}

// Query untuk mendapatkan daftar semua tabel
$query = "SHOW TABLES";
$result = $dosen->query($query);

if (!$result) {
    die("Query gagal: " . $dosen->error);
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
    <h1>Data dari Tabel 'dosen'</h1>
    <table>
        <thead>
            <tr>
                <th>NIP</th>
                <th>Nama</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query untuk mendapatkan data dari tabel dosen
            $query_dosen = "SELECT NIP, Nama FROM dosen";
            $result_dosen = $dosen->query($query_dosen);

            if ($result_dosen) {
                if ($result_dosen->num_rows > 0) {
                    // Menampilkan data dosen
                    while ($row = $result_dosen->fetch_assoc()) {
                        echo "<tr><td>" . $row['NIP'] . "</td><td>" . $row['Nama'] . "</td><td>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Tidak ada data ditemukan dalam tabel 'dosen'.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Query gagal: " . $dosen->error . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php
// Menutup koneksi
$dosen->close();
?>
