-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2024 at 06:01 PM
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
-- Database: `ugms`
--

-- --------------------------------------------------------

--
-- Table structure for table `game_registration`
--

CREATE TABLE `game_registration` (
  `id` int(11) NOT NULL,
  `student_id` varchar(15) NOT NULL,
  `game_name` varchar(15) NOT NULL,
  `board` varchar(15) NOT NULL,
  `players_number` varchar(3) NOT NULL,
  `slot` time NOT NULL,
  `apdate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_registration`
--

INSERT INTO `game_registration` (`id`, `student_id`, `game_name`, `board`, `players_number`, `slot`, `apdate`) VALUES
(58, '2021000010007', 'Chess', '1', '2', '09:00:00', '2024-02-05'),
(59, '2021000010007', 'Chess', '2', '2', '09:00:00', '2024-02-05');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `department` varchar(5) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `student_id`, `email`, `department`, `password`) VALUES
(14, 'Akash Kumar Saha', '2021000010007', 'mr.akashsaha@gmail.com', 'CSE', 'pC9Qgg9QNtD2RgS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `game_registration`
--
ALTER TABLE `game_registration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `game_registration`
--
ALTER TABLE `game_registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
