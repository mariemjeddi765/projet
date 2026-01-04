<?php
require_once "../app/models/Category.php";

class CategoryController {

    // Liste des catégories
    public function index() {
        session_start();
        $this->checkAuth();

        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        require "../app/views/admin/categories.php";
    }

    // Ajouter une catégorie
    public function add() {
        session_start();
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];

            $categoryModel = new Category();
            $categoryModel->add($name);

            header("Location: index.php?page=category");
            exit;
        }
    }

    // Modifier une catégorie
    public function edit() {
        session_start();
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];

            $categoryModel = new Category();
            $categoryModel->update($id, $name);

            header("Location: index.php?page=category");
            exit;
        }
    }

    // Supprimer une catégorie
    public function delete() {
        session_start();
        $this->checkAuth();

        if (isset($_GET['id'])) {
            $categoryModel = new Category();
            $categoryModel->delete($_GET['id']);
        }

        header("Location: index.php?page=category");
        exit;
    }

    // Vérifier l'authentification
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header("Location: index.php?page=auth&action=login");
            exit;
        }
    }
}
