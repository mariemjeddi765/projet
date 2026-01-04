<?php
require_once "../app/models/Client.php";

class ClientController {

    // Liste tous les clients
    public function index() {
        session_start();
        $this->checkAuth();

        $clientModel = new Client();
        $clients = $clientModel->getAll();

        require "../app/views/admin/clients.php";
    }

    // Vérifier l'authentification
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header("Location: index.php?page=auth&action=login");
            exit;
        }
    }
}
