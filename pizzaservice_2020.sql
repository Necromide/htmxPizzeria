-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 25. Sep 2023 um 14:17
-- Server-Version: 10.4.28-MariaDB
-- PHP-Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `pizzaservice_2020`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `picture` varchar(254) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `article`
--

INSERT INTO `article` (`id`, `name`, `picture`, `price`) VALUES
(1, 'Salami', 'Images/salami.jpg', 8.49),
(2, 'Vegetarisch', 'Images/vegetarisch.jpg', 12.99),
(3, 'Spinat-Hühnchen', 'Images/spinat.jpg', 11.99);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ordered_articles`
--

CREATE TABLE `ordered_articles` (
  `id` int(11) NOT NULL,
  `f_article_id` int(11) NOT NULL,
  `f_order_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ordering`
--

CREATE TABLE `ordering` (
  `id` int(11) NOT NULL,
  `address` varchar(200) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ordered_articles`
--
ALTER TABLE `ordered_articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_article` (`f_article_id`) USING BTREE,
  ADD KEY `index_order` (`f_order_id`) USING BTREE;

--
-- Indizes für die Tabelle `ordering`
--
ALTER TABLE `ordering`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `ordered_articles`
--
ALTER TABLE `ordered_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT für Tabelle `ordering`
--
ALTER TABLE `ordering`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `ordered_articles`
--
ALTER TABLE `ordered_articles`
  ADD CONSTRAINT `ordered_articles_ibfk_1` FOREIGN KEY (`f_order_id`) REFERENCES `ordering` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ordered_articles_ibfk_2` FOREIGN KEY (`f_article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
