<?php require __DIR__ . '/layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">

                <h3 class="card-title text-center mb-4">Secure Login</h3>

                <form method="post" action="?route=login_submit">
                    <input class="form-control mb-3" type="email" name="email" placeholder="Email" required>
                    <input class="form-control mb-4" type="password" name="password" placeholder="Password" required>
                    <button class="btn btn-primary w-100">Login</button>
                </form>
                
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>