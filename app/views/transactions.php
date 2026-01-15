<?php require __DIR__ . '/layout/header.php'; ?>

<h2 class="mb-4">Transactions</h2>

<!-- Account Selector -->
<form method="get" class="mb-3">
    <input type="hidden" name="route" value="transactions">
    <select name="account" class="form-select" onchange="this.form.submit()">
        <?php foreach ($accounts as $acc): ?>
            <option value="<?= $acc['id'] ?>"
                <?= ($selectedAccountId == $acc['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($acc['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<!-- Transaction Form -->
<div class="card mb-4">
    <div class="card-body">

        <h5 class="mb-3">Add Transaction</h5>

        <form method="post">
            <input type="hidden" name="account_id" value="<?= htmlspecialchars($selectedAccountId) ?>">

            <div class="row">
                <div class="col-md-3 mb-2">
                    <input class="form-control" name="date" type="date" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="col-md-3 mb-2">
                    <input class="form-control" name="category" placeholder="Category" required>
                </div>

                <div class="col-md-3 mb-2">
                    <input class="form-control" name="amount" placeholder="Amount (use negative for expense)" required>
                </div>

                <div class="col-md-3 mb-2">
                    <input class="form-control" name="note" placeholder="Note (optional)">
                </div>
            </div>

            <button class="btn btn-primary mt-2">Add Transaction</button>
        </form>
    </div>
</div>

<!-- Transactions Table -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Date</th>
            <th>Category</th>
            <th>Amount</th>
            <th>Note</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($transactions)): ?>
            <tr>
                <td colspan="4" class="text-center">No transactions yet</td>
            </tr>
        <?php else: ?>
            <?php foreach ($transactions as $txn): ?>
                <tr>
                    <td><?= htmlspecialchars($txn['transaction_date']) ?></td>
                    <td><?= htmlspecialchars($txn['category']) ?></td>
                    <td><?= htmlspecialchars($txn['amount']) ?></td>
                    <td><?= htmlspecialchars($txn['note']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        
    </tbody>
</table>

<?php require __DIR__ . '/layout/footer.php'; ?>