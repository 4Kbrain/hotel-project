-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2023 at 05:01 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(10) UNSIGNED NOT NULL,
  `usname` varchar(30) DEFAULT NULL,
  `pass` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `usname`, `pass`) VALUES
(1, 'Admin', '1234'),
(2, 'Prasath', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `newsletterlog`
--

CREATE TABLE `newsletterlog` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(52) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `news` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orang`
--

CREATE TABLE `orang` (
  `nama_depan` varchar(225) NOT NULL,
  `nama_belakang` varchar(225) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(225) NOT NULL,
  `prim` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orang`
--

INSERT INTO `orang` (`nama_depan`, `nama_belakang`, `email`, `password`, `prim`) VALUES
('anonymous', '2401', 'info@gmil.com', 'theone', 1),
('Remmy', 'Slowly', 'aditya.dwi3239@smk.belajar.id', '$2y$10$NrVUnDXgI2VmZjPWziK18eBrh/dX7GZ/C1YRkQtM63DlXdNaT.l7e', 3),
('adit', 'gg', 'aditadit120420@gmail.com', '$2y$10$JosvtIp4pcIC2wiU0F0c2O.q/JlMyP9pOO8x4svzHpXrN3x6FmH.q', 4),
('adit', 'haya', 'yy@gmail.com', '$2y$10$PD/5zd7cKM49nk/fIkuWqufomoarStXr08/ODVRazcNtHHTTM5D16', 5),
('ad', 'test', 'aa@gmail.com', '$2y$10$ASRdFg3e7R/cnGGgzX5Hj.QwOMdCXm.NSpZ7GniP6Np127eZe48RO', 6),
('Wildan', 'Gaming', 'wildan@gmail.com', 'Rahasia', 10);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) DEFAULT NULL,
  `title` varchar(5) DEFAULT NULL,
  `fname` varchar(30) DEFAULT NULL,
  `lname` varchar(30) DEFAULT NULL,
  `troom` varchar(30) DEFAULT NULL,
  `tbed` varchar(30) DEFAULT NULL,
  `nroom` int(11) DEFAULT NULL,
  `cin` date DEFAULT NULL,
  `cout` date DEFAULT NULL,
  `ttot` double(8,2) DEFAULT NULL,
  `fintot` double(8,2) DEFAULT NULL,
  `mepr` double(8,2) DEFAULT NULL,
  `meal` varchar(30) DEFAULT NULL,
  `btot` double(8,2) DEFAULT NULL,
  `noofdays` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `id_tamu` int(11) DEFAULT NULL,
  `id_kamar` int(11) DEFAULT NULL,
  `tanggal_checkin` date NOT NULL,
  `tanggal_checkout` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(15) DEFAULT NULL,
  `bedding` varchar(10) DEFAULT NULL,
  `place` varchar(10) DEFAULT NULL,
  `cusid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `type`, `bedding`, `place`, `cusid`) VALUES
(16, 'Deluxe Room', 'Single', 'Free', NULL),
(17, 'Deluxe Room', 'Double', 'Free', NULL),
(18, 'Superior Room', 'Single', 'Free', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roombook`
--

CREATE TABLE `roombook` (
  `id` int(10) UNSIGNED NOT NULL,
  `Title` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `FName` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `LName` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Email` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `National` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Country` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Phone` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `TRoom` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Bed` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `NRoom` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `Meal` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `cin` date DEFAULT NULL,
  `cout` date DEFAULT NULL,
  `stat` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `nodays` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roombook`
--

INSERT INTO `roombook` (`id`, `Title`, `FName`, `LName`, `Email`, `National`, `Country`, `Phone`, `TRoom`, `Bed`, `NRoom`, `Meal`, `cin`, `cout`, `stat`, `nodays`) VALUES
(1, 'Miss.', 'a', 'a', 'aditgaming105@gmail.com', 'Sri Lankan', 'Angola', '2131231', 'Superior Room', 'Single', '1', 'Room only', '2023-11-15', '2023-11-28', 'Not Conform', 13),
(2, 'Dr.', 'adit', 'dn', 'aditya.dwi3239@smk.belajar.id', 'Sri Lankan', 'Algeria', '0892131', 'Superior Room', 'Single', '1', 'Breakfast', '2023-11-15', '2023-11-27', 'Not Conform', 12),
(3, 'Miss.', 'a', 'a', 'aditgaming105@gmail.com', 'Sri Lankan', 'Angola', '2131231', 'Superior Room', 'Single', '1', 'Room only', '2023-11-15', '2023-11-28', 'Not Conform', 13),
(4, 'Miss.', 'a', 'a', 'aditgaming105@gmail.com', 'Sri Lankan', 'Angola', '2131231', 'Superior Room', 'Single', '1', 'Room only', '2023-11-15', '2023-11-28', 'Not Conform', 13),
(5, '', 'super', 'dasd', '213@gmnai.com', '', '', '123123', 'Superior Room', 'Double', '', '', '2023-11-14', '2023-11-14', 'Not Conform', 0),
(6, 'Miss.', 'a', 'sad', 'ridwannurislam@gmail.com', 'Sri Lankan', 'Bahrain', '123123', 'Deluxe Room', 'Triple', '1', 'Room only', '2023-11-23', '2023-11-24', 'Not Conform', 1),
(7, 'Dr.', 'a', 'bcd', 'asda@gmail.com', 'Sri Lankan', 'Azerbaijan', '23123', 'Single Room', 'Single', '1', 'Room only', '2023-11-22', '2023-11-27', 'Not Conform', 5),
(8, 'Rev .', 'a', 'a', 'yy@gmail.com', 'Indonesia', 'Bahrain', 'a', 'Deluxe Room', 'Single', '1', 'Room only', '2023-11-30', '2023-12-08', 'Not Conform', 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletterlog`
--
ALTER TABLE `newsletterlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orang`
--
ALTER TABLE `orang`
  ADD PRIMARY KEY (`prim`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `id_tamu` (`id_tamu`),
  ADD KEY `id_kamar` (`id_kamar`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roombook`
--
ALTER TABLE `roombook`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `newsletterlog`
--
ALTER TABLE `newsletterlog`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orang`
--
ALTER TABLE `orang`
  MODIFY `prim` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `roombook`
--
ALTER TABLE `roombook`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_tamu`) REFERENCES `tamu` (`id_tamu`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
