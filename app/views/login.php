<?php require __DIR__ . '/layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                
                <h4 class="card-title mb-3">Login</h4>

                <form method="post" action="?route=login_submit">
                    <input class="form-control mb-2" type="email" name="email" placeholder="Email" required>
                    <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
                    <button class="btn btn-primary w-100">Login</button>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>