<?php require_once "../app/views/layout/header.php"; ?>

<div class="site-panel">
    <div class="row mb-4">
        <div class="col-12">
            <h2>Gestion des Ventes</h2>
        </div>
    </div>

    <?php if (!empty($sales)): ?>
        <?php foreach ($sales as $sale): ?>
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Vente #<?php echo $sale['id']; ?> - <?php echo $sale['status']; ?></strong>
                    <span><?php echo date('d/m/Y H:i', strtotime($sale['created_at'])); ?></span>
                </div>
                <div class="card-body">
                    <h5>Client :</h5>
                    <p>
                        <?php echo $sale['client_name']; ?><br>
                        Email : <?php echo $sale['email']; ?><br>
                        Téléphone : <?php echo $sale['phone']; ?><br>
                        Adresse : <?php echo $sale['address']; ?>
                    </p>

                    <h5>Articles :</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $saleTotal = 0; ?>
                                <?php foreach ($sale['items'] as $item): ?>
                                    <?php $total = $item['price'] * $item['quantity']; ?>
                                    <?php $saleTotal += $total; ?>
                                    <tr>
                                        <td><?php echo $item['product_name']; ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><?php echo number_format($item['price'],2,',',' '); ?> DT</td>
                                        <td><?php echo number_format($total,2,',',' '); ?> DT</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <?php if ($sale['status'] !== 'validated'): ?>
                                <a href="index.php?page=admin&action=validateSale&id=<?php echo $sale['id']; ?>" class="btn btn-primary">Valider</a>
                            <?php endif; ?>
                        </div>
                        <h5>Total : <?php echo number_format($saleTotal,2,',',' '); ?> DT</h5>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune vente trouvée.</p>
    <?php endif; ?>
</div>

<?php require_once "../app/views/layout/footer.php"; ?>
