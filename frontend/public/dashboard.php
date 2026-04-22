<?php
session_start();
if (!isset($_SESSION['logged_in']) && !isset($_SESSION['member_logged_in'])) {
    header('Location: login.php');
    exit();
}
$is_officer = isset($_SESSION['logged_in']);
require_once '../user_info.php';

if ($is_officer) {
    $total_members = $conn->query("SELECT COUNT(*) FROM member")->fetch_row()[0];
    
    $total_fams = $conn->query("SELECT COUNT(*) FROM family")->fetch_row()[0];
    
    $upcoming_events_count = $conn->query("SELECT COUNT(*) FROM event WHERE event_date >= CURDATE() AND event_date <= LAST_DAY(CURDATE())")->fetch_row()[0];
    
    $officer_events = $conn->query("
        SELECT e.*, 
               (SELECT COUNT(*) FROM attendance WHERE event_id = e.event_id) as signup_count
        FROM event e 
        WHERE e.event_date >= CURDATE() 
        ORDER BY e.event_date ASC 
        LIMIT 3
    ")->fetch_all(MYSQLI_ASSOC);

    $fam_overview = $conn->query("SELECT * FROM family ORDER BY fam_name ASC")->fetch_all(MYSQLI_ASSOC);
    $avg_attendance_query = $conn->query(
        "SELECT ROUND(AVG(ratio), 1) AS avg_rate FROM (" .
        " SELECT CASE WHEN e.capacity IS NULL OR e.capacity = 0 THEN 0 " .
        " ELSE LEAST(100, COUNT(a.net_id) / e.capacity * 100) END AS ratio " .
        " FROM event e " .
        " LEFT JOIN attendance a ON a.event_id = e.event_id " .
        " WHERE e.event_date >= CURDATE() " .
        " GROUP BY e.event_id" .
        ") AS attendance_rates"
    );
    $avg_attendance = $avg_attendance_query->fetch_assoc()['avg_rate'] ?? 0;
} else {
    // Fam info
    $fam_stmt = $conn->prepare("
        SELECT f.fam_id, f.fam_name, f.member_count
        FROM member m
        JOIN family f ON m.fam_id = f.fam_id
        WHERE m.net_id = ?
    ");
    $fam_stmt->bind_param("s", $_SESSION['net_id']);
    $fam_stmt->execute();
    $fam_info = $fam_stmt->get_result()->fetch_assoc();
    $fam_stmt->close();

    if ($fam_info) {
        // Fetch fam heads separately to avoid GROUP_CONCAT
        $heads_stmt = $conn->prepare("
            SELECT m.first_name, m.last_name
            FROM fam_head fh
            JOIN member m ON fh.net_id = m.net_id
            WHERE fh.fam_id = ? 
              AND (fh.end_date IS NULL OR fh.end_date >= CURDATE())
        ");
        $heads_stmt->bind_param("i", $fam_info['fam_id']);
        $heads_stmt->execute();
        $heads_result = $heads_stmt->get_result();
        $fam_heads_list = [];
        while ($h = $heads_result->fetch_assoc()) {
            $fam_heads_list[] = $h['first_name'] . ' ' . $h['last_name'];
        }
        $fam_info['fam_heads'] = !empty($fam_heads_list) ? implode(', ', $fam_heads_list) : 'None assigned';
        $heads_stmt->close();
    }

    // Recent events (last 5)
    $ev_stmt = $conn->prepare("
        SELECT e.*,
               (SELECT COUNT(*) FROM attendance WHERE event_id = e.event_id AND net_id = ?) AS signed_up
        FROM event e
        ORDER BY e.event_date DESC
        LIMIT 5
    ");
    $ev_stmt->bind_param("s", $_SESSION['net_id']);
    $ev_stmt->execute();
    $member_events = $ev_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $ev_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FamHub</title>
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
                <li><a href="dashboard.php" class="active"><span class="nav-icon">&#9776;</span> Dashboard</a></li>
                <?php if ($is_officer): ?>
                <li><a href="event_form.php"><span class="nav-icon">&#128197;</span> Events</a></li>
                <li><a href="event_signup.php"><span class="nav-icon">&#9997;</span> Event Signup</a></li>
                <li><a href="fam_management.php"><span class="nav-icon">&#128101;</span> Fam Management</a></li>
                <li><a href="reports.php"><span class="nav-icon">&#128202;</span> Reports</a></li>
                <?php else: ?>
                <li><a href="event_signup.php"><span class="nav-icon">&#9997;</span> Events</a></li>
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
            <h1>Dashboard</h1>
            <p class="welcome-msg">Welcome back, <?= htmlspecialchars($user_first) ?> &#128075;</p>
        </div>

        <?php if ($is_officer): ?>
        <!-- Officer View -->
        <div class="stat-cards">
            <div class="stat-card">
                <div class="stat-icon">&#128101;</div>
                <h4>Total Members</h4>
                <div class="stat-value"><?= $total_members ?></div>
                <div class="stat-sub">Across <?= $total_fams ?> fams</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">&#128197;</div>
                <h4>Upcoming Events</h4>
                <div class="stat-value"><?= $upcoming_events_count ?></div>
                <div class="stat-sub">This month</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">&#128200;</div>
                <h4>Avg. Attendance</h4>
                <div class="stat-value"><?= $avg_attendance ?>%</div>
                <div class="stat-sub">Based on signup rates</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">&#127942;</div>
                <h4>Active Fams</h4>
                <div class="stat-value"><?= $total_fams ?></div>
                <div class="stat-sub">With members</div>
            </div>
        </div>

        <h2 class="section-title">Upcoming Events</h2>

        <?php if (empty($officer_events)): ?>
            <p style="color: var(--text-light); font-size: 14px;">No upcoming events found.</p>
        <?php else: foreach ($officer_events as $ev): 
            $prg = $ev['capacity'] > 0 ? min(100, round(($ev['signup_count'] / $ev['capacity']) * 100)) : 0;
        ?>
        <div class="event-card">
            <h3><?= htmlspecialchars($ev['event_name']) ?></h3>
            <p class="event-description"><?= htmlspecialchars($ev['description']) ?></p>
            <div class="event-meta">
                <span>&#128197; <?= date('M j, Y', strtotime($ev['event_date'])) ?></span>
                <span>&#128205; <?= htmlspecialchars($ev['location']) ?></span>
                <span>&#128101; <?= $ev['signup_count'] ?>/<?= $ev['capacity'] ?: '∞' ?></span>
            </div>
            <div class="progress-bar"><div class="progress-fill" style="width: <?= $prg ?>%;"></div></div>
        </div>
        <?php endforeach; endif; ?>

        <h2 class="section-title" style="margin-top: 40px;">Fam Overview</h2>
        <div class="fam-cards">
            <?php foreach ($fam_overview as $f): 
                $dot_colors = ['red', 'orange', 'green', 'blue'];
                $color = $dot_colors[$f['fam_id'] % 4];
            ?>
            <div class="fam-card">
                <div class="fam-card-header"><span class="fam-dot <?= $color ?>"></span> <?= htmlspecialchars($f['fam_name']) ?></div>
                <div class="fam-count"><?= $f['member_count'] ?></div>
                <div class="fam-label">members assigned</div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>
        <!-- Member View -->
        <?php if ($fam_info): ?>
        <div style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 28px; margin-bottom: 32px;">
            <h2 class="section-title" style="margin-top:0;">My Fam</h2>
            <div style="font-size: 22px; font-weight: 700; color: var(--text-dark); margin-bottom: 8px;">
                <?= htmlspecialchars($fam_info['fam_name']) ?>
            </div>
            <div style="display: flex; gap: 32px; margin-top: 16px;">
                <div>
                    <div style="font-size: 12px; color: var(--text-light); margin-bottom: 4px;">Members</div>
                    <div style="font-size: 28px; font-weight: 700; color: var(--text-dark);"><?= $fam_info['member_count'] ?></div>
                </div>
                <div>
                    <div style="font-size: 12px; color: var(--text-light); margin-bottom: 4px;">Fam Heads</div>
                    <div style="font-size: 14px; color: var(--text); margin-top: 6px;">
                        <?= htmlspecialchars($fam_info['fam_heads'] ?? 'None assigned') ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <h2 class="section-title">Recent Events</h2>
        <?php if (empty($member_events)): ?>
            <p style="color: var(--text-light); font-size: 14px;">No events found.</p>
        <?php else: foreach ($member_events as $ev): ?>
        <div class="event-card">
            <div class="event-card-header">
                <h3><?= htmlspecialchars($ev['event_name']) ?></h3>
                <?php if ($ev['signed_up']): ?>
                    <span class="badge" style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;">&#10003; Signed Up</span>
                <?php endif; ?>
            </div>
            <?php if ($ev['description']): ?>
                <p class="event-description"><?= htmlspecialchars($ev['description']) ?></p>
            <?php endif; ?>
            <div class="event-meta">
                <span>&#128197; <?= htmlspecialchars(date('M j, Y', strtotime($ev['event_date']))) ?></span>
                <?php if ($ev['location']): ?><span>&#128205; <?= htmlspecialchars($ev['location']) ?></span><?php endif; ?>
            </div>
        </div>
        <?php endforeach; endif; ?>

        <div style="margin-top: 24px;">
            <a href="event_signup.php" class="btn btn-primary">View All Events &amp; Sign Up &#8594;</a>
        </div>
        <?php endif; ?>

        <footer>
            <p>FamHub &copy; 2026 | CS 4347 Database Systems Project</p>
        </footer>
    </main>
</body>
</html>
