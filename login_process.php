<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

$net_id   = trim($_POST['net_id'] ?? '');
$password = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? 'officer';

if ($net_id === '' || $password === '') {
    header('Location: login.php?error=1&tab=' . $role);
    exit();
}

if ($role === 'member') {
    $stmt = $conn->prepare("SELECT password FROM member_auth WHERE net_id = ? LIMIT 1");
    $stmt->bind_param("s", $net_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['member_logged_in'] = true;
        $_SESSION['net_id']           = $net_id;
        header('Location: dashboard.php');
        exit();
    }

    header('Location: login.php?error=1&tab=member');
    exit();
}

// Officer login
$stmt = $conn->prepare("SELECT password FROM officer WHERE net_id = ? LIMIT 1");
$stmt->bind_param("s", $net_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if ($row && password_verify($password, $row['password'])) {
    $_SESSION['logged_in'] = true;
    $_SESSION['net_id']    = $net_id;
    header('Location: dashboard.php');
    exit();
}

header('Location: login.php?error=1');
exit();
?>
