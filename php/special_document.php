<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.html");
    exit;
}

include('db_connection.php');

if (!isset($_SESSION['username'])) {
    die("You must be logged in to submit a document.");
}

$user_id = $_SESSION['username']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $document_type = $_POST['document_type'];
    $l_name = $_POST['l_name'];
    $f_name = $_POST['f_name'];
    $m_name = $_POST['m_name'];
    $address = $_POST['address'];
    $place_of_issue = $_POST['place_of_issue'];
    $citizen = $_POST['citizen'];
    $place_of_birth = $_POST['place_of_birth'];
    $date_of_birth = $_POST['date_of_birth'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $gender = $_POST['gender'];
    $payment_method = $_POST['payment_method'];
    $purpose = $_POST['purpose'];

    $query = "INSERT INTO special_documents (user_id, document_type, l_name, f_name, m_name, address, place_of_issue, citizen, place_of_birth, date_of_birth, height, weight, gender, payment_method, purpose)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("sssssssssssssss", $user_id, $document_type, $l_name, $f_name, $m_name, $address, $place_of_issue, $citizen, $place_of_birth, $date_of_birth, $height, $weight, $gender, $payment_method, $purpose);
        
        if ($stmt->execute()) {
            echo "Document submitted successfully!";
            echo "<script>window.location.href='../User_Services.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing query: " . $mysqli->error;
    }

    $mysqli->close();
}
?>
