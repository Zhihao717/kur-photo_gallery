SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `photo_gallery`
--
CREATE DATABASE IF NOT EXISTS `photo_gallery` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `photo_gallery`;

-- --------------------------------------------------------

--
-- Структура таблицы `photos`
--

CREATE TABLE `photos` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `description` text,
  `rating` int DEFAULT '0',
  `upload_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `photos`
--

INSERT INTO `photos` (`id`, `user_id`, `image_path`, `description`, `rating`, `upload_date`) VALUES
(1, 1, 'uploads/676874d426ab3.jpg', 'Красивые белые тигры.', 4, '2024-12-22 20:21:40'),
(2, 2, 'uploads/676879482f513.jpg', 'Чёрные скакуны.', 4, '2024-12-22 20:40:40'),
(3, 3, 'uploads/67687a51d1d17.jpg', 'Дорога у озера.', 5, '2024-12-22 20:45:05'),
(4, 5, 'uploads/6768a86583cc2.jpg', 'Мост.', 1, '2024-12-23 00:01:41'),
(5, 8, 'uploads/6768b00bcd411.jpg', '', 0, '2024-12-23 00:34:19');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'user1', '$2y$10$mYO2LEhd8Ose353wmvC8xuzSIpm1wIgkSjxzNBm.zcfnweNYQs9De', 'user1@mail.ru', '2024-12-22 20:18:58'),
(2, 'user2', '$2y$10$CnKqBAkjP8xQSPdrcT.3X.F.OgIpyFUuHN1mh9GmRDvCCfMhvUMvK', 'user2@mail.ru', '2024-12-22 20:37:59'),
(3, 'user3', '$2y$10$pt2e8lySwybUbuF/hQZ5i.UkDQuhq1APnW8tTJxKprBCthDX0M1BC', 'user3@mail.ru', '2024-12-22 20:43:36'),
(4, 'user4', '$2y$10$3/bcF8NbTxA93NvSBrab/uBn2TO9RgTR6zjf4ALX.vkTOiN/aPgoG', 'user4@mail.ru', '2024-12-22 21:33:42'),
(5, 'user5', '$2y$10$lmVfq0ukz83M8pgGieAUPO7M3U6Fvn0jWrQyfjuswQ7VBAAMmLE16', 'user5@mail.ru', '2024-12-22 23:57:23'),
(6, 'user6', '$2y$10$XwyJ17MUbBjmXb27.uFTOOhNLO.NV4aCWqTTIVBaRJsR8T/Ozk8ke', 'user6@mail.ru', '2024-12-23 00:18:36'),
(8, 'user7', '$2y$10$O9XVp1QyYea0ywtrqcXBNOWKuwCbOTP/2l9qnXugHg0FBEaajKHv2', 'user7@mail.ru', '2024-12-23 00:20:38');

-- --------------------------------------------------------

--
-- Структура таблицы `votes`
--

CREATE TABLE `votes` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `photo_id` int DEFAULT NULL,
  `vote_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `votes`
--

INSERT INTO `votes` (`id`, `user_id`, `photo_id`, `vote_date`) VALUES
(1, 1, 2, '2024-12-22 21:13:44'),
(2, 1, 1, '2024-12-22 21:13:47'),
(3, 1, 3, '2024-12-22 21:13:50'),
(4, 2, 3, '2024-12-22 21:17:49'),
(5, 2, 2, '2024-12-22 21:17:57'),
(6, 3, 1, '2024-12-22 21:19:35'),
(7, 3, 3, '2024-12-22 21:23:07'),
(8, 3, 2, '2024-12-22 21:23:08'),
(9, 4, 1, '2024-12-22 21:33:54'),
(10, 4, 3, '2024-12-22 21:36:07'),
(11, 4, 2, '2024-12-22 21:36:09'),
(12, 5, 4, '2024-12-23 00:01:46'),
(13, 5, 1, '2024-12-23 00:01:51'),
(14, 8, 3, '2024-12-23 00:28:56');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_vote` (`user_id`,`photo_id`),
  ADD KEY `photo_id` (`photo_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
