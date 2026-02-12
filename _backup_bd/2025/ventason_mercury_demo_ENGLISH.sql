-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 16, 2025 at 10:20 AM
-- Server version: 10.11.10-MariaDB-cll-lve
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ventason_mercury_demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `administrator` (
  `id` tinyint(4) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(500) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `role` char(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO administrator (id, username, pass, ip, role) 
VALUES (1, 'admin', MD5('admin'), '127.0.0.1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE `newsletters` (
  `id` int(7) NOT NULL,
  `id_grapesjs` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `content_grapesjs` longtext DEFAULT NULL,
  `content_html` longtext DEFAULT NULL,
  `filter` varchar(100) DEFAULT NULL,
  `flag` varchar(10) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `createdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `test_emails`
--

CREATE TABLE `test_emails` (
  `id` tinyint(4) NOT NULL,
  `email` varchar(500) NOT NULL,
  `createdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `test_emails`
--

INSERT INTO `test_emails` (`id`, `email`, `createdate`) VALUES
(1, 'devmilitia@gmail.com', '2025-02-16 10:11:19'),
(2, 'graphixx.roninnovation@gmail.com', '2022-10-28 13:05:33'),
(3, 'd3vmafia@gmail.com', '2025-02-16 10:12:08'),
(4, 'devmilitiax@gmail.com', '2025-02-16 10:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `cloud`
--

CREATE TABLE `cloud` (
  `id` int(5) NOT NULL,
  `name` varchar(300) DEFAULT NULL,
  `url` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cloud`
--

INSERT INTO `cloud` (`id`, `name`, `url`) VALUES
(1, '11131488', 'https://i.imgur.com/zQsJ7cv.jpg'),
(2, 'programador', 'https://i.imgur.com/k7jctGr.jpg'),
(3, 'imagen', 'https://i.imgur.com/TyiC8gK.jpg'),
(4, 'imagen', 'https://i.imgur.com/LLovRwB.jpg'),
(5, 'contador', 'http://i.countdownmail.com/3wbzh.gif'),
(6, 'imagen', 'https://i.imgur.com/DLqdFfC.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `smtp`
--

CREATE TABLE `smtp` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `hostname` varchar(500) NOT NULL,
  `username` varchar(500) NOT NULL,
  `password` varchar(500) NOT NULL,
  `port` varchar(4) NOT NULL,
  `from_email` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` tinyint(4) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pass` varchar(500) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `role` char(1) NOT NULL,
  `createdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletters`
--
ALTER TABLE `newsletters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_emails`
--
ALTER TABLE `test_emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cloud`
--
ALTER TABLE `cloud`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smtp`
--
ALTER TABLE `smtp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrator`
--
ALTER TABLE `administrator`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newsletters`
--
ALTER TABLE `newsletters`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test_emails`
--
ALTER TABLE `test_emails`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cloud`
--
ALTER TABLE `cloud`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `smtp`
--
ALTER TABLE `smtp`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;
  
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
