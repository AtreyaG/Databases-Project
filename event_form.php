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
        <?php
        require_once 'db.php';
        
        $sql = "SELECT e.*, 
                       (SELECT COUNT(*) FROM attendance a WHERE a.event_id = e.event_id) as current_attendance 
                FROM event e 
                ORDER BY e.event_date ASC";
        $result = $conn->query($sql);
        $events = $result->fetch_all(MYSQLI_ASSOC);
        ?>
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
                    <input type="hidden" name="action" id="form_action" value="save">
                    <input type="hidden" id="event_id" name="event_id" value="">

                    <label for="event_name" class="required">Event Name</label>
                    <input type="text" id="event_name" name="event_name" placeholder="e.g., Spring Formal" maxlength="100" required>

                    <label for="event_description" class="required">Description</label>
                    <textarea id="event_description" name="event_description" placeholder="Describe the event details, agenda, dress code, etc." rows="3" required></textarea>

                    <div class="form-row">
                        <div>
                            <label for="event_date" class="required">Event Date</label>
                            <input type="date" id="event_date" name="event_date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div>
                            <label for="event_time">Event Time</label>
                            <input type="time" id="event_time" name="event_time">
                        </div>
                    </div>

                    <label for="event_location" class="required">Location</label>
                    <input type="text" id="event_location" name="event_location" placeholder="e.g., Student Union Ballroom" maxlength="200" required>

                    <div class="form-row">
                        <div>
                            <label for="event_capacity">Capacity</label>
                            <input type="number" id="event_capacity" name="event_capacity" placeholder="Maximum attendees (optional)" min="1">
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
        <?php foreach ($events as $event): ?>
        <div class="event-card">
            <div class="event-card-header">
                <h3><?= htmlspecialchars($event['event_name']) ?></h3>
                <div class="event-actions">
                    <button class="btn btn-sm" onclick="editEvent(<?= htmlspecialchars(json_encode($event)) ?>)">Edit</button>
                    <form action="event_process.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this event?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </div>
            <p class="event-description"><?= htmlspecialchars($event['description']) ?></p>
            <div class="event-meta">
                <span>&#128197; <?= date('M d, Y', strtotime($event['event_date'])) ?></span>
                <span>&#128205; <?= htmlspecialchars($event['location'] ?: 'No location set') ?></span>
                <span>&#128101; <?= $event['current_attendance'] ?>/<?= $event['capacity'] ?: '∞' ?></span>
            </div>
            <?php if ($event['capacity']): ?>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?= min(100, ($event['current_attendance'] / $event['capacity']) * 100) ?>%;"></div>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <footer>
            <p>FamHub &copy; 2026 | CS 4347 Database Systems Project</p>
        </footer>
    </main>

    <script>
    function editEvent(event) {
        document.getElementById('new-event-form').style.display = 'block';
        document.getElementById('event_id').value = event.event_id;
        document.getElementById('event_name').value = event.event_name;
        document.getElementById('event_description').value = event.description;
        document.getElementById('event_date').value = event.event_date;
        document.getElementById('event_time').value = event.start_time;
        document.getElementById('event_location').value = event.location;
        document.getElementById('event_capacity').value = event.capacity;
        document.getElementById('form_action').value = 'update';
        
        // Scroll to form
        document.getElementById('new-event-form').scrollIntoView({ behavior: 'smooth' });
        
        // Change legend and submit button text
        document.querySelector('#new-event-form legend').textContent = 'Edit Event';
        document.querySelector('#new-event-form input[type="submit"]').value = 'Update Event';
    }
    </script>
</body>
</html>
