<?php
session_start();
if (!isset($_SESSION['logged_in']) && !isset($_SESSION['member_logged_in'])) {
    header('Location: login.php');
    exit();
}
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $event_id = (int)$_POST['event_id'];
    $stmt = $conn->prepare("INSERT IGNORE INTO attendance (event_id, net_id) VALUES (?, ?)");
    $stmt->bind_param("is", $event_id, $_SESSION['net_id']);
    $stmt->execute();
    $stmt->close();
    header('Location: event_signup.php');
    exit();
}

require_once 'user_info.php';
$is_officer = isset($_SESSION['logged_in']);

$ev_stmt = $conn->prepare("
    SELECT e.*,
           (SELECT COUNT(*) FROM attendance WHERE event_id = e.event_id AND net_id = ?) AS signed_up,
           (SELECT COUNT(*) FROM attendance WHERE event_id = e.event_id) AS signup_count
    FROM event e
    ORDER BY e.event_date ASC
");
$ev_stmt->bind_param("s", $_SESSION['net_id']);
$ev_stmt->execute();
$events = $ev_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$ev_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Signup - FamHub</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h1>FamHub</h1>
            <p>Cultural Student Association</p>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="dashboard.php"><span class="nav-icon">&#9776;</span> Dashboard</a></li>
                <?php if ($is_officer): ?>
                <li><a href="event_form.php"><span class="nav-icon">&#128197;</span> Events</a></li>
                <li><a href="event_signup.php" class="active"><span class="nav-icon">&#9997;</span> Event Signup</a></li>
                <li><a href="fam_management.php"><span class="nav-icon">&#128101;</span> Fam Management</a></li>
                <li><a href="reports.php"><span class="nav-icon">&#128202;</span> Reports</a></li>
                <?php else: ?>
                <li><a href="event_signup.php" class="active"><span class="nav-icon">&#9997;</span> Events</a></li>
                <li><a href="member_attendance.php"><span class="nav-icon">&#128200;</span> My Attendance</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="sidebar-user">
            <div class="user-avatar"><?= $user_initials ?></div>
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($user_first . ' ' . $user_last) ?></div>
                <div class="user-role"><?= $is_officer ? 'Officer' : 'Member' ?></div>
            </div>
        </div>
        <div class="sidebar-signout">
            <a href="logout.php">&#8592; Sign out</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1>Event Signup</h1>
            <p>Register for events</p>
        </div>

        <?php if (empty($events)): ?>
            <p style="color: var(--text-light); font-size: 14px;">No events found.</p>
        <?php else: foreach ($events as $ev): ?>
        <div class="event-card">
            <div class="event-card-header">
                <h3><?= htmlspecialchars($ev['event_name']) ?></h3>
                <?php if ($ev['signed_up']): ?>
                    <span class="badge" style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;padding:6px 14px;font-size:13px;">&#10003; Signed Up</span>
                <?php else: ?>
                    <form method="POST" style="margin: 0;">
                        <input type="hidden" name="event_id" value="<?= $ev['event_id'] ?>">
                        <button type="submit" class="btn btn-primary btn-sm">Sign Up</button>
                    </form>
                <?php endif; ?>
            </div>
            <?php if ($ev['description']): ?>
                <p class="event-description"><?= htmlspecialchars($ev['description']) ?></p>
            <?php endif; ?>
            <div class="event-meta">
                <span>&#128197; <?= htmlspecialchars(date('M j, Y', strtotime($ev['event_date']))) ?></span>
                <?php if ($ev['location']): ?><span>&#128205; <?= htmlspecialchars($ev['location']) ?></span><?php endif; ?>
                <?php if ($ev['start_time']): ?><span>&#128336; <?= htmlspecialchars(date('g:i A', strtotime($ev['start_time']))) ?></span><?php endif; ?>
                <?php if ($ev['capacity']): ?>
                    <span>&#128101; <?= $ev['signup_count'] ?>/<?= $ev['capacity'] ?></span>
                <?php else: ?>
                    <span>&#128101; <?= $ev['signup_count'] ?> signed up</span>
                <?php endif; ?>
            </div>
            <?php if ($ev['capacity']): ?>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?= min(100, round($ev['signup_count'] / $ev['capacity'] * 100)) ?>%;"></div>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; endif; ?>

        <footer>
            <p>FamHub &copy; 2026 | CS 4347 Database Systems Project</p>
        </footer>
    </main>
</body>
</html>
