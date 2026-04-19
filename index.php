<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Mambaling E-Service System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <style>
        /* Hero Image */
        .hero {
            margin-top: 80px;
            background-image: url("Images/heroimage.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #fff;
            text-align: center;
            padding: 3rem 1rem;
            scroll-margin-top: 80px;
        }

        .team-avatar {
            background-image: url("images/logo.png");
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #fff;
            margin-bottom: 30px;
        }
        .close-btn {
            position: absolute;
            top: 12px;
            right: 16px;
            background: none;
            border: none;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            color: black;
        }

    </style>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js" defer></script>
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <div class="logo"><a href=""><img src="images/logo.png" alt=""></a>
            <h1>Barangay Mambaling E-Service System</h1></div>
        <nav class="navbar">
            <ul>
                <li><a href="#hero">Home</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#faqs">FAQs</a></li>
                <li><a href="#" style="margin-left: 80px;" id="login">Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="popup">
        <!-- Login Form -->
        <div class="form-container login-container">
        <button class="close-btn">&times;</button>

            <h2 class="loginhead">BARANGAY MAMBALING E-SERVICE SYSTEM LOGIN</h2>
            <form action="php/login.php" method="post">
                <label class="labell">Username:</label>
                <input type="text" id="username-login" name="username" placeholder="Enter username or email" required>
                <label class="labell">Password:</label>
                <input type="password" id="password-login" name="password" placeholder="Enter password" required>
                <button type="submit" class="login-btn">Sign in</button>
                <p class="parg">Don't have an account? <a href="#" id="flip-to-register">Create one.</a></p>
            </form>
        </div>

        <!-- Register Form -->
        <div class="form-container register-container" id="forms">
        <button class="close-btn" style="margin-left: 340px">&times;</button>
            <h2 class="loginhead">BARANGAY MAMBALING E-SERVICE SYSTEM - REGISTER</h2>
            <form action="php/register.php" method="post">
                <div class="row">
                    <div class="column">
                        <label class="label2">Username:</label>
                        <input type="text" name="username" placeholder="Enter username">
                        <label class="label2">First Name:</label>
                        <input type="first_name" name="first_name" placeholder="Enter first name">
                        <label class="label2" style="margin-right: 255px;">Birthday:</label>
                        <input type="date" name="birthday">
                        <label class="label2" style="margin-right: 255px;">Address:</label>
                        <input type="text" name="address" placeholder="Enter address" class="add">
                        <label for="gender" style="margin-right: 250px;">Gender:</label>
                        <div class="genders">
                            <input type="radio" id="male" name="gender" value="Male">
                            <label for="male" class="val">Male</label>
                            <input type="radio" id="female" name="gender" value="Female">
                            <label for="female" class="val">Female</label>
                            <input type="radio" id="others" name="gender" value="Others">
                            <label for="others" class="val">Others</label>
                        </div>
                    </div>
                    <div class="column">
                        <label class="label3" style="margin-right: 275px;">Email:</label>
                        <input type="email" name="email" placeholder="Enter email">
                        <label class="label3" style="margin-right: 233px;">Last Name:</label>
                        <input type="last_name" name="last_name" placeholder="Enter last name">
                        <label class="label3" style="margin-right: 190px;">Contact Number:</label>
                        <input type="tel" name="contact_number" placeholder="Enter contact number">
                        <label class="label3" style="margin-right: 240px;">Password:</label>
                        <input type="password" name="password" placeholder="Enter password">
                    </div>
                </div>
                <button class="register-btn" type="submit">Register</button>
            </form>
            
            <p class="parg">Already have an account? <a href="#" id="flip-to-login">Login here</a></p>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero" id="hero">
        <div class="team-container">
            <div class="team-content">
                <div class="team-avatar"></div>
                <div class="team-info">
                    <h1>Welcome to Barangay Mambaling E-Service System</h1>
                    <div class="tags">
                        <span class="tag">Official Website</span>
                        <span class="tag"> Local Government</span>
                    </div>
                    <p class="par">Your one-stop solution for barangay services</p>
                </div>
            </div>
            <button class="learn-more" id="get-started">Get Started</button>
        </div>
    </section>

    <!-- Slider -->
    <div class="swiper">
        <h1>Fast, Easy, and Hassle-Free Barangay Services—Get Your Certificates Online Anytime, Anywhere!</h1>
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="Images/slider1.png" alt="">
            </div>
            <div class="swiper-slide">
                <img src="Images/slider2.png" alt="">
            </div>
            <div class="swiper-slide">
                <img src="Images/slider3.png" alt="">
            </div>
            <div class="swiper-slide">
                <img src="Images/slider4.png" alt="">
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <br><br><br><br>
    
    <section class="services" id="services">
        <div class="services-container">
            <img src="Images/logo.png" alt="Barangay Seal" class="services-seal">
            <div class="services-info">
                <h1>Our Services</h1>
                <p>Discover our offered services</p>
            </div>
        </div>

        <div class="service-cards">
            <div class="service-card">
                <img src="Images/certificate.jpg" alt="Barangay Certificates">
                <h3>Barangay Certificates</h3>
                <a href="test.html" class="apply-btn">Request</a>
            </div>
        
            <div class="service-card">
                <img src="Images/healthcare.jpg" alt="Healthcare" style="height: 330px;">
                <h3>Healthcare</h3>
                <a href="request1.html" class="apply-btn">Scheduled</a>
            </div>
        
            <div class="service-card">
                <img src="Images/waste management.avif" alt="Garbage Collector">
                <h3>Waste Management</h3>
                <a href="request_form.html" class="apply-btn">Scheduled</a>
            </div>
        </div>
    </section>

    <section class="about-section" id="about">
        <h4>Know more</h4>
        <h1>About Us</h1>
        <p><em>Easily request e-documents for you</em></p>
      
        <div class="about-container">
          <div class="about-card">
            <h2><i class="fa-solid fa-rocket" style="padding-right: 5px;"></i><strong>Our Mission</strong></h2>
            <p>To provide a fast, reliable, and secure online platform that simplifies the process of issuing certificates and other essential documents to the residents of our Barangay.</p>
          </div>
      
          <div class="about-card">
            <h2><i class="fa-solid fa-eye" style="padding-right: 5px;"></i><strong>Our Vision</strong></h2>
            <p>To provide a seamless, transparent, and reliable online platform that enables residents to easily apply for and receive various certificates. Safely and efficiently, enhancing the overall delivery of public services in our barangay.</p>
          </div>
      
          <div class="about-card">
            <h2><i class="fa-solid fa-bullseye" style="padding-right: 5px;"></i></i><strong>Our Goal</strong></h2>
            <p>We aim to provide a seamless, efficient, and accessible platform for residents to request and obtain barangay-issued certificates online.</p>
          </div>

          <div class="about-card">
            <h2><strong>Why choose us?</strong></h2>
            <p>We are committed to offering fast, reliable, and hassle-free certificate services that make your life easier.</p>
          </div>
        </div>
      </section>

    <section class="location">
        <h1>Your Barangay, Your Service—Accessible Anytime, Anywhere!</h1>
        <div class="maps">
            <iframe src="https://www.google.com/maps/embed?pb=!3m2!1sen!2sph!4v1743179548936!5m2!1sen!2sph!6m8!1m7!1sFFdo0egGxLviJWJCMC2jCQ!2m2!1d10.29244527774139!2d123.8768102214925!3f203.04793288091167!4f0.6231373207872508!5f0.7820865974627469" width="600" height="450" style="border-radius: 7px; border: 0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="info">
            <h5>Email:</h5>
            <p><i class="fa-solid fa-envelope" style="padding-right: 5px;"></i>barangaymambalingofficial@gmail.com</p>
            <h5>Hotline:</h5>
            <p><i class="fa-solid fa-phone" style="padding-right: 5px;"></i>314-1342</p>
            <h5>Address:</h5>
            <p><i class="fa-solid fa-location-dot"></i> 7VRG+RJ3, Ma. Gochan St, Cebu City, 6000 Cebu</p>
        </div>
     </section>
   
    <!--Faqs-->
    <section id="faqs" class="faq-section">
      <h1>FAQs</h1>
      <h4>Frequently Asked Questions</h4>
  
      <details>
          <summary>What is the Barangay Mambaling E-Service System?</summary>
          <img src="images/faqs1.png" alt=""><p>The Barangay E-Document Service System is an online platform that allows residents to request, process, and receive barangay documents such as barangay clearance, certificates of residency, indigency, and business permits without visiting the barangay hall physically.</p>
      </details>
  
      <details>
          <summary>How can I request a document online?</summary>
          <img src="images/faqs2.png" alt="" style="max-width: 60px; margin-top: 20px; margin-left: 15px;"><p>To request a document, log in to the system, select the type of document you need, fill out the required information, upload any necessary supporting documents, and submit your request. You will receive a notification once it is processed.</p>
      </details>
     
      <details>
          <summary>How long does it take to process my request?</summary>
          <img src="images/faqs3.png" alt=""><p>Processing times vary depending on the document type and barangay workload. Most requests are processed within 1-3 business days, and you will be notified once your document is ready for download or pickup.</p>
      </details>
  
      <details>
          <summary>Is there a fee for requesting barangay documents online?</summary>
          <img src="images/faqs4.png" alt=""><p>Some documents may have processing fees, while others may be free. The system will display the applicable fees before you submit your request. Payment can be made through online banking and e-wallets.</p>
      </details>

      <details>
        <summary>How will I receive my requested document?</summary>
        <img src="images/faqs5.png" alt=""><p>Once processed, you can either download a digitally signed copy through the system or pick up a printed copy at the barangay hall.</p>
    </details>
  </section>
    <script>
// Open login popup
// Show login popup

document.querySelector("#get-started").addEventListener("click", function () {
    document.querySelector(".popup").style.display = "flex";
    document.querySelector(".form-container.register-container").style.display = "none";
    document.querySelector(".form-container.login-container").style.display = "block";
});


document.querySelector("#login").addEventListener("click", function () {
    document.querySelector(".popup").style.display = "flex";
    document.querySelector(".form-container.register-container").style.display = "none";
    document.querySelector(".form-container.login-container").style.display = "block";
});

// Flip to register
document.getElementById("flip-to-register").addEventListener("click", () => {
    document.querySelector(".form-container.login-container").style.display = "none";
    document.querySelector(".form-container.register-container").style.display = "block";
});

// Flip back to login
document.getElementById("flip-to-login").addEventListener("click", () => {
    document.querySelector(".form-container.register-container").style.display = "none";
    document.querySelector(".form-container.login-container").style.display = "block";
});

document.querySelectorAll(".close-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
        document.querySelector(".popup").style.display = "none";
    });
});

    </script>
</body>
</html>