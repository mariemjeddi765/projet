<?php require_once "../app/views/layout/header.php"; ?>

<div class="site-panel">
    <h2 class="mb-4">Mon Panier</h2>

    <?php if (!empty($cartItems)): ?>
    <form action="index.php?page=cart&action=update" method="POST">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Produit</th>
                    <th>Prix Unitaire</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $grandTotal = 0; ?>
                <?php foreach ($cartItems as $item): ?>
                    <?php $product = $productModel->getById($item['product_id']); ?>
                    <?php if ($product): ?>
                        <?php $total = $product['price'] * $item['quantity']; ?>
                        <?php $grandTotal += $total; ?>
                        <tr>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo number_format($product['price'], 2, ',', ' '); ?> DT</td>
                            <td>
                                <input type="number" name="quantities[<?php echo $item['product_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $product['quantity']; ?>" class="form-control" style="width:80px;">
                            </td>
                            <td><?php echo number_format($total, 2, ',', ' '); ?> DT</td>
                            <td>
                                <a href="index.php?page=cart&action=remove&id=<?php echo $item['product_id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <button type="submit" class="btn btn-primary">Mettre à jour le panier</button>
                <a href="index.php?page=home" class="btn btn-secondary">Continuer mes achats</a>
            </div>
            <h4>Total : <?php echo number_format($grandTotal, 2, ',', ' '); ?> DT</h4>
        </div>
    </form>

    <hr>

    <!-- Formulaire pour passer commande -->
    <h3>Passer la commande</h3>
    <form action="index.php?page=cart&action=checkout" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Nom :</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" name="email" id="email" class="form-control">
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Téléphone :</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Adresse :</label>
            <textarea name="address" id="address" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Mode de: paiement :</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="payment_method" id="carte" value="carte" required>
                <label class="form-check-label" for="carte">Carte</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="payment_method" id="espece" value="espece" required>
                <label class="form-check-label" for="espece">Espèce</label>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Valider la commande</button>
    </form>

    <?php else: ?>
        <p>Votre panier est vide. <a href="index.php?page=home">Voir les produits</a></p>
    <?php endif; ?>
</div>

<?php require_once "../app/views/layout/footer.php"; ?>
