-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2025 at 05:18 AM
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
-- Database: `golden_rules_calendar`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `recording_consent` varchar(255) NOT NULL,
  `injury_loss_risk_consent` varchar(255) NOT NULL,
  `signature_date` varchar(255) NOT NULL,
  `emergency_contact_1_name` varchar(255) DEFAULT NULL,
  `emergency_contact_1_phone` varchar(15) DEFAULT NULL,
  `emergency_contact_1_relationship` varchar(255) DEFAULT NULL,
  `emergency_contact_2_name` varchar(255) DEFAULT NULL,
  `emergency_contact_2_phone` varchar(15) DEFAULT NULL,
  `emergency_contact_2_relationship` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `edited_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `password_hash`, `reset_token`, `name`, `phone_number`, `email`, `address`, `password`, `image`, `recording_consent`, `injury_loss_risk_consent`, `signature_date`, `emergency_contact_1_name`, `emergency_contact_1_phone`, `emergency_contact_1_relationship`, `emergency_contact_2_name`, `emergency_contact_2_phone`, `emergency_contact_2_relationship`, `reset_token_expiry`, `edited_by`) VALUES
(1, '', '773e563a62b8fcb75049bac17de7417bc2bea889b071473fb05896e985d976a4', 'Admin', '2045556644', 'r@gmail.com', '', '$2y$10$mBhVWATYhem/umVFzbsuR.hGmqmHa7Ghdpsew6VWrjwrBULmE9906', NULL, '0', '0', '2024-12-11', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-12 21:56:34', NULL),
(18, '', 'fe2bb93d8652f3c7d16252083bcb888622041fc1d5919a08809ccc806c68ea33', 'Busra', '2048074140', 'busragiran@gmail.com', '', '$2y$10$UVYXqxTRcA2odYN00WFGvuKs2b6KtTyX4sS.KkJq2hedIhCbFhg12', '', '0', '0', '', '', '', '', '', '', '', '2025-02-12 21:57:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Game Club'),
(2, 'Pickleball'),
(3, 'Functional Fitness'),
(4, 'Conversation cafe'),
(5, 'Paint buddies'),
(6, 'Floor Curling'),
(7, 'Carpet Bowling & Shuffle Board'),
(8, 'Qigong'),
(9, 'Presentation'),
(10, 'Book Club'),
(11, 'Tech Talk'),
(12, 'Bingo'),
(13, 'Bingo');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `edit_date` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `short_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `start_time`, `end_time`, `created_by`, `edited_by`, `edit_date`, `image`, `created_at`, `short_name`) VALUES
(10, '🤝🤝Board Meeting', 'dnklnds', '2024-12-12 12:00:00', '2024-12-12 13:00:00', 1, 1, '2025-02-18 15:16:30', NULL, '2024-12-10 13:24:34', 'Sample'),
(11, 'cnlkdn', 'cndkcndlk', '2024-12-13 12:00:00', '2024-12-13 13:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2024-12-10 13:26:27', 'Sample'),
(13, 'Test', 'test', '2024-12-12 12:00:00', '2024-12-12 13:01:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2024-12-11 22:27:47', 'Sample'),
(15, 'cdcdscsddscsdc', 'new', '2025-02-18 12:00:00', '2025-02-18 13:00:00', 1, 1, '2025-02-18 15:16:30', NULL, '2024-12-11 22:38:13', 'Sample'),
(19, 'Information Session', 'ss', '2025-01-01 11:11:00', '2025-01-01 11:11:00', 1, 1, '2025-02-18 15:16:30', NULL, '2024-12-22 14:49:40', 'Sample'),
(20, 'Presentation: \"Emergency Preparedness\"', 'Follow the updates', '2025-01-08 12:12:00', '2025-01-08 13:13:00', 1, 1, '2025-02-18 15:16:30', NULL, '2024-12-22 15:30:39', 'Sample'),
(21, 'xs', 'dccd', '2024-12-23 11:11:00', '2024-12-23 11:11:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2024-12-22 19:46:40', 'Sample'),
(23, 'Paint Buddies', 'Edited by Admin', '2025-01-14 10:00:00', '2025-01-14 12:15:00', 18, 1, '2025-02-18 15:16:30', NULL, '2025-01-02 13:41:07', 'Sample'),
(25, 'Test Edit Time ', 'Time is 14:13', '2025-01-16 12:00:00', '2025-01-16 13:00:00', 1, 1, '2025-02-18 15:16:30', NULL, '2025-01-02 14:02:08', 'Sample'),
(26, 'Pickleball (lesson)', 'Test with Rakshita', '2025-01-09 13:00:00', '2025-01-09 14:00:00', 1, 1, '2025-02-18 15:16:30', NULL, '2025-01-08 10:54:27', 'Sample'),
(27, 'Games Club', 'Games', '2025-01-02 13:00:00', '2025-01-02 14:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 13:35:03', 'Sample'),
(28, 'Pickleball(free play)', 'PicklePlay', '2025-01-02 13:00:00', '2025-01-02 14:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 13:35:55', 'Sample'),
(29, 'Steppin\' Up ', 'Fitness', '2025-01-03 10:00:00', '2025-01-03 11:00:00', 1, 1, '2025-02-18 15:16:30', NULL, '2025-01-23 13:37:58', 'Sample'),
(30, 'Conversation Cafe', 'Conversation', '2025-01-03 13:00:00', '2025-01-03 20:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 13:52:36', 'Sample'),
(31, 'Steppin\' Up', 'Fitness', '2025-01-06 22:00:00', '2025-01-06 22:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 13:54:13', 'Sample'),
(32, 'Paint Buddies', 'Paint ', '2025-01-07 22:00:00', '2025-01-07 23:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 13:55:50', 'Sample'),
(33, 'Floor Curling', 'Curling ', '2025-01-07 12:30:00', '2025-01-07 14:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 13:57:13', 'Sample'),
(34, 'Carpet Bowling&Shuffle Board', 'Bowling', '2025-01-07 14:00:00', '2025-01-07 15:30:00', 1, 1, '2025-02-18 15:16:30', NULL, '2025-01-23 13:58:12', 'Sample'),
(35, 'Qiong (Body-Mind Exercise)', 'Exercise', '2025-01-08 10:00:00', '2025-01-08 11:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 14:54:14', 'Sample'),
(36, 'Games Club (Cribbage)', 'Games', '2025-01-09 13:00:00', '2025-01-09 14:00:00', 1, 1, '2025-02-18 15:16:30', NULL, '2025-01-23 15:04:46', 'Sample'),
(37, 'Steppin\' Up with Confidence Functional Fitness', 'Fitness', '2025-01-13 10:00:00', '2025-01-13 11:00:00', 1, 1, '2025-02-18 15:16:30', NULL, '2025-01-23 15:16:30', 'Sample'),
(38, 'Tech Talk', 'Tesch', '2025-01-13 13:00:00', '2025-01-13 14:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 15:17:04', 'Sample'),
(39, 'Floor Curling', 'Curling', '2025-01-14 12:30:00', '2025-01-14 13:30:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 15:20:33', 'Sample'),
(40, 'Carpet Bowling&Shuffle Board', 'Bowling', '2025-01-14 14:00:00', '2025-01-14 15:30:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 15:21:23', 'Sample'),
(41, 'Qiong (Body-Mind Exercise)', 'Exercise', '2025-01-15 10:00:00', '2025-01-15 11:15:00', 1, 1, '2025-02-18 15:16:30', NULL, '2025-01-23 15:22:29', 'Sample'),
(42, 'Lungtivity', 'Seminar', '2025-01-15 11:15:00', '2025-01-15 12:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 15:23:04', 'Sample'),
(43, 'Games Club', 'Games', '2025-01-16 13:00:00', '2025-01-16 14:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 15:23:33', 'Sample'),
(44, 'Pickleball (free play)', 'Pickleball', '2025-01-16 13:00:00', '2025-01-16 14:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 15:24:27', 'Sample'),
(45, 'Instructional Painting Class with Karen Wokes', 'Painting', '2025-01-17 13:00:00', '2025-01-17 13:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 15:25:28', 'Sample'),
(46, 'Steppin\' Up with Confidence Functional Fitness', 'Fitness', '2025-01-20 10:00:00', '2025-01-20 11:00:00', 1, 1, '2025-02-18 15:16:30', NULL, '2025-01-23 15:30:27', 'Sample'),
(47, 'Qiong (Body-Mind Exercise)', 'Exercise', '2025-01-22 10:00:00', '2025-01-22 11:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 15:35:02', 'Sample'),
(48, 'Lungtivity', 'Lungtivity', '2025-01-22 11:15:00', '2025-01-22 12:15:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 15:35:25', 'Sample'),
(49, 'Presentation:\"Falls Prevention\"', 'Presentation', '2025-01-22 13:00:00', '2025-01-22 14:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-01-23 15:36:24', 'Sample'),
(50, 'cfcjfkjc', 'xd', '2025-02-13 12:00:00', '2025-02-13 13:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-02-12 14:32:02', 'Sample'),
(52, 'Paint', 'z', '2025-01-22 23:11:00', '2025-01-22 12:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-02-12 18:11:11', 'Sample'),
(53, 'Book Club', 'Book ', '2025-02-14 13:00:00', '2025-02-14 14:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-02-14 13:28:27', 'Sample'),
(59, 'Test Event', 'This is a test event.', '2024-02-18 10:00:00', '2024-02-18 12:00:00', 1, NULL, '2025-02-18 15:16:30', NULL, '2025-02-18 14:34:01', 'Sample'),
(61, 'x ', ' zx', '2025-02-18 12:22:00', '2025-02-18 12:54:00', 1, NULL, '2025-02-18 14:43:03', NULL, '2025-02-18 14:43:03', 'dcsd'),
(62, 'New', 'New', '2025-02-15 00:00:00', '2025-02-15 13:00:00', 1, NULL, '2025-02-18 14:43:39', NULL, '2025-02-18 14:43:39', 'word'),
(63, 'Test Short Name', 'dcks', '2025-02-19 12:00:00', '2025-02-19 13:00:00', 1, NULL, '2025-02-18 16:12:11', NULL, '2025-02-18 16:12:11', 'TestingPurpose'),
(64, 'Basketboll For Mahfuj and Friends', 'Basketboll For Mahfuj and Friends', '2025-02-20 12:00:00', '2025-02-20 13:00:00', 1, NULL, '2025-02-18 18:25:09', NULL, '2025-02-18 18:25:09', 'Mahfuj'),
(65, 'kdsmckmdcs', 'csd', '2025-02-21 12:00:00', '2025-02-20 13:00:00', 1, NULL, '2025-02-18 18:26:51', NULL, '2025-02-18 18:26:51', 'Basketball');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `upload_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `title`, `description`, `image_path`, `upload_time`, `category`) VALUES
(1, 'Painting', 'PAinting is for the relief of mind and soul', 'uploads/priy.jpg', '2024-12-16 04:36:44', NULL),
(3, 'christmas', 'christas party', 'uploads/Front page ss.png', '2024-12-17 06:00:00', NULL),
(4, 'book keeping', 'Indeed books are great freinds but they also help us to find us the like minded people', 'uploads/Book Club_1.png', '2024-12-18 06:00:00', NULL),
(5, 'book keeping party', 'Books teach us alot of things one of which is relax mind too along with work', 'uploads/Book Club_3.png', '2024-12-18 06:00:00', NULL),
(9, 'Carpet bowling', ' 	This game involves a ball and stick. With the help of stick players through ball in the designated area', 'uploads/Carpet Bowling_2.png', '2024-12-05 06:00:00', NULL),
(11, 'hey', 'jhsri', 'uploads/Carpet Bowling_1.png', '2024-12-02 06:00:00', NULL),
(12, 'hey', 'jhsri', 'uploads/Carpet Bowling_1.png', '2024-12-02 06:00:00', 'Paint buddies');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `registered_at` datetime DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `event_id`, `user_id`, `registered_at`, `notes`, `name`, `phone_number`) VALUES
(40, 20, NULL, '2025-01-08 10:40:12', '', 'Test', '2045525620'),
(41, 19, NULL, '2025-01-08 10:40:53', '', 'Test', '2048074140'),
(42, 25, NULL, '2025-01-08 10:42:04', '', 'Rakshita', '2045525620'),
(43, 33, NULL, '2025-02-04 09:56:15', '', 'Busra', '151515151'),
(44, 27, NULL, '2025-02-12 14:29:36', '', 'Busra', '1235');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `phone_number` varchar(15) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `emergency_contact_1_name` varchar(255) DEFAULT NULL,
  `emergency_contact_1_phone` varchar(15) DEFAULT NULL,
  `emergency_contact_1_relationship` varchar(255) DEFAULT NULL,
  `emergency_contact_2_name` varchar(255) DEFAULT NULL,
  `emergency_contact_2_phone` varchar(15) DEFAULT NULL,
  `emergency_contact_2_relationship` varchar(255) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `recording_consent` tinyint(1) DEFAULT 0,
  `injury_loss_risk_consent` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `signature_date` date DEFAULT NULL,
  `edited_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `address`, `phone_number`, `email`, `emergency_contact_1_name`, `emergency_contact_1_phone`, `emergency_contact_1_relationship`, `emergency_contact_2_name`, `emergency_contact_2_phone`, `emergency_contact_2_relationship`, `image`, `recording_consent`, `injury_loss_risk_consent`, `created_at`, `signature_date`, `edited_by`) VALUES
(1, 'Admin User', '', '2245565656', 'admin@example.com', '', '', '', '', '', '', '', 0, 0, '2024-12-10 12:12:06', '0000-00-00', NULL),
(43, 'Seminar', '', '2045556644', '', '', '', '', '', '', '', '', 0, 0, '2024-12-22 19:49:01', '0000-00-00', NULL),
(44, 'Rakshita', '', '', '', '', '', '', '', '', '', '', 0, 0, '2024-12-22 19:49:13', '0000-00-00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
