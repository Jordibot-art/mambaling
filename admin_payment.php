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

// Query to fetch all transactions, ordered by created date
$query = "SELECT * FROM tbltransactions ORDER BY created_at DESC";

$transaction_table = ''; // Variable to hold the HTML table rows

// Execute the query
if ($result = $mysqli->query($query)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $transaction_type = htmlspecialchars($row['document_type']);
            $client_name = htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']);
            $transaction_date = htmlspecialchars($row['created_at']);
            $transaction_id = htmlspecialchars($row['id']);

            $status = trim($row['status']); // Remove extra whitespace

            if (empty($status)) {
                $status_display = 'Transaction Under Review'; // default fallback
            } else {
                switch ($status) {
                    case 'Payment Successful':
                        $status_display = 'Payment Successful';
                        break;
                    case 'Payment Failed':
                        $status_display = 'Payment Failed';
                        break;
                    case 'Transaction Under Review':
                        $status_display = 'Transaction Under Review';
                        break;
                    default:
                        $status_display = 'Unknown Status (' . htmlspecialchars($status) . ')';
                        break;
                }
            }
            $status_class = strtolower(str_replace(" ", "_", $status_display)); // Convert status to class name

            // Add a new row to the transaction table
            $transaction_table .= "<tr>
                <td>" . $transaction_type . "</td>
                <td>" . $client_name . "</td>
                <td>" . $transaction_date . "</td>
                <td><span class='status " . $status_class . "'>" . $status_display . "</span></td>
                <td>";

            // Add buttons for 'Transaction Under Review'
            if ($status_display == "Transaction Under Review") {
                $transaction_table .= "<button class='paid-btn' data-id='" . $transaction_id . "'>Mark as Paid</button>
                                       <button class='failed-btn' data-id='" . $transaction_id . "'>Mark as Failed</button>";
            } else {
                $transaction_table .= "<button class='view-btn' disabled>Viewed</button>";  // For other statuses
            }

            $transaction_table .= "</td></tr>";
        }
    } else {
        $transaction_table = "<tr><td colspan='5'>No transactions found.</td></tr>";
    }
    $result->close();
} else {
    echo "Error: " . $mysqli->error;
}

$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Special Documents</title>
    <link rel="stylesheet" href="Css/admin_payment.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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
        <p>Barangay Mambaling E-Service System</p>
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
        <h1>Admin's Panel</h1>
        <nav>
            <ul>
            <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="admin_special_request.php"><i class="fas fa-concierge-bell"></i> Requests</a></li>
                <li><a href="admin_announcements.php"><i class="fas fa-tasks"></i> Announcements</a></li>
                <li><a href="admin_payment.php" class="active"><i class="fa fa-exchange"></i>Transactions</a></li>
                <li><a href="admin_completed.php"><i class="fa fa-check"></i>Completed</a></li>
                <li><a href="admin_user.php"><i class="fa fa-users"></i>User & Admin Accounts</a></li>
            </ul>
        </nav>
    </aside>

    <main class="content">
        <div class="requests-header">
            <h2 style="color: #1a3b5d;">All Transactions</h2>
        </div>
        <table class="requests-table">
            <thead>
                <tr>
                    <th>Transaction Type</th>
                    <th>Client's Name</th>
                    <th>Transaction Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $transaction_table; ?>
            </tbody>
        </table>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.paid-btn').click(function() {
        var transactionId = $(this).data('id');
        updateStatus(transactionId, 'Payment Successful');  // Correct status value here
    });
    
    $('.failed-btn').click(function() {
        var transactionId = $(this).data('id');
        updateStatus(transactionId, 'Payment Failed');  // Correct status value here
    });
    function updateStatus(id, status) {
        $.ajax({
            url: 'php/update_status.php',
            type: 'POST',
            data: { id: id, status: status },
            success: function(response) {
                location.reload(); // Reload the page to reflect the new status
            },
            error: function(xhr, status, error) {
                alert('Error updating status');
            }
        });
    }
});

</script>
</body>
</html>
