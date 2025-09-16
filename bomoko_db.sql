-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2025 at 06:25 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bomoko_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `incident_reports`
--

CREATE TABLE `incident_reports` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `incident_type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `incident_date` date NOT NULL,
  `incident_address` varchar(255) NOT NULL,
  `reported_police` enum('yes','no','','') NOT NULL,
  `ob_number` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_reports`
--

INSERT INTO `incident_reports` (`id`, `full_name`, `email`, `phone`, `incident_type`, `description`, `incident_date`, `incident_address`, `reported_police`, `ob_number`, `created_at`) VALUES
(1, 'jonathan Bolonda', 'jntbolonda@gmail.com', '0757190777', 'lost_passport', 'Was stolen', '2025-06-01', 'langata', 'no', NULL, '2025-06-03 21:11:25'),
(2, 'Margaret Kariuki', 'Kariuki@gmail.com', '+254757190777', 'lost_passport', 'Lost in town', '2025-07-16', 'Nairobi', 'yes', NULL, '2025-08-06 11:43:25'),
(3, 'James Walumbe', 'james@gmail.com', '+2547656432', 'legal_issue', 'Visa expired', '2025-08-18', 'Nairobi', 'yes', '878976', '2025-08-26 07:34:52');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `from_email` varchar(100) NOT NULL,
  `to_email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `idx_to_email` varchar(100) NOT NULL,
  `idx_from_email` varchar(100) NOT NULL,
  `idx_created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `from_email`, `to_email`, `subject`, `message`, `is_read`, `created_at`, `idx_to_email`, `idx_from_email`, `idx_created_at`) VALUES
(0, 'dreq@gmail.com', 'jntbolonda@gmail.com', '<br /><b>Warning</b>:  Undefined variable $subject in <b>C:\\xampp\\htdocs\\bomoko\\send_message.php</b> on line <b>219</b><br />', 'Come tomorrow\r\n', NULL, '2025-08-21 23:03:20', '', '', '2025-08-21 23:03:20'),
(0, 'dreq@gmail.com', 'jntbolonda@gmail.com', '<br /><b>Warning</b>:  Undefined variable $subject in <b>C:\\xampp\\htdocs\\bomoko\\send_message.php</b> on line <b>219</b><br />', '<br />\r\n<b>Warning</b>:  Undefined variable $message in <b>C:\\xampp\\htdocs\\bomoko\\send_message.php</b> on line <b>224</b><br />\r\n', NULL, '2025-08-21 23:05:01', '', '', '2025-08-21 23:05:01'),
(0, 'dreq@gmail.com', 'jntbolonda@gmail.com', '<br /><b>Warning</b>:  Undefined variable $subject in <b>C:\\xampp\\htdocs\\bomoko\\send_message.php</b> on line <b>229</b><br />', 'Come tomorrow to start the retrieval process\r\n', NULL, '2025-08-21 23:10:12', '', '', '2025-08-21 23:10:12'),
(0, 'dreq@gmail.com', 'jntbolonda@gmail.com', '<br /><b>Warning</b>:  Undefined variable $subject in <b>C:\\xampp\\htdocs\\bomoko\\send_message.php</b> on line <b>229</b><br />', 'Well received', NULL, '2025-08-21 23:11:49', '', '', '2025-08-21 23:11:49'),
(0, 'dreq@gmail.com', 'jntbolonda@gmail.com', 'sorry', 'Jonathan, come to the office tomorrow\r\n', NULL, '2025-08-21 23:14:15', '', '', '2025-08-21 23:14:15'),
(0, 'dreq@gmail.com', 'jntbolonda@gmail.com', '<br /><b>Warning</b>:  Undefined variable $subject in <b>C:\\xampp\\htdocs\\bomoko\\send_message.php</b> on line <b>220</b><br />', 'Come to church', NULL, '2025-08-21 23:20:06', '', '', '2025-08-21 23:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `place_of_birth` varchar(100) NOT NULL,
  `passport_number` varchar(50) NOT NULL,
  `visa_type` varchar(50) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `residential_address` varchar(255) NOT NULL,
  `county` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `gender`, `dob`, `place_of_birth`, `passport_number`, `visa_type`, `phone`, `email`, `residential_address`, `county`, `password`, `created_at`, `is_admin`) VALUES
(4, 'Jonathan', 'Mongu', 'Bolonda', 'male', '2000-04-30', 'Kinshasa', 'OP1243564', 'tourist', '07571908765', 'jntbolonda@gmail.com', 'Karen, Nairobi', 'Nairobi City', '6a1a6b9e893c2472e6d58cb11836d49962e48d3e31071eb6b4f6c4601aa6f39f', '2025-08-04 22:00:12', 1),
(5, 'Fatuma', 'Seif', 'Rashid', 'female', '1999-09-20', 'Nairobi', 'OP1234355', 'work_permit', '+254757190777', 'seif@gmail.com', 'Nairobi Kinshasa', 'Nairobi City', 'ff05be19e604f16a385027ecaee3c47f17bb4fa9e840b5134dbc6f0a49e33073', '2025-08-06 10:15:47', 0),
(6, '1234', 'Kariuki', 'Nyambura', 'female', '2025-02-01', 'Nairobi', 'OP1234544', 'permanent_residence', '+2546756444', 'kariuki@gmail.com', 'Nairobi', 'Nakuru', 'dcd2380043ce6bf60cc4889816754be2b5d3fd09f64d62f045285219cc0b9252', '2025-08-06 11:41:18', 0),
(7, 'Jonathan', 'Dreq', 'Bolonda', 'male', '1999-04-30', 'Kinshasa', 'OP1234327', 'tourist', '+254757190777', 'dreq@gmail.com', 'Langata road link', 'Nairobi City', '6a1a6b9e893c2472e6d58cb11836d49962e48d3e31071eb6b4f6c4601aa6f39f', '2025-08-21 20:40:03', 1),
(8, 'James', 'Wambaya', 'Walumbe', 'male', '2025-08-07', 'Kinshasa', 'OP1236756', 'student', '+2547656432', 'james@gmail.com', 'Nairobi', 'Nairobi City', '338c8bf01f4552dff1d4b2eed84c7a38c3a5f001604804fba47e3d28fc6ad4f5', '2025-08-26 07:29:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `visa_applications`
--

CREATE TABLE `visa_applications` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `country` varchar(100) NOT NULL,
  `passport_number` varchar(50) NOT NULL,
  `visa_type` varchar(50) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `travel_date` date NOT NULL,
  `reason` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visa_applications`
--

INSERT INTO `visa_applications` (`id`, `full_name`, `email`, `gender`, `dob`, `country`, `passport_number`, `visa_type`, `duration`, `travel_date`, `reason`, `created_at`, `status`) VALUES
(1, 'Jonathan Bolonda', 'jntbolonda@gmail.com', 'male', '2000-04-30', 'Kenya', 'OP1291444', 'permanent_residence', '6 months', '2025-06-15', 'get paper work done', '2025-06-04 23:03:43', 'approved'),
(2, 'Jonathan Bolonda', 'dreq@gmail.com', 'male', '2000-06-30', 'Kenya', 'OP1234327', 'work_permit', '3 years', '2025-11-30', 'Work for a manufacturing company', '2025-08-21 22:00:02', 'approved'),
(3, 'Jonathan Bolonda', 'dreq@gmail.com', 'male', '2000-06-30', 'Kenya', 'OP1234327', 'work_permit', '3 years', '2025-11-30', 'Working for 3 years', '2025-08-26 07:31:36', 'rejected'),
(4, 'Jonathan Bolonda', 'dreq@gmail.com', 'male', '2000-04-30', 'Kenya', 'OP1234354', 'work_permit', '3 years', '2025-08-31', 'Working in IT', '2025-08-26 07:32:45', '');

-- --------------------------------------------------------

--
-- Table structure for table `visa_documents`
--

CREATE TABLE `visa_documents` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `passport_pdf` varchar(255) NOT NULL,
  `supporting_pdf` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visa_documents`
--

INSERT INTO `visa_documents` (`id`, `email`, `passport_pdf`, `supporting_pdf`, `uploaded_at`) VALUES
(1, '', 'uploads/passport_6840d21a8217a.pdf', 'uploads/support_6840d21a8217f.pdf', '2025-06-04 23:09:14'),
(2, '', 'uploads/passport_68a797a0f2624.pdf', 'uploads/support_68a797a0f2629.pdf', '2025-08-21 22:03:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visa_applications`
--
ALTER TABLE `visa_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visa_documents`
--
ALTER TABLE `visa_documents`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `incident_reports`
--
ALTER TABLE `incident_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `visa_applications`
--
ALTER TABLE `visa_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `visa_documents`
--
ALTER TABLE `visa_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
