<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "mambaling";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

$username = $_SESSION['username'];

$query_notifications = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt_notifications = $conn->prepare($query_notifications);
$stmt_notifications->bind_param("s", $username);
$stmt_notifications->execute();
$result_notifications = $stmt_notifications->get_result();

$query = "
    SELECT 
        (SELECT COUNT(*) FROM regular_documents WHERE user_id = ? AND status = 'pending') + 
        (SELECT COUNT(*) FROM special_documents WHERE user_id = ? AND status = 'pending') 
        AS on_process,

        (SELECT COUNT(*) FROM regular_documents WHERE user_id = ? AND status = 'approved') + 
        (SELECT COUNT(*) FROM special_documents WHERE user_id = ? AND status = 'approved') 
        AS completed
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $username, $username, $username, $username);
$stmt->execute();
$stmt->bind_result($on_process, $completed);


$stmt->fetch();
$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard-Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f3f4f6;
    margin: 0;
    padding: 0;
}

.sidebar ul li a.active {
    background: white; /* Background color for active link */
    color: #12284B;   /* Text color for active link */
}

/* Header */
header {
    background-color: #ffffff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
    width: 99%;
    position: fixed;
    color: #12284B;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 23px 20px;
    z-index: 1000;
}

.logo-container {
    margin-top: -14px;
    margin-bottom: -14px;
    display: flex;
    align-items: center;
}

.logo-container p{
    font-size: 22px;
    font-weight: bold;
    color: #12284B;
}

.logo {
    height: 55px;
    width: 55px;
    margin-right: 10px;
    color: #12284B;
    text-decoration: none;
    font: 800 25px 'Montserrat', sans-serif;
    padding-left: 5px;
}

.logout-btn {
    background: none;
    border: none;
    color: gray;
    font-size: 16px;
    cursor: pointer;
    margin-right: 25px;
}

/* Sidebar */
.sidebar {
    width: 240px;
    background-color: #12284B;
    color: white;
    padding: 15px;
    position: fixed;
    height: 100%;
    margin-top: 6%
}   

.sidebar h2 {
    font-size: 22px;
    margin-bottom: 20px;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    margin-bottom: 15px;
}

.sidebar ul li a {
    text-decoration: none;
    color: white;
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 5px;
}

.sidebar ul li a i {
    margin-right: 10px;
}

.sidebar ul li a:hover {
    background: white;
    color: #12284B;
}

/* Main Content */
.container {
    display: flex;
}
.container h1{
    margin-top: 7%;
    font-size: 34px;
    text-align: center;
}


.content {
    margin-top: 7%;
    margin-left: 20%;
    flex: 1;
    padding: 20px;
}

/* Request Cards */
.request-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.card {
    padding: 20px;
    border-radius: 10px;
    color: white;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.card.blue {
    background: #162e55;
}

.card.green {
    background: #08D467;
}

/* Events */
.events h2 {
    color: #162e55;
    margin-bottom: 7px;
    margin-left: -80%;
    margin-top: 5%; 
}

.event-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 10px;
}

.event {
    text-align: center;
    position: relative;
    overflow: hidden;
    border-radius: 5px;
}

.side {
    margin-left: -56%;
    margin-top: 0%;
}

.cen {
    margin-left: -64%;
    margin-top: 0%;
}

.event img {
    width: 100%;
    height: 80%;
    object-fit: cover;
    border-radius: 5px;
}

.event h1 {
    font-size: 23px;
    font-weight: bold;
    text-align: left;
}

.event-info {
    letter-spacing: 1px;
    font-size: 9px;
    position: absolute;
    bottom: 50px;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.49);
    color: white;
    text-align: left;
    padding: 2px;
    opacity: 0;
    transition: opacity 1.0s ease-in-out;
}

.event:hover .event-info {
    opacity: 1;
}

/* Contact Section */
.contact {
    text-align: center;
    margin-top: 40px;
    margin-left: -57%;
}

.contact p {
    color: grey;
    font-size: 13px;
    margin-bottom: -2%;
    margin-left: 33%;
}

.contact h2 {
    font-size: 32px;
    margin-left: 33%;
    margin-top: 2%;
    margin-bottom: 2%;
}

.contact form {
    max-width: 400px;
    margin: 0 auto;
    text-align: left;
}

.contact label {
    display: block;
    margin-top: 10px;
}

.contact input,
.contact textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.contact button {
    background: #1e3a8a;
    color: white;
    padding: 10px 20px;
    border: none;
    margin-top: 15px;
    cursor: pointer;
    border-radius: 5px;
}

.contact button:hover {
    background: #172554;
}

.seal {
    margin-top: -28%;
    margin-left: 73%;
}

.seal img {
    height: 65%;
    width: 65%;
}


/* Footer */
footer {
    background: #f3f4f6;;
    text-align: center;
    color: #162e55;
    margin-left: 17%;
    margin-top: 6%;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}
th {
    background-color: #12284B;
    color: white;
}
.status.approved {
    color: green;
    font-weight: bold;
}

.status.unread {
    color: red;
    font-weight: bold;
}

.btn {
    background: #12284B;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 5px;
}
.btn:hover {
    background:rgb(33, 78, 150);
}
/* User Profile Circle */
.user-controls {
    display: flex;
    align-items: center;
    gap: 15px;
}

.profile-circle {
    width: 35px;
    height: 35px;
    background-color: #12284B;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    cursor: pointer;
}

.profile-circle i {
    font-size: 18px;
}

a {
    text-decoration: none;
    color: white;
}
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="Images\logo.png" alt="Barangay Mambaling Logo" class="logo">
            <p>Barangay Mambaling E-Service System<p>
        </div>
        <div class="user-controls">
        <div class="profile-circle">
            <a href="profile.php"><i class="fas fa-user"></i></a>
        </div>
        <a href="php\logout.php"><button class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
    </div>  
    </header>

    <div class="container">
        <aside class="sidebar">
            <h1>User’s Panel</h1    >
            <nav>
                <ul>
                <li><a href="user_dashboard.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="user_services.php"><i class="fas fa-concierge-bell"></i> Services</a></li>
                    <li><a href="user_myrequest.php"><i class="fas fa-tasks"></i> My Requests</a></li>
                    <li><a href="user_myrecords.php"><i class="fas fa-folder-open"></i> My Records</a></li>
                    <li><a href="user_announcements.php" class="active"><i class="fas fa-bell"></i> Notifications</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
        <?php include('php/announcements.php'); ?>
        
        <h3>Status Change</h3>
        <table border="1">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Message</th>
                <th>Status</th>
                <th>Date</th>
                <th>Status Read</th>
            </tr>
        </thead>
        <tbody>
        <?php
if ($result_notifications->num_rows > 0) {
    while ($row = $result_notifications->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['message']) . "</td>";
        echo "<td class='status " . ($row['is_read'] ? 'approved' : 'unread') . "'>";
        echo $row['is_read'] ? 'Read' : 'Unread';
        echo "</td>";
        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";

        // Read button form
        if (!$row['is_read']) {
            echo "<td>
                <form action='php\mark_read.php' method='POST'>
                    <input type='hidden' name='notification_id' value='" . $row['id'] . "'>
                    <button type='submit' class='btn'>Mark as Read</button>
                </form>
            </td>";
        } else {
            // Delete button form if notification is read
            echo "<td>
                <form action='php\delete_notification.php' method='POST'>
                    <input type='hidden' name='notification_id' value='" . $row['id'] . "'>
                    <button type='submit' class='btn' style='background-color: #12284B;'>Delete</button>
                </form>
            </td>";
        }

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No notifications found.</td></tr>";
}
?>
        </tbody>
    </table>
        </main>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <footer>
        <p>© 2025 Barangay E-Service System. All Rights Reserved.</p>
    </footer>
</body>
</html>
