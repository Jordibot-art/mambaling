<?php
session_start();
// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

// Get the logged-in user's username
$username = $_SESSION['username'];

// Database connection
$servername = "localhost";
$db_username = "root";
$password = "";
$database = "mambaling";

$conn = new mysqli($servername, $db_username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
    $first_name = $userData['first_name'] ?? '';
    $middle_name = $userData['middle_name'] ?? '';
    $last_name = $userData['last_name'] ?? '';
    $email = $userData['email'] ?? '';
    $address = $userData['address'] ?? '';
    $gender = $userData['gender'] ?? '';
    $contact = $userData['contact_number'] ?? '';
} else {
    $email = '';
    $address = '';
    $gender = '';
    $contact = '';
}

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
    <style>
        /* General Styles */
        body {
    font-family: Arial, sans-serif;
    background-color: #f3f4f6;
    margin: 0;
    padding: 0;
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

.sidebar ul li a.active {
    background: white; /* Background color for active link */
    color: #12284B;   /* Text color for active link */
}

/* Main Content */
.container {
    display: flex;
}
.container h1{
    margin-top: -1%;
    font-size: 34px;
    text-align: center;
}

.content {
    margin-left: 250px;
    flex: 1;
    padding: 20px;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .event-list {
        grid-template-columns: 1fr 1fr; /* 2 columns for medium screens */
    }
}

@media (max-width: 480px) {
    .event-list {
        grid-template-columns: 1fr; /* 1 column for small screens */
    }
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

.profile-card {
    margin-left: 200px;
    height: 650px;
    background-color: white;
    width: 600px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
    padding: 30px;
}

.profile-header {
    text-align: center;
    margin-bottom: 30px;
}

.profile-header h1 {
    color: #12284B;
    font-size: 24px;
    margin: 0 0 20px 0;
}

.profile-icon {
    margin: 0 auto 20px;
    width: 80px;
    height: 80px;
}

.profile-icon i {
    font-size: 80px;
    color: #000;
}

.profile-info {
    margin-bottom: 30px;
}

.info-row {
    margin-left: 20px;
    gap: 50%;
    display: flex;
    margin-bottom: 15px;
}

.info-label {
    width: 120px;
    font-weight: bold;
    color: #333;
}

.info-value {
    flex: 1;
    color: #666;
}

.profile-seal {
    position: absolute;
    bottom: -25px;
    right: -23px;
    width: 200px;
    height: 200px;
    opacity: 0.7;
}

.profile-seal img {
    width: 100%;
    height: 100%;
}

.back-button {
    position: absolute;
    top: 20px;
    left: 20px;
    background: none;
    border: none;
    color: #12284B;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    text-decoration: none;
}

.back-button i {
    margin-right: 5px;
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
                    <li><a href="user_announcements.php"><i class="fas fa-bell"></i> Notifications</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
        <div class="profile-card">
        <div class="profile-header">
            <h1>PROFILE</h1>
            <div class="profile-icon">
                <i class="fas fa-user-circle"></i>
            </div>
        </div>
        
        <div class="profile-info">
        <div class="info-row">
                <span class="info-label" style="margin-top: 20px;">Name:</span>
                <span class="info-value" style="margin-top: 20px;"><?php echo htmlspecialchars($first_name . ' ' . $middle_name . ' ' . $last_name); ?></span>
            </div>

            <div class="info-row">
                <span class="info-label" style="margin-top: 20px;">Username:</span>
                <span class="info-value" style="margin-top: 20px;"><?php echo htmlspecialchars($username); ?></span>
            </div>
            
            <div class="info-row">
                <span class="info-label" style="margin-top: 20px;">Email:</span>
                <span class="info-value" style="margin-top: 20px;"><?php echo htmlspecialchars($email); ?></span>
            </div>
            
            <div class="info-row">
                <span class="info-label" style="margin-top: 20px;">Address:</span>
                <span class="info-value" style="margin-top: 20px;"><?php echo htmlspecialchars($address); ?></span>
            </div>
            
            <div class="info-row">
                <span class="info-label" style="margin-top: 20px;">Gender:</span>
                <span class="info-value" style="margin-top: 20px;"><?php echo htmlspecialchars($gender); ?></span>
            </div>
            
            <div class="info-row">
                <span class="info-label" style="margin-top: 20px;">Contact No:</span>
                <span class="info-value" style="margin-top: 20px;"><?php echo htmlspecialchars($contact); ?></span>
            </div>
        </div>
        
        <div class="profile-seal">
            <img src="Images\logo.png" alt="Barangay Mambaling Official Seal">
        </div>
    </div>

        </main>
    </div>
    <br><br><br><br><br>
    <footer>
        <p>© 2025 Barangay E-Service System. All Rights Reserved.</p>
    </footer>
</body>
</html>
