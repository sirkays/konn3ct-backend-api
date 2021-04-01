-- MySQL dump 10.13  Distrib 5.7.31, for Linux (x86_64)
--
-- Host: localhost    Database: konn3ct
-- ------------------------------------------------------
-- Server version	5.7.31-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(90) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2014_10_12_200000_add_two_factor_columns_to_users_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2020_10_09_154302_create_sessions_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(255) NOT NULL,
  `plan` int(255) NOT NULL,
  `gateway` text NOT NULL,
  `currency` varchar(90) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `status` varchar(90) NOT NULL,
  `amount` int(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reference` varchar(90) NOT NULL,
  `gateway_reference` varchar(90) NOT NULL,
  `gateway_response` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
INSERT INTO `payment` VALUES (1,11,2,'Flutterwave',NULL,NULL,'success',46000,'2020-10-16 11:03:25','konn3ct_2780524441602842486','NewwavesEcosyst/FLW347672078','{\"status\":\"success\",\"message\":\"Transaction fetched successfully\",\"data\":{\"id\":318841709,\"tx_ref\":\"konn3ct_2780524441602842486\",\"flw_ref\":\"NewwavesEcosyst/FLW347672078\",\"device_fingerprint\":\"1ae1f04c84b0c7d71e44180aa9d0ef3e\",\"amount\":46000,\"currency\":\"NGN\",\"charged_amount\":46000,\"app_fee\":598,\"merchant_fee\":0,\"processor_response\":\"Approved by Financial Institution\",\"auth_model\":\"PIN\",\"ip\":\"129.205.113.246\",\"narration\":\"CARD Transaction \",\"status\":\"successful\",\"payment_type\":\"card\",\"created_at\":\"2020-10-16T10:02:39.000Z\",\"account_id\":215512,\"card\":{\"first_6digits\":\"539923\",\"last_4digits\":\"8129\",\"issuer\":\"FIRST BANK OF NIGERIA PLC DEBIT CARD\",\"country\":\"NIGERIA NG\",\"type\":\"MASTERCARD\",\"token\":\"flw-t1nf-a0c1bca8ac21aa3543c34c7e8a839569-k3n\",\"expiry\":\"09/22\"},\"meta\":null,\"amount_settled\":45402,\"customer\":{\"id\":228185626,\"name\":\"konn3ct \",\"phone_number\":\"+2348033046408\",\"email\":\"williamsos@newwavesecosystem.com\",\"created_at\":\"2020-10-16T09:56:39.000Z\"}}}','2020-10-16 11:03:25','2020-10-16 11:03:25');
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(255) DEFAULT NULL,
  `name` varchar(900) NOT NULL,
  `url` varchar(900) NOT NULL,
  `dial_number` varchar(20) DEFAULT NULL,
  `password_attendee` varchar(90) NOT NULL,
  `password_moderator` varchar(90) NOT NULL,
  `welcome_message` text NOT NULL,
  `logout_url` text NOT NULL,
  `max_participants` int(255) NOT NULL,
  `duration` int(255) NOT NULL,
  `bbb_returncode` varchar(90) DEFAULT NULL,
  `internalMeetingID` text,
  `parentMeetingID` text,
  `voiceBridge` varchar(50) DEFAULT NULL,
  `createDate` text,
  `createTime` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1 COMMENT='This table holds all meetings room';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room`
--

LOCK TABLES `room` WRITE;
/*!40000 ALTER TABLE `room` DISABLE KEYS */;
INSERT INTO `room` VALUES (11,NULL,'Hello Group','mygroup','+1 970-519-2253','attendee','moderator','','',0,0,NULL,NULL,NULL,NULL,NULL,NULL,'2020-10-12 18:02:38','2020-10-12 18:02:38'),(12,NULL,'Samjiroom','samaaaa','+1 970-519-2253','attendee','moderator','','',0,0,NULL,NULL,NULL,NULL,NULL,NULL,'2020-10-12 18:06:24','2020-10-12 18:06:24'),(15,6,'Meet \'Seyi','meetseyi','+1 970-519-2253','attendee','moderator','','',0,0,'SUCCESS','f1abd670358e036c31296e66b3b66c382ac00812-1602529598414','bbb-none','11364','Mon Oct 12 19:06:38 UTC 2020','1602529598414','2020-10-12 19:06:38','2020-10-12 19:06:38'),(21,9,'Room1','roomone','+1 970-519-2253','attendee','moderator','','',0,0,'SUCCESS','472b07b9fcf2c2451e8781e944bf5f77cd8457c8-1602709425770','bbb-none','81890','Wed Oct 14 21:03:45 UTC 2020','1602709425770','2020-10-14 21:03:44','2020-10-14 21:03:45'),(22,9,'Room Two','roomtwo','+1 970-519-2253','attendee','moderator','','',0,0,'SUCCESS','12c6fc06c99a462375eeb3f43dfd832b08ca9e17-1602710581699','bbb-none','77192','Wed Oct 14 21:23:01 UTC 2020','1602710581699','2020-10-14 21:23:01','2020-10-14 21:23:01'),(23,1,'testlabroom','testlabroomsam','+1 970-519-2253','attendee','moderator','','',0,0,'SUCCESS','d435a6cdd786300dff204ee7c2ef942d3e9034e2-1602763247172','bbb-none','89013','Thu Oct 15 12:00:47 UTC 2020','1602763247172','2020-10-15 12:00:46','2020-10-15 12:00:48'),(25,9,'konn3ct','konn3ct','+1 970-519-2253','attendee','moderator','','',0,0,'SUCCESS','f6e1126cedebf23e1463aee73f9df08783640400-1602796821700','bbb-none','56408','Thu Oct 15 21:20:21 UTC 2020','1602796821700','2020-10-15 22:20:21','2020-10-15 22:20:21'),(26,11,'Olufemi Williams','ceo','+1 970-519-2253','attendee','moderator','','',0,0,'SUCCESS','887309d048beef83ad3eabf2a79a64a389ab1c9f-1602796829693','bbb-none','64021','Thu Oct 15 21:20:29 UTC 2020','1602796829693','2020-10-15 22:20:29','2020-10-15 22:20:29'),(27,11,'Smartfun','Smartfun','+1 970-519-2253','attendee','moderator','','https://konn3ct.com',100,600,'SUCCESS','bc33ea4e26e5e1af1408321416956113a4658763-1602880072417','bbb-none','16669','Fri Oct 16 20:27:52 UTC 2020','1602880072417','2020-10-16 21:27:51','2020-10-16 21:27:52'),(28,1,'Testlab2','samtestlab2','+1 970-519-2253','moderator','moderator','','https://konn3ct.com',100,600,'SUCCESS','0a57cb53ba59c46fc4b692527a38a87c78d84028-1602880212065','bbb-none','98556','Fri Oct 16 20:30:12 UTC 2020','1602880212065','2020-10-16 21:30:11','2020-10-16 21:30:12');
/*!40000 ALTER TABLE `room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `sessions` VALUES ('0S89Jil68j1XzjydZjxRThDEBBge4yQCzDKxs5NX',NULL,'41.184.254.15','Mozilla/5.0 (iPhone; CPU iPhone OS 13_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.1.2 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQXNtQzUzeWc3N05pV1l6eHJkWTRpRGZrNVBKZHlJbEplQUxSRWtPdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8va29ubjNjdC5jb20vam9pbnNlc3Npb24iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1602951852),('182LKDvmdsBFini5sqDCvG2kiufhVYGYOpb73wZ6',NULL,'105.112.69.118','Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoieTdBUXFNS2VFUUprU0I0UkFoZUc4MFdDdzY3WUtzSlB3YkM4TlhIaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly93d3cua29ubjNjdC5jb20vam9pbi9rb25uM2N0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1602958586),('4Z5ETKtzdCTrGWfY5l2r2s3alfo818B8NN2QZOqq',NULL,'167.248.133.51','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNnRFc09kZXpvS3pzTng3cE9VZlpMMmxlSEJGUE9Sc2V5Y2ZjOUtkWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vMjAyLjE4Mi4xMTAuMjQ1Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1602994750),('aOAAuYqY3d0SFDEFKDgRTUEWpTxMQmgFCXpuLvcG',NULL,'152.231.50.135','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibm9qUldLamRwWUY5YXdJd2lOSllCWG5BNTh6UW5JYlBrVWc4OVFEZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly8yMDIuMTgyLjExMC4yNDUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1602989670),('AZWssblVetRZPYdAcNYuGE48nUAf6cqIjkFRCM54',NULL,'162.142.125.33','Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)','YTozOntzOjY6Il90b2tlbiI7czo0MDoib1ZDT0VpRjFaeEZiSGtyRHFXODRZdVdpMnk0QUxCNHUyOUVhVVpTZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly8yMDIuMTgyLjExMC4yNDUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1602990953),('gNYFLzUL1J5nuhtP6iJ24iAc3zSHc85f6edVFaG4',NULL,'41.184.254.15','Mozilla/5.0 (iPhone; CPU iPhone OS 13_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.1.2 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWjdUT1E4UkRHWGtFQnZpMG9yUkVLemFyV043YUE2SjJxNjRBbFZvUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8va29ubjNjdC5jb20vam9pbnNlc3Npb24iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1602951853),('gPzMHdxVSr3EazptACwV2TnT1DSt1uPiiKQlHX2y',NULL,'103.149.192.29','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZHlnM2lESTN1UGt1VlZnTHVMa3VoNmhUQUt1dTVrOVBqM1oxZEVVVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vMjAyLjE4Mi4xMTAuMjQ1Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1602983581),('m88EeIyPLS1lDLOnKZcBhUBrTKQEHtLVZSVqMLn6',NULL,'40.74.226.113','Mozilla/5.0 (Windows NT 5.1; rv:9.0.1) Gecko/20100101 Firefox/9.0.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0V2RGVWa3hFMmpYaDA0dUZ0aTZoVzZtS0JxOFJjSG9QWmxER1FVNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly8yMDIuMTgyLjExMC4yNDUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1602972326),('mk2EnlNgZPaB1AK5XK2L8N38WBOlfDGpUUHlUsiM',NULL,'59.127.183.63','Mozilla/5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNVdvVXJYbjhPbFE0dnlYQUNqSk1xOG5EQ2tRbWJySld0N1RndWFGbCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly8xMjcuMC4wLjEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1602992676),('oahB1vd7CSDmGLRjjHyTTTlkLxl6SIosEApBk2KW',NULL,'122.116.230.204','Mozilla/5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNEdXNjdPeU45SlJLOUFUZ05nZkFVZ1N0UlNDN0FIQ0NnOGhWZ3ZMTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly8xMjcuMC4wLjEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1602961226),('OBELQ1mkHnsm53MV8uiZLjBX4ASBuISRQVwV5BXD',NULL,'5.154.54.51','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYW5vMzFzYjc4THJSMFVxSUtRcE5tTVlWSzdwYUF2OE9Va1lSUjZGaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly8yMDIuMTgyLjExMC4yNDUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1602986172),('ooMTicy7w0SegWGn9aCj4fZT7OGCJ3CYlVWcfove',NULL,'162.142.125.33','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVDVZblVWYmhPY0d5TjYzWmZZRE42QnBhZ1RpQ2lTb2g5VG54aTZCYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly8yMDIuMTgyLjExMC4yNDUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1602990953),('p4eAKyjhztthyYRE91IIYwpdUwwK300xx792RZuP',NULL,'128.14.134.134','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTlNiUkNDazRaRjlsUE5UMzZ0UUw5QzRSc3BRNXBwTWtTZ21sOVpPMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vMjAyLjE4Mi4xMTAuMjQ1Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1602967168),('PaXSdAWwkT5IK68w5WRKsUSJCMdISyioNyr0pnWe',NULL,'180.177.128.163','Mozilla/5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiblRycFFzeFcxSGtINnVOYU5VUHBCTkIxOXZyS2VCWXFVZGF4YlZHZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTY6Imh0dHA6Ly8xMjcuMC4wLjEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1602969855),('rekIeWk2qkITVmETcvsJ7whZMDg5DfHfuBmUYMmK',NULL,'167.248.133.51','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRnQ2N0Z5aHNSTnM1TUFJejc3a3pPUjZtbTFzbDE2UFZ1cG9aM3NVcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vMjAyLjE4Mi4xMTAuMjQ1Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1602994750),('Sc7vwKOaKPI3g7XwrksHvRWpfz7jaMkAwIXLUWUe',NULL,'71.6.232.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUDhoVkZnMjhhajM1eTNtM3IweVVTR1J0SHN0MTYwenBoU3lwZTltMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly8yMDIuMTgyLjExMC4yNDUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1602956896),('t6D7gPMtq3u1zWZ9810XQvoClvvd4O9i0c4N8abm',NULL,'31.24.202.118','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQzg2dnRVNHhab1c4OFM1N2ltM1JQem4xZVNERVB3N2ZrUXhqYTdHdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly8yMDIuMTgyLjExMC4yNDUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1602967529),('Yq1u9EdYGM55MMXm8LvCEPzGrAX9TdCcOJi85J9M',NULL,'95.123.41.94','Mozilla/5.0 zgrab/0.x','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRVJtR2Y5Q21xRFJiOW5BRjhmZUpmWW1RaDVleWE4QmkwMjVHcGtXaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vMjAyLjE4Mi4xMTAuMjQ1Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1602988081);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plan` int(5) NOT NULL,
  `subscription` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT 'new',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint(20) unsigned DEFAULT NULL,
  `profile_photo_path` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Samji Baba','odejinmisamu@gmail.com','08166939205',2,'2021-10-16 11:04:41',NULL,'$2y$10$Jg7qD.jaNYOcTFNSlCSJBeCijuKE/uTZ3.FoT.M1h16wnykRfXhme','eyJpdiI6ImxDWmY5MnRubm9WUGRNQzArUlBYWEE9PSIsInZhbHVlIjoiQ29nMGhpVDU4L1RqZzFOZGNIK2JyU0FLN0VkU3huaHJselRGQzRNOE4rdz0iLCJtYWMiOiJiY2QwZjQyMGMwNTZmMjk3YzRjN2ExZGE4Y2FjZThhMmYzZGY4YmUzYTQ5OWRhNGU5NjViOTdhNTI4MWRjYzhhIn0=','eyJpdiI6IjBEcVVWdzMvMVlCVHFtRDEyRzY5ZFE9PSIsInZhbHVlIjoiN3lWTXF5VG1MMmsrR1c3blFaVmVLUi9HaG40QWhSWkg4WjlZclNIMGM1Sk5XaGQ2TTExRmY3Tlg4WEVSK29rVlA5WC9HRkFzYjkvbzFuUThPK1BFQXl2a2xocnlCVlZzUGh3dHVPV0lUVDczSUlCbEdiVWR4cUMrOUtaUkp4RVRmQlNGR3MvSHJxMkJiZnkxQ0J5MlhmYjBRdzkzempuU0hmMHNTM1Q0bnA5T0VRZ2ovSXFBRDBDWEJ5aWhYcnVWWUpSRzYxNThDZ0JOd1U4RE9nc3dVOVY0eHRkR2VMV1lyU2NLajVsbmJnUTMwMlpscERyc2lHaURveUhzd0VwWEdLWUZsV0IrdVk5aWw3TWFjQUMwOXc9PSIsIm1hYyI6IjVmZWM1NWY1YTkwMjg3NWI3YmQzOWQ5MzIwNzM4M2ZlYTg0ODliNDY5Yjk1MjQzNDllZTEzNTM5N2EzYzc3YzMifQ==','4FFamg1tR5wnpcqPEERr0kzbIi1sfvXGMl05obcq9IzN2EbDSpWho2BHonSs',NULL,NULL,'2020-10-10 11:51:21','2020-10-13 20:07:19'),(2,'Godwin Efe Ozoma','seyiogidiolu@gmail.com',NULL,0,'new',NULL,'$2y$10$XT.Oe9dNcU/i.Qta.OXCZuWrqxDI9AyqjGu2t8tztIcPNDQIQgC6i',NULL,NULL,NULL,NULL,NULL,'2020-10-10 12:42:22','2020-10-10 12:42:22'),(3,'Garba','garba@gmail.com','07032890669',3,'active',NULL,'$2y$10$2vcByDIcmrubH1SyFdE4c.yyqiauXriGYGQlr71YifszS.QwGE8WW',NULL,NULL,NULL,NULL,NULL,'2020-10-12 06:59:28','2020-10-12 06:59:28'),(4,'Willy Processsing','willy@willyprocessing.com','07122852185258',1,'new',NULL,'$2y$10$lD2eL1rtM0X2qysvNZ7I4ePrVd.4gYUS83lfQKq6p/LLpSN360va2',NULL,NULL,NULL,NULL,NULL,'2020-10-12 18:18:40','2020-10-12 18:18:40'),(5,'procurement','procurement@dukia.com','123456789',2,'new',NULL,'$2y$10$QrRjSfF6PlOtTcaM19IlXe5wD/nQrOudu85cpTglRn0fHD7hpvLGu',NULL,NULL,NULL,NULL,NULL,'2020-10-12 18:19:33','2020-10-12 18:19:33'),(6,'\'Seyi Ogidiolu','ogidioluco@newwavesecosystem.com','08073351737',1,'new',NULL,'$2y$10$q.kLlNZBUfHyODiVj3.Bcuy49Dpu9JSRBcsraRWIQGSW88P5uw9mC',NULL,NULL,NULL,NULL,NULL,'2020-10-12 19:05:26','2020-10-12 19:05:26'),(7,'TestLite','sammy@gmail.com','08166939205',2,'new',NULL,'$2y$10$LlAJqRWTZisAY8oSLPTsm.NLLe3//GQHyjkVyItTlnYd4UrZF2tui',NULL,NULL,NULL,NULL,NULL,'2020-10-12 20:56:12','2020-10-12 20:56:12'),(8,'Williams Olufemi','femiwily@gmail.com','08032158450',3,'new',NULL,'$2y$10$Kpg3bpn7mB9kngcB50RG.uWb84ivnnWPE2Ralq2TrzY0m2ArjB5NO',NULL,NULL,NULL,NULL,NULL,'2020-10-13 14:32:53','2020-10-13 14:32:53'),
INSERT INTO `users` VALUES (2,'\'Seyi Ogidiolu','kunleadeniyi@gmail.com','08068703822',3,' 2020-11-14 20:58:24',NULL,'$2y$10$jjuGxrqYWXcOLOQmym3BE.rQQ5Z/beDZfiRZluzXY0iZ8wtF8feMC',NULL,NULL,NULL,NULL,NULL,'2020-10-14 20:47:02','2020-10-15 22:30:50'),(3,'konn3ct','williamsos@newwavesecosystem.com','+2348033046408',2,'2021-10-16 11:04:41',NULL,'$2y$10$pJbFTLR0xya16ZshpHLFGu4AceCjM1jp5q9ehM9cc7NEDwKNAQ.6K',NULL,NULL,'4RPkbmQ6FcFTASLSFaq413uf8wQ03tJ39hjf80g83PsRFV5MiF02OIlgIJ34',NULL,NULL,'2020-10-15 13:33:24','2020-10-16 11:17:21');
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

-- Dump completed on 2020-10-18  4:57:27
