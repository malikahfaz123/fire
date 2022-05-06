-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 02, 2021 at 05:50 AM
-- Server version: 5.7.32
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `whipplew_fire`
--

-- --------------------------------------------------------

--
-- Table structure for table `awarded_certificates`
--

CREATE TABLE `awarded_certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `certificate_id` bigint(20) UNSIGNED NOT NULL,
  `firefighter_id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stage` enum('initial','renewal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'initial',
  `receiving_date` date DEFAULT NULL,
  `issue_date` date NOT NULL,
  `lapse_date` date DEFAULT NULL,
  `firefighters_read_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `awarded_certificates`
--

INSERT INTO `awarded_certificates` (`id`, `certificate_id`, `firefighter_id`, `organization_id`, `stage`, `receiving_date`, `issue_date`, `lapse_date`, `firefighters_read_status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'initial', NULL, '2021-02-08', NULL, 1, '2021-02-08 11:24:27', '2021-02-08 11:42:12');

-- --------------------------------------------------------

--
-- Table structure for table `certificate_rejected_reasons`
--

CREATE TABLE `certificate_rejected_reasons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firefighter_certificates_id` bigint(20) UNSIGNED NOT NULL,
  `firefighter_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificate_statuses`
--

CREATE TABLE `certificate_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firefighter_certificates_id` bigint(20) UNSIGNED NOT NULL,
  `firefighter_id` bigint(20) UNSIGNED NOT NULL,
  `test_date` date NOT NULL,
  `test_time` time NOT NULL,
  `status` enum('none','passed','failed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificate_statuses`
--

INSERT INTO `certificate_statuses` (`id`, `firefighter_certificates_id`, `firefighter_id`, `test_date`, `test_time`, `status`, `read_status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2021-02-01', '11:35:00', 'passed', 1, '2021-02-08 11:18:52', '2021-02-08 11:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `certifications`
--

CREATE TABLE `certifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prefix_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_title` char(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `geo_sent` text COLLATE utf8mb4_unicode_ci,
  `renewable` tinyint(1) NOT NULL,
  `renewal_period` enum('1 year','2 year','3 year') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pboard` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ifsac` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_of_credit_types` int(11) NOT NULL,
  `no_of_pre_req_cert` int(11) DEFAULT NULL,
  `no_of_pre_req_course` int(11) DEFAULT NULL,
  `admin_ceu` double(8,2) NOT NULL DEFAULT '0.00',
  `tech_ceu` double(8,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certifications`
--

INSERT INTO `certifications` (`id`, `prefix_id`, `title`, `short_title`, `geo_sent`, `renewable`, `renewal_period`, `pboard`, `ifsac`, `no_of_credit_types`, `no_of_pre_req_cert`, `no_of_pre_req_course`, `admin_ceu`, `tech_ceu`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'FFC01', 'Firefighter Certificate', 'FFC', NULL, 0, NULL, NULL, NULL, 1, NULL, NULL, 0.00, 0.10, 1, '2021-02-08 07:52:40', '2021-02-08 07:52:40');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED DEFAULT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `organization_type` enum('EO','FD') COLLATE utf8mb4_unicode_ci NOT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fire_department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `facility_id` bigint(20) UNSIGNED DEFAULT NULL,
  `no_of_facility_types` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_archive` tinyint(1) DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  `archived_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `completed_courses`
--

CREATE TABLE `completed_courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firefighter_id` bigint(20) UNSIGNED DEFAULT NULL,
  `semester_id` bigint(20) UNSIGNED DEFAULT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transcript_sent` tinyint(4) NOT NULL DEFAULT '0',
  `issue_date` date DEFAULT NULL,
  `is_archive` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prefix_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fema_course` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nfpa_std` double(8,2) DEFAULT NULL,
  `admin_ceu` double(8,2) DEFAULT NULL,
  `tech_ceu` double(8,2) DEFAULT NULL,
  `course_hours` double(8,2) NOT NULL,
  `no_of_credit_types` int(11) NOT NULL,
  `no_of_pre_req_course` int(11) DEFAULT NULL,
  `maximum_students` int(11) NOT NULL,
  `instructor_level` int(11) NOT NULL,
  `is_archive` tinyint(1) DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  `archived_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `prefix_id`, `fema_course`, `course_name`, `nfpa_std`, `admin_ceu`, `tech_ceu`, `course_hours`, `no_of_credit_types`, `no_of_pre_req_course`, `maximum_students`, `instructor_level`, `is_archive`, `archived_at`, `archived_by`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'C4U01', '12345', 'Fire Safety Instructions', 123456.00, 2.00, 2.00, 5.00, 2, 0, 10, 1, NULL, NULL, NULL, 1, '2021-02-08 07:13:04', '2021-02-08 07:29:16');

-- --------------------------------------------------------

--
-- Table structure for table `course_classes`
--

CREATE TABLE `course_classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `class_id` int(11) NOT NULL,
  `firefighter_id` bigint(20) UNSIGNED DEFAULT NULL,
  `attendance` enum('completed','withdraw','no show','enrolled','stand by') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'enrolled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_prerequisites`
--

CREATE TABLE `course_prerequisites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `preq_course_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credit_types`
--

CREATE TABLE `credit_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prefix_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `credit_types`
--

INSERT INTO `credit_types` (`id`, `prefix_id`, `description`, `created_at`, `updated_at`) VALUES
(1, '001', 'Live Burn', '2021-02-08 07:12:30', '2021-02-08 07:12:30'),
(2, '002', 'Drill Tower', '2021-02-08 07:12:36', '2021-02-08 07:12:36');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prefix_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` enum('permanent','temporary') COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_municipal_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `live_burn_permit` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `organization` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `vacancy_status` enum('available','unavailable') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `mail_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_municipality` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_zipcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physical_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physical_municipality` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physical_state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physical_zipcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_address` text COLLATE utf8mb4_unicode_ci,
  `owner_city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_zipcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representative_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representative_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signator` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signator_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_archive` tinyint(1) DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  `archived_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `prefix_id`, `category`, `country_municipal_code`, `name`, `live_burn_permit`, `organization`, `status`, `vacancy_status`, `start_date`, `end_date`, `mail_address`, `mail_municipality`, `mail_state`, `mail_zipcode`, `physical_address`, `physical_municipality`, `physical_state`, `physical_zipcode`, `owner_name`, `owner_address`, `owner_city`, `owner_state`, `owner_zipcode`, `contact_person_name`, `contact_person_phone`, `representative_name`, `representative_phone`, `signator`, `signator_phone`, `is_archive`, `archived_at`, `archived_by`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'F00001', 'permanent', 'United Kingdom Municipal', 'Portable Wi-Fi Devices', 'no', NULL, 'yes', NULL, NULL, NULL, '256 Banbury Road, Summertown, Oxford OX2 7DE, UK', NULL, 'England', 'OX2 7DE', '256 Banbury Road, Summertown, Oxford OX2 7DE, UK', NULL, 'England', 'OX2 7DE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2021-02-08 07:35:03', '2021-02-08 07:35:03'),
(2, 'F00002', 'temporary', 'United Kingdom Municipal', 'Facility 01', 'yes', 1, 'yes', 'available', '2021-01-01', '2021-03-30', '256 Banbury Road, Summertown, Oxford OX2 7DE, UK', NULL, 'England', 'OX2 7DE', '256 Banbury Road, Summertown, Oxford OX2 7DE, UK', NULL, 'England', 'OX2 7DE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Richlo', '00001', NULL, NULL, NULL, 1, '2021-02-08 07:36:18', '2021-02-08 11:45:12');

-- --------------------------------------------------------

--
-- Table structure for table `facility_types`
--

CREATE TABLE `facility_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facility_types`
--

INSERT INTO `facility_types` (`id`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Internet access', '2021-02-08 07:32:52', '2021-02-08 07:32:52'),
(2, 'COL', '2021-02-08 07:32:57', '2021-02-08 07:32:57');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `firefighters`
--

CREATE TABLE `firefighters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prefix_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `f_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `m_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `l_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('male','female','transgender','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `race` enum('american indian or alaskan native','asian or pacific islander','black, not of hispanic origin','white, not of hispanic origin','hispanic') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `appointed` tinyint(1) DEFAULT '1',
  `instructor_level` int(11) DEFAULT NULL,
  `address_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_mail` tinyint(1) DEFAULT NULL,
  `address_title_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_2` text COLLATE utf8mb4_unicode_ci,
  `city_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_mail_2` tinyint(1) DEFAULT NULL,
  `address_title_3` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_3` text COLLATE utf8mb4_unicode_ci,
  `city_3` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_3` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode_3` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_mail_3` tinyint(1) DEFAULT NULL,
  `home_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cell_phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cell_phone_verified` tinyint(1) DEFAULT NULL,
  `work_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_phone_ext` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_email_verified` tinyint(1) DEFAULT NULL,
  `ssn` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ucc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nicet` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fema` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `muni` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vol` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `car` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_archive` tinyint(1) DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  `archived_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firefighter_image` text COLLATE utf8mb4_unicode_ci,
  `reset_password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_invited` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `firefighters`
--

INSERT INTO `firefighters` (`id`, `prefix_id`, `f_name`, `m_name`, `l_name`, `dob`, `gender`, `race`, `appointed`, `instructor_level`, `address_title`, `address`, `city`, `state`, `zipcode`, `postal_mail`, `address_title_2`, `address_2`, `city_2`, `state_2`, `zipcode_2`, `postal_mail_2`, `address_title_3`, `address_3`, `city_3`, `state_3`, `zipcode_3`, `postal_mail_3`, `home_phone`, `cell_phone`, `cell_phone_verified`, `work_phone`, `work_phone_ext`, `phone_token`, `email_token`, `work_email`, `work_email_verified`, `ssn`, `ucc`, `nicet`, `fema`, `muni`, `vol`, `car`, `is_archive`, `archived_at`, `archived_by`, `created_by`, `created_at`, `updated_at`, `email`, `email_verified_at`, `password`, `remember_token`, `firefighter_image`, `reset_password`, `is_invited`) VALUES
(1, '000001', 'Arshia', '', 'Qwerty', '1980-02-08', 'female', NULL, 1, NULL, 'Work Address', '256 Banbury Road, Summertown, Oxford, OX2256 Banbury Road, Summertown, Oxford, OX2', 'Oxford', 'England', 'GU167HF', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+13341077758', NULL, NULL, NULL, NULL, NULL, 'arshia@kingdom-vision.co.uk', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-02-08 07:11:33', '2021-02-08 07:11:33', 'arshia@kingdom-vision.co.uk', NULL, '$2y$10$1WcsGuW2vMmkl9vnChbT.udloD004RLkZVn3UYWNXVVea83TjxfEy', NULL, NULL, '', 0),
(2, '000002', 'Inspector', '', 'Firefighter', '1970-06-10', 'male', 'white, not of hispanic origin', 1, 1, 'Work Address', '256 Banbury Road, Summertown, Oxford, OX2256 Banbury Road, Summertown, Oxford, OX2', 'Oxford', 'England', 'GU167HF', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '+11234444444', '+14444444444', NULL, '+13341077758', '123', NULL, NULL, 'insp@gmail.com', NULL, '12345678900', '12345678900', '12345678900', '12345678900', '12345678900', '12345678900', '12345678900', NULL, NULL, NULL, 1, '2021-02-08 07:26:39', '2021-02-08 07:26:39', 'insp@gmail.com', NULL, NULL, NULL, NULL, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `firefighter_certificates`
--

CREATE TABLE `firefighter_certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firefighter_id` bigint(20) UNSIGNED NOT NULL,
  `certificate_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('applied','accepted','rejected') COLLATE utf8mb4_unicode_ci NOT NULL,
  `test_status` enum('none','passed','failed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `firefighter_certificates`
--

INSERT INTO `firefighter_certificates` (`id`, `firefighter_id`, `certificate_id`, `status`, `test_status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'accepted', 'passed', '2021-02-08 11:18:10', '2021-02-08 11:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `firefighter_courses`
--

CREATE TABLE `firefighter_courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `firefighter_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('applied','enrolled','rejected') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `firefighter_courses`
--

INSERT INTO `firefighter_courses` (`id`, `semester_id`, `course_id`, `firefighter_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'enrolled', '2021-02-08 07:14:46', '2021-02-08 07:15:27');

-- --------------------------------------------------------

--
-- Table structure for table `fire_departments`
--

CREATE TABLE `fire_departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_archive` tinyint(1) DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  `archived_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `prefix_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_of_dept_types` int(11) DEFAULT NULL,
  `email` text COLLATE utf8mb4_unicode_ci,
  `email_2` text COLLATE utf8mb4_unicode_ci,
  `email_3` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fire_departments`
--

INSERT INTO `fire_departments` (`id`, `name`, `address`, `city`, `zipcode`, `phone`, `phone2`, `is_archive`, `archived_at`, `archived_by`, `created_by`, `created_at`, `updated_at`, `prefix_id`, `state`, `no_of_dept_types`, `email`, `email_2`, `email_3`) VALUES
(1, 'Downtown Fire Department', 'Downtown London, King Street, London, ON, Canada', 'Middlesex County', 'N6A 1C3', '+19946321212', NULL, NULL, NULL, NULL, 1, '2021-02-08 07:42:53', '2021-02-08 07:42:53', 'F0001', 'Ontario', 1, 'sqatestingid@gmail.com', NULL, NULL),
(2, 'London Bridge Fire Station', 'London Bridge, London, UK', 'Greater London', 'SE1 9RA', '+19952318514', '+19925484214', NULL, NULL, NULL, 1, '2021-02-08 07:43:32', '2021-02-08 07:43:32', 'F0002', 'New Jersey', 2, 'tabseer.ishrat27@gmail.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fire_department_types`
--

CREATE TABLE `fire_department_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prefix_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fire_department_types`
--

INSERT INTO `fire_department_types` (`id`, `prefix_id`, `description`, `created_at`, `updated_at`) VALUES
(1, '001', 'FD1', '2021-02-08 07:41:57', '2021-02-08 07:41:57'),
(2, '002', 'FD2', '2021-02-08 07:42:03', '2021-02-08 07:42:03');

-- --------------------------------------------------------

--
-- Table structure for table `foreign_relations`
--

CREATE TABLE `foreign_relations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `foreign_id` int(11) NOT NULL,
  `module` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `foreign_relations`
--

INSERT INTO `foreign_relations` (`id`, `foreign_id`, `module`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 'firefighters', 'type', 'fire inspector', '2021-02-08 07:11:33', '2021-02-08 07:11:33'),
(2, 1, 'courses', 'credit_types', '1', '2021-02-08 07:13:04', '2021-02-08 07:13:04'),
(3, 1, 'courses', 'credit_types', '2', '2021-02-08 07:13:04', '2021-02-08 07:13:04'),
(4, 2, 'firefighters', 'type', 'fire instructor', '2021-02-08 07:26:39', '2021-02-08 07:26:39'),
(5, 1, 'facilities', 'facility_type', '1', '2021-02-08 07:35:03', '2021-02-08 07:35:03'),
(6, 2, 'facilities', 'facility_type', '2', '2021-02-08 07:36:18', '2021-02-08 07:36:18'),
(7, 1, 'fire_departments', 'fire_department_types', '1', '2021-02-08 07:42:53', '2021-02-08 07:42:53'),
(8, 2, 'fire_departments', 'fire_department_types', '1', '2021-02-08 07:43:32', '2021-02-08 07:43:32'),
(9, 2, 'fire_departments', 'fire_department_types', '2', '2021-02-08 07:43:32', '2021-02-08 07:43:32'),
(10, 1, 'certifications', 'credit_types', '2', '2021-02-08 07:52:40', '2021-02-08 07:52:40');

-- --------------------------------------------------------

--
-- Table structure for table `histories`
--

CREATE TABLE `histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `foreign_id` int(11) NOT NULL,
  `module` char(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `histories`
--

INSERT INTO `histories` (`id`, `user_id`, `foreign_id`, `module`, `data`, `created_at`) VALUES
(1, 1, 1, 'semesters', '[{\"label\":\"start_date\",\"prev\":\"2021-01-01\",\"new\":\"2021-02-10\"},{\"label\":\"end_date\",\"prev\":\"2021-03-31\",\"new\":\"2021-04-10\"}]', '2021-02-08 07:14:23'),
(2, 1, 1, 'semesters', '[{\"label\":\"start_date\",\"prev\":\"2021-02-10\",\"new\":\"2021-02-01\"}]', '2021-02-08 07:17:52'),
(3, 1, 1, 'courses', '[{\"label\":\"no_of_pre_req_course\",\"prev\":\"\",\"new\":\"\"},{\"label\":\"Instructor level\",\"prev\":\"3\",\"new\":1}]', '2021-02-08 07:29:16');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_prerequisites`
--

CREATE TABLE `instructor_prerequisites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `instructor_level` int(11) NOT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instructor_prerequisites`
--

INSERT INTO `instructor_prerequisites` (`id`, `instructor_level`, `course_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2021-02-08 07:16:22', '2021-02-08 07:16:22');

-- --------------------------------------------------------

--
-- Table structure for table `invite_firefighters`
--

CREATE TABLE `invite_firefighters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(297, 'default', '{\"uuid\":\"fecb07cc-0485-4730-804f-daf4fbf6d53c\",\"displayName\":\"App\\\\Jobs\\\\FirefighterCertificateAwardedJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\FirefighterCertificateAwardedJob\",\"command\":\"O:41:\\\"App\\\\Jobs\\\\FirefighterCertificateAwardedJob\\\":13:{s:11:\\\"firefighter\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Firefighter\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:11:\\\"certificate\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:17:\\\"App\\\\Certification\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:4:\\\"data\\\";a:5:{s:5:\\\"title\\\";s:24:\\\"DFS Credentialing System\\\";s:11:\\\"firefighter\\\";O:15:\\\"App\\\\Firefighter\\\":29:{s:8:\\\"\\u0000*\\u0000guard\\\";s:12:\\\"firefighters\\\";s:13:\\\"\\u0000*\\u0000connection\\\";s:5:\\\"mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:12:\\\"firefighters\\\";s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:57:{s:2:\\\"id\\\";s:1:\\\"1\\\";s:9:\\\"prefix_id\\\";s:6:\\\"000001\\\";s:6:\\\"f_name\\\";s:6:\\\"Arshia\\\";s:6:\\\"m_name\\\";s:0:\\\"\\\";s:6:\\\"l_name\\\";s:6:\\\"Qwerty\\\";s:3:\\\"dob\\\";s:10:\\\"1980-02-08\\\";s:6:\\\"gender\\\";s:6:\\\"female\\\";s:4:\\\"race\\\";N;s:9:\\\"appointed\\\";s:1:\\\"1\\\";s:16:\\\"instructor_level\\\";N;s:13:\\\"address_title\\\";s:12:\\\"Work Address\\\";s:7:\\\"address\\\";s:82:\\\"256 Banbury Road, Summertown, Oxford, OX2256 Banbury Road, Summertown, Oxford, OX2\\\";s:4:\\\"city\\\";s:6:\\\"Oxford\\\";s:5:\\\"state\\\";s:7:\\\"England\\\";s:7:\\\"zipcode\\\";s:7:\\\"GU167HF\\\";s:11:\\\"postal_mail\\\";N;s:15:\\\"address_title_2\\\";N;s:9:\\\"address_2\\\";N;s:6:\\\"city_2\\\";N;s:7:\\\"state_2\\\";N;s:9:\\\"zipcode_2\\\";N;s:13:\\\"postal_mail_2\\\";N;s:15:\\\"address_title_3\\\";N;s:9:\\\"address_3\\\";N;s:6:\\\"city_3\\\";N;s:7:\\\"state_3\\\";N;s:9:\\\"zipcode_3\\\";N;s:13:\\\"postal_mail_3\\\";N;s:10:\\\"home_phone\\\";N;s:10:\\\"cell_phone\\\";s:12:\\\"+13341077758\\\";s:19:\\\"cell_phone_verified\\\";N;s:10:\\\"work_phone\\\";N;s:14:\\\"work_phone_ext\\\";N;s:11:\\\"phone_token\\\";N;s:11:\\\"email_token\\\";N;s:10:\\\"work_email\\\";s:27:\\\"arshia@kingdom-vision.co.uk\\\";s:19:\\\"work_email_verified\\\";N;s:3:\\\"ssn\\\";N;s:3:\\\"ucc\\\";N;s:5:\\\"nicet\\\";N;s:4:\\\"fema\\\";N;s:4:\\\"muni\\\";N;s:3:\\\"vol\\\";N;s:3:\\\"car\\\";N;s:10:\\\"is_archive\\\";N;s:11:\\\"archived_at\\\";N;s:11:\\\"archived_by\\\";N;s:10:\\\"created_by\\\";N;s:10:\\\"created_at\\\";s:19:\\\"2021-02-08 07:11:33\\\";s:10:\\\"updated_at\\\";s:19:\\\"2021-02-08 07:11:33\\\";s:5:\\\"email\\\";s:27:\\\"arshia@kingdom-vision.co.uk\\\";s:17:\\\"email_verified_at\\\";N;s:8:\\\"password\\\";s:60:\\\"$2y$10$1WcsGuW2vMmkl9vnChbT.udloD004RLkZVn3UYWNXVVea83TjxfEy\\\";s:14:\\\"remember_token\\\";N;s:17:\\\"firefighter_image\\\";N;s:14:\\\"reset_password\\\";s:0:\\\"\\\";s:10:\\\"is_invited\\\";s:1:\\\"0\\\";}s:11:\\\"\\u0000*\\u0000original\\\";a:57:{s:2:\\\"id\\\";s:1:\\\"1\\\";s:9:\\\"prefix_id\\\";s:6:\\\"000001\\\";s:6:\\\"f_name\\\";s:6:\\\"Arshia\\\";s:6:\\\"m_name\\\";s:0:\\\"\\\";s:6:\\\"l_name\\\";s:6:\\\"Qwerty\\\";s:3:\\\"dob\\\";s:10:\\\"1980-02-08\\\";s:6:\\\"gender\\\";s:6:\\\"female\\\";s:4:\\\"race\\\";N;s:9:\\\"appointed\\\";s:1:\\\"1\\\";s:16:\\\"instructor_level\\\";N;s:13:\\\"address_title\\\";s:12:\\\"Work Address\\\";s:7:\\\"address\\\";s:82:\\\"256 Banbury Road, Summertown, Oxford, OX2256 Banbury Road, Summertown, Oxford, OX2\\\";s:4:\\\"city\\\";s:6:\\\"Oxford\\\";s:5:\\\"state\\\";s:7:\\\"England\\\";s:7:\\\"zipcode\\\";s:7:\\\"GU167HF\\\";s:11:\\\"postal_mail\\\";N;s:15:\\\"address_title_2\\\";N;s:9:\\\"address_2\\\";N;s:6:\\\"city_2\\\";N;s:7:\\\"state_2\\\";N;s:9:\\\"zipcode_2\\\";N;s:13:\\\"postal_mail_2\\\";N;s:15:\\\"address_title_3\\\";N;s:9:\\\"address_3\\\";N;s:6:\\\"city_3\\\";N;s:7:\\\"state_3\\\";N;s:9:\\\"zipcode_3\\\";N;s:13:\\\"postal_mail_3\\\";N;s:10:\\\"home_phone\\\";N;s:10:\\\"cell_phone\\\";s:12:\\\"+13341077758\\\";s:19:\\\"cell_phone_verified\\\";N;s:10:\\\"work_phone\\\";N;s:14:\\\"work_phone_ext\\\";N;s:11:\\\"phone_token\\\";N;s:11:\\\"email_token\\\";N;s:10:\\\"work_email\\\";s:27:\\\"arshia@kingdom-vision.co.uk\\\";s:19:\\\"work_email_verified\\\";N;s:3:\\\"ssn\\\";N;s:3:\\\"ucc\\\";N;s:5:\\\"nicet\\\";N;s:4:\\\"fema\\\";N;s:4:\\\"muni\\\";N;s:3:\\\"vol\\\";N;s:3:\\\"car\\\";N;s:10:\\\"is_archive\\\";N;s:11:\\\"archived_at\\\";N;s:11:\\\"archived_by\\\";N;s:10:\\\"created_by\\\";N;s:10:\\\"created_at\\\";s:19:\\\"2021-02-08 07:11:33\\\";s:10:\\\"updated_at\\\";s:19:\\\"2021-02-08 07:11:33\\\";s:5:\\\"email\\\";s:27:\\\"arshia@kingdom-vision.co.uk\\\";s:17:\\\"email_verified_at\\\";N;s:8:\\\"password\\\";s:60:\\\"$2y$10$1WcsGuW2vMmkl9vnChbT.udloD004RLkZVn3UYWNXVVea83TjxfEy\\\";s:14:\\\"remember_token\\\";N;s:17:\\\"firefighter_image\\\";N;s:14:\\\"reset_password\\\";s:0:\\\"\\\";s:10:\\\"is_invited\\\";s:1:\\\"0\\\";}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:1;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}s:10:\\\"\\u0000*\\u0000guarded\\\";a:1:{i:0;s:1:\\\"*\\\";}s:20:\\\"\\u0000*\\u0000rememberTokenName\\\";s:14:\\\"remember_token\\\";}s:11:\\\"certificate\\\";O:17:\\\"App\\\\Certification\\\":27:{s:13:\\\"\\u0000*\\u0000connection\\\";s:5:\\\"mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:14:\\\"certifications\\\";s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:17:{s:2:\\\"id\\\";s:1:\\\"1\\\";s:9:\\\"prefix_id\\\";s:5:\\\"FFC01\\\";s:5:\\\"title\\\";s:23:\\\"Firefighter Certificate\\\";s:11:\\\"short_title\\\";s:3:\\\"FFC\\\";s:8:\\\"geo_sent\\\";N;s:9:\\\"renewable\\\";s:1:\\\"0\\\";s:14:\\\"renewal_period\\\";N;s:6:\\\"pboard\\\";N;s:5:\\\"ifsac\\\";N;s:18:\\\"no_of_credit_types\\\";s:1:\\\"1\\\";s:18:\\\"no_of_pre_req_cert\\\";N;s:20:\\\"no_of_pre_req_course\\\";N;s:9:\\\"admin_ceu\\\";s:4:\\\"0.00\\\";s:8:\\\"tech_ceu\\\";s:4:\\\"0.10\\\";s:10:\\\"created_by\\\";s:1:\\\"1\\\";s:10:\\\"created_at\\\";s:19:\\\"2021-02-08 07:52:40\\\";s:10:\\\"updated_at\\\";s:19:\\\"2021-02-08 07:52:40\\\";}s:11:\\\"\\u0000*\\u0000original\\\";a:17:{s:2:\\\"id\\\";s:1:\\\"1\\\";s:9:\\\"prefix_id\\\";s:5:\\\"FFC01\\\";s:5:\\\"title\\\";s:23:\\\"Firefighter Certificate\\\";s:11:\\\"short_title\\\";s:3:\\\"FFC\\\";s:8:\\\"geo_sent\\\";N;s:9:\\\"renewable\\\";s:1:\\\"0\\\";s:14:\\\"renewal_period\\\";N;s:6:\\\"pboard\\\";N;s:5:\\\"ifsac\\\";N;s:18:\\\"no_of_credit_types\\\";s:1:\\\"1\\\";s:18:\\\"no_of_pre_req_cert\\\";N;s:20:\\\"no_of_pre_req_course\\\";N;s:9:\\\"admin_ceu\\\";s:4:\\\"0.00\\\";s:8:\\\"tech_ceu\\\";s:4:\\\"0.10\\\";s:10:\\\"created_by\\\";s:1:\\\"1\\\";s:10:\\\"created_at\\\";s:19:\\\"2021-02-08 07:52:40\\\";s:10:\\\"updated_at\\\";s:19:\\\"2021-02-08 07:52:40\\\";}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:1;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}s:10:\\\"\\u0000*\\u0000guarded\\\";a:1:{i:0;s:1:\\\"*\\\";}}s:10:\\\"issue_date\\\";s:12:\\\"Feb 08, 2021\\\";s:10:\\\"lapse_date\\\";N;}s:10:\\\"issue_date\\\";s:10:\\\"2021-02-08\\\";s:7:\\\"subject\\\";s:65:\\\"Credential Awarded to Arshia   Qwerty for Firefighter Certificate\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1612783471, 1612783471),
(296, 'default', '{\"uuid\":\"cfd05771-b542-4494-969e-32db052e0f43\",\"displayName\":\"App\\\\Jobs\\\\CertificationApprovedJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\CertificationApprovedJob\",\"command\":\"O:33:\\\"App\\\\Jobs\\\\CertificationApprovedJob\\\":14:{s:17:\\\"firefighter_email\\\";s:27:\\\"arshia@kingdom-vision.co.uk\\\";s:11:\\\"firefighter\\\";s:6:\\\"Arshia\\\";s:13:\\\"certification\\\";s:23:\\\"Firefighter Certificate\\\";s:9:\\\"test_date\\\";s:10:\\\"2021-02-10\\\";s:9:\\\"test_time\\\";s:8:\\\"11:35:00\\\";s:7:\\\"subject\\\";s:53:\\\"Test scheduled for Credential Firefighter Certificate\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1612783132, 1612783132),
(292, 'default', '{\"uuid\":\"a09f47cd-5a58-4474-b8ee-9495fd05789e\",\"displayName\":\"App\\\\Jobs\\\\CourseEnrollmentJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\CourseEnrollmentJob\",\"command\":\"O:28:\\\"App\\\\Jobs\\\\CourseEnrollmentJob\\\":13:{s:17:\\\"firefighter_email\\\";s:20:\\\"obaid777@yopmail.com\\\";s:11:\\\"firefighter\\\";s:4:\\\"Brad\\\";s:6:\\\"course\\\";s:7:\\\"Physics\\\";s:8:\\\"semester\\\";s:11:\\\"spring 2021\\\";s:7:\\\"subject\\\";s:49:\\\"Approval of Enrollment Request for Course Physics\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1612766415, 1612766415),
(293, 'default', '{\"uuid\":\"e7cb45ef-768a-461b-860b-4d5addfbc034\",\"displayName\":\"App\\\\Jobs\\\\FirefighterCourseEnrollementJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\FirefighterCourseEnrollementJob\",\"command\":\"O:40:\\\"App\\\\Jobs\\\\FirefighterCourseEnrollementJob\\\":19:{s:10:\\\"admin_name\\\";s:6:\\\"richlo\\\";s:11:\\\"admin_email\\\";s:22:\\\"sqatestingid@gmail.com\\\";s:17:\\\"firefighter_email\\\";s:27:\\\"arshia@kingdom-vision.co.uk\\\";s:18:\\\"firefighter_f_name\\\";s:6:\\\"Arshia\\\";s:18:\\\"firefighter_m_name\\\";s:0:\\\"\\\";s:18:\\\"firefighter_l_name\\\";s:6:\\\"Qwerty\\\";s:10:\\\"cell_phone\\\";s:12:\\\"+13341077758\\\";s:17:\\\"semester_semester\\\";s:6:\\\"spring\\\";s:13:\\\"semester_year\\\";s:4:\\\"2021\\\";s:11:\\\"course_name\\\";s:24:\\\"Fire Safety Instructions\\\";s:7:\\\"subject\\\";s:54:\\\"Request for Course Enrollment Fire Safety Instructions\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1612768486, 1612768486),
(294, 'default', '{\"uuid\":\"a068ad23-abaa-42a8-91a0-d59d97b773b4\",\"displayName\":\"App\\\\Jobs\\\\CourseEnrollmentJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\CourseEnrollmentJob\",\"command\":\"O:28:\\\"App\\\\Jobs\\\\CourseEnrollmentJob\\\":13:{s:17:\\\"firefighter_email\\\";s:27:\\\"arshia@kingdom-vision.co.uk\\\";s:11:\\\"firefighter\\\";s:6:\\\"Arshia\\\";s:6:\\\"course\\\";s:24:\\\"Fire Safety Instructions\\\";s:8:\\\"semester\\\";s:11:\\\"spring 2021\\\";s:7:\\\"subject\\\";s:66:\\\"Approval of Enrollment Request for Course Fire Safety Instructions\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1612768527, 1612768527),
(295, 'default', '{\"uuid\":\"9e6be5c3-d8a1-43d4-99ad-1195919894b2\",\"displayName\":\"App\\\\Jobs\\\\FirefighterCertificateEnrollementJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"delay\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\FirefighterCertificateEnrollementJob\",\"command\":\"O:45:\\\"App\\\\Jobs\\\\FirefighterCertificateEnrollementJob\\\":17:{s:10:\\\"admin_name\\\";s:6:\\\"richlo\\\";s:11:\\\"admin_email\\\";s:22:\\\"sqatestingid@gmail.com\\\";s:17:\\\"firefighter_email\\\";s:27:\\\"arshia@kingdom-vision.co.uk\\\";s:18:\\\"firefighter_f_name\\\";s:6:\\\"Arshia\\\";s:18:\\\"firefighter_m_name\\\";s:0:\\\"\\\";s:18:\\\"firefighter_l_name\\\";s:6:\\\"Qwerty\\\";s:10:\\\"cell_phone\\\";s:12:\\\"+13341077758\\\";s:11:\\\"certificate\\\";s:23:\\\"Firefighter Certificate\\\";s:7:\\\"subject\\\";s:46:\\\"Request for Credential Firefighter Certificate\\\";s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1612783090, 1612783090);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_08_20_164816_create_roles', 1),
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2020_04_22_062454_create_settings_table', 2),
(9, '2020_07_03_054340_create_firefighters_table', 3),
(15, '2016_06_01_000001_create_oauth_auth_codes_table', 4),
(16, '2016_06_01_000002_create_oauth_access_tokens_table', 4),
(17, '2016_06_01_000003_create_oauth_refresh_tokens_table', 4),
(18, '2016_06_01_000004_create_oauth_clients_table', 4),
(19, '2016_06_01_000005_create_oauth_personal_access_clients_table', 4),
(24, '2020_07_06_104906_create_foreign_relations_table', 5),
(25, '2020_07_08_054558_create_histories_table', 6),
(26, '2020_07_09_105920_create_credit_types', 7),
(33, '2020_07_10_053933_create_courses_table', 8),
(42, '2020_07_13_055320_create_semesters_table', 9),
(43, '2020_07_13_061528_create_semester_courses_table', 9),
(49, '2020_07_14_062417_create_organizations_table', 10),
(54, '2020_07_14_121356_create_facilities_table', 11),
(55, '2020_07_17_072614_create_facility_types_table', 11),
(58, '2020_07_20_110200_create_classes_table', 12),
(61, '2020_07_22_132724_create_course_classes_table', 13),
(84, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(85, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(86, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(87, '2016_06_01_000004_create_oauth_clients_table', 1),
(88, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(89, '2019_08_19_000000_create_failed_jobs_table', 1),
(90, '2020_04_22_062454_create_settings_table', 1),
(91, '2020_07_03_054340_create_firefighters_table', 1),
(92, '2020_07_06_104906_create_foreign_relations_table', 1),
(93, '2020_07_08_054558_create_histories_table', 1),
(94, '2020_07_09_105920_create_credit_types', 1),
(95, '2020_07_10_053933_create_courses_table', 1),
(96, '2020_07_13_055320_create_semesters_table', 1),
(97, '2020_07_13_061528_create_semester_courses_table', 1),
(98, '2020_07_14_062417_create_organizations_table', 1),
(99, '2020_07_14_121356_create_facilities_table', 1),
(100, '2020_07_17_072614_create_facility_types_table', 1),
(101, '2020_07_20_110200_create_classes_table', 1),
(102, '2020_07_22_132724_create_course_classes_table', 1),
(104, '2020_07_29_062715_create_fire_departments_table', 14),
(108, '2020_08_17_060314_create_completed_courses_table', 15),
(112, '2020_08_19_062044_create_certifications_table', 16),
(115, '2020_08_20_070025_create_prerequisites_table', 17),
(116, '2020_08_22_124814_create_awarded_certificates_table', 18),
(117, '2014_08_20_164816_create_roles', 1),
(118, '2014_10_12_000000_create_users_table', 1),
(119, '2014_10_12_100000_create_password_resets_table', 1),
(120, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(121, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(122, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(123, '2016_06_01_000004_create_oauth_clients_table', 1),
(124, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(125, '2019_08_19_000000_create_failed_jobs_table', 1),
(126, '2020_04_22_062454_create_settings_table', 1),
(127, '2020_07_03_054340_create_firefighters_table', 1),
(128, '2020_07_06_104906_create_foreign_relations_table', 1),
(129, '2020_07_08_054558_create_histories_table', 1),
(130, '2020_07_09_105920_create_credit_types', 1),
(131, '2020_07_10_053933_create_courses_table', 1),
(132, '2020_07_13_055320_create_semesters_table', 1),
(133, '2020_07_13_061528_create_semester_courses_table', 1),
(134, '2020_07_14_062417_create_organizations_table', 1),
(135, '2020_07_14_121356_create_facilities_table', 1),
(136, '2020_07_17_072614_create_facility_types_table', 1),
(137, '2020_07_20_110200_create_classes_table', 1),
(138, '2020_07_22_132724_create_course_classes_table', 1),
(139, '2020_07_29_062715_create_fire_departments_table', 1),
(140, '2020_08_17_060314_create_completed_courses_table', 1),
(141, '2020_08_19_062044_create_certifications_table', 1),
(142, '2020_08_20_070025_create_prerequisites_table', 1),
(143, '2020_08_22_124814_create_awarded_certificates_table', 1),
(171, '2014_08_20_164816_create_permission_tables', 1),
(172, '2014_10_12_000000_create_users_table', 1),
(173, '2014_10_12_100000_create_password_resets_table', 1),
(174, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(175, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(176, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(177, '2016_06_01_000004_create_oauth_clients_table', 1),
(178, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(179, '2019_08_19_000000_create_failed_jobs_table', 1),
(180, '2020_04_22_062454_create_settings_table', 1),
(181, '2020_07_03_054340_create_firefighters_table', 1),
(182, '2020_07_06_104906_create_foreign_relations_table', 1),
(183, '2020_07_08_054558_create_histories_table', 1),
(184, '2020_07_09_105920_create_credit_types', 1),
(185, '2020_07_10_053933_create_courses_table', 1),
(186, '2020_07_13_055320_create_semesters_table', 1),
(187, '2020_07_13_061528_create_semester_courses_table', 1),
(188, '2020_07_14_062417_create_organizations_table', 1),
(189, '2020_07_14_121356_create_facilities_table', 1),
(190, '2020_07_17_072614_create_facility_types_table', 1),
(191, '2020_07_20_110200_create_classes_table', 1),
(192, '2020_07_22_132724_create_course_classes_table', 1),
(193, '2020_07_29_062715_create_fire_departments_table', 1),
(194, '2020_08_17_060314_create_completed_courses_table', 1),
(195, '2020_08_19_062044_create_certifications_table', 1),
(196, '2020_08_20_070025_create_prerequisites_table', 1),
(197, '2020_08_22_124814_create_awarded_certificates_table', 1),
(225, '2014_08_20_164816_create_permission_tables', 1),
(226, '2014_10_12_000000_create_users_table', 1),
(227, '2014_10_12_100000_create_password_resets_table', 1),
(228, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(229, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(230, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(231, '2016_06_01_000004_create_oauth_clients_table', 1),
(232, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(233, '2019_08_19_000000_create_failed_jobs_table', 1),
(234, '2020_04_22_062454_create_settings_table', 1),
(235, '2020_07_03_054340_create_firefighters_table', 1),
(236, '2020_07_06_104906_create_foreign_relations_table', 1),
(237, '2020_07_08_054558_create_histories_table', 1),
(238, '2020_07_09_105920_create_credit_types', 1),
(239, '2020_07_10_053933_create_courses_table', 1),
(240, '2020_07_13_055320_create_semesters_table', 1),
(241, '2020_07_13_061528_create_semester_courses_table', 1),
(242, '2020_07_14_062417_create_organizations_table', 1),
(243, '2020_07_14_121356_create_facilities_table', 1),
(244, '2020_07_17_072614_create_facility_types_table', 1),
(245, '2020_07_20_110200_create_classes_table', 1),
(246, '2020_07_22_132724_create_course_classes_table', 1),
(247, '2020_07_29_062715_create_fire_departments_table', 1),
(248, '2020_08_17_060314_create_completed_courses_table', 1),
(249, '2020_08_19_062044_create_certifications_table', 1),
(250, '2020_08_20_070025_create_prerequisites_table', 1),
(251, '2020_08_22_124814_create_awarded_certificates_table', 1),
(252, '2020_07_29_062714_create_fire_departments_types_table', 19),
(254, '2020_10_19_111914_add_columns_to_fire_departments_table', 20),
(255, '2020_10_22_100942_create_instructor_prerequisites_table', 21),
(256, '2020_10_26_134513_add_columns_to_facilities_table', 22),
(257, '2020_10_26_140620_add_columns_to_organizations_table', 22),
(258, '2020_10_27_144520_add_geo_sent_column_to_awarded_certificates_table', 23),
(259, '2020_10_28_110637_add_instrctv_lvl_column_to_firefighters_table', 24),
(261, '2020_10_29_071409_add_date_ranges_column_to_semesters_table', 25),
(262, '2020_11_11_094046_add_columns_to_firefighters', 26),
(263, '2020_11_18_061954_create_course_prerequisites_table', 26),
(264, '2020_11_18_062714_add_column_to_courses_table', 26),
(265, '2020_11_18_063132_create_firefighter_courses_table', 26),
(266, '2020_11_18_064801_create_rejected_reasons_table', 26),
(267, '2020_11_18_065543_drop_columns_to_classes', 26),
(268, '2020_11_19_093940_add_reset_password_column_to_firefighters_table', 26),
(269, '2020_11_20_071642_add_is_invited_column_to_firefighters_table', 27),
(270, '2020_12_02_105300_create_firefighter_certificates_table', 28),
(271, '2020_12_02_105629_create_certificate_statuses_table', 28),
(272, '2020_12_02_110330_create_certificate_rejected_reasons_table', 28),
(273, '2020_12_02_110929_add_firefighters_read_status_column_to_awarded_certificates', 28),
(274, '2020_12_03_095107_create_invite_firefighters_table', 29),
(275, '2020_12_09_101829_add_test_status_column_to_firefighter_certificates', 30),
(276, '2020_12_10_112140_add_fire_department_id_to_classes', 31),
(277, '2020_12_14_063408_add_semester_column_to_course_classes', 32),
(278, '2020_12_16_063112_create_jobs_table', 32),
(279, '2021_01_05_111835_add_column_end_time_into_classes', 33);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\User', 1),
(3, 'App\\User', 2),
(4, 'App\\User', 3),
(2, 'App\\User', 5),
(2, 'App\\User', 6),
(2, 'App\\User', 9),
(1, 'App\\User', 10);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prefix_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_municipal_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci,
  `email_2` text COLLATE utf8mb4_unicode_ci,
  `email_3` text COLLATE utf8mb4_unicode_ci,
  `type` enum('fire department','fire district','government','voc-tech','higher education','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signator` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signator_phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_municipality` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_zipcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physical_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physical_municipality` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physical_state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physical_zipcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_archive` tinyint(1) DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  `archived_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `prefix_id`, `country_municipal_code`, `name`, `email`, `email_2`, `email_3`, `type`, `other_type`, `phone`, `fax`, `signator`, `signator_phone`, `mail_address`, `mail_municipality`, `mail_state`, `mail_zipcode`, `physical_address`, `physical_municipality`, `physical_state`, `physical_zipcode`, `is_archive`, `archived_at`, `archived_by`, `created_at`, `updated_at`) VALUES
(1, 'E00001', '12345', 'Organization 01', 'john@gmail.com', '', '', 'government', NULL, NULL, NULL, 'John Smith', '+13341077758', '1 Apple Park Way, Cupertino, CA, USA', 'qwerty', 'California', '95014', '1 Apple Park Way, Cupertino, CA, USA', 'qwerty', 'California', '95014', NULL, NULL, NULL, '2021-02-08 07:22:13', '2021-02-08 07:22:13'),
(2, 'E00002', 'United Kingdom', 'London School of Fire Handling', 'sqatestingid@gmail.com', '', '', 'fire department', NULL, '+19923156121', NULL, 'Richlo', '+19952313256', 'Oxford Street, London, UK', 'Municipal corporation', 'England', '80909', 'Oxford Street, London, UK', 'Municipal corporation', 'England', NULL, NULL, NULL, NULL, '2021-02-08 07:38:42', '2021-02-08 07:39:46');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('sqatestingid@gmail.com', '$2y$10$M48U6fyeoS53FcYj/8B5q.yBmYv.YYBKPEjnQdsqVDBGM17choAvG', '2020-11-28 11:05:36');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'firefighters.create', 'web', NULL, NULL),
(2, 'firefighters.read', 'web', NULL, NULL),
(3, 'firefighters.update', 'web', NULL, NULL),
(4, 'firefighters.delete', 'web', NULL, NULL),
(5, 'semesters.create', 'web', NULL, NULL),
(6, 'semesters.read', 'web', NULL, NULL),
(7, 'semesters.update', 'web', NULL, NULL),
(8, 'semesters.delete', 'web', NULL, NULL),
(9, 'courses.create', 'web', NULL, NULL),
(10, 'courses.read', 'web', NULL, NULL),
(11, 'courses.update', 'web', NULL, NULL),
(12, 'courses.delete', 'web', NULL, NULL),
(13, 'certifications.create', 'web', NULL, NULL),
(14, 'certifications.read', 'web', NULL, NULL),
(15, 'certifications.update', 'web', NULL, NULL),
(16, 'certifications.delete', 'web', NULL, NULL),
(17, 'fire_departments.create', 'web', NULL, NULL),
(18, 'fire_departments.read', 'web', NULL, NULL),
(19, 'fire_departments.update', 'web', NULL, NULL),
(20, 'fire_departments.delete', 'web', NULL, NULL),
(21, 'organizations.create', 'web', NULL, NULL),
(22, 'organizations.read', 'web', NULL, NULL),
(23, 'organizations.update', 'web', NULL, NULL),
(24, 'organizations.delete', 'web', NULL, NULL),
(25, 'facilities.create', 'web', NULL, NULL),
(26, 'facilities.read', 'web', NULL, NULL),
(27, 'facilities.update', 'web', NULL, NULL),
(28, 'facilities.delete', 'web', NULL, NULL),
(29, 'settings.create', 'web', NULL, NULL),
(30, 'settings.read', 'web', NULL, NULL),
(31, 'settings.update', 'web', NULL, NULL),
(32, 'settings.delete', 'web', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prerequisites`
--

CREATE TABLE `prerequisites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `certification_id` bigint(20) UNSIGNED NOT NULL,
  `pre_req_course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pre_req_certificate_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rejected_reasons`
--

CREATE TABLE `rejected_reasons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firefighter_courses_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', NULL, NULL),
(2, 'staff', 'web', '2020-09-10 05:58:58', '2020-09-10 06:00:33');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(6, 2),
(9, 2),
(10, 2),
(11, 2),
(12, 2),
(1, 6),
(2, 6),
(3, 6),
(4, 6),
(5, 6),
(6, 6),
(7, 6),
(8, 6),
(9, 6),
(10, 6),
(11, 6),
(12, 6),
(13, 6),
(14, 6),
(15, 6),
(16, 6),
(17, 6),
(18, 6),
(19, 6),
(20, 6),
(21, 6),
(22, 6),
(23, 6),
(24, 6),
(29, 6),
(30, 6),
(31, 6),
(32, 6);

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `semester` enum('spring','summer','fall','winter') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `year` int(11) NOT NULL,
  `is_archive` tinyint(1) DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  `archived_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `semester`, `start_date`, `end_date`, `year`, `is_archive`, `archived_at`, `archived_by`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'spring', '2021-02-01', '2021-04-10', 2021, NULL, NULL, NULL, 1, '2021-02-08 07:13:27', '2021-02-08 07:17:52');

-- --------------------------------------------------------

--
-- Table structure for table `semester_courses`
--

CREATE TABLE `semester_courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `semester_courses`
--

INSERT INTO `semester_courses` (`id`, `semester_id`, `course_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'per_page', '10'),
(2, 'min_attendance_perc', '50'),
(3, 'fall_start', NULL),
(4, 'enrollment_limit', '3'),
(5, 'pboard', NULL),
(6, 'ifsac', NULL),
(7, 'logo', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('male','female','transgender','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `race` enum('american indian or alaskan native','asian or pacific islander','black, not of hispanic origin','white, not of hispanic origin','hispanic') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_image` text COLLATE utf8mb4_unicode_ci,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cell_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cell_phone_verified` tinyint(1) DEFAULT NULL,
  `work_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_phone_ext` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_archive` tinyint(1) DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  `archived_by` bigint(20) UNSIGNED DEFAULT NULL,
  `invited_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reset_password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `email_verified_at`, `password`, `dob`, `gender`, `race`, `user_image`, `address`, `city`, `state`, `zipcode`, `home_phone`, `cell_phone`, `cell_phone_verified`, `work_phone`, `work_phone_ext`, `remember_token`, `is_archive`, `archived_at`, `archived_by`, `invited_by`, `reset_password`, `created_at`, `updated_at`) VALUES
(1, 1, 'richlo', 'sqatestingid@gmail.com', NULL, '$2y$10$1R97i4dB3at1x.w6EakiIeVjoUXdboN6D5W5rJuoMpd9mQdjKNtca', '2020-10-23', 'male', 'black, not of hispanic origin', 'demo-image1-5f64b3c815707.jpg', '256 Banbury Road, Summertown, Oxford, OX2', 'Oxford', 'United Kingdom', 'GU16 7HF', 'abcd', 'abcd', NULL, 'abcd', 'abcd', '7uP6BZcOeffuS6Bq0wr4gHZpemGXrAei5nwmD2mcOQyECqn3gSXWJtHgJeZO', NULL, NULL, NULL, NULL, NULL, NULL, '2020-10-23 10:08:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `awarded_certificates`
--
ALTER TABLE `awarded_certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `awarded_certificates_certificate_id_foreign` (`certificate_id`),
  ADD KEY `awarded_certificates_firefighter_id_foreign` (`firefighter_id`),
  ADD KEY `awarded_certificates_organization_id_foreign` (`organization_id`);

--
-- Indexes for table `certificate_rejected_reasons`
--
ALTER TABLE `certificate_rejected_reasons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificate_rejected_reasons_firefighter_certificates_id_foreign` (`firefighter_certificates_id`),
  ADD KEY `certificate_rejected_reasons_firefighter_id_foreign` (`firefighter_id`);

--
-- Indexes for table `certificate_statuses`
--
ALTER TABLE `certificate_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificate_statuses_firefighter_certificates_id_foreign` (`firefighter_certificates_id`),
  ADD KEY `certificate_statuses_firefighter_id_foreign` (`firefighter_id`);

--
-- Indexes for table `certifications`
--
ALTER TABLE `certifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certifications_created_by_foreign` (`created_by`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classes_semester_id_foreign` (`semester_id`),
  ADD KEY `classes_course_id_foreign` (`course_id`),
  ADD KEY `classes_organization_id_foreign` (`organization_id`),
  ADD KEY `classes_instructor_id_foreign` (`instructor_id`),
  ADD KEY `classes_facility_id_foreign` (`facility_id`),
  ADD KEY `classes_archived_by_foreign` (`archived_by`),
  ADD KEY `classes_created_by_foreign` (`created_by`),
  ADD KEY `classes_fire_department_id_foreign` (`fire_department_id`);

--
-- Indexes for table `completed_courses`
--
ALTER TABLE `completed_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `completed_courses_firefighter_id_foreign` (`firefighter_id`),
  ADD KEY `completed_courses_semester_id_foreign` (`semester_id`),
  ADD KEY `completed_courses_course_id_foreign` (`course_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courses_archived_by_foreign` (`archived_by`),
  ADD KEY `courses_created_by_foreign` (`created_by`);

--
-- Indexes for table `course_classes`
--
ALTER TABLE `course_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_classes_course_id_foreign` (`course_id`),
  ADD KEY `course_classes_firefighter_id_foreign` (`firefighter_id`);

--
-- Indexes for table `course_prerequisites`
--
ALTER TABLE `course_prerequisites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_prerequisites_course_id_foreign` (`course_id`),
  ADD KEY `course_prerequisites_preq_course_id_foreign` (`preq_course_id`);

--
-- Indexes for table `credit_types`
--
ALTER TABLE `credit_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facilities_organization_foreign` (`organization`),
  ADD KEY `facilities_archived_by_foreign` (`archived_by`),
  ADD KEY `facilities_created_by_foreign` (`created_by`);

--
-- Indexes for table `facility_types`
--
ALTER TABLE `facility_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `firefighters`
--
ALTER TABLE `firefighters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `firefighters_cell_phone_unique` (`cell_phone`),
  ADD UNIQUE KEY `firefighters_work_email_unique` (`work_email`),
  ADD KEY `firefighters_archived_by_foreign` (`archived_by`),
  ADD KEY `firefighters_created_by_foreign` (`created_by`);

--
-- Indexes for table `firefighter_certificates`
--
ALTER TABLE `firefighter_certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `firefighter_certificates_firefighter_id_foreign` (`firefighter_id`),
  ADD KEY `firefighter_certificates_certificate_id_foreign` (`certificate_id`);

--
-- Indexes for table `firefighter_courses`
--
ALTER TABLE `firefighter_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `firefighter_courses_semester_id_foreign` (`semester_id`),
  ADD KEY `firefighter_courses_course_id_foreign` (`course_id`),
  ADD KEY `firefighter_courses_firefighter_id_foreign` (`firefighter_id`);

--
-- Indexes for table `fire_departments`
--
ALTER TABLE `fire_departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fire_departments_archived_by_foreign` (`archived_by`),
  ADD KEY `fire_departments_created_by_foreign` (`created_by`);

--
-- Indexes for table `fire_department_types`
--
ALTER TABLE `fire_department_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `foreign_relations`
--
ALTER TABLE `foreign_relations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `histories`
--
ALTER TABLE `histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `histories_user_id_foreign` (`user_id`);

--
-- Indexes for table `instructor_prerequisites`
--
ALTER TABLE `instructor_prerequisites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructor_prerequisites_course_id_foreign` (`course_id`);

--
-- Indexes for table `invite_firefighters`
--
ALTER TABLE `invite_firefighters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizations_archived_by_foreign` (`archived_by`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prerequisites`
--
ALTER TABLE `prerequisites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prerequisites_certification_id_foreign` (`certification_id`),
  ADD KEY `prerequisites_pre_req_course_id_foreign` (`pre_req_course_id`),
  ADD KEY `prerequisites_pre_req_certificate_id_foreign` (`pre_req_certificate_id`);

--
-- Indexes for table `rejected_reasons`
--
ALTER TABLE `rejected_reasons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rejected_reasons_firefighter_courses_id_foreign` (`firefighter_courses_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `semesters_archived_by_foreign` (`archived_by`),
  ADD KEY `semesters_created_by_foreign` (`created_by`);

--
-- Indexes for table `semester_courses`
--
ALTER TABLE `semester_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `semester_courses_semester_id_foreign` (`semester_id`),
  ADD KEY `semester_courses_course_id_foreign` (`course_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_name_unique` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_cell_phone_unique` (`cell_phone`),
  ADD UNIQUE KEY `users_reset_password_unique` (`reset_password`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_archived_by_foreign` (`archived_by`),
  ADD KEY `users_invited_by_foreign` (`invited_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `awarded_certificates`
--
ALTER TABLE `awarded_certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `certificate_rejected_reasons`
--
ALTER TABLE `certificate_rejected_reasons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificate_statuses`
--
ALTER TABLE `certificate_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `certifications`
--
ALTER TABLE `certifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `completed_courses`
--
ALTER TABLE `completed_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `course_classes`
--
ALTER TABLE `course_classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_prerequisites`
--
ALTER TABLE `course_prerequisites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_types`
--
ALTER TABLE `credit_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `facility_types`
--
ALTER TABLE `facility_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `firefighters`
--
ALTER TABLE `firefighters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `firefighter_certificates`
--
ALTER TABLE `firefighter_certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `firefighter_courses`
--
ALTER TABLE `firefighter_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fire_departments`
--
ALTER TABLE `fire_departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fire_department_types`
--
ALTER TABLE `fire_department_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `foreign_relations`
--
ALTER TABLE `foreign_relations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `histories`
--
ALTER TABLE `histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `instructor_prerequisites`
--
ALTER TABLE `instructor_prerequisites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invite_firefighters`
--
ALTER TABLE `invite_firefighters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=298;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `prerequisites`
--
ALTER TABLE `prerequisites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rejected_reasons`
--
ALTER TABLE `rejected_reasons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `semester_courses`
--
ALTER TABLE `semester_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certifications`
--
ALTER TABLE `certifications`
  ADD CONSTRAINT `certifications_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_fire_department_id_foreign` FOREIGN KEY (`fire_department_id`) REFERENCES `fire_departments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
