<?php

class Database {
    private $host = "localhost";       // Adresse du serveur MySQL
    private $db_name = "castleco";     // Nom de la base de données
    private $username = "root";        // Nom d'utilisateur MySQL
    private $password = "";            // Mot de passe MySQL
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
            exit;
        }

        return $this->conn;
    }
}
