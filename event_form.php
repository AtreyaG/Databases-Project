<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - FamHub</title>
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
                <li><a href="event_form.php" class="active"><span class="nav-icon">&#128197;</span> Events</a></li>
                <li><a href="event_signup.php"><span class="nav-icon">&#9997;</span> Event Signup</a></li>
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
            <div class="page-header-row">
                <div>
                    <h1>Events</h1>
                    <p>Create and manage events</p>
                </div>
                <button class="btn btn-primary" onclick="document.getElementById('new-event-form').style.display = document.getElementById('new-event-form').style.display === 'none' ? 'block' : 'none'">+ New Event</button>
            </div>
        </div>

        <!-- New Event Form (hidden by default) -->
        <div id="new-event-form" style="display: none; margin-bottom: 32px;">
            <fieldset>
                <legend>Create New Event</legend>
                <form action="event_process.php" method="POST">
                    <input type="hidden" id="event_id" name="event_id" value="">

                    <label for="event_name" class="required">Event Name</label>
                    <input type="text" id="event_name" name="event_name" placeholder="e.g., Spring Formal" maxlength="100" required>

                    <label for="event_description" class="required">Description</label>
                    <textarea id="event_description" name="event_description" placeholder="Describe the event details, agenda, dress code, etc." rows="3" required></textarea>

                    <div class="form-row">
                        <div>
                            <label for="event_date" class="required">Event Date</label>
                            <input type="date" id="event_date" name="event_date" required>
                        </div>
                        <div>
                            <label for="event_time">Event Time</label>
                            <input type="text" id="event_time" name="event_time" placeholder="e.g., 7:00 PM">
                        </div>
                    </div>

                    <label for="event_location" class="required">Location</label>
                    <input type="text" id="event_location" name="event_location" placeholder="e.g., Student Union Ballroom" maxlength="200" required>

                    <div class="form-row">
                        <div>
                            <label for="event_capacity" class="required">Capacity</label>
                            <input type="number" id="event_capacity" name="event_capacity" placeholder="Maximum attendees" min="1" required>
                        </div>
                        <div>
                            <label for="event_type">Event Type</label>
                            <select id="event_type" name="event_type">
                                <option value="">-- Select Type --</option>
                                <option value="social">Social</option>
                                <option value="philanthropy">Philanthropy</option>
                                <option value="professional">Professional Development</option>
                                <option value="meeting">Meeting</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <input type="submit" value="Save Event">
                        <input type="reset" value="Clear Form">
                    </div>
                </form>
            </fieldset>
        </div>

        <!-- Event Cards -->
        <div class="event-card">
            <h3>Mid-Autumn Festival &#127905;</h3>
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
            <h3>Dumpling Night &#129377;</h3>
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

        <div class="event-card">
            <h3>Lunar New Year Gala &#127887;</h3>
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

        <footer>
            <p>FamHub &copy; 2026 | CS 4347 Database Systems Project</p>
        </footer>
    </main>
</body>
</html>
