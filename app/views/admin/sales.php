<?php require_once "../app/views/layout/header.php"; ?>

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
                                <td><?php echo $item['quan]()
