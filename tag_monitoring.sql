-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2026 at 03:07 PM
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
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `subsidiary_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `project_id` int(11) NOT NULL,
  `ticket_id` varchar(100) DEFAULT NULL,
  `assignee` int(11) NOT NULL,
  `requested_by` varchar(200) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `type` tinyint(4) NOT NULL DEFAULT 1,
  `size` tinyint(4) NOT NULL DEFAULT 1,
  `priority` tinyint(4) NOT NULL DEFAULT 1,
  `requested_date` datetime DEFAULT NULL,
  `finished_date` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` tinyint(4) DEFAULT NULL,
  `campaign_status` int(11) DEFAULT NULL,
  `campaign_id` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `title`, `description`, `subsidiary_id`, `department_id`, `project_id`, `ticket_id`, `assignee`, `requested_by`, `status`, `type`, `size`, `priority`, `requested_date`, `finished_date`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `campaign_status`, `campaign_id`) VALUES
(1, 'MENA Google Accounts - MCC Level Migration Alignment', '<p style=\"box-sizing: content-box; font-family: \"Malgun Gothic\", \"맑은 고딕\"; margin: 5px 0px; outline: 0px; padding: 0px; text-size-adjust: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); -webkit-font-smoothing: antialiased; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; font-size: 14px; vertical-align: top; line-height: 1.9;\"><span class=\"cui-origin-span\" style=\"box-sizing: content-box; font-family: Arial, sans-serif; margin: 0px; outline: 0px; padding: 0px; text-size-adjust: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); -webkit-font-smoothing: antialiased; font-size: 13.3333px;\">Agenda:</span></p><ul><li style=\"box-sizing: content-box; font-family: \"Malgun Gothic\", \"맑은 고딕\"; margin: 5px 0px; outline: 0px; padding: 0px; text-size-adjust: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); -webkit-font-smoothing: antialiased; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; font-size: 14px; vertical-align: top; line-height: 1.9;\"><span class=\"cui-origin-span\" style=\"box-sizing: content-box; font-family: Arial, sans-serif; margin: 0px; outline: 0px; padding: 0px; text-size-adjust: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); -webkit-font-smoothing: antialiased; font-size: 13.3333px;\">Richard to walk us through his deck</span></li><li style=\"box-sizing: content-box; font-family: \"Malgun Gothic\", \"맑은 고딕\"; margin: 5px 0px; outline: 0px; padding: 0px; text-size-adjust: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); -webkit-font-smoothing: antialiased; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; font-size: 14px; vertical-align: top; line-height: 1.9;\"><span class=\"cui-origin-span\" style=\"box-sizing: content-box; font-family: Arial, sans-serif; margin: 0px; outline: 0px; padding: 0px; text-size-adjust: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); -webkit-font-smoothing: antialiased; font-size: 13.3333px;\">CNX Team to share their inputs</span></li><li style=\"box-sizing: content-box; font-family: \"Malgun Gothic\", \"맑은 고딕\"; margin: 5px 0px; outline: 0px; padding: 0px; text-size-adjust: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); -webkit-font-smoothing: antialiased; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; border: 0px; font-size: 14px; vertical-align: top; line-height: 1.9;\"><span class=\"cui-origin-span\" style=\"box-sizing: content-box; font-family: Arial, sans-serif; margin: 0px; outline: 0px; padding: 0px; text-size-adjust: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); -webkit-font-smoothing: antialiased; font-size: 13.3333px;\">alignment on timeline and who does what</span></li></ul>', 10, NULL, 1, 'GTA-35965', 3, 'Mohamed Essam', 6, 5, 2, 3, '2026-05-19 00:00:00', '2026-05-20 00:00:00', '2026-05-19 09:24:36', 0, '2026-05-20 09:36:41', 0, NULL, NULL, NULL, ''),
(2, 'New Tag Request: ContentSquare', '<ol><li>ZS_Hybris_SA_EN-Saudi Arabia (Shop Section) WebSDK<ul><li>LC-Shop-AD(Contentsquare)-Cart-ATC-SDSME_9a0c-16784c10bbf7</li><li>LC-Shop-AD(Contentsquare)-OrderConfirmation-WL-SDSME_9a0c-16784c10bbf7</li></ul></li><li>ZS_Hybris_SA-Saudi Arabia (Shop Section) WebSDK<ul><li>LC-Shop-AD(Contentsquare)-Cart-ATC-SDSME_9fd1-4a4a5906c84b&nbsp;</li><li>LC-Shop-AD(Contentsquare)-OrderConfirmation-WL-SDSME_9fd1-4a4a5906c84b</li></ul></li></ol>', 2, NULL, 1, 'GTA-36488', 1, 'Eiass Ali', 6, 4, 3, 2, '2026-05-19 00:00:00', NULL, '2026-05-19 16:51:28', 0, '2026-06-01 11:44:24', 0, NULL, NULL, NULL, ''),
(3, 'Tag Renewals', 'Renewal of tags: <br/><ul><li>LC-Shop-AD(RTBHouse)-OrderConfirmation-WL-SDSME_b2e4-3228e7ef555e</li><li>LC-Shop-AD(RTBHouse)-Cart-WL-SDSME_b2e4-3228e7ef555e</li></ul>', 8, NULL, 1, 'GTA-36321', 1, '', 6, 1, 1, 1, NULL, '2026-05-20 00:00:00', '2026-05-20 09:00:40', 0, '2026-05-20 09:06:51', 0, NULL, NULL, NULL, ''),
(4, 'Tag Renewals', 'Renewal of tags: <br/><ul><li>LC-AEM-AD(RTBHouse)-Cart-ATC-SDSME_b2e4-3228e7ef555e</li><li>LC-AEM-AD(RTBHouse)-ViewContent-WL-SDSME_b2e4-3228e7ef555e</li></ul>', 8, NULL, 1, 'GTA-36321', 1, '', 6, 1, 1, 1, NULL, '2026-05-20 00:00:00', '2026-05-20 09:01:25', 0, '2026-05-20 09:07:47', 0, NULL, NULL, NULL, ''),
(5, 'Tag Renewals', 'Renewal of tags: <br/><ul><li>LC-AEM-AD(Appsflyer)-CM-WL-SDSME_903d-af70610536c8</li></ul>', 4, NULL, 1, 'GTA-36314', 1, '', 6, 1, 1, 1, NULL, '2026-05-20 00:00:00', '2026-05-20 09:02:02', 0, '2026-05-20 09:32:25', 0, NULL, NULL, NULL, ''),
(6, 'Tag Renewals', 'Renewal of tags: <br/><ul><li>LC-AEM-AD(Limy)-CM-ALL(WL)-SDSME-9802-48598677a5ff</li></ul>', 4, NULL, 1, 'GTA-36314', 1, '', 6, 1, 1, 1, NULL, '2026-05-20 00:00:00', '2026-05-20 09:02:30', 0, '2026-05-20 09:32:31', 0, NULL, NULL, NULL, ''),
(7, 'Tag Renewals', 'Renewal of tags: <br/><ul><li>LC-Shop-AD(Snapchat)-CM-ALL(WL)-PXFLW_8a3b930f-43fa-4bbe-b738-8c7ebb4bfabe</li><li>LC-Shop-AD(RTBHouse)-CM-ALL(WL)-PXFLW_5b362e2a-f7b9-4e18-9549-dcea0de1a5dd</li><li>LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_48d8372e-2280-4ca4-a713-5733425eb71f_rewards_page</li><li>LC-Shop-AD(Reddit)-CM-ALL(WL)-PXFLW_t2_7ovbtan2k</li><li>LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_48d8372e-2280-4ca4-a713-5733425eb71f</li></ul>', 1, NULL, 1, 'GTA-36246', 1, '', 6, 1, 1, 1, NULL, NULL, '2026-05-20 10:07:30', 0, '2026-05-21 17:40:41', 0, NULL, NULL, NULL, ''),
(8, 'Update Rule Request: Contentsquare', '<ol><li>ZP6_SA_EN-Saudi Arabia(SESAR) WebSDK - <b>LC-AEM-AD(Contentsquare)-CM-WL-SDSME_9a0c-16784c10bbf7</b></li><li>ZP6_SA-Saudi Arabia(SESAR) WebSDK - <b>LC-AEM-AD(Contentsquare)-CM-WL-SDSME_9fd1-4a4a5906c84b</b></li><li>ZS_Hybris_SA_EN-Saudi Arabia (Shop Section) WebSDK - <span style=\"font-weight: bolder;\">LC-Shop-AD(Contentsquare)-CM-WL-SDSME_9a0c-16784c10bbf7</span></li><li>ZS_Hybris_SA-Saudi Arabia (Shop Section) WebSDK - <span style=\"font-weight: bolder;\">LC-Shop-AD(Contentsquare)-CM-WL-SDSME_9fd1-4a4a5906c84b</span></li></ol>', 2, NULL, 1, 'GTA-36489', 1, 'Eiass Ali', 5, 4, 3, 2, '2026-05-19 00:00:00', NULL, '2026-05-20 13:38:19', 0, '2026-05-21 15:55:51', 0, NULL, NULL, NULL, ''),
(9, 'SETK - Meta Order & Revenue Tracking Issue', '<p>Perform live credit card purchase and Havale on Turkey website with Erva and Mert</p><p><b>Notes:</b></p><ul><li>Purchases were successful: Havale, Credit Card (Mastercard &amp; Visa)</li><li>Campaign dashboard from May 18 still shows purchases but without conversion values</li></ul>', 3, NULL, 1, 'GTA-36289', 3, '', 6, 5, 1, 1, '2026-05-21 00:00:00', NULL, '2026-05-21 10:30:48', 0, '2026-05-21 10:30:48', 0, NULL, NULL, NULL, ''),
(10, 'New Tag Request: Contentsquare', '<ol><li><b>ZP6_SA-Saudi Arabia(SESAR) WebSDK</b></li><ul><li>LC-AEM-AD(Contentsquare)-Cart-ATC-SDSME_9a0c-16784c10bbf7</li></ul></ol>', 2, NULL, 1, 'GTA-36537', 1, '', 6, 4, 2, 2, NULL, NULL, '2026-05-21 13:16:30', 0, '2026-06-01 11:37:00', 0, NULL, NULL, NULL, ''),
(11, 'New Tag Request: Contentsquare', '<ol><li><b>ZP6_SA_EN-Saudi Arabia(SESAR) WebSDK</b></li><ul><li>LC-AEM-AD(Contentsquare)-Cart-ATC-SDSME_9a0c-16784c10bbf7</li></ul></ol>', 2, NULL, 1, 'GTA-36537', 1, '', 3, 4, 2, 2, NULL, NULL, '2026-05-21 13:17:38', 0, '2026-05-22 11:54:44', 0, NULL, NULL, NULL, ''),
(12, 'Tag Renewals [GTA-36543]', 'Renewal of tags: <br/><ul><li>LC-AEM-AD(Firework)-CM-WL-SDSME_vJ0Oe2</li></ul>', 1, NULL, 1, 'GTA-36543', 1, '', 6, 4, 2, 1, NULL, NULL, '2026-05-21 17:36:44', 0, '2026-05-25 11:47:28', 0, NULL, NULL, NULL, ''),
(13, 'SETK C2S Issue Updates', '<p>Meeting regarding the updated rules mentioned by HQ in ticket GTA-35106</p>', 3, NULL, 1, 'GTA-35106', 3, 'Mert Akyol', 6, 5, 1, 1, '2026-05-20 00:00:00', NULL, '2026-05-21 17:44:50', 0, '2026-05-21 17:44:50', 0, NULL, NULL, NULL, ''),
(14, 'MCC Migration - Google Ads ID Discussion', '<p>Regarding Google Ads conversion IDs and labels</p>', 10, NULL, 1, NULL, 3, 'Richard Hartley', 6, 5, 2, 1, '2026-05-21 00:00:00', NULL, '2026-05-21 17:47:39', 0, '2026-05-21 17:47:39', 0, NULL, NULL, NULL, ''),
(15, 'Update Request for Tags [GTA-36570]', 'Update of tags: <br><ul><li>LC-AEM-AD(Firework)-CM-WL-PXFLW_floating_video_player (AE and AE_AR)</li></ul>', 1, NULL, 1, 'GTA-36570', 1, 'Shereen El Gendy', 6, 4, 2, 2, '2026-05-15 00:00:00', '2026-05-25 00:00:00', '2026-05-22 09:12:15', 0, '2026-05-25 09:17:14', 0, NULL, NULL, NULL, ''),
(16, 'MCC Migration - GoogleTag Discussion', '<p>Meeting with Dimitrios on 4th Floor to clarify things about the gtag migration</p>', 10, NULL, 1, NULL, 3, 'Dimitrios Tsakiris', 6, 5, 2, 1, '2026-05-22 00:00:00', NULL, '2026-05-22 11:54:03', 0, '2026-05-22 11:54:03', 0, NULL, NULL, NULL, ''),
(17, 'New Tag Request: Contentsquare', '<ol><li><b>ZS_Hybris_IL-Israel (Shop Section) WebSDK</b></li><ul><li>LC-Shop-AD(Contentsquare)-OrderConfirmation-WL-SDSME_94f6-63b3da673666</li></ul></ol>', 4, NULL, 1, 'GTA-36535', 1, '', 3, 4, 2, 2, NULL, NULL, '2026-05-22 15:52:56', 0, '2026-05-22 16:10:28', 0, NULL, NULL, NULL, ''),
(18, 'New Tag Request: Contentsquare', '<ol><li><b>ZP6_IL-Israel WebSDK</b></li><ul><li>LC-AEM-AD(Contentsquare)-Cart-ATC-SDSME_94f6-63b3da673666</li></ul></ol>', 4, NULL, 1, 'GTA-36535', 1, '', 3, 4, 2, 2, NULL, NULL, '2026-05-22 15:53:57', 0, '2026-05-22 16:10:22', 0, NULL, NULL, NULL, ''),
(19, 'Tag Renewals [GTA-36586]', 'Renewal of tags: <br/><ul><li>LC-Shop-AD(RTB)-CM-ALL(WL)-PXFLW_70bbf4ef-dd46-4c11-b642-7d1c2319b30e</li><li>LC-Shop-AD(Twitter)-CM-WL-PXFLW_ca8ad850-bd23-4c5e-aaa7-2a305917cd94_rewards_page</li><li>LC-Shop-AD(Reddit)-CM-ALL(WL)-PXFLW_t2_7ovbtan2k</li><li>LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_ca8ad850-bd23-4c5e-aaa7-2a305917cd94</li><li>LC-Shop-AD(Snapchat)-CM-ALL(WL)-PXFLW_9176ef98-113d-4571-9987-122be6647409</li></ul>', 1, NULL, 1, 'GTA-36586', 0, NULL, 6, 4, 2, 1, NULL, NULL, '2026-05-22 17:45:28', 0, '2026-06-01 10:58:33', 0, NULL, NULL, NULL, NULL),
(20, 'Tag Management Solution Demo', '<p>Demo the phase 1 of Tag Monitoring Inventory to Deepu</p>', 10, NULL, 1, NULL, 1, '', 6, 5, 2, 1, '2026-05-22 00:00:00', NULL, '2026-05-25 09:18:42', 0, '2026-05-25 09:18:42', 0, NULL, NULL, NULL, ''),
(21, 'Tag Renewals [GTA-36603]', 'Renewal of tags: <br/><ul><li>LC-AEM-AD(Insider)-CM-WL-PXFLW_10001941</li><li>LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_10001941</li><li>LC-Shop-AD(Insider)-OrderConfirmation-CM-ALL(WL)-PXFLW_10001941</li></ul>', 3, NULL, 1, 'GTA-36603', 0, NULL, 6, 4, 2, 1, NULL, NULL, '2026-05-25 09:56:17', 0, '2026-06-02 14:18:30', 0, NULL, NULL, NULL, NULL),
(22, 'Tag Renewals [GTA-36611]', 'Renewal of tags: <br/><ul><li>LC-AEM-AD(Insider)-CM-WL-PXFLW_10005181</li><li>LC-Shop-AD(Insider)-OrderConfirmation-CM-ALL(WL)-PXFLW_10005181</li><li>LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_10005181</li></ul>', 9, NULL, 1, 'GTA-36611', 0, NULL, 6, 4, 2, 1, NULL, NULL, '2026-05-25 10:15:47', 0, '2026-06-02 14:23:37', 0, NULL, NULL, NULL, NULL),
(23, 'Tag Renewals [GTA-36615]', 'Renewal of tags: <br/><ul><li>LC-AEM-AD(Criteo)-CM-WL-PXFLW_3be66720-a2ec-4b75-a022-ab04b3d8f41a</li><li>LC-AEM-AD(Naturalsearch)-CM-WL-PXFLW_3d1c0542-c4ad-4978-83ed-82c9f3bbd43d</li><li>LC-AEM-AD(Amazon)-CM-WL-PXFLW_277a8f5e-2d7b-4799-91f8-a089308d8e1b</li><li>LC-AEM-AD(Contentsquare)-CM-WL-PXFLW_da035490-de75-4a0b-bcac-5dd36318b187</li><li>LC-AEM-AD(Insider)-CM-WL-PXFLW_fdf2971f-8bfa-4b46-a955-eac2343ae085_rewards_page</li><li>LC-AEM-AD(Contentsquare)-CM-WL-SDSME_9006-d99180e087d0</li><li>LC-AEM-AD(Insider)-CM-WL-PXFLW_fdf2971f-8bfa-4b46-a955-eac2343ae085</li><li>LC-AEM-AD(Reddit)-CM-WL-PXFLW_t2_7ovbtan2k</li><li>LC-AEM-AD(Firework)-CM-WL-SDSME_oPNw8q</li><li>LC-AEM-AD(Appsflyer)-CM-WL-PXFLW_smartscript</li></ul>', 1, NULL, 1, 'GTA-36615', 0, NULL, 6, 4, 2, 1, NULL, NULL, '2026-05-25 10:38:45', 0, '2026-06-02 12:28:22', 0, NULL, NULL, NULL, NULL),
(24, 'Tag Renewals [GTA-36625]', 'Renewal of tags: <br/><ul><li>LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_e14c1f03-5712-4534-b3ba-00d21034c997_rewards_page</li><li>LC-Shop-AD(CampaignManager)-OrderConfirmation-ALL(WL)-PXFLW_6a70d3d0-e011-4e84-9f03-a331a9a4d472</li><li>LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_e14c1f03-5712-4534-b3ba-00d21034c997</li><li>LC-Shop-AD(Amazon)-CM-ALL(WL)-PXFLW_0d2f7c74-7fe6-458a-9b35-95f071505811</li><li>LC-Shop-AD(Criteo)-CM-ALL(WL)-PXFLW_1ebac5d7-cc79-475a-bf22-09939d6b8a71</li><li>LC-Shop-AD(Naturalsearch)-CM-ALL(WL)-PXFLW_06fcf92b-d0ee-4f15-a822-0f3acfebed88</li><li>LC-Shop-AD(Contentsquare)-CM-ALL(WL)-PXFLW_977f9a82566fb</li></ul>', 1, NULL, 1, 'GTA-36625', 0, NULL, 3, 4, 2, 1, NULL, NULL, '2026-05-25 10:52:42', 0, '2026-05-25 10:52:42', 0, NULL, NULL, NULL, NULL),
(25, 'Tag Renewals [GTA-36633]', 'Renewal of tags: <br/><ul><li>LC-Shop-AD(CampaignManager)-OrderConfirmation-ALL(WL)-PXFLW_2fbebe0a-268d-45ee-9269-1d42602d1be1</li><li>LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_66a45698-7470-407f-a046-78683064b296</li><li>LC-Shop-AD(Naturalsearch)-CM-ALL(WL)-PXFLW_36cfb790-cf94-454b-b3cd-38c8c321eaf9</li><li>LC-Shop-AD(Amazon)-CM-ALL(WL)-PXFLW_d32fd2de-4c67-4a2a-a462-2abd31040cb2</li><li>LC-Shop-AD(Criteo)-CM-ALL(WL)-PXFLW_35e1e22c-ffb0-4920-9511-261174e896a9</li><li>LC-Shop-AD(Linkedin)-CM-ALL(WL)-PXFLW_8b585520-0741-4928-a001-dcc167a1d7c8</li><li>LC-Shop-AD(Contentsquare)-CM-ALL(WL)-PXFLW_977f9a82566fb</li><li>LC-Shop-AD(Insider)-CM-WL-PXFLW_66a45698-7470-407f-a046-78683064b296_rewards_page</li></ul>', 1, NULL, 1, 'GTA-36633', 0, NULL, 3, 4, 2, 1, NULL, NULL, '2026-05-25 11:05:41', 0, '2026-05-25 11:05:41', 0, NULL, NULL, NULL, NULL),
(26, 'Tag Renewals [GTA-36642]', 'Renewal of tags: <br/><ul><li>LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_78f13c92-abd6-495c-bf2f-16b647f05e94</li><li>LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_f30d3037-cc3d-477b-8901-d77acbf62ee4</li><li>LC-AEM-AD(Linkedin)-CM-WL-PXFLW_fa8aa003-5506-4f90-a1cf-ba2bf2269d24</li><li>LC-AEM-AD(Appsflyer)-CM-WL-PXFLW_smartscript</li><li>LC-AEM-AD(Snapchat)-CM-WL-PXFLW_bc1ffb3d-a6fd-406d-91dc-f83d973d354f</li><li>LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_24ca1fdf-9b2b-4aca-b052-509feb5bbafe</li><li>LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_a9a41a2c-6d73-48ee-826e-7d52ef7c0dbc</li><li>LC-AEM-AD(Insider)-CM-WL-PXFLW_76276500-e5ee-4b60-a3df-2ae853a8b0ec</li><li>LC-AEM-AD(Adalyser)-CM-WL-SDSME_ae77-d2794efb388b</li><li>LC-AEM-AD(Snapchat)-CM-WL-PXFLW_246bc398-c43e-42cc-abda-25f6665235b4</li><li>LC-AEM-AD(Criteo)-CM-WL-PXFLW_05616dc9-7581-444e-ac88-68954b0e9425</li><li>LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_9ae7ce5e-e709-4dea-97e8-a13b64fd1bc1</li><li>LC-AEM-AD(GoogleAdsRemarketing)-CM-WL-PXFLW_68e2ea84-b8e9-4f5c-8687-2a381332bff3</li><li>LC-AEM-AD(RTB)-CM-WL-PXFLW_684fb39e-250c-4dde-8375-0aaec2361e5d</li><li>LC-AEM-AD(Admitad)-CM-WL-PXFLW_a50541f5-78e9-4f2f-8b35-e43cf23e3b33</li><li>LC-AEM-AD(Boostiny)-CM-WL-PXFLW_samsungksa</li></ul>', 2, NULL, 1, 'GTA-36642', 0, NULL, 6, 4, 2, 1, NULL, NULL, '2026-05-25 11:23:41', 0, '2026-06-02 13:51:42', 0, NULL, NULL, NULL, NULL),
(27, 'Tag Renewals [GTA-36659]', 'Renewal of tags: <br/><ul><li>LC-AEM-AD(Insider)-CM-WL-PXFLW_10009534</li></ul>', 1, NULL, 1, 'GTA-36659', 0, NULL, 3, 4, 2, 1, NULL, NULL, '2026-05-25 11:28:14', 0, '2026-05-25 11:28:14', 0, NULL, NULL, NULL, NULL),
(28, 'Update Request for Tags [GTA-36676]', 'Update of tags: <br/><ul><li>LC-AEM-AD(RTBHouse)-ViewContent-WL-SDSME_b2e4-3228e7ef555e</li></ul>', 8, NULL, 1, 'GTA-36676', 0, NULL, 3, 4, 2, 1, NULL, NULL, '2026-05-25 13:38:50', 0, '2026-05-25 13:38:50', 0, NULL, NULL, NULL, NULL),
(29, 'Update Request for Tags [GTA-36681]', 'Update of tags: <br/><ul><li>LC-AEM-AD(AdobeDSP)-CM-WL-SDSME-d477_c2bca6ae619b</li><li>LC-AEM-AD(AdobeDSP)-Cart-ATC-SDSME-d477_c2bca6ae619b</li><li>LC-AEM-AD(AdobeDSP)-Cart-ATC-SDSME-d509_afd6e0b69fe2</li><li>LC-AEM-AD(AdobeDSP)-CM-WL-SDSME-d509_afd6e0b69fe2</li><li>LC-Shop-AD(AdobeDSP)-CM-WL-SDSME-d477_c2bca6ae619b</li><li>LC-Shop-AD(AdobeDSP)-CM-WL-SDSME-d509_afd6e0b69fe2</li></ul>', 1, NULL, 1, 'GTA-36681', 0, NULL, 3, 4, 2, 1, NULL, NULL, '2026-05-25 17:31:58', 0, '2026-05-25 17:31:58', 0, NULL, NULL, NULL, NULL),
(30, 'New Tag Request: RTBHouse', '<ol><li><b>ZS_Hybris_TR-Turkey (Shop Section) WebSDK</b></li><ul><li>LC-Shop-AD(RTBHouse)-OrderConfirmation-WL-SDSME_aaf6-d1164146cf4e</li></ul></ol>', 3, NULL, 1, 'GTA-36845', 0, NULL, 6, 4, 2, 1, NULL, NULL, '2026-06-01 12:20:54', 1, '2026-06-03 08:42:02', 0, NULL, NULL, NULL, NULL),
(31, 'New Tag Request: RTBHouse', '<ol><li><b>ZP6_TR-Turkey WebSDK</b></li><ul><li>LC-AEM-AD(RTBHouse)-ViewContent-WL-SDSME_aaf6-d1164146cf4e</li></ul></ol>', 3, NULL, 1, 'GTA-36845', 0, NULL, 3, 4, 2, 1, NULL, NULL, '2026-06-01 12:21:17', 1, '2026-06-01 12:21:17', 0, NULL, NULL, NULL, NULL),
(32, 'Update Request for Tags [GTA-36852]', 'Update of tags: <br/><ul><li>LC-AEM-AD(RTBHouse)-CM-ALL(WL)-SDSME_aaf6-d1164146cf4e</li><li>LC-AEM-AD(RTBHouse)-Cart-ATC-SDSME_aaf6-d1164146cf4e</li><li>LC-Shop-AD(RTBHouse)-CM-ALL(WL)-SDSME_aaf6-d1164146cf4e</li><li>LC-Shop-AD(RTBHouse)-Cart-WL-SDSME_aaf6-d1164146cf4e</li></ul>', 3, NULL, 1, 'GTA-36852', 0, NULL, 3, 4, 2, 1, NULL, NULL, '2026-06-01 13:48:16', 0, '2026-06-01 13:48:16', 0, NULL, NULL, NULL, NULL),
(33, 'Tag Renewals [GTA-36882]', 'Renewal of tags: <br/><ul><li>LC-Shop-AD(GoogleAds)-CM-ALL(WL)-PXFLW_88018911-edb3-416b-b729-5bd80aea031e</li><li>LC-Shop-AD(GoogleAds)-OrderConfirmation-ALL(WL)-PXFLW_6955ac90-b03a-4ac2-b2f5-81dcce380e94</li><li>LC-Shop-AD(Linkedin)-CM-ALL(WL)-PXFLW_023d42c9-9ab9-41ec-a3df-9ca3d27c6605</li><li>LC-Shop-AD(Admitad)-CM-ALL(WL)-PXFLW_dd504b10-dc78-4b56-9ed9-846967c90693</li><li>LC-Shop-AD(Criteo)-CM-ALL(WL)-PXFLW_d5c18d25-78c5-471b-a991-c210bcd87fed</li><li>LC-Shop-AD(GoogleAds)-Cart-ALL(WL)-PXFLW_f3066818-8048-4248-b3aa-aea20b6a7e30</li><li>LC-Shop-AD(GoogleAds)-OrderConfirmation-ALL(WL)-PXFLW_4314d843-978a-47c1-a398-a844b532913c</li><li>LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_3e125522-a083-4162-8c47-81f9c1b4b0d8</li><li>LC-Shop-AD(Snapchat)-CM-ALL(WL)-PXFLW_1dbe5629-459d-44bb-948d-30d447c4db6f</li><li>LC-Shop-AD(Boostiny)-CM-OrderConfirmation-ALL(WL)-PXFLW_samsungksa</li><li>LC-Shop-AD(GoogleAds)-Cart-ALL(WL)-PXFLW_c9bae7ad-186b-448f-be42-71fbb6d9dcdb</li><li>LC-Shop-AD(GoogleAds)-OrderConfirmation-ALL(WL)-PXFLW_3bf47f14-307f-435d-af98-6dc13813bc8b</li><li>LC-Shop-AD(GoogleAdsRemarketing)-CM-ALL(WL)-PXFLW_e1cd03fd-90a3-4cd1-a126-700abe4225db</li><li>LC-Shop-AD(RTB)-CM-ALL(WL)-PXFLW_610c3bac-b872-46be-9aee-4b2e956ccc04</li><li>LC-Shop-AD(Boostiny)-CM-ALL(WL)-PXFLW_samsungksa</li><li>LC-Shop-AD(GoogleAds)-Cart-ALL(WL)-PXFLW_6195fa9b-8de6-42d4-bcf7-ee9fa074cf51</li></ul>', 2, NULL, 1, 'GTA-36882', 0, NULL, 3, 4, 2, 1, NULL, NULL, '2026-06-02 12:23:23', 0, '2026-06-02 12:23:23', 0, NULL, NULL, NULL, NULL),
(34, 'Update Request for Tags [GTA-36912]', 'Update of tags: <br/><ul><li>LC-Shop-AD(RTBHouse)-CM-ALL(WL)-SDSME_af47-ea652e13577e</li><li>LC-Shop-AD(RTBHouse)-CM-ALL(WL)-SDSME_ab10-62118729eba5</li></ul>', 6, NULL, 1, 'GTA-36912', 0, NULL, 3, 4, 2, 1, NULL, NULL, '2026-06-02 16:03:49', 0, '2026-06-02 16:03:49', 0, NULL, NULL, NULL, NULL),
(35, 'Tag Renewals [GTA-36956]', 'Renewal of tags: <br/><ul><li>LC-Shop-AD(GoogleAds)-Cart-ALL(WL)-PXFLW_c5eec7f6-d14e-4246-947d-4d39ff72d27c</li><li>LC-Shop-AD(GoogleAds)-OrderConfirmation-ALL(WL)-PXFLW_2bc8cb5f-db79-4119-ac23-1f5c992406f7</li><li>LC-Shop-AD(GoogleAds)-Cart-ALL(WL)-PXFLW_c4bdc2fb-0531-4a9d-8c8b-266c3bea6289</li><li>LC-Shop-AD(GoogleAds)-OrderConfirmation-ALL(WL)-PXFLW_0b26100a-8f03-4d35-8cec-c21c7ea13d41</li><li>LC-Shop-AD(GoogleAdsRemarketing)-CM-ALL(WL)-PXFLW_f4c206d0-5066-4f96-bdd6-6a2d95030faa</li><li>LC-Shop-AD(GoogleAds)-Cart-ALL(WL)-PXFLW_2d0ae448-3f8f-49c8-8f83-35735dd0b981</li><li>LC-Shop-AD(GoogleAds)-CM-ALL(WL)-PXFLW_88018911-edb3-416b-b729-5bd80aea031e</li><li>LC-Shop-AD(GoogleAds)-OrderConfirmation-ALL(WL)-PXFLW_48b8a601-4298-4d75-a340-9efe1b1072f5</li></ul>', 2, NULL, 1, 'GTA-36956', 0, NULL, 3, 4, 2, 1, NULL, NULL, '2026-06-04 13:55:43', 1, '2026-06-04 13:55:43', 0, NULL, NULL, NULL, NULL),
(36, 'New Tag Request: RTBHouse', '<ol><li><b>ZS_Hybris_JO-Jordan (Shop Section) WebSDK</b></li><ul><li>LC-Shop-AD(RTBHouse)-Cart-WL-SDSME_ab10-62118729eba5</li><li>LC-Shop-AD(RTBHouse)-OrderConfirmation-WL-SDSME_ab10-62118729eba5</li></ul></ol>', 6, NULL, 1, 'GTA-36968', 0, NULL, 3, 4, 2, 1, NULL, NULL, '2026-06-04 14:16:12', 1, '2026-06-04 14:16:12', 0, NULL, NULL, NULL, NULL),
(37, 'New Tag Request: RTBHouse', '<ol><li><b>ZS_Hybris_JO_AR-Jordan (Shop Section) WebSDK</b></li><ul><li>LC-Shop-AD(RTBHouse)-OrderConfirmation-WL-SDSME_af47-ea652e13577e</li><li>LC-Shop-AD(RTBHouse)-Cart-WL-SDSME_af47-ea652e13577e</li></ul></ol>', 6, NULL, 1, 'GTA-36968', 0, NULL, 3, 4, 2, 1, NULL, NULL, '2026-06-04 14:17:08', 1, '2026-06-04 14:17:08', 0, NULL, NULL, NULL, NULL),
(38, 'Tag Renewals [GTA-36973]', 'Renewal of tags: <br/><ul><li>LC-Shop-AD(Snapchat)-CM-ALL(WL)-PXFLW_2a4e2688-326a-44cb-b2b4-8f6afb32a72e</li><li>LC-Shop-AD(Boostiny)-CM-OrderConfirmation-ALL(WL)-PXFLW_samsungksa</li><li>LC-Shop-AD(RTB)-CM-ALL(WL)-PXFLW_1a5c788b-7eef-4c86-9225-54680849b814</li><li>LC-Shop-AD(Boostiny)-CM-ALL(WL)-PXFLW_samsungksa</li><li>LC-Shop-AD(Linkedin)-CM-ALL(WL)-PXFLW_ffee495a-b22d-4cb1-bae3-d922ff05a74c</li><li>LC-Shop-AD(Admitad)-CM-ALL(WL)-PXFLW_80b09d97-9e7d-429c-a84a-cce155712b07</li><li>LC-Shop-AD(Criteo)-CM-ALL(WL)-PXFLW_0c38a6ff-4829-4b53-8bb5-9c42616df2f5</li><li>LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_864a73c6-865f-4056-838f-68f41f5a7c71</li></ul>', 2, NULL, 1, 'GTA-36973', 1, NULL, 3, 4, 2, 1, NULL, NULL, '2026-06-04 16:32:49', 1, '2026-06-04 16:32:49', 0, NULL, NULL, NULL, NULL),
(39, 'Tag Renewals [GTA-36990]', 'Renewal of tags: <br/><ul><li>LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_10009531</li><li>LC-Shop-AD(Insider)-OrderConfirmation-CM-ALL(WL)-PXFLW_10009531</li><li>LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_22dd3ed6-ad89-40ff-ae18-eb3df2450499</li><li>LC-Shop-AD(Snapchat)-CM-ALL(WL)-PXFLW_f46e7a8e-aea5-4fe5-b51c-9d2a9aec617e</li></ul>', 6, NULL, 1, 'GTA-36990', 1, NULL, 3, 4, 2, 1, NULL, NULL, '2026-06-04 16:50:22', 1, '2026-06-04 16:50:22', 0, NULL, NULL, NULL, NULL),
(40, 'New Tag Request: RTBHouse', '<ol><li><b>ZS_Hybris_IQ_AR-Iraq (Shop Section) WebSDK</b></li><ul><li>LC-Shop-AD(RTBHouse)-Cart-WL-SDSME_8b79-e3c381a104fc</li><li>LC-Shop-AD(RTBHouse)-OrderConfirmation-WL-SDSME_8b79-e3c381a104fc</li></ul></ol>', 6, NULL, 1, 'GTA-36877', 1, NULL, 3, 4, 2, 2, NULL, NULL, '2026-06-05 13:06:58', 1, '2026-06-05 13:06:58', 0, NULL, NULL, NULL, NULL),
(41, 'New Tag Request: RTBHouse', '<ol><li><b>zP6_IQ_AR-Iraq WebSDK</b></li><ul><li>LC-AEM-AD(RTBHouse)-Cart-ATC-SDSME_8b79-e3c381a104fc</li><li>LC-AEM-AD(RTBHouse)-ViewContent-WL-SDSME_8b79-e3c381a104fc</li></ul></ol>', 6, NULL, 1, 'GTA-36877', 1, NULL, 3, 4, 2, 2, NULL, NULL, '2026-06-05 13:57:14', 1, '2026-06-05 13:57:14', 0, NULL, NULL, NULL, NULL),
(42, 'New Tag Request: RTBHouse', '<ol><li><b>ZP6_LEVANT_AR-Levant(SELV) WebSDK</b></li><ul><li>LC-AEM-AD(RTBHouse)-Cart-ATC-SDSME_af47-ea652e13577e</li><li>LC-AEM-AD(RTBHouse)-ViewContent-WL-SDSME_af47-ea652e13577e</li></ul></ol>', 6, NULL, 1, 'GTA-36876', 1, NULL, 3, 4, 2, 2, NULL, NULL, '2026-06-05 15:12:07', 1, '2026-06-05 15:12:07', 0, NULL, NULL, NULL, NULL),
(43, 'New Tag Request: RTBHouse', '<ol><li><b>ZP6_LEVANT-Levant(SELV) WebSDK</b></li><ul><li>LC-AEM-AD(RTBHouse)-Cart-ATC-SDSME_ab10-62118729eba5</li><li>LC-AEM-AD(RTBHouse)-ViewContent-WL-SDSME_ab10-62118729eba5</li></ul></ol>', 6, NULL, 1, 'GTA-36876', 1, NULL, 3, 4, 2, 2, NULL, NULL, '2026-06-05 16:08:41', 1, '2026-06-05 16:08:41', 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activity_log_comments`
--

CREATE TABLE `activity_log_comments` (
  `id` int(11) NOT NULL,
  `log_id` int(11) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 1,
  `commented_by` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log_comments`
--

INSERT INTO `activity_log_comments` (`id`, `log_id`, `comment`, `type`, `commented_by`, `created_at`, `updated_at`) VALUES
(2, 3, 'Status updated from [In Progress - Implementation] to [Completed]', 2, 0, '2026-05-20 09:06:51', '2026-05-20 09:06:51'),
(3, 3, 'Assignee changed from [System] to [Marie]', 2, 0, '2026-05-20 09:06:51', '2026-05-20 09:06:51'),
(4, 4, 'Status updated from [In Progress - Implementation] to [Completed]', 2, 0, '2026-05-20 09:07:47', '2026-05-20 09:07:47'),
(5, 4, 'Assignee changed from [System] to [Marie]', 2, 0, '2026-05-20 09:07:47', '2026-05-20 09:07:47'),
(6, 5, 'Status updated from [In Progress - Implementation] to [Completed]', 2, 0, '2026-05-20 09:32:25', '2026-05-20 09:32:25'),
(7, 5, 'Assignee changed from [System] to [Marie]', 2, 0, '2026-05-20 09:32:25', '2026-05-20 09:32:25'),
(8, 6, 'Status updated from [In Progress - Implementation] to [Completed]', 2, 0, '2026-05-20 09:32:31', '2026-05-20 09:32:31'),
(9, 6, 'Assignee changed from [System] to [Marie]', 2, 0, '2026-05-20 09:32:31', '2026-05-20 09:32:31'),
(10, 1, 'Status updated from [Not yet started] to [Completed]', 2, 0, '2026-05-20 09:36:41', '2026-05-20 09:36:41'),
(11, 9, '<p>Attended this meeting</p>', 1, 1, '2026-05-21 10:31:03', '2026-05-21 10:31:03'),
(12, 8, 'Status updated from [In Progress - Investigation] to [Pending Review]', 2, 0, '2026-05-21 15:55:51', '2026-05-21 15:55:51'),
(13, 2, '<p>Testing blocked. Gives errors when proceeding to checkout</p>', 1, 1, '2026-05-21 17:12:26', '2026-05-21 17:12:26'),
(14, 7, 'Status updated from [In Progress - Implementation] to [Completed]', 2, 0, '2026-05-21 17:38:03', '2026-05-21 17:38:03'),
(15, 7, 'Assignee changed from [System] to [Marie]', 2, 0, '2026-05-21 17:40:41', '2026-05-21 17:40:41'),
(16, 12, 'Status updated from [In Progress - Implementation] to [Pending Review]', 2, 0, '2026-05-21 17:43:14', '2026-05-21 17:43:14'),
(17, 12, 'Assignee changed from [System] to [Marie]', 2, 0, '2026-05-21 17:43:14', '2026-05-21 17:43:14'),
(18, 15, 'Assignee changed from [System] to [Marie]', 2, 0, '2026-05-22 09:12:44', '2026-05-22 09:12:44'),
(19, 15, 'Priority updated from [Low] to [Medium]', 2, 0, '2026-05-22 09:12:44', '2026-05-22 09:12:44'),
(20, 11, 'Assignee changed from [System] to [Marie]', 2, 0, '2026-05-22 11:54:44', '2026-05-22 11:54:44'),
(21, 11, 'Priority updated from [Low] to [Medium]', 2, 0, '2026-05-22 11:54:44', '2026-05-22 11:54:44'),
(22, 10, 'Assignee changed from [System] to [Marie]', 2, 0, '2026-05-22 11:54:51', '2026-05-22 11:54:51'),
(23, 10, 'Priority updated from [Low] to [Medium]', 2, 0, '2026-05-22 11:54:51', '2026-05-22 11:54:51'),
(24, 15, 'Status updated from [In Progress - Implementation] to [Pending Review]', 2, 0, '2026-05-22 11:55:08', '2026-05-22 11:55:08'),
(25, 18, 'Assignee changed from [System] to [Marie]', 2, 0, '2026-05-22 16:10:22', '2026-05-22 16:10:22'),
(26, 18, 'Priority updated from [Low] to [Medium]', 2, 0, '2026-05-22 16:10:22', '2026-05-22 16:10:22'),
(27, 17, 'Assignee changed from [System] to [Marie]', 2, 0, '2026-05-22 16:10:28', '2026-05-22 16:10:28'),
(28, 17, 'Priority updated from [Low] to [Medium]', 2, 0, '2026-05-22 16:10:28', '2026-05-22 16:10:28'),
(29, 15, 'Status updated from [Pending Review] to [Completed]', 2, 0, '2026-05-25 09:17:14', '2026-05-25 09:17:14'),
(30, 12, 'Status updated from [Pending Review] to [Completed]', 2, 0, '2026-05-25 11:47:28', '2026-05-25 11:47:28'),
(31, 19, 'Status updated from [In Progress - Implementation] to [Completed]', 2, 0, '2026-06-01 10:58:33', '2026-06-01 10:58:33'),
(32, 10, 'Status updated from [3] to [6]', 2, 0, '2026-06-01 11:37:00', '2026-06-01 11:37:00'),
(33, 2, 'Status updated from [5] to [6]', 2, 0, '2026-06-01 11:44:24', '2026-06-01 11:44:24'),
(34, 23, 'Status updated from [In Progress - Implementation] to [Completed]', 2, 0, '2026-06-02 12:28:22', '2026-06-02 12:28:22'),
(35, 26, 'Status updated from [In Progress - Implementation] to [Completed]', 2, 0, '2026-06-02 13:51:42', '2026-06-02 13:51:42'),
(36, 21, 'Status updated from [In Progress - Implementation] to [Completed]', 2, 0, '2026-06-02 14:18:30', '2026-06-02 14:18:30'),
(37, 22, 'Status updated from [In Progress - Implementation] to [Completed]', 2, 0, '2026-06-02 14:23:37', '2026-06-02 14:23:37'),
(38, 30, 'Status updated from [3] to [6]', 2, 0, '2026-06-03 08:42:02', '2026-06-03 08:42:02');

-- --------------------------------------------------------

--
-- Table structure for table `activity_log_files`
--

CREATE TABLE `activity_log_files` (
  `id` int(11) NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `log_id` tinyint(4) NOT NULL,
  `created_by` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log_files`
--

INSERT INTO `activity_log_files` (`id`, `file_name`, `file_path`, `log_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'ErrorScreenshot_TestingBlocked.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/activity_logs//ErrorScreenshot_TestingBlocked.png', 2, 1, '2026-05-21 17:16:17', '2026-05-21 17:16:17'),
(2, '20260515_RE_(7)_[Live_Commerce]_EPP_Lives.eml', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/activity_logs//20260515_RE_(7)_[Live_Commerce]_EPP_Lives.eml', 15, 1, '2026-05-22 11:51:54', '2026-05-22 11:51:54'),
(3, 'STG3 and STG2 issue.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/activity_logs//STG3 and STG2 issue.png', 2, 1, '2026-05-22 15:04:58', '2026-05-22 15:04:58');

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
(1, 'GTA-31600', '2', '1', 1, 17, 4, 1, NULL, 0, '2026-01-13 17:16:00', 0, '2026-01-29 11:43:00'),
(2, 'GTA-31599', '3', '1', 1, 17, 4, 1, NULL, 0, '2026-01-13 17:14:00', 0, '2026-03-23 08:50:00'),
(3, 'GTA-32613', '2', '2', 2, 1, 1, 1, NULL, 0, '2026-02-04 15:41:00', 0, '2026-03-20 13:01:00'),
(4, 'GTA-32613', '2', '3', 3, 2, 1, 1, NULL, 0, '2026-02-04 15:41:00', 0, '2026-03-20 13:01:00'),
(5, 'GTA-32613', '2', '4,5', 4, 4, 1, 1, NULL, 0, '2026-02-04 15:41:00', 0, '2026-03-20 13:01:00'),
(6, 'GTA-32613', '2', '4,5', 5, 4, 1, 1, NULL, 0, '2026-02-04 15:41:00', 0, '2026-03-20 13:01:00'),
(7, 'GTA-32613', '2', '6,7', 6, 6, 1, 1, NULL, 0, '2026-02-04 15:41:00', 0, '2026-03-20 13:01:00'),
(8, 'GTA-32613', '2', '6,7', 7, 6, 1, 1, NULL, 0, '2026-02-04 15:41:00', 0, '2026-03-20 13:01:00'),
(9, 'GTA-33100', '3', '2', 2, 1, 1, 1, NULL, 0, '2026-02-16 14:02:00', 0, '2026-04-14 09:05:00'),
(10, 'GTA-33100', '3', '3', 3, 2, 1, 1, NULL, 0, '2026-02-16 14:02:00', 0, '2026-04-14 09:05:00'),
(11, 'GTA-33100', '3', '4,5', 4, 4, 1, 1, NULL, 0, '2026-02-16 14:02:00', 0, '2026-04-14 09:05:00'),
(12, 'GTA-33100', '3', '4,5', 5, 4, 1, 1, NULL, 0, '2026-02-16 14:02:00', 0, '2026-04-14 09:05:00'),
(13, 'GTA-33100', '3', '6,7', 6, 6, 1, 1, NULL, 0, '2026-02-16 14:02:00', 0, '2026-04-14 09:05:00'),
(14, 'GTA-33100', '3', '6,7', 7, 6, 1, 1, NULL, 0, '2026-02-16 14:02:00', 0, '2026-04-14 09:05:00'),
(15, 'GTA-33614', '2', '8', 8, 25, 6, 1, NULL, 0, '2026-02-27 13:05:00', 0, '2026-03-02 09:37:00'),
(16, 'GTA-33474', '3', '8', 8, 25, 6, 1, NULL, 0, '2026-02-25 10:22:00', 0, '2026-04-15 09:48:00'),
(17, 'GTA-33617', '2', '9', 9, 34, 6, 1, NULL, 0, '2026-02-27 13:14:00', 0, '2026-04-06 12:04:00'),
(18, 'GTA-33617', '2', '10', 10, 32, 6, 1, NULL, 0, '2026-02-27 13:14:00', 0, '2026-04-06 12:04:00'),
(19, 'GTA-33608', '3', '9', 9, 34, 6, 1, NULL, 0, '2026-02-27 11:57:00', 0, '2026-04-20 11:12:00'),
(20, 'GTA-33609', '3', '10', 10, 32, 6, 1, NULL, 0, '2026-02-27 12:00:00', 0, '2026-04-20 11:12:00'),
(21, 'GTA-33640', '2', '11', 11, 22, 6, 1, NULL, 0, '2026-03-02 10:20:00', 0, '2026-04-06 12:04:00'),
(22, 'GTA-33640', '2', '12', 12, 30, 6, 1, NULL, 0, '2026-03-02 10:20:00', 0, '2026-04-06 12:04:00'),
(23, 'GTA-33610', '3', '11', 11, 22, 6, 1, NULL, 0, '2026-02-27 12:28:00', 0, '2026-04-24 10:57:00'),
(24, 'GTA-33610', '3', '12', 12, 30, 6, 1, NULL, 0, '2026-02-27 12:28:00', 0, '2026-04-24 10:57:00'),
(25, 'GTA-33642', '2', '13', 13, 23, 6, 1, NULL, 0, '2026-03-02 11:18:00', 0, '2026-04-06 12:04:00'),
(26, 'GTA-33642', '2', '14', 14, 31, 6, 1, NULL, 0, '2026-03-02 11:18:00', 0, '2026-04-06 12:04:00'),
(27, 'GTA-33678', '3', '13', 13, 23, 6, 1, NULL, 0, '2026-03-03 08:45:00', 0, '2026-04-24 10:57:00'),
(28, 'GTA-33678', '3', '14', 14, 31, 6, 1, NULL, 0, '2026-03-03 08:45:00', 0, '2026-04-24 10:57:00'),
(29, 'GTA-34118', '2', '15,16', 15, 39, 8, 1, NULL, 0, '2026-03-11 11:38:00', 0, '2026-03-24 15:57:00'),
(30, 'GTA-34118', '2', '15,16', 16, 39, 8, 1, NULL, 0, '2026-03-11 11:38:00', 0, '2026-03-24 15:57:00'),
(31, 'GTA-34118', '2', '17,18', 17, 42, 8, 1, NULL, 0, '2026-03-11 11:38:00', 0, '2026-03-24 15:57:00'),
(32, 'GTA-34118', '2', '17,18', 18, 42, 8, 1, NULL, 0, '2026-03-11 11:38:00', 0, '2026-03-24 15:57:00'),
(33, 'GTA-34565', '3', '15,16', 15, 39, 8, 1, NULL, 0, '2026-03-24 15:56:00', 0, '2026-04-01 12:43:00'),
(34, 'GTA-34565', '3', '15,16', 16, 39, 8, 1, NULL, 0, '2026-03-24 15:56:00', 0, '2026-04-01 12:43:00'),
(35, 'GTA-34565', '3', '17,18', 17, 42, 8, 1, NULL, 0, '2026-03-24 15:56:00', 0, '2026-04-01 12:43:00'),
(36, 'GTA-34565', '3', '17,18', 18, 42, 8, 1, NULL, 0, '2026-03-24 15:56:00', 0, '2026-04-01 12:43:00'),
(37, 'GTA-34564', '2', '19', 19, 17, 4, 1, NULL, 0, '2026-03-24 15:27:00', 0, '2026-03-25 09:02:00'),
(38, 'GTA-34578', '3', '19', 19, 17, 4, 1, NULL, 0, '2026-03-25 08:58:00', 0, '2026-05-12 10:34:00'),
(39, 'GTA-34656', '2', '20', 20, 3, 1, 1, NULL, 0, '2026-03-26 12:26:00', 0, '2026-03-30 17:02:00'),
(40, 'GTA-34796', '3', '20', 20, 3, 1, 1, NULL, 0, '2026-03-30 17:02:00', 0, '2026-05-21 17:28:00'),
(41, 'GTA-36017', '2', '21', 21, 22, 6, 1, NULL, 0, '2026-05-04 11:00:00', 0, '2026-05-05 12:04:00'),
(42, 'GTA-36055', '3', '21', 21, 22, 6, 1, NULL, 0, '2026-05-05 12:03:00', 0, '2026-05-05 20:52:00'),
(43, 'GTA-36055', '2', '22', 22, 30, 6, 1, NULL, 0, '2026-05-05 12:03:00', 0, '2026-05-05 20:52:00'),
(44, 'GTA-36055', '3', '22', 22, 30, 6, 1, NULL, 0, '2026-05-05 12:03:00', 0, '2026-05-05 20:52:00'),
(45, 'GTA-36092', '2', '23', 23, 34, 6, 1, NULL, 0, '2026-05-06 10:29:00', 0, '2026-05-06 18:24:00'),
(46, 'GTA-36092', '3', '23', 23, 34, 6, 1, NULL, 0, '2026-05-06 10:29:00', 0, '2026-05-06 18:24:00'),
(47, 'GTA-36019', '2', '24', 24, 32, 6, 1, NULL, 0, '2026-05-04 11:35:00', 0, '2026-05-06 10:29:00'),
(48, 'GTA-36092', '3', '24', 24, 32, 6, 1, NULL, 0, '2026-05-06 10:29:00', 0, '2026-05-06 18:24:00'),
(49, 'GTA-31467', '3', '25', 25, 13, 3, 1, NULL, 0, '2026-01-09 13:15:00', 0, '2026-02-12 13:20:00'),
(50, 'GTA-31570', '3', '27', 27, 7, 2, 1, NULL, 0, '2026-01-13 12:13:00', 0, '2026-02-12 13:31:00'),
(51, 'GTA-31570', '3', '28', 28, 10, 2, 1, NULL, 0, '2026-01-13 12:13:00', 0, '2026-02-12 13:31:00'),
(52, 'GTA-31574', '3', '29', 29, 8, 2, 1, NULL, 0, '2026-01-13 12:37:00', 0, '2026-02-12 13:41:00'),
(53, 'GTA-31574', '3', '30', 30, 12, 2, 1, NULL, 0, '2026-01-13 12:37:00', 0, '2026-02-12 13:41:00'),
(54, 'GTA-31701', '3', '31', 31, 17, 4, 1, NULL, 0, '2026-01-14 21:38:00', 0, '2026-02-16 12:47:00'),
(55, 'GTA-31701', '3', '32', 32, 20, 4, 1, NULL, 0, '2026-01-14 21:38:00', 0, '2026-02-16 12:47:00'),
(56, 'GTA-31718', '3', '33', 33, 43, 9, 1, NULL, 0, '2026-01-15 17:11:00', 0, '2026-03-06 07:53:00'),
(57, 'GTA-31718', '3', '34', 34, 46, 9, 1, NULL, 0, '2026-01-15 17:11:00', 0, '2026-03-06 07:53:00'),
(58, 'GTA-36107', '2', '36', 36, 7, 2, 1, NULL, 0, '2026-05-06 13:07:00', 0, '2026-05-08 17:47:00'),
(59, 'GTA-36107', '2', '37', 37, 8, 2, 1, NULL, 0, '2026-05-06 13:07:00', 0, '2026-05-08 17:47:00'),
(60, 'GTA-31762', '3', '38', 38, 35, 7, 1, NULL, 0, '2026-01-16 14:21:00', 0, '2026-03-09 09:31:00'),
(61, 'GTA-31762', '3', '39', 39, 38, 7, 1, NULL, 0, '2026-01-16 14:21:00', 0, '2026-03-09 09:31:00'),
(62, 'GTA-31814', '3', '40', 40, 39, 8, 1, NULL, 0, '2026-01-18 13:25:00', 0, '2026-03-09 09:38:00'),
(63, 'GTA-31814', '3', '41', 41, 42, 8, 1, NULL, 0, '2026-01-18 13:25:00', 0, '2026-03-09 09:38:00'),
(64, 'GTA-31818', '3', '42', 42, 24, 6, 1, NULL, 0, '2026-01-18 15:41:00', 0, '2026-03-09 09:12:00'),
(65, 'GTA-31819', '3', '43', 43, 26, 6, 1, NULL, 0, '2026-01-18 16:09:00', 0, '2026-03-09 09:12:00'),
(66, 'GTA-31819', '3', '44', 44, 25, 6, 1, NULL, 0, '2026-01-18 16:09:00', 0, '2026-03-09 09:12:00'),
(67, 'GTA-31819', '3', '45', 45, 34, 6, 1, NULL, 0, '2026-01-18 16:09:00', 0, '2026-03-09 09:12:00'),
(68, 'GTA-31820', '3', '46', 46, 32, 6, 1, NULL, 0, '2026-01-18 16:13:00', 0, '2026-03-09 09:12:00'),
(69, 'GTA-31821', '3', '47', 47, 22, 6, 1, NULL, 0, '2026-01-18 16:17:00', 0, '2026-03-10 12:39:00'),
(70, 'GTA-31821', '3', '48', 48, 30, 6, 1, NULL, 0, '2026-01-18 16:17:00', 0, '2026-03-10 12:39:00'),
(71, 'GTA-31822', '3', '49', 49, 23, 6, 1, NULL, 0, '2026-01-18 16:21:00', 0, '2026-03-10 12:40:00'),
(72, 'GTA-31822', '3', '50', 50, 31, 6, 1, NULL, 0, '2026-01-18 16:21:00', 0, '2026-03-10 12:40:00'),
(73, 'GTA-36223', '3', '36', 36, 7, 2, 1, NULL, 0, '2026-05-08 17:42:00', 0, '2026-05-20 06:26:00'),
(74, 'GTA-36223', '3', '37', 37, 8, 2, 1, NULL, 0, '2026-05-08 17:42:00', 0, '2026-05-20 06:26:00'),
(75, 'GTA-32102', '3', '53,54', 53, 2, 1, 1, NULL, 0, '2026-01-23 12:58:00', 0, '2026-01-29 14:12:00'),
(76, 'GTA-32102', '3', '53,54', 54, 2, 1, 1, NULL, 0, '2026-01-23 12:58:00', 0, '2026-01-29 14:12:00'),
(77, 'GTA-32102', '3', '55,56,57,58', 55, 6, 1, 1, NULL, 0, '2026-01-23 12:58:00', 0, '2026-01-29 14:12:00'),
(78, 'GTA-32102', '3', '55,56,57,58', 56, 6, 1, 1, NULL, 0, '2026-01-23 12:58:00', 0, '2026-01-29 14:12:00'),
(79, 'GTA-32102', '3', '55,56,57,58', 57, 6, 1, 1, NULL, 0, '2026-01-23 12:58:00', 0, '2026-01-29 14:12:00'),
(80, 'GTA-32102', '3', '55,56,57,58', 58, 6, 1, 1, NULL, 0, '2026-01-23 12:58:00', 0, '2026-01-29 14:12:00'),
(81, 'GTA-32418', '3', '59,60', 59, 1, 1, 1, NULL, 0, '2026-01-30 11:13:00', 0, '2026-02-19 09:55:00'),
(82, 'GTA-32418', '3', '59,60', 60, 1, 1, 1, NULL, 0, '2026-01-30 11:13:00', 0, '2026-02-19 09:55:00'),
(83, 'GTA-32418', '3', '61,62,63,64', 61, 4, 1, 1, NULL, 0, '2026-01-30 11:13:00', 0, '2026-02-19 09:55:00'),
(84, 'GTA-32418', '3', '61,62,63,64', 62, 4, 1, 1, NULL, 0, '2026-01-30 11:13:00', 0, '2026-02-19 09:55:00'),
(85, 'GTA-32418', '3', '61,62,63,64', 63, 4, 1, 1, NULL, 0, '2026-01-30 11:13:00', 0, '2026-02-19 09:55:00'),
(86, 'GTA-32418', '3', '61,62,63,64', 64, 4, 1, 1, NULL, 0, '2026-01-30 11:13:00', 0, '2026-02-19 09:55:00'),
(87, 'GTA-32438', '3', '65,66', 65, 13, 3, 1, NULL, 0, '2026-01-30 15:53:00', 0, '2026-02-20 11:42:00'),
(88, 'GTA-32438', '3', '65,66', 66, 13, 3, 1, NULL, 0, '2026-01-30 15:53:00', 0, '2026-02-20 11:42:00'),
(89, 'GTA-32438', '3', '67,68,69,70', 67, 16, 3, 1, NULL, 0, '2026-01-30 15:53:00', 0, '2026-02-20 11:42:00'),
(90, 'GTA-32438', '3', '67,68,69,70', 68, 16, 3, 1, NULL, 0, '2026-01-30 15:53:00', 0, '2026-02-20 11:42:00'),
(91, 'GTA-32438', '3', '67,68,69,70', 69, 16, 3, 1, NULL, 0, '2026-01-30 15:53:00', 0, '2026-02-20 11:42:00'),
(92, 'GTA-32438', '3', '67,68,69,70', 70, 16, 3, 1, NULL, 0, '2026-01-30 15:53:00', 0, '2026-02-20 11:42:00'),
(93, 'GTA-32614', '3', '71,72', 71, 7, 2, 1, NULL, 0, '2026-02-04 16:09:00', 0, '2026-02-19 13:31:00'),
(94, 'GTA-32614', '3', '71,72', 72, 7, 2, 1, NULL, 0, '2026-02-04 16:09:00', 0, '2026-02-19 13:31:00'),
(95, 'GTA-32614', '3', '73,74,75,76', 73, 10, 2, 1, NULL, 0, '2026-02-04 16:09:00', 0, '2026-02-19 13:31:00'),
(96, 'GTA-32614', '3', '73,74,75,76', 74, 10, 2, 1, NULL, 0, '2026-02-04 16:09:00', 0, '2026-02-19 13:31:00'),
(97, 'GTA-32614', '3', '73,74,75,76', 75, 10, 2, 1, NULL, 0, '2026-02-04 16:09:00', 0, '2026-02-19 13:31:00'),
(98, 'GTA-32614', '3', '73,74,75,76', 76, 10, 2, 1, NULL, 0, '2026-02-04 16:09:00', 0, '2026-02-19 13:31:00'),
(99, 'GTA-32630', '3', '77,78', 77, 8, 2, 1, NULL, 0, '2026-02-05 10:44:00', 0, '2026-02-25 11:32:00'),
(100, 'GTA-32630', '3', '77,78', 78, 8, 2, 1, NULL, 0, '2026-02-05 10:44:00', 0, '2026-02-25 11:32:00'),
(101, 'GTA-32630', '3', '79,80,81,82', 79, 12, 2, 1, NULL, 0, '2026-02-05 10:44:00', 0, '2026-02-25 11:32:00'),
(102, 'GTA-32630', '3', '79,80,81,82', 80, 12, 2, 1, NULL, 0, '2026-02-05 10:44:00', 0, '2026-02-25 11:32:00'),
(103, 'GTA-32630', '3', '79,80,81,82', 81, 12, 2, 1, NULL, 0, '2026-02-05 10:44:00', 0, '2026-02-25 11:32:00'),
(104, 'GTA-32630', '3', '79,80,81,82', 82, 12, 2, 1, NULL, 0, '2026-02-05 10:44:00', 0, '2026-02-25 11:32:00'),
(105, 'GTA-32664', '3', '83,84', 83, 17, 4, 1, NULL, 0, '2026-02-06 10:33:00', 0, '2026-03-12 10:31:00'),
(106, 'GTA-32664', '3', '83,84', 84, 17, 4, 1, NULL, 0, '2026-02-06 10:33:00', 0, '2026-03-12 10:31:00'),
(107, 'GTA-32664', '3', '85,86,87,88', 85, 20, 4, 1, NULL, 0, '2026-02-06 10:33:00', 0, '2026-03-12 10:31:00'),
(108, 'GTA-32664', '3', '85,86,87,88', 86, 20, 4, 1, NULL, 0, '2026-02-06 10:33:00', 0, '2026-03-12 10:31:00'),
(109, 'GTA-32664', '3', '85,86,87,88', 87, 20, 4, 1, NULL, 0, '2026-02-06 10:33:00', 0, '2026-03-12 10:31:00'),
(110, 'GTA-32664', '3', '85,86,87,88', 88, 20, 4, 1, NULL, 0, '2026-02-06 10:33:00', 0, '2026-03-12 10:31:00'),
(111, 'GTA-32680', '3', '89,90', 89, 43, 9, 1, NULL, 0, '2026-02-06 13:09:00', 0, '2026-02-20 12:35:00'),
(112, 'GTA-32680', '3', '89,90', 90, 43, 9, 1, NULL, 0, '2026-02-06 13:09:00', 0, '2026-02-20 12:35:00'),
(113, 'GTA-32680', '3', '91,92,93,94', 91, 46, 9, 1, NULL, 0, '2026-02-06 13:09:00', 0, '2026-02-20 12:35:00'),
(114, 'GTA-32680', '3', '91,92,93,94', 92, 46, 9, 1, NULL, 0, '2026-02-06 13:09:00', 0, '2026-02-20 12:35:00'),
(115, 'GTA-32680', '3', '91,92,93,94', 93, 46, 9, 1, NULL, 0, '2026-02-06 13:09:00', 0, '2026-02-20 12:35:00'),
(116, 'GTA-32680', '3', '91,92,93,94', 94, 46, 9, 1, NULL, 0, '2026-02-06 13:09:00', 0, '2026-02-20 12:35:00'),
(117, 'GTA-32828', '3', '95,96', 95, 35, 7, 1, NULL, 0, '2026-02-10 10:34:00', 0, '2026-02-27 09:27:00'),
(118, 'GTA-32828', '3', '95,96', 96, 35, 7, 1, NULL, 0, '2026-02-10 10:34:00', 0, '2026-02-27 09:27:00'),
(119, 'GTA-32828', '3', '97,98,99,100', 97, 38, 7, 1, NULL, 0, '2026-02-10 10:34:00', 0, '2026-02-27 09:27:00'),
(120, 'GTA-32828', '3', '97,98,99,100', 98, 38, 7, 1, NULL, 0, '2026-02-10 10:34:00', 0, '2026-02-27 09:27:00'),
(121, 'GTA-32828', '3', '97,98,99,100', 99, 38, 7, 1, NULL, 0, '2026-02-10 10:34:00', 0, '2026-02-27 09:27:00'),
(122, 'GTA-32828', '3', '97,98,99,100', 100, 38, 7, 1, NULL, 0, '2026-02-10 10:34:00', 0, '2026-02-27 09:27:00'),
(123, 'GTA-32928', '3', '101,102', 101, 39, 8, 1, NULL, 0, '2026-02-12 13:05:00', 0, '2026-02-24 04:52:00'),
(124, 'GTA-32928', '3', '101,102', 102, 39, 8, 1, NULL, 0, '2026-02-12 13:05:00', 0, '2026-02-24 04:52:00'),
(125, 'GTA-32928', '3', '103,104,105,106', 103, 42, 8, 1, NULL, 0, '2026-02-12 13:05:00', 0, '2026-02-24 04:52:00'),
(126, 'GTA-32928', '3', '103,104,105,106', 104, 42, 8, 1, NULL, 0, '2026-02-12 13:05:00', 0, '2026-02-24 04:52:00'),
(127, 'GTA-32928', '3', '103,104,105,106', 105, 42, 8, 1, NULL, 0, '2026-02-12 13:05:00', 0, '2026-02-24 04:52:00'),
(128, 'GTA-32928', '3', '103,104,105,106', 106, 42, 8, 1, NULL, 0, '2026-02-12 13:05:00', 0, '2026-02-24 04:52:00'),
(129, 'GTA-33089', '3', '107,108', 107, 26, 6, 1, NULL, 0, '2026-02-16 09:36:00', 0, '2026-03-20 12:41:00'),
(130, 'GTA-33089', '3', '107,108', 108, 26, 6, 1, NULL, 0, '2026-02-16 09:36:00', 0, '2026-03-20 12:41:00'),
(131, 'GTA-33090', '3', '109,110', 109, 25, 6, 1, NULL, 0, '2026-02-16 10:56:00', 0, '2026-03-20 12:41:00'),
(132, 'GTA-33090', '3', '109,110', 110, 25, 6, 1, NULL, 0, '2026-02-16 10:56:00', 0, '2026-03-20 12:41:00'),
(133, 'GTA-33112', '3', '111', 111, 8, 2, 1, NULL, 0, '2026-02-16 15:28:00', 0, '2026-05-18 09:52:00'),
(134, 'GTA-33112', '3', '112', 112, 12, 2, 1, NULL, 0, '2026-02-16 15:28:00', 0, '2026-05-18 09:52:00'),
(135, 'GTA-33122', '3', '113,114,115,116', 113, 34, 6, 1, NULL, 0, '2026-02-16 16:20:00', 0, '2026-03-20 12:41:00'),
(136, 'GTA-33122', '3', '113,114,115,116', 114, 34, 6, 1, NULL, 0, '2026-02-16 16:20:00', 0, '2026-03-20 12:41:00'),
(137, 'GTA-33122', '3', '113,114,115,116', 115, 34, 6, 1, NULL, 0, '2026-02-16 16:20:00', 0, '2026-03-20 12:41:00'),
(138, 'GTA-33122', '3', '113,114,115,116', 116, 34, 6, 1, NULL, 0, '2026-02-16 16:20:00', 0, '2026-03-20 12:41:00'),
(139, 'GTA-33149', '3', '117,118,119,120', 117, 32, 6, 1, NULL, 0, '2026-02-17 12:54:00', 0, '2026-04-06 12:03:00'),
(140, 'GTA-33149', '3', '117,118,119,120', 118, 32, 6, 1, NULL, 0, '2026-02-17 12:54:00', 0, '2026-04-06 12:03:00'),
(141, 'GTA-33149', '3', '117,118,119,120', 119, 32, 6, 1, NULL, 0, '2026-02-17 12:54:00', 0, '2026-04-06 12:03:00'),
(142, 'GTA-33149', '3', '117,118,119,120', 120, 32, 6, 1, NULL, 0, '2026-02-17 12:54:00', 0, '2026-04-06 12:03:00'),
(143, 'GTA-33154', '3', '121,122', 121, 22, 6, 1, NULL, 0, '2026-02-17 13:38:00', 0, '2026-04-06 12:03:00'),
(144, 'GTA-33154', '3', '121,122', 122, 22, 6, 1, NULL, 0, '2026-02-17 13:38:00', 0, '2026-04-06 12:03:00'),
(145, 'GTA-33154', '3', '123,124,125,126', 123, 30, 6, 1, NULL, 0, '2026-02-17 13:38:00', 0, '2026-04-06 12:03:00'),
(146, 'GTA-33154', '3', '123,124,125,126', 124, 30, 6, 1, NULL, 0, '2026-02-17 13:38:00', 0, '2026-04-06 12:03:00'),
(147, 'GTA-33154', '3', '123,124,125,126', 125, 30, 6, 1, NULL, 0, '2026-02-17 13:38:00', 0, '2026-04-06 12:03:00'),
(148, 'GTA-33154', '3', '123,124,125,126', 126, 30, 6, 1, NULL, 0, '2026-02-17 13:38:00', 0, '2026-04-06 12:03:00'),
(149, 'GTA-33156', '3', '127,128', 127, 23, 6, 1, NULL, 0, '2026-02-17 16:10:00', 0, '2026-03-20 12:41:00'),
(150, 'GTA-33156', '3', '127,128', 128, 23, 6, 1, NULL, 0, '2026-02-17 16:10:00', 0, '2026-03-20 12:41:00'),
(151, 'GTA-33156', '3', '129,130,131,132', 129, 31, 6, 1, NULL, 0, '2026-02-17 16:10:00', 0, '2026-03-20 12:41:00'),
(152, 'GTA-33156', '3', '129,130,131,132', 130, 31, 6, 1, NULL, 0, '2026-02-17 16:10:00', 0, '2026-03-20 12:41:00'),
(153, 'GTA-33156', '3', '129,130,131,132', 131, 31, 6, 1, NULL, 0, '2026-02-17 16:10:00', 0, '2026-03-20 12:41:00'),
(154, 'GTA-33156', '3', '129,130,131,132', 132, 31, 6, 1, NULL, 0, '2026-02-17 16:10:00', 0, '2026-03-20 12:41:00'),
(155, 'GTA-33182', '3', '133,134', 133, 24, 6, 1, NULL, 0, '2026-02-18 09:45:00', 0, '2026-04-06 12:03:00'),
(156, 'GTA-33182', '3', '133,134', 134, 24, 6, 1, NULL, 0, '2026-02-18 09:45:00', 0, '2026-04-06 12:03:00'),
(157, 'GTA-33267', '3', '135', 135, 7, 2, 1, NULL, 0, '2026-02-20 08:29:00', 0, '2026-04-09 09:07:00'),
(158, 'GTA-33267', '3', '136', 136, 10, 2, 1, NULL, 0, '2026-02-20 08:29:00', 0, '2026-04-09 09:07:00'),
(159, 'GTA-33268', '3', '137', 137, 17, 4, 1, NULL, 0, '2026-02-20 10:15:00', 0, '2026-04-09 09:11:00'),
(160, 'GTA-33268', '3', '138', 138, 20, 4, 1, NULL, 0, '2026-02-20 10:15:00', 0, '2026-04-09 09:11:00'),
(161, 'GTA-33363', '3', '139', 139, 43, 9, 1, NULL, 0, '2026-02-23 11:32:00', 0, '2026-04-10 08:26:00'),
(162, 'GTA-33363', '3', '140', 140, 46, 9, 1, NULL, 0, '2026-02-23 11:32:00', 0, '2026-04-10 08:26:00'),
(163, 'GTA-33372', '3', '141', 141, 35, 7, 1, NULL, 0, '2026-02-23 12:28:00', 0, '2026-04-14 09:44:00'),
(164, 'GTA-33372', '3', '142', 142, 38, 7, 1, NULL, 0, '2026-02-23 12:28:00', 0, '2026-04-14 09:44:00'),
(165, 'GTA-33431', '3', '143', 143, 39, 8, 1, NULL, 0, '2026-02-24 09:42:00', 0, '2026-04-15 12:20:00'),
(166, 'GTA-33431', '3', '144', 144, 42, 8, 1, NULL, 0, '2026-02-24 09:42:00', 0, '2026-04-15 12:20:00'),
(167, 'GTA-33473', '3', '145', 145, 26, 6, 1, NULL, 0, '2026-02-25 10:02:00', 0, '2026-04-15 09:47:00'),
(168, 'GTA-33517', '3', '146', 146, 39, 8, 1, NULL, 0, '2026-02-25 18:25:00', 0, '2026-04-22 12:20:00'),
(169, 'GTA-33517', '3', '147', 147, 42, 8, 1, NULL, 0, '2026-02-25 18:25:00', 0, '2026-04-22 12:20:00'),
(170, 'GTA-33524', '3', '148', 148, 34, 6, 1, NULL, 0, '2026-02-25 19:08:00', 0, '2026-04-15 12:28:00'),
(171, 'GTA-33524', '3', '149', 149, 32, 6, 1, NULL, 0, '2026-02-25 19:08:00', 0, '2026-04-15 12:28:00'),
(172, 'GTA-33525', '3', '150', 150, 22, 6, 1, NULL, 0, '2026-02-25 19:11:00', 0, '2026-04-22 12:19:00'),
(173, 'GTA-33525', '3', '151', 151, 30, 6, 1, NULL, 0, '2026-02-25 19:11:00', 0, '2026-04-22 12:19:00'),
(174, 'GTA-33563', '3', '152', 152, 26, 6, 1, NULL, 0, '2026-02-26 14:39:00', 0, '2026-04-17 07:51:00'),
(175, 'GTA-33563', '3', '153', 153, 25, 6, 1, NULL, 0, '2026-02-26 14:39:00', 0, '2026-04-17 07:51:00'),
(176, 'GTA-34166', '3', '154,155', 154, 21, 5, 1, NULL, 0, '2026-03-12 10:26:00', 0, '2026-04-13 06:25:00'),
(177, 'GTA-34166', '3', '154,155', 155, 21, 5, 1, NULL, 0, '2026-03-12 10:26:00', 0, '2026-04-13 06:25:00'),
(178, 'GTA-35665', '2', '158,159', 158, 1, 1, 1, NULL, 0, '2026-04-23 15:19:00', 0, '2026-05-11 14:18:00'),
(179, 'GTA-35665', '2', '158,159', 159, 1, 1, 1, NULL, 0, '2026-04-23 15:19:00', 0, '2026-05-11 14:18:00'),
(180, 'GTA-35665', '2', '160,161', 160, 2, 1, 1, NULL, 0, '2026-04-23 15:19:00', 0, '2026-05-11 14:18:00'),
(181, 'GTA-35665', '2', '160,161', 161, 2, 1, 1, NULL, 0, '2026-04-23 15:19:00', 0, '2026-05-11 14:18:00'),
(182, 'GTA-35665', '2', '162', 162, 4, 1, 1, NULL, 0, '2026-04-23 15:19:00', 0, '2026-05-11 14:18:00'),
(183, 'GTA-35665', '2', '163', 163, 6, 1, 1, NULL, 0, '2026-04-23 15:19:00', 0, '2026-05-11 14:18:00'),
(184, 'GTA-36279', '3', '163', 163, 6, 1, 1, NULL, 0, '2026-05-11 14:18:00', 0, '2026-05-28 12:07:00'),
(185, 'GTA-36279', '3', '162', 162, 4, 1, 1, NULL, 0, '2026-05-11 14:18:00', 0, '2026-05-28 12:07:00'),
(186, 'GTA-36279', '3', '160,161', 160, 2, 1, 1, NULL, 0, '2026-05-11 14:18:00', 0, '2026-05-28 12:07:00'),
(187, 'GTA-36279', '3', '160,161', 161, 2, 1, 1, NULL, 0, '2026-05-11 14:18:00', 0, '2026-05-28 12:07:00'),
(188, 'GTA-36279', '3', '158,159', 158, 1, 1, 1, NULL, 0, '2026-05-11 14:18:00', 0, '2026-05-28 12:07:00'),
(189, 'GTA-36279', '3', '158,159', 159, 1, 1, 1, NULL, 0, '2026-05-11 14:18:00', 0, '2026-05-28 12:07:00'),
(190, 'GTA-36488', '2', '164,165', 164, 10, 2, 1, NULL, 0, '2026-05-19 16:09:00', 0, '2026-05-21 13:15:00'),
(191, 'GTA-36488', '2', '164,165', 165, 10, 2, 1, NULL, 0, '2026-05-19 16:09:00', 0, '2026-05-21 13:15:00'),
(192, 'GTA-36488', '2', '166,167', 166, 12, 2, 1, NULL, 0, '2026-05-19 16:09:00', 0, '2026-05-21 13:15:00'),
(193, 'GTA-36488', '2', '166,167', 167, 12, 2, 1, NULL, 0, '2026-05-19 16:09:00', 0, '2026-05-21 13:15:00'),
(194, 'GTA-36321', '1', '18,17', 18, 42, 8, 1, NULL, 0, '2026-05-12 13:35:00', 0, '2026-05-25 13:35:00'),
(195, 'GTA-36321', '1', '18,17', 17, 42, 8, 1, NULL, 0, '2026-05-12 13:35:00', 0, '2026-05-25 13:35:00'),
(196, 'GTA-36321', '1', '15,16', 15, 39, 8, 1, NULL, 0, '2026-05-12 13:35:00', 0, '2026-05-25 13:35:00'),
(197, 'GTA-36321', '1', '15,16', 16, 39, 8, 1, NULL, 0, '2026-05-12 13:35:00', 0, '2026-05-25 13:35:00'),
(198, 'GTA-36314', '1', '19', 19, 17, 4, 1, NULL, 0, '2026-05-12 10:29:00', 0, '2026-05-20 09:34:00'),
(199, 'GTA-36314', '1', '1', 1, 17, 4, 1, NULL, 0, '2026-05-12 10:29:00', 0, '2026-05-20 09:34:00'),
(200, 'GTA-35990', '4', 'Q2 2026', 0, 0, 1, 1, NULL, 0, '2026-05-01 17:32:00', 0, '2026-05-06 07:18:00'),
(201, 'GTA-35990', '4', 'Q2 2026', 0, 0, 2, 1, NULL, 0, '2026-05-01 17:32:00', 0, '2026-05-06 07:18:00'),
(202, 'GTA-35990', '4', 'Q2 2026', 0, 0, 3, 1, NULL, 0, '2026-05-01 17:32:00', 0, '2026-05-06 07:18:00'),
(203, 'GTA-35990', '4', 'Q2 2026', 0, 0, 4, 1, NULL, 0, '2026-05-01 17:32:00', 0, '2026-05-06 07:18:00'),
(204, 'GTA-35990', '4', 'Q2 2026', 0, 0, 5, 1, NULL, 0, '2026-05-01 17:32:00', 0, '2026-05-06 07:18:00'),
(205, 'GTA-35990', '4', 'Q2 2026', 0, 0, 6, 1, NULL, 0, '2026-05-01 17:32:00', 0, '2026-05-06 07:18:00'),
(206, 'GTA-35990', '4', 'Q2 2026', 0, 0, 7, 1, NULL, 0, '2026-05-01 17:32:00', 0, '2026-05-06 07:18:00'),
(207, 'GTA-35990', '4', 'Q2 2026', 0, 0, 8, 1, NULL, 0, '2026-05-01 17:32:00', 0, '2026-05-06 07:18:00'),
(208, 'GTA-35990', '4', 'Q2 2026', 0, 0, 9, 1, NULL, 0, '2026-05-01 17:32:00', 0, '2026-05-06 07:18:00'),
(209, 'GTA-36246', '1', '170,169,172,168,171', 170, 1, 1, 1, NULL, 0, '2026-05-11 09:19:00', 0, '2026-05-21 17:39:00'),
(210, 'GTA-36246', '1', '170,169,172,168,171', 169, 1, 1, 1, NULL, 0, '2026-05-11 09:19:00', 0, '2026-05-21 17:39:00'),
(211, 'GTA-36246', '1', '170,169,172,168,171', 172, 1, 1, 1, NULL, 0, '2026-05-11 09:19:00', 0, '2026-05-21 17:39:00'),
(212, 'GTA-36246', '1', '170,169,172,168,171', 168, 1, 1, 1, NULL, 0, '2026-05-11 09:19:00', 0, '2026-05-21 17:39:00'),
(213, 'GTA-36246', '1', '170,169,172,168,171', 171, 1, 1, 1, NULL, 0, '2026-05-11 09:19:00', 0, '2026-05-21 17:39:00'),
(214, 'GTA-36536', '3', '166,167', 166, 12, 2, 1, NULL, 0, '2026-05-21 12:49:00', 0, '2026-05-26 11:57:00'),
(215, 'GTA-36536', '3', '166,167', 167, 12, 2, 1, NULL, 0, '2026-05-21 12:49:00', 0, '2026-05-26 11:57:00'),
(216, 'GTA-36536', '3', '164,165', 164, 10, 2, 1, NULL, 0, '2026-05-21 12:49:00', 0, '2026-05-26 11:57:00'),
(217, 'GTA-36536', '3', '164,165', 165, 10, 2, 1, NULL, 0, '2026-05-21 12:49:00', 0, '2026-05-26 11:57:00'),
(218, 'GTA-36537', '2', '173', 173, 8, 2, 1, NULL, 0, '2026-05-21 13:13:00', 0, '2026-05-21 20:52:00'),
(219, 'GTA-36537', '2', '174', 174, 7, 2, 1, NULL, 0, '2026-05-21 13:13:00', 0, '2026-05-21 20:52:00'),
(220, 'GTA-36543', '1', '20', 20, 3, 1, 1, NULL, 0, '2026-05-21 17:28:00', 0, '2026-05-25 11:43:00'),
(221, 'GTA-36570', '1', '175,176', 175, 1, 1, 1, NULL, 0, '2026-05-22 09:11:00', 0, '2026-05-25 05:23:00'),
(222, 'GTA-36570', '5', '175,176', 176, 2, 1, 1, NULL, 0, '2026-05-22 09:11:00', 0, '2026-05-25 05:23:00'),
(223, 'GTA-36535', '2', '177', 177, 20, 4, 1, NULL, 0, '2026-05-21 12:45:00', 0, '2026-05-25 20:16:00'),
(224, 'GTA-36535', '2', '178', 178, 17, 4, 1, NULL, 0, '2026-05-21 12:45:00', 0, '2026-05-25 20:16:00'),
(225, 'GTA-36586', '1', '180,183,179,182,181', 180, 6, 1, 1, NULL, 0, '2026-05-22 17:33:00', 0, '2026-06-01 11:01:00'),
(226, 'GTA-36586', '1', '180,183,179,182,181', 183, 6, 1, 1, NULL, 0, '2026-05-22 17:33:00', 0, '2026-06-01 11:01:00'),
(227, 'GTA-36586', '1', '180,183,179,182,181', 179, 6, 1, 1, NULL, 0, '2026-05-22 17:33:00', 0, '2026-06-01 11:01:00'),
(228, 'GTA-36586', '1', '180,183,179,182,181', 182, 6, 1, 1, NULL, 0, '2026-05-22 17:33:00', 0, '2026-06-01 11:01:00'),
(229, 'GTA-36586', '1', '180,183,179,182,181', 181, 6, 1, 1, NULL, 0, '2026-05-22 17:33:00', 0, '2026-06-01 11:01:00'),
(230, 'GTA-36603', '1', '184,185,186', 184, 14, 3, 1, NULL, 0, '2026-05-25 09:46:00', 0, '2026-06-02 14:22:00'),
(231, 'GTA-36603', '1', '184,185,186', 185, 15, 3, 1, NULL, 0, '2026-05-25 09:46:00', 0, '2026-06-02 14:22:00'),
(232, 'GTA-36603', '1', '184,185,186', 186, 15, 3, 1, NULL, 0, '2026-05-25 09:46:00', 0, '2026-06-02 14:22:00'),
(233, 'GTA-36611', '1', '187,189,188', 187, 44, 9, 1, NULL, 0, '2026-05-25 10:10:00', 0, '2026-06-02 14:26:00'),
(234, 'GTA-36611', '1', '187,189,188', 189, 45, 9, 1, NULL, 0, '2026-05-25 10:10:00', 0, '2026-06-02 14:26:00'),
(235, 'GTA-36611', '1', '187,189,188', 188, 45, 9, 1, NULL, 0, '2026-05-25 10:10:00', 0, '2026-06-02 14:26:00'),
(236, 'GTA-36615', '1', '194,197,190,193,196,192,195,198,175,191', 194, 1, 1, 1, NULL, 0, '2026-05-25 10:19:00', 0, '2026-06-02 13:49:00'),
(237, 'GTA-36615', '1', '194,197,190,193,196,192,195,198,175,191', 197, 1, 1, 1, NULL, 0, '2026-05-25 10:19:00', 0, '2026-06-02 13:49:00'),
(238, 'GTA-36615', '1', '194,197,190,193,196,192,195,198,175,191', 190, 1, 1, 1, NULL, 0, '2026-05-25 10:19:00', 0, '2026-06-02 13:49:00'),
(239, 'GTA-36615', '1', '194,197,190,193,196,192,195,198,175,191', 193, 1, 1, 1, NULL, 0, '2026-05-25 10:19:00', 0, '2026-06-02 13:49:00'),
(240, 'GTA-36615', '1', '194,197,190,193,196,192,195,198,175,191', 196, 1, 1, 1, NULL, 0, '2026-05-25 10:19:00', 0, '2026-06-02 13:49:00'),
(241, 'GTA-36615', '1', '194,197,190,193,196,192,195,198,175,191', 192, 1, 1, 1, NULL, 0, '2026-05-25 10:19:00', 0, '2026-06-02 13:49:00'),
(242, 'GTA-36615', '1', '194,197,190,193,196,192,195,198,175,191', 195, 1, 1, 1, NULL, 0, '2026-05-25 10:19:00', 0, '2026-06-02 13:49:00'),
(243, 'GTA-36615', '1', '194,197,190,193,196,192,195,198,175,191', 198, 1, 1, 1, NULL, 0, '2026-05-25 10:19:00', 0, '2026-06-02 13:49:00'),
(244, 'GTA-36615', '1', '194,197,190,193,196,192,195,198,175,191', 175, 1, 1, 1, NULL, 0, '2026-05-25 10:19:00', 0, '2026-06-02 13:49:00'),
(245, 'GTA-36615', '1', '194,197,190,193,196,192,195,198,175,191', 191, 1, 1, 1, NULL, 0, '2026-05-25 10:19:00', 0, '2026-06-02 13:49:00'),
(246, 'GTA-36625', '1', '205,201,204,200,203,206,202', 205, 4, 1, 1, NULL, 0, '2026-05-25 10:43:00', 0, '2026-05-25 12:29:00'),
(247, 'GTA-36625', '1', '205,201,204,200,203,206,202', 201, 4, 1, 1, NULL, 0, '2026-05-25 10:43:00', 0, '2026-05-25 12:29:00'),
(248, 'GTA-36625', '1', '205,201,204,200,203,206,202', 204, 4, 1, 1, NULL, 0, '2026-05-25 10:43:00', 0, '2026-05-25 12:29:00'),
(249, 'GTA-36625', '1', '205,201,204,200,203,206,202', 200, 4, 1, 1, NULL, 0, '2026-05-25 10:43:00', 0, '2026-05-25 12:29:00'),
(250, 'GTA-36625', '1', '205,201,204,200,203,206,202', 203, 4, 1, 1, NULL, 0, '2026-05-25 10:43:00', 0, '2026-05-25 12:29:00'),
(251, 'GTA-36625', '1', '205,201,204,200,203,206,202', 206, 4, 1, 1, NULL, 0, '2026-05-25 10:43:00', 0, '2026-05-25 12:29:00'),
(252, 'GTA-36625', '1', '205,201,204,200,203,206,202', 202, 4, 1, 1, NULL, 0, '2026-05-25 10:43:00', 0, '2026-05-25 12:29:00'),
(253, 'GTA-36633', '1', '208,211,214,207,210,213,209,212', 208, 6, 1, 1, NULL, 0, '2026-05-25 10:58:00', 0, '2026-05-25 12:42:00'),
(254, 'GTA-36633', '1', '208,211,214,207,210,213,209,212', 211, 6, 1, 1, NULL, 0, '2026-05-25 10:58:00', 0, '2026-05-25 12:42:00'),
(255, 'GTA-36633', '1', '208,211,214,207,210,213,209,212', 214, 6, 1, 1, NULL, 0, '2026-05-25 10:58:00', 0, '2026-05-25 12:42:00'),
(256, 'GTA-36633', '1', '208,211,214,207,210,213,209,212', 207, 6, 1, 1, NULL, 0, '2026-05-25 10:58:00', 0, '2026-05-25 12:42:00'),
(257, 'GTA-36633', '1', '208,211,214,207,210,213,209,212', 210, 6, 1, 1, NULL, 0, '2026-05-25 10:58:00', 0, '2026-05-25 12:42:00'),
(258, 'GTA-36633', '1', '208,211,214,207,210,213,209,212', 213, 6, 1, 1, NULL, 0, '2026-05-25 10:58:00', 0, '2026-05-25 12:42:00'),
(259, 'GTA-36633', '1', '208,211,214,207,210,213,209,212', 209, 6, 1, 1, NULL, 0, '2026-05-25 10:58:00', 0, '2026-05-25 12:42:00'),
(260, 'GTA-36633', '1', '208,211,214,207,210,213,209,212', 212, 6, 1, 1, NULL, 0, '2026-05-25 10:58:00', 0, '2026-05-25 12:42:00'),
(261, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 221, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(262, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 224, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(263, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 227, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(264, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 217, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(265, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 230, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(266, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 220, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(267, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 223, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(268, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 226, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(269, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 216, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(270, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 229, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(271, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 219, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(272, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 222, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(273, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 225, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(274, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 228, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(275, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 215, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(276, 'GTA-36642', '1', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', 218, 7, 2, 1, NULL, 0, '2026-05-25 11:09:00', 0, '2026-06-02 13:59:00'),
(277, 'GTA-36659', '1', '199', 199, 3, 1, 1, NULL, 0, '2026-05-25 11:25:00', 0, '2026-05-25 13:33:00'),
(278, 'GTA-36536', '3', '174', 174, 7, 2, 1, NULL, 0, '2026-05-21 12:49:00', 0, '2026-05-26 11:57:00'),
(279, 'GTA-36536', '3', '173', 173, 8, 2, 1, NULL, 0, '2026-05-21 12:49:00', 0, '2026-05-26 11:57:00'),
(280, 'GTA-36676', '1', '16', 16, 39, 8, 1, NULL, 0, '2026-05-25 13:25:00', 0, '2026-06-03 15:36:00'),
(281, 'GTA-36681', '5', '158,159,161,160,162,163', 158, 1, 1, 1, NULL, 0, '2026-05-25 16:05:00', 0, '2026-06-02 12:34:00'),
(282, 'GTA-36681', '5', '158,159,161,160,162,163', 159, 1, 1, 1, NULL, 0, '2026-05-25 16:05:00', 0, '2026-06-02 12:34:00'),
(283, 'GTA-36681', '5', '158,159,161,160,162,163', 161, 2, 1, 1, NULL, 0, '2026-05-25 16:05:00', 0, '2026-06-02 12:34:00'),
(284, 'GTA-36681', '5', '158,159,161,160,162,163', 160, 2, 1, 1, NULL, 0, '2026-05-25 16:05:00', 0, '2026-06-02 12:34:00'),
(285, 'GTA-36681', '5', '158,159,161,160,162,163', 162, 4, 1, 1, NULL, 0, '2026-05-25 16:05:00', 0, '2026-06-02 12:34:00'),
(286, 'GTA-36681', '5', '158,159,161,160,162,163', 163, 6, 1, 1, NULL, 0, '2026-05-25 16:05:00', 0, '2026-06-02 12:34:00'),
(287, 'GTA-36845', '2', '252', 252, 16, 3, 1, NULL, 1, '2026-06-01 11:59:00', 0, '2026-06-02 10:42:00'),
(288, 'GTA-36845', '2', '253', 253, 13, 3, 1, NULL, 1, '2026-06-01 11:59:00', 0, '2026-06-02 10:42:00'),
(289, 'GTA-36852', '5', '240,241,249,250', 240, 13, 3, 1, NULL, 0, '2026-06-01 13:44:00', 0, '2026-06-03 07:24:00'),
(290, 'GTA-36852', '5', '240,241,249,250', 241, 13, 3, 1, NULL, 0, '2026-06-01 13:44:00', 0, '2026-06-03 07:24:00'),
(291, 'GTA-36852', '5', '240,241,249,250', 249, 16, 3, 1, NULL, 0, '2026-06-01 13:44:00', 0, '2026-06-03 07:24:00'),
(292, 'GTA-36852', '5', '240,241,249,250', 250, 16, 3, 1, NULL, 0, '2026-06-01 13:44:00', 0, '2026-06-03 07:24:00'),
(293, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 261, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(294, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 264, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(295, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 267, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(296, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 254, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(297, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 257, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(298, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 260, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(299, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 263, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(300, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 266, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(301, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 269, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(302, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 256, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(303, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 259, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(304, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 262, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(305, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 265, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(306, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 268, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(307, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 255, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(308, 'GTA-36882', '1', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', 258, 10, 2, 1, NULL, 0, '2026-06-02 11:48:00', 0, '2026-06-03 07:47:00'),
(309, 'GTA-36912', '5', '149,148', 149, 32, 6, 1, NULL, 0, '2026-06-02 15:54:00', 0, '2026-06-03 18:11:00'),
(310, 'GTA-36912', '5', '149,148', 148, 34, 6, 1, NULL, 0, '2026-06-02 15:54:00', 0, '2026-06-03 18:11:00'),
(311, 'GTA-34577', '3', '253', 253, 13, 3, 1, NULL, 1, '2026-03-25 08:45:00', 0, '2026-06-03 07:42:00'),
(312, 'GTA-34577', '3', '252', 252, 16, 3, 1, NULL, 1, '2026-03-25 08:45:00', 0, '2026-06-03 07:42:00'),
(313, 'GTA-36956', '1', '326,329,325,328,331,324,327,330', 326, 12, 2, 1, NULL, 1, '2026-06-04 13:55:42', 0, '2026-06-04 13:55:42'),
(314, 'GTA-36956', '1', '326,329,325,328,331,324,327,330', 329, 12, 2, 1, NULL, 1, '2026-06-04 13:55:43', 0, '2026-06-04 13:55:43'),
(315, 'GTA-36956', '1', '326,329,325,328,331,324,327,330', 325, 12, 2, 1, NULL, 1, '2026-06-04 13:55:43', 0, '2026-06-04 13:55:43'),
(316, 'GTA-36956', '1', '326,329,325,328,331,324,327,330', 328, 12, 2, 1, NULL, 1, '2026-06-04 13:55:43', 0, '2026-06-04 13:55:43'),
(317, 'GTA-36956', '1', '326,329,325,328,331,324,327,330', 331, 12, 2, 1, NULL, 1, '2026-06-04 13:55:43', 0, '2026-06-04 13:55:43'),
(318, 'GTA-36956', '1', '326,329,325,328,331,324,327,330', 324, 12, 2, 1, NULL, 1, '2026-06-04 13:55:43', 0, '2026-06-04 13:55:43'),
(319, 'GTA-36956', '1', '326,329,325,328,331,324,327,330', 327, 12, 2, 1, NULL, 1, '2026-06-04 13:55:43', 0, '2026-06-04 13:55:43'),
(320, 'GTA-36956', '1', '326,329,325,328,331,324,327,330', 330, 12, 2, 1, NULL, 1, '2026-06-04 13:55:43', 0, '2026-06-04 13:55:43'),
(321, 'GTA-36875', '2', '479,480', 479, 34, 6, 1, NULL, 1, '2026-06-04 14:16:12', 0, '2026-06-04 14:16:12'),
(322, 'GTA-36875', '2', '479,480', 480, 34, 6, 1, NULL, 1, '2026-06-04 14:16:12', 0, '2026-06-04 14:16:12'),
(323, 'GTA-36875', '2', '481,482', 481, 32, 6, 1, NULL, 1, '2026-06-04 14:17:08', 0, '2026-06-04 14:17:08'),
(324, 'GTA-36875', '2', '481,482', 482, 32, 6, 1, NULL, 1, '2026-06-04 14:17:08', 0, '2026-06-04 14:17:08'),
(325, 'GTA-36973', '1', '335,322,334,321,333,320,323,332', 335, 12, 2, 1, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-04 16:32:49'),
(326, 'GTA-36973', '1', '335,322,334,321,333,320,323,332', 322, 12, 2, 1, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-04 16:32:49'),
(327, 'GTA-36973', '1', '335,322,334,321,333,320,323,332', 334, 12, 2, 1, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-04 16:32:49'),
(328, 'GTA-36973', '1', '335,322,334,321,333,320,323,332', 321, 12, 2, 1, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-04 16:32:49'),
(329, 'GTA-36973', '1', '335,322,334,321,333,320,323,332', 333, 12, 2, 1, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-04 16:32:49'),
(330, 'GTA-36973', '1', '335,322,334,321,333,320,323,332', 320, 12, 2, 1, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-04 16:32:49'),
(331, 'GTA-36973', '1', '335,322,334,321,333,320,323,332', 323, 12, 2, 1, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-04 16:32:49'),
(332, 'GTA-36973', '1', '335,322,334,321,333,320,323,332', 332, 12, 2, 1, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-04 16:32:49'),
(333, 'GTA-36990', '1', '421,422,430,429', 421, 33, 6, 1, NULL, 1, '2026-06-04 16:50:22', 0, '2026-06-04 16:50:22'),
(334, 'GTA-36990', '1', '421,422,430,429', 422, 33, 6, 1, NULL, 1, '2026-06-04 16:50:22', 0, '2026-06-04 16:50:22'),
(335, 'GTA-36990', '1', '421,422,430,429', 430, 34, 6, 1, NULL, 1, '2026-06-04 16:50:22', 0, '2026-06-04 16:50:22'),
(336, 'GTA-36990', '1', '421,422,430,429', 429, 34, 6, 1, NULL, 1, '2026-06-04 16:50:22', 0, '2026-06-04 16:50:22'),
(337, 'GTA-36968', '3', '479,480', 479, 34, 6, 1, NULL, 1, '2026-06-05 10:42:00', 0, '2026-06-05 10:42:00'),
(338, 'GTA-36968', '3', '479,480', 480, 34, 6, 1, NULL, 1, '2026-06-05 10:42:00', 0, '2026-06-05 10:42:00'),
(339, 'GTA-36968', '3', '481,482', 481, 32, 6, 1, NULL, 1, '2026-06-05 10:43:29', 0, '2026-06-05 10:43:29'),
(340, 'GTA-36968', '3', '481,482', 482, 32, 6, 1, NULL, 1, '2026-06-05 10:43:29', 0, '2026-06-05 10:43:29'),
(341, 'GTA-36877', '2', '483,484', 483, 30, 6, 1, NULL, 1, '2026-06-05 13:06:58', 0, '2026-06-05 13:06:58'),
(342, 'GTA-36877', '2', '483,484', 484, 30, 6, 1, NULL, 1, '2026-06-05 13:06:58', 0, '2026-06-05 13:06:58'),
(343, 'GTA-36877', '2', '485,486', 485, 22, 6, 1, NULL, 1, '2026-06-05 13:57:14', 0, '2026-06-05 13:57:14'),
(344, 'GTA-36877', '2', '485,486', 486, 22, 6, 1, NULL, 1, '2026-06-05 13:57:14', 0, '2026-06-05 13:57:14'),
(345, 'GTA-36995', '3', '483,484', 483, 30, 6, 1, NULL, 1, '2026-06-05 13:57:26', 0, '2026-06-05 13:57:26'),
(346, 'GTA-36995', '3', '483,484', 484, 30, 6, 1, NULL, 1, '2026-06-05 13:57:26', 0, '2026-06-05 13:57:26'),
(347, 'GTA-36995', '3', '485,486', 485, 22, 6, 1, NULL, 1, '2026-06-05 13:57:41', 0, '2026-06-05 13:57:41'),
(348, 'GTA-36995', '3', '485,486', 486, 22, 6, 1, NULL, 1, '2026-06-05 13:57:41', 0, '2026-06-05 13:57:41'),
(349, 'GTA-36876', '2', '487,488', 487, 25, 6, 1, NULL, 1, '2026-06-05 15:12:07', 0, '2026-06-05 15:12:07'),
(350, 'GTA-36876', '2', '487,488', 488, 25, 6, 1, NULL, 1, '2026-06-05 15:12:07', 0, '2026-06-05 15:12:07'),
(351, 'GTA-37031', '3', '487,488', 487, 25, 6, 1, NULL, 1, '2026-06-05 15:12:20', 0, '2026-06-05 15:12:20'),
(352, 'GTA-37031', '3', '487,488', 488, 25, 6, 1, NULL, 1, '2026-06-05 15:12:20', 0, '2026-06-05 15:12:20'),
(353, 'GTA-36876', '2', '489,490', 489, 26, 6, 1, NULL, 1, '2026-06-05 16:08:41', 0, '2026-06-05 16:08:41'),
(354, 'GTA-36876', '2', '489,490', 490, 26, 6, 1, NULL, 1, '2026-06-05 16:08:41', 0, '2026-06-05 16:08:41'),
(355, 'GTA-37031', '3', '489,490', 489, 26, 6, 1, NULL, 1, '2026-06-05 16:08:53', 0, '2026-06-05 16:08:53'),
(356, 'GTA-37031', '3', '489,490', 490, 26, 6, 1, NULL, 1, '2026-06-05 16:08:53', 0, '2026-06-05 16:08:53');

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
(3, 'Dentsu', 0, '2026-05-06 13:16:06', 0, '2026-05-06 13:16:06', NULL, NULL),
(4, 'HQ', 0, '2026-05-15 11:22:28', 0, '2026-05-15 11:22:28', NULL, NULL),
(5, 'Publicis Groupe', 0, '2026-05-15 12:32:06', 0, '2026-05-15 12:32:06', NULL, NULL);

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
(4, 'ZS_Hybris_AE_AR-UAE (Shop Section) WebSDK', 1, 0, 1, 1, 1, 2, 'https://shop.samsung.com/ae_ar', 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/overview', 0, NULL, 0, NULL, 0, NULL),
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
(22, 'zP6_IQ_AR-Iraq WebSDK', 1, 0, 0, 6, 12, 2, 'https://www.samsung.com/iq_ar', 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4507cc44d1e742498bc65fc391f1738f/rules', 0, '2026-04-02 11:10:38', 0, '2026-04-02 11:10:38', 0, NULL),
(23, 'zP6_IQ_KU-Kurdistan WebSDK', 1, 0, 0, 6, 13, 7, 'https://www.samsung.com/iq_ku', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR46f04dd062074bb2a482e2b7e9b132fe/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:12:46', 0, '2026-04-02 11:12:46', 0, NULL),
(24, 'zP6_LB-Lebanon WebSDK', 1, 0, 0, 6, 14, 8, 'https://www.samsung.com/lb', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRab3523e662044e63973ec19de6e82e6d/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:20:40', 0, '2026-04-02 11:20:40', 0, NULL),
(25, 'ZP6_LEVANT_AR-Levant(SELV) WebSDK', 1, 0, 0, 6, 10, 2, 'https://www.samsung.com/levant_ar', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:21:16', 0, '2026-04-02 11:21:16', 0, NULL),
(26, 'ZP6_LEVANT-Levant(SELV) WebSDK', 1, 0, 0, 6, 10, 1, 'https://www.samsung.com/levant', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR08b2de963cc24f5cb79e01f819fd90c0/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:21:48', 0, '2026-04-02 11:21:48', 0, NULL),
(27, 'zP6zEPP_IQ_AR-Iraq(AA) WebSDK', 1, 1, 0, 6, 12, 2, 'https://www.samsung.com/iq_ar/multistore/iq_samsung_partner/', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR970ae49668d54b5bbc55d74e54d01f88/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:22:17', 0, '2026-04-02 11:22:17', 0, NULL),
(28, 'zP6zEPP_JO-Jordan(AA) WebSDK', 1, 1, 0, 6, 11, 1, 'https://www.samsung.com/levant/multistore/jo_samsung_employee/', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR64613b01294b47b58da5e58992960773/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:22:44', 0, '2026-04-02 11:22:44', 0, NULL),
(29, 'ZS_Hybris_IQ_AR-Iraq EPP (Shop Section) WebSDK', 1, 1, 1, 6, 12, 2, 'https://shop.samsung.com/iq_ar/multistore/iqepp/iq_samsung_partner/auth/multistore', 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7afe3c131c6d4beb8bd2cf65fcd5d5bb/rules', 0, '2026-04-02 11:23:07', 0, '2026-04-02 11:23:07', 0, NULL),
(30, 'ZS_Hybris_IQ_AR-Iraq (Shop Section) WebSDK', 1, 0, 1, 6, 12, 2, 'https://shop.samsung.com/iq_ar', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules', 0, '2026-04-02 11:23:31', 0, '2026-04-02 11:23:31', 0, NULL),
(31, 'ZS_Hybris_IQ_KU-Kurdistan (Shop Section) WebSDK', 1, 0, 1, 6, 12, 7, 'https://shop.samsung.com/iq_ku', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRb2f83dfd81be410abe16709dbf56b08c/rules', 0, '2026-04-02 11:24:09', 0, '2026-04-02 11:24:09', 0, NULL),
(32, 'ZS_Hybris_JO_AR-Jordan (Shop Section) WebSDK', 1, 0, 1, 6, 11, 2, 'https://shop.samsung.com/jo_ar', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf28c4faf02284603896219ef6cb42fa2/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:24:32', 0, '2026-04-02 11:24:32', 0, NULL),
(33, 'ZS_Hybris_JO-Jordan EPP (Shop Section) WebSDK', 1, 1, 1, 6, 11, 1, 'https://shop.samsung.com/jo/multistore/joepp/jo_samsung_government/auth/multistore', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR2fe2ecc0f2664d599cbb5e58edacbdff/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:25:05', 0, '2026-04-02 11:25:05', 0, NULL),
(34, 'ZS_Hybris_JO-Jordan (Shop Section) WebSDK', 1, 0, 1, 6, 11, 1, 'https://shop.samsung.com/jo', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:25:34', 0, '2026-04-02 11:25:34', 0, NULL),
(35, 'ZP6_EG-Egypt WebSDK', 1, 0, 0, 7, 15, 9, 'https://www.samsung.com/eg', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc4ea06001baa496a85af80306c73dbb6/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:26:52', 0, '2026-04-02 11:26:52', 0, NULL),
(36, 'zP6zEPP_EG-Egypt(AA) WebSDK', 1, 1, 0, 7, 15, 9, 'https://www.samsung.com/eg/multistore/eg_nbe_employees', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR829b8f02ee5d4a5dbc9e816e94fb4b28/rules?filter%5Benabled%5D=EQ+true&filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:27:30', 0, '2026-04-02 11:27:30', 0, NULL),
(37, 'ZS_Hybris_EG-Egypt EPP (Shop Section) WebSDK', 1, 1, 1, 7, 15, 9, 'https://shop.samsung.com/eg/multistore/egepp/eg_nbe_employees/cart', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR975ae08dc0d54ef8988861815a84d11f/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:27:52', 0, '2026-04-02 11:27:52', 0, NULL),
(38, 'ZS_Hybris_EG-Egypt (Shop Section) WebSDK', 1, 0, 1, 7, 15, 9, 'https://shop.samsung.com/eg', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR72c3c4d394474dd0aaf703dba19ff998/rules', 0, '2026-04-02 11:28:17', 0, '2026-04-02 11:28:17', 0, NULL),
(39, 'ZP6_N_AFRICA-Africa WebSDK', 1, 0, 0, 8, 16, 1, 'https://www.samsung.com/n_africa', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRaf00914f9c194739b6e98de4b0f72380/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:28:44', 0, '2026-04-02 11:28:44', 0, NULL),
(40, 'zP6zEPP_N_AFRICA-Africa(AA) WebSDK', 1, 1, 0, 8, 16, 1, 'https://www.samsung.com/n_africa/multistore/ma_corporates', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR87ddb0fd7d0e4611ab2557083595ce20/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:30:28', 0, '2026-04-02 11:30:28', 0, NULL),
(41, 'ZS_Hybris_MA-Morocco EPP (Shop Section) WebSDK', 1, 1, 1, 8, 17, 1, 'https://shop.samsung.com/ma/multistore/maepp/ma_students/auth/multistore', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR1f72db8a7d5c49a287d913b941391d80/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:30:55', 0, '2026-04-02 11:30:55', 0, NULL),
(42, 'ZS_Hybris_MA-Morocco (Shop Section) WebSDK', 1, 0, 1, 8, 17, 1, 'https://shop.samsung.com/ma', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61cef25ff4eb400391e974558ec4c577/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:31:18', 0, '2026-04-02 11:31:18', 0, NULL),
(43, 'ZP6_PK-Pakistan WebSDK', 1, 0, 0, 9, 18, 1, 'https://www.samsung.com/pk', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4a4d53f5c57b4611a653d094fa1c523c/rules', 0, '2026-04-02 11:31:46', 0, '2026-04-02 11:31:46', 0, NULL),
(44, 'zP6zEPP_PK-Pakistan(AA) WebSDK', 1, 1, 0, 9, 1, 1, 'https://samsung.com/pk/multistore/pk_corporate/', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR14a762a6a33c4d608665a7d0e67d7e7a/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:32:07', 0, '2026-04-02 11:32:07', 0, NULL),
(45, 'ZS_Hybris_PK-Pakistan EPP (Shop Section) WebSDK', 1, 1, 1, 9, 18, 1, 'https://shop.samsung.com/pk/multistore/pkepp/pk_corporate/registration', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc0d1939c6cc84ef3be7b0ad0c5df94d4/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:32:33', 0, '2026-04-02 11:32:33', 0, NULL),
(46, 'ZS_Hybris_PK-Pakistan (Shop Section) WebSDK', 1, 0, 1, 9, 18, 1, 'https://shop.samsung.com/pk', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRfc76c7fe018d41e09006f4c3c0925588/rules?filter%5Bname%5D=CONTAINS+lc-&page%5Bnumber%5D=1', 0, '2026-04-02 11:32:54', 0, '2026-04-02 11:32:54', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `renewal_justification`
--

CREATE TABLE `renewal_justification` (
  `id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `renew` char(4) DEFAULT NULL,
  `justification` varchar(150) DEFAULT NULL,
  `duration` varchar(150) DEFAULT NULL,
  `created_by` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `renewal_logs`
--

INSERT INTO `renewal_logs` (`id`, `ticket_id`, `property_id`, `library_name`, `vendor`, `al_end_date`, `user`, `updated_at`, `created_at`) VALUES
(1, 'GTA-36321', 42, 'GTA-36321_20052026', '18,17', '2026-07-24', 0, '2026-05-20 09:00:40', '2026-05-20 09:00:40'),
(2, 'GTA-36321', 39, 'GTA-36321_20052026', '15,16', '2026-07-24', 0, '2026-05-20 09:01:25', '2026-05-20 09:01:25'),
(3, 'GTA-36314', 17, 'GTA-36314_20052026', '19', '2026-07-25', 0, '2026-05-20 09:02:02', '2026-05-20 09:02:02'),
(4, 'GTA-36314', 17, 'GTA-36314_20052026', '1', '2026-07-23', 0, '2026-05-20 09:02:30', '2026-05-20 09:02:30'),
(5, 'GTA-36246', 1, 'GTA-36246_20052026', '170,169,172,168,171', '2026-07-23', 0, '2026-05-20 10:07:30', '2026-05-20 10:07:30'),
(6, 'GTA-36543', 3, 'GTA-36543_21052026', '20', '2026-08-01', 0, '2026-05-21 17:36:44', '2026-05-21 17:36:44'),
(7, 'GTA-36586', 6, 'GTA-36586_22052026', '180,183,179,182,181', '2026-08-03', 0, '2026-05-22 17:45:28', '2026-05-22 17:45:28'),
(8, 'GTA-36603', 14, 'GTA-36603_25052026', '184,185,186', '2026-08-03', 0, '2026-05-25 09:56:17', '2026-05-25 09:56:17'),
(9, 'GTA-36603', 15, 'GTA-36603_25052026', '184,185,186', '2026-08-03', 0, '2026-05-25 09:56:17', '2026-05-25 09:56:17'),
(10, 'GTA-36611', 44, 'GTA-36611_25052026', '187,189,188', '2026-08-03', 0, '2026-05-25 10:15:47', '2026-05-25 10:15:47'),
(11, 'GTA-36611', 45, 'GTA-36611_25052026', '187,189,188', '2026-08-03', 0, '2026-05-25 10:15:47', '2026-05-25 10:15:47'),
(12, 'GTA-36615', 1, 'GTA-36615_25052026', '194,197,190,193,196,192,195,198,175,191', '2026-08-06', 0, '2026-05-25 10:38:45', '2026-05-25 10:38:45'),
(13, 'GTA-36625', 4, 'GTA-36625_25052026', '205,201,204,200,203,206,202', '2026-08-09', 0, '2026-05-25 10:52:42', '2026-05-25 10:52:42'),
(14, 'GTA-36633', 6, 'GTA-36633_25052026', '208,211,214,207,210,213,209,212', '2026-08-09', 0, '2026-05-25 11:05:41', '2026-05-25 11:05:41'),
(15, 'GTA-36642', 7, 'GTA-36642_25052026', '221,224,227,217,230,220,223,226,216,229,219,222,225,228,215,218', '2026-08-06', 0, '2026-05-25 11:23:41', '2026-05-25 11:23:41'),
(16, 'GTA-36659', 3, 'GTA-36659_25052026', '199', '2026-08-09', 0, '2026-05-25 11:28:14', '2026-05-25 11:28:14'),
(17, 'GTA-36882', 10, 'GTA-36882_02062026', '261,264,267,254,257,260,263,266,269,256,259,262,265,268,255,258', '2026-08-12', 0, '2026-06-02 12:23:23', '2026-06-02 12:23:23'),
(18, 'GTA-36956', 12, 'GTA-36956_04062026', '326,329,325,328,331,324,327,330', '2026-08-13', 0, '2026-06-04 13:55:43', '2026-06-04 13:55:43'),
(19, 'GTA-36973', 12, 'GTA-36973_04062026', '335,322,334,321,333,320,323,332', '2026-08-13', 0, '2026-06-04 16:32:49', '2026-06-04 16:32:49'),
(20, 'GTA-36990', 33, 'GTA-36990_04062026', '421,422,430,429', '2026-08-13', 0, '2026-06-04 16:50:22', '2026-06-04 16:50:22'),
(21, 'GTA-36990', 34, 'GTA-36990_04062026', '421,422,430,429', '2026-08-13', 0, '2026-06-04 16:50:22', '2026-06-04 16:50:22');

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
(1, 'LC-AEM-AD(Limy)-CM-ALL(WL)-SDSME-9802-48598677a5ff', 17, 1, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7defa9d2597c4d32803af3ed6c46f659/rules/RL74c03f7bb7484572bfef01d35b5b6c0d', 'Shaked Bibi', 'shaked.b@partner.samsung.com', 1, NULL, NULL, 0, '2026-05-20 09:31:05', 0, '2026-05-04 12:56:24', 0, NULL),
(2, 'LC-AEM-AD(Optimise)-CM-WL-SDSME-908b-732c0c7d27de', 1, 2, NULL, 1, 1, '2026-06-24', NULL, 'https://www.samsung.com/', '', '', NULL, NULL, NULL, 0, '2026-05-05 13:18:58', 0, '2026-05-05 13:14:20', 0, NULL),
(3, 'LC-AEM-AD(Optimise)-CM-WL-SDSME-908b-732c0c7d27de', 2, 2, NULL, 1, 1, '2026-06-24', NULL, 'https://www.samsung.com/', '', '', NULL, NULL, NULL, 0, '2026-05-05 13:19:04', 0, '2026-05-05 13:15:19', 0, NULL),
(4, 'LC-Shop-AD(Optimise)-CM-WL-SDSME-908b-732c0c7d27de', 4, 2, NULL, 1, 1, '2026-06-24', NULL, 'https://www.samsung.com/', '', '', NULL, NULL, NULL, 0, '2026-05-05 13:19:12', 0, '2026-05-05 13:16:18', 0, NULL),
(5, 'LC-Shop-AD(Optimise)-OrderConfirmation-WL-SDSME-908b-732c0c7d27de', 4, 2, '^shop order confirmation$', 1, 1, '2026-06-24', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RL9e505167dc7a489cbf310013db56e368', '', '', 0, NULL, NULL, 0, '2026-05-21 14:47:03', 0, '2026-05-05 13:16:18', 0, NULL),
(6, 'LC-Shop-AD(Optimise)-CM-WL-SDSME-908b-732c0c7d27de', 6, 2, NULL, 1, 1, '2026-06-24', NULL, 'https://www.samsung.com/', '', '', NULL, NULL, NULL, 0, '2026-05-05 13:19:18', 0, '2026-05-05 13:17:14', 0, NULL),
(7, 'LC-Shop-AD(Optimise)-OrderConfirmation-WL-SDSME-908b-732c0c7d27de', 6, 2, NULL, 1, 1, '2026-06-24', NULL, 'https://www.samsung.com/', '', '', NULL, NULL, NULL, 0, '2026-05-05 13:19:18', 0, '2026-05-05 13:17:14', 0, NULL),
(8, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_9c9a-76ee9eb9debc', 25, 3, NULL, 1, 1, '2026-06-27', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-05 13:26:28', 0, '2026-05-05 13:25:10', 0, NULL),
(9, 'LC-Shop-AD(Contentsquare)-CM-WL-SDSME_9aaa-619dc6194dcf', 34, 3, NULL, 1, 1, '2026-06-27', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-05 13:31:57', 0, '2026-05-05 13:27:55', 0, NULL),
(10, 'LC-Shop-AD(Contentsquare)-CM-WL-SDSME_9c9a-76ee9eb9debc', 32, 3, NULL, 1, 1, '2026-06-27', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-05 13:38:44', 0, '2026-05-05 13:28:32', 0, NULL),
(11, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_989f-31ab99742fca', 22, 3, NULL, 1, 1, '2026-07-03', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-05 13:41:16', 0, '2026-05-05 13:39:29', 0, NULL),
(12, 'LC-Shop-AD(Contentsquare)-CM-WL-SDSME_989f-31ab99742fca', 30, 3, NULL, 1, 1, '2026-07-03', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-05 13:41:33', 0, '2026-05-05 13:40:17', 0, NULL),
(13, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_917e-717e3602620f', 23, 3, 'home|shop home|microsite|offer main|offer detail|search result|ai\\-search|product category detail|shop category|product finder|business product finder|product detail|flagship pdp|shop detail|business ', 1, 1, '2026-07-03', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR46f04dd062074bb2a482e2b7e9b132fe/rules/RL704b2f840e9f4933baf51078dc0c7c4c', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-06 13:01:28', 0, '2026-05-06 12:53:20', 0, NULL),
(14, 'LC-Shop-AD(Contentsquare)-CM-WL-SDSME_917e-717e3602620f', 31, 3, NULL, 1, 1, '2026-07-03', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-06 13:01:39', 0, '2026-05-06 12:54:01', 0, NULL),
(15, 'LC-AEM-AD(RTBHouse)-Cart-ATC-SDSME_b2e4-3228e7ef555e', 39, 4, 'div.hubble-price-bar__price-cta > a,div.hubble-product__total-cta > div > div > a, [data-cta-type=', 1, 1, '2026-07-24', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRaf00914f9c194739b6e98de4b0f72380/rules/RL92a4b06eef05440c9024482c6deb0f83', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 2, NULL, NULL, 0, '2026-05-25 13:22:56', 0, '2026-05-06 13:04:28', 0, NULL),
(16, 'LC-AEM-AD(RTBHouse)-ViewContent-WL-SDSME_b2e4-3228e7ef555e', 39, 4, '^product detail$|^flagship pdp$', 1, 1, '2026-07-24', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRaf00914f9c194739b6e98de4b0f72380/rules/RLf82ec374e11c4ca29c93bbfc7115b51b', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 2, NULL, NULL, 0, '2026-05-25 13:23:17', 0, '2026-05-06 13:04:28', 0, NULL),
(17, 'LC-Shop-AD(RTBHouse)-Cart-WL-SDSME_b2e4-3228e7ef555e', 42, 4, 'shop cart', 1, 1, '2026-07-24', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61cef25ff4eb400391e974558ec4c577/rules/RL715c19abb1c14d05926cc4bb939e5845', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 2, NULL, NULL, 0, '2026-05-20 09:00:53', 0, '2026-05-06 13:05:42', 0, NULL),
(18, 'LC-Shop-AD(RTBHouse)-OrderConfirmation-WL-SDSME_b2e4-3228e7ef555e', 42, 4, '^shop order confirmation$', 1, 1, '2026-07-24', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61cef25ff4eb400391e974558ec4c577/rules/RLfbc3e62607a24fa8b2dffa347880d7bc', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 2, NULL, NULL, 0, '2026-06-02 09:37:57', 0, '2026-05-06 13:05:42', 0, NULL),
(19, 'LC-AEM-AD(Appsflyer)-CM-WL-SDSME_903d-af70610536c8', 17, 5, '(il/.*)', 1, 1, '2026-07-25', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7defa9d2597c4d32803af3ed6c46f659/rules/RLb3ebb2658427430496cdf5ff5f6189f9', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-20 09:32:07', 0, '2026-05-06 13:10:59', 0, NULL),
(20, 'LC-AEM-AD(Firework)-CM-WL-SDSME_oPNw8q', 3, 6, 'isFloatingPlayerDisabled', 1, 1, '2026-08-01', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR66456bebda424fb3af85ef35ee171c71/rules/RL3916af9c63a748be81341d1c608c4658', 'Shereen El Gendy', 'shereen.g@partner.samsung.com', 1, NULL, NULL, 0, '2026-05-25 11:47:28', 0, '2026-05-06 13:13:26', 0, NULL),
(21, 'LC-AEM-AD(Pinterest)-CM-WL-SDSME_2613528410439', 22, 7, NULL, 1, 1, '2026-07-05', NULL, 'https://www.samsung.com/', 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, NULL, NULL, 0, '2026-05-15 10:30:33', 0, '2026-05-06 13:16:06', 0, NULL),
(22, 'LC-Shop-AD(Pinterest)-CM-WL-SDSME_2613528410439', 30, 7, NULL, 1, 1, '2026-07-05', NULL, 'https://www.samsung.com/', 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, NULL, NULL, 0, '2026-05-15 10:37:37', 0, '2026-05-06 13:17:19', 0, NULL),
(23, 'LC-Shop-AD(Pinterest)-CM-WL-SDSME_2613638179718', 34, 7, NULL, 1, 1, '2026-07-06', NULL, 'https://www.samsung.com/', 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, NULL, NULL, 0, '2026-05-15 10:30:49', 0, '2026-05-06 13:18:58', 0, NULL),
(24, 'LC-Shop-AD(Pinterest)-CM-WL-SDSME_2613638179718', 32, 7, NULL, 1, 1, '2026-07-06', NULL, 'https://www.samsung.com/', 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, NULL, NULL, 0, '2026-05-15 10:37:54', 0, '2026-05-06 13:20:26', 0, NULL),
(25, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-8f7a-0b1cc84255a4', 13, 8, NULL, 1, 1, '2026-06-26', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-06 13:33:26', 0, '2026-05-06 13:24:45', 0, NULL),
(26, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-8f7a-0b1cc84255a4', 16, 8, NULL, 1, 1, '2026-06-26', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-06 13:36:17', 0, '2026-05-06 13:34:21', 0, NULL),
(27, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-8517-f38da36bcf66', 7, 8, NULL, 1, 1, '2026-07-03', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:16:12', 0, '2026-05-06 13:41:50', 0, NULL),
(28, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-8517-f38da36bcf66', 10, 8, NULL, 1, 1, '2026-07-03', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:16:16', 0, '2026-05-06 13:42:38', 0, NULL),
(29, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-8517-f38da36bcf66', 8, 8, NULL, 1, 1, '2026-07-03', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:16:19', 0, '2026-05-06 13:43:18', 0, NULL),
(30, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-8517-f38da36bcf66', 12, 8, NULL, 1, 1, '2026-07-03', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:16:27', 0, '2026-05-06 13:43:47', 0, NULL),
(31, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-8ae3-30060725acbc', 17, 8, NULL, 1, 1, '2026-07-05', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:16:29', 0, '2026-05-06 13:46:05', 0, NULL),
(32, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-8ae3-30060725acbc', 20, 8, NULL, 1, 1, '2026-07-05', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:16:32', 0, '2026-05-06 13:46:47', 0, NULL),
(33, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-b2a5-e04c11ee378e', 43, 8, NULL, 1, 1, '2026-07-06', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:16:34', 0, '2026-05-06 13:48:02', 0, NULL),
(34, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-b2a5-e04c11ee378e', 46, 8, NULL, 1, 1, '2026-07-06', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:16:36', 0, '2026-05-06 13:48:34', 0, NULL),
(36, 'LC-AEM-AD(Optimise)-CM-WL-SDSME_2361077-57144', 7, 2, NULL, 1, 1, '2026-07-08', NULL, 'https://www.samsung.com/', 'Toufic Alzaza', 'toufic.alzaza@dentsu.com', 3, NULL, NULL, 0, '2026-05-15 11:17:56', 0, '2026-05-07 12:39:42', 0, NULL),
(37, 'LC-AEM-AD(Optimise)-CM-WL-SDSME_2361077-57144', 8, 2, NULL, 1, 1, '2026-07-08', NULL, 'https://www.samsung.com/', 'Toufic Alzaza', 'toufic.alzaza@dentsu.com', 3, NULL, NULL, 0, '2026-05-15 11:17:53', 0, '2026-05-07 12:42:47', 0, NULL),
(38, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-9350-04f5c29bced5', 35, 8, NULL, 1, 1, '2026-07-09', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:17:25', 0, '2026-05-15 10:48:11', 0, NULL),
(39, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-9350-04f5c29bced5', 38, 8, NULL, 1, 1, '2026-07-09', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:17:35', 0, '2026-05-15 10:49:31', 0, NULL),
(40, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-90ff-f4ebd747ce43', 39, 8, NULL, 1, 1, '2026-07-09', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:17:38', 0, '2026-05-15 10:57:51', 0, NULL),
(41, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-90ff-f4ebd747ce43', 42, 8, NULL, 1, 1, '2026-07-09', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:17:40', 0, '2026-05-15 10:58:24', 0, NULL),
(42, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-9f8b-12f63012c9cd', 24, 8, NULL, 1, 1, '2026-07-09', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:17:42', 0, '2026-05-15 10:59:06', 0, NULL),
(43, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-9f8b-12f63012c9cd', 26, 8, NULL, 1, 1, '2026-07-09', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:17:44', 0, '2026-05-15 10:59:45', 0, NULL),
(44, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-9f8b-12f63012c9cd', 25, 8, NULL, 1, 1, '2026-07-09', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:17:47', 0, '2026-05-15 11:00:11', 0, NULL),
(45, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-9f8b-12f63012c9cd', 34, 8, NULL, 1, 1, '2026-07-09', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:17:50', 0, '2026-05-15 11:01:31', 0, NULL),
(46, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-9f8b-12f63012c9cd', 32, 8, NULL, 1, 1, '2026-07-09', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:18:03', 0, '2026-05-15 11:01:55', 0, NULL),
(47, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-9f8b-12f63012c9cd', 22, 8, NULL, 1, 1, '2026-07-18', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:18:06', 0, '2026-05-15 11:02:49', 0, NULL),
(48, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-9f8b-12f63012c9cd', 30, 8, NULL, 1, 1, '2026-07-18', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:18:09', 0, '2026-05-15 11:03:20', 0, NULL),
(49, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-9f8b-12f63012c9cd', 23, 8, NULL, 1, 1, '2026-07-18', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:18:11', 0, '2026-05-15 11:04:06', 0, NULL),
(50, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-9f8b-12f63012c9cd', 31, 8, NULL, 1, 1, '2026-07-18', NULL, 'https://www.samsung.com/', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:18:15', 0, '2026-05-15 11:04:36', 0, NULL),
(51, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-ac76-4b1c9ab097aa', 1, 8, '(.*)', 1, 1, '2026-06-25', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RL321f5ec6fc7949fd8beb649fcd1b3846', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:12:59', 0, '2026-05-15 11:10:44', 0, NULL),
(52, 'LC-AEM-AD(CJ)-CM-ALL(WL)-SDSME-ac76-4b1c9ab097aa', 2, 8, '(.*)', 1, 1, '2026-06-25', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RLabb4d3e59f424ee5809e76ac31ae971a', 'Mohamed Rafeek', '', 1, NULL, NULL, 0, '2026-05-15 11:11:13', 0, '2026-05-15 11:11:13', 0, NULL),
(53, 'LC-AEM-AD(Meta)-CM-WL-SDSME_1240253317108112', 2, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-15 11:23:59', 0, '2026-05-15 11:22:28', 0, NULL),
(54, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_1240253317108112', 2, 9, '(.*)', 1, 1, '2026-12-01', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RL628d3368da9c45e4a15a5ae2fe1414c9', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-15 11:24:41', 0, '2026-05-15 11:24:41', 0, NULL),
(55, 'LC-Shop-AD(Meta)-AddToCart-WL-SDSME_1240253317108112', 6, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:10', 0, '2026-05-15 11:27:37', 0, NULL),
(56, 'LC-Shop-AD(Meta)-CM-WL-SDSME_1240253317108112', 6, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:10', 0, '2026-05-15 11:27:37', 0, NULL),
(57, 'LC-Shop-AD(Meta)-InitiateCheckout-WL-SDSME_1240253317108112', 6, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:10', 0, '2026-05-15 11:27:37', 0, NULL),
(58, 'LC-Shop-AD(Meta)-OrderConfirmation-WL-SDSME_1240253317108112', 6, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:10', 0, '2026-05-15 11:27:37', 0, NULL),
(59, 'LC-AEM-AD(Meta)-CM-WL-SDSME_1240253317108112', 1, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:29', 0, '2026-05-15 11:28:18', 0, NULL),
(60, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_1240253317108112', 1, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:29', 0, '2026-05-15 11:28:18', 0, NULL),
(61, 'LC-Shop-AD(Meta)-AddToCart-WL-SDSME_1240253317108112', 4, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:32', 0, '2026-05-15 11:28:52', 0, NULL),
(62, 'LC-Shop-AD(Meta)-CM-WL-SDSME_1240253317108112', 4, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:32', 0, '2026-05-15 11:28:52', 0, NULL),
(63, 'LC-Shop-AD(Meta)-InitiateCheckout-WL-SDSME_1240253317108112', 4, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:32', 0, '2026-05-15 11:28:52', 0, NULL),
(64, 'LC-Shop-AD(Meta)-OrderConfirmation-WL-SDSME_1240253317108112', 4, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:32', 0, '2026-05-15 11:28:52', 0, NULL),
(65, 'LC-AEM-AD(Meta)-CM-WL-SDSME_8471933442900811', 13, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:34', 0, '2026-05-15 11:31:12', 0, NULL),
(66, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_8471933442900811', 13, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:34', 0, '2026-05-15 11:31:12', 0, NULL),
(67, 'LC-Shop-AD(Meta)-AddToCart-WL-SDSME_8471933442900811', 16, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:37', 0, '2026-05-15 11:31:49', 0, NULL),
(68, 'LC-Shop-AD(Meta)-CM-WL-SDSME_8471933442900811', 16, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:37', 0, '2026-05-15 11:31:49', 0, NULL),
(69, 'LC-Shop-AD(Meta)-InitiateCheckout-WL-SDSME_8471933442900811', 16, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:37', 0, '2026-05-15 11:31:49', 0, NULL),
(70, 'LC-Shop-AD(Meta)-OrderConfirmation-WL-SDSME_8471933442900811', 16, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:37', 0, '2026-05-15 11:31:49', 0, NULL),
(71, 'LC-AEM-AD(Meta)-CM-WL-SDSME_2611523569055248', 7, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:39', 0, '2026-05-15 11:33:02', 0, NULL),
(72, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_2611523569055248', 7, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:39', 0, '2026-05-15 11:33:02', 0, NULL),
(73, 'LC-Shop-AD(Meta)-AddToCart-WL-SDSME_2611523569055248', 10, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:42', 0, '2026-05-15 11:33:45', 0, NULL),
(74, 'LC-Shop-AD(Meta)-CM-WL-SDSME_2611523569055248', 10, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:42', 0, '2026-05-15 11:33:45', 0, NULL),
(75, 'LC-Shop-AD(Meta)-InitiateCheckout-WL-SDSME_2611523569055248', 10, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:42', 0, '2026-05-15 11:33:45', 0, NULL),
(76, 'LC-Shop-AD(Meta)-OrderConfirmation-WL-SDSME_2611523569055248', 10, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:42', 0, '2026-05-15 11:33:45', 0, NULL),
(77, 'LC-AEM-AD(Meta)-CM-WL-SDSME_2611523569055248', 8, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:45', 0, '2026-05-15 11:34:23', 0, NULL),
(78, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_2611523569055248', 8, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:45', 0, '2026-05-15 11:34:23', 0, NULL),
(79, 'LC-Shop-AD(Meta)-AddToCart-WL-SDSME_2611523569055248', 12, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:47', 0, '2026-05-15 11:35:04', 0, NULL),
(80, 'LC-Shop-AD(Meta)-CM-WL-SDSME_2611523569055248', 12, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:47', 0, '2026-05-15 11:35:04', 0, NULL),
(81, 'LC-Shop-AD(Meta)-InitiateCheckout-WL-SDSME_2611523569055248', 12, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:47', 0, '2026-05-15 11:35:04', 0, NULL),
(82, 'LC-Shop-AD(Meta)-OrderConfirmation-WL-SDSME_2611523569055248', 12, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:47', 0, '2026-05-15 11:35:04', 0, NULL),
(83, 'LC-AEM-AD(Meta)-CM-WL-SDSME_548049651104383', 17, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:49', 0, '2026-05-15 11:35:49', 0, NULL),
(84, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_548049651104383', 17, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:49', 0, '2026-05-15 11:35:49', 0, NULL),
(85, 'LC-Shop-AD(Meta)-AddToCart-WL-SDSME_548049651104383', 20, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:51', 0, '2026-05-15 11:36:26', 0, NULL),
(86, 'LC-Shop-AD(Meta)-CM-WL-SDSME_548049651104383', 20, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:51', 0, '2026-05-15 11:36:26', 0, NULL),
(87, 'LC-Shop-AD(Meta)-InitiateCheckout-WL-SDSME_548049651104383', 20, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:51', 0, '2026-05-15 11:36:26', 0, NULL),
(88, 'LC-Shop-AD(Meta)-OrderConfirmation-WL-SDSME_548049651104383', 20, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:51', 0, '2026-05-15 11:36:26', 0, NULL),
(89, 'LC-AEM-AD(Meta)-CM-WL-SDSME_482024727631312', 43, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:54', 0, '2026-05-15 11:37:16', 0, NULL),
(90, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_482024727631312', 43, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:54', 0, '2026-05-15 11:37:16', 0, NULL),
(91, 'LC-Shop-AD(Meta)-AddToCart-WL-SDSME_482024727631312', 46, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:56', 0, '2026-05-15 11:37:51', 0, NULL),
(92, 'LC-Shop-AD(Meta)-CM-WL-SDSME_482024727631312', 46, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:56', 0, '2026-05-15 11:37:51', 0, NULL),
(93, 'LC-Shop-AD(Meta)-InitiateCheckout-WL-SDSME_482024727631312', 46, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:56', 0, '2026-05-15 11:37:51', 0, NULL),
(94, 'LC-Shop-AD(Meta)-OrderConfirmation-WL-SDSME_482024727631312', 46, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:34:56', 0, '2026-05-15 11:37:51', 0, NULL),
(95, 'LC-AEM-AD(Meta)-CM-WL-SDSME_920510539933849', 35, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:05', 0, '2026-05-15 11:38:29', 0, NULL),
(96, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_920510539933849', 35, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:05', 0, '2026-05-15 11:38:29', 0, NULL),
(97, 'LC-Shop-AD(Meta)-AddToCart-WL-SDSME_920510539933849', 38, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:07', 0, '2026-05-15 11:39:03', 0, NULL),
(98, 'LC-Shop-AD(Meta)-CM-WL-SDSME_920510539933849', 38, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:07', 0, '2026-05-15 11:39:03', 0, NULL),
(99, 'LC-Shop-AD(Meta)-InitiateCheckout-WL-SDSME_920510539933849', 38, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:07', 0, '2026-05-15 11:39:03', 0, NULL),
(100, 'LC-Shop-AD(Meta)-OrderConfirmation-WL-SDSME_920510539933849', 38, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:07', 0, '2026-05-15 11:39:03', 0, NULL),
(101, 'LC-AEM-AD(Meta)-CM-WL-SDSME_1218026982855426', 39, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:09', 0, '2026-05-15 11:39:47', 0, NULL),
(102, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_1218026982855426', 39, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:09', 0, '2026-05-15 11:39:47', 0, NULL),
(103, 'LC-Shop-AD(Meta)-AddToCart-WL-SDSME_1218026982855426', 42, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:12', 0, '2026-05-15 11:40:24', 0, NULL),
(104, 'LC-Shop-AD(Meta)-CM-WL-SDSME_1218026982855426', 42, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:12', 0, '2026-05-15 11:40:24', 0, NULL),
(105, 'LC-Shop-AD(Meta)-InitiateCheckout-WL-SDSME_1218026982855426', 42, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:12', 0, '2026-05-15 11:40:24', 0, NULL),
(106, 'LC-Shop-AD(Meta)-OrderConfirmation-WL-SDSME_1218026982855426', 42, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:12', 0, '2026-05-15 11:40:24', 0, NULL),
(107, 'LC-AEM-AD(Meta)-CM-WL-SDSME_1041328750781537', 26, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:14', 0, '2026-05-15 11:41:17', 0, NULL),
(108, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_1041328750781537', 26, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:14', 0, '2026-05-15 11:41:17', 0, NULL),
(109, 'LC-AEM-AD(Meta)-CM-WL-SDSME_1041328750781537', 25, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:17', 0, '2026-05-15 11:41:55', 0, NULL),
(110, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_1041328750781537', 25, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:17', 0, '2026-05-15 11:41:55', 0, NULL),
(111, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_9fd1-4a4a5906c84b', 8, 3, NULL, 1, 1, '2026-06-19', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-20 08:36:44', 0, '2026-05-15 11:43:34', 0, NULL),
(112, 'LC-Shop-AD(Contentsquare)-CM-WL-SDSME_9fd1-4a4a5906c84b', 12, 3, NULL, 1, 1, '2026-06-19', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-20 08:36:39', 0, '2026-05-15 11:44:05', 0, NULL),
(113, 'LC-Shop-AD(Meta)-AddToCart-WL-SDSME_1041328750781537', 34, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:31', 0, '2026-05-15 11:45:06', 0, NULL),
(114, 'LC-Shop-AD(Meta)-CM-WL-SDSME_1041328750781537', 34, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:31', 0, '2026-05-15 11:45:06', 0, NULL),
(115, 'LC-Shop-AD(Meta)-InitiateCheckout-WL-SDSME_1041328750781537', 34, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:31', 0, '2026-05-15 11:45:06', 0, NULL),
(116, 'LC-Shop-AD(Meta)-OrderConfirmation-WL-SDSME_1041328750781537', 34, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:31', 0, '2026-05-15 11:45:06', 0, NULL),
(117, 'LC-Shop-AD(Meta)-AddToCart-WL-SDSME_1041328750781537', 32, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:33', 0, '2026-05-15 11:45:46', 0, NULL),
(118, 'LC-Shop-AD(Meta)-CM-WL-SDSME_1041328750781537', 32, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:33', 0, '2026-05-15 11:45:46', 0, NULL),
(119, 'LC-Shop-AD(Meta)-InitiateCheckout-WL-SDSME_1041328750781537', 32, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:33', 0, '2026-05-15 11:45:46', 0, NULL),
(120, 'LC-Shop-AD(Meta)-OrderConfirmation-WL-SDSME_1041328750781537', 32, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:33', 0, '2026-05-15 11:45:46', 0, NULL),
(121, 'LC-AEM-AD(Meta)-CM-WL-SDSME_1041328750781537', 22, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:35', 0, '2026-05-15 11:46:29', 0, NULL),
(122, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_1041328750781537', 22, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:35', 0, '2026-05-15 11:46:29', 0, NULL),
(123, 'LC-Shop-AD(Meta)-AddToCart-WL-SDSME_1041328750781537', 30, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:38', 0, '2026-05-15 11:47:09', 0, NULL),
(124, 'LC-Shop-AD(Meta)-CM-WL-SDSME_1041328750781537', 30, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:38', 0, '2026-05-15 11:47:09', 0, NULL),
(125, 'LC-Shop-AD(Meta)-InitiateCheckout-WL-SDSME_1041328750781537', 30, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:38', 0, '2026-05-15 11:47:09', 0, NULL),
(126, 'LC-Shop-AD(Meta)-OrderConfirmation-WL-SDSME_1041328750781537', 30, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:38', 0, '2026-05-15 11:47:09', 0, NULL),
(127, 'LC-AEM-AD(Meta)-CM-WL-SDSME_1041328750781537', 23, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:40', 0, '2026-05-15 11:47:47', 0, NULL),
(128, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_1041328750781537', 23, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:40', 0, '2026-05-15 11:47:47', 0, NULL),
(129, 'LC-Shop-AD(Meta)-AddToCart-WL-SDSME_1041328750781537', 31, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:43', 0, '2026-05-15 11:48:32', 0, NULL),
(130, 'LC-Shop-AD(Meta)-CM-WL-SDSME_1041328750781537', 31, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:43', 0, '2026-05-15 11:48:32', 0, NULL),
(131, 'LC-Shop-AD(Meta)-InitiateCheckout-WL-SDSME_1041328750781537', 31, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:43', 0, '2026-05-15 11:48:32', 0, NULL),
(132, 'LC-Shop-AD(Meta)-OrderConfirmation-WL-SDSME_1041328750781537', 31, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:35:43', 0, '2026-05-15 11:48:32', 0, NULL),
(133, 'LC-AEM-AD(Meta)-CM-WL-SDSME_1041328750781537', 24, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:36:57', 0, '2026-05-15 11:49:03', 0, NULL),
(134, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_1041328750781537', 24, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:36:57', 0, '2026-05-15 11:49:03', 0, NULL),
(135, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_9a0c-16784c10bbf7', 7, 3, NULL, 1, 1, '2026-06-20', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-20 08:37:49', 0, '2026-05-15 11:52:05', 0, NULL),
(136, 'LC-Shop-AD(Contentsquare)-CM-WL-SDSME_9a0c-16784c10bbf7', 10, 3, NULL, 1, 1, '2026-06-20', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-20 08:37:52', 0, '2026-05-15 11:52:52', 0, NULL),
(137, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_94f6-63b3da673666', 17, 3, NULL, 1, 1, '2026-06-20', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-20 08:37:54', 0, '2026-05-15 11:53:22', 0, NULL),
(138, 'LC-Shop-AD(Contentsquare)-CM-WL-SDSME_94f6-63b3da673666', 20, 3, NULL, 1, 1, '2026-06-20', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-20 08:38:18', 0, '2026-05-15 11:53:47', 0, NULL),
(139, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_996c-88faa44bb004', 43, 3, NULL, 1, 1, '2026-06-23', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-20 08:39:03', 0, '2026-05-15 11:54:25', 0, NULL),
(140, 'LC-Shop-AD(Contentsquare)-CM-WL-SDSME_996c-88faa44bb004', 46, 3, NULL, 1, 1, '2026-06-23', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-20 08:38:55', 0, '2026-05-15 11:55:03', 0, NULL),
(141, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_9504-c48c52f97a64', 35, 3, NULL, 1, 1, '2026-06-24', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-20 08:39:43', 0, '2026-05-15 11:55:40', 0, NULL),
(142, 'LC-Shop-AD(Contentsquare)-CM-WL-SDSME_9504-c48c52f97a64', 38, 3, NULL, 1, 1, '2026-06-24', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-20 08:39:36', 0, '2026-05-15 11:56:04', 0, NULL),
(143, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_9c48-0029352a8592', 39, 3, NULL, 1, 1, '2026-06-25', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-20 08:40:17', 0, '2026-05-15 11:56:36', 0, NULL),
(144, 'LC-Shop-AD(Contentsquare)-CM-WL-SDSME_9c48-0029352a8592', 42, 3, NULL, 1, 1, '2026-06-25', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-20 08:40:37', 0, '2026-05-15 11:56:57', 0, NULL),
(145, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_9aaa-619dc6194dcf', 26, 3, NULL, 1, 1, '2026-06-25', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-20 08:41:02', 0, '2026-05-15 11:57:44', 0, NULL),
(146, 'LC-AEM-AD(RTBHouse)-CM-ALL(WL)-SDSME_b2e4-3228e7ef555e', 39, 4, '(.*)', 1, 1, '2026-06-25', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRaf00914f9c194739b6e98de4b0f72380/rules/RLdfc9d26bbaa24c14b20837acbb5e9ab5', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, NULL, NULL, 0, '2026-05-25 13:24:15', 0, '2026-05-15 12:08:22', 0, NULL),
(147, 'LC-Shop-AD(RTBHouse)-CM-ALL(WL)-SDSME_b2e4-3228e7ef555e', 42, 4, '(.*)', 1, 1, '2026-06-25', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61cef25ff4eb400391e974558ec4c577/rules/RL232baa7b17db4012a14bf80a591a74b4', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, NULL, NULL, 0, '2026-06-02 15:57:59', 0, '2026-05-15 12:09:06', 0, NULL),
(148, 'LC-Shop-AD(RTBHouse)-CM-ALL(WL)-SDSME_ab10-62118729eba5', 34, 4, '(.*)', 1, 1, '2026-06-25', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules/RLf2d085188576435e9189e53f2735fa16', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, NULL, NULL, 0, '2026-06-02 15:53:38', 0, '2026-05-15 12:12:02', 0, NULL),
(149, 'LC-Shop-AD(RTBHouse)-CM-ALL(WL)-SDSME_af47-ea652e13577e', 32, 4, '(.*)', 1, 1, '2026-06-25', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf28c4faf02284603896219ef6cb42fa2/rules/RLb70c5ea8594647efa9664320f8376602', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, NULL, NULL, 0, '2026-06-02 15:53:19', 0, '2026-05-15 12:12:34', 0, NULL),
(150, 'LC-AEM-AD(RTBHouse)-CM-ALL(WL)-SDSME_8b79-e3c381a104fc', 22, 4, '(.*)', 1, 1, '2026-06-25', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4507cc44d1e742498bc65fc391f1738f/rules/RLdb4f174b35d64c569824783b298143d0', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, NULL, NULL, 0, '2026-06-03 12:10:24', 0, '2026-05-15 12:13:11', 0, NULL),
(151, 'LC-Shop-AD(RTBHouse)-CM-ALL(WL)-SDSME_8b79-e3c381a104fc', 30, 4, '(.*)', 1, 1, '2026-06-25', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules/RL83fd6494aa914c2d8a10ff3f9afa7ecf/ruleComponent/RCe4eb03f9c43440e6a8190848f067201f', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, NULL, NULL, 0, '2026-06-03 12:10:53', 0, '2026-05-15 12:13:43', 0, NULL),
(152, 'LC-AEM-AD(RTBHouse)-CM-ALL(WL)-SDSME_ab10-62118729eba5', 26, 4, NULL, 1, 1, '2026-06-26', NULL, 'https://www.samsung.com/', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, NULL, NULL, 0, '2026-05-20 08:45:21', 0, '2026-05-15 12:14:33', 0, NULL),
(153, 'LC-AEM-AD(RTBHouse)-CM-ALL(WL)-SDSME_af47-ea652e13577e', 25, 4, NULL, 1, 1, '2026-06-26', NULL, 'https://www.samsung.com/', 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, NULL, NULL, 0, '2026-05-20 08:45:29', 0, '2026-05-15 12:15:03', 0, NULL),
(154, 'LC-AEM-AD(Meta)-CM-WL-SDSME_548049651104383', 21, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:44:57', 0, '2026-05-15 12:28:48', 0, NULL),
(155, 'LC-AEM-AD(Meta)-ViewContent-WL-SDSME_548049651104383', 21, 9, NULL, 1, 1, '2026-12-01', NULL, 'https://www.samsung.com/', 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, NULL, NULL, 0, '2026-05-20 08:44:57', 0, '2026-05-15 12:28:48', 0, NULL),
(158, 'LC-AEM-AD(AdobeDSP)-CM-WL-SDSME-d477_c2bca6ae619b', 1, 10, '(.*)', 1, 1, '2026-07-12', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RLe448548038254f4c87c18a57661eff62?reactor-lens_version=prod20260325152620', 'Elsa Badran', 'elsa.badran@publicisgroupe.com', 5, NULL, NULL, 0, '2026-05-25 16:15:02', 0, '2026-05-15 12:32:49', 0, NULL),
(159, 'LC-AEM-AD(AdobeDSP)-Cart-ATC-SDSME-d477_c2bca6ae619b', 1, 10, '[data-cta-type=\"add-cart\"], [an-ca=\"buy cta\"], [an-ac=\"addToCart\"], [an-tr=\"bridge-flagship pdp-add item-option_click\"]', 1, 1, '2026-07-12', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RL08e4a7799ec444c2aac642d50107cb15?reactor-lens_version=prod20260325152620', 'Elsa Badran', 'elsa.badran@publicisgroupe.com', 5, NULL, NULL, 0, '2026-05-25 16:14:11', 0, '2026-05-15 12:32:49', 0, NULL),
(160, 'LC-AEM-AD(AdobeDSP)-CM-WL-SDSME-d509_afd6e0b69fe2', 2, 10, '(.*)', 1, 1, '2026-07-12', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RL24aae8d4ab3747ee8d3666aed450bb03', 'Elsa Badran', 'elsa.badran@publicisgroupe.com', 5, NULL, NULL, 0, '2026-05-25 16:17:11', 0, '2026-05-15 12:33:19', 0, NULL),
(161, 'LC-AEM-AD(AdobeDSP)-Cart-ATC-SDSME-d509_afd6e0b69fe2', 2, 10, '[data-cta-type=\"add-cart\"], [an-ca=\"buy cta\"], [an-ac=\"addToCart\"], [an-tr=\"bridge-flagship pdp-add item-option_click\"]', 1, 1, '2026-07-12', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RL4199c3ee7df5449ca8e4c2515ca44034', 'Elsa Badran', 'elsa.badran@publicisgroupe.com', 5, NULL, NULL, 0, '2026-05-25 16:16:46', 0, '2026-05-15 12:33:19', 0, NULL),
(162, 'LC-Shop-AD(AdobeDSP)-CM-WL-SDSME-d477_c2bca6ae619b', 4, 10, '(.*)', 1, 1, '2026-07-14', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RL8f29866fbb7a43779baccf073fa289fb', 'Elsa Badran', 'elsa.badran@publicisgroupe.com', 5, NULL, NULL, 0, '2026-05-25 16:19:08', 0, '2026-05-15 12:33:41', 0, NULL),
(163, 'LC-Shop-AD(AdobeDSP)-CM-WL-SDSME-d509_afd6e0b69fe2', 6, 10, '(.*)', 1, 1, '2026-07-14', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RLcea2c911335040238040c6875a67ad06', 'Elsa Badran', 'elsa.badran@publicisgroupe.com', 5, NULL, NULL, 0, '2026-05-25 16:20:10', 0, '2026-05-15 12:34:03', 0, NULL),
(164, 'LC-Shop-AD(Contentsquare)-Cart-ATC-SDSME_9a0c-16784c10bbf7', 10, 3, '(.*)', 1, 1, '2026-07-20', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-06-01 11:44:33', 0, '2026-05-19 16:09:25', 0, NULL),
(165, 'LC-Shop-AD(Contentsquare)-OrderConfirmation-WL-SDSME_9a0c-16784c10bbf7', 10, 3, '^shop order confirmation$', 1, 1, '2026-07-22', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RLa9f884512a104ebead8f1cf0ef8ea4b4', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-06-01 11:44:33', 0, '2026-05-19 16:09:25', 0, NULL),
(166, 'LC-Shop-AD(Contentsquare)-Cart-ATC-SDSME_9fd1-4a4a5906c84b', 12, 3, NULL, 1, 1, '2026-07-20', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-06-01 11:44:24', 0, '2026-05-19 16:13:16', 0, NULL),
(167, 'LC-Shop-AD(Contentsquare)-OrderConfirmation-WL-SDSME_9fd1-4a4a5906c84b', 12, 3, '^shop order confirmation$', 1, 1, '2026-07-22', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RL4e9c08cb9e384a72978cd0a69e14d2ab', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-06-01 11:44:24', 0, '2026-05-19 16:13:16', 0, NULL),
(168, 'LC-Shop-AD(Reddit)-CM-ALL(WL)-PXFLW_t2_7ovbtan2k', 4, 11, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RLe5841935545d4ae0b9c50b48a51235eb', '', '', 0, NULL, NULL, 0, '2026-05-25 11:38:09', 0, '2026-05-20 10:04:48', 0, NULL),
(169, 'LC-Shop-AD(RTBHouse)-CM-ALL(WL)-PXFLW_5b362e2a-f7b9-4e18-9549-dcea0de1a5dd', 1, 4, 'home|shop home|microsite|offer main|offer detail|search result|ai\\-search|product category detail|shop category|product finder|business product finder|product detail|flagship pdp|shop detail|business ', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RL4ac251a7143946bebdd69057ddc0245c', '', '', NULL, NULL, NULL, 0, '2026-05-21 17:38:03', 0, '2026-05-20 10:05:46', 0, NULL),
(170, 'LC-Shop-AD(Snapchat)-CM-ALL(WL)-PXFLW_8a3b930f-43fa-4bbe-b738-8c7ebb4bfabe', 4, 12, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RL05dd6ce74ab249ca93a3abd7bd2b9f5a', '', '', 0, NULL, NULL, 0, '2026-05-25 11:34:34', 0, '2026-05-20 10:06:11', 0, NULL),
(171, 'LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_48d8372e-2280-4ca4-a713-5733425eb71f', 4, 13, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RLf5a39093f5d1466baa32877ed395ec26', '', '', 0, NULL, NULL, 0, '2026-05-25 11:34:52', 0, '2026-05-20 10:06:36', 0, NULL),
(172, 'LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_48d8372e-2280-4ca4-a713-5733425eb71f_rewards_page', 4, 13, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RL64cb85b4bfb640f7a758cc994cdb282c', '', '', 0, NULL, NULL, 0, '2026-05-25 11:34:55', 0, '2026-05-20 10:06:56', 0, NULL),
(173, 'LC-AEM-AD(Contentsquare)-Cart-ATC-SDSME_9fd1-4a4a5906c84b', 8, 3, '[an-ac=\"addToCart\"], [an-tr=\"bridge-flagship pdp-add item-option_click\"], [data-cart=\"true\"], [an-ca=\"buy cta\"]', 1, 1, '2026-07-25', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RL33fd03c0d3c84277a1a1669ea8cf0aed', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-06-01 11:37:08', 0, '2026-05-21 13:14:52', 0, NULL);
INSERT INTO `tags` (`id`, `name`, `property_id`, `vendor_id`, `conditions`, `status`, `is_active`, `al_end_date`, `requested_date`, `ref_link`, `requester_name`, `requester_email`, `requester_organization`, `new_tag_request`, `new_rule_request`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(174, 'LC-AEM-AD(Contentsquare)-Cart-ATC-SDSME_9a0c-16784c10bbf7', 7, 3, '[an-ac=\"addToCart\"], [an-tr=\"bridge-flagship pdp-add item-option_click\"], [data-cart=\"true\"], [an-ca=\"buy cta\"]', 1, 1, '2026-07-22', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RLfd1ffc04a6b949d9a9099e38bd251dcf', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-06-01 11:37:00', 0, '2026-05-21 13:17:32', 0, NULL),
(175, 'LC-AEM-AD(Firework)-CM-WL-SDSME_oPNw8q', 1, 6, 'home|shop home|microsite|offer main|offer detail|search result|ai\\-search|product category detail|shop category|product finder|business product finder|product detail|flagship pdp|shop detail|business ', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RLdadf6dd7236943f584392ed7ce430aaa', 'Shereen El Gendy', 'shereen.g@partner.samsung.com', 1, NULL, NULL, 0, '2026-06-02 12:28:22', 0, '2026-05-21 17:24:28', 0, NULL),
(176, 'LC-AEM-AD(Firework)-CM-WL-PXFLW_floating_video_player', 2, 6, 'home|shop home|microsite|offer main|offer detail|search result|ai\\-search|product category detail|shop category|product finder|business product finder|product detail|flagship pdp|shop detail|business ', 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RLdadf6dd7236943f584392ed7ce430aaa', 'Shereen El Gendy', 'shereen.g@partner.samsung.com', 1, NULL, NULL, 0, '2026-05-22 09:26:42', 0, '2026-05-21 17:25:30', 0, NULL),
(177, 'LC-Shop-AD(Contentsquare)-OrderConfirmation-WL-SDSME_94f6-63b3da673666', 20, 3, NULL, 6, 1, '2026-07-25', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-22 15:52:31', 0, '2026-05-22 15:52:31', 0, NULL),
(178, 'LC-AEM-AD(Contentsquare)-Cart-ATC-SDSME_94f6-63b3da673666', 17, 3, NULL, 6, 1, '2026-07-25', NULL, 'https://www.samsung.com/', 'Eiass Ali', 'eiass.ali@samsung.com', 1, NULL, NULL, 0, '2026-05-22 15:53:29', 0, '2026-05-22 15:53:29', 0, NULL),
(179, 'LC-Shop-AD(Reddit)-CM-ALL(WL)-PXFLW_t2_7ovbtan2k', 6, 11, 'home|shop home|microsite|offer main|offer detail|search result|ai\\-search|product category detail|shop category|product finder|business product finder|product detail|flagship pdp|shop detail|business ', 1, 1, '2026-08-03', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RL671ce4649a8142dd8a100ad9712c64fb', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:59:25', 0, '2026-05-22 17:23:28', 0, NULL),
(180, 'LC-Shop-AD(RTB)-CM-ALL(WL)-PXFLW_70bbf4ef-dd46-4c11-b642-7d1c2319b30e', 6, 14, '(.*)', 1, 1, '2026-08-03', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RL028f29f94991407fbb3414a71d666d84', '', '', NULL, NULL, NULL, 0, '2026-06-01 11:00:37', 0, '2026-05-22 17:24:05', 0, NULL),
(181, 'LC-Shop-AD(Snapchat)-CM-ALL(WL)-PXFLW_9176ef98-113d-4571-9987-122be6647409', 6, 12, '(.*)', 1, 1, '2026-08-03', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RLb2090ede4e5f4905b1aa369fe6e615e5', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:59:44', 0, '2026-05-22 17:24:31', 0, NULL),
(182, 'LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_ca8ad850-bd23-4c5e-aaa7-2a305917cd94', 6, 13, '(.*)', 1, 1, '2026-08-03', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RLefe4335d4e0d403e9551a4927c267f9c', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:58:33', 0, '2026-05-22 17:24:53', 0, NULL),
(183, 'LC-Shop-AD(Twitter)-CM-WL-PXFLW_ca8ad850-bd23-4c5e-aaa7-2a305917cd94_rewards_page', 6, 13, '(.*)', 1, 1, '2026-08-03', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RLd2b6646e41bc4407bc8f0b07c6f74bb8', '', '', NULL, NULL, NULL, 0, '2026-06-01 11:00:11', 0, '2026-05-22 17:25:14', 0, NULL),
(184, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_10001941', 14, 15, '(.*)', 1, 1, '2026-08-03', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf8dc3f08cfd341188fb86091e62b374d/rules/RL13912c509d7e4a11b75a0c8179814d71', '', '', NULL, NULL, NULL, 0, '2026-06-02 14:18:30', 0, '2026-05-22 17:25:49', 0, NULL),
(185, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_10001941', 15, 15, '(.*)', 1, 1, '2026-08-03', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR37aea4f52e284fa384c3dd7ba76f44c4/rules/RL1bbe1dcab4734363a543a1243c079f51', '', '', NULL, NULL, NULL, 0, '2026-06-02 14:20:12', 0, '2026-05-22 17:26:15', 0, NULL),
(186, 'LC-Shop-AD(Insider)-OrderConfirmation-CM-ALL(WL)-PXFLW_10001941', 15, 15, '(.*)', 1, 1, '2026-08-03', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR37aea4f52e284fa384c3dd7ba76f44c4/rules/RL965da2e146b94769945b936f802fa5dc', '', '', NULL, NULL, NULL, 0, '2026-06-02 14:19:55', 0, '2026-05-22 17:26:37', 0, NULL),
(187, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_10005181', 44, 15, '(.*)', 1, 1, '2026-08-03', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR14a762a6a33c4d608665a7d0e67d7e7a/rules/RL6d1df7cef4ca49e1bae309201dec87ef', '', '', NULL, NULL, NULL, 0, '2026-06-02 14:23:37', 0, '2026-05-22 17:27:33', 0, NULL),
(188, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_10005181', 45, 15, '(.*)', 1, 1, '2026-08-03', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc0d1939c6cc84ef3be7b0ad0c5df94d4/rules/RL039a49c9566a4606a272af94862b9d19', '', '', NULL, NULL, NULL, 0, '2026-06-02 14:25:12', 0, '2026-05-22 17:27:54', 0, NULL),
(189, 'LC-Shop-AD(Insider)-OrderConfirmation-CM-ALL(WL)-PXFLW_10005181', 45, 15, '(.*)', 1, 1, '2026-08-03', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc0d1939c6cc84ef3be7b0ad0c5df94d4/rules/RL7989d3be7ddb4f1d80ab4a50026ceae5', '', '', NULL, NULL, NULL, 0, '2026-06-02 14:24:52', 0, '2026-05-22 17:28:19', 0, NULL),
(190, 'LC-AEM-AD(Amazon)-CM-WL-PXFLW_277a8f5e-2d7b-4799-91f8-a089308d8e1b', 1, 16, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RLe876e1603137407a9502d9bfb0df924d', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:29:10', 0, '2026-05-25 09:25:33', 0, NULL),
(191, 'LC-AEM-AD(Appsflyer)-CM-WL-PXFLW_smartscript', 1, 5, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RL196621ca70cd4e1faf4a0275b9a8418e', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:36:09', 0, '2026-05-25 09:26:02', 0, NULL),
(192, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_9006-d99180e087d0', 1, 3, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RL8b83c36828b340189aaf73e59d6ce6ea', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:28:47', 0, '2026-05-25 09:26:21', 0, NULL),
(193, 'LC-AEM-AD(Contentsquare)-CM-WL-PXFLW_da035490-de75-4a0b-bcac-5dd36318b187', 1, 3, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RLa6817863bc9b44c989e9da51a7acc42a', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:29:24', 0, '2026-05-25 09:26:39', 0, NULL),
(194, 'LC-AEM-AD(Criteo)-CM-WL-PXFLW_3be66720-a2ec-4b75-a022-ab04b3d8f41a', 1, 17, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RLcba1121249d242c8acc6e167adeeaed9', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:35:54', 0, '2026-05-25 09:27:03', 0, NULL),
(195, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_fdf2971f-8bfa-4b46-a955-eac2343ae085', 1, 15, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RLf76402fcf7fb4c81aa58256c4e29931f', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:35:32', 0, '2026-05-25 09:27:27', 0, NULL),
(196, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_fdf2971f-8bfa-4b46-a955-eac2343ae085_rewards_page', 1, 15, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RLffb985d955224b73ba209e395afeba44', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:29:38', 0, '2026-05-25 09:27:43', 0, NULL),
(197, 'LC-AEM-AD(Naturalsearch)-CM-WL-PXFLW_3d1c0542-c4ad-4978-83ed-82c9f3bbd43d', 1, 18, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RL4d44584851124fc1a2054edf93050b88', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:36:23', 0, '2026-05-25 09:28:02', 0, NULL),
(198, 'LC-AEM-AD(Reddit)-CM-WL-PXFLW_t2_7ovbtan2k', 1, 11, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RL58d3df5526e0408181222106b247116d', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:36:38', 0, '2026-05-25 09:28:20', 0, NULL),
(199, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_10009534', 3, 15, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR66456bebda424fb3af85ef35ee171c71/rules/RLd30dc4f7a2ab4fc8bca5529ebe6ccea1', '', '', NULL, NULL, NULL, 0, '2026-05-25 11:28:14', 0, '2026-05-25 09:31:19', 0, NULL),
(200, 'LC-Shop-AD(Amazon)-CM-ALL(WL)-PXFLW_0d2f7c74-7fe6-458a-9b35-95f071505811', 4, 16, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RL3534cbb9a0144b68b5df92d8585c8ab3', '', '', NULL, NULL, NULL, 0, '2026-05-25 10:52:42', 0, '2026-05-25 09:31:57', 0, NULL),
(201, 'LC-Shop-AD(CampaignManager)-OrderConfirmation-ALL(WL)-PXFLW_6a70d3d0-e011-4e84-9f03-a331a9a4d472', 4, 19, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RL59c3473a51f0448789614460af34f4f2', '', '', NULL, NULL, NULL, 0, '2026-05-25 10:52:42', 0, '2026-05-25 09:32:20', 0, NULL),
(202, 'LC-Shop-AD(Contentsquare)-CM-ALL(WL)-PXFLW_977f9a82566fb', 4, 3, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RL539ecb45cc084a93bfc698efff761a21', '', '', NULL, NULL, NULL, 0, '2026-05-25 10:52:42', 0, '2026-05-25 09:32:41', 0, NULL),
(203, 'LC-Shop-AD(Criteo)-CM-ALL(WL)-PXFLW_1ebac5d7-cc79-475a-bf22-09939d6b8a71', 4, 17, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RLcd01aa04feec4685a58aad91bf9b6428', '', '', NULL, NULL, NULL, 0, '2026-05-25 10:52:42', 0, '2026-05-25 09:33:09', 0, NULL),
(204, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_e14c1f03-5712-4534-b3ba-00d21034c997', 4, 15, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RLb0bba23ed99044c68d1f7029eebdc543', '', '', NULL, NULL, NULL, 0, '2026-05-25 10:52:42', 0, '2026-05-25 09:33:33', 0, NULL),
(205, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_e14c1f03-5712-4534-b3ba-00d21034c997_rewards_page', 4, 15, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RL9f69fe3213c441c896e9ebb35a9ad7cf', '', '', NULL, NULL, NULL, 0, '2026-05-25 10:52:42', 0, '2026-05-25 09:33:52', 0, NULL),
(206, 'LC-Shop-AD(Naturalsearch)-CM-ALL(WL)-PXFLW_06fcf92b-d0ee-4f15-a822-0f3acfebed88', 4, 18, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RLe399abfd98ff43c79a43be7b603a7891', '', '', 0, NULL, NULL, 0, '2026-05-25 10:52:42', 0, '2026-05-25 09:34:07', 0, NULL),
(207, 'LC-Shop-AD(Amazon)-CM-ALL(WL)-PXFLW_d32fd2de-4c67-4a2a-a462-2abd31040cb2', 6, 16, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RLf493121662b6404b8b4106a6f9096118', '', '', NULL, NULL, NULL, 0, '2026-05-25 11:05:41', 0, '2026-05-25 09:35:17', 0, NULL),
(208, 'LC-Shop-AD(CampaignManager)-OrderConfirmation-ALL(WL)-PXFLW_2fbebe0a-268d-45ee-9269-1d42602d1be1', 6, 19, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RL00de3986b68f48b4a95b26607a6e5f15', '', '', NULL, NULL, NULL, 0, '2026-05-25 11:05:41', 0, '2026-05-25 09:37:55', 0, NULL),
(209, 'LC-Shop-AD(Contentsquare)-CM-ALL(WL)-PXFLW_977f9a82566fb', 6, 3, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RL8148db9070aa45a5baad0095a7e2995c', '', '', NULL, NULL, NULL, 0, '2026-05-25 11:05:41', 0, '2026-05-25 09:38:11', 0, NULL),
(210, 'LC-Shop-AD(Criteo)-CM-ALL(WL)-PXFLW_35e1e22c-ffb0-4920-9511-261174e896a9', 6, 17, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RLeeae9ca9ed0847e9b0255c0455de73e0', '', '', NULL, NULL, NULL, 0, '2026-05-25 11:05:41', 0, '2026-05-25 09:38:27', 0, NULL),
(211, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_66a45698-7470-407f-a046-78683064b296', 6, 15, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RLb28972fdbf5f4cd7b92c312629c166f8', '', '', NULL, NULL, NULL, 0, '2026-05-25 11:05:41', 0, '2026-05-25 09:38:42', 0, NULL),
(212, 'LC-Shop-AD(Insider)-CM-WL-PXFLW_66a45698-7470-407f-a046-78683064b296_rewards_page', 6, 15, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RLbd3b94adb5ac46a5b086f3d59e1aee0a', '', '', NULL, NULL, NULL, 0, '2026-05-25 11:05:41', 0, '2026-05-25 09:39:05', 0, NULL),
(213, 'LC-Shop-AD(Linkedin)-CM-ALL(WL)-PXFLW_8b585520-0741-4928-a001-dcc167a1d7c8', 6, 20, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RL6dc78c7783a8407fad25f912591b3968', '', '', NULL, NULL, NULL, 0, '2026-05-25 11:05:41', 0, '2026-05-25 09:39:20', 0, NULL),
(214, 'LC-Shop-AD(Naturalsearch)-CM-ALL(WL)-PXFLW_36cfb790-cf94-454b-b3cd-38c8c321eaf9', 6, 18, '(.*)', 4, 1, '2026-06-09', '2026-08-09', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RLe18e1b07e7b341eeaeb832458be322d4', '', '', NULL, NULL, NULL, 0, '2026-05-25 11:05:41', 0, '2026-05-25 09:39:36', 0, NULL),
(215, 'LC-AEM-AD(Admitad)-CM-WL-PXFLW_a50541f5-78e9-4f2f-8b35-e43cf23e3b33', 7, 21, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RL20186b6b2eea4493a6939b162ca97fbc', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:53:31', 0, '2026-05-25 09:40:46', 0, NULL),
(216, 'LC-AEM-AD(Adalyser)-CM-WL-SDSME_ae77-d2794efb388b', 7, 22, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RL6f153e2babf1491fad2e76b022cc1248', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:58:23', 0, '2026-05-25 09:41:03', 0, NULL),
(217, 'LC-AEM-AD(Appsflyer)-CM-WL-PXFLW_smartscript', 7, 5, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RL581937436d76473fba6f16d9e366305f', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:55:10', 0, '2026-05-25 09:41:19', 0, NULL),
(218, 'LC-AEM-AD(Boostiny)-CM-WL-PXFLW_samsungksa', 7, 23, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RLabe290716a4e4e83a4ec8e16dc184968', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:54:07', 0, '2026-05-25 09:41:33', 0, NULL),
(219, 'LC-AEM-AD(Criteo)-CM-WL-PXFLW_05616dc9-7581-444e-ac88-68954b0e9425', 7, 17, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RLe4018cc6d8484382815763b0f9b32282', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:58:59', 0, '2026-05-25 09:41:51', 0, NULL),
(220, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_24ca1fdf-9b2b-4aca-b052-509feb5bbafe', 7, 24, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RL93abc1b13a7e4df7abe2afd87acaea97', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:51:42', 0, '2026-05-25 09:42:03', 0, NULL),
(221, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_78f13c92-abd6-495c-bf2f-16b647f05e94', 7, 24, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RL8805866adc254612b1b0a09633d87540', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:54:19', 0, '2026-05-25 09:42:16', 0, NULL),
(222, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_9ae7ce5e-e709-4dea-97e8-a13b64fd1bc1', 7, 24, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RLaf6fb2e7f3cf46c6a5cc2a93b29aac1f', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:53:04', 0, '2026-05-25 09:42:53', 0, NULL),
(223, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_a9a41a2c-6d73-48ee-826e-7d52ef7c0dbc', 7, 24, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RL0589ef3c8aea4bc087ec0589401c00ee', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:52:40', 0, '2026-05-25 09:43:09', 0, NULL),
(224, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_f30d3037-cc3d-477b-8901-d77acbf62ee4', 7, 24, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RLcd3690f064ee4be2921f8b317574396c', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:54:36', 0, '2026-05-25 09:43:28', 0, NULL),
(225, 'LC-AEM-AD(GoogleAdsRemarketing)-CM-WL-PXFLW_68e2ea84-b8e9-4f5c-8687-2a381332bff3', 7, 25, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RLb2da93916ceb45f999fbce1dd1653513', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:53:18', 0, '2026-05-25 09:43:44', 0, NULL),
(226, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_76276500-e5ee-4b60-a3df-2ae853a8b0ec', 7, 15, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RL1b05587a1799490da3cd8389fe3bcb11', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:57:50', 0, '2026-05-25 09:43:59', 0, NULL),
(227, 'LC-AEM-AD(Linkedin)-CM-WL-PXFLW_fa8aa003-5506-4f90-a1cf-ba2bf2269d24', 7, 20, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RL25b83605837a47bd9072ad4185aa926a', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:54:54', 0, '2026-05-25 09:44:12', 0, NULL),
(228, 'LC-AEM-AD(RTB)-CM-WL-PXFLW_684fb39e-250c-4dde-8375-0aaec2361e5d', 7, 14, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RLfd31da04f64c411aa5632c89802f1e4f', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:53:46', 0, '2026-05-25 09:44:32', 0, NULL),
(229, 'LC-AEM-AD(Snapchat)-CM-WL-PXFLW_246bc398-c43e-42cc-abda-25f6665235b4', 7, 12, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RLd9d7be4bb7244c7098840e4f67ed040f', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:58:41', 0, '2026-05-25 09:44:47', 0, NULL),
(230, 'LC-AEM-AD(Snapchat)-CM-WL-PXFLW_bc1ffb3d-a6fd-406d-91dc-f83d973d354f', 7, 12, '(.*)', 1, 1, '2026-08-06', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RL4c8b2dac045a40e1ac95d68608dbdb1d', '', '', NULL, NULL, NULL, 0, '2026-06-02 13:57:04', 0, '2026-05-25 09:45:01', 0, NULL),
(231, 'LC-Shop-AD(RTB)-CM-ALL(WL)-PXFLW_5b362e2a-f7b9-4e18-9549-dcea0de1a5dd', 4, 14, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RL4ac251a7143946bebdd69057ddc0245c', '', '', NULL, NULL, NULL, 0, '2026-05-25 11:37:27', 0, '2026-05-25 11:37:27', 0, NULL),
(232, 'LC-AEM-AD(Appsflyer)-CM-WL-PXFLW_smartscript', 13, 5, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR6bfef153a2dd4445b50f1d75982684b8/rules/RL121ccd5a71ef4995abc9af704fc4add1', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:37:43', 0, '2026-06-01 10:37:43', 0, NULL),
(233, 'LC-AEM-AD(Bing)-CM-WL-PXFLW_343039099', 13, 26, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR6bfef153a2dd4445b50f1d75982684b8/rules/RL040edf2eae354c478ab9b2c6db974961', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:38:38', 0, '2026-06-01 10:38:38', 0, NULL),
(234, 'LC-AEM-AD(Criteo)-CM-WL-PXFLW_a6c6e94e-3228-4b22-9ee8-3e32bac2f20f', 13, 17, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR6bfef153a2dd4445b50f1d75982684b8/rules/RL9aacc509b35b4c6dbc311b2fe51b466a', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:39:27', 0, '2026-06-01 10:39:27', 0, NULL),
(235, 'LC-AEM-AD(Firework)-CM-WL-SDSME_floating_video_player', 13, 6, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR6bfef153a2dd4445b50f1d75982684b8/rules/RL40a6381bd9d44b10b30d12cf9bd36d7a', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:40:17', 0, '2026-06-01 10:40:17', 0, NULL),
(236, 'LC-AEM-AD(Gelirortaklari)-CM-WL-PXFLW_5d0af57e-06ad-43d4-a88d-206b78982046', 13, 27, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR6bfef153a2dd4445b50f1d75982684b8/rules/RL19c5a3a717ce451cbaec1682e524538b', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:41:26', 0, '2026-06-01 10:41:26', 0, NULL),
(237, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_e55e370b-ca47-448f-a10a-1cae517fba09', 13, 15, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR6bfef153a2dd4445b50f1d75982684b8/rules/RL74f76cb2ee3c4e89a7fe7f49d2828ce6', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:42:16', 0, '2026-06-01 10:42:16', 0, NULL),
(238, 'LC-AEM-AD(Linkedin)-CM-WL-PXFLW_cfcc4663-5736-4c11-bc81-2ecfdad96ea3', 13, 20, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR6bfef153a2dd4445b50f1d75982684b8/rules/RLbdf95f17e57043a780a3b4ab9dfe61c6', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:42:54', 0, '2026-06-01 10:42:54', 0, NULL),
(239, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_e8a22b85-dad0-4850-b59b-e5a0d03770b9 copy', 13, 13, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR6bfef153a2dd4445b50f1d75982684b8/rules/RL754c84f47db845bcaa3c85c93cb1173e', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:44:17', 0, '2026-06-01 10:44:17', 0, NULL),
(240, 'LC-AEM-AD(RTBHouse)-CM-ALL(WL)-SDSME_aaf6-d1164146cf4e', 13, 4, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR6bfef153a2dd4445b50f1d75982684b8/rules/RL0df1b5bc27bf40eaa7cca5192ca25ea6', '', '', 0, NULL, NULL, 0, '2026-06-01 13:40:56', 0, '2026-06-01 10:45:05', 0, NULL),
(241, 'LC-AEM-AD(RTBHouse)-Cart-ATC-SDSME_aaf6-d1164146cf4e', 13, 4, 'div.hubble-price-bar__price-cta > a,div.hubble-product__total-cta > div > div > a, [data-cta-type=\"add-cart continue\"], [data-cta-type=\"add-cart\"], [an-tr=\"bridge-flagship pdp-add item-option_click\"],', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR6bfef153a2dd4445b50f1d75982684b8/rules/RL98e60e254fc04d46b64f1c0b3a83bdae', '', '', 0, NULL, NULL, 0, '2026-06-01 14:10:29', 0, '2026-06-01 10:46:00', 0, NULL),
(242, 'LC-Shop-AD(Bing)-CM-ALL(WL)-PXFLW_343039099', 16, 26, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRffb0b6442ddd47769aad5467c2e4064f/rules/RL12b25858d9d740978115cf8db4acfc5a', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:48:55', 0, '2026-06-01 10:48:55', 0, NULL),
(243, 'LC-Shop-AD(Criteo)-CM-ALL(WL)-PXFLW_7b3283c7-3b15-43b3-9ff5-aa60165dd328', 16, 17, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRffb0b6442ddd47769aad5467c2e4064f/rules/RL956a1beea79b4b26815db152b13fc2f9', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:49:56', 0, '2026-06-01 10:49:56', 0, NULL),
(244, 'LC-Shop-AD(Gelirortaklari)-CM-ALL(WL)-PXFLW_5d0af57e-06ad-43d4-a88d-206b78982046', 16, 27, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRffb0b6442ddd47769aad5467c2e4064f/rules/RLcd86527a646f44158877bf24ce255d47', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:50:35', 0, '2026-06-01 10:50:35', 0, NULL),
(245, 'LC-Shop-AD(GelirortaklariConversion)-OrderConfirmation-WL-SDSE_60e1bb31-d431-440a-a059-1c41f75dff31', 16, 28, '(.*orderconfirmation)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRffb0b6442ddd47769aad5467c2e4064f/rules/RL68eec32ed71943fcb246f2a09539cf3a', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:51:13', 0, '2026-06-01 10:51:13', 0, NULL),
(246, 'LC-Shop-AD(GoogleAds)-CM-ALL(WL)-PXFLW_1033933301', 16, 24, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRffb0b6442ddd47769aad5467c2e4064f/rules/RL1fd439857d1b45bf88963b7b156f7101', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:51:51', 0, '2026-06-01 10:51:51', 0, NULL),
(247, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_13b10ce9-dadd-4e7d-ac03-d96bd445043f', 16, 15, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRffb0b6442ddd47769aad5467c2e4064f/rules/RL8909aee616a64d2781501ccae89c8335', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:52:25', 0, '2026-06-01 10:52:25', 0, NULL),
(248, 'LC-Shop-AD(Linkedin)-CM-ALL(WL)-PXFLW_9f15872f-d8cb-434d-8ce3-325a23a89b71', 16, 20, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRffb0b6442ddd47769aad5467c2e4064f/rules/RL0ed3b260e67845fdad03e11cfd0026cf', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:52:57', 0, '2026-06-01 10:52:57', 0, NULL),
(249, 'LC-Shop-AD(RTBHouse)-CM-ALL(WL)-SDSME_aaf6-d1164146cf4e', 16, 4, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRffb0b6442ddd47769aad5467c2e4064f/rules/RL801a3399d5d24f969d0b1c0369e980bb', '', '', 0, NULL, NULL, 0, '2026-06-01 12:15:33', 0, '2026-06-01 10:54:03', 0, NULL),
(250, 'LC-Shop-AD(RTBHouse)-Cart-WL-SDSME_aaf6-d1164146cf4e', 16, 4, '[data-an-tr=', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRffb0b6442ddd47769aad5467c2e4064f/rules/RL6e43a2e30db2453a990ec3df0ee7e94a', '', '', 0, NULL, NULL, 0, '2026-06-01 12:17:44', 0, '2026-06-01 10:54:43', 0, NULL),
(251, 'LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_4b7882c1-d7e7-4013-9126-0287a01788f2 copy', 16, 13, '(.*)', 1, 1, '2026-07-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRffb0b6442ddd47769aad5467c2e4064f/rules/RL50d49af448c646a492ac5a64987ac603', '', '', NULL, NULL, NULL, 0, '2026-06-01 10:55:56', 0, '2026-06-01 10:55:56', 0, NULL),
(252, 'LC-Shop-AD(RTBHouse)-OrderConfirmation-WL-SDSME_aaf6-d1164146cf4e', 16, 4, '^shop order confirmation$', 1, 1, '2026-08-02', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRffb0b6442ddd47769aad5467c2e4064f/rules/RL5f446f1a5697457b8bd94f54be97daee', 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, NULL, NULL, 0, '2026-06-03 08:42:02', 1, '2026-06-01 12:20:10', 0, NULL),
(253, 'LC-AEM-AD(RTBHouse)-ViewContent-WL-SDSME_aaf6-d1164146cf4e', 13, 4, '^product detail$|^flagship pdp$', 1, 1, '2026-08-02', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR6bfef153a2dd4445b50f1d75982684b8/rules/RL0c661db2cd664e06830b4dcefe78c6eb', 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, NULL, NULL, 0, '2026-06-03 08:42:05', 1, '2026-06-01 12:21:12', 0, NULL),
(254, 'LC-Shop-AD(Admitad)-CM-ALL(WL)-PXFLW_dd504b10-dc78-4b56-9ed9-846967c90693', 10, 21, '(.*)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RL1d27773de1e34867a43dba459512f7d1', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 11:51:53', 0, NULL),
(255, 'LC-Shop-AD(Boostiny)-CM-ALL(WL)-PXFLW_samsungksa', 10, 23, '(.*)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RL3f1516d5c5cd42e6bd85c8c732a7ad18', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 11:53:38', 0, NULL),
(256, 'LC-Shop-AD(Boostiny)-CM-OrderConfirmation-ALL(WL)-PXFLW_samsungksa', 10, 23, '(.*orderConfirmation)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RLfe2b74b2e5c54bcfa73948e08a07878c', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 11:56:08', 0, NULL),
(257, 'LC-Shop-AD(Criteo)-CM-ALL(WL)-PXFLW_d5c18d25-78c5-471b-a991-c210bcd87fed', 10, 17, '(.*)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RL66ed1b66aacb44e38d56da301ed2e25f', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 11:57:15', 0, NULL),
(258, 'LC-Shop-AD(GoogleAds)-Cart-ALL(WL)-PXFLW_6195fa9b-8de6-42d4-bcf7-ee9fa074cf51', 10, 24, '(.*cart)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RLabcc036366704bdda00429ce2b4cf62f', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 11:58:55', 0, NULL),
(259, 'LC-Shop-AD(GoogleAds)-Cart-ALL(WL)-PXFLW_c9bae7ad-186b-448f-be42-71fbb6d9dcdb', 10, 24, '(.*cart)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RL922c8667a3f449a8bacf4d566a6f9cc3', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 11:59:57', 0, NULL),
(260, 'LC-Shop-AD(GoogleAds)-Cart-ALL(WL)-PXFLW_f3066818-8048-4248-b3aa-aea20b6a7e30', 10, 24, '(.*cart)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RLb70de297f2904c5da31a6c2d005bc4fd', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 12:00:59', 0, NULL),
(261, 'LC-Shop-AD(GoogleAds)-CM-ALL(WL)-PXFLW_88018911-edb3-416b-b729-5bd80aea031e', 10, 24, '(.*)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RLbded8ed4317d442e89a17bbcc222f5ca', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 12:02:04', 0, NULL),
(262, 'LC-Shop-AD(GoogleAds)-OrderConfirmation-ALL(WL)-PXFLW_3bf47f14-307f-435d-af98-6dc13813bc8b', 10, 24, '(.*orderConfirmation)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RLdce077f1582e4b188ada402a0d3e74cd', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 12:02:58', 0, NULL),
(263, 'LC-Shop-AD(GoogleAds)-OrderConfirmation-ALL(WL)-PXFLW_4314d843-978a-47c1-a398-a844b532913c', 10, 24, '(.*orderConfirmation)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RL646004f4b42c4a119f846fd60414005e', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 12:03:56', 0, NULL),
(264, 'LC-Shop-AD(GoogleAds)-OrderConfirmation-ALL(WL)-PXFLW_6955ac90-b03a-4ac2-b2f5-81dcce380e94', 10, 24, '(.*orderConfirmation)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RLfbc86086ba704148ac3f4808506d2386', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 12:04:32', 0, NULL),
(265, 'LC-Shop-AD(GoogleAdsRemarketing)-CM-ALL(WL)-PXFLW_e1cd03fd-90a3-4cd1-a126-700abe4225db', 10, 25, '(.*)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RL972370c3f9f443719364dd56131324d1', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 12:05:49', 0, NULL),
(266, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_3e125522-a083-4162-8c47-81f9c1b4b0d8', 10, 15, '(.*)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RL88504ac41eec42c1850a52a4c0b4fe73', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 12:06:16', 0, NULL),
(267, 'LC-Shop-AD(Linkedin)-CM-ALL(WL)-PXFLW_023d42c9-9ab9-41ec-a3df-9ca3d27c6605', 10, 20, '(.*)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RLb9c494a183874b31b98500af1fa76756', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 12:07:32', 0, NULL),
(268, 'LC-Shop-AD(RTB)-CM-ALL(WL)-PXFLW_610c3bac-b872-46be-9aee-4b2e956ccc04', 10, 14, '(.*)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RL1d5770356f134f5d96d64cf897493c63', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 12:08:31', 0, NULL),
(269, 'LC-Shop-AD(Snapchat)-CM-ALL(WL)-PXFLW_1dbe5629-459d-44bb-948d-30d447c4db6f', 10, 12, '(.*)', 4, 1, '2026-06-12', '2026-08-12', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RL3690b84eda2542f7af84a423cb4449b3', '', '', NULL, NULL, NULL, 0, '2026-06-02 12:23:23', 0, '2026-06-02 12:09:28', 0, NULL),
(270, 'LC-AEM-AD(RTB)-CM-WL-PXFLW_4fb32171-da02-4225-95c2-a287c0a05326', 1, 14, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RL2611acf3b49c4c92aa46679d5a7eeff3', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(271, 'LC-AEM-AD(Snapchat)-CM-WL-PXFLW_f70bd227-3846-4cdb-b0a3-8535344bb417', 1, 12, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RL07014f9747bd453a987911af601bea4b', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(272, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_de6f615a-fb97-47bf-ac10-ad044684de00', 1, 13, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RLa4bad0a67ea24e5ab4a8c1c9ebbbd96f', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(273, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_de6f615a-fb97-47bf-ac10-ad044684de00_rewards_page', 1, 13, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR5657bad4cc9a4d8da75b5a2fca2c1574/rules/RL35994051021e4d58bce34fac2860c6f5', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(274, 'LC-AEM-AD(Amazon)-CM-WL-PXFLW_ef2eeeab-6b7e-4867-b8fd-c47091cd2ff2', 2, 16, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RLdff128b12190436eab8d806d3bdd3391', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(275, 'LC-AEM-AD(Appsflyer)-CM-WL-PXFLW_smartscript', 2, 5, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RL94a08b1c00c9402289c37f4b79f3c200', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(276, 'LC-AEM-AD(Contentsquare)-CM-WL-PXFLW_7328656c-8b65-4dbd-9bb4-8bfe52854053', 2, 3, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RL262e672271ec4bffa680a6776303dcf9', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(277, 'LC-AEM-AD(Contentsquare)-CM-WL-SDSME_9619-86d547da5294', 2, 3, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RLdbb3b869f7d94035b4b7fb0acf19f065', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(278, 'LC-AEM-AD(Criteo)-CM-WL-PXFLW_0fb661ec-aeb3-4fc9-b0fc-fece8090841e', 2, 17, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RL27ff50309c9d42e0939b434fe4c6ec42', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(279, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_319ed594-92eb-4800-832d-480e0f1a2f04', 2, 15, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RLd9fa2666217b4864b2301d76afc69c23', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(280, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_319ed594-92eb-4800-832d-480e0f1a2f04_rewards_page', 2, 15, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RLfdc820fcd464495aa19e2d4a0d5af944', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL);
INSERT INTO `tags` (`id`, `name`, `property_id`, `vendor_id`, `conditions`, `status`, `is_active`, `al_end_date`, `requested_date`, `ref_link`, `requester_name`, `requester_email`, `requester_organization`, `new_tag_request`, `new_rule_request`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(281, 'LC-AEM-AD(Linkedin)-CM-WL-PXFLW_65569730-c5d4-4ba7-9fad-57d5d46a9835', 2, 20, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RL96e9bc7425f443c39a4f08df27432a23', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(282, 'LC-AEM-AD(Naturalsearch)-CM-WL-PXFLW_d43ccd91-185d-4d73-a1fa-71d92abc5d8a', 2, 18, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RL25d570fa009a46c5afab5db912425acb', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(283, 'LC-AEM-AD(Reddit)-CM-WL-PXFLW_t2_7ovbtan2k', 2, 11, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RL23c9760402bb47f88844a4ef5b7e71f8', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(284, 'LC-AEM-AD(RTB)-CM-WL-PXFLW_a9d74e59-9321-434a-a5a9-9d6269fdd383', 2, 14, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RLaea2cf977ee34676867c47c4009d3b5a', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(285, 'LC-AEM-AD(Snapchat)-CM-WL-PXFLW_11d50326-da9d-4136-9c73-0e55d50df545', 2, 12, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RLf8041d3c9ed64946b3fa8b346975dac4', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(286, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_1953be5a-640a-4e41-82dd-d13d8b1c34f4', 2, 13, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RLa2c10870daf549e7a706e75ec239cd40', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(287, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_1953be5a-640a-4e41-82dd-d13d8b1c34f4_rewards_page', 2, 13, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa7cdc4dd00c24675812c3d05053e3cf3/rules/RL9cde51c66e4949f29aa55aef83d08794', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(288, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-ac76-4b1c9ab097aa', 4, 8, NULL, 1, 1, '2026-06-25', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc290a80a826d4ceeb30cfcbe59148dbc/rules/RLa60e17a264304f8690b64836d11488ec', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(289, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_10009534', 5, 15, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR89cdc1c03c074927b97991df03ce53d7/rules/RL0079d2d39fd44a3981b8aaf5338e0bf7', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(290, 'LC-Shop-AD(Insider)-OrderConfirmation-CM-ALL(WL)-PXFLW_10009534', 5, 15, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR89cdc1c03c074927b97991df03ce53d7/rules/RL5689d55a73b74be48c015678c7c4af9e', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(291, 'LC-Shop-AD(CJ)-CM-ALL(WL)-SDSME-ac76-4b1c9ab097aa', 6, 8, NULL, 1, 1, '2026-06-25', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR508988f4e47a4b0f9064de57454b604d/rules/RL4096467fd5924ab29494b3aecb0cf0ff', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(292, 'LC-AEM-AD(Teads)-CM-WL-PXFLW_5a5895c3-fdb4-4156-8416-c392291fa155', 7, 29, NULL, 1, 1, '2026-07-16', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RLb4502e2cc8fa4c5188f9e26078bcd7c2', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(293, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_79300487-c261-44f6-9b25-8138f6fbc0ab', 7, 13, NULL, 1, 1, '2026-07-16', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RL44f2e2caa2394def814498742b102436', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(294, 'LC-AEM-AD(Taboola)-CM-ALL(WL)-SDSME_2029101', 7, 30, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR84237b02f2524aae898e192f7bab097a/rules/RL1e4bf6b0de1046679aca84d6af3fa110', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(295, 'LC-AEM-AD(Admitad)-CM-WL-PXFLW_069a2f88-235d-4aa9-8e92-289f7d665d54', 8, 21, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RLcdbcf5fa4d674d098b9727ca3f963ff0', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(296, 'LC-AEM-AD(Appsflyer)-CM-WL-PXFLW_smartscript', 8, 5, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RL177c226fee36459f88dc4252e42537c4', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(297, 'LC-AEM-AD(Boostiny)-CM-WL-PXFLW_samsungksa', 8, 23, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RL0fcf7ed7d74b4182ad71b901df442e77', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(298, 'LC-AEM-AD(Criteo)-CM-WL-PXFLW_87f45bb3-9941-464b-ab12-89ce888e0a94', 8, 17, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RL807174757a0b44cbadb9bad403e075db', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(299, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_690fb1cd-b453-4493-a2d8-baca4e7cad35', 8, 24, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RL93cc0309b6c04a2eb33ce67644e6a730', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(300, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_695ca934-c4c5-4f8a-b00b-8571a3414581', 8, 24, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RL19c7ed58dbef40e6a4ba4af4e6b7a147', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(301, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_8e549171-e3d2-4eff-8deb-4e69dd006d39', 8, 24, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RLd9b89b206a2e46b0b73faeb6aaeec5ff', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(302, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_e6c0c197-6169-44e1-a36b-de8556097fee', 8, 24, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RLdd8bf989db0b4418a5ba9bdd42dd10c7', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(303, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_f04bf817-ae1f-410a-8c23-355d299d24a7', 8, 24, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RL7f67ca0e89ea42f6a4e2e756a5ce7864', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(304, 'LC-AEM-AD(GoogleAdsRemarketing)-CM-WL-PXFLW_53f702e7-30d2-4b5f-a04a-5967f4291086', 8, 25, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RLe208c7e2512b43aab36d63fe28f4bc04', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(305, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_80a639c5-2a96-4910-a9a0-c2224408805f', 8, 15, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RL17c47f6579794f7f9f2e75db8319c246', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(306, 'LC-AEM-AD(Linkedin)-CM-WL-PXFLW_e78da698-604c-409c-8281-e753d61a821c', 8, 20, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RL9f816ea7083c45928f69c25cfcf68b9f', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(307, 'LC-AEM-AD(RTB)-CM-WL-PXFLW_f06bb1b0-681c-45e9-bb2b-22d7e2f6d178', 8, 14, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RL398a284dfa414691b988cf2d6238c1c0', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(308, 'LC-AEM-AD(Snapchat)-CM-WL-PXFLW_c466bd82-dbf4-4cce-b674-02dfbd6f2854', 8, 12, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RL4db94de2e4954451aa657eaa59149883', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(309, 'LC-AEM-AD(Teads)-CM-WL-PXFLW_d7b7dc14-6077-4bfe-b21b-479c521f279e', 8, 29, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RL9e3d758c628a4bc19d9ac40972f9bb7f', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(310, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_d4fae01f-624e-4a7b-8ae3-43450c11433e', 8, 13, NULL, 1, 1, '2026-06-23', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RLa02125f7b1cf473a86d0924e75476db9', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(311, 'LC-AEM-AD(Taboola)-CM-ALL(WL)-SDSME_2029101', 8, 30, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61d5f565065949ee998624876c792317/rules/RL7ed3196f32b9430e9d6cef764fef0de3', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(312, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_10009532', 9, 15, NULL, 1, 1, '2026-07-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRd8728de7d28842398b71295ca25d943d/rules/RLb52d3df958274ce18593f25d8ddd8b95', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(313, 'LC-Shop-AD(Teads)-CM-ALL(WL)-PXFLW_fd1dc663-74dd-4788-b296-6496ce69c8f6', 10, 29, NULL, 1, 1, '2026-07-16', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RL87c8b9948d2349a1a528f56aee668cc4', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(314, 'LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_0db0991a-87d9-4e8a-a713-28fee2447d8c', 10, 13, NULL, 1, 1, '2026-06-17', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RL160ba6bc8a194f1a8be2c21ce2569efa', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(315, 'LC-Shop-AD(Optimise)-CM-WL-SDSME_2361077-57144', 10, 2, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RLc1b54e9d4fa54fc8b89bd91fd97fd79a', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(316, 'LC-Shop-AD(Optimise)-OrderConfirmation-WL-SDSME_2361077-57144', 10, 2, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RL66d061cce83c47e7b91dd2c9e011f3f1', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(317, 'LC-Shop-AD(Taboola)-CM-ALL(WL)-SDSME_2029101', 10, 30, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRa99abcd88e334dc39052ab045fd84fd4/rules/RL8900db7858ce49548f09c4208f062b14', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(318, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_10009532', 11, 15, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR93185e03772e427aa40c9b187922f475/rules/RLb5a11cc58e97440eb9766093b45f70d8', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(319, 'LC-Shop-AD(Insider)-OrderConfirmation-CM-ALL(WL)-PXFLW_10009532', 11, 15, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR93185e03772e427aa40c9b187922f475/rules/RL93c80172239b49289bedf1b43bcb64e2', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(320, 'LC-Shop-AD(Admitad)-CM-ALL(WL)-PXFLW_80b09d97-9e7d-429c-a84a-cce155712b07', 12, 21, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RL6dbab1468184407584d03c3506e6730f', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-03 00:00:00', 0, NULL),
(321, 'LC-Shop-AD(Boostiny)-CM-ALL(WL)-PXFLW_samsungksa', 12, 23, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RLfbe8d98c9e32432d9bc575cb37e46754', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-03 00:00:00', 0, NULL),
(322, 'LC-Shop-AD(Boostiny)-CM-OrderConfirmation-ALL(WL)-PXFLW_samsungksa', 12, 23, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RL4cb5dd7c1cfc4c17b13bba5ea5dfeed7', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-03 00:00:00', 0, NULL),
(323, 'LC-Shop-AD(Criteo)-CM-ALL(WL)-PXFLW_0c38a6ff-4829-4b53-8bb5-9c42616df2f5', 12, 17, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RLc6171197f3484e8ba97f056c0e773c49', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-03 00:00:00', 0, NULL),
(324, 'LC-Shop-AD(GoogleAds)-Cart-ALL(WL)-PXFLW_2d0ae448-3f8f-49c8-8f83-35735dd0b981', 12, 24, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RLc7a23c858fe0465290da33701dad3c83', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 13:55:43', 0, '2026-06-03 00:00:00', 0, NULL),
(325, 'LC-Shop-AD(GoogleAds)-Cart-ALL(WL)-PXFLW_c4bdc2fb-0531-4a9d-8c8b-266c3bea6289', 12, 24, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RL53f8e680969d4fc095404de1143111c3', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 13:55:43', 0, '2026-06-03 00:00:00', 0, NULL),
(326, 'LC-Shop-AD(GoogleAds)-Cart-ALL(WL)-PXFLW_c5eec7f6-d14e-4246-947d-4d39ff72d27c', 12, 24, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RL1be4728d5dd74df9ae8e862d9a12a9c8', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 13:55:42', 0, '2026-06-03 00:00:00', 0, NULL),
(327, 'LC-Shop-AD(GoogleAds)-CM-ALL(WL)-PXFLW_88018911-edb3-416b-b729-5bd80aea031e', 12, 24, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RLe8ee926176fb4b5c9a1f22e76fa9ae9d', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 13:55:43', 0, '2026-06-03 00:00:00', 0, NULL),
(328, 'LC-Shop-AD(GoogleAds)-OrderConfirmation-ALL(WL)-PXFLW_0b26100a-8f03-4d35-8cec-c21c7ea13d41', 12, 24, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RL75301db64dde422e85f0d51d6218e367', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 13:55:43', 0, '2026-06-03 00:00:00', 0, NULL),
(329, 'LC-Shop-AD(GoogleAds)-OrderConfirmation-ALL(WL)-PXFLW_2bc8cb5f-db79-4119-ac23-1f5c992406f7', 12, 24, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RLc58b9898037c4c5b89d32af739f4f2ca', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 13:55:42', 0, '2026-06-03 00:00:00', 0, NULL),
(330, 'LC-Shop-AD(GoogleAds)-OrderConfirmation-ALL(WL)-PXFLW_48b8a601-4298-4d75-a340-9efe1b1072f5', 12, 24, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RLfa39e31831d344e79283e8d3eef7d8b3', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 13:55:43', 0, '2026-06-03 00:00:00', 0, NULL),
(331, 'LC-Shop-AD(GoogleAdsRemarketing)-CM-ALL(WL)-PXFLW_f4c206d0-5066-4f96-bdd6-6a2d95030faa', 12, 25, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RL487cc5eddd6243a9af60fdb1063b4586', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 13:55:43', 0, '2026-06-03 00:00:00', 0, NULL),
(332, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_864a73c6-865f-4056-838f-68f41f5a7c71', 12, 15, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RLf8e20f599b27486ab9840d703987fb2f', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-03 00:00:00', 0, NULL),
(333, 'LC-Shop-AD(Linkedin)-CM-ALL(WL)-PXFLW_ffee495a-b22d-4cb1-bae3-d922ff05a74c', 12, 20, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RLab00c926c9384f3cb822ce054f0784df', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-03 00:00:00', 0, NULL),
(334, 'LC-Shop-AD(RTB)-CM-ALL(WL)-PXFLW_1a5c788b-7eef-4c86-9225-54680849b814', 12, 14, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RL463dfd3fd3504525b810b39b14ab4fb8', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-03 00:00:00', 0, NULL),
(335, 'LC-Shop-AD(Snapchat)-CM-ALL(WL)-PXFLW_2a4e2688-326a-44cb-b2b4-8f6afb32a72e', 12, 12, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RLba0d196095954478b07b1ce2d0446601', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 16:32:49', 0, '2026-06-03 00:00:00', 0, NULL),
(336, 'LC-Shop-AD(Teads)-CM-ALL(WL)-PXFLW_3b3f44ca-c266-40f0-ad85-4c6d5f6654c1', 12, 29, NULL, 1, 1, '2026-07-16', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RLca93149ce6aa495089ad4925199bc740', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(337, 'LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_cf977755-e636-4c29-ad47-d4ae2596fb9b', 12, 13, NULL, 1, 1, '2026-07-16', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RL2f3ab4f16c2f416b9c3d2a6f338f65b1', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(338, 'LC-Shop-AD(Optimise)-CM-WL-SDSME_2361077-57144', 12, 2, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RL0172a7724b7a4cb090194493de7ee8ff', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(339, 'LC-Shop-AD(Optimise)-OrderConfirmation-WL-SDSME_2361077-57144', 12, 2, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RL36e27bfee3744ac49cde3c50b1e17a17', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(340, 'LC-Shop-AD(Taboola)-CM-ALL(WL)-SDSME_2029101', 12, 30, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc6bfd8cde63246ee8abe23e880a2ae2f/rules/RL67377c53c21f461998227654b5cdb9f4', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(341, 'LC-AEM-AD(GoogleDisplayVideo)-CM-WL-SDSME_ DC-9122577', 17, 31, NULL, 1, 1, '2026-07-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7defa9d2597c4d32803af3ed6c46f659/rules/RLce0cc2883c80405981b666da0b6bcb2b', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(342, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_1bad5529-d588-4445-9af7-de675b3af06f', 17, 15, NULL, 1, 1, '2026-07-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7defa9d2597c4d32803af3ed6c46f659/rules/RL87b3489852ba452aa2034513d069ee2e', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(343, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_unpacked_thankyou', 17, 15, NULL, 1, 1, '2026-07-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7defa9d2597c4d32803af3ed6c46f659/rules/RL4767516338f8425d99dd7544e13e2ab9', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(344, 'LC-AEM-AD(Linkedin)-CM-WL-SDSME_8a1b-77bb04f22864', 17, 20, NULL, 1, 1, '2026-07-05', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7defa9d2597c4d32803af3ed6c46f659/rules/RLa9e1367927cb421a9c8095e4e1e5510b', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(345, 'LC-AEM-AD(Outbrain)-CM-WL-PXFLW_696e06be-4498-4548-8b31-674cfb734b8a', 17, 32, NULL, 1, 1, '2026-07-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7defa9d2597c4d32803af3ed6c46f659/rules/RL49f45fd21799452aac8bc3d72a47ee07', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(346, 'LC-AEM-AD(RTB)-CM-WL-PXFLW_olNGh3PXAUrsHOS7qCor', 17, 14, NULL, 1, 1, '2026-07-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7defa9d2597c4d32803af3ed6c46f659/rules/RLd9d95d969d90423288ce66262dfd9b1e', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(347, 'LC-AEM-AD(RTB)-CM-WL-PXFLW_olNGh3PXAUrsHOS7qCor_cta', 17, 14, NULL, 1, 1, '2026-07-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7defa9d2597c4d32803af3ed6c46f659/rules/RLe7747a99f1124d12a7e5cc1ca9144a5d', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(348, 'LC-AEM-AD(Taboola)-CM-WL-PXFLW_7d3c1688-5cf5-4fd9-94b9-16ee79059ac0', 17, 30, NULL, 1, 1, '2026-07-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7defa9d2597c4d32803af3ed6c46f659/rules/RLe8a92b2b59ca4a1585c0a2b8bb9a5ec9', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(349, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_c7a452a8-a961-4a3d-9db1-4bc933cdda8e', 17, 13, NULL, 1, 1, '2026-07-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7defa9d2597c4d32803af3ed6c46f659/rules/RLdca7ee2f25c64218b75028487b70457f', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(350, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_10005179', 18, 15, NULL, 1, 1, '2026-06-19', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR02a11b1d20644c09b8b8c0a4f71b05b5/rules/RL8903e1b12c0b41de83d7720dc156210c', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(351, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_10005179', 19, 15, NULL, 1, 1, '2026-06-19', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR885b6775f5764d378cc8291cef919795/rules/RLc178234860c349248acdadb55ee6fd39', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(352, 'LC-Shop-AD(Insider)-OrderConfirmation-CM-ALL(WL)-PXFLW_10005179', 19, 15, NULL, 1, 1, '2026-06-19', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR885b6775f5764d378cc8291cef919795/rules/RL78c67fccac39467b8f403e943e8b6d8a', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(353, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_5062fa11-9597-4ddb-8ce8-7a5ddc04ad04', 20, 15, NULL, 1, 1, '2026-07-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR95e8380805264175be518bd10f5c2cda/rules/RL08a8e74dd1874dbcb0f816711c4fadf9', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(354, 'LC-Shop-AD(Outbrain)-CM-ALL(WL)-PXFLW_b218fdcc-1bb2-4876-ac79-caa662f4c689', 20, 32, NULL, 1, 1, '2026-07-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR95e8380805264175be518bd10f5c2cda/rules/RL84b1eb8fcee449749d9b28e868fc0226', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(355, 'LC-Shop-AD(RTB)-CM-ALL(WL)-PXFLW_olNGh3PXAUrsHOS7qCor', 20, 14, NULL, 1, 1, '2026-07-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR95e8380805264175be518bd10f5c2cda/rules/RL435b82fca8c74c6ab2333f977235c1e9', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(356, 'LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_42f5256b-cfad-4992-8c51-d7af8dab0b47', 20, 13, NULL, 1, 1, '2026-07-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR95e8380805264175be518bd10f5c2cda/rules/RLbae83b2eca324cf888a74c9159d76b24', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(357, 'LC-AEM-AD(CampaignManager)-CM-WL-PXFLW_156439ff-a094-493c-8706-3b13c9f73b54', 21, 19, NULL, 1, 1, '2026-07-12', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf20225ba053e4a5c9c9926fa991089ab/rules/RL15f64e38a819461db6aa45179fb1f0a9', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(358, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_85602960-e385-4a77-a5bb-4de4752a806f', 21, 24, NULL, 1, 1, '2026-07-12', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf20225ba053e4a5c9c9926fa991089ab/rules/RL9d13474889d9425b90ec838c47927636', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(359, 'LC-AEM-AD(Criteo)-CM-WL-SDSE-86725296-15a1-4a30-bf3f-aa85ef1287fc', 22, 17, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4507cc44d1e742498bc65fc391f1738f/rules/RLb7cc489b58d64d40a6c8c26a57800925', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(360, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_10007931', 22, 15, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4507cc44d1e742498bc65fc391f1738f/rules/RL66338166dd1e48b1819b25a56be7935a', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(361, 'LC-AEM-AD(Linkedin)-CM-WL-PXFLW_3841306', 22, 20, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4507cc44d1e742498bc65fc391f1738f/rules/RLe282ef4c111e49019927a4b47405a8f6', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(362, 'LC-AEM-AD(Snapchat)-CM-WL-PXFLW_8ee835f2-bddd-44f1-87e2-8ff87d0bd6be', 22, 12, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4507cc44d1e742498bc65fc391f1738f/rules/RL78c6b18e031e44249b5e176eff94898c', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(363, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_om2pf', 22, 13, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4507cc44d1e742498bc65fc391f1738f/rules/RLdda83b088bb249a0b3e99e992c9a2640', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(364, 'LC-AEM-AD(TradeDesk)-CM-WL-SDSME_89bd-32b132b23438', 22, 33, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4507cc44d1e742498bc65fc391f1738f/rules/RLc23b78eeb9bb4f0e94d9e721d7c681e5', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(365, 'LC-AEM-AD(RTB)-CM-ALL(WL)-SDSME_8b79-e3c381a104fc', 22, 14, NULL, 1, 1, '2026-06-25', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4507cc44d1e742498bc65fc391f1738f/rules/RLdb4f174b35d64c569824783b298143d0', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(366, 'LC-AEM-AD(Criteo)-CM-WL-SDSE-d3ab4b40-a792-411f-9828-d234de7a2613', 23, 17, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR46f04dd062074bb2a482e2b7e9b132fe/rules/RLe10aed57e7c04e11ac82c0676cba5a4b', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(367, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_10007931', 23, 15, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR46f04dd062074bb2a482e2b7e9b132fe/rules/RL0e4589680c754d58b3055380f396158a', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(368, 'LC-AEM-AD(Snapchat)-CM-WL-PXFLW_8ee835f2-bddd-44f1-87e2-8ff87d0bd6be', 23, 12, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR46f04dd062074bb2a482e2b7e9b132fe/rules/RL4bfd9e2388684e678c684ec54b3d5be0', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(369, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_om2pf', 23, 13, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR46f04dd062074bb2a482e2b7e9b132fe/rules/RLf95c1be483554b61a3d491a132a9ebed', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(370, 'LC-AEM-AD(TradeDesk)-CM-WL-SDSME_89bd-32b132b23438', 23, 33, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR46f04dd062074bb2a482e2b7e9b132fe/rules/RL123d87cb45d34888b4180d8c3ab41fff', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(371, 'LC-AEM-AD(Snapchat)-CM-WL-PXFLW_8ee835f2-bddd-44f1-87e2-8ff87d0bd6be', 24, 12, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRab3523e662044e63973ec19de6e82e6d/rules/RL4c66ef812ec04addb5a3a74aee07c831', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(372, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_om2pc', 24, 13, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRab3523e662044e63973ec19de6e82e6d/rules/RL46a9df6da86c44a5a3bb5cf0513da4c1', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(373, 'LC-AEM-AD(TradeDesk)-CM-WL-SDSME_93bd-3be5e43aa9c6', 24, 33, NULL, 1, 1, '2026-07-10', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRab3523e662044e63973ec19de6e82e6d/rules/RL34e36a1939b345b5896197b8c091f3d6', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(374, 'LC-AEM-AD(Criteo)-CM-WL-SDSE-d3ab4b40-a792-411f-9828-d234de7a2613', 25, 17, NULL, 1, 1, '2026-07-17', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules/RL22196744c7164f61b03e638eeb9fdb23', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(375, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_28844842-d612-4050-9aa7-1bd5ae17a3d8', 25, 24, NULL, 1, 1, '2026-07-17', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules/RL734181b4df3f47ee85f174217f464278', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(376, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_4c97a6de-3f7a-497f-8830-77bcd2ff07da', 25, 24, NULL, 1, 1, '2026-07-17', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules/RLa94d96d9130a4cfab67b874728295dd9', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(377, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_4eed3cad-48bd-4157-ae08-4056aca5a742', 25, 24, NULL, 1, 1, '2026-07-17', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules/RLd650d3a13c174c7e982a021da4749e59', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(378, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_8803aea4-066e-4312-aef9-0d9830d6741b', 25, 15, NULL, 1, 1, '2026-07-17', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules/RLceb2e6b6ec674c3fa3dd55017caba63c', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(379, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_unpacked_thankyou', 25, 15, NULL, 1, 1, '2026-07-17', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules/RL910e063400174984826ec067d599c47c', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(380, 'LC-AEM-AD(Linkedin)-CM-WL-PXFLW_3841306', 25, 20, NULL, 1, 1, '2026-07-17', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules/RL8e2382fdd2724760806097e2b7f55532', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(381, 'LC-AEM-AD(Snapchat)-CM-WL-PXFLW_df4013d0-bd28-4cd0-9081-9f7197059957', 25, 12, NULL, 1, 1, '2026-07-13', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules/RL51200e048e414922a3c15008db1a184c', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(382, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_33d17c4b-40b9-4d5c-b0dd-64bc7e8bca3f', 25, 13, NULL, 1, 1, '2026-07-13', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules/RL8a391cd2e3ed4e68875b1b5eebe66bd8', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(383, 'LC-AEM-AD(RTB)-CM-ALL(WL)-SDSME_af47-ea652e13577e', 25, 14, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules/RLd39bd1a2501341d2aad8ea3e0d4fc27b', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(384, 'LC-AEM-AD(Pinterest)-CM-WL-SDSME_2613638179718', 25, 7, NULL, 1, 1, '2026-07-07', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules/RL830aa4af473b425a92b614895bc51edd', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(385, 'LC-AEM-AD(Criteo)-CM-WL-SDSE-d3ab4b40-a792-411f-9828-d234de7a2613', 26, 17, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR08b2de963cc24f5cb79e01f819fd90c0/rules/RLbb8d64dd429a421f901d9257fe337f88', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(386, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_5edf110a-d389-4a96-bc81-fcc725a649ed', 26, 24, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR08b2de963cc24f5cb79e01f819fd90c0/rules/RLddc77cefcfa640eca3ce2e8acb5fbf25', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(387, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_9e0ea0c8-8c78-45c5-95a7-f135e082721a', 26, 24, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR08b2de963cc24f5cb79e01f819fd90c0/rules/RL62289bca3d4444b3acbebae8ff3be26f', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(388, 'LC-AEM-AD(GoogleAds)-CM-WL-PXFLW_9f78926a-b496-442f-abb6-8e3babe09dd5', 26, 24, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR08b2de963cc24f5cb79e01f819fd90c0/rules/RL8743adb8365e4143bd1afd6b0cb8faa5', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(389, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_42ddff30-e13e-4b9c-9653-7e1cffc49422', 26, 15, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR08b2de963cc24f5cb79e01f819fd90c0/rules/RL2a2663821f904e7698a1d112b8ab085c', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(390, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_unpacked_thankyou', 26, 15, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR08b2de963cc24f5cb79e01f819fd90c0/rules/RL1ab262cded1649b69afb9f21dc8b0b01', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(391, 'LC-AEM-AD(Snapchat)-CM-WL-PXFLW_7c77cdb8-7803-42c4-8f07-3a93f98bc11d', 26, 12, NULL, 1, 1, '2026-07-13', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR08b2de963cc24f5cb79e01f819fd90c0/rules/RLb2a4943f76ec4a319889e5eba2838a2f', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(392, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_c22271ae-762a-4a3f-b557-e47681dfae5a', 26, 13, NULL, 1, 1, '2026-07-13', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR08b2de963cc24f5cb79e01f819fd90c0/rules/RL62c0ebde359d4ba28e7aa355628ae9ef', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL);
INSERT INTO `tags` (`id`, `name`, `property_id`, `vendor_id`, `conditions`, `status`, `is_active`, `al_end_date`, `requested_date`, `ref_link`, `requester_name`, `requester_email`, `requester_organization`, `new_tag_request`, `new_rule_request`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(393, 'LC-AEM-AD(RTB)-CM-ALL(WL)-SDSME_ab10-62118729eba5', 26, 14, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR08b2de963cc24f5cb79e01f819fd90c0/rules/RLbe890a97adfc44cb8402a6bed8aaef5a', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(394, 'LC-AEM-AD(Pinterest)-CM-WL-SDSME_2613638179718', 26, 7, NULL, 1, 1, '2026-07-07', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR08b2de963cc24f5cb79e01f819fd90c0/rules/RL024dc9f66e444aa09124ca07f5561a2a', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(395, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_10007931', 27, 15, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR970ae49668d54b5bbc55d74e54d01f88/rules/RLb4a57e03ee814d66a4580de361a8db47', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(396, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_10009531', 28, 15, NULL, 1, 1, '2026-06-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR64613b01294b47b58da5e58992960773/rules/RL431d6262489443d1877e3ff6a43cd5d5', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(397, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_10007931', 29, 15, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7afe3c131c6d4beb8bd2cf65fcd5d5bb/rules/RLbffe2c0648de490094600a605991d7be', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(398, 'LC-Shop-AD(Insider)-OrderConfirmation-CM-ALL(WL)-PXFLW_10007931', 29, 15, NULL, 1, 1, '2026-07-11', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR7afe3c131c6d4beb8bd2cf65fcd5d5bb/rules/RL717b3624166b4780944d9eb33d050f41', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(399, 'LC-Shop-AD(Criteo)-CM-WL-SDSE-86725296-15a1-4a30-bf3f-aa85ef1287fc', 30, 17, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules/RL6e06a9d6e51c4a6f99fb3e44b71010cd', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(400, 'LC-Shop-AD(Facebook)-CM-ALL(WL)-PXFLW_1041328750781537', 30, 34, NULL, 1, 1, '2026-06-30', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules/RL4b1b0998bf4a43529135ef29f413870b', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(401, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_10007931', 30, 15, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules/RL5c428aaabbf14abfb03c27a44f71406b', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(402, 'LC-Shop-AD(Linkedin)-CM-WL-PXFLW_3841306', 30, 20, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules/RLd5eb8a0664c148f4b0570900eb52c7fa', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(403, 'LC-Shop-AD(Linkedin)-OrderConfirmation-ALL(WL)-PXFLW_3841306_17849650', 30, 20, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules/RL1fdd02610b634fbd80aa1a758078f912', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(404, 'LC-Shop-AD(Snapchat)-CM-ALL(WL)-PXFLW_8ee835f2-bddd-44f1-87e2-8ff87d0bd6be', 30, 12, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules/RL5daf2dff366241d98db2910aa1d983c6', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(405, 'LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_om2pf', 30, 13, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules/RLc108582b428842e6aa23730997909ba1', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(406, 'LC-Shop-AD(TradeDesk)-OrderConfirmation-WL-SDSME_89bd-32b132b23438', 30, 33, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules/RL930883fe961a48b6bca8ae04539b9fc2', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(407, 'LC-Shop-AD(ArabyAds)-OrderConfirmation-ALL(WL)-SDSME_adb2-bae5afdd9e4d', 30, 35, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules/RL70f314b933e94e4086d6b95094ee5029', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(408, 'LC-Shop-AD(RTB)-CM-ALL(WL)-SDSME_8b79-e3c381a104fc', 30, 14, NULL, 1, 1, '2026-06-25', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules/RL83fd6494aa914c2d8a10ff3f9afa7ecf/ruleComponent/RCe4eb03f9c43440e6a8190848f067201f', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(409, 'LC-Shop-AD(Criteo)-CM-WL-SDSE-d3ab4b40-a792-411f-9828-d234de7a2613', 31, 17, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRb2f83dfd81be410abe16709dbf56b08c/rules/RL68642a9b4088478c9539996829333914', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(410, 'LC-Shop-AD(Facebook)-CM-ALL(WL)-PXFLW_1041328750781537', 31, 34, NULL, 1, 1, '2026-06-30', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRb2f83dfd81be410abe16709dbf56b08c/rules/RLb9b4425bfdd347ddb2c2638e931c5090', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(411, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_10007931', 31, 15, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRb2f83dfd81be410abe16709dbf56b08c/rules/RL29d2da58c8d441399a69711ccfcb92b4', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(412, 'LC-Shop-AD(Snapchat)-CM-ALL(WL)-PXFLW_8ee835f2-bddd-44f1-87e2-8ff87d0bd6be', 31, 12, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRb2f83dfd81be410abe16709dbf56b08c/rules/RL14fbf9d630224548855d49435f4db6c7', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(413, 'LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_om2pf', 31, 13, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRb2f83dfd81be410abe16709dbf56b08c/rules/RLec26df502a954680888e40996d742723', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(414, 'LC-Shop-AD(Criteo)-CM-WL-SDSE-80fd6d60-f938-4283-9057-98f76c441806', 32, 17, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf28c4faf02284603896219ef6cb42fa2/rules/RL9458adfe7e724d31b4f3bd1722037720', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(415, 'LC-Shop-AD(Facebook)-CM-WL-SDSME_1041328750781537', 32, 34, NULL, 1, 1, '2026-06-30', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf28c4faf02284603896219ef6cb42fa2/rules/RL377a96b0fbb645828420582915621ebc', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(416, 'LC-Shop-AD(GoogleAds)-CM-ALL(WL)-PXFLW_27409d91-ff4c-4664-958b-06faa61327ae', 32, 24, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf28c4faf02284603896219ef6cb42fa2/rules/RL7cfe7414577449a49a72a4cceba7f303', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(417, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_ba63cf6f-1a88-4df1-a53d-9494724e2588', 32, 15, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf28c4faf02284603896219ef6cb42fa2/rules/RL5d7c478c4baa49d79ebeb0688adc3f73', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(418, 'LC-Shop-AD(Snapchat)-CM-ALL(WL)-PXFLW_ac312ab7-7b52-4146-b47c-a0ec8c822364', 32, 12, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf28c4faf02284603896219ef6cb42fa2/rules/RLcbc5e8953be44186ba0266e35b302863', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(419, 'LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_c870a91b-7dcb-4048-99cb-c787a7be871b', 32, 13, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf28c4faf02284603896219ef6cb42fa2/rules/RL92a812387b4e4ab79a37341b237f1732', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(420, 'LC-Shop-AD(RTB)-CM-ALL(WL)-SDSME_af47-ea652e13577e', 32, 14, NULL, 1, 1, '2026-06-25', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf28c4faf02284603896219ef6cb42fa2/rules/RLb70c5ea8594647efa9664320f8376602', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(421, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_10009531', 33, 15, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR2fe2ecc0f2664d599cbb5e58edacbdff/rules/RLac307f1c1d7943c7aa999fce39d37190', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 16:50:22', 0, '2026-06-03 00:00:00', 0, NULL),
(422, 'LC-Shop-AD(Insider)-OrderConfirmation-CM-ALL(WL)-PXFLW_10009531', 33, 15, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR2fe2ecc0f2664d599cbb5e58edacbdff/rules/RL68e283715adf464184529939beb26f23', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 16:50:22', 0, '2026-06-03 00:00:00', 0, NULL),
(423, 'LC-Shop-AD(Criteo)-CM-WL-SDSE-d3ab4b40-a792-411f-9828-d234de7a2613', 34, 17, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules/RLa2b7f89ee3fb46d6a05f90b2f6839c45', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(424, 'LC-Shop-AD(Facebook)-CM-WL-SDSME_1041328750781537', 34, 34, NULL, 1, 1, '2026-06-30', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules/RL64bcc560afd543a981638841cb9fa23f', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(425, 'LC-Shop-AD(GoogleAds)-CM-ALL(WL)-PXFLW_11b837a8-398f-4a7d-8e8f-59676c19c2f8', 34, 24, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules/RL89b781762c264f7697abdfc69a2c3be7', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(426, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_4c944a80-7385-498c-8a9f-fc9acbd9bd50', 34, 15, NULL, 1, 1, '2026-07-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules/RL8f02a5712067422c8596a39d6295c3d6', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(427, 'LC-Shop-AD(Linkedin)-CM-WL-PXFLW_3841306', 34, 20, '(.*)', 8, 1, '2026-06-13', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules/RL6433eee471804bc694e548d3a815383f', 'XLSX', '', 0, NULL, NULL, 1, '2026-06-04 16:52:05', 0, '2026-06-03 00:00:00', 0, NULL),
(428, 'LC-Shop-AD(Linkedin)-OrderConfirmation-ALL(WL)-PXFLW_3841306_17849658', 34, 20, '(.*orderConfirmation)', 8, 1, '2026-06-13', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules/RLa958f568d7374a41bff9fd67b5e5c682', 'XLSX', '', 0, NULL, NULL, 1, '2026-06-04 16:52:32', 0, '2026-06-03 00:00:00', 0, NULL),
(429, 'LC-Shop-AD(Snapchat)-CM-ALL(WL)-PXFLW_f46e7a8e-aea5-4fe5-b51c-9d2a9aec617e', 34, 12, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules/RL3ade10dfedc2436cafbdec796390e07a', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 16:50:22', 0, '2026-06-03 00:00:00', 0, NULL),
(430, 'LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_22dd3ed6-ad89-40ff-ae18-eb3df2450499', 34, 13, NULL, 4, 1, '2026-06-13', '2026-08-13', 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules/RL9ea42f15509241cda3d5b48f8ee60adf', 'XLSX', NULL, 0, NULL, NULL, 1, '2026-06-04 16:50:22', 0, '2026-06-03 00:00:00', 0, NULL),
(431, 'LC-Shop-AD(TradeDesk)-OrderConfirmation-WL-SDSME_bda0-3ca607c76215', 34, 33, '(.*orderConfirmation)', 8, 1, '2026-06-13', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules/RLb6917485efa4464d991d437fb5ae1c90', 'XLSX', '', 0, NULL, NULL, 1, '2026-06-04 16:52:41', 0, '2026-06-03 00:00:00', 0, NULL),
(432, 'LC-Shop-AD(ArabyAds)-OrderConfirmation-ALL(WL)-SDSME_8aae-90c924f80f3f', 34, 35, '(.*orderConfirmation)', 8, 1, '2026-06-13', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules/RL880411a3eced447183e02df654ca35a9', 'XLSX', '', 0, NULL, NULL, 1, '2026-06-04 16:52:22', 0, '2026-06-03 00:00:00', 0, NULL),
(433, 'LC-Shop-AD(RTB)-CM-ALL(WL)-SDSME_ab10-62118729eba5', 34, 14, NULL, 1, 1, '2026-06-25', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules/RLf2d085188576435e9189e53f2735fa16', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(434, 'LC-AEM-AD(Admitad)-CM-WL-PXFLW_75ce04e9-8885-4726-ae2b-a1ecbcbf6ed6', 35, 21, NULL, 1, 1, '2026-06-13', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc4ea06001baa496a85af80306c73dbb6/rules/RLb67af03403f848d0baca5c140f7540fe', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(435, 'LC-AEM-AD(Boostiny)-CM-WL-PXFLW_samsungegypt', 35, 23, NULL, 1, 1, '2026-06-13', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc4ea06001baa496a85af80306c73dbb6/rules/RL4c7a8c8ac7044e6dba927bf3a600f337', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(436, 'LC-AEM-AD(Criteo)-CM-WL-PXFLW_64a9dac0-ca08-4907-b953-7c027d2cdec7', 35, 17, NULL, 1, 1, '2026-06-13', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc4ea06001baa496a85af80306c73dbb6/rules/RLeacff4e53e8b4259bd9245f1eb7366f9', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(437, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_5c04c411-ad97-4119-8047-49d0fdb65b49', 35, 15, NULL, 1, 1, '2026-06-13', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc4ea06001baa496a85af80306c73dbb6/rules/RLd747c7f4fa52414a95ba031c246ee76a', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(438, 'LC-AEM-AD(Snapchat)-CM-WL-PXFLW_88f8c1c2-8507-429f-b95c-77f0941c235a', 35, 12, NULL, 1, 1, '2026-06-13', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc4ea06001baa496a85af80306c73dbb6/rules/RLc89cbf5745bd497e9313632a75d1020e', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(439, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_9d540b5e-337d-4e81-bb87-ea4df077afe7', 35, 13, NULL, 1, 1, '2026-06-13', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc4ea06001baa496a85af80306c73dbb6/rules/RLf5c5f1b86198405fa9c8dfcd8df5177d', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(440, 'LC-AEM-AD(WaffarX)-CM-WL-PXFLW_aeb545e8-0985-4311-8bf9-de8e1e618b82', 35, 36, NULL, 1, 1, '2026-06-13', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRc4ea06001baa496a85af80306c73dbb6/rules/RLb754bd14ea6b48a5bb512836989f3ff9', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(441, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_10005175', 36, 15, NULL, 1, 1, '2026-06-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR829b8f02ee5d4a5dbc9e816e94fb4b28/rules/RL540b64fbc8c849c990719be1fd2c3211', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(442, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_10005175', 37, 15, NULL, 1, 1, '2026-06-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR975ae08dc0d54ef8988861815a84d11f/rules/RL05e2e73a03bf48ffa37e88abb0f3da8d', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(443, 'LC-Shop-AD(Insider)-OrderConfirmation-CM-ALL(WL)-PXFLW_10005175', 37, 15, NULL, 1, 1, '2026-06-18', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR975ae08dc0d54ef8988861815a84d11f/rules/RL8e084ca349154d19aa35bfe3063701a5', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(444, 'LC-Shop-AD(Admitad)-CM-ALL(WL)-PXFLW_870c02b9-c208-4b71-b9b9-231b8e0533da', 38, 21, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR72c3c4d394474dd0aaf703dba19ff998/rules/RL4726ea1b11e24bda991c73796afb8e3f', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(445, 'LC-Shop-AD(Boostiny)-CM-ALL(WL)-PXFLW_samsungegypt', 38, 23, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR72c3c4d394474dd0aaf703dba19ff998/rules/RL103ada7381e04c25a7cb3e459a7912ad', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(446, 'LC-Shop-AD(Criteo)-CM-ALL(WL)-PXFLW_88d45703-b9d6-4cd8-8ca5-20b5dd32f64d', 38, 17, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR72c3c4d394474dd0aaf703dba19ff998/rules/RL03e6944717c142d7b0a5c3ebee167a21', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(447, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_0a953308-743e-45ee-929e-5328f0aacd92', 38, 15, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR72c3c4d394474dd0aaf703dba19ff998/rules/RL02a3f7a6c5c443b6955310d36f31aada', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(448, 'LC-Shop-AD(Snapchat)-CM-ALL(WL)-PXFLW_39c7b603-584e-4da5-a333-cb3320508803', 38, 12, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR72c3c4d394474dd0aaf703dba19ff998/rules/RLd07c1355c1de4b26997b03835827e9a4', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(449, 'LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_b9a266f5-f246-4070-8775-3efabd04d029', 38, 13, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR72c3c4d394474dd0aaf703dba19ff998/rules/RLcdb10a177b8a4fa39b2dfd6c8cddca7b', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(450, 'LC-Shop-AD(WaffarX)-CM-ALL(WL)-PXFLW_aeb545e8-0985-4311-8bf9-de8e1e618b82', 38, 36, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR72c3c4d394474dd0aaf703dba19ff998/rules/RL49c82330781049a8bd1e0ce9416936a2', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(451, 'LC-AEM-AD(Admitad)-CM-WL-PXFLW_6af4f358-6ba8-4f4f-b1ae-9b7674fa3989', 39, 21, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRaf00914f9c194739b6e98de4b0f72380/rules/RL437eec5ad9d44c7890e33871226e31e8', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(452, 'LC-AEM-AD(Criteo)-CM-WL-PXFLW_36672c60-b8eb-43c9-a683-b736943422b2', 39, 17, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRaf00914f9c194739b6e98de4b0f72380/rules/RL525a14da087f42798ee5fcdcfb0fbb1a', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(453, 'LC-AEM-AD(ESKIMI)-CM-WL-PXFLW_11026', 39, 37, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRaf00914f9c194739b6e98de4b0f72380/rules/RL0dba12a194d840dfa737ccab7502c1b8', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(454, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_2c65a968-81e6-4f73-a9d8-fa4a44ae52e5', 39, 15, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRaf00914f9c194739b6e98de4b0f72380/rules/RL491b538fdf3948158c4b8e79a91ebf29', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(455, 'LC-AEM-AD(Pinterest)-CM-WL-SDSME_b30d-6151e5ac7f5b', 39, 7, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRaf00914f9c194739b6e98de4b0f72380/rules/RL3f29ca410a9148fa836f4f5a8f67cefe', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(456, 'LC-AEM-AD(Snapchat)-CM-WL-SDSME_8d589cf5-50e2-4f17-a5fb-05446817bf40', 39, 12, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRaf00914f9c194739b6e98de4b0f72380/rules/RLe86e6a2a798b4bb2a94352188861b03e', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(457, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_5728e60e-9761-43db-949a-e15746f2544d', 39, 13, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRaf00914f9c194739b6e98de4b0f72380/rules/RLeda6dd2c57544853bcf701bb6c4f05fb', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(458, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_10009533', 40, 15, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR87ddb0fd7d0e4611ab2557083595ce20/rules/RL067c9fba3ed14c4d9b4e66c14805f223', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(459, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_10009533', 41, 15, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR1f72db8a7d5c49a287d913b941391d80/rules/RLf579f86b529241b299d7f4d8a225e44a', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(460, 'LC-Shop-AD(Insider)-OrderConfirmation-CM-ALL(WL)-PXFLW_10009533', 41, 15, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR1f72db8a7d5c49a287d913b941391d80/rules/RL6fd5a48f08d448a187058d26d31e7fa4', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(461, 'LC-Shop-AD(Admitad)-CM-ALL(WL)-PXFLW_870c02b9-c208-4b71-b9b9-231b8e0533da', 42, 21, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61cef25ff4eb400391e974558ec4c577/rules/RL36b2565e2a4b4f619e6b764fb000dcb3', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(462, 'LC-Shop-AD(Criteo)-CM-ALL(WL)-PXFLW_b287793a-db39-40a5-b93d-ac9156a4c14c', 42, 17, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61cef25ff4eb400391e974558ec4c577/rules/RL0b3c400fb2c544d0879d0ddc22984039', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(463, 'LC-Shop-AD(ESKIMI)-CM-WL-PXFLW_11026', 42, 37, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61cef25ff4eb400391e974558ec4c577/rules/RLabb4d2b37a4440d4b5e82e01a6655de9', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(464, 'LC-Shop-AD(ESKIMI)-OrderConfirmation-CM-ALL(WL)-PXFLW_11026', 42, 37, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61cef25ff4eb400391e974558ec4c577/rules/RL5b2bc16918f9464b8cccd1e54a526356', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(465, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_42a1b212-7702-437b-9c3a-1b63c4a13e1e', 42, 15, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61cef25ff4eb400391e974558ec4c577/rules/RL7bb0fd48575f465f81220f6345e214d0', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(466, 'LC-Shop-AD(Pinterest)-Cart-WL-SDSME_b30d-6151e5ac7f5b', 42, 7, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61cef25ff4eb400391e974558ec4c577/rules/RLa5f575d2d029435e82aabf615edc3562', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(467, 'LC-Shop-AD(Pinterest)-OrderConfirmation-WL-SDSME_b30d-6151e5ac7f5b', 42, 7, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61cef25ff4eb400391e974558ec4c577/rules/RLf77622348db3462ba78daab8590a66bb', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(468, 'LC-Shop-AD(Snapchat)-CM-ALL(WL)-SDSME_8d589cf5-50e2-4f17-a5fb-05446817bf40', 42, 12, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61cef25ff4eb400391e974558ec4c577/rules/RL0ca14ee943424227bdb21a7460905e49', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(469, 'LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_d635a70d-11a6-463f-a9ae-4092ef892e2a', 42, 13, NULL, 1, 1, '2026-06-27', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR61cef25ff4eb400391e974558ec4c577/rules/RL9d4a2becf74d4c24b2d5c4fa3cbf30cf', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(470, 'LC-AEM-AD(CampaignManager)-CM-WL-PXFLW_12f5d51b-853e-48c9-bda1-046fbc020435', 43, 19, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4a4d53f5c57b4611a653d094fa1c523c/rules/RL95941f298df348579aad1a161ca868ac', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(471, 'LC-AEM-AD(Insider)-CM-WL-PXFLW_21852f80-97a9-435e-9f6a-6d0881b66860', 43, 15, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4a4d53f5c57b4611a653d094fa1c523c/rules/RL6191a639d7a749c5b4cc7acd7ad6ab96', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(472, 'LC-AEM-AD(Snapchat)-CM-WL-SDSME_9252-e2229c55af47', 43, 12, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4a4d53f5c57b4611a653d094fa1c523c/rules/RLae9155a66f0e4e65817d247a7b37490b', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(473, 'LC-AEM-AD(Twitter)-CM-WL-PXFLW_8884f960-e86b-46a4-83b4-769e84ab5992', 43, 13, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4a4d53f5c57b4611a653d094fa1c523c/rules/RL9d27e192af1d4e239a612715bc2ac87e', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(474, 'LC-Shop-AD(Bambuser)-OrderConfirmation-ALL(WL)-PXFLW_e00a62f0-ff5b-4d19-bb5e-5ea15251f030', 46, 38, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRfc76c7fe018d41e09006f4c3c0925588/rules/RL5d89029c761a4d1b95643aea4cf0ea63', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(475, 'LC-Shop-AD(CampaignManager)-CM-ALL(WL)-PXFLW_dbbfeea8-6615-4ef7-8abb-fe976522c6e6', 46, 19, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRfc76c7fe018d41e09006f4c3c0925588/rules/RL9e962185ddef4a49890d63b4a3a4491c', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(476, 'LC-Shop-AD(Insider)-CM-ALL(WL)-PXFLW_46a5dedd-71b9-42fc-87bb-c72845cd4757', 46, 15, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRfc76c7fe018d41e09006f4c3c0925588/rules/RL5bef4dd14d564721acab1312c1371ffc', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(477, 'LC-Shop-AD(Snapchat)-CM-ALL(WL)-SDSME_9252-e2229c55af47', 46, 12, NULL, 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRfc76c7fe018d41e09006f4c3c0925588/rules/RL8e60c447d8274acfa767d9c9b7fe7fa2', 'XLSX', NULL, 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(478, 'LC-Shop-AD(Twitter)-CM-ALL(WL)-PXFLW_297bdf64-4e58-4ffa-b133-109263411e50', 46, 13, '(.*)', 1, 1, '2026-06-26', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRfc76c7fe018d41e09006f4c3c0925588/rules/RLc5dfcd26d55d4546bbb3ef9246de4819', 'XLSX', '', 0, NULL, NULL, 0, '2026-06-03 17:41:58', 0, '2026-06-03 00:00:00', 0, NULL),
(479, 'LC-Shop-AD(RTBHouse)-Cart-WL-SDSME_ab10-62118729eba5', 34, 4, '^shop cart$', 7, 1, '2026-08-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules/RL0eda6516de614aa1986c6152ea780921', 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, NULL, NULL, 1, '2026-06-05 10:42:00', 1, '2026-06-04 14:16:01', 0, NULL),
(480, 'LC-Shop-AD(RTBHouse)-OrderConfirmation-WL-SDSME_ab10-62118729eba5', 34, 4, '^shop order confirmation$', 7, 1, '2026-08-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR0315cee1ecf24fdbb20b7f06b67e47f8/rules/RLe6635f7918cc46a5b80ee34d23b73a14', 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, NULL, NULL, 1, '2026-06-05 10:42:00', 1, '2026-06-04 14:16:01', 0, NULL),
(481, 'LC-Shop-AD(RTBHouse)-OrderConfirmation-WL-SDSME_af47-ea652e13577e', 32, 4, '^shop order confirmation$', 7, 1, '2026-08-04', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf28c4faf02284603896219ef6cb42fa2/rules/RL65b6da450d2d4977967c854b4e08ceee', 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, NULL, NULL, 1, '2026-06-05 11:00:58', 1, '2026-06-04 14:17:02', 0, NULL),
(482, 'LC-Shop-AD(RTBHouse)-Cart-WL-SDSME_af47-ea652e13577e', 32, 4, '^shop cart$', 7, 1, '2026-08-04', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRf28c4faf02284603896219ef6cb42fa2/rules/RL24a5e2ea7ae84149b076e463094c5d23', 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, NULL, NULL, 1, '2026-06-05 11:00:58', 1, '2026-06-04 14:17:02', 0, NULL),
(483, 'LC-Shop-AD(RTBHouse)-Cart-WL-SDSME_8b79-e3c381a104fc', 30, 4, '^shop cart$', 7, 1, '2026-08-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules/RL41bd595f76734c60bb5d1343e75069e6', 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, NULL, NULL, 1, '2026-06-05 13:57:26', 1, '2026-06-05 13:06:07', 0, NULL),
(484, 'LC-Shop-AD(RTBHouse)-OrderConfirmation-WL-SDSME_8b79-e3c381a104fc', 30, 4, '^shop order confirmation$', 7, 1, '2026-08-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR78212aaddf9148dd800cfcb0a89970b0/rules/RL51c65bbeaa734547b17ad7c3ece77479', 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, NULL, NULL, 1, '2026-06-05 13:57:26', 1, '2026-06-05 13:06:07', 0, NULL),
(485, 'LC-AEM-AD(RTBHouse)-Cart-ATC-SDSME_8b79-e3c381a104fc', 22, 4, '[an-la=\"add-on:continue\"], [an-tr=\"bridge-flagship pdp-add item-option_click\"], [canaddtocart=\"true\"], [data-cart=\"true\"]', 7, 1, '2026-08-05', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4507cc44d1e742498bc65fc391f1738f/rules/RL2d14bd7649a244dbb1296886d75fa159', 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, NULL, NULL, 1, '2026-06-05 14:45:11', 1, '2026-06-05 13:56:53', 0, NULL),
(486, 'LC-AEM-AD(RTBHouse)-ViewContent-WL-SDSME_8b79-e3c381a104fc', 22, 4, '^product detail$|^flagship pdp$', 7, 1, '2026-08-05', NULL, 'https://experience.adobe.com/#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR4507cc44d1e742498bc65fc391f1738f/rules/RL511f3539e3604040b2b6574d3c7f2580', 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, NULL, NULL, 1, '2026-06-05 14:46:11', 1, '2026-06-05 13:56:53', 0, NULL),
(487, 'LC-AEM-AD(RTBHouse)-Cart-ATC-SDSME_af47-ea652e13577e', 25, 4, '[an-la=\"add-on:continue\"], [an-tr=\"bridge-flagship pdp-add item-option_click\"], [canaddtocart=\"true\"], [data-cart=\"true\"]', 7, 1, '2026-08-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules/RLe56735d35ee247d09de144773b1466c3', 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, NULL, NULL, 1, '2026-06-05 16:04:19', 1, '2026-06-05 15:11:44', 0, NULL),
(488, 'LC-AEM-AD(RTBHouse)-ViewContent-WL-SDSME_af47-ea652e13577e', 25, 4, '^product detail$|^flagship pdp$', 7, 1, '2026-08-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PRed5e2b18abc34f02bfd5d01fce4152b7/rules/RL13ddacc3c2fc4496b326f52541fcf0c0', 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, NULL, NULL, 1, '2026-06-05 16:04:42', 1, '2026-06-05 15:11:44', 0, NULL),
(489, 'LC-AEM-AD(RTBHouse)-Cart-ATC-SDSME_ab10-62118729eba5', 26, 4, '[an-la=\"add-on:continue\"], [an-tr=\"bridge-flagship pdp-add item-option_click\"], [canaddtocart=\"true\"], [data-cart=\"true\"]', 7, 1, '2026-08-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR08b2de963cc24f5cb79e01f819fd90c0/rules/RL2096366b21964ef2b01e3791f162ccad', 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, NULL, NULL, 1, '2026-06-05 16:39:14', 1, '2026-06-05 16:08:27', 0, NULL),
(490, 'LC-AEM-AD(RTBHouse)-ViewContent-WL-SDSME_ab10-62118729eba5', 26, 4, '^product detail$|^flagship pdp$', 7, 1, '2026-08-05', NULL, 'https://experience.adobe.com/?lens_env=prod&reactor-lens_version=prod20250220203018#/@samsung/data-collection/tags/companies/COae164dc89349443cb5092e1fdc571f55/properties/PR08b2de963cc24f5cb79e01f819fd90c0/rules/RL7ca51e5fd2674e81809a083b4e088fbc', 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, NULL, NULL, 1, '2026-06-05 16:39:29', 1, '2026-06-05 16:08:27', 0, NULL);

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
  `update_rule_ticket` varchar(150) DEFAULT NULL,
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

INSERT INTO `tag_requests` (`id`, `new_tag_ticket`, `tags`, `property_id`, `vendor_id`, `status`, `type`, `new_rule_ticket`, `update_rule_ticket`, `requester_name`, `requester_email`, `requester_organization`, `updated_by`, `updated_at`, `created_by`, `created_at`, `deleted_by`, `deleted_at`) VALUES
(1, 'GTA-31600', '1', 17, 1, 6, 2, 'GTA-31599', NULL, '', '', NULL, 0, '2026-05-05 13:12:23', 0, '2026-05-04 12:56:24', 0, NULL),
(2, 'GTA-32613', '2', 1, 2, 6, 2, 'GTA-33100', NULL, '', '', NULL, 0, '2026-05-05 13:18:58', 0, '2026-05-05 13:14:20', 0, NULL),
(3, 'GTA-32613', '3', 2, 2, 6, 2, 'GTA-33100', NULL, '', '', NULL, 0, '2026-05-05 13:19:04', 0, '2026-05-05 13:15:19', 0, NULL),
(4, 'GTA-32613', '4,5', 4, 2, 6, 2, 'GTA-33100', NULL, '', '', NULL, 0, '2026-05-05 13:19:12', 0, '2026-05-05 13:16:18', 0, NULL),
(5, 'GTA-32613', '6,7', 6, 2, 6, 2, 'GTA-33100', NULL, '', '', NULL, 0, '2026-05-05 13:19:18', 0, '2026-05-05 13:17:14', 0, NULL),
(6, 'GTA-33614', '8', 25, 3, 6, 2, 'GTA-33474', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-05 13:26:28', 0, '2026-05-05 13:25:10', 0, NULL),
(7, 'GTA-33617', '9', 34, 3, 6, 2, 'GTA-33608', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-05 13:31:57', 0, '2026-05-05 13:27:55', 0, NULL),
(8, 'GTA-33617', '10', 32, 3, 6, 2, 'GTA-33609', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-05 13:38:44', 0, '2026-05-05 13:28:32', 0, NULL),
(9, 'GTA-33640', '11', 22, 3, 6, 2, 'GTA-33610', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-05 13:41:16', 0, '2026-05-05 13:39:29', 0, NULL),
(10, 'GTA-33640', '12', 30, 3, 6, 2, 'GTA-33610', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-05 13:41:33', 0, '2026-05-05 13:40:17', 0, NULL),
(11, 'GTA-33642', '13', 23, 3, 6, 2, 'GTA-33678', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-06 13:01:28', 0, '2026-05-06 12:53:20', 0, NULL),
(12, 'GTA-33642', '14', 31, 3, 6, 2, 'GTA-33678', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-06 13:01:39', 0, '2026-05-06 12:54:01', 0, NULL),
(13, 'GTA-34118', '15,16', 39, 4, 6, 2, 'GTA-34565', NULL, 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 2, 0, '2026-05-06 13:06:14', 0, '2026-05-06 13:04:28', 0, NULL),
(14, 'GTA-34118', '17,18', 42, 4, 6, 2, 'GTA-34565', NULL, 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 2, 0, '2026-05-06 13:06:22', 0, '2026-05-06 13:05:42', 0, NULL),
(15, 'GTA-34564', '19', 17, 5, 6, 2, 'GTA-34578', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-06 13:11:46', 0, '2026-05-06 13:10:59', 0, NULL),
(16, 'GTA-34656', '20', 3, 6, 6, 2, 'GTA-34796', NULL, 'Shereen El Gendy', 'shereen.g@partner.samsung.com', 1, 0, '2026-05-06 13:13:41', 0, '2026-05-06 13:13:26', 0, NULL),
(17, 'GTA-36017', '21', 22, 7, 6, 2, 'GTA-36055', NULL, 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, 0, '2026-05-15 10:30:33', 0, '2026-05-06 13:16:06', 0, NULL),
(18, 'GTA-36017', '22', 30, 7, 6, 2, 'GTA-36055', NULL, 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, 0, '2026-05-15 10:37:37', 0, '2026-05-06 13:17:19', 0, NULL),
(19, 'GTA-36019', '23', 34, 7, 6, 2, 'GTA-36092', NULL, 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, 0, '2026-05-15 10:30:49', 0, '2026-05-06 13:18:58', 0, NULL),
(20, 'GTA-36019', '24', 32, 7, 6, 2, 'GTA-36092', NULL, 'Mohammad Abu Laban', 'mohammad.abulaban@carat.com', 3, 0, '2026-05-15 10:37:54', 0, '2026-05-06 13:20:26', 0, NULL),
(21, NULL, '25', 13, 8, 6, 3, 'GTA-31467', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-06 13:33:26', 0, '2026-05-06 13:24:45', 0, NULL),
(22, NULL, '26', 16, 8, 6, 3, 'GTA-31467', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-06 13:36:17', 0, '2026-05-06 13:34:21', 0, NULL),
(23, NULL, '27', 7, 8, 6, 3, 'GTA-31570', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:16:12', 0, '2026-05-06 13:41:50', 0, NULL),
(24, NULL, '28', 10, 8, 6, 3, 'GTA-31570', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:16:16', 0, '2026-05-06 13:42:38', 0, NULL),
(25, NULL, '29', 8, 8, 6, 3, 'GTA-31574', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:16:19', 0, '2026-05-06 13:43:18', 0, NULL),
(26, NULL, '30', 12, 8, 6, 3, 'GTA-31574', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:16:27', 0, '2026-05-06 13:43:47', 0, NULL),
(27, NULL, '31', 17, 8, 6, 3, 'GTA-31701', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:16:29', 0, '2026-05-06 13:46:05', 0, NULL),
(28, NULL, '32', 20, 8, 6, 3, 'GTA-31701', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:16:32', 0, '2026-05-06 13:46:47', 0, NULL),
(29, NULL, '33', 43, 8, 6, 3, 'GTA-31718', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:16:34', 0, '2026-05-06 13:48:02', 0, NULL),
(30, NULL, '34', 46, 8, 6, 3, 'GTA-31718', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:16:36', 0, '2026-05-06 13:48:34', 0, NULL),
(31, 'GTA-36107', '36', 7, 2, 6, 2, 'GTA-36223', NULL, 'Toufic Alzaza', 'toufic.alzaza@dentsu.com', 3, 0, '2026-05-15 11:17:56', 0, '2026-05-07 12:39:42', 0, NULL),
(32, 'GTA-36107', '37', 8, 2, 6, 2, 'GTA-36223', NULL, 'Toufic Alzaza', 'toufic.alzaza@dentsu.com', 3, 0, '2026-05-15 11:17:53', 0, '2026-05-07 12:42:47', 0, NULL),
(33, NULL, '38', 35, 8, 6, 3, 'GTA-31762', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:17:25', 0, '2026-05-15 10:48:11', 0, NULL),
(34, NULL, '39', 38, 8, 6, 3, 'GTA-31762', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:17:35', 0, '2026-05-15 10:49:31', 0, NULL),
(35, NULL, '40', 39, 8, 6, 3, 'GTA-31814', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:17:38', 0, '2026-05-15 10:57:51', 0, NULL),
(36, NULL, '41', 42, 8, 6, 3, 'GTA-31814', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:17:40', 0, '2026-05-15 10:58:24', 0, NULL),
(37, NULL, '42', 24, 8, 6, 3, 'GTA-31818', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:17:42', 0, '2026-05-15 10:59:06', 0, NULL),
(38, NULL, '43', 26, 8, 6, 3, 'GTA-31819', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:17:44', 0, '2026-05-15 10:59:45', 0, NULL),
(39, NULL, '44', 25, 8, 6, 3, 'GTA-31820', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:17:47', 0, '2026-05-15 11:00:11', 0, NULL),
(40, NULL, '45', 34, 8, 6, 3, 'GTA-31819', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:17:50', 0, '2026-05-15 11:01:31', 0, NULL),
(41, NULL, '46', 32, 8, 6, 3, 'GTA-31820', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:18:03', 0, '2026-05-15 11:01:55', 0, NULL),
(42, NULL, '47', 22, 8, 6, 3, 'GTA-31821', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:18:06', 0, '2026-05-15 11:02:49', 0, NULL),
(43, NULL, '48', 30, 8, 6, 3, 'GTA-31821', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:18:09', 0, '2026-05-15 11:03:20', 0, NULL),
(44, NULL, '49', 23, 8, 6, 3, 'GTA-31822', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:18:11', 0, '2026-05-15 11:04:06', 0, NULL),
(45, NULL, '50', 31, 8, 6, 3, 'GTA-31822', NULL, 'Mohamed Rafeek', '', 1, 0, '2026-05-15 11:18:15', 0, '2026-05-15 11:04:36', 0, NULL),
(46, NULL, '53,54', 2, 9, 6, 3, 'GTA-32102', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-15 11:23:59', 0, '2026-05-15 11:22:28', 0, NULL),
(47, NULL, '55,56,57,58', 6, 9, 6, 3, 'GTA-32102', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:34:10', 0, '2026-05-15 11:27:37', 0, NULL),
(48, NULL, '59,60', 1, 9, 6, 3, 'GTA-32418', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:34:29', 0, '2026-05-15 11:28:18', 0, NULL),
(49, NULL, '61,62,63,64', 4, 9, 6, 3, 'GTA-32418', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:34:32', 0, '2026-05-15 11:28:52', 0, NULL),
(50, NULL, '65,66', 13, 9, 6, 3, 'GTA-32438', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:34:34', 0, '2026-05-15 11:31:12', 0, NULL),
(51, NULL, '67,68,69,70', 16, 9, 6, 3, 'GTA-32438', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:34:37', 0, '2026-05-15 11:31:49', 0, NULL),
(52, NULL, '71,72', 7, 9, 6, 3, 'GTA-32614', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:34:39', 0, '2026-05-15 11:33:02', 0, NULL),
(53, NULL, '73,74,75,76', 10, 9, 6, 3, 'GTA-32614', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:34:42', 0, '2026-05-15 11:33:45', 0, NULL),
(54, NULL, '77,78', 8, 9, 6, 3, 'GTA-32630', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:34:45', 0, '2026-05-15 11:34:23', 0, NULL),
(55, NULL, '79,80,81,82', 12, 9, 6, 3, 'GTA-32630', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:34:47', 0, '2026-05-15 11:35:04', 0, NULL),
(56, NULL, '83,84', 17, 9, 6, 3, 'GTA-32664', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:34:49', 0, '2026-05-15 11:35:49', 0, NULL),
(57, NULL, '85,86,87,88', 20, 9, 6, 3, 'GTA-32664', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:34:51', 0, '2026-05-15 11:36:26', 0, NULL),
(58, NULL, '89,90', 43, 9, 6, 3, 'GTA-32680', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:34:54', 0, '2026-05-15 11:37:16', 0, NULL),
(59, NULL, '91,92,93,94', 46, 9, 6, 3, 'GTA-32680', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:34:56', 0, '2026-05-15 11:37:51', 0, NULL),
(60, NULL, '95,96', 35, 9, 6, 3, 'GTA-32828', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:35:05', 0, '2026-05-15 11:38:29', 0, NULL),
(61, NULL, '97,98,99,100', 38, 9, 6, 3, 'GTA-32828', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:35:07', 0, '2026-05-15 11:39:03', 0, NULL),
(62, NULL, '101,102', 39, 9, 6, 3, 'GTA-32928', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:35:09', 0, '2026-05-15 11:39:47', 0, NULL),
(63, NULL, '103,104,105,106', 42, 9, 6, 3, 'GTA-32928', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:35:12', 0, '2026-05-15 11:40:24', 0, NULL),
(64, NULL, '107,108', 26, 9, 6, 3, 'GTA-33089', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:35:14', 0, '2026-05-15 11:41:17', 0, NULL),
(65, NULL, '109,110', 25, 9, 6, 3, 'GTA-33090', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:35:17', 0, '2026-05-15 11:41:55', 0, NULL),
(66, NULL, '111', 8, 3, 6, 3, 'GTA-33112', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-20 08:36:44', 0, '2026-05-15 11:43:34', 0, NULL),
(67, NULL, '112', 12, 3, 6, 3, 'GTA-33112', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-20 08:36:39', 0, '2026-05-15 11:44:05', 0, NULL),
(68, NULL, '113,114,115,116', 34, 9, 6, 3, 'GTA-33122', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:35:31', 0, '2026-05-15 11:45:06', 0, NULL),
(69, NULL, '117,118,119,120', 32, 9, 6, 3, 'GTA-33149', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:35:33', 0, '2026-05-15 11:45:46', 0, NULL),
(70, NULL, '121,122', 22, 9, 6, 3, 'GTA-33154', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:35:35', 0, '2026-05-15 11:46:29', 0, NULL),
(71, NULL, '123,124,125,126', 30, 9, 6, 3, 'GTA-33154', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:35:38', 0, '2026-05-15 11:47:09', 0, NULL),
(72, NULL, '127,128', 23, 9, 6, 3, 'GTA-33156', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:35:40', 0, '2026-05-15 11:47:47', 0, NULL),
(73, NULL, '129,130,131,132', 31, 9, 6, 3, 'GTA-33156', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:35:43', 0, '2026-05-15 11:48:32', 0, NULL),
(74, NULL, '133,134', 24, 9, 6, 3, 'GTA-33182', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:36:57', 0, '2026-05-15 11:49:03', 0, NULL),
(75, NULL, '135', 7, 3, 6, 3, 'GTA-33267', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-20 08:37:49', 0, '2026-05-15 11:52:05', 0, NULL),
(76, NULL, '136', 10, 3, 6, 3, 'GTA-33267', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-20 08:37:52', 0, '2026-05-15 11:52:52', 0, NULL),
(77, NULL, '137', 17, 3, 6, 3, 'GTA-33268', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-20 08:37:54', 0, '2026-05-15 11:53:22', 0, NULL),
(78, NULL, '138', 20, 3, 6, 3, 'GTA-33268', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-20 08:38:18', 0, '2026-05-15 11:53:47', 0, NULL),
(79, NULL, '139', 43, 3, 6, 3, 'GTA-33363', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-20 08:39:03', 0, '2026-05-15 11:54:25', 0, NULL),
(80, NULL, '140', 46, 3, 6, 3, 'GTA-33363', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-20 08:38:55', 0, '2026-05-15 11:55:03', 0, NULL),
(81, NULL, '141', 35, 3, 6, 3, 'GTA-33372', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-20 08:39:43', 0, '2026-05-15 11:55:40', 0, NULL),
(82, NULL, '142', 38, 3, 6, 3, 'GTA-33372', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-20 08:39:36', 0, '2026-05-15 11:56:04', 0, NULL),
(83, NULL, '143', 39, 3, 6, 3, 'GTA-33431', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-20 08:40:17', 0, '2026-05-15 11:56:36', 0, NULL),
(84, NULL, '144', 42, 3, 6, 3, 'GTA-33431', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-20 08:40:37', 0, '2026-05-15 11:56:57', 0, NULL),
(85, NULL, '145', 26, 3, 6, 3, 'GTA-33473', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-20 08:41:02', 0, '2026-05-15 11:57:44', 0, NULL),
(86, NULL, '146', 39, 4, 6, 3, 'GTA-33517', NULL, 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, 0, '2026-05-20 08:44:07', 0, '2026-05-15 12:08:22', 0, NULL),
(87, NULL, '147', 42, 4, 6, 3, 'GTA-33517', NULL, 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, 0, '2026-05-20 08:44:09', 0, '2026-05-15 12:09:06', 0, NULL),
(88, NULL, '148', 34, 4, 6, 3, 'GTA-33524', NULL, 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, 0, '2026-05-20 08:44:38', 0, '2026-05-15 12:12:02', 0, NULL),
(89, NULL, '149', 32, 4, 6, 3, 'GTA-33524', NULL, 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, 0, '2026-05-20 08:44:40', 0, '2026-05-15 12:12:34', 0, NULL),
(90, NULL, '150', 22, 4, 6, 3, 'GTA-33525', NULL, 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, 0, '2026-05-20 08:45:06', 0, '2026-05-15 12:13:11', 0, NULL),
(91, NULL, '151', 30, 4, 6, 3, 'GTA-33525', NULL, 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, 0, '2026-05-20 08:45:14', 0, '2026-05-15 12:13:43', 0, NULL),
(92, NULL, '152', 26, 4, 6, 3, 'GTA-33563', NULL, 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, 0, '2026-05-20 08:45:21', 0, '2026-05-15 12:14:33', 0, NULL),
(93, NULL, '153', 25, 4, 6, 3, 'GTA-33563', NULL, 'Aamna Surahio', 'aamna.surahio@rtbhouse.com', 3, 0, '2026-05-20 08:45:29', 0, '2026-05-15 12:15:03', 0, NULL),
(94, NULL, '154,155', 21, 9, 6, 3, 'GTA-34166', NULL, 'Tagging Support Team', 'tagging.jira@concentrix.com', 4, 0, '2026-05-20 08:44:57', 0, '2026-05-15 12:28:48', 0, NULL),
(96, 'GTA-35665', '158,159', 1, 10, 6, 2, 'GTA-36279', NULL, 'Elsa Badran', 'elsa.badran@publicisgroupe.com', 5, 0, '2026-05-20 11:01:03', 0, '2026-05-15 12:32:49', 0, NULL),
(97, 'GTA-35665', '160,161', 2, 10, 6, 2, 'GTA-36279', NULL, 'Elsa Badran', 'elsa.badran@publicisgroupe.com', 5, 0, '2026-05-20 11:00:56', 0, '2026-05-15 12:33:19', 0, NULL),
(98, 'GTA-35665', '162', 4, 10, 6, 2, 'GTA-36279', NULL, 'Elsa Badran', 'elsa.badran@publicisgroupe.com', 5, 0, '2026-05-20 11:00:52', 0, '2026-05-15 12:33:41', 0, NULL),
(99, 'GTA-35665', '163', 6, 10, 6, 2, 'GTA-36279', NULL, 'Elsa Badran', 'elsa.badran@publicisgroupe.com', 5, 0, '2026-05-20 10:38:43', 0, '2026-05-15 12:34:03', 0, NULL),
(100, 'GTA-36488', '164,165', 10, 3, 6, 2, 'GTA-36536', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-06-01 11:44:33', 0, '2026-05-19 16:09:25', 0, NULL),
(101, 'GTA-36488', '166,167', 12, 3, 6, 2, 'GTA-36536', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-06-01 11:44:24', 0, '2026-05-19 16:13:16', 0, NULL),
(102, 'GTA-36537', '173', 8, 3, 6, 2, 'GTA-36536', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-06-01 11:37:08', 0, '2026-05-21 13:14:52', 0, NULL),
(103, 'GTA-36537', '174', 7, 3, 6, 2, 'GTA-36536', NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-06-01 11:37:00', 0, '2026-05-21 13:17:32', 0, NULL),
(104, 'GTA-36535', '177', 20, 3, 1, 2, NULL, NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-22 15:52:56', 0, '2026-05-22 15:52:31', 0, NULL),
(105, 'GTA-36535', '178', 17, 3, 1, 2, NULL, NULL, 'Eiass Ali', 'eiass.ali@samsung.com', 1, 0, '2026-05-22 15:53:57', 0, '2026-05-22 15:53:29', 0, NULL),
(106, 'GTA-36845', '252', 16, 4, 6, 2, 'GTA-34577', NULL, 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, 0, '2026-06-03 08:42:02', 1, '2026-06-01 12:20:10', 0, NULL),
(107, 'GTA-36845', '253', 13, 4, 6, 2, 'GTA-34577', NULL, 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, 0, '2026-06-03 08:42:05', 1, '2026-06-01 12:21:12', 0, NULL),
(108, 'GTA-36875', '479,480', 34, 4, 3, 2, 'GTA-36968', NULL, 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, 1, '2026-06-05 10:42:00', 1, '2026-06-04 14:16:01', 0, NULL),
(109, 'GTA-36875', '481,482', 32, 4, 3, 2, 'GTA-36968', NULL, 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, 1, '2026-06-05 11:00:58', 1, '2026-06-04 14:17:02', 0, NULL),
(110, 'GTA-36877', '483,484', 30, 4, 3, 2, 'GTA-36995', NULL, 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, 1, '2026-06-05 13:57:26', 1, '2026-06-05 13:06:07', 0, NULL),
(111, 'GTA-36877', '485,486', 22, 4, 3, 2, 'GTA-36995', NULL, 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, 1, '2026-06-05 13:57:41', 1, '2026-06-05 13:56:53', 0, NULL),
(112, 'GTA-36876', '487,488', 25, 4, 3, 2, 'GTA-37031', NULL, 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, 1, '2026-06-05 15:12:20', 1, '2026-06-05 15:11:44', 0, NULL),
(113, 'GTA-36876', '489,490', 26, 4, 3, 2, 'GTA-37031', NULL, 'Muhammad Hussnain Hamid', 'hussnain.h@partner.samsung.com', 1, 1, '2026-06-05 16:08:53', 1, '2026-06-05 16:08:27', 0, NULL);

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
(10, 'MENAO_SmartScript (1).txt', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEIL/ZP6_IL-Israel WebSDK/Appsflyer/MENAO_SmartScript (1).txt', 4, 15, 0, '2026-05-06 13:11:30', '2026-05-06 13:11:30'),
(11, 'New_Media_Tag_Request_Optimise.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/ZP6_AE_AR-UAE(SGE) WebSDK/Optimise/New_Media_Tag_Request_Optimise.xlsx', 3, 2, 0, '2026-05-05 08:13:44', '2026-05-05 08:13:44'),
(12, 'Samsung cookie code-all pages.pdf', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/ZP6_AE_AR-UAE(SGE) WebSDK/Optimise/Samsung cookie code-all pages.pdf', 4, 2, 0, '2026-05-05 08:13:51', '2026-05-05 08:13:51'),
(13, 'Samsung- Universal Tag and Conversion code.pdf', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/ZP6_AE_AR-UAE(SGE) WebSDK/Optimise/Samsung- Universal Tag and Conversion code.pdf', 4, 2, 0, '2026-05-05 08:13:57', '2026-05-05 08:13:57'),
(14, 'New_Media_Tag_Request_Optimise.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/ZP6_AE-UAE(SGE) WebSDK/Optimise/New_Media_Tag_Request_Optimise.xlsx', 3, 3, 0, '2026-05-05 08:15:11', '2026-05-05 08:15:11'),
(15, 'New_Media_Tag_Request_Optimise.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/ZS_Hybris_AE-UAE (Shop Section) WebSDK/Optimise/New_Media_Tag_Request_Optimise.xlsx', 3, 5, 0, '2026-05-05 08:16:37', '2026-05-05 08:16:37'),
(16, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_IQ_KU-Kurdistan (Shop Section) WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 12, 0, '2026-05-06 08:21:21', '2026-05-06 08:21:21'),
(17, 'New_Media_Tag_Request_SELV (JO)_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZP6_LEVANT_AR-Levant(SELV) WebSDK/Contentsquare/New_Media_Tag_Request_SELV (JO)_CSQ.xlsx', 3, 6, 0, '2026-05-06 08:22:29', '2026-05-06 08:22:29'),
(18, 'New_Media_Tag_Request_SELV (JO)_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_JO-Jordan (Shop Section) WebSDK/Contentsquare/New_Media_Tag_Request_SELV (JO)_CSQ.xlsx', 3, 7, 0, '2026-05-06 08:22:45', '2026-05-06 08:22:45'),
(19, 'New_Media_Tag_Request_SELV (JO)_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_JO_AR-Jordan (Shop Section) WebSDK/Contentsquare/New_Media_Tag_Request_SELV (JO)_CSQ.xlsx', 3, 8, 0, '2026-05-06 08:23:03', '2026-05-06 08:23:03'),
(20, 'New_Media_Tag_Request_SELV (IQ)_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/zP6_IQ_AR-Iraq WebSDK/Contentsquare/New_Media_Tag_Request_SELV (IQ)_CSQ.xlsx', 3, 9, 0, '2026-05-06 08:23:12', '2026-05-06 08:23:12'),
(21, 'New_Media_Tag_Request_SELV (IQ)_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_IQ_AR-Iraq (Shop Section) WebSDK/Contentsquare/New_Media_Tag_Request_SELV (IQ)_CSQ.xlsx', 3, 10, 0, '2026-05-06 08:23:22', '2026-05-06 08:23:22'),
(22, 'New_Media_Tag_Request_SELV (IQ)_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/zP6_IQ_KU-Kurdistan WebSDK/Contentsquare/New_Media_Tag_Request_SELV (IQ)_CSQ.xlsx', 3, 11, 0, '2026-05-06 08:23:32', '2026-05-06 08:23:32'),
(23, 'New_Media_Tag_Request_SELV (IQ)_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_IQ_KU-Kurdistan (Shop Section) WebSDK/Contentsquare/New_Media_Tag_Request_SELV (IQ)_CSQ.xlsx', 3, 12, 0, '2026-05-06 08:23:42', '2026-05-06 08:23:42'),
(24, 'RTBHouse_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEMAG/ZP6_N_AFRICA-Africa WebSDK/RTBHouse/RTBHouse_Approval.png', 1, 13, 0, '2026-05-06 08:24:52', '2026-05-06 08:24:52'),
(25, 'New_Media_Tag_Request_v3 - Listing Tag.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEMAG/ZP6_N_AFRICA-Africa WebSDK/RTBHouse/New_Media_Tag_Request_v3 - Listing Tag.xlsx', 3, 13, 0, '2026-05-06 08:25:04', '2026-05-06 08:25:04'),
(26, 'RTBHouse_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEMAG/ZS_Hybris_MA-Morocco (Shop Section) WebSDK/RTBHouse/RTBHouse_Approval.png', 1, 14, 0, '2026-05-06 08:25:12', '2026-05-06 08:25:12'),
(27, 'New_Media_Tag_Request_v3 - Order Tag.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEMAG/ZS_Hybris_MA-Morocco (Shop Section) WebSDK/RTBHouse/New_Media_Tag_Request_v3 - Order Tag.xlsx', 3, 14, 0, '2026-05-06 08:25:20', '2026-05-06 08:25:20'),
(28, 'New_Media_Tag_Request_v3 - basketadd tag (1).xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEMAG/ZP6_N_AFRICA-Africa WebSDK/RTBHouse/New_Media_Tag_Request_v3 - basketadd tag (1).xlsx', 3, 13, 0, '2026-05-06 08:25:38', '2026-05-06 08:25:38'),
(29, 'Appsflyer_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEIL/ZP6_IL-Israel WebSDK/Appsflyer/Appsflyer_Approval.png', 1, 15, 0, '2026-05-06 08:26:26', '2026-05-06 08:26:26'),
(30, 'Firework_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/zP6zEPP_AE-UAE(AA) WebSDK/Firework/Firework_Approval.png', 1, 16, 0, '2026-05-07 08:28:42', '2026-05-07 08:28:42'),
(31, 'Fireworks_ag_Request_EPP_latest.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/zP6zEPP_AE-UAE(AA) WebSDK/Firework/Fireworks_ag_Request_EPP_latest.xlsx', 3, 16, 0, '2026-05-07 08:28:50', '2026-05-07 08:28:50'),
(32, 'EPP stores URLs & Domains 6-2-2026.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/zP6zEPP_AE-UAE(AA) WebSDK/Firework/EPP stores URLs & Domains 6-2-2026.xlsx', 4, 16, 0, '2026-05-07 08:28:56', '2026-05-07 08:28:56'),
(33, 'Pinterest_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/zP6_IQ_AR-Iraq WebSDK/Pinterest/Pinterest_Approval.png', 1, 17, 0, '2026-05-07 08:29:49', '2026-05-07 08:29:49'),
(34, 'New_Media_Tag_Request_v3 - Pinterest Iraq.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/zP6_IQ_AR-Iraq WebSDK/Pinterest/New_Media_Tag_Request_v3 - Pinterest Iraq.xlsx', 3, 17, 0, '2026-05-07 08:30:00', '2026-05-07 08:30:00'),
(35, 'Pinterest_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_IQ_AR-Iraq (Shop Section) WebSDK/Pinterest/Pinterest_Approval.png', 1, 18, 0, '2026-05-07 08:30:07', '2026-05-07 08:30:07'),
(36, 'New_Media_Tag_Request_v3 - Pinterest Iraq.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_IQ_AR-Iraq (Shop Section) WebSDK/Pinterest/New_Media_Tag_Request_v3 - Pinterest Iraq.xlsx', 3, 18, 0, '2026-05-07 08:30:13', '2026-05-07 08:30:13'),
(37, 'Pinterest_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_JO-Jordan (Shop Section) WebSDK/Pinterest/Pinterest_Approval.png', 1, 19, 0, '2026-05-07 08:30:20', '2026-05-07 08:30:20'),
(38, 'New_Media_Tag_Request_v3 - Pinterest Jordan.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_JO-Jordan (Shop Section) WebSDK/Pinterest/New_Media_Tag_Request_v3 - Pinterest Jordan.xlsx', 3, 19, 0, '2026-05-07 08:30:26', '2026-05-07 08:30:26'),
(39, 'Pinterest_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_JO_AR-Jordan (Shop Section) WebSDK/Pinterest/Pinterest_Approval.png', 1, 20, 0, '2026-05-07 08:30:31', '2026-05-07 08:30:31'),
(40, 'New_Media_Tag_Request_v3 - Pinterest Jordan.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_JO_AR-Jordan (Shop Section) WebSDK/Pinterest/New_Media_Tag_Request_v3 - Pinterest Jordan.xlsx', 3, 20, 0, '2026-05-07 08:30:38', '2026-05-07 08:30:38'),
(41, 'CJ_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SETK/ZP6_TR-Turkey WebSDK/CJ/CJ_Approval.png', 1, 21, 0, '2026-05-07 08:31:55', '2026-05-07 08:31:55'),
(42, 'SETK_New_Media_Tag_Request_v3.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SETK/ZP6_TR-Turkey WebSDK/CJ/SETK_New_Media_Tag_Request_v3.xlsx', 3, 21, 0, '2026-05-07 08:32:01', '2026-05-07 08:32:01'),
(43, 'CJ_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SETK/ZS_Hybris_TR-Turkey (Shop Section) WebSDK/CJ/CJ_Approval.png', 1, 22, 0, '2026-05-07 08:32:16', '2026-05-07 08:32:16'),
(44, 'SETK_New_Media_Tag_Request_v3.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SETK/ZS_Hybris_TR-Turkey (Shop Section) WebSDK/CJ/SETK_New_Media_Tag_Request_v3.xlsx', 3, 22, 0, '2026-05-07 08:32:21', '2026-05-07 08:32:21'),
(45, 'CJ_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZP6_SA_EN-Saudi Arabia(SESAR) WebSDK/CJ/CJ_Approval.png', 1, 23, 0, '2026-05-07 08:32:56', '2026-05-07 08:32:56'),
(46, 'SESAR_New_Media_Tag_Request_v3.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZP6_SA_EN-Saudi Arabia(SESAR) WebSDK/CJ/SESAR_New_Media_Tag_Request_v3.xlsx', 3, 23, 0, '2026-05-07 08:33:03', '2026-05-07 08:33:03'),
(47, 'CJ_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZS_Hybris_SA_EN-Saudi Arabia (Shop Section) WebSDK/CJ/CJ_Approval.png', 1, 24, 0, '2026-05-07 08:33:12', '2026-05-07 08:33:12'),
(48, 'SESAR_New_Media_Tag_Request_v3.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZS_Hybris_SA_EN-Saudi Arabia (Shop Section) WebSDK/CJ/SESAR_New_Media_Tag_Request_v3.xlsx', 3, 24, 0, '2026-05-07 08:33:17', '2026-05-07 08:33:17'),
(49, 'CJ_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZP6_SA-Saudi Arabia(SESAR) WebSDK/CJ/CJ_Approval.png', 1, 25, 0, '2026-05-07 08:33:24', '2026-05-07 08:33:24'),
(50, 'SESAR_New_Media_Tag_Request_v3.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZP6_SA-Saudi Arabia(SESAR) WebSDK/CJ/SESAR_New_Media_Tag_Request_v3.xlsx', 3, 25, 0, '2026-05-07 08:33:30', '2026-05-07 08:33:30'),
(51, 'CJ_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZS_Hybris_SA-Saudi Arabia (Shop Section) WebSDK/CJ/CJ_Approval.png', 1, 26, 0, '2026-05-07 08:33:36', '2026-05-07 08:33:36'),
(52, 'SESAR_New_Media_Tag_Request_v3.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZS_Hybris_SA-Saudi Arabia (Shop Section) WebSDK/CJ/SESAR_New_Media_Tag_Request_v3.xlsx', 3, 26, 0, '2026-05-07 08:33:43', '2026-05-07 08:33:43'),
(53, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZP6_SA-Saudi Arabia(SESAR) WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 66, 0, '2026-05-15 08:36:06', '2026-05-15 08:36:06'),
(54, 'New_Media_Tag_Request_SESAR_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZP6_SA-Saudi Arabia(SESAR) WebSDK/Contentsquare/New_Media_Tag_Request_SESAR_CSQ.xlsx', 3, 66, 0, '2026-05-15 08:36:15', '2026-05-15 08:36:15'),
(55, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZS_Hybris_SA-Saudi Arabia (Shop Section) WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 67, 0, '2026-05-15 08:36:21', '2026-05-15 08:36:21'),
(56, 'New_Media_Tag_Request_SESAR_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZS_Hybris_SA-Saudi Arabia (Shop Section) WebSDK/Contentsquare/New_Media_Tag_Request_SESAR_CSQ.xlsx', 3, 67, 0, '2026-05-15 08:36:32', '2026-05-15 08:36:32'),
(57, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZP6_SA_EN-Saudi Arabia(SESAR) WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 75, 0, '2026-05-15 08:37:04', '2026-05-15 08:37:04'),
(58, 'New_Media_Tag_Request_SESAR_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZP6_SA_EN-Saudi Arabia(SESAR) WebSDK/Contentsquare/New_Media_Tag_Request_SESAR_CSQ.xlsx', 3, 75, 0, '2026-05-15 08:37:10', '2026-05-15 08:37:10'),
(59, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZS_Hybris_SA_EN-Saudi Arabia (Shop Section) WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 76, 0, '2026-05-15 08:37:15', '2026-05-15 08:37:15'),
(60, 'New_Media_Tag_Request_SESAR_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZS_Hybris_SA_EN-Saudi Arabia (Shop Section) WebSDK/Contentsquare/New_Media_Tag_Request_SESAR_CSQ.xlsx', 3, 76, 0, '2026-05-15 08:37:22', '2026-05-15 08:37:22'),
(61, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEIL/ZP6_IL-Israel WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 77, 0, '2026-05-15 08:37:30', '2026-05-15 08:37:30'),
(62, 'New_Media_Tag_Request_SEIL_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEIL/ZP6_IL-Israel WebSDK/Contentsquare/New_Media_Tag_Request_SEIL_CSQ.xlsx', 3, 77, 0, '2026-05-15 08:37:37', '2026-05-15 08:37:37'),
(63, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEIL/ZS_Hybris_IL-Israel (Shop Section) WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 78, 0, '2026-05-15 08:38:02', '2026-05-15 08:38:02'),
(64, 'New_Media_Tag_Request_SEIL_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEIL/ZS_Hybris_IL-Israel (Shop Section) WebSDK/Contentsquare/New_Media_Tag_Request_SEIL_CSQ.xlsx', 3, 78, 0, '2026-05-15 08:38:13', '2026-05-15 08:38:13'),
(65, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEPAK/ZP6_PK-Pakistan WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 79, 0, '2026-05-15 08:38:33', '2026-05-15 08:38:33'),
(66, 'New_Media_Tag_Request_SEPAK_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEPAK/ZP6_PK-Pakistan WebSDK/Contentsquare/New_Media_Tag_Request_SEPAK_CSQ.xlsx', 3, 79, 0, '2026-05-15 08:38:41', '2026-05-15 08:38:41'),
(67, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEPAK/ZS_Hybris_PK-Pakistan (Shop Section) WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 80, 0, '2026-05-15 08:38:47', '2026-05-15 08:38:47'),
(68, 'New_Media_Tag_Request_SEPAK_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEPAK/ZS_Hybris_PK-Pakistan (Shop Section) WebSDK/Contentsquare/New_Media_Tag_Request_SEPAK_CSQ.xlsx', 3, 80, 0, '2026-05-15 08:38:53', '2026-05-15 08:38:53'),
(69, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEEG/ZP6_EG-Egypt WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 81, 0, '2026-05-15 08:39:10', '2026-05-15 08:39:10'),
(70, 'New_Media_Tag_Request_SEEG_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEEG/ZP6_EG-Egypt WebSDK/Contentsquare/New_Media_Tag_Request_SEEG_CSQ.xlsx', 3, 81, 0, '2026-05-15 08:39:18', '2026-05-15 08:39:18'),
(71, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEEG/ZS_Hybris_EG-Egypt (Shop Section) WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 82, 0, '2026-05-15 08:39:24', '2026-05-15 08:39:24'),
(72, 'New_Media_Tag_Request_SEEG_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEEG/ZS_Hybris_EG-Egypt (Shop Section) WebSDK/Contentsquare/New_Media_Tag_Request_SEEG_CSQ.xlsx', 3, 82, 0, '2026-05-15 08:39:31', '2026-05-15 08:39:31'),
(73, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEMAG/ZP6_N_AFRICA-Africa WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 83, 0, '2026-05-15 08:40:05', '2026-05-15 08:40:05'),
(74, 'New_Media_Tag_Request_SEMAG_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEMAG/ZP6_N_AFRICA-Africa WebSDK/Contentsquare/New_Media_Tag_Request_SEMAG_CSQ.xlsx', 3, 83, 0, '2026-05-15 08:40:13', '2026-05-15 08:40:13'),
(75, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEMAG/ZS_Hybris_MA-Morocco (Shop Section) WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 84, 0, '2026-05-15 08:40:23', '2026-05-15 08:40:23'),
(76, 'New_Media_Tag_Request_SEMAG_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEMAG/ZS_Hybris_MA-Morocco (Shop Section) WebSDK/Contentsquare/New_Media_Tag_Request_SEMAG_CSQ.xlsx', 3, 84, 0, '2026-05-15 08:40:29', '2026-05-15 08:40:29'),
(77, 'ContentSquare_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZP6_LEVANT-Levant(SELV) WebSDK/Contentsquare/ContentSquare_Approval_MainTag.png', 1, 85, 0, '2026-05-15 08:40:52', '2026-05-15 08:40:52'),
(78, 'New_Media_Tag_Request_SELV (JO)_CSQ.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZP6_LEVANT-Levant(SELV) WebSDK/Contentsquare/New_Media_Tag_Request_SELV (JO)_CSQ.xlsx', 3, 85, 0, '2026-05-15 08:41:00', '2026-05-15 08:41:00'),
(79, 'RTBHouse_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEMAG/ZP6_N_AFRICA-Africa WebSDK/RTBHouse/RTBHouse_Approval_MainTag.png', 1, 86, 0, '2026-05-15 08:42:29', '2026-05-15 08:42:29'),
(80, 'RTBHouse_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SEMAG/ZS_Hybris_MA-Morocco (Shop Section) WebSDK/RTBHouse/RTBHouse_Approval_MainTag.png', 1, 87, 0, '2026-05-15 08:43:47', '2026-05-15 08:43:47'),
(81, 'RTBHouse_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_JO-Jordan (Shop Section) WebSDK/RTBHouse/RTBHouse_Approval_MainTag.png', 1, 88, 0, '2026-05-15 08:44:24', '2026-05-15 08:44:24'),
(82, 'RTBHouse_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_JO_AR-Jordan (Shop Section) WebSDK/RTBHouse/RTBHouse_Approval_MainTag.png', 1, 89, 0, '2026-05-15 08:44:29', '2026-05-15 08:44:29'),
(83, 'RTBHouse_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/zP6_IQ_AR-Iraq WebSDK/RTBHouse/RTBHouse_Approval_MainTag.png', 1, 90, 0, '2026-05-15 08:45:04', '2026-05-15 08:45:04'),
(84, 'RTBHouse_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZS_Hybris_IQ_AR-Iraq (Shop Section) WebSDK/RTBHouse/RTBHouse_Approval_MainTag.png', 1, 91, 0, '2026-05-15 08:45:13', '2026-05-15 08:45:13'),
(85, 'RTBHouse_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZP6_LEVANT-Levant(SELV) WebSDK/RTBHouse/RTBHouse_Approval_MainTag.png', 1, 92, 0, '2026-05-15 08:45:19', '2026-05-15 08:45:19'),
(86, 'RTBHouse_Approval_MainTag.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SELV/ZP6_LEVANT_AR-Levant(SELV) WebSDK/RTBHouse/RTBHouse_Approval_MainTag.png', 1, 93, 0, '2026-05-15 08:45:27', '2026-05-15 08:45:27'),
(87, 'New_Media_Tag_Request Optimise.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZP6_SA-Saudi Arabia(SESAR) WebSDK/Optimise/New_Media_Tag_Request Optimise.xlsx', 3, 32, 0, '2026-05-20 10:23:55', '2026-05-20 10:23:55'),
(88, 'Optimise_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZP6_SA-Saudi Arabia(SESAR) WebSDK/Optimise/Optimise_Approval.png', 1, 32, 0, '2026-05-20 10:24:33', '2026-05-20 10:24:33'),
(89, 'Optimise_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZP6_SA_EN-Saudi Arabia(SESAR) WebSDK/Optimise/Optimise_Approval.png', 1, 31, 0, '2026-05-20 10:24:53', '2026-05-20 10:24:53'),
(90, 'New_Media_Tag_Request Optimise.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZP6_SA_EN-Saudi Arabia(SESAR) WebSDK/Optimise/New_Media_Tag_Request Optimise.xlsx', 3, 31, 0, '2026-05-20 10:25:11', '2026-05-20 10:25:11'),
(91, 'Samsung Universal and Conversion pixel (amended).pdf', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZP6_SA_EN-Saudi Arabia(SESAR) WebSDK/Optimise/Samsung Universal and Conversion pixel (amended).pdf', 4, 31, 0, '2026-05-20 10:25:21', '2026-05-20 10:25:21'),
(92, 'Samsung cookie code-all pages.pdf', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZP6_SA_EN-Saudi Arabia(SESAR) WebSDK/Optimise/Samsung cookie code-all pages.pdf', 4, 31, 0, '2026-05-20 10:25:29', '2026-05-20 10:25:29'),
(93, 'AdobeDSP_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/ZS_Hybris_AE-UAE (Shop Section) WebSDK/AdobeDSP/AdobeDSP_Approval.png', 1, 99, 0, '2026-05-20 10:37:14', '2026-05-20 10:37:14'),
(94, 'New_Media_Tag_Request_v3.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/ZS_Hybris_AE-UAE (Shop Section) WebSDK/AdobeDSP/New_Media_Tag_Request_v3.xlsx', 3, 99, 0, '2026-05-20 10:37:25', '2026-05-20 10:37:25'),
(95, 'PublicisMedia-Samsung-ConversionTags-05052026.pdf', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/ZS_Hybris_AE-UAE (Shop Section) WebSDK/AdobeDSP/PublicisMedia-Samsung-ConversionTags-05052026.pdf', 4, 99, 0, '2026-05-20 10:37:38', '2026-05-20 10:37:38'),
(96, 'RE_(2) Adobe DSP Pixel Integration Request.eml', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/ZS_Hybris_AE-UAE (Shop Section) WebSDK/AdobeDSP/RE_(2) Adobe DSP Pixel Integration Request.eml', 2, 99, 0, '2026-05-20 10:37:44', '2026-05-20 10:37:44'),
(97, 'AdobeDSP_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/ZP6_AE-UAE(SGE) WebSDK/AdobeDSP/AdobeDSP_Approval.png', 1, 97, 0, '2026-05-20 10:38:04', '2026-05-20 10:38:04'),
(98, 'AdobeDSP_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/ZS_Hybris_AE_AR-UAE (Shop Section) WebSDK/AdobeDSP/AdobeDSP_Approval.png', 1, 98, 0, '2026-05-20 11:00:47', '2026-05-20 11:00:47'),
(99, 'AdobeDSP_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SGE/ZP6_AE_AR-UAE(SGE) WebSDK/AdobeDSP/AdobeDSP_Approval.png', 1, 96, 0, '2026-05-20 11:01:01', '2026-05-20 11:01:01'),
(100, '20260519_RE_(2)_CSQ_Implementation_Tracker_Update.eml', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZS_Hybris_SA-Saudi Arabia (Shop Section) WebSDK/Contentsquare/20260519_RE_(2)_CSQ_Implementation_Tracker_Update.eml', 2, 101, 0, '2026-05-21 12:34:29', '2026-05-21 12:34:29'),
(101, '20260519_RE_(2)_CSQ_Implementation_Tracker_Update.eml', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SESAR/ZS_Hybris_SA_EN-Saudi Arabia (Shop Section) WebSDK/Contentsquare/20260519_RE_(2)_CSQ_Implementation_Tracker_Update.eml', 2, 100, 0, '2026-05-21 12:34:43', '2026-05-21 12:34:43'),
(102, 'RTBHouse_SETK_Approval.png', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SETK/ZP6_TR-Turkey WebSDK/RTBHouse/RTBHouse_SETK_Approval.png', 1, 107, 1, '2026-06-01 12:23:50', '2026-06-01 12:23:50'),
(103, 'New_Media_Tag_Request_v3 - Listing Tag.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SETK/ZP6_TR-Turkey WebSDK/RTBHouse/New_Media_Tag_Request_v3 - Listing Tag.xlsx', 3, 107, 1, '2026-06-01 12:24:08', '2026-06-01 12:24:08'),
(104, 'New_Media_Tag_Request_v3 - Order Tag.xlsx', 'C:\\xampp\\htdocs\\tag-monitoring\\writable\\uploads/new_tag_requests/SETK/ZP6_TR-Turkey WebSDK/RTBHouse/New_Media_Tag_Request_v3 - Order Tag.xlsx', 3, 107, 1, '2026-06-01 12:24:15', '2026-06-01 12:24:15');

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

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `password`, `account_type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Marie', 'Samsung', 'marie', '$2y$10$RPznWUg/0MVG6FQm8tMeq..o6Y27GAtzZyVFaoRzkhNoKGj4WvLKK', 1, 1, '2026-05-25 22:17:04', '2026-05-25 22:17:04'),
(2, 'Khristine', 'Samsung', 'khristine', '$2y$10$ldcqGJmu0xQZb9tdBEWglObXv650OIHTYRz5MyOGm7w1ecY2rvBvi', 2, 1, '2026-05-25 22:17:04', '2026-05-25 22:17:04'),
(3, 'Lucky', 'Samsung', 'lucky', '$2y$10$ESh0OajoNX190jocI.zTJOplcip8W118kte6IFIH.qQOQKZ2IKycK', 2, 1, '2026-05-25 22:17:04', '2026-05-25 22:17:04'),
(4, 'Janitha', 'Samsung', 'janitha', '$2y$10$kJqrEPZ3zdMWTY/OC/ZG3upSOPu63fnw0kagEE2mKPH1FaLUGEnTK', 2, 1, '2026-05-25 22:17:04', '2026-05-25 22:17:04'),
(5, 'George', 'Samsung', 'george', '$2y$10$6iETjvtYbid7GUnKno3PQeRO1sBcrqThZa1GU752XuKP9Wakq4k3O', 2, 1, '2026-05-25 22:17:04', '2026-05-25 22:17:04'),
(6, 'Other', 'User', 'other', '$2y$10$u3IJsQrlGx3WUnuPUuRB1O3GzziMg5yEok1qqw1QowqiTy4z1XNnK', 2, 1, '2026-05-25 22:17:04', '2026-05-25 22:17:04'),
(7, 'System', 'Admin', 'admin', '$2y$10$lcWD3VmtfFN5pcaj0tD3IePbH1DG4mZ1TucQzJUWf5uTKyrRxxKDa', 1, 1, '2026-05-25 22:17:04', '2026-05-25 22:17:04');

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
(3, 'Contentsquare', NULL, 'It equips the team with a trustworthy view of user behavior, accelerates data-drive improvements, and minimizes ongoing maintenance overhead, which will make it a strategic win for both short-term optimization and long-term growth.', 'A snippet that is added to your website that helps your team understand how users interact with your pages, where they click, scroll, and where they experience friction. This allows to make data-driven decisions to improve digital experiences.', NULL, 1, 0, '2026-05-05 13:22:21', 0, '2026-05-05 13:22:21', 0, NULL),
(4, 'RTBHouse', NULL, 'Remarketing for conversions', 'Remarketing campaign provider', NULL, 1, 0, '2026-05-06 13:04:28', 0, '2026-05-06 13:04:28', 0, NULL),
(5, 'Appsflyer', NULL, NULL, NULL, NULL, 1, 0, '2026-05-06 13:10:59', 0, '2026-05-06 13:10:59', 0, NULL),
(6, 'Firework', NULL, 'Floating video player to stream promotional video content', 'Floating video player to stream promotional video content', NULL, 1, 0, '2026-05-06 13:13:26', 0, '2026-05-06 13:13:26', 0, NULL),
(7, 'Pinterest', NULL, NULL, NULL, NULL, 1, 0, '2026-05-06 13:16:06', 0, '2026-05-06 13:16:06', 0, NULL),
(8, 'CJ', NULL, NULL, NULL, NULL, 1, 0, '2026-05-06 13:24:45', 0, '2026-05-06 13:24:45', 0, NULL),
(9, 'Meta', NULL, NULL, NULL, NULL, 1, 0, '2026-05-15 11:22:28', 0, '2026-05-15 11:22:28', 0, NULL),
(10, 'AdobeDSP', NULL, 'As we are using Adobe Analytics to measure performance, we plan to use Adobe Advertising Cloud DSP for all Samsung campaigns moving forward. In Adobe Analytics, we would like to track both view-through and click-through conversions from Adobe Advertising Cloud DSP for all Samsung campaigns. To track view-through conversions, we would need to implement the required JavaScript code.', 'As we are using Adobe Analytics to measure performance, we plan to use Adobe Advertising Cloud DSP for all Samsung campaigns moving forward. In Adobe Analytics, we would like to track both view-through and click-through conversions from Adobe Advertising Cloud DSP for all Samsung campaigns. To track view-through conversions, we would need to implement the required JavaScript code.', NULL, 1, 0, '2026-05-15 12:32:06', 0, '2026-05-15 12:32:06', 0, NULL),
(11, 'Reddit', NULL, NULL, NULL, NULL, 1, 0, '2026-05-20 10:04:48', 0, '2026-05-20 10:04:48', 0, NULL),
(12, 'Snapchat', NULL, NULL, NULL, NULL, 1, 0, '2026-05-20 10:06:11', 0, '2026-05-20 10:06:11', 0, NULL),
(13, 'Twitter', NULL, NULL, NULL, NULL, 1, 0, '2026-05-20 10:06:36', 0, '2026-05-20 10:06:36', 0, NULL),
(14, 'RTB', NULL, NULL, NULL, NULL, 1, 0, '2026-05-22 17:24:05', 0, '2026-05-22 17:24:05', 0, NULL),
(15, 'Insider', NULL, NULL, NULL, NULL, 1, 0, '2026-05-22 17:25:49', 0, '2026-05-22 17:25:49', 0, NULL),
(16, 'Amazon', NULL, NULL, NULL, NULL, 1, 0, '2026-05-25 09:25:33', 0, '2026-05-25 09:25:33', 0, NULL),
(17, 'Criteo', NULL, NULL, NULL, NULL, 1, 0, '2026-05-25 09:27:03', 0, '2026-05-25 09:27:03', 0, NULL),
(18, 'Naturalsearch', NULL, NULL, NULL, NULL, 1, 0, '2026-05-25 09:28:02', 0, '2026-05-25 09:28:02', 0, NULL),
(19, 'CampaignManager', NULL, NULL, NULL, NULL, 1, 0, '2026-05-25 09:32:20', 0, '2026-05-25 09:32:20', 0, NULL),
(20, 'Linkedin', NULL, NULL, NULL, NULL, 1, 0, '2026-05-25 09:39:20', 0, '2026-05-25 09:39:20', 0, NULL),
(21, 'Admitad', NULL, NULL, NULL, NULL, 1, 0, '2026-05-25 09:40:46', 0, '2026-05-25 09:40:46', 0, NULL),
(22, 'Adalyser', NULL, NULL, NULL, NULL, 1, 0, '2026-05-25 09:41:03', 0, '2026-05-25 09:41:03', 0, NULL),
(23, 'Boostiny', NULL, NULL, NULL, NULL, 1, 0, '2026-05-25 09:41:33', 0, '2026-05-25 09:41:33', 0, NULL),
(24, 'GoogleAds', NULL, NULL, NULL, NULL, 1, 0, '2026-05-25 09:42:03', 0, '2026-05-25 09:42:03', 0, NULL),
(25, 'GoogleAdsRemarketing', NULL, NULL, NULL, NULL, 1, 0, '2026-05-25 09:43:44', 0, '2026-05-25 09:43:44', 0, NULL),
(26, 'Bing', NULL, NULL, NULL, NULL, 1, 0, '2026-06-01 10:38:38', 0, '2026-06-01 10:38:38', 0, NULL),
(27, 'Gelirortaklari', NULL, NULL, NULL, NULL, 1, 0, '2026-06-01 10:41:26', 0, '2026-06-01 10:41:26', 0, NULL),
(28, 'GelirortaklariConversion', NULL, NULL, NULL, NULL, 1, 0, '2026-06-01 10:51:13', 0, '2026-06-01 10:51:13', 0, NULL),
(29, 'Teads', NULL, NULL, NULL, NULL, 1, 0, '2026-06-03 17:41:58', 0, '2026-06-03 17:41:58', 0, NULL),
(30, 'Taboola', NULL, NULL, NULL, NULL, 1, 0, '2026-06-03 17:41:58', 0, '2026-06-03 17:41:58', 0, NULL),
(31, 'GoogleDisplayVideo', NULL, NULL, NULL, NULL, 1, 0, '2026-06-03 17:41:58', 0, '2026-06-03 17:41:58', 0, NULL),
(32, 'Outbrain', NULL, NULL, NULL, NULL, 1, 0, '2026-06-03 17:41:58', 0, '2026-06-03 17:41:58', 0, NULL),
(33, 'TradeDesk', NULL, NULL, NULL, NULL, 1, 0, '2026-06-03 17:41:58', 0, '2026-06-03 17:41:58', 0, NULL),
(34, 'Facebook', NULL, NULL, NULL, NULL, 1, 0, '2026-06-03 17:41:58', 0, '2026-06-03 17:41:58', 0, NULL),
(35, 'ArabyAds', NULL, NULL, NULL, NULL, 1, 0, '2026-06-03 17:41:58', 0, '2026-06-03 17:41:58', 0, NULL),
(36, 'WaffarX', NULL, NULL, NULL, NULL, 1, 0, '2026-06-03 17:41:58', 0, '2026-06-03 17:41:58', 0, NULL),
(37, 'ESKIMI', NULL, NULL, NULL, NULL, 1, 0, '2026-06-03 17:41:58', 0, '2026-06-03 17:41:58', 0, NULL),
(38, 'Bambuser', NULL, NULL, NULL, NULL, 1, 0, '2026-06-03 17:41:58', 0, '2026-06-03 17:41:58', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_log_comments`
--
ALTER TABLE `activity_log_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_log_files`
--
ALTER TABLE `activity_log_files`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `renewal_justification`
--
ALTER TABLE `renewal_justification`
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
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `activity_log_comments`
--
ALTER TABLE `activity_log_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `activity_log_files`
--
ALTER TABLE `activity_log_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `jira_history`
--
ALTER TABLE `jira_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=357;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `renewal_justification`
--
ALTER TABLE `renewal_justification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `renewal_logs`
--
ALTER TABLE `renewal_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `subsidiaries`
--
ALTER TABLE `subsidiaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=491;

--
-- AUTO_INCREMENT for table `tag_requests`
--
ALTER TABLE `tag_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `tag_request_files`
--
ALTER TABLE `tag_request_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
