<?php
require_once "../app/models/Sale.php";
require_once "../app/models/SaleItem.php";
require_once "../app/models/Product.php";

class SaleController {

    // Liste toutes les ventes
    public function index() {
        session_start();
        $this->checkAuth();

        $saleModel = new Sale();
        $sales = $saleModel->getAll();

        require "../app/views/admin/sales.php";
    }

    // Valider une vente
    public function validate() {
        session_start();
        $this->checkAuth();

        if (isset($_GET['id'])) {
            $sale_id = $_GET['id'];

            $saleModel = new Sale();
            $saleItems = $saleModel->getItems($sale_id);

            $productModel = new Product();

            // Mettre à jour la quantité des produits
            foreach ($saleItems as $item) {
                $productModel->decreaseQuantity($item['product_id'], $item['quantity']);
            }

            // Marquer la vente comme validée
            $saleModel->validate($sale_id);

            header("Location: index.php?page=sale&action=index");
            exit;
        }
    }

    // Vérifier l'authentification
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header("Location: index.php?page=auth&action=login");
            exit;
        }
    }
}
