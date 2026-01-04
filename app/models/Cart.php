<?php
class Cart {
    public function __construct() {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    }

    // Ajouter un produit au panier
    public function add($product_id, $quantity = 1) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }

    // Récupérer tous les produits du panier
    public function getAll() {
        $items = [];
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $items[] = [
                'product_id' => $product_id,
                'quantity' => $quantity
            ];
        }
        return $items;
    }

    // Supprimer un produit du panier
    public function remove($product_id) {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    // Mettre à jour la quantité d’un produit
    public function update($product_id, $quantity) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }

    // Vider le panier
    public function clear() {
        $_SESSION['cart'] = [];
    }

    // Calculer le total du panier
    public function getTotal($productModel) {
        $total = 0;
        foreach ($this->getAll() as $item) {
            $product = $productModel->getById($item['product_id']);
            if ($product) {
                $total += $product['price'] * $item['quantity'];
            }
        }
        return $total;
    }
}
