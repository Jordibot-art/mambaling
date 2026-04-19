<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

include('php/db_connection.php');

$user_id = $_SESSION['username'];

$query = "SELECT * FROM regular_documents WHERE user_id = ? AND status != 'Completed' ORDER BY created_at DESC";

$document_table = '';

if ($stmt = $mysqli->prepare($query)) {
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $status = htmlspecialchars($row['status']);
            $purpose = htmlspecialchars($row['purpose']);
            $doc_id = htmlspecialchars($row['document_id']);

            $document_table .= "<tr>
            <td>" . htmlspecialchars($row['document_type']) . "</td>
            <td>" . htmlspecialchars($row['last_name'] . ' ' . $row['first_name'] . ' ' . $row['middle_name']) . "</td>
            <td>" . $purpose . "</td>
            <td>" . htmlspecialchars($row['request_date']) . "</td>
            <td><span class='status " . str_replace(' ', '-', strtolower($status)) . "'>" . $status . "</span></td>
            <td>";
            if ($status !== 'Approved' && $status !== 'Denied' && $status !== 'Processing' && $status !== 'Completed') { 
                if ($status === 'For Pick-Up') {
                    $document_table .= "<a href='php\mark_received2.php?document_id=" . $doc_id . "' class='action-link received-btn' onclick='return confirmReceived()'>Received</a>";
                }else {
                    if ($status === 'Pending') {
                        $document_table .= "<a href='php\cancel_document1.php?document_id=" . $doc_id . "' class='action-link cancel-btn' onclick='return confirmCancel()'>Cancel</a>";
                    }
                }
            }
        
        $document_table .= "</td></tr>";
        }
    } else {
        $document_table = "<tr><td colspan='6'>No documents found.</td></tr>";
    }

    $stmt->close();
} else {
    $document_table = "<tr><td colspan='6'>Error preparing query: " . htmlspecialchars($mysqli->error) . "</td></tr>";
}

$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Mambaling E-Service System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
h2{
    color: #12284B;
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
 .received-btn {
     background-color: #28a745;
     color: white;
     padding: 6px 12px;
     border-radius: 4px;
     text-decoration: none;
     display: inline-block;
     transition: background-color 0.3s;
 }
 
 .received-btn:hover {
     background-color:#218838;
     color: white;
 
}.status.processing {
    color: blue;
}

.status.for-pick-up {
    color: green;
}
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
            flex: 1;
            padding: 20px;
        }
        .content h1{
           margin-right: 42%;
            margin-top: 8%;
            color: #12284B;
        }
        
.content {
    margin-top: 7%;
    margin-left: 20%;
    flex: 1;
    padding: 20px;
}
/* Footer */
footer {
    margin-top: 2%;
    background: #f3f4f6;;
    text-align: center;
    color: #162e55;
    margin-left: 17%;
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
.status.pending{
    color: orange;
}
.status.denied {
    color: #dc3545;
}
.btn {
    background: gray;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 5px;
}
.btn:hover {
    background: black;
}
.action-link {
    color: blue;
    text-decoration: none;
}

.sidebar ul li a.active {
    background: white; /* Background color for active link */
    color: #12284B;   /* Text color for active link */
}

/* Responsive adjustments */
@media screen and (max-width: 768px) {
    .container {
        flex-direction: column;
    }
    
    .sidebar {
        width: 100%;
        height: auto;
        position: static;
    }
    
    main {
        margin-left: 0;
    }
}

.separator {
    margin: 0 8px;
    font-weight: bold;
    color: #333; /* Dark grey */
    font-size: 16px;
}

.arrow-button {
    display: inline-block;
    width: 50px; /* Adjust size */
    height: 40px;
    background-color: #12284B; /* Background color */
    border: 2px solid black; /* Border color */
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    transition: background 0.3s ease;
    margin-top: 10px;
    margin-left: 10px;
}

.arrow-button::before {
    content: "←"; /* Right arrow */
    font-size: 20px;
    color: white; /* Arrow color */
    font-weight: bold;
}

.requests-header{
    display: flex;
    text-align: left;
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
                    <li><a href="user_myrequest.php" class="active"><i class="fas fa-tasks"></i> My Requests</a></li>
                    <li><a href="user_myrecords.php"><i class="fas fa-folder-open"></i> My Records</a></li>
                    <li><a href="user_announcements.php"><i class="fas fa-bell"></i> Notifications</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
            <div class="requests-container">
                <div class="requests-header">
                    <h2>Regular Documents</h2>
                    <a href="user_myrequest.php" class="arrow-button"></a>
                </div>
                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>Type of Document</th>
                            <th>Client's Name</th>
                            <th>Purpose</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $document_table; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
 <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <footer>
        <p>© 2025 Barangay E-Service System. All Rights Reserved.</p>
    </footer>

    <script>
    function confirmCancel() {
        return confirm("Are you sure you want to cancel this document?");
    }
    function confirmReceived() {
    return confirm("Have you received this document? This will mark it as completed.");
}
</script>

</body>
</html>
