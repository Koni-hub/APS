-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Dec 10, 2024 at 12:04 PM
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
-- Database: `shermelle_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Single House A'),
(2, 'Single House B'),
(3, 'Single House C'),
(4, 'Single House D'),
(5, 'Single House E'),
(6, 'Single House F'),
(7, 'Single House G'),
(8, 'Single House H'),
(9, 'Family House A'),
(10, 'Family House B'),
(11, 'Family House C'),
(12, 'Family House D'),
(13, 'Family House E'),
(14, 'Family House F'),
(15, 'Family House G'),
(16, 'Family House H');

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE `houses` (
  `id` int(30) NOT NULL,
  `house_no` varchar(50) DEFAULT NULL,
  `category_id` int(30) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` double DEFAULT NULL,
  `NumberOfRooms` int(30) DEFAULT NULL,
  `roomPrefixName` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`id`, `house_no`, `category_id`, `description`, `price`, `NumberOfRooms`, `roomPrefixName`) VALUES
(1, '1', 1, 'A cozy studio with a compact kitchen and a bright, large window overlooking the city.', 5000, 1, 'SH'),
(2, '2', 2, 'A minimalist single-room apartment featuring sleek hardwood floors and neutral-toned walls.  ', 5000, 1, 'SHB'),
(3, '3', 3, ' A modern studio with an open layout, built-in shelves, and a small balcony. ', 5000, 1, 'SHC'),
(4, '4', 4, 'A warm, carpeted studio with plenty of natural light and a quaint reading nook. ', 5000, 1, 'SHD'),
(5, '5', 5, 'A single room with an industrial vibe, exposed brick walls, and a polished concrete floor. ', 5000, 1, 'SHE'),
(6, '6', 6, 'A serene space with pale blue walls, white curtains, and a corner for a workspace.  ', 5000, 1, 'SHF'),
(7, '7', 7, 'A single-room apartment with a clever loft bed, maximizing space for living and dining. ', 5000, 1, 'SHG'),
(8, '8', 8, ' A cozy studio featuring a kitchenette tucked into one corner and a small dining area.', 5000, 1, 'SHH'),
(9, '9', 9, 'A bright single room with light beige walls and a built-in Murphy bed to save space.  ', 7000, 3, 'FHA'),
(10, '10', 10, 'A single-room apartment with modern lighting fixtures and a spacious walk-in closet.  ', 7000, 3, 'FHB'),
(11, '11', 11, 'A chic studio with large floor-to-ceiling windows and a small, tucked-away kitchen.', 7000, 3, 'FHC'),
(12, '12', 12, 'A rustic studio featuring wooden beams on the ceiling and a wall-mounted foldable desk.  ', 7000, 3, 'FHD'),
(13, '13', 13, 'A vibrant single room with bold accent walls, colorful décor, and a cozy lounge area.  ', 7000, 3, 'FHE'),
(14, '14', 14, 'A tiny but efficient apartment with open shelving, a minimalist kitchen, and a fold-down table.  ', 7000, 3, 'FHF');

-- --------------------------------------------------------

--
-- Table structure for table `house_images`
--

CREATE TABLE `house_images` (
  `id` int(11) NOT NULL,
  `house_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `house_images`
--

INSERT INTO `house_images` (`id`, `house_id`, `image_path`, `room_name`, `is_primary`) VALUES
(1, 1, 'uploads/1_primary_1  1.jpg', NULL, 1),
(2, 1, 'uploads/1_room_parking.jpg', 'Parking', 0),
(3, 1, 'uploads/1_room_front.jpg', 'Front View', 0),
(4, 2, 'uploads/2_primary_2 2.jpg', NULL, 1),
(5, 2, 'uploads/2_room_parking.jpg', 'Parking', 0),
(6, 2, 'uploads/2_room_front.jpg', 'Front View', 0),
(7, 3, 'uploads/3_primary_462571246_543412068517853_9064964232269369689_n.jpg', NULL, 1),
(8, 3, 'uploads/3_room_parking.jpg', 'Parking', 0),
(9, 3, 'uploads/3_room_front.jpg', 'Front View', 0),
(10, 4, 'uploads/4_primary_466695969_610224114692675_5957650750637351793_n.jpg', NULL, 1),
(11, 4, 'uploads/4_room_parking.jpg', 'Parking', 0),
(12, 4, 'uploads/4_room_front.jpg', 'Front View', 0),
(13, 5, 'uploads/5_primary_462568978_930143978629604_2556077630272874787_n.jpg', NULL, 1),
(14, 5, 'uploads/5_room_parking.jpg', 'Parking', 0),
(15, 5, 'uploads/5_room_front.jpg', 'Front View', 0),
(16, 6, 'uploads/6_primary_467473717_595512812868375_3927618086582692763_n.jpg', NULL, 1),
(17, 6, 'uploads/6_room_parking.jpg', 'Parking', 0),
(18, 6, 'uploads/6_room_front.jpg', 'Front View', 0),
(19, 7, 'uploads/7_primary_462579555_600852592627532_4419732479846632969_n.jpg', NULL, 1),
(20, 7, 'uploads/7_room_parking.jpg', 'Parking', 0),
(21, 7, 'uploads/7_room_front.jpg', 'Front View', 0),
(22, 8, 'uploads/8_primary_462585765_1743119563177744_3472979633427041285_n.jpg', NULL, 1),
(23, 8, 'uploads/8_room_parking.jpg', 'Parking', 0),
(24, 8, 'uploads/8_room_front.jpg', 'Front View', 0),
(25, 9, 'uploads/9_primary_462567054_934310908760957_933152554261804172_n.jpg', NULL, 1),
(26, 9, 'uploads/9_room_parking.jpg', 'Parking', 0),
(27, 9, 'uploads/9_room_front.jpg', 'Front View', 0),
(28, 10, 'uploads/10_primary_2.jpg', NULL, 1),
(29, 10, 'uploads/10_room_parking.jpg', 'Parking', 0),
(30, 10, 'uploads/10_room_front.jpg', 'Front View', 0),
(31, 11, 'uploads/11_primary_3.jpg', NULL, 1),
(32, 11, 'uploads/11_room_parking.jpg', 'Parking', 0),
(33, 11, 'uploads/11_room_front.jpg', 'Front View', 0),
(34, 12, 'uploads/12_primary_4.jpg', NULL, 1),
(35, 12, 'uploads/12_room_parking.jpg', 'Parking', 0),
(36, 12, 'uploads/12_room_front.jpg', 'Front View', 0),
(37, 13, 'uploads/13_primary_5.jpg', NULL, 1),
(38, 13, 'uploads/13_room_parking.jpg', 'Parking', 0),
(39, 13, 'uploads/13_room_front.jpg', 'Front View', 0),
(40, 14, 'uploads/14_primary_467477957_1803009400527751_7844270064991230232_n.jpg', NULL, 1),
(41, 14, 'uploads/14_room_parking.jpg', 'Parking', 0),
(42, 14, 'uploads/14_room_front.jpg', 'Front View', 0);

-- --------------------------------------------------------

--
-- Table structure for table `inquire`
--

CREATE TABLE `inquire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `ip_address` varchar(250) NOT NULL,
  `unread` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquire`
--

INSERT INTO `inquire` (`id`, `name`, `email`, `phone`, `message`, `created_at`) VALUES
(1, 'Micah Doyle', 'qujoliw@mailinator.com', '+1 (507) 779-5409', 'Ea tempora voluptate', '2024-12-10 10:20:22'),
(2, 'MacKenzie Hood', 'hituv@mailinator.com', '+1 (604) 664-9467', 'Amet est ipsa min', '2024-12-10 10:50:44');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `inquire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `unread` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `tenant_id`, `amount`, `invoice`, `date_created`) VALUES
(1, 1, 5000, '62953', '2024-12-06 17:26:41'),
(2, 4, 7000, '73771', '2024-12-06 18:24:19'),
(3, 10, 7000, '91038', '2024-12-06 18:25:27');

-- --------------------------------------------------------

--
-- Table structure for table `roomtbl_fha`
--

CREATE TABLE `roomtbl_fha` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roomtbl_fha`
--

INSERT INTO `roomtbl_fha` (`id`, `room_name`, `room_status`) VALUES
(1, 'Room_FHA1', 'Occupied'),
(2, 'Room_FHA2', 'Available'),
(3, 'Room_FHA3', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `roomtbl_fhb`
--

CREATE TABLE `roomtbl_fhb` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roomtbl_fhb`
--

INSERT INTO `roomtbl_fhb` (`id`, `room_name`, `room_status`) VALUES
(1, 'Room_FHB1', 'Occupied'),
(2, 'Room_FHB2', 'Available'),
(3, 'Room_FHB3', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `roomtbl_fhc`
--

CREATE TABLE `roomtbl_fhc` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roomtbl_fhc`
--

INSERT INTO `roomtbl_fhc` (`id`, `room_name`, `room_status`) VALUES
(1, 'Room_FHC1', 'Occupied'),
(2, 'Room_FHC2', 'Available'),
(3, 'Room_FHC3', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `roomtbl_fhd`
--

CREATE TABLE `roomtbl_fhd` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roomtbl_fhd`
--

INSERT INTO `roomtbl_fhd` (`id`, `room_name`, `room_status`) VALUES
(1, 'Room_FHD1', 'Occupied'),
(2, 'Room_FHD2', 'Available'),
(3, 'Room_FHD3', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `roomtbl_fhe`
--

CREATE TABLE `roomtbl_fhe` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roomtbl_fhe`
--

INSERT INTO `roomtbl_fhe` (`id`, `room_name`, `room_status`) VALUES
(1, 'Room_FHE1', 'Occupied'),
(2, 'Room_FHE2', 'Available'),
(3, 'Room_FHE3', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `roomtbl_fhf`
--

CREATE TABLE `roomtbl_fhf` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roomtbl_fhf`
--

INSERT INTO `roomtbl_fhf` (`id`, `room_name`, `room_status`) VALUES
(1, 'Room_FHF1', 'Occupied'),
(2, 'Room_FHF2', 'Available'),
(3, 'Room_FHF3', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `roomtbl_sh`
--

CREATE TABLE `roomtbl_sh` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roomtbl_sh`
--

INSERT INTO `roomtbl_sh` (`id`, `room_name`, `room_status`) VALUES
(1, 'Room_SH1', 'Occupied');

-- --------------------------------------------------------

--
-- Table structure for table `roomtbl_shb`
--

CREATE TABLE `roomtbl_shb` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roomtbl_shb`
--

INSERT INTO `roomtbl_shb` (`id`, `room_name`, `room_status`) VALUES
(1, 'Room_SHB1', 'Occupied');

-- --------------------------------------------------------

--
-- Table structure for table `roomtbl_shc`
--

CREATE TABLE `roomtbl_shc` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roomtbl_shc`
--

INSERT INTO `roomtbl_shc` (`id`, `room_name`, `room_status`) VALUES
(1, 'Room_SHC1', 'Occupied');

-- --------------------------------------------------------

--
-- Table structure for table `roomtbl_shd`
--

CREATE TABLE `roomtbl_shd` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roomtbl_shd`
--

INSERT INTO `roomtbl_shd` (`id`, `room_name`, `room_status`) VALUES
(1, 'Room_SHD1', 'Occupied');

-- --------------------------------------------------------

--
-- Table structure for table `roomtbl_she`
--

CREATE TABLE `roomtbl_she` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roomtbl_she`
--

INSERT INTO `roomtbl_she` (`id`, `room_name`, `room_status`) VALUES
(1, 'Room_SHE1', 'Occupied');

-- --------------------------------------------------------

--
-- Table structure for table `roomtbl_shf`
--

CREATE TABLE `roomtbl_shf` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roomtbl_shf`
--

INSERT INTO `roomtbl_shf` (`id`, `room_name`, `room_status`) VALUES
(1, 'Room_SHF1', 'Occupied');

-- --------------------------------------------------------

--
-- Table structure for table `roomtbl_shg`
--

CREATE TABLE `roomtbl_shg` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roomtbl_shg`
--

INSERT INTO `roomtbl_shg` (`id`, `room_name`, `room_status`) VALUES
(1, 'Room_SHG1', 'Occupied');

-- --------------------------------------------------------

--
-- Table structure for table `roomtbl_shh`
--

CREATE TABLE `roomtbl_shh` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `room_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roomtbl_shh`
--

INSERT INTO `roomtbl_shh` (`id`, `room_name`, `room_status`) VALUES
(1, 'Room_SHH1', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_img` text NOT NULL,
  `about_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `cover_img`, `about_content`) VALUES
(1, 'Sharmelle Apartment Management System', 'info@sample.comm', '+6948 8542 623', '1603344720_1602738120_pngtree-purple-hd-business-banner-image_5493.jpg', '&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; font-weight: 400; text-align: justify;&quot;&gt;&amp;nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&rsquo;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.&lt;/span&gt;&lt;br&gt;&lt;/p&gt;&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;br&gt;&lt;/p&gt;&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(30) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `house_id` int(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0= inactive',
  `date_in` date NOT NULL,
  `room_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `firstname`, `middlename`, `lastname`, `email`, `contact`, `house_id`, `status`, `date_in`, `room_id`) VALUES
(1, 'Kristine', 'S.', 'Dela Cruz', 'tine@gmail.com', '09765434565', 1, 1, '2024-12-06', 'Room_SH1'),
(2, 'Jen', '', 'Lumano', 'Jen@gmail.com', '09765434565', 9, 1, '2024-12-06', 'Room_FHA1'),
(3, 'Bryan', '', 'Rigor', 'Bryan@gmail.com', '09231632623', 10, 1, '2024-12-06', 'Room_FHB1'),
(4, 'Cian', '', 'Canaria', 'Cian', '09876754321', 11, 1, '2024-12-06', 'Room_FHC1'),
(5, 'Jona', '', 'Ebarona', 'Jona@gmail.com', '09134725231', 2, 1, '2024-12-06', 'Room_SHB1'),
(6, 'Michelle', '', 'Lamorena', 'Lamorena@gmail.com', '09231632623', 3, 1, '2024-12-06', 'Room_SHC1'),
(7, 'Jocelyn', '', 'Duculan', 'jocelyn@gmail.com', '09876754321', 4, 1, '2024-12-06', 'Room_SHD1'),
(8, 'Ashaman', '', 'Dhip', 'Ashaman@gmail.com', '09134725231', 5, 1, '2024-12-06', 'Room_SHE1'),
(9, 'Marlon', '', 'Brandon', 'Marlon', '09134725231', 6, 1, '2024-12-06', 'Room_SHF1'),
(10, 'Remmelyn', '', 'Velasteros', 'lyn@gmail.com', '09134725231', 12, 1, '2024-12-06', 'Room_FHD1'),
(11, 'ian', '', 'flores', 'ian@gmail.com', '09134725231', 13, 1, '2024-12-06', 'Room_FHE1'),
(12, 'Reymart', '', 'Mangila', 'reymartmangila@gmail.com', '09134725231', 7, 1, '2024-12-07', 'Room_SHG1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=Admin,2=Staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`) VALUES
(1, 'Koni', 'Koni', 'c6ecdaa4f0273d06f67f5e60219818e7', 0),
(3, 'Loveleequilbio', 'Love', '63d9d7b9202679a4adef678a128c88af', 0),
(5, 'Francesca', 'Chesca', '1836071b9fff822ae63d9f83f79c900d', 1),
(6, 'Roxie', 'Roxie123', '196f93acdc31588cd8436dad490bd164', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_audit_trails`
--

CREATE TABLE `user_audit_trails` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `device_info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_audit_trails`
--

INSERT INTO `user_audit_trails` (`id`, `user_id`, `username`, `login_time`, `ip_address`, `device_info`) VALUES
(1, 1, 'tine@gmail.com', '2024-12-06 17:16:39', '2001:4451:1120:7e00:2567:de41:f892:5aab', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
(2, 1, 'tine@gmail.com', '2024-12-06 17:26:54', '2001:4451:1120:7e00:2567:de41:f892:5aab', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
(3, 3, 'Love', '2024-12-06 17:40:42', '2001:4451:1120:7e00:2567:de41:f892:5aab', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
(4, 1, 'tine@gmail.com', '2024-12-06 18:26:26', '2001:4451:1120:7e00:2567:de41:f892:5aab', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
(5, 1, 'tine@gmail.com', '2024-12-06 18:26:26', '2001:4451:1120:7e00:2567:de41:f892:5aab', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
(6, 3, 'Love', '2024-12-07 04:39:23', '110.54.154.154', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
(7, 5, 'Chesca', '2024-12-07 04:40:42', '110.54.154.154', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
(8, 3, 'Love', '2024-12-07 04:48:58', '216.247.89.111', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
(9, 3, 'Love', '2024-12-07 07:10:50', '110.54.154.154', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
(10, 3, 'Love', '2024-12-08 05:46:34', '2001:1c00:181e:e100:425e:813f:3021:1a8', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Mobile Safari/537.36'),
(11, 3, 'Love', '2024-12-08 05:47:02', '2001:1c00:181e:e100:425e:813f:3021:1a8', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Mobile Safari/537.36'),
(12, 3, 'Love', '2024-12-08 08:19:08', '119.95.119.16', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
(13, 3, 'Love', '2024-12-09 00:39:25', '124.105.29.222', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
(14, 1, 'tine@gmail.com', '2024-12-09 00:43:25', '124.105.29.222', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'),
(15, 1, 'Koni', '2024-12-10 10:28:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 OPR/115.0.0.0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `houses`
--
ALTER TABLE `houses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `house_images`
--
ALTER TABLE `house_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `house_id` (`house_id`);

--
-- Indexes for table `inquire`
--
ALTER TABLE `inquire`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtbl_fha`
--
ALTER TABLE `roomtbl_fha`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtbl_fhb`
--
ALTER TABLE `roomtbl_fhb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtbl_fhc`
--
ALTER TABLE `roomtbl_fhc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtbl_fhd`
--
ALTER TABLE `roomtbl_fhd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtbl_fhe`
--
ALTER TABLE `roomtbl_fhe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtbl_fhf`
--
ALTER TABLE `roomtbl_fhf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtbl_sh`
--
ALTER TABLE `roomtbl_sh`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtbl_shb`
--
ALTER TABLE `roomtbl_shb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtbl_shc`
--
ALTER TABLE `roomtbl_shc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtbl_shd`
--
ALTER TABLE `roomtbl_shd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtbl_she`
--
ALTER TABLE `roomtbl_she`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtbl_shf`
--
ALTER TABLE `roomtbl_shf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtbl_shg`
--
ALTER TABLE `roomtbl_shg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtbl_shh`
--
ALTER TABLE `roomtbl_shh`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_audit_trails`
--
ALTER TABLE `user_audit_trails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `house_images`
--
ALTER TABLE `house_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `inquire`
--
ALTER TABLE `inquire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roomtbl_fha`
--
ALTER TABLE `roomtbl_fha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roomtbl_fhb`
--
ALTER TABLE `roomtbl_fhb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roomtbl_fhc`
--
ALTER TABLE `roomtbl_fhc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roomtbl_fhd`
--
ALTER TABLE `roomtbl_fhd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roomtbl_fhe`
--
ALTER TABLE `roomtbl_fhe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roomtbl_fhf`
--
ALTER TABLE `roomtbl_fhf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roomtbl_sh`
--
ALTER TABLE `roomtbl_sh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roomtbl_shb`
--
ALTER TABLE `roomtbl_shb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roomtbl_shc`
--
ALTER TABLE `roomtbl_shc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roomtbl_shd`
--
ALTER TABLE `roomtbl_shd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roomtbl_she`
--
ALTER TABLE `roomtbl_she`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roomtbl_shf`
--
ALTER TABLE `roomtbl_shf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roomtbl_shg`
--
ALTER TABLE `roomtbl_shg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roomtbl_shh`
--
ALTER TABLE `roomtbl_shh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_audit_trails`
--
ALTER TABLE `user_audit_trails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `house_images`
--
ALTER TABLE `house_images`
  ADD CONSTRAINT `house_images_ibfk_1` FOREIGN KEY (`house_id`) REFERENCES `houses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
