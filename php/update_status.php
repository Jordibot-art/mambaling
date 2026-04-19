<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'], $_POST['status'])) {
    include('db_connection.php');

    $id = $_POST['id'];
    $status = $_POST['status'];

    $query = "UPDATE tbltransactions SET status = ? WHERE id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param('si', $status, $id);
        if ($stmt->execute()) {
            echo "Status updated successfully!";
        } else {
            echo "Error: Could not update the status.";
        }
        $stmt->close();
    } else {
        echo "Error: Could not prepare the statement.";
    }

    $mysqli->close();
}
?>
