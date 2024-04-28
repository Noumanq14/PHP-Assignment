/*
SQLyog Ultimate v12.5.0 (64 bit)
MySQL - 8.0.30 : Database - php_assignment
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`php_assignment` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `php_assignment`;

/*Table structure for table `movies` */

DROP TABLE IF EXISTS `movies`;

CREATE TABLE `movies` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `name` varchar(255) DEFAULT NULL,
                          `release_date` date DEFAULT NULL,
                          `director` varchar(255) DEFAULT NULL,
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

/*Table structure for table `movie_casts` */

DROP TABLE IF EXISTS `movie_casts`;

CREATE TABLE `movie_casts` (
                               `id` int(11) NOT NULL AUTO_INCREMENT,
                               `movie_id` int(11) DEFAULT NULL,
                               `cast_name` text DEFAULT NULL,
                               PRIMARY KEY (`id`),
                               KEY `movie_id` (`movie_id`),
                               CONSTRAINT `movie_casts_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

/*Table structure for table `movie_ratings` */

DROP TABLE IF EXISTS `movie_ratings`;

CREATE TABLE `movie_ratings` (
                                 `id` int(11) NOT NULL AUTO_INCREMENT,
                                 `movie_id` int(11) DEFAULT NULL,
                                 `imdb` float DEFAULT NULL,
                                 `rotten_tomatto` float DEFAULT NULL,
                                 PRIMARY KEY (`id`),
                                 KEY `movie_id` (`movie_id`),
                                 CONSTRAINT `movie_ratings_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

/*Table structure for table `posts` */

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
                         `id` int(11) NOT NULL AUTO_INCREMENT,
                         `title` varchar(255) DEFAULT NULL,
                         `content` text DEFAULT NULL,
                         `author` varchar(255) DEFAULT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
