<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "Unauthorized";
    exit;
}

$username = $_SESSION['username'];

if (isset($_POST['doc_id']) && isset($_POST['status'])) {
    $docId = intval($_POST['doc_id']);
    $newStatus = '';

    if ($_POST['status'] === 'approve') {
        $newStatus = 'Processing';
    } elseif ($_POST['status'] === 'deny') {
        $newStatus = 'Denied';
    } elseif ($_POST['status'] === 'completed') {
        $newStatus = 'For Pick-Up';
    }

    if ($newStatus) {
        $stmt = $mysqli->prepare("UPDATE special_documents SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $docId);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }

        $stmt->close();
    }
} else {
    echo "Invalid request";
}

$getUserQuery = "SELECT user_id, document_type FROM special_documents WHERE id = ?";
$stmt = $mysqli->prepare($getUserQuery);
$stmt->bind_param('i', $docId);
$stmt->execute();
$stmt->bind_result($userId, $document_type);
$stmt->fetch();
$stmt->close();

if ($userId) {
    $message = "Your $document_type request has been updated to '$newStatus'.";

    $notifyQuery = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
    $notifyStmt = $mysqli->prepare($notifyQuery);
    $notifyStmt->bind_param('ss', $userId, $message);
    $notifyStmt->execute();
    $notifyStmt->close();
}

$mysqli->close();
?>
