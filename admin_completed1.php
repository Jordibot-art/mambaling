<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

if ($_SESSION['role'] !== 'admin') {  
    header("Location: user_dashboard.php");
    exit;
}


include('php/db_connection.php');

// Query to fetch all completed documents
$query = "SELECT * FROM regular_documents WHERE status = 'Completed' ORDER BY created_at DESC";
$completed_documents = '';

if ($result = $mysqli->query($query)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $completed_documents .= "<tr>
                <td>" . htmlspecialchars($row['document_type']) . "</td>
                <td>" . htmlspecialchars($row['last_name'] . ' ' . $row['first_name'] . ' ' . $row['middle_name']) . "</td>
                <td>" . htmlspecialchars($row['purpose']) . "</td>
                <td>" . htmlspecialchars($row['request_date']) . "</td>
                <td><span class='status completed'>" . $row['status'] . "</span></td>
                <td>
                    <button class='view-details' data-id='" . $row['document_id'] . "'>View Details</button>
                </td>
            </tr>";
        }
    } else {
        $completed_documents = "<tr><td colspan='5'>No completed documents found.</td></tr>";
    }
    $result->close();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Special Documents</title>
    <link rel="stylesheet" href="Css/special_request.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
.approve-btn, .deny-btn {
    display: inline-block;
    padding: 10px 15px;
    margin-top: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    color: white;
}

.approve-btn {
    background-color: #28a745;
}

.deny-btn {
    background-color: #dc3545;
}

.approve-btn:hover {
    background-color: #218838;
}

.deny-btn:hover {
    background-color: #c82333;
}


/* Modal Background */
.modal {
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

/* Modal Content */
.modal-content {
    background: #fff;
    padding: 25px;
    width: 500px;
    border-radius: 8px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
    position: relative;
    max-height: 90vh;
    overflow-y: auto;
}

/* Close Button */
.close-modal {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    background: #ff4d4d;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 50%;
}

/* Title Styling */
.modal-content h2 {
    text-align: center;
    font-size: 22px;
    margin-bottom: 15px;
}

/* Form Styling */
.modal-content form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

/* Label Styling */
.modal-content label {
    font-weight: bold;
    font-size: 14px;
}

/* Input Fields */
.modal-content input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    background: #f9f9f9;
    cursor: not-allowed;
}

/* Button Styling */
.modal-content .close-modal:hover {
    background: #cc0000;
}
.view-details {
    background: #007bff;
    color: white;
    padding: 10px 14px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    border: none;
    transition: background 0.3s ease, transform 0.2s ease;
}

.view-details:hover {
    background: #0056b3;
    transform: scale(1.05);
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
    content: "←"; /* Left arrow */
    font-size: 20px;
    color: white; /* Arrow color */
    font-weight: bold;
}

.requests-header{
    display: flex;
    text-align: left;
}
.sidebar ul li a.active {
    background: white; /* Background color for active link */
    color: #12284B;   /* Text color for active link */
}
h2 {
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

.completed-btn {
    display: inline-block;
    padding: 10px 15px;
    margin-top: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    color: white;
    background-color: green; /* Blue color for completed button */
}

.completed-btn:hover {
    background-color:rgb(8, 136, 68);
}

/* Update status colors in the main table if needed */
.status.processing {
    color:#0f4294;
}

/* Add this to your existing CSS styles */
.status.for-pick-up {
    color: #0f4294;
}
.status.completed{
    color: #08D467;
}
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
       $(document).ready(function () {
    $('.view-details').click(function () {
        var docId = $(this).data('id'); // Ensure the button has `data-id`
        
        $.ajax({
            url: 'php/get_documents_details2.php',
            type: 'POST',
            data: { document_id: docId }, // Ensure this matches PHP's $_POST['document_id']
            success: function (response) {
                $('#details-content').html(response);
                $('#details-modal').fadeIn();
            },
            error: function () {
                alert('Failed to load document details.');
            }
        });
    });

    $('.close-modal').click(function () {
        $('#details-modal').fadeOut();
    });

    // Close modal when clicking outside of it
    $(window).click(function (event) {
        if ($(event.target).is('#details-modal')) {
            $('#details-modal').fadeOut();
        }
    });
});
    </script>
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
            <h1>Admin's Panel</h1    >
            <nav>
                <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-home" ></i> Dashboard</a></li>
                <li><a href="admin_special_request.php"><i class="fas fa-concierge-bell"></i> Requests</a></li>
                <li><a href="admin_announcements.php"><i class="fas fa-tasks"></i> Announcements</a></li>
                <li><a href="admin_payment.php"><i class="fa fa-exchange"></i>Transactions</a></li>
                <li><a href="admin_completed.php" class="active"><i class="fa fa-check"></i>Completed</a></li>
                <li><a href="admin_user.php"><i class="fa fa-users"></i>User & Admin Accounts</a></li>
                </ul>
            </nav>
        </aside>

            <main class="content">
            <div class="requests-header">
                    <h2>All Completed Regular Documents Requests</h2>
                    <a href="admin_completed.php" class="arrow-button"></a>
            </div>
            <table>
            <thead>
                <tr>
                    <th>Document Type</th>
                    <th>Client's Name</th>
                    <th>Payment Method</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $completed_documents; ?>
            </tbody>
        </table>
        </main>
    </div>
    
    <!-- Modal for Viewing Details -->
    <div id="details-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Document Details</h2>
            <div id="details-content"></div>
        </div>
    </div>

    <script>
$(document).ready(function () {
    $(document).on('click', '.approve-btn, .deny-btn, .completed-btn', function () {
        var docId = $(this).data('id');
        var status = '';
        
        if ($(this).hasClass('approve-btn')) {
            status = 'approve';
        } else if ($(this).hasClass('deny-btn')) {
            status = 'deny';
        } else if ($(this).hasClass('completed-btn')) {
            status = 'completed';
        }

        $.ajax({
            url: 'php/update_document2_status.php',
            type: 'POST',
            data: { document_id: docId, status: status },
            success: function (response) {
                console.log("Server response:", response); // Debugging log
                if (response.trim() === "success") {
                    location.reload();
                } else {
                    alert("Error updating document: " + response);
                }
            }
        });
    });
});
    </script>
</body>
</html>