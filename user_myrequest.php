<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

include('php/db_connection.php');

$user_id = $_SESSION['username'];

$query = "SELECT * FROM special_documents WHERE user_id = ? AND status != 'Completed' ORDER BY created_at DESC";

$document_table = '';

if ($stmt = $mysqli->prepare($query)) {
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $status = htmlspecialchars($row['status']);
            $payment_method = htmlspecialchars($row['payment_method']);
            $doc_id = htmlspecialchars($row['id']);

            $document_table .= "<tr>
            <td>" . htmlspecialchars($row['document_type']) . "</td>
            <td>" . htmlspecialchars($row['l_name'] . ' ' . $row['f_name'] . ' ' . $row['m_name']) . "</td>
            <td>" . $payment_method . "</td>
            <td>" . htmlspecialchars($row['created_at']) . "</td>
            <td><span class='status " . str_replace(' ', '-', strtolower($status)) . "'>" . $status . "</span></td>
            <td>";
            if ($status !== 'Approved' && $status !== 'Denied' && $status !== 'Processing' && $status !== 'Completed') { 
                if ($status === 'For Pick-Up') {
                    $document_table .= "<a href='php/mark_received.php?doc_id=" . $doc_id . "' class='action-link received-btn' onclick='return confirmReceived()'>Received</a>";
                } else if ($payment_method === 'Over the Counter') {
                    $document_table .= "<a href='php/cancel_document.php?doc_id=" . $doc_id . "' class='action-link cancel-btn' onclick='return confirmCancel()'>Cancel</a>";
                } else {
                    if ($status === 'Pending') {
                        $document_table .= "<a href='php/payment_proccess.php?doc_id=" . $doc_id . "' class='action-link pay-btn'>Pay Now</a>
                        <span class='separator'>|</span>
                        <a href='php/cancel_document.php?doc_id=" . $doc_id . "' class='action-link cancel-btn' onclick='return confirmCancel()'>Cancel</a>";
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
    <link rel="stylesheet" href="Css/request.css?v=<?php echo time(); ?>">
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
                    <h2>Special Documents</h2>
                    <a href="user_myrequest1.php" class="arrow-button"></a>
                </div>
                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>Type of Document</th>
                            <th>Client's Name</th>
                            <th>Payment</th>
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
