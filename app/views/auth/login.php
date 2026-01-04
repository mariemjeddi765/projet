<?php require_once "../app/views/layout/header.php"; ?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <h2 class="mb-4 text-center">Espace Pro - Connexion</h2>

        <?php if(!empty($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=auth&action=login" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>
</div>

<?php require_once "../app/views/layout/footer.php"; ?>
