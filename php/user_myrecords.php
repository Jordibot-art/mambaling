<?php
include('db_connection.php');

if (isset($_POST['doc_id'])) {
    $doc_id = intval($_POST['doc_id']);

    $query_regular = "SELECT * FROM regular_documents WHERE document_id = ?";
    $query_special = "SELECT * FROM special_documents WHERE id = ?";

    $stmt = $mysqli->prepare($query_regular);
    $stmt->bind_param("i", $doc_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
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

                <label>Age:</label>
                <input type="text" value="' . htmlspecialchars($row['age']) . '" readonly>

                <label>Address:</label>
                <input type="text" value="' . htmlspecialchars($row['address']) . '" readonly>

                <label>Years of Residency:</label>
                <input type="text" value="' . htmlspecialchars($row['years_of_residency']) . '" readonly>

                <label>Purpose:</label>
                <textarea readonly>' . htmlspecialchars($row['purpose']) . '</textarea>
            </form>';
    } else {
        $stmt = $mysqli->prepare($query_special);
        $stmt->bind_param("i", $doc_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
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
                </form>';
        } else {
            echo "<p>No details found.</p>";
        }
    }

    $stmt->close();
}
$mysqli->close();
?>
