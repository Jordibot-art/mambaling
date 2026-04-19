<?php
include('db_connection.php');

if (isset($_POST['document_id'])) {
    $doc_id = intval($_POST['document_id']);
    $query = "SELECT * FROM regular_documents WHERE document_id = ?"; 
    
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $doc_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $status = $row['status'];
       
            echo '
            <form>
                <label>Document Type:</label>
                <input type="text" value="' . htmlspecialchars($row['document_type']) . '" readonly>

                <label>Last Name:</label>
                <input type="text" value="' . htmlspecialchars($row['last_name']) . '" readonly>

                <label>First Name:</label>
                <input type="text" value="' . htmlspecialchars($row['first_name']) . '" readonly>

                <label>Middle Name (optional):</label>
                <input type="text" value="' . htmlspecialchars($row['middle_name']) . '" readonly>

                <label>Address:</label>
                <input type="text" value="' . htmlspecialchars($row['address']) . '" readonly>

                <label>Years of Residency:</label>
                <input type="text" value="' . htmlspecialchars($row['years_of_residency']) . '" readonly>

                <label>Citizen:</label>
                <input type="text" value="' . htmlspecialchars($row['purpose']) . '" readonly>';

            if ($status == 'Pending') {
                echo '
                <button type="button" class="approve-btn" data-id="' . $doc_id . '">Approve</button>
                <button type="button" class="deny-btn" data-id="' . $doc_id . '">Deny</button>';
            } elseif ($status == 'Processing') {
                echo '
                <button type="button" class="completed-btn" data-id="' . $doc_id . '">Completed</button>';
            }

            echo '
            </form>';
        } else {
            echo "<p>No details found.</p>";
        }
        $stmt->close();
    }
}
$mysqli->close();
?>