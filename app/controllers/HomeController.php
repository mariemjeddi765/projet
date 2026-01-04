<?php
require_once "../app/models/Product.php";
require_once "../app/models/Category.php";

class HomeController {

    // Afficher la page d'accueil
    public function index() {
        $productModel = new Product();
        $categoryModel = new Category();

        // Récupérer tous les produits
        $products = $productModel->getAll();

        // Récupérer toutes les catégories pour le menu
        $categories = $categoryModel->getAll();

        // Inclure la vue
        require "../app/views/home/index.php";
    }

    // Afficher les produits par catégorie
    public function category($category_id) {
        $productModel = new Product();
        $categoryModel = new Category();

        // Récupérer les produits de la catégorie
        $products = $productModel->getByCategory($category_id);

        // Récupérer toutes les catégories pour le menu
        $categories = $categoryModel->getAll();

        // Inclure la vue
        require "../app/views/home/index.php";
    }

    // Recherche de produit
    public function search($keyword) {
        $productModel = new Product();
        $categoryModel = new Category();

        // Récupérer les produits correspondant au mot-clé
        $products = $productModel->search($keyword);

        // Récupérer toutes les catégories pour le menu
        $categories = $categoryModel->getAll();

        // Inclure la vue
        require "../app/views/home/index.php";
    }
}
