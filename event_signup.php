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
                <li><a href="event_form.php"><span class="nav-icon">&#128197;</span> Events</a></li>
                <li><a href="event_signup.php" class="active"><span class="nav-icon">&#9997;</span> Event Signup</a></li>
                <li><a href="fam_management.php"><span class="nav-icon">&#128101;</span> Fam Management</a></li>
                <li><a href="reports.php"><span class="nav-icon">&#128202;</span> Reports</a></li>
            </ul>
        </nav>
        <div class="sidebar-user">
            <div class="user-avatar">AG</div>
            <div class="user-info">
                <div class="user-name">Atreya Ghosh</div>
                <div class="user-role">Officer</div>
            </div>
        </div>
        <div class="sidebar-signout">
            <a href="login.php">&#8592; Sign out</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1>Event Signup</h1>
            <p>Register for upcoming events</p>
        </div>

        <!-- Event Cards with Sign Up buttons -->
        <div class="event-card">
            <div class="event-card-header">
                <h3>Boba Run &#129483;</h3>
                <button class="btn btn-primary btn-sm">Sign Up</button>
            </div>
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
            <div class="event-card-header">
                <h3>Dumpling Night &#129377;</h3>
                <button class="btn btn-primary btn-sm">Sign Up</button>
            </div>
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
            <div class="event-card-header">
                <h3>Karaoke Social &#127908;</h3>
                <button class="btn btn-primary btn-sm">Sign Up</button>
            </div>
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

        <div class="event-card">
            <div class="event-card-header">
                <h3>Mid-Autumn Festival &#127905;</h3>
                <button class="btn btn-primary btn-sm">Sign Up</button>
            </div>
            <p class="event-description">Celebrate with mooncakes, lanterns, and cultural performances.</p>
            <div class="event-meta">
                <span>&#128197; Sep 14, 2026</span>
                <span>&#128205; Student Union Ballroom</span>
                <span>&#128101; 87/150</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 58%;"></div>
            </div>
        </div>

        <div class="event-card">
            <div class="event-card-header">
                <h3>Lunar New Year Gala &#127887;</h3>
                <button class="btn btn-primary btn-sm">Sign Up</button>
            </div>
            <p class="event-description">Our biggest event of the year with performances, food, and fun.</p>
            <div class="event-meta">
                <span>&#128197; Jan 28, 2027</span>
                <span>&#128205; Grand Hall</span>
                <span>&#128101; 142/300</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 47%;"></div>
            </div>
        </div>

        <footer>
            <p>FamHub &copy; 2026 | CS 4347 Database Systems Project</p>
        </footer>
    </main>
</body>
</html>
