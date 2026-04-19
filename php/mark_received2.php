<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.html");
    exit;
}

include('db_connection.php');

if (isset($_GET['document_id'])) {
    $document_id = $_GET['document_id'];
    $user_id = $_SESSION['username'];
    
    $query = "SELECT * FROM regular_documents WHERE document_id = ? AND user_id = ? AND status = 'For Pick-Up'";
    
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("is", $document_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $update_query = "UPDATE regular_documents SET status = 'Completed' WHERE document_id = ?";
            
            if ($update_stmt = $mysqli->prepare($update_query)) {
                $update_stmt->bind_param("i", $document_id);
                if ($update_stmt->execute()) {
                    $_SESSION['message'] = "Document has been marked as received and completed.";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Failed to update document status.";
                    $_SESSION['message_type'] = "error";
                }
                $update_stmt->close();
            } else {
                $_SESSION['message'] = "Error preparing update query: " . $mysqli->error;
                $_SESSION['message_type'] = "error";
            }
        } else {
            $_SESSION['message'] = "Document not found or you don't have permission to mark it as received.";
            $_SESSION['message_type'] = "error";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Error preparing query: " . $mysqli->error;
        $_SESSION['message_type'] = "error";
    }
    
    $mysqli->close();
    header("Location: ../user_myrequest1.php");
    exit;
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = "error";
    header("Location: ../user_myrequest.php");
    exit;
}
?>