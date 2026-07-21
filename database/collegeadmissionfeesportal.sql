-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2026 at 10:58 AM
-- Server version: 10.5.29-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `collegeadmissionfeesportal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admissions`
--

CREATE TABLE `admissions` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `admission_no` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admissions`
--

INSERT INTO `admissions` (`id`, `student_id`, `course_id`, `batch_id`, `admission_date`, `admission_no`, `status`) VALUES
(1, 1, 1, 1, '2026-02-20', 'ADM20260221104729', 'ACTIVE'),
(2, 2, 2, 2, '2026-02-23', 'ADM20260221104759', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `batches`
--

CREATE TABLE `batches` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `batch_name` varchar(50) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `batches`
--

INSERT INTO `batches` (`id`, `course_id`, `session_id`, `batch_name`, `start_date`, `end_date`, `status`) VALUES
(1, 1, 2, 'B.Tech-A', '2026-04-01', '2027-03-31', 1),
(2, 2, 2, 'MBA-A', '2026-04-01', '2027-03-31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(100) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `duration`, `type`, `status`) VALUES
(1, 'B.Tech/B.E', 4, 'undergraduate', 1),
(2, 'MBA', 3, 'Post Graduate', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fee_installments`
--

CREATE TABLE `fee_installments` (
  `id` int(11) NOT NULL,
  `ledger_id` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `fee_installments`
--

INSERT INTO `fee_installments` (`id`, `ledger_id`, `due_date`, `amount`, `status`) VALUES
(1, 1, '2026-03-21', 44700.00, 'PENDING'),
(2, 1, '2026-04-21', 44700.00, 'PENDING'),
(3, 1, '2026-05-21', 44700.00, 'PENDING'),
(4, 2, '2026-03-21', 29833.33, 'PARTIAL'),
(5, 2, '2026-04-21', 29833.33, 'PENDING'),
(6, 2, '2026-05-21', 29833.33, 'PENDING');

-- --------------------------------------------------------

--
-- Table structure for table `fee_structures`
--

CREATE TABLE `fee_structures` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `fee_type_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `fee_structures`
--

INSERT INTO `fee_structures` (`id`, `course_id`, `fee_type_id`, `amount`, `status`) VALUES
(1, 2, 1, 10000.00, 1),
(2, 2, 2, 55000.00, 1),
(3, 2, 3, 1500.00, 1),
(4, 2, 4, 23000.00, 1),
(5, 1, 1, 12000.00, 1),
(6, 1, 2, 95000.00, 1),
(7, 1, 3, 2100.00, 1),
(8, 1, 4, 25000.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `fee_types`
--

CREATE TABLE `fee_types` (
  `id` int(11) NOT NULL,
  `fee_name` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `fee_types`
--

INSERT INTO `fee_types` (`id`, `fee_name`, `status`) VALUES
(1, 'Admission Fee', 1),
(2, 'Tution Fee', 1),
(3, 'Exam Fee', 1),
(4, 'Miscellaneous Fee', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fine_rules`
--

CREATE TABLE `fine_rules` (
  `id` int(11) NOT NULL,
  `days_after` int(11) DEFAULT NULL,
  `fine_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_table`
--

CREATE TABLE `log_table` (
  `id` int(11) NOT NULL,
  `role_id` int(128) NOT NULL,
  `user_id` int(128) NOT NULL,
  `role_name` varchar(128) NOT NULL,
  `user_name` varchar(128) NOT NULL,
  `msd_id` varchar(128) NOT NULL,
  `login_date_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `logout_date_time` varchar(128) NOT NULL,
  `status` int(32) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `log_table`
--

INSERT INTO `log_table` (`id`, `role_id`, `user_id`, `role_name`, `user_name`, `msd_id`, `login_date_time`, `logout_date_time`, `status`) VALUES
(1, 1, 243, 'Administrator', 'Yogesh Namdev', 'Yogesh@gmail.com', '2026-02-21 07:16:52', '2026-02-21 14:24:24', 1),
(2, 2, 2, 'Advisor', 'Aashi Patidar', 'MEM02246', '2026-02-21 08:54:51', '2026-02-21 14:31:30', 1),
(3, 1, 243, 'Administrator', 'Yogesh Namdev', 'Yogesh@gmail.com', '2026-02-21 09:01:33', '2026-02-21 14:58:08', 1),
(4, 1, 243, 'Administrator', 'Yogesh Namdev', 'Yogesh@gmail.com', '2026-02-21 09:28:11', '2026-02-21 15:22:15', 1),
(5, 1, 243, 'Administrator', 'Yogesh Namdev', 'Yogesh@gmail.com', '2026-02-21 10:11:56', '2026-02-21 15:43:39', 1),
(6, 2, 1, 'Student', 'Yogesh Namdev', '8871243753', '2026-02-22 05:22:16', '', 1),
(7, 1, 243, 'Administrator', 'Yogesh Namdev', 'Yogesh@gmail.com', '2026-02-22 05:23:59', '2026-02-22 10:56:44', 1),
(8, 2, 1, 'Student', 'Yogesh Namdev', '8871243753', '2026-02-22 05:26:52', '', 1),
(9, 2, 1, 'Student', 'Yogesh Namdev', '8871243753', '2026-02-22 05:27:25', '', 1),
(10, 2, 1, 'Student', 'Yogesh Namdev', '8871243753', '2026-02-22 05:30:32', '', 1),
(11, 2, 1, 'Student', 'Yogesh Namdev', '8871243753', '2026-02-22 05:30:48', '', 1),
(12, 2, 1, 'Student', 'Yogesh Namdev', '8871243753', '2026-02-22 05:39:32', '2026-02-22 11:12:59', 1),
(13, 2, 1, 'Student', 'Yogesh Namdev', '8871243753', '2026-02-22 05:43:33', '2026-02-22 13:58:48', 1),
(14, 2, 2, 'Student', 'Sharad Mehra', '8871243753', '2026-02-22 08:29:23', '2026-02-22 15:14:23', 1),
(15, 2, 1, 'Student', 'Yogesh Namdev', '8871243753', '2026-02-22 09:44:35', '2026-02-22 15:14:46', 1),
(16, 1, 243, 'Administrator', 'Yogesh Namdev', 'Yogesh@gmail.com', '2026-02-22 09:45:02', '2026-02-22 15:24:53', 1),
(17, 1, 243, 'Administrator', 'Yogesh Namdev', 'Yogesh@gmail.com', '2026-02-22 09:56:43', '2026-02-22 15:27:36', 1),
(18, 2, 2, 'Student', 'Sharad Mehra', '8871243753', '2026-02-22 09:57:48', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `master_roles`
--

CREATE TABLE `master_roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `eby` int(11) NOT NULL DEFAULT 0,
  `eat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `master_roles`
--

INSERT INTO `master_roles` (`role_id`, `role_name`, `status`, `eby`, `eat`) VALUES
(1, 'Administrator', 1, 1, '2021-05-31 19:31:33'),
(2, 'Student', 1, 1, '2022-11-18 10:49:16'),
(3, 'test', 0, 22, '2023-01-27 05:35:54'),
(4, 'TL', 1, 105, '2024-08-21 06:17:39'),
(5, 'AM', 1, 105, '2024-08-21 06:17:46'),
(6, 'QA', 1, 105, '2024-08-21 06:26:02'),
(7, 'TR', 1, 105, '2024-08-21 06:30:03');

-- --------------------------------------------------------

--
-- Table structure for table `master_users`
--

CREATE TABLE `master_users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `msd_id` varchar(100) NOT NULL,
  `old_msd_id` varchar(50) NOT NULL,
  `user_password` varchar(100) NOT NULL DEFAULT 'Gem@321',
  `login_status` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1,
  `eby` int(11) NOT NULL DEFAULT 0,
  `eat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `master_users`
--

INSERT INTO `master_users` (`user_id`, `role_id`, `user_name`, `msd_id`, `old_msd_id`, `user_password`, `login_status`, `status`, `eby`, `eat`) VALUES
(1, 1, 'Yogesh Namdev', '123', '123', '123', 1, 1, 105, '0000-00-00 00:00:00'),
(2, 2, 'Aashi Patidar', 'MEM02246', 'MSD25616', 'Gem@123', 1, 1, 243, '2026-02-17 05:44:23'),
(3, 2, 'Abhishek Kumar Verma', 'MSD25717', 'MSD25717', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(4, 2, 'Aishwarya shrivastava', 'MSD22853', 'MSD22853', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(5, 2, 'Akansha yati', 'MSD25190', 'MSD25190', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(6, 2, 'Akash Dehariya', 'MEM02270', 'MSD24469', 'Gem@123', 1, 1, 105, '2024-08-29 08:11:03'),
(7, 2, 'Alka Rawat', 'MSD25721', 'MSD25721', 'Gem@123', 0, 0, 1, '2024-06-07 07:26:14'),
(8, 2, 'Amrita Mahanand', 'MEM02263', 'MSD25613', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(9, 2, 'Ananya Soni', 'MSD25714', 'MSD25714', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(10, 2, 'Aniket sahu', 'MSD25170', 'MSD25170', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(11, 2, 'Anil Shrivastava', 'MSD24745', 'MSD24745', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(12, 2, 'Anirudh singh', 'MSD23810', 'MSD23810', 'Gem@123', 0, 0, 105, '2024-08-21 06:42:23'),
(13, 2, 'Anjali Meena', 'MSD24639', 'MSD24639', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(14, 2, 'Anurag Dubey', 'MSD25279', 'MSD25279', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(15, 2, 'Areeb Khan', 'MSD25041', 'MSD25041', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(16, 2, 'Arjun Joshi', 'MSD25043', 'MSD25043', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(17, 2, 'Ashvini Kumar1', 'MSD23326', 'MSD23326', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(18, 2, 'Astha kushwaha', 'MSD25174', 'MSD25174', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(19, 2, 'Avinash Mishra', 'MSD25724', 'MSD25724', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(20, 2, 'Ayush Dwivedi', 'MSD25266', 'MSD25266', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(21, 2, 'Bhupendra Koal', 'MEM02261', 'MSD25272', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(22, 2, 'Boby Thapa', 'MEM02250', 'MSD25282', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(23, 2, 'Chahat Khan', 'MSD22666', 'MSD22666', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(24, 2, 'Devendra', 'MSD22237', 'MSD22237', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(25, 2, 'Fazil Khan', 'MSD24644', 'MSD24644', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(26, 2, 'Govind Pal', 'MSD24755', 'MSD24755', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(27, 2, 'Hamza Khan', 'MSD25036', 'MSD25036', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(28, 2, 'Harpreetkaur Virdi', 'MEM02260', 'MSD25280', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(29, 2, 'Himanshu Dwivedi', 'MSD24765', 'MSD24765', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(30, 2, 'Huzefa khan', 'MSD24931', 'MSD24931', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(31, 2, 'Jagveer Singh', 'MSD25610', 'MSD25610', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(32, 2, 'Jai Prakash Rajput', 'MEM02240', 'MSD22570', 'Magnum@123', 1, 1, 32, '0000-00-00 00:00:00'),
(33, 2, 'Jaswant Rao', 'MSD24011', 'MSD24011', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(34, 2, 'Jaya Chauksey', 'MSD25607', 'MSD25607', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(35, 2, 'Jayesh kulkarni', 'MSD25176', 'MSD25176', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(36, 2, 'Jyoti Lilhare', 'MSD21784', 'MSD21784', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(37, 2, 'karishma Sen', 'MSD24840', 'MSD24840', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(38, 2, 'Kaushal Rajput', 'MSD25127', 'MSD25127', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(39, 2, 'Kislay Mishra', 'MSD25759', 'MSD25759', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(40, 2, 'kriti rathore', 'MSD25185', 'MSD25185', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(41, 2, 'Kush Kumar', 'MSD24833', 'MSD24833', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(42, 2, 'Lucky Sadhwani', 'MEM02233', 'MSD22852', 'Gem@123', 1, 1, 105, '0000-00-00 00:00:00'),
(43, 2, 'Mahak Nagar', 'MSD24943', 'MSD24943', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(44, 2, 'Mansi Sahu', 'MSD25471', 'MSD25471', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(45, 2, 'Mansij Sharma', 'MSD24419', 'MSD24419', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(46, 2, 'Mayank Suryavanshi', 'MSD24944', 'MSD24944', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(47, 2, 'Naman Bansod', 'MSD25719', 'MSD25719', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(48, 2, 'Neeraj raghuwanshi', 'MSD23524', 'MSD23524', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(49, 2, 'Neeru Upadhya', 'MEM02249', 'MSD25773', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(50, 2, 'Neha Pandagre', 'MEM02237', 'MSD24463', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(51, 2, 'Neha Somkuwar', 'MEM02238', 'MSD24420', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(52, 2, 'Nidhi Pandey', 'MSD24928', 'MSD24928', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(53, 2, 'Nidhi pawar', 'MSD25184', 'MSD25184', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(54, 2, 'Nilesh Ahirwar', 'MSD24750', 'MSD24750', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(55, 2, 'Nitesh Ahirwar', 'MSD24934', 'MSD24934', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(56, 2, 'Nitish Bajpai', 'MSD25472', 'MSD25472', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(57, 2, 'P Kavita', 'MSD24086', 'MSD24086', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(58, 2, 'P.V Srinath', 'MEM02262', 'MSD24013', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(59, 2, 'Prabhat Chaurasiya', 'MEM02241', 'MSD23732', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(60, 2, 'Prakash A', 'MSD21242', 'MSD21242', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(61, 2, 'Pratibha Singh', 'MSD24417', 'MSD24417', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(62, 2, 'Pratibha Yadav', 'MSD25769', 'MSD25769', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(63, 2, 'Preeti Kumari', 'MSD23604', 'MSD23604', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(64, 2, 'Princy Chaurasiya', 'MSD25048', 'MSD25048', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(65, 2, 'Priya Bala', 'MSD25474', 'MSD25474', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(66, 2, 'Purvi vaishnav', 'MSD25182', 'MSD25182', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(67, 2, 'Rajveer Singh Bagi', 'MSD25770', 'MSD25770', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(68, 2, 'Ramvati Ahirwar', 'MSD25618', 'MSD25618', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(69, 2, 'Reetika Singh', 'MSD24836', 'MSD24836', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(70, 2, 'Rinki Yadav', 'MEM02244', 'MSD25475', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(71, 2, 'Ritu Rai', 'MSD25276', 'MSD25276', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(72, 2, 'RN Shailendra Kumar', 'MSD25813', 'MSD25813', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(73, 2, 'RNikhita Nair', 'MSD22360', 'MSD22360', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(74, 2, 'Rohit agnihotri', 'MSD25171', 'MSD25171', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(75, 2, 'Rohit Kumar Pandey', 'MSD25609', 'MSD25609', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(76, 2, 'Ronak singh', 'MEM02243', 'MSD24009', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(77, 2, 'Ruby Sahu', 'MSD24837', 'MSD24837', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(78, 2, 'Sagar Tayde', 'MEM02239', 'MSD24574', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(79, 2, 'Sakshi Shukla', 'MSD25479', 'MSD25479', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(80, 2, 'Samarth Jamne', 'MEM02245', 'MSD25611', 'Gem@123', 0, 0, 105, '2024-08-21 06:41:25'),
(81, 2, 'Sapna rai', 'MSD22992', 'MSD22992', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(82, 2, 'Sayyed Ali', 'MEM02236', 'MSD25042', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(83, 2, 'Shailendra bhadoriya', 'MSD24648', 'MSD24648', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(84, 2, 'Shikha Rajput', 'MSD23662', 'MSD23662', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(85, 2, 'Shireen Raza', 'MSD24936', 'MSD24936', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(86, 2, 'Shivam Yadav', 'MEM02230', 'MSD23196', 'Gem@123', 0, 0, 105, '2024-08-21 06:40:39'),
(87, 2, 'Shrishti Tawar', 'MSD25711', 'MSD25711', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(88, 2, 'Shruti Kailasiya', 'MEM02271', 'MSD24346', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(89, 2, 'Shubham Gharu', 'MSD24839', 'MSD24839', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(90, 2, 'Shweta pandya', 'MSD23606', 'MSD23606', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(91, 2, 'Siddartha Singh Chauhan', 'MEM02232', 'MSD25482', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(92, 2, 'Sunil meena', 'MEM02229', 'MSD23598', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(93, 2, 'Tanishka Soni', 'MSD25713', 'MSD25713', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(94, 2, 'Tanmay', 'MSD25033', 'MSD25033', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(95, 2, 'Tanumoy Bose', 'MEM02265', 'MSD25620', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(96, 2, 'Tanvi shrivastava', 'MSD25167', 'MSD25167', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(97, 2, 'Tarun dhoke', 'MEM02235', 'MSD25175', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(98, 2, 'Varun Tripathi', 'MSD25054', 'MSD25054', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(99, 2, 'Vimal KK', 'MEM02267', 'MSD23180', 'Gem@123', 1, 1, 99, '0000-00-00 00:00:00'),
(100, 2, 'Vishal kumar', 'MSD23462', 'MSD23462', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(101, 2, 'Vishnu Shrivas', 'MSD25268', 'MSD25268', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(102, 2, 'Yashsava Dongre', 'MEM02227', 'MSD25605', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(103, 2, 'Yuvraj singh', 'MSD25188', 'MSD25188', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(104, 2, 'Zamar ali', 'MEM02256', 'MSD25053', 'Gem@123', 1, 1, 22, '0000-00-00 00:00:00'),
(105, 1, 'Raju Singh', 'MSD26263', 'MSD26263', 'Gem@123', 1, 1, 105, '0000-00-00 00:00:00'),
(106, 2, 'Bhoomi Mishra', 'MSD26043', 'MSD26043', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(107, 2, 'Shekhar Kumre', 'MSD21666', 'MSD21666', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(108, 2, 'Nikita Arya', 'MSD26402', 'MSD26402', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(109, 2, 'Naveen Ekka', 'MSD26436', 'MSD26436', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(110, 2, 'Rishabh Gawande', 'MSD26173', 'MSD26173', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(111, 2, 'Ujjwal Mujumdar', 'MSD26174', 'MSD26174', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(112, 2, 'Jyoti Talreja', 'MSD26405', 'MSD26405', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(113, 2, 'Sujal Malvi', 'MEM02247', 'MSD26445', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(114, 2, 'Anjali Soni', 'MSD26437', 'MSD26437', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(115, 2, 'Shrayansh Mishra', 'MSD26395', 'MSD26395', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(116, 2, 'Sourabh Chouhan', 'MSD26391', 'MSD26391', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(117, 2, 'Yamini Rajput', 'MSD26082', 'MSD26082', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(118, 2, 'Sahil Jamali', 'MEM02259', 'MSD26298', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(119, 2, 'Kavyalochan Sahu', 'MSD26448', 'MSD26448', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(120, 2, 'Ayushi', 'MSD26301', 'MSD26301', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(121, 2, 'Akil.Azhar', 'MEM02254', 'MSD26083', 'Akil@123', 1, 1, 121, '0000-00-00 00:00:00'),
(122, 2, 'Yash Jain', 'MSD26398', 'MSD26398', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(123, 2, 'Revathi Mudaliar', 'MSD26393', 'MSD26393', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(124, 2, 'Anshika Dubey', 'MSD26546', 'MSD26546', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(125, 2, 'Naman Joshi ', 'MSD26453', 'MSD26453', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(126, 2, 'Bhanu Pratap Singh', 'MSD26547', 'MSD26547', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(127, 2, 'Aadil Ali Khan', 'MSD26306', 'MSD26306', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(128, 2, 'Chandra Prakash', 'MSD26548', 'MSD26548', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(129, 2, 'Darshit Jain', 'MSD26564', 'MSD26564', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(130, 2, 'Pranav Nair', 'MEM02252', 'MSD25709', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(131, 2, 'Jayant Dohare', 'MSD26552', 'MSD26552', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(132, 2, 'Kuldeep Kaur', 'MSD26558', 'MSD26558', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(133, 2, 'Manish Patil', 'MSD26542', 'MSD26542', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(134, 2, 'Nancy Sharma', 'MSD26344', 'MSD26344', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(135, 2, 'Sudhanshu.Bhatia', 'MSD26205', 'MSD26205', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(136, 2, 'Nilesh Sinha', 'MSD26544', 'MSD26544', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(137, 2, 'Satyam.Patel', 'MSD26084', 'MSD26084', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(138, 2, 'Shahbaz Khan', 'MSD26543', 'MSD26543', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(139, 2, 'Shivanshu Rai', 'MSD26553', 'MSD26553', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(140, 2, 'Simran Meena', 'MSD26549', 'MSD26549', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(141, 2, 'Sonali Mehra', 'MEM02258', 'MSD26551', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(142, 2, 'Sunita Verma', 'MSD26343', 'MSD26343', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(143, 2, 'Tamanna Singh', 'MSD26539', 'MSD26539', 'Gem@321', 0, 0, 105, '0000-00-00 00:00:00'),
(144, 2, 'Vinay Vishwakarma', 'MSD26554', 'MSD26554', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(145, 2, 'Sohail Khan', 'MEM02228', 'MSD27330', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(146, 2, 'Yashwant Machhiwal', 'MSD27312', 'MSD27312', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(147, 2, 'Udit Dhakad', 'MSD27328', 'MSD27328', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(148, 2, 'Sapna Raikwar', 'MEM02231', 'MSD27331', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(149, 2, 'Kratika Athwal', 'MSD26956', 'MSD26956', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(150, 2, 'Sonu Khatarkar', 'MSD26959', 'MSD26959', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(151, 2, 'Aliya Khan', 'MSD26838', 'MSD26838', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(152, 2, 'Sindhi Vasim', 'MSD27317', 'MSD27317', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(153, 2, 'Shubham Gharu', 'MEM02234', 'MSD27198', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(154, 2, 'Shreya Vishwakarma', 'MSD26946', 'MSD26946', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(155, 2, 'Puja Kumari', 'MSD26957', 'MSD26957', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(156, 2, 'Iti Kushawaha', 'MSD27205', 'MSD27205', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(157, 2, 'Fardeen Khan', 'MSD27203', 'MSD27203', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(158, 2, 'Anjali Soni', 'MEM02251', 'MSD27211', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(159, 2, 'Ritika Pal', 'MSD26835', 'MSD26835', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(160, 2, 'Ved Choudhary', 'MEM02253', 'MSD27041', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(161, 2, 'Ankit Shinde', 'MSD26958', 'MSD26958', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(162, 2, 'Shivranjani Sharwan', 'MSD26972', 'MSD26972', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(163, 2, 'Syed zidan ali', 'MSD27043', 'MSD27043', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(164, 2, 'Nihal Ahmd', 'MSd26955', 'MSd26955', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(165, 2, 'Mansi Rai', 'MSD27213', 'MSD27213', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(166, 2, 'Ishika Sharma', 'MSD26830', 'MSD26830', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(167, 2, 'Ankit Meena', 'MSD26666', 'MSD26666', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(168, 2, 'Sneha Singh', 'MSD27202', 'MSD27202', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(169, 2, 'Jai Singh Rajput', 'MSD26656', 'MSD26656', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(170, 2, 'Arpan ChristopherNathaniel', 'MSD26833', 'MSD26833', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(171, 2, 'Priya Soni', 'MEM02264', 'MSD26670', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(172, 2, 'Neha Mishra', 'MSD27200', 'MSD27200', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(173, 2, 'Md. Ahmad Ansari', 'MEM02266', 'MSD26829', 'Gem@123', 1, 1, 173, '2025-07-19 12:19:03'),
(174, 2, 'Gurvinder Kaurgill', 'MSD26668', 'MSD26668', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(175, 2, 'Vikrant Singh Rajput', 'MEM02268', 'MSD26947', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(176, 2, 'Rakhi Verma', 'MEM02269', 'MSD27295', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(177, 2, 'Ayan Khan', 'MEM02272', 'MSD27309', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(178, 2, 'Devangi Shukla', 'MSD27448', 'MSD27448', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(179, 2, 'Utkarsh Singh porte', 'MEM02273', 'MSD27310', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(180, 2, 'Anuj Lodhi', 'MEM02274', 'MSD27449', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(181, 2, 'Aditya kumar Lodhi', 'MEM02275', 'MSD27770', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(182, 2, 'Brishti Roy ', 'MSD27777', 'MSD27777', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(183, 2, 'Deepa Rai', 'MEM02277', 'MSD27771', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(184, 2, 'Harsh Kumar mishra ', 'MEM02279', 'MSD27773', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(185, 2, 'Nandani Iyer ', 'MSD27800', 'MSD27800', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(186, 2, 'Neeraj Malviya ', 'MEM02281', 'MSD27443', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(187, 2, 'Neha Nagar ', 'MEM02278', 'MSD27767', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(188, 2, 'Nikita Thakur ', 'MEM02282', 'MSD27766', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(189, 2, 'Poornima Vishwakarma ', 'MSD27881', 'MSD27881', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(190, 2, 'Prince Prakash ', 'MSD27772', 'MSD27772', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(191, 2, 'Priya Singh Chauhan ', 'MEM02280', 'MSD27884', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(192, 2, 'Shweta Singh Chauhan', 'MEM02283', 'MSD27775', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(193, 2, 'Siddhant Saxena', 'MSD27886', 'MSD27886', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(194, 2, 'Sudhanshu Raikwar ', 'MSD27428', 'MSD27428', 'Gem@123', 1, 1, 1, '0000-00-00 00:00:00'),
(195, 2, 'Samarth Jamne', 'MEM02245', 'MSD25611', 'Gem@321', 0, 0, 105, '2024-08-21 06:41:36'),
(196, 2, 'Tanumoy Bose', 'MEM02265', 'MSD25620', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(197, 2, 'P.V Srinath', 'MEM02262', 'MSD24013', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(198, 2, 'Anjali Soni', 'MEM02251', 'MSD27211', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(199, 2, 'Ankit Shinde', 'MSD26958', 'MSD26958', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(200, 2, 'Darshit Jain', 'MSD26564', 'MSD26564', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(201, 2, 'Ishika Sharma', 'MSD26830', 'MSD26830', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(202, 2, 'Mansi Rai', 'MSD27213', 'MSD27213', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(203, 2, 'Nihal Ahmd', 'MSd26955', 'MSd26955', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(204, 2, 'Shivranjani Sharwan', 'MSD26972', 'MSD26972', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(205, 2, 'Shreya Vishwakarma', 'MSD26946', 'MSD26946', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(206, 2, 'Vinay Vishwakarma', 'MSD26554', 'MSD26554', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(207, 2, 'Priya Singh Chauhan ', 'MEM02280', 'MSD27884', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(208, 2, 'Priya Singh Chauhan ', 'MEM02280', 'MSD27884', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(209, 2, 'Twinkle Lahori', 'MSD28098', 'MSD28098', 'Gem123', 1, 1, 1, '0000-00-00 00:00:00'),
(210, 2, 'Vivek Bavaskar', 'MSD28100', 'MSD28100', 'Gem123', 1, 1, 1, '0000-00-00 00:00:00'),
(211, 2, 'Shreeya Sharma', 'MSD28103', 'MSD28103', 'Gem123', 1, 1, 1, '0000-00-00 00:00:00'),
(212, 2, 'Piyush Chouhan', 'MEM02285', 'MSD28107', 'Gem123', 1, 1, 1, '0000-00-00 00:00:00'),
(213, 2, 'Zahida Khatoon', 'MEM02289', 'MSD28108', 'Gem123', 1, 0, 1, '0000-00-00 00:00:00'),
(214, 2, 'Irshad Ahmed', 'MEM02290', 'MSD28109', 'Irshad@123', 1, 1, 214, '2024-06-21 03:51:35'),
(215, 2, 'Akash Anil', 'MSD28111', 'MSD28111', 'Gem123', 1, 1, 1, '0000-00-00 00:00:00'),
(216, 2, 'Paras Verma', 'MEM02284', 'MSD28113', 'Gem123', 1, 1, 1, '0000-00-00 00:00:00'),
(217, 2, 'Ayush Khatri', 'MEM02294', 'MSD28114', 'Gem123', 1, 1, 1, '0000-00-00 00:00:00'),
(218, 2, 'Akriti Panthi', 'MEM02288', 'MSD28116', 'Gem123', 1, 1, 1, '0000-00-00 00:00:00'),
(219, 2, 'Divya Batham', 'MEM02291', 'MSD28117', 'Gem123', 1, 1, 1, '0000-00-00 00:00:00'),
(220, 2, 'Chavi Singh', 'MEM02286', 'MSD28118', 'Gem123', 1, 1, 1, '0000-00-00 00:00:00'),
(221, 2, 'Keerti Tiwari ', 'MEM02293', 'MSD28280', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(222, 2, 'Udisha Yadav ', 'MSD28281', 'MSD28281', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(223, 2, 'Anurag Ashish Kujur ', 'MEM02287', 'MSD28287', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(224, 2, 'Aayush Mehra ', 'MSD28359', 'MSD28359', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(225, 2, 'Aayurved Sharma ', 'MSD28360', 'MSD28360', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(226, 2, 'Aditi sharma', 'MSD28364', 'MSD28364', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(227, 2, 'Ali ahmad', 'MSD28365', 'MSD28365', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(228, 2, 'Vaibhav Garg', 'MSD28370', 'MSD28370', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(229, 2, 'Hitaishi Mehra', 'MEM02298', 'MSD28435', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(230, 2, 'Anam Qureshi', 'MSD28444', 'MSD28444', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(231, 2, 'Mansi Sahu', 'MEM02300', 'MSD28437', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(232, 2, 'Ankita Kumari', 'MSD28433', 'MSD28433', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(233, 2, 'Nandani Verma', 'MSD28441', 'MSD28441', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(234, 2, 'Vicky Malviya', 'MSD28436', 'MSD28436', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(235, 2, 'Rooman Ansari', 'MSD28429', 'MSD28429', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(236, 2, 'Prayag Gangoliya', 'MSD28438', 'MSD28438', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(237, 2, 'Ayan Sharma', 'MEM02296', 'MSD28428', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(238, 2, 'Anam Jahan', 'MEM02301', 'MSD28464', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(239, 2, 'Surendra Meena', 'MSD28434', 'MSD28434', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(240, 2, 'Mohd Ismail Faisal', 'MEM02302', 'MSD28499', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(241, 2, 'Ashish Rohra', 'MSD28502', 'MSD28502', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(242, 2, 'Hemangini Shipre', 'MEM02309', 'MSD28504', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(243, 1, 'Yogesh Namdev', 'Yogesh@gmail.com', 'MSD28718', 'Yogesh123', 1, 1, 105, '0000-00-00 00:00:00'),
(244, 2, 'Ankit Singh', 'MEM02305', 'MSD28564', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(245, 2, 'Unnati Sahu', 'MEM02306', 'MSD28524', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(246, 2, 'Abhishek Pastariya', 'MEM02307', 'MSD28532', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(247, 2, 'Rizwan Khan', 'MEM02308', 'MSD28531', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(248, 2, 'Gaytri Rathour', 'MSD28122', 'MSD28122', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(249, 2, 'Satyendra nandi', 'MEM02310', 'MSD28622', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(250, 2, 'Harsh sharma', 'MEM02311', 'MSD28611', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(251, 2, 'Sukriti Raj', 'MSD28612', 'MSD28612', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(252, 2, 'Satish mahanand', 'MEM02313', 'MSD28613', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(253, 2, 'Koyal Patle', 'MEM02314', 'MSD28610', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(254, 2, 'Krish Dubey', 'MEM02315', 'MSD28764', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(255, 2, 'Vinod gour', 'MSD28766', 'MSD28766', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(256, 2, 'Kriti Giri', 'MEM02521', 'MSD28716', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(257, 2, 'Piyush Jaiswal', 'MEM02522', 'MSD28763', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(258, 2, 'Mehak Khan', 'MEM02523', 'MSD28765', 'Gem@321', 1, 1, 1, '0000-00-00 00:00:00'),
(259, 2, 'Hemangini Shipre', 'MEM02309', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(260, 2, 'Anam Jahan', 'MEM02301', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(261, 2, 'Ayan Sharma', 'MEM02296', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(262, 2, 'Rakhi Verma', 'MEM02269', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(263, 2, 'Hitaishi Mehra', 'MEM02298', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(264, 2, 'Keerti Tiwari ', 'MEM02293', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(265, 2, 'Mansi Sahu', 'MEM02300', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(266, 2, 'Sohail Khan', 'MEM02228', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(267, 2, 'Ayan Khan', 'MEM02272', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(268, 2, 'Utkarsh.Singhporte', 'MEM02273', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(269, 2, 'Priya Singh Chauhan', 'MEM02280', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(270, 2, 'Paras Verma', 'MEM02284', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(271, 2, 'Anuj Lodhi', 'MEM02274', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(272, 2, 'Shubham Gharu', 'MEM02234', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(273, 2, 'Neha Nagar', 'MEM02278', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(274, 2, 'Tanumoy Bose', 'MEM02265', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(275, 2, 'Neeru Upadhya', 'MEM02249', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(276, 2, 'Pranav Nair', 'MEM02252', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(277, 2, 'Vikrant Singh Rajput', 'MEM02268', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(278, 2, 'Piyush Chouhan', 'MEM02285', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(279, 2, 'Sahil Jamali', 'MEM02259', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(280, 2, 'Md. Ahmad Ansari', 'MEM02266', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(281, 2, 'Aditya Singh Lodhi', 'MEM02275', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(282, 2, 'Anjali Soni', 'MEM02251', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(283, 2, 'Aashi Patidar', 'MEM02246', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(284, 2, 'Harsh sharma', 'MEM02311', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(285, 2, 'Chavi Singh', 'MEM02286', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(286, 2, 'Sonali Mehra', 'MEM02258', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(287, 2, 'Koyal Patle', 'MEM02314', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(288, 2, 'Ankit Singh', 'MEM02305', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(289, 2, 'Satish mahanand', 'MEM02313', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(290, 2, 'Shweta Singh Chauhan', 'MEM02283', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(291, 2, 'Ayush Khatri', 'MEM02294', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(292, 2, 'Rizwan Khan', 'MEM02308', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(293, 2, 'Sagar Tayde', 'MEM02239', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(294, 2, 'Harpreetkaur Virdi', 'MEM02260', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(295, 6, 'Samarth Jamne', 'MEM02245', '', 'Gem@321', 1, 1, 105, '2024-08-21 06:41:58'),
(296, 2, 'Nikita Thakur', 'MEM02282', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(297, 2, 'Amrita Mahanand', 'MEM02263', '', 'Gem@321', 1, 1, 105, '2024-08-29 11:21:47'),
(298, 2, 'Akil.Azhar', 'MEM02254', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(299, 2, 'Neha Somkuwar', 'MEM02238', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(300, 2, 'Harsh KumarMishra', 'MEM02279', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(301, 2, 'Neeraj Malviya', 'MEM02281', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(302, 2, 'Neha Pandagre', 'MEM02237', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(303, 2, 'Deepa Rai', 'MEM02277', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(304, 2, 'Lucky Sadhwani', 'MEM02233', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(305, 2, 'Prabhat Chaurasiya', 'MEM02241', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(306, 2, 'Bhupendra Koal', 'MEM02261', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(307, 2, 'Sapna Raikwar', 'MEM02231', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(308, 2, 'Zamar Ali', 'MEM02256', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(309, 2, 'Unnati Sahu', 'MEM02306', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(310, 2, 'Sayyed Ali', 'MEM02236', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(311, 2, 'Boby Thapa', 'MEM02250', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(312, 2, 'Akriti Panthi', 'MEM02288', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(313, 2, 'Sujal Malvi', 'MEM02247', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(314, 2, 'Rinki Yadav', 'MEM02244', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(315, 2, 'Ronak Singh', 'MEM02243', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(316, 2, 'P.V Srinath', 'MEM02262', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(317, 2, 'Anurag Ashish Kujur ', 'MEM02287', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(318, 2, 'Siddartha Singh Chauhan', 'MEM02232', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(319, 2, 'Mohd Ismail Faisal', 'MEM02302', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(320, 2, 'Ved.Choudhary', 'MEM02253', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(321, 2, 'Abhishek Pastariya', 'MEM02307', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(322, 2, 'Satyendra nandi', 'MEM02310', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(323, 2, 'Shruti Kailasiya', 'MEM02271', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(324, 2, 'Divya Batham', 'MEM02291', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(325, 2, 'Tarun Dhoke', 'MEM02235', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(326, 2, 'Priya Soni', 'MEM02264', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(327, 2, 'Irshad Ahmed', 'MEM02290', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(328, 2, 'Zahida Khatoon', 'MEM02289', '', 'Gem@321', 1, 0, 1, '2023-12-21 18:30:00'),
(329, 2, 'Akash Dehariya', 'MEM02270', '', 'Gem@321', 1, 1, 105, '2024-08-29 08:36:01'),
(330, 2, 'Jai Prakash Rajput', 'MEM02240', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(331, 2, 'Vimal KK', 'MEM02267', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(332, 2, 'Sunil Meena', 'MEM02229', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(333, 2, 'Shivam Yadav', 'MEM02230', '', 'Gem@321', 0, 0, 105, '2024-08-21 06:40:27'),
(334, 2, 'Yashsava Dongre', 'MEM02227', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(335, 2, 'Krish Dubey', 'MEM02315', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(336, 2, 'Kriti Giri', 'MEM02521', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(337, 2, 'Piyush Jaiswal', 'MEM02522', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(338, 2, 'Mehak Khan', 'MEM02523', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(339, 2, 'Arti Nandmer', 'MEM02467', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(340, 2, 'Astha Singh', 'MEM02468', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(341, 2, 'Rimsha Naz', 'MEM02469', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(342, 2, 'Khushboo Soni ', 'MEM02471', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(343, 2, 'Ankit Sahu ', 'MEM02472', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(344, 2, 'Anjali Meena ', 'MEM02473', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(345, 2, 'Rahul Saini ', 'MEM02476', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(346, 2, 'Ayush Sharma ', 'MEM02477', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(347, 2, 'Shubham Sahu', 'MEM02478', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(348, 2, 'Gajendra Deshmukh', 'MEM02481', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(349, 2, 'Sanket Sharma', 'MEM02487', '', 'Gem@321', 0, 0, 105, '2024-08-21 06:41:14'),
(350, 2, 'Varun Mani Tripathi', 'MEM02488', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(351, 2, 'Shireen Raza', 'MEM02489', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(352, 2, 'Shraddha Kamble', 'MEM02524', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(353, 2, 'NIKITA ARYA', 'MEM02525', '', 'Gem@321', 1, 1, 1, '2023-12-21 18:30:00'),
(354, 2, 'SHUBHAM SHARMA ', 'MEM02537', '', 'Gem@321', 1, 1, 1, '2023-12-26 18:30:00'),
(355, 2, 'RAVI THAKRE ', 'MEM02538', '', 'Gem@321', 1, 1, 1, '2023-12-26 18:30:00'),
(356, 2, 'KIRTI PARAJAPTI ', 'MEM02539', '', 'Gem@321', 1, 1, 1, '2023-12-26 18:30:00'),
(357, 2, 'MARY MASIH ', 'MEM02542', '', 'Gem@321', 1, 1, 1, '2023-12-26 18:30:00'),
(358, 2, 'KHUSHBOO RAJPUT', 'MEM02292', '', 'Gem@321', 1, 1, 1, '2023-12-26 18:30:00'),
(359, 2, 'RESHMA SHEIKH ', 'MEM02297', '', 'Gem@321', 1, 1, 1, '2023-12-26 18:30:00'),
(360, 2, 'VIKASH DHAKAD ', 'MEM02303', '', 'Gem@321', 1, 1, 1, '2023-12-26 18:30:00'),
(361, 2, 'PRIYA SINGH', 'MEM02304', '', 'Gem@321', 1, 1, 1, '2023-12-26 18:30:00'),
(362, 2, 'DEV SINGH ', 'MEM02541', '', 'Gem@321', 1, 1, 1, '2024-01-04 18:30:00'),
(363, 2, 'LOKENDRA PAWAR ', 'MEM02543', '', 'Gem@321', 1, 1, 1, '2024-01-04 18:30:00'),
(364, 2, 'HARSH VIJAYWARGIYA', 'MEM02546', '', 'Gem@321', 1, 1, 1, '2024-01-04 18:30:00'),
(365, 2, 'Kanika Vishwakarma', 'MEM02652', '', 'Gem@321', 1, 1, 1, '2024-01-10 18:30:00'),
(366, 2, 'Sukhdev Kamde', 'MEM02653', '', 'Gem@321', 1, 1, 1, '2024-01-10 18:30:00'),
(367, 2, 'Kartik Gondane', 'MEM02654', '', 'Gem@321', 1, 1, 1, '2024-01-10 18:30:00'),
(368, 2, 'Aditi Paliwal', 'MEM02655', '', 'Gem@321', 1, 1, 1, '2024-01-10 18:30:00'),
(369, 2, 'Vipul Dubey', 'MEM02656', '', 'Gem@321', 1, 1, 1, '2024-01-10 18:30:00'),
(370, 2, 'Nikhil Prasad', 'MEM02657', '', 'Gem@321', 1, 1, 1, '2024-01-10 18:30:00'),
(371, 2, 'Diya Rajput', 'MEM02658', '', 'Gem@321', 1, 1, 1, '2024-01-10 18:30:00'),
(372, 2, 'Anshul Meshram', 'MEM02660', '', 'Gem@321', 1, 1, 1, '2024-01-10 18:30:00'),
(373, 2, 'Raj Chaturvedi', 'MEM02662', '', 'Gem@321', 1, 1, 1, '2024-01-10 18:30:00'),
(374, 2, 'Vishal Tiwari', 'MEM02693', '', 'Gem@321', 1, 1, 1, '2024-01-10 18:30:00'),
(375, 2, 'Shivangi Suryawanshi', 'MEM02694', '', 'Gem@321', 1, 1, 1, '2024-01-10 18:30:00'),
(376, 2, 'Nitesh Singh Rathore', 'MEM02696', '', 'Gem@321', 1, 1, 1, '2024-01-10 18:30:00'),
(377, 2, 'Ishan Malviya', 'MEM02699', '', 'Gem@321', 1, 1, 1, '2024-01-10 18:30:00'),
(378, 2, 'Rohit Tiwari', 'MEM02770', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(379, 2, 'Anand Dev', 'MEM02773', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(380, 2, 'Waseem Uddin Ansari', 'MEM02774', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(381, 2, 'Vaishali Masram', 'MEM02775', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(382, 2, 'Roshni Verma', 'MEM02778', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(383, 2, 'Himani Munjare', 'MEM02779', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(384, 2, 'Shweta Barar', 'MEM02781', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(385, 2, 'Tarun Kol', 'MEM02782', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(386, 2, 'Phalguni Bagde', 'MEM02824', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(387, 2, 'Rishabh Tiwari', 'MEM02821', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(388, 2, 'Anand Kumar Dongre', 'MEM02822', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(389, 2, 'Abhinesh Kumar', 'MEM02826', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(390, 2, 'Riya Soni', 'MEM02865', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(391, 2, 'Manya  Pachori', 'MEM02866', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(392, 2, 'Rachna Thakur', 'MEM02867', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(393, 2, 'Vinita Singh', 'MEM02868', '', 'Gem@321', 1, 1, 1, '2024-02-08 18:30:00'),
(394, 2, 'Dixon Devis', 'MEM02945', '', 'Gem@321', 1, 1, 1, '2024-02-14 18:30:00'),
(395, 2, 'Mehak Mehdi Naqvi', 'MEM02946', '', 'Gem@321', 1, 1, 1, '2024-02-14 18:30:00'),
(396, 2, 'Piyush Mehto', 'MEM02947', '', 'Gem@321', 1, 1, 1, '2024-02-14 18:30:00'),
(397, 2, 'Anshul Diwan', 'MEM02948', '', 'Gem@321', 1, 1, 1, '2024-02-14 18:30:00'),
(398, 2, 'Shubham Singh Thakur', 'MEM02949', '', 'Gem@321', 1, 1, 1, '2024-02-14 18:30:00'),
(399, 2, 'Nitin Nagar', 'MEM02950', '', 'Gem@321', 1, 1, 1, '2024-02-14 18:30:00'),
(400, 2, 'Sheetal Dubey', 'MEM02951', '', 'Gem@321', 1, 1, 1, '2024-02-14 18:30:00'),
(401, 2, 'Ritika Chourey', 'MEM02955', '', 'Gem@321', 1, 1, 1, '2024-02-14 18:30:00'),
(402, 2, 'Yogendra Patel', 'MEM02956', '', 'Gem@321', 1, 1, 1, '2024-02-14 18:30:00'),
(403, 2, 'Mayank Dubey', 'MEM02960', '', 'Gem@321', 1, 1, 1, '2024-02-14 18:30:00'),
(404, 2, 'John Thomas', 'MEM02953', '', 'Gem@321', 1, 1, 1, '2024-02-18 18:30:00'),
(405, 2, 'Richa Sharma', 'MEM02954', '', 'Gem@321', 1, 1, 1, '2024-02-18 18:30:00'),
(406, 2, 'Govind Gound', 'MEM03028', '', 'Gem@321', 1, 1, 1, '2024-03-05 18:30:00'),
(407, 2, 'Saurabh Purohit', 'MEM03025', '', 'Gem@321', 1, 1, 1, '2024-03-05 18:30:00'),
(408, 2, 'Amit Rajput', 'MEM03032', '', 'Gem@321', 1, 1, 1, '2024-03-05 18:30:00'),
(409, 2, 'Shilpi Raikwar', 'MEM03026', '', 'Gem@321', 1, 1, 1, '2024-03-05 18:30:00'),
(410, 2, 'Akhilesh Raut', 'MEM03031', '', 'Gem@321', 1, 1, 1, '2024-03-05 18:30:00'),
(411, 2, 'Kunvarjeet Singh Rajput', 'MEM03027', '', 'Gem@321', 1, 1, 1, '2024-03-05 18:30:00'),
(412, 2, 'Harsh kumar pandey', 'MEM03105', '', 'Gem@321', 1, 1, 1, '2024-03-05 18:30:00'),
(413, 2, 'Abhishek Soni', 'MEM03024', '', 'Gem@321', 1, 1, 1, '2024-03-10 18:30:00'),
(414, 2, 'Suresh Bhodele', 'MEM03030', '', 'Gem@321', 1, 1, 1, '2024-03-10 18:30:00'),
(415, 2, 'Suraj Samaddar', 'MEM03109', '', 'Gem@321', 1, 1, 1, '2024-03-10 18:30:00'),
(416, 2, 'Honey Pandey', 'MEM03343', '', 'Gem@321', 1, 1, 1, '2024-04-02 18:30:00'),
(417, 2, 'Nilima Dhote', 'MEM03344', '', 'Gem@321', 1, 1, 1, '2024-04-02 18:30:00'),
(418, 2, 'Shivani Soni', 'MEM03345', '', 'Gem@321', 1, 1, 1, '2024-04-02 18:30:00'),
(419, 2, 'Anuj Gupta', 'MEM03347', '', 'Gem@321', 1, 1, 1, '2024-04-02 18:30:00'),
(420, 2, 'Ajay Meena', 'MEM03349', '', 'Gem@321', 1, 1, 1, '2024-04-02 18:30:00'),
(421, 2, 'Yogesh Kumar Mishra', 'MEM03346', '', 'Gem@321', 1, 1, 1, '2024-04-05 18:30:00'),
(422, 2, 'Yashsava Dongre', 'MEM02227', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(423, 2, 'Sohail Khan', 'MEM02228', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(424, 2, 'Sunil Meena', 'MEM02229', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(425, 6, 'Shivam Yadav', 'MEM02230', '', 'Gem@321', 1, 1, 105, '2024-08-21 06:40:55'),
(426, 2, 'Siddartha Singh Chauhan', 'MEM02232', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(427, 2, 'Neha Somkuwar', 'MEM02238', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(428, 2, 'Sagar Tayde', 'MEM02239', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(429, 2, 'Jai Prakash Rajput', 'MEM02240', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(430, 2, 'Prabhat Chaurasiya', 'MEM02241', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(431, 2, 'Ronak Singh', 'MEM02243', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(432, 2, 'Rinki Yadav', 'MEM02244', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(433, 2, 'Samarth Jamne', 'MEM02245', '', 'Gem@321', 0, 0, 105, '2024-08-21 06:41:45'),
(434, 2, 'Anjali Soni', 'MEM02251', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(435, 2, 'Akil Azhar', 'MEM02254', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(436, 2, 'Zamar Ali', 'MEM02256', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(437, 2, 'Bhupendra Koal', 'MEM02261', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(438, 2, 'P V Srinath', 'MEM02262', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(439, 2, 'Amrita Mahanand', 'MEM02263', '', 'Gem@321', 1, 1, 105, '2024-08-29 11:21:48'),
(440, 2, 'Tanumoy Bose', 'MEM02265', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(441, 2, 'Md Ahmad Ansari', 'MEM02266', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(442, 2, 'Vimal Kk ', 'MEM02267', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(443, 2, 'Vikrant Singh Rajput', 'MEM02268', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(444, 2, 'Rakhi Verma', 'MEM02269', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(445, 2, 'Akash Dehariya', 'MEM02270', '', 'Gem@321', 1, 1, 105, '2024-08-29 08:36:00'),
(446, 2, 'Ayan Khan', 'MEM02272', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(447, 2, 'Neha Nagar ', 'MEM02278', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(448, 2, 'Priya Singh Chauhan ', 'MEM02280', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(449, 2, 'Neeraj Malviya ', 'MEM02281', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(450, 2, 'Nikita Thakur ', 'MEM02282', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(451, 2, 'Shweta Singh Chauhan', 'MEM02283', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(452, 2, 'Paras Verma', 'MEM02284', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(453, 2, 'Piyush Chouhan', 'MEM02285', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(454, 2, 'Anurag Ashish Kujur ', 'MEM02287', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(455, 2, 'Akriti Panthi', 'MEM02288', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(456, 2, 'Zahida Khatoon', 'MEM02289', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(457, 2, 'Irshad Ahmed', 'MEM02290', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(458, 2, 'Ayush Khatri', 'MEM02294', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(459, 2, 'Hitashi Mehra', 'MEM02298', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(460, 2, 'Anam Jahan', 'MEM02301', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(461, 2, 'Mohd Ismail Khan', 'MEM02302', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(462, 2, 'Ankit Singh', 'MEM02305', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(463, 2, 'Rizwan Khan', 'MEM02308', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(464, 2, 'Hemangini Shipre', 'MEM02309', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(465, 2, 'Koyal Patle', 'MEM02314', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(466, 2, 'Krish Dubey', 'MEM02315', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(467, 2, 'Kriti Giri', 'MEM02521', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(468, 2, 'Piyush Jaiswal', 'MEM02522', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(469, 2, 'Nikita Arya', 'MEM02525', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(470, 2, 'Arti Nandmer', 'MEM02467', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(471, 2, 'Rimsha Naz', 'MEM02469', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(472, 2, 'Khushboo Soni ', 'MEM02471', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(473, 2, 'Shubham Sahu', 'MEM02478', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(474, 2, 'Varun Mani Tripathi', 'MEM02488', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(475, 2, 'Shradha Kamble', 'MEM02524', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(476, 2, 'Ravi Thakre ', 'MEM02538', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(477, 2, 'Kirti Parajapti ', 'MEM02539', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(478, 2, 'Mary Masih ', 'MEM02542', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(479, 2, 'Khushboo Rajput', 'MEM02292', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(480, 2, 'Priya Singh', 'MEM02304', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(481, 2, 'Kanika Vishwakarma', 'MEM02652', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(482, 2, 'Sukhdev Kamde', 'MEM02653', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(483, 2, 'Kartik Gondane', 'MEM02654', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(484, 2, 'Vipul Dubey', 'MEM02656', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(485, 2, 'Diya Rajput', 'MEM02658', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(486, 2, 'Anshul Meshram', 'MEM02660', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(487, 2, 'Raj Chaturvedi', 'MEM02662', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(488, 2, 'Vishal Tiwari', 'MEM02693', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(489, 2, 'Shivangi Suryawanshi', 'MEM02694', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(490, 2, 'Nitesh Singh Rathore', 'MEM02696', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(491, 2, 'Ishan Malviya', 'MEM02699', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(492, 2, 'Phalguni Bagde', 'MEM02824', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(493, 2, 'Mehak Mehdi Naqvi', 'MEM02946', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(494, 2, 'Nitin Nagar', 'MEM02950', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(495, 2, 'John Thomas', 'MEM02953', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(496, 2, 'Richa Sharma', 'MEM02954', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(497, 2, 'Ritika Chourey', 'MEM02955', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(498, 2, 'Mayank Dubey', 'MEM02960', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(499, 2, 'Abhishek Soni', 'MEM03024', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(500, 2, 'Saurabh Purohit', 'MEM03025', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(501, 2, 'Shilpi Raikwar', 'MEM03026', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(502, 2, 'Akhilesh Raut', 'MEM03031', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(503, 2, 'Amit Rajput', 'MEM03032', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(504, 2, 'Harsh Kumar Pandey', 'MEM03105', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(505, 2, 'Suraj Samaddar', 'MEM03109', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(506, 2, 'Honey Pandey', 'MEM03343', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(507, 2, 'Nilima Dhote', 'MEM03344', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(508, 2, 'Shivani Soni', 'MEM03345', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(509, 2, 'Yogesh Kumar Mishra', 'MEM03346', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(510, 2, 'Ajay Meena', 'MEM03349', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(511, 2, 'Shivam Mourya', 'MEM03398', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(512, 2, 'Mayank Soni', 'MEM03399', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(513, 2, 'Akshay Pratap Singh', 'MEM03401', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(514, 2, 'Pratima Singh', 'MEM03403', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(515, 2, 'Bhavani Saran Mishra', 'MEM03503', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(516, 2, 'Zainab Khan', 'MEM03504', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(517, 2, 'Neetu  Singh', 'MEM03505', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(518, 2, 'Yash Batta', 'MEM03506', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(519, 2, 'Kratika Chandrol', 'MEM03507', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(520, 2, 'Shubham Pawar', 'MEM03508', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(521, 2, 'Ayush Shrivastava', 'MEM03509', '', 'MEM03509@321', 1, 1, 521, '2025-03-21 09:28:29'),
(522, 2, 'Prashant Gupta', 'MEM03510', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(523, 2, 'Satyajeet Agnihotri', 'MEM03511', '', 'Gem@321', 1, 1, 1, '2024-06-06 18:30:00'),
(524, 2, 'Khushilal Ahirwar', 'MEM04380', '', 'Gem@321', 1, 1, 1, '2024-07-31 18:30:00'),
(525, 2, 'Aman Namdeo', 'MEM04381', '', 'Gem@321', 1, 1, 1, '2024-07-31 18:30:00'),
(526, 2, 'Nivedita Kaithal', 'MEM04379', '', 'Gem@321', 1, 1, 1, '2024-07-31 18:30:00'),
(527, 2, 'Manjeet Kaur', 'MEM04377', '', 'Gem@321', 1, 1, 1, '2024-07-31 18:30:00'),
(528, 2, 'Mausmi Das Gupta', 'MEM04382', '', 'Gem@321', 1, 1, 1, '2024-07-31 18:30:00'),
(529, 2, 'Harsh Malviya ', 'MEM04374', '', 'Gem@321', 1, 1, 1, '2024-07-31 18:30:00'),
(530, 4, 'Anam Qureshi', 'MEM02335', '', 'Gem@321', 1, 1, 105, '2024-08-21 06:26:39'),
(531, 4, 'Devendra Kumar Gupta', 'MEM02334', '', 'Gem@321', 1, 1, 105, '2024-08-21 06:27:15'),
(532, 4, 'Neeraj Raghuwanshi', 'MEM02331', '', 'Gem@321', 1, 1, 105, '2024-08-21 06:27:30'),
(533, 4, 'Prakash Malvi', 'MEM02321', '', 'Gem@321', 1, 1, 105, '2024-08-21 06:27:48'),
(534, 5, 'Naveen Kumar', 'MEM02324', '', 'Gem@321', 1, 1, 105, '2024-08-21 06:28:06'),
(535, 5, 'Rupesh Jain', 'MEM02320', '', 'Gem@321', 1, 1, 105, '2024-08-21 06:28:20'),
(536, 5, 'Sunil Yadav', 'MEM02317', '', 'Gem@321', 1, 1, 105, '2024-08-21 06:28:35'),
(537, 5, 'Raju Singh', 'MEM02328', '', 'Gem@321', 1, 1, 105, '2024-08-21 06:29:49'),
(538, 7, 'Aniruddh Singh', 'MEM02330', '', 'Gem@321', 1, 1, 105, '2024-08-21 06:30:26'),
(539, 1, 'Rekha Saxena', 'MSD22633', '', 'Gem@321', 1, 1, 105, '2024-08-21 06:39:40'),
(540, 2, 'Ritika Pal', 'MEM04567', '', 'Gem@321', 1, 1, 1, '2024-09-01 18:30:00'),
(541, 2, 'Preeti Singh Maurya', 'MEM04568', '', 'Gem@321', 1, 1, 1, '2024-09-01 18:30:00'),
(542, 2, 'Saurabh Kumar', 'MEM04569', '', 'Gem@321', 1, 1, 1, '2024-09-01 18:30:00'),
(543, 2, 'Varsha Biswas', 'MEM04570', '', 'Gem@321', 1, 1, 1, '2024-09-01 18:30:00'),
(544, 2, 'Zeeshan Khan', 'MEM04572', '', 'Gem@321', 1, 1, 1, '2024-09-01 18:30:00'),
(545, 2, 'Rishikesh Tavade ', 'MEM04573', '', 'Gem@321', 1, 1, 1, '2024-09-01 18:30:00'),
(546, 2, 'Diksha Dhoke', 'MEM04607', '', 'Gem@321', 1, 1, 1, '2024-09-01 18:30:00'),
(547, 2, 'Savita Choudhary', 'MEM04416', '', 'Gem@321', 1, 1, 1, '2024-09-01 18:30:00'),
(548, 2, 'Sandeep Chaudhary', 'MEM04774', '', 'Gem@321', 1, 1, 1, '2024-10-04 18:30:00'),
(549, 2, 'Anubhav Shrivastava ', 'MEM04779', '', 'Gem@321', 1, 1, 1, '2024-10-04 18:30:00'),
(550, 2, 'Sneha  ', 'MEM04782', '', 'Gem@321', 1, 1, 1, '2024-10-04 18:30:00'),
(551, 2, 'Mohd. Asfain Shaikh ', 'MEM04784', '', 'Gem@321', 1, 1, 1, '2024-10-04 18:30:00'),
(552, 2, 'Yashwant Machhiwal ', 'MEM04818', '', 'Gem@321', 1, 1, 1, '2024-10-04 18:30:00'),
(553, 2, 'Priyanka Kushwaha ', 'MEM04827', '', 'Gem@321', 1, 1, 1, '2024-10-04 18:30:00'),
(554, 2, 'S Dayanand ', 'MEM04829', '', 'Gem@321', 1, 1, 1, '2024-10-04 18:30:00'),
(555, 2, 'S kunal Kumar', 'MEM04830', '', 'Gem@321', 1, 1, 1, '2024-10-04 18:30:00'),
(556, 2, 'Daniel David ', 'MEM05057', '', 'Gem@321', 1, 1, 1, '2024-10-16 18:30:00'),
(557, 2, 'Jai Prakash Rajput', 'MEM05085', '', 'Gem@321', 1, 1, 1, '2024-10-16 18:30:00'),
(558, 2, 'Kalash Meena ', 'MEM05053', '', 'Gem@321', 1, 1, 1, '2024-10-16 18:30:00'),
(559, 2, 'Muskan Ghoshi ', 'MEM05055', '', 'Gem@321', 1, 1, 1, '2024-10-16 18:30:00'),
(560, 2, 'ANKIT RATHORE', 'MEM05048', '', 'Gem@321', 1, 1, 1, '2024-10-16 18:30:00'),
(561, 2, 'John Wilson Tirkey', 'MEM05046', '', 'Gem@321', 1, 1, 1, '2024-10-16 18:30:00'),
(562, 2, 'Amit Jat', 'MEM05047', '', 'Gem@321', 1, 1, 1, '2024-10-16 18:30:00'),
(563, 2, 'Namrata Dhakar', 'MEM05049', '', 'Gem@321', 1, 1, 1, '2024-10-16 18:30:00'),
(564, 2, 'Garima Sharma  ', 'MEM05050', '', 'Gem@321', 1, 1, 1, '2024-10-16 18:30:00');
INSERT INTO `master_users` (`user_id`, `role_id`, `user_name`, `msd_id`, `old_msd_id`, `user_password`, `login_status`, `status`, `eby`, `eat`) VALUES
(565, 2, 'Pariyant Namdev', 'MEM05226', '', 'Gem@321', 1, 1, 1, '2024-11-14 18:30:00'),
(566, 2, 'Yash Fadnavis', 'MEM05227', '', 'Gem@321', 1, 1, 1, '2024-11-14 18:30:00'),
(567, 2, 'Shristi Sinha', 'MEM05229', '', 'Gem@321', 1, 1, 1, '2024-11-14 18:30:00'),
(568, 2, 'Maroof Khan', 'MEM05231', '', 'Gem@321', 1, 1, 1, '2024-11-14 18:30:00'),
(569, 2, 'Ruchi Jha', 'MEM05232', '', 'Gem@321', 1, 1, 1, '2024-11-14 18:30:00'),
(570, 2, 'Yash Datir', 'MEM05233', '', 'Gem@321', 1, 1, 1, '2024-11-14 18:30:00'),
(571, 2, 'Abhishek Sharma', 'MEM05237', '', 'Gem@321', 1, 1, 1, '2024-11-14 18:30:00'),
(572, 2, 'Mansi Yadav', 'MEM05234', '', 'Gem@321', 1, 1, 1, '2024-11-21 18:30:00'),
(573, 2, 'Atul rajak', 'MEM05296', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(574, 2, 'Aditya Saxena ', 'MEM05299', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(575, 2, 'Tanu Sharma ', 'MEM05302', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(576, 2, 'Sanjay Karen', 'MEM05303', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(577, 2, 'Aditya B', 'MEM05310', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(578, 2, 'Khurram Ilyas Khan', 'MEM05305', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(579, 2, 'Poonam  Tiwari', 'MEM05306', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(580, 2, 'Ritika Prajapati', 'MEM05307', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(581, 2, 'Rakhi Daheriya ', 'MEM05308', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(582, 2, 'Sharad Jha ', 'MEM05309', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(583, 2, 'Shivani Yadav ', 'MEM05311', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(584, 2, 'Vishal Parmar', 'MEM05346', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(585, 2, 'Divyanshi Singh ', 'MEM05347', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(586, 2, 'Anjali Rai ', 'MEM05348', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(587, 2, 'Yogesh Thakur ', 'MEM05349', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(588, 2, 'Apoorwa Rajpoot', 'MEM05351', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(589, 2, 'Kirti Yadav', 'MEM05352', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(590, 2, 'Ayush Saxena', 'MEM05353', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(591, 2, 'Shivani Balmiki ', 'MEM05357', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(592, 2, 'Sachin Khadve', 'MEM05359', '', 'Gem@321', 1, 1, 1, '2024-12-11 18:30:00'),
(593, 2, 'Shrishti bihariya', 'MEM05458', '', 'Gem@321', 1, 1, 1, '2024-12-30 18:30:00'),
(594, 2, 'Brajesh Yadav', 'MEM05459', '', 'Gem@321', 1, 1, 1, '2024-12-30 18:30:00'),
(595, 2, 'Shubham sharma', 'MEM05461', '', 'Gem@321', 1, 1, 1, '2024-12-30 18:30:00'),
(596, 2, 'Gayatri Vishwakarma', 'MEM05462', '', 'Gem@321', 1, 1, 1, '2024-12-30 18:30:00'),
(597, 2, 'Vanshika bilthariya', 'MEM05463', '', 'Gem@321', 1, 1, 1, '2024-12-30 18:30:00'),
(598, 2, 'Gaurang Patel', 'MEM05464', '', 'Gem@321', 1, 1, 1, '2024-12-30 18:30:00'),
(599, 2, 'Jigyasha Mishra', 'MEM05465', '', 'Gem@321', 1, 1, 1, '2024-12-30 18:30:00'),
(600, 2, 'Anjali deep', 'MEM05466', '', 'Gem@321', 1, 1, 1, '2024-12-30 18:30:00'),
(601, 2, 'Richa Patel', 'MEM05500', '', 'Gem@321', 1, 1, 1, '2024-12-30 18:30:00'),
(602, 2, 'Gyanendra Kumar Rao', 'MEM05502', '', 'Gem@321', 1, 1, 1, '2024-12-30 18:30:00'),
(603, 2, 'Pragati Singh', 'MEM05504', '', 'Gem@321', 1, 1, 1, '2024-12-30 18:30:00'),
(604, 2, 'Ritesh Vishwakarma', 'MEM05505', '', 'Gem@321', 1, 1, 1, '2024-12-30 18:30:00'),
(605, 2, 'Sakshi Jain', 'MEM05457', '', 'Gem@321', 1, 1, 1, '2025-01-07 18:30:00'),
(606, 2, 'Anjali pathak', 'MEM05980', '', 'Gem@321', 1, 1, 1, '2025-03-07 18:30:00'),
(607, 2, 'Shweta Prabhakar', 'MEM05981', '', 'Gem@321', 1, 1, 1, '2025-03-07 18:30:00'),
(608, 2, 'Pratibha Singh Rajpoot', 'MEM05983', '', 'Gem@321', 1, 1, 1, '2025-03-07 18:30:00'),
(609, 2, 'Rajni Adiwal', 'MEM05984', '', 'Gem@321', 1, 1, 1, '2025-03-07 18:30:00'),
(610, 2, 'Uma Sharma', 'MEM05990', '', 'Gem@321', 1, 1, 1, '2025-03-07 18:30:00'),
(611, 2, 'Shivansh Mishra', 'MEM05991', '', 'Gem@321', 1, 1, 1, '2025-03-07 18:30:00'),
(612, 2, 'Anjali Yadav', 'MEM06029', '', 'Gem@321', 1, 1, 1, '2025-03-07 18:30:00'),
(613, 2, 'Taufique Shah', 'MEM06070', '', 'Gem@321', 1, 1, 1, '2025-03-30 18:30:00'),
(614, 2, 'Payal Pawar  ', 'MEM06063', '', 'Gem@321', 1, 1, 1, '2025-03-30 18:30:00'),
(615, 2, 'Abhishek Singh', 'MEM06073', '', 'Gem@321', 1, 1, 1, '2025-03-30 18:30:00'),
(616, 2, 'Uphar Patel', 'MEM06072', '', 'Gem@321', 1, 1, 1, '2025-03-30 18:30:00'),
(617, 2, 'Jayant chourey', 'MEM06065', '', 'Gem@321', 1, 1, 1, '2025-03-30 18:30:00'),
(618, 2, 'Pratyush Chouhan', 'MEM06107', '', 'Gem@321', 1, 1, 1, '2025-03-30 18:30:00'),
(619, 2, 'Priyesh Bahuguna', 'MEM06068', '', 'Gem@321', 1, 1, 1, '2025-03-30 18:30:00'),
(620, 2, 'Naaz Sayyed', 'MEM06064', '', 'Gem@321', 1, 1, 1, '2025-03-30 18:30:00'),
(621, 2, 'Hashir Mohd Khan', 'MEM06067', '', 'Gem@321', 1, 1, 1, '2025-03-30 18:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `ledger_id` int(11) DEFAULT NULL,
  `installment_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_mode` varchar(50) DEFAULT NULL,
  `receipt_no` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `ledger_id`, `installment_id`, `amount`, `payment_date`, `payment_mode`, `receipt_no`) VALUES
(1, 2, 4, 28833.33, '2026-02-21', 'UPI', 'RCPT20260221110859');

-- --------------------------------------------------------

--
-- Table structure for table `payment_logs`
--

CREATE TABLE `payment_logs` (
  `id` int(11) NOT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `payment_logs`
--

INSERT INTO `payment_logs` (`id`, `payment_id`, `action`, `action_date`) VALUES
(1, 1, 'CREATED', '2026-02-21 05:38:59');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_counter`
--

CREATE TABLE `receipt_counter` (
  `id` int(11) NOT NULL,
  `last_receipt` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` int(11) NOT NULL,
  `admission_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `refund_date` date DEFAULT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `refunds`
--

INSERT INTO `refunds` (`id`, `admission_id`, `amount`, `refund_date`, `reason`) VALUES
(1, 2, 2000.00, '2026-02-21', 'TEST Purpose'),
(2, 2, 1500.00, '2026-02-19', 'TEST Purpose 2');

-- --------------------------------------------------------

--
-- Table structure for table `scholarships`
--

CREATE TABLE `scholarships` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `discount_percent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `session_name` varchar(20) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `session_name`, `status`) VALUES
(1, '2025-2026', 1),
(2, '2026-2027', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT 2,
  `name` varchar(100) DEFAULT NULL,
  `father_name` varchar(100) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `role_id`, `name`, `father_name`, `mobile`, `email`, `dob`, `address`, `photo`, `created_at`, `status`) VALUES
(1, 2, 'Yogesh Namdev', 'Rajesh Namdev', '8871243753', 'namdev.yash10@gmail.com', '1998-10-10', 'Bhopal', '1771649871.png', '2026-02-21 04:57:51', 1),
(2, 2, 'Sharad Mehra', 'Test Mehra', '8871243753', 'Sharad@gmail.com', '2000-10-24', 'Bhopal', '1771649911.png', '2026-02-21 04:58:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_documents`
--

CREATE TABLE `student_documents` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `document_name` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `student_documents`
--

INSERT INTO `student_documents` (`id`, `student_id`, `document_name`, `file_path`, `created_at`) VALUES
(1, 2, '54fedc1ad486b1e6c5b615baa43134f6', 'student_docs\\54fedc1ad486b1e6c5b615baa43134f6.png', '2026-02-22 08:31:21'),
(2, 2, '98f358c6fc95bedf79bcf672a319d0f3', 'student_docs\\98f358c6fc95bedf79bcf672a319d0f3.png', '2026-02-22 08:31:21'),
(3, 2, 'd8c9230c06d16d0e732d0aafbf979882', 'student_docs\\d8c9230c06d16d0e732d0aafbf979882.png', '2026-02-22 08:32:10'),
(4, 2, 'd2949edce5f207ecffb399e66546f546', 'student_docs\\d2949edce5f207ecffb399e66546f546.png', '2026-02-22 08:32:10');

-- --------------------------------------------------------

--
-- Table structure for table `student_fee_ledger`
--

CREATE TABLE `student_fee_ledger` (
  `id` int(11) NOT NULL,
  `admission_id` int(11) DEFAULT NULL,
  `total_fee` decimal(10,2) DEFAULT NULL,
  `paid` decimal(10,2) DEFAULT 0.00,
  `balance` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `student_fee_ledger`
--

INSERT INTO `student_fee_ledger` (`id`, `admission_id`, `total_fee`, `paid`, `balance`) VALUES
(1, 1, 134100.00, 0.00, 134100.00),
(2, 2, 89500.00, 25333.33, 64166.67);

-- --------------------------------------------------------

--
-- Table structure for table `student_scholarship`
--

CREATE TABLE `student_scholarship` (
  `id` int(11) NOT NULL,
  `admission_id` int(11) DEFAULT NULL,
  `scholarship_id` int(11) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admissions`
--
ALTER TABLE `admissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batches`
--
ALTER TABLE `batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_installments`
--
ALTER TABLE `fee_installments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_structures`
--
ALTER TABLE `fee_structures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_types`
--
ALTER TABLE `fee_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fine_rules`
--
ALTER TABLE `fine_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_table`
--
ALTER TABLE `log_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_roles`
--
ALTER TABLE `master_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `master_users`
--
ALTER TABLE `master_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_logs`
--
ALTER TABLE `payment_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipt_counter`
--
ALTER TABLE `receipt_counter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scholarships`
--
ALTER TABLE `scholarships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_documents`
--
ALTER TABLE `student_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_fee_ledger`
--
ALTER TABLE `student_fee_ledger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_scholarship`
--
ALTER TABLE `student_scholarship`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admissions`
--
ALTER TABLE `admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `batches`
--
ALTER TABLE `batches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fee_installments`
--
ALTER TABLE `fee_installments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `fee_structures`
--
ALTER TABLE `fee_structures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `fee_types`
--
ALTER TABLE `fee_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fine_rules`
--
ALTER TABLE `fine_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_table`
--
ALTER TABLE `log_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `master_roles`
--
ALTER TABLE `master_roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `master_users`
--
ALTER TABLE `master_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=622;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_logs`
--
ALTER TABLE `payment_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `receipt_counter`
--
ALTER TABLE `receipt_counter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `scholarships`
--
ALTER TABLE `scholarships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sms_logs`
--
ALTER TABLE `sms_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_documents`
--
ALTER TABLE `student_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_fee_ledger`
--
ALTER TABLE `student_fee_ledger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_scholarship`
--
ALTER TABLE `student_scholarship`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `batches`
--
ALTER TABLE `batches`
  ADD CONSTRAINT `batches_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
