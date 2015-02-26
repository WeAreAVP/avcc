-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 26, 2015 at 04:47 AM
-- Server version: 5.1.69-log
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `avccqa`
--

-- --------------------------------------------------------

--
-- Table structure for table `acid_detection_strips`
--

CREATE TABLE IF NOT EXISTS `acid_detection_strips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_DAB1064232C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=39 ;

--
-- Dumping data for table `acid_detection_strips`
--

INSERT INTO `acid_detection_strips` (`id`, `organization_id`, `name`, `score`) VALUES
(16, NULL, '0.0', 0),
(17, NULL, '0.25', 0),
(28, NULL, '0.5', 0),
(29, NULL, '0.75', 0),
(30, NULL, '1.0', 0),
(31, NULL, '1.25', 0),
(32, NULL, '1.5', 0),
(33, NULL, '1.75', 0),
(34, NULL, '2.0', 0),
(35, NULL, '2.25', 0),
(36, NULL, '2.5', 0),
(37, NULL, '2.75', 0),
(38, NULL, '3.0', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bases`
--

CREATE TABLE IF NOT EXISTS `bases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `format_id` int(11) DEFAULT NULL,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_217B2A3BD629F605` (`format_id`),
  KEY `IDX_217B2A3B32C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=61 ;

--
-- Dumping data for table `bases`
--

INSERT INTO `bases` (`id`, `format_id`, `organization_id`, `name`, `score`) VALUES
(1, 1, NULL, 'Acetate', 4),
(2, 1, NULL, 'Polyester', 5),
(3, 1, NULL, 'Paper', 2),
(4, 1, NULL, 'PVC', -4),
(5, 19, NULL, 'Glass', 2),
(6, 19, NULL, 'Aluminum', 0),
(7, 18, NULL, 'Vinyl', 0),
(8, 25, NULL, 'Wax', 0),
(9, 2, NULL, 'Acetate', 4),
(10, 2, NULL, 'Polyester', 5),
(11, 2, NULL, 'Paper', 2),
(15, 16, NULL, 'Glass', 2),
(16, 17, NULL, 'Glass', 2),
(17, 18, NULL, 'Glass', 2),
(18, 28, NULL, 'Glass', 2),
(19, 20, NULL, 'Glass', 2),
(20, 16, NULL, 'Aluminum', 0),
(21, 17, NULL, 'Aluminum', 0),
(22, 18, NULL, 'Aluminum', 0),
(23, 28, NULL, 'Aluminum', 0),
(24, 20, NULL, 'Aluminum', 0),
(25, 16, NULL, 'Vinyl', 0),
(26, 17, NULL, 'Vinyl', 0),
(27, 19, NULL, 'Vinyl', 0),
(28, 28, NULL, 'Vinyl', 0),
(29, 20, NULL, 'Vinyl', 0),
(30, 66, NULL, 'Acetate', 4),
(32, 66, NULL, 'Polyester', 5),
(33, 4, NULL, 'PVC', -4),
(34, 2, NULL, 'PVC', -4),
(35, 3, NULL, 'PVC', -4),
(36, 5, NULL, 'PVC', -4),
(39, 69, NULL, 'Nitrate', 0),
(43, 68, NULL, 'Acetate', 0),
(44, 72, NULL, 'Acetate', 0),
(45, 69, NULL, 'Acetate', 0),
(46, 70, NULL, 'Acetate', 0),
(47, 71, NULL, 'Acetate', 0),
(48, 67, NULL, 'Acetate', 0),
(49, 68, NULL, 'Polyester', 0),
(50, 72, NULL, 'Polyester', 0),
(51, 69, NULL, 'Polyester', 0),
(52, 70, NULL, 'Polyester', 0),
(53, 71, NULL, 'Polyester', 0),
(54, 67, NULL, 'Polyester', 0),
(55, 16, NULL, 'Shellac', 0),
(56, 17, NULL, 'Shellac', 0),
(57, 19, NULL, 'Shellac', 0),
(58, 18, NULL, 'Shellac', 0),
(59, 28, NULL, 'Shellac', 0),
(60, 20, NULL, 'Shellac', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cassette_sizes`
--

CREATE TABLE IF NOT EXISTS `cassette_sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_2007DC8B32C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cassette_sizes`
--

INSERT INTO `cassette_sizes` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, 'Small', 25),
(2, NULL, 'Large', 30);

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE IF NOT EXISTS `colors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_C2BEC39F32C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, 'Color', 1),
(2, NULL, 'Black&White', 2);

-- --------------------------------------------------------

--
-- Table structure for table `commercial_unique`
--

CREATE TABLE IF NOT EXISTS `commercial_unique` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_FCE4382B32C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `commercial_unique`
--

INSERT INTO `commercial_unique` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, 'Commercial', 0),
(2, NULL, 'Unique', 0);

-- --------------------------------------------------------

--
-- Table structure for table `disk_diameters`
--

CREATE TABLE IF NOT EXISTS `disk_diameters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_DD84FBDC32C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `disk_diameters`
--

INSERT INTO `disk_diameters` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, '7 Inch', 0),
(2, NULL, '10 Inch', 0),
(3, NULL, '12 Inch', 0),
(4, NULL, '16 Inch', 0);

-- --------------------------------------------------------

--
-- Table structure for table `formats`
--

CREATE TABLE IF NOT EXISTS `formats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media_format_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  `width` double DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_DBCBA3CF349458B` (`media_format_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=73 ;

--
-- Dumping data for table `formats`
--

INSERT INTO `formats` (`id`, `media_format_id`, `name`, `score`, `width`) VALUES
(1, 1, '1/4 Inch Open Reel Audio', 54, 0.75),
(2, 1, '1/2 Inch Open Reel Audio', 54, 1.25),
(3, 1, '1/2 Inch Open Reel Audio - Digital', 0, 1.25),
(4, 1, '1 Inch Open Reel Audio', 54, 1.75),
(5, 1, '2 Inch Open Reel Audio', 54, 2.75),
(6, 1, '8-Track', 0, 0.875),
(7, 1, 'Cartridge', 0, 0.875),
(8, 1, 'CD - Burnable', 35, 0.4375),
(9, 1, 'CD - Pressed', 25, 0.4375),
(10, 1, 'Compact Audiocassette', 0, 0.625),
(11, 1, 'DTRS', 80, 0.625),
(12, 1, 'DAT', 0, 0.625),
(13, 1, 'Microcassette', 0, 0.4375),
(14, 1, 'Mini-cassette', 0, 0.4375),
(15, 1, 'MiniDisc', 0, 0.375),
(16, 1, '45 RPM Disc', 0, 0.08),
(17, 1, '78 RPM Disc', 0, 0.08),
(18, 1, 'LP', 0, 0.08),
(19, 1, 'Lacquer Transcription Disc', 82, 0.08),
(20, 1, 'Other Transcription Disc', 0, 0.08),
(21, 1, '1610/1630 (U-matic)', 0, 1.5),
(22, 1, 'ADAT (VHS)', 0, 1.125),
(23, 1, 'PCM-F1 (Betamax)', 0, 1.125),
(24, 1, 'Wire Recording', 0, 0.625),
(25, 1, 'Cylinder', 0, 2.5),
(26, 1, 'Dictabelt', 0, 0.08),
(27, 1, 'Other Tape Format', 0, 0.875),
(28, 1, 'Other Disc Format', 0, 0.08),
(29, 3, '1/4 Inch Open Reel Video', 0, 0.75),
(30, 3, '1/2 Inch Open Reel Video', 0, 1.25),
(31, 3, '1 Inch Open Reel Video', 0, 1.75),
(32, 3, '2 Inch Open Reel Video', 0, 3),
(33, 3, 'Betacam', 36, 1.375),
(34, 3, 'BetacamSP', 34, 1.375),
(35, 3, 'BetacamSX', 34, 1.375),
(36, 3, 'Betamax', 0, 1.125),
(37, 3, 'Blu-Ray', 25, 0.4375),
(38, 3, 'CD-R', 0, 0.4375),
(39, 3, 'VCD', 50, 0.4375),
(40, 3, 'DVD - Pressed', 25, 0.4375),
(41, 3, 'DVD - Burned', 0, 0.4375),
(42, 3, 'D1', 0, 1.5),
(43, 3, 'D2', 0, 1.5),
(44, 3, 'D3', 0, 1.34),
(45, 3, 'D4', 0, 1.34),
(46, 3, 'D5', 0, 1.34),
(47, 3, 'DigiBeta', 0, 1.25),
(48, 3, 'MPEG IMX', 0, 1.25),
(49, 3, '8mm', 76, 0.75),
(50, 3, 'Hi-8', 74, 0.75),
(51, 3, 'Digital 8', 77, 0.75),
(52, 3, 'DV', 0, 0.675),
(53, 3, 'DVCam', 0, 0.75),
(54, 3, 'DVCPro', 0, 0.75),
(55, 3, 'DVCPro HD', 0, 0.75),
(56, 3, 'MiniDV', 0, 0.675),
(57, 3, 'HDCAM', 0, 1.3),
(58, 3, 'Laser Disc', 0, 0.4375),
(59, 3, 'U-matic', 0, 1.5),
(60, 3, 'U-maticSP', 82, 1.5),
(61, 3, 'VHS', 67, 1.125),
(62, 3, 'VHS-C', 67, 1),
(63, 3, 'S-VHS', 65, 1.125),
(64, 3, 'XDCAM', 0, 0.5),
(65, 3, 'XDCAM HD', 0, 0.5),
(66, 2, '8mm', 76, 0),
(67, 2, 'Super8', 0, 0),
(68, 2, '16mm', 0, 0),
(69, 2, '35mm', 0, 0),
(70, 2, '70mm', 0, 0),
(71, 2, '9.5mm', 0, 0),
(72, 2, '17mm', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `format_versions`
--

CREATE TABLE IF NOT EXISTS `format_versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `format_id` int(11) DEFAULT NULL,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_BEE67006D629F605` (`format_id`),
  KEY `IDX_BEE6700632C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `format_versions`
--

INSERT INTO `format_versions` (`id`, `format_id`, `organization_id`, `name`, `score`) VALUES
(1, 32, NULL, 'High Band', 0),
(2, 59, NULL, 'High Band', 0),
(3, 60, NULL, 'High Band', 0),
(4, 32, NULL, 'Low Band', 0),
(5, 59, NULL, 'Low Band', 0),
(6, 60, NULL, 'Low Band', 0),
(7, 31, NULL, 'Type A', 0),
(8, 31, NULL, 'Type B', 0),
(9, 31, NULL, 'Type C', 0),
(10, 30, NULL, 'EIAJ', 0),
(11, 30, NULL, 'CV', 0);

-- --------------------------------------------------------

--
-- Table structure for table `frame_rates`
--

CREATE TABLE IF NOT EXISTS `frame_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_870C1DFE32C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `frame_rates`
--

INSERT INTO `frame_rates` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, '18fps', 0),
(2, NULL, '24fps', 0),
(3, NULL, 'variable fps', 0);

-- --------------------------------------------------------

--
-- Table structure for table `media_diameters`
--

CREATE TABLE IF NOT EXISTS `media_diameters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_BB7F6A0232C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `media_diameters`
--

INSERT INTO `media_diameters` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, '10%', 0),
(2, NULL, '25%', 0),
(3, NULL, '33%', 0),
(4, NULL, '50%', 0),
(5, NULL, '66%', 0),
(6, NULL, '75%', 0),
(7, NULL, '90%', 0),
(8, NULL, '100%', 0);

-- --------------------------------------------------------

--
-- Table structure for table `media_types`
--

CREATE TABLE IF NOT EXISTS `media_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `media_types`
--

INSERT INTO `media_types` (`id`, `name`, `score`) VALUES
(1, 'Audio', 0),
(2, 'Film', 0),
(3, 'Video', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mono_stereo`
--

CREATE TABLE IF NOT EXISTS `mono_stereo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_8732251532C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `mono_stereo`
--

INSERT INTO `mono_stereo` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, 'Mono', 0),
(2, NULL, 'Stereo', 0);

-- --------------------------------------------------------

--
-- Table structure for table `noice_reduction`
--

CREATE TABLE IF NOT EXISTS `noice_reduction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_4A0FAC9932C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `noice_reduction`
--

INSERT INTO `noice_reduction` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, 'None', 0),
(2, NULL, 'Dolby A', 5),
(3, NULL, 'Dolby B', 5),
(4, NULL, 'Dolby C', 5),
(5, NULL, 'Dolby SR', 5),
(6, NULL, 'Dolby S', 5),
(7, NULL, 'Dolby HX', 5);

-- --------------------------------------------------------

--
-- Table structure for table `print_types`
--

CREATE TABLE IF NOT EXISTS `print_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_285503FB32C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `print_types`
--

INSERT INTO `print_types` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, 'Positive', 0),
(2, NULL, 'Negative', 0),
(3, NULL, 'Full Coat Mag', 0),
(4, NULL, 'Unknown', 0);

-- --------------------------------------------------------

--
-- Table structure for table `recording_speed`
--

CREATE TABLE IF NOT EXISTS `recording_speed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `format_id` int(11) DEFAULT NULL,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_A9A6C79BD629F605` (`format_id`),
  KEY `IDX_A9A6C79B32C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=52 ;

--
-- Dumping data for table `recording_speed`
--

INSERT INTO `recording_speed` (`id`, `format_id`, `organization_id`, `name`, `score`) VALUES
(1, 1, NULL, '15/16ips', 0),
(2, 1, NULL, '1 7/8ips', 0),
(3, 1, NULL, '3 3/4ips', 0),
(4, 1, NULL, '7 1/2ips', 0),
(5, 1, NULL, '15ips', 0),
(6, 1, NULL, '30ips', 0),
(7, 1, NULL, 'Variable ips', 0),
(8, 1, NULL, '33rpm', 0),
(9, 1, NULL, '45rpm', 0),
(10, 1, NULL, '78rpm', 0),
(11, 1, NULL, '16rpm', 0),
(12, 1, NULL, 'Variable rpm', 0),
(13, NULL, NULL, 'LP', 0),
(14, NULL, NULL, 'EP', 0),
(15, NULL, NULL, 'SLP', 0),
(16, NULL, NULL, 'SP', 0),
(17, 4, NULL, '15/16ips', 0),
(18, 2, NULL, '15/16ips', 0),
(19, 3, NULL, '15/16ips', 0),
(20, 5, NULL, '15/16ips', 0),
(21, 24, NULL, '15/16ips', 0),
(22, 4, NULL, '1 7/8ips', 0),
(23, 2, NULL, '1 7/8ips', 0),
(24, 3, NULL, '1 7/8ips', 0),
(25, 5, NULL, '1 7/8ips', 0),
(26, 24, NULL, '1 7/8ips', 0),
(27, 4, NULL, '3 3/4ips', 0),
(28, 2, NULL, '3 3/4ips', 0),
(29, 3, NULL, '3 3/4ips', 0),
(30, 5, NULL, '3 3/4ips', 0),
(31, 24, NULL, '3 3/4ips', 0),
(32, 4, NULL, '7 1/2ips', 0),
(33, 2, NULL, '7 1/2ips', 0),
(34, 3, NULL, '7 1/2ips', 0),
(35, 5, NULL, '7 1/2ips', 0),
(36, 24, NULL, '7 1/2ips', 0),
(37, 4, NULL, '15ips', 0),
(38, 2, NULL, '15ips', 0),
(39, 3, NULL, '15ips', 0),
(40, 5, NULL, '15ips', 0),
(41, 24, NULL, '15ips', 0),
(42, 4, NULL, '30ips', 0),
(43, 2, NULL, '30ips', 0),
(44, 3, NULL, '30ips', 0),
(45, 5, NULL, '30ips', 0),
(46, 24, NULL, '30ips', 0),
(47, 4, NULL, 'Variable ips', 0),
(48, 2, NULL, 'Variable ips', 0),
(49, 3, NULL, 'Variable ips', 0),
(50, 5, NULL, 'Variable ips', 0),
(51, 24, NULL, 'Variable ips', 0);

-- --------------------------------------------------------

--
-- Table structure for table `recording_standards`
--

CREATE TABLE IF NOT EXISTS `recording_standards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_8E02410832C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `recording_standards`
--

INSERT INTO `recording_standards` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, 'NTSC', 0),
(2, NULL, 'PAL', 0),
(3, NULL, 'SECAM', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reel_core`
--

CREATE TABLE IF NOT EXISTS `reel_core` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_F665FF0832C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `reel_core`
--

INSERT INTO `reel_core` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, 'Reel', 0),
(2, NULL, 'Core', 0),
(3, NULL, 'Neither', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reel_diameters`
--

CREATE TABLE IF NOT EXISTS `reel_diameters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `format_id` int(11) DEFAULT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_1350686232C8A3DE` (`organization_id`),
  KEY `IDX_13506862D629F605` (`format_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=74 ;

--
-- Dumping data for table `reel_diameters`
--

INSERT INTO `reel_diameters` (`id`, `organization_id`, `name`, `format_id`, `score`) VALUES
(1, NULL, '3 Inch', 1, 0),
(2, NULL, '4 Inch', 1, 0),
(3, NULL, '5 Inch', 1, 0),
(4, NULL, '7 Inch', 1, 0),
(5, NULL, '10.5 Inch', 1, 0),
(6, NULL, '10.5 Inch NAB', 1, 0),
(7, NULL, 'Spot Reel', 29, 0),
(8, NULL, '10 Inch', 29, 0),
(9, NULL, '12 Inch', 29, 0),
(10, NULL, '14 Inch', 29, 0),
(11, NULL, '9 Inch', NULL, 0),
(12, NULL, '11 Inch', NULL, 0),
(13, NULL, '15 Inch', NULL, 0),
(14, NULL, '25 Inch', NULL, 0),
(15, NULL, '26 Inch', NULL, 0),
(16, NULL, '3 Inch', 4, 0),
(17, NULL, '3 Inch', 2, 0),
(18, NULL, '3 Inch', 3, 0),
(19, NULL, '3 Inch', 5, 0),
(20, NULL, '3 Inch', 24, 0),
(21, NULL, '4 Inch', 4, 0),
(22, NULL, '4 Inch', 2, 0),
(23, NULL, '4 Inch', 3, 0),
(24, NULL, '4 Inch', 5, 0),
(25, NULL, '4 Inch', 24, 0),
(26, NULL, '5 Inch', 4, 0),
(27, NULL, '5 Inch', 2, 0),
(28, NULL, '5 Inch', 3, 0),
(29, NULL, '5 Inch', 5, 0),
(30, NULL, '5 Inch', 24, 0),
(31, NULL, '7 Inch', 4, 0),
(32, NULL, '7 Inch', 2, 0),
(33, NULL, '7 Inch', 3, 0),
(34, NULL, '7 Inch', 5, 0),
(35, NULL, '7 Inch', 24, 0),
(36, NULL, '10.5 Inch', 4, 0),
(37, NULL, '10.5 Inch', 2, 0),
(38, NULL, '10.5 Inch', 3, 0),
(39, NULL, '10.5 Inch', 5, 0),
(40, NULL, '10.5 Inch', 24, 0),
(41, NULL, '10.5 Inch NAB', 4, 0),
(42, NULL, '10.5 Inch NAB', 2, 0),
(43, NULL, '10.5 Inch NAB', 3, 0),
(44, NULL, '10.5 Inch NAB', 5, 0),
(45, NULL, '10.5 Inch NAB', 24, 0),
(46, NULL, '3 Inch', 31, 0),
(47, NULL, '3 Inch', 30, 0),
(48, NULL, '3 Inch', 29, 0),
(49, NULL, '3 Inch', 32, 0),
(50, NULL, '5 Inch', 31, 0),
(51, NULL, '5 Inch', 30, 0),
(52, NULL, '5 Inch', 29, 0),
(53, NULL, '5 Inch', 32, 0),
(54, NULL, '7 Inch', 31, 0),
(55, NULL, '7 Inch', 30, 0),
(56, NULL, '7 Inch', 29, 0),
(57, NULL, '7 Inch', 32, 0),
(58, NULL, 'Spot Reel', 31, 0),
(59, NULL, 'Spot Reel', 30, 0),
(60, NULL, 'Spot Reel', 32, 0),
(61, NULL, '10 Inch', 31, 0),
(62, NULL, '10 Inch', 30, 0),
(63, NULL, '10 Inch', 32, 0),
(64, NULL, '12 Inch', 31, 0),
(65, NULL, '12 Inch', 30, 0),
(66, NULL, '12 Inch', 32, 0),
(67, NULL, '14 Inch', 31, 0),
(68, NULL, '14 Inch', 30, 0),
(69, NULL, '14 Inch', 32, 0),
(70, NULL, '3 Inch', NULL, 0),
(71, NULL, '4 Inch', NULL, 0),
(72, NULL, '5 Inch', NULL, 0),
(73, NULL, '7 Inch', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE IF NOT EXISTS `slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_B8C0209132C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `slides`
--

INSERT INTO `slides` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, 'One-sided', 0),
(2, NULL, 'Two-sided', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sounds`
--

CREATE TABLE IF NOT EXISTS `sounds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_F12306F132C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `sounds`
--

INSERT INTO `sounds` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, 'Silent', 0),
(2, NULL, 'Magnetic', 0),
(3, NULL, 'Optical', 0),
(4, NULL, 'Variable Area Optical', 0),
(5, NULL, 'Variable Density Optical', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tape_thickness`
--

CREATE TABLE IF NOT EXISTS `tape_thickness` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_34DDB80832C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tape_thickness`
--

INSERT INTO `tape_thickness` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, '0.5mil', 4.5),
(2, NULL, '1.0mil', 2),
(3, NULL, '1.5mil', 0),
(4, NULL, '2.0mil', 0);

-- --------------------------------------------------------

--
-- Table structure for table `track_types`
--

CREATE TABLE IF NOT EXISTS `track_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `score` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_29C72B9732C8A3DE` (`organization_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `track_types`
--

INSERT INTO `track_types` (`id`, `organization_id`, `name`, `score`) VALUES
(1, NULL, 'Full Track', 0),
(2, NULL, 'Half Track', 0),
(3, NULL, 'Quarter Track', 0),
(4, NULL, '8-Track', 0),
(5, NULL, '16-Track', 0),
(6, NULL, '24-Track', 0),
(7, NULL, 'Other Multi-track', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `acid_detection_strips`
--
ALTER TABLE `acid_detection_strips`
  ADD CONSTRAINT `FK_DAB1064232C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bases`
--
ALTER TABLE `bases`
  ADD CONSTRAINT `FK_217B2A3B32C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_217B2A3BD629F605` FOREIGN KEY (`format_id`) REFERENCES `formats` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cassette_sizes`
--
ALTER TABLE `cassette_sizes`
  ADD CONSTRAINT `FK_2007DC8B32C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `colors`
--
ALTER TABLE `colors`
  ADD CONSTRAINT `FK_C2BEC39F32C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `commercial_unique`
--
ALTER TABLE `commercial_unique`
  ADD CONSTRAINT `FK_FCE4382B32C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `disk_diameters`
--
ALTER TABLE `disk_diameters`
  ADD CONSTRAINT `FK_DD84FBDC32C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `formats`
--
ALTER TABLE `formats`
  ADD CONSTRAINT `FK_DBCBA3CF349458B` FOREIGN KEY (`media_format_id`) REFERENCES `media_types` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `format_versions`
--
ALTER TABLE `format_versions`
  ADD CONSTRAINT `FK_BEE6700632C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_BEE67006D629F605` FOREIGN KEY (`format_id`) REFERENCES `formats` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `frame_rates`
--
ALTER TABLE `frame_rates`
  ADD CONSTRAINT `FK_870C1DFE32C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `media_diameters`
--
ALTER TABLE `media_diameters`
  ADD CONSTRAINT `FK_BB7F6A0232C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `mono_stereo`
--
ALTER TABLE `mono_stereo`
  ADD CONSTRAINT `FK_8732251532C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `noice_reduction`
--
ALTER TABLE `noice_reduction`
  ADD CONSTRAINT `FK_4A0FAC9932C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `print_types`
--
ALTER TABLE `print_types`
  ADD CONSTRAINT `FK_285503FB32C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `recording_speed`
--
ALTER TABLE `recording_speed`
  ADD CONSTRAINT `FK_A9A6C79B32C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_A9A6C79BD629F605` FOREIGN KEY (`format_id`) REFERENCES `formats` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `recording_standards`
--
ALTER TABLE `recording_standards`
  ADD CONSTRAINT `FK_8E02410832C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reel_core`
--
ALTER TABLE `reel_core`
  ADD CONSTRAINT `FK_F665FF0832C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reel_diameters`
--
ALTER TABLE `reel_diameters`
  ADD CONSTRAINT `FK_1350686232C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_13506862D629F605` FOREIGN KEY (`format_id`) REFERENCES `formats` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `slides`
--
ALTER TABLE `slides`
  ADD CONSTRAINT `FK_B8C0209132C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sounds`
--
ALTER TABLE `sounds`
  ADD CONSTRAINT `FK_F12306F132C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tape_thickness`
--
ALTER TABLE `tape_thickness`
  ADD CONSTRAINT `FK_34DDB80832C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `track_types`
--
ALTER TABLE `track_types`
  ADD CONSTRAINT `FK_29C72B9732C8A3DE` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
