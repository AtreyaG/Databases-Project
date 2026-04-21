<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: member_register.php');
    exit();
}

$net_id           = trim($_POST['net_id'] ?? '');
$password         = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if ($net_id === '' || $password === '' || $confirm_password === '') {
    header('Location: member_register.php?error=empty_fields');
    exit();
}

if ($password !== $confirm_password) {
    header('Location: member_register.php?error=password_mismatch');
    exit();
}

// Verify net_id exists in member table
$stmt = $conn->prepare("SELECT net_id FROM member WHERE net_id = ? LIMIT 1");
$stmt->bind_param("s", $net_id);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$member) {
    header('Location: member_register.php?error=no_member');
    exit();
}

// Check not already registered
$stmt = $conn->prepare("SELECT net_id FROM member_auth WHERE net_id = ? LIMIT 1");
$stmt->bind_param("s", $net_id);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($existing) {
    header('Location: member_register.php?error=already_registered');
    exit();
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO member_auth (net_id, password) VALUES (?, ?)");
$stmt->bind_param("ss", $net_id, $hashed);
$stmt->execute();
$stmt->close();

header('Location: member_register.php?success=1');
exit();
?>
