-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-02-2026 a las 01:55:05
-- Versión del servidor: 10.1.40-MariaDB
-- Versión de PHP: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `openemr`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `form_curaciones`
--

CREATE TABLE `form_curaciones` (
  `id` bigint(20) NOT NULL,
  `date` datetime DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `encounter` int(11) NOT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `herida_operatoria` tinyint(1) DEFAULT '0',
  `traqueostomia` tinyint(1) DEFAULT '0',
  `ostomias` tinyint(1) DEFAULT '0',
  `escaras` tinyint(1) DEFAULT '0',
  `via_venosa_central` tinyint(1) DEFAULT '0',
  `via_venosa` tinyint(1) DEFAULT '0',
  `hora_operacion` time DEFAULT NULL,
  `obs_herida_operatoria` varchar(255) DEFAULT NULL,
  `obs_traqueostomia` varchar(255) DEFAULT NULL,
  `obs_ostomias` varchar(255) DEFAULT NULL,
  `obs_escaras` varchar(255) DEFAULT NULL,
  `obs_via_venosa_central` varchar(255) DEFAULT NULL,
  `obs_via_venosa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `form_curaciones`
--

INSERT INTO `form_curaciones` (`id`, `date`, `pid`, `encounter`, `user`, `groupname`, `authorized`, `activity`, `herida_operatoria`, `traqueostomia`, `ostomias`, `escaras`, `via_venosa_central`, `via_venosa`, `hora_operacion`, `obs_herida_operatoria`, `obs_traqueostomia`, `obs_ostomias`, `obs_escaras`, `obs_via_venosa_central`, `obs_via_venosa`) VALUES
(1, '2025-10-19 05:12:46', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 1, 1, 1, 1, 1, 1, '05:12:00', '', 'asdasd', 'asdasd', 'asdasd', '', ''),
(2, '2025-10-19 05:16:54', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 1, 1, 1, 1, 1, 1, '05:16:00', 'asdasd', 'asdasd', 'asdasdasdas', 'asdasd', 'asdasdas', 'asdasd'),
(3, '2025-10-19 05:22:03', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 1, 1, 1, 1, 1, 1, '05:21:00', 'adasdasd', 'asdasd', 'asdasd', '', 'sasdasdasd', ''),
(4, '2025-10-19 05:28:48', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 1, 1, 1, 1, 1, 1, '05:28:00', 'asasd', 'asdasd', 'asdasd', 'asdasd', 'asdasd', 'asdasd'),
(5, '2025-10-19 05:29:57', 775369, 775369, 'BPZ-admin-45', 'Default', 1, 1, 1, 1, 1, 1, 1, 1, '05:29:00', 'ZxZx', 'ZxZx', 'ZxZX', '', '', ''),
(6, '2025-10-19 05:32:37', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 0, 0, 1, 0, 1, 0, '05:32:00', 'asdasd', 'asdasd', '', 'asdasd', 'asdasdasd', ''),
(7, '2025-10-19 05:36:30', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 0, 0, 0, 1, 0, 0, '05:36:00', '', 'asasdx', '', 'asassdas', '', 'asdasdasd'),
(8, '2025-10-19 05:37:15', 826220, 826220, 'BPZ-admin-45', 'Default', 1, 1, 0, 0, 1, 1, 1, 0, '05:36:00', '', '', '', '', '', ''),
(9, '2025-10-20 01:35:11', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 0, 0, 0, 0, 0, 0, '01:35:00', 'kjkhkj', '', '', '', '', ''),
(10, '2025-11-01 20:41:49', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 0, 1, 0, 0, 0, 0, '20:41:00', '', 'lkjljl', 'kjhkjhk', '', '', ''),
(11, '2025-11-02 14:13:09', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 0, 0, 1, 1, 1, 1, '14:04:00', 'fsfsdfsdf', 'sdfsdfsd', 'asdasd', 'sdfsdf', 'sdfsdfsdf', 'asas'),
(12, '2025-11-02 14:13:32', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 1, 1, 0, 1, 0, 0, '14:13:00', 'asdasd', 'asdadasd', '', 'asdasdasd', '', ''),
(13, '2025-11-08 08:18:33', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 1, 1, 0, 0, 0, 0, '08:18:00', 'ihjhkhgkjhg', 'ghjhghjh', '', '', '', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `form_curaciones`
--
ALTER TABLE `form_curaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pid_encounter` (`pid`,`encounter`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `form_curaciones`
--
ALTER TABLE `form_curaciones`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
