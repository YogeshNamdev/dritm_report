-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 10, 2021 at 03:45 PM
-- Server version: 8.0.27-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `enquiry_form`
--

CREATE TABLE `enquiry_form` (
  `enquiry_id` int NOT NULL,
  `name` varchar(128) NOT NULL,
  `childname` varchar(128) NOT NULL,
  `grade` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `contact` varchar(128) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `eat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `eby` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `enquiry_form`
--

INSERT INTO `enquiry_form` (`enquiry_id`, `name`, `childname`, `grade`, `email`, `contact`, `status`, `eat`, `eby`) VALUES
(1, 'Test SynQues', 'sdsdsd', 'Nursery (11:15 am to 12:15 pm)', 'deeksha@gmail.com', '1234567890', 1, '2021-12-09 11:10:52', 0),
(2, 'asasas', 'asassa', 'Jr. KG (12:30 pm to 1:45 pm)', 'deeksha.bamuriya09@gmail.com', '8982302335', 1, '2021-12-09 11:16:13', 0),
(3, 'deeksha', 'bamuriya', 'Nursery (7:15 pm to 8:15 pm)', 'deeksha.bamuriya09@gmail.com', '8982302335', 1, '2021-12-09 11:25:32', 0),
(4, 'dee', 'ssss', 'Jr. KG (12:30 pm to 1:45 pm)', 'deeksha.bamuriya09@gmail.com', '8982302335', 1, '2021-12-09 11:28:36', 0),
(5, 'mukul', 'rajak', 'Nursery (11:15 am to 12:15 pm)', 'mukul.rajak@synques.in', '8985632598', 1, '2021-12-09 11:33:02', 0),
(6, 'dee', 'sdsdsd', 'Nursery (11:15 am to 12:15 pm)', 'deeksha@gmail.com', '1234567890', 1, '2021-12-09 11:39:20', 0),
(7, 'jojhn', 'sdsdsd', 'Nursery (11:15 am to 12:15 pm)', 'utkarsh.tiwary@synques.in', '4562635896', 1, '2021-12-09 11:44:12', 0),
(8, 'Test SynQues', 'sdsdsd', 'Nursery (7:15 pm to 8:15 pm)', 'mukul.rajak@synques.in', '8563269856', 1, '2021-12-09 11:48:14', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `enquiry_form`
--
ALTER TABLE `enquiry_form`
  ADD PRIMARY KEY (`enquiry_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `enquiry_form`
--
ALTER TABLE `enquiry_form`
  MODIFY `enquiry_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
