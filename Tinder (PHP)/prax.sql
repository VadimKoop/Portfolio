-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Дек 16 2015 г., 23:18
-- Версия сервера: 5.6.17
-- Версия PHP: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `prax`
--

-- --------------------------------------------------------

--
-- Структура таблицы `mylikevadim`
--

CREATE TABLE IF NOT EXISTS `mylikevadim` (
  `idlike` int(11) NOT NULL AUTO_INCREMENT,
  `id1` int(11) NOT NULL,
  `id2` int(11) NOT NULL,
  `meeldib` enum('yes','no') CHARACTER SET utf8 NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`idlike`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Структура таблицы `myvadimregister`
--

CREATE TABLE IF NOT EXISTS `myvadimregister` (
  `id` int(11) NOT NULL,
  `login` varchar(24) CHARACTER SET utf8 NOT NULL,
  `name` varchar(24) CHARACTER SET utf8 NOT NULL,
  `password` varchar(24) CHARACTER SET utf8 NOT NULL,
  `email` varchar(24) CHARACTER SET utf8 NOT NULL,
  `sex` varchar(24) CHARACTER SET utf8 NOT NULL,
  `pic` varchar(24) DEFAULT NULL,
  `description` text,
  `current` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `myvadimregister`
--

INSERT INTO `myvadimregister` (`id`, `login`, `name`, `password`, `email`, `sex`, `pic`, `description`, `current`) VALUES
(1, 'x', 'g', 'g', 'g', 'woman', NULL, NULL, 1),
(2, 'Max', 'Max', '123', 'gkakga@gmail.com', 'man', '2.jpg', 'It''s Max! Not mini!', 1),
(3, 'Lera', 'Lera', '123', 'kjgaa@gmail.com', 'woman', '3.jpg', 'Hi it''s Lera!', 1),
(4, 'Karolina', 'Karolina', '123', 'jhgat@gmail.com', 'woman', '4.jpg', 'Hi its''t Karolina!', 1),
(5, 'MiniMax', 'Max', '123', 'Minimaxg@gmail.com', 'man', '5.jpg', 'Hello it''s Max!', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `register.php`
--

CREATE TABLE IF NOT EXISTS `register.php` (
  `id` int(255) NOT NULL,
  `login` varchar(24) NOT NULL,
  `name` varchar(24) NOT NULL,
  `password` varchar(24) NOT NULL,
  `mail` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
