<?php
session_start();
if (isset($_SESSION['logged_in']) || isset($_SESSION['member_logged_in'])) {
    header('Location: dashboard.php');
    exit();
}
$active_tab = (($_GET['tab'] ?? '') === 'member') ? 'member' : 'officer';
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

    <div class="login-right">
        <div class="login-form-container">
            <h2>Welcome back</h2>
            <p class="login-subtitle">Sign in to your account</p>

            <div class="login-tabs">
                <button class="login-tab <?= $active_tab === 'officer' ? 'active' : '' ?>" onclick="switchTab('officer')">Officer</button>
                <button class="login-tab <?= $active_tab === 'member'  ? 'active' : '' ?>" onclick="switchTab('member')">Member</button>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <p class="login-error">Invalid Net ID or password.</p>
            <?php endif; ?>

            <!-- Officer Panel -->
            <div class="login-panel <?= $active_tab === 'officer' ? 'active' : '' ?>" id="panel-officer">
                <form action="login_process.php" method="POST">
                    <input type="hidden" name="role" value="officer">
                    <label for="officer_net_id">Net ID</label>
                    <input type="text" id="officer_net_id" name="net_id" placeholder="e.g. AXG220155" required>

                    <label for="officer_password">Password</label>
                    <input type="password" id="officer_password" name="password" placeholder="Enter your password" required>

                    <div style="margin-top: 24px;">
                        <input type="submit" value="Sign in as Officer" style="width: 100%; padding: 12px;">
                    </div>
                </form>
            </div>

            <!-- Member Panel -->
            <div class="login-panel <?= $active_tab === 'member' ? 'active' : '' ?>" id="panel-member">
                <form action="login_process.php" method="POST">
                    <input type="hidden" name="role" value="member">
                    <label for="member_net_id">Net ID</label>
                    <input type="text" id="member_net_id" name="net_id" placeholder="e.g. DAL030871" required>

                    <label for="member_password">Password</label>
                    <input type="password" id="member_password" name="password" placeholder="Enter your password" required>

                    <div style="margin-top: 24px;">
                        <input type="submit" value="Sign in as Member" style="width: 100%; padding: 12px;">
                    </div>
                </form>
                <div class="login-footer">
                    No account yet? <a href="member_register.php">Register with your Net ID</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.login-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.login-panel').forEach(p => p.classList.remove('active'));
            document.querySelector('.login-tab:' + (tab === 'officer' ? 'first-child' : 'last-child')).classList.add('active');
            document.getElementById('panel-' + tab).classList.add('active');
        }
    </script>
</body>
</html>
