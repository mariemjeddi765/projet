<?php
require_once __DIR__ . "/../../config/database.php";

class Client {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect(); // ✅ BONNE MÉTHODE
    }

    // Ajouter un client
    public function add($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO clients (name, email, phone, address)
            VALUES (:name, :email, :phone, :address)
        ");

        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':address' => $data['address']
        ]);

        return $this->conn->lastInsertId();
    }

    // Récupérer tous les clients (admin)
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM clients ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
