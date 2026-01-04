<?php require_once "../app/views/layout/header.php"; ?>

<h2>Gestion des Catégories</h2>

<!-- Formulaire pour ajouter une catégorie -->
<form method="POST" action="" class="mb-4">
    <input type="hidden" name="add_category" value="1">
    <div class="mb-3">
        <label>Nom de la catégorie</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Ajouter la catégorie</button>
</form>

<!-- Tableau des catégories -->
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($categories)): ?>
            <?php foreach($categories as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><?= $cat['name'] ?></td>
                    <td>
                        <a href="index.php?page=admin&action=categories&delete=<?= $cat['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cette catégorie ?');">Supprimer</a>
                        <!-- Tu peux ajouter un bouton modifier plus tard -->
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="text-center">Aucune catégorie trouvée.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once "../app/views/layout/footer.php"; ?>
