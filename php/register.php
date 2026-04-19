<?php
$conn = new mysqli('localhost', 'root', '', 'mambaling');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$email = $_POST['email'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$birthday = $_POST['birthday'];
$contact_number = $_POST['contact_number'];
$address = $_POST['address'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$gender = $_POST['gender'];

$sql = "INSERT INTO users (username, email, first_name, last_name, birthday, contact_number, address, password, gender) 
        VALUES ('$username', '$email', '$first_name', '$last_name', '$birthday', '$contact_number', '$address', '$password', '$gender')";

if ($conn->query($sql) === TRUE) {
    header("Location: ../index.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
