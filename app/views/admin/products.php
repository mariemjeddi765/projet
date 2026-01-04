<?php require_once "../app/views/layout/header.php"; ?>

<h2>Ajouter un produit</h2>

<form method="POST" action="">
    <input type="hidden" name="add_product" value="1">

    <div class="mb-3">
        <label>Nom du produit</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
        <label>Prix</label>
        <input type="number" step="0.01" name="price" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Quantité</label>
        <input type="number" name="quantity" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Catégorie</label>
        <select name="category_id" class="form-control" required>
            <?php
            $categoryModel = new Category();
            $categories = $categoryModel->getAll();
            foreach($categories as $cat){
                echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Image (nom du fichier dans /assets/images)</label>
        <input type="text" name="image" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Ajouter le produit</button>
    <a href="index.php?page=admin&action=products" class="btn btn-secondary">Annuler</a>
</form>

<?php require_once "../app/views/layout/footer.php"; ?>
