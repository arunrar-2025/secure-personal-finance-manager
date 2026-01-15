<?php require __DIR__ . '/layout/header.php'; ?>

<h2 class="mb-4">Accounts</h2>

<div class="card mb-4">
  <div class="card-body">

    <h5 class="mb-3">Add New Account</h5>

    <form method="post">
      <div class="row">
        <div class="col-md-4 mb-2">
          <input class="form-control" name="name" placeholder="Account Name" required>
        </div>

        <div class="col-md-3 mb-2">
          <select class="form-control" name="type" required>
            <option value="">Select Type</option>
            <option value="bank">Bank</option>
            <option value="cash">Cash</option>
            <option value="wallet">Wallet</option>
            <option value="crypto">Crypto</option>
          </select>
        </div>

        <div class="col-md-3 mb-2">
          <input class="form-control" name="balance" placeholder="Opening Balance" required>
        </div>

        <div class="col-md-2 mb-2">
          <button class="btn btn-primary w-100">Add</button>
        </div>
      </div>
    </form>

  </div>
</div>

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Name</th>
      <th>Type</th>
      <th>Balance</th>
    </tr>
  </thead>
  <tbody>

    <?php if (empty($accounts)): ?>
      <tr>
        <td colspan="3" class="text-center">No accounts yet</td>
      </tr>
    <?php else: ?>
      <?php foreach ($accounts as $acc): ?>
        <tr>
          <td><?= htmlspecialchars($acc['name']) ?></td>
          <td><?= htmlspecialchars($acc['type']) ?></td>
          <td><?= htmlspecialchars($acc['balance']) ?></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>

  </tbody>
</table>

<?php require __DIR__ . '/layout/footer.php'; ?>
