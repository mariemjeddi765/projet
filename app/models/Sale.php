<?php
require_once "C:\application xampp\htdocs\CastleCo\config\database.php";
class Sale {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function getAllWithClient() {
        $stmt = $this->conn->prepare("SELECT s.*, c.name as client_name FROM sales s JOIN clients c ON s.client_id = c.id ORDER BY s.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addSale($client_id, $items, $total_price, $payment_method) {
        $stmt = $this->conn->prepare("INSERT INTO sales (client_id, total_price, payment_method) VALUES (:client_id, :total_price, :payment_method)");
        $stmt->execute([':client_id'=>$client_id, ':total_price'=>$total_price, ':payment_method'=>$payment_method]);
        $sale_id = $this->conn->lastInsertId();

        // Ajouter les produits
        foreach ($items as $item) {
            $stmt2 = $this->conn->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, price) VALUES (:sale_id, :product_id, :quantity, :price)");
            $stmt2->execute([
                ':sale_id'=>$sale_id,
                ':product_id'=>$item['id'],
                ':quantity'=>$item['quantity'],
                ':price'=>$item['price']
            ]);

            // diminuer quantité produit
            $stmt3 = $this->conn->prepare("UPDATE products SET quantity = quantity - :q WHERE id=:id");
            $stmt3->execute([':q'=>$item['quantity'], ':id'=>$item['id']]);
        }
    }
    public function getAll() {
    $stmt = $this->conn->prepare("
        SELECT s.id, c.name AS client_name, s.total_price, s.payment_method, s.status, s.created_at
        FROM sales s
        JOIN clients c ON s.client_id = c.id
        ORDER BY s.created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function validate($id) {
    $stmt = $this->conn->prepare("UPDATE sales SET status='validee' WHERE id=:id");
    $stmt->execute([':id'=>$id]);
}

}
