-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2026 at 01:34 PM
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
