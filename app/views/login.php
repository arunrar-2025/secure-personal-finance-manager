<?php
    // app/views/login.php
    require __DIR__ . '/layout/header.php';
?>
<h1>Login</h1>

<form method="post" action="?route=login_submit">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>

<?php
    require __DIR__ . '/layout/footer.php';
?>