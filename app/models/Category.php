<?php
require_once "C:\application xampp\htdocs\CastleCo\config\database.php";

class Category {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public function add($name) {
    // Vérifier si le nom existe déjà
    $stmt = $this->conn->prepare("SELECT COUNT(*) FROM categories WHERE name=:name");
    $stmt->execute([':name'=>$name]);
    if($stmt->fetchColumn() > 0) {
        // Le nom existe déjà
        return false;
    }

    // Sinon ajouter
    $stmt = $this->conn->prepare("INSERT INTO categories (name) VALUES (:name)");
    $stmt->execute([':name'=>$name]);
    return true;
}


    public function update($id, $name) {
        $stmt = $this->conn->prepare("UPDATE categories SET name=:name WHERE id=:id");
        $stmt->execute([':name'=>$name, ':id'=>$id]);
    }

   public function delete($id) {
    $stmt = $this->conn->prepare("DELETE FROM categories WHERE id=:id");
    $stmt->execute([':id' => $id]);  // <-- ici on passe le paramètre correctement
}

}

