-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Авг 12 2017 г., 14:59
-- Версия сервера: 5.7.13
-- Версия PHP: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `apklausos`
--

-- --------------------------------------------------------

--
-- Структура таблицы `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(10) unsigned NOT NULL,
  `position_id` int(11) NOT NULL,
  `body` text COLLATE utf8_unicode_ci,
  `status` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `campaigns`
--

CREATE TABLE IF NOT EXISTS `campaigns` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `respondents` int(11) NOT NULL DEFAULT '0',
  `send_email` int(11) NOT NULL DEFAULT '0',
  `same_computer` int(11) NOT NULL DEFAULT '0',
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `video` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `public` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `advertise_results` int(11) NOT NULL DEFAULT '0',
  `advertise_credits` int(11) NOT NULL DEFAULT '0',
  `used_credits` int(11) NOT NULL DEFAULT '0',
  `used_results` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `campaigns`
--

INSERT INTO `campaigns` (`id`, `user_id`, `title`, `description`, `respondents`, `send_email`, `same_computer`, `photo`, `video`, `created_at`, `updated_at`, `public`, `active`, `advertise_results`, `advertise_credits`, `used_credits`, `used_results`) VALUES
(1, 1, 'Lorem ipsum dolores or other text', 'Lorem ipsum dolores or other text. Lorem ipsum dolores or other text\r\n\r\nLorem ipsum dolores or other text\r\n\r\nLorem ipsum dolores or other text. Lorem ipsum dolores or other text', 1, 1, 1, 'uploads/campaigns/photos/MQ==/default/boxes.png', 'http://apk.local/sukurti-anketa', '2017-07-11 08:38:08', '2017-08-11 02:25:56', 1, 1, 0, 0, 46, 1),
(2, 4, 'New campaigns for test', 'Lorem ipsum dolores or other text. Lorem ipsum dolores or other text.', 1, 1, 1, NULL, NULL, '2017-08-11 02:40:33', '2017-08-11 02:44:41', 1, 1, 0, 0, 20, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `campaigns_answers`
--

CREATE TABLE IF NOT EXISTS `campaigns_answers` (
  `id` int(10) unsigned NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `result_id` int(10) unsigned NOT NULL,
  `option_id` int(11) NOT NULL DEFAULT '0',
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `campaigns_answers`
--

INSERT INTO `campaigns_answers` (`id`, `campaign_id`, `question_id`, `result_id`, `option_id`, `value`, `type`, `created_at`, `updated_at`) VALUES
(7, 1, 1, 4, 19, NULL, 'radio', '2017-08-02 11:18:41', '2017-08-02 11:18:41'),
(8, 1, 2, 4, 21, NULL, 'radio', '2017-08-02 11:18:41', '2017-08-02 11:18:41'),
(9, 1, 1, 5, 19, NULL, 'radio', '2017-08-02 11:26:41', '2017-08-02 11:26:41'),
(10, 1, 2, 5, 22, NULL, 'radio', '2017-08-02 11:26:41', '2017-08-02 11:26:41'),
(11, 1, 1, 6, 20, NULL, 'radio', '2017-08-11 02:25:56', '2017-08-11 02:25:56'),
(12, 1, 2, 6, 21, NULL, 'radio', '2017-08-11 02:25:56', '2017-08-11 02:25:56'),
(13, 2, 3, 7, 23, NULL, 'radio', '2017-08-11 02:43:17', '2017-08-11 02:43:17'),
(14, 2, 3, 8, 24, NULL, 'radio', '2017-08-11 02:44:41', '2017-08-11 02:44:41');

-- --------------------------------------------------------

--
-- Структура таблицы `campaigns_questions`
--

CREATE TABLE IF NOT EXISTS `campaigns_questions` (
  `id` int(10) unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `campaign_id` int(10) unsigned NOT NULL,
  `required` int(11) NOT NULL DEFAULT '0',
  `custom_answer` int(11) NOT NULL DEFAULT '0',
  `private` int(11) NOT NULL DEFAULT '0',
  `note` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `video` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `campaigns_questions`
--

INSERT INTO `campaigns_questions` (`id`, `type`, `title`, `campaign_id`, `required`, `custom_answer`, `private`, `note`, `photo`, `video`, `created_at`, `updated_at`) VALUES
(1, 'radio', 'Lorem ipsum', 1, 1, 0, 0, NULL, NULL, NULL, '2017-07-27 06:32:51', '2017-08-02 09:27:14'),
(2, 'radio', 'Lorem ipsum #2', 1, 1, 0, 0, NULL, NULL, NULL, '2017-08-02 03:26:29', '2017-08-02 09:27:18'),
(3, 'radio', 'Lorem ipsum', 2, 1, 0, 0, NULL, NULL, NULL, '2017-08-11 02:40:51', '2017-08-11 02:40:51');

-- --------------------------------------------------------

--
-- Структура таблицы `campaigns_questions_options`
--

CREATE TABLE IF NOT EXISTS `campaigns_questions_options` (
  `id` int(10) unsigned NOT NULL,
  `question_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `matrix` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `campaigns_questions_options`
--

INSERT INTO `campaigns_questions_options` (`id`, `question_id`, `title`, `matrix`, `created_at`, `updated_at`) VALUES
(19, 1, 'Yes', '', '2017-08-02 09:27:14', '2017-08-02 09:27:14'),
(20, 1, 'No', '', '2017-08-02 09:27:14', '2017-08-02 09:27:14'),
(21, 2, 'Be', '', '2017-08-02 09:27:18', '2017-08-02 09:27:18'),
(22, 2, 'Or not to be', '', '2017-08-02 09:27:18', '2017-08-02 09:27:18'),
(23, 3, 'Yes', '', '2017-08-11 02:40:52', '2017-08-11 02:40:52'),
(24, 3, 'No', '', '2017-08-11 02:40:52', '2017-08-11 02:40:52');

-- --------------------------------------------------------

--
-- Структура таблицы `campaigns_results`
--

CREATE TABLE IF NOT EXISTS `campaigns_results` (
  `id` int(10) unsigned NOT NULL,
  `campaign_id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `campaigns_results`
--

INSERT INTO `campaigns_results` (`id`, `campaign_id`, `user_id`, `ip`, `created_at`, `updated_at`) VALUES
(4, 1, 0, '127.0.0.1', '2017-08-02 11:18:41', '2017-08-02 11:18:41'),
(5, 1, 4, '127.0.0.1', '2017-08-02 11:26:41', '2017-08-02 11:26:41'),
(6, 1, 4, '127.0.0.1', '2017-08-11 02:25:56', '2017-08-11 02:25:56'),
(7, 2, 4, '127.0.0.1', '2017-08-11 02:43:17', '2017-08-11 02:43:17'),
(8, 2, 4, '127.0.0.1', '2017-08-11 02:44:41', '2017-08-11 02:44:41');

-- --------------------------------------------------------

--
-- Структура таблицы `campaigns_tags`
--

CREATE TABLE IF NOT EXISTS `campaigns_tags` (
  `id` int(10) unsigned NOT NULL,
  `campaign_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `campaigns_tags`
--

INSERT INTO `campaigns_tags` (`id`, `campaign_id`, `title`, `created_at`, `updated_at`) VALUES
(18, 1, 'http://apk.local/sukurti-anketa', '2017-08-02 09:27:22', '2017-08-02 09:27:22');

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_09_09_063543_create_users_table', 1),
('2014_09_09_093831_create_users_networks_table', 1),
('2014_09_11_063805_create_users_credits_table', 1),
('2014_09_11_074048_create_payments_table', 1),
('2014_09_17_120013_create_campaigns_table', 1),
('2014_09_17_120346_create_campaigns_tags_table', 1),
('2014_09_24_204622_create_campaigns_questions_table', 1),
('2014_09_26_111851_create_campaigns_results_table', 1),
('2014_09_26_143428_create_campaigns_questions_options_table', 1),
('2014_09_26_165536_update_campaigns_table', 1),
('2014_09_26_174011_create_campaigns_answers_table', 1),
('2014_09_28_220628_update_campaigns_table2', 1),
('2014_10_06_083457_create_advertised_answers', 1),
('2014_10_06_085303_update_users_table', 1),
('2014_10_09_152812_create_password_reminders_table', 1),
('2017_07_19_084046_create_banners_positions_table', 4),
('2017_07_15_223105_create_pages_table', 5),
('2017_07_19_083602_create_banners_table', 5),
('2017_08_10_075759_create_settings_table', 5);

-- --------------------------------------------------------

--
-- Структура таблицы `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `meta_title`, `meta_description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Dažniausiai užduodami klausimai', 'duk', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Totam minus inventore ea, perferendis nam corrupti, explicabo eveniet eius impedit ab esse expedita alias cum laborum, ducimus illo molestiae atque nihil, itaque sunt ipsam. Deleniti exercitationem odit quidem fuga illum laborum accusantium corporis pariatur voluptate debitis voluptas possimus quibusdam, distinctio veritatis hic odio ipsa doloremque autem est ad itaque, dicta consectetur enim adipisci totam. Dolorum iste laudantium nihil perspiciatis eius velit iure, suscipit atque excepturi aliquam autem, reiciendis eos labore tempora eaque adipisci beatae unde magni ipsum incidunt ex veniam, dignissimos hic praesentium doloremque. Illo quia excepturi quisquam repellat, repellendus possimus?</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia doloremque dolores tempora odio modi possimus velit dolor, esse adipisci! Quam aliquam incidunt tempore, odio voluptatem rerum amet facilis accusamus atque non deleniti labore esse! Repellat a tempora dicta, neque, fugiat, sed tempore temporibus explicabo eaque expedita consequuntur et itaque optio aperiam corporis cupiditate! Veritatis quia quas ipsum accusamus excepturi, minima laborum ut perferendis, atque vitae, earum id repellendus veniam beatae dolorum suscipit debitis. Consequatur rerum facilis iusto. Accusamus rerum similique omnis cumque odio, at veritatis commodi atque rem consequatur, culpa nemo, minus saepe unde fugit inventore in eos architecto. Quae consequatur asperiores, nihil illum minus necessitatibus autem non ipsum, excepturi tempora odit praesentium quasi velit harum. Quibusdam accusamus, temporibus quia nemo eligendi sed voluptatum aliquam cumque iure asperiores harum ea corporis, numquam, at dolores quos minus debitis dolor quas eum, sunt doloribus inventore. Facilis, mollitia, facere! Aut praesentium, vitae odit eum fugit repudiandae consectetur. Voluptas animi eligendi quo quis quisquam provident vel, iusto, repudiandae optio, hic nam placeat quae, error doloremque in perferendis exercitationem rem! Voluptates optio rerum rem est eligendi facilis voluptatem dolorum in suscipit aut dolor culpa, laborum deserunt quis eveniet ipsam, accusantium blanditiis. Veritatis beatae corrupti ipsum minus numquam soluta, harum voluptas consequuntur quo sunt molestiae iure deleniti omnis sed cumque. Facere quae, asperiores laboriosam perspiciatis? Error atque repudiandae assumenda, natus in recusandae nostrum quod ducimus ipsa fuga facere voluptatum omnis quia corporis ratione dolorem laboriosam dignissimos. Ipsum accusantium laborum, pariatur autem porro saepe adipisci tempora quidem.</p>', 'Dažniausiai užduodami klausimai', NULL, 1, NULL, NULL),
(2, 'Kontaktai', 'kontaktai', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Totam minus inventore ea, perferendis nam corrupti, explicabo eveniet eius impedit ab esse expedita alias cum laborum, ducimus illo molestiae atque nihil, itaque sunt ipsam. Deleniti exercitationem odit quidem fuga illum laborum accusantium corporis pariatur voluptate debitis voluptas possimus quibusdam, distinctio veritatis hic odio ipsa doloremque autem est ad itaque, dicta consectetur enim adipisci totam. Dolorum iste laudantium nihil perspiciatis eius velit iure, suscipit atque excepturi aliquam autem, reiciendis eos labore tempora eaque adipisci beatae unde magni ipsum incidunt ex veniam, dignissimos hic praesentium doloremque. Illo quia excepturi quisquam repellat, repellendus possimus?</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia doloremque dolores tempora odio modi possimus velit dolor, esse adipisci! Quam aliquam incidunt tempore, odio voluptatem rerum amet facilis accusamus atque non deleniti labore esse! Repellat a tempora dicta, neque, fugiat, sed tempore temporibus explicabo eaque expedita consequuntur et itaque optio aperiam corporis cupiditate! Veritatis quia quas ipsum accusamus excepturi, minima laborum ut perferendis, atque vitae, earum id repellendus veniam beatae dolorum suscipit debitis. Consequatur rerum facilis iusto. Accusamus rerum similique omnis cumque odio, at veritatis commodi atque rem consequatur, culpa nemo, minus saepe unde fugit inventore in eos architecto. Quae consequatur asperiores, nihil illum minus necessitatibus autem non ipsum, excepturi tempora odit praesentium quasi velit harum. Quibusdam accusamus, temporibus quia nemo eligendi sed voluptatum aliquam cumque iure asperiores harum ea corporis, numquam, at dolores quos minus debitis dolor quas eum, sunt doloribus inventore. Facilis, mollitia, facere! Aut praesentium, vitae odit eum fugit repudiandae consectetur. Voluptas animi eligendi quo quis quisquam provident vel, iusto, repudiandae optio, hic nam placeat quae, error doloremque in perferendis exercitationem rem! Voluptates optio rerum rem est eligendi facilis voluptatem dolorum in suscipit aut dolor culpa, laborum deserunt quis eveniet ipsam, accusantium blanditiis. Veritatis beatae corrupti ipsum minus numquam soluta, harum voluptas consequuntur quo sunt molestiae iure deleniti omnis sed cumque. Facere quae, asperiores laboriosam perspiciatis? Error atque repudiandae assumenda, natus in recusandae nostrum quod ducimus ipsa fuga facere voluptatum omnis quia corporis ratione dolorem laboriosam dignissimos. Ipsum accusantium laborum, pariatur autem porro saepe adipisci tempora quidem.</p>', 'Kontaktai', NULL, 1, NULL, NULL),
(3, 'Naudojimosi taisyklės', 'naudojimosi-taisykles', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Totam minus inventore ea, perferendis nam corrupti, explicabo eveniet eius impedit ab esse expedita alias cum laborum, ducimus illo molestiae atque nihil, itaque sunt ipsam. Deleniti exercitationem odit quidem fuga illum laborum accusantium corporis pariatur voluptate debitis voluptas possimus quibusdam, distinctio veritatis hic odio ipsa doloremque autem est ad itaque, dicta consectetur enim adipisci totam. Dolorum iste laudantium nihil perspiciatis eius velit iure, suscipit atque excepturi aliquam autem, reiciendis eos labore tempora eaque adipisci beatae unde magni ipsum incidunt ex veniam, dignissimos hic praesentium doloremque. Illo quia excepturi quisquam repellat, repellendus possimus?</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia doloremque dolores tempora odio modi possimus velit dolor, esse adipisci! Quam aliquam incidunt tempore, odio voluptatem rerum amet facilis accusamus atque non deleniti labore esse! Repellat a tempora dicta, neque, fugiat, sed tempore temporibus explicabo eaque expedita consequuntur et itaque optio aperiam corporis cupiditate! Veritatis quia quas ipsum accusamus excepturi, minima laborum ut perferendis, atque vitae, earum id repellendus veniam beatae dolorum suscipit debitis. Consequatur rerum facilis iusto. Accusamus rerum similique omnis cumque odio, at veritatis commodi atque rem consequatur, culpa nemo, minus saepe unde fugit inventore in eos architecto. Quae consequatur asperiores, nihil illum minus necessitatibus autem non ipsum, excepturi tempora odit praesentium quasi velit harum. Quibusdam accusamus, temporibus quia nemo eligendi sed voluptatum aliquam cumque iure asperiores harum ea corporis, numquam, at dolores quos minus debitis dolor quas eum, sunt doloribus inventore. Facilis, mollitia, facere! Aut praesentium, vitae odit eum fugit repudiandae consectetur. Voluptas animi eligendi quo quis quisquam provident vel, iusto, repudiandae optio, hic nam placeat quae, error doloremque in perferendis exercitationem rem! Voluptates optio rerum rem est eligendi facilis voluptatem dolorum in suscipit aut dolor culpa, laborum deserunt quis eveniet ipsam, accusantium blanditiis. Veritatis beatae corrupti ipsum minus numquam soluta, harum voluptas consequuntur quo sunt molestiae iure deleniti omnis sed cumque. Facere quae, asperiores laboriosam perspiciatis? Error atque repudiandae assumenda, natus in recusandae nostrum quod ducimus ipsa fuga facere voluptatum omnis quia corporis ratione dolorem laboriosam dignissimos. Ipsum accusantium laborum, pariatur autem porro saepe adipisci tempora quidem.</p>', 'Naudojimosi taisyklės', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('john@smith.com', '$2y$10$JN7oHXXbEliezRdtwQFfZe9DI7Z9blc3FbFkj//RyyKBii8AUGE/q', '2017-07-14 11:34:54');

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `ammount` int(11) NOT NULL,
  `paid` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `ammount`, `paid`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 0, '2017-07-11 10:05:55', '2017-07-11 10:05:55'),
(2, 1, 5, 0, '2017-07-11 10:06:44', '2017-07-11 10:06:44'),
(3, 1, 5, 0, '2017-07-11 10:07:23', '2017-07-11 10:07:23');

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) unsigned NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'registration_credits', '100', NULL, NULL),
(2, 'campaigns_credits', '10', NULL, NULL),
(3, 'featured_credits', '5', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `role` int(11) NOT NULL DEFAULT '0',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `remember_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `role`, `email`, `username`, `password`, `photo`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'user@host.org', 'Admin', '$2y$10$plFtgMQwLU39Q1U0Buh4Re1QNVusUbhpKmvPO.rBLoUPXh2yyhhwG', NULL, 1, 'jUKfNwZ1WLgBpe4HoyjkC8ZJJJ39eEEXjN5EOtxoZ7w0mKeXNhHcvF5KhZpi', '2017-06-30 07:15:49', '2017-07-17 04:11:48'),
(2, 0, 'john@smith.com', 'John', '$2y$10$9lMD/gRAPOTsY.9dkkmWvepIq5MhJ2XDBKs.mmBwrc0g8gI4N9rGO', NULL, 1, 'KK5Jw3zoCA1ma8XxqBpVG6GcIVVqVJKqjWiJqh6BKHDU0qhajW1WIDQqdk4O', '2017-07-11 10:39:04', '2017-08-10 08:44:20'),
(3, 0, 'test@host.org', 'test', '$2y$10$rUKDKtvbUV0yh9NpPleK8uYyZfHJUNoxo8OC4e6m4RzyNHOxUc9qK', 'uploads/users/photos//default/icon.jpg', 1, NULL, '2017-07-19 10:40:39', '2017-07-19 10:56:37'),
(4, 0, 'john@doe.com', 'John Doe', '$2y$10$mWNx.20CNY6mqMM3TNTIYuSLYIc2ZWurTd2mQFy.tUyzpqEeT57ou', NULL, 1, 'Nw9MqBNy8wzWpobEpWJcy48qyKkVNUxzrqEQlGGZXksM4IFAv6mLNA1eFSTl', '2017-08-02 11:20:41', '2017-08-02 11:20:41'),
(6, 0, 'admin@host.org', 'admin2', '$2y$10$OAN1YQQ9eN/bHaGmg0Wv5OF4oBFQ/WPedBfRunlv4ESAmhseO.Qeq', NULL, 1, 'kpdcszBgD2ZdY8XRlk507K3anvsZ3wKVxSLboR3L1IxtaDw4vfOqRX1gv5C8', '2017-08-06 04:45:49', '2017-08-06 04:45:49');

-- --------------------------------------------------------

--
-- Структура таблицы `users_credits`
--

CREATE TABLE IF NOT EXISTS `users_credits` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `credits` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `users_credits`
--

INSERT INTO `users_credits` (`id`, `user_id`, `credits`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 100, 'Įskaitymas', '2015-03-23 11:53:38', '2015-03-23 11:53:38'),
(2, 1, -4, 'Už reklamuojamą atsakymą', '2017-08-02 08:02:15', '2017-08-02 08:02:15'),
(3, 1, -4, 'Už reklamuojamą atsakymą', '2017-08-02 08:02:22', '2017-08-02 08:02:22'),
(4, 1, -4, 'Už reklamuojamą atsakymą', '2017-08-02 08:02:54', '2017-08-02 08:02:54'),
(5, 2, 4, 'Už atsakymą', '2017-08-02 08:02:54', '2017-08-02 08:02:54'),
(6, 1, -4, 'Už reklamuojamą atsakymą', '2017-08-02 11:18:41', '2017-08-02 11:18:41'),
(7, 4, 100, '', '2017-08-02 11:20:41', '2017-08-02 11:20:41'),
(8, 1, -4, 'Už reklamuojamą atsakymą', '2017-08-02 11:26:41', '2017-08-02 11:26:41'),
(9, 4, 4, 'Už atsakymą', '2017-08-02 11:26:41', '2017-08-02 11:26:41'),
(10, 6, 100, 'Įskaitymas', '2017-08-06 04:45:49', '2017-08-06 04:45:49'),
(11, 1, -10, 'Už reklamuojamą atsakymą', '2017-08-11 02:25:56', '2017-08-11 02:25:56'),
(12, 4, 10, 'Už atsakymą', '2017-08-11 02:25:56', '2017-08-11 02:25:56'),
(13, 4, -10, 'Už reklamuojamą atsakymą', '2017-08-11 02:43:17', '2017-08-11 02:43:17'),
(14, 4, -10, 'Už reklamuojamą atsakymą', '2017-08-11 02:44:41', '2017-08-11 02:44:41');

-- --------------------------------------------------------

--
-- Структура таблицы `users_networks`
--

CREATE TABLE IF NOT EXISTS `users_networks` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `social_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `campaigns_answers`
--
ALTER TABLE `campaigns_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaigns_answers_result_id_foreign` (`result_id`);

--
-- Индексы таблицы `campaigns_questions`
--
ALTER TABLE `campaigns_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaigns_questions_campaign_id_foreign` (`campaign_id`);

--
-- Индексы таблицы `campaigns_questions_options`
--
ALTER TABLE `campaigns_questions_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaigns_questions_options_question_id_foreign` (`question_id`);

--
-- Индексы таблицы `campaigns_results`
--
ALTER TABLE `campaigns_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaigns_results_campaign_id_foreign` (`campaign_id`);

--
-- Индексы таблицы `campaigns_tags`
--
ALTER TABLE `campaigns_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaigns_tags_campaign_id_foreign` (`campaign_id`);

--
-- Индексы таблицы `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_reminders_email_index` (`email`),
  ADD KEY `password_reminders_token_index` (`token`);

--
-- Индексы таблицы `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_key_index` (`key`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Индексы таблицы `users_credits`
--
ALTER TABLE `users_credits`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_networks`
--
ALTER TABLE `users_networks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `campaigns_answers`
--
ALTER TABLE `campaigns_answers`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT для таблицы `campaigns_questions`
--
ALTER TABLE `campaigns_questions`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `campaigns_questions_options`
--
ALTER TABLE `campaigns_questions_options`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT для таблицы `campaigns_results`
--
ALTER TABLE `campaigns_results`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `campaigns_tags`
--
ALTER TABLE `campaigns_tags`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT для таблицы `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `users_credits`
--
ALTER TABLE `users_credits`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT для таблицы `users_networks`
--
ALTER TABLE `users_networks`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `campaigns_answers`
--
ALTER TABLE `campaigns_answers`
  ADD CONSTRAINT `campaigns_answers_result_id_foreign` FOREIGN KEY (`result_id`) REFERENCES `campaigns_results` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `campaigns_questions`
--
ALTER TABLE `campaigns_questions`
  ADD CONSTRAINT `campaigns_questions_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `campaigns_questions_options`
--
ALTER TABLE `campaigns_questions_options`
  ADD CONSTRAINT `campaigns_questions_options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `campaigns_questions` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `campaigns_results`
--
ALTER TABLE `campaigns_results`
  ADD CONSTRAINT `campaigns_results_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `campaigns_tags`
--
ALTER TABLE `campaigns_tags`
  ADD CONSTRAINT `campaigns_tags_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
