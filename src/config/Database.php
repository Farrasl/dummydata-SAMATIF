<?php

namespace App\config;

use PDO;
use PDOException;

class Database
{
    protected string $dbname = 'dummy_data2';
    protected string $host = 'localhost';
    protected string $user = 'root';
    protected string $password = '';

    public function getConnection(): PDO
    {
      $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
      try {
        $connection = new PDO($dsn, $this->user, $this->password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
      } catch (PDOException $e) {
        die($e->getMessage());
      }
    }
}