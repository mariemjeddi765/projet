<?php require_once "../app/views/layout/header.php"; ?>

<h2>Ventes</h2>

<?php if(!empty($sales)): ?>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Total</th>
            <th>Mode de paiement</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($sales as $sale): ?>
        <tr>
            <td><?= $sale['id'] ?></td>
            <td><?= $sale['client_name'] ?></td>
            <td><?= number_format($sale['total_price'], 2, ',', ' ') ?> DT</td>
            <td><?= $sale['payment_method'] ?></td>
            <td><?= $sale['status'] ?></td>
            <td><?= $sale['created_at'] ?></td>
            <td>
                <?php if($sale['status'] === 'en_attente'): ?>
                    <a href="index.php?page=admin&action=validateSale&id=<?= $sale['id'] ?>" class="btn btn-success btn-sm">Valider</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p>Aucune vente pour le moment.</p>
<?php endif; ?>

<?php require_once "../app/views/layout/footer.php"; ?>
