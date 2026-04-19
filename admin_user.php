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

$search = isset($_POST['search']) ? $_POST['search'] : '';

$query = "SELECT * FROM users 
          WHERE username LIKE ? 
          OR first_name LIKE ? 
          OR last_name LIKE ? 
          ORDER BY 
          CASE 
          WHEN username = 'admin' THEN 0
          ELSE 1
          END, 
          username ASC";

$stmt = $mysqli->prepare($query);
$search_term = "%$search%";
$stmt->bind_param("sss", $search_term, $search_term, $search_term);

$user_table = '';

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user_table .= "<tr>
                <td>" . htmlspecialchars($row['username']) . "</td>
                <td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['role']) . "</td>
            </tr>";
        }
    } else {
        $user_table = "<tr><td colspan='4'>No users found.</td></tr>";
    }
    $result->close();
}

$stmt->close();
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

        .search-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
        }
        .search-bar input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            width: 200px;
        }

        .search-bar button {
            padding: 8px 12px;
            margin-left: 10px;
            border: none;
            background-color: #12284B;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .sidebar ul li a.active {
    background: white; /* Background color for active link */
    color: #12284B;   /* Text color for active link */
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
                <li><a href="admin_payment.php"><i class="fa fa-exchange"></i>Transactions</a></li>
                <li><a href="admin_completed.php"><i class="fa fa-check"></i>Completed</a></li>
                <li><a href="admin_user.php" class="active"><i class="fa fa-users"></i>User & Admin Accounts</a></li>
            </ul>
        </nav>
    </aside>

    <main class="content">
        <h2 style="color: #1a3b5d;">User Accounts</h2>

        <div class="search-bar">
            <form method="POST" action="admin_user.php">
                <input type="text" name="search" placeholder="Search by username or name" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <table class="user-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $user_table; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>
