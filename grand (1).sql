-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2024 at 02:01 AM
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
-- Table structure for table `kamar`
--

CREATE TABLE `kamar` (
  `id_kamar` int(11) NOT NULL,
  `type_room` varchar(255) NOT NULL,
  `room_status` varchar(255) NOT NULL,
  `room_capacity` int(11) NOT NULL,
  `bed_type` varchar(10) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kamar`
--

INSERT INTO `kamar` (`id_kamar`, `type_room`, `room_status`, `room_capacity`, `bed_type`, `user_id`) VALUES
(11, 'Superior Room', 'Waiting For Approval', 2, 'Single', 1),
(12, 'Deluxe Room', 'Waiting For Approval', 3, 'Double', 2),
(13, 'Guest House', 'Available', 4, 'Triple', 3),
(14, 'Single Room', 'Available', 1, 'Single', 4),
(15, 'Superior Room', 'Waiting For Approval', 2, 'Single', NULL),
(16, 'Deluxe Room', 'Waiting For Approval', 3, 'Double', NULL),
(17, 'Guest House', 'Available', 4, 'Triple', NULL),
(18, 'Single Room', 'Not Available', 1, 'Single', NULL),
(19, 'Superior Room', 'Waiting For Approval', 2, 'Single', NULL),
(20, 'Deluxe Room', 'Waiting For Approval', 3, 'Double', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id_payment` int(10) UNSIGNED NOT NULL,
  `First` text NOT NULL,
  `Last` text NOT NULL,
  `Gmail` varchar(50) NOT NULL,
  `id_reservation` int(10) UNSIGNED NOT NULL,
  `TRoom` varchar(20) NOT NULL,
  `Bed` varchar(10) NOT NULL,
  `NRoom` int(2) NOT NULL,
  `confirm` varchar(15) DEFAULT 'Not Confirmed',
  `amount` int(11) NOT NULL,
  `total_cost` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id_payment`, `First`, `Last`, `Gmail`, `id_reservation`, `TRoom`, `Bed`, `NRoom`, `confirm`, `amount`, `total_cost`) VALUES
(1, '0', '0', '0', 3, '0', '0', 0, 'Paid', 0, NULL),
(2, '0', '0', '0', 4, '0', '0', 0, 'Paid', 0, NULL),
(3, '0', '0', '0', 5, '0', '0', 0, 'Paid', 0, NULL),
(4, '0', '0', '0', 6, '0', '0', 0, 'Paid', 0, NULL),
(5, '0', '0', '0', 7, '0', '0', 0, 'Paid', 0, NULL),
(6, '0', '0', '0', 8, '0', '0', 0, 'Paid', 0, NULL),
(7, '0', '0', '0', 9, '0', '0', 0, 'Paid', 0, NULL),
(8, '0', '0', '0', 10, '0', '0', 0, 'Paid', 0, NULL),
(9, '0', '0', '0', 11, '0', '0', 0, 'Paid', 0, NULL),
(10, '0', '0', '0', 12, '0', '0', 0, 'Paid', 0, NULL),
(11, '0', '0', '0', 13, '0', '0', 0, 'Paid', 0, NULL),
(12, '0', '0', '0', 14, '0', '0', 0, 'Paid', 0, 400),
(16, '0', '0', '0', 16, '0', '0', 0, 'Paid', 0, 120),
(17, '0', '0', '0', 17, '0', '0', 0, 'Not Confirmed', 0, 120),
(18, '0', '0', '0', 18, '0', '0', 0, 'Not Confirmed', 0, 240),
(19, '0', '0', '0', 19, '0', '0', 0, 'Not Confirmed', 0, 100),
(20, '0', '0', '0', 20, '0', '0', 0, 'Paid', 0, 170),
(21, '0', '0', '0', 21, '0', '0', 0, 'Not Confirmed', 0, 680),
(22, '', '', '', 22, '', '', 0, 'Not Confirmed', 0, 120),
(23, '', '', '', 23, '', '', 0, 'Not Confirmed', 0, 680),
(24, '', '', '', 24, '', '', 0, 'Not Confirmed', 0, 360),
(25, '', '', '', 25, '', '', 0, 'Not Confirmed', 0, 120),
(29, '', '', '', 3, '', '', 0, 'Not Confirmed', 0, 240),
(31, '', '', '', 5, '', '', 0, 'Not Confirmed', 0, 240),
(33, '', '', '', 7, '', '', 0, 'Not Confirmed', 0, 880),
(34, '', '', '', 8, '', '', 0, 'Not Confirmed', 0, 880),
(35, '', '', '', 9, '', '', 0, 'Not Confirmed', 0, 1760);

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
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `id_kamar` int(11) DEFAULT NULL,
  `NIK` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `troom` varchar(255) NOT NULL,
  `bed` varchar(255) NOT NULL,
  `nroom` int(11) NOT NULL,
  `cin` date NOT NULL,
  `cout` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `nodays` int(11) NOT NULL DEFAULT 0,
  `total_cost` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `id_kamar`, `NIK`, `fname`, `lname`, `email`, `phone`, `troom`, `bed`, `nroom`, `cin`, `cout`, `status`, `nodays`, `total_cost`) VALUES
(7, NULL, 'aditgaming105@gmail.com', 'a', 'asd', 'yy@gmail.com', '0128391', 'Guest House', 'Triple', 4, '2024-03-04', '2024-03-06', 'Approved', 2, 880.00),
(8, NULL, 'ad@g', 'asd', 'asdad', 'aditgaming1d@gmail.com', '0128391213', 'Guest House', 'Triple', 4, '2024-03-05', '2024-03-06', 'Approved', 1, 880.00),
(9, NULL, 'ad@g', 'sd', 'asmand', 'Anonymous1023@gmail.com', '12903', 'Guest House', 'Triple', 4, '2024-03-04', '2024-03-06', 'Waiting For Approval', 2, 1760.00);

-- --------------------------------------------------------

--
-- Table structure for table `roombook`
--

CREATE TABLE `roombook` (
  `id_reservation` int(10) UNSIGNED NOT NULL,
  `NIK` int(20) NOT NULL,
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
  `total_cost` int(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roombook`
--

INSERT INTO `roombook` (`id_reservation`, `NIK`, `FName`, `LName`, `Email`, `Phone`, `TRoom`, `Bed`, `NRoom`, `cin`, `cout`, `stat`, `nodays`, `total_cost`) VALUES
(3, 0, 'lol', 'll', 'yy@gmail.com', '0128391', 'Superior Room', 'Single', '2', '2024-02-17', '2024-02-21', 'Confirmed', 2, 122),
(5, 0, 'Bob', 'Johnson', 'bob@example.com', '987654321', 'Superior Room', 'Double', '2', '2024-02-21', '2024-02-23', 'Confirmed', 2, 300),
(7, 0, 'David', 'Miller', 'david@example.com', '321654987', 'Superior Room', 'Single', '1', '2024-02-23', '2024-02-25', 'Confirmed', 2, 200),
(8, 0, 'Eve', 'Wilson', 'eve@example.com', '789123456', 'Deluxe', 'Double', '2', '2024-02-24', '2024-02-26', 'Paid', 2, 300),
(9, 0, 'Frank', 'Lee', 'frank@example.com', '654987321', 'Suite', 'King', '1', '2024-02-25', '2024-02-27', 'Paid', 2, 400),
(10, 0, 'Grace', 'Davis', 'grace@example.com', '123789456', 'Standard', 'Single', '1', '2024-02-26', '2024-02-28', 'Paid', 2, 200),
(11, 0, 'Henry', 'Clark', 'henry@example.com', '987456123', 'Deluxe', 'Double', '2', '2024-02-27', '2024-02-29', 'Paid', 2, 300),
(12, 0, 'Ivy', 'Moore', 'ivy@example.com', '456123789', 'Suite', 'King', '1', '2024-02-28', '2024-03-01', 'Paid', 2, 400),
(13, 0, 'Jack', 'White', 'jack@example.com', '321789654', 'Standard', 'Single', '1', '2024-02-29', '2024-03-02', 'Paid', 2, 200),
(14, 0, 'a', 'asd', 'p@gmail.com', 'a', 'Superior Room', 'Double', '1', '2024-02-19', '2024-02-21', 'Confirmed', 2, 120),
(15, 0, 'sad', '12313', 'sap@gmail.com', '092893', 'Superior Room', 'Quad', '1', '2024-02-19', '2024-02-20', 'Confirmed', 1, 120),
(16, 0, 'lelah', 'banget', 'aaaaa@gmail.com', '0892183392883', 'Superior Room', 'Single', '1', '2024-02-19', '2024-02-21', 'Pending', 2, 120),
(17, 0, 'halo', 'dunia', 'hado@gmail.com', '0892381273', 'Superior Room', 'Single', '1', '2024-02-19', '2024-02-20', 'Pending', 1, 120),
(18, 0, 'o', 'l', 'sadaas@gmail.com', '0987654321', 'Superior Room', 'Single', '2', '2024-02-19', '2024-02-21', 'Pending', 2, 240),
(19, 0, '1', '1', '1@v.d', '1', 'Single Room', 'Single', '1', '2024-02-20', '2024-02-21', 'Pending', 1, 100),
(20, 0, 'dinda', 'ayu', 'dinda@gm.c', '999999', 'Deluxe Room', 'Double', '1', '2024-02-20', '2024-02-22', 'Paid', 2, 170),
(21, 0, 'aaaaaaaaa', 'a', 'asasa@yahoo.com', '0921389123', 'Deluxe Room', 'Triple', '4', '1500-01-01', '2500-12-31', 'Pending', 365606, 680),
(22, 0, 'asd', 'lallal', 'whyme@gmail.com', '0892828TwT', 'Superior Room', 'Single', '1', '2024-02-21', '2024-02-23', 'Pending', 2, 120),
(23, 0, '09123', '3901', '0319@192', '0192', 'Deluxe Room', 'Double', '4', '0873-09-12', '0000-00-00', 'Pending', 414189, 680),
(24, 222, '222222', '222', '222@22', '2222', 'Superior Room', 'Double', '3', '0000-00-00', '0000-00-00', 'Pending', 8034, 360),
(25, 0, 'kontol', 'kontol', 'kontol@kontol.kontol', '000000000000', 'Superior Room', 'Single', '1', '2024-01-28', '2016-01-31', 'Pending', 2919, 120),
(26, 3, 'as', 'as', 'qs@gm', '0121291212', 'Superior Room', 'Single', '2', '0001-11-11', '0111-11-11', 'Pending', 40176, 240),
(27, 1, 'a', 'll', 'ps@gmail.com', '0128391', 'Deluxe Room', 'Double', '3', '1212-11-12', '1211-12-11', 'Pending', 337, 510),
(28, 4, 'lol', 'll', 'asdp@gmail.com', '1231', 'Deluxe Room', 'Double', '3', '2024-03-04', '2024-03-05', 'Pending', 1, 510);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `NIK` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `total_cost` decimal(10,2) DEFAULT NULL,
  `cin` date DEFAULT NULL,
  `cout` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `NIK`, `nama`, `total_cost`, `cin`, `cout`) VALUES
(4, 'aditgaming105@gmail.com', 'a asd', 880.00, '2024-03-04', '2024-03-06'),
(7, 'ad@g', 'asd asdad', 880.00, '2024-03-05', '2024-03-06');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `ID_TRANSACTION` int(11) NOT NULL,
  `NIK` int(20) NOT NULL,
  `full_name` text NOT NULL,
  `ID_ROOM` int(11) NOT NULL,
  `id_payment` int(10) NOT NULL,
  `id_reservation` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `NIK` int(20) NOT NULL,
  `gmail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`NIK`, `gmail`, `password`, `is_admin`) VALUES
(1, 'aditgaming105@gmail.com', 'admin', 1),
(2, 'ad@g', '$2y$10$sQb0aLAElqaicA5kv/.4IumqiNbFw66m70FfA8/GeF.6j3ybpox5K', 0),
(3, 'dinda@gm.c', '$2y$10$Q9zk7uSvzSNQeUuB.ayzeOru4v5a/33Txph9AfrKoQEah73clmZhu', 0),
(4, 'a@a', '$2y$10$Fz29epkwQzHo02XXbzCspewIlOCOhbCogAr5s54azbK/jq4DUpRDO', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id_kamar`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `id_reservation` (`id_reservation`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_kamar` (`id_kamar`);

--
-- Indexes for table `roombook`
--
ALTER TABLE `roombook`
  ADD PRIMARY KEY (`id_reservation`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `NIK` (`NIK`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`ID_TRANSACTION`),
  ADD KEY `id_payment` (`id_payment`),
  ADD KEY `NIK` (`NIK`),
  ADD KEY `id_reservation` (`id_reservation`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`NIK`),
  ADD UNIQUE KEY `gmail` (`gmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id_kamar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id_payment` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `roombook`
--
ALTER TABLE `roombook`
  MODIFY `id_reservation` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `NIK` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`id_reservation`) REFERENCES `roombook` (`id_reservation`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_id_kamar` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
