-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-02-2026 a las 21:38:26
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
-- Estructura de tabla para la tabla `form_encounter`
--

CREATE TABLE `form_encounter` (
  `id` bigint(20) NOT NULL,
  `date` datetime DEFAULT NULL,
  `reason` longtext,
  `departamento` varchar(55) DEFAULT NULL,
  `servicio` varchar(55) DEFAULT NULL,
  `cama` varchar(55) DEFAULT NULL,
  `out_date` date DEFAULT NULL,
  `cuarto` varchar(55) DEFAULT NULL,
  `facility` longtext,
  `facility_id` int(11) NOT NULL DEFAULT '0',
  `pid` bigint(20) DEFAULT NULL,
  `nro_registro` varchar(40) DEFAULT NULL,
  `encounter` bigint(20) DEFAULT NULL,
  `onset_date` datetime DEFAULT NULL,
  `sensitivity` varchar(30) DEFAULT NULL,
  `billing_note` text,
  `pc_catid` int(11) NOT NULL DEFAULT '5' COMMENT 'event category from openemr_postcalendar_categories',
  `last_level_billed` int(11) NOT NULL DEFAULT '0' COMMENT '0=none, 1=ins1, 2=ins2, etc',
  `last_level_closed` int(11) NOT NULL DEFAULT '0' COMMENT '0=none, 1=ins1, 2=ins2, etc',
  `last_stmt_date` date DEFAULT NULL,
  `stmt_count` int(11) NOT NULL DEFAULT '0',
  `provider_id` int(11) DEFAULT '0' COMMENT 'default and main provider for this visit',
  `supervisor_id` int(11) DEFAULT '0' COMMENT 'supervising provider, if any, for this visit',
  `invoice_refno` varchar(31) NOT NULL DEFAULT '',
  `referral_source` varchar(31) NOT NULL DEFAULT '',
  `billing_facility` int(11) NOT NULL DEFAULT '0',
  `external_id` varchar(20) DEFAULT NULL,
  `pos_code` tinyint(4) DEFAULT NULL,
  `carga_ws` varchar(3) DEFAULT NULL,
  `death_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `form_encounter`
--

INSERT INTO `form_encounter` (`id`, `date`, `reason`, `departamento`, `servicio`, `cama`, `out_date`, `cuarto`, `facility`, `facility_id`, `pid`, `nro_registro`, `encounter`, `onset_date`, `sensitivity`, `billing_note`, `pc_catid`, `last_level_billed`, `last_level_closed`, `last_stmt_date`, `stmt_count`, `provider_id`, `supervisor_id`, `invoice_refno`, `referral_source`, `billing_facility`, `external_id`, `pos_code`, `carga_ws`, `death_date`) VALUES
(472, '2021-08-20 07:55:39', NULL, 'UTI ADULTOS CLINICO', 'U.T.I. ADULTOS', '1', NULL, 'UTI A_UCO', NULL, 0, 10860600, '1163597', 10860600, '2021-08-20 07:55:39', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(473, '2021-08-19 17:50:50', NULL, 'UTI ADULTOS CLINICO', 'U.T.I. ADULTOS', '2', NULL, 'UTI A_UCO', NULL, 0, 198500, '1161654', 198500, '2021-08-19 17:50:50', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(474, '2021-08-21 16:46:55', NULL, 'UTI ADULTOS CLINICO', 'U.T.I. ADULTOS', '28', NULL, 'UTI D', NULL, 0, 10891958, '1161619', 10891958, '2021-08-21 16:46:55', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(475, '2021-08-18 11:09:53', NULL, 'UTI ADULTOS CLINICO', 'U.T.I. ADULTOS', '4', NULL, 'UTI A_UCO', NULL, 0, 10268876, '1159675', 10268876, '2021-08-18 11:09:53', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(476, '2021-08-23 09:05:19', NULL, 'UTI ADULTOS CLINICO', 'U.T.I. ADULTOS', '7', NULL, 'UTI A_UCO', NULL, 0, 775369, '1164712', 775369, '2021-08-23 09:05:19', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(477, '2021-08-20 17:08:41', NULL, 'UTI ADULTOS CLINICO', 'U.T.I. ADULTOS', '6', NULL, 'UTI A_UCO', NULL, 0, 10226111, '1164138', 10226111, '2021-08-20 17:08:41', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(478, '2021-08-17 17:57:32', NULL, 'UTI ADULTOS CLINICO', 'U.T.I. ADULTOS', '5', NULL, 'UTI A_UCO', NULL, 0, 10363241, '1159239', 10363241, '2021-08-17 17:57:32', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(479, '2021-08-17 19:15:43', NULL, 'UTI ADULTOS NEUROLOGICO', 'U.T.I. ADULTOS', '25', NULL, 'UTI D', NULL, 0, 10512746, '1163150', 10512746, '2021-08-17 19:15:43', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(480, '2021-08-11 10:24:56', NULL, 'UTI ADULTOS NEUROLOGICO', 'U.T.I. ADULTOS', '27', NULL, 'UTI D', NULL, 0, 10239807, '1162212', 10512746, '2021-08-11 10:24:56', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(481, '2021-08-18 17:21:31', NULL, 'UTI ADULTOS NEUROLOGICO', 'U.T.I. ADULTOS', '1', NULL, 'POLIVALENTE 15', NULL, 0, 10220506, '1163454', 10220506, '2021-08-18 17:21:31', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(482, '2021-08-25 21:32:49', NULL, 'UTI ADULTOS NEUROLOGICO', 'U.T.I. ADULTOS', '22', NULL, 'UTI C', NULL, 0, 642315, '1165000', 642315, '2021-08-25 21:32:49', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(483, '2021-08-18 17:24:22', NULL, 'UTI ADULTOS NEUROLOGICO', 'U.T.I. ADULTOS', '28', NULL, 'UTI D', NULL, 0, 11954819, '1163336', 11954819, '2021-08-18 17:24:22', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(484, '2021-08-07 17:41:11', NULL, 'UTI ADULTOS NEUROLOGICO', 'U.T.I. ADULTOS', '27', NULL, 'UTI D', NULL, 0, 12605066, '1160534', 12605066, '2021-08-07 17:41:11', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(485, '2021-08-22 20:00:32', NULL, 'UTI ADULTOS NEUROLOGICO', 'U.T.I. ADULTOS', '16', NULL, 'UTI B', NULL, 0, 659831, '1164529', 659831, '2021-08-22 20:00:32', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(486, '2021-08-20 10:44:01', NULL, 'UTI ADULTOS POLIVALENTE', 'U.T.I. ADULTOS', '25', NULL, 'UTI D', NULL, 0, 10586091, '1162462', 10586091, '2021-08-20 10:44:01', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(487, '2021-08-21 04:40:53', NULL, 'UTI ADULTOS POLIVALENTE', 'U.T.I. ADULTOS', '26', NULL, 'UTI D', NULL, 0, 12107014, '1162680', 12107014, '2021-08-21 04:40:53', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(488, '2021-08-12 11:14:44', NULL, 'UTI ADULTOS POLIVALENTE', 'U.T.I. ADULTOS', '32', NULL, 'UTI D', NULL, 0, 12388354, '1161792', 12388354, '2021-08-12 11:14:44', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(489, '2021-08-25 22:06:37', NULL, 'UTI ADULTOS POLIVALENTE', 'U.T.I. ADULTOS', '31', NULL, 'UTI D', NULL, 0, 11878610, '1158973', 11878610, '2021-08-25 22:06:37', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(490, '2021-08-12 11:04:17', NULL, 'UTI ADULTOS POLIVALENTE', 'U.T.I. ADULTOS', '15', NULL, 'UTI B', NULL, 0, 10209371, '1162408', 10209371, '2021-08-12 11:04:17', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(491, '2021-08-26 15:51:47', NULL, 'UTI ADULTOS POLIVALENTE', 'U.T.I. ADULTOS', '30', NULL, 'UTI D', NULL, 0, 12605267, '1162873', 12605267, '2021-08-26 15:51:47', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(492, '2021-08-23 20:52:47', NULL, 'UTI ADULTOS POLIVALENTE', 'U.T.I. ADULTOS', '28', NULL, 'UTI D', NULL, 0, 160466, '1164601', 160466, '2021-08-23 20:52:47', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(493, '2021-08-18 16:10:38', NULL, 'UTI ADULTOS POLIVALENTE', 'U.T.I. ADULTOS', '27', NULL, 'UTI D', NULL, 0, 10755578, '1156800', 10755578, '2021-08-18 16:10:38', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(494, '2021-08-23 15:53:43', NULL, 'UTI ADULTOS QUIRURGICO', 'U.T.I. ADULTOS', '10', NULL, 'UTI B', NULL, 0, 10170038, '1164394', 10170038, '2021-08-23 15:53:43', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(495, '2021-08-13 10:15:26', NULL, 'UTI ADULTOS QUIRURGICO', 'U.T.I. ADULTOS', '31', NULL, 'UTI D', NULL, 0, 10838057, '1162411', 10838057, '2021-08-13 10:15:26', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(496, '2021-08-25 15:42:29', NULL, 'UTI ADULTOS QUIRURGICO', 'U.T.I. ADULTOS', '11', NULL, 'UTI B', NULL, 0, 12360842, '1163303', 12360842, '2021-08-25 15:42:29', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(497, '2021-08-10 09:53:17', NULL, 'UTI ADULTOS QUIRURGICO', 'U.T.I. ADULTOS', '14', NULL, 'UTI B', NULL, 0, 11637938, '1161114', 11637938, '2021-08-10 09:53:17', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(498, '2021-08-13 21:04:21', NULL, 'UTI ADULTOS QUIRURGICO', 'U.T.I. ADULTOS', '27', NULL, 'UTI D', NULL, 0, 81753, '1162867', 81753, '2021-08-13 21:04:21', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(499, '2021-08-07 17:04:43', NULL, 'UTI ADULTOS QUIRURGICO', 'U.T.I. ADULTOS', '32', NULL, 'UTI D', NULL, 0, 10609856, '1160683', 10609856, '2021-08-07 17:04:43', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(500, '2021-08-16 19:35:31', NULL, 'UTI ADULTOS QUIRURGICO', 'U.T.I. ADULTOS', '29', NULL, 'UTI D', NULL, 0, 10699821, '1162868', 10699821, '2021-08-16 19:35:31', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(501, '2021-08-26 19:31:35', NULL, 'UTI ADULTOS CLINICO', 'U.T.I. ADULTOS', '1', NULL, 'UTI A_UCO', NULL, 0, 11929126, '1160155', 11929126, '2021-08-26 19:31:35', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(502, '2021-08-27 17:20:14', NULL, 'UTI ADULTOS CLINICO', 'U.T.I. ADULTOS', '5', NULL, 'UTI A_UCO', NULL, 0, 11175410, '1163823', 11175410, '2021-08-27 17:20:14', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(503, '2021-08-27 17:54:14', NULL, 'UTI ADULTOS POLIVALENTE', 'U.T.I. ADULTOS', '29', NULL, 'UTI D', NULL, 0, 10960296, '1161443', 10960296, '2021-08-27 17:54:14', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(504, '2021-08-28 03:52:52', NULL, 'UTI ADULTOS QUIRURGICO', 'U.T.I. ADULTOS', '11', NULL, 'UTI B', NULL, 0, 11640249, '1165808', 11640249, '2021-08-28 03:52:52', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(505, '2021-08-28 15:03:39', NULL, 'UTI ADULTOS QUIRURGICO', 'U.T.I. ADULTOS', '30', NULL, 'UTI D', NULL, 0, 10536712, '1165598', 10536712, '2021-08-28 15:03:39', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(506, '2021-08-29 10:36:38', NULL, 'UTI ADULTOS QUIRURGICO', 'U.T.I. ADULTOS', '13', NULL, 'UTI B', NULL, 0, 826220, '1165528', 826220, '2021-08-29 10:36:38', NULL, NULL, 16, 0, 0, NULL, 0, 0, 0, '', '', 0, NULL, NULL, 'si', NULL),
(507, '2021-09-07 00:00:00', '', '', '', '', NULL, '', 'Your Clinic Name Here', 3, 10860600, NULL, 43, '2021-09-07 00:00:00', 'normal', NULL, 5, 0, 0, NULL, 0, 1, 0, '', '', 3, '', 0, NULL, NULL),
(508, '2022-07-08 00:00:00', '', '', '', '', NULL, '', 'Your Clinic Name Here', 3, 1145537, NULL, 2, '2022-07-08 00:00:00', 'normal', NULL, 5, 0, 0, NULL, 0, 1, 0, '', '', 3, '', 0, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `form_encounter`
--
ALTER TABLE `form_encounter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid_encounter` (`pid`,`encounter`),
  ADD KEY `encounter_date` (`date`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `form_encounter`
--
ALTER TABLE `form_encounter`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=509;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
