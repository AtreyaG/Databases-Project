<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'save';
    $event_id = $_POST['event_id'] ?? null;
    $event_name = $_POST['event_name'] ?? '';
    $description = $_POST['event_description'] ?? '';
    $location = $_POST['event_location'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $start_time = $_POST['event_time'] ?? '';
    $capacity = $_POST['event_capacity'] ?? null;

    if ($action === 'delete') {
        if ($event_id) {
            $stmt = $conn->prepare("DELETE FROM event WHERE event_id = ?");
            $stmt->bind_param("i", $event_id);
            if ($stmt->execute()) {
                header("Location: event_form.php?deleted=1");
                exit();
            } else {
                die("Error deleting event: " . $conn->error);
            }
        }
    } elseif ($action === 'update' || $action === 'save') {
        if (!empty($event_name) && !empty($description) && !empty($event_date) && !empty($location)) {
            if ($action === 'update' && $event_id) {
                $stmt = $conn->prepare("
                    UPDATE event 
                    SET event_name = ?, description = ?, location = ?, event_date = ?, start_time = ?, capacity = ?
                    WHERE event_id = ?
                ");
            } else {
                $stmt = $conn->prepare("
                    INSERT INTO event (event_name, description, location, event_date, start_time, capacity)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
            }

            $time = !empty($start_time) ? $start_time : null;
            $cap = ($capacity !== '' && $capacity !== null) ? (int)$capacity : null;

            if ($action === 'update') {
                $stmt->bind_param("sssssii", $event_name, $description, $location, $event_date, $time, $cap, $event_id);
            } else {
                $stmt->bind_param("sssssi", $event_name, $description, $location, $event_date, $time, $cap);
            }

            if ($stmt->execute()) {
                header("Location: event_form.php?success=1");
                exit();
            } else {
                die("Error saving event: " . $conn->error);
            }
            $stmt->close();
        } else {
            die("Please fill in all required fields (Event Name, Description, Date, and Location).");
        }
    }
} else {
    header("Location: event_form.php");
    exit();
}
?>
