<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo "You must be logged in to view this information.";
    exit;
}

include('php/db_connection.php');

if (isset($_GET['document_id'])) {
    $document_id = $_GET['document_id'];

    $query = "SELECT * FROM special_documents WHERE document_id = ?";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $document_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $document = $result->fetch_assoc();
            echo "<p><strong>Document Type:</strong> " . htmlspecialchars($document['document_type']) . "</p>";
            echo "<p><strong>Client's Name:</strong> " . htmlspecialchars($document['l_name'] . ' ' . $document['f_name'] . ' ' . $document['m_name']) . "</p>";
            echo "<p><strong>Payment Method:</strong> " . htmlspecialchars($document['payment_method']) . "</p>";
            echo "<p><strong>Date Submitted:</strong> " . htmlspecialchars($document['created_at']) . "</p>";
            echo "<p><strong>Status:</strong> " . htmlspecialchars($document['status']) . "</p>";
            echo "<p><strong>Additional Notes:</strong> " . nl2br(htmlspecialchars($document['additional_notes'])) . "</p>";
        } else {
            echo "Document not found.";
        }
        $stmt->close();
    } else {
        echo "Error preparing query: " . $mysqli->error;
    }

    $mysqli->close();
} else {
    echo "No document ID provided.";
}
?>
