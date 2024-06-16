<?php

namespace App\app;

use App\config\AppJwt;
use App\config\Database;
use Firebase\JWT\JWT; 
use Firebase\JWT\Key;
use Exception;

class User
{
    protected \PDO $connection;

    public function __construct()
    {
        $db = new Database();
        $this->connection = $db->getConnection();
    }

    /**
     * Proses login via API
     * @return void
     */
    public function login()
    {
        header('Content-Type: application/json');

        // Validasi input POST
        if (!isset($_POST['username']) || !isset($_POST['password'])) {
            echo json_encode(['message' => 'Username and password are required']);
            exit();
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = 'SELECT * FROM user WHERE username = :username AND password = :password';
        $statement = $this->connection->prepare($query);

        // Bind params
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $password);
        $statement->execute();

        // Jika tidak ada data
        if ($statement->rowCount() === 0) {
            echo json_encode(['message' => 'Invalid username or password']);
            exit();
        }
        $result = $statement->fetch(\PDO::FETCH_OBJ);

        // Outputkan token
        $data = [
            'username' => $result->username,
            'role' => $result->role,
            'id' => $result->id,
        ];
        $token = JWT::encode($data, AppJwt::JWT_SECRET, 'HS256');
        echo json_encode(['token' => $token]);
    }

    /**
     * Melihat info user yg mengakses berd JWT
     * @return void
     */
    public function get()
    {
        header('Content-Type: application/json');

        $allHeaders = getallheaders();
        if (!isset($allHeaders['Authorization'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            exit();
        }

        list(, $token) = explode(' ', $allHeaders['Authorization']);
        
        try {
            $decoded = JWT::decode($token, new Key(AppJwt::JWT_SECRET, 'HS256'));
            $user = [
                'id' => $decoded->id,
                'role' => $decoded->role,
                'username' => $decoded->username,
            ];
            echo json_encode($user);
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }
}
?>
