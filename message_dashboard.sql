-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 01, 2024 at 09:33 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `message_dashboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `recipient_id`, `message`, `created`, `modified`, `deleted_at`) VALUES
(1, 1, 2, 'Something to somebody else', '2024-02-26 13:09:00', '2024-02-26 13:09:00', NULL),
(2, 1, 3, 'New Message to CakePHP', '2024-02-26 13:12:15', '2024-02-26 13:12:15', NULL),
(3, 3, 1, 'Testing recipient', '2024-02-26 13:19:14', '2024-02-26 13:19:14', NULL),
(4, 1, 3, 'Hey there!', '2024-02-27 08:00:00', '2024-02-27 08:00:00', NULL),
(5, 3, 1, 'Hello! How are you?', '2024-02-27 08:05:00', '2024-02-27 08:05:00', NULL),
(6, 1, 3, 'I\'m doing well, thanks!', '2024-02-27 08:10:00', '2024-02-27 08:10:00', NULL),
(7, 3, 1, 'Great to hear! What are you up to today?', '2024-02-27 08:15:00', '2024-02-27 08:15:00', NULL),
(8, 1, 3, 'Just working on some coding projects. How about you?', '2024-02-27 08:20:00', '2024-02-27 08:20:00', NULL),
(9, 3, 1, 'I\'m studying for exams. It\'s been pretty hectic.', '2024-02-27 08:25:00', '2024-02-27 08:25:00', NULL),
(10, 1, 3, 'I can imagine! Do you need any help with your studies?', '2024-02-27 08:30:00', '2024-02-27 08:30:00', NULL),
(11, 3, 1, 'That would be great, actually. I\'m having trouble with calculus.', '2024-02-27 08:35:00', '2024-02-27 08:35:00', NULL),
(12, 1, 3, 'Sure, I\'d be happy to help. We can go over some problems together.', '2024-02-27 08:40:00', '2024-02-27 08:40:00', NULL),
(13, 3, 1, 'Thanks, that would be really helpful. When are you available?', '2024-02-27 08:45:00', '2024-02-27 08:45:00', NULL),
(14, 1, 3, 'How about tomorrow afternoon? Around 2 PM?', '2024-02-27 08:50:00', '2024-02-27 08:50:00', NULL),
(15, 3, 1, 'That works for me. Let\'s meet at the library.', '2024-02-27 08:55:00', '2024-02-27 08:55:00', NULL),
(16, 1, 3, 'Sounds like a plan. See you then!', '2024-02-27 09:00:00', '2024-02-27 09:00:00', NULL),
(17, 3, 1, 'Looking forward to it. Have a good day!', '2024-02-27 09:05:00', '2024-02-27 09:05:00', NULL),
(18, 1, 3, 'You too!', '2024-02-27 09:10:00', '2024-02-27 09:10:00', NULL),
(19, 3, 1, 'Hey, I just finished studying. Do you still want to meet up?', '2024-02-28 08:00:00', '2024-02-28 08:00:00', NULL),
(20, 1, 3, 'Yes, I\'m already at the library. Come find me.', '2024-02-28 08:05:00', '2024-02-28 08:05:00', NULL),
(21, 3, 1, 'On my way.', '2024-02-28 08:10:00', '2024-02-28 08:10:00', NULL),
(22, 1, 3, 'See you soon.', '2024-02-28 08:15:00', '2024-02-28 08:15:00', NULL),
(23, 3, 1, 'I\'m here. Where are you?', '2024-02-28 08:20:00', '2024-02-28 08:20:00', NULL),
(24, 1, 3, 'I\'m sitting at the back corner.', '2024-02-28 08:25:00', '2024-02-28 08:25:00', NULL),
(25, 3, 1, 'Found you.', '2024-02-28 08:30:00', '2024-02-28 08:30:00', NULL),
(26, 1, 3, 'Let\'s get started on those calculus problems.', '2024-02-28 08:35:00', '2024-02-28 08:35:00', NULL),
(27, 3, 1, 'Sounds good. I have a few questions on the last chapter.', '2024-02-28 08:40:00', '2024-02-28 08:40:00', NULL),
(28, 1, 3, 'Let\'s go over them together.', '2024-02-28 08:45:00', '2024-02-28 08:45:00', NULL),
(29, 3, 1, 'Thanks for the help. I feel more confident now.', '2024-02-28 08:50:00', '2024-02-28 08:50:00', NULL),
(30, 1, 3, 'You\'re welcome. Anytime you need help, just ask.', '2024-02-28 08:55:00', '2024-02-28 08:55:00', NULL),
(31, 3, 1, 'I will. Have a good day!', '2024-02-28 09:00:00', '2024-02-28 09:00:00', NULL),
(32, 1, 3, 'You too!', '2024-02-28 09:05:00', '2024-02-28 09:05:00', NULL),
(33, 1, 3, 'You too Madafaka!', '2024-02-28 09:08:00', '2024-02-28 09:08:00', NULL),
(34, 1, 3, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Fames ac turpis egestas integer eget aliquet nibh praesent tristique. Placerat vestibulum lectus mauris ultrices eros in cursus turpis. Lacus sed viverra tellus in hac habitasse platea dictumst vestibulum. Nec dui nunc mattis enim ut tellus elementum sagittis. Non blandit massa enim nec dui nunc mattis enim. Etiam erat velit scelerisque in dictum non consectetur. Tincidunt vitae semper quis lectus nulla at volutpat diam. Lectus mauris ultrices eros in cursus. Cursus risus at ultrices mi. Gravida neque convallis a cras semper auctor neque vitae tempus. Mi eget mauris pharetra et ultrices neque. Velit laoreet id donec ultrices. Dui sapien eget mi proin. Enim ut sem viverra aliquet eget. Elementum eu facilisis sed odio morbi quis commodo odio aenean. Lacus vel facilisis volutpat est velit. Libero enim sed faucibus turpis.', '2024-02-28 17:19:15', '2024-03-01 16:29:46', '2024-03-01 16:29:46'),
(35, 1, 3, 'Imo mama batig nawng', '2024-02-28 17:21:45', '2024-03-01 16:28:34', '2024-03-01 16:28:34'),
(36, 1, 3, 'Mo reply ka or dili ?', '2024-02-29 09:53:40', '2024-03-01 16:28:32', '2024-03-01 16:28:32'),
(37, 2, 1, 'Waddup maniga', '2024-02-29 10:48:19', '2024-02-29 10:48:19', NULL),
(38, 1, 2, 'Elfin Lied', '2024-02-29 10:52:00', '2024-02-29 10:52:00', NULL),
(39, 2, 1, 'No you lief', '2024-02-29 10:52:15', '2024-02-29 10:52:15', NULL),
(40, 3, 1, 'Imo mama cake', '2024-02-29 11:25:13', '2024-02-29 11:25:13', NULL),
(41, 3, 1, 'imo mama bubu', '2024-02-29 11:45:25', '2024-02-29 11:45:25', NULL),
(42, 3, 1, 'abno', '2024-02-29 16:38:34', '2024-02-29 16:38:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `hobby` longtext DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modified` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `gender`, `birthdate`, `hobby`, `picture`, `last_login`, `created`, `modified`) VALUES
(1, 'Brisbane Bacalla', 'brisbane1234@yahoo.com', '$2y$10$RY4XMiHyOk4rBXh8azFJZuMLyiVV5dCa..JAD9YAHeL50goH0OFmK', 'male', '1997-03-31', 'I wanna be the very best\r\nLike no one ever was\r\nTo catch them is my real test\r\nTo train them is my cause\r\nI will travel across the land\r\nSearching far and wide\r\nTeach Pokémon to understand\r\nThe power that\'s inside\r\n\r\n(Pokémon\r\nGotta catch \'em all) It\'s you and me\r\nI know it\'s my destiny (Pokémon)\r\nOh, you\'re my best friend\r\nIn a world we must defend (Pokémon\r\nGotta catch \'em all) A heart so true\r\nOur courage will pull us through\r\nYou teach me and I\'ll teach you (Ooh, ooh)\r\nPokémon! (Gotta catch \'em all)\r\nGotta catch \'em all\r\nYeah', '1-profilePicture.jpg', '2024-03-01 16:03:03', '2024-02-23 11:10:20', '2024-03-01 16:03:03'),
(2, 'Azura', 'jmicarus12@gmail.com', '$2y$10$13zRwOa2g5Qr5vTm0bMeueficaUMW5Q8l5q9t5CwdB.w37lSFQdSq', NULL, NULL, NULL, NULL, '2024-02-29 10:47:43', '2024-02-23 11:11:36', '2024-02-29 10:47:43'),
(3, 'CakePHP', 'Cake@php.com', '$2y$10$OueJ5B3AP9FlkCt9LEWMCOuLzYTzVi.FWCGECD8.htOjNi9AE.dkG', NULL, NULL, NULL, NULL, '2024-02-29 16:38:28', '2024-02-23 11:17:38', '2024-02-29 16:38:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sender_id` (`sender_id`),
  ADD KEY `idx_recipient_id` (`recipient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
