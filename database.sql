-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2018 at 02:53 AM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ele`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblcontrollers`
--

CREATE TABLE `tblcontrollers` (
  `ctr_id` int(11) NOT NULL,
  `ctr_name` text NOT NULL,
  `ctr_allow` int(11) NOT NULL DEFAULT '0',
  `ctr_status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcontrollers`
--

INSERT INTO `tblcontrollers` (`ctr_id`, `ctr_name`, `ctr_allow`, `ctr_status`) VALUES
(1, 'Front Light 1', 1, 1),
(2, 'Front Light 2', 1, 1),
(3, 'Front Fan 1', 1, 0),
(4, 'Projector', 0, 0),
(5, 'Amplifire', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblinput`
--

CREATE TABLE `tblinput` (
  `inp_id` int(11) NOT NULL,
  `inp_name` text NOT NULL,
  `inp_weight` float NOT NULL,
  `inp_status` int(11) NOT NULL DEFAULT '1',
  `inp_value` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblinput`
--

INSERT INTO `tblinput` (`inp_id`, `inp_name`, `inp_weight`, `inp_status`, `inp_value`) VALUES
(1, 'Test', 100, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblreference`
--

CREATE TABLE `tblreference` (
  `ref_id` int(11) NOT NULL,
  `ref_value` int(11) NOT NULL,
  `ref_time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblreference`
--

INSERT INTO `tblreference` (`ref_id`, `ref_value`, `ref_time`) VALUES
(1, 20, '2018-09-03 11:10:48');

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `usr_id` int(11) NOT NULL,
  `usr_name` text NOT NULL,
  `usr_username` text NOT NULL,
  `usr_password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`usr_id`, `usr_name`, `usr_username`, `usr_password`) VALUES
(1, 'Pasan Guruge', 'pasanbguruge@gmail.com', 'cGFzcw=='),
(3, 'pasan', 'pasanbguruge@gmail.co', 'MDAwMA==');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblcontrollers`
--
ALTER TABLE `tblcontrollers`
  ADD PRIMARY KEY (`ctr_id`);

--
-- Indexes for table `tblinput`
--
ALTER TABLE `tblinput`
  ADD PRIMARY KEY (`inp_id`);

--
-- Indexes for table `tblreference`
--
ALTER TABLE `tblreference`
  ADD PRIMARY KEY (`ref_id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`usr_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
