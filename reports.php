<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}
require_once 'user_info.php';

// --- Members per Fam ---
$fam_result = $conn->query("SELECT fam_name, member_count FROM family ORDER BY fam_id");
$fams = [];
$fam_total = 0;
while ($row = $fam_result->fetch_assoc()) {
    $fams[] = $row;
    $fam_total += $row['member_count'];
}

// --- Event Attendance Rate (5 most recent) ---
$att_stmt = $conn->query("
    SELECT e.event_name,
           COUNT(a.net_id) AS attended,
           (SELECT COUNT(*) FROM member) AS total_members
    FROM event e
    LEFT JOIN attendance a ON e.event_id = a.event_id AND a.attended = TRUE
    GROUP BY e.event_id, e.event_name
    ORDER BY e.event_date DESC
    LIMIT 5
");
$events = [];
while ($row = $att_stmt->fetch_assoc()) {
    $rate = $row['total_members'] > 0 ? round($row['attended'] / $row['total_members'] * 100) : 0;
    $events[] = ['name' => $row['event_name'], 'rate' => $rate];
}
$events = array_reverse($events); // chronological order for chart

// --- Potentially Inactive Members ---
$inactive_result = $conn->query("
    SELECT m.first_name, m.last_name, f.fam_name
    FROM member m
    LEFT JOIN family f ON m.fam_id = f.fam_id
    LEFT JOIN attendance a ON m.net_id = a.net_id
    WHERE a.net_id IS NULL
    ORDER BY m.last_name, m.first_name
");
$inactive = [];
while ($row = $inactive_result->fetch_assoc()) {
    $inactive[] = $row;
}

// Pie chart colors
$pie_colors = ['#c44a4a', '#d4706e', '#d4885a', '#dbb86a', '#8a9bab', '#6a8bab', '#7ab88a'];

// Bar chart colors
$bar_colors = ['#c44a4a', '#d4706e', '#a63d3d', '#d4885a', '#dbb86a'];

// SVG pie path helper
function pie_path($cx, $cy, $r, $start_deg, $end_deg, $color) {
    $start_rad = deg2rad($start_deg - 90);
    $end_rad   = deg2rad($end_deg - 90);
    $x1 = $cx + $r * cos($start_rad);
    $y1 = $cy + $r * sin($start_rad);
    $x2 = $cx + $r * cos($end_rad);
    $y2 = $cy + $r * sin($end_rad);
    $large = ($end_deg - $start_deg > 180) ? 1 : 0;
    return "<path d=\"M{$cx},{$cy} L{$x1},{$y1} A{$r},{$r} 0 {$large},1 {$x2},{$y2} Z\" fill=\"{$color}\"/>";
}
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
            <!-- Members per Fam Pie Chart -->
            <div class="chart-card">
                <h3>Members per Fam</h3>
                <div class="chart-placeholder" id="pie-chart">
                    <svg viewBox="0 0 200 200" width="200" height="200">
                        <?php if (count($fams) === 1): ?>
                            <circle cx="100" cy="100" r="90" fill="<?= $pie_colors[0] ?>"/>
                        <?php elseif (count($fams) > 1):
                            $angle = 0;
                            foreach ($fams as $i => $fam):
                                $slice = ($fam['member_count'] / $fam_total) * 360;
                                echo pie_path(100, 100, 90, $angle, $angle + $slice, $pie_colors[$i % count($pie_colors)]);
                                $angle += $slice;
                            endforeach;
                        endif; ?>
                    </svg>
                </div>
                <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; margin-top: 12px; font-size: 12px; color: var(--text-light);">
                    <?php foreach ($fams as $i => $fam): ?>
                        <span>
                            <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:<?= $pie_colors[$i % count($pie_colors)] ?>;margin-right:4px;"></span>
                            <?= htmlspecialchars($fam['fam_name']) ?> (<?= $fam['member_count'] ?>)
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Event Attendance Rate Bar Chart -->
            <div class="chart-card">
                <h3>Event Attendance Rate (%)</h3>
                <div class="chart-placeholder" id="bar-chart">
                    <?php
                    $svg_w = 380; $svg_h = 230;
                    $label_w = 145; $pad_right = 15; $pad_top = 15; $pad_bottom = 28;
                    $chart_w = $svg_w - $label_w - $pad_right;
                    $chart_h = $svg_h - $pad_top - $pad_bottom;
                    $n = count($events);
                    $bar_h = $n > 0 ? min(32, ($chart_h / $n) * 0.65) : 32;
                    $gap    = $n > 1 ? ($chart_h - $bar_h * $n) / ($n - 1) : 0;
                    ?>
                    <svg viewBox="0 0 <?= $svg_w ?> <?= $svg_h ?>" width="<?= $svg_w ?>" height="<?= $svg_h ?>">
                        <!-- X axis grid lines & labels -->
                        <?php foreach ([0, 25, 50, 75, 100] as $val):
                            $x = $label_w + ($val / 100) * $chart_w;
                        ?>
                            <line x1="<?= $x ?>" y1="<?= $pad_top ?>" x2="<?= $x ?>" y2="<?= $pad_top + $chart_h ?>" stroke="#e5e7eb" stroke-width="0.5"/>
                            <text x="<?= $x ?>" y="<?= $svg_h - $pad_bottom + 16 ?>" font-size="11" fill="#9ca3af" text-anchor="middle"><?= $val ?>%</text>
                        <?php endforeach; ?>
                        <!-- Bars -->
                        <?php foreach ($events as $i => $ev):
                            $by    = $pad_top + $i * ($bar_h + $gap);
                            $bw    = ($ev['rate'] / 100) * $chart_w;
                            $color = $bar_colors[$i % count($bar_colors)];
                        ?>
                            <text x="<?= $label_w - 8 ?>" y="<?= $by + $bar_h / 2 + 4 ?>" font-size="11" fill="#374151" text-anchor="end"><?= htmlspecialchars($ev['name']) ?></text>
                            <rect x="<?= $label_w ?>" y="<?= $by ?>" width="<?= $bw ?>" height="<?= $bar_h ?>" rx="4" fill="<?= $color ?>"/>
                            <text x="<?= $label_w + $bw + 4 ?>" y="<?= $by + $bar_h / 2 + 4 ?>" font-size="11" fill="#374151"><?= $ev['rate'] ?>%</text>
                        <?php endforeach; ?>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Potentially Inactive Members -->
        <div class="inactive-section">
            <h2 class="section-title">Potentially Inactive Members</h2>
            <p style="color: var(--text-light); font-size: 14px; margin-bottom: 20px;">Members who haven't signed up for any events.</p>

            <div class="inactive-cards">
                <?php if (empty($inactive)): ?>
                    <p style="color: var(--text-light); font-size: 14px;">All members have attended at least one event.</p>
                <?php else: foreach ($inactive as $m):
                    $initials = strtoupper(substr($m['first_name'], 0, 1) . substr($m['last_name'], 0, 1));
                    $color = $pie_colors[abs(crc32($m['last_name'])) % count($pie_colors)];
                ?>
                    <div class="inactive-card">
                        <div class="member-initials" style="background: <?= $color ?>;"><?= htmlspecialchars($initials) ?></div>
                        <div class="member-info">
                            <div class="member-name"><?= htmlspecialchars($m['first_name'] . ' ' . $m['last_name']) ?></div>
                            <div class="member-fam"><?= htmlspecialchars($m['fam_name'] ?? 'No Fam') ?></div>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <footer>
            <p>FamHub &copy; 2026 | CS 4347 Database Systems Project</p>
        </footer>
    </main>
</body>
</html>
