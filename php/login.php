<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = new mysqli("localhost", "root", "", "mambaling");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $conn->real_escape_string($username);

    $sql = "SELECT * FROM users WHERE username='$username' OR email='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Check password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: ../admin_dashboard.php");
            } else {
                header("Location: ../user_dashboard.php");
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }

    $conn->close();
}
?>
