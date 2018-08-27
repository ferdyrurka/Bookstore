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
-- Baza danych: `bookstore`
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
(7, 'Programowanie', '', 'Programowanie'),
(8, 'PHP', '', 'Php'),
(9, 'Java', '', 'Java');

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
(1, 'DHL koszt: 12.00 zł', 12);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `forgot_password`
--

CREATE TABLE `forgot_password` (
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `time_create` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, NULL, 1, 2, 'a:1:{i:20;a:4:{s:8:\"how_much\";i:1;s:4:\"cost\";d:122.98999999999999;s:4:\"name\";s:16:\"Thinking in Java\";s:3:\"obj\";O:18:\"App\\Entity\\Product\":11:{s:22:\"\0App\\Entity\\Product\0id\";i:20;s:24:\"\0App\\Entity\\Product\0name\";s:16:\"Thinking in Java\";s:25:\"\0App\\Entity\\Product\0price\";i:9999;s:30:\"\0App\\Entity\\Product\0priceFloat\";d:122.98999999999999;s:34:\"\0App\\Entity\\Product\0productImageId\";i:4;s:28:\"\0App\\Entity\\Product\0magazine\";i:11;s:31:\"\0App\\Entity\\Product\0description\";s:930:\"<p>Sed sagittis ultrices convallis. Nulla facilisi. Sed vehicula, dolor sodales feugiat fringilla, tellus tellus porta purus, sit amet malesuada libero enim tempus arcu. Sed id metus nec lacus hendrerit elementum eu sed libero. Nunc mattis sem eu lorem auctor efficitur. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean euismod ipsum nec neque facilisis dignissim. Suspendisse dictum sed eros et pellentesque. Phasellus vehicula porttitor eleifend. Aliquam erat volutpat. Sed et ipsum et tortor sodales dignissim sed et nunc. Mauris et convallis arcu.</p>\r\n\r\n<p>Mauris iaculis tempus finibus. Aliquam rutrum eros ac felis commodo venenatis. Vivamus quis euismod ligula, quis varius tortor. Aliquam non tempus lacus. Maecenas accumsan metus diam, id mollis libero tincidunt laoreet. Donec sed dolor facilisis, auctor odio et, pharetra risus. Vestibulum vitae vulputate ipsum.</p>\";s:24:\"\0App\\Entity\\Product\0slug\";s:16:\"thinking-in-java\";s:29:\"\0App\\Entity\\Product\0createdAt\";O:8:\"DateTime\":3:{s:4:\"date\";s:26:\"2018-08-27 11:22:15.000000\";s:13:\"timezone_type\";i:3;s:8:\"timezone\";s:3:\"UTC\";}s:42:\"\0App\\Entity\\Product\0productImageReferences\";O:38:\"Proxies\\__CG__\\App\\Entity\\ProductImage\":6:{s:17:\"__isInitialized__\";b:0;s:34:\"\0App\\Entity\\ProductImage\0productId\";i:4;s:29:\"\0App\\Entity\\ProductImage\0path\";N;s:33:\"\0App\\Entity\\ProductImage\0hashName\";N;s:34:\"\0App\\Entity\\ProductImage\0extension\";N;s:42:\"\0App\\Entity\\ProductImage\0productReferences\";N;}s:38:\"\0App\\Entity\\Product\0categoryReferences\";O:33:\"Doctrine\\ORM\\PersistentCollection\":2:{s:13:\"\0*\0collection\";O:43:\"Doctrine\\Common\\Collections\\ArrayCollection\":1:{s:53:\"\0Doctrine\\Common\\Collections\\ArrayCollection\0elements\";a:0:{}}s:14:\"\0*\0initialized\";b:0;}}}}', 134.99, 'To jest pierwsze zamówienie!', 'Łukasz', 'Staniszewski', '28-230', 123456789, 'luksta556@gmail.com', 'Warszawa', 'A.Mickiewicza', '1');

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
(1, 'Płatność przy odbiorze'),
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
(19, 3, 'PHP7 podstawy', 4999, 100, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis porta quam id interdum pretium. In in nibh vulputate, facilisis metus in, hendrerit augue. Proin orci nibh, eleifend sit amet turpis id, volutpat fringilla nisl. Sed dictum sem purus, quis feugiat ipsum fermentum a. Donec interdum vestibulum massa, quis suscipit metus. Nulla congue metus id nunc commodo, et pretium arcu bibendum. Aliquam lacus mi, tincidunt et cursus vel, luctus eget urna. Ut non mollis dui.</p>\r\n\r\n<p>Etiam eget sem nec lorem blandit pretium. Integer ultrices vehicula bibendum. Curabitur sed lorem semper, efficitur sem quis, condimentum diam. In malesuada posuere leo sit amet aliquet. Praesent eget faucibus arcu. Nulla sit amet nibh arcu. In id eleifend augue, eget cursus mauris. Etiam finibus non erat vitae imperdiet. Curabitur at sollicitudin massa. Ut bibendum, sapien in feugiat condimentum, felis dolor commodo metus, et posuere dolor massa ac lectus. Aliquam quis sapien et mi varius egestas vitae ac ante. Aliquam mattis quam augue, a pretium velit lobortis eu. Quisque porta, odio vel auctor tristique, sapien enim posuere mauris, sit amet interdum odio ligula sit amet ligula. Donec justo quam, gravida et porta nec, efficitur non sapien.</p>', 'php7-podstawy', '2018-08-27 11:21:46'),
(20, 4, 'Thinking in Java', 9999, 11, '<p>Sed sagittis ultrices convallis. Nulla facilisi. Sed vehicula, dolor sodales feugiat fringilla, tellus tellus porta purus, sit amet malesuada libero enim tempus arcu. Sed id metus nec lacus hendrerit elementum eu sed libero. Nunc mattis sem eu lorem auctor efficitur. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean euismod ipsum nec neque facilisis dignissim. Suspendisse dictum sed eros et pellentesque. Phasellus vehicula porttitor eleifend. Aliquam erat volutpat. Sed et ipsum et tortor sodales dignissim sed et nunc. Mauris et convallis arcu.</p>\r\n\r\n<p>Mauris iaculis tempus finibus. Aliquam rutrum eros ac felis commodo venenatis. Vivamus quis euismod ligula, quis varius tortor. Aliquam non tempus lacus. Maecenas accumsan metus diam, id mollis libero tincidunt laoreet. Donec sed dolor facilisis, auctor odio et, pharetra risus. Vestibulum vitae vulputate ipsum.</p>', 'thinking-in-java', '2018-08-27 11:22:15'),
(21, 5, 'Czysty kod przewodnik profesjonalisty', 15999, 1000, '<p><strong>Etiam in iaculis nisi.</strong> Nam consequat nisi vel odio tincidunt dignissim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.<em> Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum sodales ac risus id interdum. In eget ex pharetra, luctus eros et, venenatis arcu</em>. Quisque laoreet dui ac sagittis suscipit.</p>', 'czysty-kod-przewodnik-profesjonalisty', '2018-08-27 11:24:13'),
(22, NULL, 'Czysty kod', 3999, 57, '<p>Morbi tristique lacus eu magna venenatis vulputate. Duis ullamcorper lacus vitae dapibus gravida. Integer vel pretium sem. Curabitur mauris urna, placerat quis odio eu, lacinia faucibus metus. Nam mi mauris, vulputate fermentum consequat euismod, faucibus ac odio. Donec convallis, odio sit amet euismod auctor, elit velit venenatis erat, ut fermentum sem nulla quis tortor. Suspendisse vel arcu id eros blandit placerat. Morbi sapien turpis, tempus ut nisi ornare, consectetur pellentesque felis. Mauris venenatis consequat ligula, ut vehicula lorem varius at. Donec sagittis, justo ut malesuada dapibus, neque enim fringilla augue, non mattis justo ipsum mattis ex. Praesent nisi ex, mollis a odio ac, consequat tincidunt purus. Curabitur a pharetra ex.</p>', 'czysty-kod', '2018-08-27 11:25:18');

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
(19, 7),
(19, 8),
(20, 7),
(20, 9),
(21, 7),
(22, 7);

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

--
-- Zrzut danych tabeli `product_image`
--

INSERT INTO `product_image` (`product_id`, `path`, `hash_name`, `extension`) VALUES
(3, '/img/product/dc418842aa2684a086dae502579838c6.jpeg', 'dc418842aa2684a086dae502579838c6', 'jpeg'),
(4, '/img/product/b07b208b65a2e65bcd3a432603f360f2.jpeg', 'b07b208b65a2e65bcd3a432603f360f2', 'jpeg'),
(5, '/img/product/5886317f17050b6adb5bb21f50e5beb1.jpeg', '5886317f17050b6adb5bb21f50e5beb1', 'jpeg');

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
(3, 'Admin', 'Administrator', 'Administrator', 'kontakt@lukaszstaniszewski.pl', '$2y$13$BGVxDy7zcXMJEOdx/Py5M.SCGfT9W/4W0S.uGUKo7RUa1.MVPvP9.', 1, 'ROLE_ADMIN'),
(4, 'User', 'Użytkownik', 'Ferdyrurka', 'admin@lukaszstaniszewski.pl', '$2y$13$BGVxDy7zcXMJEOdx/Py5M.SCGfT9W/4W0S.uGUKo7RUa1.MVPvP9.', 1, 'ROLE_USER');

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

--
-- Zrzut danych tabeli `user_session`
--

INSERT INTO `user_session` (`id`, `user_agent`, `session`, `user_ip`) VALUES
(1, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:61.0) Gecko/20100101 Firefox/61.0', '1be984d1b00b9840a1be682a6e3cefe9', '127.0.0.1'),
(2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:61.0) Gecko/20100101 Firefox/61.0', '26ec2ba38b140d7cab3b52cdf836c55d', '127.0.0.1'),
(3, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36', 'ca48f6cb93681da76dbca2ddf6740101', '127.0.0.1'),
(4, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36', '6ae54c9969e4d37700184598f2df3a02', '127.0.0.1'),
(5, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:61.0) Gecko/20100101 Firefox/61.0', '57abbc3ee6cdc8c13e2ae58cb46ac08d', '127.0.0.1');

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
-- Zrzut danych tabeli `view_product`
--

INSERT INTO `view_product` (`id`, `user_id`, `product_id`, `user_ip`, `device`) VALUES
(1, NULL, 19, '127.0.0.1', 'Windows 10'),
(2, NULL, 20, '127.0.0.1', 'Windows 10'),
(3, NULL, 21, '127.0.0.1', 'Windows 10'),
(4, NULL, 21, '127.0.0.1', 'Windows 10'),
(5, NULL, 20, '127.0.0.1', 'Windows 10'),
(6, NULL, 20, '127.0.0.1', 'Windows 10');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT dla tabeli `delivery_method`
--
ALTER TABLE `delivery_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `price_method`
--
ALTER TABLE `price_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT dla tabeli `product_image`
--
ALTER TABLE `product_image`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `user_session`
--
ALTER TABLE `user_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT dla tabeli `view_product`
--
ALTER TABLE `view_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
