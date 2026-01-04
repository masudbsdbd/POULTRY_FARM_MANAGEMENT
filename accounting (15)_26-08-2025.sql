-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2025 at 01:44 PM
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
-- Database: `accounting`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `expense_id` int(11) DEFAULT NULL,
  `sell_return_id` int(11) DEFAULT NULL,
  `purchase_return_id` int(11) DEFAULT NULL,
  `damage_id` int(11) DEFAULT NULL,
  `income_id` int(11) DEFAULT NULL,
  `investment_id` int(11) DEFAULT NULL,
  `payable_id` int(11) DEFAULT NULL,
  `receivable_id` int(11) DEFAULT NULL,
  `asset_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL COMMENT 'Purchase = 1, Sell = 2, bank deposit = 3, bank withdraw = 4, Customer Advance = 5, Supplier Advance = 6, Customer Due = 7, Supplier Due = 8, Expense = 9, Sell Return = 10, Purchase Return = 11, Employee Salary = 12, Customer = 13, Supplier = 14, Income = 15, Investment = 16, Receivable = 17, Asset = 18, Damage = 19',
  `balance` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `credit` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `debit` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `description` text DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `entry_type` int(11) NOT NULL DEFAULT 0,
  `payment_method` tinyint(4) NOT NULL COMMENT 'unpaid = 0, cash = 1, bank = 2',
  `entry_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT 'unpaid = 0, debit = 1, credit = 2',
  `entry_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `purchase_id`, `sell_id`, `expense_id`, `sell_return_id`, `purchase_return_id`, `damage_id`, `income_id`, `investment_id`, `payable_id`, `receivable_id`, `asset_id`, `type`, `balance`, `credit`, `debit`, `amount`, `description`, `customer_id`, `supplier_id`, `employee_id`, `entry_type`, `payment_method`, `entry_by`, `status`, `entry_date`, `created_at`, `updated_at`) VALUES
(1, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 16, 100000.00000000, 100000.00000000, 0.00000000, 0.00000000, 'Shakir', 0, 0, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 01:14:15', '2025-08-26 01:14:15'),
(2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 95000.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Payment received of 5000 Tk as Advance from supplier .', 0, 12, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 01:17:30', '2025-08-26 01:17:30'),
(3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 95000.00000000, 0.00000000, 0.00000000, 5000.00000000, 'Sale due to supplier fahruk2, amount: 5000 Tk', 0, 13, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 01:18:30', '2025-08-26 01:18:30'),
(4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 96000.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 1000 Tk from the supplier fahruk', 0, 12, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 01:20:14', '2025-08-26 01:20:14'),
(5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 8, 93000.00000000, 0.00000000, 3000.00000000, 0.00000000, 'paid amount of 3000 tk to supplier fahruk2  for purchase due. ', 0, 13, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 01:20:41', '2025-08-26 01:20:41'),
(6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 96000.00000000, 3000.00000000, 0.00000000, 0.00000000, 'adjust 3000 into cash as invest', 0, 0, 0, 1, 1, 1, 2, '2025-08-26', '2025-08-26 02:17:04', '2025-08-26 02:17:04'),
(7, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 89000.00000000, 0.00000000, 0.00000000, 9000.00000000, 'Purchase(SQ1) Products Form Supplier fahruk', 0, 12, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 02:19:08', '2025-08-26 02:22:07'),
(8, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 89000.00000000, 0.00000000, 7000.00000000, 0.00000000, 'Paid 7,000.00 Tk to Supplier fahruk for Purchase (SQ1).', 0, 12, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 02:19:08', '2025-08-26 02:19:08'),
(9, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 11, 90000.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Purchase return (fahruk) payment of 1000.00 Tk has been successfully paid.', 0, 12, 0, 0, 2, 1, 2, '2025-08-26', '2025-08-26 02:23:54', '2025-08-26 02:23:54'),
(10, 0, 0, 0, 0, 2, 0, 0, 0, 0, 0, 0, 11, 91000.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Purchase return (fahruk) payment of 1000.00 Tk has been successfully paid.', 0, 12, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 02:32:24', '2025-08-26 02:32:24'),
(11, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 0, 11, 92000.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Purchase return (fahruk) payment of 1000.00 Tk has been successfully paid.', 0, 12, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 02:38:24', '2025-08-26 02:38:24'),
(12, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 83000.00000000, 0.00000000, 0.00000000, 9000.00000000, 'Purchase(SQ2) Products Form Supplier fahruk', 0, 12, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 02:44:36', '2025-08-26 02:47:23'),
(13, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 83000.00000000, 0.00000000, 9000.00000000, 0.00000000, 'Paid 9,000.00 Tk to Supplier fahruk for Purchase (SQ2).', 0, 12, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 02:44:36', '2025-08-26 02:44:36'),
(14, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 80000.00000000, 0.00000000, 3000.00000000, 0.00000000, 'Paid advance payment of 3000 Tk to the supplier fahruk', 0, 12, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 02:49:12', '2025-08-26 02:49:12'),
(15, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 76000.00000000, 0.00000000, 0.00000000, 2000.00000000, 'Purchase(SQ3) Products Form Supplier fahruk', 0, 12, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 02:50:22', '2025-08-26 02:51:28'),
(16, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 76000.00000000, 0.00000000, 4000.00000000, 0.00000000, 'Paid 4,000.00 Tk to Supplier fahruk for Purchase (SQ3).', 0, 12, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 02:50:22', '2025-08-26 02:50:22'),
(17, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 72000.00000000, 0.00000000, 0.00000000, 5000.00000000, 'Purchase(SQ4) Products Form Supplier fahruk', 0, 12, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 02:52:09', '2025-08-26 02:53:20'),
(18, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 72000.00000000, 0.00000000, 4000.00000000, 0.00000000, 'Paid 4,000.00 Tk to Supplier fahruk for Purchase (SQ4).', 0, 12, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 02:52:09', '2025-08-26 02:52:09'),
(19, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 69000.00000000, 0.00000000, 0.00000000, 3000.00000000, 'Purchase(SQ5) Products Form Supplier fahruk', 0, 12, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 02:54:10', '2025-08-26 02:54:35'),
(20, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 69000.00000000, 0.00000000, 3000.00000000, 0.00000000, 'Paid 3,000.00 Tk to Supplier fahruk for Purchase (SQ5).', 0, 12, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 02:54:10', '2025-08-26 02:54:10'),
(21, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 64000.00000000, 0.00000000, 0.00000000, 3000.00000000, 'Purchase(SQ6) Products Form Supplier fahruk', 0, 12, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 03:09:55', '2025-08-26 03:12:45'),
(22, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 64000.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier fahruk for Purchase (SQ6).', 0, 12, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 03:09:55', '2025-08-26 03:09:55'),
(23, 7, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 64000.00000000, 0.00000000, 0.00000000, 3000.00000000, 'Purchase(SQ7) Products Form Supplier fahruk', 0, 12, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 03:13:33', '2025-08-26 03:13:33'),
(24, 7, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 62000.00000000, 0.00000000, 2000.00000000, 0.00000000, 'Paid 2,000.00 Tk to Supplier fahruk for Purchase (SQ7).', 0, 12, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 03:13:33', '2025-08-26 03:13:33'),
(25, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 62000.00000000, 0.00000000, 0.00000000, 5000.00000000, 'Purchase(SQ8) Products Form Supplier fahruk', 0, 12, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 03:17:39', '2025-08-26 03:17:39'),
(26, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 59000.00000000, 0.00000000, 3000.00000000, 0.00000000, 'Paid 3,000.00 Tk to Supplier fahruk for Purchase (SQ8).', 0, 12, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 03:17:39', '2025-08-26 03:17:39'),
(27, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 55000.00000000, 0.00000000, 0.00000000, 3000.00000000, 'Purchase(SQ9) Products Form Supplier fahruk', 0, 12, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 03:21:15', '2025-08-26 03:30:55'),
(28, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 55000.00000000, 0.00000000, 4000.00000000, 0.00000000, 'Paid 4,000.00 Tk to Supplier fahruk for Purchase (SQ9).', 0, 12, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 03:21:15', '2025-08-26 03:21:15'),
(29, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 55000.00000000, 0.00000000, 0.00000000, 3000.00000000, 'Purchase(SQ10) Products Form Supplier fahruk', 0, 12, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 03:32:08', '2025-08-26 03:32:08'),
(30, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 53000.00000000, 0.00000000, 2000.00000000, 0.00000000, 'Paid 2,000.00 Tk to Supplier fahruk for Purchase (SQ10).', 0, 12, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 03:32:08', '2025-08-26 03:32:08'),
(31, 11, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 53000.00000000, 0.00000000, 0.00000000, 3000.00000000, 'Purchase(SQ11) Products Form Supplier fahruk', 0, 12, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 03:32:38', '2025-08-26 03:32:38'),
(32, 11, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 51000.00000000, 0.00000000, 2000.00000000, 0.00000000, 'Paid 2,000.00 Tk to Supplier fahruk for Purchase (SQ11).', 0, 12, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 03:32:38', '2025-08-26 03:32:38'),
(33, 12, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 46000.00000000, 0.00000000, 0.00000000, 3000.00000000, 'Purchase(SQ12) Products Form Supplier fahruk', 0, 12, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 03:35:58', '2025-08-26 03:39:47'),
(34, 12, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 46000.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier fahruk for Purchase (SQ12).', 0, 12, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 03:35:58', '2025-08-26 03:35:58'),
(35, 13, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 46000.00000000, 0.00000000, 0.00000000, 4000.00000000, 'Purchase(SQ13) Products Form Supplier fahruk', 0, 12, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 03:40:43', '2025-08-26 03:45:13'),
(36, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 41000.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Payment received of 5000 Tk as Advance from supplier .', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 03:53:02', '2025-08-26 03:53:02'),
(37, 14, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 34000.00000000, 0.00000000, 0.00000000, 10000.00000000, 'Purchase(SQ14) Products Form Supplier tonni', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 03:53:47', '2025-08-26 04:04:08'),
(38, 14, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 36000.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ14).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 03:53:47', '2025-08-26 03:53:47'),
(39, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 34000.00000000, 0.00000000, 2000.00000000, 0.00000000, 'Paid advance payment of 2000 Tk to the supplier tonni', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 04:00:49', '2025-08-26 04:00:49'),
(40, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 36000.00000000, 2000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 2000 Tk from the supplier tonni', 0, 14, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 04:14:50', '2025-08-26 04:14:50'),
(41, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 26000.00000000, 0.00000000, 0.00000000, 9000.00000000, 'Purchase(SQ15) Products Form Supplier tonni', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 04:15:19', '2025-08-26 04:19:44'),
(42, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 26000.00000000, 0.00000000, 10000.00000000, 0.00000000, 'Paid 10,000.00 Tk to Supplier tonni for Purchase (SQ15).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 04:15:19', '2025-08-26 04:15:19'),
(43, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 27000.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 1000 Tk from the supplier tonni', 0, 14, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 04:23:27', '2025-08-26 04:23:27'),
(44, 16, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 22000.00000000, 0.00000000, 0.00000000, 4000.00000000, 'Purchase(SQ16) Products Form Supplier tonni', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 04:23:57', '2025-08-26 04:24:31'),
(45, 16, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 22000.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ16).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 04:23:57', '2025-08-26 04:23:57'),
(46, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 21000.00000000, 0.00000000, 1000.00000000, 0.00000000, 'Paid advance payment of 1000 Tk to the supplier tonni', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 04:24:52', '2025-08-26 04:24:52'),
(47, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 23000.00000000, 2000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 2000 Tk from the supplier tonni', 0, 14, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 04:24:59', '2025-08-26 04:24:59'),
(48, 17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 18000.00000000, 0.00000000, 0.00000000, 4000.00000000, 'Purchase(SQ17) Products Form Supplier tonni', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 04:25:42', '2025-08-26 04:26:30'),
(49, 17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 18000.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ17).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 04:25:42', '2025-08-26 04:25:42'),
(50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 19000.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 1000 Tk from the supplier tonni', 0, 14, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 04:33:15', '2025-08-26 04:33:15'),
(51, 18, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 14000.00000000, 0.00000000, 0.00000000, 4000.00000000, 'Purchase(SQ18) Products Form Supplier tonni', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 04:33:34', '2025-08-26 04:35:16'),
(52, 18, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 14000.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ18).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 04:33:34', '2025-08-26 04:33:34'),
(53, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 15000.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 1000 Tk from the supplier tonni', 0, 14, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 04:36:00', '2025-08-26 04:36:00'),
(54, 19, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 10000.00000000, 0.00000000, 0.00000000, 6000.00000000, 'Purchase(SQ19) Products Form Supplier tonni with a due of 1000 Tk', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 04:36:19', '2025-08-26 04:36:29'),
(55, 19, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 10000.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ19).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 04:36:19', '2025-08-26 04:36:19'),
(56, 19, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 8, 9000.00000000, 0.00000000, 1000.00000000, 0.00000000, 'Parchase due amount of 1000 Tk paid to the supplier tonni for previous Purchase (purchase-invoice-19)', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 04:41:36', '2025-08-26 04:41:36'),
(57, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 10000.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 1000 Tk from the supplier tonni', 0, 14, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 04:41:42', '2025-08-26 04:41:42'),
(58, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0.00000000, 0.00000000, 0.00000000, 6000.00000000, 'Purchase(SQ20) Products Form Supplier tonni with a due of 1000 Tk', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 04:42:24', '2025-08-26 04:42:31'),
(59, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 5000.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ20).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 04:42:24', '2025-08-26 04:42:24'),
(60, 21, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 5000.00000000, 0.00000000, 0.00000000, 5000.00000000, 'Purchase(SQ21) Products Form Supplier tonni', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 04:42:24', '2025-08-26 04:42:24'),
(61, 21, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ21).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 04:42:24', '2025-08-26 04:42:24'),
(62, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 1000.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 1000 Tk from the supplier tonni', 0, 14, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 04:46:13', '2025-08-26 04:46:13'),
(63, 22, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, -4000.00000000, 0.00000000, 0.00000000, 6000.00000000, 'Purchase(SQ22) Products Form Supplier tonni with a due of 1000 Tk', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 04:47:03', '2025-08-26 04:47:10'),
(64, 22, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, -4000.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ22).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 04:47:03', '2025-08-26 04:47:03'),
(65, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, -3000.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 1000 Tk from the supplier tonni', 0, 14, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 04:51:07', '2025-08-26 04:51:07'),
(66, 23, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, -8000.00000000, 0.00000000, 0.00000000, 6000.00000000, 'Purchase(SQ23) Products Form Supplier tonni with a due of 1000 Tk', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 04:52:12', '2025-08-26 04:52:18'),
(67, 23, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, -8000.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ23).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 04:52:12', '2025-08-26 04:52:12'),
(68, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, -6500.00000000, 1500.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 1500 Tk from the supplier tonni', 0, 14, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 04:56:55', '2025-08-26 04:56:55'),
(69, 24, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, -11500.00000000, 0.00000000, 0.00000000, 4000.00000000, 'Purchase(SQ24) Products Form Supplier tonni', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 04:57:18', '2025-08-26 04:57:39'),
(70, 24, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, -11500.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ24).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 04:57:18', '2025-08-26 04:57:18'),
(71, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, -10500.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 1000 Tk from the supplier tonni', 0, 14, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 04:59:48', '2025-08-26 04:59:48'),
(72, 25, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, -15500.00000000, 0.00000000, 0.00000000, 4000.00000000, 'Purchase(SQ25) Products Form Supplier tonni', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 05:00:08', '2025-08-26 05:00:48'),
(73, 25, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, -15500.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ25).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 05:00:08', '2025-08-26 05:00:08'),
(74, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, -14500.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 1000 Tk from the supplier tonni', 0, 14, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 05:01:40', '2025-08-26 05:01:40'),
(75, 26, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, -19500.00000000, 0.00000000, 0.00000000, 4000.00000000, 'Purchase(SQ26) Products Form Supplier tonni', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 05:02:51', '2025-08-26 05:06:09'),
(76, 26, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, -19500.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ26).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 05:02:51', '2025-08-26 05:02:51'),
(77, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, -18500.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 1000 Tk from the supplier tonni', 0, 14, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 05:07:44', '2025-08-26 05:07:44'),
(78, 27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, -23500.00000000, 0.00000000, 0.00000000, 4000.00000000, 'Purchase(SQ27) Products Form Supplier tonni', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 05:08:17', '2025-08-26 05:26:41'),
(79, 27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, -23500.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ27).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 05:08:17', '2025-08-26 05:08:17'),
(80, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, -22500.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 1000 Tk from the supplier tonni', 0, 14, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 05:27:28', '2025-08-26 05:27:28'),
(81, 28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, -27500.00000000, 0.00000000, 0.00000000, 4000.00000000, 'Purchase(SQ28) Products Form Supplier tonni', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 05:28:42', '2025-08-26 05:33:08'),
(82, 28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, -27500.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ28).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 05:28:42', '2025-08-26 05:28:42'),
(83, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, -26500.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 1000 Tk from the supplier tonni', 0, 14, 0, 0, 1, 1, 2, '2025-08-26', '2025-08-26 05:35:44', '2025-08-26 05:35:44'),
(84, 29, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, -26500.00000000, 0.00000000, 0.00000000, 5000.00000000, 'Purchase(SQ29) Products Form Supplier tonni', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 05:36:11', '2025-08-26 05:36:11'),
(85, 29, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, -31500.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ29).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 05:36:11', '2025-08-26 05:36:11'),
(86, 30, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, -36500.00000000, 0.00000000, 0.00000000, 3000.00000000, 'Purchase(SQ30) Products Form Supplier tonni', 0, 14, 0, 0, 0, 1, 0, '2025-08-26', '2025-08-26 05:37:06', '2025-08-26 05:38:08'),
(87, 30, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, -36500.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid 5,000.00 Tk to Supplier tonni for Purchase (SQ30).', 0, 14, 0, 0, 1, 1, 1, '2025-08-26', '2025-08-26 05:37:06', '2025-08-26 05:37:06');

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `asset_head_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `type` int(11) NOT NULL DEFAULT 0,
  `debit_or_credit` varchar(255) DEFAULT NULL,
  `effective_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `entry_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_heads`
--

CREATE TABLE `asset_heads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_heads`
--

INSERT INTO `asset_heads` (`id`, `name`, `description`, `type`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Long-term investments', 'Long-term Assets', 1, 0, '2025-06-27 21:48:08', '2025-06-27 21:48:08'),
(2, 'Property, plant and equipment', 'Long-term Assets', 1, 0, '2025-06-27 21:48:08', '2025-06-27 21:48:08');

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_no` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `balance` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `entry_by` int(11) NOT NULL,
  `entry_date` datetime NOT NULL,
  `update_by` int(11) DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = Not Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `account_name`, `account_no`, `bank_name`, `branch_name`, `balance`, `entry_by`, `entry_date`, `update_by`, `last_update`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Tanvir Hossain Sadi', '2244', 'Islami Bank Limited (IBL)', 'Nikunja, Branch', 1000.00000000, 1, '2025-06-28 04:39:18', NULL, NULL, 1, 0, '2025-06-27 22:39:18', '2025-08-26 02:23:54');

-- --------------------------------------------------------

--
-- Table structure for table `bank_transactions`
--

CREATE TABLE `bank_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `check_no` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `credit` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `debit` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `balance` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `withdrawer_name` varchar(255) DEFAULT NULL,
  `depositor_name` varchar(255) DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `entry_date` datetime DEFAULT NULL,
  `expense_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL COMMENT 'debit = 1, credit = 2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_transactions`
--

INSERT INTO `bank_transactions` (`id`, `account_id`, `bank_id`, `check_no`, `description`, `credit`, `debit`, `balance`, `withdrawer_name`, `depositor_name`, `entry_by`, `entry_date`, `expense_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 9, 1, '9566565', 'Company return product to Supplierfahruk Total amount 1000.00', 1000.00000000, 0.00000000, 1000.00000000, '', 'tanvir', 1, '2025-08-26 08:23:54', 0, 2, '2025-08-26 02:23:54', '2025-08-26 02:23:54');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Zara', 1, 0, '2025-08-26 01:15:41', '2025-08-26 01:15:41');

-- --------------------------------------------------------

--
-- Table structure for table `bs_accounts`
--

CREATE TABLE `bs_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `journal_entry_id` int(11) NOT NULL,
  `investment_id` int(11) DEFAULT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `expense_id` int(11) DEFAULT NULL,
  `purchase_return_id` int(11) DEFAULT NULL,
  `sell_return_id` int(11) DEFAULT NULL,
  `account_type` varchar(255) NOT NULL,
  `account_sub_type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `debit` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `credit` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bs_types`
--

CREATE TABLE `bs_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `main_type` int(11) DEFAULT NULL,
  `sub_type` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bs_types`
--

INSERT INTO `bs_types` (`id`, `main_type`, `sub_type`, `type`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'Cash - Current Assets', NULL, 0, NULL, NULL),
(2, 1, 1, 2, 'Bank - Current Assets', NULL, 0, NULL, NULL),
(3, 3, 1, NULL, 'Owner’s Capital', NULL, 0, NULL, NULL),
(4, 4, NULL, NULL, 'Utilities - Expense', NULL, 0, NULL, NULL),
(5, 1, 2, 1, 'Long-term investments - Non-current Assets', NULL, 0, NULL, NULL),
(6, 1, 2, 2, 'Property, plant and equipment - Non-current Assets', NULL, 0, NULL, NULL),
(7, 1, 3, 3, 'Supplier Advance - Receivable', NULL, 0, NULL, NULL),
(8, 3, 2, NULL, 'Sales Revenue A/C - Income', NULL, 0, NULL, NULL),
(9, 1, 3, 1, 'Customer Sell Due - Receivable', NULL, 0, NULL, NULL),
(10, 1, 3, 2, 'Employee Advance Salary', NULL, 0, NULL, NULL),
(11, 2, 1, 2, 'Customer Advance - Payable', NULL, 0, NULL, NULL),
(12, 2, 1, 1, 'Purchase Due - Payable', NULL, 0, NULL, NULL),
(13, 3, 2, NULL, 'Purchase A/C - Income', NULL, 0, NULL, NULL),
(14, 3, 2, NULL, 'Salary Expense A/C - Income', NULL, 0, NULL, NULL),
(15, 2, 1, 3, 'Employee Salary - Payable', NULL, 0, NULL, NULL),
(16, 2, 1, 4, 'Expense - Payable', NULL, 0, NULL, NULL),
(17, 3, 2, NULL, 'Office Expense A/C - Income', NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:6:{s:1:\"a\";s:12:\"section_name\";s:1:\"b\";s:2:\"id\";s:1:\"c\";s:4:\"name\";s:1:\"d\";s:10:\"guard_name\";s:1:\"e\";s:5:\"order\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:117:{i:0;a:6:{s:1:\"a\";s:4:\"Role\";s:1:\"b\";i:1;s:1:\"c\";s:9:\"role-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:1;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:6:{s:1:\"a\";s:4:\"Role\";s:1:\"b\";i:2;s:1:\"c\";s:11:\"role-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:1;s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:6:{s:1:\"a\";s:4:\"Role\";s:1:\"b\";i:3;s:1:\"c\";s:9:\"role-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:1;s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:6:{s:1:\"a\";s:4:\"Role\";s:1:\"b\";i:4;s:1:\"c\";s:11:\"role-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:1;s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:6:{s:1:\"a\";s:4:\"User\";s:1:\"b\";i:5;s:1:\"c\";s:9:\"user-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:2;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:6:{s:1:\"a\";s:4:\"User\";s:1:\"b\";i:6;s:1:\"c\";s:11:\"user-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:2;s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:6:{s:1:\"a\";s:4:\"User\";s:1:\"b\";i:7;s:1:\"c\";s:9:\"user-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:2;s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:6:{s:1:\"a\";s:4:\"User\";s:1:\"b\";i:8;s:1:\"c\";s:11:\"user-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:2;s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:6:{s:1:\"a\";s:7:\"Product\";s:1:\"b\";i:9;s:1:\"c\";s:12:\"product-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:3;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:6:{s:1:\"a\";s:7:\"Product\";s:1:\"b\";i:10;s:1:\"c\";s:14:\"product-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:3;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:6:{s:1:\"a\";s:7:\"Product\";s:1:\"b\";i:11;s:1:\"c\";s:12:\"product-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:3;s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:6:{s:1:\"a\";s:7:\"Product\";s:1:\"b\";i:12;s:1:\"c\";s:14:\"product-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:3;s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:6:{s:1:\"a\";s:8:\"Category\";s:1:\"b\";i:13;s:1:\"c\";s:13:\"category-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:4;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:6:{s:1:\"a\";s:8:\"Category\";s:1:\"b\";i:14;s:1:\"c\";s:15:\"category-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:4;s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:6:{s:1:\"a\";s:8:\"Category\";s:1:\"b\";i:15;s:1:\"c\";s:13:\"category-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:4;s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:6:{s:1:\"a\";s:8:\"Category\";s:1:\"b\";i:16;s:1:\"c\";s:15:\"category-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:4;s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:6:{s:1:\"a\";s:12:\"Sub Category\";s:1:\"b\";i:17;s:1:\"c\";s:17:\"sub-category-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:5;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:17;a:6:{s:1:\"a\";s:12:\"Sub Category\";s:1:\"b\";i:18;s:1:\"c\";s:19:\"sub-category-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:5;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:18;a:6:{s:1:\"a\";s:12:\"Sub Category\";s:1:\"b\";i:19;s:1:\"c\";s:17:\"sub-category-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:5;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:19;a:6:{s:1:\"a\";s:12:\"Sub Category\";s:1:\"b\";i:20;s:1:\"c\";s:19:\"sub-category-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:5;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:20;a:6:{s:1:\"a\";s:5:\"Brand\";s:1:\"b\";i:21;s:1:\"c\";s:10:\"brand-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:6;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:21;a:6:{s:1:\"a\";s:5:\"Brand\";s:1:\"b\";i:22;s:1:\"c\";s:12:\"brand-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:6;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:22;a:6:{s:1:\"a\";s:5:\"Brand\";s:1:\"b\";i:23;s:1:\"c\";s:10:\"brand-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:6;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:23;a:6:{s:1:\"a\";s:5:\"Brand\";s:1:\"b\";i:24;s:1:\"c\";s:12:\"brand-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:6;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:24;a:6:{s:1:\"a\";s:8:\"Customer\";s:1:\"b\";i:25;s:1:\"c\";s:13:\"customer-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:7;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:25;a:6:{s:1:\"a\";s:8:\"Customer\";s:1:\"b\";i:26;s:1:\"c\";s:15:\"customer-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:7;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:26;a:6:{s:1:\"a\";s:8:\"Customer\";s:1:\"b\";i:27;s:1:\"c\";s:13:\"customer-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:7;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:27;a:6:{s:1:\"a\";s:8:\"Customer\";s:1:\"b\";i:28;s:1:\"c\";s:15:\"customer-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:7;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:28;a:6:{s:1:\"a\";s:8:\"Customer\";s:1:\"b\";i:29;s:1:\"c\";s:16:\"customer-payment\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:7;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:29;a:6:{s:1:\"a\";s:8:\"Supplier\";s:1:\"b\";i:30;s:1:\"c\";s:13:\"supplier-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:8;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:30;a:6:{s:1:\"a\";s:8:\"Supplier\";s:1:\"b\";i:31;s:1:\"c\";s:15:\"supplier-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:8;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:31;a:6:{s:1:\"a\";s:8:\"Supplier\";s:1:\"b\";i:32;s:1:\"c\";s:13:\"supplier-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:8;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:32;a:6:{s:1:\"a\";s:8:\"Supplier\";s:1:\"b\";i:33;s:1:\"c\";s:15:\"supplier-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:8;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:33;a:6:{s:1:\"a\";s:8:\"Supplier\";s:1:\"b\";i:34;s:1:\"c\";s:16:\"supplier-payment\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:8;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:34;a:6:{s:1:\"a\";s:8:\"Purchase\";s:1:\"b\";i:35;s:1:\"c\";s:13:\"purchase-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:9;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:35;a:6:{s:1:\"a\";s:8:\"Purchase\";s:1:\"b\";i:36;s:1:\"c\";s:15:\"purchase-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:9;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:36;a:6:{s:1:\"a\";s:8:\"Purchase\";s:1:\"b\";i:37;s:1:\"c\";s:13:\"purchase-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:9;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:37;a:6:{s:1:\"a\";s:8:\"Purchase\";s:1:\"b\";i:38;s:1:\"c\";s:15:\"purchase-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:9;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:38;a:6:{s:1:\"a\";s:8:\"Purchase\";s:1:\"b\";i:39;s:1:\"c\";s:16:\"purchase-payment\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:9;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:39;a:6:{s:1:\"a\";s:15:\"Purchase Return\";s:1:\"b\";i:40;s:1:\"c\";s:20:\"purchase-return-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:10;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:40;a:6:{s:1:\"a\";s:15:\"Purchase Return\";s:1:\"b\";i:41;s:1:\"c\";s:22:\"purchase-return-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:10;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:41;a:6:{s:1:\"a\";s:15:\"Purchase Return\";s:1:\"b\";i:42;s:1:\"c\";s:20:\"purchase-return-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:10;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:42;a:6:{s:1:\"a\";s:15:\"Purchase Return\";s:1:\"b\";i:43;s:1:\"c\";s:22:\"purchase-return-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:10;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:43;a:6:{s:1:\"a\";s:4:\"Sell\";s:1:\"b\";i:44;s:1:\"c\";s:9:\"sell-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:11;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:44;a:6:{s:1:\"a\";s:4:\"Sell\";s:1:\"b\";i:45;s:1:\"c\";s:11:\"sell-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:11;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:45;a:6:{s:1:\"a\";s:4:\"Sell\";s:1:\"b\";i:46;s:1:\"c\";s:9:\"sell-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:11;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:46;a:6:{s:1:\"a\";s:4:\"Sell\";s:1:\"b\";i:47;s:1:\"c\";s:11:\"sell-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:11;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:47;a:6:{s:1:\"a\";s:4:\"Sell\";s:1:\"b\";i:48;s:1:\"c\";s:12:\"sell-payment\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:11;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:48;a:6:{s:1:\"a\";s:4:\"Sell\";s:1:\"b\";i:49;s:1:\"c\";s:13:\"sell-delivery\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:11;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:49;a:6:{s:1:\"a\";s:6:\"Income\";s:1:\"b\";i:50;s:1:\"c\";s:11:\"income-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:12;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:50;a:6:{s:1:\"a\";s:6:\"Income\";s:1:\"b\";i:51;s:1:\"c\";s:13:\"income-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:12;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:51;a:6:{s:1:\"a\";s:6:\"Income\";s:1:\"b\";i:52;s:1:\"c\";s:11:\"income-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:12;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:52;a:6:{s:1:\"a\";s:6:\"Income\";s:1:\"b\";i:53;s:1:\"c\";s:13:\"income-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:12;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:53;a:6:{s:1:\"a\";s:11:\"Income List\";s:1:\"b\";i:54;s:1:\"c\";s:16:\"income-list-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:13;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:54;a:6:{s:1:\"a\";s:11:\"Income List\";s:1:\"b\";i:55;s:1:\"c\";s:18:\"income-list-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:13;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:55;a:6:{s:1:\"a\";s:11:\"Income List\";s:1:\"b\";i:56;s:1:\"c\";s:16:\"income-list-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:13;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:56;a:6:{s:1:\"a\";s:11:\"Income List\";s:1:\"b\";i:57;s:1:\"c\";s:18:\"income-list-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:13;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:57;a:6:{s:1:\"a\";s:11:\"Sell Return\";s:1:\"b\";i:58;s:1:\"c\";s:16:\"sell-return-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:14;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:58;a:6:{s:1:\"a\";s:11:\"Sell Return\";s:1:\"b\";i:59;s:1:\"c\";s:18:\"sell-return-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:14;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:59;a:6:{s:1:\"a\";s:11:\"Sell Return\";s:1:\"b\";i:60;s:1:\"c\";s:16:\"sell-return-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:14;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:60;a:6:{s:1:\"a\";s:11:\"Sell Return\";s:1:\"b\";i:61;s:1:\"c\";s:18:\"sell-return-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:14;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:61;a:6:{s:1:\"a\";s:12:\"Expense Head\";s:1:\"b\";i:62;s:1:\"c\";s:17:\"expense-head-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:15;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:62;a:6:{s:1:\"a\";s:12:\"Expense Head\";s:1:\"b\";i:63;s:1:\"c\";s:19:\"expense-head-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:15;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:63;a:6:{s:1:\"a\";s:12:\"Expense Head\";s:1:\"b\";i:64;s:1:\"c\";s:17:\"expense-head-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:15;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:64;a:6:{s:1:\"a\";s:12:\"Expense Head\";s:1:\"b\";i:65;s:1:\"c\";s:19:\"expense-head-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:15;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:65;a:6:{s:1:\"a\";s:7:\"Expense\";s:1:\"b\";i:66;s:1:\"c\";s:12:\"expense-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:16;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:66;a:6:{s:1:\"a\";s:7:\"Expense\";s:1:\"b\";i:67;s:1:\"c\";s:14:\"expense-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:16;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:67;a:6:{s:1:\"a\";s:7:\"Expense\";s:1:\"b\";i:68;s:1:\"c\";s:12:\"expense-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:16;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:68;a:6:{s:1:\"a\";s:7:\"Expense\";s:1:\"b\";i:69;s:1:\"c\";s:14:\"expense-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:16;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:69;a:6:{s:1:\"a\";s:6:\"Damage\";s:1:\"b\";i:70;s:1:\"c\";s:11:\"damage-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:17;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:70;a:6:{s:1:\"a\";s:6:\"Damage\";s:1:\"b\";i:71;s:1:\"c\";s:13:\"damage-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:17;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:71;a:6:{s:1:\"a\";s:6:\"Damage\";s:1:\"b\";i:72;s:1:\"c\";s:11:\"damage-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:17;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:72;a:6:{s:1:\"a\";s:6:\"Damage\";s:1:\"b\";i:73;s:1:\"c\";s:13:\"damage-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:17;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:73;a:6:{s:1:\"a\";s:4:\"Bank\";s:1:\"b\";i:74;s:1:\"c\";s:9:\"bank-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:18;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:74;a:6:{s:1:\"a\";s:4:\"Bank\";s:1:\"b\";i:75;s:1:\"c\";s:11:\"bank-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:18;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:75;a:6:{s:1:\"a\";s:4:\"Bank\";s:1:\"b\";i:76;s:1:\"c\";s:9:\"bank-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:18;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:76;a:6:{s:1:\"a\";s:4:\"Bank\";s:1:\"b\";i:77;s:1:\"c\";s:11:\"bank-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:18;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:77;a:6:{s:1:\"a\";s:16:\"Bank Transaction\";s:1:\"b\";i:78;s:1:\"c\";s:16:\"bank-transaction\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:19;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:78;a:6:{s:1:\"a\";s:16:\"Bank Transaction\";s:1:\"b\";i:79;s:1:\"c\";s:12:\"bank-diposit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:19;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:79;a:6:{s:1:\"a\";s:16:\"Bank Transaction\";s:1:\"b\";i:80;s:1:\"c\";s:13:\"bank-withdraw\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:19;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:80;a:6:{s:1:\"a\";s:8:\"Employee\";s:1:\"b\";i:81;s:1:\"c\";s:13:\"employee-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:20;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:81;a:6:{s:1:\"a\";s:8:\"Employee\";s:1:\"b\";i:82;s:1:\"c\";s:15:\"employee-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:20;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:82;a:6:{s:1:\"a\";s:8:\"Employee\";s:1:\"b\";i:83;s:1:\"c\";s:13:\"employee-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:20;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:83;a:6:{s:1:\"a\";s:8:\"Employee\";s:1:\"b\";i:84;s:1:\"c\";s:15:\"employee-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:20;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:84;a:6:{s:1:\"a\";s:20:\"Employee Transaction\";s:1:\"b\";i:85;s:1:\"c\";s:25:\"employee-transaction-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:21;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:85;a:6:{s:1:\"a\";s:20:\"Employee Transaction\";s:1:\"b\";i:86;s:1:\"c\";s:27:\"employee-transaction-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:21;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:86;a:6:{s:1:\"a\";s:20:\"Employee Transaction\";s:1:\"b\";i:87;s:1:\"c\";s:25:\"employee-transaction-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:21;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:87;a:6:{s:1:\"a\";s:20:\"Employee Transaction\";s:1:\"b\";i:88;s:1:\"c\";s:27:\"employee-transaction-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:21;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:88;a:6:{s:1:\"a\";s:8:\"Investor\";s:1:\"b\";i:89;s:1:\"c\";s:13:\"investor-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:22;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:89;a:6:{s:1:\"a\";s:8:\"Investor\";s:1:\"b\";i:90;s:1:\"c\";s:15:\"investor-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:22;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:90;a:6:{s:1:\"a\";s:8:\"Investor\";s:1:\"b\";i:91;s:1:\"c\";s:13:\"investor-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:22;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:91;a:6:{s:1:\"a\";s:8:\"Investor\";s:1:\"b\";i:92;s:1:\"c\";s:15:\"investor-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:22;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:92;a:6:{s:1:\"a\";s:10:\"Investment\";s:1:\"b\";i:93;s:1:\"c\";s:15:\"investment-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:23;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:93;a:6:{s:1:\"a\";s:10:\"Investment\";s:1:\"b\";i:94;s:1:\"c\";s:17:\"investment-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:23;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:94;a:6:{s:1:\"a\";s:10:\"Investment\";s:1:\"b\";i:95;s:1:\"c\";s:15:\"investment-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:23;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:95;a:6:{s:1:\"a\";s:10:\"Investment\";s:1:\"b\";i:96;s:1:\"c\";s:17:\"investment-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:23;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:96;a:6:{s:1:\"a\";s:4:\"Unit\";s:1:\"b\";i:97;s:1:\"c\";s:9:\"unit-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:24;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:97;a:6:{s:1:\"a\";s:4:\"Unit\";s:1:\"b\";i:98;s:1:\"c\";s:11:\"unit-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:24;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:98;a:6:{s:1:\"a\";s:4:\"Unit\";s:1:\"b\";i:99;s:1:\"c\";s:9:\"unit-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:24;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:99;a:6:{s:1:\"a\";s:4:\"Unit\";s:1:\"b\";i:100;s:1:\"c\";s:11:\"unit-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:24;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:100;a:6:{s:1:\"a\";s:13:\"Customer Type\";s:1:\"b\";i:101;s:1:\"c\";s:18:\"customer-type-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:25;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:101;a:6:{s:1:\"a\";s:13:\"Customer Type\";s:1:\"b\";i:102;s:1:\"c\";s:20:\"customer-type-create\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:25;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:102;a:6:{s:1:\"a\";s:13:\"Customer Type\";s:1:\"b\";i:103;s:1:\"c\";s:18:\"customer-type-edit\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:25;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:103;a:6:{s:1:\"a\";s:13:\"Customer Type\";s:1:\"b\";i:104;s:1:\"c\";s:20:\"customer-type-delete\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:25;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:104;a:6:{s:1:\"a\";s:5:\"Stock\";s:1:\"b\";i:105;s:1:\"c\";s:10:\"stock-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:26;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:105;a:6:{s:1:\"a\";s:7:\"Reports\";s:1:\"b\";i:106;s:1:\"c\";s:25:\"balance-sheet-report-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:27;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:106;a:6:{s:1:\"a\";s:7:\"Reports\";s:1:\"b\";i:107;s:1:\"c\";s:11:\"profit-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:27;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:107;a:6:{s:1:\"a\";s:7:\"Reports\";s:1:\"b\";i:108;s:1:\"c\";s:20:\"delivery-report-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:27;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:108;a:6:{s:1:\"a\";s:7:\"Reports\";s:1:\"b\";i:109;s:1:\"c\";s:20:\"purchase-report-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:27;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:109;a:6:{s:1:\"a\";s:7:\"Reports\";s:1:\"b\";i:110;s:1:\"c\";s:19:\"expense-report-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:27;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:110;a:6:{s:1:\"a\";s:7:\"Reports\";s:1:\"b\";i:111;s:1:\"c\";s:16:\"sell-report-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:27;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:111;a:6:{s:1:\"a\";s:7:\"Reports\";s:1:\"b\";i:112;s:1:\"c\";s:20:\"discount-report-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:27;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:112;a:6:{s:1:\"a\";s:7:\"Reports\";s:1:\"b\";i:113;s:1:\"c\";s:18:\"damage-report-list\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:27;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:113;a:6:{s:1:\"a\";s:8:\"Maintain\";s:1:\"b\";i:114;s:1:\"c\";s:14:\"asset-maintain\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:28;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:114;a:6:{s:1:\"a\";s:8:\"Maintain\";s:1:\"b\";i:115;s:1:\"c\";s:22:\"balance-sheet-maintain\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:28;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:115;a:6:{s:1:\"a\";s:8:\"Maintain\";s:1:\"b\";i:116;s:1:\"c\";s:24:\"general-setting-maintain\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:28;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:116;a:6:{s:1:\"a\";s:8:\"Maintain\";s:1:\"b\";i:117;s:1:\"c\";s:16:\"account-maintain\";s:1:\"d\";s:3:\"web\";s:1:\"e\";i:28;s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"b\";i:1;s:1:\"c\";s:11:\"Super Admin\";s:1:\"d\";s:3:\"web\";}i:1;a:3:{s:1:\"b\";i:2;s:1:\"c\";s:8:\"Employee\";s:1:\"d\";s:3:\"web\";}}}', 1756285723);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Clothing', 1, 0, '2025-08-26 01:15:27', '2025-08-26 01:15:27');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0 = Inactive, 1 = Active',
  `type` varchar(255) DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `entry_date` timestamp NULL DEFAULT NULL,
  `advance` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `due` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `code`, `name`, `company`, `mobile`, `address`, `comments`, `email`, `status`, `type`, `entry_by`, `entry_date`, `advance`, `due`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'CUSS4ZTDY', 'Rafiul Islam', 'RR', '212', 'Dhaka', NULL, 'rafiul@gmail.com', 1, '1', 1, '2025-06-27 22:55:56', 0.00000000, 0.00000000, 0, '2025-06-27 22:55:56', '2025-06-27 22:55:56'),
(2, 'CUSE8HAX2', 'Farid Islam', 'Farid', '23489', 'Dhaka', NULL, 'fr@gmail.com', 1, '2', 1, '2025-06-27 23:06:20', 0.00000000, 0.00000000, 0, '2025-06-27 23:06:20', '2025-06-27 23:30:39'),
(3, 'CUSJ26YQN', 'Tiru', 'Tiru', '3452', 'Dhaka', NULL, 'tri@gmail.com', 1, '3', 1, '2025-06-27 23:08:09', 0.00000000, 0.00000000, 0, '2025-06-27 23:08:09', '2025-06-27 23:29:34'),
(4, 'CUSBLW04V', 'zz', 'zz', '2342121', 'zz', NULL, 'zz@gmail.com', 1, '2', 1, '2025-06-27 23:12:12', 0.00000000, 0.00000000, 0, '2025-06-27 23:12:12', '2025-06-27 23:12:12'),
(5, 'CUSANNHYH', 'zzrr', 'zzrr', '234212111', 'zzrr', NULL, 'zzrr@gmail.com', 1, '2', 1, '2025-06-27 23:14:29', 0.00000000, 0.00000000, 0, '2025-06-27 23:14:29', '2025-06-27 23:14:29'),
(6, 'CUSPHVIRJ', 'zzrrss', 'zzrrss', '2342121112', 'zzrrss', NULL, 'zzrrss@gmail.com', 1, '2', 1, '2025-06-27 23:16:19', 0.00000000, 0.00000000, 0, '2025-06-27 23:16:19', '2025-06-27 23:16:19');

-- --------------------------------------------------------

--
-- Table structure for table `customer_returns`
--

CREATE TABLE `customer_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `base_product_id` int(11) NOT NULL,
  `total_qty` int(11) NOT NULL,
  `total_return_price` decimal(28,8) NOT NULL,
  `entry_date` date NOT NULL,
  `last_update` date DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_return_items`
--

CREATE TABLE `customer_return_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_return_id` int(11) NOT NULL,
  `purchase_batch_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `sell_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `return_qty` int(11) NOT NULL,
  `avg_sell_price` decimal(28,8) NOT NULL,
  `retun_sell_price` decimal(28,8) NOT NULL,
  `retun_total_sell_price` decimal(28,8) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_types`
--

CREATE TABLE `customer_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_types`
--

INSERT INTO `customer_types` (`id`, `name`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Importer', 1, 0, '2025-06-27 21:48:10', '2025-06-27 21:48:10'),
(2, 'Exporter', 1, 0, '2025-06-27 21:48:10', '2025-06-27 21:48:10'),
(3, 'Retailer', 1, 0, '2025-06-27 21:48:10', '2025-06-27 21:48:10');

-- --------------------------------------------------------

--
-- Table structure for table `damages`
--

CREATE TABLE `damages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `purchase_batch_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `price` decimal(28,8) NOT NULL,
  `total_damage_price` decimal(28,8) NOT NULL,
  `qty` int(11) NOT NULL,
  `total_qty` int(11) NOT NULL,
  `description` longtext DEFAULT NULL,
  `conversation` longtext DEFAULT NULL,
  `entry_date` date NOT NULL,
  `replacement_repair_date` date DEFAULT NULL,
  `last_update` date DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `damage_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Indicates the status of the damage: 0 = Draft (not finalized), 1 = Confirmed Damaged (finalized)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sell_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `entry_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `demo_users`
--

CREATE TABLE `demo_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `lastNmae` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nid` varchar(40) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `image` text DEFAULT NULL,
  `joining_date` date NOT NULL,
  `salary` decimal(28,8) NOT NULL,
  `conveyance` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `status` varchar(255) NOT NULL,
  `entry_by` int(11) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `entry_date` timestamp NULL DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `code`, `mobile`, `address`, `email`, `nid`, `designation`, `image`, `joining_date`, `salary`, `conveyance`, `status`, `entry_by`, `is_deleted`, `entry_date`, `update_by`, `created_at`, `updated_at`) VALUES
(1, 'Tariq', 'EMPGOENNX', '21354', 'ggg', 'Tariq@gmail.com', '3241', 'Tariq', NULL, '2025-06-18', 1000.00000000, 0.00000000, '1', 1, 0, '2025-06-28 02:13:46', NULL, '2025-06-28 02:13:46', '2025-06-28 02:13:46'),
(2, 'naim', 'EMPHKPYFD', '564123', 'xddd', 'naim@gmail.com', '546546456', 'naim', NULL, '2025-06-13', 5000.00000000, 0.00000000, '1', 1, 0, '2025-06-28 03:01:04', NULL, '2025-06-28 03:01:04', '2025-06-28 03:01:04'),
(3, 'gima', 'EMP9WWTXM', '43534', 'ss', 'gima@gmail.com', '423', 'gima', NULL, '2025-06-15', 2000.00000000, 0.00000000, '1', 1, 0, '2025-06-28 03:06:46', NULL, '2025-06-28 03:06:46', '2025-06-28 03:06:46'),
(4, 'zira', 'EMPPC6MOD', '3212', 'dsdsds', 'zira@gmail.com', '324332', 'zira', NULL, '2025-06-15', 3000.00000000, 0.00000000, '1', 1, 0, '2025-06-28 03:09:59', NULL, '2025-06-28 03:09:59', '2025-06-28 03:09:59'),
(5, 'gini', 'EMPZCNAZL', '34223234', 'sddsad', 'gini@gmail.com', '23423423', 'gini', NULL, '2025-06-26', 3000.00000000, 0.00000000, '1', 1, 0, '2025-06-28 03:15:04', NULL, '2025-06-28 03:15:04', '2025-06-28 03:15:04'),
(6, 'mehrab', 'EMPC7OW2H', '23423423', 'fff', 'mehrab@gmail.com', '423423423', 'mehrab', NULL, '2025-06-06', 5000.00000000, 0.00000000, '1', 1, 0, '2025-06-28 03:27:31', NULL, '2025-06-28 03:27:31', '2025-06-28 03:27:31'),
(7, 'maruf', 'EMP7AY3JK', '015446545', 'Dhaka', 'sr@gmail.com', '54564', 'SR', NULL, '2025-06-29', 5000.00000000, 0.00000000, '1', 1, 0, '2025-06-28 22:06:30', NULL, '2025-06-28 22:06:30', '2025-06-28 22:06:30'),
(8, 'fahim', 'EMPOBX8DE', '454565', 'sdas', 'fahim@gmail.com', '45564', 'fahim', NULL, '2025-06-18', 6000.00000000, 0.00000000, '1', 1, 0, '2025-06-28 22:15:52', NULL, '2025-06-28 22:15:52', '2025-06-28 22:15:52'),
(9, 'titu', 'EMPPZIJWQ', '3234324', 'ss', 'titu@gmail.com', '234234', 'titu', NULL, '2025-06-20', 4000.00000000, 0.00000000, '1', 1, 0, '2025-06-28 22:18:30', NULL, '2025-06-28 22:18:30', '2025-06-28 22:18:30'),
(10, 'faruq', 'EMP4ZZULN', '423423423', 'faruq', 'faruq@gmail.com', '23423423', 'faruq', NULL, '2025-06-24', 2000.00000000, 0.00000000, '1', 1, 0, '2025-06-28 22:21:03', NULL, '2025-06-28 22:21:03', '2025-06-28 22:21:03'),
(11, 'sss1', 'EMPFDXIXF', '32432432', 'sdas', 'sss1@gmail.com', '324234', 'sss', NULL, '2025-06-20', 1000.00000000, 0.00000000, '1', 1, 0, '2025-06-28 22:23:31', NULL, '2025-06-28 22:23:31', '2025-06-28 22:23:31'),
(12, 'sfsf', 'EMPZJL6WK', '324234', 'sfsf', 'sfsf@gmail.com', '32423', 'sfsf', NULL, '2025-06-02', 2000.00000000, 0.00000000, '1', 1, 0, '2025-06-28 22:26:55', NULL, '2025-06-28 22:26:55', '2025-06-28 22:26:55'),
(13, 'farhan', 'EMPNFDYQU', '01793096447', 'Kuril', 'farhan@gmail.com', '234234234', 'farhan', NULL, '2025-08-25', 18000.00000000, 2000.00000000, '1', 1, 0, '2025-08-25 03:07:12', NULL, '2025-08-25 03:07:12', '2025-08-25 03:07:12'),
(14, 'nazmul', 'EMPTUVZYC', '01145454545', 'Dhaka', 'nazmul@gmail.com', '342857349058092', 'staff', NULL, '2025-08-04', 18000.00000000, 2000.00000000, '1', 1, 0, '2025-08-25 04:02:00', NULL, '2025-08-25 04:02:00', '2025-08-25 04:02:00'),
(15, 'moni', 'EMPBOVSLH', '01748561241', 'Dhaka', 'moni@gmail.com', '123456', 'cro', NULL, '2025-08-26', 10000.00000000, 10000.00000000, '1', 1, 0, '2025-08-26 00:33:05', NULL, '2025-08-26 00:33:05', '2025-08-26 00:33:05'),
(16, 'fahim1', 'EMPSDDVGR', '01793096409', 'Kuril', 'fahim1@gmail.com', '23412312', 'Staff', NULL, '2025-08-26', 20000.00000000, 5000.00000000, '1', 1, 0, '2025-08-26 00:54:13', 1, '2025-08-26 00:54:13', '2025-08-26 01:04:44');

-- --------------------------------------------------------

--
-- Table structure for table `employee_monthly_transactions`
--

CREATE TABLE `employee_monthly_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` int(11) NOT NULL,
  `month` varchar(7) NOT NULL,
  `net_salary` decimal(28,8) NOT NULL,
  `salary_amount` decimal(28,8) NOT NULL,
  `punishment` decimal(28,8) NOT NULL,
  `total_paid` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `due` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `advance` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `bs_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = calculated, 0 = not calculated',
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_transactions`
--

CREATE TABLE `employee_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `monthly_transaction_id` int(11) NOT NULL,
  `salary_date` varchar(255) DEFAULT NULL,
  `transaction_date` timestamp NULL DEFAULT NULL,
  `salary_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `received_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `punishment` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `description` text DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_head_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT 0,
  `entry_date` date NOT NULL,
  `debit_or_credit` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `effective_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `paid_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `pending_status` tinyint(4) NOT NULL DEFAULT 0,
  `entry_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_heads`
--

CREATE TABLE `expense_heads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `primary_contact_number` varchar(255) DEFAULT NULL,
  `alternate_contact_number` varchar(255) DEFAULT NULL,
  `primary_email_address` varchar(255) DEFAULT NULL,
  `alternate_email_address` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `pagination` int(11) DEFAULT NULL,
  `subcategory_module` tinyint(1) NOT NULL COMMENT '1 = activated, 0 = deactivated	',
  `bs_module` tinyint(1) NOT NULL,
  `brand_module` tinyint(1) NOT NULL COMMENT '1 = activated, 0 = deactivated	',
  `barcode` tinyint(4) NOT NULL COMMENT '1 = activated, 0 = deactivated',
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `logo`, `user_image`, `favicon`, `company_name`, `primary_contact_number`, `alternate_contact_number`, `primary_email_address`, `alternate_email_address`, `website_url`, `pagination`, `subcategory_module`, `bs_module`, `brand_module`, `barcode`, `address`, `created_at`, `updated_at`) VALUES
(1, '1746253873.png', '1745040104.png', '1735710929.jpg', 'Bangladesh Software Development', '313131', '1111111111', 'jyqyzolen@mailinator.com', 'lylyxacep@mailinator.com', 'http://youtube.com', 10, 1, 1, 1, 1, 'Nikunja, Dhaka', '2024-12-31 04:28:37', '2025-06-26 05:50:21');

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `income_list_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `entry_type` int(11) NOT NULL DEFAULT 0,
  `debit_or_credit` varchar(255) DEFAULT NULL,
  `effective_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `entry_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incomes`
--

INSERT INTO `incomes` (`id`, `income_list_id`, `description`, `amount`, `entry_type`, `debit_or_credit`, `effective_amount`, `entry_by`, `update_by`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Purchase due to supplier fahruk2, amount: 5000 Tk', 0.00000000, 1, 'debit', 5000.00000000, 1, NULL, 0, '2025-08-26 01:18:30', '2025-08-26 01:18:30');

-- --------------------------------------------------------

--
-- Table structure for table `income_lists`
--

CREATE TABLE `income_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `entry_type` int(11) NOT NULL DEFAULT 0,
  `debit_or_credit` varchar(255) DEFAULT NULL,
  `effective_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `entry_date` datetime DEFAULT NULL,
  `payment_method` tinyint(4) NOT NULL COMMENT 'Bank = 2, Cash = 1',
  `description` varchar(255) DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`id`, `name`, `amount`, `entry_type`, `debit_or_credit`, `effective_amount`, `entry_date`, `payment_method`, `description`, `entry_by`, `created_at`, `updated_at`) VALUES
(1, 'Shakir', 100000.00000000, 0, NULL, 0.00000000, '2025-08-26 13:13:00', 1, 'Shakir', 1, '2025-08-26 01:14:15', '2025-08-26 01:14:15');

-- --------------------------------------------------------

--
-- Table structure for table `investors`
--

CREATE TABLE `investors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `entry_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `debit_or_credit` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `entry_date` datetime DEFAULT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `entry_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `name`, `type`, `code`, `debit_or_credit`, `description`, `entry_date`, `amount`, `entry_by`, `update_by`, `created_at`, `updated_at`) VALUES
(1, 'Cash - Current Assets', '1', 'WLXZEJ', 'debit', 'adjust 3000 into cash as invest', '2025-08-26 14:16:00', 3000.00000000, 1, NULL, '2025-08-26 02:17:04', '2025-08-26 02:17:04'),
(2, 'Owner’s Capital', '3', 'WLXZEJ', 'credit', 'adjust 3000 into cash as invest', '2025-08-26 14:16:00', 3000.00000000, 1, NULL, '2025-08-26 02:17:04', '2025-08-26 02:17:04');

-- --------------------------------------------------------

--
-- Table structure for table `manage_stocks`
--

CREATE TABLE `manage_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `total_qty` int(11) NOT NULL,
  `grand_total` decimal(28,8) NOT NULL,
  `description` text DEFAULT NULL,
  `stock_status` int(11) NOT NULL DEFAULT 0 COMMENT '1 = Damage, 2 = Lost, 3 = Found, 4 = Expiry, 5 = Theft, 6 = Manual Increase, 7 = Manual Decrease',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `manage_stock_items`
--

CREATE TABLE `manage_stock_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `manage_stock_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `avg_purchase_price` decimal(28,8) DEFAULT NULL,
  `total_amount` decimal(28,8) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_11_24_040724_create_categories_table', 1),
(5, '2024_11_24_090234_create_subcategories_table', 1),
(6, '2024_11_24_100031_create_brands_table', 1),
(7, '2024_11_24_110210_create_customers_table', 1),
(8, '2024_11_25_051246_create_units_table', 1),
(9, '2024_11_25_054804_create_products_table', 1),
(10, '2024_11_25_085233_create_customer_types_table', 1),
(11, '2024_11_26_035655_create_suppliers_table', 1),
(12, '2024_11_26_082310_create_purchases_table', 1),
(13, '2024_11_26_100218_create_employees_table', 1),
(14, '2024_11_27_035010_create_employee_transactions', 1),
(15, '2024_11_28_044643_create_purchase_items_table', 1),
(16, '2024_11_28_053935_create_bank_transactions_table', 1),
(17, '2024_11_28_054018_create_banks_table', 1),
(18, '2024_11_30_111714_create_purchase_batches_table', 1),
(19, '2024_12_02_040906_create_stocks_table', 1),
(20, '2024_12_02_104628_create_stock_items_table', 1),
(21, '2024_12_05_034534_create_sell_records_table', 1),
(22, '2024_12_08_040530_create_sells_table', 1),
(23, '2024_12_09_105324_create_accounts_table', 1),
(24, '2024_12_12_103656_create_damages_table', 1),
(25, '2024_12_17_102920_create_supplier_returns_table', 1),
(26, '2024_12_19_105736_create_supplier_return_items_table', 1),
(27, '2024_12_22_113456_create_customer_returns_table', 1),
(28, '2024_12_22_113639_create_customer_return_items_table', 1),
(29, '2024_12_25_104958_create_deliveries_table', 1),
(30, '2024_12_30_165633_create_general_settings_table', 1),
(31, '2025_01_01_105008_create_expenses_table', 1),
(32, '2025_01_05_040352_create_permission_tables', 1),
(33, '2025_01_11_091441_create_expense_heads_table', 1),
(34, '2025_01_29_053711_create_incomes_table', 1),
(35, '2025_01_29_053758_create_income_lists_table', 1),
(36, '2025_02_08_063541_create_investments_table', 1),
(37, '2025_02_16_080430_create_bs_accounts_table', 1),
(38, '2025_02_16_081556_create_bs_types_table', 1),
(39, '2025_02_17_033739_create_journal_entries_table', 1),
(40, '2025_02_17_114510_create_employee_monthly_transactions_table', 1),
(41, '2025_03_12_104700_create_payables_table', 1),
(42, '2025_03_12_104800_create_receivables_table', 1),
(43, '2025_03_12_105820_create_payable_heads_table', 1),
(44, '2025_03_12_110751_create_receivable_heads_table', 1),
(45, '2025_03_18_120357_create_asset_heads_table', 1),
(46, '2025_03_18_121820_create_assets_table', 1),
(47, '2025_04_20_081132_create_investors_table', 1),
(48, '2025_05_04_110721_create_warehouses_table', 1),
(49, '2025_05_19_081137_create_manage_stocks_table', 1),
(50, '2025_05_21_090921_create_manage_stock_items_table', 1),
(51, '2025_06_18_092838_create_demo_users_table', 1),
(52, '2025_06_24_044641_create_owners_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 6),
(2, 'App\\Models\\User', 7),
(2, 'App\\Models\\User', 8),
(2, 'App\\Models\\User', 9),
(2, 'App\\Models\\User', 10),
(2, 'App\\Models\\User', 11),
(2, 'App\\Models\\User', 12),
(2, 'App\\Models\\User', 13),
(2, 'App\\Models\\User', 14),
(2, 'App\\Models\\User', 15),
(2, 'App\\Models\\User', 16),
(2, 'App\\Models\\User', 17);

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entry_type` int(11) NOT NULL DEFAULT 0,
  `debit_or_credit` varchar(255) DEFAULT NULL,
  `amount` decimal(28,8) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`id`, `entry_type`, `debit_or_credit`, `amount`, `description`, `created_at`, `updated_at`) VALUES
(1, 0, 'credit', 94000.00000000, 'Shakir', '2025-08-26 01:14:15', '2025-08-26 01:14:15'),
(2, 1, 'credit', 3000.00000000, 'adjust 3000 into cash as invest', '2025-08-26 02:17:04', '2025-08-26 02:17:04');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payables`
--

CREATE TABLE `payables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `expense_id` int(11) DEFAULT NULL,
  `payables_head_id` int(11) NOT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `entry_type` int(11) NOT NULL DEFAULT 0,
  `debit_or_credit` varchar(255) DEFAULT NULL,
  `effective_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `payable_amount` decimal(28,8) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payables`
--

INSERT INTO `payables` (`id`, `purchase_id`, `supplier_id`, `customer_id`, `employee_id`, `expense_id`, `payables_head_id`, `invoice_no`, `entry_type`, `debit_or_credit`, `effective_amount`, `payable_amount`, `description`, `created_at`, `updated_at`) VALUES
(1, NULL, 13, NULL, NULL, NULL, 5, NULL, 0, NULL, 0.00000000, 2000.00000000, NULL, '2025-08-26 01:18:30', '2025-08-26 01:20:41'),
(2, 13, 12, NULL, NULL, NULL, 1, 'purchase-invoice-13', 0, NULL, 0.00000000, 2000.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 03:40:43', '2025-08-26 03:44:52'),
(3, 14, 14, NULL, NULL, NULL, 1, 'purchase-invoice-14', 0, NULL, 0.00000000, 1000.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 03:55:42', '2025-08-26 04:02:30'),
(4, 15, 14, NULL, NULL, NULL, 1, 'purchase-invoice-15', 0, NULL, 0.00000000, 1000.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 04:15:19', '2025-08-26 04:17:12'),
(5, 16, 14, NULL, NULL, NULL, 1, 'purchase-invoice-16', 0, NULL, 0.00000000, 1000.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 04:23:57', '2025-08-26 04:24:16'),
(6, 17, 14, NULL, NULL, NULL, 1, 'purchase-invoice-17', 0, NULL, 0.00000000, 1000.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 04:25:42', '2025-08-26 04:25:54'),
(7, 18, 14, NULL, NULL, NULL, 1, 'purchase-invoice-18', 0, NULL, 0.00000000, 1000.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 04:33:34', '2025-08-26 04:33:45'),
(8, 19, 14, NULL, NULL, NULL, 1, 'purchase-invoice-19', 0, NULL, 0.00000000, 0.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 04:36:19', '2025-08-26 04:41:36'),
(9, 20, 14, NULL, NULL, NULL, 1, 'purchase-invoice-20', 0, NULL, 0.00000000, 1000.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 04:42:24', '2025-08-26 04:42:31'),
(10, 21, 14, NULL, NULL, NULL, 1, 'purchase-invoice-21', 0, NULL, 0.00000000, 0.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 04:42:24', '2025-08-26 04:42:24'),
(11, 22, 14, NULL, NULL, NULL, 1, 'purchase-invoice-22', 0, NULL, 0.00000000, 1000.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 04:47:03', '2025-08-26 04:47:10'),
(12, 23, 14, NULL, NULL, NULL, 1, 'purchase-invoice-23', 0, NULL, 0.00000000, 1000.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 04:52:12', '2025-08-26 04:52:18'),
(13, 24, 14, NULL, NULL, NULL, 1, 'purchase-invoice-24', 0, NULL, 0.00000000, 1000.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 04:57:18', '2025-08-26 04:57:27'),
(14, 25, 14, NULL, NULL, NULL, 1, 'purchase-invoice-25', 0, NULL, 0.00000000, 1000.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 05:00:08', '2025-08-26 05:00:15'),
(15, 26, 14, NULL, NULL, NULL, 1, 'purchase-invoice-26', 0, NULL, 0.00000000, 1000.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 05:02:51', '2025-08-26 05:02:57'),
(16, 27, 14, NULL, NULL, NULL, 1, 'purchase-invoice-27', 0, NULL, 0.00000000, 0.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 05:08:17', '2025-08-26 05:26:41'),
(17, 28, 14, NULL, NULL, NULL, 1, 'purchase-invoice-28', 0, NULL, 0.00000000, 0.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 05:28:42', '2025-08-26 05:33:08'),
(18, 29, 14, NULL, NULL, NULL, 1, 'purchase-invoice-29', 0, NULL, 0.00000000, 0.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 05:36:11', '2025-08-26 05:36:11'),
(19, 30, 14, NULL, NULL, NULL, 1, 'purchase-invoice-30', 0, NULL, 0.00000000, 0.00000000, 'Purchase due amount of  Tk has been entered in Account\'s Payable.', '2025-08-26 05:37:06', '2025-08-26 05:38:08');

-- --------------------------------------------------------

--
-- Table structure for table `payable_heads`
--

CREATE TABLE `payable_heads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` tinyint(4) NOT NULL COMMENT 'autometic = 1, manual = 2	',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payable_heads`
--

INSERT INTO `payable_heads` (`id`, `name`, `description`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Purchase Due', 'Purchase Due', 1, '2025-06-27 21:48:06', '2025-06-27 21:48:06'),
(2, 'Customer Advance', 'This is Customer Advance', 1, '2025-06-27 21:48:06', '2025-06-27 21:48:06'),
(3, 'Employee Salary Payable', 'This is Employee Salary Payable', 1, '2025-06-27 21:48:06', '2025-06-27 21:48:06'),
(4, 'Expense Amount', 'This is Expense Amount.', 1, '2025-06-27 21:48:06', '2025-06-27 21:48:06'),
(5, 'Supplier Due', 'This is Supplier Due.', 1, '2025-06-27 21:48:06', '2025-06-27 21:48:06');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `section_name` varchar(255) DEFAULT NULL,
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`section_name`, `id`, `name`, `guard_name`, `order`, `created_at`, `updated_at`) VALUES
('Role', 1, 'role-list', 'web', 1, '2025-06-27 21:47:52', '2025-06-27 21:47:53'),
('Role', 2, 'role-create', 'web', 1, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Role', 3, 'role-edit', 'web', 1, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Role', 4, 'role-delete', 'web', 1, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('User', 5, 'user-list', 'web', 2, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('User', 6, 'user-create', 'web', 2, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('User', 7, 'user-edit', 'web', 2, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('User', 8, 'user-delete', 'web', 2, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Product', 9, 'product-list', 'web', 3, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Product', 10, 'product-create', 'web', 3, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Product', 11, 'product-edit', 'web', 3, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Product', 12, 'product-delete', 'web', 3, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Category', 13, 'category-list', 'web', 4, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Category', 14, 'category-create', 'web', 4, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Category', 15, 'category-edit', 'web', 4, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Category', 16, 'category-delete', 'web', 4, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Sub Category', 17, 'sub-category-list', 'web', 5, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Sub Category', 18, 'sub-category-create', 'web', 5, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Sub Category', 19, 'sub-category-edit', 'web', 5, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Sub Category', 20, 'sub-category-delete', 'web', 5, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Brand', 21, 'brand-list', 'web', 6, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Brand', 22, 'brand-create', 'web', 6, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Brand', 23, 'brand-edit', 'web', 6, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Brand', 24, 'brand-delete', 'web', 6, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Customer', 25, 'customer-list', 'web', 7, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Customer', 26, 'customer-create', 'web', 7, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Customer', 27, 'customer-edit', 'web', 7, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Customer', 28, 'customer-delete', 'web', 7, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Customer', 29, 'customer-payment', 'web', 7, '2025-06-27 21:47:53', '2025-06-27 21:47:53'),
('Supplier', 30, 'supplier-list', 'web', 8, '2025-06-27 21:47:53', '2025-06-27 21:47:54'),
('Supplier', 31, 'supplier-create', 'web', 8, '2025-06-27 21:47:53', '2025-06-27 21:47:54'),
('Supplier', 32, 'supplier-edit', 'web', 8, '2025-06-27 21:47:53', '2025-06-27 21:47:54'),
('Supplier', 33, 'supplier-delete', 'web', 8, '2025-06-27 21:47:53', '2025-06-27 21:47:54'),
('Supplier', 34, 'supplier-payment', 'web', 8, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Purchase', 35, 'purchase-list', 'web', 9, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Purchase', 36, 'purchase-create', 'web', 9, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Purchase', 37, 'purchase-edit', 'web', 9, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Purchase', 38, 'purchase-delete', 'web', 9, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Purchase', 39, 'purchase-payment', 'web', 9, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Purchase Return', 40, 'purchase-return-list', 'web', 10, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Purchase Return', 41, 'purchase-return-create', 'web', 10, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Purchase Return', 42, 'purchase-return-edit', 'web', 10, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Purchase Return', 43, 'purchase-return-delete', 'web', 10, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Sell', 44, 'sell-list', 'web', 11, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Sell', 45, 'sell-create', 'web', 11, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Sell', 46, 'sell-edit', 'web', 11, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Sell', 47, 'sell-delete', 'web', 11, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Sell', 48, 'sell-payment', 'web', 11, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Sell', 49, 'sell-delivery', 'web', 11, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Income', 50, 'income-list', 'web', 12, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Income', 51, 'income-create', 'web', 12, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Income', 52, 'income-edit', 'web', 12, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Income', 53, 'income-delete', 'web', 12, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Income List', 54, 'income-list-list', 'web', 13, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Income List', 55, 'income-list-create', 'web', 13, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Income List', 56, 'income-list-edit', 'web', 13, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Income List', 57, 'income-list-delete', 'web', 13, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Sell Return', 58, 'sell-return-list', 'web', 14, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Sell Return', 59, 'sell-return-create', 'web', 14, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Sell Return', 60, 'sell-return-edit', 'web', 14, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Sell Return', 61, 'sell-return-delete', 'web', 14, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Expense Head', 62, 'expense-head-list', 'web', 15, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Expense Head', 63, 'expense-head-create', 'web', 15, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Expense Head', 64, 'expense-head-edit', 'web', 15, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Expense Head', 65, 'expense-head-delete', 'web', 15, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Expense', 66, 'expense-list', 'web', 16, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Expense', 67, 'expense-create', 'web', 16, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Expense', 68, 'expense-edit', 'web', 16, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Expense', 69, 'expense-delete', 'web', 16, '2025-06-27 21:47:54', '2025-06-27 21:47:54'),
('Damage', 70, 'damage-list', 'web', 17, '2025-06-27 21:47:54', '2025-06-27 21:47:55'),
('Damage', 71, 'damage-create', 'web', 17, '2025-06-27 21:47:54', '2025-06-27 21:47:55'),
('Damage', 72, 'damage-edit', 'web', 17, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Damage', 73, 'damage-delete', 'web', 17, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Bank', 74, 'bank-list', 'web', 18, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Bank', 75, 'bank-create', 'web', 18, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Bank', 76, 'bank-edit', 'web', 18, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Bank', 77, 'bank-delete', 'web', 18, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Bank Transaction', 78, 'bank-transaction', 'web', 19, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Bank Transaction', 79, 'bank-diposit', 'web', 19, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Bank Transaction', 80, 'bank-withdraw', 'web', 19, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Employee', 81, 'employee-list', 'web', 20, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Employee', 82, 'employee-create', 'web', 20, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Employee', 83, 'employee-edit', 'web', 20, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Employee', 84, 'employee-delete', 'web', 20, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Employee Transaction', 85, 'employee-transaction-list', 'web', 21, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Employee Transaction', 86, 'employee-transaction-create', 'web', 21, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Employee Transaction', 87, 'employee-transaction-edit', 'web', 21, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Employee Transaction', 88, 'employee-transaction-delete', 'web', 21, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Investor', 89, 'investor-list', 'web', 22, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Investor', 90, 'investor-create', 'web', 22, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Investor', 91, 'investor-edit', 'web', 22, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Investor', 92, 'investor-delete', 'web', 22, '2025-06-27 21:47:55', '2025-06-27 21:47:55'),
('Investment', 93, 'investment-list', 'web', 23, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Investment', 94, 'investment-create', 'web', 23, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Investment', 95, 'investment-edit', 'web', 23, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Investment', 96, 'investment-delete', 'web', 23, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Unit', 97, 'unit-list', 'web', 24, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Unit', 98, 'unit-create', 'web', 24, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Unit', 99, 'unit-edit', 'web', 24, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Unit', 100, 'unit-delete', 'web', 24, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Customer Type', 101, 'customer-type-list', 'web', 25, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Customer Type', 102, 'customer-type-create', 'web', 25, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Customer Type', 103, 'customer-type-edit', 'web', 25, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Customer Type', 104, 'customer-type-delete', 'web', 25, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Stock', 105, 'stock-list', 'web', 26, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Reports', 106, 'balance-sheet-report-list', 'web', 27, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Reports', 107, 'profit-list', 'web', 27, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Reports', 108, 'delivery-report-list', 'web', 27, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Reports', 109, 'purchase-report-list', 'web', 27, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Reports', 110, 'expense-report-list', 'web', 27, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Reports', 111, 'sell-report-list', 'web', 27, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Reports', 112, 'discount-report-list', 'web', 27, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Reports', 113, 'damage-report-list', 'web', 27, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Maintain', 114, 'asset-maintain', 'web', 28, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Maintain', 115, 'balance-sheet-maintain', 'web', 28, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Maintain', 116, 'general-setting-maintain', 'web', 28, '2025-06-27 21:47:56', '2025-06-27 21:47:56'),
('Maintain', 117, 'account-maintain', 'web', 28, '2025-06-27 21:47:56', '2025-06-27 21:47:56');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `price` decimal(28,8) NOT NULL,
  `description` longtext DEFAULT NULL,
  `image` text DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `sub_category_id`, `brand_id`, `unit_id`, `code`, `price`, `description`, `image`, `barcode`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 't-Shirt', 1, 1, 1, 1, 'PRCW40VF', 100.00000000, 'good t-Shirt', '1756192571.png', '123', 1, 0, '2025-08-26 01:16:11', '2025-08-26 01:16:11');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` varchar(255) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_qty` int(11) NOT NULL,
  `main_price` decimal(28,8) DEFAULT NULL,
  `total_price` decimal(28,8) DEFAULT NULL,
  `commission` decimal(28,8) DEFAULT NULL,
  `discount` decimal(28,8) DEFAULT NULL,
  `payment_received` decimal(28,8) DEFAULT NULL,
  `due_to_company` decimal(28,8) DEFAULT NULL,
  `advance` decimal(28,8) DEFAULT NULL,
  `purchase_revision_advance` decimal(28,8) DEFAULT NULL,
  `invoice_no` varchar(40) DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `entry_date` date NOT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `supplier_id`, `purchase_date`, `total_qty`, `main_price`, `total_price`, `commission`, `discount`, `payment_received`, `due_to_company`, `advance`, `purchase_revision_advance`, `invoice_no`, `entry_by`, `entry_date`, `update_by`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, '12', '2025-08-26 08:22:07', 90, 9000.00000000, 9000.00000000, 0.00000000, 0.00000000, 7000.00000000, 0.00000000, 2000.00000000, 0.00000000, 'purchase-invoice-1', 1, '2025-08-26', 1, 0, '2025-08-26 02:19:08', '2025-08-26 02:22:07'),
(2, '12', '2025-08-26 08:47:23', 90, 9000.00000000, 9000.00000000, 0.00000000, 0.00000000, 9000.00000000, 0.00000000, 0.00000000, 0.00000000, 'purchase-invoice-2', 1, '2025-08-26', 1, 0, '2025-08-26 02:44:36', '2025-08-26 02:47:23'),
(3, '12', '2025-08-26 08:51:28', 20, 2000.00000000, 2000.00000000, 0.00000000, 0.00000000, 4000.00000000, 0.00000000, 0.00000000, 2000.00000000, 'purchase-invoice-3', 1, '2025-08-26', 1, 0, '2025-08-26 02:50:22', '2025-08-26 02:51:28'),
(4, '12', '2025-08-26 08:53:19', 50, 5000.00000000, 5000.00000000, 0.00000000, 0.00000000, 4000.00000000, 0.00000000, 1000.00000000, 0.00000000, 'purchase-invoice-4', 1, '2025-08-26', 1, 0, '2025-08-26 02:52:09', '2025-08-26 02:53:19'),
(5, '12', '2025-08-26 08:54:00', 30, 3000.00000000, 3000.00000000, 0.00000000, 0.00000000, 3000.00000000, 0.00000000, 0.00000000, 0.00000000, 'purchase-invoice-5', 1, '2025-08-26', 1, 0, '2025-08-26 02:54:10', '2025-08-26 03:08:46'),
(6, '12', '2025-08-26 09:12:45', 30, 3000.00000000, 3000.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, 0.00000000, 2000.00000000, 'purchase-invoice-6', 1, '2025-08-26', 1, 0, '2025-08-26 03:09:55', '2025-08-26 03:12:45'),
(7, '12', '2025-08-26 09:13:00', 20, 2000.00000000, 2000.00000000, 0.00000000, 0.00000000, 2000.00000000, 0.00000000, 1000.00000000, NULL, 'purchase-invoice-7', 1, '2025-08-26', 1, 0, '2025-08-26 03:13:33', '2025-08-26 03:17:03'),
(8, '12', '2025-08-26 09:17:00', 20, 2000.00000000, 2000.00000000, 0.00000000, 0.00000000, 3000.00000000, 0.00000000, 2000.00000000, NULL, 'purchase-invoice-8', 1, '2025-08-26', 1, 0, '2025-08-26 03:17:39', '2025-08-26 03:30:31'),
(9, '12', '2025-08-26 09:30:55', 30, 3000.00000000, 3000.00000000, 0.00000000, 0.00000000, 4000.00000000, 0.00000000, 1000.00000000, 1000.00000000, 'purchase-invoice-9', 1, '2025-08-26', 1, 0, '2025-08-26 03:21:15', '2025-08-26 03:30:55'),
(10, '12', '2025-08-26 09:32:08', 30, 3000.00000000, 3000.00000000, NULL, NULL, 2000.00000000, 0.00000000, 1000.00000000, NULL, 'purchase-invoice-10', 1, '2025-08-26', NULL, 0, '2025-08-26 03:32:08', '2025-08-26 03:32:08'),
(11, '12', '2025-08-26 09:32:38', 30, 3000.00000000, 3000.00000000, NULL, NULL, 2000.00000000, 0.00000000, 1000.00000000, NULL, 'purchase-invoice-11', 1, '2025-08-26', NULL, 0, '2025-08-26 03:32:38', '2025-08-26 03:32:38'),
(12, '12', '2025-08-26 09:39:47', 30, 3000.00000000, 3000.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, 0.00000000, 2000.00000000, 'purchase-invoice-12', 1, '2025-08-26', 1, 0, '2025-08-26 03:35:58', '2025-08-26 03:39:47'),
(13, '12', '2025-08-26 09:45:13', 40, 4000.00000000, 4000.00000000, 0.00000000, 0.00000000, NULL, 0.00000000, 4000.00000000, 0.00000000, 'purchase-invoice-13', 1, '2025-08-26', 1, 0, '2025-08-26 03:40:43', '2025-08-26 03:45:13'),
(14, '14', '2025-08-26 10:04:08', 100, 10000.00000000, 10000.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, 5000.00000000, 0.00000000, 'purchase-invoice-14', 1, '2025-08-26', 1, 0, '2025-08-26 03:53:47', '2025-08-26 04:04:08'),
(15, '14', '2025-08-26 10:19:44', 90, 9000.00000000, 9000.00000000, 0.00000000, 0.00000000, 10000.00000000, 0.00000000, NULL, 1000.00000000, 'purchase-invoice-15', 1, '2025-08-26', 1, 0, '2025-08-26 04:15:19', '2025-08-26 04:19:44'),
(16, '14', '2025-08-26 10:24:30', 40, 4000.00000000, 4000.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, NULL, 1000.00000000, 'purchase-invoice-16', 1, '2025-08-26', 1, 0, '2025-08-26 04:23:57', '2025-08-26 04:24:30'),
(17, '14', '2025-08-26 10:26:30', 40, 4000.00000000, 4000.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, NULL, 1000.00000000, 'purchase-invoice-17', 1, '2025-08-26', 1, 0, '2025-08-26 04:25:42', '2025-08-26 04:26:30'),
(18, '14', '2025-08-26 10:35:16', 40, 4000.00000000, 4000.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, NULL, 1000.00000000, 'purchase-invoice-18', 1, '2025-08-26', 1, 0, '2025-08-26 04:33:34', '2025-08-26 04:35:16'),
(19, '14', '2025-08-26 10:41:36', 40, 4000.00000000, 4000.00000000, 0.00000000, 0.00000000, 4000.00000000, 0.00000000, NULL, 1000.00000000, 'purchase-invoice-19', 1, '2025-08-26', 1, 0, '2025-08-26 04:36:19', '2025-08-26 04:41:36'),
(20, '14', '2025-08-26 10:44:29', 40, 4000.00000000, 4000.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, NULL, 1000.00000000, 'purchase-invoice-20', 1, '2025-08-26', 1, 0, '2025-08-26 04:42:24', '2025-08-26 04:44:29'),
(21, '14', '2025-08-26 10:42:24', 50, 5000.00000000, 5000.00000000, NULL, NULL, 5000.00000000, 0.00000000, NULL, NULL, 'purchase-invoice-21', 1, '2025-08-26', NULL, 0, '2025-08-26 04:42:24', '2025-08-26 04:42:24'),
(22, '14', '2025-08-26 10:48:13', 40, 4000.00000000, 4000.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, NULL, 1000.00000000, 'purchase-invoice-22', 1, '2025-08-26', 1, 0, '2025-08-26 04:47:03', '2025-08-26 04:48:13'),
(23, '14', '2025-08-26 10:53:17', 35, 3500.00000000, 3500.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, NULL, 1500.00000000, 'purchase-invoice-23', 1, '2025-08-26', 1, 0, '2025-08-26 04:52:11', '2025-08-26 04:53:17'),
(24, '14', '2025-08-26 10:57:39', 40, 4000.00000000, 4000.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, NULL, 1000.00000000, 'purchase-invoice-24', 1, '2025-08-26', 1, 0, '2025-08-26 04:57:18', '2025-08-26 04:57:39'),
(25, '14', '2025-08-26 11:00:48', 40, 4000.00000000, 4000.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, NULL, 1000.00000000, 'purchase-invoice-25', 1, '2025-08-26', 1, 0, '2025-08-26 05:00:08', '2025-08-26 05:00:48'),
(26, '14', '2025-08-26 11:02:00', 40, 4000.00000000, 4000.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, NULL, 1000.00000000, 'purchase-invoice-26', 1, '2025-08-26', 1, 0, '2025-08-26 05:02:51', '2025-08-26 05:06:09'),
(27, '14', '2025-08-26 11:26:41', 40, 4000.00000000, 4000.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, NULL, 1000.00000000, 'purchase-invoice-27', 1, '2025-08-26', 1, 0, '2025-08-26 05:08:17', '2025-08-26 05:26:41'),
(28, '14', '2025-08-26 11:33:08', 40, 4000.00000000, 4000.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, NULL, 1000.00000000, 'purchase-invoice-28', 1, '2025-08-26', 1, 0, '2025-08-26 05:28:42', '2025-08-26 05:33:08'),
(29, '14', '2025-08-26 11:36:11', 50, 5000.00000000, 5000.00000000, NULL, NULL, 5000.00000000, 0.00000000, NULL, NULL, 'purchase-invoice-29', 1, '2025-08-26', NULL, 0, '2025-08-26 05:36:11', '2025-08-26 05:36:11'),
(30, '14', '2025-08-26 11:38:08', 30, 3000.00000000, 3000.00000000, 0.00000000, 0.00000000, 5000.00000000, 0.00000000, NULL, 2000.00000000, 'purchase-invoice-30', 1, '2025-08-26', 1, 0, '2025-08-26 05:37:06', '2025-08-26 05:38:08');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_batches`
--

CREATE TABLE `purchase_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `batch_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_batches`
--

INSERT INTO `purchase_batches` (`id`, `supplier_id`, `purchase_id`, `batch_code`, `created_at`, `updated_at`) VALUES
(1, 12, 1, 'SQ1', '2025-08-26 02:19:08', '2025-08-26 02:19:08'),
(2, 12, 2, 'SQ2', '2025-08-26 02:44:36', '2025-08-26 02:44:36'),
(3, 12, 3, 'SQ3', '2025-08-26 02:50:22', '2025-08-26 02:50:22'),
(4, 12, 4, 'SQ4', '2025-08-26 02:52:09', '2025-08-26 02:52:09'),
(5, 12, 5, 'SQ5', '2025-08-26 02:54:10', '2025-08-26 02:54:10'),
(6, 12, 6, 'SQ6', '2025-08-26 03:09:55', '2025-08-26 03:09:55'),
(7, 12, 7, 'SQ7', '2025-08-26 03:13:33', '2025-08-26 03:13:33'),
(8, 12, 8, 'SQ8', '2025-08-26 03:17:39', '2025-08-26 03:17:39'),
(9, 12, 9, 'SQ9', '2025-08-26 03:21:15', '2025-08-26 03:21:15'),
(10, 12, 10, 'SQ10', '2025-08-26 03:32:08', '2025-08-26 03:32:08'),
(11, 12, 11, 'SQ11', '2025-08-26 03:32:38', '2025-08-26 03:32:38'),
(12, 12, 12, 'SQ12', '2025-08-26 03:35:58', '2025-08-26 03:35:58'),
(13, 12, 13, 'SQ13', '2025-08-26 03:40:43', '2025-08-26 03:40:43'),
(14, 14, 14, 'SQ14', '2025-08-26 03:53:47', '2025-08-26 03:53:47'),
(15, 14, 15, 'SQ15', '2025-08-26 04:15:19', '2025-08-26 04:15:19'),
(16, 14, 16, 'SQ16', '2025-08-26 04:23:57', '2025-08-26 04:23:57'),
(17, 14, 17, 'SQ17', '2025-08-26 04:25:42', '2025-08-26 04:25:42'),
(18, 14, 18, 'SQ18', '2025-08-26 04:33:34', '2025-08-26 04:33:34'),
(19, 14, 19, 'SQ19', '2025-08-26 04:36:19', '2025-08-26 04:36:19'),
(20, 14, 20, 'SQ20', '2025-08-26 04:42:24', '2025-08-26 04:42:24'),
(21, 14, 21, 'SQ21', '2025-08-26 04:42:24', '2025-08-26 04:42:24'),
(22, 14, 22, 'SQ22', '2025-08-26 04:47:03', '2025-08-26 04:47:03'),
(23, 14, 23, 'SQ23', '2025-08-26 04:52:11', '2025-08-26 04:52:12'),
(24, 14, 24, 'SQ24', '2025-08-26 04:57:18', '2025-08-26 04:57:18'),
(25, 14, 25, 'SQ25', '2025-08-26 05:00:08', '2025-08-26 05:00:08'),
(26, 14, 26, 'SQ26', '2025-08-26 05:02:51', '2025-08-26 05:02:51'),
(27, 14, 27, 'SQ27', '2025-08-26 05:08:17', '2025-08-26 05:08:17'),
(28, 14, 28, 'SQ28', '2025-08-26 05:28:42', '2025-08-26 05:28:42'),
(29, 14, 29, 'SQ29', '2025-08-26 05:36:11', '2025-08-26 05:36:11'),
(30, 14, 30, 'SQ30', '2025-08-26 05:37:06', '2025-08-26 05:37:06');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(28,8) NOT NULL,
  `avg_purchase_price` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `qty` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL COMMENT 'warehouses.id',
  `supplier_name` varchar(255) NOT NULL,
  `total_amount` decimal(28,8) NOT NULL,
  `update_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `price`, `avg_purchase_price`, `qty`, `warehouse_id`, `supplier_name`, `total_amount`, `update_by`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 100.00000000, 100.00000000, 90, 1, 'fahruk', 9000.00000000, 1, '2025-08-26 02:22:07', '2025-08-26 02:22:07'),
(5, 2, 1, 100.00000000, 100.00000000, 90, 1, 'fahruk', 9000.00000000, 1, '2025-08-26 02:47:23', '2025-08-26 02:47:23'),
(8, 3, 1, 100.00000000, 100.00000000, 20, 1, 'fahruk', 2000.00000000, 1, '2025-08-26 02:51:28', '2025-08-26 02:51:28'),
(11, 4, 1, 100.00000000, 100.00000000, 50, 1, 'fahruk', 5000.00000000, 1, '2025-08-26 02:53:19', '2025-08-26 02:53:19'),
(13, 5, 1, 100.00000000, 100.00000000, 30, 1, 'fahruk', 3000.00000000, 1, '2025-08-26 02:54:35', '2025-08-26 02:54:35'),
(16, 6, 1, 100.00000000, 100.00000000, 30, 1, 'fahruk', 3000.00000000, 1, '2025-08-26 03:12:45', '2025-08-26 03:12:45'),
(17, 7, 1, 100.00000000, 100.00000000, 30, 1, 'fahruk', 3000.00000000, 1, '2025-08-26 03:13:33', '2025-08-26 03:13:33'),
(18, 8, 1, 100.00000000, 100.00000000, 50, 1, 'fahruk', 5000.00000000, 1, '2025-08-26 03:17:39', '2025-08-26 03:17:39'),
(20, 9, 1, 100.00000000, 100.00000000, 30, 1, 'fahruk', 3000.00000000, 1, '2025-08-26 03:30:55', '2025-08-26 03:30:55'),
(21, 10, 1, 100.00000000, 100.00000000, 30, 1, 'fahruk', 3000.00000000, 1, '2025-08-26 03:32:08', '2025-08-26 03:32:08'),
(22, 11, 1, 100.00000000, 100.00000000, 30, 1, 'fahruk', 3000.00000000, 1, '2025-08-26 03:32:38', '2025-08-26 03:32:38'),
(29, 12, 1, 100.00000000, 100.00000000, 30, 1, 'fahruk', 3000.00000000, 1, '2025-08-26 03:39:47', '2025-08-26 03:39:47'),
(35, 13, 1, 100.00000000, 100.00000000, 40, 1, 'fahruk', 4000.00000000, 1, '2025-08-26 03:45:13', '2025-08-26 03:45:13'),
(45, 14, 1, 100.00000000, 100.00000000, 100, 1, 'tonni', 10000.00000000, 1, '2025-08-26 04:04:08', '2025-08-26 04:04:08'),
(50, 15, 1, 100.00000000, 100.00000000, 90, 1, 'tonni', 9000.00000000, 1, '2025-08-26 04:19:44', '2025-08-26 04:19:44'),
(53, 16, 1, 100.00000000, 100.00000000, 40, 1, 'tonni', 4000.00000000, 1, '2025-08-26 04:24:30', '2025-08-26 04:24:30'),
(56, 17, 1, 100.00000000, 100.00000000, 40, 1, 'tonni', 4000.00000000, 1, '2025-08-26 04:26:30', '2025-08-26 04:26:30'),
(59, 18, 1, 100.00000000, 100.00000000, 40, 1, 'tonni', 4000.00000000, 1, '2025-08-26 04:35:16', '2025-08-26 04:35:16'),
(61, 19, 1, 100.00000000, 100.00000000, 60, 1, 'tonni', 6000.00000000, 1, '2025-08-26 04:36:29', '2025-08-26 04:36:29'),
(63, 21, 1, 100.00000000, 100.00000000, 50, 1, 'tonni', 5000.00000000, 1, '2025-08-26 04:42:24', '2025-08-26 04:42:24'),
(64, 20, 1, 100.00000000, 100.00000000, 60, 1, 'tonni', 6000.00000000, 1, '2025-08-26 04:42:31', '2025-08-26 04:42:31'),
(66, 22, 1, 100.00000000, 100.00000000, 60, 1, 'tonni', 6000.00000000, 1, '2025-08-26 04:47:10', '2025-08-26 04:47:10'),
(68, 23, 1, 100.00000000, 100.00000000, 60, 1, 'tonni', 6000.00000000, 1, '2025-08-26 04:52:18', '2025-08-26 04:52:18'),
(71, 24, 1, 100.00000000, 100.00000000, 40, 1, 'tonni', 4000.00000000, 1, '2025-08-26 04:57:39', '2025-08-26 04:57:39'),
(74, 25, 1, 100.00000000, 100.00000000, 40, 1, 'tonni', 4000.00000000, 1, '2025-08-26 05:00:48', '2025-08-26 05:00:48'),
(77, 26, 1, 100.00000000, 100.00000000, 40, 1, 'tonni', 4000.00000000, 1, '2025-08-26 05:06:09', '2025-08-26 05:06:09'),
(80, 27, 1, 100.00000000, 100.00000000, 40, 1, 'tonni', 4000.00000000, 1, '2025-08-26 05:26:41', '2025-08-26 05:26:41'),
(83, 28, 1, 100.00000000, 100.00000000, 40, 1, 'tonni', 4000.00000000, 1, '2025-08-26 05:33:08', '2025-08-26 05:33:08'),
(84, 29, 1, 100.00000000, 100.00000000, 50, 1, 'tonni', 5000.00000000, 1, '2025-08-26 05:36:11', '2025-08-26 05:36:11'),
(87, 30, 1, 100.00000000, 100.00000000, 30, 1, 'tonni', 3000.00000000, 1, '2025-08-26 05:38:08', '2025-08-26 05:38:08');

-- --------------------------------------------------------

--
-- Table structure for table `receivables`
--

CREATE TABLE `receivables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sell_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `receivable_head_id` int(11) NOT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `entry_type` int(11) NOT NULL DEFAULT 0,
  `debit_or_credit` varchar(255) DEFAULT NULL,
  `effective_amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `receivable_amount` decimal(28,8) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receivables`
--

INSERT INTO `receivables` (`id`, `sell_id`, `customer_id`, `supplier_id`, `employee_id`, `receivable_head_id`, `invoice_no`, `entry_type`, `debit_or_credit`, `effective_amount`, `receivable_amount`, `description`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 12, NULL, 3, NULL, 0, NULL, 0.00000000, 1500.00000000, NULL, '2025-08-26 01:17:30', '2025-08-26 03:45:13'),
(2, NULL, NULL, 13, NULL, 3, NULL, 0, NULL, 0.00000000, NULL, NULL, '2025-08-26 01:18:30', '2025-08-26 01:18:30'),
(3, NULL, NULL, 14, NULL, 3, NULL, 0, NULL, 0.00000000, 2000.00000000, NULL, '2025-08-26 03:53:02', '2025-08-26 05:38:08');

-- --------------------------------------------------------

--
-- Table structure for table `receivable_heads`
--

CREATE TABLE `receivable_heads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` tinyint(4) NOT NULL COMMENT 'autometic = 1, manual = 2	',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receivable_heads`
--

INSERT INTO `receivable_heads` (`id`, `name`, `description`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Customer Sell Due', 'This is Customer Sell Due.', 1, '2025-06-27 21:48:04', '2025-06-27 21:48:04'),
(2, 'Employee Advance Salary', 'This is Employee Salary Advance', 1, '2025-06-27 21:48:04', '2025-06-27 21:48:04'),
(3, 'Supplier Advance', 'This is Supplier Advance.', 1, '2025-06-27 21:48:04', '2025-06-27 21:48:04'),
(4, 'Customer Due', 'This is Customer Due.', 1, '2025-06-27 21:48:04', '2025-06-27 21:48:04');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2025-06-27 21:47:58', '2025-06-27 21:47:58'),
(2, 'Employee', 'web', '2025-06-27 22:02:29', '2025-06-27 22:02:29');

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
(1, 2),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(5, 2),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(11, 1),
(12, 1),
(13, 1),
(13, 2),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(17, 2),
(18, 1),
(18, 2),
(19, 1),
(19, 2),
(20, 1),
(20, 2),
(21, 1),
(21, 2),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(24, 1),
(24, 2),
(25, 1),
(25, 2),
(26, 1),
(26, 2),
(27, 1),
(27, 2),
(28, 1),
(28, 2),
(29, 1),
(29, 2),
(30, 1),
(30, 2),
(31, 1),
(31, 2),
(32, 1),
(32, 2),
(33, 1),
(33, 2),
(34, 1),
(34, 2),
(35, 1),
(35, 2),
(36, 1),
(36, 2),
(37, 1),
(37, 2),
(38, 1),
(38, 2),
(39, 1),
(39, 2),
(40, 1),
(40, 2),
(41, 1),
(41, 2),
(42, 1),
(42, 2),
(43, 1),
(43, 2),
(44, 1),
(44, 2),
(45, 1),
(45, 2),
(46, 1),
(46, 2),
(47, 1),
(47, 2),
(48, 1),
(48, 2),
(49, 1),
(49, 2),
(50, 1),
(50, 2),
(51, 1),
(51, 2),
(52, 1),
(52, 2),
(53, 1),
(53, 2),
(54, 1),
(54, 2),
(55, 1),
(55, 2),
(56, 1),
(56, 2),
(57, 1),
(57, 2),
(58, 1),
(58, 2),
(59, 1),
(59, 2),
(60, 1),
(60, 2),
(61, 1),
(61, 2),
(62, 1),
(62, 2),
(63, 1),
(63, 2),
(64, 1),
(64, 2),
(65, 1),
(65, 2),
(66, 1),
(66, 2),
(67, 1),
(67, 2),
(68, 1),
(68, 2),
(69, 1),
(69, 2),
(70, 1),
(70, 2),
(71, 1),
(71, 2),
(72, 1),
(72, 2),
(73, 1),
(73, 2),
(74, 1),
(74, 2),
(75, 1),
(75, 2),
(76, 1),
(76, 2),
(77, 1),
(77, 2),
(78, 1),
(78, 2),
(79, 1),
(79, 2),
(80, 1),
(80, 2),
(81, 1),
(81, 2),
(82, 1),
(82, 2),
(83, 1),
(83, 2),
(84, 1),
(84, 2),
(85, 1),
(85, 2),
(86, 1),
(86, 2),
(87, 1),
(87, 2),
(88, 1),
(88, 2),
(89, 1),
(89, 2),
(90, 1),
(90, 2),
(91, 1),
(91, 2),
(92, 1),
(92, 2),
(93, 1),
(93, 2),
(94, 1),
(94, 2),
(95, 1),
(95, 2),
(96, 1),
(96, 2),
(97, 1),
(97, 2),
(98, 1),
(98, 2),
(99, 1),
(99, 2),
(100, 1),
(100, 2),
(101, 1),
(101, 2),
(102, 1),
(102, 2),
(103, 1),
(103, 2),
(104, 1),
(104, 2),
(105, 1),
(105, 2),
(106, 1),
(106, 2),
(107, 1),
(107, 2),
(108, 1),
(108, 2),
(109, 1),
(109, 2),
(110, 1),
(110, 2),
(111, 1),
(111, 2),
(112, 1),
(112, 2),
(113, 1),
(113, 2),
(114, 1),
(114, 2),
(115, 1),
(115, 2),
(116, 1),
(116, 2),
(117, 1),
(117, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sells`
--

CREATE TABLE `sells` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `sell_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_price` decimal(28,8) NOT NULL,
  `profit` decimal(28,8) NOT NULL,
  `total_qty` int(11) NOT NULL,
  `payment_received` decimal(28,8) DEFAULT NULL,
  `due_to_company` decimal(28,8) NOT NULL,
  `advance` decimal(28,8) DEFAULT NULL,
  `sell_revision_advance` decimal(28,8) DEFAULT NULL,
  `invoice_no` varchar(40) DEFAULT NULL,
  `discount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `transport_cost` int(11) NOT NULL DEFAULT 0,
  `labor_cost` int(11) NOT NULL DEFAULT 0,
  `labour_name` varchar(255) DEFAULT NULL,
  `entry_date` date NOT NULL,
  `last_update` date DEFAULT NULL,
  `delivery_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = not delivered, 1 = delivered',
  `delivery_date` timestamp NULL DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sell_records`
--

CREATE TABLE `sell_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sell_id` int(11) NOT NULL,
  `purchase_batch_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `discount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `avg_purchase_price` decimal(28,8) NOT NULL,
  `qty` int(11) NOT NULL,
  `sell_qty` int(11) NOT NULL,
  `sell_price` decimal(28,8) NOT NULL,
  `profit` decimal(28,8) NOT NULL,
  `avg_sell_price` decimal(28,8) NOT NULL,
  `total_amount` decimal(28,8) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('r6JlEPEZdOQ2XWr4B1FyKnEmOUbW7hGKGFHvrDJX', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWUZ3dlZkTE1YQnd4eXRpc1REVjVpRzBrRTdiVXhob3FpUGdLOXNYQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9icy5hY2NvdW50L2NoYXJ0L2FjY291bnQ/YWN0aW9uPXNlYXJjaCZkYXRlPTIwMjUtMDgtMjYmcmFuZ2U9Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1756208290);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `purchase_batch_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `avg_purchase_price` decimal(28,8) NOT NULL,
  `purchase_qty` int(11) NOT NULL,
  `total_purchase_price` decimal(28,8) NOT NULL,
  `stock` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL COMMENT 'warehouses.id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `purchase_id`, `purchase_batch_id`, `product_id`, `product_name`, `avg_purchase_price`, `purchase_qty`, `total_purchase_price`, `stock`, `warehouse_id`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 1, 't-Shirt', 100.00000000, 90, 9000.00000000, 60, 1, '2025-08-26 02:22:07', '2025-08-26 02:38:24'),
(5, 2, 2, 1, 't-Shirt', 100.00000000, 90, 9000.00000000, 90, 1, '2025-08-26 02:47:23', '2025-08-26 02:47:23'),
(8, 3, 3, 1, 't-Shirt', 100.00000000, 20, 2000.00000000, 20, 1, '2025-08-26 02:51:28', '2025-08-26 02:51:28'),
(11, 4, 4, 1, 't-Shirt', 100.00000000, 50, 5000.00000000, 50, 1, '2025-08-26 02:53:20', '2025-08-26 02:53:20'),
(13, 5, 5, 1, 't-Shirt', 100.00000000, 30, 3000.00000000, 30, 1, '2025-08-26 02:54:35', '2025-08-26 02:54:35'),
(16, 6, 6, 1, 't-Shirt', 100.00000000, 30, 3000.00000000, 30, 1, '2025-08-26 03:12:45', '2025-08-26 03:12:45'),
(17, 7, 7, 1, 't-Shirt', 100.00000000, 30, 3000.00000000, 30, 1, '2025-08-26 03:13:33', '2025-08-26 03:13:33'),
(18, 8, 8, 1, 't-Shirt', 100.00000000, 50, 5000.00000000, 50, 1, '2025-08-26 03:17:39', '2025-08-26 03:17:39'),
(20, 9, 9, 1, 't-Shirt', 100.00000000, 30, 3000.00000000, 30, 1, '2025-08-26 03:30:55', '2025-08-26 03:30:55'),
(21, 10, 10, 1, 't-Shirt', 100.00000000, 30, 3000.00000000, 30, 1, '2025-08-26 03:32:08', '2025-08-26 03:32:08'),
(22, 11, 11, 1, 't-Shirt', 100.00000000, 30, 3000.00000000, 30, 1, '2025-08-26 03:32:38', '2025-08-26 03:32:38'),
(29, 12, 12, 1, 't-Shirt', 100.00000000, 30, 3000.00000000, 30, 1, '2025-08-26 03:39:47', '2025-08-26 03:39:47'),
(35, 13, 13, 1, 't-Shirt', 100.00000000, 40, 4000.00000000, 40, 1, '2025-08-26 03:45:13', '2025-08-26 03:45:13'),
(45, 14, 14, 1, 't-Shirt', 100.00000000, 100, 10000.00000000, 100, 1, '2025-08-26 04:04:08', '2025-08-26 04:04:08'),
(50, 15, 15, 1, 't-Shirt', 100.00000000, 90, 9000.00000000, 90, 1, '2025-08-26 04:19:44', '2025-08-26 04:19:44'),
(53, 16, 16, 1, 't-Shirt', 100.00000000, 40, 4000.00000000, 40, 1, '2025-08-26 04:24:30', '2025-08-26 04:24:30'),
(56, 17, 17, 1, 't-Shirt', 100.00000000, 40, 4000.00000000, 40, 1, '2025-08-26 04:26:30', '2025-08-26 04:26:30'),
(59, 18, 18, 1, 't-Shirt', 100.00000000, 40, 4000.00000000, 40, 1, '2025-08-26 04:35:16', '2025-08-26 04:35:16'),
(61, 19, 19, 1, 't-Shirt', 100.00000000, 60, 6000.00000000, 60, 1, '2025-08-26 04:36:29', '2025-08-26 04:36:29'),
(63, 21, 21, 1, 't-Shirt', 100.00000000, 50, 5000.00000000, 50, 1, '2025-08-26 04:42:24', '2025-08-26 04:42:24'),
(64, 20, 20, 1, 't-Shirt', 100.00000000, 60, 6000.00000000, 60, 1, '2025-08-26 04:42:31', '2025-08-26 04:42:31'),
(66, 22, 22, 1, 't-Shirt', 100.00000000, 60, 6000.00000000, 60, 1, '2025-08-26 04:47:10', '2025-08-26 04:47:10'),
(68, 23, 23, 1, 't-Shirt', 100.00000000, 60, 6000.00000000, 60, 1, '2025-08-26 04:52:18', '2025-08-26 04:52:18'),
(71, 24, 24, 1, 't-Shirt', 100.00000000, 40, 4000.00000000, 40, 1, '2025-08-26 04:57:39', '2025-08-26 04:57:39'),
(74, 25, 25, 1, 't-Shirt', 100.00000000, 40, 4000.00000000, 40, 1, '2025-08-26 05:00:48', '2025-08-26 05:00:48'),
(77, 26, 26, 1, 't-Shirt', 100.00000000, 40, 4000.00000000, 40, 1, '2025-08-26 05:06:09', '2025-08-26 05:06:09'),
(80, 27, 27, 1, 't-Shirt', 100.00000000, 40, 4000.00000000, 40, 1, '2025-08-26 05:26:41', '2025-08-26 05:26:41'),
(83, 28, 28, 1, 't-Shirt', 100.00000000, 40, 4000.00000000, 40, 1, '2025-08-26 05:33:08', '2025-08-26 05:33:08'),
(84, 29, 29, 1, 't-Shirt', 100.00000000, 50, 5000.00000000, 50, 1, '2025-08-26 05:36:11', '2025-08-26 05:36:11'),
(87, 30, 30, 1, 't-Shirt', 100.00000000, 30, 3000.00000000, 30, 1, '2025-08-26 05:38:08', '2025-08-26 05:38:08');

-- --------------------------------------------------------

--
-- Table structure for table `stock_items`
--

CREATE TABLE `stock_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `total_purchase_qty` int(11) NOT NULL,
  `total_sell_qty` int(11) NOT NULL DEFAULT 0,
  `purchase_return_qty` int(11) NOT NULL DEFAULT 0,
  `sell_return_qty` int(11) NOT NULL DEFAULT 0,
  `total_damage_qty` int(11) NOT NULL DEFAULT 0,
  `total_delivered` int(11) NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_items`
--

INSERT INTO `stock_items` (`id`, `product_id`, `product_name`, `total_purchase_qty`, `total_sell_qty`, `purchase_return_qty`, `sell_return_qty`, `total_damage_qty`, `total_delivered`, `stock`, `created_at`, `updated_at`) VALUES
(1, 1, 't-Shirt', -300, 0, 30, 0, 0, 0, -330, '2025-08-26 02:19:08', '2025-08-26 05:38:08');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 1, 't-Shirt', 1, 0, '2025-08-26 01:15:35', '2025-08-26 01:15:35');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `entry_date` timestamp NULL DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `advance` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `due` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `code`, `name`, `company`, `mobile`, `address`, `email`, `status`, `entry_by`, `entry_date`, `update_by`, `advance`, `due`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'SUPF4YTI3', 'Tariqul Islam', 'Tariqul Telecom', '0145556', 'Dhaka', 'tariqul@gmail.com', 1, 1, '2025-06-27 22:03:17', NULL, 0.00000000, 0.00000000, 0, '2025-06-27 22:03:17', '2025-06-27 22:03:17'),
(2, 'SUPLYBRST', 'Rafi Islam', 'Rafi Islam', '3124234', 'dhaka', 'rafi@gmail.com', 1, 1, '2025-06-27 22:19:49', NULL, 0.00000000, 0.00000000, 0, '2025-06-27 22:19:49', '2025-06-27 23:26:59'),
(3, 'SUPRGPZIR', 'Tanim', 'Tanim', '32423423', 'Dhaka', 'tamim@gmail.com', 1, 1, '2025-06-27 22:22:36', NULL, 0.00000000, 0.00000000, 0, '2025-06-27 22:22:36', '2025-06-27 22:22:36'),
(4, 'SUPREVBXF', 'Shamin', 'Shamin', '01478', 'dhaka', 'shamim@gmail.com', 1, 1, '2025-06-27 22:38:42', NULL, 0.00000000, 0.00000000, 0, '2025-06-27 22:38:42', '2025-06-27 22:38:42'),
(5, 'SUPDADD0P', 'Tanvir', 'Tanvir', '324243', 'Dhaka', 'tvr@gmail.com', 1, 1, '2025-06-27 22:40:53', NULL, 0.00000000, 0.00000000, 0, '2025-06-27 22:40:53', '2025-06-27 22:40:53'),
(6, 'SUPHNUN05', 'sfaf', 'sfaf', '34234234233', 'sfaf', 'sfaf@gmail.com', 1, 1, '2025-06-27 22:42:22', NULL, 0.00000000, 0.00000000, 0, '2025-06-27 22:42:22', '2025-06-27 22:42:22'),
(7, 'SUPMQOXKC', 'faysal', 'faysal', '2323', 'Dhaka', 'faysal@gmail.com', 1, 1, '2025-06-27 22:49:11', NULL, 0.00000000, 0.00000000, 0, '2025-06-27 22:49:11', '2025-06-27 23:21:04'),
(8, 'SUPEEGHTM', 'yasin', 'yasin', '324222', 'dhaka', 'yasin@gmail.com', 1, 1, '2025-06-27 22:50:43', NULL, 0.00000000, 0.00000000, 0, '2025-06-27 22:50:43', '2025-06-27 22:50:43'),
(9, 'SUPJ2R8CA', 'nishu', 'nishu IT', '01793096434', 'Kuril', 'nishu@gmail.com', 1, 1, '2025-08-24 23:20:41', NULL, 0.00000000, 0.00000000, 0, '2025-08-24 23:20:41', '2025-08-24 23:20:41'),
(10, 'SUPUYJOYS', 'riaz', 'riaz', '01793096400', 'Kuril', 'riaz@gmail.com', 1, 1, '2025-08-24 23:22:11', NULL, 0.00000000, 0.00000000, 0, '2025-08-24 23:22:11', '2025-08-24 23:22:11'),
(11, 'SUPJUYEJX', 'ataur', 'ataur IT', '01478545454', 'Dhaka', 'ataur@gmail.com', 1, 1, '2025-08-24 23:22:50', NULL, 0.00000000, 0.00000000, 0, '2025-08-24 23:22:50', '2025-08-24 23:22:50'),
(12, 'SUPDW0OZ0', 'fahruk', 'fahruk IT', '01794496434', 'Kuril', 'fahruk@gmail.com', 1, 1, '2025-08-26 01:17:30', NULL, 2000.00000000, 0.00000000, 0, '2025-08-26 01:17:30', '2025-08-26 03:45:13'),
(13, 'SUPPJO554', 'fahruk2', 'fahruk IT', '01794496400', 'Kuril', 'fahruk2@gmail.com', 1, 1, '2025-08-26 01:18:30', NULL, 0.00000000, 2000.00000000, 0, '2025-08-26 01:18:30', '2025-08-26 01:20:41'),
(14, 'SUPUJMCED', 'tonni', 'tonni', '01454545457', 'Kuril', 'tonni@gmail.com', 1, 1, '2025-08-26 03:53:02', NULL, 2000.00000000, 0.00000000, 0, '2025-08-26 03:53:02', '2025-08-26 05:38:08');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_returns`
--

CREATE TABLE `supplier_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `purchase_batch_id` int(11) NOT NULL,
  `total_qty` int(11) NOT NULL,
  `total_return_price` decimal(28,8) NOT NULL,
  `entry_date` date NOT NULL,
  `last_update` date DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_returns`
--

INSERT INTO `supplier_returns` (`id`, `supplier_id`, `purchase_batch_id`, `total_qty`, `total_return_price`, `entry_date`, `last_update`, `entry_by`, `update_by`, `is_deleted`, `status`, `created_at`, `updated_at`) VALUES
(1, 12, 1, 10, 1000.00000000, '2025-08-26', NULL, 1, NULL, 0, 0, '2025-08-26 02:23:54', '2025-08-26 02:23:54'),
(2, 12, 1, 10, 1000.00000000, '2025-08-26', NULL, 1, NULL, 0, 0, '2025-08-26 02:32:24', '2025-08-26 02:32:24'),
(3, 12, 1, 10, 1000.00000000, '2025-08-26', NULL, 1, NULL, 0, 0, '2025-08-26 02:38:24', '2025-08-26 02:38:24');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_return_items`
--

CREATE TABLE `supplier_return_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_return_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `return_qty` int(11) NOT NULL,
  `avg_purchase_price` decimal(28,8) NOT NULL,
  `retun_product_price` decimal(28,8) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_return_items`
--

INSERT INTO `supplier_return_items` (`id`, `supplier_return_id`, `purchase_id`, `product_id`, `return_qty`, `avg_purchase_price`, `retun_product_price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 10, 100.00000000, 1000.00000000, '2025-08-26 02:23:54', '2025-08-26 02:23:54'),
(2, 2, 1, 1, 10, 100.00000000, 1000.00000000, '2025-08-26 02:32:24', '2025-08-26 02:32:24'),
(3, 3, 1, 1, 10, 100.00000000, 1000.00000000, '2025-08-26 02:38:24', '2025-08-26 02:38:24');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'ps', 1, 0, '2025-06-27 21:48:12', '2025-06-27 21:48:12'),
(2, 'kg', 1, 0, '2025-06-27 21:48:12', '2025-06-27 21:48:12'),
(3, 'box', 1, 0, '2025-06-27 21:48:12', '2025-06-27 21:48:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@gmail.com', NULL, '$2y$12$yGuFQG9syk/WBGVac4coNuhF5KS0YEiRnFQ3GI0X0yE/oBzt13DIW', NULL, '2025-06-27 21:47:58', '2025-06-27 21:47:58'),
(2, 'Tariq', 'Tariq@gmail.com', NULL, '$2y$12$OEbpHdjv09Hfbz.L4vSzCOXdTB.W4GrSPSQK8vm3fyS9L.cuzLD6i', NULL, '2025-06-28 02:13:46', '2025-06-28 02:13:46'),
(3, 'naim', 'naim@gmail.com', NULL, '$2y$12$DHOStD0R6gVaksSH7CEpROUUAttXH0ThdnRP6A./YKEEIK62Bk/ba', NULL, '2025-06-28 03:01:05', '2025-06-28 03:01:05'),
(4, 'gima', 'gima@gmail.com', NULL, '$2y$12$RuIktR2IqE/niBR9AUaByetc8L47ItxI/jKGVWpNaW8UiQ/ZZZRVK', NULL, '2025-06-28 03:06:46', '2025-06-28 03:06:46'),
(5, 'zira', 'zira@gmail.com', NULL, '$2y$12$iKLUomJzmSiSZk85smnIMuJDGmI.OKzb313uZVGw8hel6vM2HWfGK', NULL, '2025-06-28 03:10:00', '2025-06-28 03:10:00'),
(6, 'gini', 'gini@gmail.com', NULL, '$2y$12$Y4aZBaqhstBrPc22N.9Yo.QYqys/FLAV1MZwLCpFpmGiCcUYBWya6', NULL, '2025-06-28 03:15:04', '2025-06-28 03:15:04'),
(7, 'mehrab', 'mehrab@gmail.com', NULL, '$2y$12$AmlHkG6BbkD4f1k89rFM/Oc0X4MCY2fMUmTsCCVdNPjQ/RMUAD8ZK', NULL, '2025-06-28 03:27:31', '2025-06-28 03:27:31'),
(8, 'maruf', 'sr@gmail.com', NULL, '$2y$12$fnZMjuKHmfoUi4dJRYuMLu/VSald/RchknKVNdWJGhOjhiuzfOHza', NULL, '2025-06-28 22:06:30', '2025-06-28 22:06:30'),
(9, 'fahim', 'fahim@gmail.com', NULL, '$2y$12$C9PaDF7ynEOYN63KR5s94eZ3OR1GI.mAFrv5XbfYiVipSTAzf5MUG', NULL, '2025-06-28 22:15:52', '2025-06-28 22:15:52'),
(10, 'titu', 'titu@gmail.com', NULL, '$2y$12$m1Ti36nzsfnKjcpnRedNxu4Ndetl1lu9SUTENwj.mziEv0ZeZbZFi', NULL, '2025-06-28 22:18:30', '2025-06-28 22:18:30'),
(11, 'faruq', 'faruq@gmail.com', NULL, '$2y$12$eYNnHxnDsFLOkglIndVerew4X9ubXD444/cQrdUgeksSIClFWKmRi', NULL, '2025-06-28 22:21:03', '2025-06-28 22:21:03'),
(12, 'sss1', 'sss1@gmail.com', NULL, '$2y$12$eQ6rav9mPL9of5ObfWGW0eJHNusZY62GEzU7EuWEPJ1YeZjkRpd3C', NULL, '2025-06-28 22:23:32', '2025-06-28 22:23:32'),
(13, 'sfsf', 'sfsf@gmail.com', NULL, '$2y$12$cTvZ6fgRQEwHXPQKxEFjyeOSQS5frFWWArNuNRP.aeTXVc94Th3S2', NULL, '2025-06-28 22:26:56', '2025-06-28 22:26:56'),
(14, 'farhan', 'farhan@gmail.com', NULL, '$2y$12$6wQkLb2G71ZmOUfKCedqlOzrzKZP/DX3vaVe8hRVFFaJyvwWUnUWC', NULL, '2025-08-25 03:07:12', '2025-08-25 03:07:12'),
(15, 'nazmul', 'nazmul@gmail.com', NULL, '$2y$12$U/LMhIciN2Ryd3iMhc5BE.GGUqWrQLmaNC61/XtrRbOBmHygH9Duq', NULL, '2025-08-25 04:02:01', '2025-08-25 04:02:01'),
(16, 'moni', 'moni@gmail.com', NULL, '$2y$12$7Hx0b0SOE3heTk2A7nhKUuOW80aurSX4X4KqfH9jdZ.05tJDfu9xS', NULL, '2025-08-26 00:33:06', '2025-08-26 00:33:06'),
(17, 'fahim1', 'fahim1@gmail.com', NULL, '$2y$12$oBiQt1l2WIGHknpw9JRt7./LrJfNhfPoeQ1CzFZ7PYyABQUBgogoG', NULL, '2025-08-26 00:54:13', '2025-08-26 00:54:13');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_code` varchar(255) DEFAULT NULL,
  `warehouse_name` varchar(255) NOT NULL,
  `warehouse_address` text DEFAULT NULL,
  `warehouse_manager` int(11) DEFAULT NULL COMMENT 'users.id',
  `warehouse_phone` varchar(255) DEFAULT NULL,
  `warehouse_email` varchar(255) DEFAULT NULL,
  `warehouse_status` int(11) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `warehouse_code`, `warehouse_name`, `warehouse_address`, `warehouse_manager`, `warehouse_phone`, `warehouse_email`, `warehouse_status`, `created_at`, `updated_at`) VALUES
(1, 'WH001', 'Main Storage', 'Building A, Zone 3', 1, '123-456-7890', 'mainstorage@example.com', 1, '2025-06-27 21:48:14', '2025-06-27 21:48:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asset_heads`
--
ALTER TABLE `asset_heads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `asset_heads_name_unique` (`name`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_name_unique` (`name`);

--
-- Indexes for table `bs_accounts`
--
ALTER TABLE `bs_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bs_types`
--
ALTER TABLE `bs_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_code_unique` (`code`);

--
-- Indexes for table `customer_returns`
--
ALTER TABLE `customer_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_return_items`
--
ALTER TABLE `customer_return_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_types`
--
ALTER TABLE `customer_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_types_name_unique` (`name`);

--
-- Indexes for table `damages`
--
ALTER TABLE `damages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `demo_users`
--
ALTER TABLE `demo_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_code_unique` (`code`);

--
-- Indexes for table `employee_monthly_transactions`
--
ALTER TABLE `employee_monthly_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_transactions`
--
ALTER TABLE `employee_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_heads`
--
ALTER TABLE `expense_heads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_lists`
--
ALTER TABLE `income_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investors`
--
ALTER TABLE `investors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manage_stocks`
--
ALTER TABLE `manage_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manage_stock_items`
--
ALTER TABLE `manage_stock_items`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payables`
--
ALTER TABLE `payables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payable_heads`
--
ALTER TABLE `payable_heads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payable_heads_name_unique` (`name`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_code_unique` (`code`),
  ADD UNIQUE KEY `products_barcode_unique` (`barcode`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_batches`
--
ALTER TABLE `purchase_batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_batches_batch_code_unique` (`batch_code`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receivables`
--
ALTER TABLE `receivables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receivable_heads`
--
ALTER TABLE `receivable_heads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receivable_heads_name_unique` (`name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sells`
--
ALTER TABLE `sells`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sell_records`
--
ALTER TABLE `sell_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_items`
--
ALTER TABLE `stock_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subcategories_name_unique` (`name`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_code_unique` (`code`);

--
-- Indexes for table `supplier_returns`
--
ALTER TABLE `supplier_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_return_items`
--
ALTER TABLE `supplier_return_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `units_name_unique` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_heads`
--
ALTER TABLE `asset_heads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bs_accounts`
--
ALTER TABLE `bs_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bs_types`
--
ALTER TABLE `bs_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer_returns`
--
ALTER TABLE `customer_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_return_items`
--
ALTER TABLE `customer_return_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_types`
--
ALTER TABLE `customer_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `damages`
--
ALTER TABLE `damages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `demo_users`
--
ALTER TABLE `demo_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `employee_monthly_transactions`
--
ALTER TABLE `employee_monthly_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_transactions`
--
ALTER TABLE `employee_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_heads`
--
ALTER TABLE `expense_heads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `income_lists`
--
ALTER TABLE `income_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `investors`
--
ALTER TABLE `investors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `manage_stocks`
--
ALTER TABLE `manage_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manage_stock_items`
--
ALTER TABLE `manage_stock_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `owners`
--
ALTER TABLE `owners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payables`
--
ALTER TABLE `payables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `payable_heads`
--
ALTER TABLE `payable_heads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `purchase_batches`
--
ALTER TABLE `purchase_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `receivables`
--
ALTER TABLE `receivables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `receivable_heads`
--
ALTER TABLE `receivable_heads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sells`
--
ALTER TABLE `sells`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sell_records`
--
ALTER TABLE `sell_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `stock_items`
--
ALTER TABLE `stock_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `supplier_returns`
--
ALTER TABLE `supplier_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `supplier_return_items`
--
ALTER TABLE `supplier_return_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
