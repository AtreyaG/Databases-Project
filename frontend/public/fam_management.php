<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}
require_once '../user_info.php';
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
                <tr>
                    <td>Atreya Ghosh</td>
                    <td>atreya@u.edu</td>
                    <td><span class="badge badge-officer">officer</span></td>
                    <td>
                        <select style="width: auto; padding: 6px 10px; font-size: 13px;">
                            <option selected>Dragon Fam &#128009;</option>
                            <option>Phoenix Fam &#128293;</option>
                            <option>Tiger Fam &#128047;</option>
                            <option>Panda Fam &#128060;</option>
                        </select>
                    </td>
                    <td><button class="btn btn-secondary btn-sm">Edit</button></td>
                </tr>
                <tr>
                    <td>Priya Sharma</td>
                    <td>priya@u.edu</td>
                    <td><span class="badge badge-famhead">fam head</span></td>
                    <td>
                        <select style="width: auto; padding: 6px 10px; font-size: 13px;">
                            <option selected>Dragon Fam &#128009;</option>
                            <option>Phoenix Fam &#128293;</option>
                            <option>Tiger Fam &#128047;</option>
                            <option>Panda Fam &#128060;</option>
                        </select>
                    </td>
                    <td><button class="btn btn-secondary btn-sm">Edit</button></td>
                </tr>
                <tr>
                    <td>Kenji Tanaka</td>
                    <td>kenji@u.edu</td>
                    <td><span class="badge badge-famhead">fam head</span></td>
                    <td>
                        <select style="width: auto; padding: 6px 10px; font-size: 13px;">
                            <option>Dragon Fam &#128009;</option>
                            <option selected>Phoenix Fam &#128293;</option>
                            <option>Tiger Fam &#128047;</option>
                            <option>Panda Fam &#128060;</option>
                        </select>
                    </td>
                    <td><button class="btn btn-secondary btn-sm">Edit</button></td>
                </tr>
                <tr>
                    <td>Sarah Kim</td>
                    <td>sarah@u.edu</td>
                    <td><span class="badge badge-famhead">fam head</span></td>
                    <td>
                        <select style="width: auto; padding: 6px 10px; font-size: 13px;">
                            <option>Dragon Fam &#128009;</option>
                            <option>Phoenix Fam &#128293;</option>
                            <option selected>Tiger Fam &#128047;</option>
                            <option>Panda Fam &#128060;</option>
                        </select>
                    </td>
                    <td><button class="btn btn-secondary btn-sm">Edit</button></td>
                </tr>
                <tr>
                    <td>Jamal Robinson</td>
                    <td>jamal@u.edu</td>
                    <td><span class="badge badge-famhead">fam head</span></td>
                    <td>
                        <select style="width: auto; padding: 6px 10px; font-size: 13px;">
                            <option>Dragon Fam &#128009;</option>
                            <option>Phoenix Fam &#128293;</option>
                            <option>Tiger Fam &#128047;</option>
                            <option selected>Panda Fam &#128060;</option>
                        </select>
                    </td>
                    <td><button class="btn btn-secondary btn-sm">Edit</button></td>
                </tr>
                <tr>
                    <td>Mei-Ling Chen</td>
                    <td>meiling@u.edu</td>
                    <td><span class="badge badge-member">member</span></td>
                    <td>
                        <select style="width: auto; padding: 6px 10px; font-size: 13px;">
                            <option selected>Dragon Fam &#128009;</option>
                            <option>Phoenix Fam &#128293;</option>
                            <option>Tiger Fam &#128047;</option>
                            <option>Panda Fam &#128060;</option>
                        </select>
                    </td>
                    <td><button class="btn btn-secondary btn-sm">Edit</button></td>
                </tr>
                <tr>
                    <td>Ryan Nguyen</td>
                    <td>ryan@u.edu</td>
                    <td><span class="badge badge-member">member</span></td>
                    <td>
                        <select style="width: auto; padding: 6px 10px; font-size: 13px;">
                            <option>Dragon Fam &#128009;</option>
                            <option selected>Phoenix Fam &#128293;</option>
                            <option>Tiger Fam &#128047;</option>
                            <option>Panda Fam &#128060;</option>
                        </select>
                    </td>
                    <td><button class="btn btn-secondary btn-sm">Edit</button></td>
                </tr>
                <tr>
                    <td>Fatima Al-Hassan</td>
                    <td>fatima@u.edu</td>
                    <td><span class="badge badge-member">member</span></td>
                    <td>
                        <select style="width: auto; padding: 6px 10px; font-size: 13px;">
                            <option>Dragon Fam &#128009;</option>
                            <option>Phoenix Fam &#128293;</option>
                            <option selected>Tiger Fam &#128047;</option>
                            <option>Panda Fam &#128060;</option>
                        </select>
                    </td>
                    <td><button class="btn btn-secondary btn-sm">Edit</button></td>
                </tr>
            </tbody>
        </table>

        <footer>
            <p>FamHub &copy; 2026 | CS 4347 Database Systems Project</p>
        </footer>
    </main>
</body>
</html>
