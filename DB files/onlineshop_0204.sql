-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2017 at 09:01 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onlineshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Description` text NOT NULL,
  `Parent_category` int(11) DEFAULT NULL COMMENT 'ID nadredjene kategorije. Moze da bude NULL',
  `Status` int(11) NOT NULL DEFAULT '1' COMMENT '0-kat izbrisana; 1-kat. aktivna'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Parent_category`, `Status`) VALUES
(1, 'WOMEN\'S', 'Women\'s clothing.', 0, 1),
(2, 'MEN\'S', 'Men\'s clothing', 0, 1),
(3, 'Hoodies h', 'Men\'s hoodies.', 6, 1),
(4, 'Tops', 'Summer tops for women.', 1, 1),
(5, 'Dresses', 'Beach summer dresses.', 0, 1),
(6, 'Sweaters', 'Men\'s sweaters hoodies.', 0, 1),
(7, 'Collars', 'Collar shirts.', 2, 0),
(8, 'neka kat', 'kljll', 4, 0),
(9, 'Brnd', 'Brand new', 0, 0),
(10, 'Erase', 'This. Edit first.', 4, 0),
(11, 'Nova', 'Nova kolekcija', 5, 0),
(12, 'Coco', 'Kakao koko', 4, 0),
(13, 'adsa', 'lalla', 7, 0),
(14, 'jknkjn', 'nknk', 0, 0),
(15, 'Jackets', 'Denim western jackets', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories_log`
--

CREATE TABLE `categories_log` (
  `ID` int(11) NOT NULL,
  `ID_category` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Description` text NOT NULL,
  `Parent_category` int(11) NOT NULL,
  `Status` int(11) NOT NULL,
  `ID_admin` int(11) NOT NULL,
  `Date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories_log`
--

INSERT INTO `categories_log` (`ID`, `ID_category`, `Name`, `Description`, `Parent_category`, `Status`, `ID_admin`, `Date_time`) VALUES
(1, 1, 'WOMEN\'S', 'Women\'s clothing.', 0, 1, 49, '2016-05-31 10:02:16'),
(2, 2, 'MEN\'S', 'Men\'s clothing', 0, 1, 49, '2016-05-31 10:02:35'),
(3, 3, 'Hoodies', 'Men\'s hoodies.', 2, 1, 49, '2016-05-31 10:02:55'),
(4, 4, 'Tops', 'Summer tops.', 1, 1, 49, '2016-05-31 10:18:58'),
(5, 5, 'Dresses', 'Beach dresses.', 1, 1, 49, '2016-05-31 14:05:46'),
(6, 6, 'Sweaters', 'Men\'s sweaters.', 2, 1, 49, '2016-06-01 14:04:35'),
(7, 4, 'Tops', 'Summer tops.', 1, 1, 49, '2016-07-12 13:50:45'),
(8, 7, 'Colars', 'Colar shirts.', 2, 1, 49, '2017-02-12 19:02:48'),
(9, 7, 'Colars', 'Colar shirts.', 2, 1, 49, '2017-02-12 19:03:18'),
(10, 8, 'kjk', 'kljll', 2, 1, 49, '2017-02-23 21:56:16'),
(11, 8, 'kjk', 'kljll', 2, 1, 49, '2017-02-23 21:56:32'),
(12, 8, 'neka kat', 'kljll', 4, 1, 49, '2017-02-23 21:56:38'),
(13, 6, 'Sweaters', 'Men\'s sweaters.', 2, 1, 49, '2017-03-11 14:56:03'),
(14, 3, 'Hoodies', 'Men\'s hoodies.', 2, 1, 49, '2017-03-21 22:54:20'),
(15, 9, 'new', 'new', 3, 1, 49, '2017-03-21 22:59:19'),
(16, 9, 'new', 'new', 3, 1, 53, '2017-03-28 14:14:33'),
(17, 10, 'Erase', 'This', 1, 1, 49, '2017-03-28 18:49:51'),
(18, 10, 'Erase', 'This', 1, 1, 49, '2017-03-28 18:50:09'),
(19, 10, 'Erase', 'This. Edit first.', 4, 1, 49, '2017-03-28 18:55:07'),
(20, 9, 'new', 'Brand new...', 2, 1, 49, '2017-03-28 18:56:49'),
(21, 6, 'Sweaters', 'Men\'s sweaters hoodies.', 2, 1, 49, '2017-03-28 21:28:53'),
(22, 5, 'Dresses', 'Beach dresses.', 1, 1, 49, '2017-03-28 21:29:48'),
(23, 6, 'Sweaters', 'Men\'s sweaters hoodies.', 1, 1, 49, '2017-03-28 21:30:47'),
(24, 4, 'Tops', 'Summer tops for women.', 1, 1, 49, '2017-03-28 21:49:46'),
(25, 3, 'Hoodies h', 'Men\'s hoodies.', 6, 1, 49, '2017-03-28 21:50:29'),
(26, 5, 'Dresses', 'Beach dresses.', 1, 1, 49, '2017-03-28 22:25:55'),
(27, 11, 'Nova', 'njnjnj', 3, 1, 49, '2017-03-29 14:00:24'),
(28, 11, 'Nova', 'njnjnj', 3, 1, 49, '2017-03-29 14:00:38'),
(29, 11, 'Nova', 'Nova kolekcija', 5, 1, 49, '2017-03-29 14:00:44'),
(30, 12, 'hjkh', 'jkhkjh', 2, 1, 49, '2017-03-29 14:57:55'),
(31, 12, 'hjkh', 'jkhkjh', 2, 1, 49, '2017-03-29 14:58:24'),
(32, 12, 'Coco', 'Kakao koko', 4, 1, 49, '2017-03-29 14:58:30'),
(33, 13, 'test', 'tttt', 1, 1, 60, '2017-03-30 19:37:56'),
(35, 13, 'test', 'tttt', 1, 1, 60, '2017-03-30 19:38:35'),
(36, 13, 'test', 'titi', 1, 1, 60, '2017-03-30 19:38:49'),
(37, 13, 'test', 'lalla', 1, 1, 60, '2017-03-30 19:39:06'),
(38, 7, 'Collars', 'Collar shirts.', 2, 1, 60, '2017-03-30 19:39:25'),
(39, 14, 'jknkjn', 'nknk', 13, 1, 49, '2017-04-01 20:07:50'),
(40, 9, 'Brnd', 'Brand new', 1, 1, 49, '2017-04-01 20:08:07'),
(41, 5, 'Dresses', 'Beach summer dresses.', 1, 1, 49, '2017-04-01 20:08:13'),
(42, 6, 'Sweaters', 'Men\'s sweaters hoodies.', 1, 1, 49, '2017-04-01 20:08:19'),
(43, 13, 'adsa', 'lalla', 7, 1, 49, '2017-04-01 20:08:32'),
(44, 5, 'Dresses', 'Beach summer dresses.', 0, 1, 49, '2017-04-01 20:35:01'),
(45, 6, 'Sweaters', 'Men\'s sweaters hoodies.', 0, 1, 49, '2017-04-01 20:35:07'),
(47, 14, 'jknkjn', 'nknk', 0, 1, 49, '2017-04-01 20:36:15'),
(48, 5, 'Dresses', 'Beach summer dresses.', 14, 1, 49, '2017-04-01 20:36:15'),
(49, 6, 'Sweaters', 'Men\'s sweaters hoodies.', 14, 1, 49, '2017-04-01 20:36:15'),
(51, 5, 'Dresses', 'Beach summer dresses.', 0, 1, 49, '2017-04-02 16:18:36'),
(52, 6, 'Sweaters', 'Men\'s sweaters hoodies.', 0, 1, 49, '2017-04-02 16:18:41'),
(53, 9, 'Brnd', 'Brand new', 0, 1, 49, '2017-04-02 16:18:46'),
(54, 5, 'Dresses', 'Beach summer dresses.', 9, 1, 49, '2017-04-02 16:18:46'),
(55, 6, 'Sweaters', 'Men\'s sweaters hoodies.', 9, 1, 49, '2017-04-02 16:18:46'),
(57, 15, 'Jackets', 'Denim jackets', 1, 1, 49, '2017-04-02 16:27:20'),
(58, 15, 'Jackets', 'Denim jackets', 1, 1, 49, '2017-04-02 16:27:41');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `ID` int(11) NOT NULL,
  `Shortname` text NOT NULL COMMENT 'Skraceni naziv valute.',
  `Rate` decimal(10,4) NOT NULL COMMENT '1RSD = xEUR'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`ID`, `Shortname`, `Rate`) VALUES
(1, 'RSD', '123.2690'),
(4, 'USD', '1.1187'),
(5, 'AUD', '1.5496');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Description` text NOT NULL,
  `Price` decimal(10,0) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ID`, `Name`, `Description`, `Price`, `Status`) VALUES
(1, 'Misty top', 'Women\'s  wear.', '1500', 1),
(2, 'Koko top', 'Women\'s day clothing.', '2000', 1),
(3, 'Air crew hoodie', 'Men\'s hoodie.', '6000', 1),
(4, 'Bon bon top', 'Summer women\'s top.', '2000', 1),
(5, 'Forest sweater', 'Men\'s cothing.', '5000', 1),
(6, 'Cheeky top', 'Women\'s clothing.', '12', 1),
(7, 'EW', 'EW', '2342', 1),
(8, 'Halji', 'jkhkhhj', '1312', 1),
(9, 'jhg', 'jkhkhkj', '3212', 0),
(10, 'hgj', 'jjb', '878', 0),
(11, 'kjhj', 'jhjh', '76', 1),
(12, 'Cheeky boots', 'Boots', '340', 1),
(13, 'Cheeky tee', 'Summer shirt', '313', 1),
(14, 'Dzerad', 'Kusner', '21231', 0),
(15, 'Nova', 'Martina\'s style', '1000000', 0),
(16, 'Drag', 'Queens', '87897', 0),
(17, 'Kim Jacket', 'Denim oriental jacket', '2000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products_log`
--

CREATE TABLE `products_log` (
  `ID` int(11) NOT NULL,
  `ID_product` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Description` text NOT NULL,
  `Price` decimal(10,0) NOT NULL,
  `Status` int(11) NOT NULL,
  `ID_admin` int(11) NOT NULL,
  `Date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_log`
--

INSERT INTO `products_log` (`ID`, `ID_product`, `Name`, `Description`, `Price`, `Status`, `ID_admin`, `Date_time`) VALUES
(1, 1, 'Misty top', 'Women\'s beach wear.', '1500', 1, 49, '2016-05-31 10:20:50'),
(2, 2, 'Kokita top', 'Women\'s summer clothing.', '1500', 1, 49, '2016-05-31 10:24:03'),
(3, 3, 'Air crew hoodie', 'Men\'s hoodie.', '6000', 1, 49, '2016-05-31 10:43:29'),
(4, 4, 'Kiki top', 'Summer women\'s top.', '2000', 1, 49, '2016-05-31 14:07:22'),
(5, 5, 'Forest sweater', 'Men\'s cothing.', '5000', 1, 49, '2016-06-01 14:08:08'),
(6, 6, 'Cheeky top', 'Women\'s clothing.', '12', 1, 49, '2016-06-14 11:54:04'),
(7, 2, 'Koko top', 'Women\'s summer clothing.', '1500', 1, 49, '2016-06-15 15:05:18'),
(8, 4, 'Kiki top', 'Summer women\'s top.', '2000', 1, 49, '2016-06-15 15:11:03'),
(9, 2, 'Koko top', 'Women\'s summer clothing.', '1500', 1, 49, '2016-06-16 09:25:12'),
(10, 7, 'EW', 'EW', '2342', 1, 49, '2017-02-12 21:59:39'),
(11, 8, 'Halji', 'jkhkhhj', '1312', 1, 49, '2017-02-13 20:38:11'),
(12, 9, 'jhg', 'jkhkhkj', '3212', 1, 49, '2017-02-13 21:21:46'),
(13, 10, 'hgj', 'jjb', '878', 1, 49, '2017-02-13 21:26:18'),
(14, 11, 'kjhj', 'jhjh', '76', 1, 49, '2017-02-13 21:30:10'),
(15, 12, 'Cheeky boots', 'Boots', '23424', 1, 49, '2017-02-25 19:58:16'),
(16, 12, 'Cheeky boots', 'Boots', '23424', 1, 49, '2017-02-25 19:58:37'),
(17, 1, 'Misty top', 'Women\'s beach wear.', '1500', 1, 49, '2017-02-27 17:56:53'),
(18, 1, 'Misty top', 'Women\'s beach wear.', '1500', 1, 49, '2017-02-27 22:02:55'),
(19, 1, 'Misty top', 'Women\'s night wear.', '1500', 1, 49, '2017-02-27 22:14:16'),
(20, 1, 'Misty top', 'Women\'s night wear.', '1500', 1, 49, '2017-02-27 22:18:23'),
(21, 1, 'Misty top', 'Women\'s night wear.', '1500', 1, 49, '2017-02-27 22:19:32'),
(22, 1, 'Misty top', 'Women\'s night wear.', '1500', 1, 49, '2017-02-27 22:20:49'),
(23, 1, 'Misty top', 'Women\'s  wear.', '1500', 1, 49, '2017-02-27 22:21:46'),
(24, 1, 'Misty top', 'Women\'s night wear.', '1500', 1, 49, '2017-02-27 22:22:59'),
(25, 2, 'Koko top', 'Women\'s clothing.', '2000', 1, 49, '2017-02-27 22:24:47'),
(26, 1, 'Misty top', 'Women\'s day wear.', '1500', 1, 49, '2017-02-27 22:28:07'),
(27, 1, 'Misty top', 'Women\'s day wear.', '1500', 1, 49, '2017-02-27 22:28:41'),
(28, 1, 'Misty top', 'Women\'s wear.', '1500', 1, 49, '2017-02-27 22:30:24'),
(29, 1, 'Misty top', 'Women\'s wear.', '1500', 1, 49, '2017-02-27 22:34:24'),
(30, 1, 'Misty top', 'Women\'s day wear.', '1500', 1, 49, '2017-02-27 22:36:41'),
(31, 1, 'Misty top', 'Women\'s  wear.', '1500', 1, 49, '2017-03-02 19:10:41'),
(32, 2, 'Koko top', 'Women\'s day clothing.', '2000', 1, 49, '2017-03-02 19:11:03'),
(33, 3, 'Air crew hoodie', 'Men\'s hoodie.', '6000', 1, 49, '2017-03-02 19:11:28'),
(34, 5, 'Forest sweater', 'Men\'s cothing.', '5000', 1, 49, '2017-03-02 19:11:56'),
(35, 5, 'Forest sweater', 'Men\'s cothing.', '5000', 1, 49, '2017-03-02 19:31:00'),
(36, 5, 'Forest sweater', 'Men\'s cothing.', '5000', 1, 49, '2017-03-02 19:32:27'),
(37, 5, 'Forest sweater', 'Men\'s cothing.', '5000', 1, 49, '2017-03-02 19:32:34'),
(38, 5, 'Forest sweater', 'Men\'s cothing.', '5000', 1, 49, '2017-03-02 19:38:09'),
(39, 5, 'Forest sweater', 'Men\'s cothing.', '5000', 1, 49, '2017-03-02 19:38:15'),
(40, 5, 'Forest sweater', 'Men\'s cothing.', '5000', 1, 49, '2017-03-02 19:38:19'),
(41, 5, 'Forest sweater', 'Men\'s cothing.', '5000', 1, 49, '2017-03-02 19:38:31'),
(42, 4, 'Bon bon top', 'Summer women\'s top.', '2000', 1, 49, '2017-03-09 13:57:05'),
(43, 13, 'Cheeky tee', 'Summer shirt', '313', 1, 49, '2017-03-22 20:44:02'),
(44, 14, 'Novi', 'NOvi bovi', '41432', 1, 49, '2017-03-28 19:07:33'),
(45, 14, 'Dzerad', 'Kusner', '21231', 1, 49, '2017-03-28 19:25:16'),
(46, 14, 'Dzerad', 'Kusner', '21231', 1, 49, '2017-03-28 19:25:35'),
(47, 15, 'nnn', 'khjknkjb', '23142', 1, 49, '2017-03-29 14:05:20'),
(48, 15, 'Nova', 'Martina\'s style', '1000000', 1, 49, '2017-03-29 14:06:29'),
(49, 15, 'Nova', 'Martina\'s style', '1000000', 1, 49, '2017-03-29 14:06:45'),
(50, 16, 'kjhkjk', 'kjkj', '87897', 1, 49, '2017-03-29 15:00:29'),
(51, 16, 'Drag', 'Queens', '87897', 1, 49, '2017-03-29 15:00:53'),
(52, 16, 'Drag', 'Queens', '87897', 1, 49, '2017-03-29 15:00:59'),
(53, 17, 'Kim Jacket', 'Denim short jacket', '2000', 1, 49, '2017-04-02 16:31:44'),
(54, 17, 'Kim Jacket', 'Denim oriental jacket', '2000', 1, 49, '2017-04-02 16:39:31'),
(55, 9, 'jhg', 'jkhkhkj', '3212', 1, 49, '2017-04-02 16:44:26'),
(56, 10, 'hgj', 'jjb', '878', 1, 49, '2017-04-02 16:53:49');

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `ID` int(11) NOT NULL,
  `ID_category` int(11) NOT NULL,
  `ID_product` int(11) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '1' COMMENT 'Aktivan par ili obrisan.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`ID`, `ID_category`, `ID_product`, `Status`) VALUES
(1, 1, 1, 1),
(2, 4, 1, 1),
(3, 1, 2, 1),
(4, 4, 2, 1),
(5, 3, 3, 1),
(6, 1, 4, 1),
(7, 4, 4, 1),
(8, 2, 5, 1),
(9, 6, 5, 1),
(10, 1, 6, 1),
(11, 4, 6, 1),
(13, 1, 7, 1),
(14, 1, 8, 1),
(15, 1, 9, 0),
(16, 2, 10, 0),
(17, 1, 11, 1),
(18, 1, 12, 1),
(19, 1, 13, 1),
(20, 4, 13, 1),
(21, 2, 14, 0),
(22, 9, 14, 0),
(23, 1, 14, 0),
(24, 3, 14, 0),
(25, 1, 15, 0),
(26, 3, 15, 0),
(27, 5, 15, 0),
(29, 1, 16, 0),
(30, 2, 16, 0),
(31, 3, 16, 0),
(32, 15, 17, 1),
(33, 1, 17, 0),
(34, 1, 17, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_category_log`
--

CREATE TABLE `product_category_log` (
  `ID` int(11) NOT NULL,
  `ID_CP` int(11) NOT NULL,
  `ID_category` int(11) NOT NULL,
  `ID_product` int(11) NOT NULL,
  `Status` int(11) NOT NULL,
  `ID_admin` int(11) NOT NULL,
  `Date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_category_log`
--

INSERT INTO `product_category_log` (`ID`, `ID_CP`, `ID_category`, `ID_product`, `Status`, `ID_admin`, `Date_time`) VALUES
(1, 1, 1, 1, 1, 49, '2016-05-31 10:20:50'),
(2, 2, 4, 1, 1, 49, '2016-05-31 10:20:50'),
(3, 3, 1, 2, 1, 49, '2016-05-31 10:24:03'),
(4, 4, 4, 2, 1, 49, '2016-05-31 10:24:03'),
(5, 5, 3, 3, 1, 49, '2016-05-31 10:43:29'),
(6, 6, 1, 4, 1, 49, '2016-05-31 14:07:22'),
(7, 7, 4, 4, 1, 49, '2016-05-31 14:07:22'),
(8, 8, 2, 5, 1, 49, '2016-06-01 14:08:08'),
(9, 9, 6, 5, 1, 49, '2016-06-01 14:08:08'),
(10, 10, 1, 6, 1, 49, '2016-06-14 11:54:04'),
(11, 11, 4, 6, 1, 49, '2016-06-14 11:54:04'),
(12, 12, 1, 7, 1, 49, '2017-02-12 21:59:39'),
(13, 14, 1, 8, 1, 49, '2017-02-13 20:38:11'),
(14, 15, 1, 9, 1, 49, '2017-02-13 21:21:46'),
(15, 16, 2, 10, 1, 49, '2017-02-13 21:26:18'),
(16, 17, 1, 11, 1, 49, '2017-02-13 21:30:10'),
(17, 18, 1, 12, 1, 49, '2017-02-25 19:58:16'),
(18, 19, 1, 13, 1, 49, '2017-03-22 20:44:02'),
(19, 20, 4, 13, 1, 49, '2017-03-22 20:44:02'),
(20, 21, 2, 14, 1, 49, '2017-03-28 19:07:33'),
(21, 22, 9, 14, 1, 49, '2017-03-28 19:07:33'),
(22, 21, 2, 14, 0, 49, '2017-03-28 19:25:16'),
(23, 22, 9, 14, 0, 49, '2017-03-28 19:25:16'),
(24, 23, 1, 14, 1, 49, '2017-03-28 19:25:35'),
(25, 24, 3, 14, 1, 49, '2017-03-28 19:25:35'),
(27, 25, 1, 15, 1, 49, '2017-03-29 14:05:20'),
(28, 26, 3, 15, 1, 49, '2017-03-29 14:05:20'),
(29, 26, 3, 15, 0, 49, '2017-03-29 14:06:29'),
(30, 25, 1, 15, 1, 49, '2017-03-29 14:06:45'),
(31, 27, 5, 15, 1, 49, '2017-03-29 14:06:45'),
(33, 28, 1, 16, 1, 49, '2017-03-29 15:00:29'),
(34, 29, 2, 16, 1, 49, '2017-03-29 15:00:29'),
(35, 29, 1, 16, 0, 49, '2017-03-29 15:00:53'),
(36, 30, 2, 16, 0, 49, '2017-03-29 15:00:53'),
(37, 31, 3, 16, 1, 49, '2017-03-29 15:00:59'),
(38, 32, 15, 17, 1, 49, '2017-04-02 16:31:44'),
(39, 33, 1, 17, 0, 49, '2017-04-02 16:42:29'),
(40, 15, 1, 9, 1, 49, '2017-04-02 16:44:26'),
(41, 16, 2, 10, 1, 49, '2017-04-02 16:53:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Lastname` text NOT NULL,
  `Username` text NOT NULL COMMENT 'korisnicki username',
  `Email` text NOT NULL,
  `Password` text NOT NULL,
  `API_key` int(11) DEFAULT NULL,
  `Access_rights` char(1) NOT NULL DEFAULT 'R' COMMENT 'A - administrator, W - writer, R - reader',
  `Locked` int(11) NOT NULL DEFAULT '3' COMMENT 'Cuva broj preostalih pokusaja logovanja sa pogresnim passwordom. Svaki pokusaj smanjuje broj za 1. Ako je 0 nalog je zakljucan.',
  `Status` int(11) NOT NULL DEFAULT '1' COMMENT '0-korisnik izbrisan; 1-korisnik aktivan'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Name`, `Lastname`, `Username`, `Email`, `Password`, `API_key`, `Access_rights`, `Locked`, `Status`) VALUES
(2, 'Kosta', 'Dimitrijevic', 'kosta', 'kosta@yahoo.com', 'kosta123', NULL, 'A', 3, 1),
(13, 'No', 'Photo', 'no photo', 'n@gmail.com', 'nnn111', NULL, 'R', 3, 1),
(49, 'Nikolina', 'Pavkovic', 'koko', 'nikolinap85@gmail.com', 'koko123', NULL, 'A', 3, 1),
(50, 'Ciri', 'Bu', 'ciri bu', 'ciri@bu.com', 'ccc111', NULL, 'W', 3, 0),
(51, 'Ciri', 'Bu', 'cici', 'cici@gmail.com', 'ccc111', NULL, 'W', 3, 0),
(52, 'hokus', 'pokus', 'hokus pokus', 'hokus@gmail.com', 'hhh111', NULL, 'W', 3, 0),
(53, 'Maca', 'Macak', 'corma', 'mac@gmail.com', 'mac123', NULL, 'A', 3, 0),
(54, 'bzvz', 'bzvz', 'bzvz', 'bz@gmail.com', 'bzvz123', NULL, 'A', 3, 0),
(55, 'Cvrle', 'Cvrlic', 'cvrlic', 'cic@gmail.com', 'cic123', NULL, 'R', 3, 0),
(56, 'Cicko', 'Micko', 'cicmic', 'cicmic@gmail.com', 'cic123', NULL, 'A', 3, 0),
(57, 'Maca', 'Macak', 'corma', 'mac@gmail.com', 'mac123', NULL, 'A', 3, 0),
(58, 'Maca', 'Macak', 'corma', 'mac@gmail.com', 'mac123', NULL, 'A', 3, 0),
(59, 'Maca', 'Macak', 'corma', 'mac@gmail.com', 'mac123', NULL, 'A', 3, 0),
(60, 'Macak', 'Macak', 'corma', 'mac@gmail.com', 'mac123', NULL, 'A', 3, 1),
(61, 'Novi', 'Novi', 'novica', 'novi@gmail.com', 'nnn123', NULL, 'A', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_log`
--

CREATE TABLE `users_log` (
  `ID` int(11) NOT NULL,
  `ID_user` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Lastname` text NOT NULL,
  `Email` text NOT NULL,
  `Username` text NOT NULL,
  `Password` text NOT NULL,
  `API_key` int(11) DEFAULT NULL,
  `Access_rights` text NOT NULL,
  `Locked` int(11) NOT NULL,
  `Status` int(11) NOT NULL,
  `ID_admin` int(11) NOT NULL,
  `Data_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_log`
--

INSERT INTO `users_log` (`ID`, `ID_user`, `Name`, `Lastname`, `Email`, `Username`, `Password`, `API_key`, `Access_rights`, `Locked`, `Status`, `ID_admin`, `Data_time`) VALUES
(1, 50, 'Ciri', 'Bu', 'ciri@bu.com', 'ciri bu', 'ccc111', 0, '0', 3, 1, 49, '2016-05-09 13:49:01'),
(2, 51, 'Ciri', 'Bu', 'cici@gmail.com', 'cici', 'ccc111', 0, '0', 3, 1, 49, '2016-05-09 13:56:00'),
(3, 52, 'hokus', 'pokus', 'hokus@gmail.com', 'hokus pokus', 'hhh111', 0, '0', 3, 1, 49, '2016-05-09 13:59:42'),
(8, 2, 'Kosta', 'Dimitrijevic', 'kosta@yahoo.com', 'kosta', 'kosta123', 0, '0', 3, 1, 49, '2016-05-10 12:27:24'),
(9, 2, 'Kosta', 'Dimitrijevic', 'kosta@yahoo.com', 'kosta', 'kosta123', 0, 'R', 3, 1, 49, '2016-05-10 12:41:52'),
(10, 2, 'Kosta', 'Dimitrijevic', 'kosta@yahoo.com', 'kosta', 'kosta123', 0, 'A', 3, 1, 49, '2016-05-10 12:44:05'),
(11, 13, 'No', 'Photo', 'n@gmail.com', 'no photo', 'nnn111', NULL, 'A', 3, 1, 49, '2017-03-15 21:38:04'),
(12, 2, 'Kosta', 'Dimitrijevic', 'kosta@yahoo.com', 'kosta', 'kosta123', NULL, 'A', 3, 1, 49, '2017-03-27 19:54:37'),
(13, 2, 'Kosta', 'Dimitrijevic', 'kosta@yahoo.com', 'kosta', 'kosta123', NULL, 'A', 3, 1, 2, '2017-03-27 19:55:31'),
(14, 53, 'Macak', 'Macak', 'mac@gmail.com', 'corma', 'mac123', NULL, 'W', 3, 1, 53, '2017-03-27 20:52:29'),
(15, 53, 'undefined', 'undefined', 'mac@gmail.com', 'corma', 'mac123', NULL, 'R', 3, 1, 53, '2017-03-27 20:56:47'),
(16, 53, 'Macak', 'Macak', 'mac@gmail.com', 'corma', 'mac123', NULL, 'R', 3, 1, 49, '2017-03-28 13:43:46'),
(17, 53, 'undefined', 'undefined', 'mac@gmail.com', 'corma', 'mac123', NULL, 'R', 3, 1, 49, '2017-03-28 13:47:05'),
(18, 53, 'Macak', 'Macak', 'mac@gmail.com', 'corma', 'mac123', NULL, 'R', 3, 1, 49, '2017-03-28 13:57:11'),
(19, 53, 'Macak', 'Macak', 'mac@gmail.com', 'corma', 'mac123', NULL, 'R', 3, 1, 49, '2017-03-28 13:57:50'),
(20, 54, 'bzvz', 'bzvz', 'bz@gmail.com', 'bzvz', 'bzvz123', NULL, 'A', 3, 1, 49, '2017-03-28 14:11:37'),
(21, 53, 'Macak', 'Macak', 'mac@gmail.com', 'corma', 'mac123', NULL, 'R', 3, 1, 49, '2017-03-28 14:13:52'),
(22, 53, 'Macak', 'Macak', 'mac@gmail.com', 'corma', 'mac123', NULL, 'W', 0, 1, 49, '2017-03-28 14:19:11'),
(23, 56, 'Cicko', 'MIcko', 'cicmic@gmail.com', 'cicmic', 'cic123', NULL, 'W', 3, 1, 49, '2017-03-29 15:03:20'),
(24, 56, 'Cicko', 'Micko', 'cicmic@gmail.com', 'cicmic', 'cic123', NULL, 'A', 3, 1, 49, '2017-03-29 15:03:54'),
(25, 55, 'Cvrle', 'Cvrlic', 'cic@gmail.com', 'cvrlic', 'cic123', NULL, 'R', 3, 1, 49, '2017-03-30 14:01:08'),
(26, 53, 'Macak', 'Macak', 'mac@gmail.com', 'corma', 'mac123', NULL, 'W', 3, 1, 49, '2017-03-30 14:50:20'),
(27, 57, 'Macak', 'Macak', 'mac@gmail.com', 'corma', 'mac123', NULL, 'W', 3, 1, 49, '2017-03-30 14:54:42'),
(28, 58, 'Macak', 'Macak', 'mac@gmail.com', 'corma', 'mac123', NULL, 'W', 3, 1, 49, '2017-03-30 14:58:31'),
(29, 59, 'Macak', 'm', 'mac@gmail.com', 'corma', 'mac123', NULL, 'A', 3, 1, 49, '2017-03-30 15:02:15'),
(30, 60, 'Maca', 'Macak', 'mac@gmail.com', 'corma', 'mac123', NULL, 'A', 0, 1, 49, '2017-03-30 19:16:25'),
(31, 60, 'Maca', 'Macak', 'mac@gmail.com', 'corma', 'mac123', NULL, 'A', 3, 1, 49, '2017-03-30 19:30:13'),
(32, 61, 'Novi', 'Novi', 'novi@gmail.com', 'novica', 'nnn123', NULL, 'A', 3, 1, 61, '2017-04-02 20:14:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `categories_log`
--
ALTER TABLE `categories_log`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `products_log`
--
ALTER TABLE `products_log`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `product_category_log`
--
ALTER TABLE `product_category_log`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users_log`
--
ALTER TABLE `users_log`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `categories_log`
--
ALTER TABLE `categories_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `products_log`
--
ALTER TABLE `products_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `product_category_log`
--
ALTER TABLE `product_category_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT for table `users_log`
--
ALTER TABLE `users_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
