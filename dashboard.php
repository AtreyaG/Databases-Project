<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}
require_once 'user_info.php';
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
                <li><a href="event_form.php"><span class="nav-icon">&#128197;</span> Events</a></li>
                <li><a href="event_signup.php"><span class="nav-icon">&#9997;</span> Event Signup</a></li>
                <li><a href="fam_management.php"><span class="nav-icon">&#128101;</span> Fam Management</a></li>
                <li><a href="reports.php"><span class="nav-icon">&#128202;</span> Reports</a></li>
            </ul>
        </nav>
        <div class="sidebar-user">
            <div class="user-avatar"><?= $user_initials ?></div>
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($user_first . ' ' . $user_last) ?></div>
                <div class="user-role">Officer</div>
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

        <!-- Stats Cards -->
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

        <!-- Upcoming Events -->
        <h2 class="section-title">Upcoming Events</h2>

        <div class="event-card">
            <h3>Boba Run &#129483;</h3>
            <p class="event-description">Fam bonding over boba tea!</p>
            <div class="event-meta">
                <span>&#128197; Apr 17, 2026</span>
                <span>&#128205; Tea House Downtown</span>
                <span>&#128101; 20/25</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 80%;"></div>
            </div>
        </div>

        <div class="event-card">
            <h3>Dumpling Night &#129�;</h3>
            <p class="event-description">Learn to fold and cook dumplings together!</p>
            <div class="event-meta">
                <span>&#128197; Apr 24, 2026</span>
                <span>&#128205; Community Kitchen</span>
                <span>&#128101; 38/40</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 95%;"></div>
            </div>
        </div>

        <div class="event-card">
            <h3>Karaoke Social &#127908;</h3>
            <p class="event-description">Sing your heart out at our fam karaoke night.</p>
            <div class="event-meta">
                <span>&#128197; May 1, 2026</span>
                <span>&#128205; KTV Lounge</span>
                <span>&#128101; 15/30</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 50%;"></div>
            </div>
        </div>

        <!-- Fam Overview -->
        <h2 class="section-title" style="margin-top: 40px;">Fam Overview</h2>
        <div class="fam-cards">
            <div class="fam-card">
                <div class="fam-card-header">
                    <span class="fam-dot red"></span> Dragon Fam &#128009;
                </div>
                <div class="fam-count">4</div>
                <div class="fam-label">members assigned</div>
            </div>
            <div class="fam-card">
                <div class="fam-card-header">
                    <span class="fam-dot orange"></span> Phoenix Fam &#128293;
                </div>
                <div class="fam-count">2</div>
                <div class="fam-label">members assigned</div>
            </div>
            <div class="fam-card">
                <div class="fam-card-header">
                    <span class="fam-dot green"></span> Tiger Fam &#128047;
                </div>
                <div class="fam-count">2</div>
                <div class="fam-label">members assigned</div>
            </div>
            <div class="fam-card">
                <div class="fam-card-header">
                    <span class="fam-dot blue"></span> Panda Fam &#128060;
                </div>
                <div class="fam-count">2</div>
                <div class="fam-label">members assigned</div>
            </div>
        </div>

        <footer>
            <p>FamHub &copy; 2026 | CS 4347 Database Systems Project</p>
        </footer>
    </main>
</body>
</html>
