-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-02-2026 a las 01:53:21
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
-- Estructura de tabla para la tabla `form_cuidados`
--

CREATE TABLE `form_cuidados` (
  `id` bigint(20) NOT NULL,
  `date` datetime DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `encounter` int(11) NOT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `posicion_paciente` varchar(50) DEFAULT NULL,
  `obs_posicion_paciente` varchar(255) DEFAULT NULL,
  `enjuague_bucal` tinyint(1) DEFAULT '0',
  `obs_enjuague_bucal` varchar(255) DEFAULT NULL,
  `higiene_manos` tinyint(1) DEFAULT '0',
  `obs_higiene_manos` varchar(255) DEFAULT NULL,
  `aspirado_secreciones` tinyint(1) DEFAULT '0',
  `obs_aspirado_secreciones` varchar(255) DEFAULT NULL,
  `suspension_sedacion` tinyint(1) DEFAULT '0',
  `obs_suspension_sedacion` varchar(255) DEFAULT NULL,
  `medicion_cuff` tinyint(1) DEFAULT '0',
  `obs_medicion_cuff` varchar(255) DEFAULT NULL,
  `hora_cuidado` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `form_cuidados`
--

INSERT INTO `form_cuidados` (`id`, `date`, `pid`, `encounter`, `user`, `groupname`, `authorized`, `activity`, `posicion_paciente`, `obs_posicion_paciente`, `enjuague_bucal`, `obs_enjuague_bucal`, `higiene_manos`, `obs_higiene_manos`, `aspirado_secreciones`, `obs_aspirado_secreciones`, `suspension_sedacion`, `obs_suspension_sedacion`, `medicion_cuff`, `obs_medicion_cuff`, `hora_cuidado`) VALUES
(1, '2025-10-19 23:32:49', 775369, 775369, 'BPZ-admin-45', 'Default', 1, 1, 'DS', '', 1, '', 0, 'qweqweqwe', 1, 'qwqweqwe', 0, 'qweqweqwe', 1, 'weqweqwe', '23:32:00'),
(2, '2025-10-20 00:12:44', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 'DLD', 'jfgfhfhfjfj', 1, 'jhfhgfhgf', 0, '', 0, 'jhvhjgfhgfh', 1, 'jhvhghgfg', 0, '', '00:12:00'),
(3, '2025-10-20 00:12:44', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 'DLD', 'jfgfhfhfjfj', 1, 'jhfhgfhgf', 0, '', 0, 'jhvhjgfhgfh', 1, 'jhvhghgfg', 0, '', '00:12:00'),
(4, '2025-10-20 01:34:56', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 'CABECERA 30°', '', 0, '', 0, 'kjkhkj', 0, '', 0, '', 0, '', '01:34:00'),
(5, '2025-11-08 08:19:31', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 'DLI', 'adasdasdasdasd', 1, 'asdasdasd', 1, 'asdasdasdsad', 1, 'asdasd', 0, '', 1, 'asdasdasdjkhgjh', '14:36:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `form_cuidados`
--
ALTER TABLE `form_cuidados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pid_encounter` (`pid`,`encounter`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `form_cuidados`
--
ALTER TABLE `form_cuidados`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
