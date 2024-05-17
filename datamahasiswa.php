<?php
$host       = "localhost";
$username   = "root";
$password   = "";
$database   = "dummy_data2";

$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$query = "SELECT * FROM mahasiswa";
$result = $koneksi->query($query);

$data_json = array();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data_json[] = $row;
    }
} else {
    $data_json["error"] = "Query gagal: " . $koneksi->error;
}

header('Content-Type: application/json');
echo json_encode($data_json, JSON_PRETTY_PRINT);

$koneksi->close();
?>
