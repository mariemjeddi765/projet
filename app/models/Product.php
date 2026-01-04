<?php
require_once "C:\application xampp\htdocs\CastleCo\config\database.php";
require_once "Category.php";

class Product {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($data) {
        $stmt = $this->conn->prepare("INSERT INTO products (name, description, price, quantity, category_id) VALUES (:name, :description, :price, :quantity, :category_id)");
        $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':quantity' => $data['quantity'],
            ':category_id' => $data['category_id']
        ]);
    }

    public function update($data) {
        $stmt = $this->conn->prepare("UPDATE products SET name=:name, description=:description, price=:price, quantity=:quantity, category_id=:category_id WHERE id=:id");
        $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':quantity' => $data['quantity'],
            ':category_id' => $data['category_id'],
            ':id' => $data['id']
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id=:id");
        $stmt->execute([':id'=>$id]);
    }
    public function decreaseQuantity($id, $quantity) {
    $stmt = $this->conn->prepare("UPDATE products SET quantity = quantity - :quantity WHERE id = :id");
    $stmt->execute([':quantity' => $quantity, ':id' => $id]);
}
public function getById($id) {
    $stmt = $this->conn->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = :id");
    $stmt->execute([':id'=>$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getByCategory($category_id, $exclude_id = null) {
    $sql = "SELECT * FROM products WHERE category_id = :cat";
    if ($exclude_id) {
        $sql .= " AND id != :exclude";
    }
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':cat', $category_id);
    if ($exclude_id) {
        $stmt->bindParam(':exclude', $exclude_id);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



}
