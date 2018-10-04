-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 03. Okt 2018 um 16:52
-- Server-Version: 10.1.16-MariaDB
-- PHP-Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `werner_rr`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bonus`
--

CREATE TABLE `bonus` (
  `user_id` int(11) NOT NULL,
  `last` int(11) NOT NULL,
  `invested` decimal(30,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `bonus`
--

INSERT INTO `bonus` (`user_id`, `last`, `invested`) VALUES
(1, 1474111207, '0.00'),
(4, 1474028832, '0.00'),
(5, 1474356516, '0.00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bugs`
--

CREATE TABLE `bugs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fahrer`
--

CREATE TABLE `fahrer` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `skill` double NOT NULL,
  `liga` tinyint(4) NOT NULL,
  `anteil` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fahrer`
--

INSERT INTO `fahrer` (`id`, `driver_id`, `user_id`, `name`, `skill`, `liga`, `anteil`) VALUES
(17, 140920162, 1, 'Driver-ID#81126', 256.646, 1, 15),
(19, 140920161, 1, 'Renner 1', 384.0133333333333, 2, 12),
(20, 140920163, 1, 'Driver-ID#2551', 321.8266666666667, 2, 13),
(21, 150920165, 1, 'Markus W', 767.996, 4, 11),
(23, 160920161, 1, 'Markus', 1029.0066666666667, 5, 10);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `faxes`
--

CREATE TABLE `faxes` (
  `id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `open` tinyint(1) NOT NULL,
  `date` int(11) NOT NULL,
  `betreff` tinytext NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `faxes`
--

INSERT INTO `faxes` (`id`, `to_id`, `from_id`, `open`, `date`, `betreff`, `message`) VALUES
(25, 1, 2, 1, 1442411884, '<i>No title</i>', 'dfefef'),
(27, 1, 1, 1, 1442412412, '<i>no_title</i>', 'ewfwefwefewfwef'),
(28, 1, 1, 1, 1442412430, '<i>Kein Betreff</i>', 'ewfwefwefewfwef'),
(76, 0, 0, 0, 1455633199, 'New League', 'Congratulations, you advanced to league 1!'),
(78, 0, 0, 0, 1455694684, 'New League', 'Congratulations, you advanced to league 1!'),
(80, 1, 1, 1, 1455696862, 'lol', 'okay'),
(91, 0, 0, 0, 1455700253, 'New League', 'Congratulations, you advanced to league 1!'),
(103, 0, 0, 0, 1455711548, 'New League', 'Congratulations, you advanced to league 1!'),
(105, 0, 0, 0, 1455713791, 'New League', 'Congratulations, you advanced to league 1!'),
(107, 0, 0, 0, 1455721242, 'New League', 'Congratulations, you advanced to league 1!'),
(109, 0, 0, 0, 1455783913, 'New League', 'Congratulations, you advanced to league 1!'),
(112, 0, 0, 0, 1455879318, 'New League', 'Congratulations, you advanced to league 1!'),
(113, 0, 0, 0, 1456128111, 'New League', 'Congratulations, you advanced to league 1!'),
(115, 0, 0, 0, 1456141992, 'New League', 'Congratulations, you advanced to league 1!'),
(116, 0, 0, 0, 1456149574, 'New League', 'Congratulations, you advanced to league 1!'),
(117, 0, 0, 0, 1456212877, 'New League', 'Congratulations, you advanced to league 1!'),
(118, 0, 0, 0, 1456219266, 'New League', 'Congratulations, you advanced to league 1!'),
(119, 0, 0, 0, 1456226615, 'New League', 'Congratulations, you advanced to league 1!'),
(120, 0, 0, 0, 1456234562, 'New League', 'Congratulations, you advanced to league 1!'),
(121, 0, 0, 0, 1456298652, 'New League', 'Congratulations, you advanced to league 1!'),
(125, 0, 0, 0, 1456299695, 'New League', 'Congratulations, you advanced to league 1!'),
(127, 0, 0, 0, 1456300436, 'New League', 'Congratulations, you advanced to league 1!'),
(128, 0, 0, 0, 1456300461, 'New League', 'Congratulations, you advanced to league 1!'),
(131, 0, 0, 0, 1456311884, 'New League', 'Congratulations, you advanced to league 1!'),
(132, 0, 0, 0, 1456315769, 'New League', 'Congratulations, you advanced to league 1!'),
(133, 0, 0, 0, 1456321226, 'New League', 'Congratulations, you advanced to league 1!'),
(134, 0, 0, 0, 1456323879, 'New League', 'Congratulations, you advanced to league 1!'),
(135, 0, 0, 0, 1456323911, 'New League', 'Congratulations, you advanced to league 1!'),
(136, 0, 0, 0, 1456324017, 'New League', 'Congratulations, you advanced to league 1!'),
(137, 0, 0, 0, 1456325523, 'New League', 'Congratulations, you advanced to league 1!'),
(138, 0, 0, 0, 1456325540, 'New League', 'Congratulations, you advanced to league 1!'),
(139, 1, 2, 1, 1456325554, 'test', 'tets'),
(140, 1, 2, 1, 1456325559, 'test', 'test'),
(141, 2, 2, 1, 1456325572, 'hallo', 'edeqwd <b>hallo</b>'),
(142, 0, 0, 0, 1456325574, 'New League', 'Congratulations, you advanced to league 1!'),
(144, 0, 0, 0, 1456325590, 'New League', 'Congratulations, you advanced to league 1!'),
(145, 0, 0, 0, 1456325647, 'New League', 'Congratulations, you advanced to league 1!'),
(148, 0, 0, 0, 1456395451, 'New League', 'Congratulations, you advanced to league 1!'),
(150, 0, 0, 0, 1456402836, 'New League', 'Congratulations, you advanced to league 1!'),
(151, 0, 0, 0, 1456405603, 'New League', 'Congratulations, you advanced to league 1!'),
(152, 0, 0, 0, 1456474304, 'New League', 'Congratulations, you advanced to league 1!'),
(153, 0, 0, 0, 1456483726, 'New League', 'Congratulations, you advanced to league 1!'),
(154, 0, 0, 0, 1459248641, 'New League', 'Congratulations, you advanced to league 1!'),
(155, 0, 0, 0, 1459930975, 'New League', 'Congratulations, you advanced to league 1!'),
(157, 0, 0, 0, 1459941530, 'New League', 'Congratulations, you advanced to league 1!'),
(173, 0, 0, 0, 1473681828, 'New League', 'Congratulations, you advanced to league 1!'),
(174, 0, 0, 0, 1473839313, 'New League', 'Congratulations, you advanced to league 1!'),
(175, 0, 0, 0, 1473841796, 'New League', 'Congratulations, you advanced to league 1!'),
(188, 0, 0, 0, 1473922567, 'New League', 'Congratulations, you advanced to league 1!'),
(189, 0, 0, 0, 1473922883, 'New League', 'Congratulations, you advanced to league 1!'),
(190, 0, 0, 0, 1473922883, 'New League', 'Congratulations, you advanced to league 1!'),
(191, 0, 0, 0, 1473923071, 'New League', 'Congratulations, you advanced to league 1!'),
(192, 0, 0, 0, 1473923146, 'New League', 'Congratulations, you advanced to league 1!'),
(193, 0, 0, 0, 1473923223, 'New League', 'Congratulations, you advanced to league 1!'),
(194, 0, 0, 0, 1473923251, 'New League', 'Congratulations, you advanced to league 1!'),
(195, 0, 0, 0, 1473923287, 'New League', 'Congratulations, you advanced to league 1!'),
(196, 0, 0, 0, 1473923348, 'New League', 'Congratulations, you advanced to league 1!'),
(200, 1, 0, 1, 1473931218, 'Pro Meisterschaft finished.', 'You made 924,75€ and 164,40 EP!'),
(201, 0, 0, 0, 1473937173, 'New League', 'Congratulations, you advanced to league 1!'),
(202, 0, 0, 0, 1473937178, 'New League', 'Congratulations, you advanced to league 1!'),
(203, 0, 0, 0, 1473937183, 'New League', 'Congratulations, you advanced to league 1!'),
(204, 0, 0, 0, 1473938523, 'New League', 'Congratulations, you advanced to league 1!'),
(205, 0, 0, 0, 1473938533, 'New League', 'Congratulations, you advanced to league 1!'),
(206, 0, 0, 0, 1473938580, 'New League', 'Congratulations, you advanced to league 1!'),
(207, 0, 0, 0, 1473938615, 'New League', 'Congratulations, you advanced to league 1!'),
(208, 0, 0, 0, 1473939659, 'New League', 'Congratulations, you advanced to league 1!'),
(209, 0, 0, 0, 1473939813, 'New League', 'Congratulations, you advanced to league 1!'),
(210, 0, 0, 0, 1473943150, 'New League', 'Congratulations, you advanced to league 1!'),
(211, 0, 0, 0, 1473943944, 'New League', 'Congratulations, you advanced to league 1!'),
(212, 0, 0, 0, 1473943988, 'New League', 'Congratulations, you advanced to league 1!'),
(213, 0, 0, 0, 1473944375, 'New League', 'Congratulations, you advanced to league 1!'),
(214, 0, 0, 0, 1473944385, 'New League', 'Congratulations, you advanced to league 1!'),
(215, 0, 0, 0, 1473944404, 'New League', 'Congratulations, you advanced to league 1!'),
(216, 0, 0, 0, 1473944476, 'New League', 'Congratulations, you advanced to league 1!'),
(217, 0, 0, 0, 1473944548, 'New League', 'Congratulations, you advanced to league 1!'),
(218, 0, 0, 0, 1473944894, 'New League', 'Congratulations, you advanced to league 1!'),
(219, 0, 0, 0, 1473945137, 'New League', 'Congratulations, you advanced to league 1!'),
(220, 0, 0, 0, 1473945148, 'New League', 'Congratulations, you advanced to league 1!'),
(221, 0, 0, 0, 1473945165, 'New League', 'Congratulations, you advanced to league 1!'),
(222, 0, 0, 0, 1473945246, 'New League', 'Congratulations, you advanced to league 1!'),
(223, 0, 0, 0, 1474012511, 'New League', 'Congratulations, you advanced to league 1!'),
(224, 0, 0, 0, 1474012694, 'New League', 'Congratulations, you advanced to league 1!'),
(225, 0, 0, 0, 1474012841, 'New League', 'Congratulations, you advanced to league 1!'),
(226, 0, 0, 0, 1474013635, 'New League', 'Congratulations, you advanced to league 1!'),
(227, 0, 0, 0, 1474013638, 'New League', 'Congratulations, you advanced to league 1!'),
(228, 0, 0, 0, 1474013666, 'New League', 'Congratulations, you advanced to league 1!'),
(229, 0, 0, 0, 1474013755, 'New League', 'Congratulations, you advanced to league 1!'),
(230, 0, 0, 0, 1474013816, 'New League', 'Congratulations, you advanced to league 1!'),
(231, 0, 0, 0, 1474013881, 'New League', 'Congratulations, you advanced to league 1!'),
(232, 0, 0, 0, 1474013894, 'New League', 'Congratulations, you advanced to league 1!'),
(233, 0, 0, 0, 1474013967, 'New League', 'Congratulations, you advanced to league 1!'),
(234, 0, 0, 0, 1474014146, 'New League', 'Congratulations, you advanced to league 1!'),
(235, 0, 0, 0, 1474014348, 'New League', 'Congratulations, you advanced to league 1!'),
(236, 0, 0, 0, 1474014507, 'New League', 'Congratulations, you advanced to league 1!'),
(237, 0, 0, 0, 1474014509, 'New League', 'Congratulations, you advanced to league 1!'),
(238, 0, 0, 0, 1474014646, 'New League', 'Congratulations, you advanced to league 1!'),
(239, 0, 0, 0, 1474014676, 'New League', 'Congratulations, you advanced to league 1!'),
(240, 0, 0, 0, 1474014736, 'New League', 'Congratulations, you advanced to league 1!'),
(241, 0, 0, 0, 1474014742, 'New League', 'Congratulations, you advanced to league 1!'),
(242, 0, 0, 0, 1474014750, 'New League', 'Congratulations, you advanced to league 1!'),
(243, 0, 0, 0, 1474014751, 'New League', 'Congratulations, you advanced to league 1!'),
(244, 0, 0, 0, 1474014834, 'New League', 'Congratulations, you advanced to league 1!'),
(245, 0, 0, 0, 1474014855, 'New League', 'Congratulations, you advanced to league 1!'),
(246, 0, 0, 0, 1474014865, 'New League', 'Congratulations, you advanced to league 1!'),
(247, 0, 0, 0, 1474014908, 'New League', 'Congratulations, you advanced to league 1!'),
(248, 0, 0, 0, 1474014931, 'New League', 'Congratulations, you advanced to league 1!'),
(250, 0, 0, 0, 1474019701, 'New League', 'Congratulations, you advanced to league 1!'),
(251, 0, 0, 0, 1474028813, 'New League', 'Congratulations, you advanced to league 1!'),
(252, 0, 0, 0, 1474028846, 'New League', 'Congratulations, you advanced to league 1!'),
(253, 5, 0, 1, 1474028894, 'Anfänger Rennen finished.', 'You made 7,38€ and 4,43 EP!'),
(254, 5, 0, 1, 1474028931, 'New League', 'Congratulations, you advanced to league 2!'),
(255, 0, 0, 0, 1474029538, 'New League', 'Congratulations, you advanced to league 1!'),
(256, 1, 0, 1, 1474030786, 'Anfänger Rennen finished.', 'You made 40,44€ and 9,71 EP!'),
(257, 0, 0, 0, 1474104801, 'New League', 'Congratulations, you advanced to league 1!'),
(258, 1, 0, 1, 1474104806, 'Pro Meisterschaft finished.', 'You made 929,25€ and 165,20 EP!'),
(259, 1, 0, 1, 1474104806, 'Amateur Rennen finished.', 'You made 95,46€ and 58,60 EP!'),
(260, 1, 0, 1, 1474107509, 'Anfänger Rennen finished.', 'You made 42,33€ and 10,16 EP!'),
(261, 0, 0, 0, 1474111192, 'New League', 'Congratulations, you advanced to league 1!'),
(262, 1, 0, 1, 1474111198, 'Pro Meisterschaft finished.', 'You made 1.040,18€ and 184,92 EP!'),
(263, 0, 0, 0, 1474356127, 'New League', 'Congratulations, you advanced to league 1!'),
(264, 1, 0, 0, 1474356489, 'Anfänger Ausdauerrennen finished.', 'You made 246,64€ and 23,02 EP!'),
(265, 0, 0, 0, 1474356495, 'New League', 'Congratulations, you advanced to league 1!'),
(266, 0, 0, 0, 1474356495, 'New League', 'Congratulations, you advanced to league 1!'),
(267, 5, 0, 0, 1474356499, 'Anfänger Ausdauerrennen finished.', 'You made 46,37€ and 10,82 EP!'),
(268, 0, 0, 0, 1474357053, 'New League', 'Congratulations, you advanced to league 1!');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `garage`
--

CREATE TABLE `garage` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` tinytext NOT NULL,
  `sell` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `garage`
--

INSERT INTO `garage` (`id`, `user_id`, `car_id`, `sell`) VALUES
(47, 1, 'beamer_pole', 0),
(48, 1, 'hatcher_conq', 0),
(49, 1, 'hatcher_vision', 0),
(50, 1, 'hatcher_vision', 0),
(51, 1, 'hatcher_legendr', 0),
(52, 1, 'hatcher_conq', 0),
(53, 4, 'beamer_pole', 0),
(54, 1, 'santini_azzuro', 0),
(55, 3, 'beamer_pole', 0),
(56, 1, 'beamer_adler', 0),
(57, 4, 'beamer_pole', 0),
(58, 5, 'beamer_pole', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `loggedin`
--

CREATE TABLE `loggedin` (
  `user_id` int(11) NOT NULL,
  `token` tinytext NOT NULL,
  `created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `loggedin`
--

INSERT INTO `loggedin` (`user_id`, `token`, `created`) VALUES
(1, 'c+43vVeo@3w4Q6g7sFkFeNRgN@K3w8cHLOuO6SiyDC8IJjYb3g@9dQmu3SauqMzn', 1474357057),
(2, '', 1474014885),
(4, '', 1474028818),
(5, '', 1474356499);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `new_cars`
--

CREATE TABLE `new_cars` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `title` text NOT NULL,
  `ps` int(11) NOT NULL,
  `perf` int(11) NOT NULL,
  `preis` int(11) NOT NULL,
  `liga` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `new_cars`
--

INSERT INTO `new_cars` (`id`, `name`, `title`, `ps`, `perf`, `preis`, `liga`) VALUES
(1, 'beamer_pole', 'Beamer Pole', 20, 20, 2400, 1),
(2, 'beamer_gulf', 'Beamer Gulf', 80, 70, 12000, 2),
(3, 'lmp_fx1', 'Lampadati FX1', 450, 700, 320000, 7),
(4, 'lmp_fxs', 'Lampadati FX-s.', 645, 800, 833000, 8),
(5, 'santini_figurati', 'Santini Figurati', 55, 50, 8000, 1),
(6, 'santini_azzuro', 'Santini Azzuro', 85, 100, 16000, 2),
(7, 'santini_scusi', 'Santini Scusi', 160, 300, 41000, 4),
(8, 'santini_dolciamaro', 'Santini Dolciamaro ''74', 220, 400, 59400, 5),
(9, 'santini_rubacuori', 'Santini Rubacuori', 420, 650, 320000, 7),
(10, 'lmp_g5s', 'Lampadati G5-s.', 370, 400, 125000, 6),
(11, 'lmp_g6', 'Lampadati G6', 500, 620, 412499, 7),
(12, 'lmp_g7', 'Lampadati G7', 600, 750, 900000, 8),
(13, 'beamer_adler', 'Beamer Adler ', 135, 120, 27000, 3),
(14, 'beamer_gulfr', 'Beamer Gulf R', 200, 200, 55000, 5),
(15, 'beamer_adlerm', 'Beamer Adler (M)', 300, 300, 85000, 6),
(17, 'hatcher_vision', 'Hatcher Vision', 45, 40, 7800, 1),
(18, 'hatcher_legend', 'Hatcher Legend', 90, 70, 13000, 2),
(20, 'hatcher_crusader', 'Hatcher Crusader', 110, 130, 20000, 3),
(21, 'hatcher_fires', 'Hatcher Fire S', 190, 200, 40000, 4),
(22, 'hatcher_firem', 'Hatcher Fire M', 222, 320, 64000, 5),
(23, 'hatcher_legendr', 'Hatcher Legend R', 250, 270, 69000, 5),
(24, 'hatcher_firel', 'Hatcher Fire L', 300, 400, 97340, 6),
(25, 'hatcher_centurion', 'Hatcher Centurion', 400, 490, 235000, 7),
(26, 'hatcher_conq', 'Hatcher Conquerer', 700, 720, 850000, 8),
(27, 'jonda_apex', 'Jõnda Apex', 65, 30, 8000, 1),
(29, 'jonda_aura', 'Jõnda Aura', 100, 60, 13300, 2),
(30, 'jonda_nightline', 'Jõnda Nightline', 130, 120, 21000, 3),
(31, 'jonda_legacy', 'Jõnda Legacy', 210, 120, 44000, 4),
(32, 'jonda_motion', 'Jõnda Motion', 270, 180, 72000, 5),
(33, 'jonda_legacyst', 'Jõnda Legacy ST', 350, 300, 159000, 6),
(34, 'jonda_realm', 'Jõnda Realm', 320, 315, 115000, 6);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `parts`
--

CREATE TABLE `parts` (
  `id` int(11) NOT NULL,
  `kat` tinytext NOT NULL,
  `part` tinytext NOT NULL,
  `liga` int(11) NOT NULL,
  `preis` int(11) NOT NULL,
  `worst` int(11) NOT NULL,
  `best` int(11) NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `parts`
--

INSERT INTO `parts` (`id`, `kat`, `part`, `liga`, `preis`, `worst`, `best`, `duration`) VALUES
(1, 'motor', 'nockenwelle', 1, 500, 2, 10, 60),
(3, 'motor', 'kolben', 3, 4000, 8, 15, 1002),
(4, 'auspuff', 'sportesd', 1, 200, 1, 5, 60),
(5, 'auspuff', 'msd', 0, 1000, 2, 8, 200),
(6, 'bremse', 'gelochte', 1, 1000, 5, 15, 10),
(7, 'turbo', 'ladeluftkuehler', 0, 5000, 20, 50, 10),
(8, 'schaltung', 'getriebe', 1, 1000, 7, 14, 100),
(10, 'auspuff', 'kruemmer', 1, 500, 1, 4, 40),
(11, 'motor', 'schwungrad', 0, 400, 3, 8, 20),
(12, 'motor', 'kolben', 1, 400, 3, 7, 120),
(13, 'motor', 'kolben', 2, 1000, 5, 10, 300),
(15, 'turbo', 'turbocharger', 1, 5000, 20, 50, 500),
(16, 'auspuff', 'katalysator', 1, 300, 1, 3, 23),
(17, 'auspuff', 'katalysator', 2, 1000, 2, 5, 123),
(18, 'schaltung', 'doppelkupplung', 1, 1000, 5, 10, 300),
(19, 'schaltung', 'doppelkupplung', 2, 3000, 8, 17, 700),
(21, 'motor', 'nockenwelle', 2, 1500, 5, 15, 320),
(22, 'motor', 'nockenwelle', 3, 3000, 8, 20, 800),
(23, 'motor', 'nockenwelle', 4, 10000, 10, 20, 1800),
(24, 'motor', 'nockenwelle', 5, 25000, 14, 25, 3600),
(25, 'motor', 'nockenwelle', 6, 60000, 20, 29, 6000),
(26, 'motor', 'nockenwelle', 7, 150000, 28, 32, 13000),
(27, 'motor', 'nockenwelle', 8, 400000, 23, 40, 17000),
(28, 'schaltung', 'doppelkupplung', 3, 10000, 12, 25, 600),
(29, 'schaltung', 'doppelkupplung', 4, 19000, 18, 30, 1000),
(30, 'schaltung', 'doppelkupplung', 5, 44000, 23, 38, 2000),
(31, 'schaltung', 'doppelkupplung', 6, 100000, 30, 42, 3000),
(32, 'schaltung', 'doppelkupplung', 7, 350000, 33, 45, 5000),
(33, 'schaltung', 'doppelkupplung', 8, 440200, 40, 55, 11000),
(34, 'bremse', 'gelochte', 2, 1500, 6, 18, 800),
(35, 'bremse', 'gelochte', 3, 3000, 7, 20, 1200),
(36, 'bremse', 'gelochte', 4, 6700, 10, 24, 1700),
(37, 'bremse', 'gelochte', 5, 15000, 14, 30, 3600),
(38, 'bremse', 'gelochte', 6, 44000, 18, 34, 6500),
(39, 'bremse', 'gelochte', 7, 120000, 20, 38, 8000),
(40, 'bremse', 'gelochte', 8, 299000, 30, 50, 25000),
(41, 'turbo', 'turbocharger', 2, 12000, 30, 60, 2000),
(42, 'turbo', 'turbocharger', 3, 30000, 70, 80, 7200),
(43, 'turbo', 'turbocharger', 4, 70000, 75, 90, 12000),
(44, 'turbo', 'turbocharger', 5, 12000, 100, 130, 20000),
(45, 'turbo', 'turbocharger', 6, 400000, 130, 170, 25000),
(46, 'turbo', 'turbocharger', 7, 775000, 200, 250, 40000),
(47, 'turbo', 'turbocharger', 8, 820000, 300, 500, 80000),
(49, 'schaltung', 'getriebe', 2, 2000, 9, 18, 200),
(50, 'schaltung', 'getriebe', 3, 3400, 10, 21, 400),
(51, 'schaltung', 'getriebe', 4, 5000, 13, 25, 750),
(52, 'schaltung', 'getriebe', 5, 12000, 20, 30, 1200),
(53, 'schaltung', 'getriebe', 6, 40000, 25, 35, 2300),
(54, 'schaltung', 'getriebe', 7, 100000, 30, 38, 4000),
(55, 'schaltung', 'getriebe', 8, 225000, 35, 45, 7200),
(56, 'auspuff', 'sportesd', 2, 600, 2, 10, 240),
(57, 'auspuff', 'sportesd', 3, 1300, 4, 15, 600),
(58, 'auspuff', 'sportesd', 4, 2500, 8, 18, 900),
(59, 'auspuff', 'sportesd', 5, 6000, 10, 22, 1200),
(60, 'auspuff', 'sportesd', 6, 11000, 13, 28, 2800),
(61, 'auspuff', 'sportesd', 7, 30000, 17, 35, 3600),
(62, 'auspuff', 'sportesd', 8, 99000, 25, 40, 5000),
(65, 'auspuff', 'kruemmer', 2, 800, 3, 6, 70),
(66, 'auspuff', 'kruemmer', 3, 1200, 5, 8, 140),
(67, 'auspuff', 'kruemmer', 4, 2120, 7, 10, 200),
(68, 'auspuff', 'kruemmer', 5, 2800, 10, 13, 540),
(69, 'auspuff', 'kruemmer', 6, 6230, 12, 15, 840),
(70, 'auspuff', 'kruemmer', 7, 18540, 15, 20, 1200),
(71, 'auspuff', 'kruemmer', 8, 65000, 20, 25, 1600),
(72, 'auspuff', 'katalysator', 3, 2000, 4, 8, 230),
(73, 'auspuff', 'katalysator', 4, 4000, 8, 14, 360),
(74, 'auspuff', 'katalysator', 5, 6000, 10, 18, 1200),
(75, 'auspuff', 'katalysator', 6, 14900, 13, 21, 1500),
(76, 'auspuff', 'katalysator', 7, 35000, 15, 24, 1800),
(77, 'auspuff', 'katalysator', 8, 99500, 20, 35, 3600),
(78, 'motor', 'kolben', 4, 10000, 12, 20, 1400),
(79, 'motor', 'kolben', 5, 22000, 18, 25, 2200),
(80, 'motor', 'kolben', 6, 74750, 22, 30, 3000),
(81, 'motor', 'kolben', 7, 223000, 25, 35, 4300),
(82, 'motor', 'kolben', 8, 480000, 32, 40, 7200);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `races`
--

CREATE TABLE `races` (
  `id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `liga` tinyint(4) NOT NULL,
  `ps` smallint(6) NOT NULL,
  `dur` int(11) NOT NULL,
  `reward` int(11) NOT NULL,
  `exp` mediumint(9) NOT NULL,
  `exp_needed` int(11) NOT NULL,
  `sprit_needed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `races`
--

INSERT INTO `races` (`id`, `name`, `liga`, `ps`, `dur`, `reward`, `exp`, `exp_needed`, `sprit_needed`) VALUES
(1, 'beginner_race', 1, 90, 30, 50, 30, 0, 10),
(2, 'beginner_cup', 1, 100, 180, 100, 40, 0, 10),
(3, 'beginner_end', 1, 140, 900, 300, 70, 100, 50),
(4, 'beginner_master', 1, 200, 500, 600, 60, 350, 25),
(5, 'amateur_race', 2, 150, 300, 130, 70, 350, 60),
(6, 'amateur_cup', 2, 180, 1800, 400, 100, 400, 80),
(7, 'amateur_end', 2, 200, 36000, 900, 250, 800, 200),
(8, 'amateur_master', 2, 250, 7000, 1500, 500, 2000, 140),
(9, 'pro_race', 3, 200, 1000, 500, 1000, 2000, 120),
(10, 'pro_cup', 3, 270, 3000, 1000, 200, 3000, 180),
(11, 'pro_end', 3, 220, 40000, 4000, 700, 6000, 340),
(12, 'pro_master', 3, 350, 8000, 1500, 400, 8000, 290),
(13, 'exp_race', 4, 230, 2000, 1000, 300, 10000, 220),
(14, 'exp_cup', 4, 260, 8000, 1700, 450, 14000, 350),
(15, 'exp_end', 4, 310, 53240, 5000, 4000, 46000, 700),
(16, 'exp_master', 4, 380, 14437, 3200, 2000, 57000, 450),
(17, 'med_race', 5, 280, 3600, 2200, 500, 64000, 300),
(18, 'med_cup', 5, 320, 12000, 3000, 700, 90000, 440),
(19, 'med_end', 5, 380, 72000, 6500, 800, 150000, 1000),
(20, 'med_master', 5, 430, 23210, 4500, 500, 180000, 670),
(21, 'int_race', 6, 400, 3600, 2420, 2500, 200000, 400),
(22, 'int_drag', 6, 500, 500, 700, 130, 220000, 150),
(23, 'int_cup', 6, 430, 8000, 4300, 4500, 400000, 600),
(24, 'int_end', 6, 410, 86400, 12000, 5000, 600000, 1800),
(25, 'int_master', 6, 490, 33022, 8000, 3000, 700000, 999),
(26, 'exp_drag', 4, 400, 300, 250, 100, 12000, 80),
(27, 'eli_race', 7, 550, 3800, 5000, 4000, 800000, 600),
(28, 'eli_cup', 7, 600, 14000, 12000, 7000, 1000000, 1000),
(29, 'eli_drag', 7, 730, 500, 1000, 600, 1200000, 260),
(30, 'eli_end', 7, 640, 129600, 24000, 13000, 1350000, 4200),
(31, 'eli_master', 7, 750, 34000, 15000, 10000, 1500000, 2600),
(32, 'black_race', 8, 700, 3600, 15000, 9000, 2000000, 1100),
(33, 'black_drag', 8, 900, 800, 3000, 3000, 2300000, 440),
(34, 'black_end', 8, 850, 172800, 40000, 40000, 3000000, 8000),
(35, 'black_master', 8, 1000, 12800, 19000, 20000, 4000000, 6700);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `races_run`
--

CREATE TABLE `races_run` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `race_id` int(11) NOT NULL,
  `time_end` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sprit`
--

CREATE TABLE `sprit` (
  `id` int(11) NOT NULL,
  `title` tinytext NOT NULL,
  `liga` tinyint(4) NOT NULL,
  `lit` float NOT NULL,
  `cost` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `sprit`
--

INSERT INTO `sprit` (`id`, `title`, `liga`, `lit`, `cost`) VALUES
(1, 'arbeiter', 1, 0.5, 1500),
(2, 'machines', 2, 1, 7500),
(3, 'pur', 3, 2, 17000),
(4, 'chef', 4, 3.7, 45000),
(5, 'marketing', 5, 7, 103000),
(6, 'manager', 6, 10.4, 199000),
(7, 'invest', 7, 15, 475630),
(8, 'place', 8, 24, 1000000);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sprit_upt`
--

CREATE TABLE `sprit_upt` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `updated` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `sprit_upt`
--

INSERT INTO `sprit_upt` (`id`, `user_id`, `updated`) VALUES
(1, 1, 1474357070),
(2, 2, 1474014907),
(3, 3, 1456325523),
(4, 4, 1474028845),
(5, 5, 1474357052);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sprit_usr`
--

CREATE TABLE `sprit_usr` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sprit_id` int(11) NOT NULL,
  `count` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `sprit_usr`
--

INSERT INTO `sprit_usr` (`id`, `user_id`, `sprit_id`, `count`) VALUES
(1, 1, 1, 55),
(2, 1, 4, 13),
(3, 1, 2, 18),
(4, 1, 3, 14),
(5, 1, 7, 11),
(6, 1, 8, 12),
(7, 1, 6, 11),
(8, 1, 5, 5),
(9, 2, 1, 2),
(10, 3, 1, 2),
(11, 4, 1, 1),
(12, 5, 1, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stats`
--

CREATE TABLE `stats` (
  `id` int(11) NOT NULL,
  `money` decimal(30,2) NOT NULL,
  `liga` smallint(6) NOT NULL,
  `exp` int(11) NOT NULL,
  `sprit` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `stats`
--

INSERT INTO `stats` (`id`, `money`, `liga`, `exp`, `sprit`) VALUES
(1, '11033348773.36', 8, 5058, 10000),
(2, '758.35', 1, 0, 10000),
(3, '239.80', 1, 0, 74.5968),
(4, '4500.00', 1, 0, 186),
(5, '2201.91', 2, 312, 9294.92);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `storage`
--

CREATE TABLE `storage` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `garage_id` int(11) NOT NULL,
  `liga` int(11) NOT NULL,
  `part` tinytext NOT NULL,
  `value` int(11) NOT NULL,
  `sell` double NOT NULL,
  `sell_date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `storage`
--

INSERT INTO `storage` (`id`, `user_id`, `part_id`, `garage_id`, `liga`, `part`, `value`, `sell`, `sell_date`) VALUES
(45, 4, 11, 53, 1, 'schwungrad', 3, 0, 0),
(46, 1, 1, 0, 1, 'nockenwelle', 8, 423, 1442489563),
(47, 1, 8, 0, 1, 'getriebe', 376, 34134, 1442489538),
(48, 1, 11, 0, 1, 'schwungrad', 2, 234, 1442489578),
(49, 1, 4, 47, 1, 'sportesd', 2, 0, 0),
(50, 1, 1, 0, 1, 'nockenwelle', 6, 2134, 1442489527),
(51, 1, 1, 0, 1, 'nockenwelle', 6, 234, 1442489529),
(52, 1, 1, 0, 1, 'nockenwelle', 10, 23, 1442489523),
(53, 1, 1, 0, 1, 'nockenwelle', 10, 23, 1442471645),
(54, 1, 1, 0, 1, 'nockenwelle', 7, 23, 1442489531),
(55, 4, 11, 0, 1, 'schwungrad', 1, 0, 0),
(56, 1, 1, 0, 1, 'nockenwelle', 11, 546, 1442408828),
(57, 1, 1, 0, 1, 'nockenwelle', 10, 234, 1442489575),
(58, 1, 11, 0, 1, 'schwungrad', 3, 432, 1442489571),
(59, 1, 3, 39, 3, 'kolben', 22, 0, 0),
(60, 1, 2, 39, 2, 'schwungrad', 12, 0, 0),
(61, 1, 13, 0, 2, 'kolben', 3, 234, 1442489525),
(62, 1, 12, 47, 1, 'kolben', 2, 0, 0),
(63, 1, 19, 47, 2, 'doppelkupplung', 91, 0, 0),
(64, 1, 19, 54, 2, 'doppelkupplung', 14, 0, 0),
(65, 1, 11, 0, 1, 'schwungrad', 3, 0, 0),
(66, 1, 1, 49, 1, 'nockenwelle', 13, 0, 0),
(67, 1, 1, 0, 1, 'nockenwelle', 7, 0, 0),
(68, 1, 15, 0, 1, 'turbocharger', 60, 0, 0),
(69, 1, 15, 49, 1, 'turbocharger', 80, 0, 0),
(70, 1, 3, 0, 3, 'kolben', 20, 100, 1474021632),
(71, 1, 2, 0, 2, 'schwungrad', 9, 100, 1455694779),
(72, 1, 3, 0, 3, 'kolben', 29, 100, 1473856972),
(73, 1, 17, 54, 2, 'katalysator', 2, 0, 0),
(74, 1, 41, 54, 2, 'turbocharger', 49, 0, 0),
(75, 1, 27, 48, 8, 'nockenwelle', 38, 0, 0),
(76, 1, 2, 54, 2, 'schwungrad', 14, 0, 0),
(77, 1, 13, 54, 2, 'kolben', 3, 0, 0),
(78, 1, 21, 0, 2, 'nockenwelle', 5, 0, 0),
(79, 1, 19, 0, 2, 'doppelkupplung', 12, 0, 0),
(80, 1, 1, 0, 1, 'nockenwelle', 6, 0, 0),
(81, 1, 1, 0, 1, 'nockenwelle', 7, 0, 0),
(82, 1, 1, 0, 1, 'nockenwelle', 5, 0, 0),
(83, 1, 10, 49, 1, 'kruemmer', 3, 0, 0),
(84, 1, 10, 0, 1, 'kruemmer', 2, 0, 0),
(85, 1, 10, 0, 1, 'kruemmer', 2, 0, 0),
(86, 1, 4, 49, 1, 'sportesd', 1, 0, 0),
(87, 1, 10, 0, 1, 'kruemmer', 1, 0, 0),
(88, 1, 1, 0, 1, 'nockenwelle', 5, 0, 0),
(89, 1, 1, 0, 1, 'nockenwelle', 4, 0, 0),
(90, 1, 1, 0, 1, 'nockenwelle', 4, 0, 0),
(91, 1, 21, 0, 2, 'nockenwelle', 9, 0, 0),
(92, 2, 1, 0, 1, 'nockenwelle', 3, 0, 0),
(93, 1, 66, 0, 3, 'kruemmer', 5, 0, 0),
(94, 1, 1, 0, 1, 'nockenwelle', 9, 0, 0),
(95, 1, 42, 0, 3, 'turbocharger', 72, 0, 0),
(96, 1, 22, 0, 3, 'nockenwelle', 11, 0, 0),
(97, 1, 35, 0, 3, 'gelochte', 10, 0, 0),
(98, 1, 47, 0, 8, 'turbocharger', 361, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `storage_run`
--

CREATE TABLE `storage_run` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `time_end` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `pass` text NOT NULL,
  `email` text NOT NULL,
  `regdate` int(11) NOT NULL,
  `lang` char(2) NOT NULL,
  `lastlogin` int(11) NOT NULL,
  `token` tinytext NOT NULL,
  `token_date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
-- Fehler beim Lesen der Daten: (#2006 - MySQL server has gone away)

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bonus`
--
ALTER TABLE `bonus`
  ADD PRIMARY KEY (`user_id`);

--
-- Indizes für die Tabelle `bugs`
--
ALTER TABLE `bugs`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `fahrer`
--
ALTER TABLE `fahrer`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `faxes`
--
ALTER TABLE `faxes`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `garage`
--
ALTER TABLE `garage`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `loggedin`
--
ALTER TABLE `loggedin`
  ADD PRIMARY KEY (`user_id`);

--
-- Indizes für die Tabelle `new_cars`
--
ALTER TABLE `new_cars`
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`);

--
-- Indizes für die Tabelle `parts`
--
ALTER TABLE `parts`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indizes für die Tabelle `races`
--
ALTER TABLE `races`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `races_run`
--
ALTER TABLE `races_run`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `sprit`
--
ALTER TABLE `sprit`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `sprit_upt`
--
ALTER TABLE `sprit_upt`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indizes für die Tabelle `sprit_usr`
--
ALTER TABLE `sprit_usr`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `stats`
--
ALTER TABLE `stats`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `storage`
--
ALTER TABLE `storage`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `storage_run`
--
ALTER TABLE `storage_run`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `bugs`
--
ALTER TABLE `bugs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `fahrer`
--
ALTER TABLE `fahrer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT für Tabelle `faxes`
--
ALTER TABLE `faxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=269;
--
-- AUTO_INCREMENT für Tabelle `garage`
--
ALTER TABLE `garage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT für Tabelle `new_cars`
--
ALTER TABLE `new_cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT für Tabelle `parts`
--
ALTER TABLE `parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
--
-- AUTO_INCREMENT für Tabelle `races`
--
ALTER TABLE `races`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT für Tabelle `races_run`
--
ALTER TABLE `races_run`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `sprit`
--
ALTER TABLE `sprit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT für Tabelle `sprit_upt`
--
ALTER TABLE `sprit_upt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT für Tabelle `sprit_usr`
--
ALTER TABLE `sprit_usr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT für Tabelle `storage`
--
ALTER TABLE `storage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;
--
-- AUTO_INCREMENT für Tabelle `storage_run`
--
ALTER TABLE `storage_run`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
  
  