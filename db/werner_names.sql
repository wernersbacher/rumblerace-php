-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 17. Apr 2019 um 21:45
-- Server-Version: 10.1.36-MariaDB
-- PHP-Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `werner_names`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chn_first`
--

CREATE TABLE `chn_first` (
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `chn_first`
--

INSERT INTO `chn_first` (`name`) VALUES
('Tan'),
('Yu');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chn_last`
--

CREATE TABLE `chn_last` (
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `chn_last`
--

INSERT INTO `chn_last` (`name`) VALUES
('Duyi'),
('Luli');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `de_first`
--

CREATE TABLE `de_first` (
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `de_first`
--

INSERT INTO `de_first` (`name`) VALUES
('Hans'),
('Maria'),
('Markus'),
('Otto'),
('Sebastian');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `de_last`
--

CREATE TABLE `de_last` (
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `de_last`
--

INSERT INTO `de_last` (`name`) VALUES
('Brecht'),
('Frentzen'),
('Mann'),
('Müller'),
('Schmitt'),
('Schäfer'),
('Vettel'),
('Werner');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `en_first`
--

CREATE TABLE `en_first` (
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `en_first`
--

INSERT INTO `en_first` (`name`) VALUES
('Harley'),
('Lewis');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `en_last`
--

CREATE TABLE `en_last` (
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `en_last`
--

INSERT INTO `en_last` (`name`) VALUES
('Garrett'),
('Simon');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fr_first`
--

CREATE TABLE `fr_first` (
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fr_first`
--

INSERT INTO `fr_first` (`name`) VALUES
('Margaux '),
('Mathieu');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fr_last`
--

CREATE TABLE `fr_last` (
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fr_last`
--

INSERT INTO `fr_last` (`name`) VALUES
('Bellamy'),
('D’Aramitz');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ru_first`
--

CREATE TABLE `ru_first` (
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `ru_first`
--

INSERT INTO `ru_first` (`name`) VALUES
('Katia'),
('Veniamin');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ru_last`
--

CREATE TABLE `ru_last` (
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `ru_last`
--

INSERT INTO `ru_last` (`name`) VALUES
('Petrov'),
('Semenova');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `chn_first`
--
ALTER TABLE `chn_first`
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `chn_last`
--
ALTER TABLE `chn_last`
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `de_first`
--
ALTER TABLE `de_first`
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `de_last`
--
ALTER TABLE `de_last`
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `en_first`
--
ALTER TABLE `en_first`
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `en_last`
--
ALTER TABLE `en_last`
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `fr_first`
--
ALTER TABLE `fr_first`
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `fr_last`
--
ALTER TABLE `fr_last`
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `ru_first`
--
ALTER TABLE `ru_first`
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `ru_last`
--
ALTER TABLE `ru_last`
  ADD UNIQUE KEY `name` (`name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
