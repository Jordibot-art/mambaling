<?php
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "mambaling";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['change_status'])) {
    $user_id = $_POST['user_id'];
    $new_status = $_POST['new_status']; 

    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $user_id);

    if ($stmt->execute()) {
        echo "Status updated successfully!";
        
        $notification_message = "Your status has been changed to: " . htmlspecialchars($new_status);

        $stmt2 = $conn->prepare("INSERT INTO status_notifications (user_id, message) VALUES (?, ?)");
        $stmt2->bind_param("is", $user_id, $notification_message);
        $stmt2->execute();

        $stmt2->close();

    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
