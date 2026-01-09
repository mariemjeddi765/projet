<?php require_once "../app/views/layout/header.php"; ?>

<div class="row site-panel align-items-center">
    <!-- Détail produit -->
    <div class="col-md-6 mb-3">
        <div class="card product-card">
            <img src="assets/images/<?php echo $product['image'] ?? 'default.png'; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
        </div>
    </div>

    <div class="col-md-6">
        <h2 class="product-title"><?php echo $product['name']; ?></h2>
        <p class="fw-bold product-subtitle"><?php echo number_format($product['price'], 2, ',', ' '); ?> DT</p>
        <p><?php echo $product['description']; ?></p>

        <!-- Formulaire Ajouter au panier -->
        <form action="index.php?page=cart&action=add" method="POST" class="mb-3">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <div class="mb-2">
                <label for="quantity" class="form-label">Quantité :</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="<?php echo $product['quantity']; ?>">
            </div>
            <button type="submit" class="btn btn-primary me-2">Ajouter au panier</button>
            <a href="index.php?page=cart&action=checkout&product_id=<?php echo $product['id']; ?>" class="btn btn-outline-primary">Acheter</a>
        </form>

        <p>Stock disponible : <?php echo $product['quantity']; ?></p>
    </div>
</div>

<?php if (!empty($relatedProducts)): ?>
    <h3>Produits similaires</h3>
    <div class="row">
        <?php foreach ($relatedProducts as $p): ?>
            <div class="col-md-3">
                <div class="card">
                    <img src="assets/images/<?php echo $p['image'] ?? 'default.png'; ?>" class="card-img-top">
                    <div class="card-body">
                        <h5><?php echo $p['name']; ?></h5>
                        <p><?php echo number_format($p['price'],2,',',' '); ?> DT</p>
                        <a href="index.php?page=product&action=show&id=<?php echo $p['id']; ?>" class="btn btn-success">Voir</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>



<?php require_once "../app/views/layout/footer.php"; ?>
