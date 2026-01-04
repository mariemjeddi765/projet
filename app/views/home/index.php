<?php require_once "../app/views/layout/header.php"; ?>

<div class="row mb-4">
    <!-- Barre de recherche -->
    <div class="col-md-6">
        <form action="index.php?page=home" method="GET" class="d-flex">
            <input type="hidden" name="page" value="home">
            <input type="text" name="search" class="form-control me-2" placeholder="Rechercher un produit..." value="<?php echo $_GET['search'] ?? ''; ?>">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>
    </div>
</div>

<div class="row">
    <?php if (!empty($products)): ?>
        <?php foreach($products as $product): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="assets/images/<?php echo $product['image'] ?? 'default.png'; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                        <p class="card-text"><?php echo substr($product['description'], 0, 50) . '...'; ?></p>
                        <p class="card-text fw-bold"><?php echo number_format($product['price'], 2, ',', ' '); ?> DT</p>
                        <div class="mt-auto">
                            <a href="index.php?page=home&action=product&id=<?php echo $product['id']; ?>" class="btn btn-success w-100 mb-2">Voir</a>
                            <form action="index.php?page=cart&action=add" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-primary w-100">Ajouter au panier</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <p class="text-center">Aucun produit trouvé.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once "../app/views/layout/footer.php"; ?>
