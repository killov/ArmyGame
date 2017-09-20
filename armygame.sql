-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Čtv 21. zář 2017, 00:45
-- Verze serveru: 10.1.24-MariaDB
-- Verze PHP: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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

CREATE TABLE `akce` (
  `id` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `mesto` int(11) DEFAULT NULL,
  `cil` int(11) NOT NULL,
  `budova` int(11) DEFAULT NULL,
  `delka` int(11) DEFAULT NULL,
  `cas` int(11) DEFAULT NULL,
  `dokonceni` int(11) NOT NULL,
  `typ` int(11) DEFAULT NULL,
  `typ_jednotky` int(11) NOT NULL,
  `obchodniku` int(11) DEFAULT NULL,
  `surovina1` int(11) DEFAULT NULL,
  `surovina2` int(11) DEFAULT NULL,
  `surovina3` int(11) DEFAULT NULL,
  `surovina4` int(11) DEFAULT NULL,
  `j1` int(11) NOT NULL,
  `j2` int(11) NOT NULL,
  `j3` int(11) NOT NULL,
  `j4` int(11) NOT NULL,
  `j5` int(11) NOT NULL,
  `j6` int(11) NOT NULL,
  `j7` int(11) NOT NULL,
  `j8` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `cesta`
--

CREATE TABLE `cesta` (
  `id` int(11) NOT NULL,
  `start_x` int(11) NOT NULL,
  `start_y` int(11) NOT NULL,
  `target_x` int(11) NOT NULL,
  `target_y` int(11) NOT NULL,
  `cesta` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `chat`
--

CREATE TABLE `chat` (
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

CREATE TABLE `mapa` (
  `id` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `blokx` int(11) NOT NULL,
  `bloky` int(11) NOT NULL,
  `typ` int(11) NOT NULL,
  `hrana` int(11) NOT NULL,
  `stat` int(11) NOT NULL,
  `hranice` int(11) NOT NULL,
  `dom` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `mapa_bloky`
--

CREATE TABLE `mapa_bloky` (
  `id` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `verze` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `mesto`
--

CREATE TABLE `mesto` (
  `id` int(11) NOT NULL DEFAULT '0',
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `typ` int(11) NOT NULL,
  `stat` int(11) NOT NULL,
  `statjmeno` varchar(20) NOT NULL,
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
-- Struktura tabulky `podpory`
--

CREATE TABLE `podpory` (
  `id` int(11) NOT NULL,
  `mesto` int(11) NOT NULL,
  `kde` int(11) NOT NULL,
  `j1` int(11) NOT NULL,
  `j2` int(11) NOT NULL,
  `j3` int(11) NOT NULL,
  `j4` int(11) NOT NULL,
  `j5` int(11) NOT NULL,
  `j6` int(11) NOT NULL,
  `j7` int(11) NOT NULL,
  `j8` int(11) NOT NULL,
  `surovina1` int(11) NOT NULL,
  `surovina2` int(11) NOT NULL,
  `surovina3` int(11) NOT NULL,
  `surovina4` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `pohyb`
--

CREATE TABLE `pohyb` (
  `id` int(11) NOT NULL,
  `akce` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `cas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `stat`
--

CREATE TABLE `stat` (
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

CREATE TABLE `stat_pozvanky` (
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

CREATE TABLE `tasks` (
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

CREATE TABLE `users` (
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

CREATE TABLE `uzi` (
  `dada` int(11) NOT NULL,
  `asad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `ws_auth`
--

CREATE TABLE `ws_auth` (
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
-- Klíče pro tabulku `cesta`
--
ALTER TABLE `cesta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `start_x` (`start_x`),
  ADD KEY `start_y` (`start_y`),
  ADD KEY `target_x` (`target_x`),
  ADD KEY `target_y` (`target_y`);

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
  ADD KEY `blokx` (`blokx`),
  ADD KEY `bloky` (`bloky`),
  ADD KEY `x` (`x`),
  ADD KEY `y` (`y`),
  ADD KEY `typ` (`typ`);

--
-- Klíče pro tabulku `mapa_bloky`
--
ALTER TABLE `mapa_bloky`
  ADD PRIMARY KEY (`id`),
  ADD KEY `x` (`x`),
  ADD KEY `y` (`y`);

--
-- Klíče pro tabulku `mesto`
--
ALTER TABLE `mesto`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `podpory`
--
ALTER TABLE `podpory`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `pohyb`
--
ALTER TABLE `pohyb`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pro tabulku `cesta`
--
ALTER TABLE `cesta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pro tabulku `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;
--
-- AUTO_INCREMENT pro tabulku `mapa`
--
ALTER TABLE `mapa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160001;
--
-- AUTO_INCREMENT pro tabulku `mapa_bloky`
--
ALTER TABLE `mapa_bloky`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1601;
--
-- AUTO_INCREMENT pro tabulku `podpory`
--
ALTER TABLE `podpory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pro tabulku `pohyb`
--
ALTER TABLE `pohyb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pro tabulku `ws_auth`
--
ALTER TABLE `ws_auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
