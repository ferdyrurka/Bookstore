-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 27 Sie 2018, 12:47
-- Wersja serwera: 5.7.14
-- Wersja PHP: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `bookstore-test`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `cart`
--

CREATE TABLE `cart` (
  `cart_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `products` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `category`
--

INSERT INTO `category` (`id`, `name`, `description`, `slug`) VALUES
(1, 'Tests category', 'qwertyuiop', 'tests-category');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `delivery_method`
--

CREATE TABLE `delivery_method` (
  `id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `delivery_method`
--

INSERT INTO `delivery_method` (`id`, `name`, `cost`) VALUES
(1, 'DHL', 12),
(2, 'DPD', 14);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `forgot_password`
--

CREATE TABLE `forgot_password` (
  `user_id` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_create` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `forgot_password`
--

INSERT INTO `forgot_password` (`user_id`, `token`, `time_create`) VALUES
(6, '2b42f823-0b5c-4f68-93c7-081b91ef345d', '2018-08-30 00:00:00'),
(1, 'd823b1d4-9a79-4514-af57-080ab2162456', '2018-08-26 22:30:37');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `delivery_method_id` int(11) NOT NULL,
  `price_method_id` int(11) NOT NULL,
  `products` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `cost` double NOT NULL,
  `other_information` longtext COLLATE utf8mb4_unicode_ci,
  `first_name` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` int(11) NOT NULL,
  `email` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `house_number` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `order`
--

INSERT INTO `order` (`id`, `user_id`, `delivery_method_id`, `price_method_id`, `products`, `cost`, `other_information`, `first_name`, `surname`, `post_code`, `phone`, `email`, `city`, `street`, `house_number`) VALUES
(1, NULL, 1, 2, 'a:1:{i:1;a:4:{s:8:\"how_much\";i:1;s:4:\"cost\";d:2.23;s:4:\"name\";s:9:\"sdadsaasd\";s:8:\"magazine\";i:11;}}', 12, NULL, 'Hello', 'World', '20-200', 123456789, 'email@example.pl', 'Example', 'Los Santos', '2/2');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `price_method`
--

CREATE TABLE `price_method` (
  `id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `price_method`
--

INSERT INTO `price_method` (`id`, `name`) VALUES
(1, 'Transfer'),
(2, 'Payu');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `product_image_id` int(11) DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `magazine` int(11) NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `product`
--

INSERT INTO `product` (`id`, `product_image_id`, `name`, `price`, `magazine`, `description`, `slug`, `created_at`) VALUES
(1, NULL, 'tests', 10, 10, 'qwertyuiop', 'tests', '2018-08-18 00:00:00'),
(2, NULL, 'tests second', 10, 10, 'qwertyuiop', 'tests-second', '2018-08-18 00:00:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `product_category`
--

CREATE TABLE `product_category` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `product_category`
--

INSERT INTO `product_category` (`product_id`, `category_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `product_image`
--

CREATE TABLE `product_image` (
  `product_id` int(11) NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hash_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `roles` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `first_name`, `surname`, `username`, `email`, `password`, `status`, `roles`) VALUES
(1, 'admin', 'admin', 'UserFront', 'user@lukaszstaniszewski.pl', '$2y$13$xBKBAiS1fnOSgqxVPcuLdOYg591eRFG7Q3PLHxH9dASicAEQjL47G', 1, 'ROLE_USER'),
(2, 'admin', 'admin', 'Administrator', 'kontakt@lukaszstaniszewski.pl', '$2y$13$BGVxDy7zcXMJEOdx/Py5M.SCGfT9W/4W0S.uGUKo7RUa1.MVPvP9.', 1, 'ROLE_ADMIN'),
(3, 'forgot-password', 'forgot-password', 'ForgotPassword', 'test@lukaszstaniszewski.pl', '$2y$13$bZ3Y6luU32XOluo1Xwk9k.2jvXbtFD2bpVlPyrK1Wh3XbH/Gh2Que', 1, 'ROLE_USER'),
(6, 'forgot-password2', 'forgot-password2', 'ForgotPassword2', 'test2@lukaszstaniszewski.pl', '$2y$13$BGVxDy7zcXMJEOdx/Py5M.SCGfT9W/4W0S.uGUKo7RUa1.MVPvP9.', 1, 'ROLE_USER'),
(7, 'Disable', 'Activate', 'DisableActivate', 'activateDisable@lukaszstaniszewski.pl', '$2y$13$BGVxDy7zcXMJEOdx/Py5M.SCGfT9W/4W0S.uGUKo7RUa1.MVPvP9.', 1, 'ROLE_USER');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_session`
--

CREATE TABLE `user_session` (
  `id` int(11) NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `session` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `view_product`
--

CREATE TABLE `view_product` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `user_ip` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indeksy dla tabeli `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_64C19C15E237E06` (`name`),
  ADD UNIQUE KEY `UNIQ_64C19C1989D9B62` (`slug`);

--
-- Indeksy dla tabeli `delivery_method`
--
ALTER TABLE `delivery_method`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4048C3EE5E237E06` (`name`);

--
-- Indeksy dla tabeli `forgot_password`
--
ALTER TABLE `forgot_password`
  ADD PRIMARY KEY (`token`),
  ADD UNIQUE KEY `UNIQ_2AB9B566A76ED395` (`user_id`);

--
-- Indeksy dla tabeli `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F5299398A76ED395` (`user_id`),
  ADD KEY `IDX_F52993985DED75F5` (`delivery_method_id`),
  ADD KEY `IDX_F52993989A390A9B` (`price_method_id`);

--
-- Indeksy dla tabeli `price_method`
--
ALTER TABLE `price_method`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D34A04AD5E237E06` (`name`),
  ADD UNIQUE KEY `UNIQ_D34A04AD989D9B62` (`slug`),
  ADD UNIQUE KEY `UNIQ_D34A04ADF6154FFA` (`product_image_id`);

--
-- Indeksy dla tabeli `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `IDX_CDFC73564584665A` (`product_id`),
  ADD KEY `IDX_CDFC735612469DE2` (`category_id`);

--
-- Indeksy dla tabeli `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `UNIQ_64617F03E1F029B6` (`hash_name`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Indeksy dla tabeli `user_session`
--
ALTER TABLE `user_session`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `view_product`
--
ALTER TABLE `view_product`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `delivery_method`
--
ALTER TABLE `delivery_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `price_method`
--
ALTER TABLE `price_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `product_image`
--
ALTER TABLE `product_image`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT dla tabeli `user_session`
--
ALTER TABLE `user_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `view_product`
--
ALTER TABLE `view_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `forgot_password`
--
ALTER TABLE `forgot_password`
  ADD CONSTRAINT `FK_2AB9B566A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ograniczenia dla tabeli `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK_F52993985DED75F5` FOREIGN KEY (`delivery_method_id`) REFERENCES `delivery_method` (`id`),
  ADD CONSTRAINT `FK_F52993989A390A9B` FOREIGN KEY (`price_method_id`) REFERENCES `price_method` (`id`),
  ADD CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ograniczenia dla tabeli `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04ADF6154FFA` FOREIGN KEY (`product_image_id`) REFERENCES `product_image` (`product_id`);

--
-- Ograniczenia dla tabeli `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `FK_CDFC735612469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_CDFC73564584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
