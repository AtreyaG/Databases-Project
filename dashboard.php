<?php
session_start();
if (!isset($_SESSION['logged_in']) && !isset($_SESSION['member_logged_in'])) {
    header('Location: login.php');
    exit();
}
$is_officer = isset($_SESSION['logged_in']);
require_once 'user_info.php';

if (!$is_officer) {
    // Fam info
    $fam_stmt = $conn->prepare("
        SELECT f.fam_name, f.member_count,
               GROUP_CONCAT(m2.first_name, ' ', m2.last_name ORDER BY m2.last_name SEPARATOR ', ') AS fam_heads
        FROM member m
        JOIN family f ON m.fam_id = f.fam_id
        LEFT JOIN fam_head fh ON fh.fam_id = f.fam_id
            AND (fh.end_date IS NULL OR fh.end_date >= CURDATE())
        LEFT JOIN member m2 ON m2.net_id = fh.net_id
        WHERE m.net_id = ?
        GROUP BY f.fam_id
    ");
    $fam_stmt->bind_param("s", $_SESSION['net_id']);
    $fam_stmt->execute();
    $fam_info = $fam_stmt->get_result()->fetch_assoc();
    $fam_stmt->close();

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
                <div class="stat-value">47</div>
                <div class="stat-sub">Across 4 fams</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">&#128197;</div>
                <h4>Upcoming Events</h4>
                <div class="stat-value">3</div>
                <div class="stat-sub">This month</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">&#128200;</div>
                <h4>Avg. Attendance</h4>
                <div class="stat-value">78%</div>
                <div class="stat-sub">+5% from last semester</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">&#127942;</div>
                <h4>Active Fams</h4>
                <div class="stat-value">4</div>
                <div class="stat-sub">All competing</div>
            </div>
        </div>

        <h2 class="section-title">Upcoming Events</h2>

        <div class="event-card">
            <h3>Boba Run &#129483;</h3>
            <p class="event-description">Fam bonding over boba tea!</p>
            <div class="event-meta">
                <span>&#128197; Apr 17, 2026</span>
                <span>&#128205; Tea House Downtown</span>
                <span>&#128101; 20/25</span>
            </div>
            <div class="progress-bar"><div class="progress-fill" style="width: 80%;"></div></div>
        </div>
        <div class="event-card">
            <h3>Dumpling Night &#129377;</h3>
            <p class="event-description">Learn to fold and cook dumplings together!</p>
            <div class="event-meta">
                <span>&#128197; Apr 24, 2026</span>
                <span>&#128205; Community Kitchen</span>
                <span>&#128101; 38/40</span>
            </div>
            <div class="progress-bar"><div class="progress-fill" style="width: 95%;"></div></div>
        </div>
        <div class="event-card">
            <h3>Karaoke Social &#127908;</h3>
            <p class="event-description">Sing your heart out at our fam karaoke night.</p>
            <div class="event-meta">
                <span>&#128197; May 1, 2026</span>
                <span>&#128205; KTV Lounge</span>
                <span>&#128101; 15/30</span>
            </div>
            <div class="progress-bar"><div class="progress-fill" style="width: 50%;"></div></div>
        </div>

        <h2 class="section-title" style="margin-top: 40px;">Fam Overview</h2>
        <div class="fam-cards">
            <div class="fam-card">
                <div class="fam-card-header"><span class="fam-dot red"></span> Dragon Fam &#128009;</div>
                <div class="fam-count">4</div>
                <div class="fam-label">members assigned</div>
            </div>
            <div class="fam-card">
                <div class="fam-card-header"><span class="fam-dot orange"></span> Phoenix Fam &#128293;</div>
                <div class="fam-count">2</div>
                <div class="fam-label">members assigned</div>
            </div>
            <div class="fam-card">
                <div class="fam-card-header"><span class="fam-dot green"></span> Tiger Fam &#128047;</div>
                <div class="fam-count">2</div>
                <div class="fam-label">members assigned</div>
            </div>
            <div class="fam-card">
                <div class="fam-card-header"><span class="fam-dot blue"></span> Panda Fam &#128060;</div>
                <div class="fam-count">2</div>
                <div class="fam-label">members assigned</div>
            </div>
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
