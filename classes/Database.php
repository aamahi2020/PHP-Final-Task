<?php
class Database {
    private $host = 'localhost';
    private $db = 'password_manager';
    private $user = 'root';
    private $pass = '';
    private $pdo;

    public function connect() {
        if (!$this->pdo) {
            $dsn = "mysql:host=$this->host;dbname=$this->db;charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $this->pdo;
    }
}
