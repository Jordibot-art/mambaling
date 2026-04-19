<?php
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "mambaling";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['add_event'])) {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_address = $_POST['event_address'];
    $event_photo = '';

    if (isset($_FILES['event_photo'])) {
        $photo_name = $_FILES['event_photo']['name'];
        $photo_tmp_name = $_FILES['event_photo']['tmp_name'];
        $upload_dir = '../pictures';

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $photo_path = $upload_dir . '/' . basename($photo_name);

        if (move_uploaded_file($photo_tmp_name, $photo_path)) {
            $event_photo = $photo_name;
        } else {
            echo "Error uploading photo.";
            exit;
        }
    }

    $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, event_address, event_photo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $event_name, $event_date, $event_address, $event_photo);

    if ($stmt->execute()) {
        echo "Document submitted successfully!";
        echo "<script>window.location.href='../admin_announcements.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
