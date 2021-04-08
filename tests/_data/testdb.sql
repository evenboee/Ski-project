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
CREATE TABLE `employee` (
                            `number` INT(50) NOT NULL AUTO_INCREMENT,
                            `name` VARCHAR(50) NOT NULL,
                            `department` VARCHAR(50) NOT NULL,
                            PRIMARY KEY (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `franchise_store`
--

CREATE TABLE `Customer` (
                            `id` int(50) NOT NULL AUTO_INCREMENT,
                            `name` varchar(50) NOT NULL,
                            `start_date` DATE NOT NULL,
                            `end_date` DATE,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;


-- --------------------------------------------------------

--
-- Table structure for table `franchise`
--

CREATE TABLE `Franchise` (
                             `id` int(50) NOT NULL,
                             `shipping_address` varchar(50) NOT NULL,
                             `price_multiplier` DECIMAL(3, 2) UNSIGNED NOT NULL, -- range from 0.00 to 9.99
                             PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_skier`
--

CREATE TABLE `Individual_store` (
                                    `id` int(50) NOT NULL,
                                    `shipping_address` varchar(50) NOT NULL,
                                    `price_multiplier` DECIMAL(3, 2) UNSIGNED NOT NULL, -- range from 0.00 to 9.99
                                    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `franchise_store`
--

CREATE TABLE `franchise_store` (
                                   `franchise_id` int(50) NOT NULL,
                                   `store_id` int(50) NOT NULL,
                                   PRIMARY KEY (`franchise_id`, `store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_skier`
--

CREATE TABLE `team_skier` (
                              `id` int(50) NOT NULL AUTO_INCREMENT,
                              `dob` DATE NOT NULL,
                              `club` varchar(50) NOT NULL,
                              `num_skies` int(50) NOT NULL DEFAULT 0,
                              PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `Order_log` (
                             `log_number` int(50) NOT NULL AUTO_INCREMENT,
                             `employee_number` int(50) NOT NULL,
                             `order_number` int(50) NOT NULL,
                             `old_state` varchar(40) NOT NULL,
                             `new_state` varchar(40) NOT NULL,
                             `time` timestamp default (CURRENT_TIMESTAMP),
                             PRIMARY KEY (`log_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_plan`
--

CREATE TABLE `production_plan` (
                                   `id` int(50) NOT NULL AUTO_INCREMENT,
                                   `start_date` DATE NOT NULL,
                                   `end_date` DATE NOT NULL,
                                   `plannerNo` int(50) NOT NULL,
                                   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_plan_reference`
--

CREATE TABLE `production_plan_reference` (
                                             `plan_id` int(50) NOT NULL,
                                             `model` varchar(50) NOT NULL,
                                             `size` int(50) NOT NULL,
                                             `weight` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
                                             `quantity` int(50) NOT NULL,
                                             PRIMARY KEY (`plan_id`, `model`, `size`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

CREATE TABLE `shipment` (
                            `number` int(50) NOT NULL AUTO_INCREMENT,
                            `store_name` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
                            `shipping_address` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL,
                            `pickup_date` DATE COLLATE utf8mb4_danish_ci NOT NULL,
                            `state` varchar(50) COLLATE utf8mb4_danish_ci NOT NULL DEFAULT 'ready',
                            `driver_id` int(50) NOT NULL,
                            `repNo` int(50) NOT NULL,
                            PRIMARY KEY (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ski`
--

CREATE TABLE `ski` (
                       `product_number` int(50) NOT NULL AUTO_INCREMENT,
                       `model` VARCHAR(50) NOT NULL,
                       `size` int(50) NOT NULL,
                       `weight` varchar(50) NOT NULL,
                       PRIMARY KEY (`product_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ski_model`
--

CREATE TABLE `ski_model` (
                             `model` varchar(50) NOT NULL,
                             `skiing_type` varchar(50) NOT NULL,
                             `description` text NOT NULL,
                             `grip_system` varchar(50) NOT NULL,
                             `historical` BOOLEAN NOT NULL DEFAULT 0,
                             `temperature` varchar(50) NOT NULL,
                             `url` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ski_order`
--

CREATE TABLE `ski_order` (
                             `order_number` int(50) NOT NULL AUTO_INCREMENT,
                             `total_price` int(50) NOT NULL,
                             `state` varchar(50) NOT NULL DEFAULT 'new',
                             `ref_larger_order` int(50) DEFAULT NULL,
                             `customer_id` int(50) NOT NULL,
                             `shipment_number` int(50) DEFAULT NULL,
                             PRIMARY KEY (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ski_type`
--

CREATE TABLE `ski_type` (
                            `size` int(50) NOT NULL,
                            `weight_class` varchar(50) NOT NULL,
                            `MSRP` int(50) NOT NULL,
                            `model` varchar(50) NOT NULL,
                            PRIMARY KEY (`model`, `size`, `weight_class`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ski_type_order`
--

CREATE TABLE `ski_type_order` (
                                  `order_number` int(50) NOT NULL,
                                  `model` varchar(50) NOT NULL,
                                  `size` int(50) NOT NULL,
                                  `weight` varchar(50) NOT NULL,
                                  `quantity` int(50) NOT NULL,
                                  PRIMARY KEY (`order_number`, `model`, `size`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transporter`
--

CREATE TABLE `transporter` (
    `company_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Shipment_transition_log`
--

CREATE TABLE `Shipment_transition_log` (
                                           `log_number` INT(50) NOT NULL AUTO_INCREMENT,
                                           `shipment_number` int(50) NOT NULL,
                                           `time` timestamp default (CURRENT_TIMESTAMP),
                                           PRIMARY KEY (`log_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

CREATE TABLE `auth_token` (
                              `token` VARCHAR(50) NOT NULL,
                              `role` VARCHAR(50) NOT NULL,
                              PRIMARY KEY(`token`)
);

CREATE VIEW `ski_model_type_view` AS
SELECT `ski_model`.*, `ski_type`.`size`, `ski_type`.`weight_class`, `ski_type`.`MSRP`
FROM `ski_type`
         INNER JOIN `ski_model`
                    ON `ski_type`.`model` = `ski_model`.`model`;


-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ski_model`
--
ALTER TABLE `ski_model`
    ADD PRIMARY KEY (`model`);

--
-- Indexes for table `ski_type`
--
ALTER TABLE `ski_type`
    ADD CONSTRAINT `ski_type_ski_model_fk` FOREIGN KEY (`model`)
        REFERENCES `Ski_model`(`model`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;


--
-- Indexes for table `transporter`
--
ALTER TABLE `transporter`
    ADD PRIMARY KEY (`company_name`);

ALTER TABLE `ski`
    ADD CONSTRAINT `ski_ski_model_fk` FOREIGN KEY (`model`)
        REFERENCES `Ski_model`(`model`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    ADD CONSTRAINT `ski_ski_type_fk` FOREIGN KEY (`model`, `size`, `weight`)
        REFERENCES `Ski_type`(`model`, `size`, `weight_class`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

ALTER TABLE `Customer`
    ADD KEY (`name`);

ALTER TABLE `franchise_store`
    ADD CONSTRAINT `franchise_store_franchise_fk` FOREIGN KEY (`franchise_id`)
        REFERENCES `Franchise`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    ADD CONSTRAINT `franchise_store_individual_store_fk` FOREIGN KEY (`store_id`)
        REFERENCES `Individual_store`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

ALTER TABLE `team_skier`
    ADD CONSTRAINT `team_skier_customer_fk` FOREIGN KEY (`id`)
        REFERENCES `Customer`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

ALTER TABLE `Franchise`
    ADD CONSTRAINT `franchise_customer_fk` FOREIGN KEY (`id`)
        REFERENCES `Customer`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

ALTER TABLE `Individual_store`
    ADD CONSTRAINT `individual_store_customer_fk` FOREIGN KEY (`id`)
        REFERENCES `Customer`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

ALTER TABLE `ski_order`
    ADD CONSTRAINT `ski_order_corporation_fk` FOREIGN KEY (`customer_id`)
        REFERENCES `customer`(`id`)
        ON UPDATE CASCADE,
    ADD CONSTRAINT `ski_order_shipment_fk` FOREIGN KEY (`shipment_number`)
        REFERENCES `shipment`(`number`)
        ON UPDATE CASCADE;

ALTER TABLE `ski_type_order`
    ADD CONSTRAINT `ski_type_order_Order_fk` FOREIGN KEY (`order_number`)
        REFERENCES `ski_order`(`order_number`),
    ADD CONSTRAINT `ski_type_order_Ski_type_fk` FOREIGN KEY (`model`, `size`, `weight`)
        REFERENCES `Ski_type`(`model`, `size`, `weight_class`);

ALTER TABLE `shipment`
    ADD CONSTRAINT `shipment_Customer_representative_fk` FOREIGN KEY (`repNo`)
        REFERENCES `employee`(`number`)
        ON UPDATE CASCADE,
    ADD CONSTRAINT `shipment_Corporation_fk` FOREIGN KEY (`store_name`)
        REFERENCES `Customer`(`name`)
        ON UPDATE CASCADE;

ALTER TABLE `Order_log`
    ADD CONSTRAINT `Order_log_Employee_fk` FOREIGN KEY (`employee_number`)
        REFERENCES `Employee`(`number`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    ADD CONSTRAINT `Order_log_Order_fk` FOREIGN KEY (`order_number`)
        REFERENCES `ski_order`(`order_number`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

ALTER TABLE `production_plan`
    ADD CONSTRAINT `production_plan_Employee_fk` FOREIGN KEY (`plannerNo`)
        REFERENCES `Employee`(`number`)
        ON UPDATE CASCADE;

ALTER TABLE `production_plan_reference`
    ADD CONSTRAINT `production_plan_reference_Ski_type_fk` FOREIGN KEY (`model` ,`size`, `weight`)
        REFERENCES `Ski_type`(`model`, `size`, `weight_class`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    ADD CONSTRAINT `production_plan_reference_production_plan_fk` FOREIGN KEY (`plan_id`)
        REFERENCES `production_plan`(`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

ALTER TABLE `Shipment_transition_log`
    ADD CONSTRAINT `shipment_transition_log_shipment_fk` FOREIGN KEY (`shipment_number`)
        REFERENCES `shipment`(`number`)
        ON UPDATE CASCADE
        ON DELETE CASCADE;

-- -----------------------------------------------
-- Adding data

--
-- Dumping data for table `customer_representative`
--

INSERT INTO `employee` (`name`, `department`) VALUES
('Jane Smith', 'customer rep'),
('Matt Karensson', 'storekeeper'),
('Hannibal Barcka', 'production planner'),
('Amund Amundsson', 'storekeeper'),
('Even Bryns Boe ', 'customer rep');

--
-- Dumping data for table `franchise`
--

INSERT INTO `customer` (`start_date`, `end_date`, `name`) VALUES
('2020-01-02', '2026-01-01', 'XXL Sport'),
('2021-01-01', '2026-01-01', 'Amundsen Sport'),
('2018-01-01', '2025-01-01', 'Sportsgutta'),
('2021-01-01', '2024-01-01', 'Even Jegermann'),
('2020-01-02', '2021-01-28', 'Carol Xavier'),
('2021-03-26', '2025-01-01', 'Olav Krokmyr');

INSERT INTO `Franchise` (`id`, `shipping_address`, `price_multiplier`) VALUES
(1, 'Oslo', 0.5),
(2, 'Sentrumsvegen 7, Oslo 3187', 0.75);

INSERT INTO `Individual_store` (`id`, `shipping_address`, `price_multiplier`) VALUES
(3, 'Lillehammervegen 1, Gjøvik', 0.8);

--
-- Dumping data for table `franchise_store`
--

INSERT INTO `franchise_store` (`franchise_id`, `store_id`) VALUES
(1, 3);

--
-- Dumping data for table `ski_model`
--

INSERT INTO `ski_model` (`model`, `skiing_type`, `description`, `grip_system`, `historical`, `temperature`, `url`) VALUES
('Fisher', 'classic', 'Fisher is the latest model of the Fish ski series, made for casual as well as veteran skiiers. ', 'IntelliGrip', 0, 'mild', 'http:/coolskiis.com/marketplace/Fisher'),
('Frogger', 'double pole', 'Frogger was the ultimate trickster skiis from the K-9000 series.', 'wax', 1, 'cold', 'http:/betterskiis.com/marketplace/Frogger'),
('Redline', 'skate', 'The redline model from the T-series of pro-skiing.', 'wax', 0, 'cold', 'http:/coolskiis.com/marketplace/Redline');

--
-- Dumping data for table `ski_type`
--

INSERT INTO `ski_type` (`size`, `weight_class`, `MSRP`, `model`) VALUES
(140, '20-30', 4000, 'Redline'),
(140, '40-50', 4600, 'Fisher'),
(145, '20-30', 4300, 'Fisher'),
(150, '30-40', 5500, 'Frogger'),
(150, '40-50', 3500, 'Redline'),
(165, '40-50', 2100, 'Fisher');

--
-- Dumping data for table `ski`
--

INSERT INTO `ski` (`model`, `size`, `weight`) VALUES
('Redline', 140, '20-30'),
('Redline', 140, '20-30'),
('Fisher', 165, '40-50'),
('Fisher', 140, '40-50'),
('Frogger', 150, '30-40'),
('Frogger', 150, '30-40'),
('Redline', 150, '40-50');

--
-- Dumping data for table `production_plan`
--

INSERT INTO `production_plan` (`start_date`, `end_date`, `plannerNo`) VALUES
('2021-01-01', '2021-01-28', 1),
('2021-01-29', '2021-02-26', 1),
('2021-03-26', '2021-04-23', 5);

--
-- Dumping data for table `production_plan_reference`
--

INSERT INTO `production_plan_reference` (`plan_id`, `model`, `size`, `weight`, `quantity`) VALUES
(1, 'Redline', 140, '20-30', 20),
(1, 'Redline', 150, '40-50', 26),
(2, 'Frogger', 150, '30-40', 34),
(3, 'Frogger', 150, '30-40', 11),
(3, 'Redline', 150, '40-50', 54);

--
-- Dumping data for table `team_skier`
--

INSERT INTO `team_skier` (`id`, `dob`, `club`, `num_skies`) VALUES
(4, '1996-02-11', 'Club Penguin', 2),
(5, '1991-02-12', 'Club Penguin', 4),
(6, '1967-04-04', 'The heroes of the skies', 6);


--
-- Dumping data for table `transporter`
--

INSERT INTO `transporter` (`company_name`) VALUES
('Sauland Transport AS'),
('Telemark Transport AS');

INSERT INTO `Shipment` (`store_name`, `shipping_address`, `state`, `pickup_date`, `driver_id`, `repNo`) VALUES
('XXL Sport', 'Vegvegen 0 0000By', 'shipped', '2021-05-28', 3, 1),
('XXL Sport', 'Vegvegen 0 0000By', 'ready','2021-05-29', 2, 1);

--
-- Dumping data for table `ski_order`
--

INSERT INTO `ski_order` (`total_price`, `customer_id`, `shipment_number`) VALUES
(8000, 1, 1),
(8600, 1, 2),
(4000, 2, 2);

UPDATE `ski_order`
    SET `state` = 'open'
    WHERE `order_number` = 1;

--
-- Dumping data for table `ski_type_order`
--

INSERT INTO `ski_type_order` (`order_number`, `model`, `size`, `weight`, `quantity`) VALUES
(1, 'Redline', 140, '20-30', 2),
(1, 'Redline', 150, '40-50', 1),
(2, 'Frogger', 150, '30-40', 1);

--
-- Dumping data for table `logs`
--

INSERT INTO `Order_log` (`employee_number`, `order_number`, `old_state`, `new_state`) VALUES
(1, 1, 'new', 'open'),
(1, 2, 'new', 'open'),
(2, 1, 'open', 'skis-available');


INSERT INTO `Shipment_transition_log` (`shipment_number`) VALUES
(1);

INSERT INTO `auth_token` (`role`, `token`) VALUES
('rep', 'rep'),
('storekeeper', 'storekeeper'),
('customer', 'customer'),
('shipper', 'shipper'),
('production-planner', 'production-planner');

--
-- Setting up users
--
CREATE USER IF NOT EXISTS 'rep'@'%' IDENTIFIED BY '8c8b8f2042878ab21f68e256841e3dc71d48ff36';
GRANT SELECT, UPDATE, INSERT, DELETE ON `ski_order` TO 'rep'@'%';
GRANT INSERT ON `order_log` TO 'rep'@'%';
GRANT INSERT, DELETE ON `ski_type_order` TO 'rep'@'%';
GRANT SELECT ON `ski_type` TO 'rep'@'%';


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
