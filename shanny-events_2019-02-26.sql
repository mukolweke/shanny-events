# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.22-0ubuntu18.04.1)
# Database: shanny-events
# Generation Time: 2019-02-26 10:10:39 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `people_count` int(11) DEFAULT NULL,
  `total_cost` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_events_users1_idx` (`user_id`),
  KEY `fk_events_events_status1_idx` (`status`),
  CONSTRAINT `fk_events_events_status1` FOREIGN KEY (`status`) REFERENCES `events_status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_events_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;

INSERT INTO `events` (`id`, `name`, `location`, `date`, `people_count`, `total_cost`, `user_id`, `created_at`, `updated_at`, `deleted_at`, `status`)
VALUES
	(1,'Office Meeting kuku','Sarova Hotel','2019-02-23 03:14:41',10,'10000',15,'2019-02-19 03:15:23','2019-02-19 03:15:23',NULL,2),
	(2,'wedding','kasarani','2019-03-12 00:00:00',100,'200000',15,NULL,NULL,NULL,3),
	(3,'Son\'s Birthday','Westlands','2019-03-01 00:00:00',50,'10000',15,NULL,NULL,NULL,4),
	(4,'last test','cbd','2019-02-02 00:00:00',20,'10000',15,NULL,NULL,NULL,1),
	(5,'birthday shanny','cbd','2019-02-28 00:00:00',80,'9000',15,NULL,NULL,NULL,3);

/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table events_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events_status`;

CREATE TABLE `events_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `slug` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

LOCK TABLES `events_status` WRITE;
/*!40000 ALTER TABLE `events_status` DISABLE KEYS */;

INSERT INTO `events_status` (`id`, `name`, `slug`, `created_at`, `updated_at`)
VALUES
	(1,'Completed','completed',NULL,NULL),
	(2,'Ongoing','ongoing',NULL,NULL),
	(3,'Latest','latest',NULL,NULL),
	(4,'Rejected','rejected',NULL,NULL),
	(5,'Unprocessed','unprocessed',NULL,NULL);

/*!40000 ALTER TABLE `events_status` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table events_task
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events_task`;

CREATE TABLE `events_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `cost` int(11) DEFAULT NULL,
  `event_id` int(255) DEFAULT NULL,
  `status` int(11) DEFAULT '2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_events_task_events1_idx` (`event_id`),
  KEY `fk_events_task_events_status1_idx` (`status`),
  CONSTRAINT `fk_events_task_events1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_events_task_events_status1` FOREIGN KEY (`status`) REFERENCES `events_status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;



# Dump of table user_type
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_type`;

CREATE TABLE `user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

LOCK TABLES `user_type` WRITE;
/*!40000 ALTER TABLE `user_type` DISABLE KEYS */;

INSERT INTO `user_type` (`id`, `name`, `created_at`, `deleted_at`)
VALUES
	(1,'Admin',NULL,NULL),
	(2,'Customer',NULL,NULL);

/*!40000 ALTER TABLE `user_type` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_type` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_user_type_idx` (`user_type`),
  CONSTRAINT `fk_users_user_type` FOREIGN KEY (`user_type`) REFERENCES `user_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `user_type`, `created_at`, `updated_at`, `deleted_at`)
VALUES
	(15,'Mukolwe','Jordan','mikemike@gmail.com','0722858490','$2y$10$ver98BEMDSN.QQxvzBMl0Onre1sp30jl6g3QDp5d0R5aVC57QODi6',2,NULL,NULL,NULL),
	(16,'Admin','Events','admin@gmail.com','0722000000','$2y$10$qPoRTOpsVOXm.DTBy2wR1uLAbqewcRazPx2kF8/SCGfAal3DsykBO',1,NULL,NULL,NULL);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
