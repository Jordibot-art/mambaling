-- phpMyAdmin SQL Dump
-- Database: `mambaling`
-- Barangay Mambaling E-Service System

CREATE DATABASE IF NOT EXISTS `mambaling` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mambaling`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `birthday` varchar(50) DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `status` varchar(50) NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
-- Default admin: admin / admin123
-- Default test user: john_doe / user123
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `middle_name`, `last_name`, `birthday`, `contact_number`, `address`, `gender`, `role`, `status`, `created_at`) VALUES
(1, 'admin', 'admin@mambaling.gov.ph', '$2y$10$4tj6.q.Sq13K7wb8arnuO.eOCCSv31K2TIkcFUVa3Bw2dQM1EjBsC', 'Barangay', 'E-Service', 'Admin', '1990-01-01', '09123456789', 'Barangay Mambaling, Cebu City', 'Male', 'admin', 'Active', NOW()),
(2, 'john_doe', 'johndoe@example.com', '$2y$10$/NQ561F/5BZM/neuXdjwCOMYX1kNFo1cVTZQs76EHHbeFxPJY0b/6', 'John', 'Cruz', 'Doe', '1998-05-15', '09987654321', 'Sitio Alaska, Mambaling, Cebu City', 'Male', 'user', 'Active', NOW());

-- --------------------------------------------------------

--
-- Table structure for table `regular_documents`
--

CREATE TABLE IF NOT EXISTS `regular_documents` (
  `document_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(100) NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `years_of_residency` varchar(50) NOT NULL,
  `purpose` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `request_date` date NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`document_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `special_documents`
--

CREATE TABLE IF NOT EXISTS `special_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) DEFAULT NULL,
  `user_id` varchar(100) NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `f_name` varchar(100) NOT NULL,
  `m_name` varchar(100) DEFAULT NULL,
  `l_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `place_of_issue` varchar(255) DEFAULT NULL,
  `citizen` varchar(100) DEFAULT NULL,
  `place_of_birth` varchar(255) DEFAULT NULL,
  `date_of_birth` varchar(50) DEFAULT NULL,
  `height` varchar(50) DEFAULT NULL,
  `weight` varchar(50) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `additional_notes` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbltransactions`
--

CREATE TABLE IF NOT EXISTS `tbltransactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(50) NOT NULL DEFAULT 'Transaction Under Review',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `status_notifications`
--

CREATE TABLE IF NOT EXISTS `status_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `announcement` text NOT NULL,
  `date_posted` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) NOT NULL,
  `event_date` varchar(100) NOT NULL,
  `event_address` varchar(255) NOT NULL,
  `event_photo` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Legacy tables (for compatibility with earlier versions)
--

CREATE TABLE IF NOT EXISTS `document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_type` varchar(100) DEFAULT NULL,
  `client_name` varchar(150) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `years` varchar(50) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `document_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_type` varchar(100) DEFAULT NULL,
  `client_name` varchar(150) DEFAULT NULL,
  `payment` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `years` varchar(50) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
