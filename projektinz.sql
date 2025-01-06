-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sty 06, 2025 at 12:08 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

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

INSERT INTO `auctions` (`id_auction`, `id_user`, `id_category`, `title`, `description`, `image`, `start_price`, `end_time`, `highest_bidder_id`, `status`) VALUES
(94, 31, 7, 'zestaw lego ', 'Lego technics koparka. Super zabawka', '../uploads/pobrane.jpeg', 123, '2025-04-02 14:14:00', NULL, 'active'),
(95, 31, 3, 'Piłka nike', 'pilka nike. najlepsza naswiecie', '../uploads/pobrane (1).jpeg', 45, '2025-04-16 19:25:00', NULL, 'active'),
(96, 30, 2, 'bmw x3', 'szybki suv dla rodziny', '../uploads/bmw.jpg', 32000, '2025-04-21 17:29:00', NULL, 'active'),
(97, 30, 2, 'porshe cayenne', 'nowy salonowy samochod. sprzedaje bo sie znudził', '../uploads/porshe.jpg', 320000, '2025-03-29 14:29:00', NULL, 'active'),
(98, 29, 8, 'tabletki na ból głowy apap', 'najlepsze tabletki na ból głowy. ból znika w mig.', '../uploads/pobrane.png', 12, '2025-03-31 14:33:00', NULL, 'active'),
(99, 29, 8, 'flegamina', 'lek na kaszel', '../uploads/pobrane (2).jpeg', 32, '2025-03-19 14:35:00', NULL, 'active'),
(101, 28, 3, 'piłka adidas ', 'Piłka mistrzostw świata', '../uploads/Pilka-nozna-adidas-Mundial-Al-Rihla-2022-r-5.jpeg', 67, '2025-04-19 11:27:00', NULL, 'active'),
(102, 28, 6, 'Laptop do gier', 'laptop do gier. kopanie krypto i te sprawy', '../uploads/laptop.png', 1500, '2025-04-12 13:32:00', NULL, 'active'),
(103, 27, 10, 'lampa do pokoju', 'super lampa. świeci dobrze', '../uploads/Zrzut ekranu 2024-11-06 101551.png', 87, '2025-04-16 19:36:00', NULL, 'active'),
(104, 27, 10, 'stolik ikea', 'stolik z ikei. ślady użytkowania', '../uploads/stolik ikea.png', 321, '2025-04-02 13:34:00', NULL, 'active'),
(105, 25, 9, 'Deskorolka', 'deskorolka dla kozaków', '../uploads/deskorolka.png', 100, '2025-04-04 13:36:00', NULL, 'active'),
(106, 25, 3, 'Rower górski', 'super rower. Genialny na prezent', '../uploads/rower.jpeg', 2000, '2025-04-12 11:38:00', NULL, 'active'),
(107, 25, 9, 'hulajnoga elektyczna', 'najlepsza hulajnoga na świecie', '../uploads/hulajnoga.png', 677, '2025-04-30 11:39:00', NULL, 'active'),
(108, 22, 8, 'apap', 'Na ból głowy. Szybki i skuteczny', '../uploads/pobrane.png', 23, '2025-04-11 11:47:00', NULL, 'active'),
(109, 22, 8, 'xanax', 'Lek na uspokojenie.', '../uploads/pobrane (3).jpeg', 56, '2025-04-19 15:53:00', NULL, 'active'),
(111, 24, 4, 'Bluza dior', 'Bluza dior dla lubiących się pokazać', '../uploads/diorbluza.jpeg', 899, '2025-04-23 11:51:00', NULL, 'active'),
(112, 24, 4, 'spodnie nike', 'Spodnie nike. Bardzo wygodne', '../uploads/spodnie nike.jpeg', 168, '2025-04-12 11:53:00', NULL, 'active');

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
(47, 'Zestaw do detailingu samochodu', '../uploads/pobrane (1).jpeg', 'Komplet akcesoriów do pielęgnacji samochodu, w tym woski, środki czyszczące i ściereczki.', '2024-10-30 16:34:00', 150.00, 'completed', 30, NULL, 'niezaplacone', 'nieodebrane', 'nie'),
(49, 'T-shirt ', '../uploads/pobrane (3).jpeg', 'Wygodny bawełniany T-shirt z oryginalnym nadrukiem.', '2024-10-30 01:37:00', 50.00, 'completed', 25, NULL, 'niezaplacone', 'nieodebrane', 'nie'),
(51, 'Zabawka edukacyjna dla dzieci', '../uploads/pobrane (5).jpeg', 'Interaktywna zabawka rozwijająca zdolności manualne i logiczne myślenie.', '2024-10-31 04:38:00', 70.00, 'completed', 28, NULL, 'niezaplacone', 'nieodebrane', 'nie'),
(52, 'Suplement diety z witaminą D', '../uploads/pobrane (6).jpeg', 'Wspiera układ odpornościowy i zdrowie kości.', '2024-10-29 18:40:00', 47.00, 'completed', 15, 31, 'niezaplacone', 'nieodebrane', 'nie'),
(53, 'Gra planszowa Wiedźmin', '../uploads/pobrane (7).jpeg', 'Epicka gra planszowa osadzona w uniwersum Wiedźmina.', '2024-10-29 20:43:00', 202.00, 'completed', 27, 28, 'niezaplacone', 'nieodebrane', 'nie'),
(54, 'Doniczka z systemem nawadniania', '../uploads/pobrane (8).jpeg', 'Nowoczesna doniczka, która automatycznie nawadnia rośliny.', '2024-10-29 23:42:00', 90.00, 'completed', 32, NULL, 'niezaplacone', 'nieodebrane', 'nie'),
(55, 'Krem nawilżający do twarzy', '../uploads/pobrane (9).jpeg', 'Lekki krem z naturalnymi składnikami, doskonały dla wszystkich typów skóry.', '2024-10-30 11:41:00', 75.00, 'completed', 16, NULL, 'niezaplacone', 'nieodebrane', 'nie'),
(56, 'Zabawka dla kota', '../uploads/pobrane (10).jpeg', 'Interaktywna zabawka w formie myszki, zachęcająca do zabawy.', '2024-10-30 19:44:00', 25.00, 'completed', 15, NULL, 'niezaplacone', 'nieodebrane', 'nie'),
(57, 'Odtwarzacz multimedialny do samochodu', '../uploads/pobrane (11).jpeg', 'Nowoczesny odtwarzacz z ekranem dotykowym, Bluetooth i GPS.', '2024-10-30 20:09:00', 450.00, 'completed', 25, NULL, 'niezaplacone', 'nieodebrane', 'nie'),
(58, 'Mata szmata', '../uploads/pobrane (12).jpeg', 'Antypoślizgowa mata, idealna do ćwiczeń w domu i na zewnątrz.', '2024-10-30 11:17:00', 100.00, 'completed', 27, NULL, 'niezaplacone', 'nieodebrane', 'nie'),
(60, 'Smartwatch', '../uploads/pobrane (14).jpeg', 'Inteligentny zegarek z funkcjami monitorowania zdrowia i aktywności fizycznej.', '2024-10-31 07:48:00', 600.00, 'completed', 32, NULL, 'niezaplacone', 'nieodebrane', 'nie'),
(77, 'Piłka', '../uploads/_f4db675e-9dcd-4a5d-baae-8db910eea07b.jpg', 'ddsvbbn fhfnhfnhfghnfgn', '2024-10-29 19:09:00', 301.00, 'completed', 29, 20, 'zaplacone', 'odebrane', 'nie'),
(78, 'Panie co tak drogo', '../uploads/_eee8c321-bd0c-45fc-8d6e-db13b047106a.jpg', 'sdsfdasdfsdfsfsdfds', '2024-10-29 17:38:00', 4.00, 'completed', 31, 28, 'zaplacone', 'odebrane', 'tak'),
(79, 'dasdasdadsasd', '../uploads/_f4db675e-9dcd-4a5d-baae-8db910eea07b.jpg', 'sdasdasdasdasdasd', '2024-10-29 20:07:00', 34.00, 'completed', 20, 31, 'niezaplacone', 'nieodebrane', 'tak'),
(80, 'kwas', '../uploads/_2fa9aca3-ab82-4f5b-9c64-7e70ea5217f7.jpg', 'wedfgfgbsdf dbvcbbv', '2024-10-29 23:13:00', 45.00, 'completed', 31, 30, 'zaplacone', 'odebrane', 'tak'),
(87, 'Piłka', '../uploads/_f4db675e-9dcd-4a5d-baae-8db910eea07b.jpg', 'byvc uc', '2024-11-06 10:38:00', 32.00, 'completed', 20, NULL, 'niezaplacone', 'nieodebrane', 'nie'),
(88, 'g ckckckckckck', '../uploads/_30077089-005d-4f98-952e-9f4850e00392.jpg', 'gkcckckgckg', '2024-11-06 10:41:00', 152.00, 'completed', 20, 24, 'zaplacone', 'odebrane', 'tak'),
(89, 'dasdas', '../uploads/_30077089-005d-4f98-952e-9f4850e00392.jpg', 'sdfsdfsdfsfd', '2024-11-09 17:15:00', 32.00, 'completed', 20, NULL, 'niezaplacone', 'nieodebrane', 'nie'),
(90, 'Piłka', '../uploads/_2fa9aca3-ab82-4f5b-9c64-7e70ea5217f7.jpg', 'dadsada', '2024-11-17 13:13:00', 150.00, 'completed', 31, NULL, 'niezaplacone', 'nieodebrane', 'nie'),
(91, 'bhsdnfbdsjkf', '../uploads/_f4db675e-9dcd-4a5d-baae-8db910eea07b.jpg', 'dfsdfsdfsdfsdfsdfsfdsdf', '2024-12-01 19:19:00', 322.00, 'completed', 31, 36, 'zaplacone', 'odebrane', 'tak'),
(92, 'dasdasdsada', '../uploads/bmw.jpg', 'dasdasdsa', '2024-12-13 10:41:00', 321.00, 'completed', 31, NULL, 'niezaplacone', 'nieodebrane', 'nie'),
(93, 'gfdgd', '../uploads/bmw x4.jpg', 'fdgfgdfg', '2024-12-11 09:44:00', 321.00, 'completed', 31, NULL, 'niezaplacone', 'nieodebrane', 'nie'),
(100, 'dsadasdasdas', '../uploads/Zrzut ekranu 2023-03-24 152824.png', 'dasdasdada', '2025-01-05 21:47:00', 35.00, 'completed', 31, 20, 'niezaplacone', 'nieodebrane', 'nie');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `favorites`
--

CREATE TABLE `favorites` (
  `id_favorite` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_auction` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `reviews`
--

CREATE TABLE `reviews` (
  `id_review` int(11) NOT NULL,
  `completed_auction_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `reviewed_user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` varchar(1000) DEFAULT NULL,
  `review_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id_review`, `completed_auction_id`, `reviewer_id`, `reviewed_user_id`, `rating`, `comment`, `review_date`) VALUES
(3, 81, 22, 31, 5, 'badziew', '2024-10-30 11:04:06'),
(4, 88, 24, 20, 4, 'gites', '2024-11-06 10:45:00'),
(5, 91, 36, 31, 4, 'super aukcja', '2024-12-01 19:22:20'),
(6, 77, 20, 29, 4, 'ekstra produkt', '2025-01-05 21:45:53');

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
  `token_expiry` datetime DEFAULT NULL,
  `bank_account_number` varchar(34) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `firstName`, `lastName`, `email`, `id_role`, `pass`, `passwordrepeat`, `city`, `street`, `house_number`, `postal_code`, `phone_number`, `profile_image`, `reset_token`, `token_expiry`, `bank_account_number`) VALUES
(1, 'Kuba', 'Zguba', 'kuba@wp.pl', 3, '123', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(2, 'Kacper', 'łokieć', 'loki@wp.pl', 1, '123', '', 'Zielona gora', 'jakas', '31', '62-987', '987654321', '../uploads/6720a3d534d02.jpg', NULL, NULL, 'PL90783088396620068851478172'),
(3, 'marek', 'farek', 'marek@wp.pl', 1, '$2y$10$Ih/xQrlORgk6myj.tShom..qQbpI/VlUXnM.kJfAiw2Zgcuu7J0MC', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(4, 'dsa', 'dsa', 'fra@dsa.pl', 1, '123456789', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(7, 'piotr', 'jak', 'jak@wp.pl', 1, '$2y$10$MRCyAdsh.3ut/Ai66326Demi7Wf8/tzrOJMqQkCHGcbdMoRMhoV2S', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(8, 'Kacper', 'Lokiec', 'lokieta@wp.pl', 1, '$2y$10$wuzKf8xSkvbcp7abF0uMmeEE.XAz0ex9sSI..nyrd4w.liPaytIza', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(9, 'Jakub', 'Kakub', 'kakub@wp.pl', 1, '$2y$10$th03v48GNlfV4hWrMzNf6.zxO7VQtEs5GZ/2HRizUaTJ8oZOrCV8O', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(10, 'Hans', 'Dutch', 'hans@wp.pl', 3, 'Hans@12345', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(11, 'Franc', 'Stans', 'franc@wp.pl', 3, '$2y$10$Gw/0SO9ODPPYiaL99nMp9.EihQy02aSeepVolXAIEKI7rfC0PE6ba', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(12, 'Jakub', 'Kumer', 'Jckbob@wp.pl', 1, '$2y$10$I9OgdVhLdHHw7FcxiOl.8Op4lNq8wz1MHyee9CBFbZ9z/ll93i/hW', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(13, 'Kuva', 'dsadas', 'dsa@wp.pl', 1, '$2y$10$tZk6BfnSnjFVypDh9ktkaeQcpJeTL7HxtHNCyHUqwvtvI04nDrxTa', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(14, 'Kacper', 'Lokiec', 'lokiec@o2.pl', 1, '$2y$10$N795HHF/S.nYuMQMgOz8wuyMWjknBFWUPkdd3y6jXMxPkhDoTdVOS', '', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(15, 'Michal', 'Kacper', 'kacper2002@o2.pl', 1, '$2y$10$ky43k7dJDyrZO14sd0KZAugAZgPYLkCtPPi05w7PjyhHENWleYmS6', '', 'Zielona Góra', 'Widokowa', '11', '66-004', '518335834', '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(16, 'mateusz', 'Vateusz', 'mateusz@wp.pl', 1, '$2y$10$Nn1vBMqUCCw0VUv3k8P4VuW54AGgnE85I5NqpL6ZecRTTCULJoHfe', '$2y$10$Nn1vBMqUCCw0VUv3k8P4VuW54AGgnE85I5NqpL6ZecRTTCULJoHfe', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(18, 'dsadas', 'ghfnbnvnbv', 'god@wp.pl', 1, '$2y$10$yItGTuPy/NZqEqOyFv0PSutR8jIQ9XthfAmiEAkHLqVygprPTI9pC', '$2y$10$yItGTuPy/NZqEqOyFv0PSutR8jIQ9XthfAmiEAkHLqVygprPTI9pC', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(19, 'Jakub', 'Kumer', 'kumi@wp.pl', 1, '$2y$10$nqzr/owleiVa2eRKUxJlyewSommA3Q7aDBecC715V.wZ5RVX9Qz1u', '$2y$10$nqzr/owleiVa2eRKUxJlyewSommA3Q7aDBecC715V.wZ5RVX9Qz1u', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(20, 'fdsa', 'fdsafsd', 'janek@wp.pl', 1, '$2y$10$Ra8saP9vwZqkncp5njEf0.7i9W/JRaFk3E/7VFxRIS3r3o.1HSo.q', '$2y$10$Ra8saP9vwZqkncp5njEf0.7i9W/JRaFk3E/7VFxRIS3r3o.1HSo.q', 'Poznan', 'Polna', '7', '98-876', '876543123', '../uploads/677aeee17a09d.jpg', NULL, NULL, 'PL17362271562986793427924781'),
(21, 'Maja', 'Stasko', 'stasko@wp.pl', 1, '$2y$10$Bhg1eReVIn7g7XaUlQMIGe6gu9mnKc4ZX1ExBBnfzb/R1UxgSU.K2', '$2y$10$Bhg1eReVIn7g7XaUlQMIGe6gu9mnKc4ZX1ExBBnfzb/R1UxgSU.K2', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(22, 'Firma', 'Farmaceutyczna', 'farm@wp.pl', 1, '$2y$10$4McZ8/7ZcwJ6Odc7UxjjL.pje6VRzQy3Ai64TYyWQjCwMN8QhiLa.', '$2y$10$4McZ8/7ZcwJ6Odc7UxjjL.pje6VRzQy3Ai64TYyWQjCwMN8QhiLa.', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(23, 'biblioteka', 'poznanska', 'biblio@wp.pl', 3, '$2y$10$2aU0pqSutdJpxVPwV4kE0ONK9a.rW5AzgdyohwruyPo9cceVMDBaG', '$2y$10$2aU0pqSutdJpxVPwV4kE0ONK9a.rW5AzgdyohwruyPo9cceVMDBaG', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(24, 'Basia', 'Kowal', 'kowal@wp.pl', 1, '$2y$10$JDAh8aYZIErcu.NOpQZms.zoyrJuKv58zxRNZz.eVzYJatXAIx6wG', '$2y$10$JDAh8aYZIErcu.NOpQZms.zoyrJuKv58zxRNZz.eVzYJatXAIx6wG', 'Poznan', 'Polna', '5', '60-600', '867768590', '../uploads/user-3331256_1280.png', NULL, NULL, 'PL93441206962999825288861751'),
(25, 'Jakub', 'walek', 'walek@interia.pl', 1, '$2y$10$0uA0A0W05H5vQKWeDyLlYOJ0ZKIbDaWA5Hv3/U.BkONd9tQ4hx5K.', '$2y$10$0uA0A0W05H5vQKWeDyLlYOJ0ZKIbDaWA5Hv3/U.BkONd9tQ4hx5K.', 'Krakow', 'jakas', '9', '56-500', '987654321', '../uploads/6720a2f15410b.jpg', NULL, NULL, 'PL53867519625366832828787461'),
(26, 'Kuba', 'Kumer', 'jakubkumer@interia.pl', 1, '$2y$10$WJfEvZob6alKMwgmWXVyxuxiMDLNn8h8UvtSkJBmBgV0xDI/T9j1W', '$2y$10$2Zt2U3rtBGzK.vsPsHDOfO1g91uv.HB32mE5yszc8qQ5k2/T6IUrS', 'chojnice', 'prochowa', '32', '98-606', '888999000', '../uploads/user-3331256_1280.png', NULL, NULL, 'PL03109024029849819558446237'),
(27, 'Jakub', 'Kumer', 'freezsky7@interia.pl', 1, '$2y$10$mdX50HmES2r5Ogc3YbfCX.QF5kNovZ4P6XASsqI7gPcOW9aIdvRee', '$2y$10$gYE0r/lOKCRu.9bhKO69sOHigrZHw33ykkhnsvHUwbUUwZ9KatjYi', 'Poznan', 'Winiarska', '24', '32-422', '321123321', '../uploads/user-3331256_1280.png', NULL, NULL, 'PL527831821617923857128398556666'),
(28, 'Juleczka', 'Kuleczka', 'julka@wp.pl', 1, '$2y$10$yFWsqKvnmoXZUSRzpVG0LuI3TClRicg15K0frl/wdWH4usDML9cei', '$2y$10$yFWsqKvnmoXZUSRzpVG0LuI3TClRicg15K0frl/wdWH4usDML9cei', 'Gdańsk', 'Portowa', '32', '32-234', '432567098', '../uploads/user-3331256_1280.png', NULL, NULL, 'PL12292993487740609636947886'),
(29, 'Bartek', 'Beton', 'bartek@wp.pl', 1, '$2y$10$IBZZyTvh.VsGYxKVW3gBzeaEJc9CmfRYRtr3DgT4c0XH1/x8mjAs6', '$2y$10$IBZZyTvh.VsGYxKVW3gBzeaEJc9CmfRYRtr3DgT4c0XH1/x8mjAs6', 'Warszawa', 'Złota', '44', '53-321', '123456789', '../uploads/6720f47f2a145.jpg', NULL, NULL, 'PL93441206962999825288861751'),
(30, 'Marian', 'Kwas', 'marian@wp.pl', 1, '$2y$10$tkTgQJCC7XMCfWOq/jdQK.XaS8YTcDkyUhZz06dJRgHSKd1UgPCJ2', '$2y$10$tkTgQJCC7XMCfWOq/jdQK.XaS8YTcDkyUhZz06dJRgHSKd1UgPCJ2', 'Warocław', 'szybka', '5', '54-879', '321456789', '../uploads/6720d4a6cb0ac.jpg', NULL, NULL, 'PL12104484348704793456684775'),
(31, 'Grzegorz', 'kowalski', 'grzegorz@wp.pl', 1, '$2y$10$8zygwWRK6TSjZxoCdINtUOp55XLl11C0Jb6FGEE3bDifBkf4zK8Te', '$2y$10$8zygwWRK6TSjZxoCdINtUOp55XLl11C0Jb6FGEE3bDifBkf4zK8Te', 'Chojnice', 'Prochowa', '3', '89-675', '321456987', '../uploads/67210f021c193.jpg', NULL, NULL, 'PL92250654312603302168021823'),
(32, '3212', '3212', '3212@wp.pl', 1, '$2y$10$gJ3SguzOWst00xIZA0wGkOMpzgPjnpf3WAEkrM.b.mstCpX3PoHGO', '$2y$10$gJ3SguzOWst00xIZA0wGkOMpzgPjnpf3WAEkrM.b.mstCpX3PoHGO', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(33, 'Karol', 'Kot', 'availablemadelaine@navalcadets.com', 1, '$2y$10$RCPOfN7USXOHv7gCK1kdwOa0tgtB/0vVW5oeBQ2GyMHigXmVrENvm', '$2y$10$5.E2oUfuBr4gKXcBnrITIerdxA9eoNXrRsq1udlfIDh9Ebi6FW1RO', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(35, 'karol', 'kolek', 'kolek@wp.pl', 1, '$2y$10$4zESnWOGn.LUMHTXGEvbIehf9ykOszL6EiV3SIkzSaJLObcQZAAkG', '$2y$10$4zESnWOGn.LUMHTXGEvbIehf9ykOszL6EiV3SIkzSaJLObcQZAAkG', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL),
(36, 'ksdasd', 'dasdasdas', 'pat@wp.pl', 1, '$2y$10$YWf0V6GElf4R1hsZW/pM6eKXCAIhrK5HayzF.jVa1IVufkYeBxc02', '$2y$10$YWf0V6GElf4R1hsZW/pM6eKXCAIhrK5HayzF.jVa1IVufkYeBxc02', NULL, NULL, NULL, NULL, NULL, '../uploads/user-3331256_1280.png', NULL, NULL, NULL);

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
-- Indeksy dla tabeli `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `completed_auction_id` (`completed_auction_id`),
  ADD KEY `reviewer_id` (`reviewer_id`),
  ADD KEY `reviewed_user_id` (`reviewed_user_id`);

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
  MODIFY `id_auction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id_favorite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auctions`
--
ALTER TABLE `auctions`
  ADD CONSTRAINT `fk_highest_bidder` FOREIGN KEY (`highest_bidder_id`) REFERENCES `users` (`id_user`);

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
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
