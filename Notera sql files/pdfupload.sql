-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2024 at 05:38 PM
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
-- Database: `pdfupload`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cat_id`, `cat_name`) VALUES
(1, 'Sem1'),
(2, 'Sem2'),
(3, 'Sem3'),
(4, 'Sem4'),
(5, 'Sem5'),
(6, 'Sem6'),
(10, 'Sem7'),
(11, 'Sem 8');

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `download_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `download_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `downloads`
--

INSERT INTO `downloads` (`download_id`, `user_id`, `book_id`, `download_date`) VALUES
(6, 17, 8, '2023-10-01 07:32:17'),
(8, 17, 9, '2023-10-01 08:05:04'),
(9, 17, 3, '2023-10-01 08:05:23'),
(10, 29, 8, '2023-10-01 14:07:06'),
(11, 29, 4, '2023-10-01 14:07:25'),
(12, 29, 9, '2023-10-02 11:58:25'),
(13, 29, 9, '2023-10-02 11:59:08'),
(14, 29, 9, '2023-10-02 12:45:54'),
(15, 29, 9, '2024-04-20 13:49:10');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `pdf` varchar(255) NOT NULL,
  `book_cover` varchar(255) NOT NULL,
  `book_name` varchar(255) NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `published_date` date NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `cat_id` int(11) DEFAULT NULL,
  `subcat_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `pdf`, `book_cover`, `book_name`, `author_name`, `published_date`, `date_added`, `cat_id`, `subcat_id`) VALUES
(3, 'The Alchemist.pdf', 'alc.jpg', 'The  Alchemist', 'Paulo Coelho', '2005-10-05', '2023-09-25 13:32:51', 5, NULL),
(4, 'Frankenstein.pdf', 'frank.jpeg', 'Frankenstein', 'Mary Shelley', '1818-01-01', '2023-09-25 13:32:51', 3, NULL),
(5, 'DrJekyllAndMrHyde.pdf', 'dr.jpg', 'DrJekyllAndMrHyde', 'Robert Louis Stevenson', '1886-01-05', '2023-09-25 13:32:51', 3, NULL),
(6, 'Robinson Crusoe.pdf', 'robin.jpg', 'Robinson Crusoe', 'Daniel Defoe', '1719-04-25', '2023-09-25 13:32:51', 2, NULL),
(7, 'Pragmatic Programmer.pdf', 'pragmatic.jpg', 'The Pragmatic Programmer', 'Andy Hunt, Dave Thomas', '1999-10-01', '2023-09-25 13:32:51', 4, NULL),
(8, 'Rich Dad Poor Dad What the Rich Teach Their Kids About Money That the Poor and Middle Class Do Not by Robert T. Kiyosaki (z-lib.org).epub.pdf', 'richdad.jpg', 'Rich Dad Poor Dad ', 'Robert T. Kiyosaki', '1997-05-06', '2023-09-25 16:52:40', 6, NULL),
(9, 'The-Island-of-Fantasy.pdf', 'islandfantasy.jpg', 'The Island of Fantasy', 'Fergus Hume', '1892-02-19', '2023-09-26 07:09:25', 1, NULL),
(10, 'Network_Theory_sem3.pdf', '328841ad-afda-4502-9067-8cb3ea307344.jpeg', 'Network Theory', 'NT', '2024-04-20', '2024-04-20 14:17:55', 2, 3),
(11, 'OS_sem_5 (1).pdf', '20230627_145323.jpg', 'OS', 'os', '2024-04-26', '2024-04-20 14:57:49', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pending_books`
--

CREATE TABLE `pending_books` (
  `id` int(11) NOT NULL,
  `book_name` varchar(255) NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `published_date` date NOT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pdf` varchar(255) NOT NULL,
  `book_cover` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `subcat_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `subcat_id` int(11) NOT NULL,
  `subcat_name` varchar(255) NOT NULL,
  `cat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategory`
--

INSERT INTO `subcategory` (`subcat_id`, `subcat_name`, `cat_id`) VALUES
(1, 'Physics', 1),
(2, 'Chemistry', 1),
(3, 'Maths', 2),
(4, 'Applied Mechanics', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`download_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `downloads_ibfk_2` (`book_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `images_ibfk_1` (`cat_id`),
  ADD KEY `fk_images_subcategory` (`subcat_id`);

--
-- Indexes for table `pending_books`
--
ALTER TABLE `pending_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_cat_id` (`cat_id`),
  ADD KEY `fk_subcat_id` (`subcat_id`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`subcat_id`),
  ADD KEY `cat_id` (`cat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `download_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pending_books`
--
ALTER TABLE `pending_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `subcat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `downloads`
--
ALTER TABLE `downloads`
  ADD CONSTRAINT `downloads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `lms`.`users` (`id`),
  ADD CONSTRAINT `downloads_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `images` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `fk_images_subcategory` FOREIGN KEY (`subcat_id`) REFERENCES `subcategory` (`subcat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pending_books`
--
ALTER TABLE `pending_books`
  ADD CONSTRAINT `fk_cat_id` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`),
  ADD CONSTRAINT `fk_subcat_id` FOREIGN KEY (`subcat_id`) REFERENCES `subcategory` (`subcat_id`),
  ADD CONSTRAINT `pending_books_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`),
  ADD CONSTRAINT `pending_books_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `lms`.`users` (`id`);

--
-- Constraints for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD CONSTRAINT `subcategory_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
