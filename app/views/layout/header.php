<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CastleCo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Custom overrides (keep after Bootstrap) -->
    <link rel="stylesheet" href="css/custom.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container">
            <a class="navbar-brand" href="index.php?page=home">CastleCo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=home">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=home&action=products">Tous les produits</a>
                    </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=chatbot">Assistant</a>
                        </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Catégories
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            require_once "../app/models/Category.php";
                            $categoryModel = new Category();
                            $categories = $categoryModel->getAll();
                            foreach ($categories as $cat) {
                                echo '<li><a class="dropdown-item" href="index.php?page=home&category_id='.$cat['id'].'">'.$cat['name'].'</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=cart">Panier 
                            <?php
                            if(isset($_SESSION['cart'])) {
                                $count = count($_SESSION['cart']);
                                echo "($count)";
                            }
                            ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a class="nav-link" href="index.php?page=auth&action=logout">Déconnexion</a>
                        <?php else: ?>
                            <a class="nav-link" href="index.php?page=auth&action=login">Espace Pro</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
