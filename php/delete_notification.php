<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "mambaling";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $notification_id = $_POST['notification_id'];

    $query = "DELETE FROM notifications WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $notification_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Successfully deleted notification
        header("Location: ../user_announcements.php");
        exit;
    } else {
        echo "Error deleting notification.";
    }

    $stmt->close();
}

$conn->close();
?>
