<?php
class Koneksi {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "dummy_data2";
    public $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Koneksi gagal: " . $e->getMessage();
        }
    }
}

$database = new Koneksi();
$conn = $database->conn;
?>
