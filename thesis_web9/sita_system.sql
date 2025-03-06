-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2025 at 06:26 AM
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
-- Database: `sita_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user`, `action`, `timestamp`) VALUES
(93, 'police1000', 'User login successful', '2025-02-28 19:24:57'),
(94, 'police1000', 'User  logout', '2025-02-28 19:25:23'),
(95, 'admin123', 'User login successful', '2025-02-28 19:28:16'),
(96, 'admin123', 'User  logout', '2025-02-28 19:29:15'),
(97, 'police1000', 'User login successful', '2025-02-28 19:29:29'),
(98, 'police1000', 'User  logout', '2025-02-28 19:29:55'),
(99, 'newpolice', 'User login successful', '2025-02-28 19:30:10'),
(100, 'newpolice', 'User  logout', '2025-02-28 19:35:39'),
(101, 'admin123', 'User login successful', '2025-02-28 19:36:38'),
(102, 'admin123', 'User  logout', '2025-02-28 19:39:28'),
(103, 'police1000', 'User login successful', '2025-02-28 19:40:34'),
(104, 'police1000', 'User  logout', '2025-02-28 20:04:03'),
(105, 'admin123', 'User login successful', '2025-02-28 20:04:12'),
(106, 'police1000', 'User login successful', '2025-03-01 06:46:58'),
(107, 'admin123', 'User login successful', '2025-03-01 12:32:46'),
(108, 'police1000', 'User login successful', '2025-03-01 12:34:52'),
(109, 'police1000', 'User  logout', '2025-03-01 12:34:55'),
(110, 'police1000', 'User login successful', '2025-03-01 12:35:29'),
(111, 'police1000', 'User  logout', '2025-03-01 14:39:11'),
(112, 'newpolice', 'User login successful', '2025-03-01 14:39:32'),
(113, 'newpolice', 'User  logout', '2025-03-01 15:05:15'),
(114, 'police1000', 'User login successful', '2025-03-01 15:05:27'),
(115, 'police1000', 'User  logout', '2025-03-01 16:05:43'),
(116, 'police1000', 'User login successful', '2025-03-01 16:10:01'),
(117, 'police1000', 'User  logout', '2025-03-01 18:00:19'),
(118, 'newpolice', 'User login successful', '2025-03-01 18:00:36'),
(119, 'newpolice', 'User login successful', '2025-03-02 10:35:54'),
(120, 'newpolice', 'User  logout', '2025-03-02 16:53:11'),
(121, 'admin123', 'User login successful', '2025-03-02 16:53:24'),
(122, 'admin123', 'User  logout', '2025-03-02 16:55:12'),
(123, 'admin123', 'User login successful', '2025-03-02 16:55:20'),
(124, 'admin123', 'User  logout', '2025-03-02 16:56:31'),
(125, 'newpolice', 'User login successful', '2025-03-02 16:56:41'),
(126, 'police1000', 'User login successful', '2025-03-03 07:53:50'),
(127, 'police1000', 'User  logout', '2025-03-03 08:44:47'),
(128, 'police1000', 'User login successful', '2025-03-03 08:45:05'),
(129, 'police1000', 'User  logout', '2025-03-03 08:45:26'),
(130, 'Unknown User', 'User  logout', '2025-03-03 08:45:28'),
(131, 'admin123', 'User login successful', '2025-03-03 08:46:14'),
(132, 'admin123', 'User  logout', '2025-03-03 08:46:54'),
(133, 'police1000', 'User login successful', '2025-03-03 08:48:17'),
(134, 'police1000', 'User  logout', '2025-03-03 08:56:20'),
(135, 'admin123', 'User login successful', '2025-03-03 08:56:35'),
(136, 'admin123', 'Admin created new user: dragonuser', '2025-03-03 09:06:23'),
(137, 'admin123', 'User  logout', '2025-03-03 09:06:36'),
(138, 'dragonuser', 'User login successful', '2025-03-03 09:07:03'),
(139, 'police1000', 'User login successful', '2025-03-03 16:01:17'),
(140, 'police1000', 'User  logout', '2025-03-03 16:38:34'),
(141, 'newpolice', 'User login successful', '2025-03-03 16:38:42'),
(142, 'newpolice', 'User  logout', '2025-03-03 17:03:03'),
(143, 'newpolice', 'User login successful', '2025-03-03 17:03:13'),
(144, 'newpolice', 'User  logout', '2025-03-03 18:12:42'),
(145, 'admin123', 'User login successful', '2025-03-03 18:12:55'),
(146, 'admin123', 'Admin created new user: julius123', '2025-03-03 18:16:39'),
(147, 'admin123', 'User  logout', '2025-03-03 18:33:50'),
(148, 'newpolice', 'User login successful', '2025-03-03 18:34:05'),
(149, 'newpolice', 'User  logout', '2025-03-03 18:59:13'),
(150, 'julius123', 'User login successful', '2025-03-03 18:59:42'),
(151, 'dragonuser', 'User  logout', '2025-03-04 02:42:46'),
(152, 'admin123', 'Failed login attempt', '2025-03-04 02:47:18'),
(153, 'admin123', 'User login successful', '2025-03-04 02:47:27'),
(154, 'admin123', 'User  logout', '2025-03-04 02:52:13'),
(155, 'admin123', 'User login successful', '2025-03-04 02:52:41'),
(156, 'admin123', 'User  logout', '2025-03-04 02:53:34'),
(157, 'julius123', 'User login successful', '2025-03-04 02:55:19'),
(158, 'julius123', 'User  logout', '2025-03-04 02:55:48'),
(159, 'admin123', 'User login successful', '2025-03-04 02:55:59'),
(160, 'admin123', 'Admin created new user: HARVEY', '2025-03-04 02:56:36'),
(161, 'admin123', 'User  logout', '2025-03-04 02:56:39'),
(162, 'HARVEY', 'User login successful', '2025-03-04 02:56:48'),
(163, 'admin123', 'User login successful', '2025-03-04 03:28:38'),
(164, 'HARVEY', 'User  logout', '2025-03-04 05:24:28'),
(165, 'HARVEY', 'User login successful', '2025-03-04 05:24:47');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `user_type` varchar(20) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `timestamp`) VALUES
(5, 2, 'new', 0, '2025-03-03 00:54:42'),
(6, 19, 'hzdgfh', 0, '2025-03-03 01:59:24'),
(7, 17, 'jhasd', 0, '2025-03-03 16:11:07'),
(8, 2, 'hello\r\n', 0, '2025-03-04 10:47:40'),
(9, 2, 'heehhe\'', 0, '2025-03-04 10:48:59'),
(10, 2, 'OKAY\r\n', 0, '2025-03-04 11:56:58'),
(11, 22, 'OOKAY', 0, '2025-03-04 11:57:21'),
(12, 22, 'QHKHKksh\r\n', 0, '2025-03-04 11:57:45');

-- --------------------------------------------------------

--
-- Table structure for table `ordinances`
--

CREATE TABLE `ordinances` (
  `ordinance_title` varchar(500) NOT NULL,
  `ordinance_desc` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordinances`
--

INSERT INTO `ordinances` (`ordinance_title`, `ordinance_desc`) VALUES
('Municipal Ordinance No. 16 Series 2021', 'AN ORDINANCE PERMANENTLY CLOSING TO ANY VEHICULAR TRAFFIC BOTH LANES OF L. MILLAN STREET, ASINGAN, PANGASINAN'),
('Municipal Ordinance No. 18 Series 2021', 'AN ORDINANCE IMPLEMENTING R.A. 10754, AN ACT EXPANDING THE BENEFITS AND PRIVILEGES OF PERSONS WITH DISABILITY (PWD) IN THE MUNICIPALITY OF ASINGAN, PANGASINAN'),
('Municipal Ordinance No. 19 Series 2021', 'AN ORDINANCE HONORING AND GRANTING CASH INCENTIVES TO ASINGAN CENTENARIANS AND PROVIDING FUNDS THEREFOR'),
('ordinance t', 'ordinance d'),
('ewee', 'qqeeqeqeq'),
('qwqwq', 'hj1gjgj1gj1'),
('khwhyi2y83', '82831381'),
('8128182', '\r\n\"!!\"!21212\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n!\"!\"!\"\r\n\r\n\r\n\r\n!\"!\"'),
('121212y12', '212u1216218288177\r\n\r\n!!\"\"\r\n\r\n!\"!\"!\r\n\"\r\n!\r\n\"\r\nD\r\n\r\n\r\n\r\n'),
('dragon', 'dragon');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `status` enum('active','inactive') DEFAULT 'inactive',
  `last_login` datetime DEFAULT NULL,
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `name` varchar(255) NOT NULL,
  `rank` varchar(255) NOT NULL,
  `badge_no` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `status`, `last_login`, `last_active`, `created_at`, `updated_at`, `name`, `rank`, `badge_no`) VALUES
(2, 'admin123', 'admin123', 'admin', 'inactive', NULL, NULL, '2025-02-27 23:22:01', '2025-02-27 23:22:01', '', '', ''),
(21, 'julius123', 'den121001', 'user', 'inactive', NULL, NULL, '2025-03-04 02:16:39', '2025-03-04 02:16:39', 'julius', 'spo1', '143'),
(22, 'HARVEY', '12345678', 'user', 'inactive', NULL, NULL, '2025-03-04 10:56:36', '2025-03-04 10:56:36', 'HARVEY', 'SGT', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `violations`
--

CREATE TABLE `violations` (
  `violation_id` int(11) NOT NULL,
  `ticket_num` int(11) NOT NULL,
  `offender_details` varchar(255) NOT NULL,
  `vehicle_details` varchar(255) NOT NULL,
  `apprehending_officer` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL,
  `status` varchar(55) NOT NULL,
  `fines` varchar(55) NOT NULL,
  `offender_name` varchar(255) DEFAULT NULL,
  `offender_address` varchar(255) DEFAULT NULL,
  `license_no` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `violation_place` varchar(255) DEFAULT NULL,
  `vehicle_type` varchar(255) DEFAULT NULL,
  `plate_no` varchar(255) DEFAULT NULL,
  `vehicle_registration` varchar(255) DEFAULT NULL,
  `rank` varchar(100) NOT NULL,
  `officer_name` varchar(100) NOT NULL,
  `badge_no` varchar(20) NOT NULL,
  `violation_name` varchar(255) NOT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `officer_id` int(11) NOT NULL,
  `offense_count` enum('1st offense','2nd offense','3rd offense') DEFAULT '1st offense'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `violations`
--

INSERT INTO `violations` (`violation_id`, `ticket_num`, `offender_details`, `vehicle_details`, `apprehending_officer`, `datetime`, `status`, `fines`, `offender_name`, `offender_address`, `license_no`, `birthdate`, `violation_place`, `vehicle_type`, `plate_no`, `vehicle_registration`, `rank`, `officer_name`, `badge_no`, `violation_name`, `is_deleted`, `user_id`, `officer_id`, `offense_count`) VALUES
(54, 10, '', '', 'JOHNDEL', '2025-03-03 03:58:00', 'Resolved', '5000', 'Luck', 'Anonas', 'no101010', '2025-03-12', 'Manaoag', 'TMX honda', '777777878090', 'dec1210', 'spo5555', '', '23', 'Dazzling light', 1, 19, 0, '1st offense'),
(55, 10, '', '', 'JOHNDEL', '2025-03-03 04:00:00', 'Resolved', '500', 'Luck', 'Anonas', 'no101010', '2025-03-03', 'Manaoag', 'TMX honda', '777777878090', 'dec1210', 'spo5555', '', '23', 'Trucks/Bus Ban', 1, 19, 0, '2nd offense'),
(56, 10, '', '', 'JOHNDEL', '2025-03-03 04:01:00', 'Resolved', '500', 'Luck', 'Anonas', 'no101010', '2025-03-03', 'Manaoag', 'TMX honda', '777777878090', 'dec1210', 'spo5555', '', '23', 'Dazzling light', 1, 19, 0, '3rd offense'),
(58, 10, '', '', 'JOHNDEL', '2025-03-03 08:52:00', 'Resolved', '500', 'Luck', 'Anonas', 'no101010', '2025-03-04', 'Manaoag', 'TMX honda', '777777878090', 'dec1210', 'spo5555', '', '23', 'Reckless Driving', 1, 19, 0, ''),
(59, 10, '', '', 'JOHNDEL', '2025-03-06 08:53:00', 'Resolved', '500', 'Luck', 'Anonas', 'no101010', '2025-03-11', 'Manaoag', 'TMX honda', '777777878090', 'dec1210', 'spo5555', '', '23', 'Illegal Parking', 1, 19, 0, ''),
(60, 10, '', '', 'JOHNDEL', '2025-03-03 11:05:00', 'Resolved', '500', 'Luck', 'Anonas', 'no101010', '2025-03-03', 'Manaoag', 'TMX honda', '777777878090', 'dec1210', 'spo5555', '', '23', 'Dazzling light', 1, 19, 0, ''),
(61, 10, '', '', 'JOHNDEL', '2025-03-03 11:25:00', 'Resolved', '500', 'Lucky me', 'Anonas', 'no0000111', '2025-03-20', 'Manaoag', 'TMX honda', '777777878090', 'dec1210', 'spo5555', '', '23', 'Dazzling light', 1, 19, 0, '1st offense'),
(62, 10, '', '', 'JOHNDEL', '2025-03-03 13:25:00', 'Resolved', '500', 'Lucky me', 'Anonas', 'no0000111', '2025-03-05', 'Manaoag', 'TMX honda', '777777878090', 'dec1210', 'spo5555', '', '23', 'Dazzling light', 1, 19, 0, '2nd offense'),
(63, 10, '', '', 'JOHNDEL', '2025-03-03 13:26:00', 'Resolved', '500', 'Lucky me', 'Anonas', 'no0000111', '2025-03-05', 'Manaoag', 'TMX honda', '777777878090', 'dec1210', 'spo5555', '', '23', 'Dazzling light', 1, 19, 0, '3rd offense'),
(64, 10, '', '', 'JOHNDEL', '2025-03-03 13:26:00', 'Resolved', '500', 'Lucky me', 'Anonas', 'no0000111', '2025-02-23', 'Manaoag', 'TMX honda', '777777878090', 'dec1210', 'spo5555', '', '23', 'Dazzling light', 1, 19, 0, ''),
(65, 4589, '', '', 'JOGNDEL', '2025-03-03 16:06:00', 'Resolved', '4000', 'Lucky', 'Alaminos', 'no101010', '2025-03-02', 'Urdaneta', 'rusiii', '121001dec', 'weh456', 'spo88888', '', '2334', 'Dazzling light', 1, 17, 0, ''),
(66, 4509, '', '', 'Arden', '2025-03-03 16:52:00', 'Pending', '4500', 'Harvy', 'Flores', 'no-246', '2025-03-03', 'Asingan', 'skydrive', 'plate_no...', 'qwerty123', 'sergeant', '', '001', 'Leaving the scene of accident without justifiable cause', 0, 17, 0, '1st offense'),
(67, 4589, '', '', 'JOHNDEL', '2025-03-03 16:53:00', 'Pending', '5000', 'Harvy', 'Flores', 'no-246', '2025-02-23', 'Binalonan', 'rusiii101010', 'trykuhhhhh789', 'vehic12', 'spoXXX.com', '', '101010', 'Dazzling light', 0, 17, 0, '2nd offense'),
(68, 988, '', '', 'denden', '2025-03-05 17:03:00', 'Resolved', '30000', 'JOHN SMITH', 'Flores', 'no097777777777', '2025-03-02', 'Urdaneta', 'TMX honda', 'plate_no...', 'dec1210', 'sergeant', '', '2334', 'Failure to Signal Movement', 1, 2, 0, '1st offense'),
(69, 1000, '', '', 'PAquiz', '2025-03-04 00:29:00', 'Pending', '600', 'Denmark Cucal', 'Alaminos City', '1000', '2025-02-23', 'Umingan', 'mio sporty', 'HPY-886857', '09834yuhtg', 'sergeant10000', '', '2445', 'Riding a motorcycle without helmet', 0, 17, 0, '2nd offense'),
(70, 1000, '', '', 'JOHNDEL', '2025-03-04 00:40:00', 'Status', '500', 'Denmark Cucal', 'Alaminos', '1000', '2025-03-04', 'Manaoag', 'TMX honda', '777777878090', 'dec1210', 'spo5555', '', '23', 'Obstruction', 0, 19, 0, '2nd offense'),
(71, 342, '', '', 'JOHNDEL', '2025-03-14 01:02:00', 'Disputed', '500', 'Denmark Cucal', 'Alaminos', '1000', '2025-03-05', 'Manaoag', 'TMX honda', '777777878090', 'dec1210', 'spo5555', '', '23', 'Operating out at line', 0, 19, 0, '3rd offense'),
(72, 0, '', '', 'JOHNDEL', '2025-03-06 01:02:00', 'Pending', '500', 'Denmark Cucal', 'Alaminos', '1000', '2025-03-06', 'Manaoag', 'TMX honda', '777777878090', 'dec1210', 'spo5555', '', '23', 'Dazzling light', 0, 19, 0, ''),
(73, 0, '', '', 'dHEA', '2025-03-04 01:05:00', 'Pending', '500', 'Kyle', 'TAR', '10', '2025-03-03', 'URD', 'IHTH12', 'JNDJE', 'JWFI', 'HR', '', '23', 'Dazzling light', 0, 19, 0, '1st offense'),
(74, 0, '', '', 'dHEA', '2025-03-04 01:06:00', 'Pending', '500', 'Kyle', 'TAR', '10', '2025-03-02', 'URD', 'IHTH12', 'JNDJE', 'JWFI', 'HR', '', '23', 'Dazzling light', 0, 19, 0, '2nd offense'),
(75, 0, '', '', 'dHEA', '2025-03-04 01:06:00', 'Pending', '500', 'Kyle', 'TAR', '10', '2025-02-23', 'URD', 'IHTH12', 'JNDJE', 'JWFI', 'HR', '', '23', 'Dazzling light', 0, 19, 0, '3rd offense'),
(76, 0, '', '', 'dHEA', '2025-03-04 01:06:00', 'Status', '500', 'Kyle', 'TAR', '10', '2025-03-04', 'URD', 'IHTH12', 'JNDJE', 'JWFI', 'HR', '', '23', 'Dazzling light', 0, 19, 0, ''),
(77, 12345, '', '', 'julius', '2025-03-04 03:10:00', 'Pending', '300', 'grail', 'anonas', 'n/a', '2025-02-24', 'camantiles', 'TMX honda', 'JNDJE', 'JWFI', 'spo1', '', '143', 'Dazzling light', 0, 21, 0, '1st offense'),
(78, 10101010, '', '', 'HARVEY', '2025-03-04 11:30:00', 'Resolved', '1000', 'gokusa', 'namek', '0987', '2025-03-04', 'fdfdsfs', 'sffsfs', '54545', '45454', 'SGT', '', '1234', 'Dazzling light', 1, 22, 0, '1st offense'),
(79, 10101010, '', '', 'HARVEY', '2025-03-04 12:54:00', 'Status', '1000', '1ABC', 'namek', '290120912129', '2025-03-04', 'fdfdsfs', 'sffsfs', '54545', '45454', 'SGT', '', '1234', 'Dazzling light', 0, 22, 0, '1st offense');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_type` (`user_type`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`violation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `violations`
--
ALTER TABLE `violations`
  MODIFY `violation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
