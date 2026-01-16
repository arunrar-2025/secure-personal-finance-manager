<?php require __DIR__ . '/layout/header.php'; ?>

<h2 class="mb-4">Reports</h2>

<!-- Month Selector -->
<form method="get" class="mb-3">
    <input type="hidden" name="route" value="reports">
    <input type="month" name="month" value="<?= htmlspecialchars($monthYear) ?>" onchange="this.form.submit()">
</form>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-bg-success shadow-sm">
            <div class="card-body">
                <h6>Total Income</h6>
                <h4><?= htmlspecialchars($summary['income']) ?></h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-bg-danger shadow-sm">
            <div class="card-body">
                <h6>Total Expense</h6>
                <h4><?= htmlspecialchars($summary['expense']) ?></h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-bg-primary shadow-sm">
            <div class="card-body">
                <h6>Savings</h6>
                <h4><?= htmlspecialchars($summary['savings']) ?>
            </div>
        </div>
    </div>
</div>

<!-- Category Breakdown -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Category</th>
            <th>Total Spent</th>
        </tr>
    </thead>
    <tbody>

        <?php if (empty($categories)): ?>
            <tr>
                <td colspan="2" class="text-center">No data for this month</td>
            </tr>
        <?php else: ?>
            <?php foreach ($categories as $cat => $total): ?>
                <tr>
                    <td><?= htmlspecialchars($cat) ?></td>
                    <td><?= htmlspecialchars($total) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>

    </tbody>
</table>

<?php require __DIR__ . '/layout/footer.php'; ?>