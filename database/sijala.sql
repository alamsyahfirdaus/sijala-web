-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for sijala
CREATE DATABASE IF NOT EXISTS `sijala` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sijala`;

-- Dumping structure for table sijala.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.cache: ~0 rows (approximately)

-- Dumping structure for table sijala.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.cache_locks: ~0 rows (approximately)

-- Dumping structure for table sijala.consultations
CREATE TABLE IF NOT EXISTS `consultations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `caller_id` bigint unsigned NOT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `counseling_session_id` bigint unsigned DEFAULT NULL,
  `channel_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` text COLLATE utf8mb4_unicode_ci,
  `call_type` enum('video','audio') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'video',
  `status` enum('calling','ringing','accepted','rejected','ended','missed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'calling',
  `started_at` timestamp NULL DEFAULT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  `duration` int NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_screen_sharing` tinyint(1) NOT NULL DEFAULT '0',
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `consultations_channel_name_unique` (`channel_name`),
  KEY `consultations_caller_id_foreign` (`caller_id`),
  KEY `consultations_receiver_id_foreign` (`receiver_id`),
  KEY `consultations_counseling_session_id_foreign` (`counseling_session_id`),
  CONSTRAINT `consultations_caller_id_foreign` FOREIGN KEY (`caller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consultations_counseling_session_id_foreign` FOREIGN KEY (`counseling_session_id`) REFERENCES `counseling_sessions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `consultations_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.consultations: ~0 rows (approximately)

-- Dumping structure for table sijala.consultation_messages
CREATE TABLE IF NOT EXISTS `consultation_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `consultation_id` bigint unsigned NOT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `message_type` enum('text','image','file','audio','video','system') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `message` text COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `consultation_messages_consultation_id_foreign` (`consultation_id`),
  KEY `consultation_messages_sender_id_foreign` (`sender_id`),
  CONSTRAINT `consultation_messages_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consultation_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.consultation_messages: ~0 rows (approximately)

-- Dumping structure for table sijala.consultation_presentations
CREATE TABLE IF NOT EXISTS `consultation_presentations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `consultation_id` bigint unsigned NOT NULL,
  `education_content_id` bigint unsigned NOT NULL,
  `presenter_id` bigint unsigned NOT NULL COMMENT 'User yang membagikan presentasi',
  `status` enum('playing','paused','stopped') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'playing',
  `current_position` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `metadata` json DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `consultation_presentations_consultation_id_is_active_index` (`consultation_id`,`is_active`),
  KEY `consultation_presentations_consultation_id_status_index` (`consultation_id`,`status`),
  KEY `consultation_presentations_education_content_id_index` (`education_content_id`),
  KEY `consultation_presentations_presenter_id_index` (`presenter_id`),
  KEY `consultation_presentations_started_at_index` (`started_at`),
  CONSTRAINT `consultation_presentations_consultation_id_foreign` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consultation_presentations_education_content_id_foreign` FOREIGN KEY (`education_content_id`) REFERENCES `education_contents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consultation_presentations_presenter_id_foreign` FOREIGN KEY (`presenter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.consultation_presentations: ~0 rows (approximately)

-- Dumping structure for table sijala.counseling_chats
CREATE TABLE IF NOT EXISTS `counseling_chats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `counseling_session_id` bigint unsigned NOT NULL,
  `status` enum('active','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_chat_session` (`counseling_session_id`),
  CONSTRAINT `fk_chat_session` FOREIGN KEY (`counseling_session_id`) REFERENCES `counseling_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.counseling_chats: ~0 rows (approximately)
INSERT INTO `counseling_chats` (`id`, `counseling_session_id`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'active', '2026-07-16 12:38:53', '2026-07-16 12:38:53');

-- Dumping structure for table sijala.counseling_chat_messages
CREATE TABLE IF NOT EXISTS `counseling_chat_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `counseling_chat_id` bigint unsigned NOT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `sender_role` enum('konselor','konseli') COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_chat_message_chat` (`counseling_chat_id`),
  KEY `fk_chat_message_sender` (`sender_id`),
  CONSTRAINT `fk_chat_message_chat` FOREIGN KEY (`counseling_chat_id`) REFERENCES `counseling_chats` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_chat_message_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.counseling_chat_messages: ~8 rows (approximately)
INSERT INTO `counseling_chat_messages` (`id`, `counseling_chat_id`, `sender_id`, `sender_role`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
	(1, 1, 4, 'konseli', 'Assalamu\'alaikum, saya ingin berkonsultasi mengenai kondisi orang tua saya yang akhir-akhir ini sering hampir terjatuh.', 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(2, 1, 3, 'konselor', 'Wa\'alaikumsalam. Tentu, silakan ceritakan kondisi lansia yang sedang Anda dampingi.', 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(3, 1, 4, 'konseli', 'Usia beliau 70 tahun dan sering kehilangan keseimbangan saat berjalan, terutama setelah bangun tidur.', 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(4, 1, 3, 'konselor', 'Apakah beliau memiliki riwayat jatuh sebelumnya atau menggunakan alat bantu berjalan?', 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(5, 1, 4, 'konseli', 'Ya, sekitar dua bulan lalu beliau sempat terpeleset di kamar mandi.', 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(6, 1, 3, 'konselor', 'Baik. Saya sarankan memasang pegangan di kamar mandi, menggunakan alas anti-slip, dan memastikan pencahayaan rumah cukup terang.', 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(7, 1, 4, 'konseli', 'Baik, terima kasih. Apakah ada latihan yang bisa dilakukan untuk meningkatkan keseimbangan?', 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(8, 1, 3, 'konselor', 'Ada. Lansia dapat melakukan latihan berdiri satu kaki dengan bantuan pegangan, jalan tumit-ke-jari, dan senam ringan secara rutin.', 0, '2026-07-16 12:38:53', '2026-07-16 12:38:53');

-- Dumping structure for table sijala.counseling_resume_options
CREATE TABLE IF NOT EXISTS `counseling_resume_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sort_order` smallint unsigned NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `counseling_resume_options_category_id_foreign` (`category_id`),
  CONSTRAINT `counseling_resume_options_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `counseling_resume_options` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.counseling_resume_options: ~33 rows (approximately)
INSERT INTO `counseling_resume_options` (`id`, `category_id`, `title`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'Interaksi Awal', NULL, 1, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(2, NULL, 'Skrining Awal', NULL, 2, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(3, NULL, 'Persiapan Konseling', NULL, 3, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(4, NULL, 'Pelaksanaan Konseling', NULL, 4, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(5, NULL, 'Edukasi', NULL, 5, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(6, NULL, 'Evaluasi', NULL, 6, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(7, NULL, 'Skrining Akhir', NULL, 7, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(8, 1, 'Interaksi awal (bina trust)', NULL, 1, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(9, 1, 'Menjelaskan tujuan konseling', NULL, 2, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(10, 2, 'Melakukan skrining risiko jatuh', NULL, 1, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(11, 2, 'Melakukan asesmen pemberdayaan keluarga', NULL, 2, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(12, 2, 'Mengidentifikasi masalah lain dalam merawat lansia', NULL, 3, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(13, 3, 'Membuat kontrak waktu konseling', NULL, 1, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(14, 4, 'Melakukan konseling melalui chat', NULL, 1, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(15, 4, 'Melakukan konseling melalui video', NULL, 2, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(16, 4, 'Melakukan konseling melalui telepon / WhatsApp', NULL, 3, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(17, 4, 'Menentukan masalah yang dihadapi keluarga', NULL, 4, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(18, 4, 'Mencari alternatif solusi', NULL, 5, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(19, 4, 'Memberikan edukasi dan dukungan (support)', NULL, 6, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(20, 5, 'Edukasi pencegahan jatuh pada lansia', NULL, 1, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(21, 5, 'Edukasi masalah psikologis dalam merawat lansia', NULL, 2, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(22, 5, 'Edukasi komunikasi dengan lansia', NULL, 3, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(23, 5, 'Edukasi penggunaan alat bantu jalan yang benar', NULL, 4, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(24, 5, 'Edukasi latihan keseimbangan (Otago)', NULL, 5, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(25, 5, 'Edukasi WSP', NULL, 6, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(26, 6, 'Eksplorasi perasaan keluarga', NULL, 1, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(27, 6, 'Evaluasi pengetahuan pencegahan jatuh', NULL, 2, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(28, 6, 'Evaluasi pengetahuan masalah psikologis dalam merawat lansia', NULL, 3, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(29, 6, 'Evaluasi pengetahuan komunikasi dengan lansia', NULL, 4, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(30, 6, 'Evaluasi pengetahuan latihan keseimbangan (Otago)', NULL, 5, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(31, 6, 'Evaluasi pengetahuan penggunaan alat bantu jalan', NULL, 6, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(32, 7, 'Melakukan skrining risiko jatuh akhir', NULL, 1, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(33, 7, 'Melakukan asesmen pemberdayaan keluarga akhir', NULL, 2, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53');

-- Dumping structure for table sijala.counseling_sessions
CREATE TABLE IF NOT EXISTS `counseling_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `elderly_counselee_id` bigint unsigned NOT NULL,
  `counselor_id` bigint unsigned DEFAULT NULL COMMENT 'user_id',
  `service_mode` enum('chat','video') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'chat',
  `status` enum('ongoing','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ongoing',
  `note` text COLLATE utf8mb4_unicode_ci,
  `resume` json DEFAULT NULL,
  `is_latest` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `counseling_sessions_elderly_counselee_id_foreign` (`elderly_counselee_id`),
  KEY `counseling_sessions_counselor_id_foreign` (`counselor_id`),
  CONSTRAINT `counseling_sessions_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `counseling_sessions_elderly_counselee_id_foreign` FOREIGN KEY (`elderly_counselee_id`) REFERENCES `elderly_counselee` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.counseling_sessions: ~0 rows (approximately)
INSERT INTO `counseling_sessions` (`id`, `elderly_counselee_id`, `counselor_id`, `service_mode`, `status`, `note`, `resume`, `is_latest`, `created_at`, `updated_at`) VALUES
	(1, 1, 3, 'chat', 'ongoing', NULL, NULL, 0, '2026-07-16 12:38:53', '2026-07-16 12:38:53');

-- Dumping structure for table sijala.districts
CREATE TABLE IF NOT EXISTS `districts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regency_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `districts_regency_id_foreign` (`regency_id`),
  CONSTRAINT `districts_regency_id_foreign` FOREIGN KEY (`regency_id`) REFERENCES `regencies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.districts: ~3 rows (approximately)
INSERT INTO `districts` (`id`, `code`, `name`, `regency_id`, `created_at`, `updated_at`) VALUES
	(1, '3277010', 'Cimahi Utara', 1, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(2, '3277020', 'Cimahi Tengah', 1, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(3, '3277030', 'Cimahi Selatan', 1, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(4, '3278010', 'Bungursari', 3, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(5, '3278020', 'Cibeureum', 3, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(6, '3278030', 'Cipedes', 3, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(7, '3278040', 'Indihiang', 3, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(8, '3278050', 'Kawalu', 3, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(9, '3278060', 'Mangkubumi', 3, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(10, '3278070', 'Purbaratu', 3, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(11, '3278080', 'Tamansari', 3, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(12, '3278090', 'Tawang', 3, '2026-07-16 12:38:51', '2026-07-16 12:38:51');

-- Dumping structure for table sijala.education_contents
CREATE TABLE IF NOT EXISTS `education_contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('video','poster') COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.education_contents: ~9 rows (approximately)
INSERT INTO `education_contents` (`id`, `title`, `category`, `file_path`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Pencegahan Jatuh pada Lansia', 'poster', 'pencegahan_jatuh.jpg', 'Materi edukasi mengenai faktor risiko jatuh dan langkah-langkah pencegahan yang dapat dilakukan di rumah maupun lingkungan sekitar.', 1, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(2, 'Penggunaan Alat Bantu Jalan yang Benar', 'poster', 'alat_bantu_jalan.jpg', 'Panduan penggunaan alat bantu jalan seperti tongkat, walker, dan kursi roda agar lansia dapat bergerak dengan aman dan mandiri.', 1, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(3, 'Cara Aman Menggunakan Tongkat untuk Lansia', 'video', 'https://youtu.be/Kb-YJe_OpS4?si=_Sq8b6zVnyqXL4oQ', 'Video panduan penggunaan tongkat yang benar untuk membantu menjaga keseimbangan dan mengurangi risiko jatuh saat berjalan.', 1, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(4, 'Cara Aman Menggunakan Walker untuk Lansia', 'video', 'https://youtu.be/ZbtdHBsXnC8?si=1iV997UFNs-Jjdbh', 'Video edukasi tentang cara menggunakan walker secara tepat agar lansia dapat berjalan lebih stabil dan aman.', 1, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(5, 'Cara Aman Menggunakan Kursi Roda untuk Lansia', 'video', 'https://youtu.be/1CpcOTZlka8?si=41KB6uQH9C6xBsMC', 'Panduan penggunaan kursi roda yang aman, termasuk teknik berpindah posisi dan langkah keselamatan saat digunakan.', 1, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(6, 'Latihan Keseimbangan untuk Mencegah Risiko Jatuh pada Lansia', 'video', 'https://youtu.be/5UlD1n-6QqU?si=Ob8-FhRPJo3zj0v2', 'Video latihan sederhana untuk meningkatkan kekuatan otot, koordinasi, dan keseimbangan tubuh lansia.', 1, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(7, 'Latihan Keseimbangan bagi Lansia Pengguna Kursi Roda', 'video', 'https://youtu.be/oPG9EYbCp9w?si=SttP98VbxMXOkLAy', 'Latihan gerak yang dirancang khusus bagi lansia pengguna kursi roda untuk menjaga fleksibilitas dan keseimbangan tubuh.', 1, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(8, 'Cara Berkomunikasi dengan Lansia', 'poster', 'komunikasi_lansia.jpg', 'Materi edukasi mengenai teknik komunikasi yang sabar, empatik, dan efektif untuk membangun hubungan yang baik dengan lansia.', 1, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(9, 'Masalah Psikologis Keluarga dalam Merawat Lansia', 'poster', 'psikologis_keluarga.jpg', 'Materi edukasi tentang tantangan emosional yang dapat dialami keluarga serta strategi menghadapi stres dalam merawat lansia.', 1, '2026-07-16 12:38:52', '2026-07-16 12:38:52');

-- Dumping structure for table sijala.elderly_counselee
CREATE TABLE IF NOT EXISTS `elderly_counselee` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `counselee_id` bigint unsigned NOT NULL COMMENT 'user_id',
  `care_duration_months` int unsigned DEFAULT NULL,
  `elderly_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `elderly_gender` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `elderly_age` int unsigned DEFAULT NULL,
  `health_problems` text COLLATE utf8mb4_unicode_ci,
  `has_fallen` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `elderly_counselee_counselee_id_foreign` (`counselee_id`),
  CONSTRAINT `elderly_counselee_counselee_id_foreign` FOREIGN KEY (`counselee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.elderly_counselee: ~0 rows (approximately)
INSERT INTO `elderly_counselee` (`id`, `counselee_id`, `care_duration_months`, `elderly_name`, `elderly_gender`, `elderly_age`, `health_problems`, `has_fallen`, `created_at`, `updated_at`) VALUES
	(1, 3, 24, 'Ahmad Sudrajat', 'L', 70, NULL, NULL, '2026-07-16 12:38:52', '2026-07-16 12:38:52');

-- Dumping structure for table sijala.elderly_fall_risk_answers
CREATE TABLE IF NOT EXISTS `elderly_fall_risk_answers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `screening_id` bigint unsigned NOT NULL,
  `question_id` bigint unsigned NOT NULL,
  `answer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `score` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `elderly_fall_risk_answers_screening_id_foreign` (`screening_id`),
  KEY `elderly_fall_risk_answers_question_id_foreign` (`question_id`),
  CONSTRAINT `elderly_fall_risk_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `elderly_fall_risk_questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `elderly_fall_risk_answers_screening_id_foreign` FOREIGN KEY (`screening_id`) REFERENCES `elderly_fall_risk_screenings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.elderly_fall_risk_answers: ~0 rows (approximately)

-- Dumping structure for table sijala.elderly_fall_risk_questions
CREATE TABLE IF NOT EXISTS `elderly_fall_risk_questions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer_type` enum('yes_no','scale','number') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes_no',
  `score_yes` int DEFAULT NULL,
  `score_no` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.elderly_fall_risk_questions: ~12 rows (approximately)
INSERT INTO `elderly_fall_risk_questions` (`id`, `question`, `answer_type`, `score_yes`, `score_no`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
	(1, 'Apakah lansia pernah mengalami jatuh dalam 6 bulan terakhir?', 'yes_no', 2, 0, 1, 1, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(2, 'Apakah lansia menggunakan atau disarankan menggunakan tongkat atau alat bantu berjalan?', 'yes_no', 2, 0, 1, 2, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(3, 'Apakah lansia sering merasa tidak stabil saat berjalan?', 'yes_no', 1, 0, 1, 3, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(4, 'Apakah lansia sering berpegangan pada furnitur atau benda di sekitarnya saat berjalan di rumah?', 'yes_no', 1, 0, 1, 4, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(5, 'Apakah lansia merasa khawatir akan mengalami jatuh?', 'yes_no', 1, 0, 1, 5, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(6, 'Apakah lansia memerlukan bantuan tangan untuk berdiri dari posisi duduk?', 'yes_no', 1, 0, 1, 6, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(7, 'Apakah lansia mengalami kesulitan saat naik trotoar atau anak tangga?', 'yes_no', 1, 0, 1, 7, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(8, 'Apakah lansia sering terburu-buru menuju kamar mandi?', 'yes_no', 1, 0, 1, 8, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(9, 'Apakah lansia mengalami penurunan sensasi atau rasa pada kaki?', 'yes_no', 1, 0, 1, 9, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(10, 'Apakah obat yang dikonsumsi lansia menyebabkan pusing, mengantuk, atau mudah lelah?', 'yes_no', 1, 0, 1, 10, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(11, 'Apakah lansia mengonsumsi obat tidur atau obat yang memengaruhi suasana hati?', 'yes_no', 1, 0, 1, 11, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(12, 'Apakah lansia sering merasa sedih, murung, atau mengalami depresi?', 'yes_no', 1, 0, 1, 12, '2026-07-16 12:38:52', '2026-07-16 12:38:52');

-- Dumping structure for table sijala.elderly_fall_risk_screenings
CREATE TABLE IF NOT EXISTS `elderly_fall_risk_screenings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `counseling_session_id` bigint unsigned NOT NULL,
  `total_score` int unsigned DEFAULT NULL,
  `risk_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interpretation` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `elderly_fall_risk_screenings_counseling_session_id_foreign` (`counseling_session_id`),
  CONSTRAINT `elderly_fall_risk_screenings_counseling_session_id_foreign` FOREIGN KEY (`counseling_session_id`) REFERENCES `counseling_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.elderly_fall_risk_screenings: ~0 rows (approximately)

-- Dumping structure for table sijala.evaluations
CREATE TABLE IF NOT EXISTS `evaluations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `counseling_session_id` bigint unsigned NOT NULL,
  `evaluation_topic_id` bigint unsigned NOT NULL,
  `evaluation_type` enum('pre_test','post_test') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post_test',
  `total_questions` int NOT NULL DEFAULT '0',
  `correct_answers` int NOT NULL DEFAULT '0',
  `total_score` int NOT NULL DEFAULT '0',
  `percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interpretation` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_evaluation_session` (`counseling_session_id`),
  KEY `fk_evaluation_topic` (`evaluation_topic_id`),
  CONSTRAINT `fk_evaluation_session` FOREIGN KEY (`counseling_session_id`) REFERENCES `counseling_sessions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_evaluation_topic` FOREIGN KEY (`evaluation_topic_id`) REFERENCES `evaluation_topics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.evaluations: ~0 rows (approximately)

-- Dumping structure for table sijala.evaluation_answers
CREATE TABLE IF NOT EXISTS `evaluation_answers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `evaluation_id` bigint unsigned NOT NULL,
  `evaluation_question_id` bigint unsigned NOT NULL,
  `selected_answer` enum('a','b','c','d') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `score` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_eval_answer_eval` (`evaluation_id`),
  KEY `fk_eval_answer_question` (`evaluation_question_id`),
  CONSTRAINT `fk_eval_answer_eval` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_eval_answer_question` FOREIGN KEY (`evaluation_question_id`) REFERENCES `evaluation_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.evaluation_answers: ~0 rows (approximately)

-- Dumping structure for table sijala.evaluation_questions
CREATE TABLE IF NOT EXISTS `evaluation_questions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `evaluation_topic_id` bigint unsigned NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_a` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_b` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_c` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_d` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `correct_answer` enum('a','b','c','d') COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_eval_question_topic` (`evaluation_topic_id`),
  CONSTRAINT `fk_eval_question_topic` FOREIGN KEY (`evaluation_topic_id`) REFERENCES `evaluation_topics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.evaluation_questions: ~37 rows (approximately)
INSERT INTO `evaluation_questions` (`id`, `evaluation_topic_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `score`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Manakah yang termasuk faktor internal (dari dalam) penyebab jatuh pada lansia?', 'Lantai licin', 'Kabel listrik berserakan', 'Penurunan keseimbangan dan kekuatan otot', 'Pencahayaan buruk', 'c', 1, 1, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(2, 1, 'Berikut yang termasuk faktor lingkungan penyebab jatuh adalah …', 'Osteoporosis', 'Stroke', 'Diabetes', 'Karpet yang mudah bergeser', 'd', 1, 1, 2, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(3, 1, 'Salah satu cara keluarga mencegah risiko jatuh pada lansia adalah …', 'Membiarkan lansia berjalan sendiri tanpa pengawasan', 'Menyediakan lingkungan rumah yang aman', 'Mengurangi aktivitas fisik lansia sepenuhnya', 'Membatasi komunikasi dengan lansia', 'b', 1, 1, 3, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(4, 1, 'Apa yang harus dilakukan pertama kali saat lansia terjatuh?', 'Langsung memaksa lansia berdiri', 'Memberikan makanan dan minuman', 'Tetap tenang dan memeriksa kondisi lansia', 'Membiarkan lansia beristirahat sendiri', 'c', 1, 1, 4, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(5, 1, 'Bila dicurigai terdapat patah tulang atau cedera kepala setelah jatuh, tindakan yang tepat adalah …', 'Dipijat segera', 'Dibantu berjalan perlahan', 'Diberikan obat tidur', 'Segera dibawa ke fasilitas kesehatan', 'd', 1, 1, 5, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(6, 1, 'Perilaku berikut yang dapat meningkatkan risiko jatuh pada lansia adalah …', 'Bangun dari tempat tidur secara perlahan', 'Menggunakan alas kaki yang sesuai', 'Tergesa-gesa saat berjalan', 'Menggunakan pegangan tangan di kamar mandi', 'c', 1, 1, 6, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(7, 1, 'Mengapa latihan fisik dan latihan keseimbangan penting bagi lansia?', 'Membuat lansia cepat lelah', 'Menurunkan kekuatan otot', 'Membantu menjaga keseimbangan dan kekuatan tubuh', 'Mengurangi kemampuan berjalan', 'c', 1, 1, 7, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(8, 1, 'Pencegahan jatuh pada lansia terbukti dapat …', 'Mengurangi kemandirian lansia', 'Meningkatkan risiko cedera', 'Menurunkan kualitas hidup', 'Mempertahankan kemandirian lansia', 'd', 1, 1, 8, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(9, 2, 'Apa manfaat utama penggunaan alat bantu jalan pada lansia?', 'Membuat lansia lebih cepat berjalan', 'Mengurangi risiko jatuh dan meningkatkan stabilitas', 'Menghilangkan seluruh nyeri sendi', 'Membatasi aktivitas lansia', 'b', 1, 1, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(10, 2, 'Tongkat sebaiknya digunakan pada tangan yang …', 'Sama dengan kaki yang lemah', 'Paling kuat', 'Berlawanan dengan kaki yang lemah atau nyeri', 'Dominan digunakan sehari-hari', 'c', 1, 1, 2, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(11, 2, 'Tinggi tongkat yang benar adalah ketika pegangan tongkat …', 'Setinggi bahu', 'Sejajar lutut', 'Sejajar lipatan pergelangan tangan saat berdiri tegak', 'Setinggi pinggang', 'c', 1, 1, 3, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(12, 2, 'Saat berjalan menggunakan tongkat, langkah yang benar adalah …', 'Kaki kuat maju lebih dulu', 'Tongkat diangkat tinggi-tinggi', 'Tongkat dimajukan bersama kaki yang lemah', 'Tongkat diletakkan jauh di samping tubuh', 'c', 1, 1, 4, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(13, 2, 'Berikut ini yang harus diperiksa secara rutin pada walker adalah …', 'Warna walker', 'Berat walker', 'Bentuk pegangan walker', 'Karet, baut, roda, dan rem walker', 'd', 1, 1, 5, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(14, 2, 'Saat berdiri menggunakan walker, tindakan yang benar adalah …', 'Menarik walker agar tubuh terangkat', 'Berdiri sambil menarik walker', 'Dorong badan ke ujung kursi lalu berdiri dan pegang walker', 'Mengangkat walker terlebih dahulu', 'c', 1, 1, 6, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(15, 2, 'Cara berjalan yang benar menggunakan walker adalah …', 'Walker diangkat tinggi-tinggi', 'Walker didorong sedikit ke depan lalu kaki lemah melangkah terlebih dahulu', 'Kaki kuat melangkah jauh ke depan', 'Walker diseret sambil berjalan cepat', 'b', 1, 1, 7, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(16, 2, 'Banyak lansia jatuh saat menggunakan alat bantu jalan karena …', 'Lansia terlalu aktif', 'Alat bantu terlalu mahal', 'Alat bantu tidak pas atau cara penggunaan salah', 'Lansia kurang tidur', 'c', 1, 1, 8, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(17, 3, 'Apa yang dimaksud dengan validasi perasaan pada lansia?', 'Mengabaikan perasaan lansia', 'Menyalahkan perasaan lansia', 'Menghargai dan memahami perasaan lansia', 'Memaksa lansia mengikuti pendapat orang lain', 'c', 1, 1, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(18, 3, 'Dalam berkomunikasi dengan lansia, sikap yang paling penting adalah …', 'Berbicara dengan cepat', 'Menyimak dengan penuh perhatian', 'Memotong pembicaraan lansia', 'Mengubah topik pembicaraan terus-menerus', 'b', 1, 1, 2, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(19, 3, 'Nostalgia pada lansia biasanya berkaitan dengan …', 'Keinginan membeli barang baru', 'Cerita dan pengalaman masa lalu', 'Kemampuan bekerja berat', 'Aktivitas olahraga berat', 'b', 1, 1, 3, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(20, 3, 'Tujuan komunikasi yang baik dengan lansia adalah …', 'Membuat lansia merasa takut', 'Membatasi interaksi sosial lansia', 'Membantu lansia merasa dihargai dan nyaman', 'Menghindari percakapan dengan lansia', 'c', 1, 1, 4, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(21, 3, 'Saat lansia sedang bercerita, tindakan yang tepat adalah …', 'Menyela pembicaraan', 'Mengabaikan cerita', 'Mendengarkan dengan sabar', 'Meminta lansia berhenti berbicara', 'c', 1, 1, 5, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(22, 3, 'Berikut ini contoh komunikasi yang kurang baik pada lansia adalah …', 'Menggunakan bahasa yang sopan', 'Menatap lawan bicara saat berbicara', 'Berbicara sambil marah-marah', 'Memberi kesempatan lansia berbicara', 'c', 1, 1, 6, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(23, 3, 'Mengapa validasi perasaan penting bagi lansia?', 'Agar lansia merasa dihukum', 'Agar lansia merasa dihargai dan dipahami', 'Agar lansia lebih banyak diam', 'Agar komunikasi cepat selesai', 'b', 1, 1, 7, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(24, 3, 'Salah satu manfaat menyimak aktif pada lansia adalah …', 'Lansia merasa tidak diperhatikan', 'Mengurangi kepercayaan diri lansia', 'Membantu terciptanya hubungan yang baik', 'Membatasi komunikasi dengan keluarga', 'c', 1, 1, 8, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(25, 4, 'Apa faktor utama yang menyebabkan masalah psikologis pada keluarga yang merawat lansia menurut materi?', 'Dukungan sosial yang terlalu banyak', 'Tuntutan perawatan yang tinggi baik secara fisik maupun emosional', 'Lansia yang masih sangat mandiri', 'Waktu perawatan yang singkat', 'b', 1, 1, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(26, 4, 'Kondisi lansia seperti apa yang dapat menyebabkan waktu perawatan menjadi panjang dan memicu stres keluarga?', 'Lansia dengan flu ringan', 'Lansia dengan hobi berkebun', 'Kondisi demensia (pikun) atau disabilitas dengan ketergantungan tinggi', 'Lansia yang rutin berolahraga', 'c', 1, 1, 2, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(27, 4, 'Manakah di bawah ini yang termasuk dalam masalah psikologis yang sering dialami keluarga (caregiver)?', 'Peningkatan rasa percaya diri', 'Burnout (kelelahan hebat) dan depresi', 'Hubungan sosial yang makin luas', 'Tubuh yang terasa makin segar', 'b', 1, 1, 3, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(28, 4, 'Selain kecemasan, perasaan negatif apa yang sering muncul pada keluarga karena merasa tidak maksimal dalam merawat lansia?', 'Rasa bangga', 'Rasa bersalah', 'Rasa tidak peduli', 'Rasa tenang', 'b', 1, 1, 4, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(29, 4, 'Apa dampak dari masalah psikologis keluarga terhadap kualitas asuhan lansia?', 'Kualitas perawatan lansia menurun', 'Lansia menjadi lebih sehat', 'Perawatan menjadi lebih profesional', 'Tidak ada dampak pada lansia', 'a', 1, 1, 5, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(30, 4, 'Risiko berbahaya apa yang meningkat jika beban psikologis keluarga tidak segera ditangani?', 'Peningkatan ekonomi keluarga', 'Risiko kekerasan pada lansia (elder abuse)', 'Lansia menjadi lebih penurut', 'Komunikasi keluarga menjadi lebih lancar', 'b', 1, 1, 6, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(31, 4, 'Strategi apa yang disarankan untuk memberikan jeda istirahat sementara bagi keluarga yang merawat lansia?', 'Isolasi sosial', 'Pengabaian perawatan', 'Time-Out atau Respite Care (beristirahat sebentar, bergantian merawat lansia)', 'Manajemen konflik', 'c', 1, 1, 7, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(32, 4, 'Jika keluarga merasa sudah tidak mampu menangani tekanan emosional sendiri, langkah apa yang paling tepat sesuai strategi penanganan?', 'Menanggung beban sendiri agar tidak merepotkan orang lain', 'Berhenti merawat lansia sepenuhnya', 'Mencari bantuan profesional', 'Mengurangi komunikasi dengan anggota keluarga lain', 'c', 1, 1, 8, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(33, 5, 'Apa tujuan utama dari pelaksanaan latihan keseimbangan Otago bagi lansia?', 'Menurunkan berat badan secara drastis', 'Mencegah kejadian jatuh melalui peningkatan kekuatan otot dan keseimbangan', 'Mengobati penyakit jantung akut', 'Mengganti peran obat-obatan medis', 'b', 1, 1, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(34, 5, 'Berapa frekuensi dan durasi latihan keseimbangan yang disarankan dalam satu minggu?', 'Setiap hari selama 1 jam', 'Dua kali seminggu selama 10 menit', 'Tiga kali seminggu dengan durasi 20-30 menit', 'Satu kali seminggu selama 45 menit', 'c', 1, 1, 2, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(35, 5, 'Manakah di bawah ini yang merupakan indikasi lansia yang diperbolehkan melakukan latihan keseimbangan?', 'Lansia berusia 65 tahun ke atas yang mampu berdiri dan berjalan', 'Lansia yang menggunakan kursi roda sepenuhnya', 'Lansia yang mengalami gangguan kognitif berat', 'Lansia dengan masalah kardiovaskuler akut', 'a', 1, 1, 3, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(36, 5, 'Manakah kondisi berikut yang menjadi kontraindikasi (larangan) untuk melakukan latihan OEP?', 'Lansia yang ingin hidup mandiri', 'Lansia dengan nyeri akut atau gangguan muskuloskeletal (tulang dan otot) berat', 'Lansia yang merasa percaya diri', 'Lansia yang ingin meningkatkan kekuatan otot', 'b', 1, 1, 4, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(37, 5, 'Hal penting apa yang harus diperhatikan terkait keselamatan saat melakukan latihan di rumah?', 'Melakukan latihan di permukaan yang licin', 'Memakai alas kaki yang tidak licin dan berhenti jika merasa nyeri', 'Tetap memaksakan latihan meskipun merasa sangat lelah', 'Tidak perlu berkonsultasi dengan tenaga kesehatan meskipun ragu', 'b', 1, 1, 5, '2026-07-16 12:38:53', '2026-07-16 12:38:53');

-- Dumping structure for table sijala.evaluation_topics
CREATE TABLE IF NOT EXISTS `evaluation_topics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `topic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.evaluation_topics: ~4 rows (approximately)
INSERT INTO `evaluation_topics` (`id`, `topic`, `description`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
	(1, 'Pencegahan Jatuh pada Lansia', 'Evaluasi mengenai pencegahan risiko jatuh pada lansia.', 1, 1, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(2, 'Penggunaan Alat Bantu Jalan yang Benar', 'Evaluasi mengenai penggunaan alat bantu jalan pada lansia.', 1, 2, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(3, 'Komunikasi Efektif dengan Lansia', 'Evaluasi mengenai komunikasi efektif dan empati terhadap lansia.', 1, 3, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(4, 'Masalah Psikologis dalam Merawat Lansia', 'Evaluasi mengenai masalah psikologis keluarga dalam merawat lansia.', 1, 4, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(5, 'Latihan Otago untuk Keseimbangan Lansia', 'Evaluasi mengenai latihan Otago untuk meningkatkan keseimbangan dan mengurangi risiko jatuh pada lansia.', 1, 5, '2026-07-16 12:38:53', '2026-07-16 12:38:53');

-- Dumping structure for table sijala.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table sijala.family_empowerment_answers
CREATE TABLE IF NOT EXISTS `family_empowerment_answers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `empowerment_id` bigint unsigned NOT NULL,
  `question_id` bigint unsigned NOT NULL,
  `answer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `score` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `family_empowerment_answers_question_id_foreign` (`question_id`),
  KEY `fk_fe_answers_assessment` (`empowerment_id`),
  CONSTRAINT `family_empowerment_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `family_empowerment_questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fe_answers_assessment` FOREIGN KEY (`empowerment_id`) REFERENCES `family_empowerment_assessments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.family_empowerment_answers: ~0 rows (approximately)

-- Dumping structure for table sijala.family_empowerment_assessments
CREATE TABLE IF NOT EXISTS `family_empowerment_assessments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `counseling_session_id` bigint unsigned NOT NULL,
  `total_score` int DEFAULT NULL,
  `empowerment_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interpretation` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `family_empowerment_assessments_counseling_session_id_foreign` (`counseling_session_id`),
  CONSTRAINT `family_empowerment_assessments_counseling_session_id_foreign` FOREIGN KEY (`counseling_session_id`) REFERENCES `counseling_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.family_empowerment_assessments: ~0 rows (approximately)

-- Dumping structure for table sijala.family_empowerment_questions
CREATE TABLE IF NOT EXISTS `family_empowerment_questions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dimension_id` bigint unsigned DEFAULT NULL,
  `item_number` tinyint unsigned DEFAULT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_favorable` tinyint(1) DEFAULT NULL,
  `order` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `family_empowerment_questions_dimension_id_foreign` (`dimension_id`),
  CONSTRAINT `family_empowerment_questions_dimension_id_foreign` FOREIGN KEY (`dimension_id`) REFERENCES `family_empowerment_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.family_empowerment_questions: ~40 rows (approximately)
INSERT INTO `family_empowerment_questions` (`id`, `dimension_id`, `item_number`, `question`, `is_favorable`, `order`, `created_at`, `updated_at`) VALUES
	(1, NULL, NULL, 'Kemampuan keluarga mengenal masalah', NULL, 1, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(2, 1, 1, 'Risiko jatuh pada lansia adalah kemungkinan lansia untuk terjatuh karena berbagai faktor, baik yang berasal dari diri lansia itu sendiri maupun dari lingkungannya.', 1, 2, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(3, 1, 2, 'Faktor usia, penyakit yang lama, otot yang lemah, keseimbangan yang tidak baik, dan pemakaian obat tertentu merupakan faktor yang dapat menimbulkan risiko jatuh.', 1, 3, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(4, 1, 3, 'Lansia sering tersandung merupakan tanda ketidakseimbangan lansia yang dapat berpotensi jatuh.', 1, 4, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(5, 1, 4, 'Lansia yang pernah mengalami jatuh akan berisiko mengalami jatuh kembali.', 1, 5, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(6, 1, 5, 'Kondisi lingkungan rumah, seperti pencahayaan kurang, lantai licin, atau rumah yang berantakan, dapat menjadi penyebab jatuh pada lansia.', 1, 6, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(7, 1, 6, 'Pusing pada lansia tidak berkaitan dengan risiko jatuh.', 0, 7, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(8, 1, 7, 'Lansia dengan gangguan penglihatan berisiko jatuh.', 1, 8, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(9, NULL, NULL, 'Kemampuan keluarga mengambil keputusan', NULL, 9, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(10, 9, 8, 'Bagi keluarga kami, risiko jatuh lansia merupakan masalah yang serius.', 1, 10, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(11, 9, 9, 'Bagi keluarga kami, jatuh pada lansia dapat menyebabkan patah tulang, kecacatan, bahkan kematian.', 1, 11, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(12, 9, 10, 'Perlu tindakan segera ketika lansia mengalami jatuh.', 1, 12, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(13, 9, 11, 'Kami memutuskan melakukan upaya pencegahan jatuh, misalnya dengan memasang pegangan di kamar mandi dan mengawasi aktivitas lansia.', 1, 13, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(14, 9, 12, 'Keluarga menunda keputusan modifikasi rumah karena biaya tinggi meskipun risiko jatuh tinggi.', 0, 14, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(15, 9, 13, 'Keluarga ragu menerapkan latihan fisik karena takut lansia lelah.', 0, 15, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(16, NULL, NULL, 'Kemampuan keluarga merawat anggota keluarga yang sakit', NULL, 16, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(17, 16, 14, 'Saya melibatkan anggota keluarga yang lain dalam mencegah jatuh pada lansia.', 1, 17, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(18, 16, 15, 'Saya mengajarkan pada lansia latihan berjalan dan memakai alat bantu jalan yang benar agar terhindar dari jatuh.', 1, 18, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(19, 16, 16, 'Saya mengajarkan latihan keseimbangan pada lansia untuk mengurangi risiko jatuh.', 1, 19, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(20, 16, 17, 'Saya mengondisikan rumah yang aman untuk lansia agar terhindar dari jatuh, misalnya pencahayaan yang terang dan lantai tidak licin.', 1, 20, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(21, 16, 18, 'Saya mengabaikan kondisi anggota keluarga yang berisiko jatuh.', 0, 21, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(22, 16, 19, 'Saya membatasi aktivitas lansia secara berlebihan agar tidak jatuh.', 0, 22, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(23, 16, 20, 'Saya memantau obat yang dikonsumsi lansia dan memperhatikan kemungkinan efek samping seperti pusing.', 1, 23, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(24, 16, 21, 'Lansia yang pernah jatuh tidak perlu diawasi dalam beraktivitas.', 0, 24, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(25, NULL, NULL, 'Kemampuan keluarga memodifikasi lingkungan', NULL, 25, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(26, 25, 22, 'Saya menempatkan lansia dalam ruangan dengan pencahayaan yang cukup agar tidak jatuh.', 1, 26, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(27, 25, 23, 'Keluarga membatasi kebisingan agar lansia fokus berjalan.', 0, 27, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(28, 25, 24, 'Saya mencegah lantai basah dan licin agar lansia terhindar dari jatuh.', 1, 28, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(29, 25, 25, 'Pegangan rambatan diperlukan untuk mencegah jatuh.', 1, 29, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(30, 25, 26, 'Tidak perlu menata perabotan karena bukan penyebab jatuh.', 0, 30, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(31, 25, 27, 'Tidak masalah kabel listrik berserakan karena bukan penyebab jatuh lansia.', 0, 31, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(32, 25, 28, 'Kami sering lupa membersihkan area basah di dapur atau kamar mandi.', 0, 32, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(33, 25, 29, 'Menumpuk barang di lorong tidak berkaitan dengan risiko jatuh lansia.', 0, 33, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(34, NULL, NULL, 'Kemampuan keluarga memanfaatkan fasilitas kesehatan', NULL, 34, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(35, 34, 30, 'Saya mengantar lansia ke puskesmas untuk kontrol penyakit lansia.', 1, 35, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(36, 34, 31, 'Pemeriksaan kesehatan lansia secara rutin dapat mencegah risiko jatuh.', 1, 36, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(37, 34, 32, 'Pemeriksaan risiko jatuh dilakukan di fasilitas kesehatan.', 1, 37, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(38, 34, 33, 'Saya tidak memeriksakan kesehatan lansia ke fasilitas kesehatan karena jauh.', 0, 38, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(39, 34, 34, 'Keluarga kurang percaya pada tenaga kesehatan terkait pencegahan jatuh.', 0, 39, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(40, 34, 35, 'Saya membawa lansia yang berisiko jatuh ke pengobatan alternatif sebagai pengganti pengobatan medis.', 0, 40, '2026-07-16 12:38:52', '2026-07-16 12:38:52');

-- Dumping structure for table sijala.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.jobs: ~0 rows (approximately)

-- Dumping structure for table sijala.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.job_batches: ~0 rows (approximately)

-- Dumping structure for table sijala.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.migrations: ~0 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_01_30_104145_create_provinces_table', 1),
	(5, '2026_01_30_104616_create_regencies_table', 1),
	(6, '2026_01_30_105553_create_districts_table', 1),
	(7, '2026_01_30_112541_create_villages_table', 1),
	(8, '2026_01_30_114624_create_puskesmas_table', 1),
	(9, '2026_01_30_173323_add_village_id_to_users_table', 1),
	(10, '2026_01_30_174329_create_elderly_table', 1),
	(11, '2026_01_30_175456_create_counseling_sessions_table', 1),
	(12, '2026_01_31_171331_create_education_contents_table', 1),
	(13, '2026_01_31_172305_create_elderly_fall_risk_questions_table', 1),
	(14, '2026_01_31_172747_create_elderly_fall_risk_screenings_table', 1),
	(15, '2026_01_31_172945_create_elderly_fall_risk_answers_table', 1),
	(16, '2026_01_31_175601_create_family_empowerment_questions_table', 1),
	(17, '2026_01_31_175614_create_family_empowerment_assessments_table', 1),
	(18, '2026_01_31_175634_create_family_empowerment_answers_table', 1),
	(19, '2026_01_31_182731_create_counseling_chats_table', 1),
	(20, '2026_01_31_182805_create_counseling_chat_messages_table', 1),
	(21, '2026_01_31_183556_create_qa_questions_table', 1),
	(22, '2026_01_31_184213_create_qa_answers_table', 1),
	(23, '2026_05_17_183124_create_evaluation_topics_table', 1),
	(24, '2026_05_17_183208_create_evaluation_questions_table', 1),
	(25, '2026_05_17_183222_create_evaluations_table', 1),
	(26, '2026_05_17_183237_create_evaluation_answers_table', 1),
	(27, '2026_05_27_104246_create_consultations_table', 1),
	(28, '2026_05_27_104949_create_consultation_messages_table', 1),
	(29, '2026_05_27_111239_create_notifications_table', 1),
	(30, '2026_05_27_113126_create_user_devices_table', 1),
	(31, '2026_06_29_184259_create_consultation_presentations_table', 1),
	(32, '2026_07_14_113245_create_counseling_resume_options_table', 1);

-- Dumping structure for table sijala.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `type` enum('incoming_call','call_accepted','missed_call','message','consultation','reminder','system') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'system',
  `data` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.notifications: ~0 rows (approximately)

-- Dumping structure for table sijala.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table sijala.provinces
CREATE TABLE IF NOT EXISTS `provinces` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.provinces: ~3 rows (approximately)
INSERT INTO `provinces` (`id`, `code`, `name`, `created_at`, `updated_at`) VALUES
	(1, '31', 'DKI Jakarta', '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(2, '32', 'Jawa Barat', '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(3, '33', 'Jawa Tengah', '2026-07-16 12:38:51', '2026-07-16 12:38:51');

-- Dumping structure for table sijala.puskesmas
CREATE TABLE IF NOT EXISTS `puskesmas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `village_id` bigint unsigned NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `head_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1 = Rawat Inap, 2 = Non Rawat Inap',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `puskesmas_village_id_foreign` (`village_id`),
  CONSTRAINT `puskesmas_village_id_foreign` FOREIGN KEY (`village_id`) REFERENCES `villages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.puskesmas: ~13 rows (approximately)
INSERT INTO `puskesmas` (`id`, `code`, `name`, `village_id`, `address`, `phone`, `email`, `head_name`, `service_type`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'P3277010201', 'Cimahi Selatan', 13, 'Jl. Baros No. 16 Kel. Utama, Kec. Cimahi Selatan', '0226629300', NULL, NULL, '2', NULL, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(2, 'P3277010202', 'Melong Asih', 14, 'Jl. Melong Blok I No. 1 Kel. Melong, Kec. Cimahi Selatan', '0226023833', NULL, NULL, '2', NULL, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(3, 'P3277010203', 'Cibeureum', 15, 'Jl. Raya Cibeureum No. 125 Kel. Cibeureum, Kec. Cimahi Selatan', '0226075623', NULL, NULL, '2', NULL, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(4, 'P3277010204', 'Cibeber', 11, 'Jl. Puri Fajar No. 1 Kel. Cibeber, Kec. Cimahi Selatan', '0226628983', NULL, NULL, '2', NULL, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(5, 'P3277010205', 'Leuwigajah', 12, 'Jl. Kihapit Barat RT 08 RW 09 Kel. Leuwigajah, Kec. Cimahi Selatan', '0226677649', NULL, NULL, '2', NULL, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(6, 'P3277010206', 'Melong Tengah', 14, 'Jl. Melong Tengah RT 02 RW 04 Kel. Melong, Kec. Cimahi Selatan', '0226004991', NULL, NULL, '2', NULL, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(7, 'P3277020201', 'Cimahi Tengah', 9, 'Jl. Djulaeha Karmita No. 5 Kel. Cimahi, Kec. Cimahi Tengah', '0226630213', NULL, NULL, '2', NULL, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(8, 'P3277020202', 'Cigugur Tengah', 6, 'Jl. Abdul Halim No. 199 Kel. Cigugur Tengah, Kec. Cimahi Tengah', '0226632343', NULL, NULL, '2', NULL, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(9, 'P3277020203', 'Padasuka', 10, 'Jl. Kebon Manggu Kel. Padasuka, Kec. Cimahi Tengah', '0226621701', NULL, NULL, '2', NULL, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(10, 'P3277030201', 'Cimahi Utara', 3, 'Jl. Serut No. 16 Kel. Cibabat, Kec. Cimahi Utara', '0226631547', NULL, NULL, '2', NULL, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(11, 'P3277030202', 'Cipageran', 1, 'Jl. Bobojong No. 148 Kel. Cipageran, Kec. Cimahi Utara', '0226627698', NULL, NULL, '2', NULL, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(12, 'P3277030203', 'Pasirkaliki', 4, 'Jl. Cidamar Kel. Pasirkaliki, Kec. Cimahi Utara', '0222021935', NULL, NULL, '2', NULL, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(13, 'P3277030204', 'Citeureup', 2, 'Kel. Citeureup, Kec. Cimahi Utara', '0226628983', NULL, NULL, '2', NULL, '2026-07-16 12:38:51', '2026-07-16 12:38:51');

-- Dumping structure for table sijala.qa_answers
CREATE TABLE IF NOT EXISTS `qa_answers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `qa_question_id` bigint unsigned NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'answered_by',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_qa_answer_question` (`qa_question_id`),
  KEY `fk_qa_answer_user` (`user_id`),
  CONSTRAINT `fk_qa_answer_question` FOREIGN KEY (`qa_question_id`) REFERENCES `qa_questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_qa_answer_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.qa_answers: ~20 rows (approximately)
INSERT INTO `qa_answers` (`id`, `qa_question_id`, `answer`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Jatuh adalah kejadian ketika seseorang tidak sengaja kehilangan keseimbangan dan berpindah ke posisi lebih rendah, seperti lantai, baik dengan atau tanpa cedera.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(2, 2, 'Jatuh dapat menyebabkan cedera seperti patah tulang, trauma kepala, penurunan kemandirian, hingga meningkatkan risiko kematian.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(3, 3, 'Ya, semua lansia memiliki risiko jatuh, namun tingkat risikonya berbeda tergantung kondisi kesehatan, lingkungan, dan aktivitas.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(4, 4, 'Tanda risiko tinggi jatuh antara lain sering pusing, berjalan tidak stabil, pernah jatuh sebelumnya, atau membutuhkan alat bantu berjalan.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(5, 5, 'Penyebabnya multifaktor, seperti gangguan keseimbangan, kelemahan otot, penyakit kronis, dan faktor lingkungan.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(6, 6, 'Penurunan kekuatan otot, penglihatan kabur, dan gangguan saraf dapat membuat lansia mudah kehilangan keseimbangan.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(7, 7, 'Ya, beberapa obat seperti penenang, antihipertensi, atau obat tidur dapat menyebabkan pusing atau mengantuk.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(8, 8, 'Lantai licin, pencahayaan buruk, dan barang berserakan dapat meningkatkan risiko tersandung atau terpeleset.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(9, 9, 'Ya, penyakit seperti stroke, diabetes, arthritis, dan gangguan jantung dapat meningkatkan risiko jatuh.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(10, 10, 'Takut jatuh justru dapat membuat lansia lebih kaku saat berjalan dan meningkatkan risiko jatuh.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(11, 11, 'Menjaga rumah tetap rapi, memasang pegangan di kamar mandi, dan memastikan pencahayaan cukup dapat membantu mencegah jatuh.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(12, 12, 'Sangat penting. Latihan keseimbangan dan kekuatan otot seperti senam lansia dapat mengurangi risiko jatuh.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(13, 13, 'Alat bantu seperti tongkat atau walker sangat membantu meningkatkan stabilitas saat berjalan.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(14, 14, 'Keluarga dapat mengawasi aktivitas lansia, membantu kebutuhan sehari-hari, dan memastikan lingkungan tetap aman.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(15, 15, 'Ya, pemeriksaan rutin diperlukan untuk memantau tekanan darah, penglihatan, dan efek samping obat.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(16, 16, 'Gunakan lampu yang cukup terang, terutama di malam hari dan pada area seperti tangga dan kamar mandi.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(17, 17, 'Gunakan sepatu atau sandal dengan sol tidak licin dan pas di kaki.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(18, 18, 'Gunakan alas anti-slip, pegangan dinding, dan hindari lantai basah di kamar mandi.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(19, 19, 'Segera evaluasi penyebab jatuh, konsultasikan ke tenaga kesehatan, dan lakukan langkah pencegahan ulang.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(20, 20, 'Cari bantuan tenaga kesehatan jika lansia sering jatuh, mengalami cedera, atau menunjukkan perubahan keseimbangan dan kesadaran.', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53');

-- Dumping structure for table sijala.qa_questions
CREATE TABLE IF NOT EXISTS `qa_questions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('open','answered') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'created_by',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_qa_question_user` (`user_id`),
  CONSTRAINT `fk_qa_question_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.qa_questions: ~20 rows (approximately)
INSERT INTO `qa_questions` (`id`, `title`, `question`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 'Pengertian Jatuh pada Lansia', 'Apa yang dimaksud dengan jatuh pada lansia?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(2, 'Bahaya Jatuh pada Lansia', 'Mengapa jatuh pada lansia menjadi masalah serius?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(3, 'Risiko Jatuh pada Semua Lansia', 'Apakah semua lansia berisiko jatuh?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(4, 'Tanda Risiko Tinggi Jatuh', 'Apa tanda lansia berisiko tinggi jatuh?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(5, 'Penyebab Utama Jatuh pada Lansia', 'Apa penyebab utama jatuh pada lansia?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(6, 'Pengaruh Faktor Fisik', 'Bagaimana faktor fisik memengaruhi risiko jatuh?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(7, 'Obat-obatan dan Risiko Jatuh', 'Apakah obat-obatan bisa menyebabkan jatuh?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(8, 'Pengaruh Kondisi Lingkungan', 'Bagaimana kondisi lingkungan berpengaruh?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(9, 'Penyakit yang Meningkatkan Risiko Jatuh', 'Apakah penyakit tertentu meningkatkan risiko jatuh?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(10, 'Faktor Psikologis dan Risiko Jatuh', 'Apakah faktor psikologis berpengaruh?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(11, 'Pencegahan Jatuh di Rumah', 'Bagaimana cara mencegah jatuh pada lansia di rumah?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(12, 'Pentingnya Olahraga', 'Apakah olahraga penting untuk mencegah jatuh?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(13, 'Penggunaan Alat Bantu Jalan', 'Seberapa penting penggunaan alat bantu jalan?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(14, 'Peran Keluarga', 'Bagaimana peran keluarga dalam pencegahan jatuh?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(15, 'Pentingnya Pemeriksaan Rutin', 'Apakah pemeriksaan kesehatan rutin diperlukan?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(16, 'Pencahayaan Rumah yang Aman', 'Bagaimana mengatur pencahayaan rumah yang aman?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(17, 'Alas Kaki yang Aman', 'Apa alas kaki yang aman untuk lansia?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(18, 'Pencegahan Jatuh di Kamar Mandi', 'Bagaimana mencegah jatuh saat di kamar mandi?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(19, 'Tindakan Setelah Pernah Jatuh', 'Apa yang harus dilakukan jika lansia sudah pernah jatuh?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53'),
	(20, 'Kapan Harus ke Tenaga Kesehatan', 'Kapan harus mencari bantuan tenaga kesehatan?', 'answered', NULL, '2026-07-16 12:38:53', '2026-07-16 12:38:53');

-- Dumping structure for table sijala.regencies
CREATE TABLE IF NOT EXISTS `regencies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `regencies_province_id_foreign` (`province_id`),
  CONSTRAINT `regencies_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.regencies: ~3 rows (approximately)
INSERT INTO `regencies` (`id`, `code`, `name`, `province_id`, `created_at`, `updated_at`) VALUES
	(1, '3277', 'Kota Cimahi', 2, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(2, '3206', 'Kabupaten Tasikmalaya', 2, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(3, '3278', 'Kota Tasikmalaya', 2, '2026-07-16 12:38:51', '2026-07-16 12:38:51');

-- Dumping structure for table sijala.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.sessions: ~0 rows (approximately)

-- Dumping structure for table sijala.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','konselor','konseli') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `puskesmas_id` bigint unsigned DEFAULT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `education` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_puskesmas_id_foreign` (`puskesmas_id`),
  CONSTRAINT `users_puskesmas_id_foreign` FOREIGN KEY (`puskesmas_id`) REFERENCES `puskesmas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.users: ~4 rows (approximately)
INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `phone`, `gender`, `photo`, `birth_place`, `birth_date`, `puskesmas_id`, `occupation`, `education`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Alamsyah Firdaus', 'alamsyahfirdaus', 'alamsyah.firdaus.af31@gmail.com', NULL, '$2y$12$M.W7NwlARlvQ6hZvbRQfiO8QYUx27mLATKC.PCRupi6b39pH6EGOC', NULL, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
	(2, 'Admin Jaga Lansia', 'admin', 'admin@jagalansia.id', NULL, '$2y$12$JpmBJKf4ZXFKBaln6Fx3B.vV/bCrG82tHIg1S7uywWs8oDQxYbB1i', NULL, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(3, 'Lina Safarina', 'konselor', 'konselor@jagalansia.id', NULL, '$2y$12$g0CPqljmmno7bWcAWU7RoOSNCqPngymGhikPwKxJky9Cz6k0.QSP.', NULL, 'konselor', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, '2026-07-16 12:38:52', '2026-07-16 12:38:52'),
	(4, 'Alamsyah Firdaus', 'konseli', 'konseli@jagalansia.id', NULL, '$2y$12$XvXDhp27mA3LhvlMG.s2QOfqCugDrBqWqKVsPJe7U9G1ZXbwOH8Oq', NULL, 'konseli', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, '2026-07-16 12:38:52', '2026-07-16 12:38:52');

-- Dumping structure for table sijala.user_devices
CREATE TABLE IF NOT EXISTS `user_devices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `fcm_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_type` enum('android','ios','web','tablet') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'android',
  `device_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_version` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_devices_user_id_foreign` (`user_id`),
  CONSTRAINT `user_devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.user_devices: ~0 rows (approximately)

-- Dumping structure for table sijala.villages
CREATE TABLE IF NOT EXISTS `villages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `villages_district_id_foreign` (`district_id`),
  CONSTRAINT `villages_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sijala.villages: ~21 rows (approximately)
INSERT INTO `villages` (`id`, `code`, `name`, `district_id`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'Cipageran', 1, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(2, NULL, 'Citeureup', 1, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(3, NULL, 'Cibabat', 1, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(4, NULL, 'Pasirkaliki', 1, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(5, NULL, 'Baros', 2, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(6, NULL, 'Cigugur Tengah', 2, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(7, NULL, 'Karangmekar', 2, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(8, NULL, 'Setiamanah', 2, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(9, NULL, 'Cimahi', 2, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(10, NULL, 'Padasuka', 2, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(11, NULL, 'Cibeber', 3, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(12, NULL, 'Leuwigajah', 3, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(13, NULL, 'Utama', 3, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(14, NULL, 'Melong', 3, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(15, NULL, 'Cibeureum', 3, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(16, NULL, 'Ciherang', 5, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(17, NULL, 'Setiawargi', 5, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(18, NULL, 'Kersamenak', 5, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(19, NULL, 'Tawangsari', 12, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(20, NULL, 'Lengkongsari', 12, '2026-07-16 12:38:51', '2026-07-16 12:38:51'),
	(21, NULL, 'Cikalang', 12, '2026-07-16 12:38:51', '2026-07-16 12:38:51');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
