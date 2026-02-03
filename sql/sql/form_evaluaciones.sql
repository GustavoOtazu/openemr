-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-02-2026 a las 01:55:44
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
-- Estructura de tabla para la tabla `form_evaluaciones`
--

CREATE TABLE `form_evaluaciones` (
  `id` bigint(20) NOT NULL,
  `date` datetime DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `encounter` int(11) NOT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `conciencia` varchar(50) DEFAULT NULL,
  `obs_conciencia` varchar(255) DEFAULT NULL,
  `tono` varchar(50) DEFAULT NULL,
  `obs_tono` varchar(255) DEFAULT NULL,
  `pupilas` varchar(50) DEFAULT NULL,
  `obs_pupilas` varchar(255) DEFAULT NULL,
  `mucosas` varchar(50) DEFAULT NULL,
  `obs_mucosas` varchar(255) DEFAULT NULL,
  `glasgow_ojos` varchar(50) DEFAULT NULL,
  `obs_glasgow_ojos` varchar(255) DEFAULT NULL,
  `glasgow_motora` varchar(50) DEFAULT NULL,
  `obs_glasgow_motora` varchar(255) DEFAULT NULL,
  `glasgow_verbal` varchar(50) DEFAULT NULL,
  `obs_glasgow_verbal` varchar(255) DEFAULT NULL,
  `glasgow_total` int(2) DEFAULT NULL,
  `hora_evaluacion` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `form_evaluaciones`
--

INSERT INTO `form_evaluaciones` (`id`, `date`, `pid`, `encounter`, `user`, `groupname`, `authorized`, `activity`, `conciencia`, `obs_conciencia`, `tono`, `obs_tono`, `pupilas`, `obs_pupilas`, `mucosas`, `obs_mucosas`, `glasgow_ojos`, `obs_glasgow_ojos`, `glasgow_motora`, `obs_glasgow_motora`, `glasgow_verbal`, `obs_glasgow_verbal`, `glasgow_total`, `hora_evaluacion`) VALUES
(1, '2025-10-21 02:23:32', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 'SOMNOLIENTO', 'asdasdasd', 'FLACIDO', 'asdasdasd', 'MIDRIASIS', 'asdasdasd', 'HUMEDA', 'asdasdasd', 'ESPONTANEAMENTE', 'asdasdas', 'LOCALIZA DOLOR', 'asdasdasdasd', 'DESORIENTADO Y CONVERSA', 'asdasdasdasd', 13, '02:22:00'),
(2, '2025-11-02 15:07:45', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 'VIGIL', 'asdasd', 'NORMAL', 'asdasdasd', 'MIDRIASIS', 'asdasdasd', 'HUMEDA', 'sdasdasd', 'ESPONTANEAMENTE', 'asdasdasd', 'OBEDECE ORDENES', 'asdasdasd', 'ORIENTADO Y CONVERSA', 'asdasdasd', 15, '15:04:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `form_evaluaciones`
--
ALTER TABLE `form_evaluaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`,`encounter`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `form_evaluaciones`
--
ALTER TABLE `form_evaluaciones`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
