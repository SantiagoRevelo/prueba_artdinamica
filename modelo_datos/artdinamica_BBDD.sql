-- Adminer 4.2.6-dev MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `artdinamica`;
CREATE DATABASE `artdinamica` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci */;
USE `artdinamica`;

DROP TABLE IF EXISTS `contracts`;
CREATE TABLE `contracts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` date DEFAULT NULL,
  `finish_date` date DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `workplace_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `workplace_id` (`workplace_id`),
  CONSTRAINT `contracts_ibfk_4` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `contracts_ibfk_5` FOREIGN KEY (`workplace_id`) REFERENCES `workplaces` (`id`),
  CONSTRAINT `contracts_ibfk_6` FOREIGN KEY (`workplace_id`) REFERENCES `workplaces` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE `contracts`;
INSERT INTO `contracts` (`id`, `start_date`, `finish_date`, `role_id`, `workplace_id`) VALUES
(1,	'2019-10-29',	NULL,	1,	1),
(2,	'2018-10-29',	NULL,	3,	2),
(3,	'2019-02-01',	NULL,	2,	3),
(4,	'2015-03-21',	NULL,	2,	4),
(5,	'2017-10-29',	NULL,	2,	3);

DROP TABLE IF EXISTS `deviations`;
CREATE TABLE `deviations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `deviation` float DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

TRUNCATE `deviations`;
INSERT INTO `deviations` (`id`, `employee_id`, `deviation`, `update_date`) VALUES
(18,	1,	0.559017,	'2019-10-30'),
(19,	2,	0.25,	'2019-10-30'),
(20,	3,	0,	'2019-10-30'),
(21,	4,	0,	'2019-10-30');

DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `dni` varchar(9) DEFAULT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `photo` text,
  PRIMARY KEY (`id`),
  KEY `contract_id` (`contract_id`),
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE `employees`;
INSERT INTO `employees` (`id`, `name`, `lastname`, `address`, `birth_date`, `dni`, `contract_id`, `photo`) VALUES
(1,	'Felipe',	'Guzman Pérez',	'Calle Menorca, 16, 3ºA, 28009 Madrid',	'2019-10-29',	'123456456',	1,	'assets/felipe.jpeg'),
(2,	'Pedro',	'Feliz Cortés',	'Calle Rodrigo de Arana, 6, 4ºB, 28044 Madrid',	'2019-10-29',	'789987789',	2,	'assets/pedro.jpeg'),
(3,	'Joaquín',	'Cabezas Quintero',	'Calle de Servator, 22, Bajo C 28043 Madrid',	'2019-10-29',	'654456654',	3,	'assets/joaquin.jpeg'),
(4,	'Magdalena',	'Saura Piqueras',	'Calle de Servator, 22, 28043 Madrid',	'2019-10-29',	'147741147',	4,	'assets/magdalena.jpeg');

DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `entrance_time` time DEFAULT NULL,
  `exit_time` time DEFAULT NULL,
  `hours_worked` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE `reports`;
INSERT INTO `reports` (`id`, `employee_id`, `date`, `entrance_time`, `exit_time`, `hours_worked`) VALUES
(1,	1,	'2019-10-01',	'08:00:00',	'19:00:00',	8.00),
(2,	1,	'2019-10-02',	'09:15:00',	'18:20:00',	8.00),
(3,	2,	'2019-10-01',	'08:00:00',	'17:15:00',	8.25),
(4,	1,	'2019-10-03',	'09:00:00',	'17:00:00',	7.00),
(5,	1,	'2019-10-04',	'09:00:00',	'18:30:00',	8.50),
(6,	3,	'2019-10-01',	'09:00:00',	'18:00:00',	8.00),
(7,	3,	'2019-10-02',	'09:00:00',	'18:00:00',	8.00),
(8,	3,	'2019-10-02',	'09:00:00',	'18:00:00',	8.00);

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `entrance_time` time DEFAULT NULL,
  `exit_time` time DEFAULT NULL,
  `job_hours` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE `roles`;
INSERT INTO `roles` (`id`, `name`, `entrance_time`, `exit_time`, `job_hours`) VALUES
(1,	'Gerente',	'08:00:00',	'17:00:00',	8),
(2,	'Vendedor',	'10:00:00',	'19:00:00',	8),
(3,	'Mozo de Almacén',	'09:00:00',	'18:00:00',	8),
(4,	'Vendedor media jornada',	'09:00:00',	'16:00:00',	6);

DROP TABLE IF EXISTS `workplaces`;
CREATE TABLE `workplaces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

TRUNCATE `workplaces`;
INSERT INTO `workplaces` (`id`, `name`) VALUES
(1,	'Oficina central'),
(2,	'Almacén'),
(3,	'Tienda 1'),
(4,	'Tienda 2'),
(5,	'Tienda 3');

-- 2019-10-30 14:23:53
