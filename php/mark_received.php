<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login.html");
    exit;
}

include('db_connection.php');

if (isset($_GET['doc_id'])) {
    $document_id = $_GET['doc_id'];
    $user_id = $_SESSION['username'];
    
    $query = "SELECT * FROM special_documents WHERE id = ? AND user_id = ? AND status = 'For Pick-Up'";
    
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("is", $document_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $update_query = "UPDATE special_documents SET status = 'Completed' WHERE id = ?";
            
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
    header("Location: ../user_myrequest.php");
    exit;
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = "error";
    header("Location: ../user_myrequest.php");
    exit;
}
?>