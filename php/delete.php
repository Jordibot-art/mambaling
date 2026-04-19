<?php
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "mambaling";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $announcement_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $announcement_id);

    if ($stmt->execute()) {
        echo "Announcement deleted successfully!";
        echo "<script>window.location.href='../user_announcements.php';</script>";
    } else {
        echo "Error deleting announcement: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No announcement ID specified.";
}

$conn->close();
?>
