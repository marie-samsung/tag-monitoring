-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2026 at 12:48 PM
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
  `tag_id` int(11) NOT NULL COMMENT 'Reference to ''tags'' table',
  `is_active` int(11) NOT NULL DEFAULT 1,
  `lp_path` varchar(200) NOT NULL,
  `created_by` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jira_history`
--

INSERT INTO `jira_history` (`id`, `ticket_id`, `tag_id`, `is_active`, `lp_path`, `created_by`, `created_at`) VALUES
(1, 'GTA-33374', 2, 1, '/test', 0, '2026-02-23 12:42:26'),
(2, 'GTA-30930', 1, 1, '/test', 0, '2026-02-23 12:42:39'),
(3, 'GTA-302333', 3, 1, '/test', 0, '2026-02-23 12:42:53');

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

INSERT INTO `properties` (`id`, `name`, `is_active`, `is_epp`, `subsidiary_id`, `country_id`, `language_id`, `website`, `ref_link`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'ZP6_AE_AR-UAE(SGE) WebSDK', 1, 0, 1, 1, 2, 'https://www.samsung.com/ae_ar', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/overviewhttps://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/overview', 0, NULL, 0, NULL, 0, NULL),
(2, 'ZP6_AE-UAE(SGE) WebSDK', 1, 0, 1, 1, 1, 'https://www.samsung.com/ae', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/overview', 0, NULL, 0, NULL, 0, NULL),
(3, 'zP6zEPP_AE-UAE(AA) WebSDK', 1, 1, 1, 1, 1, 'https://www.samsung.com/ae/multistore/samsung_employee/', '', 0, NULL, 0, NULL, 0, NULL);

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
  `vendor_id` int(11) NOT NULL,
  `conditions` varchar(200) NOT NULL COMMENT '(.*)',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Refer to ''Constants'' model for values',
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `al_end_date` datetime DEFAULT NULL,
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

INSERT INTO `tags` (`id`, `name`, `property_id`, `vendor_id`, `conditions`, `status`, `is_active`, `al_end_date`, `ref_link`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'LC-AEM-AD(Amazon)-CM-WL-PXFLW_277a8f5e-2d7b-4799-91f8-a089308d8e1b', 1, 0, '', 1, 1, NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RLe876e1603137407a9502d9bfb0df924d', 0, NULL, 0, NULL, 0, NULL),
(2, 'LC-AEM-AD(Appsflyer)-CM-WL-PXFLW_smartscript', 1, 0, '', 1, 1, NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RL196621ca70cd4e1faf4a0275b9a8418e', 0, NULL, 0, NULL, 0, NULL),
(3, 'LC-AEM-AD(Amazon)-CM-WL-PXFLW_ef2eeeab-6b7e-4867-b8fd-c47091cd2ff2', 2, 0, '', 1, 1, NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RLdff128b12190436eab8d806d3bdd3391', 0, NULL, 0, NULL, 0, NULL),
(4, 'LC-B2C-AD(Appsflyer)-CM-WL-PXFLW_smartscript', 2, 0, '', 1, 1, NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RL94a08b1c00c9402289c37f4b79f3c200', 0, NULL, 0, NULL, 0, NULL),
(5, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_10009534', 3, 0, '', 1, 1, NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR66456bebda424fb3af85ef35ee171c71/rules/RLd30dc4f7a2ab4fc8bca5529ebe6ccea1', 0, NULL, 0, NULL, 0, NULL),
(6, 'LC-TEST', 1, 0, '(.*)', 1, 1, '2026-04-08 00:00:00', 'https://www.samsung.com/en', 0, '2026-02-20 12:30:09', 0, '2026-02-20 12:30:09', 0, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subsidiaries`
--
ALTER TABLE `subsidiaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
