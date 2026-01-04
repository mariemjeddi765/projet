<?php require_once "../app/views/layout/header.php"; ?>

<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Tableau de bord - Espace Pro</h2>
        <p>Bienvenue, <?php echo $_SESSION['username'] ?? 'Utilisateur'; ?> !</p>
    </div>
</div>

<div class="row">
    <!-- Gestion des produits -->
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body d-flex flex-column justify-content-center">
                <h5 class="card-title">Produits</h5>
                <p class="card-text">Voir, ajouter, modifier ou supprimer des produits.</p>
                <a href="index.php?page=admin&action=products" class="btn btn-primary mt-auto">Gérer les produits</a>
            </div>
        </div>
    </div>

    <!-- Gestion des catégories -->
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body d-flex flex-column justify-content-center">
                <h5 class="card-title">Catégories</h5>
                <p class="card-text">Voir et modifier les catégories de produits.</p>
                <a href="index.php?page=admin&action=categories" class="btn btn-primary mt-auto">Gérer les catégories</a>
            </div>
        </div>
    </div>

    <!-- Gestion des ventes -->
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body d-flex flex-column justify-content-center">
                <h5 class="card-title">Ventes</h5>
                <p class="card-text">Voir et valider les ventes des clients.</p>
                <a href="index.php?page=admin&action=sales" class="btn btn-primary mt-auto">Gérer les ventes</a>
            </div>
        </div>
    </div>

    <!-- Gestion des clients -->
    <div class="col-md-3 mb-3">
        <div class="card text-center h-100">
            <div class="card-body d-flex flex-column justify-content-center">
                <h5 class="card-title">Clients</h5>
                <p class="card-text">Voir tous les clients et leurs coordonnées.</p>
                <a href="index.php?page=admin&action=clients" class="btn btn-primary mt-auto">Gérer les clients</a>
            </div>
        </div>
    </div>
</div>

<?php require_once "../app/views/layout/footer.php"; ?>
