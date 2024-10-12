-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
<<<<<<< HEAD
-- Generation Time: Paź 10, 2024 at 09:54 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12
=======
-- Generation Time: Paź 08, 2024 at 11:18 AM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.2.4
>>>>>>> 20275a825c54e7c954d08ca742e4d295306f52d4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projektinz`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `auctions`
--

CREATE TABLE `auctions` (
  `id_auction` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `image` varchar(255) NOT NULL,
  `start_price` double NOT NULL,
  `end_time` datetime NOT NULL DEFAULT current_timestamp(),
  `highest_bidder_id` int(11) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `auctions`
--

<<<<<<< HEAD
INSERT INTO `auctions` (`id_auction`, `id_user`, `id_category`, `title`, `description`, `image`, `start_price`, `end_time`, `highest_bidder_id`, `status`) VALUES
(15, 20, 1, 'Pies', 'vbdfgndfgngfdnnfgnn', '../uploads/pies.jpg', 432423432423, '2024-06-17 10:27:51', 24, 'completed'),
(16, 16, 2, 'lambo', 'super szybki samochod', '../uploads/_0e078736-0ae0-4f31-9662-a46dcb69d1a2.jpg', 200000, '2024-06-17 10:45:30', NULL, 'completed'),
(23, 20, 2, 'Audi A5 2.0', 'Super samochód na daily. Jeden z lepszych do użytku na codzień. ', '../uploads/_2fa9aca3-ab82-4f5b-9c64-7e70ea5217f7.jpg', 323000, '2024-09-29 13:37:20', 20, 'active'),
(24, 20, 3, 'Piłka', 'Piłka adidas Tango 12 Polska ukraina Euro 2012. Nowa, nie śmigana.', '../uploads/images.jpg', 41, '2024-09-29 13:42:29', 24, 'active'),
(25, 21, 6, 'Iphone 14 pro max 64Gb', 'Uzywany iphone. w pelni sprawny ', '../uploads/pobrane.jpg', 3000, '2024-09-29 13:46:50', NULL, 'active'),
(26, 21, 6, 'Iphone 8 32Gb', 'Iphone 8 ślady użycia w stattracku', '../uploads/pobrane (1).jpg', 899, '2024-09-29 13:48:34', NULL, 'active'),
(27, 22, 8, 'Krem na haluksy', 'Super efketywny krem na haluksy. znikaja w mig ', '../uploads/pobrane (2).jpg', 12, '2024-09-29 13:54:09', NULL, 'active'),
(28, 22, 8, 'ibuprofen', 'tabletki na ból głowy ', '../uploads/pobrane (3).jpg', 7, '2024-09-29 13:55:10', 20, 'active'),
(29, 23, 9, 'Pan Tadeusz', 'Produkt z bilbioteki publicznej, ślady użytku', '../uploads/pobrane (4).jpg', 4, '2024-09-29 14:34:37', NULL, 'active'),
(32, 23, 9, 'Indiana Jones film', 'Film reżyserii Stevena Spielberga z lat 90-tych. W roli głownej Harrison Ford.', '../uploads/images (1).jpg', 13, '2024-09-29 14:39:14', 23, 'active'),
(33, 24, 10, 'Ławka ogrodowa z Prawdziwego Drewna', 'ławka zrobiona z Dębowego drewna.', '../uploads/pobrane (5).jpg', 399, '2024-09-29 14:41:46', NULL, 'active'),
(34, 24, 10, 'Lodówka Samsung Ekologiczna', 'Lodównka samsung nowa. Jedna z lepszych', '../uploads/pobrane (6).jpg', 2000, '2024-09-29 14:42:44', 23, 'active'),
(35, 24, 11, 'Krem do rąk', 'Krem nawilżający do rąk', '../uploads/pobrane (7).jpg', 2, '2024-09-29 14:46:14', NULL, 'active'),
(36, 24, 11, 'Krem Do twarzy', 'Krem do twarzy zapychajacy pory ', '../uploads/images (2).jpg', 4, '2024-09-29 14:47:23', NULL, 'active'),
(37, 20, 6, 'sdsfdfsdffsd', '1saxgbdfgnbngb g   ', '../uploads/pobrane (5).jpg', 32, '2024-10-04 15:11:13', NULL, 'active');
=======
INSERT INTO `auctions` (`id_auction`, `id_user`, `id_category`, `title`, `description`, `image`, `start_price`, `end_time`, `highest_bidder_id`) VALUES
(15, 16, 1, 'Pies', 'vbdfgndfgngfdnnfgnn', '../uploads/pies.jpg', 432423432423, '2024-06-17 10:27:51', 24),
(16, 16, 2, 'lambo', 'super szybki samochod', '../uploads/_0e078736-0ae0-4f31-9662-a46dcb69d1a2.jpg', 200000, '2024-06-17 10:45:30', NULL),
(23, 20, 2, 'Audi A5 2.0', 'Super samochód na daily. Jeden z lepszych do użytku na codzień. ', '../uploads/_2fa9aca3-ab82-4f5b-9c64-7e70ea5217f7.jpg', 322000, '2024-09-29 13:37:20', NULL),
(24, 20, 3, 'Piłka', 'Piłka adidas Tango 12 Polska ukraina Euro 2012. Nowa, nie śmigana.', '../uploads/images.jpg', 41, '2024-09-29 13:42:29', 24),
(25, 21, 6, 'Iphone 14 pro max 64Gb', 'Uzywany iphone. w pelni sprawny ', '../uploads/pobrane.jpg', 3000, '2024-09-29 13:46:50', NULL),
(26, 21, 6, 'Iphone 8 32Gb', 'Iphone 8 ślady użycia w stattracku', '../uploads/pobrane (1).jpg', 899, '2024-09-29 13:48:34', NULL),
(27, 22, 8, 'Krem na haluksy', 'Super efketywny krem na haluksy. znikaja w mig ', '../uploads/pobrane (2).jpg', 12, '2024-09-29 13:54:09', NULL),
(28, 22, 8, 'ibuprofen', 'tabletki na ból głowy ', '../uploads/pobrane (3).jpg', 7, '2024-09-29 13:55:10', 25),
(29, 23, 9, 'Pan Tadeusz', 'Produkt z bilbioteki publicznej, ślady użytku', '../uploads/pobrane (4).jpg', 4, '2024-09-29 14:34:37', NULL),
(32, 23, 9, 'Indiana Jones film', 'Film reżyserii Stevena Spielberga z lat 90-tych. W roli głownej Harrison Ford.', '../uploads/images (1).jpg', 13, '2024-09-29 14:39:14', 23),
(33, 24, 10, 'Ławka ogrodowa z Prawdziwego Drewna', 'ławka zrobiona z Dębowego drewna.', '../uploads/pobrane (5).jpg', 399, '2024-09-29 14:41:46', NULL),
(34, 24, 10, 'Lodówka Samsung Ekologiczna', 'Lodównka samsung nowa. Jedna z lepszych', '../uploads/pobrane (6).jpg', 2000, '2024-09-29 14:42:44', 23),
(35, 24, 11, 'Krem do rąk', 'Krem nawilżający do rąk', '../uploads/pobrane (7).jpg', 2, '2024-09-29 14:46:14', NULL),
(36, 24, 11, 'Krem Do twarzy', 'Krem do twarzy zapychajacy pory ', '../uploads/images (2).jpg', 4, '2024-09-29 14:47:23', NULL),
(37, 25, 7, 'produkciak', 'Produkciak z biedry', '../uploads/YL38uhw.gif', 120, '2024-10-14 11:36:23', NULL),
(38, 26, 7, 'Mystery Box', 'Mystery Box z turniej Kit Kat! ', '../uploads/Fvdx8cXXsAMehzZ.jpeg', 200, '2024-10-15 10:41:14', 26);
>>>>>>> 20275a825c54e7c954d08ca742e4d295306f52d4

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

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_category`, `category_name`) VALUES
(1, 'Zwierzęta'),
(2, 'Motoryzacja'),
(3, 'Sport'),
(4, 'Moda'),
(6, 'Elektronika'),
(7, 'Dziecko'),
(8, 'Zdrowie'),
(9, 'Kultura i rozrywka'),
(10, 'Dom i ogród'),
(11, 'Uroda');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `completed_auctions`
--

CREATE TABLE `completed_auctions` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `highest_bidder_id` int(11) DEFAULT NULL,
  `payment_status` enum('zaplacone','niezaplacone') DEFAULT 'niezaplacone',
  `delivery_status` enum('odebrane','nieodebrane') DEFAULT 'nieodebrane',
  `is_send` enum('tak','nie') DEFAULT 'nie'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `completed_auctions`
--

INSERT INTO `completed_auctions` (`id`, `title`, `image`, `description`, `end_time`, `price`, `status`, `id_user`, `highest_bidder_id`, `payment_status`, `delivery_status`, `is_send`) VALUES
(15, 'Pies', '../uploads/pies.jpg', 'vbdfgndfgngfdnnfgnn', '2024-06-17 10:27:51', 99.99, 'completed', 20, 24, 'zaplacone', 'nieodebrane', 'tak'),
(16, 'lambo', '../uploads/_0e078736-0ae0-4f31-9662-a46dcb69d1a2.jpg', 'super szybki samochod', '2024-06-17 10:45:30', 200000.00, 'completed', 21, NULL, 'niezaplacone', 'nieodebrane', 'nie');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `favorites`
--

CREATE TABLE `favorites` (
  `id_favorite` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_auction` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id_favorite`, `id_user`, `id_auction`) VALUES
(9, 20, 23),
(7, 23, 16),
(5, 23, 32),
(6, 23, 34),
(10, 25, 28),
(11, 26, 38);

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
  `id_user` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `date` datetime NOT NULL,
  `id_seller` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id_review`, `id_user`, `rating`, `comment`, `date`, `id_seller`) VALUES
(1, 25, 5, 'Świetna aukcja!', '2024-10-07 10:00:00', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `role`
--

CREATE TABLE `role` (
  `id` tinyint(4) NOT NULL,
  `role` enum('user','moderator','administrator') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `role`
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
  `phone_number` varchar(20) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT '../uploads/user-3331256_1280.png',
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

<<<<<<< HEAD
INSERT INTO `users` (`id_user`, `firstName`, `lastName`, `email`, `id_role`, `pass`, `passwordrepeat`, `city`, `street`, `house_number`, `postal_code`, `phone_number`, `profile_image`) VALUES
(1, 'Kuba', 'Zguba', 'kuba@wp.pl', 3, '123', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(2, 'Kacper', 'łokieć', 'loki@wp.pl', 1, '123', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(3, 'marek', 'farek', 'marek@wp.pl', 1, '$2y$10$Ih/xQrlORgk6myj.tShom..qQbpI/VlUXnM.kJfAiw2Zgcuu7J0MC', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(4, 'dsa', 'dsa', 'fra@dsa.pl', 1, '123456789', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(7, 'piotr', 'jak', 'jak@wp.pl', 1, '$2y$10$MRCyAdsh.3ut/Ai66326Demi7Wf8/tzrOJMqQkCHGcbdMoRMhoV2S', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(8, 'Kacper', 'Lokiec', 'lokieta@wp.pl', 1, '$2y$10$wuzKf8xSkvbcp7abF0uMmeEE.XAz0ex9sSI..nyrd4w.liPaytIza', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(9, 'Jakub', 'Kakub', 'kakub@wp.pl', 1, '$2y$10$th03v48GNlfV4hWrMzNf6.zxO7VQtEs5GZ/2HRizUaTJ8oZOrCV8O', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(10, 'Hans', 'Dutch', 'hans@wp.pl', 3, 'Hans@12345', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(11, 'Franc', 'Stans', 'franc@wp.pl', 3, '$2y$10$Gw/0SO9ODPPYiaL99nMp9.EihQy02aSeepVolXAIEKI7rfC0PE6ba', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(12, 'Jakub', 'Kumer', 'Jckbob@wp.pl', 1, '$2y$10$I9OgdVhLdHHw7FcxiOl.8Op4lNq8wz1MHyee9CBFbZ9z/ll93i/hW', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(13, 'Kuva', 'dsadas', 'dsa@wp.pl', 1, '$2y$10$tZk6BfnSnjFVypDh9ktkaeQcpJeTL7HxtHNCyHUqwvtvI04nDrxTa', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(14, 'Kacper', 'Lokiec', 'lokiec@o2.pl', 1, '$2y$10$N795HHF/S.nYuMQMgOz8wuyMWjknBFWUPkdd3y6jXMxPkhDoTdVOS', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(15, 'Michal', 'Kacper', 'kacper2002@o2.pl', 1, '$2y$10$ky43k7dJDyrZO14sd0KZAugAZgPYLkCtPPi05w7PjyhHENWleYmS6', '', 'Zielona Góra', 'Widokowa', '11', '66-004', '518335834', '../uploads/user-3331256_1280.png'),
(16, 'mateusz', 'Vateusz', 'mateusz@wp.pl', 1, '$2y$10$Nn1vBMqUCCw0VUv3k8P4VuW54AGgnE85I5NqpL6ZecRTTCULJoHfe', '$2y$10$Nn1vBMqUCCw0VUv3k8P4VuW54AGgnE85I5NqpL6ZecRTTCULJoHfe', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(18, 'dsadas', 'ghfnbnvnbv', 'god@wp.pl', 1, '$2y$10$yItGTuPy/NZqEqOyFv0PSutR8jIQ9XthfAmiEAkHLqVygprPTI9pC', '$2y$10$yItGTuPy/NZqEqOyFv0PSutR8jIQ9XthfAmiEAkHLqVygprPTI9pC', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(19, 'Jakub', 'Kumer', 'kumi@wp.pl', 1, '$2y$10$nqzr/owleiVa2eRKUxJlyewSommA3Q7aDBecC715V.wZ5RVX9Qz1u', '$2y$10$nqzr/owleiVa2eRKUxJlyewSommA3Q7aDBecC715V.wZ5RVX9Qz1u', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(20, 'fdsa', 'fdsafsd', 'cwel@wp.pl', 1, '$2y$10$Ra8saP9vwZqkncp5njEf0.7i9W/JRaFk3E/7VFxRIS3r3o.1HSo.q', '$2y$10$Ra8saP9vwZqkncp5njEf0.7i9W/JRaFk3E/7VFxRIS3r3o.1HSo.q', '', '', '', '', '', '../uploads/66f55fd1ba159.jpg'),
(21, 'Maja', 'Stasko', 'stasko@wp.pl', 1, '$2y$10$Bhg1eReVIn7g7XaUlQMIGe6gu9mnKc4ZX1ExBBnfzb/R1UxgSU.K2', '$2y$10$Bhg1eReVIn7g7XaUlQMIGe6gu9mnKc4ZX1ExBBnfzb/R1UxgSU.K2', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(22, 'Firma', 'Farmaceutyczna', 'farm@wp.pl', 1, '$2y$10$4McZ8/7ZcwJ6Odc7UxjjL.pje6VRzQy3Ai64TYyWQjCwMN8QhiLa.', '$2y$10$4McZ8/7ZcwJ6Odc7UxjjL.pje6VRzQy3Ai64TYyWQjCwMN8QhiLa.', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(23, 'biblioteka', 'poznanska', 'biblio@wp.pl', 3, '$2y$10$2aU0pqSutdJpxVPwV4kE0ONK9a.rW5AzgdyohwruyPo9cceVMDBaG', '$2y$10$2aU0pqSutdJpxVPwV4kE0ONK9a.rW5AzgdyohwruyPo9cceVMDBaG', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(24, 'Basia', 'Kowal', 'kowal@wp.pl', 1, '$2y$10$JDAh8aYZIErcu.NOpQZms.zoyrJuKv58zxRNZz.eVzYJatXAIx6wG', '$2y$10$JDAh8aYZIErcu.NOpQZms.zoyrJuKv58zxRNZz.eVzYJatXAIx6wG', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png'),
(25, 'Jakub', 'walek', 'walek@interia.pl', 1, '$2y$10$0uA0A0W05H5vQKWeDyLlYOJ0ZKIbDaWA5Hv3/U.BkONd9tQ4hx5K.', '$2y$10$0uA0A0W05H5vQKWeDyLlYOJ0ZKIbDaWA5Hv3/U.BkONd9tQ4hx5K.', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png');
=======
INSERT INTO `users` (`id_user`, `firstName`, `lastName`, `email`, `id_role`, `pass`, `passwordrepeat`, `city`, `street`, `house_number`, `postal_code`, `phone_number`, `profile_image`, `reset_token`, `token_expiry`) VALUES
(1, 'Kuba', 'Zguba', 'kuba@wp.pl', 3, '123', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(2, 'Kacper', 'łokieć', 'loki@wp.pl', 1, '123', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(3, 'marek', 'farek', 'marek@wp.pl', 1, '$2y$10$Ih/xQrlORgk6myj.tShom..qQbpI/VlUXnM.kJfAiw2Zgcuu7J0MC', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(4, 'dsa', 'dsa', 'fra@dsa.pl', 1, '123456789', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(7, 'piotr', 'jak', 'jak@wp.pl', 1, '$2y$10$MRCyAdsh.3ut/Ai66326Demi7Wf8/tzrOJMqQkCHGcbdMoRMhoV2S', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(8, 'Kacper', 'Lokiec', 'lokieta@wp.pl', 1, '$2y$10$wuzKf8xSkvbcp7abF0uMmeEE.XAz0ex9sSI..nyrd4w.liPaytIza', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(9, 'Jakub', 'Kakub', 'kakub@wp.pl', 1, '$2y$10$th03v48GNlfV4hWrMzNf6.zxO7VQtEs5GZ/2HRizUaTJ8oZOrCV8O', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(10, 'Hans', 'Dutch', 'hans@wp.pl', 3, 'Hans@12345', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(11, 'Franc', 'Stans', 'franc@wp.pl', 3, '$2y$10$Gw/0SO9ODPPYiaL99nMp9.EihQy02aSeepVolXAIEKI7rfC0PE6ba', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(12, 'Jakub', 'Kumer', 'Jckbob@wp.pl', 1, '$2y$10$I9OgdVhLdHHw7FcxiOl.8Op4lNq8wz1MHyee9CBFbZ9z/ll93i/hW', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(13, 'Kuva', 'dsadas', 'dsa@wp.pl', 1, '$2y$10$tZk6BfnSnjFVypDh9ktkaeQcpJeTL7HxtHNCyHUqwvtvI04nDrxTa', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(14, 'Kacper', 'Lokiec', 'lokiec@o2.pl', 1, '$2y$10$N795HHF/S.nYuMQMgOz8wuyMWjknBFWUPkdd3y6jXMxPkhDoTdVOS', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(15, 'Michal', 'Kacper', 'kacper2002@o2.pl', 1, '$2y$10$ky43k7dJDyrZO14sd0KZAugAZgPYLkCtPPi05w7PjyhHENWleYmS6', '', 'Zielona Góra', 'Widokowa', '11', '66-004', '518335834', '../uploads/user-3331256_1280.png', NULL, NULL),
(16, 'mateusz', 'Vateusz', 'mateusz@wp.pl', 1, '$2y$10$Nn1vBMqUCCw0VUv3k8P4VuW54AGgnE85I5NqpL6ZecRTTCULJoHfe', '$2y$10$Nn1vBMqUCCw0VUv3k8P4VuW54AGgnE85I5NqpL6ZecRTTCULJoHfe', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(18, 'dsadas', 'ghfnbnvnbv', 'god@wp.pl', 1, '$2y$10$yItGTuPy/NZqEqOyFv0PSutR8jIQ9XthfAmiEAkHLqVygprPTI9pC', '$2y$10$yItGTuPy/NZqEqOyFv0PSutR8jIQ9XthfAmiEAkHLqVygprPTI9pC', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(19, 'Jakub', 'Kumer', 'kumi@wp.pl', 1, '$2y$10$nqzr/owleiVa2eRKUxJlyewSommA3Q7aDBecC715V.wZ5RVX9Qz1u', '$2y$10$nqzr/owleiVa2eRKUxJlyewSommA3Q7aDBecC715V.wZ5RVX9Qz1u', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(20, 'fdsa', 'fdsafsd', 'cwel@wp.pl', 1, '$2y$10$Ra8saP9vwZqkncp5njEf0.7i9W/JRaFk3E/7VFxRIS3r3o.1HSo.q', '$2y$10$Ra8saP9vwZqkncp5njEf0.7i9W/JRaFk3E/7VFxRIS3r3o.1HSo.q', '', '', '', '', '', '../uploads/66f55fd1ba159.jpg', NULL, NULL),
(21, 'Maja', 'Stasko', 'stasko@wp.pl', 1, '$2y$10$Bhg1eReVIn7g7XaUlQMIGe6gu9mnKc4ZX1ExBBnfzb/R1UxgSU.K2', '$2y$10$Bhg1eReVIn7g7XaUlQMIGe6gu9mnKc4ZX1ExBBnfzb/R1UxgSU.K2', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(22, 'Firma', 'Farmaceutyczna', 'farm@wp.pl', 1, '$2y$10$4McZ8/7ZcwJ6Odc7UxjjL.pje6VRzQy3Ai64TYyWQjCwMN8QhiLa.', '$2y$10$4McZ8/7ZcwJ6Odc7UxjjL.pje6VRzQy3Ai64TYyWQjCwMN8QhiLa.', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(23, 'biblioteka', 'poznanska', 'biblio@wp.pl', 1, '$2y$10$2aU0pqSutdJpxVPwV4kE0ONK9a.rW5AzgdyohwruyPo9cceVMDBaG', '$2y$10$2aU0pqSutdJpxVPwV4kE0ONK9a.rW5AzgdyohwruyPo9cceVMDBaG', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(24, 'Basia', 'Kowal', 'kowal@wp.pl', 1, '$2y$10$JDAh8aYZIErcu.NOpQZms.zoyrJuKv58zxRNZz.eVzYJatXAIx6wG', '$2y$10$JDAh8aYZIErcu.NOpQZms.zoyrJuKv58zxRNZz.eVzYJatXAIx6wG', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL),
(25, 'Monika', 'Rogowska', 'rogowska@o2.pl', 1, '$2y$10$j3pvov3uKlK8XMZJuV6ij.jgQr0Z5dPLVE60fnBGoA0.78diMbYjy', '$2y$10$j3pvov3uKlK8XMZJuV6ij.jgQr0Z5dPLVE60fnBGoA0.78diMbYjy', 'Zielona Góra', 'Wrocławska', '69', '65-001', '884194964', '../uploads/6703a52c117cc.png', NULL, NULL),
(26, 'Kacper', 'Lokietek', 'Lokiec33PL@gmail.com', 1, '$2y$10$mHV2a9WVELJPHy7wkPCdZOrMBKuq/pTEzm0LuvT3Iz9JmW9Y9EHiy', '$2y$10$zRc.wVKsK.vrEXu4hg/Ju.l4zjt7fJ17tKm6rPc7/ocmwVCbLezS2', 'Zielona Góra', 'Racula-Widokowa', '11', '66-004', '518335834', '../uploads/6704eff7c89ec.jpg', 'dc620f85a99a4655f6893f049e379522b4ea7ef0080226ae273f723ddb09f00218c25bca94b41396d86992335e33a16cac8f', '2024-10-08 12:16:54');
>>>>>>> 20275a825c54e7c954d08ca742e4d295306f52d4

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`id_auction`),
  ADD KEY `fk_highest_bidder` (`highest_bidder_id`);

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
-- Indeksy dla tabeli `completed_auctions`
--
ALTER TABLE `completed_auctions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `highest_bidder_id` (`highest_bidder_id`);

--
-- Indeksy dla tabeli `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id_favorite`),
  ADD UNIQUE KEY `id_user` (`id_user`,`id_auction`),
  ADD KEY `id_auction` (`id_auction`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auctions`
--
ALTER TABLE `auctions`
<<<<<<< HEAD
  MODIFY `id_auction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
=======
  MODIFY `id_auction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
>>>>>>> 20275a825c54e7c954d08ca742e4d295306f52d4

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `id_bid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
<<<<<<< HEAD
  MODIFY `id_favorite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
=======
  MODIFY `id_favorite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
>>>>>>> 20275a825c54e7c954d08ca742e4d295306f52d4

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id_photo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
<<<<<<< HEAD
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
=======
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
>>>>>>> 20275a825c54e7c954d08ca742e4d295306f52d4

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auctions`
--
ALTER TABLE `auctions`
  ADD CONSTRAINT `fk_highest_bidder` FOREIGN KEY (`highest_bidder_id`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `auction_categories`
--
ALTER TABLE `auction_categories`
  ADD CONSTRAINT `auction_categories_ibfk_1` FOREIGN KEY (`id_auction`) REFERENCES `auctions` (`id_auction`),
  ADD CONSTRAINT `auction_categories_ibfk_2` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`);

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`id_auction`) REFERENCES `auctions` (`id_auction`),
  ADD CONSTRAINT `bids_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `completed_auctions`
--
ALTER TABLE `completed_auctions`
  ADD CONSTRAINT `completed_auctions_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `completed_auctions_ibfk_2` FOREIGN KEY (`highest_bidder_id`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`id_auction`) REFERENCES `auctions` (`id_auction`);

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`id_auction`) REFERENCES `auctions` (`id_auction`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
