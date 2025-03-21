<?php
require_once "config/db.php";

class UsersModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ambil semua user dari database
    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(); // Sama seperti 'rows' di mysql2/promise
    }
}
public function addUser($username, $email, $password) {
    $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    return $stmt->execute([$username, $email, $password]);
}

?>
