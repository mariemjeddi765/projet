<?php require_once "../app/views/layout/header.php"; ?>

<div class="site-panel">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Statistiques des ventes</h2>
        <div>
            <a href="index.php?page=admin&action=dashboard" class="btn btn-outline-secondary">Retour</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Total ventes (6 mois)</h5>
                <p class="display-6"><?php echo number_format($totalSales,2,',',' '); ?> TND</p>
                <small><?php echo $totalOrders; ?> commandes</small>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-3">
                <canvas id="salesLineChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Répartition par moyen de paiement</h5>
                <canvas id="paymentPieChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Détails par mois</h5>
                <table class="table table-sm">
                    <thead>
                        <tr><th>Mois</th><th>Commandes</th><th>CA (TND)</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($labels as $i => $lab): ?>
                            <tr>
                                <td><?php echo htmlentities($lab); ?></td>
                                <td><?php echo $counts[$i]; ?></td>
                                <td><?php echo number_format($totals[$i],2,',',' '); ?> TND</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?php echo json_encode($labels); ?>;
const totals = <?php echo json_encode($totals); ?>;
const counts = <?php echo json_encode($counts); ?>;
const paymentLabels = <?php echo json_encode($payment_labels); ?>;
const paymentValues = <?php echo json_encode($payment_values); ?>;

// Line chart for sales
new Chart(document.getElementById('salesLineChart'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: "Chiffre d'affaires (TND)",
            data: totals,
            borderColor: '#b07a5a',
            backgroundColor: 'rgba(176,122,90,0.12)',
            tension: 0.25,
            fill: true
        }]
    },
    options: { responsive:true, plugins:{legend:{display:false}} }
});

// Pie chart for payment methods
new Chart(document.getElementById('paymentPieChart'), {
    type: 'pie',
    data: {
        labels: paymentLabels,
        datasets: [{
            data: paymentValues,
            backgroundColor: ['#c9a78e','#e9d7c4','#8fb196','#b07a5a']
        }]
    },
    options: { responsive:true }
});
</script>

<?php require_once "../app/views/layout/footer.php"; ?>
