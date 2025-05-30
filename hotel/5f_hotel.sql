-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 28, 2025 at 11:15 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `prenotazioni`
--

CREATE TABLE `prenotazioni` (
  `ID` int NOT NULL,
  `UtenteID` int NOT NULL,
  `StanzaID` int NOT NULL,
  `DataInizio` date NOT NULL,
  `DataFine` date NOT NULL,
  `DataPrenotazione` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Stato` enum('confermata','annullata') DEFAULT 'confermata'
) ;

-- --------------------------------------------------------

--
-- Table structure for table `stanze`
--

CREATE TABLE `stanze` (
  `ID` int NOT NULL,
  `HotelID` int NOT NULL,
  `NumeroStanza` varchar(10) NOT NULL,
  `TipoStanza` enum('singola','doppia') NOT NULL,
  `Prezzo` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utenti`
--

CREATE TABLE `utenti` (
  `ID` int NOT NULL,
  `Nome` varchar(250) NOT NULL,
  `Cognome` varchar(250) NOT NULL,
  `Email` varchar(250) NOT NULL,
  `Telefono` varchar(250) NOT NULL,
  `Password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `utenti`
--

INSERT INTO `utenti` (`ID`, `Nome`, `Cognome`, `Email`, `Telefono`, `Password`) VALUES
(1, 'Luca', 'Bianchi', 'luca.bianchi@example.com', '3331234567', '$2y$10$x3qISti14gOCyQV1AsRS2.6LE0J2LPMO4a97oUB0P841GBfv10.Qa'),
(2, 'Giulia', 'Rossi', 'giulia.rossi@example.com', '3337654321', '$2y$10$A5ERYb9YlDfPjPEJgNAdAOdZtzj8xaDnutLtLhOiLzYnDidcwqZT2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UtenteID` (`UtenteID`),
  ADD KEY `idx_prenotazioni_dates` (`StanzaID`,`DataInizio`,`DataFine`,`Stato`);

--
-- Indexes for table `stanze`
--
ALTER TABLE `stanze`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unique_room_per_hotel` (`HotelID`,`NumeroStanza`),
  ADD KEY `idx_stanze_hotel` (`HotelID`);

--
-- Indexes for table `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prenotazioni`
--
ALTER TABLE `prenotazioni`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stanze`
--
ALTER TABLE `stanze`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD CONSTRAINT `prenotazioni_ibfk_1` FOREIGN KEY (`UtenteID`) REFERENCES `utenti` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `prenotazioni_ibfk_2` FOREIGN KEY (`StanzaID`) REFERENCES `stanze` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `stanze`
--
ALTER TABLE `stanze`
  ADD CONSTRAINT `stanze_ibfk_1` FOREIGN KEY (`HotelID`) REFERENCES `hotel` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
