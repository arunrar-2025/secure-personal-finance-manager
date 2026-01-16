<?php require __DIR__ . '/layout/header.php'; ?>

<h2 class="mb-4">Create Account</h2>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">

                <form method="post" action="?route=register_submit">
                    <input class="form-control mb-3" type="email" name="email" placeholder="Email" required>
                    <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
                    <input class="form-control mb-4" type="password" name="confirm_password" placeholder="Confirm Password" required>
                    <button class="btn btn-primary w-100">Register</button>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>