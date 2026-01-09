<?php require_once "../app/views/layout/header.php"; ?>

<?php
// Chargement des modèles pour afficher catégories & produits
require_once "../app/models/Category.php";
require_once "../app/models/Product.php";
$categoryModel = new Category();
$categories = $categoryModel->getAll();
$productModel = new Product();
$allProducts = $productModel->getAll();
// Prendre quelques produits pour l'aperçu
$featuredProducts = array_slice($allProducts, 0, 8);
?>

<!-- HERO BANNER -->
<section class="hero mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title">Bienvenue chez CastleCo</h1>
                <p class="hero-subtitle">Découvrez une sélection premium de produits cosmétiques — douceur, naturel et élégance.</p>
                <div class="hero-cta mt-4">
                    <a href="index.php?page=home&action=products" class="btn btn-primary btn-lg">Voir la collection</a>
                    <a href="index.php?page=home&action=categories" class="btn btn-outline-primary btn-lg">Explorer les catégories</a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <img src="assets/images/pr.jpg" alt="Bannière"  class="img-fluid rounded" />
            </div>
        </div>
    </div>
</section>

<!-- CATEGORIES PREVIEW -->
<section class="mb-5">
    <div class="container">
        <h3 class="mb-3">Catégories populaires</h3>
        <div class="d-flex flex-wrap gap-2 mb-3">
            <?php foreach($categories as $cat): ?>
                <a href="index.php?page=home&category_id=<?php echo $cat['id']; ?>" class="category-badge"><?php echo htmlspecialchars($cat['name']); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="mb-5">
    <div class="container">
        <h3 class="mb-4">Nos produits phares</h3>
        <div class="row product-grid">
            <?php if (!empty($featuredProducts)): ?>
                <?php foreach($featuredProducts as $product): ?>
                    <div class="col-6 col-md-3">
                        <div class="card product-card h-100">
                            <img src="assets/images/<?php echo $product['image'] ?? 'default.png'; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title product-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text product-subtitle"><?php echo substr(strip_tags($product['description']), 0, 60) . '...'; ?></p>
                                <p class="card-text fw-bold mt-auto"><?php echo number_format($product['price'], 2, ',', ' '); ?> DT</p>
                                <a href="index.php?page=product&action=show&id=<?php echo $product['id']; ?>" class="btn btn-outline-primary w-100 mt-2">Voir</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p>Aucun produit disponible pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once "../app/views/layout/footer.php"; ?>
