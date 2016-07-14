-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 19, 2016 at 09:29 PM
-- Server version: 5.1.40
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `catalog`
--

-- --------------------------------------------------------

--
-- Table structure for table `amount`
--

CREATE TABLE IF NOT EXISTS `amount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `amount`
--

INSERT INTO `amount` (`id`, `product_id`, `size_id`, `amount`) VALUES
(1, 1, 3, 2),
(2, 1, 5, 1),
(3, 2, 3, 2),
(4, 2, 4, 3),
(5, 3, 3, 2),
(6, 3, 4, 3),
(7, 3, 5, 1),
(8, 4, 4, 2),
(9, 4, 5, 1),
(10, 4, 7, 3),
(11, 5, 3, 2),
(12, 5, 4, 3),
(13, 5, 5, 1),
(14, 6, 3, 2),
(15, 6, 4, 3),
(16, 6, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=58 ;

--
-- Dumping data for table `category`
--
-- TRUNCATE  `category`;
INSERT INTO `category` (`id`, `parent_id`, `name`) VALUES
(1, 0, 'Мужская одежда'),
(2, 0, 'Женская одежда'),
(3, 1, 'Футболки'),
(4, 1, 'Брюки'),
(5, 2, 'Футболки'),
(6, 2, 'Брюки'),
(7, 6, 'Джинсы'),
(57, 6, 'Спортивные');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `customer`
--


-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `sum` int(255) NOT NULL,
  `text` text NOT NULL,
  `status` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `order`
--


-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `note` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_wm` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `category`, `price`, `note`, `image`, `image_wm`) VALUES
(1, 6, 500, 'data', '/images/8cc3e5f200b14719ad4de09f34176ea6.png', '/images/wm_8cc3e5f200b14719ad4de09f34176ea6.png'),
(2, 7, 550, 'data', '/images/13bf372f0f9be783c22aaf430ed3cb71.png', '/images/wm_13bf372f0f9be783c22aaf430ed3cb71.png'),
(3, 3, 500, 'data', '/images/81a18827a5da6db5750ca7ddee956766.png', '/images/wm_81a18827a5da6db5750ca7ddee956766.png'),
(4, 3, 500, 'data', '/images/8ca25cb7a14fc328b5c50bb640f44d16.png', '/images/wm_8ca25cb7a14fc328b5c50bb640f44d16.png'),
(5, 4, 700, 'data', '/images/134f4ed6a407c5b790edbda4046dbf59.png', '/images/wm_134f4ed6a407c5b790edbda4046dbf59.png'),
(6, 3, 500, 'data', '/images/96d0510823a689be7f56ad9de6f4a45c.png', '/images/wm_96d0510823a689be7f56ad9de6f4a45c.png');

-- --------------------------------------------------------

--
-- Table structure for table `size`
--

CREATE TABLE IF NOT EXISTS `size` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `size`
--

INSERT INTO `size` (`id`, `name`) VALUES
(1, 'XS'),
(2, 'S'),
(3, 'M'),
(4, 'L'),
(5, 'XL'),
(6, 'XXL'),
(7, 'XXXL');
