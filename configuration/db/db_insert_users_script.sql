-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 14, 2020 at 01:05 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `status`, `photo_name`, `points`, `qr_code`) VALUES
(1, 'Ivaylo', 'Ivanov', 'ivaylo@abv.bg', '$2y$10$Ls2.zREScuqEGrrKMnMG0.85GrH98IN.R8wW7XjA2Bo7X/PCFhogK', 'ADMIN', '5ee5180bf36ed.png', 0, NULL),
(2, 'Ivan', 'Ivanov', 'ivan@abv.bg', '$2y$10$Yog94zaiDexL9D8lTGVNDOWm0N8m7SX.6QThOF7BB2sXQuJdO7YEm', 'PERMANENT', '5ee518b7a655a.png', 1, 'IvanIvanov.png'),
(3, 'Hristo', 'Hristov', 'hristo@abv.bg', '$2y$10$XaPMseb8LfGRNMTddGqY0.sFj/9/qjc7El70PQA7mzqHCukisnKZy', 'TEMPORARY', '5ee518da17fb5.png', 5, 'HristoHristov.png'),
(4, 'Milen', 'Petrov', 'milen@abv.bg', '$2y$10$1yIgB0h3sMus6SgPdYsgGeR0Tkq5NXJ9GXD7upTHe0LxLHPINdDaG', 'PERMANENT', '5ee519159ab10.png', 9, 'MilenPetrov.png'),
(5, 'Georgi', 'Georgiev', 'georgi@abv.bg', '$2y$10$EUzz2WrtucWaAhWfVO89AOdJWwunrCGri5t8KN8nmJtVPlKkyqwqO', 'BLOCKED', '5ee5193401d0e.jpg', 0, 'GeorgiGeorgiev.png'),
(6, 'Plamena', 'Plamenova', 'plamena@abv.bg', '$2y$10$zb7jk7Q.g9ZvNd542VmVNuoML32y.slwXi9eLX3KKjJPzy.L6/Wbu', 'PERMANENT', '5ee5196c0dd0b.jpg', 0, 'PlamenaPlamenova.png'),
(7, 'Iva', 'Ivanova', 'iva@abv.bg', '$2y$10$hg.ji3ovh.Mu.Nl/lcNR3uriqvmW/pnth1ob6bUmTDJHY9U/x3GlC', 'TEMPORARY', '5ee5198cab9ad.jpg', -1, 'IvaIvanova.png'),
(8, 'Daniela', 'Danielova', 'daniela@abv.bg', '$2y$10$g/o7rTzW/d3DIufRNNvoEesmUSvlzuDo7L76VhFHZk6ZAIOoafcpq', 'TEMPORARY', '5ee51a1d86cff.jpg', 3, 'DanielaDanielova.png');