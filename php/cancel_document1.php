<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

if (isset($_GET['document_id'])) {
    $doc_id = $_GET['document_id'];

    $user_id = $_SESSION['username'];

    $query = "DELETE FROM regular_documents WHERE document_id = ? AND user_id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("is", $doc_id, $user_id);
        if ($stmt->execute()) {
            header("Location: ../user_myrequest1.php?success=Document deleted");
            exit;
        } else {
            echo "Error deleting document.";
        }
        $stmt->close();
    } else {
        echo "Error preparing query.";
    }
} else {
    echo "Invalid request.";
}

$mysqli->close();
?>
