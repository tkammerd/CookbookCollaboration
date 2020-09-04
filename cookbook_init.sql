-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 23, 2019 at 03:08 PM
-- Server version: 5.6.43-cll-lve
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
-- Database: `MyDemos`
--

-- --------------------------------------------------------

--
-- Table structure for table `RECIPES`
--

DROP TABLE IF EXISTS `RECIPES`;
CREATE TABLE IF NOT EXISTS `RECIPES` (
  `RecipeId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `RecipeTitle` varchar(60) DEFAULT NULL,
  `RecipeAuthFName` varchar(40) DEFAULT NULL,
  `RecipeAuthLName` varchar(40) DEFAULT NULL,
  `RecipeAuthCName` varchar(40) DEFAULT NULL,
  `RecipeAuthClass` int(11) DEFAULT NULL,
  `RecipeInputBy` varchar(40) DEFAULT NULL,
  `RecipeLastStartedBy` varchar(40) DEFAULT NULL,
  `RecipeLastStartedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `RecipeRawFilePath` varchar(60) DEFAULT NULL,
  `RecipeText` text,
  PRIMARY KEY (`RecipeId`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `RECIPES`
--

INSERT INTO `RECIPES` (`RecipeId`, `RecipeTitle`, `RecipeAuthFName`, `RecipeAuthLName`, `RecipeAuthCName`, `RecipeAuthClass`, `RecipeInputBy`, `RecipeLastStartedBy`, `RecipeLastStartedAt`, `RecipeRawFilePath`, `RecipeText`) VALUES
(1, 'Blooming Onion', 'Michael', 'Dundee', NULL, NULL, NULL, NULL, '2019-06-23 22:02:12', 'bloomin onion 300dpi hq b&w.pdf', NULL),
(2, 'Divinity', 'Milton', 'Hershey', NULL, NULL, NULL, NULL, '2019-06-23 22:02:17', 'divinity.pdf', NULL),
(3, 'Bread Mix', 'Betty', 'Crocker', NULL, NULL, NULL, NULL, '2019-06-23 22:02:23', 'bread mix.pdf', NULL),
(4, 'East Texas Sweet Potato Pie', 'Misty', 'Gale', 'Smith', 1980, 'chefB', 'guest', '2019-06-23 20:21:42', 'east-gale-guest.pdf', '@@@@@\r\n\r\nEast Texas Sweet Potato Pie\r\n\r\ndessert\r\n\r\n      ----East Texas Sweet Potato Pie----\r\n2 cups cooked sweet potatoes\r\n1 1/2 cups sugar\r\n1/3  cup cream or evaporated milk\r\n1/2 <<stick>> butter\r\n1 x egg\r\n1 teaspoon vanilla\r\n      ----Mom\'s Easy Non-Shortening Pie Crust----\r\n2 cups flour\r\n1 teaspoon salt\r\n1/2 cup oil\r\n1/4 cup milk\r\n\r\nMix well. Pour into unbaked piecrust. Bake at 350 for 50 minutes or until knife comes out clean.\r\n\r\nThis is my personal adaptation of my favorite pie -- the pie my mom made me for my birthday when I was a kid, instead of a cake. This pie has less butter and sugar than some recipes, but tastes great in my opinion. You can add another 1/2 cup sugar and 1/2 stick butter if you want it richer. I usually double the recipe and make two pies while I\'m at it.\r\n\r\nDirections for pie crust:\r\nMeasure accurately. Mix 2 cups flour with 1 tsp. salt. In a measuring cup, mix 1/2 cup oil and 1/4 cup milk. Pour into flour mixture and blend well. (Makes 2 crusts)\r\n\r\nContributor: Melody Spray (was Jones in 1980)\r\n\r\nYield: 12 servings\r\n\r\nPreparation Time: 1:15');

-- --------------------------------------------------------

--
-- Table structure for table `TEAM`
--

DROP TABLE IF EXISTS `TEAM`;
CREATE TABLE IF NOT EXISTS `TEAM` (
  `TeammateName` varchar(40) NOT NULL,
  `EncryptedPassword` varchar(40) DEFAULT NULL,
  `RecipesStarted` int(11) DEFAULT '0',
  `LatestRecipeStart` datetime DEFAULT NULL,
  `RecipesCompleted` int(11) DEFAULT '0',
  `LatestRecipeCompletion` datetime DEFAULT NULL,
  PRIMARY KEY (`TeammateName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `TEAM`
--

INSERT INTO `TEAM` (`TeammateName`, `EncryptedPassword`, `RecipesStarted`, `LatestRecipeStart`, `RecipesCompleted`, `LatestRecipeCompletion`) VALUES
('guest', 'si5THvaq1YVlw', 4, '2019-06-23 13:21:42', 0, NULL),
('chefB', 'sibr3J.Vd4mRY', 0, '2019-06-19 11:03:27', 1, '2019-06-24 13:21:42');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
