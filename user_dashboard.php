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
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];

$query = "
    SELECT 
        (
            (SELECT COUNT(*) FROM regular_documents WHERE user_id = ? AND status IN ('Pending', 'Processing', 'For Pick-Up')) +
            (SELECT COUNT(*) FROM special_documents WHERE user_id = ? AND status IN ('Pending', 'Processing', 'For Pick-Up'))
        ) AS on_process,
        (
            (SELECT COUNT(*) FROM regular_documents WHERE user_id = ? AND status = 'Completed') +
            (SELECT COUNT(*) FROM special_documents WHERE user_id = ? AND status = 'Completed')
        ) AS completed
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
    font-family: 'Montserrat', sans-serif;
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
    margin-top: ;
    flex: 1;
    padding: 20px;
}
.content h1{
   margin-right: 42%;
    margin-top: 8%;
    color: #12284B;
}

/* Request Cards */
.request-cards {
    margin-left: 25%;
    margin-top: 7%;
    display: grid;
    width: 70%; ;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 4%;
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

/* Events Section */
.events h2 {
    color: #162e55;
    margin-bottom: 20px;
    text-align: left;
    font-size: 28px;
    font-weight: bold;
    margin-left: 25%;
}

.event-list {
    margin-left: 25%;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    padding: 0;
    margin-bottom: 40px;
}

.event {
    background: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.event:hover {
    transform: translateY(-5px);
}

.event-image-container {
    position: relative;
    overflow: hidden;
}

.event img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.event:hover img {
    transform: scale(1.05);
}

.no-image {
    width: 100%;
    height: 200px;
    background-color: #eee;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #999;
}

.event h3 {
    font-size: 18px;
    color: #000;
    margin: 12px 15px;
    font-weight: bold;
}

/* Overlay styling */
.event-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 15px;
    transform: translateY(100%);
    transition: transform 0.3s ease;
    opacity: 0;
}

.event:hover .event-overlay {
    transform: translateY(0);
    opacity: 1;
}

.overlay-date {
    font-weight: bold;
    margin: 0 0 5px 0;
    font-size: 14px;
}

.overlay-location {
    margin: 0;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Add Event Form Styling */
.add-event-section {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin-top: 30px;
}

.add-event-form {
    display: grid;
    gap: 15px;
    max-width: 600px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.form-group label {
    font-weight: bold;
    color: #162e55;
}

.form-group input, .form-group textarea {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.submit-btn {
    background-color: #12284B;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    margin-top: 10px;
}

.submit-btn:hover {
    background-color: #0e1e3a;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .event-list {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .event-list {
        grid-template-columns: 1fr;
    }
    
    .add-event-form {
        grid-template-columns: 1fr;
    }
}
/* Contact Section */
.contact {
    text-align: center;
    margin-top: 70px;
    margin-left: -25%;
}

.contact p {
    color: grey;
    font-size: 13px;
    margin-bottom: -2%;
    margin-left: 33%;
    margin-top: 5%;
}

.contact h2 {
    font-size: 32px;
    margin-left: 33%;
    margin-top: 2%;
    margin-bottom: 2%;
}

.contact form {
    max-width: 400px;
    margin: 3% auto;
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
    margin-top: -25%;
    margin-left: 73%;
}

.seal img {
    height: 65%;
    width: 65%;
}

.sidebar ul li a.active {
    background: white; /* Background color for active link */
    color: #12284B;   /* Text color for active link */
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
                    <li><a href="user_dashboard.php" class="active"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="user_services.php"><i class="fas fa-concierge-bell"></i> Services</a></li>
                    <li><a href="user_myrequest.php"><i class="fas fa-tasks"></i> My Requests</a></li>
                    <li><a href="user_myrecords.php"><i class="fas fa-folder-open"></i> My Records</a></li>
                    <li><a href="user_announcements.php"><i class="fas fa-bell"></i> Notifications</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
        <div class="request-cards">
            <div class="card blue">
                <h3>ON PROCESS REQUEST</h3>
                <p><?php echo $on_process; ?></p> 
            </div>
            <div class="card green">
                <h3>COMPLETED REQUEST</h3>
                <p><?php echo $completed; ?></p>
            </div>
        </div>

        <section class="events">
    <h2>Upcoming Events</h2>
    <div class="event-list">
        <?php
        $servername = "localhost";
        $username = "root"; 
        $password = "";
        $dbname = "mambaling";
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Retrieve events from the database
        $sql = "SELECT event_name, event_date, event_address, event_photo FROM events ORDER BY event_date ASC";
        $result = $conn->query($sql);
        
        // Check if there are events to display
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $event_name = $row['event_name'];
                $event_date = $row['event_date'];
                $event_address = $row['event_address'];
                $event_photo = $row['event_photo']; // The image file name
        
                // Path to the image
                $photo_path = 'pictures/' . $event_photo;
                
                // Display event with hover effect
                echo "<div class='event'>";
                // Image container with overlay
                echo "<div class='event-image-container'>";
                if ($event_photo) {
                    echo "<img src='" . $photo_path . "' alt='" . $event_name . "'>";
                } else {
                    echo "<div class='no-image'>No image available</div>";
                }
                
                // Hover overlay with details
                echo "<div class='event-overlay'>";
                echo "<p class='overlay-date'>" . date('F j, Y', strtotime($event_date)) . "</p>";
                echo "<p class='overlay-location'><i class='fas fa-map-marker-alt'></i> " . $event_address . "</p>";
                echo "</div>";
                echo "</div>";
                
                // Event name below image
                echo "<h3>" . $event_name . "</h3>";
                echo "</div>";
            }
        } else {
            echo "<p>No events found.</p>";
        }
        
        $conn->close();
        ?>
    </div>
</section>

            <section class="contact">
    <p>Do you have concerns?</p>
    <h2>Contact Us</h2>
    
    <form action="https://api.web3forms.com/submit" method="POST">

        <input type="hidden" name="access_key" value="519b5612-fc12-471b-b34d-c800741d1a73">

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" placeholder="Enter name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter email" required>

        <label for="message">Message:</label>
        <textarea name="message" id="message" placeholder="Enter announcement..." required></textarea>

        <button type="submit">Submit</button>
    </form>
    <div class="seal">
        <img src="Images/logo.png" alt="Barangay Mambaling Official Seal">
        </div>
    </section>

        </main>
    </div>
    <br><br><br><br><br>
    <footer>
        <p>© 2025 Barangay E-Service System. All Rights Reserved.</p>
    </footer>
</body>
</html>
