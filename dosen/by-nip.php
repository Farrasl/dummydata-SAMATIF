<?php
include '../koneksi.php';

header('Content-Type: application/json');

$data_json = array();

try {
    if (isset($_GET['nip'])) {
        $nip = $_GET['nip'];
        $query = "SELECT * FROM dosen WHERE nip = :nip";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nip', $nip, PDO::PARAM_STR);
    } else {
        $query = "SELECT * FROM dosen";
        $stmt = $conn->prepare($query);
    }

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        $data_json = $result;
    } else {
        $data_json["error"] = "Dosen dengan NIP tersebut tidak ditemukan.";
    }
} catch(PDOException $e) {
    $data_json["error"] = "Query gagal: " . $e->getMessage();
}

echo json_encode($data_json, JSON_PRETTY_PRINT);

$conn = null;   
?>
