/*!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.5.25-MariaDB, for Linux (x86_64)
--
-- Host: incargo365-db.ctgao2q62bzx.us-east-1.rds.amazonaws.com    Database: incargo365
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `client_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `rolID` int DEFAULT NULL,
  `townID` int DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isactive` tinyint(1) DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`client_id`),
  UNIQUE KEY `email` (`email`),
  KEY `rolID` (`rolID`),
  KEY `townID` (`townID`),
  CONSTRAINT `client_ibfk_1` FOREIGN KEY (`rolID`) REFERENCES `rol` (`rolID`) ON DELETE SET NULL,
  CONSTRAINT `client_ibfk_2` FOREIGN KEY (`townID`) REFERENCES `location` (`townID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (3,'Sherzod','Yarashev','sher_mcnemo@yahoo.com','$2y$10$0WfSFPgBwgT9ye2AFf.O2ezJN6l9bgp.127FTbcs4qIh3g02cBDpe','610925792',9,NULL,NULL,NULL,'Active','2025-05-02 08:42:22','2025-05-02 08:42:22',1,NULL),(4,'Carlos','García','carlos.garcia@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','612345678',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(5,'María','López','maria.lopez@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','622345678',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(6,'José','Martínez','jose.martinez@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','632345678',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(7,'Ana','Sánchez','ana.sanchez@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','642345678',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(8,'Luis','Rodríguez','luis.rodriguez@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','652345678',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(9,'Laura','Fernández','laura.fernandez@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','662345678',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(10,'David','Gómez','david.gomez@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','672345678',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(11,'Lucía','Díaz','lucia.diaz@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','682345678',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(12,'Javier','Ruiz','javier.ruiz@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','692345678',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(13,'Elena','Hernández','elena.hernandez@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','602345678',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(14,'Pablo','Jiménez','pablo.jimenez@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','611112222',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(15,'Isabel','Moreno','isabel.moreno@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','622223333',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(16,'Manuel','Muñoz','manuel.munoz@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','633334444',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(17,'Sara','Romero','sara.romero@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','644445555',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(18,'Alberto','Navarro','alberto.navarro@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','655556666',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(19,'Cristina','Gil','cristina.gil@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','666667777',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(20,'Raúl','Iglesias','raul.iglesias@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','677778888',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(21,'Marta','Ortega','marta.ortega@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','688889999',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(22,'Miguel','Castro','miguel.castro@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','699990000',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(23,'Natalia','Vega','natalia.vega@example.com','bd94dcda26fccb4e68d6a31f9b5aac0b571ae266d822620e901ef7ebe3a11d4f','600001111',9,1,NULL,NULL,'Active','2025-05-02 09:37:25','2025-05-02 09:37:25',1,NULL),(24,'Cliente','Demo','cliente@incargo365.com','$2y$10$Rrjfvr9fHB.zyVcRR0R7M.RimPTFe5XEpnUksPnFacWJ5RS1uVy..','600123456',9,NULL,NULL,NULL,'Active','2025-05-02 10:34:51','2025-05-04 11:06:40',1,NULL),(26,'ClienteNuevo','Demo','client@incargo365.com','$2y$10$Pgc3iK3NUAaK6urmyXbhr.FEhgq/gbPEnInJ5Pq5VjQ.IU9LOs9zK',NULL,9,NULL,NULL,NULL,'Active','2025-05-02 12:02:40','2025-05-02 12:07:38',1,NULL),(27,'Carlos','Cliente','cliente1@incargo365.com','cliente1',NULL,9,NULL,NULL,NULL,'Active','2025-05-02 12:16:32','2025-05-02 12:16:32',1,NULL),(28,'Lucía','Usuario','cliente2@incargo365.com','cliente2',NULL,9,NULL,NULL,NULL,'Active','2025-05-02 12:16:32','2025-05-02 12:16:32',1,NULL),(29,'usuario22','22','cliente22@incargo365.com','$2y$10$5/sOrau9qpnuNnAIBDnZZuk07s4WAAzsiSr1Dg4KWUOQ0SBqLxk/K','55',9,NULL,NULL,NULL,'Active','2025-05-04 20:22:47','2025-05-07 11:07:07',1,NULL),(30,'Carlos','Inactivo','pruebas20@incargo365.com','$2y$10$PMatoKqFOfJGX6fwjzXFE.dTQTDThR3qb7cQMXTchYncLc7Fr27IS','600123456',9,NULL,NULL,NULL,'Inactive','2025-05-05 09:26:03','2025-05-05 16:23:19',1,NULL),(31,'Sher','Y','client_sher@incargo365.com','$2y$10$QG4z48DtupZlOmJcawxt6OMHnV9RBe7JH01KQjx/SKV09ms0vIQ8y','123456789',9,NULL,NULL,NULL,'Active','2025-05-06 13:15:20','2025-05-06 13:16:21',1,NULL),(32,'XXX','XXX','xxx@incargo365.com','$2y$10$U2979Bm1Uh9Fb.DIRRpfXe.iFjY0BJvmlCyFfmlxATtpgDQkc9LdG','1112222',9,NULL,NULL,NULL,'Active','2025-05-07 08:05:59','2025-05-07 08:05:59',1,NULL),(35,'Sherzod ','Yarashev','yarashev@mail.com','$2y$10$dvlSd0MtlfZJgFLhqVcIFewbEBoOS6.sqxlWoK2UTbht0Vq1VT/p2','1234',9,NULL,NULL,NULL,'Active','2025-05-15 17:54:12','2025-05-15 17:54:12',1,NULL),(36,'Julia','Igual','julia@julia.com','$2y$10$fNM4aZz..xD89vJTpYlJPePcyd0ZbTskuH2GTKXSFs7/lFmojh3Oy','1223444',9,NULL,NULL,NULL,'Active','2025-05-19 14:17:10','2025-05-19 14:17:10',1,NULL),(37,'client','gg','client2005@incargo365.com','$2y$10$YN3F7wTeEGIE.7/HQ3sct.aNMXRFNAHYjB4PIYXtsWjzTUv6lM5hm','55',9,NULL,NULL,NULL,'Active','2025-05-20 11:37:44','2025-05-20 11:37:44',1,NULL);
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee` (
  `employee_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `rolID` int DEFAULT NULL,
  `warehouseID` int DEFAULT NULL,
  `townID` int DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isactive` tinyint(1) DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `fleetID` int DEFAULT NULL,
  PRIMARY KEY (`employee_id`),
  UNIQUE KEY `email` (`email`),
  KEY `rolID` (`rolID`),
  KEY `townID` (`townID`),
  KEY `fk_fleet_employee` (`fleetID`),
  CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`rolID`) REFERENCES `rol` (`rolID`) ON DELETE SET NULL,
  CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`townID`) REFERENCES `location` (`townID`) ON DELETE SET NULL,
  CONSTRAINT `fk_fleet_employee` FOREIGN KEY (`fleetID`) REFERENCES `fleet` (`fleetID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (2,'Owner','Owner','owner@incargo365.com','$2y$10$eK7eKKIS6k8pHPMibIu2NeL06XY6R9OcOP.G7LFNBiI07AvQIwBtu','+34600000025',1,NULL,1,NULL,NULL,'Active','2025-05-01 21:21:49','2025-05-04 14:18:25',1,NULL,NULL),(13,'Laura','Martínez','laura.finance1@incargo365.com','$2y$10$57q3CItoN77r2r5aHgJDEe3voFZdPgzSupdNXP6dILWkb99stWQXa','+34600000001',2,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(14,'Carlos','González','carlos.finance2@incargo365.com','$2y$10$mJ8qLAxOj3FWGI4iVoTO/uMLKgfkAMnrnej9dyQ7/oyG6PmD0wrh.','+34600000002',2,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-05 17:16:39',1,NULL,NULL),(15,'Elena','Ruiz','elena.people1@incargo365.com','$2y$10$X9agrlt1HU3I8mez4Tt0ieOvDsrxdxcgPMQLAffTbefXzukuPZ11q','+34600000003',3,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-05 17:06:52',1,NULL,NULL),(16,'Miguel','Torres','miguel.people2@incargo365.com','$2y$10$1ZBYWvN2a2iY8Sfx0H9xCeda3.IOMD4aUmCS.8wzye0ORc0S4Wlry','+34600000004',3,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-05 17:09:24',1,NULL,NULL),(17,'Andrea','Soler','andrea.itdev1@incargo365.com','$2y$10$RoUOuwL7TmQ3J8xIOID5n.FpmoVmHMmJDU58I5ceiV5IXy0YqAAwa','+34600000005',4,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-05 18:53:33',1,NULL,NULL),(18,'Sergio','Fernández','sergio.ittech@incargo365.com','$2y$10$lzhtTACiuJIspEqXBVCN3.zSneAHbkzaC5TP3d.gbg88tWyAU0M6e','+34600000006',5,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-05 20:22:11',1,NULL,NULL),(19,'Lucía','Vega','lucia.cs1@incargo365.com','$2y$10$LApokdI6cPeIFkFE71yEXuXIRcTOqtzGMEw6ynMyMJz1cn1F/nC92','+34600000007',6,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(20,'Jorge','Pérez','jorge.cs2@incargo365.com','$2y$10$qUV1SKZI1N4PZyWDKHMQ9e3Axj6zoH1rOiIlb4nHFFkVnNG0FAEvO','+34600000008',6,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(21,'Natalia','Campos','natalia.cs3@incargo365.com','$2y$10$CM8bGynV4OGhxd6U3K1Xoe.kflIErK4wPOzrbU0g09i3O/mF2Akty','+34600000009',6,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(22,'Pablo','López','pablo.warehouse1@incargo365.com','$2y$10$gUQqUN35Mu3Tnb93I2g3fOt0CUZ98N0hBlWE5jEZPo2BqfWvUeNaG','+34600000010',7,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(23,'Laura','Ramos','laura.warehouse2@incargo365.com','$2y$10$Bl8Qq2fT3gRPcz0XkLmlieYQlT5QAYPiSzFgCh8kd9L1vD5O8CNVe','+34600000011',7,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(24,'David','Moreno','david.warehouse3@incargo365.com','$2y$10$gvw6gnZk1oZb6A9bxzyxOekDa4MEaRtndwTzXZxMWa3WIaT3v04W2','+34600000024',7,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(25,'Isabel','Sánchez','isabel.warehouse4@incargo365.com','$2y$10$BLTL12AgZjXCFW2Ap9Pg8.Rb3aPVFJRYbzL5gyznlgHvPje/nJXKy','+34600000012',7,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(26,'Rafael','Ortiz','rafael.warehouse5@incargo365.com','$2y$10$zWJ4M/1aFG6WVLnqO/UD2eU5z9cKZqPMY8AvMKkzodH6hFtU1F0Cy','+34600000013',7,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(27,'Álvaro','Gómez','alvaro.driver1@incargo365.com','$2y$10$PfOpEnPfCPITBcx3bd6F5ewNCCiN7aB3gSU7Z0slpKxOCUE9ScQ9i','+34600000014',8,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(28,'Daniela','Castro','daniela.driver2@incargo365.com','$2y$10$O0gckALNdEQa4gyqg7yoX.1qpyJp37WvZUEpg0Qv5vQ8r7pznV8HW','+34600000015',8,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(29,'Marcos','Navarro','marcos.driver3@incargo365.com','$2y$10$qv2kRUhQpbe6DkwVoxUQpeUz9p8bKHAFUynTX3MZhFSIA6UbT3AbG','+34600000016',8,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(30,'Alicia','Cruz','alicia.driver4@incargo365.com','$2y$10$2dUKFg1O45F47CUE9N3jb.Fkk3syEcT34YwKi.MvKywAy0.qMIoMe','+34600000017',8,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(31,'Juan','Serrano','juan.driver5@incargo365.com','$2y$10$B0PiLkzqKpk5JbgpiUPnLe5ILjTphgPSyRYDw9Ufb9ml.RsZoFCIm','+34600000018',8,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(32,'Irene','Ramírez','irene.driver6@incargo365.com','$2y$10$ZEFcpHyCEBxBWuK1oIUV4OtsYTWEuGZED7elZHKTVNL99VtxSK8aW','+34600000019',8,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(33,'Antonio','Molina','antonio.driver7@incargo365.com','$2y$10$cmZAzddr4WdlNiMva9ObQOJXf80ed3NkzwPK4JP4DdHR6FVj3WX3C','+34600000020',8,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(34,'Sofía','León','sofia.driver8@incargo365.com','$2y$10$uvWjBOSiBt3dT0v56lvFieC0I06qRJbMViMfzzso6qFFwHZgcQ8aG','+34600000021',8,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(35,'Hugo','Blanco','hugo.driver9@incargo365.com','$2y$10$byLtVdrbRjFDXcPrKXgYd.XCE3DoAjYOReizJVaFHK/7qYdd0zK7q','+34600000022',8,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(36,'Carla','Domingo','carla.driver10@incargo365.com','$2y$10$pZzWqbg8x3KTu14.OyOKDuyvQ70.PTn5R7RCzW3Gi5bE1Kxrk6amS','+34600000023',8,NULL,NULL,NULL,NULL,'Active','2025-05-04 14:00:14','2025-05-04 14:18:25',1,NULL,NULL),(40,'Sher','Y','sher@incargo365.com','$2y$10$nyFXdhqmCupKiGk2ZiBLOea4KuKkSCxO2AfyVXzMW1fIimWHJZdiu','1111111',2,NULL,NULL,NULL,NULL,'Active','2025-05-05 20:51:45','2025-05-07 07:47:35',1,NULL,NULL),(41,'empleado1','empleado1','empleado1@mail.com','$2y$10$.54vc3wmWgIjzfQiq3ryau9Qx4XdIJIdweirG1T95QqqmvIdiUaku','123',8,NULL,NULL,NULL,NULL,'Active','2025-05-15 14:28:24','2025-05-15 18:35:51',1,NULL,NULL),(42,'Julia','Igual','julia@incargo365.com','$2y$10$3lpstH6bhamvN1Zti.q8l.r1OLO8/W/F/xB8WN.tWeukLzgLOanIq','123456',4,NULL,NULL,NULL,NULL,'Active','2025-05-19 14:12:22','2025-05-19 14:24:06',1,NULL,NULL),(53,'PC Prodidy','gg','it2105@incargo365.com','$2y$10$vhCs7EWQp.5qOka338CQ3uyZPW7oeWNdaN6o4bg09lIahrkaPbLzG','55',5,NULL,NULL,NULL,NULL,'Active','2025-05-21 19:11:34','2025-05-21 19:11:34',1,NULL,NULL),(54,'Prueba1','Prueba1','prueba1@incargo365.com','$2y$10$ZaWk7N3iiNoYTU1OGg9UHO3b5X73JcT6nxwOxg.zA3QHkGCbWZtSS','12233444',2,NULL,NULL,NULL,NULL,'Active','2025-05-23 17:35:28','2025-05-23 17:35:28',1,NULL,NULL);
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fleet`
--

DROP TABLE IF EXISTS `fleet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fleet` (
  `fleetID` int NOT NULL AUTO_INCREMENT,
  `type` enum('Truck','Van','Container') NOT NULL,
  `license_plate` varchar(20) DEFAULT NULL,
  `capacity_kg` decimal(10,2) DEFAULT NULL,
  `status` enum('Available','On Route','Maintenance') DEFAULT 'Available',
  `townID` int DEFAULT NULL,
  `lastMaintenance` date DEFAULT NULL,
  PRIMARY KEY (`fleetID`),
  UNIQUE KEY `license_plate` (`license_plate`),
  KEY `townID` (`townID`),
  CONSTRAINT `fleet_ibfk_1` FOREIGN KEY (`townID`) REFERENCES `location` (`townID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fleet`
--

LOCK TABLES `fleet` WRITE;
/*!40000 ALTER TABLE `fleet` DISABLE KEYS */;
INSERT INTO `fleet` VALUES (21,'Truck','INC-TRK-001',8000.00,'Available',1,'2024-12-10'),(22,'Van','INC-VAN-002',2000.00,'On Route',1,'2025-02-15'),(23,'Container','INC-CON-003',10000.00,'Maintenance',1,'2025-01-20'),(24,'Truck','INC-TRK-004',8500.00,'Available',1,'2024-11-05'),(25,'Van','INC-VAN-005',2200.00,'Available',1,'2025-01-01'),(26,'Truck','INC-TRK-006',9000.00,'On Route',1,'2024-12-18'),(27,'Container','INC-CON-007',12000.00,'Available',1,'2024-10-30'),(28,'Van','INC-VAN-008',2500.00,'Maintenance',1,'2025-03-01'),(29,'Truck','INC-TRK-009',9500.00,'Available',1,'2024-12-12'),(30,'Container','INC-CON-010',10500.00,'Available',1,'2024-09-25');
/*!40000 ALTER TABLE `fleet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `integrations`
--

DROP TABLE IF EXISTS `integrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `integrations` (
  `integration_id` int NOT NULL AUTO_INCREMENT,
  `integration_name` varchar(100) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `last_sync` datetime DEFAULT NULL,
  PRIMARY KEY (`integration_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `integrations`
--

LOCK TABLES `integrations` WRITE;
/*!40000 ALTER TABLE `integrations` DISABLE KEYS */;
INSERT INTO `integrations` VALUES (1,'API Transporte','REST',1,'2025-05-04 12:14:37'),(2,'Webhook Facturación','Webhook',1,'2025-05-04 12:14:37'),(3,'CRM Enlace','SOAP',0,NULL);
/*!40000 ALTER TABLE `integrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice`
--

DROP TABLE IF EXISTS `invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice` (
  `invoice_id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `packageID` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`invoice_id`),
  KEY `client_id` (`client_id`),
  KEY `packageID` (`packageID`),
  CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`),
  CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`packageID`) REFERENCES `package` (`packageID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice`
--

LOCK TABLES `invoice` WRITE;
/*!40000 ALTER TABLE `invoice` DISABLE KEYS */;
INSERT INTO `invoice` VALUES (1,18,52,12233.00,'2025-05-15 14:48:21'),(2,18,60,0.00,'2025-05-20 11:44:03');
/*!40000 ALTER TABLE `invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location` (
  `townID` int NOT NULL AUTO_INCREMENT,
  `townName` varchar(100) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`townID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
INSERT INTO `location` VALUES (1,'Barcelona','España');
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_history`
--

DROP TABLE IF EXISTS `login_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_history` (
  `loginID` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `client_id` int DEFAULT NULL,
  `login_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `IP_address` varchar(50) DEFAULT NULL,
  `user_agent` text,
  PRIMARY KEY (`loginID`),
  KEY `client_id` (`client_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `login_history_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`) ON DELETE CASCADE,
  CONSTRAINT `login_history_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_history`
--

LOCK TABLES `login_history` WRITE;
/*!40000 ALTER TABLE `login_history` DISABLE KEYS */;
INSERT INTO `login_history` VALUES (1,2,NULL,'2025-05-04 17:44:38','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(2,13,NULL,'2025-05-04 18:21:20','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(3,14,NULL,'2025-05-04 18:21:41','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(4,13,NULL,'2025-05-04 18:26:43','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(5,14,NULL,'2025-05-04 18:30:01','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(6,2,NULL,'2025-05-04 18:42:46','90.166.75.242','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(7,2,NULL,'2025-05-04 19:37:12','90.166.75.242','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(8,2,NULL,'2025-05-04 19:50:02','90.166.75.242','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(10,13,NULL,'2025-05-04 20:20:17','90.166.75.242','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(11,NULL,29,'2025-05-04 20:22:56','90.166.75.242','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(12,NULL,29,'2025-05-04 20:23:27','90.166.75.242','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(14,2,NULL,'2025-05-05 07:49:01','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(15,2,NULL,'2025-05-05 14:38:22','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(16,2,NULL,'2025-05-05 14:41:50','85.192.70.145','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:137.0) Gecko/20100101 Firefox/137.0'),(17,2,NULL,'2025-05-05 16:22:51','85.192.70.145','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:137.0) Gecko/20100101 Firefox/137.0'),(18,2,NULL,'2025-05-05 16:24:50','85.192.70.145','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:137.0) Gecko/20100101 Firefox/137.0'),(19,2,NULL,'2025-05-05 16:25:15','85.192.70.145','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:137.0) Gecko/20100101 Firefox/137.0'),(20,2,NULL,'2025-05-05 16:34:02','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(21,14,NULL,'2025-05-05 16:55:44','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(22,2,NULL,'2025-05-05 17:02:36','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(23,15,NULL,'2025-05-05 17:07:52','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(24,16,NULL,'2025-05-05 17:09:45','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(25,2,NULL,'2025-05-05 17:10:16','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(26,14,NULL,'2025-05-05 17:13:23','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(27,2,NULL,'2025-05-05 17:13:46','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(28,14,NULL,'2025-05-05 17:14:13','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(29,14,NULL,'2025-05-05 17:15:57','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(30,14,NULL,'2025-05-05 17:16:11','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(31,2,NULL,'2025-05-05 17:16:22','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(32,14,NULL,'2025-05-05 17:16:44','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(33,2,NULL,'2025-05-05 17:16:53','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(34,14,NULL,'2025-05-05 17:17:05','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(35,2,NULL,'2025-05-05 17:20:16','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(36,2,NULL,'2025-05-05 17:21:58','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(37,2,NULL,'2025-05-05 17:28:41','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(38,16,NULL,'2025-05-05 17:35:38','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(39,16,NULL,'2025-05-05 17:42:00','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(40,16,NULL,'2025-05-05 17:43:11','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(41,2,NULL,'2025-05-05 18:42:20','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(42,2,NULL,'2025-05-05 18:52:36','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(43,17,NULL,'2025-05-05 18:53:54','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(44,2,NULL,'2025-05-05 20:17:31','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(45,18,NULL,'2025-05-05 20:23:08','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(46,2,NULL,'2025-05-05 20:48:25','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(47,40,NULL,'2025-05-05 20:52:08','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(48,2,NULL,'2025-05-05 20:52:34','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(49,40,NULL,'2025-05-05 20:53:17','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(50,2,NULL,'2025-05-05 21:16:46','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(51,2,NULL,'2025-05-06 08:19:23','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(52,40,NULL,'2025-05-06 08:20:02','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(53,40,NULL,'2025-05-06 08:20:47','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(54,2,NULL,'2025-05-06 08:48:47','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(55,40,NULL,'2025-05-06 08:49:07','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(56,2,NULL,'2025-05-06 13:05:40','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(57,2,NULL,'2025-05-06 13:12:48','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(58,2,NULL,'2025-05-06 13:13:06','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(59,2,NULL,'2025-05-06 13:13:24','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(60,2,NULL,'2025-05-06 13:14:15','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(61,2,NULL,'2025-05-06 13:14:37','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(62,40,NULL,'2025-05-06 13:15:33','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(63,2,NULL,'2025-05-06 13:15:54','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(64,NULL,31,'2025-05-06 13:17:08','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(65,2,NULL,'2025-05-06 13:29:45','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(66,2,NULL,'2025-05-07 07:05:42','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(67,2,NULL,'2025-05-07 07:08:29','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(68,2,NULL,'2025-05-07 07:12:02','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(69,2,NULL,'2025-05-07 07:15:05','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(70,40,NULL,'2025-05-07 07:16:53','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(71,2,NULL,'2025-05-07 07:18:16','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(72,40,NULL,'2025-05-07 07:47:44','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(73,2,NULL,'2025-05-07 08:05:09','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(74,NULL,32,'2025-05-07 08:06:12','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(75,2,NULL,'2025-05-07 10:53:12','90.166.74.57','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(76,NULL,29,'2025-05-07 11:07:18','90.166.74.57','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(77,2,NULL,'2025-05-07 11:08:41','90.166.74.57','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(78,2,NULL,'2025-05-07 17:14:47','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(79,NULL,29,'2025-05-07 17:19:54','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(80,2,NULL,'2025-05-07 17:37:35','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(81,2,NULL,'2025-05-08 17:51:49','79.117.174.79','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36'),(82,2,NULL,'2025-05-15 13:33:00','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(84,2,NULL,'2025-05-15 14:12:34','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(85,41,NULL,'2025-05-15 14:28:39','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(86,41,NULL,'2025-05-15 14:42:55','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(87,2,NULL,'2025-05-15 14:51:52','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(88,41,NULL,'2025-05-15 14:52:25','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(89,2,NULL,'2025-05-15 16:14:32','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(90,41,NULL,'2025-05-15 16:14:57','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(91,2,NULL,'2025-05-15 16:33:29','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(92,41,NULL,'2025-05-15 16:33:55','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(93,2,NULL,'2025-05-15 16:53:53','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(94,41,NULL,'2025-05-15 16:54:19','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(95,2,NULL,'2025-05-15 17:53:15','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(96,NULL,35,'2025-05-15 17:54:39','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(97,2,NULL,'2025-05-15 18:21:46','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(98,41,NULL,'2025-05-15 18:35:15','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(99,2,NULL,'2025-05-15 18:35:36','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(100,41,NULL,'2025-05-15 18:36:02','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(101,2,NULL,'2025-05-16 14:58:28','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(102,2,NULL,'2025-05-19 14:11:00','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(103,42,NULL,'2025-05-19 14:12:53','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(104,2,NULL,'2025-05-19 14:16:20','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(105,42,NULL,'2025-05-19 14:21:33','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(106,2,NULL,'2025-05-19 14:23:53','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(107,2,NULL,'2025-05-19 14:24:14','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(108,42,NULL,'2025-05-19 14:24:24','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(109,2,NULL,'2025-05-19 16:21:56','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(110,2,NULL,'2025-05-19 16:31:56','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(111,2,NULL,'2025-05-19 16:42:09','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(112,2,NULL,'2025-05-20 11:20:24','95.17.64.16','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(113,2,NULL,'2025-05-20 11:23:30','95.17.64.16','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(119,2,NULL,'2025-05-20 12:06:40','95.17.64.16','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(121,2,NULL,'2025-05-20 12:09:03','95.17.64.16','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(123,NULL,37,'2025-05-20 12:13:44','95.17.64.16','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(124,2,NULL,'2025-05-20 12:17:23','95.17.64.16','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(126,NULL,37,'2025-05-20 12:24:51','95.17.64.16','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(127,2,NULL,'2025-05-21 16:25:32','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(128,2,NULL,'2025-05-21 17:12:25','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(130,2,NULL,'2025-05-21 17:15:54','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(131,2,NULL,'2025-05-21 19:03:54','95.17.64.16','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(133,2,NULL,'2025-05-21 19:10:54','95.17.64.16','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(134,NULL,37,'2025-05-21 19:11:49','95.17.64.16','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(135,53,NULL,'2025-05-21 19:13:44','95.17.64.16','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36'),(136,2,NULL,'2025-05-23 17:33:47','85.192.70.145','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36');
/*!40000 ALTER TABLE `login_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `log_type` varchar(50) DEFAULT NULL,
  `message` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `source` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (1,'Info','Inicio de sesión exitoso del usuario itdev@incargo365.com','2025-05-04 12:15:34','Login'),(2,'Error','Fallo al conectar con API externa','2025-05-04 12:15:34','Integración API'),(3,'Debug','Consulta SQL ejecutada correctamente','2025-05-04 12:15:34','Base de datos'),(4,'Info','Sincronización completada con éxito','2025-05-04 12:15:34','Integración API'),(5,'Error','Token expirado en API de transporte','2025-05-04 12:15:34','Integración API');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `messageID` int NOT NULL AUTO_INCREMENT,
  `ticketID` int NOT NULL,
  `client_id` int DEFAULT NULL,
  `employee_id` int DEFAULT NULL,
  `message_text` varchar(500) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`messageID`),
  KEY `client_id` (`client_id`),
  KEY `employee_id` (`employee_id`),
  KEY `ticketID` (`ticketID`),
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`) ON DELETE CASCADE,
  CONSTRAINT `message_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE,
  CONSTRAINT `message_ibfk_3` FOREIGN KEY (`ticketID`) REFERENCES `ticket` (`ticketID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
INSERT INTO `message` VALUES (2,26,7,NULL,'Aquí mi comentario adicional','2025-05-15 14:15:03','2025-05-15 14:15:03'),(3,20,NULL,41,'asdasdasd','2025-05-15 14:42:27','2025-05-15 14:42:27'),(4,18,NULL,41,'asdasfasfd','2025-05-15 15:18:29','2025-05-15 15:18:29'),(5,18,NULL,41,'es segunda mensaje','2025-05-15 15:33:08','2025-05-15 15:33:08'),(6,18,NULL,41,'es tersera mensaje','2025-05-15 15:33:19','2025-05-15 15:33:19'),(7,23,NULL,41,'primer mensaje','2025-05-15 16:50:05','2025-05-15 16:50:05'),(8,26,NULL,41,'es primer mensaje','2025-05-15 17:42:14','2025-05-15 17:42:14'),(9,24,NULL,41,'primer ,emsajes','2025-05-15 17:44:34','2025-05-15 17:44:34'),(10,28,35,NULL,'primer mensaje','2025-05-15 18:18:24','2025-05-15 18:18:24'),(11,28,35,NULL,'segundo mensaje','2025-05-15 18:18:41','2025-05-15 18:18:41'),(12,28,35,NULL,'Mensaje para tiquet 28','2025-05-15 18:20:36','2025-05-15 18:20:36'),(13,28,NULL,2,'Error','2025-05-15 18:29:51','2025-05-15 18:29:51'),(14,28,NULL,2,'Error','2025-05-15 18:29:59','2025-05-15 18:29:59'),(15,24,NULL,42,'Esta actualizado','2025-05-19 14:15:15','2025-05-19 14:15:15'),(16,24,NULL,2,'Mensaje de Owner','2025-05-19 14:19:23','2025-05-19 14:19:23'),(17,28,NULL,2,'Me encanta que se pudo hacer esto','2025-05-20 11:28:09','2025-05-20 11:28:09');
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `package`
--

DROP TABLE IF EXISTS `package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `package` (
  `packageID` int NOT NULL AUTO_INCREMENT,
  `client_id` int DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','In Transit','Delivered') DEFAULT 'Pending',
  `warehouseID` int DEFAULT NULL,
  `fleetID` int DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `delivery_confirmation` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`packageID`),
  KEY `client_id` (`client_id`),
  KEY `warehouseID` (`warehouseID`),
  KEY `fleetID` (`fleetID`),
  CONSTRAINT `package_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`) ON DELETE CASCADE,
  CONSTRAINT `package_ibfk_2` FOREIGN KEY (`warehouseID`) REFERENCES `warehouse` (`warehouseID`) ON DELETE SET NULL,
  CONSTRAINT `package_ibfk_3` FOREIGN KEY (`fleetID`) REFERENCES `fleet` (`fleetID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `package`
--

LOCK TABLES `package` WRITE;
/*!40000 ALTER TABLE `package` DISABLE KEYS */;
INSERT INTO `package` VALUES (52,3,12.50,'',1,NULL,'2025-05-01 10:00:00',0),(53,4,8.20,'In Transit',2,NULL,'2025-05-02 11:30:00',0),(54,5,5.60,'Delivered',3,NULL,'2025-05-03 14:45:00',1),(55,6,15.00,'',4,NULL,'2025-05-04 09:20:00',0),(56,7,22.10,'Delivered',5,NULL,'2025-05-05 13:10:00',1),(57,8,7.80,'In Transit',1,NULL,'2025-05-06 16:00:00',0),(58,9,13.30,'',2,NULL,'2025-05-07 08:00:00',0),(59,10,18.90,'Delivered',3,NULL,'2025-05-08 15:25:00',1),(60,11,9.50,'In Transit',4,NULL,'2025-05-09 10:10:00',0),(61,12,6.00,'',5,NULL,'2025-05-10 12:40:00',0),(62,13,14.20,'Delivered',1,NULL,'2025-05-11 11:30:00',1),(63,14,3.90,'In Transit',2,NULL,'2025-05-12 17:00:00',0),(64,15,27.00,'',3,NULL,'2025-05-13 09:10:00',0),(65,16,11.40,'Delivered',4,NULL,'2025-05-14 14:00:00',1),(66,17,10.60,'In Transit',5,NULL,'2025-05-15 10:30:00',0),(67,18,20.00,'',1,NULL,'2025-05-16 13:50:00',0),(68,19,6.60,'Delivered',2,NULL,'2025-05-17 15:30:00',1),(69,20,17.30,'In Transit',3,NULL,'2025-05-18 11:20:00',0),(70,21,9.90,'',4,NULL,'2025-05-19 16:10:00',0),(71,22,5.10,'Delivered',5,NULL,'2025-05-20 14:00:00',1),(72,23,8.80,'In Transit',1,NULL,'2025-05-21 09:40:00',0),(73,24,16.70,'',2,NULL,'2025-05-22 12:00:00',0),(74,26,4.40,'Delivered',3,NULL,'2025-05-23 13:30:00',1),(75,3,11.10,'In Transit',4,25,'2025-05-24 08:45:00',0),(76,3,23.40,'Delivered',5,NULL,'2025-05-25 11:15:00',1);
/*!40000 ALTER TABLE `package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rol` (
  `rolID` int NOT NULL AUTO_INCREMENT,
  `role_name` enum('owner','finance','people','IT_Dev','IT_Tech','customer_service','warehouse','driver','client') NOT NULL,
  `can_view_any_ticket` tinyint(1) NOT NULL DEFAULT '0',
  `can_view_own_ticket` tinyint(1) NOT NULL DEFAULT '0',
  `can_create_ticket` tinyint(1) NOT NULL DEFAULT '0',
  `can_update_ticket` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete_ticket` tinyint(1) NOT NULL DEFAULT '0',
  `can_add_message` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rolID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'owner',1,1,1,1,1,1),(2,'finance',1,0,0,1,0,1),(3,'people',1,0,0,1,0,1),(4,'IT_Dev',1,1,1,1,1,1),(5,'IT_Tech',1,0,0,1,0,1),(6,'customer_service',1,0,0,1,0,1),(7,'warehouse',0,0,0,0,0,0),(8,'driver',0,0,0,0,0,0),(9,'client',0,1,1,0,0,1);
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `route`
--

DROP TABLE IF EXISTS `route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `route` (
  `route_id` int NOT NULL AUTO_INCREMENT,
  `driver_id` int DEFAULT NULL,
  `origin` varchar(100) DEFAULT NULL,
  `destination` varchar(100) DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`route_id`),
  KEY `driver_id` (`driver_id`),
  CONSTRAINT `route_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `employee` (`employee_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `route`
--

LOCK TABLES `route` WRITE;
/*!40000 ALTER TABLE `route` DISABLE KEYS */;
INSERT INTO `route` VALUES (1,NULL,'Barcelona','Valencia','2025-05-03','Pendiente'),(2,NULL,'Valencia','Madrid','2025-05-04','En curso'),(3,NULL,'Madrid','Sevilla','2025-05-05','Entregado');
/*!40000 ALTER TABLE `route` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_logs`
--

DROP TABLE IF EXISTS `system_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_logs` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `log_type` varchar(50) DEFAULT NULL,
  `message` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_logs`
--

LOCK TABLES `system_logs` WRITE;
/*!40000 ALTER TABLE `system_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket` (
  `ticketID` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `employee_id` int DEFAULT NULL,
  `packageID` int DEFAULT NULL,
  `status` enum('Resolved','Pending','In Progress') NOT NULL DEFAULT 'Pending',
  `categoria` enum('Incidencias','Reclamaciones','Consulta','Info') NOT NULL DEFAULT 'Incidencias',
  `message` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ticketID`),
  KEY `client_id` (`client_id`),
  KEY `employee_id` (`employee_id`),
  KEY `packageID` (`packageID`),
  KEY `idx_ticket_pending_created` (`status`,`created_at`),
  KEY `idx_ticket_upd_desc` (`status`,`updated_at`),
  KEY `idx_ticket_client_inprog` (`status`,`updated_at`),
  KEY `idx_ticket_client_pending` (`status`,`created_at`),
  KEY `idx_ticket_client_resolved` (`status`,`updated_at`),
  CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`) ON DELETE CASCADE,
  CONSTRAINT `ticket_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE,
  CONSTRAINT `ticket_ibfk_3` FOREIGN KEY (`packageID`) REFERENCES `package` (`packageID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket`
--

LOCK TABLES `ticket` WRITE;
/*!40000 ALTER TABLE `ticket` DISABLE KEYS */;
INSERT INTO `ticket` VALUES (11,3,13,52,'In Progress','Incidencias',NULL,'2025-04-29 18:19:24','2025-05-15 13:42:47'),(12,4,14,53,'In Progress','Incidencias',NULL,'2025-04-30 18:19:24','2025-05-15 13:42:47'),(13,5,15,54,'In Progress','Incidencias',NULL,'2025-04-24 18:19:24','2025-05-15 13:42:47'),(14,6,16,55,'In Progress','Incidencias',NULL,'2025-05-02 18:19:24','2025-05-15 13:42:47'),(15,7,NULL,56,'Resolved','Incidencias',NULL,'2025-05-03 18:19:24','2025-05-15 13:42:47'),(16,8,17,NULL,'Resolved','Incidencias',NULL,'2025-04-27 18:19:24','2025-05-15 13:42:47'),(17,9,18,57,'Resolved','Incidencias',NULL,'2025-05-01 18:19:24','2025-05-15 13:42:47'),(18,10,19,NULL,'In Progress','Info','actualizado','2025-04-28 18:19:24','2025-05-20 11:41:04'),(19,11,30,58,'In Progress','Incidencias','zxdxzcxzc','2025-04-19 18:19:24','2025-05-15 14:41:15'),(20,12,20,59,'In Progress','Incidencias','sdsasdasda','2025-04-22 18:19:24','2025-05-15 14:43:38'),(23,31,27,NULL,'In Progress','Consulta','primer M','2025-05-07 07:34:02','2025-05-20 11:40:50'),(24,18,42,52,'In Progress','Incidencias','Esta asignado','2025-05-15 13:33:22','2025-05-19 14:19:28'),(25,7,NULL,NULL,'Pending','Consulta','Texto inicial de la consulta','2025-05-15 14:00:36','2025-05-20 12:22:23'),(26,7,NULL,NULL,'Pending','Consulta','Texto inicial de la consulta','2025-05-15 14:15:03','2025-05-15 17:42:14'),(27,7,30,52,'In Progress','Incidencias','xczzxcxzczxc','2025-05-15 14:23:23','2025-05-15 14:26:29'),(28,35,40,65,'In Progress','Reclamaciones','Primer paquete','2025-05-15 18:15:44','2025-05-20 11:28:09'),(31,37,NULL,NULL,'Pending','Incidencias','ff','2025-05-20 12:25:04','2025-05-20 12:25:04'),(32,37,NULL,NULL,'Pending','Info','Hola memoria','2025-05-21 19:13:07','2025-05-21 19:13:07');
/*!40000 ALTER TABLE `ticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vw_tickets_clientes`
--

DROP TABLE IF EXISTS `vw_tickets_clientes`;
/*!50001 DROP VIEW IF EXISTS `vw_tickets_clientes`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_tickets_clientes` AS SELECT
 1 AS `ticketID`,
  1 AS `client_id`,
  1 AS `employee_id`,
  1 AS `packageID`,
  1 AS `status`,
  1 AS `categoria`,
  1 AS `message`,
  1 AS `created_at`,
  1 AS `updated_at` */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vw_tickets_empresas`
--

DROP TABLE IF EXISTS `vw_tickets_empresas`;
/*!50001 DROP VIEW IF EXISTS `vw_tickets_empresas`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_tickets_empresas` AS SELECT
 1 AS `ticketID`,
  1 AS `client_id`,
  1 AS `employee_id`,
  1 AS `packageID`,
  1 AS `status`,
  1 AS `categoria`,
  1 AS `message`,
  1 AS `created_at`,
  1 AS `updated_at` */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `warehouse`
--

DROP TABLE IF EXISTS `warehouse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warehouse` (
  `warehouseID` int NOT NULL AUTO_INCREMENT,
  `townID` int DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`warehouseID`),
  KEY `townID` (`townID`),
  CONSTRAINT `warehouse_ibfk_1` FOREIGN KEY (`townID`) REFERENCES `location` (`townID`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouse`
--

LOCK TABLES `warehouse` WRITE;
/*!40000 ALTER TABLE `warehouse` DISABLE KEYS */;
INSERT INTO `warehouse` VALUES (1,1,'Almacén Central Barcelona'),(2,1,'Depósito Zona Franca'),(3,1,'Centro Logístico Sants'),(4,1,'Almacén Norte Barcelona'),(5,1,'Plataforma El Prat');
/*!40000 ALTER TABLE `warehouse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'incargo365'
--

--
-- Final view structure for view `vw_tickets_clientes`
--

/*!50001 DROP VIEW IF EXISTS `vw_tickets_clientes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`admin`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_tickets_clientes` AS select `ticket`.`ticketID` AS `ticketID`,`ticket`.`client_id` AS `client_id`,`ticket`.`employee_id` AS `employee_id`,`ticket`.`packageID` AS `packageID`,`ticket`.`status` AS `status`,`ticket`.`categoria` AS `categoria`,`ticket`.`message` AS `message`,`ticket`.`created_at` AS `created_at`,`ticket`.`updated_at` AS `updated_at` from `ticket` order by (case `ticket`.`status` when 'In Progress' then 1 when 'Pending' then 2 when 'Resolved' then 3 else 4 end),(case when (`ticket`.`status` = 'In Progress') then `ticket`.`updated_at` end) desc,(case when (`ticket`.`status` = 'Pending') then `ticket`.`created_at` end),(case when (`ticket`.`status` = 'Resolved') then `ticket`.`updated_at` end) desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vw_tickets_empresas`
--

/*!50001 DROP VIEW IF EXISTS `vw_tickets_empresas`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`admin`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_tickets_empresas` AS select `ticket`.`ticketID` AS `ticketID`,`ticket`.`client_id` AS `client_id`,`ticket`.`employee_id` AS `employee_id`,`ticket`.`packageID` AS `packageID`,`ticket`.`status` AS `status`,`ticket`.`categoria` AS `categoria`,`ticket`.`message` AS `message`,`ticket`.`created_at` AS `created_at`,`ticket`.`updated_at` AS `updated_at` from `ticket` order by (case `ticket`.`status` when 'Pending' then 1 when 'In Progress' then 2 when 'Resolved' then 3 else 4 end),(case when (`ticket`.`status` = 'Pending') then `ticket`.`created_at` end),(case when (`ticket`.`status` in ('In Progress','Resolved')) then `ticket`.`updated_at` end) desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-24  8:52:00
