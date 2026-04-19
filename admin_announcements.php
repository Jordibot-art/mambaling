<?php
// Start the session
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

if ($_SESSION['role'] !== 'admin') {  
    header("Location: user_dashboard.php");
    exit;
}

// Database connection setup
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "mambaling";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard-Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="Css/admin_announcements.css?v=<?php echo time(); ?>">
    <style>
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
.sidebar ul li a.active {
    background: white; /* Background color for active link */
    color: #12284B;   /* Text color for active link */
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
            <a href="profile_admin.php"><i class="fas fa-user"></i></a>
        </div>
        <a href="php\logout.php"><button class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
    </div>
    </header>

    <div class="container">
    <aside class="sidebar">
            <h2>Admin's Panel</h2>
            <nav>
                <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-home" ></i> Dashboard</a></li>
                <li><a href="admin_special_request.php"><i class="fas fa-concierge-bell"></i> Requests</a></li>
                <li><a href="admin_announcements.php" class="active"><i class="fas fa-tasks"></i> Announcements</a></li>
                <li><a href="admin_payment.php"><i class="fa fa-exchange"></i>Transactions</a></li>
                <li><a href="admin_completed.php"><i class="fa fa-check"></i>Completed</a></li>
                <li><a href="admin_user.php"><i class="fa fa-users"></i>User & Admin Accounts</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
        <form method="POST" action="php\announcements.php">
        <div class="form-section announcements-section">
        <h2>Make Announcements</h2>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" placeholder="Enter name" required>
        </div>
        <div class="form-group">
            <label for="announcement">Announcement:</label>
            <textarea name="announcement" id="announcement" placeholder="Enter announcements..." required></textarea>
        </div>
        <button type="submit" name="announce" id="notify">Notify</button>
    </div>
    </form>

    <form method="POST" action="php\events.php" enctype="multipart/form-data">
    <div class="form-section">
        <h2>Add Events</h2>
        <div class="two-column">
            <div class="column">
                <div class="form-group">
                    <label for="event-name">Name of event:</label>
                    <input type="text" name="event_name" id="event-name" placeholder="Enter name of event" required>
                </div>
                <div class="form-group">
                    <label for="event-date">Date of event:</label>
                    <input type="date" name="event_date" id="event-date" required>
                </div>
                <div class="form-group">
                    <label for="event-address">Address:</label>
                    <div class="address-container">
                    <input type="text" name="event_address" id="event-address" placeholder="Enter address" required>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="form-group">
                    <label>Photo:</label>
                    <div class="photo-upload">
                    <input type="file" name="event_photo" id="event-photo" accept="image/*" required style="margin-left: 80px;">
                    </div>
                </div>
            </div>
        </div>
        <div style="text-align: center; margin-left: 180px;">
        <button type="submit" name="add_event" id="add-event">Add Event</button>
        </div>
    </div>
    </form>
        </main>
    </div>
    <br><br><br><br><br>
    <footer>
        <p>© 2025 Barangay E-Service System. All Rights Reserved.</p>
    </footer>
</body>
</html>