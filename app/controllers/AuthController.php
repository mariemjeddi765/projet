<?php

class AuthController {

    public function login() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 🔓 Connexion forcée (DEMO)
            $_SESSION['user'] = [
                'id' => 1,
                'username' => 'admin',
                'role' => 'admin'
            ];

            // Redirection directe vers l'espace pro
            header("Location: index.php?page=admin&action=dashboard");
            exit;
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
        exit;
    }
}
