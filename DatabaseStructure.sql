-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Jan 13, 2023 at 11:42 AM
-- Server version: 10.10.2-MariaDB-1:10.10.2+maria~ubu2204
-- PHP Version: 8.0.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gemorskos`
--

-- --------------------------------------------------------

--
-- Table structure for table `Events`
--

CREATE TABLE `Events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(70) NOT NULL,
  `event_description` text NOT NULL,
  `location_street` varchar(50) DEFAULT NULL,
  `location_postal_code` varchar(6) DEFAULT NULL,
  `location_city` varchar(30) DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_max_participant` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Events`
--

INSERT INTO `Events` (`event_id`, `event_name`, `event_description`, `location_street`, `location_postal_code`, `location_city`, `event_time`, `event_date`, `event_max_participant`, `active`) VALUES
(1, 'job1', 'afavarfac', 'aerwfawc', 'arac', 'awefawc', '25:45:33', '2023-01-13', 2, 2),
(2, 'jimj', 'vwcs', 'ewcaecax', 'weca', 'evca', '33:53:37', '2023-01-23', 7, 2),
(3, 'hi', 'wrgwsv', 'eqfvac', 'evfac', 'efva', '33:23:14', '2023-01-14', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Event_Details`
--

CREATE TABLE `Event_Details` (
  `event_details_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `checkin_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Event_Details`
--

INSERT INTO `Event_Details` (`event_details_id`, `event_id`, `user_id`, `checkin_date`) VALUES
(1, 1, 1, '2023-01-12 20:59:55'),
(2, 2, 1, '2023-01-12 21:23:04'),
(3, 3, 1, '2023-01-12 21:23:55');

-- --------------------------------------------------------

--
-- Table structure for table `Roles`
--

CREATE TABLE `Roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(25) NOT NULL,
  `role_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Roles`
--

INSERT INTO `Roles` (`role_id`, `role_name`, `role_description`) VALUES
(1, '1', 'fvd cxdfbvsva');

-- --------------------------------------------------------

--
-- Table structure for table `TypesOfStaff`
--

CREATE TABLE `TypesOfStaff` (
  `type_of_staff_id` int(11) NOT NULL,
  `type_of_staff_description` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `TypesOfStaff`
--

INSERT INTO `TypesOfStaff` (`type_of_staff_id`, `type_of_staff_description`) VALUES
(1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `user_password` varchar(60) NOT NULL,
  `password_change_date` timestamp NULL DEFAULT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `email_address` varchar(50) NOT NULL,
  `type_of_staff` int(11) NOT NULL,
  `user_role` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `photo` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `user_password`, `password_change_date`, `first_name`, `last_name`, `email_address`, `type_of_staff`, `user_role`, `active`, `photo`) VALUES
(1, '123456789', '2023-01-13 13:51:22', 'Stefan', 'Sadeghi', 'stefan@gmail.com', 1, 1, 1, '319553651_968469570803749_6748591277500092898_n.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Events`
--
ALTER TABLE `Events`
  ADD PRIMARY KEY (`event_id`),
  ADD UNIQUE KEY `event_name` (`event_name`);

--
-- Indexes for table `Event_Details`
--
ALTER TABLE `Event_Details`
  ADD PRIMARY KEY (`event_details_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `TypesOfStaff`
--
ALTER TABLE `TypesOfStaff`
  ADD PRIMARY KEY (`type_of_staff_id`),
  ADD UNIQUE KEY `type_of_staff_description` (`type_of_staff_description`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_address` (`email_address`),
  ADD KEY `type_of_staff` (`type_of_staff`),
  ADD KEY `user_role` (`user_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Events`
--
ALTER TABLE `Events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Event_Details`
--
ALTER TABLE `Event_Details`
  MODIFY `event_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Roles`
--
ALTER TABLE `Roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `TypesOfStaff`
--
ALTER TABLE `TypesOfStaff`
  MODIFY `type_of_staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Event_Details`
--
ALTER TABLE `Event_Details`
  ADD CONSTRAINT `Event_Details_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `Events` (`event_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `Event_Details_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `Users_ibfk_1` FOREIGN KEY (`type_of_staff`) REFERENCES `TypesOfStaff` (`type_of_staff_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `Users_ibfk_2` FOREIGN KEY (`user_role`) REFERENCES `Roles` (`role_id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
