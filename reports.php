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
    <title>Reports &amp; Analytics - FamHub</title>
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
                <li><a href="event_form.php"><span class="nav-icon">&#128197;</span> Events</a></li>
                <li><a href="event_signup.php"><span class="nav-icon">&#9997;</span> Event Signup</a></li>
                <li><a href="fam_management.php"><span class="nav-icon">&#128101;</span> Fam Management</a></li>
                <li><a href="reports.php" class="active"><span class="nav-icon">&#128202;</span> Reports</a></li>
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
            <h1>Reports & Analytics</h1>
            <p>Track participation and engagement</p>
        </div>

        <!-- Charts Grid -->
        <div class="charts-grid">
            <div class="chart-card">
                <h3>Members per Fam</h3>
                <div class="chart-placeholder" id="pie-chart">
                    <!-- Inline SVG Pie Chart -->
                    <svg viewBox="0 0 200 200" width="200" height="200">
                        <circle cx="100" cy="100" r="90" fill="#c44a4a"/>
                        <path d="M100,100 L100,10 A90,90 0 0,1 177,145 Z" fill="#d4706e"/>
                        <path d="M100,100 L177,145 A90,90 0 0,1 100,190 Z" fill="#d4885a"/>
                        <path d="M100,100 L100,190 A90,90 0 0,1 23,145 Z" fill="#dbb86a"/>
                        <path d="M100,100 L23,145 A90,90 0 0,1 45,40 Z" fill="#8a9bab"/>
                    </svg>
                </div>
                <div style="display: flex; gap: 16px; justify-content: center; margin-top: 12px; font-size: 12px; color: var(--text-light);">
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#c44a4a;margin-right:4px;"></span>Dragon</span>
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#d4885a;margin-right:4px;"></span>Phoenix</span>
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#dbb86a;margin-right:4px;"></span>Tiger</span>
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#8a9bab;margin-right:4px;"></span>Panda</span>
                </div>
            </div>
            <div class="chart-card">
                <h3>Event Attendance Rate (%)</h3>
                <div class="chart-placeholder" id="bar-chart">
                    <!-- Inline SVG Bar Chart -->
                    <svg viewBox="0 0 300 200" width="300" height="200">
                        <!-- Y axis labels -->
                        <text x="25" y="20" font-size="10" fill="#9ca3af" text-anchor="end">100</text>
                        <text x="25" y="60" font-size="10" fill="#9ca3af" text-anchor="end">75</text>
                        <text x="25" y="100" font-size="10" fill="#9ca3af" text-anchor="end">50</text>
                        <text x="25" y="140" font-size="10" fill="#9ca3af" text-anchor="end">25</text>
                        <text x="25" y="180" font-size="10" fill="#9ca3af" text-anchor="end">0</text>
                        <!-- Grid lines -->
                        <line x1="30" y1="17" x2="290" y2="17" stroke="#e5e7eb" stroke-width="0.5"/>
                        <line x1="30" y1="57" x2="290" y2="57" stroke="#e5e7eb" stroke-width="0.5"/>
                        <line x1="30" y1="97" x2="290" y2="97" stroke="#e5e7eb" stroke-width="0.5"/>
                        <line x1="30" y1="137" x2="290" y2="137" stroke="#e5e7eb" stroke-width="0.5"/>
                        <line x1="30" y1="177" x2="290" y2="177" stroke="#e5e7eb" stroke-width="0.5"/>
                        <!-- Bars -->
                        <rect x="55" y="82" width="50" height="95" rx="4" fill="#c44a4a"/>
                        <rect x="125" y="97" width="50" height="80" rx="4" fill="#d4706e"/>
                        <rect x="195" y="17" width="50" height="160" rx="4" fill="#a63d3d"/>
                        <!-- X axis labels -->
                        <text x="80" y="195" font-size="9" fill="#9ca3af" text-anchor="middle">Mid-Autumn</text>
                        <text x="150" y="195" font-size="9" fill="#9ca3af" text-anchor="middle">Karaoke</text>
                        <text x="220" y="195" font-size="9" fill="#9ca3af" text-anchor="middle">Boba Run</text>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Potentially Inactive Members -->
        <div class="inactive-section">
            <h2 class="section-title">Potentially Inactive Members</h2>
            <p style="color: var(--text-light); font-size: 14px; margin-bottom: 20px;">Members who haven't signed up for any events recently.</p>

            <div class="inactive-cards">
                <div class="inactive-card">
                    <div class="member-initials" style="background: #c44a4a;">MC</div>
                    <div class="member-info">
                        <div class="member-name">Mei-Ling Chen</div>
                        <div class="member-fam">Dragon Fam &#128009;</div>
                    </div>
                </div>
                <div class="inactive-card">
                    <div class="member-initials" style="background: #d4885a;">RN</div>
                    <div class="member-info">
                        <div class="member-name">Ryan Nguyen</div>
                        <div class="member-fam">Phoenix Fam &#128293;</div>
                    </div>
                </div>
                <div class="inactive-card">
                    <div class="member-initials" style="background: #8a9bab;">FA</div>
                    <div class="member-info">
                        <div class="member-name">Fatima Al-Hassan</div>
                        <div class="member-fam">Tiger Fam &#128047;</div>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <p>FamHub &copy; 2026 | CS 4347 Database Systems Project</p>
        </footer>
    </main>
</body>
</html>
