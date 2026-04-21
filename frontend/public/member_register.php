<?php
session_start();
if (isset($_SESSION['logged_in']) || isset($_SESSION['member_logged_in'])) {
    header('Location: dashboard.php');
    exit();
}
$error   = $_GET['error'] ?? '';
$success = isset($_GET['success']);
$error_messages = [
    'no_member'          => 'No member found with that Net ID. Please double-check your Net ID.',
    'already_registered' => 'This Net ID already has an account. Try signing in instead.',
    'password_mismatch'  => 'Passwords do not match. Please try again.',
    'empty_fields'       => 'All fields are required.',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FamHub</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="login-page">
    <div class="login-left">
        <h1>FamHub</h1>
        <p>Join your fam. Track your events. Stay connected.</p>
        <div class="login-emojis">
            <span>&#129483;</span>
            <span>&#127861;</span>
            <span>&#127836;</span>
            <span>&#129390;</span>
        </div>
    </div>

    <div class="login-right">
        <div class="login-form-container">
            <h2>Create account</h2>
            <p class="login-subtitle">Register with your UTD Net ID</p>

            <?php if ($success): ?>
                <div class="login-success">
                    Account created! <a href="login.php?tab=member">Sign in as a member &rarr;</a>
                </div>
            <?php else: ?>
                <?php if ($error && isset($error_messages[$error])): ?>
                    <p class="login-error"><?= htmlspecialchars($error_messages[$error]) ?></p>
                <?php endif; ?>

                <form action="member_register_process.php" method="POST">
                    <label for="net_id">Net ID</label>
                    <input type="text" id="net_id" name="net_id" placeholder="e.g. DAL030871" required>

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>

                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat your password" required>

                    <div style="margin-top: 24px;">
                        <input type="submit" value="Create account" style="width: 100%; padding: 12px;">
                    </div>
                </form>

                <div class="login-footer">
                    Already have an account? <a href="login.php?tab=member">Sign in</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
