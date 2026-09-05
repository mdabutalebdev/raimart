-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: raimart
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT 'hero',
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `badge_text` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'hero',NULL,NULL,NULL,'banners/mc7zsk44FAVjOs6Av5nb4zZR5KNPsfaLtYQOn4l1.webp','#',NULL,0,1,'2026-07-12 04:34:55','2026-08-30 20:40:27'),(6,'promo',NULL,NULL,NULL,'banners/m9re7QLLmtcBiR7OufCwihdyrlVqypxBYGPWMvju.webp','#',NULL,0,1,'2026-07-12 04:45:58','2026-08-30 20:40:27'),(8,'mid',NULL,NULL,NULL,'banners/mc7zsk44FAVjOs6Av5nb4zZR5KNPsfaLtYQOn4l1.png','/shop',NULL,0,1,'2026-08-15 17:08:22','2026-08-15 17:08:22'),(9,'promo',NULL,NULL,NULL,'banners/X0pOfBuI5K9wIEVNKBLLxNpQhbMOz3RjTDFvBPHp.webp',NULL,NULL,0,1,'2026-08-30 17:31:37','2026-08-30 20:40:27'),(10,'hero',NULL,NULL,NULL,'banners/7cOXoPgECO4JUjCRV3YDZEJ3Wf02QkJF6dKkoXFB.webp',NULL,NULL,0,1,'2026-08-30 18:05:13','2026-08-30 20:40:27');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `show_on_home` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,'Dove','dove','brands/Hmhvmt5NF3KCSkCNejMzrA4r8ZfKCgVBKHcnCRta.webp',0,1,1,'2026-08-02 14:17:33','2026-08-30 20:40:26'),(2,'Vaseline','vaseline','brands/orZkq4LfXMjdXC4Isv4cMuko2cf1lbMIasih6zEd.webp',1,1,1,'2026-08-02 14:17:33','2026-08-30 20:40:26'),(3,'LAIKOU','laikou','brands/JJP63YtDXURf4zKPE43iAxjBeAwYTNqghCgxt97t.webp',2,1,1,'2026-08-02 14:17:33','2026-08-30 20:40:26'),(4,'Lux','lux','brands/fveefIhYULPMemXR8ke9IC17lJNkPOjSmjYmLca5.webp',3,1,1,'2026-08-02 14:17:33','2026-08-30 20:40:27'),(5,'Nivea','nivea','brands/dN2aPB0X3aqCcQ7S3Eeq44CZ7qi1fglJp0lFWiJV.webp',4,1,1,'2026-08-02 14:17:33','2026-08-30 20:40:27'),(6,'Sunsilk','sunsilk',NULL,5,1,1,'2026-08-02 14:17:33','2026-08-02 14:17:33');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('raimart-cache-home:banners','a:2:{i:0;O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:2:{i:0;O:17:\"App\\Models\\Banner\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:7:\"banners\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:1;s:4:\"type\";s:4:\"hero\";s:5:\"title\";N;s:8:\"subtitle\";N;s:10:\"badge_text\";N;s:5:\"image\";s:53:\"banners/mc7zsk44FAVjOs6Av5nb4zZR5KNPsfaLtYQOn4l1.webp\";s:4:\"link\";s:1:\"#\";s:11:\"button_text\";N;s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:27\";}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:1;s:4:\"type\";s:4:\"hero\";s:5:\"title\";N;s:8:\"subtitle\";N;s:10:\"badge_text\";N;s:5:\"image\";s:53:\"banners/mc7zsk44FAVjOs6Av5nb4zZR5KNPsfaLtYQOn4l1.webp\";s:4:\"link\";s:1:\"#\";s:11:\"button_text\";N;s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:27\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:4:\"type\";i:1;s:5:\"title\";i:2;s:8:\"subtitle\";i:3;s:10:\"badge_text\";i:4;s:5:\"image\";i:5;s:4:\"link\";i:6;s:11:\"button_text\";i:7;s:10:\"sort_order\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:17:\"App\\Models\\Banner\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:7:\"banners\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:10;s:4:\"type\";s:4:\"hero\";s:5:\"title\";N;s:8:\"subtitle\";N;s:10:\"badge_text\";N;s:5:\"image\";s:53:\"banners/7cOXoPgECO4JUjCRV3YDZEJ3Wf02QkJF6dKkoXFB.webp\";s:4:\"link\";N;s:11:\"button_text\";N;s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-08-31 00:05:13\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:27\";}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:10;s:4:\"type\";s:4:\"hero\";s:5:\"title\";N;s:8:\"subtitle\";N;s:10:\"badge_text\";N;s:5:\"image\";s:53:\"banners/7cOXoPgECO4JUjCRV3YDZEJ3Wf02QkJF6dKkoXFB.webp\";s:4:\"link\";N;s:11:\"button_text\";N;s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-08-31 00:05:13\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:27\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:4:\"type\";i:1;s:5:\"title\";i:2;s:8:\"subtitle\";i:3;s:10:\"badge_text\";i:4;s:5:\"image\";i:5;s:4:\"link\";i:6;s:11:\"button_text\";i:7;s:10:\"sort_order\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:2:{i:0;O:17:\"App\\Models\\Banner\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:7:\"banners\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:6;s:4:\"type\";s:5:\"promo\";s:5:\"title\";N;s:8:\"subtitle\";N;s:10:\"badge_text\";N;s:5:\"image\";s:53:\"banners/m9re7QLLmtcBiR7OufCwihdyrlVqypxBYGPWMvju.webp\";s:4:\"link\";s:1:\"#\";s:11:\"button_text\";N;s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:45:58\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:27\";}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:6;s:4:\"type\";s:5:\"promo\";s:5:\"title\";N;s:8:\"subtitle\";N;s:10:\"badge_text\";N;s:5:\"image\";s:53:\"banners/m9re7QLLmtcBiR7OufCwihdyrlVqypxBYGPWMvju.webp\";s:4:\"link\";s:1:\"#\";s:11:\"button_text\";N;s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:45:58\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:27\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:4:\"type\";i:1;s:5:\"title\";i:2;s:8:\"subtitle\";i:3;s:10:\"badge_text\";i:4;s:5:\"image\";i:5;s:4:\"link\";i:6;s:11:\"button_text\";i:7;s:10:\"sort_order\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:17:\"App\\Models\\Banner\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:7:\"banners\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:9;s:4:\"type\";s:5:\"promo\";s:5:\"title\";N;s:8:\"subtitle\";N;s:10:\"badge_text\";N;s:5:\"image\";s:53:\"banners/X0pOfBuI5K9wIEVNKBLLxNpQhbMOz3RjTDFvBPHp.webp\";s:4:\"link\";N;s:11:\"button_text\";N;s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-08-30 23:31:37\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:27\";}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:9;s:4:\"type\";s:5:\"promo\";s:5:\"title\";N;s:8:\"subtitle\";N;s:10:\"badge_text\";N;s:5:\"image\";s:53:\"banners/X0pOfBuI5K9wIEVNKBLLxNpQhbMOz3RjTDFvBPHp.webp\";s:4:\"link\";N;s:11:\"button_text\";N;s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-08-30 23:31:37\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:27\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:4:\"type\";i:1;s:5:\"title\";i:2;s:8:\"subtitle\";i:3;s:10:\"badge_text\";i:4;s:5:\"image\";i:5;s:4:\"link\";i:6;s:11:\"button_text\";i:7;s:10:\"sort_order\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}',1788165714),('raimart-cache-home:categories','O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:8:{i:0;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:1;s:9:\"parent_id\";N;s:4:\"name\";s:7:\"Shampoo\";s:4:\"slug\";s:7:\"shampoo\";s:5:\"image\";s:56:\"categories/EJYt3lZ49rEAM3Ut5j1g4Lwh2htMln1JGvsM7RP0.webp\";s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:14:\"products_total\";i:2;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:1;s:9:\"parent_id\";N;s:4:\"name\";s:7:\"Shampoo\";s:4:\"slug\";s:7:\"shampoo\";s:5:\"image\";s:56:\"categories/EJYt3lZ49rEAM3Ut5j1g4Lwh2htMln1JGvsM7RP0.webp\";s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:8:\"children\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:2:{i:0;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:2;s:9:\"parent_id\";i:1;s:4:\"name\";s:4:\"Dove\";s:4:\"slug\";s:4:\"dove\";s:5:\"image\";s:56:\"categories/wNl925Unc98twZOR0zisexZps2KJseNGLpJo368Z.webp\";s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:0;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:2;s:9:\"parent_id\";i:1;s:4:\"name\";s:4:\"Dove\";s:4:\"slug\";s:4:\"dove\";s:5:\"image\";s:56:\"categories/wNl925Unc98twZOR0zisexZps2KJseNGLpJo368Z.webp\";s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:0;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:5:\"image\";i:4;s:10:\"sort_order\";i:5;s:9:\"is_active\";i:6;s:12:\"show_on_home\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:39;s:9:\"parent_id\";i:1;s:4:\"name\";s:14:\"Loreal shampoo\";s:4:\"slug\";s:14:\"loreal-shampoo\";s:5:\"image\";N;s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:0;s:10:\"created_at\";s:19:\"2026-07-13 20:53:53\";s:10:\"updated_at\";s:19:\"2026-07-13 20:53:53\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:39;s:9:\"parent_id\";i:1;s:4:\"name\";s:14:\"Loreal shampoo\";s:4:\"slug\";s:14:\"loreal-shampoo\";s:5:\"image\";N;s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:0;s:10:\"created_at\";s:19:\"2026-07-13 20:53:53\";s:10:\"updated_at\";s:19:\"2026-07-13 20:53:53\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:5:\"image\";i:4;s:10:\"sort_order\";i:5;s:9:\"is_active\";i:6;s:12:\"show_on_home\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:5:\"image\";i:4;s:10:\"sort_order\";i:5;s:9:\"is_active\";i:6;s:12:\"show_on_home\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:34;s:9:\"parent_id\";N;s:4:\"name\";s:12:\"Toys & Games\";s:4:\"slug\";s:10:\"toys-games\";s:5:\"image\";s:56:\"categories/5l97EsMynTwxS4seyP69ryIOhxUqzLoUvEsdbTLv.webp\";s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:14:\"products_total\";i:0;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:34;s:9:\"parent_id\";N;s:4:\"name\";s:12:\"Toys & Games\";s:4:\"slug\";s:10:\"toys-games\";s:5:\"image\";s:56:\"categories/5l97EsMynTwxS4seyP69ryIOhxUqzLoUvEsdbTLv.webp\";s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:8:\"children\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:5:\"image\";i:4;s:10:\"sort_order\";i:5;s:9:\"is_active\";i:6;s:12:\"show_on_home\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:41;s:9:\"parent_id\";N;s:4:\"name\";s:15:\"Baby Collection\";s:4:\"slug\";s:15:\"baby-collection\";s:5:\"image\";s:56:\"categories/ESAdzT8bibktMzBMITjxOrezFrb0trb1iT6ASdNO.webp\";s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-08-31 01:09:21\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:14:\"products_total\";i:0;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:41;s:9:\"parent_id\";N;s:4:\"name\";s:15:\"Baby Collection\";s:4:\"slug\";s:15:\"baby-collection\";s:5:\"image\";s:56:\"categories/ESAdzT8bibktMzBMITjxOrezFrb0trb1iT6ASdNO.webp\";s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-08-31 01:09:21\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:8:\"children\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:5:\"image\";i:4;s:10:\"sort_order\";i:5;s:9:\"is_active\";i:6;s:12:\"show_on_home\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:42;s:9:\"parent_id\";N;s:4:\"name\";s:5:\"women\";s:4:\"slug\";s:5:\"women\";s:5:\"image\";s:56:\"categories/GDNIZGSpgpHLejw2sVxpnjjwnKym6KU54SAGNxkf.webp\";s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-08-31 01:09:46\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:14:\"products_total\";i:0;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:42;s:9:\"parent_id\";N;s:4:\"name\";s:5:\"women\";s:4:\"slug\";s:5:\"women\";s:5:\"image\";s:56:\"categories/GDNIZGSpgpHLejw2sVxpnjjwnKym6KU54SAGNxkf.webp\";s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-08-31 01:09:46\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:8:\"children\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:5:\"image\";i:4;s:10:\"sort_order\";i:5;s:9:\"is_active\";i:6;s:12:\"show_on_home\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:38;s:9:\"parent_id\";N;s:4:\"name\";s:3:\"oil\";s:4:\"slug\";s:3:\"oil\";s:5:\"image\";s:56:\"categories/Ekg99MPfiEIcG6o3oDgbxHm7rRL18KoJLyIy7uIo.webp\";s:10:\"sort_order\";i:1;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-07-13 20:33:15\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:14:\"products_total\";i:0;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:38;s:9:\"parent_id\";N;s:4:\"name\";s:3:\"oil\";s:4:\"slug\";s:3:\"oil\";s:5:\"image\";s:56:\"categories/Ekg99MPfiEIcG6o3oDgbxHm7rRL18KoJLyIy7uIo.webp\";s:10:\"sort_order\";i:1;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-07-13 20:33:15\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:8:\"children\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:40;s:9:\"parent_id\";i:38;s:4:\"name\";s:10:\"Olives Oil\";s:4:\"slug\";s:10:\"olives-oil\";s:5:\"image\";N;s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:0;s:10:\"created_at\";s:19:\"2026-07-13 20:58:53\";s:10:\"updated_at\";s:19:\"2026-07-13 20:58:53\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:40;s:9:\"parent_id\";i:38;s:4:\"name\";s:10:\"Olives Oil\";s:4:\"slug\";s:10:\"olives-oil\";s:5:\"image\";N;s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:0;s:10:\"created_at\";s:19:\"2026-07-13 20:58:53\";s:10:\"updated_at\";s:19:\"2026-07-13 20:58:53\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:5:\"image\";i:4;s:10:\"sort_order\";i:5;s:9:\"is_active\";i:6;s:12:\"show_on_home\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:5:\"image\";i:4;s:10:\"sort_order\";i:5;s:9:\"is_active\";i:6;s:12:\"show_on_home\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:5;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:13;s:9:\"parent_id\";N;s:4:\"name\";s:13:\"Home & Living\";s:4:\"slug\";s:11:\"home-living\";s:5:\"image\";s:56:\"categories/5L36L2ms4dWV3vj835AZrcZcoSfRLVqcO7RQ9Uk5.webp\";s:10:\"sort_order\";i:2;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:14:\"products_total\";i:0;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:13;s:9:\"parent_id\";N;s:4:\"name\";s:13:\"Home & Living\";s:4:\"slug\";s:11:\"home-living\";s:5:\"image\";s:56:\"categories/5L36L2ms4dWV3vj835AZrcZcoSfRLVqcO7RQ9Uk5.webp\";s:10:\"sort_order\";i:2;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:8:\"children\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:5:\"image\";i:4;s:10:\"sort_order\";i:5;s:9:\"is_active\";i:6;s:12:\"show_on_home\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:6;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:18;s:9:\"parent_id\";N;s:4:\"name\";s:6:\"Beauty\";s:4:\"slug\";s:6:\"beauty\";s:5:\"image\";s:56:\"categories/HquMhrsw1C7T9OLCXw41OCKar2bdgMP9rbh1afZp.webp\";s:10:\"sort_order\";i:3;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:14:\"products_total\";i:1;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:18;s:9:\"parent_id\";N;s:4:\"name\";s:6:\"Beauty\";s:4:\"slug\";s:6:\"beauty\";s:5:\"image\";s:56:\"categories/HquMhrsw1C7T9OLCXw41OCKar2bdgMP9rbh1afZp.webp\";s:10:\"sort_order\";i:3;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:8:\"children\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:5:\"image\";i:4;s:10:\"sort_order\";i:5;s:9:\"is_active\";i:6;s:12:\"show_on_home\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:7;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:13:{s:2:\"id\";i:23;s:9:\"parent_id\";N;s:4:\"name\";s:9:\"Groceries\";s:4:\"slug\";s:9:\"groceries\";s:5:\"image\";s:56:\"categories/wYNvHEM36xjr3mFHxAuZq94airS0enkioxISHs6Y.webp\";s:10:\"sort_order\";i:4;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:14:\"products_total\";i:0;}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:23;s:9:\"parent_id\";N;s:4:\"name\";s:9:\"Groceries\";s:4:\"slug\";s:9:\"groceries\";s:5:\"image\";s:56:\"categories/wYNvHEM36xjr3mFHxAuZq94airS0enkioxISHs6Y.webp\";s:10:\"sort_order\";i:4;s:9:\"is_active\";i:1;s:12:\"show_on_home\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-08-31 02:40:26\";s:10:\"meta_title\";N;s:16:\"meta_description\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:8:\"children\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:7:{i:0;s:9:\"parent_id\";i:1;s:4:\"name\";i:2;s:4:\"slug\";i:3;s:5:\"image\";i:4;s:10:\"sort_order\";i:5;s:9:\"is_active\";i:6;s:12:\"show_on_home\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}',1788165714),('raimart-cache-home:testimonials','O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;O:22:\"App\\Models\\Testimonial\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:12:\"testimonials\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:1;s:5:\"phone\";N;s:5:\"email\";N;s:4:\"name\";s:13:\"Rakib Hossain\";s:8:\"location\";s:5:\"Dhaka\";s:6:\"avatar\";N;s:6:\"rating\";i:5;s:4:\"text\";s:90:\"Genuine products, fast delivery every single time. Raimart is now my go-to for everything.\";s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-07-12 10:34:55\";}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:1;s:5:\"phone\";N;s:5:\"email\";N;s:4:\"name\";s:13:\"Rakib Hossain\";s:8:\"location\";s:5:\"Dhaka\";s:6:\"avatar\";N;s:6:\"rating\";i:5;s:4:\"text\";s:90:\"Genuine products, fast delivery every single time. Raimart is now my go-to for everything.\";s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-07-12 10:34:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:4:\"name\";i:1;s:5:\"email\";i:2;s:5:\"phone\";i:3;s:8:\"location\";i:4;s:6:\"avatar\";i:5;s:6:\"rating\";i:6;s:4:\"text\";i:7;s:10:\"sort_order\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:22:\"App\\Models\\Testimonial\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:12:\"testimonials\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:5;s:5:\"phone\";s:11:\"01622243011\";s:5:\"email\";s:24:\"mdabutaleb.dev@gmail.com\";s:4:\"name\";s:17:\"Md Abu Taleb Khan\";s:8:\"location\";N;s:6:\"avatar\";N;s:6:\"rating\";i:5;s:4:\"text\";s:5:\"hello\";s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-07-14 08:25:12\";s:10:\"updated_at\";s:19:\"2026-07-14 08:31:56\";}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:5;s:5:\"phone\";s:11:\"01622243011\";s:5:\"email\";s:24:\"mdabutaleb.dev@gmail.com\";s:4:\"name\";s:17:\"Md Abu Taleb Khan\";s:8:\"location\";N;s:6:\"avatar\";N;s:6:\"rating\";i:5;s:4:\"text\";s:5:\"hello\";s:10:\"sort_order\";i:0;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-07-14 08:25:12\";s:10:\"updated_at\";s:19:\"2026-07-14 08:31:56\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:4:\"name\";i:1;s:5:\"email\";i:2;s:5:\"phone\";i:3;s:8:\"location\";i:4;s:6:\"avatar\";i:5;s:6:\"rating\";i:6;s:4:\"text\";i:7;s:10:\"sort_order\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:22:\"App\\Models\\Testimonial\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:12:\"testimonials\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:2;s:5:\"phone\";N;s:5:\"email\";N;s:4:\"name\";s:11:\"Sadia Islam\";s:8:\"location\";s:10:\"Chattogram\";s:6:\"avatar\";N;s:6:\"rating\";i:5;s:4:\"text\";s:82:\"Customer support helped me with a return within minutes. Really smooth experience.\";s:10:\"sort_order\";i:1;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-07-12 10:34:55\";}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:2;s:5:\"phone\";N;s:5:\"email\";N;s:4:\"name\";s:11:\"Sadia Islam\";s:8:\"location\";s:10:\"Chattogram\";s:6:\"avatar\";N;s:6:\"rating\";i:5;s:4:\"text\";s:82:\"Customer support helped me with a return within minutes. Really smooth experience.\";s:10:\"sort_order\";i:1;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-07-12 10:34:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:4:\"name\";i:1;s:5:\"email\";i:2;s:5:\"phone\";i:3;s:8:\"location\";i:4;s:6:\"avatar\";i:5;s:6:\"rating\";i:6;s:4:\"text\";i:7;s:10:\"sort_order\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:22:\"App\\Models\\Testimonial\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:12:\"testimonials\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:3;s:5:\"phone\";N;s:5:\"email\";N;s:4:\"name\";s:12:\"Tanvir Ahmed\";s:8:\"location\";s:6:\"Sylhet\";s:6:\"avatar\";N;s:6:\"rating\";i:4;s:4:\"text\";s:70:\"Great prices and the packaging quality is excellent. Highly recommend.\";s:10:\"sort_order\";i:2;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-07-12 10:34:55\";}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:3;s:5:\"phone\";N;s:5:\"email\";N;s:4:\"name\";s:12:\"Tanvir Ahmed\";s:8:\"location\";s:6:\"Sylhet\";s:6:\"avatar\";N;s:6:\"rating\";i:4;s:4:\"text\";s:70:\"Great prices and the packaging quality is excellent. Highly recommend.\";s:10:\"sort_order\";i:2;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-07-12 10:34:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:4:\"name\";i:1;s:5:\"email\";i:2;s:5:\"phone\";i:3;s:8:\"location\";i:4;s:6:\"avatar\";i:5;s:6:\"rating\";i:6;s:4:\"text\";i:7;s:10:\"sort_order\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:22:\"App\\Models\\Testimonial\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:12:\"testimonials\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:12:{s:2:\"id\";i:4;s:5:\"phone\";N;s:5:\"email\";N;s:4:\"name\";s:12:\"Nusrat Jahan\";s:8:\"location\";s:6:\"Khulna\";s:6:\"avatar\";N;s:6:\"rating\";i:5;s:4:\"text\";s:79:\"Wide range of categories in one place, I barely need to shop anywhere else now.\";s:10:\"sort_order\";i:3;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-07-12 10:34:55\";}s:11:\"\0*\0original\";a:12:{s:2:\"id\";i:4;s:5:\"phone\";N;s:5:\"email\";N;s:4:\"name\";s:12:\"Nusrat Jahan\";s:8:\"location\";s:6:\"Khulna\";s:6:\"avatar\";N;s:6:\"rating\";i:5;s:4:\"text\";s:79:\"Wide range of categories in one place, I barely need to shop anywhere else now.\";s:10:\"sort_order\";i:3;s:9:\"is_active\";i:1;s:10:\"created_at\";s:19:\"2026-07-12 10:34:55\";s:10:\"updated_at\";s:19:\"2026-07-12 10:34:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:9:{i:0;s:4:\"name\";i:1;s:5:\"email\";i:2;s:5:\"phone\";i:3;s:8:\"location\";i:4;s:6:\"avatar\";i:5;s:6:\"rating\";i:6;s:4:\"text\";i:7;s:10:\"sort_order\";i:8;s:9:\"is_active\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}',1788165714),('raimart-cache-nav:categories','a:9:{i:0;a:5:{s:4:\"name\";s:7:\"Shampoo\";s:4:\"slug\";s:7:\"shampoo\";s:5:\"image\";s:56:\"categories/EJYt3lZ49rEAM3Ut5j1g4Lwh2htMln1JGvsM7RP0.webp\";s:14:\"products_count\";i:0;s:13:\"subcategories\";a:2:{i:0;a:2:{s:4:\"name\";s:4:\"Dove\";s:4:\"slug\";s:4:\"dove\";}i:1;a:2:{s:4:\"name\";s:14:\"Loreal shampoo\";s:4:\"slug\";s:14:\"loreal-shampoo\";}}}i:1;a:5:{s:4:\"name\";s:12:\"Toys & Games\";s:4:\"slug\";s:10:\"toys-games\";s:5:\"image\";s:56:\"categories/5l97EsMynTwxS4seyP69ryIOhxUqzLoUvEsdbTLv.webp\";s:14:\"products_count\";i:0;s:13:\"subcategories\";a:0:{}}i:2;a:5:{s:4:\"name\";s:15:\"Baby Collection\";s:4:\"slug\";s:15:\"baby-collection\";s:5:\"image\";s:56:\"categories/ESAdzT8bibktMzBMITjxOrezFrb0trb1iT6ASdNO.webp\";s:14:\"products_count\";i:0;s:13:\"subcategories\";a:0:{}}i:3;a:5:{s:4:\"name\";s:5:\"women\";s:4:\"slug\";s:5:\"women\";s:5:\"image\";s:56:\"categories/GDNIZGSpgpHLejw2sVxpnjjwnKym6KU54SAGNxkf.webp\";s:14:\"products_count\";i:0;s:13:\"subcategories\";a:0:{}}i:4;a:5:{s:4:\"name\";s:7:\"Fashion\";s:4:\"slug\";s:7:\"fashion\";s:5:\"image\";s:56:\"categories/pz3AmvrwEjD64wITMySAtAlMuum5Tena953M0i8d.webp\";s:14:\"products_count\";i:0;s:13:\"subcategories\";a:0:{}}i:5;a:5:{s:4:\"name\";s:3:\"oil\";s:4:\"slug\";s:3:\"oil\";s:5:\"image\";s:56:\"categories/Ekg99MPfiEIcG6o3oDgbxHm7rRL18KoJLyIy7uIo.webp\";s:14:\"products_count\";i:0;s:13:\"subcategories\";a:1:{i:0;a:2:{s:4:\"name\";s:10:\"Olives Oil\";s:4:\"slug\";s:10:\"olives-oil\";}}}i:6;a:5:{s:4:\"name\";s:13:\"Home & Living\";s:4:\"slug\";s:11:\"home-living\";s:5:\"image\";s:56:\"categories/5L36L2ms4dWV3vj835AZrcZcoSfRLVqcO7RQ9Uk5.webp\";s:14:\"products_count\";i:0;s:13:\"subcategories\";a:0:{}}i:7;a:5:{s:4:\"name\";s:6:\"Beauty\";s:4:\"slug\";s:6:\"beauty\";s:5:\"image\";s:56:\"categories/HquMhrsw1C7T9OLCXw41OCKar2bdgMP9rbh1afZp.webp\";s:14:\"products_count\";i:1;s:13:\"subcategories\";a:0:{}}i:8;a:5:{s:4:\"name\";s:9:\"Groceries\";s:4:\"slug\";s:9:\"groceries\";s:5:\"image\";s:56:\"categories/wYNvHEM36xjr3mFHxAuZq94airS0enkioxISHs6Y.webp\";s:14:\"products_count\";i:0;s:13:\"subcategories\";a:0:{}}}',1788165714),('raimart-cache-site_settings:all','a:14:{s:16:\"site_description\";s:113:\"Your trusted online shopping destination. Quality products across every category, delivered fast and priced fair.\";s:12:\"facebook_url\";s:28:\"https://facebook.com/raimart\";s:13:\"instagram_url\";s:29:\"https://instagram.com/raimart\";s:11:\"twitter_url\";s:21:\"https://x.com/raimart\";s:13:\"app_store_url\";s:1:\"#\";s:15:\"google_play_url\";s:1:\"#\";s:15:\"contact_address\";s:17:\"Dhaka, Bangladesh\";s:13:\"contact_phone\";s:16:\"+880 1XXX-XXXXXX\";s:13:\"contact_email\";s:19:\"support@raimart.com\";s:23:\"free_shipping_threshold\";s:4:\"1500\";s:21:\"shipping_inside_label\";s:12:\"Inside Dhaka\";s:19:\"shipping_inside_fee\";s:2:\"60\";s:22:\"shipping_outside_label\";s:13:\"Outside Dhaka\";s:20:\"shipping_outside_fee\";s:3:\"120\";}',1788187351);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `show_on_home` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,'Shampoo','shampoo','categories/EJYt3lZ49rEAM3Ut5j1g4Lwh2htMln1JGvsM7RP0.webp',0,1,1,'2026-07-12 04:34:55','2026-08-30 20:40:26',NULL,NULL),(2,1,'Dove','dove','categories/wNl925Unc98twZOR0zisexZps2KJseNGLpJo368Z.webp',0,1,0,'2026-07-12 04:34:55','2026-08-30 20:40:26',NULL,NULL),(7,NULL,'Fashion','fashion','categories/pz3AmvrwEjD64wITMySAtAlMuum5Tena953M0i8d.webp',1,1,0,'2026-07-12 04:34:55','2026-08-30 20:40:26',NULL,NULL),(13,NULL,'Home & Living','home-living','categories/5L36L2ms4dWV3vj835AZrcZcoSfRLVqcO7RQ9Uk5.webp',2,1,1,'2026-07-12 04:34:55','2026-08-30 20:40:26',NULL,NULL),(18,NULL,'Beauty','beauty','categories/HquMhrsw1C7T9OLCXw41OCKar2bdgMP9rbh1afZp.webp',3,1,1,'2026-07-12 04:34:55','2026-08-30 20:40:26',NULL,NULL),(23,NULL,'Groceries','groceries','categories/wYNvHEM36xjr3mFHxAuZq94airS0enkioxISHs6Y.webp',4,1,1,'2026-07-12 04:34:55','2026-08-30 20:40:26',NULL,NULL),(34,NULL,'Toys & Games','toys-games','categories/5l97EsMynTwxS4seyP69ryIOhxUqzLoUvEsdbTLv.webp',0,1,1,'2026-07-12 04:34:55','2026-08-30 20:40:26',NULL,NULL),(38,NULL,'oil','oil','categories/Ekg99MPfiEIcG6o3oDgbxHm7rRL18KoJLyIy7uIo.webp',1,1,1,'2026-07-13 14:33:15','2026-08-30 20:40:26',NULL,NULL),(39,1,'Loreal shampoo','loreal-shampoo',NULL,0,1,0,'2026-07-13 14:53:53','2026-07-13 14:53:53',NULL,NULL),(40,38,'Olives Oil','olives-oil',NULL,0,1,0,'2026-07-13 14:58:53','2026-07-13 14:58:53',NULL,NULL),(41,NULL,'Baby Collection','baby-collection','categories/ESAdzT8bibktMzBMITjxOrezFrb0trb1iT6ASdNO.webp',0,1,1,'2026-08-30 19:09:21','2026-08-30 20:40:26',NULL,NULL),(42,NULL,'women','women','categories/GDNIZGSpgpHLejw2sVxpnjjwnKym6KU54SAGNxkf.webp',0,1,1,'2026-08-30 19:09:46','2026-08-30 20:40:26',NULL,NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `type` enum('percent','fixed') NOT NULL DEFAULT 'percent',
  `value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT NULL,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(10) unsigned DEFAULT NULL,
  `used_count` int(10) unsigned NOT NULL DEFAULT 0,
  `free_shipping` tinyint(1) NOT NULL DEFAULT 0,
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_07_09_152738_add_profile_fields_to_users_table',1),(5,'2026_07_09_152738_create_categories_table',1),(6,'2026_07_09_152738_z_create_products_table',1),(7,'2026_07_09_152739_create_banners_table',1),(8,'2026_07_09_152739_create_product_images_table',1),(9,'2026_07_09_152740_create_orders_table',1),(10,'2026_07_09_152740_create_site_settings_table',1),(11,'2026_07_09_152740_create_testimonials_table',1),(12,'2026_07_09_152741_create_order_items_table',1),(13,'2026_07_09_152741_create_payments_table',1),(14,'2026_07_13_195240_add_avatar_to_users_table',2),(15,'2026_07_13_202732_add_show_on_home_to_categories_table',3),(16,'2026_07_13_215251_add_new_fields_to_products_and_order_items_tables',4),(17,'2026_07_14_081318_add_email_and_phone_to_testimonials_table',5),(18,'2026_07_14_081847_add_email_to_testimonials_table',6),(19,'2026_08_03_000001_create_brands_table',7),(20,'2026_08_03_000002_add_brand_id_to_products_table',7),(21,'2026_08_03_000003_add_top_selling_to_products_table',8),(22,'2026_08_16_000001_create_product_reviews_table',9),(23,'2026_08_16_100000_add_weight_to_products_table',10),(24,'2026_08_31_000001_add_ga_tracking_to_orders_table',11),(25,'2026_08_31_000002_create_social_links_table',12),(26,'2026_08_31_000003_create_pages_table',12),(27,'2026_08_31_000004_create_contact_messages_table',12),(28,'2026_08_31_000005_create_coupons_table',13),(29,'2026_08_31_000006_add_tracking_and_seo_fields',14);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `price` decimal(10,2) NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,NULL,'Wireless Over-Ear Headphones',NULL,2499.00,1,2499.00,'2026-07-12 06:40:31','2026-07-12 06:40:31'),(2,1,NULL,'Leather Tote Handbag',NULL,1799.00,1,1799.00,'2026-07-12 06:40:31','2026-07-12 06:40:31'),(3,2,15,'Dove Intensive repair shampoo',NULL,1000.00,1,1000.00,'2026-07-14 01:32:25','2026-07-14 01:32:25'),(5,4,17,'japan sakura day cream',NULL,450.00,1,450.00,'2026-08-30 19:50:41','2026-08-30 19:50:41'),(6,4,15,'Dove Intensive repair shampoo',NULL,1000.00,2,2000.00,'2026-08-30 19:50:41','2026-08-30 19:50:41'),(7,5,15,'Dove Intensive repair shampoo',NULL,1000.00,1,1000.00,'2026-08-30 20:09:35','2026-08-30 20:09:35'),(8,6,15,'Dove Intensive repair shampoo',NULL,1000.00,1,1000.00,'2026-08-31 04:05:28','2026-08-31 04:05:28'),(9,7,15,'Dove Intensive repair shampoo',NULL,1000.00,1,1000.00,'2026-08-31 04:07:18','2026-08-31 04:07:18'),(10,8,15,'Dove Intensive repair shampoo',NULL,1000.00,1,1000.00,'2026-08-31 04:16:19','2026-08-31 04:16:19'),(11,9,15,'Dove Intensive repair shampoo',NULL,1000.00,1,1000.00,'2026-08-31 04:17:13','2026-08-31 04:17:13'),(12,10,16,'Dove Intensive repair shampoo',NULL,1000.00,1,1000.00,'2026-08-31 04:19:32','2026-08-31 04:19:32');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ga_client_id` varchar(255) DEFAULT NULL,
  `ga_session_id` varchar(255) DEFAULT NULL,
  `purchase_tracked_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `delivery_area` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `coupon_code` varchar(255) DEFAULT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `courier_name` varchar(255) DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cod',
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'RMTEST00001',NULL,NULL,NULL,NULL,'Test Customer','01700000000','test@example.com','123 Test Road','Dhaka',NULL,NULL,4298.00,NULL,0.00,60.00,4358.00,'pending',NULL,NULL,NULL,'cod','unpaid','2026-07-12 06:40:31','2026-07-13 15:31:28'),(2,'RM260714VIMEH',1,NULL,NULL,NULL,'Raimart Admin','01622243011','admin@raimart.com','ashuliya','savar,Dhaka',NULL,NULL,1000.00,NULL,0.00,60.00,1060.00,'processing',NULL,NULL,NULL,'cod','unpaid','2026-07-14 01:32:25','2026-07-14 01:33:01'),(4,'RM260831H1YKZ',1,NULL,NULL,NULL,'Raimart Admin','01622243011','admin@raimart.com','aaa','Bhola','outside',NULL,2450.00,NULL,0.00,0.00,2450.00,'pending',NULL,NULL,NULL,'piprapay','unpaid','2026-08-30 19:50:41','2026-08-30 19:50:41'),(5,'RM260831NZFBH',1,NULL,NULL,NULL,'Raimart Admin','01622243011','admin@raimart.com','ashuliya','Bagerhat','outside',NULL,1000.00,NULL,0.00,120.00,1120.00,'pending',NULL,NULL,NULL,'piprapay','unpaid','2026-08-30 20:09:35','2026-08-30 20:09:35'),(6,'RM2608313MSZB',NULL,NULL,NULL,NULL,'Abu Taleb Khan','01622243011','abutalebkhan.dev@gmail.com','Amin Model Town, East Dendabor, Cemetery Road,Savar','Jhalakathi','outside',NULL,1000.00,NULL,0.00,120.00,1120.00,'pending',NULL,NULL,NULL,'piprapay','unpaid','2026-08-31 04:05:28','2026-08-31 04:05:28'),(7,'RM260831QH8QL',NULL,NULL,NULL,NULL,'Abu Taleb Khan','01622243011','abutalebkhan.dev@gmail.com','Amin Model Town, East Dendabor, Cemetery Road,Savar','Bagerhat','outside',NULL,1000.00,NULL,0.00,120.00,1120.00,'pending',NULL,NULL,NULL,'piprapay','unpaid','2026-08-31 04:07:18','2026-08-31 04:07:18'),(8,'RM260831JKGHL',NULL,NULL,NULL,NULL,'Abu Taleb Khan','01622243011','abutalebkhan.dev@gmail.com','Amin Model Town, East Dendabor, Cemetery Road,Savar','Bagerhat','outside',NULL,1000.00,NULL,0.00,120.00,1120.00,'pending',NULL,NULL,NULL,'piprapay','unpaid','2026-08-31 04:16:19','2026-08-31 04:16:19'),(9,'RM2608314DBHK',NULL,NULL,NULL,NULL,'Abu Taleb Khan','56556235026','abutalebkhan.dev@gmail.com','Amin Model Town, East Dendabor, Cemetery Road,Savar','Bandarban','outside',NULL,1000.00,NULL,0.00,120.00,1120.00,'pending',NULL,NULL,NULL,'piprapay','unpaid','2026-08-31 04:17:13','2026-08-31 04:17:13'),(10,'RM260831KAUET',NULL,NULL,NULL,NULL,'Abu Taleb Khan','01622243011','abutalebkhan.dev@gmail.com','Amin Model Town, East Dendabor, Cemetery Road,Savar','Bandarban','outside',NULL,1000.00,NULL,0.00,120.00,1120.00,'pending',NULL,NULL,NULL,'piprapay','unpaid','2026-08-31 04:19:32','2026-08-31 04:19:32');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `show_in_footer` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Privacy Policy','privacy-policy','<p>Write your privacy policy here.</p>',NULL,1,1,1,'2026-08-30 18:35:27','2026-08-30 18:35:27'),(2,'Terms & Conditions','terms-conditions','<p>Write your terms and conditions here.</p>',NULL,1,1,2,'2026-08-30 18:35:27','2026-08-30 18:35:27'),(3,'Return & Refund Policy','return-refund-policy','<p>Write your return and refund policy here.</p>',NULL,1,1,3,'2026-08-30 18:35:27','2026-08-30 18:35:27');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `gateway` varchar(255) NOT NULL DEFAULT 'piprapay',
  `pp_id` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `raw_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_response`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_order_id_foreign` (`order_id`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,10,'piprapay','464918359161209421623280074',NULL,1120.00,'pending','{\"pp_id\":\"464918359161209421623280074\",\"pp_url\":\"https:\\/\\/pay.raimartbd.com\\/payment\\/464918359161209421623280074\"}','2026-08-31 04:19:32','2026-08-31 04:19:32');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (29,15,'products/fl7VwwTSN4W6wlZRRBnHYASjkg9jTSO5nlSskSyB.webp',1,'2026-07-13 15:15:16','2026-08-30 20:40:24'),(30,15,'products/fbNDHZBJXlfImQEzMAhd6Ci1DsHU2aGRrNWD9Htc.webp',2,'2026-07-13 15:15:16','2026-08-30 20:40:24'),(31,16,'products/2OcHjdsmLG85QBxGu1o4uZrLmv1GdIqWDzmVPDyn.webp',1,'2026-07-13 16:04:18','2026-08-30 20:40:25'),(32,16,'products/UtTSWnDKtT5kM9ETc2c1PjWMDPoOrCnwCuD7TgCZ.webp',2,'2026-07-13 16:04:18','2026-08-30 20:40:25'),(33,16,'products/UCHi3wadvpYZl6KKVALKnz60reeexYc0t41pg9Kn.webp',3,'2026-07-13 16:04:18','2026-08-30 20:40:25'),(34,16,'products/lCYv8QGrC86UpoOMDFt8OVaahdtDvVgVuFzpjAuw.webp',4,'2026-07-13 16:04:18','2026-08-30 20:40:25'),(39,17,'products/qTTQKzBWPKIFy98aXQRNAI7rK9ZaAkoYfnafLTmk.m4v',1,'2026-08-02 16:22:09','2026-08-02 16:22:09'),(40,17,'products/8IhdfJj65GZFR0W6tzRRIG7tYXlvyTIGpGTd7CfM.webp',2,'2026-08-02 16:22:09','2026-08-30 20:40:25'),(41,17,'products/SXSS6nUIkwPISFnBYAfFaiotu5WfnpvYJDsQS8Er.webp',3,'2026-08-02 16:22:09','2026-08-30 20:40:25'),(42,17,'products/nDvBMx7MO4SYQ4MFyitluUoxDrB3hjLyA5mYeeW3.webp',4,'2026-08-02 16:22:09','2026-08-30 20:40:25'),(43,17,'products/TvHITLxORmalvEWefmGpNAdKg0KypENV4dJdkfDj.webp',5,'2026-08-02 16:22:09','2026-08-30 20:40:25');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `rating` tinyint(3) unsigned NOT NULL DEFAULT 5,
  `text` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_reviews_product_id_is_approved_index` (`product_id`,`is_approved`),
  CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_reviews`
--

LOCK TABLES `product_reviews` WRITE;
/*!40000 ALTER TABLE `product_reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `brand_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `stock` int(10) unsigned NOT NULL DEFAULT 0,
  `sku` varchar(255) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `rating` decimal(2,1) NOT NULL DEFAULT 0.0,
  `reviews_count` int(10) unsigned NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_best_seller` tinyint(1) NOT NULL DEFAULT 0,
  `is_new_arrival` tinyint(1) NOT NULL DEFAULT 0,
  `is_top_selling` tinyint(1) NOT NULL DEFAULT 0,
  `top_selling_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `attributes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attributes`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_brand_id_foreign` (`brand_id`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (15,2,1,'Dove Intensive repair shampoo',NULL,'dove-intensive-repair-shampoo-0kjy',NULL,'<p><strong>Dove Intensive Repair Shampoo</strong> শুষ্ক, রুক্ষ ও ক্ষতিগ্রস্ত চুলের যত্নের জন্য তৈরি একটি প্রিমিয়াম হেয়ার কেয়ার শ্যাম্পু। <strong>Amino Serum</strong> এবং <strong>Bio-Protein Care Technology</strong> সমৃদ্ধ এর উন্নত ফর্মুলা চুলকে আলতোভাবে পরিষ্কার করার পাশাপাশি প্রয়োজনীয় পুষ্টি জোগাতে এবং ক্ষতিগ্রস্ত চুলের যত্ন নিতে সহায়তা করে। নিয়মিত ব্যবহারে চুল আরও নরম, মসৃণ ও সহজে ম্যানেজযোগ্য অনুভূত হয়। প্রতিদিনের ব্যবহারের জন্য উপযোগী এই শ্যাম্পুটি আপনার দৈনন্দিন হেয়ার কেয়ার রুটিনের একটি নির্ভরযোগ্য পছন্দ।</p>',NULL,NULL,1000.00,1250.00,28,'dove-001','400g',0.0,0,1,1,1,1,0,1,NULL,'2026-07-13 15:15:16','2026-08-31 04:17:13'),(16,2,2,'Dove Intensive repair shampoo','Dove Intensive Repair Shampoo শুষ্ক, রুক্ষ ও ক্ষতিগ্রস্ত চুলের যত্নের জন্য তৈরি একটি প্রিমিয়াম হেয়ার কেয়ার শ্যাম্পু। Amino Serum এবং Bio-Protein Care Technology সমৃদ্ধ এর উন্নত ফর্মুলা চুলকে আলতোভাবে পরিষ্কার করার পাশাপাশি প্রয়োজনীয় পুষ্টি জোগাতে এবং ক্ষতিগ্রস্ত চুলের যত্ন নিতে সহায়তা করে।','dove-intensive-repair-shampoo-5nex','products/tNqFry6TmpWjdvvtOnGcWs1smmp42D29vI8VnISj.webp','<p><strong>Dove Intensive Repair Shampoo</strong> শুষ্ক, রুক্ষ ও ক্ষতিগ্রস্ত চুলের যত্নের জন্য তৈরি একটি প্রিমিয়াম হেয়ার কেয়ার শ্যাম্পু। <strong>Amino Serum</strong> এবং <strong>Bio-Protein Care Technology</strong> সমৃদ্ধ এর উন্নত ফর্মুলা চুলকে আলতোভাবে পরিষ্কার করার পাশাপাশি প্রয়োজনীয় পুষ্টি জোগাতে এবং ক্ষতিগ্রস্ত চুলের যত্ন নিতে সহায়তা করে। নিয়মিত ব্যবহারে চুল আরও নরম, মসৃণ ও সহজে ম্যানেজযোগ্য অনুভূত হয়। প্রতিদিনের ব্যবহারের জন্য উপযোগী এই শ্যাম্পুটি আপনার দৈনন্দিন হেয়ার কেয়ার রুটিনের একটি নির্ভরযোগ্য পছন্দ।</p><p><strong>Dove Intensive Repair Shampoo</strong> শুষ্ক, রুক্ষ ও ক্ষতিগ্রস্ত চুলের যত্নের জন্য তৈরি একটি প্রিমিয়াম হেয়ার কেয়ার শ্যাম্পু। <strong>Amino Serum</strong> এবং <strong>Bio-Protein Care Technology</strong> সমৃদ্ধ এর উন্নত ফর্মুলা চুলকে আলতোভাবে পরিষ্কার করার পাশাপাশি প্রয়োজনীয় পুষ্টি জোগাতে এবং ক্ষতিগ্রস্ত চুলের যত্ন নিতে সহায়তা করে। নিয়মিত ব্যবহারে চুল আরও নরম, মসৃণ ও সহজে ম্যানেজযোগ্য অনুভূত হয়। প্রতিদিনের ব্যবহারের জন্য উপযোগী এই শ্যাম্পুটি আপনার দৈনন্দিন হেয়ার কেয়ার রুটিনের একটি নির্ভরযোগ্য পছন্দ।</p>',NULL,NULL,1000.00,1250.00,39,'dove-002',NULL,0.0,0,1,1,1,1,1,1,'[{\"name\":\"400 ML\",\"values\":\"\"}]','2026-07-13 16:04:18','2026-08-31 04:19:32'),(17,18,NULL,'japan sakura day cream','this is very beautiful product','japan-sakura-day-cream-ydve','products/UiY72TGwMrkVZ2Edb9EHP8eWouhOviuRF5CHkbHF.webp','<p>this is very beautiful product this is very beautiful product this is very beautiful product</p>',NULL,NULL,450.00,350.00,19,'ddd',NULL,0.0,0,1,1,1,1,2,1,'[]','2026-08-02 16:22:09','2026-08-30 19:50:41');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('E1w5L52k1jp21GaM0qPVMTfGKIoYVYQu37HDAiqJ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Claude/1.40609.0 Chrome/148.0.7778.280 Safari/537.36 MSIX','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaFBDTEhyM0lsUWNETmZVdEZmczgxMXBqZzlHcFIwZWdLeWlsVUx1ZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1788140588),('iN8SsEK5f5tnLLdFbsirl4P4oP7WYh3UPet7qInS',NULL,'127.0.0.1','Symfony','YTozOntzOjY6Il90b2tlbiI7czo0MDoid3o1cFZkTUs5NjNSQlJ1RFlJdzZ5YnVzdHo0YUFucnBiR1ZvQXF6UyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1788144133),('JEBDt7gvZHFhIrBQcPYax0Vq2E4ON8DzZblckdkB',NULL,'127.0.0.1','Symfony','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV3BYQ1pOOTdPcHliSXJrM2VzVGI0QjM0YVlrZXlTMzA2WjRxdHMyTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Qvc2hvcCI7czo1OiJyb3V0ZSI7czo0OiJzaG9wIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1788143730),('x8yur2iEWhxUCd8p4fVbj3gJURD60jAFGmoumUcg',NULL,'127.0.0.1','Symfony','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTHFHUGtWV09yNVFCTEEwZ3R0M0ZhenZMa2kzSDVHeVhoSG5wZlMzTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1788143713),('z7RvjxasTiLzjCx9wKkgb7oD8U8mgimzOv3UIfJM',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ0NBT2VJZ2NmMDdBeVlXRWREZmhKZHJuU0tHdTZkMm8yZmpYMUFJZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTc1OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vb3JkZXJzP2FyZWE9b3V0c2lkZSZmcm9tPTIwMjYtMDgtMzEmbWF4X3RvdGFsPSZtaW5fdG90YWw9JnBheW1lbnRfbWV0aG9kPSZwYXltZW50X3N0YXR1cz0mcGVyX3BhZ2U9MjAmc2VhcmNoPSZzb3J0PXRvdGFsX2hpZ2gmc3RhdHVzPSZ0bz0yMDI2LTA4LTMxIjtzOjU6InJvdXRlIjtzOjE4OiJhZG1pbi5vcmRlcnMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1788144152),('zTLApNKPLCrQDsSlBko9euVeiVc5VQbotFMdh1gw',NULL,'127.0.0.1','Symfony','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMUtnSjZtcnR5UWw4MmwzYnZwTWltOHc2NmZNcFlQb0Y0eksyb2FIaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly9sb2NhbGhvc3QiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1788144114);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_settings`
--

DROP TABLE IF EXISTS `site_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_settings`
--

LOCK TABLES `site_settings` WRITE;
/*!40000 ALTER TABLE `site_settings` DISABLE KEYS */;
INSERT INTO `site_settings` VALUES (1,'site_description','Your trusted online shopping destination. Quality products across every category, delivered fast and priced fair.','2026-07-12 04:34:55','2026-07-12 04:34:55'),(2,'facebook_url','https://facebook.com/raimart','2026-07-12 04:34:55','2026-07-12 04:34:55'),(3,'instagram_url','https://instagram.com/raimart','2026-07-12 04:34:55','2026-07-12 04:34:55'),(4,'twitter_url','https://x.com/raimart','2026-07-12 04:34:55','2026-07-12 04:34:55'),(5,'app_store_url','#','2026-07-12 04:34:55','2026-07-12 04:34:55'),(6,'google_play_url','#','2026-07-12 04:34:55','2026-07-12 04:34:55'),(7,'contact_address','Dhaka, Bangladesh','2026-07-12 04:34:55','2026-07-12 04:34:55'),(8,'contact_phone','+880 1XXX-XXXXXX','2026-07-12 04:34:55','2026-07-12 04:34:55'),(9,'contact_email','support@raimart.com','2026-07-12 04:34:55','2026-07-12 04:34:55'),(10,'free_shipping_threshold','1500','2026-08-30 19:40:18','2026-08-30 19:41:44'),(11,'shipping_inside_label','Inside Dhaka','2026-08-30 19:41:44','2026-08-30 19:41:44'),(12,'shipping_inside_fee','60','2026-08-30 19:41:44','2026-08-30 19:41:44'),(13,'shipping_outside_label','Outside Dhaka','2026-08-30 19:41:44','2026-08-30 19:41:44'),(14,'shipping_outside_fee','120','2026-08-30 19:41:44','2026-08-30 19:41:44'),(15,'telegram_bot_token',NULL,'2026-08-31 05:08:46','2026-08-31 05:08:46'),(16,'telegram_chat_id',NULL,'2026-08-31 05:08:46','2026-08-31 05:08:46');
/*!40000 ALTER TABLE `site_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_links`
--

DROP TABLE IF EXISTS `social_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `social_links` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `platform` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `social_links_platform_unique` (`platform`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_links`
--

LOCK TABLES `social_links` WRITE;
/*!40000 ALTER TABLE `social_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `testimonials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `rating` tinyint(3) unsigned NOT NULL DEFAULT 5,
  `text` text NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,NULL,NULL,'Rakib Hossain','Dhaka',NULL,5,'Genuine products, fast delivery every single time. Raimart is now my go-to for everything.',0,1,'2026-07-12 04:34:55','2026-07-12 04:34:55'),(2,NULL,NULL,'Sadia Islam','Chattogram',NULL,5,'Customer support helped me with a return within minutes. Really smooth experience.',1,1,'2026-07-12 04:34:55','2026-07-12 04:34:55'),(3,NULL,NULL,'Tanvir Ahmed','Sylhet',NULL,4,'Great prices and the packaging quality is excellent. Highly recommend.',2,1,'2026-07-12 04:34:55','2026-07-12 04:34:55'),(4,NULL,NULL,'Nusrat Jahan','Khulna',NULL,5,'Wide range of categories in one place, I barely need to shop anywhere else now.',3,1,'2026-07-12 04:34:55','2026-07-12 04:34:55'),(5,'01622243011','mdabutaleb.dev@gmail.com','Md Abu Taleb Khan',NULL,NULL,5,'hello',0,1,'2026-07-14 02:25:12','2026-07-14 02:31:56');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Raimart Admin','admin@raimart.com','2026-07-12 04:34:55','$2y$12$i2kjRVLuff/tDb6OTa2dVuEgy5Oej5V45rWAU8/poA27HlBu0eW36','avatars/iOa0GQmCfsRct6kSFRJVDhdUtPPMMkXLAsDREEJU.png',1,NULL,NULL,NULL,'0EMaPKYaFEsWpha2Q2JQkB8SITqWiRNGIG4iUwYehGcoSWk8rBbOj83bRk1e','2026-07-12 04:34:55','2026-07-13 14:05:24');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-31 17:44:19
