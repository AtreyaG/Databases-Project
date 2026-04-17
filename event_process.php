<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'] ?? '';
    $description = $_POST['event_description'] ?? '';
    $location = $_POST['event_location'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $start_time = $_POST['event_time'] ?? '';
    $capacity = $_POST['event_capacity'] ?? null;

    if (!empty($event_name) && !empty($description) && !empty($event_date)) {
        // Using MySQLi prepared statements to prevent SQL injection
        $stmt = $conn->prepare("
            INSERT INTO event (event_name, description, location, event_date, start_time, capacity)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $time = $start_time ?: null;
        $cap = $capacity ?: null;

        $stmt->bind_param("sssssi", 
            $event_name, 
            $description, 
            $location, 
            $event_date, 
            $time, 
            $cap
        );

        if ($stmt->execute()) {
            header("Location: event_form.php?success=1");
            exit();
        } else {
            die("Error saving event: " . $conn->error);
        }
        $stmt->close();
    } else {
        die("Please fill in all required fields.");
    }
} else {
    header("Location: event_form.php");
    exit();
}
?>
