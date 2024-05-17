<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dummy_data2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql_mahasiswa = "SELECT Nama FROM mahasiswa";
$result_mahasiswa = $conn->query($sql_mahasiswa);

$nama_mahasiswa = array();
if ($result_mahasiswa->num_rows > 0) {
    while ($row = $result_mahasiswa->fetch_assoc()) {
        $nama_mahasiswa[] = $row['Nama'];
    }
}

if (isset($_POST['selected_mahasiswa'])) {
    $selected_mahasiswa = $_POST['selected_mahasiswa'];
} else {
    $selected_mahasiswa = ""; 
}

$sql_setoran = "SELECT s.nama AS nama_surah, i.tanggal, i.kelancaran, i.tajwid, i.makhrajul_huruf
        FROM setoran rs
        JOIN setoran i ON rs.id_setoran = i.id_setoran
        JOIN riwayat_pa pa ON rs.NIM = pa.NIM
        JOIN surah s ON i.id_surah = s.id_surah
        JOIN mahasiswa m ON rs.NIM = m.NIM
        WHERE m.Nama = '$selected_mahasiswa'";
$result_setoran = $conn->query($sql_setoran);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Setoran Mahasiswa</title>
    <style>
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }
        .styled-table thead th {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            background-color: #f2f2f2;
            text-align: left;
        }
        .styled-table tbody td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .styled-table tbody td:first-child {
            border-left: 1px solid #ddd;
        }
        .styled-table tbody td:last-child {
            border-right: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h2>Pilih Mahasiswa:</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <?php
        foreach ($nama_mahasiswa as $mahasiswa) {
            echo "<input type='submit' name='selected_mahasiswa' value='$mahasiswa'>";
        }
        ?>
    </form>
    <?php
    if ($result_setoran && $result_setoran->num_rows > 0) {
        echo "<h2>Daftar Setoran Mahasiswa: $selected_mahasiswa</h2>";
        echo "<table class='styled-table'>";
        echo "<thead><tr><th>No</th><th>Surah</th><th>Tanggal</th><th>Kelancaran</th><th>Tajwid</th><th>Makhrijul Huruf</th></tr></thead>";
        echo "<tbody>";
        $no = 1; 
        while ($row = $result_setoran->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>"; 
            echo "<td>" . $row["nama_surah"] . "</td>";
            echo "<td>" . $row["tanggal"] . "</td>";
            echo "<td>" . $row["kelancaran"] . "</td>";
            echo "<td>" . $row["tajwid"] . "</td>";
            echo "<td>" . $row["makhrajul_huruf"] . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>Tidak ada setoran yang ditemukan untuk mahasiswa: $selected_mahasiswa</p>";
    }
    $conn->close();
    ?>
</body>
</html>
