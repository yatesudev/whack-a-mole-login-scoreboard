-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 14. Mrz 2021 um 16:50
-- Server-Version: 10.4.17-MariaDB
-- PHP-Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `login_goralewski`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `scoreboard`
--

CREATE TABLE `scoreboard` (
  `Username` varchar(50) NOT NULL,
  `Points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `scoreboard`
--

INSERT INTO `scoreboard` (`Username`, `Points`) VALUES
('test', 12),
('test1', 11);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `Username` varchar(255) NOT NULL,
  `Passwort` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`Username`, `Passwort`) VALUES
('test', '$2y$10$hH0uVZNjVSuUumfdtSqt6ecwz62ZlrcUxluYm/Hro3Ojhpc9OqebW'),
('test1', '$2y$10$jj7P1K6Btl/ci3Z3/flHIOJSn8uEd7r55JBR4L1ersDrKllqdk.XK');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `scoreboard`
--
ALTER TABLE `scoreboard`
  ADD PRIMARY KEY (`Username`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`Username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
