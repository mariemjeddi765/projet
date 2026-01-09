<?php require_once "../app/views/layout/header.php"; ?>

<div class="container mt-4">
    <h2 class="mb-4">Toutes les catégories</h2>

    <div class="row">
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $cat): ?>
                <div class="col-6 col-md-3 mb-3">
                    <a href="index.php?page=home&category_id=<?php echo $cat['id']; ?>" class="text-decoration-none">
                        <div class="site-panel text-center h-100">
                            <h5 class="mb-1"><?php echo htmlspecialchars($cat['name']); ?></h5>
                            <p class="small text-muted">Voir les produits</p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">Aucune catégorie disponible.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once "../app/views/layout/footer.php"; ?>
