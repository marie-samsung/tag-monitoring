-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2026 at 03:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tag_monitoring`
--

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` varchar(10) NOT NULL,
  `updated_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `updated_at` datetime DEFAULT NULL,
  `created_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `created_at` datetime DEFAULT NULL,
  `deleted_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'UAE', 'AE', 0, NULL, 0, NULL, 0, NULL),
(2, 'Qatar', 'QA', 0, NULL, 0, NULL, 0, NULL),
(3, 'Oman', 'OM', 0, NULL, 0, NULL, 0, NULL),
(4, 'Kuwait', 'KW', 0, NULL, 0, NULL, 0, NULL),
(5, 'Bahrin', 'BH', 0, NULL, 0, NULL, 0, NULL),
(6, 'Saudi Arabia', 'SA', 0, NULL, 0, NULL, 0, NULL),
(7, 'Turkey', 'TR', 0, NULL, 0, NULL, 0, NULL),
(8, 'Israel', 'IL', 0, NULL, 0, NULL, 0, NULL),
(9, 'Palestine', 'PS', 0, NULL, 0, NULL, 0, NULL),
(10, 'Levant', 'LEVANT', 0, NULL, 0, NULL, 0, NULL),
(11, 'Jordan', 'JO', 0, NULL, 0, NULL, 0, NULL),
(12, 'Iraq', 'IQ', 0, NULL, 0, NULL, 0, NULL),
(13, 'Kurdistan', 'KU', 0, NULL, 0, NULL, 0, NULL),
(14, 'Lebanon', 'LB', 0, NULL, 0, NULL, 0, NULL),
(15, 'Egypt', 'EG', 0, NULL, 0, NULL, 0, NULL),
(16, 'North Africa', 'N_AFRICA', 0, NULL, 0, NULL, 0, NULL),
(17, 'Morocco', 'MA', 0, NULL, 0, NULL, 0, NULL),
(18, 'Pakistan', 'PK', 0, NULL, 0, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jira_history`
--

CREATE TABLE `jira_history` (
  `id` int(11) NOT NULL,
  `ticket_id` varchar(150) NOT NULL,
  `type` varchar(150) NOT NULL,
  `comments` varchar(500) DEFAULT NULL,
  `tag_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Reference to ''tags'' table',
  `property_id` int(11) NOT NULL DEFAULT 0,
  `subsidiary_id` int(11) NOT NULL DEFAULT 0,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `file_path` varchar(200) DEFAULT NULL,
  `created_by` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jira_history`
--

INSERT INTO `jira_history` (`id`, `ticket_id`, `type`, `comments`, `tag_id`, `property_id`, `subsidiary_id`, `is_active`, `file_path`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'GTA-31600', '2', '1', 1, 17, 4, 1, NULL, 0, '2026-05-04 12:56:45', 0, '2026-05-04 12:56:45'),
(2, 'GTA-31599', '3', '1', 1, 17, 4, 1, NULL, 0, '2026-05-05 13:11:34', 0, '2026-05-05 13:11:34'),
(3, 'GTA-32613', '2', '2', 2, 1, 1, 1, NULL, 0, '2026-05-05 13:14:38', 0, '2026-05-05 13:14:38'),
(4, 'GTA-32613', '2', '3', 3, 2, 1, 1, NULL, 0, '2026-05-05 13:15:27', 0, '2026-05-05 13:15:27'),
(5, 'GTA-32613', '2', '4,5', 4, 4, 1, 1, NULL, 0, '2026-05-05 13:16:40', 0, '2026-05-05 13:16:40'),
(6, 'GTA-32613', '2', '4,5', 5, 4, 1, 1, NULL, 0, '2026-05-05 13:16:40', 0, '2026-05-05 13:16:40'),
(7, 'GTA-32613', '2', '6,7', 6, 6, 1, 1, NULL, 0, '2026-05-05 13:17:17', 0, '2026-05-05 13:17:17'),
(8, 'GTA-32613', '2', '6,7', 7, 6, 1, 1, NULL, 0, '2026-05-05 13:17:17', 0, '2026-05-05 13:17:17'),
(9, 'GTA-33100', '3', '2', 2, 1, 1, 1, NULL, 0, '2026-05-05 13:18:56', 0, '2026-05-05 13:18:56'),
(10, 'GTA-33100', '3', '3', 3, 2, 1, 1, NULL, 0, '2026-05-05 13:19:03', 0, '2026-05-05 13:19:03'),
(11, 'GTA-33100', '3', '4,5', 4, 4, 1, 1, NULL, 0, '2026-05-05 13:19:11', 0, '2026-05-05 13:19:11'),
(12, 'GTA-33100', '3', '4,5', 5, 4, 1, 1, NULL, 0, '2026-05-05 13:19:11', 0, '2026-05-05 13:19:11'),
(13, 'GTA-33100', '3', '6,7', 6, 6, 1, 1, NULL, 0, '2026-05-05 13:19:16', 0, '2026-05-05 13:19:16'),
(14, 'GTA-33100', '3', '6,7', 7, 6, 1, 1, NULL, 0, '2026-05-05 13:19:16', 0, '2026-05-05 13:19:16'),
(15, 'GTA-33614', '2', '8', 8, 25, 6, 1, NULL, 0, '2026-05-05 13:25:18', 0, '2026-05-05 13:25:18'),
(16, 'GTA-33474', '3', '8', 8, 25, 6, 1, NULL, 0, '2026-05-05 13:26:26', 0, '2026-05-05 13:26:26'),
(17, 'GTA-33617', '2', '9', 9, 34, 6, 1, NULL, 0, '2026-05-05 13:28:03', 0, '2026-05-05 13:28:03'),
(18, 'GTA-33617', '2', '10', 10, 32, 6, 1, NULL, 0, '2026-05-05 13:28:40', 0, '2026-05-05 13:28:40'),
(19, 'GTA-33608', '3', '9', 9, 34, 6, 1, NULL, 0, '2026-05-05 13:31:53', 0, '2026-05-05 13:31:53'),
(20, 'GTA-33609', '3', '10', 10, 32, 6, 1, NULL, 0, '2026-05-05 13:38:36', 0, '2026-05-05 13:38:36'),
(21, 'GTA-33640', '2', '11', 11, 22, 6, 1, NULL, 0, '2026-05-05 13:39:36', 0, '2026-05-05 13:39:36'),
(22, 'GTA-33640', '2', '12', 12, 30, 6, 1, NULL, 0, '2026-05-05 13:40:25', 0, '2026-05-05 13:40:25'),
(23, 'GTA-33610', '3', '11', 11, 22, 6, 1, NULL, 0, '2026-05-05 13:41:14', 0, '2026-05-05 13:41:14'),
(24, 'GTA-33610', '3', '12', 12, 30, 6, 1, NULL, 0, '2026-05-05 13:41:22', 0, '2026-05-05 13:41:22'),
(25, 'GTA-33642', '2', '13', 13, 23, 6, 1, NULL, 0, '2026-05-06 12:53:29', 0, '2026-05-06 12:53:29'),
(26, 'GTA-33642', '2', '14', 14, 31, 6, 1, NULL, 0, '2026-05-06 12:54:08', 0, '2026-05-06 12:54:08'),
(27, 'GTA-33678', '3', '13', 13, 23, 6, 1, NULL, 0, '2026-05-06 13:01:26', 0, '2026-05-06 13:01:26'),
(28, 'GTA-33678', '3', '14', 14, 31, 6, 1, NULL, 0, '2026-05-06 13:01:33', 0, '2026-05-06 13:01:33'),
(29, 'GTA-34118', '2', '15,16', 15, 39, 8, 1, NULL, 0, '2026-05-06 13:04:37', 0, '2026-05-06 13:04:37'),
(30, 'GTA-34118', '2', '15,16', 16, 39, 8, 1, NULL, 0, '2026-05-06 13:04:37', 0, '2026-05-06 13:04:37'),
(31, 'GTA-34118', '2', '17,18', 17, 42, 8, 1, NULL, 0, '2026-05-06 13:05:50', 0, '2026-05-06 13:05:50'),
(32, 'GTA-34118', '2', '17,18', 18, 42, 8, 1, NULL, 0, '2026-05-06 13:05:50', 0, '2026-05-06 13:05:50'),
(33, 'GTA-34565', '3', '15,16', 15, 39, 8, 1, NULL, 0, '2026-05-06 13:06:13', 0, '2026-05-06 13:06:13'),
(34, 'GTA-34565', '3', '15,16', 16, 39, 8, 1, NULL, 0, '2026-05-06 13:06:13', 0, '2026-05-06 13:06:13'),
(35, 'GTA-34565', '3', '17,18', 17, 42, 8, 1, NULL, 0, '2026-05-06 13:06:20', 0, '2026-05-06 13:06:20'),
(36, 'GTA-34565', '3', '17,18', 18, 42, 8, 1, NULL, 0, '2026-05-06 13:06:20', 0, '2026-05-06 13:06:20'),
(37, 'GTA-34564', '2', '19', 19, 17, 4, 1, NULL, 0, '2026-05-06 13:11:07', 0, '2026-05-06 13:11:07'),
(38, 'GTA-34578', '3', '19', 19, 17, 4, 1, NULL, 0, '2026-05-06 13:11:44', 0, '2026-05-06 13:11:44'),
(39, 'GTA-34656', '2', '20', 20, 3, 1, 1, NULL, 0, '2026-05-06 13:13:31', 0, '2026-05-06 13:13:31'),
(40, 'GTA-34796', '3', '20', 20, 3, 1, 1, NULL, 0, '2026-05-06 13:13:39', 0, '2026-05-06 13:13:39'),
(41, 'GTA-36017', '2', '21', 21, 22, 6, 1, NULL, 0, '2026-05-06 13:16:13', 0, '2026-05-06 13:16:13'),
(42, 'GTA-36055', '3', '21', 21, 22, 6, 1, NULL, 0, '2026-05-06 13:16:21', 0, '2026-05-06 13:16:21'),
(43, 'GTA-36055', '2', '22', 22, 30, 6, 1, NULL, 0, '2026-05-06 13:17:26', 0, '2026-05-06 13:17:26'),
(44, 'GTA-36055', '3', '22', 22, 30, 6, 1, NULL, 0, '2026-05-06 13:17:45', 0, '2026-05-06 13:17:45'),
(45, 'GTA-36092', '2', '23', 23, 34, 6, 1, NULL, 0, '2026-05-06 13:19:10', 0, '2026-05-06 13:19:10'),
(46, 'GTA-36092', '3', '23', 23, 34, 6, 1, NULL, 0, '2026-05-06 13:20:00', 0, '2026-05-06 13:20:00'),
(47, 'GTA-36019', '2', '24', 24, 32, 6, 1, NULL, 0, '2026-05-06 13:20:36', 0, '2026-05-06 13:20:36'),
(48, 'GTA-36092', '3', '24', 24, 32, 6, 1, NULL, 0, '2026-05-06 13:20:43', 0, '2026-05-06 13:20:43'),
(49, 'GTA-31467', '3', '25', 25, 13, 3, 1, NULL, 0, '2026-05-06 13:25:03', 0, '2026-05-06 13:25:03'),
(50, 'GTA-31570', '3', '27', 27, 7, 2, 1, NULL, 0, '2026-05-06 13:41:56', 0, '2026-05-06 13:41:56'),
(51, 'GTA-31570', '3', '28', 28, 10, 2, 1, NULL, 0, '2026-05-06 13:42:45', 0, '2026-05-06 13:42:45'),
(52, 'GTA-31574', '3', '29', 29, 8, 2, 1, NULL, 0, '2026-05-06 13:43:24', 0, '2026-05-06 13:43:24'),
(53, 'GTA-31574', '3', '30', 30, 12, 2, 1, NULL, 0, '2026-05-06 13:43:53', 0, '2026-05-06 13:43:53'),
(54, 'GTA-31701', '3', '31', 31, 17, 4, 1, NULL, 0, '2026-05-06 13:46:15', 0, '2026-05-06 13:46:15'),
(55, 'GTA-31701', '3', '32', 32, 20, 4, 1, NULL, 0, '2026-05-06 13:46:54', 0, '2026-05-06 13:46:54'),
(56, 'GTA-31718', '3', '33', 33, 43, 9, 1, NULL, 0, '2026-05-06 13:48:15', 0, '2026-05-06 13:48:15'),
(57, 'GTA-31718', '3', '34', 34, 46, 9, 1, NULL, 0, '2026-05-06 13:48:42', 0, '2026-05-06 13:48:42'),
(58, 'GTA-36107', '2', '36', 36, 7, 2, 1, NULL, 0, '2026-05-07 12:41:01', 0, '2026-05-07 12:41:01'),
(59, 'GTA-36107', '2', '37', 37, 8, 2, 1, NULL, 0, '2026-05-07 12:43:00', 0, '2026-05-07 12:43:00');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `updated_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `updated_at` datetime DEFAULT NULL,
  `created_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `created_at` datetime DEFAULT NULL,
  `deleted_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'EN', 0, NULL, 0, NULL, 0, NULL),
(2, 'AR', 0, NULL, 0, NULL, 0, NULL),
(3, 'TR', 0, NULL, 0, NULL, 0, NULL),
(4, 'IL', 0, NULL, 0, NULL, 0, NULL),
(5, 'FR', 0, NULL, 0, NULL, 0, NULL),
(6, 'PS', 0, NULL, 0, NULL, 0, NULL),
(7, 'KU', 0, NULL, 0, NULL, 0, NULL),
(8, 'LB', 0, NULL, 0, NULL, 0, NULL),
(9, 'EG', 0, NULL, 0, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `updated_by` tinyint(4) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL,
  `created_by` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `deleted_by` tinyint(4) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'Samsung', 0, '2026-05-05 13:25:10', 0, '2026-05-05 13:25:10', NULL, NULL),
(2, 'RTBHouse', 0, '2026-05-06 13:04:28', 0, '2026-05-06 13:04:28', NULL, NULL),
(3, 'Dentsu', 0, '2026-05-06 13:16:06', 0, '2026-05-06 13:16:06', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `is_epp` tinyint(4) NOT NULL DEFAULT 0,
  `is_shop` tinyint(4) NOT NULL DEFAULT 0,
  `subsidiary_id` int(11) NOT NULL COMMENT 'Refers to the subsidiaries table',
  `country_id` int(11) NOT NULL,
  `language_id` tinyint(4) NOT NULL DEFAULT 1,
  `website` varchar(200) NOT NULL,
  `ref_link` varchar(500) NOT NULL COMMENT 'Links to AL',
  `updated_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `updated_at` datetime DEFAULT NULL,
  `created_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `created_at` datetime DEFAULT NULL,
  `deleted_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `name`, `is_active`, `is_epp`, `is_shop`, `subsidiary_id`, `country_id`, `language_id`, `website`, `ref_link`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'ZP6_AE_AR-UAE(SGE) WebSDK', 1, 0, 0, 1, 1, 2, 'https://www.samsung.com/ae_ar', 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/overview', 0, NULL, 0, NULL, 0, NULL),
(2, 'ZP6_AE-UAE(SGE) WebSDK', 1, 0, 0, 1, 1, 1, 'https://www.samsung.com/ae', 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/overview', 0, NULL, 0, NULL, 0, NULL),
(3, 'zP6zEPP_AE-UAE(AA) WebSDK', 1, 1, 0, 1, 1, 1, 'https://www.samsung.com/ae/multistore/samsung_employee/', 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR66456bebda424fb3af85ef35ee171c71/overview', 0, NULL, 0, NULL, 0, NULL),
(4, 'ZS_Hybris_AE_AR-UAE (Shop Section) WebSDK\r\n', 1, 0, 1, 1, 1, 2, 'https://shop.samsung.com/ae_ar', 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/overview', 0, NULL, 0, NULL, 0, NULL),
(5, 'ZS_Hybris_AE-UAE EPP (Shop Section) WebSDK', 1, 1, 1, 1, 1, 1, 'https://www.samsung.com/ae_ar/multistore/samsung_employee/', 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR89cdc1c03c074927b97991df03ce53d7/overview', 0, NULL, 0, NULL, 0, NULL),
(6, 'ZS_Hybris_AE-UAE (Shop Section) WebSDK', 1, 0, 1, 1, 1, 1, 'https://shop.samsung.com/ae', 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/overview', 0, NULL, 0, NULL, 0, NULL),
(7, 'ZP6_SA_EN-Saudi Arabia(SESAR) WebSDK', 1, 0, 0, 2, 6, 1, 'https://www.samsung.com/sa_en', 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/overview', 0, NULL, 0, NULL, 0, NULL),
(8, 'ZP6_SA-Saudi Arabia(SESAR) WebSDK', 1, 0, 0, 2, 6, 2, 'https://www.samsung.com/sa', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 08:50:04', 0, '2026-04-02 08:50:04', 0, NULL),
(9, 'zP6zEPP_SA-Saudi Arabia(AA) WebSDK', 1, 1, 0, 2, 6, 2, 'https://www.samsung.com/sa/multistore/', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRd8728de7d28842398b71295ca25d943d/rules', 0, '2026-04-02 10:56:45', 0, '2026-04-02 10:56:45', 0, NULL),
(10, 'ZS_Hybris_SA_EN-Saudi Arabia (Shop Section) WebSDK', 1, 0, 1, 2, 6, 1, 'https://shop.samsung.com/sa_en/', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 10:57:14', 0, '2026-04-02 10:57:14', 0, NULL),
(11, 'ZS_Hybris_SA-Saudi Arabia EPP (Shop Section) WebSDK', 1, 1, 1, 2, 6, 2, 'https://shop.samsung.com/sa/multistore/sa_epp/saudistudents/auth/multistore', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR93185e03772e427aa40c9b187922f475/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 10:57:45', 0, '2026-04-02 10:57:45', 0, NULL),
(12, 'ZS_Hybris_SA-Saudi Arabia (Shop Section) WebSDK', 1, 0, 1, 2, 6, 2, 'https://shop.samsung.com/sa/', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 10:59:00', 0, '2026-04-02 10:59:00', 0, NULL),
(13, 'ZP6_TR-Turkey WebSDK', 1, 0, 0, 3, 7, 3, 'https://www.samsung.com/tr', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR6bfef153a2dd4445b50f1d75982684b8/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 10:59:57', 0, '2026-04-02 10:59:57', 0, NULL),
(14, 'zP6zEPP_TR-Turkey(AA) WebSDK', 1, 1, 0, 3, 7, 3, 'https://www.samsung.com/tr/multistore', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf8dc3f08cfd341188fb86091e62b374d/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:00:38', 0, '2026-04-02 11:00:38', 0, NULL),
(15, 'ZS_Hybris_TR-Turkey EPP (Shop Section) WebSDK', 1, 1, 1, 3, 7, 3, 'https://shop.samsung.com/tr/multistore/trepp/tr_internal_epp/login/multistore', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR37aea4f52e284fa384c3dd7ba76f44c4/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:01:08', 0, '2026-04-02 11:01:08', 0, NULL),
(16, 'ZS_Hybris_TR-Turkey (Shop Section) WebSDK', 1, 0, 1, 3, 7, 3, 'https://shop.samsung.com/tr', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRffb0b6442ddd47769aad5467c2e4064f/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:01:38', 0, '2026-04-02 11:01:38', 0, NULL),
(17, 'ZP6_IL-Israel WebSDK', 1, 0, 0, 4, 8, 4, 'https://www.samsung.com/il', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7defa9d2597c4d32803af3ed6c46f659/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=2', 0, '2026-04-02 11:02:50', 0, '2026-04-02 11:02:50', 0, NULL),
(18, 'zP6zEPP_IL-Israel(AA) WebSDK', 1, 1, 0, 4, 8, 4, 'https://www.samsung.com/il/multistore/il_meitar/', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR02a11b1d20644c09b8b8c0a4f71b05b5/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:05:21', 0, '2026-04-02 11:05:21', 0, NULL),
(19, 'ZS_Hybris_IL-Israel EPP (Shop Section) WebSDK', 1, 1, 1, 4, 8, 4, 'https://shop.samsung.com/il/multistore/il_epp/samsung_employees/', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR885b6775f5764d378cc8291cef919795/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:08:01', 0, '2026-04-02 11:08:01', 0, NULL),
(20, 'ZS_Hybris_IL-Israel (Shop Section) WebSDK', 1, 0, 1, 4, 8, 4, 'https://shop.samsung.com/il', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR95e8380805264175be518bd10f5c2cda/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:08:39', 0, '2026-04-02 11:08:39', 0, NULL),
(21, 'ZP6_PS-Palestine WebSDK', 1, 0, 0, 5, 9, 6, 'https://www.samsung.com/ps', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf20225ba053e4a5c9c9926fa991089ab/rules', 0, '2026-04-02 11:09:46', 0, '2026-04-02 11:09:46', 0, NULL),
(22, 'zP6_IQ_AR-Iraq WebSDK', 1, 0, 0, 6, 12, 2, 'https://www.samsung.com/iq_ar', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf20225ba053e4a5c9c9926fa991089ab/rules', 0, '2026-04-02 11:10:38', 0, '2026-04-02 11:10:38', 0, NULL),
(23, 'zP6_IQ_KU-Kurdistan WebSDK', 1, 0, 0, 6, 13, 7, 'https://www.samsung.com/iq_ku', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR46f04dd062074bb2a482e2b7e9b132fe/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:12:46', 0, '2026-04-02 11:12:46', 0, NULL),
(24, 'zP6_LB-Lebanon WebSDK', 1, 0, 0, 6, 14, 8, 'https://www.samsung.com/lb', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRab3523e662044e63973ec19de6e82e6d/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:20:40', 0, '2026-04-02 11:20:40', 0, NULL),
(25, 'ZP6_LEVANT_AR-Levant(SELV) WebSDK', 1, 0, 0, 6, 10, 2, 'https://www.samsung.com/levant_ar', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:21:16', 0, '2026-04-02 11:21:16', 0, NULL),
(26, 'ZP6_LEVANT-Levant(SELV) WebSDK', 1, 0, 0, 6, 10, 1, 'https://www.samsung.com/levant', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR08b2de963cc24f5cb79e01f819fd90c0/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:21:48', 0, '2026-04-02 11:21:48', 0, NULL),
(27, 'zP6zEPP_IQ_AR-Iraq(AA) WebSDK', 1, 1, 0, 6, 12, 2, 'https://www.samsung.com/iq_ar/multistore/iq_samsung_partner/', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR970ae49668d54b5bbc55d74e54d01f88/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:22:17', 0, '2026-04-02 11:22:17', 0, NULL),
(28, 'zP6zEPP_JO-Jordan(AA) WebSDK', 1, 1, 0, 6, 11, 1, 'https://www.samsung.com/levant/multistore/jo_samsung_employee/', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR64613b01294b47b58da5e58992960773/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:22:44', 0, '2026-04-02 11:22:44', 0, NULL),
(29, 'ZS_Hybris_IQ_AR-Iraq EPP (Shop Section) WebSDK', 1, 1, 1, 6, 12, 2, 'https://shop.samsung.com/iq_ar/multistore/iqepp/iq_samsung_partner/auth/multistore', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR64613b01294b47b58da5e58992960773/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:23:07', 0, '2026-04-02 11:23:07', 0, NULL),
(30, 'ZS_Hybris_IQ_AR-Iraq (Shop Section) WebSDK', 1, 0, 1, 6, 12, 2, 'https://shop.samsung.com/iq_ar', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules', 0, '2026-04-02 11:23:31', 0, '2026-04-02 11:23:31', 0, NULL),
(31, 'ZS_Hybris_IQ_KU-Kurdistan (Shop Section) WebSDK', 1, 0, 1, 6, 12, 7, 'https://shop.samsung.com/iq_ku', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRb2f83dfd81be410abe16709dbf56b08c/rules', 0, '2026-04-02 11:24:09', 0, '2026-04-02 11:24:09', 0, NULL),
(32, 'ZS_Hybris_JO_AR-Jordan (Shop Section) WebSDK', 1, 0, 1, 6, 11, 2, 'https://shop.samsung.com/jo_ar', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf28c4faf02284603896219ef6cb42fa2/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:24:32', 0, '2026-04-02 11:24:32', 0, NULL),
(33, 'ZS_Hybris_JO-Jordan EPP (Shop Section) WebSDK', 1, 1, 1, 6, 11, 1, 'https://shop.samsung.com/jo/multistore/joepp/jo_samsung_government/auth/multistore', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR2fe2ecc0f2664d599cbb5e58edacbdff/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:25:05', 0, '2026-04-02 11:25:05', 0, NULL),
(34, 'ZS_Hybris_JO-Jordan (Shop Section) WebSDK', 1, 0, 1, 6, 11, 1, 'https://shop.samsung.com/jo', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:25:34', 0, '2026-04-02 11:25:34', 0, NULL),
(35, 'ZP6_EG-Egypt WebSDK', 1, 0, 0, 7, 15, 9, 'https://www.samsung.com/eg', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc4ea06001baa496a85af80306c73dbb6/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:26:52', 0, '2026-04-02 11:26:52', 0, NULL),
(36, 'zP6zEPP_EG-Egypt(AA) WebSDK', 1, 1, 0, 7, 15, 9, 'https://www.samsung.com/eg/multistore/eg_nbe_employees', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR829b8f02ee5d4a5dbc9e816e94fb4b28/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:27:30', 0, '2026-04-02 11:27:30', 0, NULL),
(37, 'ZS_Hybris_EG-Egypt EPP (Shop Section) WebSDK', 1, 1, 1, 7, 15, 9, 'https://shop.samsung.com/eg/multistore/egepp/eg_nbe_employees/cart', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR975ae08dc0d54ef8988861815a84d11f/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:27:52', 0, '2026-04-02 11:27:52', 0, NULL),
(38, 'ZS_Hybris_EG-Egypt (Shop Section) WebSDK', 1, 0, 1, 7, 15, 9, 'https://shop.samsung.com/eg', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR72c3c4d394474dd0aaf703dba19ff998/rules', 0, '2026-04-02 11:28:17', 0, '2026-04-02 11:28:17', 0, NULL),
(39, 'ZP6_N_AFRICA-Africa WebSDK', 1, 0, 0, 8, 1, 1, 'https://www.samsung.com/n_africa', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRaf00914f9c194739b6e98de4b0f72380/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:28:44', 0, '2026-04-02 11:28:44', 0, NULL),
(40, 'zP6zEPP_N_AFRICA-Africa(AA) WebSDK', 1, 1, 0, 8, 16, 1, 'https://www.samsung.com/n_africa/multistore/ma_corporates', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR87ddb0fd7d0e4611ab2557083595ce20/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:30:28', 0, '2026-04-02 11:30:28', 0, NULL),
(41, 'ZS_Hybris_MA-Morocco EPP (Shop Section) WebSDK', 1, 1, 1, 8, 17, 1, 'https://shop.samsung.com/ma/multistore/maepp/ma_students/auth/multistore', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR1f72db8a7d5c49a287d913b941391d80/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:30:55', 0, '2026-04-02 11:30:55', 0, NULL),
(42, 'ZS_Hybris_MA-Morocco (Shop Section) WebSDK', 1, 0, 1, 8, 17, 1, 'https://shop.samsung.com/ma', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61cef25ff4eb400391e974558ec4c577/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:31:18', 0, '2026-04-02 11:31:18', 0, NULL),
(43, 'ZP6_PK-Pakistan WebSDK', 1, 0, 0, 9, 18, 1, 'https://www.samsung.com/pk', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4a4d53f5c57b4611a653d094fa1c523c/rules', 0, '2026-04-02 11:31:46', 0, '2026-04-02 11:31:46', 0, NULL),
(44, 'zP6zEPP_PK-Pakistan(AA) WebSDK', 1, 1, 0, 9, 1, 1, 'https://samsung.com/pk/multistore/pk_corporate/', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR14a762a6a33c4d608665a7d0e67d7e7a/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:32:07', 0, '2026-04-02 11:32:07', 0, NULL),
(45, 'ZS_Hybris_PK-Pakistan EPP (Shop Section) WebSDK', 1, 1, 1, 9, 18, 1, 'https://shop.samsung.com/pk/multistore/pkepp/pk_corporate/registration', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc0d1939c6cc84ef3be7b0ad0c5df94d4/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:32:33', 0, '2026-04-02 11:32:33', 0, NULL),
(46, 'ZS_Hybris_PK-Pakistan (Shop Section) WebSDK', 1, 0, 1, 9, 18, 1, 'https://shop.samsung.com/pk', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRfc76c7fe018d41e09006f4c3c0925588/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:32:54', 0, '2026-04-02 11:32:54', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `renewal_logs`
--

CREATE TABLE `renewal_logs` (
  `id` int(11) NOT NULL,
  `ticket_id` varchar(150) NOT NULL,
  `property_id` tinyint(4) NOT NULL,
  `library_name` varchar(150) NOT NULL,
  `vendor` varchar(500) NOT NULL,
  `al_end_date` date NOT NULL,
  `user` tinyint(4) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subsidiaries`
--

CREATE TABLE `subsidiaries` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `updated_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `updated_at` datetime DEFAULT NULL,
  `created_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `created_at` datetime DEFAULT NULL,
  `deleted_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subsidiaries`
--

INSERT INTO `subsidiaries` (`id`, `name`, `is_active`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'SGE', 1, 0, NULL, 0, NULL, 0, NULL),
(2, 'SESAR', 1, 0, NULL, 0, NULL, 0, NULL),
(3, 'SETK', 1, 0, NULL, 0, NULL, 0, NULL),
(4, 'SEIL', 1, 0, NULL, 0, NULL, 0, NULL),
(5, 'SEILPS', 1, 0, NULL, 0, NULL, 0, NULL),
(6, 'SELV', 1, 0, NULL, 0, NULL, 0, NULL),
(7, 'SEEG', 1, 0, NULL, 0, NULL, 0, NULL),
(8, 'SEMAG', 1, 0, NULL, 0, NULL, 0, NULL),
(9, 'SEPAK', 1, 0, NULL, 0, NULL, 0, NULL),
(10, 'MENAO', 1, 0, NULL, 0, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `property_id` int(11) NOT NULL COMMENT 'Refers to the properties table',
  `vendor_id` int(11) NOT NULL DEFAULT 0,
  `conditions` varchar(200) DEFAULT NULL COMMENT '(.*)',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Refer to ''Constants'' model for values',
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `al_end_date` date DEFAULT NULL,
  `requested_date` date DEFAULT NULL,
  `ref_link` varchar(500) NOT NULL COMMENT 'Links to AL',
  `requester_name` varchar(150) DEFAULT NULL,
  `requester_email` varchar(150) DEFAULT NULL,
  `requester_organization` tinyint(4) DEFAULT NULL,
  `new_tag_request` tinyint(4) DEFAULT NULL,
  `new_rule_request` tinyint(4) DEFAULT NULL,
  `updated_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `updated_at` datetime DEFAULT NULL,
  `created_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `created_at` datetime DEFAULT NULL,
  `deleted_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `property_id`, `vendor_id`, `conditions`, `status`, `is_active`, `al_end_date`, `requested_date`, `ref_link`, `requester_name`, `requester_email`, `requester_organization`, `new_tag_request`, `new_rule_request`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'LC-AEM-AD(Limy)-CM-ALL(WL)-SDSME-9802-48598677a5ff', 17, 1, NULL, 1, 1, '2026-05-23', NULL, 'https://www.samsung.com/', '', '', NULL, NULL, NULL, 0, '2026-05-05 13:12:23', 0, '2026-05-04 12:56:24', 0, NULL),
(2, 'LC-AEM-AD(Optimise)-CM-WL-SDSME-908b-732c0c7d27de', 1, 2, NULL, 1, 1, '2026-06-24', NULL, 'https://www.samsung.com/', '', '', NULL, NULL, NULL, 0, '2026-05-05 13:18:58', 0, '2026-05-05 13:14:20', 0, NULL),
(3, 'LC-AEM-AD(Optimise)-CM-WL-SDSME-908b-732c0c7d27de', 2, 2, NULL, 1, 1, '2026-06-24', NULL, 'https://www.samsung.com/', '', '', NULL, NULL, NULL, 0, '2026-05-05 13:19:04', 0, '2026-05-05 13:15:19', 0, NULL),
(4, 'LC-Shop-AD(Optimise)-CM-WL-SDSME-908b-732c0c7d27de', 4, 2, NULL, 1, 1, '2026-06-24', NULL, 'https://www.samsung.com/', '', '', NULL, NULL, NULL, 0, '2026-05-05 13:19:12', 0, '2026-05-05 13:16:18', 0, NULL),
(5, 'LC-Shop-AD(Optimise)-OrderConfirmation-WL-SDSME-908b-732c0c7d27de', 4, 2, NULL, 1, 1, '2026-06-24', NULL, 'https://www.samsung.com/', '', '', NULL, NULL, NULL, 0, '2026-05-05 13:19:12', 0, '2026-05-05 13:16:18', 0, NULL),
(6, 'LC-Shop-AD(Optimise)-CM-WL-SDSME-908b-732c0c7d27de', 6, 2, NULL, 1, 1, '2026-06-24', NULL, 'https://www.samsung.com/', '', '', NULL, NULL, NULL, 0, '2026-05-05 13:19:18', 0, '2026-05-05 13:17:14', 0, NULL),
(7, 'LC-Shop-AD(Optimise)-OrderConfirmation-WL-SDSME-908b-732c0c7d27de', 6, 2, NULL, 1, 1, '2026-06-24', NULL, 'https://www.samsung.com/', '', '', NULL, NULL, NULL, 0, '2026-05-05 13:19:18', 0, '2026-05-05 13:17:14', 0, NULL),
(8, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_9c9a-76ee9eb9debc', 25, 3, NULL, 1, 1, '2026-06-27', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-05 13:26:28', 0, '2026-05-05 13:25:10', 0, NULL),
(9, 'LC-Shop-AD(Contentsquare)-CM-WL-SDSME_9aaa-619dc6194dcf', 34, 3, NULL, 1, 1, '2026-06-27', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-05 13:31:57', 0, '2026-05-05 13:27:55', 0, NULL),
(10, 'LC-Shop-AD(Contentsquare)-CM-WL-SDSME_9c9a-76ee9eb9debc', 32, 3, NULL, 1, 1, '2026-06-27', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-05 13:38:44', 0, '2026-05-05 13:28:32', 0, NULL),
(11, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_989f-31ab99742fca', 22, 3, NULL, 1, 1, '2026-07-03', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-05 13:41:16', 0, '2026-05-05 13:39:29', 0, NULL),
(12, 'LC-Shop-AD(Contentsquare)-CM-WL-SDSME_989f-31ab99742fca', 30, 3, NULL, 1, 1, '2026-07-03', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-05 13:41:33', 0, '2026-05-05 13:40:17', 0, NULL),
(13, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_917e-717e3602620f', 23, 3, 'home|shop home|microsite|offer main|offer detail|search result|ai\\-search|product category detail|shop category|product finder|business product finder|product detail|flagship pdp|shop detail|business ', 1, 1, '2026-07-03', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR46f04dd062074bb2a482e2b7e9b132fe/rules/RL704b2f840e9f4933baf51078dc0c7c4c', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-06 13:01:28', 0, '2026-05-06 12:53:20', 0, NULL),
(14, 'LC-Shop-AD(Contentsquare)-CM-WL-SDSME_917e-717e3602620f', 31, 3, NULL, 1, 1, '2026-07-03', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-06 13:01:39', 0, '2026-05-06 12:54:01', 0, NULL),
(15, 'LC-AEM-AD(RTB)-BasketAdd-WL-SDSME_b2e4-3228e7ef555e', 39, 4, NULL, 1, 1, '2026-05-24', NULL, 'https://www.samsung.com/', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 2, NULL, NULL, 0, '2026-05-06 13:06:14', 0, '2026-05-06 13:04:28', 0, NULL),
(16, 'LC-AEM-AD(RTB)-ViewContent-WL-SDSME_b2e4-3228e7ef555e', 39, 4, NULL, 1, 1, '2026-05-24', NULL, 'https://www.samsung.com/', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 2, NULL, NULL, 0, '2026-05-06 13:06:14', 0, '2026-05-06 13:04:28', 0, NULL),
(17, 'LC-Shop-AD(RTB)-BasketAdd-WL-SDSME_b2e4-3228e7ef555e', 42, 4, NULL, 1, 1, '2026-05-24', NULL, 'https://www.samsung.com/', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 2, NULL, NULL, 0, '2026-05-06 13:06:22', 0, '2026-05-06 13:05:42', 0, NULL),
(18, 'LC-Shop-AD(RTB)-OrderConfirmation-WL-SDSME_b2e4-3228e7ef555e', 42, 4, NULL, 1, 1, '2026-05-24', NULL, 'https://www.samsung.com/', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 2, NULL, NULL, 0, '2026-05-06 13:06:22', 0, '2026-05-06 13:05:42', 0, NULL),
(19, 'LC-AEM-AD(Appsflyer)-CM-WL-SDSME_903d-af70610536c8', 17, 5, NULL, 1, 1, '2026-05-25', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-06 13:11:46', 0, '2026-05-06 13:10:59', 0, NULL),
(20, 'LC-AEM-AD(Firework)-CM-WL-SDSME_floating_video_player', 3, 6, NULL, 1, 1, '2026-06-01', NULL, 'https://www.samsung.com/', 'Shereen El Gendy', 'shereen.g@partner.samsung.com', 1, NULL, NULL, 0, '2026-05-06 13:13:41', 0, '2026-05-06 13:13:26', 0, NULL),
(21, 'LC-AEM-AD(Pinterest)-CM-WL-SDSME_2613528410439', 22, 7, NULL, 7, 1, '2026-07-05', NULL, 'https://www.samsung.com/', 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, NULL, NULL, 0, '2026-05-06 13:16:21', 0, '2026-05-06 13:16:06', 0, NULL),
(22, 'LC-Shop-AD(Pinterest)-CM-WL-SDSME_2613528410439', 30, 7, NULL, 7, 1, '2026-07-05', NULL, 'https://www.samsung.com/', 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, NULL, NULL, 0, '2026-05-06 13:17:45', 0, '2026-05-06 13:17:19', 0, NULL),
(23, 'LC-Shop-AD(Pinterest)-CM-WL-SDSME_2613638179718', 34, 7, NULL, 7, 1, '2026-07-06', NULL, 'https://www.samsung.com/', 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, NULL, NULL, 0, '2026-05-06 13:20:00', 0, '2026-05-06 13:18:58', 0, NULL),
(24, 'LC-Shop-AD(Pinterest)-CM-WL-SDSME_2613638179718', 32, 7, NULL, 7, 1, '2026-07-06', NULL, 'https://www.samsung.com/', 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, NULL, NULL, 0, '2026-05-06 13:20:43', 0, '2026-05-06 13:20:26', 0, NULL),
(25, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-8f7a-0b1cc84255a4', 13, 8, NULL, 1, 1, '2026-06-26', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-06 13:33:26', 0, '2026-05-06 13:24:45', 0, NULL),
(26, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-8f7a-0b1cc84255a4', 16, 8, NULL, 1, 1, '2026-06-26', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-06 13:36:17', 0, '2026-05-06 13:34:21', 0, NULL),
(27, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-8517-f38da36bcf66', 7, 8, NULL, 7, 1, '2026-07-03', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-06 13:41:50', 0, '2026-05-06 13:41:50', 0, NULL),
(28, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-8517-f38da36bcf66', 10, 8, NULL, 7, 1, '2026-07-03', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-06 13:42:38', 0, '2026-05-06 13:42:38', 0, NULL),
(29, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-8517-f38da36bcf66', 8, 8, NULL, 7, 1, '2026-07-03', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-06 13:43:18', 0, '2026-05-06 13:43:18', 0, NULL),
(30, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-8517-f38da36bcf66', 12, 8, NULL, 7, 1, '2026-07-03', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-06 13:43:47', 0, '2026-05-06 13:43:47', 0, NULL),
(31, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-8ae3-30060725acbc', 17, 8, NULL, 7, 1, '2026-07-05', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-06 13:46:05', 0, '2026-05-06 13:46:05', 0, NULL),
(32, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-8ae3-30060725acbc', 20, 8, NULL, 7, 1, '2026-07-05', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-06 13:46:47', 0, '2026-05-06 13:46:47', 0, NULL),
(33, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-b2a5-e04c11ee378e', 43, 8, NULL, 7, 1, '2026-07-06', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-06 13:48:02', 0, '2026-05-06 13:48:02', 0, NULL),
(34, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-b2a5-e04c11ee378e', 46, 8, NULL, 7, 1, '2026-07-06', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-06 13:48:34', 0, '2026-05-06 13:48:34', 0, NULL),
(35, 'LC-AEM-AD(Optimise)-CM-WL-SDSME_2361077-57144', 7, 2, NULL, 6, 1, '2026-07-08', NULL, 'https://www.samsung.com/', 'Toufic Alzaza', 'toufic.alzaza@dentsu.com', 3, NULL, NULL, 0, '2026-05-07 12:39:42', 0, '2026-05-07 12:39:42', 0, NULL),
(36, 'LC-AEM-AD(Optimise)-CM-WL-SDSME_2361077-57144', 7, 2, NULL, 6, 1, '2026-07-08', NULL, 'https://www.samsung.com/', 'Toufic Alzaza', 'toufic.alzaza@dentsu.com', 3, NULL, NULL, 0, '2026-05-07 12:39:42', 0, '2026-05-07 12:39:42', 0, NULL),
(37, 'LC-AEM-AD(Optimise)-CM-WL-SDSME_2361077-57144', 8, 2, NULL, 6, 1, '2026-07-08', NULL, 'https://www.samsung.com/', 'Toufic Alzaza', 'toufic.alzaza@dentsu.com', 3, NULL, NULL, 0, '2026-05-07 12:42:47', 0, '2026-05-07 12:42:47', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tag_requests`
--

CREATE TABLE `tag_requests` (
  `id` int(11) NOT NULL,
  `new_tag_ticket` varchar(150) DEFAULT NULL,
  `tags` varchar(200) NOT NULL,
  `property_id` tinyint(4) NOT NULL,
  `vendor_id` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT 'New Tag or New Rule',
  `new_rule_ticket` varchar(150) DEFAULT NULL,
  `requester_name` varchar(200) DEFAULT NULL,
  `requester_email` varchar(200) DEFAULT NULL,
  `requester_organization` tinyint(4) DEFAULT NULL,
  `updated_by` tinyint(4) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL,
  `created_by` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `deleted_by` tinyint(4) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tag_requests`
--

INSERT INTO `tag_requests` (`id`, `new_tag_ticket`, `tags`, `property_id`, `vendor_id`, `status`, `type`, `new_rule_ticket`, `requester_name`, `requester_email`, `requester_organization`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'GTA-31600', '1', 17, 1, 6, 2, 'GTA-31599', '', '', NULL, 0, '2026-05-05 13:12:23', 0, '2026-05-04 12:56:24', 0, NULL),
(2, 'GTA-32613', '2', 1, 2, 6, 2, 'GTA-33100', '', '', NULL, 0, '2026-05-05 13:18:58', 0, '2026-05-05 13:14:20', 0, NULL),
(3, 'GTA-32613', '3', 2, 2, 6, 2, 'GTA-33100', '', '', NULL, 0, '2026-05-05 13:19:04', 0, '2026-05-05 13:15:19', 0, NULL),
(4, 'GTA-32613', '4,5', 4, 2, 6, 2, 'GTA-33100', '', '', NULL, 0, '2026-05-05 13:19:12', 0, '2026-05-05 13:16:18', 0, NULL),
(5, 'GTA-32613', '6,7', 6, 2, 6, 2, 'GTA-33100', '', '', NULL, 0, '2026-05-05 13:19:18', 0, '2026-05-05 13:17:14', 0, NULL),
(6, 'GTA-33614', '8', 25, 3, 6, 2, 'GTA-33474', 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-05 13:26:28', 0, '2026-05-05 13:25:10', 0, NULL),
(7, 'GTA-33617', '9', 34, 3, 6, 2, 'GTA-33608', 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-05 13:31:57', 0, '2026-05-05 13:27:55', 0, NULL),
(8, 'GTA-33617', '10', 32, 3, 6, 2, 'GTA-33609', 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-05 13:38:44', 0, '2026-05-05 13:28:32', 0, NULL),
(9, 'GTA-33640', '11', 22, 3, 6, 2, 'GTA-33610', 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-05 13:41:16', 0, '2026-05-05 13:39:29', 0, NULL),
(10, 'GTA-33640', '12', 30, 3, 6, 2, 'GTA-33610', 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-05 13:41:33', 0, '2026-05-05 13:40:17', 0, NULL),
(11, 'GTA-33642', '13', 23, 3, 6, 2, 'GTA-33678', 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-06 13:01:28', 0, '2026-05-06 12:53:20', 0, NULL),
(12, 'GTA-33642', '14', 31, 3, 6, 2, 'GTA-33678', 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-06 13:01:39', 0, '2026-05-06 12:54:01', 0, NULL),
(13, 'GTA-34118', '15,16', 39, 4, 6, 2, 'GTA-34565', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 2, 0, '2026-05-06 13:06:14', 0, '2026-05-06 13:04:28', 0, NULL),
(14, 'GTA-34118', '17,18', 42, 4, 6, 2, 'GTA-34565', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 2, 0, '2026-05-06 13:06:22', 0, '2026-05-06 13:05:42', 0, NULL),
(15, 'GTA-34564', '19', 17, 5, 6, 2, 'GTA-34578', 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-06 13:11:46', 0, '2026-05-06 13:10:59', 0, NULL),
(16, 'GTA-34656', '20', 3, 6, 6, 2, 'GTA-34796', 'Shereen El Gendy', 'shereen.g@partner.samsung.com', 1, 0, '2026-05-06 13:13:41', 0, '2026-05-06 13:13:26', 0, NULL),
(17, 'GTA-36017', '21', 22, 7, 3, 2, 'GTA-36055', 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, 0, '2026-05-06 13:16:21', 0, '2026-05-06 13:16:06', 0, NULL),
(18, 'GTA-36055', '22', 30, 7, 3, 2, 'GTA-36055', 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, 0, '2026-05-06 13:17:45', 0, '2026-05-06 13:17:19', 0, NULL),
(19, 'GTA-36019', '23', 34, 7, 3, 2, 'GTA-36092', 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, 0, '2026-05-06 13:20:00', 0, '2026-05-06 13:18:58', 0, NULL),
(20, 'GTA-36019', '24', 32, 7, 3, 2, 'GTA-36092', 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, 0, '2026-05-06 13:20:43', 0, '2026-05-06 13:20:26', 0, NULL),
(21, NULL, '25', 13, 8, 6, 3, 'GTA-31467', 'Mohamed Rafeek', '', 1, 0, '2026-05-06 13:33:26', 0, '2026-05-06 13:24:45', 0, NULL),
(22, NULL, '26', 16, 8, 6, 3, 'GTA-31467', 'Mohamed Rafeek', '', 1, 0, '2026-05-06 13:36:17', 0, '2026-05-06 13:34:21', 0, NULL),
(23, NULL, '27', 7, 8, 1, 3, 'GTA-31570', 'Mohamed Rafeek', '', 1, 0, '2026-05-06 13:41:56', 0, '2026-05-06 13:41:50', 0, NULL),
(24, NULL, '28', 10, 8, 1, 3, 'GTA-31570', 'Mohamed Rafeek', '', 1, 0, '2026-05-06 13:42:45', 0, '2026-05-06 13:42:38', 0, NULL),
(25, NULL, '29', 8, 8, 1, 3, 'GTA-31574', 'Mohamed Rafeek', '', 1, 0, '2026-05-06 13:43:24', 0, '2026-05-06 13:43:18', 0, NULL),
(26, NULL, '30', 12, 8, 1, 3, 'GTA-31574', 'Mohamed Rafeek', '', 1, 0, '2026-05-06 13:43:53', 0, '2026-05-06 13:43:47', 0, NULL),
(27, NULL, '31', 17, 8, 1, 3, 'GTA-31701', 'Mohamed Rafeek', '', 1, 0, '2026-05-06 13:46:15', 0, '2026-05-06 13:46:05', 0, NULL),
(28, NULL, '32', 20, 8, 1, 3, 'GTA-31701', 'Mohamed Rafeek', '', 1, 0, '2026-05-06 13:46:54', 0, '2026-05-06 13:46:47', 0, NULL),
(29, NULL, '33', 43, 8, 1, 3, 'GTA-31718', 'Mohamed Rafeek', '', 1, 0, '2026-05-06 13:48:15', 0, '2026-05-06 13:48:02', 0, NULL),
(30, NULL, '34', 46, 8, 1, 3, 'GTA-31718', 'Mohamed Rafeek', '', 1, 0, '2026-05-06 13:48:42', 0, '2026-05-06 13:48:34', 0, NULL),
(31, 'GTA-36107', '36', 7, 2, 1, 2, NULL, 'Toufic Alzaza', 'toufic.alzaza@dentsu.com', 3, 0, '2026-05-07 12:41:01', 0, '2026-05-07 12:39:42', 0, NULL),
(32, 'GTA-36107', '37', 8, 2, 1, 2, NULL, 'Toufic Alzaza', 'toufic.alzaza@dentsu.com', 3, 0, '2026-05-07 12:43:00', 0, '2026-05-07 12:42:47', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tag_request_files`
--

CREATE TABLE `tag_request_files` (
  `id` int(11) NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `request_id` tinyint(4) NOT NULL,
  `created_by` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tag_request_files`
--

INSERT INTO `tag_request_files` (`id`, `file_name`, `file_path`, `type`, `request_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'SEIL-LIMY-Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEIL/ZP6_IL-Israel WebSDK/Limy/SEIL-LIMY-Approval.png', 1, 1, 0, '2026-05-05 12:39:04', '2026-05-05 12:39:04'),
(2, 'New_Media_Tag_Request_v3 (2).xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEIL/ZP6_IL-Israel WebSDK/Limy/New_Media_Tag_Request_v3 (2).xlsx', 3, 1, 0, '2026-05-05 13:10:09', '2026-05-05 13:10:09'),
(3, 'ContentSquare-LEVANT_AR_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZP6_LEVANT_AR-Levant(SELV) WebSDK/Contentsquare/ContentSquare-LEVANT_AR_Approval.png', 1, 6, 0, '2026-05-05 13:25:57', '2026-05-05 13:25:57'),
(4, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_JO-Jordan (Shop Section) WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 7, 0, '2026-05-05 13:29:55', '2026-05-05 13:29:55'),
(5, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_JO_AR-Jordan (Shop Section) WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 8, 0, '2026-05-05 13:30:06', '2026-05-05 13:30:06'),
(6, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/zP6_IQ_AR-Iraq WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 9, 0, '2026-05-05 13:41:09', '2026-05-05 13:41:09'),
(7, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_IQ_AR-Iraq (Shop Section) WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 10, 0, '2026-05-05 13:41:31', '2026-05-05 13:41:31'),
(8, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/zP6_IQ_KU-Kurdistan WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 11, 0, '2026-05-06 13:01:00', '2026-05-06 13:01:00'),
(9, 'SEIL_SmartScript_New_Media_Tag_Request_v3.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEIL/ZP6_IL-Israel WebSDK/Appsflyer/SEIL_SmartScript_New_Media_Tag_Request_v3.xlsx', 3, 15, 0, '2026-05-06 13:11:22', '2026-05-06 13:11:22'),
(10, 'MENAO_SmartScript (1).txt', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEIL/ZP6_IL-Israel WebSDK/Appsflyer/MENAO_SmartScript (1).txt', 4, 15, 0, '2026-05-06 13:11:30', '2026-05-06 13:11:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(500) NOT NULL,
  `account_type` tinyint(4) NOT NULL DEFAULT 0,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `about` varchar(500) DEFAULT NULL,
  `reason` varchar(500) DEFAULT NULL,
  `tag_purpose` varchar(500) DEFAULT NULL,
  `collected_data` varchar(500) DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `updated_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `updated_at` datetime DEFAULT NULL,
  `created_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `created_at` datetime DEFAULT NULL,
  `deleted_by` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Account ID of PIC',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `about`, `reason`, `tag_purpose`, `collected_data`, `is_active`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'Limy', NULL, NULL, NULL, NULL, 1, 0, '2026-05-04 12:46:03', 0, '2026-05-04 12:46:03', 0, NULL),
(2, 'Optimise', NULL, NULL, NULL, NULL, 1, 0, '2026-05-05 13:14:20', 0, '2026-05-05 13:14:20', 0, NULL),
(3, 'Contentsquare', NULL, NULL, NULL, NULL, 1, 0, '2026-05-05 13:22:21', 0, '2026-05-05 13:22:21', 0, NULL),
(4, 'RTB', NULL, NULL, NULL, NULL, 1, 0, '2026-05-06 13:04:28', 0, '2026-05-06 13:04:28', 0, NULL),
(5, 'Appsflyer', NULL, NULL, NULL, NULL, 1, 0, '2026-05-06 13:10:59', 0, '2026-05-06 13:10:59', 0, NULL),
(6, 'Firework', NULL, NULL, NULL, NULL, 1, 0, '2026-05-06 13:13:26', 0, '2026-05-06 13:13:26', 0, NULL),
(7, 'Pinterest', NULL, NULL, NULL, NULL, 1, 0, '2026-05-06 13:16:06', 0, '2026-05-06 13:16:06', 0, NULL),
(8, 'CJ', NULL, NULL, NULL, NULL, 1, 0, '2026-05-06 13:24:45', 0, '2026-05-06 13:24:45', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jira_history`
--
ALTER TABLE `jira_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `renewal_logs`
--
ALTER TABLE `renewal_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subsidiaries`
--
ALTER TABLE `subsidiaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tag_requests`
--
ALTER TABLE `tag_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tag_request_files`
--
ALTER TABLE `tag_request_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `jira_history`
--
ALTER TABLE `jira_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `renewal_logs`
--
ALTER TABLE `renewal_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subsidiaries`
--
ALTER TABLE `subsidiaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tag_requests`
--
ALTER TABLE `tag_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tag_request_files`
--
ALTER TABLE `tag_request_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
