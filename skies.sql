-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2021 at 02:55 PM
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
-- Database: `skies`
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

-- --------------------------------------------------------

--
-- Table structure for table `franchise`
--

CREATE TABLE `franchise` (
  `customer_id` int(50) NOT NULL,
  `store_info` text COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_public`
--

CREATE TABLE `general_public` (
  `account_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `individual_store`
--

CREATE TABLE `individual_store` (
  `customer_id` int(50) NOT NULL,
  `start_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `end_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_danish_ci NOT NULL,
  `shipping_address` varchar(40) COLLATE utf8mb4_danish_ci NOT NULL,
  `buing_price` int(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer`
--

CREATE TABLE `manufacturer` (
  `name` varchar(40) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_number` int(50) NOT NULL,
  `total_price` int(50) NOT NULL,
  `state` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `customer_id` int(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `planner_production_plans`
--

CREATE TABLE `planner_production_plans` (
  `planner_id` int(50) NOT NULL,
  `plan_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_plan`
--

CREATE TABLE `production_plan` (
  `id` int(50) NOT NULL,
  `start_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `end_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `manufacturer` varchar(40) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_planner`
--

CREATE TABLE `production_planner` (
  `number` int(50) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `department` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `public_ski_models`
--

CREATE TABLE `public_ski_models` (
  `account_id` int(50) NOT NULL,
  `model_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quantity_of_skies`
--

CREATE TABLE `quantity_of_skies` (
  `order_number` int(11) NOT NULL,
  `ski_type` int(11) NOT NULL,
  `weight_class` int(11) NOT NULL,
  `model` int(11) NOT NULL,
  `ski_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `representative_review_order`
--

CREATE TABLE `representative_review_order` (
  `rep_id` int(50) NOT NULL,
  `order_number` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

CREATE TABLE `shipment` (
  `number` int(50) NOT NULL,
  `store_name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `shipping_address` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `pickup_date` varchar(40) COLLATE utf8mb4_danish_ci NOT NULL,
  `state` varchar(40) COLLATE utf8mb4_danish_ci NOT NULL,
  `transporter` varchar(40) COLLATE utf8mb4_danish_ci NOT NULL,
  `driver_id` int(50) NOT NULL,
  `representative_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ski`
--

CREATE TABLE `ski` (
  `manufacturer` varchar(40) COLLATE utf8mb4_danish_ci NOT NULL,
  `id` int(30) NOT NULL,
  `size` varchar(30) COLLATE utf8mb4_danish_ci NOT NULL,
  `MSRPP` int(30) NOT NULL,
  `model` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `weight_class` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `ski_type` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ski_model`
--

CREATE TABLE `ski_model` (
  `model` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `description` text COLLATE utf8mb4_danish_ci NOT NULL,
  `grip_system` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `historical` tinyint(1) NOT NULL,
  `url` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `temperature` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ski_production_quantity`
--

CREATE TABLE `ski_production_quantity` (
  `plan_id` int(50) NOT NULL,
  `working_day` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `ski_model` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `ski_size` int(50) NOT NULL,
  `ski_weight_class` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ski_type`
--

CREATE TABLE `ski_type` (
  `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ski_type_quantity`
--

CREATE TABLE `ski_type_quantity` (
  `order_number` int(50) NOT NULL,
  `ski_type` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `weight_class` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `model` varchar(40) COLLATE utf8mb4_danish_ci NOT NULL,
  `size` int(50) NOT NULL,
  `quantity` int(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `storekeeper`
--

CREATE TABLE `storekeeper` (
  `number` int(50) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `department` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `storekeeper_update_order`
--

CREATE TABLE `storekeeper_update_order` (
  `keeper_id` int(50) NOT NULL,
  `order_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_skier`
--

CREATE TABLE `team_skier` (
  `customer_id` int(50) NOT NULL,
  `start_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `end_date` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `dob` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `club` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `num_skies` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transporter`
--

CREATE TABLE `transporter` (
  `company_name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transporter_retrive_order`
--

CREATE TABLE `transporter_retrive_order` (
  `transporter_id` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `order_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weight_class`
--

CREATE TABLE `weight_class` (
  `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
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
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `general_public`
--
ALTER TABLE `general_public`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `individual_store`
--
ALTER TABLE `individual_store`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_number`);

--
-- Indexes for table `planner_production_plans`
--
ALTER TABLE `planner_production_plans`
  ADD PRIMARY KEY (`planner_id`,`plan_id`);

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
-- Indexes for table `public_ski_models`
--
ALTER TABLE `public_ski_models`
  ADD PRIMARY KEY (`account_id`,`model_id`);

--
-- Indexes for table `quantity_of_skies`
--
ALTER TABLE `quantity_of_skies`
  ADD PRIMARY KEY (`order_number`,`ski_type`,`weight_class`,`model`,`ski_id`);

--
-- Indexes for table `representative_review_order`
--
ALTER TABLE `representative_review_order`
  ADD PRIMARY KEY (`rep_id`,`order_number`);

--
-- Indexes for table `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`number`);

--
-- Indexes for table `ski`
--
ALTER TABLE `ski`
  ADD PRIMARY KEY (`manufacturer`,`id`);

--
-- Indexes for table `ski_model`
--
ALTER TABLE `ski_model`
  ADD PRIMARY KEY (`model`);

--
-- Indexes for table `ski_production_quantity`
--
ALTER TABLE `ski_production_quantity`
  ADD PRIMARY KEY (`plan_id`,`working_day`,`ski_model`,`ski_size`,`ski_weight_class`);

--
-- Indexes for table `ski_type`
--
ALTER TABLE `ski_type`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `ski_type_quantity`
--
ALTER TABLE `ski_type_quantity`
  ADD PRIMARY KEY (`order_number`,`ski_type`,`weight_class`,`model`,`size`);

--
-- Indexes for table `storekeeper`
--
ALTER TABLE `storekeeper`
  ADD PRIMARY KEY (`number`);

--
-- Indexes for table `storekeeper_update_order`
--
ALTER TABLE `storekeeper_update_order`
  ADD PRIMARY KEY (`keeper_id`,`order_id`);

--
-- Indexes for table `team_skier`
--
ALTER TABLE `team_skier`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `transporter`
--
ALTER TABLE `transporter`
  ADD PRIMARY KEY (`company_name`);

--
-- Indexes for table `transporter_retrive_order`
--
ALTER TABLE `transporter_retrive_order`
  ADD PRIMARY KEY (`transporter_id`,`order_id`);

--
-- Indexes for table `weight_class`
--
ALTER TABLE `weight_class`
  ADD PRIMARY KEY (`name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
