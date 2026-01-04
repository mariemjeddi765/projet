<?php
require_once "../app/models/Cart.php";
require_once "../app/models/Product.php";
require_once "../app/models/Client.php";
require_once "../app/models/Sale.php";

class CartController {

    // Afficher le panier
    public function index() {
        $cartModel = new Cart();
        $productModel = new Product();

        $cartItems = $cartModel->getAll();
        $totalPrice = 0;

        foreach ($cartItems as &$item) {
            $product = $productModel->getById($item['product_id']);
            $item['name'] = $product['name'];
            $item['price'] = $product['price'];
            $item['image'] = $product['image'];
            $totalPrice += $item['quantity'] * $item['price'];
        }

        require "../app/views/cart/index.php";
    }

    // Ajouter un produit au panier
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'];
            $quantity = $_POST['quantity'] ?? 1;

            $cartModel = new Cart();
            $cartModel->add($product_id, $quantity);

            header("Location: index.php?page=cart");
            exit;
        }
    }

    // Supprimer un produit du panier
    public function remove() {
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];

            $cartModel = new Cart();
            $cartModel->remove($product_id);

            header("Location: index.php?page=cart");
            exit;
        }
    }

    // Mettre à jour la quantité d’un produit
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            $cartModel = new Cart();
            $cartModel->update($product_id, $quantity);

            header("Location: index.php?page=cart");
            exit;
        }
    }

    // Checkout - passer la commande
    public function checkout() {
        $cartModel = new Cart();
        $productModel = new Product();
        $saleModel = new Sale();
        $clientModel = new Client();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1️⃣ Ajouter le client
            $clientData = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address']
            ];
            $clientId = $clientModel->add($clientData);

            // 2️⃣ Récupérer le panier
            $cartItems = $cartModel->getAll();

            // 3️⃣ Compléter les infos des produits et ajouter 'id' pour addSale()
            $totalPrice = 0;
            foreach ($cartItems as &$item) {
                $product = $productModel->getById($item['product_id']);
                $item['price'] = $product['price'];
                $item['id'] = $item['product_id']; // pour addSale()
                $totalPrice += $item['quantity'] * $item['price'];
            }

            // 4️⃣ Ajouter la vente
            $saleModel->addSale($clientId, $cartItems, $totalPrice, $_POST['payment_method']);

            // 5️⃣ Vider le panier
            $cartModel->clear();

            // 6️⃣ Rediriger vers page de confirmation
            header("Location: index.php?page=cart&action=success");
            exit;
        }

        // Afficher le formulaire de checkout
        require "../app/views/cart/checkout.php";
    }

    // Page de succès après commande
    public function success() {
        require "../app/views/cart/success.php";
    }
}
