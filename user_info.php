<?php
require_once 'db.php';
$_stmt = $conn->prepare("SELECT first_name, last_name FROM member WHERE net_id = ?");
$_stmt->bind_param("s", $_SESSION['net_id']);
$_stmt->execute();
$_row = $_stmt->get_result()->fetch_assoc();
$_stmt->close();
$user_first    = $_row['first_name'] ?? 'User';
$user_last     = $_row['last_name']  ?? '';
$user_initials = strtoupper(substr($user_first, 0, 1) . substr($user_last, 0, 1));
?>
