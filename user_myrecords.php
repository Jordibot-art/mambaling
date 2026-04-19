<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

include('php/db_connection.php');

$user_id = $_SESSION['username'];

$document_table = "";

$query_special = "SELECT document_type, f_name, l_name, m_name, payment_method, created_at, status, id 
                  FROM special_documents 
                  WHERE user_id = ? AND (status = 'Approved' OR status = 'Completed') 
                  ORDER BY created_at DESC";

if ($stmt = $mysqli->prepare($query_special)) {
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $document_table .= "<tr>
            <td>" . htmlspecialchars($row['document_type']) . "</td>
            <td>" . htmlspecialchars($row['l_name'] . ' ' . $row['f_name'] . ' ' . $row['m_name']) . "</td>
            <td>" . htmlspecialchars($row['payment_method']) . "</td>
            <td>" . htmlspecialchars($row['created_at']) . "</td>
            <td><span class='status approved'>Completed</span></td>
            <td><button class='view-details' data-id='" . $row['id'] . "'>View Details</button></td>
        </tr>";
    }
    $stmt->close();
}

$query_regular = "SELECT document_type, first_name, last_name, middle_name, purpose, request_date, status, document_id 
                  FROM regular_documents 
                  WHERE user_id = ? AND (status = 'Approved' OR status = 'Completed') 
                  ORDER BY created_at DESC";

if ($stmt = $mysqli->prepare($query_regular)) {
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $document_table .= "<tr>
            <td>" . htmlspecialchars($row['document_type']) . "</td>
            <td>" . htmlspecialchars($row['last_name'] . ' ' . $row['first_name'] . ' ' . $row['middle_name']) . "</td>
            <td>N/A</td> <!-- Payment method does not exist in regular_documents -->
            <td>" . htmlspecialchars($row['request_date']) . "</td>
            <td><span class='status approved'>Completed</span></td>
            <td><button class='view-details' data-id='" . $row['document_id'] . "'>View Details</button></td>

        </tr>";
    }
    $stmt->close();
}

$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>My Records</title>
    <link rel="stylesheet" href="Css/request.css?v=<?php echo time(); ?>">
    <style>
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

        h2{
            color: #12284B
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function () {
    $('.view-details').click(function () {
        var docId = $(this).data('id');

        $.ajax({
            url: 'php/user_myrecords.php', // Updated PHP file for both document types
            type: 'POST',
            data: { doc_id: docId },
            success: function (response) {
                $('#details-content').html(response);
                $('#details-modal').fadeIn(300);
            },
            error: function () {
                alert('Failed to load document details.');
            }
        });
    });

    $('.close-modal').click(function () {
        $('#details-modal').fadeOut(300);
    });

    $(window).click(function (event) {
        if ($(event.target).is('#details-modal')) {
            $('#details-modal').fadeOut(300);
        }
    });
});

    </script>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="Images/logo.png" alt="Barangay Mambaling Logo" class="logo">
            <p>Barangay Mambaling E-Service System</p>
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
            <h1>User’s Panel</h1>
            <nav>
                <ul>
                <li><a href="user_dashboard.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="user_services.php"><i class="fas fa-concierge-bell"></i> Services</a></li>
                    <li><a href="user_myrequest.php"><i class="fas fa-tasks"></i> My Requests</a></li>
                    <li><a href="user_myrecords.php" class="active"><i class="fas fa-folder-open"></i> My Records</a></li>
                    <li><a href="user_announcements.php"><i class="fas fa-bell"></i> Notifications</a></li>
                </ul>
            </nav>
        </aside>

        <main class="content">
            <div class="requests-container">
                <h2>My Records</h2>
                <table class="requests-table">
                    <thead>
                        <tr>
                            <th>Type of Document</th>
                            <th>Client’s Name</th>
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
      <div id="details-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Document Details</h2>
            <div id="details-content"></div>
        </div>
    </div>

    <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    awdawdawa
    dwadwa
    <footer>
        <p>© 2025 Barangay E-Service System. All Rights Reserved.</p>
    </footer>

</body>
</html>
