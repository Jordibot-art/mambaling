<?php
include('db_connection.php');

if (isset($_POST['doc_id'])) {
    $doc_id = intval($_POST['doc_id']);
    $query = "SELECT * FROM special_documents WHERE id = ?";
    
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
                <input type="text" value="' . htmlspecialchars($row['l_name']) . '" readonly>

                <label>First Name:</label>
                <input type="text" value="' . htmlspecialchars($row['f_name']) . '" readonly>

                <label>Middle Name (optional):</label>
                <input type="text" value="' . htmlspecialchars($row['m_name']) . '" readonly>

                <label>Address:</label>
                <input type="text" value="' . htmlspecialchars($row['address']) . '" readonly>

                <label>Place of Issue:</label>
                <input type="text" value="' . htmlspecialchars($row['place_of_issue']) . '" readonly>

                <label>Citizen:</label>
                <input type="text" value="' . htmlspecialchars($row['citizen']) . '" readonly>

                <label>Place Of Birth:</label>
                <input type="text" value="' . htmlspecialchars($row['place_of_birth']) . '" readonly>

                <label>Date Of Birth:</label>
                <input type="text" value="' . htmlspecialchars($row['date_of_birth']) . '" readonly>

                <label>Height:</label>
                <input type="text" value="' . htmlspecialchars($row['height']) . '" readonly>

                <label>Payment Method:</label>
                <input type="text" value="' . htmlspecialchars($row['payment_method']) . '" readonly>';

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