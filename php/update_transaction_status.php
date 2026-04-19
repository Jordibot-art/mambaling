<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "Unauthorized";
    exit;
}

if (isset($_POST['doc_id']) && isset($_POST['status'])) {
    $docId = intval($_POST['doc_id']);
    $status = ($_POST['status'] === 'paid') ? 'paid' : 'failed';

    $stmt = $mysqli->prepare("UPDATE tbltransactions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $docId);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request";
}

$mysqli->close();
?>
