<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}
require_once '../user_info.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_fam'])) {
    $net_id = $_POST['net_id'];
    $new_fam_id = $_POST['fam_id'] !== '' ? intval($_POST['fam_id']) : null;

    $current_stmt = $conn->prepare("SELECT fam_id FROM member WHERE net_id = ?");
    $current_stmt->bind_param("s", $net_id);
    $current_stmt->execute();
    $current_fam_id = $current_stmt->get_result()->fetch_assoc()['fam_id'];
    $current_stmt->close();

    if ($new_fam_id === null) {
        $update_stmt = $conn->prepare("UPDATE member SET fam_id = NULL WHERE net_id = ?");
        $update_stmt->bind_param("s", $net_id);
    } else {
        $update_stmt = $conn->prepare("UPDATE member SET fam_id = ? WHERE net_id = ?");
        $update_stmt->bind_param("is", $new_fam_id, $net_id);
    }
    $update_stmt->execute();
    $update_stmt->close();

    if ($current_fam_id) {
        $conn->query("UPDATE family SET member_count = GREATEST(member_count - 1, 0) WHERE fam_id = " . intval($current_fam_id));
    }
    if ($new_fam_id) {
        $conn->query("UPDATE family SET member_count = member_count + 1 WHERE fam_id = " . intval($new_fam_id));
    }

    header('Location: fam_management.php');
    exit();
}

$fams = $conn->query("SELECT fam_id, fam_name, member_count FROM family ORDER BY fam_name ASC")->fetch_all(MYSQLI_ASSOC);

$members = $conn->query(
    "SELECT m.net_id, m.first_name, m.last_name, m.fam_id, f.fam_name, " .
    "CASE WHEN o.net_id IS NOT NULL THEN 'officer' " .
    "WHEN fh.net_id IS NOT NULL THEN 'fam head' ELSE 'member' END AS role " .
    "FROM member m " .
    "LEFT JOIN family f ON m.fam_id = f.fam_id " .
    "LEFT JOIN officer o ON m.net_id = o.net_id AND (o.end_date IS NULL OR o.end_date >= CURDATE()) " .
    "LEFT JOIN fam_head fh ON m.net_id = fh.net_id AND (fh.end_date IS NULL OR fh.end_date >= CURDATE()) " .
    "ORDER BY m.last_name, m.first_name"
)->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fam Management - FamHub</title>
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
                <li><a href="fam_management.php" class="active"><span class="nav-icon">&#128101;</span> Fam Management</a></li>
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
            <h1>Fam Management</h1>
            <p>Assign members to fams and manage teams</p>
        </div>

        <!-- Fam Summary Cards -->
        <div class="fam-cards">
            <?php foreach ($fams as $index => $fam): ?>
            <?php $colors = ['red', 'orange', 'green', 'blue']; ?>
            <div class="fam-card">
                <div class="fam-card-header">
                    <span class="fam-dot <?= $colors[$index % count($colors)] ?>"></span> <?= htmlspecialchars($fam['fam_name']) ?>
                </div>
                <div class="fam-count"><?= $fam['member_count'] ?></div>
                <div class="fam-label">members assigned</div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Members Table -->
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Fam</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                <tr>
                    <td><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?></td>
                    <td><?= htmlspecialchars($member['net_id']) ?>@u.edu</td>
                    <td><span class="badge badge-<?= str_replace(' ', '', $member['role']) ?>"><?= htmlspecialchars($member['role']) ?></span></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="net_id" value="<?= htmlspecialchars($member['net_id']) ?>">
                            <input type="hidden" name="update_fam" value="1">
                            <select name="fam_id" onchange="this.form.submit()" style="width: auto; padding: 6px 10px; font-size: 13px;">
                                <option value="" <?= $member['fam_id'] === null ? 'selected' : '' ?>>No Fam</option>
                                <?php foreach ($fams as $fam): ?>
                                <option value="<?= $fam['fam_id'] ?>" <?= $member['fam_id'] == $fam['fam_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($fam['fam_name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <footer>
            <p>FamHub &copy; 2026 | CS 4347 Database Systems Project</p>
        </footer>
    </main>
</body>
</html>
