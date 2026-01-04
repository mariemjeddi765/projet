<?php
require_once "../app/models/Product.php";
require_once "../app/models/Category.php";
require_once "../app/models/Cart.php";

class ProductController {

    // Afficher un produit
    public function show() {
        if (!isset($_GET['id'])) {
            header("Location: index.php?page=home");
            exit;
        }

        $id = $_GET['id'];
        $productModel = new Product();
        $cartModel = new Cart();

        // Récupérer le produit
        $product = $productModel->getById($id);
        if (!$product) {
            echo "Produit non trouvé";
            exit;
        }

        // Produits similaires
        $relatedProducts = $productModel->getByCategory($product['category_id'], $id);

        require "../app/views/product/show.php";
    }

    // Ajouter un produit au panier
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'];
            $quantity = $_POST['quantity'] ?? 1;

            $cartModel = new Cart();
            $cartModel->add($product_id, $quantity);

            header("Location: index.php?page=cart");
            exit;
        }
    }

    // Produits par catégorie
    public function category() {
        if (!isset($_GET['id'])) {
            header("Location: index.php?page=home");
            exit;
        }

        $category_id = $_GET['id'];
        $productModel = new Product();
        $categoryModel = new Category();

        $products = $productModel->getByCategory($category_id);
        $categories = $categoryModel->getAll();

        require "../app/views/home/index.php";
    }
}
