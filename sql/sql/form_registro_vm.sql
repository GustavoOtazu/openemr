-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-02-2026 a las 01:56:38
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
-- Estructura de tabla para la tabla `form_registro_vm`
--

CREATE TABLE `form_registro_vm` (
  `id` bigint(20) NOT NULL,
  `date` datetime DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `encounter` int(11) NOT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT '1',
  `modo_ventilacion` varchar(50) DEFAULT NULL,
  `obs_modo` varchar(255) DEFAULT NULL,
  `hora_registro` time DEFAULT NULL,
  `presion` tinyint(1) DEFAULT '0',
  `volumen` tinyint(1) DEFAULT '0',
  `simv` tinyint(1) DEFAULT '0',
  `psv` tinyint(1) DEFAULT '0',
  `otros` tinyint(1) DEFAULT '0',
  `frecuencia_respiratoria` tinyint(1) DEFAULT '0',
  `p_inspiratorio` tinyint(1) DEFAULT '0',
  `p_media` tinyint(1) DEFAULT '0',
  `p_max` tinyint(1) DEFAULT '0',
  `chst` tinyint(1) DEFAULT '0',
  `disparo` tinyint(1) DEFAULT '0',
  `fvt` tinyint(1) DEFAULT '0',
  `vol_tidal` tinyint(1) DEFAULT '0',
  `vm_programado` tinyint(1) DEFAULT '0',
  `petco2` tinyint(1) DEFAULT '0',
  `vdvt` tinyint(1) DEFAULT '0',
  `ko2` tinyint(1) DEFAULT '0',
  `obs_presion` varchar(255) DEFAULT NULL,
  `obs_volumen` varchar(255) DEFAULT NULL,
  `obs_simv` varchar(255) DEFAULT NULL,
  `obs_psv` varchar(255) DEFAULT NULL,
  `obs_otros` varchar(255) DEFAULT NULL,
  `obs_frecuencia_respiratoria` varchar(255) DEFAULT NULL,
  `obs_p_inspiratorio` varchar(255) DEFAULT NULL,
  `obs_p_media` varchar(255) DEFAULT NULL,
  `obs_p_max` varchar(255) DEFAULT NULL,
  `obs_chst` varchar(255) DEFAULT NULL,
  `obs_disparo` varchar(255) DEFAULT NULL,
  `obs_fvt` varchar(255) DEFAULT NULL,
  `obs_vol_tidal` varchar(255) DEFAULT NULL,
  `obs_vm_programado` varchar(255) DEFAULT NULL,
  `obs_petco2` varchar(255) DEFAULT NULL,
  `obs_vdvt` varchar(255) DEFAULT NULL,
  `obs_ko2` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `form_registro_vm`
--

INSERT INTO `form_registro_vm` (`id`, `date`, `pid`, `encounter`, `user`, `groupname`, `authorized`, `activity`, `modo_ventilacion`, `obs_modo`, `hora_registro`, `presion`, `volumen`, `simv`, `psv`, `otros`, `frecuencia_respiratoria`, `p_inspiratorio`, `p_media`, `p_max`, `chst`, `disparo`, `fvt`, `vol_tidal`, `vm_programado`, `petco2`, `vdvt`, `ko2`, `obs_presion`, `obs_volumen`, `obs_simv`, `obs_psv`, `obs_otros`, `obs_frecuencia_respiratoria`, `obs_p_inspiratorio`, `obs_p_media`, `obs_p_max`, `obs_chst`, `obs_disparo`, `obs_fvt`, `obs_vol_tidal`, `obs_vm_programado`, `obs_petco2`, `obs_vdvt`, `obs_ko2`) VALUES
(1, '2025-10-22 02:08:26', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 'VENTILACION MECANICA', NULL, '02:07:00', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'asdasdasd', 'asdasd', '', '', 'asdasdasd', '', 'asdasdasd', '', 'asdasd', '', '', '', '', 'asdasdasd', '', '', ''),
(2, '2025-11-02 18:18:48', 198500, 198500, 'BPZ-admin-45', 'Default', 1, 1, 'ESPONTANEA', 'adasdasd', '18:18:00', 1, 1, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'asdasd', 'asdasdasd', 'asdasdas', '', '', '', '', 'asdasd', '', '', '', '', '', '', '', '', ''),
(3, '2025-11-07 20:51:56', 775369, 775369, 'BPZ-admin-45', 'Default', 1, 1, 'ESPONTANEA', 'asdasdasd', '20:50:00', 1, 0, 1, 1, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 'asdasdas', '', 'asdasdasd', 'asasdasd', '', '', '', '', 'asdasdasd', '', 'asdasdasdas', '', '', '', '', '', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `form_registro_vm`
--
ALTER TABLE `form_registro_vm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid_encounter` (`pid`,`encounter`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `form_registro_vm`
--
ALTER TABLE `form_registro_vm`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
