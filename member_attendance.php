<?php
session_start();
if (!isset($_SESSION['member_logged_in'])) {
    header('Location: login.php');
    exit();
}
require_once 'user_info.php';

$stmt = $conn->prepare("
    SELECT e.event_name, e.event_date, e.location, a.attended
    FROM attendance a
    JOIN event e ON a.event_id = e.event_id
    WHERE a.net_id = ?
    ORDER BY e.event_date DESC
");
$stmt->bind_param("s", $_SESSION['net_id']);
$stmt->execute();
$history = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance - FamHub</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h1>FamHub</h1>
            <p>Cultural Student Association</p>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="dashboard.php"><span class="nav-icon">&#9776;</span> Dashboard</a></li>
                <li><a href="event_signup.php"><span class="nav-icon">&#9997;</span> Events</a></li>
                <li><a href="member_attendance.php" class="active"><span class="nav-icon">&#128200;</span> My Attendance</a></li>
            </ul>
        </nav>
        <div class="sidebar-user">
            <div class="user-avatar"><?= $user_initials ?></div>
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($user_first . ' ' . $user_last) ?></div>
                <div class="user-role">Member</div>
            </div>
        </div>
        <div class="sidebar-signout">
            <a href="logout.php">&#8592; Sign out</a>
        </div>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h1>My Attendance</h1>
            <p>Your event history</p>
        </div>

        <?php if (empty($history)): ?>
            <p style="color: var(--text-light); font-size: 14px;">You haven't signed up for any events yet. <a href="event_signup.php">Browse events</a></p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['event_name']) ?></td>
                    <td><?= htmlspecialchars(date('M j, Y', strtotime($row['event_date']))) ?></td>
                    <td><?= htmlspecialchars($row['location'] ?? '—') ?></td>
                    <td>
                        <?php if ($row['attended']): ?>
                            <span class="badge" style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;">&#10003; Attended</span>
                        <?php else: ?>
                            <span class="badge badge-member">Signed up</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <footer>
            <p>FamHub &copy; 2026 | CS 4347 Database Systems Project</p>
        </footer>
    </main>
</body>
</html>
