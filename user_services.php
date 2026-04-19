<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel - Services</title>
    <link rel="stylesheet" href="Css/services.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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

.card {
    margin-left: 260px;
    border-radius: 8px;
    margin-bottom: 20px;
    position: relative;
}

.card-header {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    gap: 10px;
    color: #333;
}

.icon {
    color: #4CAF50;
    font-size: 20px;
}

.card-content {
    padding: 0 20px 20px;
    color: #666;
    font-size: 14px;
    line-height: 1.6;
}

.schedule {
    font-style: italic;
    margin: 15px 0;
    color: #555;
}

.center-list {
    margin: 10px 0;
}

.center-item {
    margin-bottom: 5px;
}

/* Healthcare specific */
.healthcare-icon {
    color: #4CAF50;
}

.hospital-image {
    position: absolute;
    right: 20px;
    top: 50px;
    width: 120px;
    height: 100px;
}

/* Waste management specific */
.waste-bins {
    display: flex;
    margin: 20px 0;
}

.bin {
    width: 60px;
    height: 80px;
    margin-right: 15px;
}

.waste-management-content {
    display: flex;
    justify-content: space-between;
}

.waste-text {
    width: 65%;
}

/* New section for community services */
.community-services {
    margin-top: 30px;
}

.services-grid {
    grid-template-columns: 1fr 1fr;
    margin-bottom: 30px;
}

@media (max-width: 768px) {
    .services-grid {
        grid-template-columns: 1fr;
    }
    
    .waste-management-content {
        flex-direction: column;
    }
    
    .waste-text {
        width: 100%;
    }
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
            <h1>User's Panel</h1>
            <nav>
                <ul>
                <li><a href="user_dashboard.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="user_services.php" class="active"><i class="fas fa-concierge-bell"></i> Services</a></li>
                    <li><a href="user_myrequest.php"><i class="fas fa-tasks"></i> My Requests</a></li>
                    <li><a href="user_myrecords.php"><i class="fas fa-folder-open"></i> My Records</a></li>
                    <li><a href="user_announcements.php"><i class="fas fa-bell"></i> Notifications</a></li>
                </ul>
            </nav>
        </aside>
        
        <main class="content">
            <h1>Our Services</h1>
            <div class="container">
                <!-- Left Side: Certificates Table -->
                <div class="certificate-section">
                    <h2 class="head">Certificates</h2>
                    <hr>

                    <!-- Regular Documents Table -->
                    <table class="certificate-table">
                        <tr>
                            <th>Regular Documents</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td>Barangay Residency</td>
                            <td>Free</td>
                            <td><button class="request-btn" onclick="submitRequest('Barangay Residency', 0)">Request</button></td>
                        </tr>
                        <tr>
                            <td>Barangay Clearance</td>
                            <td>Free</td>
                            <td><button class="request-btn" onclick="submitRequest('Barangay Clearance', 0)">Request</button></td>
                        </tr>
                        <tr>
                            <td>First Time Job Seeker</td>
                            <td>Free</td>
                            <td><button class="request-btn" onclick="submitRequest('First Time Job Seeker', 0)">Request</button></td>
                        </tr>
                        <tr>
                            <td>Certificate of Attestation</td>
                            <td>Free</td>
                            <td><button class="request-btn" onclick="submitRequest('Certificate of Attestation', 0)">Request</button></td>
                        </tr>
                    </table>
                    <hr>

                    <!-- Special Documents Table -->
                    <table class="certificate-table">
                        <tr>
                            <th>Special Documents</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <tr>
                                <td>Cedula</td>
                                <td>₱80</td>
                                <td><button class="request-btn" onclick="submitRequest('Cedula', 80)">Request</button></td>
                            </tr>
                            <td>Manhole</td>
                            <td>₱500</td>
                            <td><button class="request-btn" onclick="submitRequest('Manhole', 500)">Request</button></td>
                        </tr>
                        <tr>
                            <td>Building Permit</td>
                            <td>₱500</td>
                            <td><button class="request-btn" onclick="submitRequest('Building Permit', 500)">Request</button></td>
                        </tr>
                        <tr>
                            <td>Himo tabay</td>
                            <td>₱500</td>
                            <td><button class="request-btn" onclick="submitRequest('Himo tabay', 500)">Request</button></td>
                        </tr>
                        <tr>
                            <td>Himo poso</td>
                            <td>₱500</td>
                            <td><button class="request-btn" onclick="submitRequest('Himo poso', 500)">Request</button></td>
                        </tr>
                    </table>
                </div>

                <!-- Badge Section -->
                <div class="badge-section">
                    <img src="Images/document_logo.png" alt="Certificate Icon">
                    <h4>Request your needed certificate now!</h4>
                </div>
            </div>
              
            <div class="community-services">
                <div class="services-grid">
                    <!-- Healthcare Card -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-heartbeat icon healthcare-icon"></i>
                            <h2>Healthcare</h2>
                        </div>
                        
                        <div class="card-content">
                            <p>We are dedicated to providing accessible, efficient, and quality healthcare services to our community.</p>
                            <br>
                            <p>Our center offers medical consultations, preventive healthcare programs, vaccinations, and other essential health services to ensure the well-being of every resident.</p>
                            
                            <div class="schedule">
                                <strong>Healthcare Schedule:</strong> Monday to Friday(9 am to 5 pm)
                            </div>
                            
                            <div class="center-list">
                                <strong>Health Centers:</strong>
                                <div class="center-item">Mia Gautam Health Centre(Mia Gautam Street Nshankung, Cubu City, Cubu)</div>
                                <div class="center-item">Aloisia Health Center(Spa Plita, Mzuria, Marchipang, Cubu City, Cubu)</div>
                            </div>
                            
                            <!-- Hospital illustration -->
                            <svg class="hospital-image" viewBox="0 0 200 160" xmlns="http://www.w3.org/2000/svg">
                                <!-- Hospital Building -->
                                <rect x="40" y="60" width="120" height="100" fill="#f0f0f0" stroke="#ccc"/>
                                <rect x="70" y="120" width="60" height="40" fill="#f0f0f0" stroke="#ccc"/>
                                <rect x="90" y="140" width="20" height="20" fill="#f0f0f0" stroke="#ccc"/>
                                
                                <!-- Windows -->
                                <rect x="60" y="80" width="20" height="20" fill="#B3E5FC" stroke="#4FC3F7"/>
                                <rect x="120" y="80" width="20" height="20" fill="#B3E5FC" stroke="#4FC3F7"/>
                                <rect x="60" y="110" width="20" height="20" fill="#B3E5FC" stroke="#4FC3F7"/>
                                <rect x="120" y="110" width="20" height="20" fill="#B3E5FC" stroke="#4FC3F7"/>
                                
                                <!-- Hospital Sign -->
                                <rect x="90" y="60" width="20" height="20" fill="#4CAF50" stroke="#388E3C"/>
                                <line x1="100" y1="65" x2="100" y2="75" stroke="white" stroke-width="2"/>
                                <line x1="95" y1="70" x2="105" y2="70" stroke="white" stroke-width="2"/>
                                
                                <!-- Trees -->
                                <circle cx="30" cy="130" r="15" fill="#81C784"/>
                                <rect x="28" y="130" width="4" height="30" fill="#795548"/>
                                <circle cx="170" cy="130" r="15" fill="#81C784"/>
                                <rect x="168" y="130" width="4" height="30" fill="#795548"/>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Waste Management Card -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-trash-alt icon"></i>
                            <h2>Waste Management</h2>
                        </div>
                        
                        <div class="card-content">
                            <div class="waste-management-content">
                                <div class="waste-text">
                                    <p>Our township is committed to a clean and sustainable environment through efficient waste management. This is carefully system allows residents to report waste concerns, schedule garbage collection, and access guidelines for proper waste segregation. Together, we can promote responsible waste disposal and maintain a healthier community for everyone.</p>
                                    
                                    <div class="schedule">
                                        <strong>Garbage Collection Schedule:</strong> Thursday(9 am to 3 pm)
                                    </div>
                                </div>
                                
                                <div class="waste-bins">
                                    <!-- Blue Bin -->
                                    <svg class="bin" viewBox="0 0 60 80" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="10" y="20" width="40" height="60" fill="#2196F3" rx="5"/>
                                        <rect x="15" y="25" width="30" height="50" fill="#1E88E5" rx="3"/>
                                        <path d="M10 25 L50 25 L45 15 L15 15 Z" fill="#0D47A1"/>
                                        <circle cx="30" cy="40" r="8" fill="white"/>
                                        <path d="M30 35 L30 45 M25 40 L35 40" stroke="#0D47A1" stroke-width="2"/>
                                    </svg>
                                    
                                    <!-- Green Bin -->
                                    <svg class="bin" viewBox="0 0 60 80" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="10" y="20" width="40" height="60" fill="#4CAF50" rx="5"/>
                                        <rect x="15" y="25" width="30" height="50" fill="#43A047" rx="3"/>
                                        <path d="M10 25 L50 25 L45 15 L15 15 Z" fill="#1B5E20"/>
                                        <circle cx="30" cy="40" r="8" fill="white"/>
                                        <path d="M25 40 L35 40 M30 35 L30 45" stroke="#1B5E20" stroke-width="2"/>
                                    </svg>
                                    
                                    <!-- Red Bin -->
                                    <svg class="bin" viewBox="0 0 60 80" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="10" y="20" width="40" height="60" fill="#F44336" rx="5"/>
                                        <rect x="15" y="25" width="30" height="50" fill="#E53935" rx="3"/>
                                        <path d="M10 25 L50 25 L45 15 L15 15 Z" fill="#B71C1C"/>
                                        <circle cx="30" cy="40" r="8" fill="white"/>
                                        <path d="M25 35 L35 45 M25 45 L35 35" stroke="#B71C1C" stroke-width="2"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <div id="popupRegular" class="popup">
        <form action="php\regular_document.php" method="POST" class="popup-content">
            <span class="close" onclick="closePopup('popupRegular')">&times;</span>
            <h2>Regular Documents</h2>
            <label for="document_type">Document Type:</label>
            <select id="regular_document_type" name="document_type" required>
                <option value="Select Document" disabled selected>Select Document</option>
                <option value="Barangay Residency">Barangay Residency</option>
                <option value="Barangay Clearance">Barangay Clearance</option>
                <option value="First Time Job Seeker">First Time Job Seeker</option>
                <option value="Certificate of Attestation">Certificate of Attestation</option>
            </select>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required placeholder="Ramos">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required placeholder="John Ariel">
            <label for="middle_name">Middle Name (optional):</label>
            <input type="text" id="middle_name" name="middle_name" placeholder="(Leave blank if none)">
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required placeholder="19" required>
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <label for="address">Address:</label>
            <input type="address" id="address" name="address" placeholder="Tabada, Mambaling" required>
            <label for="years_of_residency">Years of Residency:</label>
            <select id="years_of_residency" name="years_of_residency" required>
                <option value="">Select Years</option>
                <option value="1">1 Year</option>
                <option value="2">2 Years</option>
                <option value="3">3 Years</option>
                <option value="5">5+ Years</option>
                <option value="10">10+ Years</option>
            </select>
            <label for="purpose">Purpose</label>
            <textarea name="purpose" rows="5" cols="42" required placeholder="Your Purpose"></textarea>
            <button type="submit" class="confirm-btn" onclick="confirmRequest('popupRegular')">Submit</button>
            <button type="button" class="cancel-btn" onclick="closePopup('popupRegular')">Cancel</button>
        </form>
    </div>
    <div id="popupSpecial" class="popup">
        <form id="specialDocumentForm" action="php\special_document.php" method="POST" class="popup-content">
            <span class="close" onclick="closePopup('popupSpecial')">&times;</span>
            <h2>Special Documents</h2>

            <label for="document_type">Document Type:</label>
            <select id="special_document_type" name="document_type" required>
                <option value="" disabled selected>Select Document</option>
                <option value="Cedula">Cedula</option>
                <option value="Manhole">Manhole</option>
                <option value="Building Permit">Building Permit</option>
                <option value="Himo tabay">Himo tabay</option>
                <option value="Himo poso">Himo poso</option>
            </select>

            <label for="l_name">Last Name:</label>
            <input type="text" id="l_name" name="l_name" required placeholder="Ramos">

            <label for="f_name">First Name:</label>
            <input type="text" id="f_name" name="f_name" required placeholder="John Ariel">

            <label for="m_name">Middle Name (optional):</label>
            <input type="text" id="m_name" name="m_name" placeholder="(Leave blank if none)">

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" placeholder="Makati City Office of the Building Official">

            <label for="place_of_issue">Place of Issue (City/Mun./Prov.):</label>
            <input type="text" id="place_of_issue" name="place_of_issue">

            <label for="citizen">Citizen:</label>
            <input type="text" id="citizen" name="citizen">

            <label for="place_of_birth">Place Of Birth:</label>
            <input type="text" id="place_of_birth" name="place_of_birth">

            <label for="date_of_birth">Date Of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth">

            <label for="height">Height:</label>
            <select id="height" name="height" required>
                <option value="" disabled selected>Select your height</option>
                <option value="5'6">5'6"</option>
                <option value="5'7">5'7"</option>
                <option value="5'8">5'8"</option>
                <option value="5'9">5'9"</option>
                <option value="6'0">6'0"</option>
            </select>

            <label for="weight">Weight:</label>
            <select id="weight" name="weight" required>
                <option value="" disabled selected>Select your weight (kg)</option>
                <option value="50">50 kg</option>
                <option value="60">60 kg</option>
                <option value="70">70 kg</option>
                <option value="80">80 kg</option>
            </select>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="Over the Counter">Over the Counter</option>
                <option value="Online Payment">Online Payment</option>
            </select>
            <label for="purpose">Purpose:</label>
            <textarea name="purpose" rows="5" cols="42" required placeholder="Your Purpose"></textarea>
            <button type="submit" class="confirm-btn">Submit and Pay</button>
            <button type="button" class="cancel-btn" onclick="closePopup('popupSpecial')">Cancel</button>
        </form>
    </div>

<script>
 let selectedDocument = "";

 function submitRequest(documentName, price) {
    // Special case for Cedula
    const isSpecial = documentName === "Manhole" || documentName === "Building Permit" || 
                      documentName === "Himo tabay" || documentName === "Himo poso" || documentName === "Cedula";

    const popupId = isSpecial ? "popupSpecial" : "popupRegular";
    const documentTypeId = isSpecial ? "special_document_type" : "regular_document_type";

    document.getElementById(documentTypeId).value = documentName;
    document.getElementById(popupId).style.display = "flex";
}
// Function to close the popup
function closePopup(popupId) {
    document.getElementById(popupId).style.display = "none";
}
</script>
</body>
</html>