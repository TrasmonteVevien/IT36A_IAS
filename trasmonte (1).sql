-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 06:25 PM
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
-- Database: `trasmonte`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'zeke', '$2y$10$hMmYNWzo3PRbcQiFaZoOqO7/f3jtM8SSLWazpuo3teOQBIoPXZm7S'),
(2, 'vevien', '$2y$10$JrQPY1vsgp8cWT8K7ZwYpeC1abPUcTtrnCLVxyPqHkN8y8CUndBa.');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `available`) VALUES
(1, 'Pride and Prejudice', 'Jane Austen', 1),
(2, 'Moby-Dick', 'Herman Melville', 1),
(3, 'War and Peace', 'Leo Tolstoy', 1),
(4, 'The Catcher in the Rye', 'J.D. Salinger', 1),
(5, 'The Hobbit', 'J.R.R. Tolkien', 1),
(6, 'Brave New World', 'Aldous Huxley', 1),
(7, 'The Odyssey', 'Homer', 1),
(8, 'Crime and Punishment', 'Fyodor Dostoevsky', 1),
(9, 'The Divine Comedy', 'Dante Alighieri', 1),
(10, 'Frankenstein', 'Mary Shelley', 1),
(11, 'Jane Eyre', 'Charlotte Bront?', 1),
(12, 'The Lord of the Rings', 'J.R.R. Tolkien', 1),
(13, 'The Iliad', 'Homer', 1),
(14, 'Catch-22', 'Joseph Heller', 1),
(15, 'Animal Farm', 'George Orwell', 1),
(16, 'The Brothers Karamazov', 'Fyodor Dostoevsky', 1),
(17, 'The Adventures of Huckleberry Finn', 'Mark Twain', 1),
(18, 'The Great Expectations', 'Charles Dickens', 1),
(19, 'Les Mis?rables', 'Victor Hugo', 1),
(20, 'Dracula', 'Bram Stoker', 1);

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`id`, `user_id`, `book_id`, `borrow_date`) VALUES
(1, 9, 2, '2024-11-03'),
(2, 9, 1, '2024-11-03'),
(3, 9, 3, '2024-11-03'),
(5, 13, 12, '2024-11-04'),
(6, 11, 6, '2024-11-04'),
(7, 16, 4, '2024-11-08'),
(9, 18, 9, '2024-11-11'),
(10, 18, 5, '2024-11-11'),
(11, 18, 7, '2024-11-11');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempt_time` datetime DEFAULT current_timestamp(),
  `status` enum('failed','granted','removed') DEFAULT 'failed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `username`, `ip_address`, `attempt_time`, `status`) VALUES
(1, 'kiddy', '::1', '2025-02-27 06:49:58', 'removed'),
(2, 'sisa', '::1', '2025-02-27 08:26:26', 'failed'),
(3, 'sisa', '::1', '2025-02-27 08:26:50', 'failed'),
(4, 'xoxo', '::1', '2025-04-08 21:36:36', 'failed'),
(5, 'xoxo', '::1', '2025-04-08 21:36:43', 'failed'),
(6, 'xoxo', '::1', '2025-04-08 23:24:35', 'failed'),
(7, 'xoxo', '::1', '2025-04-08 23:24:41', 'failed'),
(8, 'opop', '::1', '2025-04-08 23:25:47', 'failed'),
(9, 'opop', '::1', '2025-04-08 23:25:51', 'failed'),
(10, 'xoxo', '::1', '2025-04-09 00:04:15', 'failed'),
(11, 'xoxo', '::1', '2025-04-09 00:04:18', 'failed');

-- --------------------------------------------------------

--
-- Table structure for table `login_verification_requests`
--

CREATE TABLE `login_verification_requests` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `request_time` datetime DEFAULT current_timestamp(),
  `status` enum('pending','granted','denied') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `phone`) VALUES
(1, 'loray', '$2y$10$W0dV0TbeOo7gXed3dlPr5OBRO6xTFl1DGvJKFgtMHixQdSYajJv5e', ''),
(8, 'mariel', '$2y$10$aQU8SFuzi6Fv4CEpYGqnceGS03tOHB6s0fIP2PgMBsi/ScnClEpKi', ''),
(9, 'saraum', '$2y$10$sxFckfh9NCIlJM/mOqyrc.34TDCPzXvobU6LOkjO56inwn17ctjtC', ''),
(10, 'alabs', '$2y$10$xeeODsQCdAabIiZ.yzxfLOAu7zLAoa1TMkzObwluU7kpJHngr7alu', ''),
(11, 'aldrian', '$2y$10$uRbSHOroJyR9RxsmBRIpVurZxy43oawd75pJpwdWTGjMp0VYOLkY.', ''),
(12, 'boggy', '$2y$10$9C18TI9GSaYivkr6A1NTfeGX8f5/bkmlHPq4WiL5o3NA349aP9TI.', ''),
(13, 'vevien', '$2y$10$CvwSuaF71owPN53uQXqpsee3HuBGMymVcUeqrcs57TOe04mq/xDmC', ''),
(14, 'jaging', '$2y$10$H59YLKLJDlwATV6VmRlS1uYm.uBGL.BTGCz1XzkkrgRcsStF4WLaO', ''),
(15, 'banuging', '$2y$10$pRRmRJxExppZuIRrPQeXyepDaSMEFwGmMhuwpdEJOQSXJJYOemqLy', ''),
(16, 'lor', '$2y$10$tY4Kh0AR7MxE4MIxc3Kkju.4iVgAahrxJ0Agxc/W1170Adz0ol0WW', ''),
(17, 'ld', '$2y$10$5nYfSAJDe37IfpPMrCVRDuQYelBDqv2Ce49m2OWm6g/jpYIFmltHy', ''),
(18, 'kaka', '$2y$10$RmCgjwb6VTyuEiNxQDX3tOu5VNApKWpWwSRY0xQQhOQoevGQPikYm', ''),
(19, '20221185', '$2y$10$JtXk7Y1h5nPHkqIxIAQyIOdOLXDgbsYQfZWJaLAJHhVCwoV3lHg.G', ''),
(20, 'degol', '$2y$10$BRcmgnwzVzU03gU./60u..iAkZd2/oHkYRKcEsAybzIUoC0dpWAsC', ''),
(21, '20224567', '$2y$10$Jx0mVT5ZA3HDB9BhnfWSC.joBYQV9gTlo9zCdkuSF5pAcaiFJDX82', ''),
(22, '20211185', '$2y$10$86IvdS2ZwUtR9Ls22znNduy4yn.4a0ik6pfUnL/ThR/eew1C7cdRq', ''),
(23, '64363453', '$2y$10$Oyq2fFiHm.BAkvuJ3EupROrmYQjL7NMs1dijLiWoAOPYcbnwq4URu', ''),
(24, '55553453', '$2y$10$/uZiy1O8NFFU90Qg/IqOX.Op5.LIDa5pq4CX8P3RcDtAPuJ.Go9rW', ''),
(25, 'althia', '$2y$10$S/BJtuNa.qjU77dAiCrvjueMqalRrGDla2EDGFH64ahIxrH0zWx3y', ''),
(26, 'roren', '$2y$10$Ne9nMr6H.qtQl/7E9NappeTQmbsTHGmpZYY274DxJTPf1Icpk17IW', ''),
(27, 'rory', '$2y$10$8NG.tZWGJPktq3D967yQ6OOXw6oJcihqTbm3Ch0o9dJxKp1kKCRLG', ''),
(28, 'kiddy', '$2y$10$nCqBLbWN4RWtZDNZhD6IwO5oIdYYveJofqMrKt83x6R0zPr1icynS', ''),
(29, 'maricel', '$2y$10$v8uH6W5IylcMKt9U/lZ0Eu3kfD49MlRSExP6vvVi6xkgXgwqz3g9e', ''),
(30, 'wenj', '$2y$10$ZFrkWmfI5DsJC1Ss/F5qKerXG.qqSOmu19vmACCRzJbsSKOeCJAAW', ''),
(31, 'dacol', '$2y$10$2AABzOcu5.qggrboBE.Zo.Ryndg7CFLjaLgK3zVtJJNcYkm4dFfqS', ''),
(32, 'tine', '$2y$10$EpFa1I3zp82mBibftd2mZ.9ZtFmK026UYGQksdEHVMznHkYIjGHJK', ''),
(33, 'roll', '$2y$10$Q/ObqKHavuubY.OHerLeUeQqpaMVxwcJfByRcQK/6ByzwN5LY/w6i', ''),
(34, 'sisa', '$2y$10$c6POxp18kpt9GqkojbQgKu5XSD4w/0JNoAqfxr.3z2cW8lDW7xB4O', ''),
(35, 'xoxo', '$2y$10$wE.aKtk1odpFzQf0OtHIHewb3gUIbjZgu5hkwsUMuCEjUDoXVKmhS', '09511959950'),
(36, 'opop', '$2y$10$ry5JeQvujzbi3MA2yI.i4eKvN1SBW386GiRx3U8C89b4XUcOXJi.W', '09511959950');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_verification_requests`
--
ALTER TABLE `login_verification_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `login_verification_requests`
--
ALTER TABLE `login_verification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `borrowed_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
