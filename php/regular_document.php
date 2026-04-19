<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.html");
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'mambaling');

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

$user_id = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $document_type = trim($_POST['document_type']);
    $last_name = trim($_POST['last_name']);
    $first_name = trim($_POST['first_name']);
    $middle_name = !empty($_POST['middle_name']) ? trim($_POST['middle_name']) : NULL;
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);
    $address = trim($_POST['address']);
    $years_of_residency = trim($_POST['years_of_residency']);
    $purpose = trim($_POST['purpose']);

    if (
        empty($document_type) || 
        empty($last_name) || 
        empty($first_name) || 
        empty($age) || 
        empty($gender) || 
        empty($address) || 
        empty($years_of_residency) || 
        empty($purpose)
    ) {
        die("Please fill in all required fields.");
    }

    $sql = "INSERT INTO regular_documents 
            (user_id, document_type, last_name, first_name, middle_name, age, gender, address, years_of_residency, purpose) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssisss", 
        $user_id, 
        $document_type, 
        $last_name, 
        $first_name, 
        $middle_name, 
        $age, 
        $gender, 
        $address, 
        $years_of_residency, 
        $purpose
    );

    // Execute query and handle the result
    if ($stmt->execute()) {
        echo "Document submitted successfully!";
        echo "<script>window.location.href='../User_Services.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
