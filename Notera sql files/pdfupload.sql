-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2024 at 11:01 AM
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
(10, 'Sem7');

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
(9, 17, 3, '2023-10-01 08:05:23'),
(11, 29, 4, '2023-10-01 14:07:25'),
(16, 29, 12, '2024-07-03 15:22:06'),
(17, 29, 12, '2024-07-03 16:55:21'),
(18, 29, 10, '2024-07-04 09:46:57'),
(19, 29, 19, '2024-07-04 11:54:44'),
(20, 29, 21, '2024-07-04 13:49:22'),
(21, 29, 12, '2024-07-04 19:01:39');

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
  `subcat_id` int(11) DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `pdf`, `book_cover`, `book_name`, `author_name`, `published_date`, `date_added`, `cat_id`, `subcat_id`, `uploaded_by`) VALUES
(3, 'The Alchemist.pdf', 'alc.jpg', 'The  Alchemist', 'Paulo Coelho', '2005-10-05', '2023-09-25 13:32:51', 5, NULL, 17),
(4, 'Frankenstein.pdf', 'frank.jpeg', 'Frankenstein', 'Mary Shelley', '1818-01-01', '2023-09-25 13:32:51', 3, NULL, 17),
(6, 'Robinson Crusoe.pdf', 'robin.jpg', 'Robinson Crusoe', 'Daniel Defoe', '1719-04-25', '2023-09-25 13:32:51', 2, NULL, 38),
(7, 'Pragmatic Programmer.pdf', 'pragmatic.jpg', 'The Pragmatic Programmer', 'Andy Hunt, Dave Thomas', '1999-10-01', '2023-09-25 13:32:51', 4, NULL, 17),
(10, 'Network_Theory_sem3.pdf', 'dipson.jpg', 'Network Theory', 'NT', '2024-04-20', '2024-04-20 14:17:55', 2, 3, 29),
(12, 'embedded_sys_sem6_pastqn.pdf', 'bookss.jpg', 'embedded system', 'DRG', '2024-04-26', '2024-04-26 10:27:59', 2, 1, 38),
(18, 'Data_mining_assignment.pdf', 'Screenshot_20240609_141952_Video Player.jpg', 'screenshot', 'sadfa', '2024-07-03', '2024-07-04 11:52:51', 1, 1, 0),
(19, 'CamScanner 05-05-2024 18.44.pdf', 'microcover.jpg', 'random', 'random', '2024-07-04', '2024-07-04 11:52:54', 1, 2, 0),
(20, 'CVandCoverLetter_020313 (1).pdf', '33a6b8e8-0df1-4eeb-bdb0-4e06aa793d18.jpg', 'cv', 'hello world', '2024-07-04', '2024-07-04 11:55:30', 1, 1, 0),
(21, 'simulation_modeling_sem6_pastqn.pdf', '76d0a14b-5b19-40bf-b762-1821cc6b5ee4.jpeg', 's&m', 'sk', '2024-04-26', '2024-07-04 11:56:24', 2, 3, 29);

--
-- Triggers `images`
--
DELIMITER $$
CREATE TRIGGER `update_pending_books_uploaded_by` AFTER UPDATE ON `images` FOR EACH ROW BEGIN
    UPDATE pending_books
    SET uploaded_by = NEW.uploaded_by
    WHERE uploaded_by = OLD.uploaded_by;
END
$$
DELIMITER ;

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
  `subcat_id` int(11) DEFAULT NULL,
  `uploaded_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_books`
--

INSERT INTO `pending_books` (`id`, `book_name`, `author_name`, `published_date`, `cat_id`, `user_id`, `pdf`, `book_cover`, `status`, `created_at`, `subcat_id`, `uploaded_by`) VALUES
(2, 'simulation', 'sk', '2024-04-26', 2, 29, 'hand written note(S&M).pdf', 'IMG-20230922-WA0016.jpg', 'pending', '2024-04-26 11:42:09', 4, 0),
(9, 'Use case', 'user', '2024-07-04', 2, 29, 'Use_case_analysis.pdf', '417280944_240041669148795_896031377269378056_n.jpg', 'pending', '2024-07-04 10:40:45', 3, 29);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review_text` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `book_id`, `user_id`, `rating`, `review_text`, `review_date`) VALUES
(1, 12, 29, 2, 'nice book', '2024-07-03 09:52:15'),
(2, 12, 38, 5, 'excellent note', '2024-07-03 10:07:03'),
(3, 10, 38, 4, 'quite good', '2024-07-03 11:04:13'),
(7, 3, 38, 4, 'good', '2024-07-03 11:14:15'),
(8, 4, 38, 2, 'bad', '2024-07-03 11:18:27'),
(10, 6, 38, 1, 'very bad', '2024-07-03 11:23:40');

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
(4, 'Applied Mechanics', 2),
(10, 'EEM', 4),
(13, 'Data communication', 6);

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
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `book_id` (`book_id`);

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
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `download_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pending_books`
--
ALTER TABLE `pending_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `subcat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `images` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD CONSTRAINT `subcategory_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
