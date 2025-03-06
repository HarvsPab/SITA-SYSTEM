-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2025 at 07:23 AM
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
(165, 'HARVEY', 'User login successful', '2025-03-04 05:24:47'),
(166, 'HARVEY', 'User  logout', '2025-03-04 05:27:28'),
(167, 'admin123', 'Admin created new user: VEGETA', '2025-03-04 05:28:06'),
(168, 'VEGETA', 'User login successful', '2025-03-04 05:28:18'),
(169, 'admin123', 'User  logout', '2025-03-04 06:27:07'),
(170, 'admin123', 'Failed login attempt', '2025-03-04 06:27:17'),
(171, 'admin123', 'User login successful', '2025-03-04 06:27:26'),
(172, 'VEGETA', 'User  logout', '2025-03-04 06:43:36'),
(173, 'ZkingSaiyan', 'Login attempt with non-existent username', '2025-03-04 06:43:44'),
(174, 'HARVEY', 'User login successful', '2025-03-04 06:43:54'),
(175, 'HARVEY', 'User  logout', '2025-03-04 07:06:50'),
(176, 'HARVEY', 'User login successful', '2025-03-04 07:06:59'),
(177, 'admin123', 'User  logout', '2025-03-04 07:07:15'),
(178, 'admin123', 'User login successful', '2025-03-04 07:07:24'),
(179, 'HARVEY', 'User  logout', '2025-03-04 07:07:46'),
(180, 'HARVEY', 'Failed login attempt', '2025-03-04 07:07:58'),
(181, 'HARVEY', 'User login successful', '2025-03-04 07:08:07'),
(182, 'admin123', 'Admin created new user: phil', '2025-03-04 07:21:01'),
(183, 'HARVEY', 'User  logout', '2025-03-04 07:21:06'),
(184, 'phil', 'User login successful', '2025-03-04 07:21:15'),
(185, 'admin123', 'User  logout', '2025-03-04 11:45:05'),
(186, 'HARVEY', 'User login successful', '2025-03-04 11:45:16'),
(187, 'HARVEY', 'User  logout', '2025-03-04 11:46:29'),
(188, 'VEGETA', 'User login successful', '2025-03-04 11:46:39'),
(189, 'VEGETA', 'User  logout', '2025-03-04 11:46:49'),
(190, 'admin123', 'User login successful', '2025-03-04 11:47:19'),
(191, 'admin123', 'User  logout', '2025-03-04 12:26:31'),
(192, 'admin123', 'Failed login attempt', '2025-03-04 12:26:39'),
(193, 'admin123', 'Failed login attempt', '2025-03-04 12:26:50'),
(194, 'admin123', 'User login successful', '2025-03-04 12:27:11'),
(195, 'admin123', 'User  logout', '2025-03-04 12:27:29'),
(196, 'admin123', 'User login successful', '2025-03-04 12:27:37'),
(197, 'admin123', 'User  logout', '2025-03-04 12:52:10'),
(198, 'admin123', 'User login successful', '2025-03-04 12:52:23'),
(199, 'admin123', 'User  logout', '2025-03-04 12:52:28'),
(200, 'admin', 'Login attempt with non-existent username', '2025-03-04 12:52:40'),
(201, 'admin123', 'User login successful', '2025-03-04 12:52:50'),
(202, 'phil', 'User  logout', '2025-03-04 12:53:51'),
(203, 'admin123', 'User  logout', '2025-03-04 13:03:43'),
(204, 'admin123', 'User login successful', '2025-03-04 13:09:25'),
(205, 'admin123', 'User  logout', '2025-03-04 13:09:29'),
(206, 'admin123', 'User login successful', '2025-03-04 13:09:44'),
(207, 'admin123', 'User  logout', '2025-03-04 13:11:49'),
(208, 'admin123', 'User login successful', '2025-03-04 13:13:33'),
(209, 'admin123', 'User  logout', '2025-03-04 13:18:48'),
(210, 'admin123', 'User login successful', '2025-03-04 13:19:18'),
(211, 'HARVEY', 'User login successful', '2025-03-04 13:19:53'),
(212, 'HARVEY', 'User  logout', '2025-03-04 13:19:56'),
(213, 'HARVEY', 'User login successful', '2025-03-04 13:20:08'),
(214, 'HARVEY', 'User  logout', '2025-03-04 13:20:37'),
(215, 'admin123', 'User  logout', '2025-03-04 13:20:47'),
(216, 'admin123', 'User login successful', '2025-03-04 13:26:27'),
(217, 'admin123', 'User  logout', '2025-03-04 13:26:35'),
(218, 'admin123', 'User login successful', '2025-03-04 13:26:46'),
(219, 'admin123', 'User  logout', '2025-03-04 13:26:52'),
(220, 'admin123', 'User login successful', '2025-03-04 13:28:57'),
(221, 'admin123', 'User  logout', '2025-03-04 14:02:29'),
(222, 'admin123', 'User login successful', '2025-03-04 14:02:41'),
(223, 'admin123', 'User  logout', '2025-03-04 14:02:59'),
(224, 'admin123', 'User login successful', '2025-03-04 14:03:11'),
(225, 'HARVEY', 'User login successful', '2025-03-04 14:03:50'),
(226, 'HARVEY', 'User  logout', '2025-03-04 14:39:28'),
(227, 'HARVEY', 'User login successful', '2025-03-04 14:39:36'),
(228, 'HARVEY', 'User  logout', '2025-03-04 14:42:09'),
(229, 'HARVEY', 'User login successful', '2025-03-04 14:42:24'),
(230, 'HARVEY', 'User  logout', '2025-03-04 14:42:57'),
(231, 'HARVEY', 'User login successful', '2025-03-04 14:43:13'),
(232, 'HARVEY', 'User  logout', '2025-03-04 14:44:59'),
(233, 'HARVEY', 'User login successful', '2025-03-04 14:45:08'),
(234, 'HARVEY', 'User  logout', '2025-03-04 14:45:17'),
(235, 'HARVEY', 'User login successful', '2025-03-04 14:45:34'),
(236, 'HARVEY', 'User  logout', '2025-03-04 14:47:35'),
(237, 'HARVEY', 'User login successful', '2025-03-04 14:47:51'),
(238, 'HARVEY', 'User  logout', '2025-03-04 14:50:16'),
(239, 'HARVEY', 'Failed login attempt', '2025-03-04 14:50:24'),
(240, 'HARVEY', 'User login successful', '2025-03-04 14:50:33'),
(241, 'HARVEY', 'User  logout', '2025-03-04 14:54:02'),
(242, 'HARVEY', 'User login successful', '2025-03-04 14:54:13'),
(243, 'admin123', 'User  logout', '2025-03-04 14:55:06'),
(244, 'admin123', 'User login successful', '2025-03-04 14:55:14'),
(245, 'admin123', 'User  logout', '2025-03-04 14:57:27'),
(246, 'admin123', 'User login successful', '2025-03-04 14:57:38'),
(247, 'admin123', 'User  logout', '2025-03-04 14:58:45'),
(248, 'admin123', 'User login successful', '2025-03-04 14:58:53'),
(249, 'admin123', 'User  logout', '2025-03-04 14:58:58'),
(250, 'admin123', 'User login successful', '2025-03-04 14:59:06'),
(251, 'admin123', 'User  logout', '2025-03-04 15:02:17'),
(252, 'admin123', 'User login successful', '2025-03-04 15:02:30'),
(253, 'admin123', 'User  logout', '2025-03-04 15:04:06'),
(254, 'admin123', 'User login successful', '2025-03-04 15:04:15'),
(255, 'admin123', 'User  logout', '2025-03-04 15:05:42'),
(256, 'admin123', 'User login successful', '2025-03-04 15:05:51'),
(257, 'admin123', 'User  logout', '2025-03-04 15:06:29'),
(258, 'admin123', 'User login successful', '2025-03-04 15:06:36'),
(259, 'admin123', 'Admin created new user: Zeno', '2025-03-04 15:07:21'),
(260, 'HARVEY', 'User  logout', '2025-03-04 15:07:56'),
(261, 'Zeno', 'User login successful', '2025-03-04 15:08:08'),
(262, 'admin123', 'User  logout', '2025-03-04 15:18:54'),
(263, 'admin123', 'User login successful', '2025-03-04 15:19:03'),
(264, 'admin123', 'User  logout', '2025-03-04 15:24:20'),
(265, 'admin123', 'User login successful', '2025-03-04 15:24:29'),
(266, 'Zeno', 'User  logout', '2025-03-04 15:29:35'),
(267, 'HARVEY', 'User login successful', '2025-03-04 15:29:45'),
(268, 'HARVEY', 'User  logout', '2025-03-04 15:55:17'),
(269, 'HARVEY', 'User login successful', '2025-03-04 15:55:29'),
(270, 'HARVEY', 'User  logout', '2025-03-04 16:11:16'),
(271, 'Zeno', 'User login successful', '2025-03-04 16:12:50'),
(272, 'Zeno', 'User  logout', '2025-03-04 16:30:07'),
(273, 'Zeno', 'User login successful', '2025-03-04 16:30:21'),
(274, 'Zeno', 'User  logout', '2025-03-04 16:39:41'),
(275, 'Zeno', 'User login successful', '2025-03-04 16:39:51'),
(276, 'Zeno', 'User  logout', '2025-03-05 04:29:28'),
(277, 'HARVEY', 'User login successful', '2025-03-05 04:30:13'),
(278, 'admin123', 'User  logout', '2025-03-05 04:41:21'),
(279, 'admin123', 'User login successful', '2025-03-05 04:41:33'),
(280, 'admin123', 'User  logout', '2025-03-05 04:43:08'),
(281, 'admin123', 'User login successful', '2025-03-05 04:43:19'),
(282, 'admin123', 'Admin created new user: Beerus', '2025-03-05 05:57:33'),
(283, 'HARVEY', 'User  logout', '2025-03-05 05:57:46'),
(284, 'Beerus', 'User login successful', '2025-03-05 05:58:04'),
(285, 'admin123', 'User  logout', '2025-03-06 04:28:07'),
(286, 'admin123', 'Login attempt with non-existent username', '2025-03-06 04:28:16'),
(287, 'SITA', 'User login successful', '2025-03-06 04:28:29'),
(288, 'Beerus', 'User  logout', '2025-03-06 04:49:48'),
(289, 'beerus', 'Login attempt with non-existent username', '2025-03-06 04:49:58');

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

-- --------------------------------------------------------

--
-- Table structure for table `ordinances`
--

CREATE TABLE `ordinances` (
  `ordinance_title` varchar(500) NOT NULL,
  `ordinance_desc` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordinances`
--

INSERT INTO `ordinances` (`ordinance_title`, `ordinance_desc`) VALUES
('Municipal Ordinance No. 16 Series 2021 ', 'AN ORDINANCE PERMANENTLY CLOSING TO ANY VEHICULAR TRAFFIC BOTH LANES OF L. MILLAN STREET, ASINGAN, PANGASINAN '),
('Municipal Ordinance No. 18 Series 2021 ', 'AN ORDINANCE IMPLEMENTING R.A. 10754, AN ACT EXPANDING THE BENEFITS AND PRIVILEGES OF PERSONS WITH DISABILITY (PWD) IN THE MUNICIPALITY OF ASINGAN, PANGASINAN '),
('Municipal Ordinance No. 19 Series 2021 ', 'AN ORDINANCE HONORING AND GRANTING CASH INCENTIVES TO ASINGAN CENTENARIANS AND PROVIDING FUNDS THEREFOR '),
('Municipal Ordinance No.5 Series 2015 ', 'AN ORDINANCE ADOPTING THE GENDER AND DEVELOPMENT CODE OF THE MUNICIPALITY OF ASINGAN, PANGASINAN AND FOR OTHER PURPOSES '),
('Municipal Ordinance No. 3 Series 2013 ', 'AN ORDINANCE CELEBRATING ARBOR DAY ON THE 14TH AUGUST OF EVERY YEAR IN THE MUNICIPALITY OF ASINGAN, PANGASINAN '),
('Municipal Ordinance No. 3 Series 2012 ', 'HEALTH AND SANITATION CODE OF THE MUNICIPALITY OF ASINGAN, PANGASINAN '),
('Municipal Ordinance No. 1 Series 2011 ', 'AN ORDINANCE BANNING THE OPERATIONS OF ALL KINDS OF BUSINESS ESTABLISHMENTS, THE LANE IMMEDIATELY FRONTING THE ST. LOUIS BERTRAND, ROMAN CATHOLIC CHURCH DURING TOWN FIESTA '),
('Municipal Ordinance No. 1 Series 2011 ', 'AN ORDINANCE CREATING THE ASINGAN MUNICIPAL HOUSING BOARD, DEFINING ITS CLEARING HOUSE FUNCTIONS PURSUANT TO EXECUTIVE ORDER NO. 708, S.2008 AND FOR OTHER PURPOSES. '),
('Municipal Ordinance No. 2 Series 2011 ', 'AN ORDINANCE IMPOSING FARE RATE INCREASE FOR MOTORIZED TRICYCLES PLYING THEIR ROUTE WITHIN THE TERRITORIAL JURISDICTION OF THE MUNICIPALITY OF ASINGAN, PANGASINAN '),
('Municipal Ordinance No. 7 Series 2010 ', 'REVISED REVENUE CODE OF THE MUNICIPALITY OF ASINGAN PROVINCE OF PANGASINAN '),
('Municipal Ordinance No. 3 Series 2008 ', 'AN ORDINANCE PROMOTING THE USE OF FORTIFIED FOODS IN ASINGAN, PANGASINAN '),
('Ordinance No. 325-2024 Approved on July 1 2024', 'AN ORDINANCE REQUIRING INCREASED NIGHTTIME VISIBILITY AMONG MOTORCYCLE, TRICYCLE, BICYCLE, E-TRIKE AND E-BIKE RIDERS THROUGH THE USE OF HIGH-VISIBILITY REFLECTIVE VESTS FOR ALL MOTORCYCLE, AND TRICYCLE RIDERS AND REFLECTIVE VESTS AND/OR HIGH-VISIBILITY LIGHTS FOR BICYCLE AND E-BIKE AND E-TRIKE RIDERS WHILE TRAVELING ALONG THE THOROUGHFARES OF THE PROVINCE OF PANGASINAN FROM 6 P.M. TO 6 A.M. AND PROVIDING PENALTIES THEREOF '),
('Ordinance No. 322-2024 Approved on April 15 2024', 'AN ORDINANCE PROHIBITING THE LITTERING, THROWING OF GARBAGE, FILTH AND OTHER WASTE MATTERS IN OPEN OR PUBLIC SPACES WITHIN THE CAPITOL COMPLEX AND PROVIDING PENALTIES THEREOF'),
('Ordinance No. 317-2024 Approved on January 29 2024', 'AN ORDINANCE REGULATING THE USE, SALE, DISTRIBUTION, AND ADVERTISEMENT OF CIGARETTES AND OTHER TOBACCO PRODUCTS, ELECTRONIC NICOTINE AND NON-NICOTINE DELIVERY SYSTEMS, HEATED TOBACCO PRODUCTS AND OTHER NOVEL TOBACCO PRODUCTS, IN CERTAIN PLACES, IMPOSING PENALTIES FOR VIOLATIONS THEREOF AND PROVIDING FUNDS THEREFOR, TO INSTILL HEALTH CONSCIOUSNESS AND FOR OTHER PURPOSES'),
('Ordinance No. 299-2023 Approved on May 22 2023', 'AN ORDINANCE MANDATING ALL TELECOMMUNICATION COMPANIES, SERVICE PROVIDERS AND OTHER SIMILAR ENTITIES THAT USE OVERHEAD WIRES AND/OR CABLES OPERATING WITHIN THE PROVINCE OF PANGASINAN TO INSTALL AND/OR RELOCATE WIRES AND CABLE SYSTEM.'),
('Ordinance No. 297-2023 Approved on March 20 2023', 'AN ORDINANCE AMENDING PROVINCIAL TAX ORDINANCE NO. 1-2022 PARTICULARLY SECTION 38 THEREOF BY CHANGING THE ROAD MAINTENANCE FEE IMPOSED UNDER OTHER FEES'),
('Ordinance No. 291-2022 Approved on December 20 2022', 'AN ORDINANCE REGULATING THE HAULING AND TRANSPORTING OF SAND, GRAVEL, AND OTHER QUARRY MATERIALS WITHIN THE PROVINCE OF PANGASINAN'),
('Ordinance No. 284-2022 Approved on November 7 2022', 'AN ORDINANCE ADOPTING THE LOCAL PUBLIC TRANSPORT ROUTE PLAN (LPTRP) OF THE PROVINCE OF PANGASINAN'),
('Ordinance No. 263-2021 Approved on April 7 2021', 'AN ORDINANCE AMENDING THE CURFEW ORDINANCE OF THE PROVINCE OF PANGASINAN BY IMPOSING CURFEW FROM EIGHT O’CLOCK IN THE EVENING (8:00 PM) TO FOUR O’CLOCK IN THE MORNING (4:00 AM) PURSUANT TO EXECUTIVE ORDER NO. 0030-2021 OF THE PROVINCIAL GOVERNOR, AMADO I. ESPINO III, AND PROVIDING A SUPPLEMENTARY PROVISION THERETO'),
('Ordinance No. 260-2021 Approved on March 1 2021', 'AN ORDINANCE PROHIBITING ANY OBSTRUCTION ON PUBLIC ROADS AND OTHER SIMILAR PUBLIC PLACES WITHIN THE TERRITORIAL JURISDICTION OF THE PROVINCE OF PANGASINAN AND PROVIDING PENALTIES THEREOF'),
('Ordinance No. 252-2020 Approved on December 7 2020', 'AN ORDINANCE AMENDING PROVINCIAL ORDINANCE NO. 249-2020 BY IMPOSING CURFEW IN THE PROVINCE OF PANGASINAN FROM EIGHT O’CLOCK IN THE EVENING (8:00 PM) TO FOUR O’CLOCK IN THE MORNING (4:00 AM) TO NINE O’CLOCK IN THE EVENING (9:00 PM) TO FOUR O’CLOCK IN THE MORNING (4:00 AM)'),
('Ordinance No. 244-2020 Approved on October 19 2020', 'AN ORDINANCE ESTABLISHING A GENDER-BASED VIOLENCE AND RAPE CASES RESPONSE MECHANISM AND PROTOCOL IN HADLING GENDER BASED VIOLENCE AND RAPE CASES IN THE PROVINCE OF PANGASINAN'),
('Ordinance No. 234-2020 Approved on February 10 2020', 'AN ORDINANCE RATIFYING THE LOAN AGREEMENT AND OTHER SUPPORTING DOCUMENTS PERTAINING TO THE LOAN ENTERED INTO BY AND BETWEEN THE PROVINCIAL GOVERNMENT OF PANGASINAN REPRESENTED BY HON. AMADO I. ESPINO III AND THE LANDBANK OF THE PHILIPPINES REPRESENTED BY ITS HEAD, PANGASINAN LENDING CENTER AVP DEMETRIO P. ESPIRITU III IN THE AMOUNT OF NINE HUNDRED FIFTY MILLION PESOS ONLY (P950,000,000.00) TO FINANCE THE CONSTRUCTION OF THE PANGASINAN CONVENTION AND MULTI PURPOSE CENTER (PHASE II), PROCUREMENT OF HOSPITAL EQUIPMENT, PROCUREMENT OF HEAVY AND LIGHT EQUIPMENT, CONSTRUCTION OF VARIOUS GOVERNMENT BUILDINGS AND FACILITIES, PAVING ROADS AND PATHWALKS, AND RECONSTRUCTION OF BRIDGES INCLUDED IN THE APPROVED ANNUAL INVESTMENT PROGRAM FOR CY 2019/2020 AND ELIGIBLE FOR FINANCING.'),
('10. Ordinance No. 208-2017 Approved on April 3 2017', 'AN ORDINANCE STRICTLY PROHIBITING ALCOHOLIC BEVERAGES OR INTOXICATING LIQUORS OF ANY KIND WITHIN THE CAPITOL COMPOUND, INCLUDING THE BEACHFRONT, AND OTHER PUBLIC AREAS OWNED AND BUILT BY THE PROVINCIAL GOVERNMENT AND PROVIDING PENALTIES FOR VIOLATIONS THEREOF AND FOR OTHER PURPOSES'),
('Ordinance No. 206-2017 Approved on February 20 2017', 'AN ORDINANCE ADOPTING THE OFFICIAL SEAL AND THE OFFICIAL FLAG OF THE PROVINCE OF PANGASINAN'),
('Ordinance No. 200-2016 Approved on August 1 2016', 'AN ORDINANCE IMPLEMENTING A MANDATORY RANDOM DRUG TEST PROGRAM IN THE PROVINCE OF PANGASINAN AND APPROPRIATING FUNDS THEREFOR'),
('Ordinance No. 169-2013 Approved on March 25 2013', 'AN ORDINANCE PROHIBITING THE USE OF STYROFOAM AND PLASTIC BAGS ON DRY GOODS AND REGULATING ITS UTILIZATION ON WET GOODS WITHIN THE CAPITOL COMPLEX INCLUDING OTHER PROVINCIAL GOVERNMENT OFFICES OUTSIDE THE CAPITOL COMPLEX AND PRESCRIBING PENALTIES THEREOF'),
('Ordinance No. 170-2013 Approved on March 25 2013', 'AN ORDINANCE REGULATING THE SALE OR SLAUGHTER OF FEMALE CARABAOS (RIVERINE BUFFALOES AND CROSSBREDS) WITHIN THE PROVINCE OF PANGASINAN THEREBY ESTABLISHING A BUY BACK SCHEME FOR FEMALE BREEDABLE CARABAO AND PROVIDING FUNDS THEREFOR'),
('Ordinance No. 168-2013 Approved on March 11 2013', 'AN ORDINANCE INSTITUTIONALIZING THE EMPLOYEES’ HEALTH AND WELLNESS PROGRAMS OF THE PROVINCIAL GOVERNMENT OF PANGASINAN, PROVIDING APPROPRIATIONS FOR THE PURPOSE AND ADOPTING POLICIES TO GOVERN THE PROGRAMS’ IMPLEMENTATION'),
('Ordinance No. 166-2012 Approved on November 12 2012', 'AN ORDINANCE GRANTING INCENTIVES/TAX DISCOUNTS FOR PROMPT PAYMENT OF CURRENT REAL PROPERTY TAXES WITHIN THE TERRITORIAL JURISDICTION OF THE PROVINCE OF PANGASINAN'),
('Ordinance No. 166-2012 Approved on November 12 2012', 'AN ORDINANCE GRANTING INCENTIVES/TAX DISCOUNTS FOR PROMPT PAYMENT OF CURRENT REAL PROPERTY TAXES WITHIN THE TERRITORIAL JURISDICTION OF THE PROVINCE OF PANGASINAN'),
('Ordinance No. 150-2011 Approved on May 16 2011', 'AN ORDINANCE IMPLEMENTING THE “NO PRESCRIPTION, NO DISPENSING OF ANTI-TUBERCULOSIS DRUGS” IN ALL DRUG OUTLETS IN THE PROVINCE OF PANGASINAN AND PROVIDING PENALTIES THEREOF'),
('Ordinance No. 151-2011 Approved on May 16 2011', 'AMENDING PROVINCIAL ORDINANCE NO. 146-2010, AN ORDINANCE ADOPTING THE NEW SCHEDULE OF FAIR AND BASE UNIT MARKET VALUES FOR LANDS AND SCHEDULE OF BASE UNIT CONSTRUCTION COST FOR BUILDINGS AND OTHER STRUCTURES AS INDICATED IN RA 7160 AS BASIS FOR THE CLASSIFICATION, APPRAISAL, AND ASSESSMENT OF REAL PROPERTIES IN THE PROVINCE OF PANGASINAN, AND PRESCRIBING THE SCHEDULE OF IMPLEMENTATIN THEREOF'),
('Ordinance No. 148-2010 Approved on December 17 2010', 'AN ORDINANCE REGULATING THE TRANSPORT OF INCOMING LIVESTOCK AND BUTCHERED ANIMALS IN THE PROVINCE OF PANGASINAN BY IMPOSING INSPECTION FEE THEREOF'),
('Ordinance No. 146-2010 Approved on December 10 2010', 'AN ORDINANCE ADOPTING THE NEW SCHEDULE OF FAIR AND BASE UNIT MARKET VALUES FOR LANDS AND SCHEDULE OF BASE UNIT CONSTRUCTION COST FOR BUILDINGS AND OTHER STRUCTURES AS INDICATED IN RA 7160 AS BASIS FOR THE CLASSIFICATION, APPRAISSAL AND ASSESSTMENT OF REAL PROPERTIES IN THE PROVINCE OF PANGASINAN'),
('Ordinance No. 133-2008 Approved on September 22 2008', 'AN ORDINANCE PROVIDING FOR THE PROVINCE’S ANNUALOBSERVANCE OF THE SENIOR CITIZENS DAY, WHICH WOULD BE CELEBRATED ON THE 7TH DAY OF OCTOBER OF EVERY YEAR'),
('Ordinance No. 129-2007 Approved on February 9 2007', 'AN ORDINANCE REQUIRING EVERY MEDICAL MISSION TO BE CONDUCTED WITHIN THE JURISDICTION OF THE PROVINCE OF PANGASINAN TO GIVE NOTICE AND COORDINATE WITH THE PROVINCIAL HEALTH OFFICE AND/OR CHIEFS OF HOSPITALS OF THE PROVINCIAL, DISTRICT, COMMUNITY/MEDICARE HOSPITALS FOR ANY PROGRAM OR ACTIVITY INVOLVING MINOR AND/OR MAJOR SURGICAL OPERATIONS, MEDICAL OR DENTAL SERVICES, AND PROVIDING PENALTIES FOR THE VIOLATION THEROF');

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
(2, 'SITA', 'SITAASINGANPSADMINAKO', 'admin', 'inactive', NULL, NULL, '2025-02-27 23:22:01', '2025-03-06 12:27:30', '', '', '');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=290;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `violations`
--
ALTER TABLE `violations`
  MODIFY `violation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
