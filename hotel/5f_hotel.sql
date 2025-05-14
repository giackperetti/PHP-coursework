-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 14, 2025 at 11:13 AM
-- Server version: 8.0.35
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `5f_hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `ID` int NOT NULL,
  `Denominazione` varchar(50) NOT NULL,
  `Localita` varchar(50) NOT NULL,
  `Indirizzo` varchar(100) NOT NULL,
  `Telefono` varchar(10) NOT NULL,
  `Categoria` tinyint NOT NULL,
  `NumeroCamere` smallint NOT NULL,
  `Foto` varchar(255) DEFAULT NULL
) ;

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`ID`, `Denominazione`, `Localita`, `Indirizzo`, `Telefono`, `Categoria`, `NumeroCamere`, `Foto`) VALUES
(1, 'Hotel Bellavista', 'Como', 'Via Lago 12', '0311234567', 5, 50, 'bellavista.jpeg'),
(2, 'Albergo Centrale', 'Bologna', 'Piazza Maggiore 5', '0519876543', 3, 35, 'centrale.jpeg'),
(3, 'Residenza Mare Blu', 'Rimini', 'Viale del Sole 101', '0541278990', 4, 80, 'mare_blu.jpeg'),
(4, 'Hotel Tranquillità', 'Assisi', 'Via San Francesco 33', '0752233445', 2, 20, 'tranquillita.jpeg'),
(5, 'Montagna Verde Lodge', 'Cortina d\'Ampezzo', 'Via Dolomiti 8', '0436555123', 3, 40, 'verde_lodge.jpeg'),
(6, 'Hotel Aurora', 'Roma', 'Via della Luce 45', '0654321987', 4, 60, 'aurora.jpeg'),
(7, 'Hotel Sole Mare', 'Napoli', 'Lungomare Caracciolo 10', '0813345567', 3, 80, 'sole_mare.jpeg'),
(8, 'Albergo Trentino', 'Trento', 'Piazza Duomo 3', '0461123456', 2, 40, 'trentino.jpeg'),
(9, 'Grand Hotel Milano', 'Milano', 'Corso Buenos Aires 55', '0287654321', 5, 120, 'grand_hotel.jpeg'),
(10, 'Locanda del Lago', 'Como', 'Via Lario 18', '0312233445', 3, 25, 'locanda.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
