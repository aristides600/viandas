-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: fraser
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

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
-- Table structure for table `colores`
--

DROP TABLE IF EXISTS `colores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `colores` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID del color',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del color',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colores`
--

LOCK TABLES `colores` WRITE;
/*!40000 ALTER TABLE `colores` DISABLE KEYS */;
INSERT INTO `colores` VALUES (1,'BLANCO'),(2,'ROJO'),(3,'AZUL'),(4,'AMARILLO');
/*!40000 ALTER TABLE `colores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentos`
--

DROP TABLE IF EXISTS `documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehiculo_id` int(11) DEFAULT NULL,
  `tipo_id` int(11) unsigned DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `observacion` varchar(255) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `recordatorio_enviado` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `camion_id` (`vehiculo_id`),
  KEY `fk_documento1` (`tipo_id`),
  KEY `fk_documento3` (`usuario_id`),
  CONSTRAINT `fk_documento1` FOREIGN KEY (`tipo_id`) REFERENCES `tipos` (`id`),
  CONSTRAINT `fk_documento2` FOREIGN KEY (`vehiculo_id`) REFERENCES `vehiculos` (`id`),
  CONSTRAINT `fk_documento3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentos`
--

LOCK TABLES `documentos` WRITE;
/*!40000 ALTER TABLE `documentos` DISABLE KEYS */;
INSERT INTO `documentos` VALUES (1,1,1,'2024-08-01 08:55:25','2024-07-26','tiempo',1,0,0),(2,1,2,'2024-08-01 08:55:33','2024-09-03','nada nada',1,0,0),(3,2,1,'2024-08-02 08:55:46','2024-07-31','ads volvooooooooo',1,0,0),(4,1,1,'2024-08-01 08:56:58','2024-07-26','hola',1,0,0),(5,2,1,'2024-08-01 08:57:02','2024-07-31','nada bien',1,0,0),(6,1,2,'2024-08-01 08:57:06','2024-08-30','sd',1,0,0),(7,1,2,'2024-08-01 08:57:09','2024-07-30','nada',1,0,0),(12,3,1,'2024-08-29 11:26:45','2024-08-30','ddd',1,0,0),(13,4,1,'2024-08-29 11:30:55','2024-08-31','tal vez',1,0,0),(14,3,2,'2024-08-30 10:32:24','2024-08-31','holaaaaaa',1,0,0),(15,7,1,'2024-09-06 09:18:12','2024-09-06','tal vez',1,0,0),(16,1,1,'2024-09-09 14:07:28','2024-09-10','sa',1,0,0),(17,8,2,'2024-09-09 23:00:47','2024-09-10','BUENISIMO',1,0,0),(18,4,2,'2024-09-09 23:03:27','2024-09-28','sa',1,1,1),(20,4,1,'2024-09-09 23:10:21','2024-10-29','asa',1,1,0),(21,3,1,'2024-09-09 23:11:37','2024-09-19','ds',1,1,1),(22,3,2,'2024-09-09 23:11:50','2024-09-18','s',1,0,1),(23,1,2,'2024-09-21 17:09:31','2024-09-22','jmjnj',1,0,0),(24,2,1,'2024-10-11 16:21:40','2024-10-11','ds',1,0,0);
/*!40000 ALTER TABLE `documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marcas`
--

DROP TABLE IF EXISTS `marcas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marcas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID de la marca',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre de la marca',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marcas`
--

LOCK TABLES `marcas` WRITE;
/*!40000 ALTER TABLE `marcas` DISABLE KEYS */;
INSERT INTO `marcas` VALUES (1,'VOLVO'),(2,'RENAULT'),(3,'SALTO'),(4,'FIAT');
/*!40000 ALTER TABLE `marcas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modelos`
--

DROP TABLE IF EXISTS `modelos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modelos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID de la modelo',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre de la modelo',
  `marca_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modelos`
--

LOCK TABLES `modelos` WRITE;
/*!40000 ALTER TABLE `modelos` DISABLE KEYS */;
INSERT INTO `modelos` VALUES (1,'a1212',1),(2,'B200',1),(3,'1114',2),(4,'8000',1),(5,'a400',2),(6,'abierto',3),(7,'CRONOS',4);
/*!40000 ALTER TABLE `modelos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del permiso',
  `rol_id` int(11) NOT NULL COMMENT 'Identificador del rol asociado',
  `modulo` varchar(100) NOT NULL COMMENT 'Módulo al que se aplica el permiso',
  `permiso` int(1) NOT NULL COMMENT 'Permiso (1=permitido, 0=no permitido)',
  PRIMARY KEY (`id`),
  KEY `fk_permiso1` (`rol_id`),
  CONSTRAINT `fk_permiso1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES (1,1,'documentos',1),(2,1,'vehiculos',1),(3,1,'usuarios',1),(4,2,'documentos',1),(5,2,'vehiculos',1),(6,2,'usuarios',0);
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del rol',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del rol',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrador'),(2,'Operador');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipos`
--

DROP TABLE IF EXISTS `tipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del tipo',
  `nombre` varchar(20) NOT NULL COMMENT 'Nombre del tipo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos`
--

LOCK TABLES `tipos` WRITE;
/*!40000 ALTER TABLE `tipos` DISABLE KEYS */;
INSERT INTO `tipos` VALUES (1,'RTO'),(2,'SEGURO');
/*!40000 ALTER TABLE `tipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tramites_documentos`
--

DROP TABLE IF EXISTS `tramites_documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tramites_documentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `documento_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_tramite` datetime NOT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tramite_documento1` (`documento_id`),
  KEY `fk_tramite_documento` (`usuario_id`),
  CONSTRAINT `fk_tramite_documento` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_tramite_documento1` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tramites_documentos`
--

LOCK TABLES `tramites_documentos` WRITE;
/*!40000 ALTER TABLE `tramites_documentos` DISABLE KEYS */;
INSERT INTO `tramites_documentos` VALUES (1,7,1,'2024-09-04 02:11:12','asda'),(2,7,1,'2024-09-04 02:32:46','afsdf'),(3,2,1,'2024-09-04 02:33:13','fasd'),(4,7,1,'2024-09-04 02:35:02','sa'),(5,6,2,'2024-09-04 02:37:35','fraser'),(6,5,1,'2024-09-05 17:03:12','asd'),(7,15,1,'2024-09-06 14:18:58','tal vez'),(8,1,1,'2024-09-10 02:29:08','hg'),(9,6,1,'2024-09-10 02:29:19','h'),(10,2,1,'2024-09-10 02:29:27','g'),(11,1,1,'2024-09-10 02:29:32','g'),(12,1,1,'2024-09-10 02:29:39','g'),(13,3,1,'2024-09-10 02:29:47','t'),(14,6,1,'2024-09-10 02:29:51','t'),(15,12,1,'2024-09-10 02:29:55','t'),(16,2,1,'2024-09-10 02:30:00','t'),(17,1,1,'2024-09-10 02:31:29','s'),(18,4,1,'2024-09-10 02:31:44','s'),(19,16,1,'2024-09-10 02:34:19','ok'),(20,1,1,'2024-09-10 02:49:16','ok'),(21,4,1,'2024-09-10 02:49:30','e'),(22,4,1,'2024-09-10 02:49:34','e'),(23,7,1,'2024-09-10 02:49:38','e'),(24,12,1,'2024-09-10 02:49:47','e'),(25,14,1,'2024-09-10 02:49:51','e'),(26,5,1,'2024-09-10 02:49:55','e'),(27,16,1,'2024-09-10 02:50:10','d'),(28,5,1,'2024-09-10 02:50:15','d'),(29,3,1,'2024-09-10 02:50:30','d'),(30,1,1,'2024-09-10 02:50:43','ds'),(31,4,1,'2024-09-10 02:50:59','s'),(32,1,1,'2024-09-10 03:21:07','s'),(33,5,1,'2024-09-10 03:21:55','siempre'),(34,3,1,'2024-09-10 03:09:30','tito'),(35,1,1,'2024-09-10 03:28:03','sabar'),(36,1,1,'2024-09-10 03:31:28','pooc'),(37,1,1,'2024-09-09 22:35:55','jujuy'),(38,13,1,'2024-09-09 23:03:05','sa'),(39,17,1,'2024-09-09 23:03:43','se'),(40,22,1,'2024-09-13 08:49:48','ds'),(41,22,1,'2024-09-18 11:46:19','OK'),(42,23,1,'2024-09-21 17:10:09','ok'),(43,24,1,'2024-10-11 16:21:56','as');
/*!40000 ALTER TABLE `tramites_documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del usuario',
  `dni` int(8) NOT NULL COMMENT 'Documento Nacional de Identidad del usuario',
  `apellido` varchar(255) NOT NULL COMMENT 'Apellido del usuario',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del usuario',
  `usuario` varchar(100) NOT NULL COMMENT 'Nombre de usuario para inicio de sesión',
  `clave` varchar(100) NOT NULL COMMENT 'Clave de acceso del usuario',
  `rol_id` int(11) NOT NULL COMMENT 'Identificador del rol del usuario',
  `estado` tinyint(1) DEFAULT NULL COMMENT 'Estado del usuario (1=activo, 0=inactivo)',
  PRIMARY KEY (`id`),
  KEY `fk_usuario1` (`rol_id`),
  CONSTRAINT `fk_usuario1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,26629619,'TITO','ALEJANDRO','ATITO','$2y$10$0pfBpIDcTUIWPDJI/EgucOEAwZXfR1ddZ3xPng1/6dHS1kltQc54a',1,1),(2,26629627,'FRASER','ROBERTO','fraser','$2y$10$9bzoH2PKJ7RIgzFSK.RwQezf.wh/qR5Ao0DsiL0nmD/J3DcTbPQxS',2,1),(3,26629624,'MANSILLA','JORGE','jorge','$2y$10$G5yCLhSe84QRbWTM3VRf/uRh9cVVbxHCE3Y9rYNUXod0osvH7d0uu',2,1),(4,26629624,'MANSILLA','JORGE','jorge','$2y$10$WZZdV94stmZa/o6P0ofynevWR80Ctecp.gG2eP25zfBog3YWoxor.',2,1),(5,123456,'SANDRO','SANDRA','sandro','$2y$10$G.T2JaEFbof7DlQ3hOpSx.ajl6niaF0GOpSC/yxOPVGtAM5tzIDhS',1,1),(6,26,'SANDRO','SANDRO','melisa','$2y$10$zAUukBTUpN2KXJ0azTfKCuJ7mRgY0KzbI7xcDI5.NLv4oFeh9WLKm',2,1),(7,56,'GRONE','GRONE','gronE','$2y$10$XllMTQvBicWy5hSzvxHwU.hvrQ7sAdrkJfWalUTFNE7f5R/mOi.n2',2,1),(8,0,'S','S','pepe','$2y$10$zO6RH8EGftOKXZmMnBtb.OHb4AC8lo/sY/R8mVB.ECxCmz1htu3TK',2,1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehiculos`
--

DROP TABLE IF EXISTS `vehiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patente` varchar(10) NOT NULL,
  `fecha_alta` date NOT NULL,
  `marca_id` int(11) unsigned NOT NULL,
  `color_id` int(11) unsigned NOT NULL,
  `motor` varchar(50) NOT NULL,
  `modelo_id` int(11) unsigned NOT NULL,
  `anio` int(11) NOT NULL,
  `corroceria` varchar(50) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_vehiculo1` (`marca_id`),
  KEY `fk_vehiculo2` (`modelo_id`),
  KEY `fk_vehiculo3` (`color_id`),
  CONSTRAINT `fk_vehiculo1` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`),
  CONSTRAINT `fk_vehiculo2` FOREIGN KEY (`modelo_id`) REFERENCES `modelos` (`id`),
  CONSTRAINT `fk_vehiculo3` FOREIGN KEY (`color_id`) REFERENCES `colores` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehiculos`
--

LOCK TABLES `vehiculos` WRITE;
/*!40000 ALTER TABLE `vehiculos` DISABLE KEYS */;
INSERT INTO `vehiculos` VALUES (1,'aa111aa','2024-09-02',1,1,'a',1,2022,'a',1),(2,'bb111bb','2024-09-02',1,2,'sa',2,2024,'dsa',1),(3,'aa111ax','2024-09-02',1,1,'sad321as',1,2023,'sdf3232',1),(4,'aa111ab','2024-09-02',1,1,'sad321as',1,2023,'sdf3232',1),(5,'aa111az','2024-09-02',1,2,'asd',1,2023,'s',1),(6,'gg555BB','2024-09-02',1,1,'1213',1,2024,'a',1),(7,'aa111ak','2024-09-02',1,1,'s',1,2024,'sdf3232',1),(8,'aa111ah','2024-09-02',1,1,'1213as',1,2024,'sdf3232',1);
/*!40000 ALTER TABLE `vehiculos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-13  8:36:10
