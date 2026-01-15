<?php require __DIR__ . '/layout/header.php'; ?>

<h2 class="mb-4">Dashboard</h2>

<div class="row">
    <div class="col-md-3">
        <div class="card text-bg-primary mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Accounts</h5>
                <p class="card-text">Manage financial accounts</p>
                <a href="?route=accounts" class="btn btn-light btn-sm">Open</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-bg-success mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Transactions</h5>
                <p class="card-text">View and add transactions</p>
                <a href="?route=transactions" class="btn btn-light btn-sm">Open</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-bg-warning mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Budgets</h5>
                <p class="card-text">Track monthly budgets</p>
                <a href="?route=budgets" class="btn btn-light btn-sm">Open</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-bg-dark mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Reports</h5>
                <p class="card-text">View financial reports</p>
                <a href="?route=reports" class="btn btn-light btn-sm">Open</a>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>