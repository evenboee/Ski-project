-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2021 at 04:34 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `auth_token`
--

CREATE TABLE `auth_token` (
  `token` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `auth_token`
--

INSERT INTO `auth_token` (`token`, `role`) VALUES
(sha1('storekeeper'), 'storekeeper'),
(sha1('rep'), 'rep'),
(sha1('shipper'), 'shipper'),
(sha1('customer'), 'customer'),
(sha1('production-planner'), 'production-planner');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `start_date`, `end_date`) VALUES
(1, 'XXL Sport', '2020-01-02', '2026-01-01'),
(2, 'Amundsen Sport', '2021-01-01', '2026-01-01'),
(3, 'Sportsgutta', '2018-01-01', '2025-01-01'),
(4, 'Even Jegermann', '2021-01-01', '2024-01-01'),
(5, 'Carol Xavier', '2020-01-02', '2021-01-28'),
(6, 'Olav Krokmyr', '2021-03-26', '2025-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `number` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `department` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`number`, `name`, `department`) VALUES
(1, 'Jane Smith', 'customer rep'),
(2, 'Matt Karensson', 'storekeeper'),
(3, 'Hannibal Barcka', 'production planner'),
(4, 'Amund Amundsson', 'storekeeper'),
(5, 'Even Bryns Boe ', 'customer rep');

-- --------------------------------------------------------

--
-- Table structure for table `franchise`
--

CREATE TABLE `franchise` (
  `id` int(11) NOT NULL,
  `shipping_address` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `price_multiplier` decimal(3,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `franchise`
--

INSERT INTO `franchise` (`id`, `shipping_address`, `price_multiplier`) VALUES
(1, 'Oslo', '0.50'),
(2, 'Sentrumsvegen 7, Oslo 3187', '0.75');

-- --------------------------------------------------------

--
-- Table structure for table `franchise_store`
--

CREATE TABLE `franchise_store` (
  `franchise_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `franchise_store`
--

INSERT INTO `franchise_store` (`franchise_id`, `store_id`) VALUES
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `individual_store`
--

CREATE TABLE `individual_store` (
  `id` int(11) NOT NULL,
  `shipping_address` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `price_multiplier` decimal(3,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `individual_store`
--

INSERT INTO `individual_store` (`id`, `shipping_address`, `price_multiplier`) VALUES
(3, 'Lillehammervegen 1, Gjøvik', '0.80');

-- --------------------------------------------------------

--
-- Table structure for table `order_log`
--

CREATE TABLE `order_log` (
  `log_number` int(11) NOT NULL,
  `employee_number` int(11) NOT NULL,
  `order_number` int(11) NOT NULL,
  `old_state` varchar(40) COLLATE utf8mb4_danish_ci NOT NULL,
  `new_state` varchar(40) COLLATE utf8mb4_danish_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `order_log`
--

INSERT INTO `order_log` (`log_number`, `employee_number`, `order_number`, `old_state`, `new_state`, `time`) VALUES
(1, 1, 1, 'new', 'open', '2021-05-24 14:33:38'),
(2, 1, 2, 'new', 'open', '2021-05-24 14:33:38'),
(3, 2, 1, 'open', 'skis-available', '2021-05-24 14:33:38');

-- --------------------------------------------------------

--
-- Table structure for table `production_plan`
--

CREATE TABLE `production_plan` (
  `id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `plannerNo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `production_plan`
--

INSERT INTO `production_plan` (`id`, `start_date`, `end_date`, `plannerNo`) VALUES
(1, '2021-01-01', '2021-01-28', 1),
(2, '2021-01-29', '2021-02-26', 1),
(3, '2021-03-26', '2021-04-23', 5);

-- --------------------------------------------------------

--
-- Table structure for table `production_plan_reference`
--

CREATE TABLE `production_plan_reference` (
  `plan_id` int(11) NOT NULL,
  `model` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `size` int(11) NOT NULL,
  `weight` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `production_plan_reference`
--

INSERT INTO `production_plan_reference` (`plan_id`, `model`, `size`, `weight`, `quantity`) VALUES
(1, 'Redline', 140, '20-30', 20),
(1, 'Redline', 150, '40-50', 26),
(2, 'Frogger', 150, '30-40', 34),
(3, 'Frogger', 150, '30-40', 11),
(3, 'Redline', 150, '40-50', 54);

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

CREATE TABLE `shipment` (
  `number` int(11) NOT NULL,
  `order_number` int(11) NOT NULL,
  `store_name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `shipping_address` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `pickup_date` date NOT NULL,
  `state` varchar(20) COLLATE utf8mb4_danish_ci NOT NULL DEFAULT 'ready',
  `driver_id` int(11) NOT NULL,
  `repNo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `shipment`
--

INSERT INTO `shipment` (`number`, `order_number`, `store_name`, `shipping_address`, `pickup_date`, `state`, `driver_id`, `repNo`) VALUES
(1, 1, 'XXL Sport', 'Vegvegen 0 0000By', '2021-05-28', 'shipped', 3, 1),
(2, 2, 'XXL Sport', 'Vegvegen 0 0000By', '2021-05-29', 'ready', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `shipment_transition_log`
--

CREATE TABLE `shipment_transition_log` (
  `log_number` int(11) NOT NULL,
  `shipment_number` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `shipment_transition_log`
--

INSERT INTO `shipment_transition_log` (`log_number`, `shipment_number`, `time`) VALUES
(1, 1, '2021-05-24 14:33:38');

-- --------------------------------------------------------

--
-- Table structure for table `ski`
--

CREATE TABLE `ski` (
  `product_number` int(11) NOT NULL,
  `model` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `size` int(11) NOT NULL,
  `weight` varchar(10) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `ski`
--

INSERT INTO `ski` (`product_number`, `model`, `size`, `weight`) VALUES
(4, 'Fisher', 140, '40-50'),
(3, 'Fisher', 165, '40-50'),
(5, 'Frogger', 150, '30-40'),
(6, 'Frogger', 150, '30-40'),
(1, 'Redline', 140, '20-30'),
(2, 'Redline', 140, '20-30'),
(7, 'Redline', 150, '40-50');

-- --------------------------------------------------------

--
-- Table structure for table `ski_model`
--

CREATE TABLE `ski_model` (
  `model` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `skiing_type` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `description` text COLLATE utf8mb4_danish_ci NOT NULL,
  `grip_system` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `historical` tinyint(1) NOT NULL DEFAULT 0,
  `temperature` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `url` varchar(200) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `ski_model`
--

INSERT INTO `ski_model` (`model`, `skiing_type`, `description`, `grip_system`, `historical`, `temperature`, `url`) VALUES
('Fisher', 'classic', 'Fisher is the latest model of the Fish ski series, made for casual as well as veteran skiiers. ', 'INTelliGrip', 0, 'mild', 'http:/coolskiis.com/marketplace/Fisher'),
('Frogger', 'double pole', 'Frogger was the ultimate trickster skiis from the K-9000 series.', 'wax', 1, 'cold', 'http:/betterskiis.com/marketplace/Frogger'),
('Redline', 'skate', 'The redline model from the T-series of pro-skiing.', 'wax', 0, 'cold', 'http:/coolskiis.com/marketplace/Redline');

-- --------------------------------------------------------

--
-- Table structure for table `ski_order`
--

CREATE TABLE `ski_order` (
  `order_number` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `state` varchar(20) COLLATE utf8mb4_danish_ci NOT NULL DEFAULT 'new',
  `ref_larger_order` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `shipment_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `ski_order`
--

INSERT INTO `ski_order` (`order_number`, `total_price`, `state`, `ref_larger_order`, `customer_id`, `shipment_number`) VALUES
(1, 8000, 'new', NULL, 1, 1),
(2, 8600, 'new', NULL, 1, 2),
(3, 4000, 'new', NULL, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `ski_type`
--

CREATE TABLE `ski_type` (
  `size` int(11) NOT NULL,
  `weight_class` varchar(10) COLLATE utf8mb4_danish_ci NOT NULL,
  `MSRP` int(11) NOT NULL,
  `model` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `ski_type`
--

INSERT INTO `ski_type` (`size`, `weight_class`, `MSRP`, `model`) VALUES
(140, '40-50', 4600, 'Fisher'),
(145, '20-30', 4300, 'Fisher'),
(165, '40-50', 2100, 'Fisher'),
(150, '30-40', 5500, 'Frogger'),
(140, '20-30', 4000, 'Redline'),
(150, '40-50', 3500, 'Redline');

-- --------------------------------------------------------

--
-- Table structure for table `ski_type_order`
--

CREATE TABLE `ski_type_order` (
  `order_number` int(11) NOT NULL,
  `model` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `size` int(11) NOT NULL,
  `weight` varchar(10) COLLATE utf8mb4_danish_ci NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `ski_type_order`
--

INSERT INTO `ski_type_order` (`order_number`, `model`, `size`, `weight`, `quantity`) VALUES
(1, 'Redline', 140, '20-30', 2),
(1, 'Redline', 150, '40-50', 1),
(2, 'Frogger', 150, '30-40', 1);

-- --------------------------------------------------------

--
-- Table structure for table `team_skier`
--

CREATE TABLE `team_skier` (
  `id` int(11) NOT NULL,
  `dob` date NOT NULL,
  `club` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
  `num_skis` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

--
-- Dumping data for table `team_skier`
--

INSERT INTO `team_skier` (`id`, `dob`, `club`, `num_skis`) VALUES
(4, '1996-02-11', 'Club Penguin', 2),
(5, '1991-02-12', 'Club Penguin', 4),
(6, '1967-04-04', 'The heroes of the skis', 6);

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

DROP VIEW IF EXISTS `ski_model_type_view`;
CREATE VIEW `ski_model_type_view` AS
SELECT ski_model.*, ski_type.size, ski_type.weight_class, ski_type.MSRP
FROM ski_type
    INNER JOIN ski_model
        ON ski_type.model = ski_model.model;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_token`
--
ALTER TABLE `auth_token`
  ADD PRIMARY KEY (`token`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`number`);

--
-- Indexes for table `franchise`
--
ALTER TABLE `franchise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `franchise_store`
--
ALTER TABLE `franchise_store`
  ADD PRIMARY KEY (`franchise_id`,`store_id`),
  ADD KEY `franchise_store_individual_store_fk` (`store_id`);

--
-- Indexes for table `individual_store`
--
ALTER TABLE `individual_store`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_log`
--
ALTER TABLE `order_log`
  ADD PRIMARY KEY (`log_number`),
  ADD KEY `Order_log_Employee_fk` (`employee_number`),
  ADD KEY `Order_log_Order_fk` (`order_number`);

--
-- Indexes for table `production_plan`
--
ALTER TABLE `production_plan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_plan_Employee_fk` (`plannerNo`);

--
-- Indexes for table `production_plan_reference`
--
ALTER TABLE `production_plan_reference`
  ADD PRIMARY KEY (`plan_id`,`model`,`size`,`weight`),
  ADD KEY `production_plan_reference_Ski_type_fk` (`model`,`size`,`weight`);

--
-- Indexes for table `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`number`),
  ADD KEY `shipment_Customer_representative_fk` (`repNo`),
  ADD KEY `shipment_Corporation_fk` (`store_name`),
  ADD KEY `shipment_Order_fk` (`order_number`);

--
-- Indexes for table `shipment_transition_log`
--
ALTER TABLE `shipment_transition_log`
  ADD PRIMARY KEY (`log_number`),
  ADD KEY `shipment_transition_log_shipment_fk` (`shipment_number`);

--
-- Indexes for table `ski`
--
ALTER TABLE `ski`
  ADD PRIMARY KEY (`product_number`),
  ADD KEY `ski_ski_type_fk` (`model`,`size`,`weight`);

--
-- Indexes for table `ski_model`
--
ALTER TABLE `ski_model`
  ADD PRIMARY KEY (`model`);

--
-- Indexes for table `ski_order`
--
ALTER TABLE `ski_order`
  ADD PRIMARY KEY (`order_number`),
  ADD KEY `ski_order_corporation_fk` (`customer_id`),
  ADD KEY `ski_order_shipment_fk` (`shipment_number`);

--
-- Indexes for table `ski_type`
--
ALTER TABLE `ski_type`
  ADD PRIMARY KEY (`model`,`size`,`weight_class`);

--
-- Indexes for table `ski_type_order`
--
ALTER TABLE `ski_type_order`
  ADD PRIMARY KEY (`order_number`,`model`,`size`,`weight`),
  ADD KEY `ski_type_order_Ski_type_fk` (`model`,`size`,`weight`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_log`
--
ALTER TABLE `order_log`
  MODIFY `log_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `production_plan`
--
ALTER TABLE `production_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shipment`
--
ALTER TABLE `shipment`
  MODIFY `number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shipment_transition_log`
--
ALTER TABLE `shipment_transition_log`
  MODIFY `log_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ski`
--
ALTER TABLE `ski`
  MODIFY `product_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ski_order`
--
ALTER TABLE `ski_order`
  MODIFY `order_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `team_skier`
--
ALTER TABLE `team_skier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `franchise`
--
ALTER TABLE `franchise`
  ADD CONSTRAINT `franchise_customer_fk` FOREIGN KEY (`id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `franchise_store`
--
ALTER TABLE `franchise_store`
  ADD CONSTRAINT `franchise_store_franchise_fk` FOREIGN KEY (`franchise_id`) REFERENCES `franchise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `franchise_store_individual_store_fk` FOREIGN KEY (`store_id`) REFERENCES `individual_store` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `individual_store`
--
ALTER TABLE `individual_store`
  ADD CONSTRAINT `individual_store_customer_fk` FOREIGN KEY (`id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_log`
--
ALTER TABLE `order_log`
  ADD CONSTRAINT `Order_log_Employee_fk` FOREIGN KEY (`employee_number`) REFERENCES `employee` (`number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Order_log_Order_fk` FOREIGN KEY (`order_number`) REFERENCES `ski_order` (`order_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `production_plan`
--
ALTER TABLE `production_plan`
  ADD CONSTRAINT `production_plan_Employee_fk` FOREIGN KEY (`plannerNo`) REFERENCES `employee` (`number`) ON UPDATE CASCADE;

--
-- Constraints for table `production_plan_reference`
--
ALTER TABLE `production_plan_reference`
  ADD CONSTRAINT `production_plan_reference_Ski_type_fk` FOREIGN KEY (`model`,`size`,`weight`) REFERENCES `ski_type` (`model`, `size`, `weight_class`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `production_plan_reference_production_plan_fk` FOREIGN KEY (`plan_id`) REFERENCES `production_plan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shipment`
--
ALTER TABLE `shipment`
  ADD CONSTRAINT `shipment_Corporation_fk` FOREIGN KEY (`store_name`) REFERENCES `customer` (`name`) ON UPDATE CASCADE,
  ADD CONSTRAINT `shipment_Customer_representative_fk` FOREIGN KEY (`repNo`) REFERENCES `employee` (`number`) ON UPDATE CASCADE,
  ADD CONSTRAINT `shipment_Order_fk` FOREIGN KEY (`order_number`) REFERENCES `ski_order` (`order_number`) ON UPDATE CASCADE;

--
-- Constraints for table `shipment_transition_log`
--
ALTER TABLE `shipment_transition_log`
  ADD CONSTRAINT `shipment_transition_log_shipment_fk` FOREIGN KEY (`shipment_number`) REFERENCES `shipment` (`number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ski`
--
ALTER TABLE `ski`
  ADD CONSTRAINT `ski_ski_model_fk` FOREIGN KEY (`model`) REFERENCES `ski_model` (`model`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ski_ski_type_fk` FOREIGN KEY (`model`,`size`,`weight`) REFERENCES `ski_type` (`model`, `size`, `weight_class`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ski_order`
--
ALTER TABLE `ski_order`
  ADD CONSTRAINT `ski_order_corporation_fk` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ski_order_shipment_fk` FOREIGN KEY (`shipment_number`) REFERENCES `shipment` (`number`) ON UPDATE CASCADE;

--
-- Constraints for table `ski_type`
--
ALTER TABLE `ski_type`
  ADD CONSTRAINT `ski_type_ski_model_fk` FOREIGN KEY (`model`) REFERENCES `ski_model` (`model`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ski_type_order`
--
ALTER TABLE `ski_type_order`
  ADD CONSTRAINT `ski_type_order_Order_fk` FOREIGN KEY (`order_number`) REFERENCES `ski_order` (`order_number`),
  ADD CONSTRAINT `ski_type_order_Ski_type_fk` FOREIGN KEY (`model`,`size`,`weight`) REFERENCES `ski_type` (`model`, `size`, `weight_class`);

--
-- Constraints for table `team_skier`
--
ALTER TABLE `team_skier`
  ADD CONSTRAINT `team_skier_customer_fk` FOREIGN KEY (`id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
