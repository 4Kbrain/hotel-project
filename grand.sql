-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2024 at 07:08 PM
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
-- Database: `grand`
--

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id_payment` int(10) UNSIGNED NOT NULL,
  `id_reservation` int(10) UNSIGNED NOT NULL,
  `confirm` varchar(15) DEFAULT 'Not Confirmed',
  `total_cost` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id_payment`, `id_reservation`, `confirm`, `total_cost`) VALUES
(1, 3, 'Paid', NULL),
(2, 4, 'Paid', NULL),
(3, 5, 'Paid', NULL),
(4, 6, 'Paid', NULL),
(5, 7, 'Paid', NULL),
(6, 8, 'Paid', NULL),
(7, 9, 'Paid', NULL),
(8, 10, 'Paid', NULL),
(9, 11, 'Not Confirmed', NULL),
(10, 12, 'Not Confirmed', NULL),
(11, 13, 'Not Confirmed', NULL),
(12, 14, 'Paid', 400),
(16, 16, 'Paid', 120);

--
-- Triggers `payment`
--
DELIMITER $$
CREATE TRIGGER `update_roombook_stat` AFTER UPDATE ON `payment` FOR EACH ROW BEGIN
    IF NEW.confirm = 'Paid' THEN
        UPDATE roombook SET stat = 'Paid' WHERE id_reservation = NEW.id_reservation;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `roombook`
--

CREATE TABLE `roombook` (
  `id_reservation` int(10) UNSIGNED NOT NULL,
  `FName` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `LName` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Email` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Phone` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `TRoom` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Bed` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `NRoom` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `cin` date DEFAULT NULL,
  `cout` date DEFAULT NULL,
  `stat` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `nodays` int(11) DEFAULT NULL,
  `total_cost` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roombook`
--

INSERT INTO `roombook` (`id_reservation`, `FName`, `LName`, `Email`, `Phone`, `TRoom`, `Bed`, `NRoom`, `cin`, `cout`, `stat`, `nodays`, `total_cost`) VALUES
(3, 'lol', 'll', 'yy@gmail.com', '0128391', 'Superior Room', 'Single', '2', '2024-02-17', '2024-02-21', 'Confirmed', 2, 122),
(5, 'Bob', 'Johnson', 'bob@example.com', '987654321', 'Deluxe', 'Double', '2', '2024-02-21', '2024-02-23', 'Pending', 2, 300),
(7, 'David', 'Miller', 'david@example.com', '321654987', 'Standard', 'Single', '1', '2024-02-23', '2024-02-25', 'Paid', 2, 200),
(8, 'Eve', 'Wilson', 'eve@example.com', '789123456', 'Deluxe', 'Double', '2', '2024-02-24', '2024-02-26', 'Paid', 2, 300),
(9, 'Frank', 'Lee', 'frank@example.com', '654987321', 'Suite', 'King', '1', '2024-02-25', '2024-02-27', 'Paid', 2, 400),
(10, 'Grace', 'Davis', 'grace@example.com', '123789456', 'Standard', 'Single', '1', '2024-02-26', '2024-02-28', 'Paid', 2, 200),
(11, 'Henry', 'Clark', 'henry@example.com', '987456123', 'Deluxe', 'Double', '2', '2024-02-27', '2024-02-29', 'Pending', 2, 300),
(12, 'Ivy', 'Moore', 'ivy@example.com', '456123789', 'Suite', 'King', '1', '2024-02-28', '2024-03-01', 'Confirmed', 2, 400),
(13, 'Jack', 'White', 'jack@example.com', '321789654', 'Standard', 'Single', '1', '2024-02-29', '2024-03-02', 'Confirm', 2, 200),
(14, 'a', 'asd', 'p@gmail.com', 'a', 'Superior Room', 'Double', '1', '2024-02-19', '2024-02-21', 'Paid', 2, 120),
(15, 'sad', '12313', 'sap@gmail.com', '092893', 'Superior Room', 'Quad', '1', '2024-02-19', '2024-02-20', 'Confirmed', 1, 120),
(16, 'lelah', 'banget', 'aaaaa@gmail.com', '0892183392883', 'Superior Room', 'Single', '1', '2024-02-19', '2024-02-21', 'Pending', 2, 120);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_name` enum('Superior Room','Deluxe Room','Guest House','Single Room') NOT NULL,
  `room_status` enum('Available','Occupied','Under Maintenance') NOT NULL,
  `room_capacity` enum('Single','Double','Triple','Quad') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `room_status`, `room_capacity`) VALUES
(1, 'Deluxe Room', 'Available', 'Single'),
(2, 'Superior Room', 'Available', 'Single'),
(3, 'Single Room', 'Available', 'Single');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `gmail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `gmail`, `password`, `is_admin`) VALUES
(1, 'aditgaming105@gmail.com', 'admin', 1),
(2, 'ad@g', '$2y$10$sQb0aLAElqaicA5kv/.4IumqiNbFw66m70FfA8/GeF.6j3ybpox5K', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `id_reservation` (`id_reservation`);

--
-- Indexes for table `roombook`
--
ALTER TABLE `roombook`
  ADD PRIMARY KEY (`id_reservation`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `gmail` (`gmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id_payment` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `roombook`
--
ALTER TABLE `roombook`
  MODIFY `id_reservation` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`id_reservation`) REFERENCES `roombook` (`id_reservation`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
