-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2024 at 04:56 AM
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
-- Table structure for table `history`
--

CREATE TABLE `history` (
  `id_history` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL,
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
  `nodays` int(11) NOT NULL DEFAULT 0,
  `total_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(255) NOT NULL,
  `kembalian` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id_history`, `id_reservation`, `id_kamar`, `NIK`, `fname`, `lname`, `email`, `phone`, `troom`, `bed`, `nroom`, `cin`, `cout`, `nodays`, `total_cost`, `status`, `kembalian`) VALUES
(1, 24, 32, '2', 'mm', 'mm', 'aditadit120420@gmail.com', '10283', 'Superior Room', 'Single', 1, '2024-05-14', '2024-05-17', 3, 360.00, 'Booked', -2900.00),
(2, 25, 33, '2', 'mama', 'mamam', 'veldora5000@gmail.com', '91283', 'Superior Room', 'Single', 2, '2024-05-14', '2024-05-16', 2, 480.00, 'Booked', -2500.00),
(3, 26, 34, '2', 'mm', 'mmm', 'mmm@gmail', 'mmm', 'Guest House', 'Single', 1, '2024-05-14', '2024-05-21', 7, 1540.00, 'Booked', -2000.00);

-- --------------------------------------------------------

--
-- Table structure for table `kamar`
--

CREATE TABLE `kamar` (
  `id_kamar` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  `type_room` varchar(255) NOT NULL,
  `room_status` varchar(255) NOT NULL,
  `room_capacity` int(11) NOT NULL,
  `bed_type` varchar(10) DEFAULT NULL,
  `NIK` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kamar`
--

INSERT INTO `kamar` (`id_kamar`, `id_reservation`, `type_room`, `room_status`, `room_capacity`, `bed_type`, `NIK`) VALUES
(32, 24, 'Superior Room', 'In Use', 1, 'Single', 2),
(33, 25, 'Superior Room', 'In Use', 2, 'Single', 2),
(34, 26, 'Guest House', 'In Use', 1, 'Single', 2);

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
  `payment` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `kembalian` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `id_kamar`, `NIK`, `fname`, `lname`, `email`, `phone`, `troom`, `bed`, `nroom`, `cin`, `cout`, `status`, `nodays`, `payment`, `total_cost`, `kembalian`) VALUES
(23, 34, '2', 'M', 'M', 'ma@gmail.com', '00', 'Superior Room', 'Single', 1, '2024-05-14', '2024-05-19', 'Cancelled', 5, 0.00, 600.00, 0.00),
(24, 34, '2', 'mm', 'mm', 'aditadit120420@gmail.com', '10283', 'Superior Room', 'Single', 1, '2024-05-14', '2024-05-17', 'Booked', 3, 400.00, 360.00, 40.00),
(25, 34, '2', 'mama', 'mamam', 'veldora5000@gmail.com', '91283', 'Superior Room', 'Single', 2, '2024-05-14', '2024-05-16', 'Booked', 2, 500.00, 480.00, 20.00),
(26, 34, '2', 'mm', 'mmm', 'mmm@gmail', 'mmm', 'Guest House', 'Single', 1, '2024-05-14', '2024-05-21', 'Booked', 7, 2000.00, 1540.00, 460.00);

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

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id_transactions` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  `id_kamar` int(11) NOT NULL,
  `NIK` varchar(255) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `troom` varchar(255) DEFAULT NULL,
  `bed` varchar(255) DEFAULT NULL,
  `nroom` int(11) DEFAULT NULL,
  `nodays` int(11) NOT NULL,
  `cin` date DEFAULT NULL,
  `cout` date DEFAULT NULL,
  `payment` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `kembalian` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id_transactions`, `id_reservation`, `id_kamar`, `NIK`, `nama`, `email`, `troom`, `bed`, `nroom`, `nodays`, `cin`, `cout`, `payment`, `total_cost`, `kembalian`) VALUES
(19, 26, 34, '2', 'mm mmm', '', 'Guest House', 'Single', 1, 7, '2024-05-14', '2024-05-21', 2000.00, 1540.00, 460.00);

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
(4, 'a@a', '$2y$10$Fz29epkwQzHo02XXbzCspewIlOCOhbCogAr5s54azbK/jq4DUpRDO', 0),
(5, 'wildan@gmail.com', '$2y$10$0ErK7zezhHNDCR4QSbvbyOAsYWWQmM8AyI9N/8CfDOOaxYyFLIEVS', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id_history`),
  ADD KEY `id_reservation` (`id_reservation`,`id_kamar`,`NIK`);

--
-- Indexes for table `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id_kamar`),
  ADD KEY `NIK` (`NIK`),
  ADD KEY `id_reservation` (`id_reservation`);

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
  ADD KEY `NIK` (`NIK`),
  ADD KEY `id_kamar` (`id_kamar`),
  ADD KEY `id_kamar_2` (`id_kamar`);

--
-- Indexes for table `roombook`
--
ALTER TABLE `roombook`
  ADD PRIMARY KEY (`id_reservation`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id_transactions`),
  ADD UNIQUE KEY `NIK` (`NIK`),
  ADD KEY `id_reservation` (`id_reservation`),
  ADD KEY `id_kamar` (`id_kamar`);

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
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id_kamar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id_payment` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `roombook`
--
ALTER TABLE `roombook`
  MODIFY `id_reservation` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id_transactions` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `NIK` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
