<?php require_once "../app/views/layout/header.php"; ?>

<div class="row mb-4">
    <div class="col-12">
        <h2>Gestion des Clients</h2>
    </div>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Adresse</th>
            <th>Date d'inscription</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($clients)): ?>
            <?php foreach($clients as $client): ?>
                <tr>
                    <td><?php echo $client['id']; ?></td>
                    <td><?php echo $client['name']; ?></td>
                    <td><?php echo $client['email'] ?? '-'; ?></td>
                    <td><?php echo $client['phone']; ?></td>
                    <td><?php echo $client['address']; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($client['created_at'])); ?></td>
                    <td>
                        <a href="index.php?page=admin&action=deleteClient&id=<?php echo $client['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce client ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">Aucun client trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once "../app/views/layout/footer.php"; ?>
