-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 29, 2022 at 08:31 AM
-- Server version: 5.7.31
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `allmedik`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

DROP TABLE IF EXISTS `appointment`;
CREATE TABLE IF NOT EXISTS `appointment` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `allergies` varchar(1) NOT NULL,
  `allergies_reason` varchar(1000) DEFAULT NULL,
  `diabetic` varchar(1) NOT NULL,
  `diabetic_reason` varchar(1000) DEFAULT NULL,
  `asthmatic` varchar(1) NOT NULL,
  `asthmatic_reason` varchar(1000) DEFAULT NULL,
  `hypertensive` varchar(1) NOT NULL,
  `hypertensive_reason` varchar(1000) DEFAULT NULL,
  `smoke` varchar(1) NOT NULL,
  `smoke_reason` varchar(1000) DEFAULT NULL,
  `alcohol` varchar(1) NOT NULL,
  `alcohol_reason` varchar(1000) DEFAULT NULL,
  `lung_infection` varchar(1) NOT NULL,
  `lung_infection_reason` varchar(1000) DEFAULT NULL,
  `surgery` varchar(1) NOT NULL,
  `surgery_reason` varchar(1000) DEFAULT NULL,
  `covid_vacinated` varchar(1) NOT NULL,
  `covid_vacinated_reason` varchar(1000) DEFAULT NULL,
  `covid_contact` varchar(1) NOT NULL,
  `covid_contact_reason` varchar(1000) DEFAULT NULL,
  `ent_date` timestamp NULL DEFAULT NULL,
  `duration_id` bigint(3) DEFAULT NULL,
  `appoint_to` int(3) NOT NULL,
  `problem` longtext NOT NULL,
  `appoint_by` bigint(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `allergies`, `allergies_reason`, `diabetic`, `diabetic_reason`, `asthmatic`, `asthmatic_reason`, `hypertensive`, `hypertensive_reason`, `smoke`, `smoke_reason`, `alcohol`, `alcohol_reason`, `lung_infection`, `lung_infection_reason`, `surgery`, `surgery_reason`, `covid_vacinated`, `covid_vacinated_reason`, `covid_contact`, `covid_contact_reason`, `ent_date`, `duration_id`, `appoint_to`, `problem`, `appoint_by`) VALUES
(3, 'Y', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'N', '', 'N', '', 'N', '', 'N', '', 'N', '', 'N', '', 'N', '', 'N', '', 'N', '', '2022-05-23 12:13:17', 4, 6, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 9);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_dtl_exp`
--

DROP TABLE IF EXISTS `doctor_dtl_exp`;
CREATE TABLE IF NOT EXISTS `doctor_dtl_exp` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(11) NOT NULL,
  `institute` varchar(500) NOT NULL,
  `job_title` varchar(500) NOT NULL,
  `job_from` varchar(20) NOT NULL,
  `job_end` varchar(20) NOT NULL,
  `total_years` bigint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `doctor_dtl_exp`
--

INSERT INTO `doctor_dtl_exp` (`id`, `register_id`, `institute`, `job_title`, `job_from`, `job_end`, `total_years`) VALUES
(1, 6, 'Standard Lorem Ipsum', 'Finibus Bonorum et Malorum', '05/01/2022', '05/25/2022', 5);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_edu`
--

DROP TABLE IF EXISTS `doctor_edu`;
CREATE TABLE IF NOT EXISTS `doctor_edu` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(11) NOT NULL,
  `degree` varchar(1000) NOT NULL,
  `start_year` bigint(5) NOT NULL,
  `end_year` bigint(5) NOT NULL,
  `institute` varchar(1000) NOT NULL,
  `ent_date` timestamp NULL DEFAULT NULL,
  `certificate_path` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `doctor_edu`
--

INSERT INTO `doctor_edu` (`id`, `register_id`, `degree`, `start_year`, `end_year`, `institute`, `ent_date`, `certificate_path`) VALUES
(1, 6, 'Lorem Ipsum', 2010, 2014, 'De Finibus Bonorum et Malorum', '2022-05-14 04:54:01', 'edu_certificate_1_6.jpg'),
(2, 6, 'Lorem Ipsum', 2014, 2018, 'Finibus Bonorum Et Malorum', '2022-05-14 04:54:59', 'edu_certificate_2_6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_mst`
--

DROP TABLE IF EXISTS `doctor_mst`;
CREATE TABLE IF NOT EXISTS `doctor_mst` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(11) NOT NULL,
  `fname` varchar(250) NOT NULL,
  `lname` varchar(250) NOT NULL,
  `date_of_birth` varchar(20) NOT NULL,
  `age` int(3) NOT NULL DEFAULT '0',
  `height` int(3) NOT NULL DEFAULT '0',
  `weight` int(3) NOT NULL DEFAULT '0',
  `gender` varchar(1) NOT NULL,
  `martial_status` varchar(1) NOT NULL,
  `reg_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_img` varchar(1000) DEFAULT NULL,
  `about` longtext NOT NULL,
  `specialization_id` int(3) DEFAULT NULL,
  `hospital` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `doctor_mst`
--

INSERT INTO `doctor_mst` (`id`, `register_id`, `fname`, `lname`, `date_of_birth`, `age`, `height`, `weight`, `gender`, `martial_status`, `reg_at`, `profile_img`, `about`, `specialization_id`, `hospital`) VALUES
(1, 6, 'Keith', 'Williams', '24/08/1990', 35, 172, 70, 'M', 'S', '2022-05-14 04:50:54', 'profile6.jpg', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 10, 'Allied Hospital Faisalabad');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_services`
--

DROP TABLE IF EXISTS `doctor_services`;
CREATE TABLE IF NOT EXISTS `doctor_services` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(11) NOT NULL,
  `service` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `doctor_services`
--

INSERT INTO `doctor_services` (`id`, `register_id`, `service`) VALUES
(1, 6, 'Lorem Ipsum'),
(2, 6, 'Lorem Ipsum 1'),
(3, 6, 'Lorem Ipsu2'),
(4, 6, 'Lorem Ipsum 3');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_specialities`
--

DROP TABLE IF EXISTS `doctor_specialities`;
CREATE TABLE IF NOT EXISTS `doctor_specialities` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(3) NOT NULL,
  `speciality` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `doctor_specialities`
--

INSERT INTO `doctor_specialities` (`id`, `register_id`, `speciality`) VALUES
(1, 6, 'Bonorum et Malorum'),
(2, 6, 'Bonorum et Malorum 2'),
(3, 6, 'Bonorum et Malorum 3'),
(4, 6, 'Bonorum et Malorum 4');

-- --------------------------------------------------------

--
-- Table structure for table `doc_dtl_awards`
--

DROP TABLE IF EXISTS `doc_dtl_awards`;
CREATE TABLE IF NOT EXISTS `doc_dtl_awards` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(11) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `award_date` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `doc_dtl_awards`
--

INSERT INTO `doc_dtl_awards` (`id`, `register_id`, `title`, `description`, `award_date`) VALUES
(1, 6, 'Finibus Bonorum et Malorum', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.', '05/19/2022'),
(2, 6, 'Bonorum et Malorum', 'Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.', '05/02/2022');

-- --------------------------------------------------------

--
-- Table structure for table `durations`
--

DROP TABLE IF EXISTS `durations`;
CREATE TABLE IF NOT EXISTS `durations` (
  `d_id` bigint(3) NOT NULL AUTO_INCREMENT,
  `d_desc` varchar(1000) NOT NULL,
  PRIMARY KEY (`d_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `durations`
--

INSERT INTO `durations` (`d_id`, `d_desc`) VALUES
(1, '10 Minutes'),
(2, '15 Minutes'),
(3, '20 Minutes'),
(4, '25 Minutes');

-- --------------------------------------------------------

--
-- Table structure for table `hospital_dtl_images`
--

DROP TABLE IF EXISTS `hospital_dtl_images`;
CREATE TABLE IF NOT EXISTS `hospital_dtl_images` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(11) NOT NULL,
  `img` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hospital_dtl_images`
--

INSERT INTO `hospital_dtl_images` (`id`, `register_id`, `img`) VALUES
(1, 3, 'hospital_3_1.jpg'),
(2, 3, 'hospital_3_2.jpg'),
(3, 3, 'hospital_3_3.jpg'),
(4, 3, 'hospital_3_4.jpg'),
(5, 3, 'hospital_3_5.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `hospital_dtl_services`
--

DROP TABLE IF EXISTS `hospital_dtl_services`;
CREATE TABLE IF NOT EXISTS `hospital_dtl_services` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(11) NOT NULL,
  `service_id` bigint(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hospital_dtl_services`
--

INSERT INTO `hospital_dtl_services` (`id`, `register_id`, `service_id`) VALUES
(1, 3, 1),
(2, 3, 2),
(3, 3, 4),
(4, 3, 7),
(5, 3, 12);

-- --------------------------------------------------------

--
-- Table structure for table `hospital_mst`
--

DROP TABLE IF EXISTS `hospital_mst`;
CREATE TABLE IF NOT EXISTS `hospital_mst` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `address` varchar(500) NOT NULL,
  `hours_of_operations` bigint(3) DEFAULT NULL,
  `profile_img` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hospital_mst`
--

INSERT INTO `hospital_mst` (`id`, `register_id`, `name`, `address`, `hours_of_operations`, `profile_img`) VALUES
(1, 3, 'Abc Hospital Name', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 5, 'hospital3_1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `nurse_dtl_certifications`
--

DROP TABLE IF EXISTS `nurse_dtl_certifications`;
CREATE TABLE IF NOT EXISTS `nurse_dtl_certifications` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(3) NOT NULL,
  `title` varchar(250) NOT NULL,
  `award_date` varchar(20) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nurse_dtl_certifications`
--

INSERT INTO `nurse_dtl_certifications` (`id`, `register_id`, `title`, `award_date`, `description`) VALUES
(1, 2, 'Nurse Of Year', '05/20/2022', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.');

-- --------------------------------------------------------

--
-- Table structure for table `nurse_dtl_edu`
--

DROP TABLE IF EXISTS `nurse_dtl_edu`;
CREATE TABLE IF NOT EXISTS `nurse_dtl_edu` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(3) NOT NULL,
  `degree` varchar(250) NOT NULL,
  `start_year` varchar(250) NOT NULL,
  `ending_year` varchar(250) NOT NULL,
  `institute` varchar(250) NOT NULL,
  `degree_img` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nurse_dtl_edu`
--

INSERT INTO `nurse_dtl_edu` (`id`, `register_id`, `degree`, `start_year`, `ending_year`, `institute`, `degree_img`) VALUES
(1, 2, 'Masters Of Medicine', '05/01/2022', '07/28/2022', 'Xyz University', 'nurse_profile_2_1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `nurse_dtl_experiances`
--

DROP TABLE IF EXISTS `nurse_dtl_experiances`;
CREATE TABLE IF NOT EXISTS `nurse_dtl_experiances` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(3) NOT NULL,
  `job_title` varchar(250) NOT NULL,
  `institute` varchar(250) NOT NULL,
  `description` varchar(500) NOT NULL,
  `experiance` bigint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nurse_dtl_experiances`
--

INSERT INTO `nurse_dtl_experiances` (`id`, `register_id`, `job_title`, `institute`, `description`, `experiance`) VALUES
(1, 2, 'Nursing', 'Xyz Hospital', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 2);

-- --------------------------------------------------------

--
-- Table structure for table `nurse_dtl_specializations`
--

DROP TABLE IF EXISTS `nurse_dtl_specializations`;
CREATE TABLE IF NOT EXISTS `nurse_dtl_specializations` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `specialization_in` varchar(500) NOT NULL,
  `register_id` bigint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nurse_dtl_specializations`
--

INSERT INTO `nurse_dtl_specializations` (`id`, `specialization_in`, `register_id`) VALUES
(1, 'Baby Care', 2),
(2, 'Medical Emergency', 2);

-- --------------------------------------------------------

--
-- Table structure for table `nurse_mst`
--

DROP TABLE IF EXISTS `nurse_mst`;
CREATE TABLE IF NOT EXISTS `nurse_mst` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(11) NOT NULL,
  `f_name` varchar(250) NOT NULL,
  `l_name` varchar(250) NOT NULL,
  `dt_of_birth` varchar(250) NOT NULL,
  `age` bigint(3) NOT NULL,
  `height` bigint(3) NOT NULL,
  `weight` bigint(3) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `martial_status` varchar(1) DEFAULT NULL,
  `address` varchar(500) NOT NULL,
  `pf_img` varchar(1000) DEFAULT NULL,
  `ent_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `license_img` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nurse_mst`
--

INSERT INTO `nurse_mst` (`id`, `register_id`, `f_name`, `l_name`, `dt_of_birth`, `age`, `height`, `weight`, `gender`, `martial_status`, `address`, `pf_img`, `ent_date`, `license_img`) VALUES
(1, 2, 'Kate', 'Winslet', '24/08/1995', 25, 175, 85, 'F', 'S', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'nurse_profile_2.jpg', '2022-05-09 12:43:03', 'nurse_license_2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `patient_dtl`
--

DROP TABLE IF EXISTS `patient_dtl`;
CREATE TABLE IF NOT EXISTS `patient_dtl` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(11) NOT NULL,
  `doc_type` varchar(2) NOT NULL,
  `path` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `patient_dtl`
--

INSERT INTO `patient_dtl` (`id`, `patient_id`, `doc_type`, `path`) VALUES
(9, 9, 'IC', 'identitycard_90.jpg'),
(8, 9, 'PP', 'passport_90.jpg'),
(7, 9, 'DP', 'driverpermit_90.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `patient_mst`
--

DROP TABLE IF EXISTS `patient_mst`;
CREATE TABLE IF NOT EXISTS `patient_mst` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(11) NOT NULL,
  `fname` varchar(250) NOT NULL,
  `lname` varchar(250) NOT NULL,
  `date_of_birth` varchar(20) NOT NULL,
  `age` int(3) NOT NULL DEFAULT '0',
  `height` int(3) NOT NULL DEFAULT '0',
  `weight` int(3) NOT NULL DEFAULT '0',
  `gender` varchar(1) NOT NULL,
  `martial_status` varchar(1) NOT NULL,
  `eg_contact` varchar(250) NOT NULL,
  `eg_relation` varchar(250) NOT NULL,
  `eg_phone` varchar(20) NOT NULL,
  `eg_address` varchar(2000) NOT NULL,
  `reg_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_img` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `patient_mst`
--

INSERT INTO `patient_mst` (`id`, `register_id`, `fname`, `lname`, `date_of_birth`, `age`, `height`, `weight`, `gender`, `martial_status`, `eg_contact`, `eg_relation`, `eg_phone`, `eg_address`, `reg_at`, `profile_img`) VALUES
(2, 1, 'Muhammad', 'Qasim', '24/08/1995', 25, 175, 80, 'M', 'S', 'Ihsan', 'Father', '03217601315', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '2022-05-16 04:46:34', 'profile1.jpg'),
(6, 9, 'Muhammad', 'Qasim', '24/08/1995', 25, 175, 85, 'M', 'S', 'Ihsan', 'Father', '03217601315', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '2022-05-16 06:40:30', 'profile9.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pf_dtl_types`
--

DROP TABLE IF EXISTS `pf_dtl_types`;
CREATE TABLE IF NOT EXISTS `pf_dtl_types` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(11) NOT NULL,
  `type_id` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pf_dtl_types`
--

INSERT INTO `pf_dtl_types` (`id`, `register_id`, `type_id`) VALUES
(1, 1, 4),
(2, 2, 3),
(3, 3, 4),
(4, 4, 5),
(8, 1, 1),
(6, 6, 2),
(15, 9, 1),
(16, 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pf_mst`
--

DROP TABLE IF EXISTS `pf_mst`;
CREATE TABLE IF NOT EXISTS `pf_mst` (
  `pf_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) NOT NULL,
  `active` varchar(1) DEFAULT 'Y',
  `ent_dt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `login_password` varchar(20) NOT NULL,
  PRIMARY KEY (`pf_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pf_mst`
--

INSERT INTO `pf_mst` (`pf_id`, `email`, `active`, `ent_dt`, `login_password`) VALUES
(1, 'hospital@gmail.com', 'Y', '2022-05-09 12:36:45', 'hospital123'),
(2, 'nurse@gmail.com', 'Y', '2022-05-09 12:41:42', 'nurse123'),
(3, 'hospital123@gmail.com', 'Y', '2022-05-09 12:46:11', 'hospital123'),
(4, 'pharmacy@gmail.com', 'Y', '2022-05-09 12:48:50', 'pharmacy123'),
(9, 'qasim@gmail.com', 'Y', '2022-05-16 06:32:44', 'qasim123'),
(6, 'doctor@gmail.com', 'Y', '2022-05-14 04:49:13', 'doctor123'),
(10, 'patient@gmail.com', 'Y', '2022-05-21 05:06:52', 'patient123');

-- --------------------------------------------------------

--
-- Table structure for table `pf_types`
--

DROP TABLE IF EXISTS `pf_types`;
CREATE TABLE IF NOT EXISTS `pf_types` (
  `type_id` int(2) NOT NULL,
  `type_desc` varchar(250) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pf_types`
--

INSERT INTO `pf_types` (`type_id`, `type_desc`) VALUES
(1, 'PATIENT'),
(2, 'DOCTOR'),
(3, 'NURSE'),
(4, 'HOSPITAL'),
(5, 'PHARMACY');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_dtl_images`
--

DROP TABLE IF EXISTS `pharmacy_dtl_images`;
CREATE TABLE IF NOT EXISTS `pharmacy_dtl_images` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(11) NOT NULL,
  `img` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pharmacy_dtl_images`
--

INSERT INTO `pharmacy_dtl_images` (`id`, `register_id`, `img`) VALUES
(1, 4, 'hospital_4_1.jpg'),
(2, 4, 'hospital_4_2.jpg'),
(3, 4, 'hospital_4_3.jpg'),
(4, 4, 'hospital_4_4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_dtl_services`
--

DROP TABLE IF EXISTS `pharmacy_dtl_services`;
CREATE TABLE IF NOT EXISTS `pharmacy_dtl_services` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(11) NOT NULL,
  `service_id` bigint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pharmacy_dtl_services`
--

INSERT INTO `pharmacy_dtl_services` (`id`, `register_id`, `service_id`) VALUES
(1, 4, 2),
(2, 4, 4),
(3, 4, 12);

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_mst`
--

DROP TABLE IF EXISTS `pharmacy_mst`;
CREATE TABLE IF NOT EXISTS `pharmacy_mst` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `register_id` bigint(3) NOT NULL,
  `name` varchar(250) NOT NULL,
  `address` varchar(500) NOT NULL,
  `hours_of_operations` bigint(3) NOT NULL,
  `profile_img` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pharmacy_mst`
--

INSERT INTO `pharmacy_mst` (`id`, `register_id`, `name`, `address`, `hours_of_operations`, `profile_img`) VALUES
(1, 4, 'Xyz Pharmacy Name', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 10, 'hospital4_1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `prices_temp`
--

DROP TABLE IF EXISTS `prices_temp`;
CREATE TABLE IF NOT EXISTS `prices_temp` (
  `register_id` bigint(11) DEFAULT NULL,
  `d_id` bigint(3) NOT NULL,
  `price` bigint(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prices_temp`
--

INSERT INTO `prices_temp` (`register_id`, `d_id`, `price`) VALUES
(6, 1, 25),
(6, 2, 50),
(6, 3, 65),
(6, 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `specialities`
--

DROP TABLE IF EXISTS `specialities`;
CREATE TABLE IF NOT EXISTS `specialities` (
  `id` bigint(3) NOT NULL AUTO_INCREMENT,
  `description` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `specialities`
--

INSERT INTO `specialities` (`id`, `description`) VALUES
(1, 'Allergy and Immunology'),
(2, 'Anesthesiology'),
(3, 'Dermatology'),
(4, 'Diagnostic radiology'),
(5, 'Emergency medicine'),
(6, 'Family medicine'),
(7, 'Internal medicine'),
(8, 'Medical genetics'),
(9, 'Neurology'),
(10, 'Nuclear medicine'),
(11, 'Obstetrics and gynecology'),
(12, 'Ophthalmology'),
(13, 'Pathology'),
(14, 'Pediatrics'),
(15, 'Physical medicine and Rehabilitation'),
(16, 'Preventive medicine'),
(17, 'Psychiatry'),
(18, 'Radiation oncology'),
(19, 'Surgery'),
(20, 'Urology');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
