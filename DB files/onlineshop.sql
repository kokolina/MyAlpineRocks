-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2017 at 11:28 AM
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
-- Table structure for table `kategorija_proizvoda`
--

CREATE TABLE `kategorija_proizvoda` (
  `ID` int(11) NOT NULL,
  `ID_kategorije` int(11) NOT NULL,
  `ID_proizvoda` int(11) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '1' COMMENT 'Aktivan par ili obrisan.'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategorija_proizvoda`
--

INSERT INTO `kategorija_proizvoda` (`ID`, `ID_kategorije`, `ID_proizvoda`, `Status`) VALUES
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
(15, 1, 9, 1),
(16, 2, 10, 1),
(17, 1, 11, 1),
(18, 1, 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `kategorija_proizvoda_log`
--

CREATE TABLE `kategorija_proizvoda_log` (
  `ID` int(11) NOT NULL,
  `ID_KP` int(11) NOT NULL,
  `ID_kategorije` int(11) NOT NULL,
  `ID_proizvoda` int(11) NOT NULL,
  `Status` int(11) NOT NULL,
  `ID_admin` int(11) NOT NULL,
  `Date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategorija_proizvoda_log`
--

INSERT INTO `kategorija_proizvoda_log` (`ID`, `ID_KP`, `ID_kategorije`, `ID_proizvoda`, `Status`, `ID_admin`, `Date_time`) VALUES
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
(17, 18, 1, 12, 1, 49, '2017-02-25 19:58:16');

-- --------------------------------------------------------

--
-- Table structure for table `kategorije`
--

CREATE TABLE `kategorije` (
  `ID` int(11) NOT NULL,
  `Naziv` text NOT NULL,
  `Opis` text NOT NULL,
  `Parent_kategorija` int(11) DEFAULT NULL COMMENT 'ID nadredjene kategorije. Moze da bude NULL',
  `Status` int(11) NOT NULL DEFAULT '1' COMMENT '0-kat izbrisana; 1-kat. aktivna'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategorije`
--

INSERT INTO `kategorije` (`ID`, `Naziv`, `Opis`, `Parent_kategorija`, `Status`) VALUES
(1, 'WOMEN\'S', 'Women\'s clothing.', 0, 1),
(2, 'MEN\'S', 'Men\'s clothing', 0, 1),
(3, 'Hoodies', 'Men\'s hoodies.', 2, 1),
(4, 'Tops', 'Summer tops for women.', 1, 1),
(5, 'Dresses', 'Beach dresses.', 1, 1),
(6, 'Sweaters', 'Men\'s sweaters.', 2, 1),
(7, 'Collars', 'Collar shirts.', 2, 1),
(8, 'neka kat', 'kljll', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `kategorije_log`
--

CREATE TABLE `kategorije_log` (
  `ID` int(11) NOT NULL,
  `ID_kategorije` int(11) NOT NULL,
  `Naziv` text NOT NULL,
  `Opis` text NOT NULL,
  `Parent_kategorija` int(11) NOT NULL,
  `Status` int(11) NOT NULL,
  `ID_admin` int(11) NOT NULL,
  `Date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategorije_log`
--

INSERT INTO `kategorije_log` (`ID`, `ID_kategorije`, `Naziv`, `Opis`, `Parent_kategorija`, `Status`, `ID_admin`, `Date_time`) VALUES
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
(12, 8, 'neka kat', 'kljll', 4, 1, 49, '2017-02-23 21:56:38');

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

CREATE TABLE `korisnici` (
  `ID` int(11) NOT NULL,
  `Ime` text NOT NULL,
  `Prezime` text NOT NULL,
  `Username` text NOT NULL COMMENT 'korisnicki username',
  `Email` text NOT NULL,
  `Password` text NOT NULL,
  `API_key` int(11) DEFAULT NULL,
  `Prava_pristupa` char(1) NOT NULL DEFAULT 'R' COMMENT 'A - administrator, W - writer, R - reader',
  `Locked` int(11) NOT NULL DEFAULT '3' COMMENT 'Cuva broj preostalih pokusaja logovanja sa pogresnim passwordom. Svaki pokusaj smanjuje broj za 1. Ako je 0 nalog je zakljucan.',
  `Status` int(11) NOT NULL DEFAULT '1' COMMENT '0-korisnik izbrisan; 1-korisnik aktivan'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`ID`, `Ime`, `Prezime`, `Username`, `Email`, `Password`, `API_key`, `Prava_pristupa`, `Locked`, `Status`) VALUES
(2, 'Kosta', 'Dimitrijevic', 'kosta', 'kosta@yahoo.com', 'kosta123', NULL, 'A', 3, 1),
(13, 'No', 'Photo', 'no photo', 'n@gmail.com', 'nnn111', NULL, 'A', 3, 1),
(49, 'Nikolina', 'Pavkovic', 'koko', 'nikolinap85@gmail.com', 'koko123', NULL, 'A', 3, 1),
(50, 'Ciri', 'Bu', 'ciri bu', 'ciri@bu.com', 'ccc111', NULL, 'W', 3, 0),
(51, 'Ciri', 'Bu', 'cici', 'cici@gmail.com', 'ccc111', NULL, 'W', 3, 0),
(52, 'hokus', 'pokus', 'hokus pokus', 'hokus@gmail.com', 'hhh111', NULL, 'W', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `korisnici_log`
--

CREATE TABLE `korisnici_log` (
  `ID` int(11) NOT NULL,
  `ID_korisnika` int(11) NOT NULL,
  `Ime` text NOT NULL,
  `Prezime` text NOT NULL,
  `Email` text NOT NULL,
  `Username` text NOT NULL,
  `Password` text NOT NULL,
  `API_key` int(11) NOT NULL,
  `Prava_pristupa` text NOT NULL,
  `Locked` int(11) NOT NULL,
  `Status` int(11) NOT NULL,
  `ID_admin` int(11) NOT NULL,
  `Data_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `korisnici_log`
--

INSERT INTO `korisnici_log` (`ID`, `ID_korisnika`, `Ime`, `Prezime`, `Email`, `Username`, `Password`, `API_key`, `Prava_pristupa`, `Locked`, `Status`, `ID_admin`, `Data_time`) VALUES
(1, 50, 'Ciri', 'Bu', 'ciri@bu.com', 'ciri bu', 'ccc111', 0, '0', 3, 1, 49, '2016-05-09 13:49:01'),
(2, 51, 'Ciri', 'Bu', 'cici@gmail.com', 'cici', 'ccc111', 0, '0', 3, 1, 49, '2016-05-09 13:56:00'),
(3, 52, 'hokus', 'pokus', 'hokus@gmail.com', 'hokus pokus', 'hhh111', 0, '0', 3, 1, 49, '2016-05-09 13:59:42'),
(8, 2, 'Kosta', 'Dimitrijevic', 'kosta@yahoo.com', 'kosta', 'kosta123', 0, '0', 3, 1, 49, '2016-05-10 12:27:24'),
(9, 2, 'Kosta', 'Dimitrijevic', 'kosta@yahoo.com', 'kosta', 'kosta123', 0, 'R', 3, 1, 49, '2016-05-10 12:41:52'),
(10, 2, 'Kosta', 'Dimitrijevic', 'kosta@yahoo.com', 'kosta', 'kosta123', 0, 'A', 3, 1, 49, '2016-05-10 12:44:05');

-- --------------------------------------------------------

--
-- Table structure for table `proizvodi`
--

CREATE TABLE `proizvodi` (
  `ID` int(11) NOT NULL,
  `Naziv` text NOT NULL,
  `Opis` text NOT NULL,
  `Cena` decimal(10,2) NOT NULL COMMENT 'Cena je izrazena u EUR.',
  `Status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `proizvodi`
--

INSERT INTO `proizvodi` (`ID`, `Naziv`, `Opis`, `Cena`, `Status`) VALUES
(1, 'Misty top', 'Women\'s  wear.', '1500.00', 1),
(2, 'Koko top', 'Women\'s day clothing.', '2000.00', 1),
(3, 'Air crew hoodie', 'Men\'s hoodie.', '6000.00', 1),
(4, 'Bon bon top', 'Summer women\'s top.', '2000.00', 1),
(5, 'Forest sweater', 'Men\'s cothing.', '5000.00', 1),
(6, 'Cheeky top', 'Women\'s clothing.', '12.17', 1),
(7, 'EW', 'EW', '2342.00', 1),
(8, 'Halji', 'jkhkhhj', '1312.00', 1),
(9, 'jhg', 'jkhkhkj', '3212.00', 1),
(10, 'hgj', 'jjb', '878.00', 1),
(11, 'kjhj', 'jhjh', '76.00', 1),
(12, 'Cheeky boots', 'Boots', '340.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `proizvodi_log`
--

CREATE TABLE `proizvodi_log` (
  `ID` int(11) NOT NULL,
  `ID_proizvoda` int(11) NOT NULL,
  `Naziv` text NOT NULL,
  `Opis` text NOT NULL,
  `Cena` decimal(10,2) NOT NULL,
  `Status` int(11) NOT NULL,
  `ID_admin` int(11) NOT NULL,
  `Date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `proizvodi_log`
--

INSERT INTO `proizvodi_log` (`ID`, `ID_proizvoda`, `Naziv`, `Opis`, `Cena`, `Status`, `ID_admin`, `Date_time`) VALUES
(1, 1, 'Misty top', 'Women\'s beach wear.', '1500.00', 1, 49, '2016-05-31 10:20:50'),
(2, 2, 'Kokita top', 'Women\'s summer clothing.', '1500.00', 1, 49, '2016-05-31 10:24:03'),
(3, 3, 'Air crew hoodie', 'Men\'s hoodie.', '6000.00', 1, 49, '2016-05-31 10:43:29'),
(4, 4, 'Kiki top', 'Summer women\'s top.', '2000.00', 1, 49, '2016-05-31 14:07:22'),
(5, 5, 'Forest sweater', 'Men\'s cothing.', '5000.00', 1, 49, '2016-06-01 14:08:08'),
(6, 6, 'Cheeky top', 'Women\'s clothing.', '12.17', 1, 49, '2016-06-14 11:54:04'),
(7, 2, 'Koko top', 'Women\'s summer clothing.', '1500.00', 1, 49, '2016-06-15 15:05:18'),
(8, 4, 'Kiki top', 'Summer women\'s top.', '2000.00', 1, 49, '2016-06-15 15:11:03'),
(9, 2, 'Koko top', 'Women\'s summer clothing.', '1500.00', 1, 49, '2016-06-16 09:25:12'),
(10, 7, 'EW', 'EW', '2342.00', 1, 49, '2017-02-12 21:59:39'),
(11, 8, 'Halji', 'jkhkhhj', '1312.00', 1, 49, '2017-02-13 20:38:11'),
(12, 9, 'jhg', 'jkhkhkj', '3212.00', 1, 49, '2017-02-13 21:21:46'),
(13, 10, 'hgj', 'jjb', '878.00', 1, 49, '2017-02-13 21:26:18'),
(14, 11, 'kjhj', 'jhjh', '76.00', 1, 49, '2017-02-13 21:30:10'),
(15, 12, 'Cheeky boots', 'Boots', '23424.00', 1, 49, '2017-02-25 19:58:16'),
(16, 12, 'Cheeky boots', 'Boots', '23424.00', 1, 49, '2017-02-25 19:58:37'),
(17, 1, 'Misty top', 'Women\'s beach wear.', '1500.00', 1, 49, '2017-02-27 17:56:53'),
(18, 1, 'Misty top', 'Women\'s beach wear.', '1500.00', 1, 49, '2017-02-27 22:02:55'),
(19, 1, 'Misty top', 'Women\'s night wear.', '1500.00', 1, 49, '2017-02-27 22:14:16'),
(20, 1, 'Misty top', 'Women\'s night wear.', '1500.00', 1, 49, '2017-02-27 22:18:23'),
(21, 1, 'Misty top', 'Women\'s night wear.', '1500.00', 1, 49, '2017-02-27 22:19:32'),
(22, 1, 'Misty top', 'Women\'s night wear.', '1500.00', 1, 49, '2017-02-27 22:20:49'),
(23, 1, 'Misty top', 'Women\'s  wear.', '1500.00', 1, 49, '2017-02-27 22:21:46'),
(24, 1, 'Misty top', 'Women\'s night wear.', '1500.00', 1, 49, '2017-02-27 22:22:59'),
(25, 2, 'Koko top', 'Women\'s clothing.', '2000.00', 1, 49, '2017-02-27 22:24:47'),
(26, 1, 'Misty top', 'Women\'s day wear.', '1500.00', 1, 49, '2017-02-27 22:28:07'),
(27, 1, 'Misty top', 'Women\'s day wear.', '1500.00', 1, 49, '2017-02-27 22:28:41'),
(28, 1, 'Misty top', 'Women\'s wear.', '1500.00', 1, 49, '2017-02-27 22:30:24'),
(29, 1, 'Misty top', 'Women\'s wear.', '1500.00', 1, 49, '2017-02-27 22:34:24'),
(30, 1, 'Misty top', 'Women\'s day wear.', '1500.00', 1, 49, '2017-02-27 22:36:41'),
(31, 1, 'Misty top', 'Women\'s  wear.', '1500.00', 1, 49, '2017-03-02 19:10:41'),
(32, 2, 'Koko top', 'Women\'s day clothing.', '2000.00', 1, 49, '2017-03-02 19:11:03'),
(33, 3, 'Air crew hoodie', 'Men\'s hoodie.', '6000.00', 1, 49, '2017-03-02 19:11:28'),
(34, 5, 'Forest sweater', 'Men\'s cothing.', '5000.00', 1, 49, '2017-03-02 19:11:56'),
(35, 5, 'Forest sweater', 'Men\'s cothing.', '5000.00', 1, 49, '2017-03-02 19:31:00'),
(36, 5, 'Forest sweater', 'Men\'s cothing.', '5000.00', 1, 49, '2017-03-02 19:32:27'),
(37, 5, 'Forest sweater', 'Men\'s cothing.', '5000.00', 1, 49, '2017-03-02 19:32:34'),
(38, 5, 'Forest sweater', 'Men\'s cothing.', '5000.00', 1, 49, '2017-03-02 19:38:09'),
(39, 5, 'Forest sweater', 'Men\'s cothing.', '5000.00', 1, 49, '2017-03-02 19:38:15'),
(40, 5, 'Forest sweater', 'Men\'s cothing.', '5000.00', 1, 49, '2017-03-02 19:38:19'),
(41, 5, 'Forest sweater', 'Men\'s cothing.', '5000.00', 1, 49, '2017-03-02 19:38:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `kategorija_proizvoda`
--
ALTER TABLE `kategorija_proizvoda`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `kategorija_proizvoda_log`
--
ALTER TABLE `kategorija_proizvoda_log`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `kategorije`
--
ALTER TABLE `kategorije`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `kategorije_log`
--
ALTER TABLE `kategorije_log`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `korisnici`
--
ALTER TABLE `korisnici`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `korisnici_log`
--
ALTER TABLE `korisnici_log`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `proizvodi`
--
ALTER TABLE `proizvodi`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `proizvodi_log`
--
ALTER TABLE `proizvodi_log`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `kategorija_proizvoda`
--
ALTER TABLE `kategorija_proizvoda`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `kategorija_proizvoda_log`
--
ALTER TABLE `kategorija_proizvoda_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `kategorije`
--
ALTER TABLE `kategorije`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `kategorije_log`
--
ALTER TABLE `kategorije_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `korisnici`
--
ALTER TABLE `korisnici`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `korisnici_log`
--
ALTER TABLE `korisnici_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `proizvodi`
--
ALTER TABLE `proizvodi`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `proizvodi_log`
--
ALTER TABLE `proizvodi_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
