-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2024 at 03:23 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `insight_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `activity_type` enum('upload','resubmit','status update') NOT NULL,
  `activity_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`log_id`, `file_id`, `activity_type`, `activity_date`) VALUES
(38, 19, 'upload', '2024-12-04 17:49:06'),
(39, 19, 'status update', '2024-12-04 17:50:57');

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `announcement_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `starting_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `type` enum('schedule and activities','Notes') NOT NULL,
  `status` enum('Active','Declined','Pending','Hidden') NOT NULL DEFAULT 'Pending',
  `reason` varchar(200) NOT NULL DEFAULT 'N/A',
  `announcementPosted` datetime NOT NULL DEFAULT current_timestamp(),
  `announcementUpdated` datetime NOT NULL DEFAULT current_timestamp(),
  `SchedAct_targetViewer` varchar(200) DEFAULT 'All',
  `mailingStatus` enum('pending','sending','sent','failed') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`announcement_id`, `user_id`, `title`, `description`, `starting_date`, `end_date`, `type`, `status`, `reason`, `announcementPosted`, `announcementUpdated`, `SchedAct_targetViewer`, `mailingStatus`) VALUES
(21, 1, 'OJT Orientation', '', '2024-12-16', '2024-12-20', 'schedule and activities', 'Active', 'N/A', '2024-12-16 09:20:22', '2024-12-16 09:20:22', 'All', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `narrativereports`
--

CREATE TABLE `narrativereports` (
  `narrative_id` int(11) NOT NULL,
  `enrolled_stud_id` int(11) NOT NULL,
  `ojt_adv_id` int(11) DEFAULT NULL,
  `ay_sem_id` int(11) DEFAULT NULL,
  `narrative_file_name` varchar(200) NOT NULL,
  `upload_date` datetime NOT NULL DEFAULT current_timestamp(),
  `file_status` enum('Pending','Declined','Approved','Archived') NOT NULL DEFAULT 'Pending',
  `remarks` varchar(200) NOT NULL DEFAULT 'N/A',
  `narrativeConvertJobID` varchar(200) DEFAULT NULL,
  `convertStatus` enum('pending','processing','success','failed','N/A') NOT NULL DEFAULT 'N/A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `narrativereports`
--

INSERT INTO `narrativereports` (`narrative_id`, `enrolled_stud_id`, `ojt_adv_id`, `ay_sem_id`, `narrative_file_name`, `upload_date`, `file_status`, `remarks`, `narrativeConvertJobID`, `convertStatus`) VALUES
(38, 210101249, 44, 21, '210101249_6750263f80b130.49754560.pdf', '2024-12-04 17:51:59', 'Approved', 'N/A', NULL, 'success'),
(39, 210101241, 44, 21, '210101241_67502f548ef8c9.89423551.pdf', '2024-12-04 18:30:44', 'Declined', 'bnbnbbb', NULL, 'N/A');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `program_id` int(11) NOT NULL,
  `program_code` varchar(10) NOT NULL,
  `program_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`program_id`, `program_code`, `program_name`) VALUES
(2, 'BSIT', 'Bachelor of Science in Information Technology'),
(3, 'BSInT', 'Bachelor of Science in Industrial Technology'),
(4, 'BSBA - MM', 'Bachelor of Science in Business Administration - Marketing Management'),
(5, 'BSBA - HCM', 'Bachelor of Science in Business Administration - Human Resource Management'),
(7, 'BSCS', 'Bachelor of Science in Computer Science'),
(8, 'BSHM', 'Bachelor of Science in Hospitality Management');

-- --------------------------------------------------------

--
-- Table structure for table `revision_attachment`
--

CREATE TABLE `revision_attachment` (
  `attachment_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `attach_img_file_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revision_attachment`
--

INSERT INTO `revision_attachment` (`attachment_id`, `comment_id`, `attach_img_file_name`) VALUES
(58, 31, '675025a1245a7.jpg'),
(59, 31, '675025a139c2a.jpg'),
(60, 31, '675025a13d2df.jpg'),
(61, 32, '675025fee9910.jpg'),
(62, 32, '675025feeb976.jpg'),
(63, 32, '675025feed3f7.jpg'),
(64, 32, '675025feee9aa.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `year_sec_Id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `section` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`year_sec_Id`, `year`, `section`) VALUES
(1, 4, 'A'),
(2, 4, 'B'),
(3, 4, 'C'),
(4, 4, 'D'),
(5, 1, 'A'),
(6, 2, 'A'),
(7, 2, 'B'),
(8, 3, 'B'),
(9, 3, 'C'),
(10, 3, 'A'),
(13, 1, 'B'),
(14, 1, 'C'),
(15, 1, 'D'),
(16, 2, 'C');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_accounts`
--

CREATE TABLE `tbl_accounts` (
  `acc_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `OTP` int(11) DEFAULT NULL,
  `OTP_generated_Date` timestamp NULL DEFAULT NULL,
  `OTP_expiration_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_accounts`
--

INSERT INTO `tbl_accounts` (`acc_id`, `user_id`, `email`, `password`, `date_created`, `status`, `OTP`, `OTP_generated_Date`, `OTP_expiration_Date`) VALUES
(1, 1, 'cc.riocarl.delacruz@cvsu.edu.ph', '$2y$10$GWjkOPQ5hHbBOfnIWXsksuayQKC76sCKm6ROtKge8B6NpUGpqYrqS', '2024-09-25 06:39:35', 'active', NULL, NULL, NULL),
(3, 4, 'riodelacruz0258@gmail.com', '$2y$10$6bTSOvUdu4Vd7V8nnsB3IOQ.vVAX837v5SuRPuqBnZayApPZtortS', '2024-09-27 02:17:01', 'active', NULL, NULL, NULL),
(28, 44, 'jhumelignas123@cvsu.edu.ph', '$2y$10$wHMAaMf2ngzMVFif32/raeiyMeT96.UTIUrfgpMs1w870ALq3K1Zm', '2024-10-20 05:28:08', 'active', NULL, NULL, NULL),
(31, 47, 'riodelacruz033@gmail.com', '$2y$10$ipyJc/.0Rxbc37v2Ot2aP.azvG0PoKclor6ZddsafJVW6OkDQL0Vu', '2024-11-05 10:42:06', 'inactive', NULL, NULL, NULL),
(80, 97, 'juan@gmail.com', '$2y$10$lvjVSO9WrBOYGH2oHxZH/O7t7axWAKsrcmc/pOTJgLSt9DV1SESwK', '2024-12-04 08:59:18', 'active', NULL, NULL, NULL),
(81, 98, 'maria@gmail.com', '$2y$10$2CN3wKWFqxBONTsSqocJJu4RxvbLhTf2p24ARl.FXdGKNiI/tu97C', '2024-12-04 08:59:18', 'active', NULL, NULL, NULL),
(82, 99, 'pedro@gmail.com', '$2y$10$oN6T8LLv0Qgn19DPO0AzD.kUI7LtgVuROCyhsFBc.UywQzWjpZRM6', '2024-12-04 08:59:18', 'active', NULL, NULL, NULL),
(83, 100, 'ana@gmail.com', '$2y$10$ZPsutvFN6SmFJbOy6JvaB.fUUrPXYgOV4SeJt7.c.iKe5GQws8yo.', '2024-12-04 08:59:18', 'active', NULL, NULL, NULL),
(84, 101, 'miguel@gmail.com', '$2y$10$8t8NXfMY7WmFun15p55G6.mUxKUmE1DC6PU9iM3mBV2BVEFhBX9cG', '2024-12-04 08:59:18', 'active', NULL, NULL, NULL),
(85, 102, 'carla@gmail.com', '$2y$10$vxZgZTYHw.pnYigGwbRKeewLWtnE/00XBxOfgHTTdKunJMQFJLbxm', '2024-12-04 08:59:18', 'active', NULL, NULL, NULL),
(86, 103, 'luis@gmail.com', '$2y$10$y8oB.DTC9BJypgF5egp8peVB8UJDBPcEqx36/xg3Q1JjqIIaYPf.O', '2024-12-04 08:59:18', 'active', NULL, NULL, NULL),
(87, 104, 'sofia@gmail.com', '$2y$10$eF7M6jF/hYIxc0vp6Xe.YONm/IFO7QJ7sAHcFo99M76.wEV7bakRK', '2024-12-04 08:59:18', 'active', NULL, NULL, NULL),
(88, 105, 'jose@gmail.com', '$2y$10$.3KgbCbBSFWsA/q5m/xgrOJruesoCxW1WZ254btsgVxokguuZCvbm', '2024-12-04 08:59:19', 'active', NULL, NULL, NULL),
(89, 106, 'paula@gmail.com', '$2y$10$0vPvd3kISLSOVGYr5guZDuGNlWysSOtApUItEAPqFbVuFH2C9hQgK', '2024-12-04 08:59:19', 'active', NULL, NULL, NULL),
(90, 107, 'adrian@gmail.com', '$2y$10$KXmDFvE0HwCGGtNs45QeN.i8Q3bJvapFA057.szGEzPTB9Jv11koS', '2024-12-04 08:59:19', 'active', NULL, NULL, NULL),
(91, 108, 'julia@gmail.com', '$2y$10$HY42m2MOwtyXhZZgk3Bdp.PgEQPUeaKKCx0AiYiirAQoUscC9YRlW', '2024-12-04 08:59:19', 'active', NULL, NULL, NULL),
(92, 109, 'marco@gmail.com', '$2y$10$YSZPgGdJ6q1vDbmK/b9PqO/2G1l9Gl.RtYsjdwfQI1MunqHA8uFkG', '2024-12-04 08:59:19', 'active', NULL, NULL, NULL),
(93, 110, 'elena@gmail.com', '$2y$10$s51IHoWvEIGa0OR3T3u2BeSB1nM5oZ/uy0xjFWpzi5QuYfLxeJZ8O', '2024-12-04 08:59:19', 'active', NULL, NULL, NULL),
(94, 111, 'carlos@gmail.com', '$2y$10$VZ3zVHr8NAHl8k2oZ0hc9OQOWhWJDd/YtPC5gWNPjVaAa5ZbUE18e', '2024-12-04 08:59:19', 'active', NULL, NULL, NULL),
(95, 112, 'isabel@gmail.com', '$2y$10$gwnc/Uw.rgPo7e5bYGYyUu4bB2lp1Wgtcxz3Buwd.bXu.vVULkQHW', '2024-12-04 08:59:19', 'active', NULL, NULL, NULL),
(96, 113, 'diego@gmail.com', '$2y$10$QcgVx50y4TPbD8l6fRBOqeUPbgr2Q9xNN2cpKtpCdYNKUgewFhgWu', '2024-12-04 08:59:19', 'active', NULL, NULL, NULL),
(97, 114, 'clara@gmail.com', '$2y$10$7a8c7cK9u50pHJCRHTOX7.7EP3ddnEQqBrUbWNMe8KZ6y7dhXycFW', '2024-12-04 08:59:19', 'active', NULL, NULL, NULL),
(98, 115, 'pablo@gmail.com', '$2y$10$o4.wsvVF2t9FaKw1eFmIHeE3EQ1UJfSkc9kjoAAJOUFxIAY7migM2', '2024-12-04 08:59:20', 'active', NULL, NULL, NULL),
(99, 116, 'monica@gmail.com', '$2y$10$.aNOIojlwSDOUQctU7s2Hu6Yq8ArDM1AoRr9qP4aTt6MyLSAcl3kW', '2024-12-04 08:59:20', 'active', NULL, NULL, NULL),
(100, 117, 'jak1e@gmail.com', '$2y$10$DvrzWSkp3Umb9QLjmCiS1OriOUWjxsyiVAoVhplmAkwTVsIzxLOd.', '2024-12-04 09:00:01', 'active', NULL, NULL, NULL),
(101, 118, 'ma2ria@gmail.com', '$2y$10$sz.So.HxDB.Bvil5nkx/MeH49VMDLletbNinn7uw89G7K7mFbFU1u', '2024-12-04 09:00:01', 'active', NULL, NULL, NULL),
(102, 119, '3ethan@gmail.com', '$2y$10$yWBL3eviMF/IYSUVdUpCk.pFzFJQqnkxaBKMS81ssdVgUuolPe/jS', '2024-12-04 09:00:01', 'active', NULL, NULL, NULL),
(103, 120, 'sophia1@gmail.com', '$2y$10$hPIch51tm81wOf3RiViVo.MRtj2uc.uTMJ5sAOuf09qHLghpxr8wK', '2024-12-04 09:00:01', 'active', NULL, NULL, NULL),
(104, 121, 'noa2h@gmail.com', '$2y$10$qhhuUFtD9RjW4Mrn1Qiqp.lTj5CbaTwhJVu0J9M2O2JGHeIAC.oYy', '2024-12-04 09:00:01', 'active', NULL, NULL, NULL),
(105, 122, 'grac3e@gmail.com', '$2y$10$hh9tBChxl4EhTreDxHnIsuAcKEEydr.raE1.H6S4LHHzUaAsCoJWO', '2024-12-04 09:00:28', 'active', NULL, NULL, NULL),
(106, 123, 'liam3@gmail.com', '$2y$10$EBOWWPlt.NNrmCUgP5etBublTUw0AdJHTGRcoFWehcQDPmS4GySxO', '2024-12-04 09:00:28', 'active', NULL, NULL, NULL),
(107, 124, 'chl6oe@gmail.com', '$2y$10$MmxffpG7ZWxOGDa6PqnocedNrepCySUgxB2H3NrQCIEu3Nvm52.w2', '2024-12-04 09:00:28', 'active', NULL, NULL, NULL),
(108, 125, 'mas1on@gmail.com', '$2y$10$0lAB1eZF98CCGB/nf2OPRe1.zl9KLAwsLzz8LgGSa2oi1PPmCoYfm', '2024-12-04 09:00:28', 'active', NULL, NULL, NULL),
(109, 126, 'isab2ella@gmail.com', '$2y$10$UcNlHPwL5UEc5LG.tqeYcOf0RomHRRki2F8.XgfK6psnnAnKZfHhS', '2024-12-04 09:00:28', 'active', NULL, NULL, NULL),
(110, 127, 'elijah2@gmail.com', '$2y$10$PTyP.J2mFXVekHQ94FV4oOHs8WmGQquHkDi7ngWZ5wOgbxb//9Qb6', '2024-12-04 09:02:37', 'active', NULL, NULL, NULL),
(111, 128, 'ava@gmail.com', '$2y$10$NMVMCEzexYe/al9JSNP42.CgCs03UQedeECo4EywEyzZMtT682kya', '2024-12-04 09:02:37', 'active', NULL, NULL, NULL),
(112, 129, 'lucas3@gmail.com', '$2y$10$Ye7yGfJgrhdeTFJ0Y/Gt9Oj0NLhZut2Tj4zFhq/RYbjCJRMm4fAX2', '2024-12-04 09:02:37', 'active', NULL, NULL, NULL),
(113, 130, 'olivia@gmail.com', '$2y$10$/8sSy6xD1K13uSVDjRagcONCJR.NPq7FxRjIPDeI5B90Uam2z9nwe', '2024-12-04 09:02:37', 'active', NULL, NULL, NULL),
(114, 131, 'jackson@gmail.com', '$2y$10$Ljq5WvaJ58qadXL/EdMTQugwqYRXViKawl22XtManHuFNYkTHtp4S', '2024-12-04 09:02:37', 'active', NULL, NULL, NULL),
(115, 132, 'mia@gmail.com', '$2y$10$EJRuezCUn/3MzdGIEVTfQegiDSzClbWMr.SPpDEBdu/KBfM/FET3u', '2024-12-04 09:02:58', 'active', NULL, NULL, NULL),
(116, 133, 'aiden@gmail.com', '$2y$10$w3gn6khJppTfzrvB1PGG9ugUr2qwv/GW8/xxHfyTZi6QWbfpCiulC', '2024-12-04 09:02:58', 'active', NULL, NULL, NULL),
(117, 134, 'emma@gmail.com', '$2y$10$ZcfUZAerJYvhn0myt3GMwuPL901ikeBll1Jd2fyZunFT4oCXmHMtm', '2024-12-04 09:02:58', 'active', NULL, NULL, NULL),
(118, 135, 'logan@gmail.com', '$2y$10$DYO/yWCSNvdgb3aMEH8hn.P8sn2eKwbi5anLEd/xLQavqQ2w4V5j.', '2024-12-04 09:02:58', 'active', NULL, NULL, NULL),
(119, 136, 'harper@gmail.com', '$2y$10$3crSfWxooyPfnmbptPJ3JuWzLUXgi1bO88yD02wQxBVFV60znxUFS', '2024-12-04 09:02:58', 'active', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_advisoryhandle`
--

CREATE TABLE `tbl_advisoryhandle` (
  `adv_handle_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `adv_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_advisoryhandle`
--

INSERT INTO `tbl_advisoryhandle` (`adv_handle_id`, `program_id`, `adv_id`) VALUES
(156, 2, 44),
(157, 3, 4),
(158, 8, 47);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_aysem`
--

CREATE TABLE `tbl_aysem` (
  `id` int(11) NOT NULL,
  `ayStarting` int(11) NOT NULL,
  `ayEnding` int(11) NOT NULL,
  `Semester` enum('First','Second','Midyear','') NOT NULL,
  `Curray_sem` enum('Yes','No') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_aysem`
--

INSERT INTO `tbl_aysem` (`id`, `ayStarting`, `ayEnding`, `Semester`, `Curray_sem`) VALUES
(6, 2023, 2024, 'First', 'No'),
(20, 2023, 2024, 'Second', 'No'),
(21, 2023, 2024, 'Midyear', 'Yes'),
(22, 2024, 2025, 'First', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_courseavailability`
--

CREATE TABLE `tbl_courseavailability` (
  `course_availability_id` int(11) NOT NULL,
  `course_code_id` int(11) NOT NULL,
  `year_sec_Id` int(11) NOT NULL,
  `ay_sem_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_courseavailability`
--

INSERT INTO `tbl_courseavailability` (`course_availability_id`, `course_code_id`, `year_sec_Id`, `ay_sem_id`) VALUES
(324, 1, 1, 20),
(325, 1, 2, 20),
(326, 1, 3, 20),
(327, 1, 4, 20),
(328, 14, 1, 20),
(329, 14, 2, 20),
(330, 15, 10, 20),
(331, 15, 8, 20),
(332, 20, 10, 20),
(333, 20, 8, 20),
(334, 20, 9, 20),
(335, 3, 1, 20),
(336, 3, 2, 20),
(337, 3, 3, 20),
(338, 3, 4, 20),
(339, 8, 1, 20),
(340, 8, 2, 20),
(341, 8, 3, 20),
(342, 8, 4, 20),
(343, 14, 1, 21),
(344, 1, 1, 21),
(345, 6, 5, 21),
(346, 6, 13, 21),
(347, 6, 14, 21),
(348, 6, 15, 21),
(349, 14, 1, 22),
(350, 14, 2, 22),
(351, 14, 3, 22),
(352, 1, 1, 22),
(353, 15, 10, 22),
(354, 7, 1, 22),
(355, 7, 2, 22),
(368, 2, 6, 6),
(369, 2, 7, 6),
(370, 6, 1, 6),
(371, 6, 2, 6),
(372, 6, 3, 6),
(373, 6, 4, 6),
(374, 1, 1, 6),
(375, 1, 2, 6),
(376, 1, 3, 6),
(377, 1, 4, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_course_code`
--

CREATE TABLE `tbl_course_code` (
  `course_code_id` int(11) NOT NULL,
  `course_code` varchar(200) NOT NULL,
  `OJT_hours` int(11) NOT NULL,
  `program_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_course_code`
--

INSERT INTO `tbl_course_code` (`course_code_id`, `course_code`, `OJT_hours`, `program_id`) VALUES
(1, 'ITEC 199', 486, 2),
(2, 'BSHM 199A', 300, 8),
(3, 'BSHM 199B', 600, 8),
(6, 'ELEX 199A', 240, 3),
(7, 'ELEX 199B', 640, 3),
(8, 'ELEX 199C', 640, 3),
(14, 'COSC', 240, 7),
(15, 'BMGY 199', 600, 4),
(20, 'BMGY 199', 600, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_revision`
--

CREATE TABLE `tbl_revision` (
  `comment_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_revision`
--

INSERT INTO `tbl_revision` (`comment_id`, `file_id`, `user_id`, `comment`, `comment_date`) VALUES
(31, 19, 115, 'OK', '2024-12-04 09:49:21'),
(32, 19, 44, 'OK', '2024-12-04 09:50:54');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_students`
--

CREATE TABLE `tbl_students` (
  `enrolled_stud_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `adv_id` int(11) DEFAULT NULL,
  `program_id` int(11) NOT NULL,
  `course_code_id` int(11) DEFAULT NULL,
  `year_sec_Id` int(11) DEFAULT NULL,
  `ojt_center` varchar(200) DEFAULT 'N/A',
  `ojt_contact` text DEFAULT 'N/A',
  `OJT_started` date DEFAULT NULL,
  `OJT_ended` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_students`
--

INSERT INTO `tbl_students` (`enrolled_stud_id`, `user_id`, `adv_id`, `program_id`, `course_code_id`, `year_sec_Id`, `ojt_center`, `ojt_contact`, `OJT_started`, `OJT_ended`) VALUES
(210101231, 97, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101232, 98, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101233, 99, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101234, 100, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101235, 101, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101236, 102, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101237, 103, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101238, 104, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101239, 105, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101240, 106, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101241, 107, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101242, 108, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101243, 109, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101244, 110, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101245, 111, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101246, 112, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101247, 113, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101248, 114, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101249, 115, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(210101250, 116, 44, 2, 1, 1, 'N/A', 'N/A', NULL, NULL),
(230301101, 117, 4, 3, 6, 5, 'N/A', 'N/A', NULL, NULL),
(230301102, 118, 4, 3, 6, 5, 'N/A', 'N/A', NULL, NULL),
(230301103, 119, 4, 3, 6, 5, 'N/A', 'N/A', NULL, NULL),
(230301104, 120, 4, 3, 6, 5, 'N/A', 'N/A', NULL, NULL),
(230301105, 121, 4, 3, 6, 5, 'N/A', 'N/A', NULL, NULL),
(230301106, 122, 4, 3, 6, 13, 'N/A', 'N/A', NULL, NULL),
(230301107, 123, 4, 3, 6, 13, 'N/A', 'N/A', NULL, NULL),
(230301108, 124, 4, 3, 6, 13, 'N/A', 'N/A', NULL, NULL),
(230301109, 125, 4, 3, 6, 13, 'N/A', 'N/A', NULL, NULL),
(230301110, 126, 4, 3, 6, 13, 'N/A', 'N/A', NULL, NULL),
(230301111, 127, 4, 3, 6, 14, 'N/A', 'N/A', NULL, NULL),
(230301112, 128, 4, 3, 6, 14, 'N/A', 'N/A', NULL, NULL),
(230301113, 129, 4, 3, 6, 14, 'N/A', 'N/A', NULL, NULL),
(230301114, 130, 4, 3, 6, 14, 'N/A', 'N/A', NULL, NULL),
(230301115, 131, 4, 3, 6, 14, 'N/A', 'N/A', NULL, NULL),
(230301116, 132, 4, 3, 6, 15, 'N/A', 'N/A', NULL, NULL),
(230301117, 133, 4, 3, 6, 15, 'N/A', 'N/A', NULL, NULL),
(230301118, 134, 4, 3, 6, 15, 'N/A', 'N/A', NULL, NULL),
(230301119, 135, 4, 3, 6, 15, 'N/A', 'N/A', NULL, NULL),
(230301120, 136, 4, 3, 6, 15, 'N/A', 'N/A', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_info`
--

CREATE TABLE `tbl_user_info` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(200) NOT NULL DEFAULT 'N/A',
  `last_name` varchar(50) NOT NULL,
  `address` varchar(255) DEFAULT 'N/A',
  `contact_number` varchar(11) DEFAULT NULL,
  `sex` enum('male','female') NOT NULL,
  `profile_img_file` varchar(255) NOT NULL DEFAULT 'N/A',
  `user_type` enum('admin','adviser','student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user_info`
--

INSERT INTO `tbl_user_info` (`user_id`, `first_name`, `middle_name`, `last_name`, `address`, `contact_number`, `sex`, `profile_img_file`, `user_type`) VALUES
(1, 'Rio', 'N/A', 'De La Cruz', 'Binan', '09498349649', 'male', '135f8a7f97c0426fecd4928144917bc0.png', 'admin'),
(4, 'Kanye', 'middle', 'West', 'Sorento', '09512223111', 'male', '3fc5b7c902f1d69105fdd20e8646002b.png', 'adviser'),
(44, 'Jhumel', 'N/A', 'Ignas', 'Carmona', '9244121154', 'male', '7f9fb5f884e008d9bbf115c15efa5646.png', 'adviser'),
(47, 'James', 'N/A', 'Weller', 'Sorento', '9512223111', 'male', 'N/A', 'adviser'),
(97, 'Juan', 'MiddleName', 'Delacruz', 'N/A', NULL, 'male', 'N/A', 'student'),
(98, 'Maria', 'MiddleName', 'Santos', 'N/A', NULL, 'female', 'N/A', 'student'),
(99, 'Pedro', 'MiddleName', 'Reyes', 'N/A', NULL, 'male', 'N/A', 'student'),
(100, 'Ana', 'MiddleName', 'Garcia', 'N/A', NULL, 'female', 'N/A', 'student'),
(101, 'Miguel', 'MiddleName', 'Cruz', 'N/A', NULL, 'male', 'N/A', 'student'),
(102, 'Carla', 'MiddleName', 'Lopez', 'N/A', NULL, 'female', 'N/A', 'student'),
(103, 'Luis', 'MiddleName', 'Torres', 'N/A', NULL, 'male', 'N/A', 'student'),
(104, 'Sofia', 'MiddleName', 'Flores', 'N/A', NULL, 'female', 'N/A', 'student'),
(105, 'Jose', 'MiddleName', 'Martinez', 'N/A', NULL, 'male', 'N/A', 'student'),
(106, 'Paula', 'MiddleName', 'Ramos', 'N/A', NULL, 'female', 'N/A', 'student'),
(107, 'Adrian', 'MiddleName', 'Dominguez', 'N/A', NULL, 'male', 'N/A', 'student'),
(108, 'Julia', 'MiddleName', 'Hernandez', 'N/A', NULL, 'female', 'N/A', 'student'),
(109, 'Marco', 'MiddleName', 'Vargas', 'N/A', NULL, 'male', 'N/A', 'student'),
(110, 'Elena', 'MiddleName', 'Gutierrez', 'N/A', NULL, 'female', 'N/A', 'student'),
(111, 'Carlos', 'MiddleName', 'Mendoza', 'N/A', NULL, 'male', 'N/A', 'student'),
(112, 'Isabel', 'MiddleName', 'Fernandez', 'N/A', NULL, 'female', 'N/A', 'student'),
(113, 'Diego', 'MiddleName', 'Ortiz', 'N/A', NULL, 'male', 'N/A', 'student'),
(114, 'Clara', 'MiddleName', 'Morales', 'N/A', NULL, 'female', 'N/A', 'student'),
(115, 'Pablo', 'MiddleName', 'Rivera', 'N/A', NULL, 'male', 'N/A', 'student'),
(116, 'Monica', 'MiddleName', 'Alvarez', 'N/A', NULL, 'female', 'N/A', 'student'),
(117, 'Jake', 'MiddleName', 'Alonso', 'N/A', NULL, 'male', 'N/A', 'student'),
(118, 'Maria', 'MiddleName', 'Blanco', 'N/A', NULL, 'female', 'N/A', 'student'),
(119, 'Ethan', 'MiddleName', 'Carvajal', 'N/A', NULL, 'male', 'N/A', 'student'),
(120, 'Sophia', 'MiddleName', 'Duarte', 'N/A', NULL, 'female', 'N/A', 'student'),
(121, 'Noah', 'MiddleName', 'Espino', 'N/A', NULL, 'male', 'N/A', 'student'),
(122, 'Grace', 'MiddleName', 'Fernandez', 'N/A', NULL, 'female', 'N/A', 'student'),
(123, 'Liam', 'MiddleName', 'Garcia', 'N/A', NULL, 'male', 'N/A', 'student'),
(124, 'Chloe', 'MiddleName', 'Hernandez', 'N/A', NULL, 'female', 'N/A', 'student'),
(125, 'Mason', 'MiddleName', 'Iglesias', 'N/A', NULL, 'male', 'N/A', 'student'),
(126, 'Isabella', 'MiddleName', 'Juarez', 'N/A', NULL, 'female', 'N/A', 'student'),
(127, 'Elijah', 'MiddleName', 'Kim', 'N/A', NULL, 'male', 'N/A', 'student'),
(128, 'Ava', 'MiddleName', 'Lopez', 'N/A', NULL, 'female', 'N/A', 'student'),
(129, 'Lucas', 'MiddleName', 'Martinez', 'N/A', NULL, 'male', 'N/A', 'student'),
(130, 'Olivia', 'MiddleName', 'Nieves', 'N/A', NULL, 'female', 'N/A', 'student'),
(131, 'Jackson', 'MiddleName', 'Ortega', 'N/A', NULL, 'male', 'N/A', 'student'),
(132, 'Mia', 'MiddleName', 'Perez', 'N/A', '09543332225', 'female', 'N/A', 'student'),
(133, 'Aiden', 'MiddleName', 'Quintero', 'N/A', NULL, 'male', 'N/A', 'student'),
(134, 'Emma', 'MiddleName', 'Rodriguez', 'N/A', NULL, 'female', 'N/A', 'student'),
(135, 'Logan', 'MiddleName', 'Santiago', 'N/A', NULL, 'male', 'N/A', 'student'),
(136, 'Harper', 'MiddleName', 'Torres', 'N/A', NULL, 'female', 'N/A', 'student');

-- --------------------------------------------------------

--
-- Table structure for table `weeklyreport`
--

CREATE TABLE `weeklyreport` (
  `file_id` int(11) NOT NULL,
  `stud_user_id` int(11) NOT NULL,
  `weeklyFileReport` text NOT NULL,
  `week` varchar(200) NOT NULL,
  `upload_date` datetime NOT NULL,
  `upload_status` enum('pending','approved','revision') NOT NULL,
  `readStatus` enum('Read','Unread') NOT NULL DEFAULT 'Unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weeklyreport`
--

INSERT INTO `weeklyreport` (`file_id`, `stud_user_id`, `weeklyFileReport`, `week`, `upload_date`, `upload_status`, `readStatus`) VALUES
(19, 115, '210101249_WeeklyJournal_week_675025924bc4e4.64482812.pdf', 'Dec 1, 2024 - Dec 8, 2024', '2024-12-04 17:49:06', 'approved', 'Read');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `file_id` (`file_id`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `narrativereports`
--
ALTER TABLE `narrativereports`
  ADD PRIMARY KEY (`narrative_id`),
  ADD UNIQUE KEY `conversionJobId` (`narrativeConvertJobID`),
  ADD KEY `enrolled_stud_id` (`enrolled_stud_id`),
  ADD KEY `ojt_adv` (`ojt_adv_id`),
  ADD KEY `ay_sem_id` (`ay_sem_id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`program_id`),
  ADD UNIQUE KEY `program_name` (`program_name`),
  ADD UNIQUE KEY `program_code` (`program_code`);

--
-- Indexes for table `revision_attachment`
--
ALTER TABLE `revision_attachment`
  ADD PRIMARY KEY (`attachment_id`),
  ADD KEY `comment_id` (`comment_id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`year_sec_Id`);

--
-- Indexes for table `tbl_accounts`
--
ALTER TABLE `tbl_accounts`
  ADD PRIMARY KEY (`acc_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_advisoryhandle`
--
ALTER TABLE `tbl_advisoryhandle`
  ADD PRIMARY KEY (`adv_handle_id`),
  ADD KEY `handle_orogm_Id` (`program_id`),
  ADD KEY `handle_adv` (`adv_id`);

--
-- Indexes for table `tbl_aysem`
--
ALTER TABLE `tbl_aysem`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_courseavailability`
--
ALTER TABLE `tbl_courseavailability`
  ADD PRIMARY KEY (`course_availability_id`),
  ADD KEY `ay_sem` (`ay_sem_id`),
  ADD KEY `coursecde` (`course_code_id`),
  ADD KEY `yrSec` (`year_sec_Id`);

--
-- Indexes for table `tbl_course_code`
--
ALTER TABLE `tbl_course_code`
  ADD PRIMARY KEY (`course_code_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `tbl_revision`
--
ALTER TABLE `tbl_revision`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `file_id` (`file_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_students`
--
ALTER TABLE `tbl_students`
  ADD PRIMARY KEY (`enrolled_stud_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `advisor_id` (`adv_id`),
  ADD KEY `year_sec_Id` (`year_sec_Id`),
  ADD KEY `course_code_id` (`course_code_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `tbl_user_info`
--
ALTER TABLE `tbl_user_info`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `contact_number` (`contact_number`);

--
-- Indexes for table `weeklyreport`
--
ALTER TABLE `weeklyreport`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `stud_user_id` (`stud_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `narrativereports`
--
ALTER TABLE `narrativereports`
  MODIFY `narrative_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `revision_attachment`
--
ALTER TABLE `revision_attachment`
  MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `year_sec_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_accounts`
--
ALTER TABLE `tbl_accounts`
  MODIFY `acc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `tbl_advisoryhandle`
--
ALTER TABLE `tbl_advisoryhandle`
  MODIFY `adv_handle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `tbl_aysem`
--
ALTER TABLE `tbl_aysem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbl_courseavailability`
--
ALTER TABLE `tbl_courseavailability`
  MODIFY `course_availability_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=378;

--
-- AUTO_INCREMENT for table `tbl_course_code`
--
ALTER TABLE `tbl_course_code`
  MODIFY `course_code_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_revision`
--
ALTER TABLE `tbl_revision`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbl_students`
--
ALTER TABLE `tbl_students`
  MODIFY `enrolled_stud_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230301121;

--
-- AUTO_INCREMENT for table `tbl_user_info`
--
ALTER TABLE `tbl_user_info`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `weeklyreport`
--
ALTER TABLE `weeklyreport`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `weeklyreport` (`file_id`);

--
-- Constraints for table `announcement`
--
ALTER TABLE `announcement`
  ADD CONSTRAINT `announcement_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user_info` (`user_id`);

--
-- Constraints for table `narrativereports`
--
ALTER TABLE `narrativereports`
  ADD CONSTRAINT `ay_sem_id` FOREIGN KEY (`ay_sem_id`) REFERENCES `tbl_aysem` (`id`),
  ADD CONSTRAINT `narrativereports_ibfk_2` FOREIGN KEY (`enrolled_stud_id`) REFERENCES `tbl_students` (`enrolled_stud_id`),
  ADD CONSTRAINT `ojt_adv` FOREIGN KEY (`ojt_adv_id`) REFERENCES `tbl_user_info` (`user_id`);

--
-- Constraints for table `revision_attachment`
--
ALTER TABLE `revision_attachment`
  ADD CONSTRAINT `revision_attachment_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `tbl_revision` (`comment_id`);

--
-- Constraints for table `tbl_accounts`
--
ALTER TABLE `tbl_accounts`
  ADD CONSTRAINT `tbl_accounts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user_info` (`user_id`);

--
-- Constraints for table `tbl_advisoryhandle`
--
ALTER TABLE `tbl_advisoryhandle`
  ADD CONSTRAINT `tbl_advisoryhandle_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `program` (`program_id`),
  ADD CONSTRAINT `tbl_advisoryhandle_ibfk_4` FOREIGN KEY (`adv_id`) REFERENCES `tbl_user_info` (`user_id`);

--
-- Constraints for table `tbl_courseavailability`
--
ALTER TABLE `tbl_courseavailability`
  ADD CONSTRAINT `ay_sem` FOREIGN KEY (`ay_sem_id`) REFERENCES `tbl_aysem` (`id`),
  ADD CONSTRAINT `coursecde` FOREIGN KEY (`course_code_id`) REFERENCES `tbl_course_code` (`course_code_id`),
  ADD CONSTRAINT `yrSec` FOREIGN KEY (`year_sec_Id`) REFERENCES `section` (`year_sec_Id`);

--
-- Constraints for table `tbl_course_code`
--
ALTER TABLE `tbl_course_code`
  ADD CONSTRAINT `tbl_course_code_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `program` (`program_id`);

--
-- Constraints for table `tbl_revision`
--
ALTER TABLE `tbl_revision`
  ADD CONSTRAINT `tbl_revision_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `weeklyreport` (`file_id`),
  ADD CONSTRAINT `tbl_revision_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_user_info` (`user_id`);

--
-- Constraints for table `tbl_students`
--
ALTER TABLE `tbl_students`
  ADD CONSTRAINT `tbl_students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user_info` (`user_id`),
  ADD CONSTRAINT `tbl_students_ibfk_2` FOREIGN KEY (`course_code_id`) REFERENCES `tbl_course_code` (`course_code_id`),
  ADD CONSTRAINT `tbl_students_ibfk_3` FOREIGN KEY (`year_sec_Id`) REFERENCES `section` (`year_sec_Id`),
  ADD CONSTRAINT `tbl_students_ibfk_4` FOREIGN KEY (`adv_id`) REFERENCES `tbl_user_info` (`user_id`),
  ADD CONSTRAINT `tbl_students_ibfk_5` FOREIGN KEY (`program_id`) REFERENCES `program` (`program_id`);

--
-- Constraints for table `weeklyreport`
--
ALTER TABLE `weeklyreport`
  ADD CONSTRAINT `weeklyreport_ibfk_1` FOREIGN KEY (`stud_user_id`) REFERENCES `tbl_user_info` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
