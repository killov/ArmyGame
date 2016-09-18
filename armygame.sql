-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Ned 18. zář 2016, 11:16
-- Verze serveru: 5.6.26
-- Verze PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `armygame`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `akce`
--

CREATE TABLE IF NOT EXISTS `akce` (
  `id` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `mesto` int(11) DEFAULT NULL,
  `budova` int(11) DEFAULT NULL,
  `delka` int(11) DEFAULT NULL,
  `cas` int(11) DEFAULT NULL,
  `dokonceni` int(11) NOT NULL,
  `typ` int(11) DEFAULT NULL,
  `obchodniku` int(11) DEFAULT NULL,
  `surovina1` int(11) DEFAULT NULL,
  `surovina2` int(11) DEFAULT NULL,
  `surovina3` int(11) DEFAULT NULL,
  `surovina4` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL,
  `u1` int(11) NOT NULL,
  `u2` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `text` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `mapa`
--

CREATE TABLE IF NOT EXISTS `mapa` (
  `id` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `verze` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `mesto`
--

CREATE TABLE IF NOT EXISTS `mesto` (
  `id` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `blokx` int(11) NOT NULL,
  `bloky` int(11) NOT NULL,
  `typ` int(11) NOT NULL,
  `hrana` int(11) NOT NULL,
  `stat` int(11) NOT NULL,
  `statjmeno` varchar(20) NOT NULL,
  `hranice` int(11) NOT NULL,
  `dom` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `userjmeno` varchar(20) NOT NULL,
  `jmeno` varchar(20) NOT NULL,
  `surovina1` int(11) NOT NULL,
  `surovina1_produkce` int(11) NOT NULL,
  `surovina2` int(11) NOT NULL,
  `surovina2_produkce` int(11) NOT NULL,
  `surovina3` int(11) NOT NULL,
  `surovina3_produkce` int(11) NOT NULL,
  `surovina4` int(11) NOT NULL,
  `surovina4_produkce` int(11) NOT NULL,
  `suroviny_time` int(11) NOT NULL,
  `sklad` int(11) NOT NULL,
  `populace` int(11) NOT NULL,
  `b1` int(11) NOT NULL,
  `b2` int(11) NOT NULL,
  `b3` int(11) NOT NULL,
  `b4` int(11) NOT NULL,
  `b5` int(11) NOT NULL,
  `b6` int(11) NOT NULL,
  `b7` int(11) NOT NULL,
  `b8` int(11) NOT NULL,
  `b9` int(11) NOT NULL,
  `b10` int(11) NOT NULL,
  `b11` int(11) NOT NULL,
  `v1` int(11) NOT NULL,
  `v2` int(11) NOT NULL,
  `v3` int(11) NOT NULL,
  `v4` int(11) NOT NULL,
  `v5` int(11) NOT NULL,
  `v6` int(11) NOT NULL,
  `v7` int(11) NOT NULL,
  `v8` int(11) NOT NULL,
  `j1` int(11) NOT NULL,
  `j2` int(11) NOT NULL,
  `j3` int(11) NOT NULL,
  `j4` int(11) NOT NULL,
  `j5` int(11) NOT NULL,
  `j6` int(11) NOT NULL,
  `j7` int(11) NOT NULL,
  `j8` int(11) NOT NULL,
  `smrt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `stat`
--

CREATE TABLE IF NOT EXISTS `stat` (
  `id` int(11) NOT NULL,
  `jmeno` varchar(20) NOT NULL,
  `clenu` int(11) NOT NULL,
  `pop` int(11) NOT NULL,
  `poradi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `stat_pozvanky`
--

CREATE TABLE IF NOT EXISTS `stat_pozvanky` (
  `id` int(11) NOT NULL,
  `stat` int(11) NOT NULL,
  `statjmeno` varchar(20) NOT NULL,
  `user` int(11) NOT NULL,
  `userjmeno` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `tasks`
--

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL,
  `pro` int(11) NOT NULL,
  `typ` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `jmeno` varchar(20) NOT NULL,
  `heslo` varchar(32) NOT NULL,
  `email` varchar(30) NOT NULL,
  `mesto` int(11) NOT NULL,
  `pop` int(11) NOT NULL,
  `mest` int(11) NOT NULL,
  `poradi` int(11) NOT NULL,
  `zprava` int(11) NOT NULL,
  `stat` int(11) NOT NULL,
  `statjmeno` varchar(20) NOT NULL,
  `sp_all` int(11) NOT NULL,
  `penize` int(11) NOT NULL,
  `v1` int(11) NOT NULL,
  `v2` int(11) NOT NULL,
  `v3` int(11) NOT NULL,
  `v4` int(11) NOT NULL,
  `v5` int(11) NOT NULL,
  `banka` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `uzi`
--

CREATE TABLE IF NOT EXISTS `uzi` (
  `dada` int(11) NOT NULL,
  `asad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `ws_auth`
--

CREATE TABLE IF NOT EXISTS `ws_auth` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `akce`
--
ALTER TABLE `akce`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `mapa`
--
ALTER TABLE `mapa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `x` (`x`),
  ADD KEY `y` (`y`);

--
-- Klíče pro tabulku `mesto`
--
ALTER TABLE `mesto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blokx` (`blokx`),
  ADD KEY `bloky` (`bloky`),
  ADD KEY `user` (`user`),
  ADD KEY `x` (`x`),
  ADD KEY `y` (`y`),
  ADD KEY `typ` (`typ`);

--
-- Klíče pro tabulku `stat`
--
ALTER TABLE `stat`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `stat_pozvanky`
--
ALTER TABLE `stat_pozvanky`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `ws_auth`
--
ALTER TABLE `ws_auth`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `akce`
--
ALTER TABLE `akce`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `mapa`
--
ALTER TABLE `mapa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `mesto`
--
ALTER TABLE `mesto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `stat`
--
ALTER TABLE `stat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `stat_pozvanky`
--
ALTER TABLE `stat_pozvanky`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pro tabulku `ws_auth`
--
ALTER TABLE `ws_auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
