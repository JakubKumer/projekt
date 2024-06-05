-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 05 Cze 2024, 11:27
-- Wersja serwera: 10.4.27-MariaDB
-- Wersja PHP: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `projektinz`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `auctions`
--

CREATE TABLE `auctions` (
  `id_auction` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `start_price` double NOT NULL,
  `end_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Zrzut danych tabeli `auctions`
--

INSERT INTO `auctions` (`id_auction`, `id_user`, `title`, `description`, `start_price`, `end_time`) VALUES
(2, 1, 'daksijmiksadf', 'kgsodkgfs', 10, '2024-06-05 10:21:07'),
(3, 1, 'lpu', 'dsad ad ', 6, '2024-06-05 10:21:07'),
(8, 1, 'królik', 'ddsfsdfsdfsd', 3, '2024-06-05 10:21:07'),
(9, 1, 'królik', 'ddsfsdfsdfsd', 3, '2024-06-05 10:21:07'),
(10, 1, 'królik', 'ddsfsdfsdfsd', 3, '2024-06-05 10:21:07'),
(11, 1, 'dsds', 'Gówna puizda', 2, '2024-06-05 10:21:07'),
(12, 1, 'Zorza', 'piękna zorza nad bałtykiem', 10, '2024-06-05 10:21:07'),
(13, 1, 'ciastko', 'Pyszne ciastko', 2, '2024-06-05 10:21:07');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `auction_categories`
--

CREATE TABLE `auction_categories` (
  `id_auction` int(11) NOT NULL,
  `id_category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `bids`
--

CREATE TABLE `bids` (
  `id_bid` int(11) NOT NULL,
  `id_auction` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bid_amount` double NOT NULL,
  `bid_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `categories`
--

CREATE TABLE `categories` (
  `id_category` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `photos`
--

CREATE TABLE `photos` (
  `id_photo` int(11) NOT NULL,
  `id_auction` int(11) NOT NULL,
  `photo_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `reviews`
--

CREATE TABLE `reviews` (
  `id_review` int(11) NOT NULL,
  `id_auction` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `role`
--

CREATE TABLE `role` (
  `id` tinyint(4) NOT NULL,
  `role` enum('user','moderator','administrator') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Zrzut danych tabeli `role`
--

INSERT INTO `role` (`id`, `role`) VALUES
(1, 'user'),
(2, 'moderator'),
(3, 'administrator');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL,
  `id_role` tinyint(4) NOT NULL DEFAULT 1,
  `pass` varchar(255) NOT NULL,
  `passwordrepeat` varchar(255) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `house_number` varchar(10) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id_user`, `firstName`, `lastName`, `email`, `id_role`, `pass`, `passwordrepeat`, `city`, `street`, `house_number`, `postal_code`, `phone_number`) VALUES
(1, 'Kuba', 'Zguba', 'kuba@wp.pl', 3, '123', '', NULL, NULL, NULL, NULL, NULL),
(2, 'Kacper', 'łokieć', 'loki@wp.pl', 1, '123', '', NULL, NULL, NULL, NULL, NULL),
(3, 'marek', 'farek', 'marek@wp.pl', 1, '$2y$10$Ih/xQrlORgk6myj.tShom..qQbpI/VlUXnM.kJfAiw2Zgcuu7J0MC', '', NULL, NULL, NULL, NULL, NULL),
(4, 'dsa', 'dsa', 'fra@dsa.pl', 1, '123456789', '', NULL, NULL, NULL, NULL, NULL),
(7, 'piotr', 'jak', 'jak@wp.pl', 1, '$2y$10$MRCyAdsh.3ut/Ai66326Demi7Wf8/tzrOJMqQkCHGcbdMoRMhoV2S', '', NULL, NULL, NULL, NULL, NULL),
(8, 'Kacper', 'Lokiec', 'lokieta@wp.pl', 1, '$2y$10$wuzKf8xSkvbcp7abF0uMmeEE.XAz0ex9sSI..nyrd4w.liPaytIza', '', NULL, NULL, NULL, NULL, NULL),
(9, 'Jakub', 'Kakub', 'kakub@wp.pl', 1, '$2y$10$th03v48GNlfV4hWrMzNf6.zxO7VQtEs5GZ/2HRizUaTJ8oZOrCV8O', '', NULL, NULL, NULL, NULL, NULL),
(10, 'Hans', 'Dutch', 'hans@wp.pl', 3, 'Hans@12345', '', NULL, NULL, NULL, NULL, NULL),
(11, 'Franc', 'Stans', 'franc@wp.pl', 3, '$2y$10$Gw/0SO9ODPPYiaL99nMp9.EihQy02aSeepVolXAIEKI7rfC0PE6ba', '', NULL, NULL, NULL, NULL, NULL),
(12, 'Jakub', 'Kumer', 'Jckbob@wp.pl', 1, '$2y$10$I9OgdVhLdHHw7FcxiOl.8Op4lNq8wz1MHyee9CBFbZ9z/ll93i/hW', '', NULL, NULL, NULL, NULL, NULL),
(13, 'Kuva', 'dsadas', 'dsa@wp.pl', 1, '$2y$10$tZk6BfnSnjFVypDh9ktkaeQcpJeTL7HxtHNCyHUqwvtvI04nDrxTa', '', NULL, NULL, NULL, NULL, NULL),
(14, 'Kacper', 'Lokiec', 'lokiec@o2.pl', 1, '$2y$10$N795HHF/S.nYuMQMgOz8wuyMWjknBFWUPkdd3y6jXMxPkhDoTdVOS', '', NULL, NULL, NULL, NULL, NULL),
(15, 'Michal', 'Kacper', 'kacper2002@o2.pl', 1, '$2y$10$ky43k7dJDyrZO14sd0KZAugAZgPYLkCtPPi05w7PjyhHENWleYmS6', '', 'Zielona Góra', 'Widokowa', '11', '66-004', '518335834'),
(16, 'mateusz', 'Vateusz', 'mateusz@wp.pl', 1, '$2y$10$Nn1vBMqUCCw0VUv3k8P4VuW54AGgnE85I5NqpL6ZecRTTCULJoHfe', '$2y$10$Nn1vBMqUCCw0VUv3k8P4VuW54AGgnE85I5NqpL6ZecRTTCULJoHfe', NULL, NULL, NULL, NULL, NULL);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`id_auction`);

--
-- Indeksy dla tabeli `auction_categories`
--
ALTER TABLE `auction_categories`
  ADD KEY `id_auction` (`id_auction`),
  ADD KEY `id_category` (`id_category`);

--
-- Indeksy dla tabeli `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`id_bid`),
  ADD KEY `id_auction` (`id_auction`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeksy dla tabeli `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Indeksy dla tabeli `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id_photo`),
  ADD KEY `id_auction` (`id_auction`);

--
-- Indeksy dla tabeli `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `id_auction` (`id_auction`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeksy dla tabeli `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_role` (`id_role`),
  ADD KEY `id_role_2` (`id_role`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `auctions`
--
ALTER TABLE `auctions`
  MODIFY `id_auction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT dla tabeli `bids`
--
ALTER TABLE `bids`
  MODIFY `id_bid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `photos`
--
ALTER TABLE `photos`
  MODIFY `id_photo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `role`
--
ALTER TABLE `role`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `auction_categories`
--
ALTER TABLE `auction_categories`
  ADD CONSTRAINT `auction_categories_ibfk_1` FOREIGN KEY (`id_auction`) REFERENCES `auctions` (`id_auction`),
  ADD CONSTRAINT `auction_categories_ibfk_2` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`);

--
-- Ograniczenia dla tabeli `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`id_auction`) REFERENCES `auctions` (`id_auction`),
  ADD CONSTRAINT `bids_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ograniczenia dla tabeli `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`id_auction`) REFERENCES `auctions` (`id_auction`);

--
-- Ograniczenia dla tabeli `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`id_auction`) REFERENCES `auctions` (`id_auction`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ograniczenia dla tabeli `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
