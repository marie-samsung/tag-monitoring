-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2026 at 09:21 AM
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
  `subsidiary_id` int(11) NOT NULL DEFAULT 0,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `file_path` varchar(200) DEFAULT NULL,
  `created_by` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(4, 'HE', 0, NULL, 0, NULL, 0, NULL),
(5, 'FR', 0, NULL, 0, NULL, 0, NULL);

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
(7, 'ZP6_SA_EN-Saudi Arabia(SESAR) WebSDK', 1, 0, 0, 2, 6, 1, 'https://www.samsung.com/sa_en', 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/overview', 0, NULL, 0, NULL, 0, NULL);

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
(9, 'SEPAK', 1, 0, NULL, 0, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `property_id` int(11) NOT NULL COMMENT 'Refers to the properties table',
  `vendor_id` int(11) NOT NULL DEFAULT 0,
  `conditions` varchar(200) NOT NULL COMMENT '(.*)',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Refer to ''Constants'' model for values',
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `al_end_date` date DEFAULT NULL,
  `requested_date` date DEFAULT NULL,
  `ref_link` varchar(500) NOT NULL COMMENT 'Links to AL',
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

INSERT INTO `tags` (`id`, `name`, `property_id`, `vendor_id`, `conditions`, `status`, `is_active`, `al_end_date`, `requested_date`, `ref_link`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'LC-AEM-AD(Amazon)-CM-WL-PXFLW_277a8f5e-2d7b-4799-91f8-a089308d8e1b', 1, 1, '(.*)', 1, 1, '2026-04-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RLe876e1603137407a9502d9bfb0df924d', 0, '2026-03-27 08:47:00', 0, '2026-03-27 08:47:00', 0, NULL),
(2, 'LC-AEM-AD(Appsflyer)-CM-WL-PXFLW_smartscript', 1, 2, '(.*)', 1, 1, '2026-04-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RL196621ca70cd4e1faf4a0275b9a8418e', 0, '2026-03-27 08:47:21', 0, '2026-03-27 08:47:21', 0, NULL),
(3, 'LC-AEM-AD(Contentsquare)-CM-WL-PXFLW_977f9a82566fb', 1, 4, '(.*)', 1, 1, '2026-04-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RL8b83c36828b340189aaf73e59d6ce6ea', 0, '2026-03-27 08:48:13', 0, '2026-03-27 08:48:13', 0, NULL),
(4, 'LC-AEM-AD(Contentsquare)-CM-WL-PXFLW_da035490-de75-4a0b-bcac-5dd36318b187', 1, 4, '(.*)', 1, 1, '2026-04-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RLa6817863bc9b44c989e9da51a7acc42a', 0, '2026-03-27 09:12:28', 0, '2026-03-27 08:48:29', 0, NULL),
(5, 'LC-AEM-AD(Criteo)-CM-WL-PXFLW_3be66720-a2ec-4b75-a022-ab04b3d8f41a', 1, 5, '(.*)', 1, 1, '2026-04-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RLcba1121249d242c8acc6e167adeeaed9', 0, '2026-03-27 09:12:33', 0, '2026-03-27 08:50:02', 0, NULL),
(6, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-ac76-4b1c9ab097aa', 1, 8, '(.*)', 1, 1, '2026-04-25', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RL321f5ec6fc7949fd8beb649fcd1b3846', 0, '2026-03-27 08:50:31', 0, '2026-03-27 08:50:31', 0, NULL),
(7, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-8517-f38da36bcf66', 7, 8, '(.*)', 1, 1, '2026-05-03', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RLf1cd577493d942a1b3d21ea8cf176115', 0, '2026-03-27 09:00:03', 0, '2026-03-27 09:00:03', 0, NULL),
(8, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_d75b776c0f82a', 7, 4, '(.*)', 1, 1, '2026-04-20', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RL7c3d2702ee0748f58f63e3bed21ad36c', 0, '2026-03-27 09:01:18', 0, '2026-03-27 09:01:18', 0, NULL),
(9, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-ac76-4b1c9ab097aa', 1, 8, '(.*)', 1, 1, '2026-04-25', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RL321f5ec6fc7949fd8beb649fcd1b3846', 0, '2026-03-31 08:49:53', 0, '2026-03-31 08:49:53', 0, NULL),
(10, 'LC-AEM-AD(Admitad)-CM-WL-PXFLW_a50541f5-78e9-4f2f-8b35-e43cf23e3b33', 7, 3, '(.*)', 1, 1, '2026-04-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RL20186b6b2eea4493a6939b162ca97fbc', 0, '2026-03-31 08:52:59', 0, '2026-03-31 08:52:59', 0, NULL),
(11, 'LC-AEM-AD(Appsflyer)-CM-WL-PXFLW_smartscript', 7, 2, '(.*)', 1, 1, '2026-04-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RL581937436d76473fba6f16d9e366305f', 0, '2026-03-31 08:53:33', 0, '2026-03-31 08:53:33', 0, NULL),
(12, 'LC-AEM-AD(Criteo)-CM-WL-PXFLW_05616dc9-7581-444e-ac88-68954b0e9425', 7, 5, '(.*)', 1, 1, '2026-04-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RLe4018cc6d8484382815763b0f9b32282', 0, '2026-03-31 08:54:26', 0, '2026-03-31 08:53:59', 0, NULL);

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

INSERT INTO `vendors` (`id`, `name`, `about`, `tag_purpose`, `collected_data`, `is_active`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'Amazon', NULL, NULL, NULL, 1, 0, NULL, 0, NULL, 0, NULL),
(2, 'Appsflyer', NULL, NULL, NULL, 1, 0, NULL, 0, NULL, 0, NULL),
(3, 'Admitad', NULL, NULL, NULL, 1, 0, NULL, 0, NULL, 0, NULL),
(4, 'Contentsquare', NULL, NULL, NULL, 1, 0, NULL, 0, NULL, 0, NULL),
(5, 'Criteo', NULL, NULL, NULL, 1, 0, NULL, 0, NULL, 0, NULL),
(6, 'Firework', NULL, NULL, NULL, 1, 0, NULL, 0, NULL, 0, NULL),
(7, 'Insider', NULL, NULL, NULL, 1, 0, NULL, 0, NULL, 0, NULL),
(8, 'CJ', NULL, NULL, NULL, 1, 0, NULL, 0, NULL, 0, NULL),
(9, 'Meta', NULL, NULL, NULL, 1, 0, NULL, 0, NULL, 0, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `renewal_logs`
--
ALTER TABLE `renewal_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subsidiaries`
--
ALTER TABLE `subsidiaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
