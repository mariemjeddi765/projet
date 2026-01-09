<?php

class AdminController {

    public function __construct() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=auth&action=login");
            exit;
        }
    }

    public function dashboard() {
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function products() {
        $productModel = new Product();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
            $productModel->add($_POST);
        }

        if (isset($_GET['delete'])) {
            $productModel->delete($_GET['delete']);
        }

        if (isset($_POST['edit_product'])) {
            $productModel->update($_POST);
        }

        $products = $productModel->getAll();
        require_once __DIR__ . '/../views/admin/products.php';
    }

    public function addProduct() {
        $productModel = new Product();
        $categoryModel = new Category();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'quantity' => $_POST['quantity'],
                'category_id' => $_POST['category_id'],
                'image' => $_POST['image'] ?? 'default.png'
            ];
            $productModel->add($data);
            header("Location: index.php?page=admin&action=products");
            exit;
        }

        $categories = $categoryModel->getAll();
        require_once __DIR__ . '/../views/admin/add_product.php';
    }

    public function categories() {
        $categoryModel = new Category();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
            $categoryModel->add($_POST['name']);
            header("Location: index.php?page=admin&action=categories");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category'])) {
            $categoryModel->update($_POST['id'], $_POST['name']);
            header("Location: index.php?page=admin&action=categories");
            exit;
        }

        if (isset($_GET['delete'])) {
            $categoryModel->delete($_GET['delete']);
            header("Location: index.php?page=admin&action=categories");
            exit;
        }

        $categories = $categoryModel->getAll();
        require_once __DIR__ . '/../views/admin/categories.php';
    }

    public function sales() {
        $saleModel = new Sale();
        $sales = $saleModel->getAllWithClient();
        require_once __DIR__ . '/../views/admin/sales/index.php';
    }

    public function validateSale() {
        if (isset($_GET['id'])) {
            $saleModel = new Sale();
            $saleModel->validate($_GET['id']);
        }
        header("Location: index.php?page=admin&action=sales");
        exit;
    }

    public function clients() {
        $clientModel = new Client();
        $clients = $clientModel->getAll();
        require_once __DIR__ . '/../views/admin/clients/index.php';
    }

    public function stats() {
        $saleModel = new Sale();
        $sales = $saleModel->getAll();

        // Prepare last 6 months buckets
        $months = [];
        $now = new DateTime();
        for ($i = 5; $i >= 0; $i--) {
            $m = (clone $now)->modify("-{$i} months");
            $key = $m->format('Y-m');
            $months[$key] = [
                'label' => $m->format('M Y'),
                'total' => 0.0,
                'count' => 0
            ];
        }

        $paymentMethods = [];
        foreach ($sales as $s) {
            $d = new DateTime($s['created_at']);
            $k = $d->format('Y-m');
            if (isset($months[$k])) {
                $months[$k]['total'] += floatval($s['total_price']);
                $months[$k]['count'] += 1;
            }

            $pm = $s['payment_method'] ?? 'unknown';
            if (!isset($paymentMethods[$pm])) $paymentMethods[$pm] = 0;
            $paymentMethods[$pm] += 1;
        }

        $labels = [];
        $totals = [];
        $counts = [];
        foreach ($months as $m) {
            $labels[] = $m['label'];
            $totals[] = round($m['total'], 2);
            $counts[] = $m['count'];
        }

        $payment_labels = array_keys($paymentMethods);
        $payment_values = array_values($paymentMethods);

        $totalSales = array_sum($totals);
        $totalOrders = array_sum($counts);

        require_once __DIR__ . '/../views/admin/stats.php';
    }
}
