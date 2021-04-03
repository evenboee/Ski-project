-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2021 at 12:23 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skidb`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer_representative`
--

CREATE TABLE `customer_representative` (
  `number` int(50) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `department` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `customer_representative`
--

INSERT INTO `customer_representative` (`number`, `name`, `department`) VALUES
(1, 'Jane Smith', 'Customer Service'),
(2, 'Matt Karensson', 'Customer Service'),
(3, 'Hannibal Barcka', 'Department of Information');

-- --------------------------------------------------------

--
-- Table structure for table `franchise`
--

CREATE TABLE `franchise` (
  `id` int(50) NOT NULL,
  `start_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `end_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `shipping_address` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `buing_price` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `store_info` text COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `franchise`
--

INSERT INTO `franchise` (`id`, `start_date`, `end_date`, `name`, `shipping_address`, `buing_price`, `store_info`) VALUES
(111, '2020-01-02', '2026-01-01', 'XXL Sport', 'Jernbanegata 5, Gjøvik 2821', '50% of MSRPP', 'XXL Sport og Villmark er Nordens største sportskjede. Vi tilbyr et bredt sortiment av kjente merkevarer på nett og i våre mange varehus.'),
(123, '2021-01-01', '2026-01-01', 'Amundsen Sport', 'Sentrumsvegen 7, Oslo 3187', '75% of MSRPP', 'Amundsen Sport ble startet allerede i 1918 av Albert Amundsen – bestefaren til dagens driver.'),
(321, '2018-01-01', '2025-01-01', 'Sportsgutta', 'Tordenskjoldsgate 16, Oslo 3187', '75% of MSRPP', 'Sportsgutta er en av Norges beste sportsbutikker og tilbyr alt innenfor extremsport.');

-- --------------------------------------------------------

--
-- Table structure for table `general_public`
--

CREATE TABLE `general_public` (
  `account_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `general_public`
--

INSERT INTO `general_public` (`account_id`) VALUES
(1),
(2),
(5),
(8),
(65),
(69);

-- --------------------------------------------------------

--
-- Table structure for table `individual_store`
--

CREATE TABLE `individual_store` (
  `id` int(50) NOT NULL,
  `start_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `end_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `shipping_address` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `buying_price` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `individual_store`
--

INSERT INTO `individual_store` (`id`, `start_date`, `end_date`, `name`, `shipping_address`, `buying_price`) VALUES
(665, '2020-01-02', '2026-01-01', 'Oslo Sportslager', 'Karl Johansgate 10, Oslo 3176', '76% of MSRPP'),
(676, '2018-01-01', '2025-01-01', 'Telemarks Helter', 'Mo-Byrtevegen 201b, Dalen 3880', '20% of MSRPP'),
(998, '2021-01-01', '2024-01-01', 'Trondheim Sport', 'Tordenskjoldgate 87, Trondheim 7634', '55% of MSRPP');

-- --------------------------------------------------------

--
-- Table structure for table `keeper_check_order`
--

CREATE TABLE `keeper_check_order` (
  `keeperNo` int(50) NOT NULL,
  `orderNo` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keeper_log`
--

CREATE TABLE `keeper_log` (
  `keeperNo` int(50) NOT NULL,
  `logNo` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `keeper_log`
--

INSERT INTO `keeper_log` (`keeperNo`, `logNo`) VALUES
(1098764, 1),
(1098764, 2);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `logNo` int(50) NOT NULL AUTO_INCREMENT,
  `comment` text COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`logNo`, `comment`) VALUES
(1, 'dummy'),
(2, 'dummy'),
(4, 'dummy'),
(5, 'dummy'),
(6, 'dummy'),
(7, 'dummy');

-- --------------------------------------------------------

--
-- Table structure for table `production_plan`
--

CREATE TABLE `production_plan` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `start_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `end_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `plannerNo` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `production_plan`
--

INSERT INTO `production_plan` (`id`, `start_date`, `end_date`, `plannerNo`) VALUES
(777, '2021-01-01', '2021-01-28', 444),
(778, '2021-01-29', '2021-02-26', 444),
(779, '2021-03-26', '2021-04-23', 445);

-- --------------------------------------------------------

--
-- Table structure for table `production_planner`
--

CREATE TABLE `production_planner` (
  `number` int(50) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `department` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `production_planner`
--

INSERT INTO `production_planner` (`number`, `name`, `department`) VALUES
(444, 'Amund Amundsson', 'Department of Leaders'),
(445, 'Even Bryns Boe ', 'Department of Information');

-- --------------------------------------------------------

--
-- Table structure for table `production_plan_reference`
--

CREATE TABLE `production_plan_reference` (
  `plan_id` int(50) NOT NULL,
  `size` int(50) NOT NULL,
  `weight` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `quantity` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `production_plan_reference`
--

INSERT INTO `production_plan_reference` (`plan_id`, `size`, `weight`, `quantity`) VALUES
(777, 145, '20-30', 2000),
(777, 165, '40-50', 2600),
(778, 150, '30-40', 3400),
(778, 150, '30-50', 1100),
(779, 165, '40-50', 5400);

-- --------------------------------------------------------

--
-- Table structure for table `public_view_ski`
--

CREATE TABLE `public_view_ski` (
  `account_id` int(50) NOT NULL,
  `size` int(50) NOT NULL,
  `weight` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `public_view_ski`
--

INSERT INTO `public_view_ski` (`account_id`, `size`, `weight`) VALUES
(1, 140, '20-30'),
(2, 150, '40-50'),
(5, 140, '40-50'),
(8, 165, '40-50'),
(65, 150, '30-40'),
(69, 145, '20-30'),
(69, 150, '30-40');

-- --------------------------------------------------------

--
-- Table structure for table `rep_log`
--

CREATE TABLE `rep_log` (
  `repNo` int(50) NOT NULL,
  `logNo` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `rep_log`
--

INSERT INTO `rep_log` (`repNo`, `logNo`) VALUES
(1, 4),
(2, 5),
(2, 6),
(3, 7);

-- --------------------------------------------------------

--
-- Table structure for table `rep_review_order`
--

CREATE TABLE `rep_review_order` (
  `repNo` int(50) NOT NULL,
  `orderNo` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

CREATE TABLE `shipment` (
  `number` int(50) NOT NULL AUTO_INCREMENT,
  `store_name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `shipping_address` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `pickup_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `state` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `driver_id` int(50) NOT NULL,
  `repNo` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ski`
--

CREATE TABLE `ski` (
  `product_number` int(50) NOT NULL AUTO_INCREMENT,
  `size` int(50) NOT NULL,
  `weight` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `ski`
--

INSERT INTO `ski` (`product_number`, `size`, `weight`) VALUES
(111, 140, '20-30'),
(222, 140, '20-30'),
(333, 140, '20-30'),
(444, 165, '40-50'),
(555, 165, '40-50'),
(667, 150, '40-50'),
(777, 150, '40-50'),
(888, 150, '40-50'),
(889, 150, '40-50'),
(999, 150, '40-50');

-- --------------------------------------------------------

--
-- Table structure for table `ski_model`
--

CREATE TABLE `ski_model` (
  `model` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `skiing_type` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `description` text COLLATE utf8mb4_danish_ci NOT NULL,
  `grip_system` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `historical` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `temperature` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `url` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `ski_model`
--

INSERT INTO `ski_model` (`model`, `skiing_type`, `description`, `grip_system`, `historical`, `temperature`, `url`) VALUES
('Fisher', 'classic', 'Fisher is the latest model of the Fish ski series, made for casual as well as veteran skiiers. ', 'IntelliGrip', 'no', 'mild', 'http:/coolskiis.com/marketplace/Fisher'),
('Frogger', 'double pole', 'Frogger was the ultimate trickster skiis from the K-9000 series.', 'wax', 'yes', 'cold', 'http:/betterskiis.com/marketplace/Frogger'),
('Redline', 'skate', 'The redline model from the T-series of pro-skiing.', 'wax', 'no', 'cold', 'http:/coolskiis.com/marketplace/Redline');

-- --------------------------------------------------------

--
-- Table structure for table `ski_order`
--

CREATE TABLE `ski_order` (
  `order_number` int(50) NOT NULL AUTO_INCREMENT,
  `total_price` int(50) NOT NULL,
  `state` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `ref_larger_order` int(50) NOT NULL,
  `customer_id` int(50) NOT NULL,
  `shipment_number` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `ski_order`
--

INSERT INTO `ski_order` (`order_number`, `total_price`, `state`, `ref_larger_order`, `customer_id`, `shipment_number`) VALUES
(1, 8000, 'new', 1, 665, 1),
(2, 8600, 'new', 2, 7799, 2),
(3, 4000, 'new', 2, 7799, 2);

-- --------------------------------------------------------

--
-- Table structure for table `ski_type`
--

CREATE TABLE `ski_type` (
  `size` int(50) NOT NULL,
  `weight_class` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `MSRPP` int(50) NOT NULL,
  `model` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `ski_type`
--

INSERT INTO `ski_type` (`size`, `weight_class`, `MSRPP`, `model`) VALUES
(140, '20-30', 4000, 'Redline'),
(140, '40-50', 4600, 'Fisher'),
(145, '20-30', 4300, 'Fisher'),
(150, '30-40', 5500, 'Frogger'),
(150, '40-50', 3500, 'Redline'),
(165, '40-50', 2100, 'Fisher');

-- --------------------------------------------------------

--
-- Table structure for table `ski_type_order`
--

CREATE TABLE `ski_type_order` (
  `order_number` int(50) NOT NULL,
  `size` int(50) NOT NULL,
  `weight` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `quantity` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `ski_type_order`
--

INSERT INTO `ski_type_order` (`order_number`, `size`, `weight`, `quantity`) VALUES
(1, 140, '20-30', 2),
(2, 140, '40-50', 1),
(3, 140, '20-30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `storekeeper`
--

CREATE TABLE `storekeeper` (
  `number` int(50) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `department` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `storekeeper`
--

INSERT INTO `storekeeper` (`number`, `name`, `department`) VALUES
(1098764, 'Kryz Dresden', 'Store and stuff'),
(76324141, 'Ulf Utireir', 'Store and stuff');

-- --------------------------------------------------------

--
-- Table structure for table `team_skier`
--

CREATE TABLE `team_skier` (
  `id` int(50) NOT NULL,
  `start_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `end_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `dob` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `club` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `num_skies` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `team_skier`
--

INSERT INTO `team_skier` (`id`, `start_date`, `end_date`, `name`, `dob`, `club`, `num_skies`) VALUES
(7799, '2021-01-01', '2024-01-01', 'Even Jegermann', '1996-02-11', 'Club Penguin', 2),
(9911, '2020-01-02', '2021-01-28', 'Carol Xavier', '1991-02-12', 'Club Penguin', 4),
(887766, '2021-03-26', '2025-01-01', 'Olav Krokmyr', '1967-04-04', 'The heroes of the skies', 6);

-- --------------------------------------------------------

--
-- Table structure for table `transporter`
--

CREATE TABLE `transporter` (
  `company_name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `transporter`
--

INSERT INTO `transporter` (`company_name`) VALUES
('Sauland Transport AS'),
('Telemark Transport AS');

-- --------------------------------------------------------

--
-- Table structure for table `transporter_view_order`
--

CREATE TABLE `transporter_view_order` (
  `order_number` int(50) NOT NULL,
  `company_name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer_representative`
--
ALTER TABLE `customer_representative`
  ADD PRIMARY KEY (`number`);

--
-- Indexes for table `franchise`
--
ALTER TABLE `franchise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_public`
--
ALTER TABLE `general_public`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `individual_store`
--
ALTER TABLE `individual_store`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keeper_check_order`
--
ALTER TABLE `keeper_check_order`
  ADD PRIMARY KEY (`keeperNo`,`orderNo`);

--
-- Indexes for table `keeper_log`
--
ALTER TABLE `keeper_log`
  ADD PRIMARY KEY (`keeperNo`,`logNo`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`logNo`);

--
-- Indexes for table `production_plan`
--
ALTER TABLE `production_plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_planner`
--
ALTER TABLE `production_planner`
  ADD PRIMARY KEY (`number`);

--
-- Indexes for table `production_plan_reference`
--
ALTER TABLE `production_plan_reference`
  ADD PRIMARY KEY (`plan_id`,`size`,`weight`);

--
-- Indexes for table `public_view_ski`
--
ALTER TABLE `public_view_ski`
  ADD PRIMARY KEY (`account_id`,`size`,`weight`);

--
-- Indexes for table `rep_review_order`
--
ALTER TABLE `rep_review_order`
  ADD PRIMARY KEY (`repNo`,`orderNo`);

--
-- Indexes for table `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`number`);

--
-- Indexes for table `ski`
--
ALTER TABLE `ski`
  ADD PRIMARY KEY (`product_number`);

--
-- Indexes for table `ski_model`
--
ALTER TABLE `ski_model`
  ADD PRIMARY KEY (`model`);

--
-- Indexes for table `ski_order`
--
ALTER TABLE `ski_order`
  ADD PRIMARY KEY (`order_number`);

--
-- Indexes for table `ski_type`
--
ALTER TABLE `ski_type`
  ADD PRIMARY KEY (`size`,`weight_class`);

--
-- Indexes for table `ski_type_order`
--
ALTER TABLE `ski_type_order`
  ADD PRIMARY KEY (`order_number`,`size`,`weight`);

--
-- Indexes for table `storekeeper`
--
ALTER TABLE `storekeeper`
  ADD PRIMARY KEY (`number`);

--
-- Indexes for table `team_skier`
--
ALTER TABLE `team_skier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transporter`
--
ALTER TABLE `transporter`
  ADD PRIMARY KEY (`company_name`);

--
-- Indexes for table `transporter_view_order`
--
ALTER TABLE `transporter_view_order`
  ADD PRIMARY KEY (`order_number`,`company_name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
