<?php require_once "../app/views/layout/header.php"; ?>

<h2>Passer la commande</h2>

<form method="POST" action="">
    <div class="mb-3">
        <label>Nom</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
    </div>
    <div class="mb-3">
        <label>Téléphone</label>
        <input type="text" name="phone" class="form-control">
    </div>
    <div class="mb-3">
        <label>Adresse</label>
        <textarea name="address" class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label>Mode de paiement</label>
        <select name="payment_method" class="form-control">
            <option value="carte">Carte</option>
            <option value="espece">Espèce</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Valider la commande</button>
</form>

<?php require_once "../app/views/layout/footer.php"; ?>
