<?php
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "mambaling";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['announce'])) {
    $name = $_POST['name'];
    $announcement = $_POST['announcement'];

    $stmt = $conn->prepare("INSERT INTO announcements (name, announcement, date_posted) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $name, $announcement);
    
    if ($stmt->execute()) {
        echo "Document submitted successfully!";
        echo "<script>window.location.href='../admin_announcements.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$query_announcements = "SELECT * FROM announcements ORDER BY date_posted DESC";
$result_announcements = $conn->query($query_announcements);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <style>
        h3 {
            color: #12284B;
        }
        .delete-btn {
        background: #12284B;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 5px;
        }
    </style>
</head>
<body>
    <h3>Recent Announcements</h3>
    
    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Announcement</th>
                <th>Date Posted</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_announcements->num_rows > 0) {
                while ($row = $result_announcements->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . nl2br(htmlspecialchars($row['announcement'])) . "</td>";
                    echo "<td>" . $row['date_posted'] . "</td>";
                    echo "<td><a href='php\delete.php?id=" . $row['id'] . "' class='delete-btn'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No announcements available.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
