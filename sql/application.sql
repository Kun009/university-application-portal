-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2024 at 08:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `application`
--

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `sid` int(11) NOT NULL,
  `session` varchar(50) NOT NULL,
  `postingdate` date NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`sid`, `session`, `postingdate`, `status`) VALUES
(19, 'September/October Intake ', '0000-00-00', 0),
(21, 'January/February Intake', '0000-00-00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_applicant`
--

CREATE TABLE `tbl_applicant` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `mobile_no` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `country` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `marital_status` enum('single','married','divorced','widowed') DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `passport_photo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_applicant`
--

INSERT INTO `tbl_applicant` (`id`, `first_name`, `last_name`, `middle_name`, `email`, `mobile_no`, `address`, `country`, `date_of_birth`, `gender`, `marital_status`, `qualification`, `passport_photo`, `password`) VALUES
(1, 'Alice', 'Johnson', 'Marie', 'alice.johnson@example.com', '+12345678901', '123 Maple St, Cityville', 'United Kingdom', '1999-01-15', 'female', 'single', 'Bachelor\'s', 'alice.jpg', 'Alice'),
(2, 'Bob', 'Smith', 'William', 'bob.smith@example.com', '+12345678902', '456 Oak St, Townsville', 'United Kingdom', '1994-05-22', 'male', 'married', 'Master\'s', 'bob.jpg', 'Bob'),
(3, 'Charlie', 'Brown', 'Robert', 'charlie.brown@example.com', '+12345678903', '789 Pine St, Villageville', 'United Kingdom', '1996-11-30', 'male', 'single', 'PhD', 'charlie.jpg', 'Charlie'),
(4, 'Diana', 'Clark', 'Anne', 'diana.clark@example.com', '+12345678904', '321 Birch St, Hamletville', 'United Kingdom', '1999-08-10', 'female', 'single', 'Bachelor\'s', 'diana.jpg', 'Diana'),
(5, 'Ethan', 'Davis', 'Michael', 'ethan.davis@example.com', '+12345678905', '654 Cedar St, Countyville', 'United Kingdom', '1992-02-25', 'male', 'married', 'Master\'s', 'ethan.jpg', 'Ethan'),
(6, 'Fiona', 'Evans', 'Grace', 'fiona.evans@example.com', '+12345678906', '987 Elm St, Stateville', 'United Kingdom', '1997-04-17', 'female', 'single', 'Bachelor\'s', 'fiona.jpg', 'Fiona'),
(7, 'George', 'Garcia', 'Hugo', 'george.garcia@example.com', '+12345678907', '135 Ash St, Countryville', 'United Kingdom', '1995-12-05', 'male', 'single', 'Master\'s', 'george.jpg', 'George'),
(8, 'Hannah', 'Martinez', 'Lily', 'hannah.martinez@example.com', '+12345678908', '246 Willow St, Citytown', 'United Kingdom', '2000-07-23', 'female', 'single', 'Bachelor\'s', 'hannah.jpg', 'Hannah'),
(9, 'Ian', 'Rodriguez', 'Carlos', 'ian.rodriguez@example.com', '+12345678909', '357 Spruce St, Towncity', 'United Kingdom', '1996-10-15', 'male', 'single', 'MSc', 'ian.jpg', 'Ian'),
(10, 'Jessica', 'Lopez', 'Sarah', 'jessica.lopez@example.com', '+12345678910', '468 Poplar St, Villagecity', 'United Kingdom', '1993-03-09', 'female', 'married', 'Master\'s', 'jessica.jpg', 'Jessica');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_applications`
--

CREATE TABLE `tbl_applications` (
  `id` int(11) NOT NULL,
  `applicant_id` int(12) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `middleName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `program` varchar(100) NOT NULL,
  `degreeType` enum('Bachelor','Masters','PhD') NOT NULL,
  `session` varchar(50) NOT NULL,
  `universityId` int(11) NOT NULL,
  `disciplinaryIssues` enum('yes','no') NOT NULL,
  `felonyCharge` enum('yes','no') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transcripts` varchar(255) DEFAULT NULL,
  `diploma` varchar(255) DEFAULT NULL,
  `statement_of_purpose` varchar(255) DEFAULT NULL,
  `recommendation_letters` varchar(255) DEFAULT NULL,
  `standardized_test_scores` varchar(255) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `personal_statement` varchar(255) DEFAULT NULL,
  `proof_of_english_proficiency` varchar(255) DEFAULT NULL,
  `financial_documents` varchar(255) DEFAULT NULL,
  `passport_copy` varchar(255) DEFAULT NULL,
  `admissionstatus` enum('submitted','processing','denied','admitted') DEFAULT 'submitted',
  `admissionLetter` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_applications`
--

INSERT INTO `tbl_applications` (`id`, `applicant_id`, `firstName`, `middleName`, `lastName`, `email`, `program`, `degreeType`, `session`, `universityId`, `disciplinaryIssues`, `felonyCharge`, `created_at`, `updated_at`, `transcripts`, `diploma`, `statement_of_purpose`, `recommendation_letters`, `standardized_test_scores`, `resume`, `personal_statement`, `proof_of_english_proficiency`, `financial_documents`, `passport_copy`, `admissionstatus`, `admissionLetter`) VALUES
(27, 9, 'Ian', 'Carlos', 'Rodriguez', 'ian.rodriguez@example.com', 'Mathematics', 'Masters', '18', 1, 'no', 'no', '2024-11-04 07:47:31', '2024-11-04 08:21:01', 'Transcript.docx', 'Certificate.docx', NULL, 'Recommendation.docx', 'S Test.docx', 'Resume.docx', 'SOP.docx', 'English Proficiency.docx', 'Financial Documents.docx', 'Passport.docx', 'admitted', '../admission/uploads/admission letter.docx'),
(28, 9, 'Ian', 'Carlos', 'Rodriguez', 'ian.rodriguez@example.com', 'Computer Science', 'Masters', '18', 2, 'no', 'no', '2024-11-04 07:47:49', '2024-11-04 07:47:49', 'Transcript.docx', 'Certificate.docx', NULL, 'Recommendation.docx', 'S Test.docx', 'Resume.docx', 'SOP.docx', 'English Proficiency.docx', 'Financial Documents.docx', 'Passport.docx', 'submitted', ''),
(29, 1, 'Alice', 'Marie', 'Johnson', 'alice.johnson@example.com', 'Computer Science', 'Masters', '18', 1, 'no', 'no', '2024-11-04 07:51:55', '2024-11-04 08:21:23', 'Transcript.docx', 'Certificate.docx', NULL, 'Recommendation.docx', 'S Test.docx', 'Resume.docx', 'SOP.docx', 'English Proficiency.docx', 'Financial Documents.docx', 'Passport.docx', 'denied', '../admission/uploads/admission letter.docx'),
(30, 2, 'Bob', 'William', 'Smith', 'bob.smith@example.com', 'Pharmacy', 'Bachelor', '18', 1, 'no', 'no', '2024-11-04 08:19:48', '2024-11-04 08:19:48', 'Transcript.docx', 'Certificate.docx', NULL, 'Recommendation.docx', 'S Test.docx', 'Resume.docx', 'SOP.docx', 'English Proficiency.docx', 'Financial Documents.docx', 'Passport.docx', 'submitted', ''),
(31, 4, 'Diana', 'Anne', 'Clark', 'diana.clark@example.com', 'Chemical Engineering', 'PhD', 'September/October Intake ', 1, 'no', 'no', '2024-11-08 16:27:30', '2024-11-08 16:27:30', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', NULL, 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'submitted', ''),
(32, 4, 'Diana', 'Anne', 'Clark', 'diana.clark@example.com', 'Nursing', 'Bachelor', 'September/October Intake ', 3, 'no', 'no', '2024-11-08 16:28:34', '2024-11-08 16:28:34', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', NULL, 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'submitted', ''),
(33, 4, 'Diana', 'Anne', 'Clark', 'diana.clark@example.com', 'Nursing', 'Bachelor', 'September/October Intake ', 3, 'no', 'no', '2024-11-08 16:32:27', '2024-11-08 16:32:27', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', NULL, 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'submitted', ''),
(34, 4, 'Diana', 'Anne', 'Clark', 'diana.clark@example.com', 'Physics', 'Bachelor', 'September/October Intake ', 2, 'no', 'no', '2024-11-08 16:32:55', '2024-11-08 16:32:55', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', NULL, 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'submitted', ''),
(35, 4, 'Diana', 'Anne', 'Clark', 'diana.clark@example.com', 'Physics', 'Bachelor', 'September/October Intake ', 2, 'no', 'no', '2024-11-08 16:35:36', '2024-11-08 16:35:36', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', NULL, 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'submitted', ''),
(36, 4, 'Diana', 'Anne', 'Clark', 'diana.clark@example.com', 'Physics', 'Bachelor', 'September/October Intake ', 6, 'no', 'no', '2024-11-08 16:36:22', '2024-11-08 16:36:22', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', NULL, 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'submitted', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart`
--

CREATE TABLE `tbl_cart` (
  `cart_id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `university_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_cart`
--

INSERT INTO `tbl_cart` (`cart_id`, `applicant_id`, `university_id`, `email`, `date_added`) VALUES
(10, 9, 3, 'ian.rodriguez@example.com', '2024-11-04 07:47:16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login`
--

CREATE TABLE `tbl_login` (
  `id` int(11) NOT NULL,
  `FullName` varchar(255) DEFAULT NULL,
  `AdminEmail` varchar(255) DEFAULT NULL,
  `loginid` varchar(250) NOT NULL,
  `password` text NOT NULL,
  `phone_number` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_login`
--

INSERT INTO `tbl_login` (`id`, `FullName`, `AdminEmail`, `loginid`, `password`, `phone_number`) VALUES
(1, 'admin', 'admin123@gmail.com', 'admin', 'Test@12345', 0),
(4, 'James Hayden ', 'james@gmail.com', 'james', 'james123', 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_officer`
--

CREATE TABLE `tbl_officer` (
  `officer_id` int(11) NOT NULL,
  `unid` int(11) NOT NULL,
  `university_name` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `joining_date` date NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_officer`
--

INSERT INTO `tbl_officer` (`officer_id`, `unid`, `university_name`, `first_name`, `last_name`, `email`, `phone_number`, `photo`, `position`, `joining_date`, `address`, `status`, `created_at`, `updated_at`, `password`) VALUES
(1, 1, 'University of Greenwich', 'John', 'Doe', 'john.doe@greenwich.ac.uk', '+44 20 8331 800', 'john.jpg', 'Professor', '2024-10-28', '123 Greenwich St, London, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'John'),
(2, 1, 'University of Greenwich', 'Jane', 'Smith', 'jane.smith@greenwich.ac.uk', '+44 20 8331 800', 'jane.jpg', 'Lecturer', '2024-10-28', '456 Greenwich St, London, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Jane'),
(3, 1, 'University of Greenwich', 'Mark', 'Johnson', 'mark.johnson@greenwich.ac.uk', '+44 20 8331 800', 'mark.jpg', 'Administrator', '2024-10-28', '789 Greenwich St, London, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Mark'),
(4, 2, 'University College London', 'Emily', 'Brown', 'emily.brown@ucl.ac.uk', '+44 20 7679 200', 'emily.jpg', 'Professor', '2024-10-28', '123 UCL Rd, London, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Emily'),
(5, 2, 'University College London', 'Michael', 'Davis', 'michael.davis@ucl.ac.uk', '+44 20 7679 200', 'michael.jpg', 'Lecturer', '2024-10-28', '456 UCL Rd, London, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Michael'),
(6, 2, 'University College London', 'Sarah', 'Wilson', 'sarah.wilson@ucl.ac.uk', '+44 20 7679 200', 'sarah.jpg', 'Administrator', '2024-10-28', '789 UCL Rd, London, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Sarah'),
(7, 3, 'Imperial College London', 'David', 'Taylor', 'david.taylor@imperial.ac.uk', '+44 20 7589 511', 'david.jpg', 'Professor', '2024-10-28', '123 Imperial Rd, London, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'David'),
(8, 3, 'Imperial College London', 'Laura', 'Anderson', 'laura.anderson@imperial.ac.uk', '+44 20 7589 511', 'laura.jpg', 'Lecturer', '2024-10-28', '456 Imperial Rd, London, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Laura'),
(9, 3, 'Imperial College London', 'Robert', 'Moore', 'robert.moore@imperial.ac.uk', '+44 20 7589 511', 'robert.jpg', 'Administrator', '2024-10-28', '789 Imperial Rd, London, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Robert'),
(10, 4, 'King\'s College London', 'Anna', 'Lee', 'anna.lee@kcl.ac.uk', '+44 20 7836 545', 'anna.jpg', 'Professor', '2024-10-28', '123 Kings Rd, London, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Anna'),
(11, 4, 'King\'s College London', 'James', 'Harris', 'james.harris@kcl.ac.uk', '+44 20 7836 545', 'james.jpg', 'Lecturer', '2024-10-28', '456 Kings Rd, London, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'James'),
(12, 4, 'King\'s College London', 'Jessica', 'Clark', 'jessica.clark@kcl.ac.uk', '+44 20 7836 545', 'jessica.jpg', 'Administrator', '2024-10-28', '789 Kings Rd, London, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Jessica'),
(13, 5, 'University of Edinburgh', 'William', 'Lewis', 'william.lewis@ed.ac.uk', '+44 131 650 100', 'william.jpg', 'Professor', '2024-10-28', '123 Edinburgh St, Edinburgh, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'William'),
(14, 5, 'University of Edinburgh', 'Olivia', 'Walker', 'olivia.walker@ed.ac.uk', '+44 131 650 100', 'olivia.jpg', 'Lecturer', '2024-10-28', '456 Edinburgh St, Edinburgh, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Olivia'),
(15, 5, 'University of Edinburgh', 'Daniel', 'Hall', 'daniel.hall@ed.ac.uk', '+44 131 650 100', 'daniel.jpg', 'Administrator', '2024-10-28', '789 Edinburgh St, Edinburgh, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Daniel'),
(16, 6, 'University of Manchester', 'Sophia', 'Allen', 'sophia.allen@manchester.ac.uk', '+44 161 306 600', 'sophia.jpg', 'Professor', '2024-10-28', '123 Manchester St, Manchester, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Sophia'),
(17, 6, 'University of Manchester', 'Ethan', 'Young', 'ethan.young@manchester.ac.uk', '+44 161 306 600', 'ethan.jpg', 'Lecturer', '2024-10-28', '456 Manchester St, Manchester, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Ethan'),
(18, 6, 'University of Manchester', 'Mia', 'King', 'mia.king@manchester.ac.uk', '+44 161 306 600', 'mia.jpg', 'Administrator', '2024-10-28', '789 Manchester St, Manchester, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Mia'),
(19, 7, 'University of Birmingham', 'Ava', 'Scott', 'ava.scott@bham.ac.uk', '+44 121 414 334', 'ava.jpg', 'Professor', '2024-10-28', '123 Birmingham St, Birmingham, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Ava'),
(20, 7, 'University of Birmingham', 'Noah', 'Green', 'noah.green@bham.ac.uk', '+44 121 414 334', 'noah.jpg', 'Lecturer', '2024-10-28', '456 Birmingham St, Birmingham, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Noah'),
(21, 7, 'University of Birmingham', 'Isabella', 'Adams', 'isabella.adams@bham.ac.uk', '+44 121 414 334', 'isabella.jpg', 'Administrator', '2024-10-28', '789 Birmingham St, Birmingham, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Isabella'),
(22, 8, 'University of Leeds', 'Liam', 'Baker', 'liam.baker@leeds.ac.uk', '+44 113 243 175', 'liam.jpg', 'Professor', '2024-10-28', '123 Leeds St, Leeds, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Liam'),
(23, 8, 'University of Leeds', 'Charlotte', 'Nelson', 'charlotte.nelson@leeds.ac.uk', '+44 113 243 175', 'charlotte.jpg', 'Lecturer', '2024-10-28', '456 Leeds St, Leeds, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Charlotte'),
(24, 8, 'University of Leeds', 'Lucas', 'Mitchell', 'lucas.mitchell@leeds.ac.uk', '+44 113 243 175', 'lucas.jpg', 'Administrator', '2024-10-28', '789 Leeds St, Leeds, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Lucas'),
(25, 9, 'University of Glasgow', 'Mason', 'Perez', 'mason.perez@glasgow.ac.uk', '+44 141 330 200', 'mason.jpg', 'Professor', '2024-10-28', '123 Glasgow St, Glasgow, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Mason'),
(26, 9, 'University of Glasgow', 'Ella', 'Carter', 'ella.carter@glasgow.ac.uk', '+44 141 330 200', 'ella.jpg', 'Lecturer', '2024-10-28', '456 Glasgow St, Glasgow, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Ella'),
(27, 9, 'University of Glasgow', 'Aiden', 'Stewart', 'aiden.stewart@glasgow.ac.uk', '+44 141 330 200', 'aiden.jpg', 'Administrator', '2024-10-28', '789 Glasgow St, Glasgow, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Aiden'),
(28, 10, 'University of Liverpool', 'Jackson', 'Powell', 'jackson.powell@liverpool.ac.uk', '+44 151 794 200', 'jackson.jpg', 'Lecturer', '2024-10-28', '456 Liverpool St, Liverpool, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Jackson'),
(29, 10, 'University of Liverpool', 'Grace', 'Long', 'grace.long@liverpool.ac.uk', '+44 151 794 200', 'grace.jpg', 'Administrator', '2024-10-28', '789 Liverpool St, Liverpool, UK', 'active', '2024-10-28 18:22:11', '2024-10-28 18:22:11', 'Grace');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_profile`
--

CREATE TABLE `tbl_profile` (
  `id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `transcripts` varchar(255) DEFAULT NULL,
  `diploma` varchar(255) DEFAULT NULL,
  `recommendation_letters` varchar(255) DEFAULT NULL,
  `standardized_test_scores` varchar(255) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `proof_of_english_proficiency` varchar(255) DEFAULT NULL,
  `financial_documents` varchar(255) DEFAULT NULL,
  `passport_copy` varchar(255) DEFAULT NULL,
  `disciplinaryIssues` varchar(255) NOT NULL,
  `felonyCharge` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_profile`
--

INSERT INTO `tbl_profile` (`id`, `applicant_id`, `transcripts`, `diploma`, `recommendation_letters`, `standardized_test_scores`, `resume`, `proof_of_english_proficiency`, `financial_documents`, `passport_copy`, `disciplinaryIssues`, `felonyCharge`, `created_at`, `updated_at`) VALUES
(2, 9, 'Transcript.docx', 'Certificate.docx', 'Recommendation.docx', 'S Test.docx', 'Resume.docx', 'English Proficiency.docx', 'Financial Documents.docx', 'Passport.docx', 'No', 'No', '2024-11-04 06:09:37', '2024-11-04 06:09:37'),
(3, 1, 'Transcript.docx', 'Certificate.docx', 'Recommendation.docx', 'S Test.docx', 'Resume.docx', 'English Proficiency.docx', 'Financial Documents.docx', 'Passport.docx', 'No', 'No', '2024-11-04 07:51:42', '2024-11-04 07:51:42'),
(4, 2, 'Transcript.docx', 'Certificate.docx', 'Recommendation.docx', 'S Test.docx', 'Resume.docx', 'English Proficiency.docx', 'Financial Documents.docx', 'Passport.docx', 'No', 'No', '2024-11-04 08:01:45', '2024-11-04 08:01:45'),
(5, 4, 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'pexels-aida-cervera-234109944-29232927.jpg', 'No', 'No', '2024-11-08 16:20:27', '2024-11-08 16:20:27');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_university`
--

CREATE TABLE `tbl_university` (
  `unid` int(11) NOT NULL,
  `universityName` varchar(255) NOT NULL,
  `aboutUniversity` text NOT NULL,
  `programs` text NOT NULL,
  `degreesOffered` text NOT NULL,
  `universityAddress` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phoneNumber` varchar(20) NOT NULL,
  `admissionRequirements` text NOT NULL,
  `schoolWebsite` varchar(255) DEFAULT NULL,
  `creationDate` date NOT NULL,
  `faculties` varchar(255) NOT NULL,
  `logoPath` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_university`
--

INSERT INTO `tbl_university` (`unid`, `universityName`, `aboutUniversity`, `programs`, `degreesOffered`, `universityAddress`, `email`, `phoneNumber`, `admissionRequirements`, `schoolWebsite`, `creationDate`, `faculties`, `logoPath`) VALUES
(1, 'University of Greenwich', 'The University of Greenwich is a public university located in London, known for its diverse programs and strong emphasis on research.', 'Business, Engineering, Humanities, Science', 'Bachelors, Masters, PhD', 'Greenwich, London, UK', 'info@greenwich.ac.uk', '+44 20 8331 8000', 'A-levels or equivalent, IELTS for non-native speakers.', 'https://www.greenwich.ac.uk', '2024-10-28', 'Business, Engineering, Social Sciences', 'greenwich.jpg'),
(2, 'University College London', 'UCL is a world-renowned university known for its research excellence and innovative programs.', 'Arts, Science, Engineering', 'Bachelors, Masters, PhD', 'Gower St, Bloomsbury, London, UK', 'info@ucl.ac.uk', '+44 20 7679 2000', 'A-levels or equivalent, personal statement, references.', 'https://www.ucl.ac.uk', '2024-10-28', 'Arts, Sciences, Engineering', 'ucl.jpg'),
(3, 'Imperial College London', 'Imperial College London is a leading science, engineering, business, and medicine university.', 'Engineering, Business, Medicine', 'Bachelors, Masters, PhD', 'Exhibition Rd, South Kensington, London, UK', 'info@imperial.ac.uk', '+44 20 7589 5111', 'A-levels or equivalent, admissions test.', 'https://www.imperial.ac.uk', '2024-10-28', 'Engineering, Medicine, Business', 'imperial.jpg'),
(4, 'King\'s College London', 'King’s College London is one of the oldest universities in England, offering a wide range of programs.', 'Humanities, Science, Health', 'Bachelors, Masters, PhD', 'Strand, London, UK', 'info@kcl.ac.uk', '+44 20 7836 5454', 'A-levels or equivalent, personal statement.', 'https://www.kcl.ac.uk', '2024-10-28', 'Health, Sciences, Humanities', 'kings.jpg'),
(5, 'University of Edinburgh', 'The University of Edinburgh is one of the world’s top universities, known for its research and teaching.', 'Arts, Humanities, Sciences', 'Bachelors, Masters, PhD', 'Old College, South Bridge, Edinburgh, UK', 'info@ed.ac.uk', '+44 131 650 1000', 'A-levels or equivalent, personal statement.', 'https://www.ed.ac.uk', '2024-10-28', 'Arts, Sciences, Business', 'edinburgh.jpg'),
(6, 'University of Manchester', 'The University of Manchester is a red brick university known for its research and diverse programs.', 'Engineering, Business, Science', 'Bachelors, Masters, PhD', 'Oxford Rd, Manchester, UK', 'info@manchester.ac.uk', '+44 161 306 6000', 'A-levels or equivalent, personal statement.', 'https://www.manchester.ac.uk', '2024-10-28', 'Engineering, Humanities, Science', 'manchester.jpg'),
(7, 'University of Birmingham', 'The University of Birmingham is a prestigious university known for its research and teaching excellence.', 'Arts, Sciences, Engineering', 'Bachelors, Masters, PhD', 'Edgbaston, Birmingham, UK', 'info@bham.ac.uk', '+44 121 414 3344', 'A-levels or equivalent, personal statement.', 'https://www.birmingham.ac.uk', '2024-10-28', 'Arts, Sciences, Engineering', 'birmingham.jpg'),
(8, 'University of Leeds', 'The University of Leeds is a leading research university with a wide range of programs.', 'Arts, Business, Sciences', 'Bachelors, Masters, PhD', 'Woodhouse Ln, Leeds, UK', 'info@leeds.ac.uk', '+44 113 243 1751', 'A-levels or equivalent, personal statement.', 'https://www.leeds.ac.uk', '2024-10-28', 'Arts, Business, Science', 'leeds.jpg'),
(9, 'University of Glasgow', 'The University of Glasgow is one of the UK’s oldest universities, known for its research output.', 'Arts, Sciences, Engineering', 'Bachelors, Masters, PhD', 'University Ave, Glasgow, UK', 'info@glasgow.ac.uk', '+44 141 330 2000', 'A-levels or equivalent, personal statement.', 'https://www.glasgow.ac.uk', '2024-10-28', 'Arts, Sciences, Engineering', 'glasgow.jpg'),
(10, 'University of Liverpool', 'The University of Liverpool is a research-led university with a diverse range of programs.', 'Arts, Sciences, Health', 'Bachelors, Masters, PhD', 'Brownlow Hill, Liverpool, UK', 'info@liverpool.ac.uk', '+44 151 794 2000', 'A-levels or equivalent, personal statement.', 'https://www.liverpool.ac.uk', '2024-10-28', 'Arts, Sciences, Health', 'liverpool.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `tbl_applicant`
--
ALTER TABLE `tbl_applicant`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_applications`
--
ALTER TABLE `tbl_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `universityId` (`universityId`);

--
-- Indexes for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `applicant_id` (`applicant_id`),
  ADD KEY `university_id` (`university_id`);

--
-- Indexes for table `tbl_login`
--
ALTER TABLE `tbl_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_officer`
--
ALTER TABLE `tbl_officer`
  ADD PRIMARY KEY (`officer_id`),
  ADD KEY `university_id` (`unid`);

--
-- Indexes for table `tbl_profile`
--
ALTER TABLE `tbl_profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `tbl_university`
--
ALTER TABLE `tbl_university`
  ADD PRIMARY KEY (`unid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_applicant`
--
ALTER TABLE `tbl_applicant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbl_applications`
--
ALTER TABLE `tbl_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_login`
--
ALTER TABLE `tbl_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_officer`
--
ALTER TABLE `tbl_officer`
  MODIFY `officer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_profile`
--
ALTER TABLE `tbl_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_university`
--
ALTER TABLE `tbl_university`
  MODIFY `unid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_applications`
--
ALTER TABLE `tbl_applications`
  ADD CONSTRAINT `tbl_applications_ibfk_1` FOREIGN KEY (`universityId`) REFERENCES `tbl_university` (`unid`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD CONSTRAINT `tbl_cart_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `tbl_applicant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_cart_ibfk_2` FOREIGN KEY (`university_id`) REFERENCES `tbl_university` (`unid`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_officer`
--
ALTER TABLE `tbl_officer`
  ADD CONSTRAINT `tbl_officer_ibfk_1` FOREIGN KEY (`unid`) REFERENCES `tbl_university` (`unid`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_profile`
--
ALTER TABLE `tbl_profile`
  ADD CONSTRAINT `tbl_profile_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `tbl_applicant` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
