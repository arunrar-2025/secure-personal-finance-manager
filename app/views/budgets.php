<?php require __DIR__ . '/layout/header.php'; ?>

<h2 class="mb-4">Budgets (<?= htmlspecialchars($monthYear) ?>)</h2>

<!-- Add Budget -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-3">Set Budget</h5>

        <form method="post">
            <div class="row">
                <div class="col-md-5 mb-2">
                    <input class="form-control" name="category" placeholder="Category" required>
                </div>

                <div class="col-md-5 mb-2">
                    <input class="form-control" name="limit" placeholder="Monthly Limit" required>
                </div>

                <div class="col-md-2 mb-2">
                    <button class="btn btn-primary w-100">Save</button>
                </div>
            </div>
        </form>

    </div>
</div>

<!-- Budget List -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Category</th>
            <th>Limit</th>
            <th>Spent</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>

    <?php if (empty($budgets)): ?>
        <tr>
            <td colspan="4" class="text-center">No budgets set</td>
        </tr>
    <?php else: ?>
        <?php foreach ($budgets as $b): ?>
            <tr>
                <td><?= htmlspecialchars($b['category']) ?></td>
                <td><?= htmlspecialchars($b['limit']) ?></td>
                <td><?= htmlspecialchars($b['spent']) ?></td>
                <td>
                    <?php if (abs($b['spent']) > $b['limit']): ?>
                        <span class="badge bg-danger">Over Budget</span>
                    <?php else: ?>
                        <span class="badge bg-success">Within Budget</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>

    </tbody>
</table>

<?php require __DIR__ . '/layout/footer.php'; ?>