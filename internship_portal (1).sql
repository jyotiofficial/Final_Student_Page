-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2023 at 10:22 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `internship_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `announcement_title` varchar(50) NOT NULL,
  `admission_no` varchar(10) NOT NULL,
  `contact_no` varchar(20) NOT NULL,
  `student_location` varchar(255) NOT NULL,
  `cv_file` varchar(255) NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `resume` longblob DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `student_name`, `announcement_title`, `admission_no`, `contact_no`, `student_location`, `cv_file`, `application_date`, `resume`, `action`, `comment`) VALUES
(1, 'A', 'Tata', 'a', 'a', 'a', 'A_Tata_a.pdf', '2023-07-17 08:17:48', 0x415f546174615f612e706466, NULL, NULL),
(2, 'B', 'Mahindra', 'b', 'b', 'b', 'B_Mahindra_b.pdf', '2023-07-17 08:18:04', 0x425f4d6168696e6472615f622e706466, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_panel`
--

CREATE TABLE `faculty_panel` (
  `fac_id` int(11) NOT NULL,
  `fac_name` varchar(128) NOT NULL,
  `fac_email` varchar(128) NOT NULL,
  `fac_age` int(11) NOT NULL,
  `fac_mobile` int(11) NOT NULL,
  `fac_address` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_students`
--

CREATE TABLE `group_students` (
  `id` int(11) NOT NULL,
  `groupId` int(11) DEFAULT NULL,
  `studentName` varchar(255) DEFAULT NULL,
  `studentAdmNumber` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_students`
--

INSERT INTO `group_students` (`id`, `groupId`, `studentName`, `studentAdmNumber`) VALUES
(1, 15, 'abc', '1'),
(2, 15, 'xyz', '2'),
(3, 15, 'pqr', '3'),
(4, 15, 'tuv', '4'),
(5, 15, 'rst', '5'),
(6, 3, 'abc', '123'),
(7, 3, 'pqr', '456'),
(8, 3, 'xyz', '789'),
(9, 3, 'tuv', '1011'),
(10, 3, 'ghi', '1213');

-- --------------------------------------------------------

--
-- Table structure for table `internship_applications`
--

CREATE TABLE `internship_applications` (
  `ID` int(11) NOT NULL,
  `CompanyName` varchar(255) DEFAULT NULL,
  `CompanyAddress` varchar(255) DEFAULT NULL,
  `CompanyLocation` varchar(255) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `Stipend` decimal(10,2) DEFAULT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status` varchar(255) DEFAULT NULL,
  `Comment` text DEFAULT NULL,
  `Certificate_LOR` varchar(255) DEFAULT NULL,
  `Action` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `internship_applications`
--

INSERT INTO `internship_applications` (`ID`, `CompanyName`, `CompanyAddress`, `CompanyLocation`, `startDate`, `endDate`, `branch`, `semester`, `Stipend`, `Location`, `created_at`, `Status`, `Comment`, `Certificate_LOR`, `Action`) VALUES
(1, 'ABC', '123', '123', '2023-01-01', '2023-02-01', 'ECS', 'Semester 6', 123.00, '123', '2023-07-02 15:24:03', NULL, NULL, 'certificate_uploads/certificate_64a1998f58c48_Certificate2.pdf', NULL),
(2, 'Louis Vuitton', 'abc', 'abc', '2023-10-05', '2023-10-06', 'AUTO', 'Semester 1', 213.00, '123', '2023-07-07 14:05:28', NULL, NULL, NULL, NULL),
(3, 'Louis Vuitton', '123 Stuart, LA', 'LA', '2023-01-02', '2023-02-02', 'ECS', 'Semester 3', 123.00, '132', '2023-07-10 07:29:07', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `internship_certificates`
--

CREATE TABLE `internship_certificates` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `certificate_type` enum('insider','outsider') DEFAULT NULL,
  `certificate_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `internship_certificates`
--

INSERT INTO `internship_certificates` (`id`, `student_id`, `certificate_type`, `certificate_file`) VALUES
(1, 1, 'insider', 'certificate_1.pdf'),
(2, 1, 'insider', 'certificate_1.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `new_annoucement`
--

CREATE TABLE `new_annoucement` (
  `announcement_id` int(11) NOT NULL,
  `announcement_title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `skills_required` text NOT NULL,
  `location` text NOT NULL,
  `start_date` date NOT NULL,
  `duration` int(11) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `work_type` varchar(255) NOT NULL,
  `stipend_type` varchar(10) NOT NULL,
  `stipend` text NOT NULL,
  `work_location` varchar(50) NOT NULL,
  `perks` varchar(255) NOT NULL,
  `user_id` varchar(80) NOT NULL,
  `published_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `new_annoucement`
--

INSERT INTO `new_annoucement` (`announcement_id`, `announcement_title`, `description`, `skills_required`, `location`, `start_date`, `duration`, `branch`, `work_type`, `stipend_type`, `stipend`, `work_location`, `perks`, `user_id`, `published_on`, `status`) VALUES
(1, 'Tata', '123.', '123.', '123.', '2023-01-02', 1, 'ECS', 'Paid', 'UnPaid', '123.', 'WFH', '123.', '123.', '2023-07-17 06:05:50', ''),
(2, 'Mahindra', '123.', '123.', '123.', '2023-01-02', 1, 'CS', 'Paid', 'UnPaid', '123.', 'WFH', '123.', '456.', '2023-07-17 06:07:27', ''),
(3, 'Honda', '123.', '123.', '123.', '2023-01-02', 1, 'MECH', 'Paid', 'UnPaid', '123.', 'WFH', '123.', '789.', '2023-07-17 06:11:35', ''),
(4, 'Royal Enfield', '123.', '123.', '123.', '2023-01-02', 1, 'AUTO', 'Paid', 'Monthly', '123.', 'WFH', '123.', '', '2023-07-17 07:12:35', ''),
(5, 'ABC', '123.', '123.', '123.', '2023-01-02', 1, 'EXTC', 'Paid', 'Monthly', '123.', 'WFH', '123.', '', '2023-07-17 07:49:13', '');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `s_id` int(11) NOT NULL,
  `s_name` varchar(128) NOT NULL,
  `s_email` varchar(128) NOT NULL,
  `s_age` int(11) NOT NULL,
  `s_mobile` int(11) NOT NULL,
  `s_address` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_profile`
--

CREATE TABLE `student_profile` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_profile`
--

INSERT INTO `student_profile` (`id`, `fullName`, `email`, `age`, `mobile`, `address`) VALUES
(1, 'Jason Blossom', 'jasonb@example.com', 30, '15235486', '123 Street, City');

-- --------------------------------------------------------

--
-- Table structure for table `tpo`
--

CREATE TABLE `tpo` (
  `t_id` int(11) NOT NULL,
  `t_name` varchar(128) NOT NULL,
  `t_email` varchar(128) NOT NULL,
  `t_age` int(11) NOT NULL,
  `t_mobile` int(11) NOT NULL,
  `t_address` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty_panel`
--
ALTER TABLE `faculty_panel`
  ADD PRIMARY KEY (`fac_id`),
  ADD UNIQUE KEY `fac_email` (`fac_email`),
  ADD KEY `fac_name` (`fac_name`),
  ADD KEY `fac_mobile` (`fac_mobile`);

--
-- Indexes for table `group_students`
--
ALTER TABLE `group_students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `internship_applications`
--
ALTER TABLE `internship_applications`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `internship_certificates`
--
ALTER TABLE `internship_certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `new_annoucement`
--
ALTER TABLE `new_annoucement`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`s_id`),
  ADD UNIQUE KEY `s_email` (`s_email`),
  ADD KEY `s_name` (`s_name`),
  ADD KEY `s_mobile` (`s_mobile`);

--
-- Indexes for table `student_profile`
--
ALTER TABLE `student_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tpo`
--
ALTER TABLE `tpo`
  ADD PRIMARY KEY (`t_id`),
  ADD UNIQUE KEY `t_email` (`t_email`),
  ADD KEY `t_name` (`t_name`),
  ADD KEY `t_mobile` (`t_mobile`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faculty_panel`
--
ALTER TABLE `faculty_panel`
  MODIFY `fac_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_students`
--
ALTER TABLE `group_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `internship_applications`
--
ALTER TABLE `internship_applications`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `internship_certificates`
--
ALTER TABLE `internship_certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `new_annoucement`
--
ALTER TABLE `new_annoucement`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_profile`
--
ALTER TABLE `student_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tpo`
--
ALTER TABLE `tpo`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
