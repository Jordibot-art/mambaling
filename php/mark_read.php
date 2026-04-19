<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_POST['notification_id'])) {
    header("Location: ../user_dashboard.php");
    exit;
}

$notification_id = $_POST['notification_id'];
$conn = new mysqli("localhost", "root", "", "mambaling");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
$stmt->bind_param("is", $notification_id, $_SESSION['username']);
$stmt->execute();

$stmt->close();
$conn->close();

header("Location: ../user_announcements.php");
exit;
