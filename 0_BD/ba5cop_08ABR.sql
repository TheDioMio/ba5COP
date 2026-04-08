-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 08, 2026 at 09:50 AM
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`id`, `user_id`, `action`, `entity_id`, `occurred_at`) VALUES
(1, 2, 'CREATE', 2001, '2026-03-05 16:10:14');

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
('admin', '4', 1772631030),
('comandante', '3', 1772725627),
('gestorPedidos', '2', 1773247851),
('Operador', '1', 1773247841);

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
('admin', 1, 'Administrador', NULL, NULL, 1772631030, 1772631030),
('audit.view', 2, 'Ver auditoria', NULL, NULL, 1772631030, 1772631030),
('comandante', 1, 'Comandante da Base Aérea N.º5', NULL, NULL, 1772631030, 1772631030),
('cop.view', 2, 'Ver dashboard COP', NULL, NULL, 1772631030, 1772631030),
('decision.manage', 2, 'Gerir decisões (criar/editar/publicar)', NULL, NULL, 1772631030, 1772631030),
('gestorPedidos', 1, 'Gestor de Pedidos', NULL, NULL, 1772631030, 1772631030),
('incident.close', 2, 'Fechar/reabrir incidentes', NULL, NULL, 1772631030, 1772631030),
('incident.manage', 2, 'Criar/editar/atribuir incidentes', NULL, NULL, 1772631030, 1772631030),
('kpi.manage', 2, 'Gerir KPIs (ver/editar/publicar)', NULL, NULL, 1772631030, 1772631030),
('login.backend', 2, 'Acesso ao Backend', NULL, NULL, 1772631030, 1772631030),
('login.frontend', 2, 'Acesso ao Frontend', NULL, NULL, 1772631030, 1772631030),
('map.manage', 2, 'Gerir mapa (camadas, editar, exportar, etc.)', NULL, NULL, 1772631030, 1772631030),
('map.view', 2, 'Ver mapa', NULL, NULL, 1772631030, 1772631030),
('Operador', 1, 'Operador', NULL, NULL, 1772631030, 1772631030),
('request.decide', 2, 'Aprovar/recusar pedidos', NULL, NULL, 1772631030, 1772631030),
('request.manage', 2, 'Criar/editar/atribuir/fechar pedidos', NULL, NULL, 1772631030, 1772631030),
('task.complete', 2, 'Concluir/reabrir tasks', NULL, NULL, 1772631030, 1772631030),
('task.manage', 2, 'Criar/editar/atribuir tasks', NULL, NULL, 1772631030, 1772631030),
('user.manage', 2, 'CRUD de utilizadores', NULL, NULL, 1772631030, 1772631030);

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
('gestorPedidos', 'cop.view'),
('Operador', 'cop.view'),
('admin', 'incident.close'),
('comandante', 'incident.close'),
('admin', 'incident.manage'),
('comandante', 'incident.manage'),
('Operador', 'incident.manage'),
('admin', 'login.backend'),
('admin', 'login.frontend'),
('comandante', 'login.frontend'),
('gestorPedidos', 'login.frontend'),
('Operador', 'login.frontend'),
('admin', 'map.manage'),
('admin', 'map.view'),
('comandante', 'map.view'),
('gestorPedidos', 'map.view'),
('Operador', 'map.view'),
('admin', 'request.decide'),
('comandante', 'request.decide'),
('admin', 'request.manage'),
('comandante', 'request.manage'),
('gestorPedidos', 'request.manage'),
('admin', 'task.complete'),
('comandante', 'task.complete'),
('Operador', 'task.complete'),
('admin', 'task.manage'),
('comandante', 'task.manage'),
('Operador', 'task.manage'),
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  PRIMARY KEY (`id`),
  KEY `fk_decision_log_user` (`decided_by`),
  KEY `fk_decision_log_entity` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=40062 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `entity`
--

INSERT INTO `entity` (`id`, `entity_type_id`) VALUES
(1001, 1),
(1002, 1),
(1003, 1),
(4005, 1),
(40001, 1),
(40002, 1),
(40003, 1),
(40007, 1),
(40008, 1),
(40009, 1),
(40010, 1),
(40011, 1),
(40012, 1),
(40013, 1),
(40014, 1),
(40015, 1),
(40016, 1),
(40017, 1),
(40018, 1),
(40019, 1),
(40020, 1),
(40021, 1),
(40022, 1),
(40023, 1),
(40024, 1),
(40025, 1),
(40026, 1),
(40054, 1),
(40055, 1),
(40056, 1),
(40057, 1),
(40058, 1),
(40059, 1),
(40060, 1),
(40061, 1),
(2001, 2),
(2002, 2),
(20000, 2),
(20001, 2),
(20002, 2),
(20003, 2),
(20004, 2),
(20005, 2),
(20006, 2),
(20007, 2),
(20008, 2),
(3001, 3),
(3002, 3),
(3003, 3),
(40000, 4),
(40004, 4),
(40005, 4),
(40006, 4),
(40027, 4),
(40028, 4),
(40029, 4),
(40030, 4),
(40031, 4),
(40032, 4),
(40033, 4),
(40034, 4),
(40035, 4),
(40036, 4),
(40037, 4),
(40038, 4),
(40039, 4),
(40040, 4),
(40041, 4),
(40042, 4),
(40043, 4),
(40044, 4),
(40045, 4),
(40046, 4),
(40047, 4),
(40048, 4),
(40049, 4),
(40050, 4),
(40051, 4),
(40052, 4),
(40053, 4);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `entity_type`
--

INSERT INTO `entity_type` (`id`, `name`) VALUES
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `incident`
--

INSERT INTO `incident` (`id`, `location_id`, `title`, `description`, `incident_type_id`, `priority_id`, `status_type_id`, `mitigate_by`, `reported_by`, `entity_id`) VALUES
(1, 1, 'Fuga de água - piso 1', 'Perda de pressão e água no corredor do piso 1.', 1, 1, 4, NULL, 2, 2001),
(2, 2, 'Risco elétrico', 'Cheiro a queimado no quadro secundário perto da cozinha.', 2, 1, 5, NULL, 3, 2002),
(3, 11, 'Tubo torre', 'Fuga num tubo na casa de banho da torre', 1, 1, 4, '2026-04-08 23:59:59', 1, 20004),
(4, 10, 'dasdasdsa', 'dadsadasdsa', 1, 3, 5, NULL, 2, 20005),
(5, 3, 'ffdsfdsfdsfds', '321321321fdsa', 3, 1, 4, NULL, 2, 20006),
(6, 23, 'Ratos Carnívoras', 'ELES ESTÃO A COMER-NOS A TODOSSSS! (se é que me entendem)', 3, 2, 4, NULL, 1, 20007),
(7, 23, 'Furo de Àgua Comprometido', 'Furo de àgua da base, na avenida do rato, comprometido.', 4, 1, 4, NULL, 3, 20008);

-- --------------------------------------------------------

--
-- Table structure for table `incident_type`
--

DROP TABLE IF EXISTS `incident_type`;
CREATE TABLE IF NOT EXISTS `incident_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `incident_type`
--

INSERT INTO `incident_type` (`id`, `description`) VALUES
(1, 'WATER_LEAK'),
(2, 'ELECTRIC_RISK'),
(3, 'SECURITY'),
(4, 'SANITARY_RISK');

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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `location_type_id`, `name`, `notes`, `geometry`, `status_type_id`, `updated_at`, `entity_id`, `is_critical`) VALUES
(1, 3, 'Novo local', NULL, '{\"type\":\"Polygon\",\"coordinates\":[[[-8.95010,38.62110],[-8.95030,38.62125],[-8.95005,38.62135],[-8.94990,38.62120],[-8.95010,38.62110]]]}', 1, '2026-03-23 13:17:11', 1001, 0),
(2, 3, 'Novo local', NULL, '{\"type\":\"Point\",\"coordinates\":[-8.94960,38.62105]}', 1, '2026-03-23 13:16:49', 1002, 0),
(3, 4, 'Perímetro Norte', NULL, '{\"type\":\"LineString\",\"coordinates\":[[-8.95100,38.62160],[-8.94880,38.62160]]}', 1, '2026-03-04 13:22:28', 1003, 0),
(6, 4, 'Estrada - Acesso', NULL, '{\"type\":\"LineString\",\"coordinates\":[[80,620],[980,620]]}', 1, '2026-03-05 16:36:43', 4005, 0),
(10, 3, 'ddddasdadada', NULL, '{\"type\":\"Point\",\"coordinates\":[442,361.599976]}', 1, '2026-03-14 14:33:25', 40002, 0),
(11, 1, 'Torre', 'Em obras - DI', '{\"type\":\"Polygon\",\"coordinates\":[[[600,320.585246],[600,380.614318],[662,380.614318],[662,320.585246],[600,320.585246]]]}', 2, '2026-03-23 14:49:35', 40003, 1),
(16, 5, 'ESTE 1', NULL, '{\"type\":\"LineString\",\"coordinates\":[[522,396.399994],[530,371.899994]]}', 1, '2026-03-23 13:44:47', 40011, 0),
(17, 5, 'ESTE 2', NULL, '{\"type\":\"LineString\",\"coordinates\":[[530,371.899994],[537,340.899994]]}', 2, '2026-03-23 13:45:26', 40012, 0),
(22, 4, 'Estreito de V', NULL, '{\"type\":\"LineString\",\"coordinates\":[[667,233.399994],[664.5,220.399994]]}', 1, '2026-03-23 16:03:17', 40017, 1),
(23, 4, 'Avenida do Rato', NULL, '{\"type\":\"LineString\",\"coordinates\":[[371.5,336.399994],[427.5,207.899994]]}', 1, '2026-03-23 16:03:53', 40018, 1),
(24, 4, 'Roaço ao Jardim', NULL, '{\"type\":\"LineString\",\"coordinates\":[[458,277.899994],[463.5,215.399994]]}', 2, '2026-03-23 16:36:40', 40019, 1),
(25, 6, 'Estacionamento do vagar', NULL, '{\"type\":\"Polygon\",\"coordinates\":[[[358,350.449997],[358,374.449997],[415.5,374.449997],[415.5,350.449997],[358,350.449997]]]}', 1, '2026-03-26 12:01:05', 40020, 1),
(26, 4, 'Estrada Olho do Sul', NULL, '{\"type\":\"LineString\",\"coordinates\":[[637.75,256.949997],[661.5,270.449997],[686,288.949997],[701.5,309.949997],[708.75,326.449997],[707.75,344.199997],[701.75,355.699997],[689.25,367.449997],[669.75,374.449997],[651.75,369.949997]]}', 1, '2026-03-26 16:16:11', 40021, 1),
(27, 4, 'Estrada Olho do Norte', NULL, '{\"type\":\"LineString\",\"coordinates\":[[597.5,357.224998],[588.5,373.599998],[589.125,395.599998],[594.25,413.349998],[606.375,430.599998],[621.75,441.849998],[646.625,448.724998],[671.25,449.974998]]}', 1, '2026-03-26 16:16:44', 40022, 1),
(28, 4, 'dddd', NULL, '{\"type\":\"LineString\",\"coordinates\":[[260,501.199997],[258.5,480.949997]]}', 1, '2026-03-26 16:17:11', 40023, 1),
(29, 4, 'dfsdfdsfsd', NULL, '{\"type\":\"LineString\",\"coordinates\":[[299.25,507.699997],[299.75,481.199997]]}', 1, '2026-03-26 16:17:19', 40024, 1),
(30, 4, 'dddddd', NULL, '{\"type\":\"LineString\",\"coordinates\":[[387.75,514.199997],[348.5,454.449997]]}', 1, '2026-03-26 16:17:26', 40025, 1),
(31, 4, 'ffdsfdsfsd', NULL, '{\"type\":\"LineString\",\"coordinates\":[[341.25,507.199997],[300,444.199997]]}', 1, '2026-03-26 16:17:33', 40026, 1),
(32, 7, 'TACAN', 'Operacional', '{\"type\":\"Point\",\"coordinates\":[739.5,239.899994]}', 1, '2026-04-07 14:30:28', 40054, 1),
(33, 7, 'ILS 36', 'Indisponível por falta de calibração', '{\"type\":\"Point\",\"coordinates\":[688.5,475.899994]}', 2, '2026-04-07 14:43:23', 40055, 1),
(34, 7, 'Radar', 'Operacional', '{\"type\":\"Point\",\"coordinates\":[610,492.899994]}', 1, '2026-04-07 14:44:06', 40056, 1),
(35, 7, 'ILS 18', 'Indisponível', '{\"type\":\"Point\",\"coordinates\":[808,472.899994]}', 3, '2026-04-07 14:44:57', 40057, 1),
(36, 7, 'ABN', 'Indisponível', '{\"type\":\"Point\",\"coordinates\":[553,527.799988]}', 3, '2026-04-07 14:45:25', 40058, 1),
(37, 7, 'AD Light', NULL, '{\"type\":\"Point\",\"coordinates\":[506,544.899994]}', 2, '2026-04-07 14:47:02', 40059, 1),
(38, 7, 'BAK-12N', 'On raised position', '{\"type\":\"Point\",\"coordinates\":[431,572.799988]}', 2, '2026-04-07 14:47:52', 40060, 1),
(39, 7, 'BAK-12-S', 'Operacional', '{\"type\":\"Point\",\"coordinates\":[358,581.799988]}', 1, '2026-04-07 14:48:17', 40061, 1);

-- --------------------------------------------------------

--
-- Table structure for table `location_type`
--

DROP TABLE IF EXISTS `location_type`;
CREATE TABLE IF NOT EXISTS `location_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(7, 'NAVAids');

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lodging_entry`
--

INSERT INTO `lodging_entry` (`id`, `lodging_site_id`, `people_count`, `checkin_at`, `checkout_at`, `notes`, `unit_id`) VALUES
(9, 1, 32, '2026-03-03', '2026-03-11 00:00:00', NULL, 5),
(11, 1, 30, '2026-03-11', '2026-03-11 00:00:00', NULL, 4),
(12, 1, 31, '2026-03-11', '2026-03-11 00:00:00', NULL, 4),
(17, 1, 31, '2026-03-17', '2026-03-17 00:00:00', '3131331', 2),
(18, 1, 10, '2026-03-18', NULL, 'ddasdsadas', 3),
(19, 1, 10, '2026-03-19', NULL, NULL, 1),
(20, 4, 4, '2026-03-24', '2026-03-26 00:00:00', 'dddd', 4),
(21, 4, 2, '2026-03-26', NULL, 'ddd', 2);

-- --------------------------------------------------------

--
-- Table structure for table `lodging_site`
--

DROP TABLE IF EXISTS `lodging_site`;
CREATE TABLE IF NOT EXISTS `lodging_site` (
  `id` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `capacity_total` int NOT NULL,
  `capacity_available` int NOT NULL,
  `notes` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_lodging_location` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lodging_site`
--

INSERT INTO `lodging_site` (`id`, `location_id`, `name`, `capacity_total`, `capacity_available`, `notes`) VALUES
(1, 3, 'Especialistas 2', 300, 300, NULL),
(3, 1, 'Bloco 5 RC/C', 4, 4, 'T0, cama 2, 2+ colchão no chão'),
(4, 22, 'Alojamento dos Oficiais 1', 100, 100, NULL);

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
('m260408_085326_update_task_decision_log_and_incident_tables', 1775638457);

-- --------------------------------------------------------

--
-- Table structure for table `priority`
--

DROP TABLE IF EXISTS `priority`;
CREATE TABLE IF NOT EXISTS `priority` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `status` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `entity_id` int NOT NULL,
  `request_type_id` int NOT NULL DEFAULT '1',
  `quantity` int DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_request_priority` (`priority_id`),
  KEY `fk_request_status` (`status`),
  KEY `fk_request_entity` (`entity_id`),
  KEY `idx-request-request_type_id` (`request_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`id`, `is_external`, `origin`, `details`, `priority_id`, `status`, `created_at`, `entity_id`, `request_type_id`, `quantity`) VALUES
(37, 0, 'Banho Escola Primária Arrabald', 'Banho Escola Primária ArrabaldBanho Escola Primária Arrabald', 2, 15, '2026-04-07 13:07:49', 40047, 2, 1),
(38, 1, 'Banhos Ext 1', 'Banhos Ext 1', 1, 15, '2026-04-07 13:21:30', 40048, 2, 1),
(39, 1, 'Banho Quente Int 2', 'Banho Quente Int 2', 2, 15, '2026-04-07 13:31:26', 40049, 1, 1),
(40, 0, 'Bath 3', 'Bath 3', 2, 15, '2026-04-07 13:32:19', 40050, 2, 1),
(41, 1, 'Bath ext 2', 'Bath ext', 2, 15, '2026-04-07 13:33:00', 40051, 2, 9),
(42, 1, 'dadadad', 'saddsaadasda', 2, 13, '2026-04-07 13:47:02', 40052, 1, 1),
(43, 1, 'bed 1', 'bed 1bed 1', 1, 15, '2026-04-07 13:57:16', 40053, 3, 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `description` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_status_type_entity_type` (`entity_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `status_type`
--

INSERT INTO `status_type` (`id`, `entity_type_id`, `description`) VALUES
(1, 1, 'GREEN'),
(2, 1, 'YELLOW'),
(3, 1, 'RED'),
(4, 2, 'OPEN'),
(5, 2, 'IN_PROGRES'),
(6, 2, 'RESOLVED'),
(7, 3, 'NEW'),
(8, 3, 'DOING'),
(9, 3, 'BLOCKED'),
(10, 3, 'DONE'),
(11, 4, 'NEW'),
(12, 4, 'APPROVED'),
(13, 4, 'REJECTED'),
(14, 4, 'IN_PROGRES'),
(15, 4, 'DONE');

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
  KEY `fk_task_entity` (`entity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `location_id`, `incident_id`, `title`, `description`, `priority_id`, `status_type_id`, `block_reason`, `assigned_to`, `created_by`, `created_at`, `due_at`, `entity_id`) VALUES
(1, 1, 1, 'Isolar setor e repar', 'Fechar válvula do setor e substituir união danificada.', 1, 8, NULL, 2, 1, '2026-03-04 13:28:12', '2026-03-04', 3001),
(2, 2, 2, 'Inspeção do quadro e', 'Verificar aquecimento anormal e aperto de ligações.', 1, 8, 'Equipa ocupada até dia 10ABR', 3, 1, '2026-03-04 13:28:12', '2026-03-04', 3002),
(3, 3, 1, 'Ronda ao perímetro n', 'Confirmar integridade da vedação e pontos de acesso.', 2, 7, NULL, 2, 1, '2026-03-04 13:28:12', '2026-03-05', 3003);

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `verification_token`) VALUES
(1, 'diogo', 'k1', 'hash_fake_1', NULL, 'diogo@ba5.local', 10, 1772630548, 1772630548, NULL),
(2, 'mario', 'k2', 'hash_fake_2', NULL, 'mario@ba5.local', 10, 1772630548, 1772630548, NULL),
(3, 'igor', 'k3', 'hash_fake_3', NULL, 'igor@ba5.local', 10, 1772630548, 1772630548, NULL),
(4, 'administrator', 'Hd0LOyLlsbE8dIHEiOTywQ8UzXC_J50O', '$2y$13$qrs4qw3cQj4QUGDCsSQS1u.7O38rIwTtCpRtvX2ePqxyPLfU6cXBe', NULL, 'administrator@emfa.gov.pt', 10, 1772630999, 1772630999, 'a6GOZ__IIrniu6CNtiCjMVxLREwR3Fsc_1772630999');

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
-- Constraints for table `lodging_site`
--
ALTER TABLE `lodging_site`
  ADD CONSTRAINT `fk_lodging_location` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `fk-request-request_type_id` FOREIGN KEY (`request_type_id`) REFERENCES `request_type` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_request_entity` FOREIGN KEY (`entity_id`) REFERENCES `entity` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_request_priority` FOREIGN KEY (`priority_id`) REFERENCES `priority` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_request_status` FOREIGN KEY (`status`) REFERENCES `status_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `status_type`
--
ALTER TABLE `status_type`
  ADD CONSTRAINT `fk_status_type_entity_type` FOREIGN KEY (`entity_type_id`) REFERENCES `entity_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
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
