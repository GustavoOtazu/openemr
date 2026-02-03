-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-02-2026 a las 01:50:41
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
-- Estructura de tabla para la tabla `form_aplicaciones`
--

CREATE TABLE `form_aplicaciones` (
  `id` bigint(20) NOT NULL,
  `date` datetime DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `encounter` int(11) NOT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `medicamentos` tinyint(1) DEFAULT '0',
  `obs_medicamentos` varchar(255) DEFAULT NULL,
  `sueros` tinyint(1) DEFAULT '0',
  `obs_sueros` varchar(255) DEFAULT NULL,
  `vacunas` tinyint(1) DEFAULT '0',
  `obs_vacunas` varchar(255) DEFAULT NULL,
  `expansiones` tinyint(1) DEFAULT '0',
  `obs_expansiones` varchar(255) DEFAULT NULL,
  `sangre` tinyint(1) DEFAULT '0',
  `obs_sangre` varchar(255) DEFAULT NULL,
  `hora_registro` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `form_aplicaciones`
--

INSERT INTO `form_aplicaciones` (`id`, `date`, `pid`, `encounter`, `user`, `groupname`, `authorized`, `activity`, `medicamentos`, `obs_medicamentos`, `sueros`, `obs_sueros`, `vacunas`, `obs_vacunas`, `expansiones`, `obs_expansiones`, `sangre`, `obs_sangre`, `hora_registro`) VALUES
(1, '2025-10-26 00:16:23', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 0, 'ASasAS', 0, '', 1, '', 0, 'asAS', 0, 'asASas', '00:16:00'),
(2, '2025-10-29 23:50:25', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 1, 'uyuuy', 1, 'giuuyiu', 0, '', 0, '', 0, '', '00:00:00'),
(3, '2025-10-30 00:02:34', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 1, 'adasdasd', 1, 'SADASD', 1, 'ASDASD', 1, 'ASDSDAS', 1, 'ASDASDASD', '00:01:00'),
(4, '2025-11-01 21:21:58', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 1, 'aSDASDA', 1, 'ADADA', 0, '', 0, '', 0, '', '21:21:00'),
(5, '2025-11-02 14:18:32', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 1, 'ASasSdasdasd', 1, '', 0, '', 1, 'sdfsdf', 1, 'sdfsdf', '13:52:00'),
(6, '2025-11-02 14:18:47', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 1, 'asdasd', 1, 'asdasdas', 0, '', 0, '', 0, '', '14:18:00'),
(7, '2025-11-02 14:24:14', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 1, 'asdasd', 1, 'asdasdasdasd', 0, '', 1, 'asdasd', 1, 'asdasd', '14:23:00'),
(8, '2025-11-02 15:54:44', 775369, 775369, 'BPZ-admin-45', 'Default', 1, 1, 1, 'adasdasd', 1, 'asdasdasd', 1, 'asdasd', 1, 'asdasdasd', 0, '', '15:54:00'),
(9, '2025-11-07 20:47:59', 775369, 775369, 'BPZ-admin-45', 'Default', 1, 1, 1, 'adfasfasfasdasd', 1, 'asfasf', 0, 'asfasf', 1, 'asfasf', 1, 'asdasd', '20:46:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `form_aplicaciones`
--
ALTER TABLE `form_aplicaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`,`encounter`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `form_aplicaciones`
--
ALTER TABLE `form_aplicaciones`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
