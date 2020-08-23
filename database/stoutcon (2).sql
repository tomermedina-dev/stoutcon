-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 04, 2020 at 01:50 AM
-- Server version: 5.7.30-0ubuntu0.18.04.1
-- PHP Version: 7.2.31-1+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stoutcon`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `attendance` (IN `user_id` INTEGER, IN `bio_id` INTEGER, IN `month_year` DATE)  BEGIN

SELECT 
date_field,

(SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 1) as first_time_in,

(SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 1,1) as first_time_out,

(SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 2,1) as second_time_in,

(SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 3,1) as second_time_out,

(SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 4,1) as third_time_in,

(SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 5,1) as third_time_out,

(SELECT TIMEDIFF((SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord DESC  LIMIT 1), (SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 1))) as time_diff,


(SELECT  SUBTIME( (SELECT TIMEDIFF((SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord DESC  LIMIT 1), (SELECT biologs.TimeOnlyRecord as time FROM users INNER JOIN biologs ON users.biometric_id = biologs.IndRegID WHERE users.id = user_id AND biologs.DateOnlyRecord =  date_field AND biologs.MachineNumber = bio_id ORDER BY biologs.TimeOnlyRecord ASC  LIMIT 1))) , '09:00:00') ) as overtime_diff



FROM
(
    SELECT
        MAKEDATE(YEAR(month_year),1) +
        INTERVAL (MONTH(month_year)-1) MONTH +
        INTERVAL daynum DAY date_field
    FROM
    (
        SELECT t*10+u daynum
        FROM
            (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3) A,
            (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
            UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
            UNION SELECT 8 UNION SELECT 9) B
        ORDER BY daynum
    ) AA
) AAA
WHERE MONTH(date_field) = MONTH(month_year);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_balance_sheet` (IN `period_filter` VARCHAR(255))  BEGIN

SELECT

'' as id,
'ASSETS' as title,
'' as category,
'' as account,
'' as amount,
'' as total

UNION

SELECT

'' as id,
'' as title,
'CURRENT ASSETS' as category,
'' as account,
'' as amount,
'' as total

UNION

SELECT

id as id,
'' as title,
'' as category,
account,
FORMAT(amount,2) as amount,
'' as total

FROM balance_sheet WHERE (period = period_filter OR  status = 1) AND category = 1 AND type = 1 AND deleted_at IS NULL

UNION

SELECT

'' as id,
'' as title,
'FIXED ASSETS' as category,
'' as account,
'' as amount,
'' as total




UNION

SELECT

id as id,
'' as title,
'' as category,
account,
FORMAT(amount,2) as amount,
'' as total

FROM balance_sheet WHERE (period = period_filter OR  status = 1) AND category = 2 AND type = 1 AND deleted_at IS NULL





UNION

SELECT

'' as id,
'TOTAL ASSETS' as title,
'' as category,
'' as account,
'' as amount,
FORMAT(SUM(amount),2) as total

FROM balance_sheet WHERE (period = period_filter OR  status = 1) AND category IN (1,2) AND type = 1 AND deleted_at IS NULL


UNION

SELECT

'' as id,
'' as title,
'' as category,
'' as account,
'' as amount,
'-' as total


UNION

SELECT

'' as id,
"LIABILITIES & OWNER'S EQUITY" as title,
'' as category,
'' as account,
'' as amount,
'' as total


UNION

SELECT

'' as id,
'' as title,
'CURRENT LIABILITIES' as category,
'' as account,
'' as amount,
'' as total

UNION

SELECT

id as id,
'' as title,
'' as category,
account,
FORMAT(amount,2) as amount,
'' as total

FROM balance_sheet WHERE (period = period_filter OR  status = 1) AND type = 2 AND deleted_at IS NULL



UNION

SELECT

'' as id,
'TOTAL LIABILITIES' as title,
'' as category,
'' as account,
'' as amount,
FORMAT(SUM(amount),2) as total

FROM balance_sheet WHERE (period = period_filter OR  status = 1) AND type = 2 AND deleted_at IS NULL



UNION

SELECT

'' as id,
'' as title,
'' as category,
'' as account,
'' as amount,
'-' as total



UNION

SELECT

'' as id,
'' as title,
"OWNER'S EQUITY" as category,
'' as account,
'' as amount,
'' as total



UNION

SELECT

id as id,
'' as title,
'' as category,
account,
FORMAT(amount,2) as amount,
'' as total

FROM balance_sheet WHERE (period = period_filter OR  status = 1) AND type = 3 AND deleted_at IS NULL


UNION

SELECT

'' as id,
'TOTAL EQUITY' as title,
'' as category,
'' as account,
'' as amount,
FORMAT(SUM(amount),2) as total

FROM balance_sheet WHERE (period = period_filter OR  status = 1) AND type = 3 AND deleted_at IS NULL


UNION

SELECT

'' as id,
'' as title,
'' as category,
'' as account,
'' as amount,
'--' as total



UNION

SELECT

'' as id,
"TOTAL LIANILITIES AND OWNER'S EQUITY" as title,
'' as category,
'' as account,
'' as amount,
FORMAT(SUM(amount),2) as total

FROM balance_sheet WHERE (period = period_filter OR  status = 1) AND type IN (2,3) AND deleted_at IS NULL;




END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_income_statement` (IN `period_filter` VARCHAR(255))  BEGIN

SELECT

'' as id,'REVENUE:' as title,
'' as account,
'' as period,
'' as col1,
'' as col2

UNION

SELECT 
id,
'' as title,
account,
period,

(
 IF(type = 4,FORMAT(amount,2),'') 
) as col1,

'' as col2

FROM income_statement WHERE (period = period_filter OR  status = 1) AND type = 4 AND deleted_at IS NULL

UNION

SELECT

'' as id,
'TOTAL REVENUE:' as title,
'' as account,
'' as period,
'' as col1,
(SELECT FORMAT(SUM(amount),2)
FROM income_statement WHERE (period = period_filter OR  status = 1) AND type = 4 AND deleted_at IS NULL ) as col2

UNION

SELECT

'' as id,
'' as title,
'' as account,
'' as period,
'' as col1,
'' as col2


UNION

SELECT

'' as id,
'EXPENSES:' as title,
'' as account,
'' as period,
'' as col1,
'' as col2

UNION

SELECT 
id,
'' as title,
account,
period,

(
 IF(type = 5,FORMAT(amount,2),'') 

) col1,

'' as col2


FROM income_statement WHERE (period = period_filter OR  status = 1) AND type = 5 AND deleted_at IS NULL

UNION

SELECT

'' as id,
'' as title,
'' as account,
'' as period,
'' as col1,
'' as col2


UNION

SELECT

'' as id,
'TOTAL EXPENSES:' as title,
'' as account,
'' as period,

'' as col1,
(SELECT FORMAT(SUM(amount),2)
FROM income_statement WHERE (period = period_filter OR  status = 1) AND type = 5 AND deleted_at IS NULL ) as col2

UNION
SELECT

'' as id,
'' as title,
'' as account,
'' as period,
'' as col1,
'' as col2

UNION


SELECT

'' as id,
'' as title,
'' as account,
'' as period,
'' as col1,
'-' as col2

UNION

SELECT

'' as id,
'PROFIT:' as title,
'' as account,
'' as period,
'-' as col1,
(FORMAT((SELECT SUM(amount)
FROM income_statement WHERE (period = period_filter OR  status = 1) AND type = 4 AND deleted_at IS NULL ) - (SELECT SUM(amount)
FROM income_statement WHERE (period = period_filter OR  status = 1) AND type = 5 AND deleted_at IS NULL ),2)) as col2;



END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_trial_balance` (IN `period_filter` VARCHAR(255))  BEGIN


SELECT 
id,
account,
period,
(
 IF(tool = 1,FORMAT(amount,2),'0.00') 

) as debit,

(
 IF(tool = 2,FORMAT(amount,2),'0.00') 

) as credit,
type,
status,
tool

FROM trial_balance WHERE (period = period_filter OR  status = 1) AND deleted_at IS NULL;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `accounting_period`
--

CREATE TABLE `accounting_period` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `period` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `balance_sheet`
--

CREATE TABLE `balance_sheet` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `period` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trial_balanace_id` int(11) NOT NULL,
  `account` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `category` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `amount` double(15,2) DEFAULT '0.00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `balance_sheet`
--

INSERT INTO `balance_sheet` (`id`, `period`, `trial_balanace_id`, `account`, `type`, `category`, `status`, `amount`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '06/2020', 6, 'Inventory', 1, 1, 1, 150000.00, NULL, '2020-06-03 05:03:41', '2020-06-03 05:24:35'),
(2, '06/2020', 7, 'Furniture', 1, 2, 1, 89000.00, NULL, '2020-06-03 05:04:07', '2020-06-03 22:37:08'),
(3, '06/2020', 8, 'Accumulated Depreciation - Furniture', 1, 2, 1, 900.00, NULL, '2020-06-03 05:27:51', '2020-06-03 05:27:51'),
(4, '06/2020', 9, 'Equipment', 1, 2, 1, 120000.00, NULL, '2020-06-03 05:28:31', '2020-06-03 05:28:31'),
(5, '06/2020', 10, 'Accumulated Depreciation - Equipment', 1, 2, 1, 1300.00, NULL, '2020-06-03 05:29:02', '2020-06-03 05:29:02'),
(6, '06/2020', 0, 'Accounts Payable', 2, NULL, 1, 17363.07, NULL, '2020-06-03 22:32:34', '2020-06-03 22:32:34'),
(7, '06/2020', 0, 'werwer', 3, NULL, 1, 23434.00, NULL, '2020-06-03 22:37:27', '2020-06-03 22:37:27'),
(8, '06/2020', 12, 'test', 3, 1, 1, 345345.00, NULL, '2020-06-03 23:13:32', '2020-06-03 23:13:32');

-- --------------------------------------------------------

--
-- Table structure for table `biologs`
--

CREATE TABLE `biologs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `MachineNumber` int(11) NOT NULL,
  `IndRegID` int(11) NOT NULL,
  `DwInOutMode` int(11) NOT NULL,
  `DateTimeRecord` datetime NOT NULL,
  `DateOnlyRecord` date NOT NULL,
  `TimeOnlyRecord` time NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `biometrics`
--

CREATE TABLE `biometrics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `income_statement`
--

CREATE TABLE `income_statement` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `period` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trial_balanace_id` int(11) NOT NULL,
  `account` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `amount` double(15,2) DEFAULT '0.00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `income_statement`
--

INSERT INTO `income_statement` (`id`, `period`, `trial_balanace_id`, `account`, `type`, `status`, `amount`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '06/2020', 1, 'Checking Account', 4, 1, 212000.00, NULL, '2020-06-03 05:01:12', '2020-06-03 05:01:12'),
(2, '06/2020', 2, 'Savings Account', 4, 1, 30000.00, NULL, '2020-06-03 05:01:35', '2020-06-03 05:01:35'),
(3, '06/2020', 3, 'Cash on Hand', 4, 2, 10000.00, NULL, '2020-06-03 05:02:00', '2020-06-03 05:04:16'),
(4, '06/2020', 4, 'Petty Cash', 4, 1, 1200.00, NULL, '2020-06-03 05:02:25', '2020-06-03 05:02:25'),
(5, '06/2020', 5, 'Accounts Receivable', 4, 2, 99000.00, NULL, '2020-06-03 05:02:54', '2020-06-03 05:08:05'),
(6, '06/2020', 11, 'Inventory', 4, 1, 150000.00, NULL, '2020-06-03 07:17:15', '2020-06-03 09:31:01'),
(7, '06/2020', 0, 'Furniture', 4, 1, 88000.00, NULL, '2020-06-03 09:18:50', '2020-06-03 09:22:31'),
(8, '06/2020', 0, 'Service Revenue - Patient Conduction', 4, 1, 33000.00, '2020-06-03 09:22:22', '2020-06-03 09:19:13', '2020-06-03 09:22:22'),
(9, '06/2020', 0, 'Intense Expenses', 5, 1, 30000.00, NULL, '2020-06-03 09:34:00', '2020-06-03 09:34:00');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(34, '2014_10_12_000000_create_users_table', 1),
(35, '2014_10_12_100000_create_password_resets_table', 1),
(36, '2019_08_19_000000_create_failed_jobs_table', 1),
(37, '2020_05_21_173311_create_biometrics_table', 1),
(38, '2020_05_22_145359_create_biologs_table', 1),
(39, '2020_05_29_154910_create_payroll_table', 1),
(40, '2020_06_01_182111_create_trial_balance_table', 1),
(41, '2020_06_01_182150_create_balance_sheet_table', 1),
(42, '2020_06_02_011335_create_income_statement_table', 1),
(43, '2020_06_02_012423_create_statement_of_change_in_equilty_table', 1),
(44, '2020_06_02_130917_create_accounting_period_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `month_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `days` int(11) DEFAULT '0',
  `basic_salary` double(15,2) DEFAULT '0.00',
  `nights` int(11) DEFAULT '0',
  `night_differencial` double(15,2) DEFAULT '0.00',
  `total_basic_salary` double(15,2) DEFAULT '0.00',
  `overtime` double(15,2) DEFAULT '0.00',
  `benefits` double(15,2) DEFAULT '0.00',
  `other_benefits` double(15,2) DEFAULT '0.00',
  `gross_pay` double(15,2) DEFAULT '0.00',
  `sss` double(15,2) DEFAULT '0.00',
  `philhealth` double(15,2) DEFAULT '0.00',
  `pag_ibig` double(15,2) DEFAULT '0.00',
  `tax` double(15,2) DEFAULT '0.00',
  `tardiness` double(15,2) DEFAULT '0.00',
  `total_deduction` double(15,2) DEFAULT '0.00',
  `net_pay` double(15,2) DEFAULT '0.00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `user_id`, `month_year`, `days`, `basic_salary`, `nights`, `night_differencial`, `total_basic_salary`, `overtime`, `benefits`, `other_benefits`, `gross_pay`, `sss`, `philhealth`, `pag_ibig`, `tax`, `tardiness`, `total_deduction`, `net_pay`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, 2070.00, 4050.00, 1274.26, 32344.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 30246.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(2, 3, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(3, 4, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(4, 5, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(5, 6, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(6, 7, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(7, 8, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, 2070.00, 4050.00, 1274.26, 32344.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 30246.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(8, 9, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(9, 10, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(10, 11, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(11, 12, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(12, 13, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(13, 14, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(14, 15, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, 2070.00, 4050.00, 1274.26, 32344.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 30246.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(15, 16, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(16, 17, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(17, 18, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(18, 19, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(19, 20, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(20, 21, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(21, 22, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, 2070.00, 4050.00, 1274.26, 32344.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 30246.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(22, 23, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(23, 24, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(24, 25, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(25, 26, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(26, 27, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(27, 28, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(28, 29, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(29, 30, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(30, 31, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(31, 32, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(32, 33, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(33, 34, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(34, 35, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(35, 36, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(36, 37, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:07', '2020-06-04 00:22:07'),
(37, 38, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(38, 39, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, 2070.00, 4050.00, 1274.26, 32344.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 30246.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(39, 40, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(40, 41, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(41, 42, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(42, 43, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(43, 44, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(44, 45, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(45, 46, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(46, 47, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(47, 48, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(48, 49, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, 2070.00, 4050.00, 1274.26, 32344.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 30246.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(49, 50, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(50, 51, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(51, 52, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(52, 53, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(53, 54, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(54, 55, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(55, 56, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(56, 57, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(57, 58, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(58, 59, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(59, 60, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(60, 61, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(61, 62, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(62, 63, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(63, 64, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, 2070.00, 4050.00, 1274.26, 32344.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 30246.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(64, 65, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(65, 66, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(66, 67, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(67, 68, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(68, 69, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(69, 70, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(70, 71, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(71, 72, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(72, 73, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(73, 74, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(74, 75, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(75, 76, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(76, 77, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(77, 78, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(78, 79, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(79, 80, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(80, 81, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(81, 82, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, 2070.00, 4050.00, 1274.26, 32344.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 30246.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(82, 83, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(83, 84, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(84, 85, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(85, 86, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(86, 87, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(87, 88, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(88, 89, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(89, 90, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(90, 91, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(91, 92, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(92, 93, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(93, 94, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(94, 95, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(95, 96, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(96, 97, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(97, 98, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(98, 99, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(99, 100, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(100, 101, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, 2070.00, 4050.00, 1274.26, 32344.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 30246.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(101, 102, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(102, 103, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(103, 104, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(104, 105, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, 2070.00, 4050.00, 1274.26, 32344.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 30246.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(105, 106, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(106, 107, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(107, 108, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(108, 109, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(109, 110, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(110, 111, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(111, 112, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(112, 113, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(113, 114, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(114, 115, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, 2070.00, 4050.00, 1274.26, 32344.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 30246.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(115, 116, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(116, 117, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(117, 118, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(118, 119, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(119, 120, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, 2070.00, 4050.00, 1274.26, 32344.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 30246.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08'),
(120, 121, '05/2020', 16, 17664.00, 6, 7286.40, 24950.40, NULL, 4050.00, 1274.26, 30274.66, 800.00, 374.26, 100.00, 823.48, NULL, 2097.74, 28176.92, NULL, '2020-06-04 00:22:08', '2020-06-04 00:22:08');

-- --------------------------------------------------------

--
-- Stand-in structure for view `payslip`
-- (See below for the actual view)
--
CREATE TABLE `payslip` (
`user_id` bigint(20) unsigned
,`name` varchar(255)
,`email` varchar(255)
,`username` varchar(255)
,`password` varchar(255)
,`date_of_birth` date
,`address` varchar(255)
,`mobile_number` varchar(255)
,`employee_identification` varchar(255)
,`position` varchar(255)
,`department` varchar(255)
,`project` varchar(255)
,`location` varchar(255)
,`start_date` date
,`end_date` date
,`work_status` varchar(255)
,`biometric_id` varchar(255)
,`biometric` int(11)
,`payslip_id` bigint(20) unsigned
,`month_year` varchar(255)
,`days` int(11)
,`basic_salary` double(15,2)
,`nights` int(11)
,`night_differencial` double(15,2)
,`total_basic_salary` double(15,2)
,`overtime` double(15,2)
,`benefits` double(15,2)
,`other_benefits` double(15,2)
,`gross_pay` double(15,2)
,`sss` double(15,2)
,`philhealth` double(15,2)
,`pag_ibig` double(15,2)
,`tax` double(15,2)
,`tardiness` double(15,2)
,`total_deduction` double(15,2)
,`net_pay` double(15,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `statement_of_changes_in_equity`
--

CREATE TABLE `statement_of_changes_in_equity` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `period` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trial_balanace_id` int(11) NOT NULL,
  `account` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `category` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `amount` double(15,2) DEFAULT '0.00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trial_balance`
--

CREATE TABLE `trial_balance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `period` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tool` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `category` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `amount` double(15,2) DEFAULT '0.00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trial_balance`
--

INSERT INTO `trial_balance` (`id`, `period`, `account`, `tool`, `type`, `category`, `status`, `amount`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '06/2020', 'Checking Account', 1, 4, 1, 2, 212000.00, NULL, '2020-06-03 05:01:12', '2020-06-03 05:01:12'),
(2, '06/2020', 'Savings Account', 1, 4, 1, 2, 30000.00, NULL, '2020-06-03 05:01:35', '2020-06-03 05:01:35'),
(3, '06/2020', 'Cash on Hand', 1, 4, 1, 2, 10000.00, NULL, '2020-06-03 05:01:59', '2020-06-03 05:04:16'),
(4, '06/2020', 'Petty Cash', 1, 4, 1, 2, 1200.00, NULL, '2020-06-03 05:02:24', '2020-06-03 05:02:24'),
(5, '06/2020', 'Accounts Receivable', 1, 4, 1, 2, 99000.00, NULL, '2020-06-03 05:02:54', '2020-06-03 05:02:54'),
(6, '06/2020', 'Inventory', 1, 1, 1, 1, 150000.00, NULL, '2020-06-03 05:03:41', '2020-06-03 05:24:35'),
(7, '06/2020', 'Furniture', 1, 1, 2, 1, 88000.00, NULL, '2020-06-03 05:04:06', '2020-06-03 05:04:06'),
(8, '06/2020', 'Accumulated Depreciation - Furniture', 2, 1, 2, 1, 900.00, '2020-06-03 09:21:30', '2020-06-03 05:27:50', '2020-06-03 09:21:30'),
(9, '06/2020', 'Equipment', 1, 1, 2, 1, 120000.00, NULL, '2020-06-03 05:28:29', '2020-06-03 05:28:29'),
(10, '06/2020', 'Accumulated Depreciation - Equipment', 2, 1, 2, 1, 1300.00, NULL, '2020-06-03 05:29:02', '2020-06-03 05:29:02'),
(11, '06/2020', 'Interest Expense', 1, 5, 1, 1, 3500.00, NULL, '2020-06-03 07:17:15', '2020-06-03 07:17:15'),
(12, '06/2020', 'test', 1, 3, 1, 1, 345345.00, NULL, '2020-06-03 23:13:32', '2020-06-03 23:13:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_identification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `rate_per_hour` double(15,2) DEFAULT '0.00',
  `night_differencial` double(15,2) DEFAULT '0.00',
  `salary_amount` double(15,2) DEFAULT '0.00',
  `sss_contribution` double(15,2) DEFAULT '0.00',
  `philhealh` double(15,2) DEFAULT '0.00',
  `pag_ibig` double(15,2) DEFAULT '0.00',
  `tax_withheld` double(15,2) DEFAULT '0.00',
  `benefits` double(15,2) DEFAULT '0.00',
  `other_benefits` double(15,2) DEFAULT '0.00',
  `biometric_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biometric` int(11) NOT NULL DEFAULT '1',
  `role` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `date_of_birth`, `address`, `mobile_number`, `employee_identification`, `position`, `department`, `work_status`, `project`, `location`, `start_date`, `end_date`, `rate_per_hour`, `night_differencial`, `salary_amount`, `sss_contribution`, `philhealh`, `pag_ibig`, `tax_withheld`, `benefits`, `other_benefits`, `biometric_id`, `biometric`, `role`, `status`, `email_verified_at`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@admin.com', 'admin', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 1, 1, 1, NULL, NULL, NULL, '2020-06-03 04:55:35', '2020-06-03 04:55:35'),
(2, 'Prof. Miguel Hill', 'judson42@morissette.com', 'nwill', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2003-01-23', '55257 Bechtelar Passage\nCartwrightborough, MO 91096', '(787) 669-6200 x100', '33-2627315', 'Cartoonist', 'Hahn, Bernier and Fay', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2017-06-17', '2019-02-25', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:35', '2020-06-03 04:55:35'),
(3, 'Berniece Ruecker', 'lorenzo73@yahoo.com', 'zachery.walter', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1992-03-06', '5936 Geovany Hills Suite 079\nEast Jabari, LA 22412', '(834) 760-5812', '16-9269333', 'Lay-Out Worker', 'Dietrich Inc', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1977-04-09', '1999-05-17', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:35', '2020-06-03 04:55:35'),
(4, 'Kennedy Nitzsche', 'rau.lolita@gmail.com', 'concepcion.price', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1998-04-22', '4581 Jarod Circles\nKaseyton, NM 70333-9693', '932-546-0003 x598', '52-7791577', 'Jewelry Model OR Mold Makers', 'Connelly LLC', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1980-10-07', '1975-04-08', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:35', '2020-06-03 04:55:35'),
(5, 'Lon Kuhn', 'ternser@carter.info', 'gino04', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1981-09-17', '8618 Louisa Mill\nLake Wilburnhaven, WA 08209-1943', '463-796-1045 x6781', '12-5955724', 'Therapist', 'Kassulke Inc', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2018-10-30', '2010-12-26', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:35', '2020-06-03 04:55:35'),
(6, 'Joany Balistreri I', 'rgreenfelder@huels.biz', 'dmorar', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2008-03-17', '826 Damon Squares Apt. 985\nNorth Heber, CA 83934-9026', '+1-636-286-8652', '42-6250046', 'Medical Records Technician', 'Rowe and Sons', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1982-03-08', '2011-08-30', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:35', '2020-06-03 04:55:35'),
(7, 'Mrs. Annie Schuster', 'graham15@schamberger.biz', 'rigoberto.farrell', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1973-07-20', '189 Kiehn Curve Suite 652\nPort Orionfurt, PA 73175-8618', '+1.978.665.7224', '82-2049525', 'Production Planning', 'Jacobson, Lesch and Casper', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1996-06-11', '2004-10-30', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:35', '2020-06-03 04:55:35'),
(8, 'Prof. Gussie Koch V', 'grimes.demetris@gmail.com', 'amely.zemlak', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2009-10-22', '2703 Alba Ports Suite 862\nRomagueraborough, SD 09687-2671', '(583) 439-1388 x5456', '26-2330266', 'Loan Interviewer', 'Schoen, Runte and Mayer', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1984-01-26', '2017-03-15', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(9, 'Jean Batz', 'aniyah84@rice.org', 'mariano.russel', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2007-02-07', '638 Swift Falls Suite 220\nJohnstonview, ID 05715-0100', '923-220-8836 x02669', '56-2555799', 'Law Enforcement Teacher', 'Rowe and Sons', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2009-11-23', '2019-06-10', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(10, 'Aaliyah Hagenes', 'tianna.mraz@hotmail.com', 'eladio.ankunding', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2011-10-27', '3724 Dortha Rapid\nPort Berenice, AL 04235-2280', '1-870-896-5118 x1234', '37-2936121', 'Real Estate Sales Agent', 'Kemmer Ltd', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1987-02-03', '2004-02-21', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(11, 'Nathanael Kihn', 'kward@rice.info', 'elenora60', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2015-03-16', '294 Miles Lane Suite 036\nWest Vicenta, AK 97613-8708', '(981) 230-5613', '68-8083475', 'Utility Meter Reader', 'Gottlieb-Mann', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1982-07-14', '2013-01-26', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(12, 'Miss Lizzie Bode IV', 'heathcote.corene@gmail.com', 'zhilpert', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1994-01-15', '751 Anderson Plain\nWest Elnorafort, MT 92696', '1-421-929-5854 x4015', '63-8011088', 'Sailor', 'Casper-Berge', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2020-03-08', '2004-09-15', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(13, 'Coty Rippin', 'dooley.jaron@will.net', 'arnold21', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1977-11-30', '833 Arely Plains\nNorth Newellhaven, VT 85174', '450-575-2106 x0872', '01-1816852', 'Engine Assembler', 'Senger-Adams', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1984-10-11', '1996-04-21', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(14, 'Francisca Hegmann', 'zarmstrong@hotmail.com', 'zherzog', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2014-09-15', '32019 Stiedemann Falls\nBlakeborough, ND 44142', '+16916005330', '32-8530959', 'Postal Service Mail Carrier', 'Oberbrunner-Spencer', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2016-11-24', '1976-01-16', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(15, 'Reymundo Bashirian DDS', 'alvis40@deckow.org', 'gcorkery', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2010-06-11', '1824 Gino Landing\nDoyleburgh, PA 52110-0828', '1-689-246-2249 x000', '42-2117021', 'Printing Machine Operator', 'Oberbrunner, Shanahan and Abbott', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1993-03-13', '1994-03-29', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(16, 'Erik Ondricka', 'kolby.prohaska@osinski.com', 'roman.swift', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2009-05-04', '131 Keebler Mount\nNorth Norma, ID 36376-0108', '552.835.5684 x0415', '25-0202149', 'Custom Tailor', 'O\'Connell Ltd', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2020-05-02', '2018-03-05', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(17, 'Velda Senger', 'davis.anjali@collier.com', 'isabel.dickinson', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2012-12-16', '5932 Cicero River\nWest Melanyview, NV 74532', '938-781-2828 x25750', '45-8153815', 'Ceiling Tile Installer', 'Hirthe-Buckridge', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1992-05-09', '2003-04-07', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(18, 'Dr. Parker Dach', 'dooley.thelma@koepp.com', 'abbott.alia', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2009-01-15', '567 Becker Fall\nPort Arvel, OK 09620', '1-401-358-2240 x743', '85-0721388', 'Editor', 'Collier, Klocko and Nader', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2009-06-21', '2004-05-09', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(19, 'Prof. Ibrahim Mayer II', 'deckow.jermaine@gmail.com', 'santina13', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1979-12-20', '63283 Thelma Cape\nLucyview, DE 28568', '1-362-940-3859 x8460', '88-8488341', 'Homeland Security', 'Rempel-Koepp', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2015-05-26', '1972-07-21', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(20, 'Connie Hegmann', 'aufderhar.dell@kirlin.org', 'augustus.bradtke', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2007-01-12', '16156 Ernestine Common Suite 252\nWest Eloisaton, CO 82540', '+1.601.533.7725', '43-6847535', 'Mathematical Science Teacher', 'Corwin, Wehner and Littel', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1998-03-08', '2001-05-22', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(21, 'Name Kuphal', 'gislason.jamir@lockman.com', 'lysanne97', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1983-05-29', '7448 Douglas Dale Apt. 757\nO\'Connershire, KY 68391', '789.568.1393 x98991', '72-9077860', 'Photographic Restorer', 'Nikolaus PLC', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1977-02-18', '1983-09-01', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(22, 'Prof. Matteo Upton', 'frida31@hotmail.com', 'efren65', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2011-08-05', '4380 Rhea Extension\nKeelingmouth, NH 64106-8412', '637-891-1950 x7126', '39-0895340', 'Parking Lot Attendant', 'McClure Group', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2001-08-25', '1999-12-31', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(23, 'Mr. Jerod O\'Reilly', 'mhamill@yahoo.com', 'aohara', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1975-12-22', '6566 Blick Estate Apt. 029\nPort Antonettechester, ID 21068', '1-756-243-5734 x3249', '91-9861343', 'Securities Sales Agent', 'Parisian PLC', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1994-03-08', '2001-09-11', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(24, 'Gonzalo Farrell', 'larkin.kris@hyatt.info', 'wlakin', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2019-10-16', '616 Mann Trail\nWest Carolynside, MD 19678', '474.522.9379', '15-9612297', 'Welder', 'Zieme-Parisian', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1972-01-29', '2015-06-12', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(25, 'Rusty Littel', 'bwisozk@collins.com', 'bayer.lonny', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1998-09-26', '28000 Jessyca Stravenue\nJonesstad, IA 01054-0481', '+19523101822', '85-0691513', 'Agricultural Worker', 'Nader, Ryan and Metz', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1985-03-11', '1981-10-23', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:36', '2020-06-03 04:55:36'),
(26, 'Nicholas Kuhlman', 'nschuster@schaefer.info', 'schultz.lacey', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1986-09-20', '14582 Olaf Estate Apt. 002\nClintonview, VA 13352', '(898) 802-2046 x64381', '85-7368682', 'Press Machine Setter, Operator', 'McClure-Monahan', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1976-11-18', '1988-05-12', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(27, 'Ashley Padberg', 'bayer.miguel@kuhlman.com', 'rebecca.halvorson', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2007-05-09', '78144 Hudson Spurs Apt. 357\nHannahaven, PA 35533-3247', '1-531-295-4397 x8137', '47-9229401', 'Textile Cutting Machine Operator', 'Rodriguez LLC', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2001-10-21', '2003-07-11', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(28, 'Demetris Nikolaus', 'aric94@smith.net', 'labadie.sidney', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1976-08-26', '73494 Maryam Flat\nCartwrightchester, IA 27177', '+1.458.803.3383', '23-9479857', 'Utility Meter Reader', 'Koepp-Zboncak', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1974-08-26', '1983-06-23', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(29, 'Kiley Kerluke', 'ullrich.makenzie@yahoo.com', 'ashlynn42', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2005-05-17', '964 Braun Mountain Apt. 945\nFeeneychester, CA 91138-0826', '1-592-514-4250', '43-5980673', 'Truck Driver', 'Schulist-Feest', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1982-07-26', '1995-09-21', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(30, 'Luigi Welch', 'torey91@mcglynn.net', 'schroeder.christian', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2003-07-08', '26860 Patrick Corners\nBashirianshire, DC 00188', '268.587.1959', '75-7878771', 'Aircraft Body Repairer', 'Boyle-Zieme', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1999-12-08', '1995-10-30', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(31, 'Chasity Cummings I', 'jeromy.stanton@yahoo.com', 'geovanni91', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1991-11-13', '41837 Unique Views\nNew Aimee, MA 29420-8214', '606.595.9761 x793', '67-3098161', 'Forester', 'Brekke, Weissnat and Beer', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1980-12-21', '1992-04-27', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(32, 'Kavon Hilpert', 'jefferey81@hotmail.com', 'russell.legros', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1983-01-05', '399 Schoen Mountains Apt. 549\nBonitafort, VA 82128-2891', '729-279-4829 x33704', '92-6413669', 'Social Science Research Assistant', 'Cormier, Mante and Howe', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1994-07-23', '1996-12-04', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(33, 'Loma Schuster', 'rosenbaum.emelia@hotmail.com', 'nellie.braun', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2010-10-17', '28462 Stephen Via\nEast Gloria, MD 48461', '454-845-2809 x5581', '23-1528299', 'Nuclear Medicine Technologist', 'Schuppe, Bernier and Bergnaum', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2000-07-26', '1993-04-21', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(34, 'Brittany Wiza IV', 'akihn@gmail.com', 'nayeli.heidenreich', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2015-06-17', '23670 Carroll Squares Apt. 579\nWestside, WV 62407', '287.796.7470 x13597', '46-7320869', 'Sawing Machine Tool Setter', 'Turcotte and Sons', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2011-03-15', '1977-09-29', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(35, 'Isabella Wilkinson', 'powlowski.bernita@gmail.com', 'kunde.kadin', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1998-04-24', '7139 Shanelle Vista Suite 726\nHarberberg, GA 86451-6340', '456-407-9961 x62152', '66-6900730', 'Maintenance Worker', 'Schinner Inc', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1977-11-22', '2013-01-29', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(36, 'Brandt Dibbert', 'vframi@collins.com', 'pschimmel', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1983-01-22', '324 Lockman Estates\nPort Shane, MS 96114', '1-809-228-4277 x308', '90-6727800', 'Pile-Driver Operator', 'Swaniawski-Lehner', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2015-07-24', '2004-02-02', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(37, 'Gustave Treutel', 'baumbach.kadin@bailey.com', 'kassulke.nathanial', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1978-12-20', '9080 Gregg Shoal\nRunolfsdottirland, FL 90280-4913', '+1-356-394-6134', '56-5102119', 'Audio-Visual Collections Specialist', 'DuBuque and Sons', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2014-04-22', '1974-02-10', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(38, 'Paxton Stoltenberg', 'rubye81@yahoo.com', 'trantow.gabrielle', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2008-02-18', '374 Bartoletti Ramp\nEast Javon, AL 25263-1854', '+16494221105', '52-4826536', 'Automatic Teller Machine Servicer', 'Spencer-Hills', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2008-08-02', '1970-11-10', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(39, 'Noe Rosenbaum', 'constance.kulas@yahoo.com', 'viola93', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2011-06-19', '41119 Frederique Street Suite 809\nDejuanbury, LA 25285-9112', '567.507.6014', '54-3485611', 'Sawing Machine Tool Setter', 'Rempel, Quitzon and Bernhard', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1976-09-11', '2008-02-05', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(40, 'Karli Erdman', 'bthompson@yahoo.com', 'donavon.oconnell', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1970-01-16', '730 Will Canyon Apt. 803\nNew Jessyborough, VT 30539', '+1-232-770-5957', '48-5638262', 'Financial Manager', 'Blick-Hoeger', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1986-08-03', '1979-10-08', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(41, 'Nat Kutch', 'treutel.madonna@hotmail.com', 'luther80', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1985-03-23', '5547 Reichel Ridge\nJakubowskimouth, SD 93101-3231', '920.348.0913 x4712', '04-4175297', 'Coaches and Scout', 'Weber-Walsh', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2003-01-16', '1990-04-10', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(42, 'Miss Cristal Rau', 'ophelia.luettgen@hoppe.org', 'jaycee27', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1987-06-16', '753 Yundt Lake Apt. 433\nAlexyston, OK 26131-1836', '+1 (787) 794-5559', '54-4241588', 'Chemical Engineer', 'Keeling-Champlin', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2018-05-04', '1997-03-13', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(43, 'Austin Ziemann', 'gerlach.linnie@yahoo.com', 'carolina.bauch', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2003-06-03', '648 Upton Islands Apt. 053\nNorth Edison, CA 26486', '1-350-839-3551', '82-8804558', 'Diagnostic Medical Sonographer', 'Welch Inc', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2001-04-17', '1997-03-16', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:37', '2020-06-03 04:55:37'),
(44, 'Anya Auer IV', 'dmorissette@gmail.com', 'brenden98', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1996-04-25', '9353 Stoltenberg Plains Suite 805\nEast Rollinfurt, RI 30659', '438.671.4378 x53180', '58-0113012', 'Environmental Engineering Technician', 'Hessel, Parker and Walter', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2000-12-29', '2007-06-17', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(45, 'Cecil Fahey', 'qnitzsche@yahoo.com', 'jacky.corwin', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1993-04-03', '695 Hahn Street Suite 796\nGaylordchester, CO 86426', '578-442-5666 x9511', '81-8658959', 'Electronic Equipment Assembler', 'Gerlach, Douglas and Quigley', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1984-08-27', '1976-01-14', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(46, 'Aditya Larson', 'hegmann.laila@gmail.com', 'okeefe.khalid', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1999-12-13', '4068 Rowena Hill Apt. 867\nPaulborough, NY 68095-1970', '1-214-945-3610', '64-7795546', 'Production Helper', 'Cartwright, Ullrich and Oberbrunner', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2013-07-16', '2003-02-25', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(47, 'Dr. Meaghan Von', 'karlie.hoeger@osinski.com', 'annalise.walter', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2006-07-16', '8301 Andres Gateway\nSchultzberg, CA 92145-6774', '+14136738104', '75-8944039', 'Mechanical Engineer', 'Williamson, Tromp and Fahey', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1981-10-22', '1988-06-20', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(48, 'Tyler White Jr.', 'kiehn.moshe@gmail.com', 'ruthe.marks', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2015-12-13', '6701 German Island\nDemarcusstad, NY 84936', '1-340-475-3502 x49578', '21-3459615', 'Lawn Service Manager', 'Cormier Inc', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1973-08-07', '1994-11-24', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(49, 'Mr. Jose Batz IV', 'grace70@hotmail.com', 'robin.rohan', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2005-02-13', '5937 Hallie Parkway\nNew Krystelmouth, MO 21536-2741', '1-930-631-4873 x768', '25-6467808', 'Motorboat Mechanic', 'Kirlin, Rodriguez and Schultz', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1987-06-15', '1977-04-26', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(50, 'Timmy Rice', 'ksenger@boyle.org', 'janessa.thiel', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1986-10-23', '50813 Joelle Rapid\nKerlukeland, IA 73827-3052', '267-274-9411 x413', '44-8956912', 'Outdoor Power Equipment Mechanic', 'Strosin PLC', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2009-03-24', '1983-09-07', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(51, 'Prof. Lewis Little', 'ali.cronin@cruickshank.com', 'vwilkinson', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1976-06-29', '4884 Amparo Fords Apt. 533\nWilkinsonland, NC 42756', '629-658-9964 x548', '75-4008021', 'Forensic Investigator', 'Treutel-Herzog', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1979-07-20', '2007-11-06', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(52, 'Armando Hermiston', 'bednar.kristy@hane.com', 'keely.auer', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1987-04-13', '5864 Gleason Fall\nTillmanport, GA 12442-1864', '1-983-837-0957 x25220', '72-9932423', 'Animal Scientist', 'Wunsch and Sons', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1992-03-04', '1992-09-15', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(53, 'Jeffrey Borer II', 'fhartmann@murazik.com', 'larson.crystel', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1977-11-14', '3888 Kassulke Terrace Suite 213\nWest Sophiechester, VA 35345', '979.625.0132 x338', '72-1073911', 'Personnel Recruiter', 'Conroy-Will', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2010-05-31', '1970-04-14', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(54, 'Theresa Kuphal MD', 'keebler.gay@hotmail.com', 'yferry', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2003-01-24', '7948 Bria Greens Apt. 255\nReillybury, SC 84628-9567', '1-286-635-5845 x3871', '74-1105652', 'Machine Feeder', 'Smith-Stokes', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1972-05-07', '1990-01-23', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(55, 'Eloise Barrows', 'terence08@yahoo.com', 'faye09', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2011-09-15', '527 Milton Flat\nLemkebury, ID 59787-1487', '893-240-5608 x35502', '61-1139594', 'Merchandise Displayer OR Window Trimmer', 'Stroman Inc', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1973-04-21', '2000-04-17', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(56, 'Jamar Kessler', 'teresa53@emmerich.net', 'jklein', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1997-12-08', '8402 Metz Bridge\nSabrynashire, WA 27628-3244', '568.624.5871 x2452', '44-5460571', 'Railroad Inspector', 'Mueller-Harber', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1973-12-01', '1990-10-11', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(57, 'Russel Hagenes IV', 'ablock@zboncak.com', 'joan.dooley', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1988-01-19', '274 Sharon Oval Suite 606\nEast Lucinda, RI 64705-3420', '992-353-3643', '81-5576223', 'Forestry Conservation Science Teacher', 'Keeling Group', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2007-12-26', '2018-04-29', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(58, 'Art Rutherford', 'hpowlowski@yahoo.com', 'tia01', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1988-08-24', '37913 Conroy Inlet\nAufderharport, VT 77436', '1-404-674-6612 x520', '22-7102634', 'Cook', 'Predovic-Heathcote', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2014-11-24', '2016-10-14', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(59, 'Jamal Batz', 'daisy16@vonrueden.com', 'dixie23', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2017-07-10', '262 Delilah Forest\nNorth Alessandro, CT 84949-0126', '310-890-6766', '31-7221359', 'Psychiatric Technician', 'Macejkovic LLC', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1997-12-22', '1993-03-17', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(60, 'Julie Ondricka', 'winston.hane@champlin.info', 'jovan.bartoletti', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2019-02-22', '75040 Jordy Brooks Apt. 824\nSouth Adrainfurt, WV 97651', '(504) 619-0861', '35-5634986', 'Aerospace Engineer', 'Boyle-Volkman', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1992-05-17', '1998-10-20', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(61, 'Sophie Lebsack', 'tavares90@lesch.info', 'friedrich.corkery', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1982-10-26', '6041 Turcotte Street\nPort Danyka, NC 46894-6673', '591-891-9235 x2504', '82-5785241', 'Ship Carpenter and Joiner', 'Abbott Ltd', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1991-04-15', '1990-02-22', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(62, 'Ervin Beahan MD', 'cathrine.cummings@steuber.biz', 'yasmin35', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1995-06-02', '58645 Crona Summit Apt. 682\nDanamouth, RI 15574', '550.573.2369', '20-5529099', 'Fitter', 'Cartwright, Mayert and Cormier', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1974-02-11', '1991-10-10', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(63, 'Helen Ziemann Sr.', 'gerlach.margarett@kuhic.com', 'bartholome01', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1996-09-13', '1811 Branson Glen\nEast Marcelinaton, ME 52642', '1-617-825-2395 x30470', '83-0818475', 'Grinder OR Polisher', 'Adams-Shanahan', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2004-01-03', '1987-10-26', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:38', '2020-06-03 04:55:38'),
(64, 'Henri Morissette', 'antwan18@hotmail.com', 'charlie.rohan', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2019-06-02', '634 Ayana Courts\nPrincessport, NV 82479-3729', '1-669-926-2382 x539', '43-2259034', 'City', 'White-Crona', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2002-09-01', '2019-07-09', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(65, 'Roberta Kulas', 'hmraz@gmail.com', 'darien.leffler', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2015-03-19', '50348 Donato Lakes Suite 770\nWest Tyshawn, WA 96102', '1-540-794-2476', '02-3562024', 'Umpire and Referee', 'Kuphal-Kassulke', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1988-01-26', '2010-06-05', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(66, 'Fletcher Daniel', 'lucinda.kiehn@hotmail.com', 'polly30', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1990-11-24', '877 Donnell Pass Apt. 057\nEast Jennings, NV 73980-0985', '1-523-316-5611 x11534', '59-0080118', 'Agricultural Crop Farm Manager', 'Rolfson and Sons', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1974-10-16', '2017-08-18', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(67, 'Efrain Koelpin V', 'hdaugherty@gmail.com', 'schaden.lauren', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2018-01-23', '13077 Wuckert Groves\nPort Bomouth, CO 77924-5771', '(432) 286-7879 x5242', '86-2914697', 'Art Director', 'Conn and Sons', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2008-09-15', '1980-03-17', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(68, 'Mr. Ferne Harris', 'junius.dach@barton.info', 'akovacek', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1994-10-05', '4733 Gleason Ferry Suite 069\nWest Michealborough, IA 21354-1595', '(353) 330-6827 x5351', '22-0784875', 'Gaming Surveillance Officer', 'Wisozk-Spencer', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1975-03-23', '1984-10-21', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(69, 'Anais Beer', 'hparisian@gmail.com', 'kailey.ward', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2002-01-11', '1984 Douglas Lock Apt. 627\nNew Maxineland, PA 43667', '553.377.9321', '47-1462245', 'Transportation Manager', 'Bechtelar Ltd', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1975-09-12', '2015-09-07', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(70, 'Heather Boyle', 'kmccullough@gmail.com', 'buckridge.taurean', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1990-07-11', '8468 Padberg Shores Suite 090\nFritschburgh, NM 90114-9927', '842-783-7462', '05-0152383', 'Purchasing Manager', 'Monahan-Haag', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2019-01-08', '2006-07-05', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(71, 'Nayeli Senger DVM', 'oshanahan@eichmann.info', 'towne.antwon', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2018-07-25', '15065 Eden Canyon Apt. 885\nRoobchester, AL 68488', '(228) 667-4691 x391', '33-7586746', 'Cost Estimator', 'Beatty, Reinger and Botsford', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2019-02-13', '2013-08-05', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(72, 'Prof. Misael Braun Jr.', 'edgar50@kiehn.com', 'turner.lukas', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2012-07-11', '111 Jackie Road\nSamville, MA 39515-4101', '(505) 601-0086 x341', '23-2293513', 'Bindery Machine Operator', 'Schimmel, Schulist and Gibson', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1972-10-23', '2008-06-02', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(73, 'Filiberto Marks', 'rromaguera@gmail.com', 'greynolds', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2002-12-28', '1522 Champlin Neck\nNew Leola, AL 12172', '1-398-530-6403', '33-3129713', 'Forest Fire Fighter', 'McCullough Inc', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1996-12-17', '1981-02-27', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(74, 'Jolie Boehm', 'davis.carrie@zieme.biz', 'noe.heidenreich', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1971-01-04', '32724 Kiehn Corners Apt. 774\nNew Amelie, WI 19056', '(324) 458-3514 x9741', '26-2464165', 'Reservation Agent OR Transportation Ticket Agent', 'Waelchi Group', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1972-10-28', '1976-07-18', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(75, 'Dr. Sydney Kuphal MD', 'weber.max@douglas.com', 'macy.olson', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2000-10-19', '252 Octavia Rue\nEast Carleeport, NH 43534-1733', '841-548-4735', '60-6289834', 'Hazardous Materials Removal Worker', 'Brown-Balistreri', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2001-03-08', '1986-10-24', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(76, 'Prof. Imani Rippin PhD', 'mollie.mccullough@hotmail.com', 'stokes.jermain', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2016-07-24', '369 Margarita Tunnel Suite 583\nNew Reilly, MA 77585-5428', '865-767-7881 x28517', '58-9316005', 'Database Administrator', 'Rutherford-Tromp', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2005-03-15', '1975-01-03', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(77, 'Mr. Kennedi Huel I', 'germaine.bednar@nolan.com', 'jonathon84', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2001-12-14', '884 Justine Brook Apt. 856\nWest Desmondmouth, WV 34070-0644', '879.798.8102 x03891', '75-8376861', 'Range Manager', 'Osinski-Wunsch', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1982-09-11', '2014-09-29', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(78, 'Lyda Ondricka', 'quigley.eladio@volkman.com', 'kunze.leif', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1992-01-19', '6217 Maribel Shores\nEast Josephine, IN 94164', '1-278-309-2033', '14-8474392', 'Locker Room Attendant', 'Murazik PLC', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2000-11-05', '2003-07-01', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(79, 'Ernestina Hoeger', 'bonnie50@cormier.com', 'icrooks', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2012-03-23', '19034 Makayla Crescent Suite 945\nLake Annette, CA 00174', '+1 (471) 446-6158', '39-0431313', 'Life Science Technician', 'Stracke and Sons', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2004-06-02', '2019-11-18', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(80, 'Velva Veum', 'maritza.witting@hotmail.com', 'hettinger.torrey', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2001-12-22', '452 Maggio Circle Apt. 699\nEast Amaliaview, MI 54210', '(293) 866-7947 x69860', '22-0185397', 'City Planning Aide', 'Ritchie Group', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2015-03-27', '1978-01-29', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(81, 'Rodrigo Bergnaum', 'ksawayn@terry.com', 'josefa48', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2013-11-03', '51083 Arlene Wells Apt. 404\nNorth Frederick, MA 32051', '808-363-1112', '48-0605351', 'Market Research Analyst', 'Swift Inc', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2015-03-19', '2005-07-05', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:39', '2020-06-03 04:55:39'),
(82, 'Kristopher Vandervort', 'wuckert.peggie@yahoo.com', 'stiedemann.berenice', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1987-08-04', '9856 Lind Coves Apt. 747\nMohrland, GA 66026', '+1-645-422-3008', '03-3336810', 'Surgical Technologist', 'Luettgen LLC', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2019-08-02', '1971-03-06', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:40', '2020-06-03 04:55:40'),
(83, 'Jedidiah Rutherford', 'jolie.bernhard@boehm.com', 'nleffler', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1972-02-28', '270 Brakus Highway Suite 459\nPort Virgil, HI 65957', '418-697-9024 x750', '16-2803443', 'Cook', 'Leuschke, Huels and Rohan', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2005-10-13', '1982-01-17', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:40', '2020-06-03 04:55:40'),
(84, 'Amy Jacobs', 'hayden02@hotmail.com', 'claud72', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1994-05-18', '69779 Collins Bridge\nNorth Uniquemouth, NM 92994', '1-283-259-0653', '83-0151902', 'Computer Hardware Engineer', 'Nitzsche, White and Schuster', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2015-09-13', '1980-08-09', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:40', '2020-06-03 04:55:40'),
(85, 'Jayson Mueller', 'maryam.lind@gmail.com', 'feil.tressie', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1972-03-23', '8484 Hegmann Pass\nWinnifredfort, SC 40473-3472', '+1-859-892-6002', '22-8942286', 'City', 'Kunde Ltd', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2020-06-02', '1999-04-06', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:40', '2020-06-03 04:55:40'),
(86, 'Tomasa Herzog', 'waldo.corkery@gmail.com', 'deron.rau', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1994-03-30', '16263 Johnathan Skyway Suite 712\nHilperttown, MS 68835-6775', '964.466.8405 x725', '15-8256435', 'Pipelaying Fitter', 'Lesch, Tillman and Schimmel', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1977-08-20', '1998-03-21', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:40', '2020-06-03 04:55:40'),
(87, 'Evans Stamm', 'werner.littel@ankunding.com', 'quigley.donavon', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1999-05-17', '10590 Estel Mount Apt. 239\nHaleytown, DE 92679', '1-287-615-2664 x72112', '30-6268486', 'Artist', 'Barrows, Morissette and Schiller', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1970-04-08', '1984-02-26', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:40', '2020-06-03 04:55:40'),
(88, 'Ruth VonRueden I', 'larue.upton@gerlach.com', 'jschaefer', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1983-04-10', '40152 Funk Cliffs\nDarrelton, IN 56324-2488', '(801) 293-1293 x54353', '80-4259368', 'Gaming Manager', 'Bogisich-Brakus', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2014-07-20', '1978-07-19', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:40', '2020-06-03 04:55:40'),
(89, 'Mr. Aiden Hegmann Sr.', 'lou62@konopelski.com', 'columbus18', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1998-01-31', '671 Predovic Trace Suite 101\nLake Edythland, UT 25413', '+1.479.563.4242', '65-9732932', 'Cartoonist', 'Ankunding-Cassin', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1990-07-17', '1972-12-31', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:40', '2020-06-03 04:55:40'),
(90, 'Arielle Bayer', 'pauline.nader@mohr.com', 'gregoria58', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2008-04-10', '66365 Helena Lakes\nNew Leda, CA 91187-0652', '+16014800718', '26-9268259', 'Agricultural Manager', 'Flatley-Hodkiewicz', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1976-02-20', '1990-12-06', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:40', '2020-06-03 04:55:40'),
(91, 'Miss Stephania Cremin IV', 'hammes.nash@goodwin.org', 'ludwig.ullrich', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1980-12-06', '7189 Alexandria Prairie Apt. 504\nOttilieshire, SC 69094-7313', '(227) 339-5280 x376', '65-9932770', 'Industrial-Organizational Psychologist', 'Schuster-Bednar', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1976-03-27', '1976-08-13', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:40', '2020-06-03 04:55:40'),
(92, 'Leif Jast', 'winnifred.konopelski@lindgren.biz', 'kelly.ratke', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2009-09-22', '71044 Gulgowski Station\nJesuschester, WI 49587-2996', '(257) 423-5025 x455', '13-1386555', 'Electric Meter Installer', 'Pollich Ltd', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2015-02-05', '2002-09-23', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:40', '2020-06-03 04:55:40');
INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `date_of_birth`, `address`, `mobile_number`, `employee_identification`, `position`, `department`, `work_status`, `project`, `location`, `start_date`, `end_date`, `rate_per_hour`, `night_differencial`, `salary_amount`, `sss_contribution`, `philhealh`, `pag_ibig`, `tax_withheld`, `benefits`, `other_benefits`, `biometric_id`, `biometric`, `role`, `status`, `email_verified_at`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(93, 'Dr. Dominic Spencer DDS', 'jordane.lockman@hotmail.com', 'roxanne10', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1979-04-22', '79401 Malcolm Views\nPrestonbury, ND 65588', '1-871-978-5656 x44668', '42-6076432', 'Production Helper', 'Gulgowski Group', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2016-01-15', '1978-05-21', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:40', '2020-06-03 04:55:40'),
(94, 'Mrs. Beatrice Ratke MD', 'hartmann.emmie@yahoo.com', 'guadalupe26', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1997-11-20', '4777 Marley Shore Suite 437\nConntown, CO 00658', '584-366-4758 x8006', '22-3005601', 'Auditor', 'Kub, Zulauf and Schuster', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1974-11-07', '2019-11-11', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:40', '2020-06-03 04:55:40'),
(95, 'Johan Collins', 'ward.dagmar@wunsch.net', 'colt21', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2005-09-18', '63244 Westley Knoll\nSouth Marjolaine, IL 63176-3400', '983.986.9532 x149', '63-8355813', 'Architecture Teacher', 'Roob, Kub and Brekke', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1992-11-29', '2003-01-11', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:40', '2020-06-03 04:55:40'),
(96, 'Katelin Ondricka V', 'fdaugherty@yahoo.com', 'idell.streich', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1978-05-22', '5999 Hugh Greens Suite 497\nElwynstad, IL 85131', '1-907-739-0335 x36263', '05-4993012', 'Metal Molding Operator', 'Willms, Rolfson and Kerluke', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2015-06-05', '2006-06-08', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(97, 'Audrey Conroy DDS', 'flind@gmail.com', 'abbigail.nolan', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1986-01-31', '26104 Reinhold Rapids Suite 971\nNew Yolanda, ME 36539', '1-483-809-6607', '57-5590116', 'Potter', 'Wunsch, Wisoky and Bogisich', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1978-04-12', '2015-08-28', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(98, 'Nellie Schulist V', 'elnora.heller@gmail.com', 'albertha.kiehn', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2006-10-04', '8733 Abelardo Shores Suite 513\nSterlingside, WY 43559-2128', '546-345-7546 x407', '53-5943875', 'Camera Repairer', 'Feil-Wuckert', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2008-09-19', '1975-03-21', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(99, 'Ms. Marguerite Swift', 'simonis.sidney@yahoo.com', 'vkutch', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2008-11-04', '75469 Lilliana Street\nLaneyshire, OK 32374-8244', '217-229-7342 x51649', '68-9164004', 'Structural Metal Fabricator', 'Smitham, Adams and Deckow', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2010-09-09', '2013-01-15', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(100, 'Ludie Spencer Sr.', 'stella.brekke@gmail.com', 'nona22', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1977-11-18', '496 Kshlerin Harbors Suite 628\nPort Shanonbury, IL 50759', '202.929.8188 x536', '22-1214224', 'Engineering Technician', 'Mitchell, Pouros and Yost', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2007-08-20', '1972-04-01', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(101, 'Tony Will', 'thompson.leanna@rodriguez.biz', 'beier.grant', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1980-08-31', '448 Hortense Tunnel Apt. 316\nEast Bridgetside, MA 29823', '356.843.1661 x9891', '22-8949158', 'Welding Machine Operator', 'Stracke-Langworth', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2000-07-26', '1998-08-20', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(102, 'Angel Berge', 'norberto.carroll@yahoo.com', 'phickle', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2012-06-27', '41651 Alexandrea Mall\nQuitzonside, AK 65826', '+1.971.357.2892', '41-3742449', 'Civil Drafter', 'Kunze, Harber and Beier', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1994-03-31', '1986-03-19', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(103, 'Jarvis Bayer', 'cstark@hauck.com', 'carolina38', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1988-02-22', '19159 Rodger Loaf\nStacystad, MI 95356-3229', '+1-789-618-9281', '57-7735452', 'Molding and Casting Worker', 'Nader and Sons', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1999-08-29', '1989-03-21', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(104, 'Francisca Daugherty DVM', 'ransom.koss@hotmail.com', 'tcollins', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2019-02-18', '397 Dorris Groves\nEast Dandre, DE 26451', '(210) 783-4686', '59-8668051', 'Postal Service Clerk', 'Jakubowski, Wintheiser and Hackett', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1998-02-10', '2006-11-28', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(105, 'Ms. Johanna Hessel II', 'dglover@gibson.net', 'hgutmann', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2005-08-11', '400 Lebsack Views Apt. 960\nLillyville, CT 90028-0594', '656-229-6489 x742', '35-5898570', 'Real Estate Appraiser', 'Hane Ltd', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1973-03-27', '1978-03-07', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(106, 'Prof. Dane D\'Amore', 'samara47@towne.biz', 'hpfeffer', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1997-04-05', '1474 Raynor Islands\nPort Jaymeburgh, TX 38639', '862-532-0743 x52743', '77-9457648', 'Drilling and Boring Machine Tool Setter', 'Zboncak, Ward and Rogahn', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1990-03-14', '1988-06-17', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(107, 'Russ Glover', 'lesly.bernier@ziemann.com', 'qjohns', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1997-06-08', '8421 Kelly Mission Apt. 512\nKarichester, AK 82515-6824', '729.374.6606', '24-6540861', 'Pewter Caster', 'Huel Ltd', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1983-01-27', '1996-02-14', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(108, 'Nina O\'Kon', 'simone.kirlin@heathcote.info', 'ffeest', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2002-07-09', '366 Wunsch Creek Apt. 552\nWest Minervafurt, ND 26979-5758', '1-674-477-5743', '47-9918890', 'Lay-Out Worker', 'Hackett, Kozey and Breitenberg', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2004-12-12', '1978-02-08', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(109, 'Malinda Okuneva', 'dorthy26@gmail.com', 'winnifred97', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2011-07-13', '399 Mervin Stravenue Apt. 961\nWisokytown, ID 37190', '256.904.6375 x1146', '66-8128309', 'Middle School Teacher', 'Effertz Ltd', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2003-03-14', '2002-07-17', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(110, 'Prof. Weston Breitenberg', 'torphy.jacques@yahoo.com', 'bheidenreich', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2017-09-09', '978 Myrtie Burg\nNew Hazelport, NJ 04503', '770.814.8817', '72-1121923', 'Economics Teacher', 'Kuhn, Goyette and Stamm', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1983-07-27', '1996-10-16', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(111, 'Linda Gutmann', 'kub.danyka@yahoo.com', 'bernhard.francis', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1992-05-23', '24979 Konopelski Valley Suite 235\nMattside, MS 31393-2225', '+1-425-334-3653', '60-3379755', 'Brake Machine Setter', 'Gleichner-McCullough', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2002-05-04', '1987-01-01', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(112, 'Heaven Keeling', 'floy.lueilwitz@shanahan.com', 'reid05', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1996-05-10', '63087 Lonzo Unions\nNorth Mitchellberg, MD 84255-0123', '+18236777329', '85-4590731', 'Travel Agent', 'Bergnaum Ltd', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2001-12-07', '1991-03-02', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(113, 'Prof. Mya Gaylord', 'joaquin25@fisher.net', 'russel.sandrine', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1973-01-01', '71256 Charlene Brooks\nSouth Elouise, IL 20552-7573', '(987) 941-4855', '81-0731776', 'Mining Engineer OR Geological Engineer', 'Stracke and Sons', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1976-03-29', '2002-09-05', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:41', '2020-06-03 04:55:41'),
(114, 'Benedict Pfeffer', 'aron48@hotmail.com', 'bernhard96', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1976-11-27', '54875 Lindsey Pike Suite 151\nWest Ralph, NJ 60195-7919', '342-795-8107', '06-3349522', 'Highway Patrol Pilot', 'Schroeder, Casper and Berge', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1994-09-30', '1979-12-24', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:42', '2020-06-03 04:55:42'),
(115, 'Mrs. Leila Fritsch', 'volkman.cassidy@okeefe.com', 'norene31', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1988-12-29', '4839 Mckenna View Suite 682\nEast Maximilianland, WV 43928', '1-212-357-7227', '98-3645636', 'Substance Abuse Counselor', 'Gulgowski Ltd', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2018-08-05', '2007-08-28', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:42', '2020-06-03 04:55:42'),
(116, 'Oren Harber', 'ehomenick@gmail.com', 'ecarter', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1993-11-10', '3344 Damion Camp Apt. 016\nFeestchester, CT 72035', '+12594019261', '42-4218062', 'Criminal Investigator', 'Larkin, Hamill and Kohler', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2017-09-02', '1992-07-20', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:42', '2020-06-03 04:55:42'),
(117, 'Marlee Mante', 'jesse.kuhn@bosco.info', 'athena92', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1976-11-28', '3378 Estell Wall Suite 183\nPort Lizamouth, OH 43811-1087', '1-497-544-2244 x1658', '05-4698954', 'Kindergarten Teacher', 'Lebsack and Sons', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1989-07-31', '1999-10-05', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:42', '2020-06-03 04:55:42'),
(118, 'Julien Schulist', 'caesar89@yahoo.com', 'ortiz.santa', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1973-08-01', '63184 Towne Plaza\nClayton, NV 04022-6771', '1-885-979-6401 x236', '45-4105091', 'Tire Builder', 'Little-Gislason', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1988-04-16', '2017-08-16', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:42', '2020-06-03 04:55:42'),
(119, 'Cassie Grimes', 'myrl.reinger@gmail.com', 'qmayer', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1976-08-26', '61129 Antone Plains Suite 749\nNew Taylor, VA 67269-8922', '485.357.3822', '31-5893626', 'Computer Repairer', 'Prohaska-Nitzsche', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1993-03-31', '2020-02-01', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:42', '2020-06-03 04:55:42'),
(120, 'Julio Ondricka', 'jakob45@gmail.com', 'hilpert.thea', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '2010-10-17', '128 Bernhard Spur\nEast Maynard, MT 53986', '1-795-639-8029 x211', '72-7800616', 'Bookbinder', 'Gerlach, Sporer and Gottlieb', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '1989-08-04', '1987-09-22', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:42', '2020-06-03 04:55:42'),
(121, 'Rhianna Anderson', 'florida.von@yahoo.com', 'joel.stamm', '$2y$10$oPW1.rcmWz2HtN.gLLQXZOoWyuwglH0PCeBgN5TIhE2KDIqWPvXpu', '1991-06-10', '6949 Baumbach Pine\nSouth Samir, TN 73220', '756.683.6564 x533', '60-6215842', 'Computer Software Engineer', 'Howe, Parisian and Lowe', 'Regular Employee', 'Pilipinas Shell Petroleum Corp.', 'Tabangao, Batangas City', '2017-07-15', '1971-12-21', 141.76, 14.18, 24950.40, 800.00, 374.26, 100.00, 823.48, 4050.00, 1274.26, NULL, 1, 0, 1, NULL, NULL, NULL, '2020-06-03 04:55:42', '2020-06-03 04:55:42');

-- --------------------------------------------------------

--
-- Structure for view `payslip`
--
DROP TABLE IF EXISTS `payslip`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `payslip`  AS  select `t2`.`id` AS `user_id`,`t2`.`name` AS `name`,`t2`.`email` AS `email`,`t2`.`username` AS `username`,`t2`.`password` AS `password`,`t2`.`date_of_birth` AS `date_of_birth`,`t2`.`address` AS `address`,`t2`.`mobile_number` AS `mobile_number`,`t2`.`employee_identification` AS `employee_identification`,`t2`.`position` AS `position`,`t2`.`department` AS `department`,`t2`.`project` AS `project`,`t2`.`location` AS `location`,`t2`.`start_date` AS `start_date`,`t2`.`end_date` AS `end_date`,`t2`.`work_status` AS `work_status`,`t2`.`biometric_id` AS `biometric_id`,`t2`.`biometric` AS `biometric`,`t1`.`id` AS `payslip_id`,`t1`.`month_year` AS `month_year`,`t1`.`days` AS `days`,`t1`.`basic_salary` AS `basic_salary`,`t1`.`nights` AS `nights`,`t1`.`night_differencial` AS `night_differencial`,`t1`.`total_basic_salary` AS `total_basic_salary`,`t1`.`overtime` AS `overtime`,`t1`.`benefits` AS `benefits`,`t1`.`other_benefits` AS `other_benefits`,`t1`.`gross_pay` AS `gross_pay`,`t1`.`sss` AS `sss`,`t1`.`philhealth` AS `philhealth`,`t1`.`pag_ibig` AS `pag_ibig`,`t1`.`tax` AS `tax`,`t1`.`tardiness` AS `tardiness`,`t1`.`total_deduction` AS `total_deduction`,`t1`.`net_pay` AS `net_pay` from (`payroll` `t1` left join `users` `t2` on((`t1`.`user_id` = `t2`.`id`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounting_period`
--
ALTER TABLE `accounting_period`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `balance_sheet`
--
ALTER TABLE `balance_sheet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `biologs`
--
ALTER TABLE `biologs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `biometrics`
--
ALTER TABLE `biometrics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_statement`
--
ALTER TABLE `income_statement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_duplicated_checker` (`user_id`,`month_year`);

--
-- Indexes for table `statement_of_changes_in_equity`
--
ALTER TABLE `statement_of_changes_in_equity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trial_balance`
--
ALTER TABLE `trial_balance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounting_period`
--
ALTER TABLE `accounting_period`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `balance_sheet`
--
ALTER TABLE `balance_sheet`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `biologs`
--
ALTER TABLE `biologs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `biometrics`
--
ALTER TABLE `biometrics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `income_statement`
--
ALTER TABLE `income_statement`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;
--
-- AUTO_INCREMENT for table `statement_of_changes_in_equity`
--
ALTER TABLE `statement_of_changes_in_equity`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `trial_balance`
--
ALTER TABLE `trial_balance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
