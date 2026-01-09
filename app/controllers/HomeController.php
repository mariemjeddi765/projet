<?php
require_once "../app/models/Product.php";
require_once "../app/models/Category.php";

class HomeController {

    // Afficher la page d'accueil
    public function index() {
        $productModel = new Product();
        $categoryModel = new Category();
        // Si une recherche est fournie, afficher les résultats
        if (!empty($_GET['search'])) {
            $products = $productModel->search($_GET['search']);
            $categories = $categoryModel->getAll();
            require "../app/views/home/index.php";
            return;
        }

        // Si une catégorie est fournie via category_id, afficher la liste filtrée
        if (!empty($_GET['category_id'])) {
            $category_id = (int)$_GET['category_id'];
            $products = $productModel->getByCategory($category_id);
            $categories = $categoryModel->getAll();
            require "../app/views/home/index.php";
            return;
        }

        // Sinon afficher la page d'accueil (hero + aperçu)
        $products = $productModel->getAll();
        $categories = $categoryModel->getAll();
        require "../app/views/home/homepage.php";
    }

    // Afficher la liste complète des produits (collection)
    public function products() {
        $productModel = new Product();
        $categoryModel = new Category();

        $products = $productModel->getAll();
        $categories = $categoryModel->getAll();

        require "../app/views/home/index.php";
    }

    // Afficher toutes les catégories (aperçu)
    public function categories() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        require "../app/views/home/categories.php";
    }

    // Afficher les produits par catégorie
    public function category() {
        // Supporter category_id via GET pour compatibilité avec les liens
        if (empty($_GET['category_id'])) {
            header("Location: index.php?page=home");
            exit;
        }
        $category_id = (int)$_GET['category_id'];
        $productModel = new Product();
        $categoryModel = new Category();

        $products = $productModel->getByCategory($category_id);
        $categories = $categoryModel->getAll();
        require "../app/views/home/index.php";
    }

    // Recherche de produit
    public function search() {
        if (empty($_GET['search'])) {
            header("Location: index.php?page=home");
            exit;
        }
        $keyword = $_GET['search'];
        $productModel = new Product();
        $categoryModel = new Category();

        $products = $productModel->search($keyword);
        $categories = $categoryModel->getAll();
        require "../app/views/home/index.php";
    }
}
