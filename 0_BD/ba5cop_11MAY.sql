-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 11, 2026 at 09:06 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ba5cop`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE IF NOT EXISTS `audit_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `action` varchar(10) NOT NULL,
  `entity_id` int NOT NULL,
  `occurred_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_audit_log_user` (`user_id`),
  KEY `fk_audit_log_entity` (`entity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_id`, `occurred_at`) VALUES
(1, 4, 'CREATE', 10000, '2026-05-04 08:53:38'),
(2, 7, 'CREATE', 10001, '2026-05-04 10:18:28'),
(3, 7, 'CREATE', 10002, '2026-05-04 10:18:53'),
(4, 7, 'CREATE', 10003, '2026-05-04 10:19:30'),
(5, 7, 'CREATE', 10004, '2026-05-04 10:19:50'),
(6, 7, 'CREATE', 10005, '2026-05-04 10:20:03'),
(7, 7, 'CREATE', 10006, '2026-05-04 10:20:16'),
(8, 7, 'CREATE', 10007, '2026-05-04 10:20:30'),
(9, 7, 'CREATE', 10008, '2026-05-04 10:20:47'),
(10, 7, 'CREATE', 10009, '2026-05-04 10:21:06'),
(11, 7, 'UPDATE', 10004, '2026-05-04 10:22:07'),
(12, 7, 'DELETE', 10004, '2026-05-04 10:23:09'),
(13, 7, 'CREATE', 10010, '2026-05-04 10:23:44'),
(14, 7, 'UPDATE', 10010, '2026-05-04 10:24:04'),
(15, 7, 'UPDATE', 10007, '2026-05-04 10:24:09'),
(16, 7, 'UPDATE', 10002, '2026-05-04 10:24:19'),
(17, 7, 'DELETE', 10010, '2026-05-04 10:25:04'),
(18, 7, 'CREATE', 10011, '2026-05-04 10:25:30'),
(19, 7, 'UPDATE', 10005, '2026-05-04 10:27:24'),
(20, 4, 'CREATE', 10012, '2026-05-04 14:30:42'),
(21, 4, 'CREATE', 10013, '2026-05-04 14:46:55'),
(22, 4, 'CREATE', 10014, '2026-05-04 15:00:51'),
(23, 4, 'DELETE', 10001, '2026-05-04 15:40:20'),
(24, 4, 'CREATE', 10015, '2026-05-04 15:40:55'),
(25, 4, 'CREATE', 10016, '2026-05-04 15:41:26'),
(26, 4, 'CREATE', 10017, '2026-05-04 15:41:51'),
(27, 4, 'CREATE', 10018, '2026-05-04 15:42:34'),
(28, 4, 'CREATE', 10019, '2026-05-04 15:43:10'),
(29, 4, 'CREATE', 10020, '2026-05-04 15:43:22'),
(30, 4, 'CREATE', 10021, '2026-05-04 15:43:45'),
(31, 4, 'CREATE', 10022, '2026-05-04 15:44:15'),
(32, 4, 'UPDATE', 10022, '2026-05-04 15:44:23'),
(33, 4, 'CREATE', 10023, '2026-05-04 15:44:36'),
(34, 4, 'CREATE', 10024, '2026-05-04 15:44:51'),
(35, 4, 'CREATE', 10025, '2026-05-04 15:45:31'),
(36, 4, 'UPDATE', 10025, '2026-05-04 15:45:38'),
(37, 4, 'CREATE', 50000, '2026-05-04 15:50:43'),
(38, 4, 'CREATE', 10026, '2026-05-04 15:52:06'),
(39, 4, 'CREATE', 20000, '2026-05-04 15:53:09'),
(40, 4, 'CREATE', 20001, '2026-05-04 15:54:45'),
(41, 4, 'CREATE', 20002, '2026-05-04 15:55:59'),
(42, 4, 'CREATE', 10027, '2026-05-04 15:57:36'),
(43, 4, 'CREATE', 10028, '2026-05-04 15:58:37'),
(44, 4, 'UPDATE', 10028, '2026-05-04 15:58:57'),
(45, 4, 'UPDATE', 10026, '2026-05-04 16:00:12'),
(46, 4, 'UPDATE', 10027, '2026-05-04 16:00:17'),
(47, 4, 'UPDATE', 10021, '2026-05-04 16:00:57'),
(48, 4, 'CREATE', 40000, '2026-05-04 16:05:49'),
(49, 4, 'CREATE', 40002, '2026-05-04 16:06:42'),
(50, 4, 'CREATE', 40003, '2026-05-04 16:40:26'),
(51, 4, 'CREATE', 40004, '2026-05-04 16:42:46'),
(52, 4, 'CREATE', 40005, '2026-05-04 16:44:31'),
(53, 4, 'UPDATE', 40005, '2026-05-04 16:44:36'),
(54, 4, 'UPDATE', 40005, '2026-05-04 16:49:23'),
(55, 4, 'CREATE', 10029, '2026-05-04 16:50:07'),
(56, 4, 'CREATE', 30000, '2026-05-04 16:51:34'),
(57, 4, 'CREATE', 30001, '2026-05-04 16:52:31'),
(58, 4, 'UPDATE', 30001, '2026-05-04 16:53:02'),
(59, 4, 'UPDATE', 20000, '2026-05-04 16:53:20'),
(60, 4, 'CREATE', 30002, '2026-05-04 16:53:52'),
(61, 4, 'CREATE', 50001, '2026-05-04 16:56:43'),
(62, 4, 'CREATE', 10030, '2026-05-04 20:05:11'),
(63, 4, 'CREATE', 50002, '2026-05-05 10:17:09'),
(64, 4, 'CREATE', 30003, '2026-05-05 10:32:17'),
(65, 4, 'CREATE', 10031, '2026-05-05 13:46:38'),
(66, 4, 'CREATE', 10032, '2026-05-05 13:47:03'),
(67, 4, 'UPDATE', 10031, '2026-05-05 13:47:19'),
(68, 4, 'UPDATE', 10032, '2026-05-05 13:47:24'),
(69, 4, 'CREATE', 10033, '2026-05-05 13:49:06'),
(70, 4, 'UPDATE', 40000, '2026-05-05 15:04:17'),
(71, 4, 'UPDATE', 40000, '2026-05-05 15:10:00'),
(72, 4, 'UPDATE', 40002, '2026-05-07 09:05:28'),
(73, 4, 'CREATE', 40006, '2026-05-07 10:01:48'),
(74, 4, 'UPDATE', 40006, '2026-05-07 10:01:48'),
(75, 4, 'CREATE', 40007, '2026-05-07 10:02:38'),
(76, 4, 'DELETE', 40007, '2026-05-07 10:02:42'),
(77, 4, 'CREATE', 50003, '2026-05-07 14:38:23'),
(78, 4, 'CREATE', 50004, '2026-05-07 14:38:43'),
(79, 4, 'DELETE', 50003, '2026-05-07 14:38:46'),
(80, 4, 'DELETE', 50004, '2026-05-07 14:38:50'),
(81, 4, 'UPDATE', 50000, '2026-05-07 14:40:31'),
(82, 4, 'UPDATE', 50000, '2026-05-07 14:41:47'),
(83, 4, 'CREATE', 20003, '2026-05-07 15:13:04'),
(84, 4, 'DELETE', 20003, '2026-05-07 15:13:26'),
(85, 4, 'CREATE', 20004, '2026-05-07 15:13:37'),
(86, 4, 'DELETE', 20004, '2026-05-07 15:13:41');

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', '4', 1777885875),
('comandante', '6', 1777885905),
('comandante', '8', 1777909809),
('Operador', '7', 1777885917);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` smallint NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 1, 'Administrador', NULL, NULL, 1777885874, 1777885874),
('audit.view', 2, 'Ver auditoria', NULL, NULL, 1777885874, 1777885874),
('comandante', 1, 'Comandante da Base Aérea N.º5', NULL, NULL, 1777885874, 1777885874),
('cop.view', 2, 'Ver dashboard COP', NULL, NULL, 1777885874, 1777885874),
('decisionLog.manage', 2, 'Fazer e eliminar decisões', NULL, NULL, 1777885874, 1777885874),
('gestorPedidos', 1, 'Gestor de Pedidos', NULL, NULL, 1777885874, 1777885874),
('incident.manage', 2, 'Criar/editar/atribuir incidentes', NULL, NULL, 1777885874, 1777885874),
('lodging.manage', 2, 'Gestão de alojamentos e check-ins', NULL, NULL, 1777885874, 1777885874),
('login.backend', 2, 'Acesso ao Backend', NULL, NULL, 1777885874, 1777885874),
('login.frontend', 2, 'Acesso ao Frontend', NULL, NULL, 1777885874, 1777885874),
('map.manage', 2, 'Gerir mapa (camadas, editar, exportar, etc.)', NULL, NULL, 1777885874, 1777885874),
('map.view', 2, 'Ver mapa', NULL, NULL, 1777885874, 1777885874),
('Operador', 1, 'Operador', NULL, NULL, 1777885874, 1777885874),
('request.manage', 2, 'Criar/editar/atribuir/fechar pedidos', NULL, NULL, 1777885874, 1777885874),
('sensibleEntity.manage', 2, 'CRUD de entidades sensíveis ao sistema', NULL, NULL, 1777885874, 1777885874),
('user.manage', 2, 'CRUD de utilizadores', NULL, NULL, 1777885874, 1777885874);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('admin', 'audit.view'),
('comandante', 'audit.view'),
('admin', 'cop.view'),
('comandante', 'cop.view'),
('Operador', 'cop.view'),
('admin', 'decisionLog.manage'),
('comandante', 'decisionLog.manage'),
('Operador', 'decisionLog.manage'),
('admin', 'incident.manage'),
('comandante', 'incident.manage'),
('Operador', 'incident.manage'),
('admin', 'lodging.manage'),
('Operador', 'lodging.manage'),
('admin', 'login.backend'),
('Operador', 'login.backend'),
('admin', 'login.frontend'),
('comandante', 'login.frontend'),
('Operador', 'login.frontend'),
('admin', 'map.manage'),
('Operador', 'map.manage'),
('admin', 'map.view'),
('comandante', 'map.view'),
('Operador', 'map.view'),
('admin', 'request.manage'),
('comandante', 'request.manage'),
('Operador', 'request.manage'),
('admin', 'sensibleEntity.manage'),
('admin', 'user.manage');

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) COLLATE utf8mb3_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

DROP TABLE IF EXISTS `branch`;
CREATE TABLE IF NOT EXISTS `branch` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`id`, `description`) VALUES
(1, 'FA'),
(2, 'EX'),
(3, 'MAR'),
(4, 'GNR'),
(5, 'ANPC'),
(6, 'OUTRO');

-- --------------------------------------------------------

--
-- Table structure for table `decision_log`
--

DROP TABLE IF EXISTS `decision_log`;
CREATE TABLE IF NOT EXISTS `decision_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reason` varchar(50) DEFAULT NULL,
  `impact` varchar(50) DEFAULT NULL,
  `decided_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `decided_by` int NOT NULL,
  `entity_id` int NOT NULL,
  `status_type_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_decision_log_user` (`decided_by`),
  KEY `fk_decision_log_entity` (`entity_id`),
  KEY `idx-decision_log-status_type_id` (`status_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `decision_log`
--

INSERT INTO `decision_log` (`id`, `reason`, `impact`, `decided_at`, `decided_by`, `entity_id`, `status_type_id`) VALUES
(1, 'Pessoal civil não pode pernoitar na base', 'Não há camas a serem dadas para civis', '2026-04-27 23:00:00', 8, 50000, 17),
(2, 'Não há refeições take-away', 'Todas as refeições têm de ser consumidas na MESSE', '2026-04-22 23:00:00', 8, 50001, 17),
(3, 'Sem saídas a partir das 1h30', 'Militares só podem voltar a sair a partir das 6h00', '2026-05-05 10:17:09', 8, 50002, 17);

-- --------------------------------------------------------

--
-- Table structure for table `entity`
--

DROP TABLE IF EXISTS `entity`;
CREATE TABLE IF NOT EXISTS `entity` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entity_type_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_entity_entity_type` (`entity_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50005 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `entity`
--

INSERT INTO `entity` (`id`, `entity_type_id`) VALUES
(10000, 1),
(10001, 1),
(10002, 1),
(10003, 1),
(10004, 1),
(10005, 1),
(10006, 1),
(10007, 1),
(10008, 1),
(10009, 1),
(10010, 1),
(10011, 1),
(10012, 1),
(10013, 1),
(10014, 1),
(10015, 1),
(10016, 1),
(10017, 1),
(10018, 1),
(10019, 1),
(10020, 1),
(10021, 1),
(10022, 1),
(10023, 1),
(10024, 1),
(10025, 1),
(10026, 1),
(10027, 1),
(10028, 1),
(10029, 1),
(10030, 1),
(10031, 1),
(10032, 1),
(10033, 1),
(20000, 2),
(20001, 2),
(20002, 2),
(20003, 2),
(20004, 2),
(30000, 3),
(30001, 3),
(30002, 3),
(30003, 3),
(40000, 4),
(40001, 4),
(40002, 4),
(40003, 4),
(40004, 4),
(40005, 4),
(40006, 4),
(40007, 4),
(50000, 5),
(50001, 5),
(50002, 5),
(50003, 5),
(50004, 5);

-- --------------------------------------------------------

--
-- Table structure for table `entity_type`
--

DROP TABLE IF EXISTS `entity_type`;
CREATE TABLE IF NOT EXISTS `entity_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `entity_type`
--

INSERT INTO `entity_type` (`id`, `name`) VALUES
(5, 'DECISION'),
(2, 'INCIDENT'),
(1, 'LOCATION'),
(4, 'REQUEST'),
(3, 'TASK');

-- --------------------------------------------------------

--
-- Table structure for table `entity_update`
--

DROP TABLE IF EXISTS `entity_update`;
CREATE TABLE IF NOT EXISTS `entity_update` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entity_id` int NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note` varchar(120) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_entity_update_entity` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incident`
--

DROP TABLE IF EXISTS `incident`;
CREATE TABLE IF NOT EXISTS `incident` (
  `id` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `title` varchar(25) NOT NULL,
  `description` varchar(120) NOT NULL,
  `incident_type_id` int NOT NULL,
  `priority_id` int NOT NULL,
  `status_type_id` int NOT NULL,
  `mitigate_by` datetime DEFAULT NULL,
  `reported_by` int NOT NULL,
  `entity_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_incident_location` (`location_id`),
  KEY `fk_incident_incident_type` (`incident_type_id`),
  KEY `fk_incident_priority` (`priority_id`),
  KEY `fk_incident_user` (`reported_by`),
  KEY `fk_incident_status_type` (`status_type_id`),
  KEY `fk_incident_entity` (`entity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `incident`
--

INSERT INTO `incident` (`id`, `location_id`, `title`, `description`, `incident_type_id`, `priority_id`, `status_type_id`, `mitigate_by`, `reported_by`, `entity_id`) VALUES
(1, 27, 'Postos de Luz sem Energia', 'Todos os postos de energias que estão no estacionamento não têm energia', 2, 1, 4, NULL, 7, 20000),
(2, 22, 'Cancela toda aberta', 'Cancela SUL10 está completamente destruída, a travessia é muito fácil por lá.', 3, 1, 4, NULL, 6, 20001),
(3, 8, 'Cancela desapareceu', 'Com o vento, esta secção da cancela levantou voo', 3, 1, 4, '2026-05-04 23:59:59', 8, 20002);

-- --------------------------------------------------------

--
-- Table structure for table `incident_type`
--

DROP TABLE IF EXISTS `incident_type`;
CREATE TABLE IF NOT EXISTS `incident_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `incident_type`
--

INSERT INTO `incident_type` (`id`, `description`) VALUES
(1, 'ÀGUA'),
(2, 'ELÉTRICO'),
(3, 'SEGURANÇA'),
(4, 'SANITÁRIO');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
CREATE TABLE IF NOT EXISTS `location` (
  `id` int NOT NULL AUTO_INCREMENT,
  `location_type_id` int NOT NULL,
  `name` varchar(25) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `geometry` longtext NOT NULL,
  `status_type_id` int NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `entity_id` int NOT NULL,
  `is_critical` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_location_location_type` (`location_type_id`),
  KEY `fk_location_status` (`status_type_id`),
  KEY `fk_location_entity` (`entity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `location_type_id`, `name`, `notes`, `geometry`, `status_type_id`, `updated_at`, `entity_id`, `is_critical`) VALUES
(1, 7, 'TACAN', 'Não está calibrado', '{\"type\":\"Point\",\"coordinates\":[609,494.799988]}', 2, '2026-05-04 08:53:38', 10000, 1),
(3, 5, 'Sul1', NULL, '{\"type\":\"LineString\",\"coordinates\":[[987,72.100006],[923,89.100006]]}', 2, '2026-05-04 10:18:53', 10002, 0),
(4, 4, 'Sul2', NULL, '{\"type\":\"LineString\",\"coordinates\":[[923,89.100006],[894,147.600006]]}', 1, '2026-05-04 10:19:30', 10003, 0),
(6, 5, 'Sul3', NULL, '{\"type\":\"LineString\",\"coordinates\":[[878.5,165.600006],[819,184.600006]]}', 1, '2026-05-04 10:20:03', 10005, 0),
(7, 5, 'Sul4', NULL, '{\"type\":\"LineString\",\"coordinates\":[[819,184.600006],[680,189.100006]]}', 1, '2026-05-04 10:20:16', 10006, 0),
(8, 5, 'Sul5', NULL, '{\"type\":\"LineString\",\"coordinates\":[[680,189.100006],[613.5,158.600006]]}', 3, '2026-05-04 10:20:30', 10007, 0),
(9, 5, 'Sul6', NULL, '{\"type\":\"LineString\",\"coordinates\":[[613.5,158.600006],[592,80.600006]]}', 1, '2026-05-04 10:20:47', 10008, 0),
(10, 5, 'Sul7', NULL, '{\"type\":\"LineString\",\"coordinates\":[[592,80.600006],[551,44.600006]]}', 1, '2026-05-04 10:21:06', 10009, 0),
(12, 5, 'Torre2', NULL, '{\"type\":\"Polygon\",\"coordinates\":[[[889.5,170.699997],[895.5,168.949997],[898.5,165.199997],[899.5,160.449997],[898.25,154.699997],[894,147.600006],[887,150.949997],[882,152.949997],[878.25,159.449997],[878.5,165.600006],[883.5,168.949997],[889.5,170.699997]]]}', 3, '2026-05-04 10:25:30', 10011, 0),
(13, 8, 'Posto de Transformação #1', NULL, '{\"type\":\"Point\",\"coordinates\":[886,312.799988]}', 1, '2026-05-04 14:30:42', 10012, 0),
(14, 8, 'PT #2', NULL, '{\"type\":\"Point\",\"coordinates\":[507,180.799988]}', 2, '2026-05-04 14:46:55', 10013, 0),
(15, 8, 'PT #3', NULL, '{\"type\":\"Point\",\"coordinates\":[241,434.899994]}', 3, '2026-05-04 15:00:51', 10014, 0),
(16, 5, 'Torre #1', NULL, '{\"type\":\"Polygon\",\"coordinates\":[[[1000.75,85.949997],[1007.5,85.449997],[1013.5,81.199997],[1017,75.449997],[1018.5,68.449997],[1017.5,60.949997],[1011.5,55.699997],[1004.5,52.199997],[996.75,53.199997],[988.75,57.949997],[985.5,66.699997],[987,77.449997],[992.75,83.449997],[1000.75,85.949997]]]}', 1, '2026-05-04 15:40:55', 10015, 0),
(17, 5, 'Torre #3', NULL, '{\"type\":\"Polygon\",\"coordinates\":[[[537,69.199997],[543.25,68.949997],[548.75,66.449997],[550.75,59.449997],[549.875,52.724998],[546.375,49.224998],[541.125,47.849998],[535.375,49.974998],[531.5,52.724998],[530.375,56.599998],[530.125,61.099998],[531.375,65.474998],[534.5,68.099998],[537,69.199997]]]}', 1, '2026-05-04 15:41:26', 10016, 0),
(18, 5, 'Torre #4', NULL, '{\"type\":\"Polygon\",\"coordinates\":[[[511.625,69.474998],[516.375,69.224998],[521.625,72.224998],[524,76.474998],[524,82.099998],[523.125,85.974998],[520.375,88.474998],[515.75,89.974998],[511.75,90.099998],[507.625,88.974998],[504.875,86.224998],[503.75,82.349998],[504.125,77.724998],[505.5,74.474998],[508,70.849998],[511.625,69.474998]]]}', 1, '2026-05-04 15:41:51', 10017, 0),
(19, 2, 'Torre #5', NULL, '{\"type\":\"Polygon\",\"coordinates\":[[[490.75,80.099998],[495.125,76.599998],[497.375,71.474998],[496.875,64.724998],[494.375,59.599998],[488.5,56.849998],[482.125,57.099998],[476.75,59.724998],[474.375,64.974998],[473.25,70.599998],[474.5,75.099998],[478.75,79.224998],[483.625,81.099998],[490.75,80.099998]]]}', 1, '2026-05-04 15:42:34', 10018, 0),
(20, 5, 'Sul8', NULL, '{\"type\":\"LineString\",\"coordinates\":[[551,44.600006],[546.375,49.224998]]}', 1, '2026-05-04 15:43:10', 10019, 0),
(21, 4, 'Sul9', NULL, '{\"type\":\"LineString\",\"coordinates\":[[531.375,65.474998],[521.625,72.224998]]}', 1, '2026-05-04 15:43:22', 10020, 0),
(22, 5, 'Sul10', NULL, '{\"type\":\"LineString\",\"coordinates\":[[504.125,77.724998],[495.125,76.599998],[500,76.974998]]}', 3, '2026-05-04 15:43:45', 10021, 0),
(23, 5, 'Sul11', NULL, '{\"type\":\"LineString\",\"coordinates\":[[473.25,70.599998],[424,72.199997]]}', 1, '2026-05-04 15:44:15', 10022, 0),
(24, 5, 'Sul12', NULL, '{\"type\":\"LineString\",\"coordinates\":[[424,72.199997],[368.25,74.699997],[381.5,74.699997],[382.5,73.449997]]}', 1, '2026-05-04 15:44:36', 10023, 0),
(25, 5, 'Sul13', NULL, '{\"type\":\"LineString\",\"coordinates\":[[368.25,74.699997],[316.5,77.449997]]}', 1, '2026-05-04 15:44:51', 10024, 0),
(26, 5, 'Torre #6', NULL, '{\"type\":\"Polygon\",\"coordinates\":[[[316.5,77.449997],[313.875,85.099998],[308.375,90.349998],[301.375,91.724998],[295.875,90.474998],[291.75,86.599998],[289.625,81.349998],[291,74.474998],[294.875,69.099998],[301.25,67.099998],[308.875,68.974998],[312.375,72.599998],[316.5,77.449997]]]}', 1, '2026-05-04 15:45:31', 10025, 0),
(27, 6, 'Estacionamento Norte 1', NULL, '{\"type\":\"Polygon\",\"coordinates\":[[[379,344.399994],[413,358.899994],[399.5,391.399994],[359.5,375.399994],[379,344.399994]]]}', 2, '2026-05-04 15:52:06', 10026, 1),
(28, 4, 'C de César', NULL, '{\"type\":\"LineString\",\"coordinates\":[[634.5,255.899994],[654.5,264.399994],[674,278.899994],[690.5,292.399994],[704.5,313.899994],[710,333.399994],[706.5,349.399994],[700,360.899994],[687.5,367.899994],[677,372.399994],[666.5,374.399994],[655,372.399994]]}', 3, '2026-05-04 15:57:36', 10027, 1),
(29, 4, 'Rua Desvi\'Omar', NULL, '{\"type\":\"LineString\",\"coordinates\":[[884.5,484.899994],[864,470.399994],[845,464.399994],[819.5,457.399994],[796,452.899994],[766,449.399994],[747.5,447.399994],[724.5,445.899994],[704,445.899994],[680.5,451.399994],[654.5,460.899994],[637.5,466.899994],[611,478.399994],[591.5,487.399994],[569.5,497.899994],[553.5,503.899994],[533.5,507.399994],[514,505.899994],[504.5,502.899994]]}', 1, '2026-05-04 15:58:37', 10028, 1),
(30, 7, 'ILS 12/AK', NULL, '{\"type\":\"Point\",\"coordinates\":[345.5,548.351977]}', 1, '2026-05-04 16:50:07', 10029, 0),
(31, 7, 'TACAN 12', NULL, '{\"type\":\"Point\",\"coordinates\":[945,445.645812]}', 1, '2026-05-04 20:05:11', 10030, 1),
(32, 4, 'Avenida Jardim I', NULL, '{\"type\":\"LineString\",\"coordinates\":[[563,164.399994],[551.5,202.899994],[535.5,251.899994],[519.5,303.399994],[509.5,337.399994],[494.5,383.899994]]}', 1, '2026-05-05 13:46:38', 10031, 1),
(33, 4, 'Avenida Jardim II', NULL, '{\"type\":\"LineString\",\"coordinates\":[[475.5,374.399994],[493.5,323.899994],[512.5,266.899994],[530.5,213.899994],[556,137.899994]]}', 1, '2026-05-05 13:47:03', 10032, 1),
(34, 4, 'Estrada Vendetta', NULL, '{\"type\":\"LineString\",\"coordinates\":[[645,249.399994],[692.5,252.399994],[759.5,262.899994],[804,277.399994],[831,298.399994],[849.5,309.399994],[869.5,317.899994],[900.5,328.399994],[944.5,340.899994]]}', 1, '2026-05-05 13:49:06', 10033, 1);

-- --------------------------------------------------------

--
-- Table structure for table `location_type`
--

DROP TABLE IF EXISTS `location_type`;
CREATE TABLE IF NOT EXISTS `location_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `location_type`
--

INSERT INTO `location_type` (`id`, `description`) VALUES
(1, 'BUILDING'),
(2, 'AREA'),
(3, 'POINT'),
(4, 'ROAD'),
(5, 'VEDACAO'),
(6, 'PARKING'),
(7, 'NAVAids'),
(8, 'PT');

-- --------------------------------------------------------

--
-- Table structure for table `lodging_entry`
--

DROP TABLE IF EXISTS `lodging_entry`;
CREATE TABLE IF NOT EXISTS `lodging_entry` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lodging_site_id` int NOT NULL,
  `people_count` int NOT NULL,
  `checkin_at` date NOT NULL,
  `checkout_at` datetime DEFAULT NULL,
  `notes` varchar(30) DEFAULT NULL,
  `unit_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lodging_entry_lodging_site` (`lodging_site_id`),
  KEY `idx-lodging_entry-unit_id` (`unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lodging_entry`
--

INSERT INTO `lodging_entry` (`id`, `lodging_site_id`, `people_count`, `checkin_at`, `checkout_at`, `notes`, `unit_id`) VALUES
(1, 1, 30, '2026-05-03', NULL, 'Praças voluntários do CA', 1),
(2, 1, 15, '2026-05-04', NULL, 'Guardas voluntários nas telhas', 4),
(3, 3, 8, '2026-05-03', '2026-05-04 17:04:56', '8 juristas na logística', 3),
(4, 1, 100, '2026-05-07', NULL, NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `lodging_site`
--

DROP TABLE IF EXISTS `lodging_site`;
CREATE TABLE IF NOT EXISTS `lodging_site` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `capacity_total` int NOT NULL,
  `capacity_available` int NOT NULL,
  `notes` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `geometry` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lodging_site`
--

INSERT INTO `lodging_site` (`id`, `name`, `capacity_total`, `capacity_available`, `notes`, `geometry`) VALUES
(1, 'Alojamento Praças 1', 300, 155, NULL, '{\"type\":\"Point\",\"coordinates\":[306,357.899994]}'),
(2, 'Praças 2', 200, 200, NULL, '{\"type\":\"Point\",\"coordinates\":[332.5,344.399994]}'),
(3, 'Oficiais', 110, 110, NULL, '{\"type\":\"Point\",\"coordinates\":[352,335.899994]}'),
(4, 'Sargentos 1', 100, 100, NULL, '{\"type\":\"Point\",\"coordinates\":[918,244.799988]}');

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1772200554),
('m130524_201442_init', 1772200557),
('m190124_110200_add_verification_token_column_to_user_table', 1772200557),
('m140506_102106_rbac_init', 1772206286),
('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1772206287),
('m180523_151638_rbac_updates_indexes_without_prefix', 1772206287),
('m200409_110543_rbac_update_mssql_trigger', 1772206287),
('m260306_130949_add_notes_to_location', 1772802650),
('m260310_143311_turn_checkoutat_null_to_lodging_entry_table', 1773155211),
('m260318_141226_create_unit_and_replace_branch_in_lodging_entry', 1773850486),
('m260323_135626_add_is_critical_to_location', 1774274252),
('m260402_155004_create_request_type_table', 1775145573),
('m260402_155058_add_request_type_and_quantity_to_request', 1775145573),
('m260408_085326_update_task_decision_log_and_incident_tables', 1775638457),
('m260408_095312_add_fk_task_assigned_to_user', 1775642019),
('m260408_135617_add_status_type_to_decision_log', 1775656948),
('m260417_145216_finish_lodging_site_geometry_change', 1776437624);

-- --------------------------------------------------------

--
-- Table structure for table `priority`
--

DROP TABLE IF EXISTS `priority`;
CREATE TABLE IF NOT EXISTS `priority` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `priority`
--

INSERT INTO `priority` (`id`, `description`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

DROP TABLE IF EXISTS `request`;
CREATE TABLE IF NOT EXISTS `request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `is_external` tinyint NOT NULL,
  `origin` varchar(30) NOT NULL,
  `details` varchar(120) NOT NULL,
  `priority_id` int NOT NULL,
  `status_type_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `entity_id` int NOT NULL,
  `request_type_id` int NOT NULL DEFAULT '1',
  `quantity` int DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_request_priority` (`priority_id`),
  KEY `fk_request_entity` (`entity_id`),
  KEY `idx-request-request_type_id` (`request_type_id`),
  KEY `idx-request-status_type_id` (`status_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`id`, `is_external`, `origin`, `details`, `priority_id`, `status_type_id`, `created_at`, `entity_id`, `request_type_id`, `quantity`) VALUES
(1, 1, 'Banho Escola Primária', 'Banhos quentes alunos EB1 Arrabalde', 3, 12, '2026-05-04 16:05:49', 40000, 2, 20),
(2, 1, 'Refeições Família de 4', 'Refeições Família de 4', 1, 13, '2026-05-04 16:06:42', 40002, 1, 4),
(3, 1, 'Refeições Família de 4', 'Refeições Família de 4', 2, 12, '2026-05-04 16:40:26', 40003, 1, 4),
(4, 0, 'Banhos Quentes família de 9', 'Banhos Quentes família de 9', 1, 12, '2026-05-04 16:42:46', 40004, 2, 1),
(5, 1, 'Refeição de 2', 'Refeição de 2', 1, 12, '2026-05-04 16:44:31', 40005, 1, 6),
(6, 0, 'Cama para família sem casa', 'Família de 7 pessoas ficaram sem casa durante a Kristin, fizeram um pedido para serem alojados.', 1, 14, '2026-05-07 10:01:48', 40006, 3, 7);

-- --------------------------------------------------------

--
-- Table structure for table `request_type`
--

DROP TABLE IF EXISTS `request_type`;
CREATE TABLE IF NOT EXISTS `request_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `request_type`
--

INSERT INTO `request_type` (`id`, `description`) VALUES
(2, 'BATH'),
(3, 'BED'),
(6, 'LOGISTIC_SUPPORT'),
(4, 'MACHINE_HOURS'),
(1, 'MEAL'),
(5, 'TEAM_HOURS');

-- --------------------------------------------------------

--
-- Table structure for table `status_type`
--

DROP TABLE IF EXISTS `status_type`;
CREATE TABLE IF NOT EXISTS `status_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entity_type_id` int NOT NULL,
  `description` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_status_type_entity_type` (`entity_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `status_type`
--

INSERT INTO `status_type` (`id`, `entity_type_id`, `description`) VALUES
(1, 1, 'GREEN'),
(2, 1, 'YELLOW'),
(3, 1, 'RED'),
(4, 2, 'ABERTO'),
(5, 2, 'EM PROGRESSO'),
(6, 2, 'RESOLVIDO'),
(7, 3, 'NOVO'),
(8, 3, 'A FAZER'),
(10, 3, 'FEITO'),
(11, 4, 'NOVO'),
(12, 4, 'APROVADO'),
(13, 4, 'REJEITADO'),
(14, 4, 'EM ANÁLISE'),
(17, 5, 'A SER SEGUIDO'),
(18, 5, 'CANCELADO');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

DROP TABLE IF EXISTS `task`;
CREATE TABLE IF NOT EXISTS `task` (
  `id` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `incident_id` int NOT NULL,
  `title` varchar(20) NOT NULL,
  `description` varchar(120) NOT NULL,
  `priority_id` int NOT NULL,
  `status_type_id` int NOT NULL,
  `block_reason` varchar(120) DEFAULT NULL,
  `assigned_to` int NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `due_at` date NOT NULL,
  `entity_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_task_user` (`created_by`),
  KEY `fk_task_incident` (`incident_id`),
  KEY `fk_task_location` (`location_id`),
  KEY `fk_task_priority` (`priority_id`),
  KEY `fk_task_status_type` (`status_type_id`),
  KEY `fk_task_entity` (`entity_id`),
  KEY `idx-task-assigned_to` (`assigned_to`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `location_id`, `incident_id`, `title`, `description`, `priority_id`, `status_type_id`, `block_reason`, `assigned_to`, `created_by`, `created_at`, `due_at`, `entity_id`) VALUES
(1, 8, 3, 'Procurar restos', 'Procurar pelos restos da cancela', 3, 7, NULL, 7, 4, '2026-05-04 16:51:34', '2026-05-15', 30000),
(2, 8, 3, 'Adquirir cancela', 'Comprar nova cancela temporária para mitigar risco de pessoas entrarem', 1, 8, NULL, 8, 4, '2026-05-04 16:52:31', '2026-05-04', 30001),
(3, 27, 1, 'Diagnosticar problem', 'Diagnosticar o porquê da energia não chegar ao estacionamento', 1, 7, NULL, 7, 4, '2026-05-04 16:53:52', '2026-05-04', 30002),
(4, 22, 2, 'Mitigar Aberturas', 'Tentar fechar ao máximo todos os buracos', 3, 7, NULL, 6, 4, '2026-05-05 10:32:17', '2026-05-05', 30003);

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

DROP TABLE IF EXISTS `unit`;
CREATE TABLE IF NOT EXISTS `unit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx-unit-branch_id` (`branch_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`id`, `name`, `branch_id`, `created_at`) VALUES
(1, 'Unidade Genérica - FA', 1, '2026-03-18 16:14:46'),
(2, 'Unidade Genérica - EX', 2, '2026-03-18 16:14:46'),
(3, 'Unidade Genérica - MAR', 3, '2026-03-18 16:14:46'),
(4, 'Unidade Genérica - GNR', 4, '2026-03-18 16:14:46'),
(5, 'Unidade Genérica - ANPC', 5, '2026-03-18 16:14:46'),
(6, 'Unidade Genérica - OUTRO', 6, '2026-03-18 16:14:46');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `status` smallint NOT NULL DEFAULT '10',
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  `verification_token` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `verification_token`) VALUES
(4, 'administrator', 'Hd0LOyLlsbE8dIHEiOTywQ8UzXC_J50O', '$2y$13$qrs4qw3cQj4QUGDCsSQS1u.7O38rIwTtCpRtvX2ePqxyPLfU6cXBe', NULL, 'administrator@emfa.gov.pt', 10, 1772630999, 1772630999, 'a6GOZ__IIrniu6CNtiCjMVxLREwR3Fsc_1772630999'),
(6, 'frontendUser', '37VS-C0nxcjOnR3dtFTAKxfPWJjthdol', '$2y$13$.HAg.QLfxA3tp/gnTVEr/O2Tj4SXBIlb9N0txjXYIU87e0C6P/8y2', NULL, 'frontendUser@gmail.com', 10, 1777297853, 1777885905, 'fa8JBnvdUW_7F2G06GyOCanJvsptAOkE_1777297853'),
(7, 'operadorBA5', '2Xv6XZPcpK1C8NhMH8z81zmHVo1jbDDs', '$2y$13$1aJK5Ru6ag1Hs6c1VudBDuNX53nzWKEACDvFaxFlDXxpF4sBzrKM2', NULL, 'operador@emfa.gov.pt', 10, 1777885029, 1777885917, NULL),
(8, 'comandanteBA5', '-a5G4h0nsPxSulAmfco6ubUlzB5Eq---', '$2y$13$4z6CU/wC9natZ8/VqmVeuexdBUANts21JT0wROABdKVEAV6ot0Af.', NULL, 'comandanteBA5@emfa.gov.pt', 10, 1777909809, 1777909809, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `fk_audit_log_entity` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_audit_log_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `decision_log`
--
ALTER TABLE `decision_log`
  ADD CONSTRAINT `fk-decision_log-status_type_id` FOREIGN KEY (`status_type_id`) REFERENCES `status_type` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_decision_log_entity` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_decision_log_user` FOREIGN KEY (`decided_by`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `entity`
--
ALTER TABLE `entity`
  ADD CONSTRAINT `fk_entity_entity_type` FOREIGN KEY (`entity_type_id`) REFERENCES `entity_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `entity_update`
--
ALTER TABLE `entity_update`
  ADD CONSTRAINT `fk_entity_update_entity` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `incident`
--
ALTER TABLE `incident`
  ADD CONSTRAINT `fk_incident_entity` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_incident_incident_type` FOREIGN KEY (`incident_type_id`) REFERENCES `incident_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_incident_location` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_incident_priority` FOREIGN KEY (`priority_id`) REFERENCES `priority` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_incident_status_type` FOREIGN KEY (`status_type_id`) REFERENCES `status_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_incident_user` FOREIGN KEY (`reported_by`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `fk_location_entity` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_location_location_type` FOREIGN KEY (`location_type_id`) REFERENCES `location_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_location_status` FOREIGN KEY (`status_type_id`) REFERENCES `status_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `lodging_entry`
--
ALTER TABLE `lodging_entry`
  ADD CONSTRAINT `fk-lodging_entry-unit_id` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_lodging_entry_lodging_site` FOREIGN KEY (`lodging_site_id`) REFERENCES `lodging_site` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `fk-request-request_type_id` FOREIGN KEY (`request_type_id`) REFERENCES `request_type` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-request-status_type_id` FOREIGN KEY (`status_type_id`) REFERENCES `status_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_request_entity` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_request_priority` FOREIGN KEY (`priority_id`) REFERENCES `priority` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `status_type`
--
ALTER TABLE `status_type`
  ADD CONSTRAINT `fk_status_type_entity_type` FOREIGN KEY (`entity_type_id`) REFERENCES `entity_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `fk-task-assigned_to-user-id` FOREIGN KEY (`assigned_to`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_task_entity` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_task_incident` FOREIGN KEY (`incident_id`) REFERENCES `incident` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_task_location` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_task_priority` FOREIGN KEY (`priority_id`) REFERENCES `priority` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_task_status_type` FOREIGN KEY (`status_type_id`) REFERENCES `status_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_task_user` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `unit`
--
ALTER TABLE `unit`
  ADD CONSTRAINT `fk-unit-branch_id` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
