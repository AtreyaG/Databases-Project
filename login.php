<?php
session_start();
if (isset($_SESSION['logged_in'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FamHub</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="login-page">

    <div class="login-left">
        <h1>FamHub</h1>
        <p>Manage your fams, plan events, and build community &mdash; all in one place.</p>
        <div class="login-emojis">
            <span>&#129483;</span>
            <span>&#127861;</span>
            <span>&#127836;</span>
            <span>&#129390;</span>
        </div>
    </div>

    <!-- Login Form -->
    <div class="login-right">
        <div class="login-form-container">
            <h2>Welcome back</h2>
            <p class="login-subtitle">Sign in to your account</p>

            <?php if (isset($_GET['error'])): ?>
                <p class="login-error">Invalid Net ID or password.</p>
            <?php endif; ?>

            <form action="login_process.php" method="POST">
                <label for="net_id">Net ID</label>
                <input type="text" id="net_id" name="net_id" placeholder="e.g. SQN200001" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>

                <div style="margin-top: 24px;">
                    <input type="submit" value="Sign in" style="width: 100%; padding: 12px;">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
