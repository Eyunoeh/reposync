-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2024 at 07:23 AM
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
-- Database: `reposync_db`
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
(2, 7, 'upload', '2024-05-06 15:29:32'),
(3, 12, 'upload', '2024-05-06 15:46:24'),
(4, 7, 'resubmit', '2024-05-06 16:02:44'),
(7, 7, 'status update', '2024-05-07 00:43:12'),
(8, 7, 'status update', '2024-05-07 00:43:44'),
(9, 7, 'status update', '2024-05-07 00:45:18'),
(10, 7, 'status update', '2024-05-07 00:45:24'),
(11, 7, 'status update', '2024-05-07 00:45:28'),
(12, 7, 'status update', '2024-05-07 00:45:36'),
(13, 7, 'status update', '2024-05-07 00:46:30'),
(14, 7, 'status update', '2024-05-07 00:46:35'),
(15, 7, 'status update', '2024-05-07 00:46:39'),
(16, 7, 'status update', '2024-05-07 00:49:37'),
(17, 7, 'status update', '2024-05-07 00:49:56'),
(18, 7, 'status update', '2024-05-07 00:50:00'),
(19, 12, 'status update', '2024-05-07 00:51:34'),
(20, 7, 'status update', '2024-05-07 00:52:13'),
(21, 13, 'upload', '2024-05-07 01:04:34'),
(22, 13, 'status update', '2024-05-07 15:53:12'),
(23, 13, 'status update', '2024-05-07 15:54:25'),
(24, 13, 'status update', '2024-05-07 15:54:29'),
(25, 13, 'status update', '2024-05-07 15:54:39'),
(26, 13, 'status update', '2024-05-07 15:55:00'),
(27, 13, 'status update', '2024-05-07 15:55:05'),
(31, 13, 'resubmit', '2024-05-07 17:00:32'),
(42, 13, 'status update', '2024-05-07 17:10:02'),
(43, 13, 'status update', '2024-05-07 17:10:05'),
(44, 13, 'status update', '2024-05-07 17:11:10'),
(45, 13, 'status update', '2024-05-07 17:11:14'),
(46, 13, 'status update', '2024-05-07 17:24:32'),
(47, 13, 'status update', '2024-05-07 17:24:36'),
(48, 13, 'status update', '2024-05-07 17:25:01'),
(49, 13, 'status update', '2024-05-07 17:25:04'),
(50, 13, 'status update', '2024-05-07 17:25:07'),
(51, 13, 'status update', '2024-05-07 17:25:10'),
(52, 13, 'status update', '2024-05-08 13:45:30'),
(53, 12, 'status update', '2024-05-08 13:45:49'),
(54, 13, 'status update', '2024-05-19 18:49:46'),
(55, 13, 'status update', '2024-05-19 18:49:50'),
(56, 14, 'upload', '2024-05-20 20:34:22'),
(57, 13, 'resubmit', '2024-05-21 08:23:35'),
(58, 15, 'upload', '2024-05-21 15:34:23'),
(59, 14, 'status update', '2024-05-21 15:50:13'),
(60, 14, 'status update', '2024-05-21 22:07:29'),
(61, 14, 'status update', '2024-05-21 22:07:33'),
(62, 14, 'status update', '2024-05-22 09:37:46'),
(63, 16, 'upload', '2024-06-04 08:58:28'),
(64, 16, 'status update', '2024-06-04 08:59:17'),
(65, 16, 'status update', '2024-06-17 23:49:05'),
(66, 17, 'upload', '2024-06-17 23:49:40'),
(67, 17, 'resubmit', '2024-07-02 10:46:54'),
(68, 17, 'resubmit', '2024-07-02 11:33:38'),
(69, 13, 'resubmit', '2024-07-02 13:12:18'),
(70, 17, 'status update', '2024-07-04 10:55:33'),
(71, 15, 'status update', '2024-08-03 13:24:04'),
(72, 15, 'status update', '2024-08-03 13:26:37'),
(73, 15, 'status update', '2024-08-03 13:33:33'),
(74, 17, 'status update', '2024-08-03 13:36:39'),
(75, 17, 'status update', '2024-08-03 13:36:48'),
(76, 15, 'status update', '2024-08-04 08:42:43'),
(77, 15, 'status update', '2024-08-04 08:43:51'),
(78, 15, 'status update', '2024-08-04 08:46:24'),
(79, 15, 'status update', '2024-08-04 08:46:51'),
(80, 15, 'status update', '2024-08-04 08:54:12'),
(81, 15, 'status update', '2024-08-04 08:55:19'),
(82, 15, 'status update', '2024-08-04 09:01:58'),
(83, 15, 'status update', '2024-08-04 09:02:51'),
(84, 15, 'status update', '2024-08-04 09:16:22'),
(85, 15, 'status update', '2024-08-04 09:18:33'),
(86, 15, 'status update', '2024-08-04 10:15:55'),
(87, 15, 'status update', '2024-08-04 10:21:39');

-- --------------------------------------------------------

--
-- Table structure for table `advisory_list`
--

CREATE TABLE `advisory_list` (
  `adv_list_id` int(11) NOT NULL,
  `adv_sch_user_id` int(11) NOT NULL,
  `stud_sch_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisory_list`
--

INSERT INTO `advisory_list` (`adv_list_id`, `adv_sch_user_id`, `stud_sch_user_id`) VALUES
(1, 10, 11),
(2, 10, 2),
(3, 10, 3),
(4, 12, 5),
(5, 10, 6),
(6, 10, 4),
(8, 12, 16),
(9, 13, 17),
(10, 10, 21),
(11, 10, 22);

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
  `status` enum('Active','Hidden','Declined','Pending') NOT NULL DEFAULT 'Pending',
  `reason` varchar(200) NOT NULL DEFAULT 'N/A',
  `announcementPosted` datetime NOT NULL DEFAULT current_timestamp(),
  `announcementUpdated` datetime NOT NULL DEFAULT current_timestamp(),
  `SchedAct_targetViewer` varchar(200) DEFAULT 'All'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`announcement_id`, `user_id`, `title`, `description`, `starting_date`, `end_date`, `type`, `status`, `reason`, `announcementPosted`, `announcementUpdated`, `SchedAct_targetViewer`) VALUES
(17, 9, 'New Note', 'EDITTEDITTEDITTEDITTEDIasdasd', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-16 13:43:18', '2024-08-05 07:59:33', 'N/A'),
(18, 9, 'Sec Note', 'MEssage MEssageMEssage MEssageMEssage MEssageMEssage MEssageMEssage MEssageMEssage MEssageMEssage MEssageMEssage MEssageMEssage MEssage', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-15 15:05:22', '2024-08-05 07:59:33', 'N/A'),
(19, 9, 'new Note', 'After Edit test', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-15 15:05:48', '2024-08-05 07:59:33', 'N/A'),
(20, 9, 'Last Test New Note', 'Message Message Message Message Message MessageMessage Message MessageMessage Message MessageMessage Message MessageMessage Message MessageMessage Message MessageMessage Message MessageMessage Message MessageMessage Message MessageMessage Message Message', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-15 15:13:39', '2024-08-05 07:59:33', 'N/A'),
(21, 9, 'Update', 'aaaaa', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-16 00:21:23', '2024-08-05 07:59:33', 'N/A'),
(22, 9, 'new', 'asdasd', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-16 00:21:30', '2024-08-05 07:59:33', 'N/A'),
(23, 9, 'NEw', 'after delete', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-16 14:27:33', '2024-08-05 07:59:33', 'N/A'),
(24, 9, 'new', 'asdasdasdasdasd', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-16 14:28:36', '2024-08-05 07:59:33', 'N/A'),
(25, 9, 'Last note asdaaaaas', 'asdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasd', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-17 16:46:04', '2024-08-05 07:59:33', 'N/A'),
(26, 9, 'new note', 'asdddddddddddasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasd', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-17 16:44:42', '2024-08-05 07:59:33', 'N/A'),
(27, 12, 'create', 'note', NULL, NULL, 'Notes', 'Active', 'N/A', '2024-05-17 12:35:39', '2024-08-05 07:59:33', 'N/A'),
(28, 9, 'new note', 'asdasdasdasdasddfgdfg edfsdfsdf  casdasdwe', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-17 15:54:11', '2024-08-05 07:59:33', 'N/A'),
(31, 9, 'Thats crazy', 'asdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasdasdasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssasd', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-17 16:44:29', '2024-08-05 07:59:33', 'N/A'),
(32, 9, 'asdasdasd', 'asdasdasdasdasdasd', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-17 16:43:39', '2024-08-05 07:59:33', 'N/A'),
(33, 9, 'Note', 'aa', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-21 08:17:43', '2024-08-05 07:59:33', 'N/A'),
(34, 9, 'Updated Notess', 'asdasdasdasdaasdasdsdasdasd', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-18 00:26:30', '2024-08-05 07:59:33', 'N/A'),
(39, 9, 'OJT Orientation', '', '2024-02-26', '2024-03-01', 'schedule and activities', 'Active', 'N/A', '2024-08-04 13:59:07', '2024-08-05 07:59:33', 'All'),
(40, 9, 'OJT orientation', 'asdasdasdasdasdasd', '2024-02-08', '2024-05-18', 'schedule and activities', 'Hidden', 'N/A', '2024-05-17 22:02:55', '2024-08-05 07:59:33', 'N/A'),
(41, 9, 'Beginning of Classes', '', '2024-02-26', '2024-02-26', 'schedule and activities', 'Active', 'N/A', '2024-05-28 12:26:30', '2024-08-05 07:59:33', 'All'),
(42, 9, 'new activity after edit', 'asdasdas', '2024-05-28', '2024-06-06', 'schedule and activities', 'Hidden', 'N/A', '2024-05-17 23:57:02', '2024-08-05 07:59:33', 'N/A'),
(43, 9, 'sdasdasdasdasdasdasdasdasdasdasdasdxcvxcvx', 'asdasdas', '2024-05-16', '2024-06-06', 'schedule and activities', 'Hidden', 'N/A', '2024-05-18 00:03:14', '2024-08-05 07:59:33', 'N/A'),
(44, 9, 'Last day of Recommendation Letter request', '', '2024-03-16', '2024-03-16', 'schedule and activities', 'Active', 'N/A', '2024-05-28 12:26:28', '2024-08-05 07:59:33', 'All'),
(45, 9, 'Last day of MOA request', '', '2024-03-16', '2024-03-16', 'schedule and activities', 'Active', 'N/A', '2024-05-28 12:26:26', '2024-08-05 07:59:33', 'All'),
(46, 9, 'OJT Visit', '', '2024-03-17', '2024-06-15', 'schedule and activities', 'Active', 'N/A', '2024-05-28 12:26:22', '2024-08-05 07:59:33', 'All'),
(47, 9, 'Last day of On-the-Job Training', '', '2024-06-02', '2024-06-22', 'schedule and activities', 'Active', 'N/A', '2024-05-28 12:26:44', '2024-08-05 07:59:33', 'All'),
(48, 9, 'Submission of Student Profile Report', '', '2024-06-29', '2024-06-29', 'schedule and activities', 'Active', 'N/A', '2024-05-28 12:26:50', '2024-08-05 07:59:33', 'All'),
(49, 9, 'Submission of Narrative Report', '', '2024-06-29', '2024-06-29', 'schedule and activities', 'Active', 'N/A', '2024-05-28 12:27:27', '2024-08-05 07:59:33', 'All'),
(50, 9, 'Submission of Summary Training Report', '', '2024-06-29', '2024-06-29', 'schedule and activities', 'Active', 'N/A', '2024-05-28 12:26:54', '2024-08-05 07:59:33', 'All'),
(51, 9, 'Submission of Notarize Moa', '', '2024-06-29', '2024-06-29', 'schedule and activities', 'Active', 'N/A', '2024-05-28 12:26:58', '2024-08-05 07:59:33', 'All'),
(52, 9, 'Last Day of Submission and Uploading of Grades', '(Graduating)\r\n(Non-Graduating)', '2024-07-13', '2024-07-20', 'schedule and activities', 'Active', 'N/A', '2024-05-28 12:26:57', '2024-08-05 07:59:33', 'All'),
(53, 9, 'new ote', 'asdasdasdasd', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-18 20:27:30', '2024-08-05 07:59:33', 'N/A'),
(54, 9, 'Notes', 'asdasdasdasd', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-21 00:44:30', '2024-08-05 07:59:33', 'N/A'),
(55, 9, 'ngi', 'Declineddd', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-26 14:51:51', '2024-08-05 07:59:33', 'N/A'),
(56, 9, 'asdasd', 'asdasdasd', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-19 00:50:24', '2024-08-05 07:59:33', 'N/A'),
(57, 9, 'asdasdasd', 'aaaa', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-19 15:39:07', '2024-08-05 07:59:33', 'N/A'),
(58, 9, 'new Noteeee', 'asdaasaasddds asd', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-19 18:45:05', '2024-08-05 07:59:33', 'N/A'),
(59, 9, 'defense week', '', '2024-05-27', '2024-06-10', 'schedule and activities', '', 'N/A', '2024-05-19 18:47:01', '2024-08-05 07:59:33', 'N/A'),
(60, 12, 'NOtE', 'asdddcxxcvxcvxcvaaa', NULL, NULL, 'Notes', 'Declined', 'Note reason', '2024-05-20 20:29:26', '2024-08-05 07:59:33', 'N/A'),
(61, 9, 'New Note', 'Notif check', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-21 00:58:41', '2024-08-05 07:59:33', 'N/A'),
(62, 9, 'test', 'Notif', '2024-05-21', '2024-05-21', 'schedule and activities', '', 'N/A', '2024-05-21 01:07:32', '2024-08-05 07:59:33', 'N/A'),
(63, 10, 'asdasd', 'asdasd', NULL, NULL, 'Notes', 'Active', 'N/A', '2024-05-21 16:02:19', '2024-08-05 07:59:33', 'N/A'),
(64, 10, 'asdasd', 'asdasdasd', NULL, NULL, 'Notes', 'Active', 'N/A', '2024-05-22 09:38:29', '2024-08-05 07:59:33', 'N/A'),
(65, 9, 'asdasd', 'asdasd', '2024-05-22', '2024-05-22', 'schedule and activities', 'Active', 'N/A', '2024-05-28 13:04:44', '2024-08-05 07:59:33', 'BSIT'),
(66, 9, 'asd', 'asd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasdasasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd asasd', NULL, NULL, 'Notes', 'Hidden', 'Please change the note', '2024-05-26 18:51:45', '2024-08-05 07:59:33', 'N/A'),
(67, 9, 'Ojt visit reminder', 'asdasd as', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-06-05 11:50:57', '2024-08-05 07:59:33', 'N/A'),
(68, 9, 'asd', 'asd', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-05-26 19:10:24', '2024-08-05 07:59:33', 'N/A'),
(69, 10, 'Submission reminderr', 'Please submit your weekly report on August 30 2024', NULL, NULL, 'Notes', 'Active', 'N/A', '2024-05-26 19:44:07', '2024-08-06 08:39:14', 'N/A'),
(70, 9, 'For BSBM', 'This announcement s for bsbm students', '2024-06-03', '2024-06-10', 'schedule and activities', 'Hidden', 'N/A', '2024-05-28 12:36:30', '2024-08-05 07:59:33', 'BSBM'),
(71, 9, 'For BSBM', 'BSBMactivities', '2024-05-28', '2024-06-12', 'schedule and activities', 'Active', 'N/A', '2024-05-28 13:03:33', '2024-08-05 07:59:33', 'BSBM'),
(72, 9, 'Test', 'aasdasd', NULL, NULL, 'Notes', 'Hidden', 'asaaasdasdasd', '2024-06-04 23:13:08', '2024-08-05 07:59:33', 'All'),
(73, 10, 'OJT visit Reminder', 'Some details', NULL, NULL, 'Notes', 'Active', 'N/A', '2024-08-05 07:41:39', '2024-08-05 07:59:33', 'All'),
(74, 9, 'asdasd', '', '2024-07-01', '2024-07-01', 'schedule and activities', 'Hidden', 'N/A', '2024-07-04 11:10:54', '2024-08-05 07:59:33', 'All'),
(75, 9, 'NOTE', 'AAAAAA', NULL, NULL, 'Notes', 'Hidden', 'N/A', '2024-07-05 12:57:52', '2024-08-05 07:59:33', 'All'),
(76, 10, 'Note', 'Email Notif test', NULL, NULL, 'Notes', 'Active', 'N/A', '2024-08-04 11:00:48', '2024-08-05 07:59:33', 'All'),
(77, 10, 'Note', 'Please accept', NULL, NULL, 'Notes', 'Declined', 'this declined note reason will be sent though email', '2024-08-04 11:01:32', '2024-08-05 10:55:47', 'All'),
(78, 9, 'ActivityAnnoucement Test', 'Email notif Test', '2024-08-04', '2024-08-28', 'schedule and activities', 'Hidden', 'N/A', '2024-08-04 13:37:31', '2024-08-05 07:59:33', 'BSIT'),
(79, 9, 'Announcement Notification Test', '', '2024-08-04', '2024-08-21', 'schedule and activities', 'Hidden', 'N/A', '2024-08-04 13:44:49', '2024-08-05 07:59:33', 'All'),
(80, 9, 'Announcement Notification Test', '', '2024-08-04', '2024-08-21', 'schedule and activities', 'Hidden', 'N/A', '2024-08-04 13:43:04', '2024-08-05 07:59:33', 'BSIT'),
(81, 9, 'For BSIT', 'Announcemnet gmail text', '2024-08-26', '2024-08-31', 'schedule and activities', 'Hidden', 'N/A', '2024-08-04 14:03:13', '2024-08-05 07:59:33', 'BSIT'),
(82, 9, 'SEND MAIL', 'test this email has been sent through background process', '2024-08-05', '2024-08-29', 'schedule and activities', 'Hidden', 'N/A', '2024-08-04 15:09:02', '2024-08-05 07:59:33', 'All');

-- --------------------------------------------------------

--
-- Table structure for table `narrativereports`
--

CREATE TABLE `narrativereports` (
  `narrative_id` int(11) NOT NULL,
  `OJT_adviser_ID` int(11) NOT NULL,
  `stud_school_id` varchar(200) NOT NULL,
  `first_name` varchar(200) NOT NULL,
  `middle_name` varchar(200) NOT NULL DEFAULT 'N/A',
  `last_name` varchar(200) NOT NULL,
  `sex` varchar(200) NOT NULL,
  `program` varchar(200) NOT NULL,
  `section` varchar(200) NOT NULL,
  `training_hours` int(11) NOT NULL,
  `company_name` varchar(200) NOT NULL DEFAULT 'N/A',
  `sySubmitted` varchar(200) NOT NULL DEFAULT 'N/A',
  `narrative_file_name` varchar(200) NOT NULL,
  `upload_date` datetime NOT NULL,
  `file_status` enum('Pending','Declined','OK','Archived') NOT NULL,
  `remarks` varchar(200) NOT NULL DEFAULT 'N/A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `narrativereports`
--

INSERT INTO `narrativereports` (`narrative_id`, `OJT_adviser_ID`, `stud_school_id`, `first_name`, `middle_name`, `last_name`, `sex`, `program`, `section`, `training_hours`, `company_name`, `sySubmitted`, `narrative_file_name`, `upload_date`, `file_status`, `remarks`) VALUES
(33, 10, '0123654223', 'Ronieer', 'Carlos', 'Sulek', 'Male', 'BSIT', '4D', 0, '0', '2023,2024', 'Ronieer_Sulek_BSIT_4D_0123654223.pdf', '2024-08-06 09:35:02', 'Declined', 'Please reconvert the document reason missing pages and please double check the information of the student i notice some mismatch in the name'),
(34, 10, '01235123', 'Billyr', 'Joe', 'Jean', 'Female', 'BSIT', '4B', 300, 'Redbull', '2024,2025', 'Billyr_Jean_BSIT_4B_01235123.pdf', '2024-08-06 12:41:35', 'Pending', ''),
(35, 14, '4776241', 'Walterr', '', 'White', 'Male', 'BSIT', '4A', 0, '0', '2023,2024', 'Walterr_White_BSIT_4A_4776241.pdf', '2024-07-10 10:03:26', 'OK', 'OK'),
(36, 13, '01234567', 'Jesse', 'N/A', 'Pinkman', 'Male', 'BSIT', '4C', 300, 'Cavite State University Carmona Campus', '2021,2022', 'Jesse_Pinkman_BSIT_4C_01234567.pdf', '2024-06-04 08:37:26', 'OK', 'OK'),
(37, 14, '01234542', 'JEss', 'N/A', 'ThePinkman', 'Male', 'BSIT', '4C', 0, '0', '2017,2018', 'JEss_ThePinkman_BSIT_4C_01234542.pdf', '2024-05-30 00:04:11', 'OK', 'OK'),
(39, 10, '0365489', 'Tio', 'N/A', 'Salamanca', 'Female', 'BSIT', '4A', 0, '0', '2023,2024', 'Tio_Salamanca_BSIT_4A_0365489.pdf', '2024-07-04 11:11:45', 'OK', 'OK'),
(40, 13, '0443521', 'Johny', 'N/A', 'Doe', 'Female', 'BSIT', '4C', 400, 'Petron', '2022,2023', 'Johny_Doe_BSIT_4C_0443521.pdf', '2024-06-07 10:36:26', 'OK', 'OK'),
(41, 14, '210103548', 'Johny', 'N/A', 'Cooks', 'Male', 'BSCS', '4B', 0, '0', '2022,2023', 'Johny_Cooks_BSCS_4B_210103548.pdf', '2024-05-30 00:03:13', 'OK', 'OK'),
(42, 13, '212065477', 'Sally', 'N/A', 'KitKit', 'Male', 'BSIT', '4B', 0, '0', '2019,2020', 'Sally_KitKit_BSIT_4B_212065477.pdf', '2024-05-30 00:03:24', 'OK', 'OK'),
(43, 14, '210201235', 'Leo', 'N/A', 'Messi', 'Male', 'BSIT', '4B', 0, '0', '2020,2021', 'Leo_Messi_BSIT_4B_210201235.pdf', '2024-05-30 00:03:48', 'OK', 'OK'),
(44, 14, '201231231', 'Leo', 'N/A', 'Christiano', 'Male', 'BSBM', '4C', 0, '0', 'N/A', 'Leo_Christiano_BSBM_4C_201231231.pdf', '2024-05-19 16:11:56', 'OK', 'OK'),
(45, 10, '210345454', 'newStud', 'N/A', 'lastname', 'Male', 'BSCS', '4C', 0, '0', '2020,2021', 'newStud_lastname_BSCS_4C_210345454.pdf', '2024-08-05 12:54:49', 'OK', 'OK'),
(46, 13, '201235454', 'Pedro', 'N/A', 'Juan', 'Male', 'BSBM', '4A', 0, '0', 'N/A', 'Pedro_Juan_BSBM_4A_201235454.pdf', '2024-05-21 01:11:03', 'OK', 'OK'),
(48, 14, '201233445', 'Juan', 'N/A', 'DelaCruz', 'Male', 'BSCS', '4D', 0, '0', '2020,2021', 'Juan_DelaCruz_BSCS_4D_201233445.pdf', '2024-06-04 08:30:05', 'OK', 'OK'),
(49, 12, '201235543', 'TestConvertFname', 'N/A', 'TestConvertLname', 'Male', 'BSBM', '4C', 0, '0', 'N/A', 'TestConvertFname_TestConvertLname_BSBM_4C_201235543.pdf', '2024-05-21 08:46:30', 'OK', 'OK'),
(50, 12, '123123455', 'Yuri', 'N/A', 'Ausan', 'Male', 'BSCS', '4C', 0, '0', '2022,2023', 'Yuri_Ausan_BSCS_4C_123123455.pdf', '2024-05-30 00:00:55', 'OK', 'OK'),
(51, 12, '200145561', 'MONICA JOYCE ANN', '', 'MULLET', 'Male', 'BSIT', '4C', 486, 'SYKES ASIA, INC', '2023,2024', 'MONICAJOYCEANN_MULLET_BSIT_4C_200145561.pdf', '2024-06-07 11:39:39', 'Pending', ''),
(52, 10, '912312345', 'ASASDASDdda', 'N/A', 'SDSDFSDFSD', 'Male', 'BSIT', '4A', 300, 'GoCrayon', '2023,2024', 'ASASDASDdda_SDSDFSDFSD_BSIT_4A_912312345.pdf', '2024-05-29 23:58:56', 'OK', 'OK'),
(53, 10, '212304545', 'james', 'JJ', 'asugan', 'Male', 'BSIT', '4A', 222, 'aqq', '2020,2021', 'james_asugan_BSIT_4A_212304545.pdf', '2024-05-29 23:54:08', 'OK', 'OK'),
(54, 10, '901231234', 'Jesse', 'White', 'Pinkman', 'Male', 'BSIT', '4A', 600, 'Petron', '2023,2024', 'Jesse_Pinkman_BSIT_4A_901231234.pdf', '2024-05-29 23:53:29', 'OK', 'OK'),
(55, 10, '201233421', 'Maxx', '', 'Verstappen', 'Male', 'BSIT', '4A', 400, 'Redbull', '2023,2024', 'Maxx_Verstappen_BSIT_4A_201233421.pdf', '2024-06-07 13:30:45', 'OK', 'OK'),
(56, 12, '200120034', 'Jasmin', 'N/A', 'Gallarte', 'Female', 'BSIT', '4A', 486, 'MUNICIPALITY OF CARMONA, CAVITE', '2022,2023', 'Jasmin_Gallarte_BSIT_4A_200120034.pdf', '2024-06-07 10:42:17', 'OK', 'OK'),
(57, 12, '200165066', 'Ralph Victorr', 'Baybay', 'Leonardo', 'Male', 'BSIT', '4B', 486, 'ADVANCE ABILITY ASSISTANCE HUMAN RESOURCES INC.', '2022,2023', 'RalphVictorr_Leonardo_BSIT_4B_200165066.pdf', '2024-06-07 11:25:46', 'OK', 'OK'),
(58, 10, '203123455', 'Fionaa', '', 'Ronaldino', 'Female', 'BSIT', '4A', 480, 'SYKES ASIA, INC', '2023,2024', 'Fionaa_Ronaldino_BSIT_4A_203123455.pdf', '2024-08-05 13:12:24', 'OK', 'OK');

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
(1, 'BSIT', 'Bachelor of Science in Information Technology'),
(2, 'BSCS', 'Bachelor of Science in Computer Science'),
(3, 'BSBM', 'Bachelor of Science in Business Administration'),
(4, 'BSCpE', 'Bachelor of Science in Computer Engineering'),
(6, 'BSHM', 'Bachelor of Science Hospitality Management'),
(7, 'BSIndT', 'Bachelor of Science in Industrial Technology');

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
(60, 52, '6670563093798.png'),
(61, 52, '66705630947ce.PNG'),
(62, 53, '66860f0d5592e.png');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `section_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `section` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`section_id`, `year`, `section`) VALUES
(1, 4, 'A'),
(2, 4, 'B'),
(3, 4, 'C'),
(4, 4, 'D'),
(5, 4, 'E'),
(6, 4, 'F'),
(7, 3, 'C'),
(8, 2, 'D'),
(9, 2, 'A');

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
  `status` enum('active','inactive') NOT NULL,
  `OTP` int(11) NOT NULL,
  `OTP_generated_Date` timestamp NULL DEFAULT NULL,
  `OTP_expiration_Date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_accounts`
--

INSERT INTO `tbl_accounts` (`acc_id`, `user_id`, `email`, `password`, `date_created`, `status`, `OTP`, `OTP_generated_Date`, `OTP_expiration_Date`) VALUES
(1, 1, 'gusnewEmails@gmail.com', '$2y$10$BcpHovoEOszbeHVb7rH0GuHQ7z5TYmNJw78CtrC2ne0T3Eu0lNaNe', '2024-05-02 03:28:08', 'inactive', 0, NULL, NULL),
(2, 2, 'cr7sui@gmail.com', '$2y$10$mNrnvKtwJhfq8JhtBnlM2eU89HU1NIJq/fgdGl1Dpzkfz3rCJo34C', '2024-05-02 05:39:51', 'active', 0, NULL, NULL),
(3, 3, 'jessePinkman@gmail.com', '$2y$10$Rogups.M7CQNgPJiDzTuWOCK4zwOrZKnbRph7BfLCrJ39HW5s6AOq', '2024-05-03 02:02:00', 'active', 0, NULL, NULL),
(4, 4, 'johny123@gmail.com', '$2y$10$ZeceePc33DPD1PMboUpL.Ouvzr7eYA9YyUPGsQwvvvJjoUfI4P7Sm', '2024-05-03 11:31:01', 'active', 0, NULL, NULL),
(5, 5, 'janeDoe01235@gmail.com', '$2y$10$cDHCjEA5zHC0s/aQwRNoLeFuPK4vmIEqu.pUEgnK8PZMd7QdnertC', '2024-05-03 11:36:17', 'active', 0, NULL, NULL),
(6, 6, 'riodelacruz033@gmail.com', '$2y$10$wxPhrE6l5w8rTOD4Z4gSk.uHnLMsPueFcSEh5ILnyoGK24ZtlgBzq', '2024-05-03 11:37:50', 'active', 0, NULL, NULL),
(9, 9, 'cc.riocarl.delacruz@cvsu.edu.ph', '$2y$10$yApbO5RISPOfmvK7Nu6q4.kjQIZ0RGls74B2i2pF/0CG6Xl.71NAK', '2024-05-03 14:08:41', 'active', 0, NULL, NULL),
(10, 10, 'riodelacruz0258@gmail.com', '$2y$10$oo0HzdrogKum.mXkhJRbYuie3xlrYbUk9v/OmjJL2Tflbg/3UFUse', '2024-05-04 02:44:49', 'active', 0, NULL, NULL),
(11, 11, 'walterjr123@gmail.com', '$2y$10$VrUt1qBn0WnmtgnNYA9kQuMVhIa1Q.vmxAgHwuc7TBRN85hEVb0Zu', '2024-05-04 10:21:19', 'active', 0, NULL, NULL),
(12, 12, 'walter123@gmail.com', '$2y$10$33NkHIMOo.ecPI5VHZOELO93HpfDNwVPN7G7sgc3tdOUZu6FkDaWS', '2024-05-14 13:03:02', 'active', 0, NULL, NULL),
(13, 13, 'juan@gmail.com', '$2y$10$GAE7tW9/CNKImwI/sdvJuu4gN8BQWTjjJuW0/d8pu.YZcMqeuZDLe', '2024-05-20 16:29:23', 'active', 0, NULL, NULL),
(14, 14, 'pedro@gmail.com', '$2y$10$tu3.oGRDfIbwwywX1rMdLuRVdSJilKivkZLry3/Gm6ALiEFEuJE96', '2024-05-20 16:31:02', 'active', 0, NULL, NULL),
(16, 16, 'pedroBatumbakal11@gmail.com', '$2y$10$jox3eA55o/ZbN.PnyXBfe.XOLPaU/Qblg.OZdZZJxOnN8qQmULaBW', '2024-05-21 13:59:44', 'active', 0, NULL, NULL),
(17, 17, 'bugoynakoykoy-6052@yopmail.com', '$2y$10$QCpMHTp6G7/EV8.xJklrTeAynQO9dYf7qsPTEto6gV9zdIfsaVjVO', '2024-08-07 00:55:55', 'active', 0, NULL, NULL),
(19, 21, 'janePinkman@asdaad.com', '$2y$10$ZFKLmEPD9RyBnBkqtYwUPut9E6Z5o5Pli31A7nETzoh/LWA2Uw8ie', '2024-08-07 03:45:51', 'active', 0, NULL, NULL),
(20, 22, 'triplelift20@gmail.com', '$2y$10$BufwWvHuWsOeitRmJyVxxu0wQW1KtQJyJHjYmmUVIoPvdfs9MYi2S', '2024-08-07 03:51:20', 'active', 0, NULL, NULL);

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
(52, 16, 10, 'revise', '2024-06-17 15:28:48'),
(53, 17, 10, 'SomeComments', '2024-07-04 02:55:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_students`
--

CREATE TABLE `tbl_students` (
  `enrolled_stud_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `company_name` varchar(200) DEFAULT NULL,
  `training_hours` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_students`
--

INSERT INTO `tbl_students` (`enrolled_stud_id`, `user_id`, `program_id`, `section_id`, `company_name`, `training_hours`) VALUES
(1, 1, 2, 2, 'asd', 212),
(2, 2, 1, 1, 'GoCrayons', 1500),
(3, 3, 1, 1, 'GoCrayons', 1500),
(4, 4, 1, 1, 'SSS', 400),
(5, 5, 2, 2, '7/11', 400),
(6, 6, 1, 2, 'Petron', 400),
(7, 11, 1, 2, 'Amkor', 400),
(9, 16, 2, 5, 'Samsung', 400),
(10, 17, 3, 8, 'Hustlin', 600),
(11, 21, 3, 7, 'SYKES ASIA, INC', 600),
(12, 22, 1, 6, 'They Dont Know me Son', 600);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_info`
--

CREATE TABLE `tbl_user_info` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(200) NOT NULL DEFAULT 'N/A',
  `last_name` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `sex` enum('Male','Female','Other') NOT NULL,
  `school_id` varchar(200) NOT NULL,
  `profile_img_file` varchar(255) NOT NULL DEFAULT 'N/A',
  `user_type` enum('admin','adviser','student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user_info`
--

INSERT INTO `tbl_user_info` (`user_id`, `first_name`, `middle_name`, `last_name`, `address`, `contact_number`, `sex`, `school_id`, `profile_img_file`, `user_type`) VALUES
(1, 'Skyer', 'N/A', 'Fring', 'Dubai', '09136546546', 'Male', '210101279', 'N/A', 'student'),
(2, 'Christiano', 'N/A', 'Ronaldo', 'Dubai', '91234567943', 'Male', '210101275', 'N/A', 'student'),
(3, 'Jesse', 'N/A', 'Pinkman', 'Albuquerque', '09123463214', 'Male', '200101234', 'N/A', 'student'),
(4, 'Johny', 'N/A', 'Doe', 'Carmona', '09123456798', 'Male', '210101539', 'N/A', 'student'),
(5, 'Jane', 'N/A', 'Doe', 'Carmona Dubai', '09123246554', 'Female', '210101234', 'N/A', 'student'),
(6, 'Walter', 'N/A', 'White', 'Albuquerque', '09123463213', 'Female', '210132436', 'N/A', 'student'),
(9, 'Rioasd', 'Hubilla', 'Delacruz', 'Carmona', '09013264654', 'Male', '111111111', '947c4e2df685b1dd0d39aab8bde7f24e.png', 'admin'),
(10, 'Jack', 'Nomiddlename', 'Daniel', 'New York', '09232132165', 'Male', '222222222', 'd0a79978916ab600b1c802a1f6e6a668.png', 'adviser'),
(11, 'Walter JR', 'N/A', 'White', 'New Mexico', '08912345678', 'Male', '210103548', 'N/A', 'student'),
(12, 'Walter', 'N/A', 'White', 'Dubai', '09456546545', 'Male', '333333333', 'N/A', 'adviser'),
(13, 'Juan', 'N/A', 'TheAdviser', 'New Mexico', '09123533232', 'Male', '444444444', 'N/A', 'adviser'),
(14, 'Pedro', 'N/A', 'TheAdviser', 'New Mexico', '09124554333', 'Male', '555555555', 'N/A', 'adviser'),
(16, 'Pedro', 'Tigasin', 'Batumbakal', 'Tondo', '09124533111', 'Male', '201225455', 'N/A', 'student'),
(17, 'Bugoy', 'Na', 'Koykoy', 'Sorento', '09512223111', 'Male', '055421233', 'N/A', 'student'),
(20, 'Bugoy', 'Na', 'Koykoy', 'Sorento', '09123545454', 'Male', '0443521', 'N/A', 'student'),
(21, 'Jane', '', 'Pinkman', 'Albuquerque', '09554343453', 'Female', '210102344', 'N/A', 'student'),
(22, 'David', '', 'Goggins', 'USA', '09455612312', 'Male', '210120445', 'N/A', 'student');

-- --------------------------------------------------------

--
-- Table structure for table `weeklyreport`
--

CREATE TABLE `weeklyreport` (
  `file_id` int(11) NOT NULL,
  `stud_user_id` int(11) NOT NULL,
  `weeklyFileReport` text NOT NULL,
  `upload_date` datetime NOT NULL,
  `upload_status` enum('pending','approved','revision') NOT NULL,
  `readStatus` enum('Read','Unread') NOT NULL DEFAULT 'Unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weeklyreport`
--

INSERT INTO `weeklyreport` (`file_id`, `stud_user_id`, `weeklyFileReport`, `upload_date`, `upload_status`, `readStatus`) VALUES
(7, 6, '210132436_WeeklyReport_week_1.pdf', '2024-05-06 15:29:32', 'approved', 'Read'),
(12, 6, '210132436_WeeklyReport_week_2.pdf', '2024-05-06 15:46:24', 'approved', 'Read'),
(13, 6, '210132436_WeeklyReport_week_3.pdf', '2024-05-07 01:04:34', 'revision', 'Unread'),
(14, 2, '210101270_WeeklyReport_week_1.pdf', '2024-05-20 20:34:22', 'approved', 'Read'),
(15, 6, '210132436_WeeklyReport_week_4.pdf', '2024-05-21 15:34:23', 'revision', 'Read'),
(16, 4, '210101539_WeeklyReport_week_1.pdf', '2024-06-04 08:58:28', 'approved', 'Read'),
(17, 4, '210101539_WeeklyReport_week_2.pdf', '2024-06-17 23:49:40', 'revision', 'Read');

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
-- Indexes for table `advisory_list`
--
ALTER TABLE `advisory_list`
  ADD PRIMARY KEY (`adv_list_id`),
  ADD KEY `adv_sch_user_id` (`adv_sch_user_id`),
  ADD KEY `stud_sch_user_id` (`stud_sch_user_id`);

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
  ADD KEY `OJT_adviser_ID` (`OJT_adviser_ID`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`program_id`),
  ADD UNIQUE KEY `program_code` (`program_code`),
  ADD UNIQUE KEY `program_name` (`program_name`);

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
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `tbl_accounts`
--
ALTER TABLE `tbl_accounts`
  ADD PRIMARY KEY (`acc_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

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
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_user_info`
--
ALTER TABLE `tbl_user_info`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `school_id` (`school_id`),
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
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `advisory_list`
--
ALTER TABLE `advisory_list`
  MODIFY `adv_list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `narrativereports`
--
ALTER TABLE `narrativereports`
  MODIFY `narrative_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
  MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `revision_attachment`
--
ALTER TABLE `revision_attachment`
  MODIFY `attachment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_accounts`
--
ALTER TABLE `tbl_accounts`
  MODIFY `acc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_revision`
--
ALTER TABLE `tbl_revision`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `tbl_students`
--
ALTER TABLE `tbl_students`
  MODIFY `enrolled_stud_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_user_info`
--
ALTER TABLE `tbl_user_info`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `weeklyreport`
--
ALTER TABLE `weeklyreport`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `weeklyreport` (`file_id`);

--
-- Constraints for table `advisory_list`
--
ALTER TABLE `advisory_list`
  ADD CONSTRAINT `advisory_list_ibfk_1` FOREIGN KEY (`adv_sch_user_id`) REFERENCES `tbl_user_info` (`user_id`),
  ADD CONSTRAINT `advisory_list_ibfk_2` FOREIGN KEY (`stud_sch_user_id`) REFERENCES `tbl_user_info` (`user_id`);

--
-- Constraints for table `announcement`
--
ALTER TABLE `announcement`
  ADD CONSTRAINT `announcement_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user_info` (`user_id`);

--
-- Constraints for table `narrativereports`
--
ALTER TABLE `narrativereports`
  ADD CONSTRAINT `narrativereports_ibfk_1` FOREIGN KEY (`OJT_adviser_ID`) REFERENCES `tbl_user_info` (`user_id`);

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
-- Constraints for table `tbl_revision`
--
ALTER TABLE `tbl_revision`
  ADD CONSTRAINT `tbl_revision_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `weeklyreport` (`file_id`),
  ADD CONSTRAINT `tbl_revision_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_user_info` (`user_id`);

--
-- Constraints for table `tbl_students`
--
ALTER TABLE `tbl_students`
  ADD CONSTRAINT `tbl_students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user_info` (`user_id`);

--
-- Constraints for table `weeklyreport`
--
ALTER TABLE `weeklyreport`
  ADD CONSTRAINT `weeklyreport_ibfk_1` FOREIGN KEY (`stud_user_id`) REFERENCES `tbl_user_info` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
