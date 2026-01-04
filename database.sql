-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2025 at 07:31 AM
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
  `type` int(11) NOT NULL COMMENT 'Purchase = 1, Sell = 2, bank deposit = 3, bank withdraw = 4, Customer Advance = 5, Supplier Advance = 6, Customer Due = 7, Supplier Due = 8, Expense = 9, Sell Return = 10, Purchase Return = 11, Employee Salary = 12, Customer = 13, Supplier = 14, Income = 15, Investment = 16, Receivable = 17, Payable = 18',
  `balance` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `credit` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `debit` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `description` text DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
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

INSERT INTO `accounts` (`id`, `purchase_id`, `sell_id`, `expense_id`, `sell_return_id`, `purchase_return_id`, `damage_id`, `income_id`, `investment_id`, `payable_id`, `receivable_id`, `asset_id`, `type`, `balance`, `credit`, `debit`, `amount`, `description`, `customer_id`, `supplier_id`, `employee_id`, `payment_method`, `entry_by`, `status`, `entry_date`, `created_at`, `updated_at`) VALUES
(6, 0, 0, 0, 0, 0, 0, 0, 3, NULL, 0, NULL, 15, 1000000.00000000, 1000000.00000000, 0.00000000, 0.00000000, 'Tarikul Investment', 0, 0, 0, 1, 2, 2, '2025-03-19', '2025-03-19 04:21:49', '2025-03-19 04:21:49'),
(7, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 3, 999000.00000000, 0.00000000, 1000.00000000, 0.00000000, 'Cash debited by Bank opening deposit', 0, 0, 0, 1, 2, 1, '2025-03-19', '2025-03-19 04:32:09', '2025-03-19 04:32:09'),
(8, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 3, 1000000.00000000, 1000.00000000, 0.00000000, 0.00000000, 'Bank Opening Deposit', 0, 0, 0, 2, 2, 2, '2025-03-19', '2025-03-19 04:32:09', '2025-03-19 04:32:09'),
(9, 0, 0, 0, 0, 0, 0, 0, 4, NULL, 0, NULL, 15, 2000000.00000000, 1000000.00000000, 0.00000000, 0.00000000, 'Alamin\'s Investmen\\t', 0, 0, 0, 1, 2, 2, '2025-03-19', '2025-03-19 04:40:34', '2025-03-19 04:40:34'),
(10, 2, 0, 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 1, 2000000.00000000, 0.00000000, 0.00000000, 30000.00000000, 'Purchase(SQ2) Products Form Supplier Sophia Martinez with a due of 30000 Tk', 0, 10, 0, 0, 2, 0, '2025-03-19', '2025-03-19 04:46:40', '2025-03-19 04:46:40'),
(11, 3, 0, 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 1, 2000000.00000000, 0.00000000, 0.00000000, 80000.00000000, 'Purchase(SQ3) Products Form Supplier Michael Brown', 0, 9, 0, 0, 2, 0, '2025-03-19', '2025-03-19 04:58:52', '2025-03-19 04:58:52'),
(12, 3, 0, 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 14, 1920000.00000000, 0.00000000, 80000.00000000, 0.00000000, 'Paid 80,000.00 Tk to Supplier Michael Brown for Purchase (SQ3).', 0, 9, 0, 1, 2, 1, '2025-03-19', '2025-03-19 04:58:52', '2025-03-19 04:58:52'),
(13, 4, 0, 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 1, 1920000.00000000, 0.00000000, 0.00000000, 30000.00000000, 'Purchase(SQ4) Products Form Supplier Jane Wilson with a due of 29000 Tk', 0, 7, 0, 0, 2, 0, '2025-03-19', '2025-03-19 05:01:18', '2025-03-19 05:01:18'),
(14, 4, 0, 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 14, 1919000.00000000, 0.00000000, 1000.00000000, 0.00000000, 'Paid 1,000.00 Tk to Supplier Jane Wilson for Purchase (SQ4).', 0, 7, 0, 1, 2, 1, '2025-03-19', '2025-03-19 05:01:18', '2025-03-19 05:01:18'),
(15, 0, 1, 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 2, 1919000.00000000, 0.00000000, 0.00000000, 8000.00000000, 'Sell Products to customer Lisa Carter. Sell invoice (sell-invoice-1). with a due of 8000 Tk', 9, 0, 0, 0, 2, 0, '2025-03-19', '2025-03-19 05:04:52', '2025-03-19 05:04:52'),
(16, 0, 2, 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 2, 1919000.00000000, 0.00000000, 0.00000000, 25500.00000000, 'Sell Products to customer Ahmed Raza. Sell invoice (sell-invoice-2).', 5, 0, 0, 0, 2, 0, '2025-03-19', '2025-03-19 05:11:33', '2025-03-19 05:11:33'),
(17, 0, 2, 0, 0, 0, 0, 0, 0, NULL, 0, NULL, 13, 1944500.00000000, 25500.00000000, 0.00000000, 0.00000000, 'Received 25,500.00 Tk from Customer Ahmed Raza for Sell invoice (sell-invoice-2).', 5, 0, 0, 1, 2, 2, '2025-03-19', '2025-03-19 05:11:33', '2025-03-19 05:11:33'),
(18, 0, 0, 0, 0, 1, 0, 0, 0, NULL, 0, NULL, 11, 1937000.00000000, 0.00000000, 7500.00000000, 0.00000000, 'Purchase return (Jane Wilson) payment of 7500.00 Tk has been successfully paid.', 0, 7, 0, 1, 2, 1, '2025-03-19', '2025-03-19 05:15:08', '2025-03-19 05:15:08'),
(19, 0, 0, 0, 1, 0, 0, 0, 0, NULL, 0, NULL, 10, 1938600.00000000, 1600.00000000, 0.00000000, 0.00000000, 'Sell return (Lisa Carter) payment of 1600.00 Tk has been successfully paid.', 9, 0, 0, 1, 2, 2, '2025-03-19', '2025-03-19 05:18:44', '2025-03-19 05:18:44'),
(20, 0, 0, 0, 0, 0, 0, 1, 0, NULL, 0, NULL, 15, 1938700.00000000, 100.00000000, 0.00000000, 0.00000000, 'Income (Training Fees) of 100 Tk has been successfully received as payment.', 0, 0, 0, 1, 2, 2, '2025-03-19', '2025-03-19 05:39:58', '2025-03-19 05:39:58'),
(21, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 8, 1937700.00000000, 0.00000000, 1000.00000000, 0.00000000, 'Partial due amount of 1000 Tk paid to the supplier Sophia Martinez for previous Purchase (purchase-invoice-2)', 0, 10, 0, 1, 2, 1, '2025-03-22', '2025-03-22 04:08:36', '2025-03-22 04:08:36'),
(22, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 8, 1936700.00000000, 0.00000000, 1000.00000000, 0.00000000, 'Partial due amount of 1000 Tk paid to the supplier Sophia Martinez for previous Purchase (purchase-invoice-2)', 0, 10, 0, 1, 2, 1, '2025-03-22', '2025-03-22 04:08:52', '2025-03-22 04:08:52'),
(23, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 8, 1935700.00000000, 0.00000000, 1000.00000000, 0.00000000, 'Partial due amount of 1000 Tk paid to the supplier Sophia Martinez for previous Purchase (purchase-invoice-2)', 0, 10, 0, 1, 2, 1, '2025-03-22', '2025-03-22 04:09:04', '2025-03-22 04:09:04'),
(24, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 8, 1909700.00000000, 0.00000000, 26000.00000000, 0.00000000, 'Parchase due amount of 26000 Tk paid to the supplier Sophia Martinez for previous Purchase (purchase-invoice-2)', 0, 10, 0, 1, 2, 1, '2025-03-22', '2025-03-22 04:09:18', '2025-03-22 04:09:18'),
(25, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 1905700.00000000, 0.00000000, 4000.00000000, 0.00000000, 'Paid advance payment of 4000 Tk to the supplier Sophia Martinez', 0, 10, 0, 1, 2, 1, '2025-03-22', '2025-03-22 04:09:18', '2025-03-22 04:09:18'),
(26, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 1900700.00000000, 0.00000000, 5000.00000000, 0.00000000, 'Paid advance payment of 5000 Tk to the supplier Shakir', 0, 11, 0, 1, 2, 1, '2025-03-22', '2025-03-22 04:23:34', '2025-03-22 04:23:34'),
(27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 1900800.00000000, 100.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 100 Tk from the supplier Shakir', 0, 11, 0, 1, 2, 2, '2025-03-22', '2025-03-22 05:16:11', '2025-03-22 05:16:11'),
(28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 1900700.00000000, 0.00000000, 100.00000000, 0.00000000, 'Paid advance payment of 100 Tk to the supplier Shakir', 0, 11, 0, 1, 2, 1, '2025-03-22', '2025-03-22 05:17:59', '2025-03-22 05:17:59'),
(29, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 1902700.00000000, 2000.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 2000 Tk from the supplier Shakir', 0, 11, 0, 1, 2, 2, '2025-03-22', '2025-03-22 05:18:56', '2025-03-22 05:18:56'),
(30, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 1902800.00000000, 100.00000000, 0.00000000, 0.00000000, 'Receive advance amount of 100 Tk from the supplier Shakir', 0, 11, 0, 1, 2, 2, '2025-03-22', '2025-03-22 05:19:35', '2025-03-22 05:19:35'),
(31, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1902700.00000000, 0.00000000, 100.00000000, 0.00000000, 'Company give salary to empoyeeShakir amount of 100.00 Tk.', 0, 0, 1, 1, 2, 1, '2025-03-23', '2025-03-23 04:31:02', '2025-03-23 04:31:02'),
(32, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1901800.00000000, 0.00000000, 900.00000000, 0.00000000, 'Company give salary to empoyeeShakir amount of 900.00 Tk.', 0, 0, 1, 1, 2, 1, '2025-03-23', '2025-03-23 04:31:17', '2025-03-23 04:31:17'),
(33, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1901700.00000000, 0.00000000, 100.00000000, 0.00000000, 'Company give salary to empoyeeShakir amount of 100.00 Tk.', 0, 0, 1, 1, 2, 1, '2025-03-23', '2025-03-23 04:31:42', '2025-03-23 04:31:42'),
(34, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1901600.00000000, 0.00000000, 100.00000000, 0.00000000, 'Company give salary to empoyeeShakir amount of 100.00 Tk.', 0, 0, 1, 1, 2, 1, '2025-03-23', '2025-03-23 05:06:04', '2025-03-23 05:06:04'),
(35, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1901500.00000000, 0.00000000, 100.00000000, 0.00000000, 'Company give salary to empoyeeShakir amount of 100.00 Tk.', 0, 0, 1, 1, 2, 1, '2025-03-23', '2025-03-23 05:07:29', '2025-03-23 05:07:29'),
(36, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1901200.00000000, 0.00000000, 300.00000000, 0.00000000, 'Company give salary to empoyeeShakir amount of 300.00 Tk.', 0, 0, 1, 1, 2, 1, '2025-03-23', '2025-03-23 05:22:34', '2025-03-23 05:22:34'),
(37, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1900400.00000000, 0.00000000, 800.00000000, 0.00000000, 'Company give salary to empoyeeShakir amount of 800.00 Tk.', 0, 0, 1, 1, 2, 1, '2025-03-23', '2025-03-23 05:23:29', '2025-03-23 05:23:29'),
(38, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1899400.00000000, 0.00000000, 1000.00000000, 0.00000000, 'Company give salary to empoyeeShakir amount of 1,000.00 Tk.', 0, 0, 1, 1, 2, 1, '2025-03-23', '2025-03-23 05:46:49', '2025-03-23 05:46:49'),
(39, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1899300.00000000, 0.00000000, 100.00000000, 0.00000000, 'Company give salary to empoyeeShakir amount of 100.00 Tk.', 0, 0, 1, 1, 2, 1, '2025-03-23', '2025-03-23 05:47:04', '2025-03-23 05:47:04'),
(40, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1898400.00000000, 0.00000000, 900.00000000, 0.00000000, 'Company give salary to empoyeeShakir amount of 900.00 Tk.', 0, 0, 1, 1, 2, 1, '2025-03-23', '2025-03-23 06:31:10', '2025-03-23 06:31:10'),
(41, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1898200.00000000, 0.00000000, 200.00000000, 0.00000000, 'Company give salary to empoyeeShakir amount of 200.00 Tk.', 0, 0, 1, 1, 2, 1, '2025-03-23', '2025-03-23 06:31:31', '2025-03-23 06:31:31'),
(42, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1898000.00000000, 0.00000000, 200.00000000, 0.00000000, 'Company give salary to empoyeeShakir amount of 200.00 Tk.', 0, 0, 1, 1, 2, 1, '2025-03-23', '2025-03-23 06:32:08', '2025-03-23 06:32:08'),
(43, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1897500.00000000, 0.00000000, 500.00000000, 0.00000000, 'Company give salary to empoyeeShakir amount of 500.00 Tk.', 0, 0, 1, 1, 2, 1, '2025-03-23', '2025-03-23 06:38:07', '2025-03-23 06:38:07'),
(44, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1896500.00000000, 0.00000000, 1000.00000000, 0.00000000, 'Company give salary to empoyeeShakir amount of 1,000.00 Tk.', 0, 0, 1, 1, 2, 1, '2025-03-23', '2025-03-23 06:38:42', '2025-03-23 06:38:42'),
(45, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 9, 1896400.00000000, 0.00000000, 100.00000000, 0.00000000, 'Expense(Shakir) payment of 100 Tk has been successfully paid.', 0, 0, 1, 1, 2, 1, '2025-03-24', '2025-03-24 05:16:41', '2025-03-24 05:16:41');

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `asset_head_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `purchase_date` date NOT NULL,
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(2, 'Ashik', '1231231231231', 'Brac Bank', 'Dokkhin khan', 1000.00000000, 2, '2025-03-19 10:32:09', NULL, NULL, 1, 0, '2025-03-19 04:32:09', '2025-03-19 04:32:09');

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
(1, 8, 2, '', 'Bank Opening Deposit', 1000.00000000, 0.00000000, 1000.00000000, '', 'Ashik', 2, '2025-03-19 10:32:09', 0, 2, '2025-03-19 04:32:09', '2025-03-19 04:32:09');

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
(1, 'TP-Link', 1, 0, '2025-03-18 04:05:06', '2025-03-18 04:05:06'),
(2, 'Morphy Richards', 1, 0, '2025-03-18 04:05:14', '2025-03-18 04:05:14'),
(3, 'Panasonic', 1, 0, '2025-03-18 04:05:30', '2025-03-18 04:05:30'),
(4, 'Philips', 1, 0, '2025-03-18 04:05:44', '2025-03-18 04:05:44'),
(5, 'OnePlus', 1, 0, '2025-03-18 04:05:58', '2025-03-18 04:05:58'),
(6, 'Xiaomi', 1, 0, '2025-03-18 04:06:07', '2025-03-18 04:06:07'),
(7, 'Apple', 1, 0, '2025-03-18 04:06:16', '2025-03-18 04:06:16'),
(8, 'Asus', 1, 0, '2025-03-18 04:06:33', '2025-03-18 04:06:33'),
(9, 'Lenovo', 1, 0, '2025-03-18 04:07:03', '2025-03-18 04:07:03'),
(10, 'Dell', 1, 0, '2025-03-18 04:07:33', '2025-03-18 04:07:33'),
(11, 'HP', 1, 0, '2025-03-18 04:07:58', '2025-03-18 04:07:58'),
(12, 'Sony', 1, 0, '2025-03-18 04:08:08', '2025-03-18 04:08:08'),
(13, 'Haier', 1, 0, '2025-03-18 04:08:18', '2025-03-18 04:08:18'),
(14, 'Whirlpool', 1, 0, '2025-03-18 04:08:50', '2025-03-18 04:08:50'),
(15, 'LG', 1, 0, '2025-03-18 04:09:33', '2025-03-18 04:09:33'),
(16, 'Samsung', 1, 0, '2025-03-18 04:09:57', '2025-03-18 04:09:57');

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
  `type` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `balance` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:88:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:9:\"role-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:11:\"role-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"role-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:11:\"role-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:9:\"user-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:11:\"user-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:9:\"user-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:11:\"user-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:12:\"product-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:14:\"product-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:12:\"product-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:14:\"product-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:13:\"category-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:15:\"category-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:13:\"category-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:15:\"category-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:17:\"sub-category-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:19:\"sub-category-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:17:\"sub-category-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:19:\"sub-category-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:10:\"brand-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:4;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:12:\"brand-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:10:\"brand-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:12:\"brand-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:13:\"customer-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:15:\"customer-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:13:\"customer-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:15:\"customer-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:16:\"customer-payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:13:\"supplier-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:15:\"supplier-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:13:\"supplier-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:15:\"supplier-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:16:\"supplier-payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:13:\"purchase-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:15:\"purchase-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:13:\"purchase-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:15:\"purchase-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:16:\"purchase-payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:20:\"purchase-return-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:22:\"purchase-return-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:20:\"purchase-return-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:22:\"purchase-return-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:9:\"sell-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:11:\"sell-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:9:\"sell-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:11:\"sell-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:12:\"sell-payment\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:13:\"sell-delivery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:11:\"income-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:13:\"income-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:11:\"income-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:13:\"income-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:16:\"income-list-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:18:\"income-list-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:16:\"income-list-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:18:\"income-list-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:57;a:4:{s:1:\"a\";i:58;s:1:\"b\";s:16:\"sell-return-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:18:\"sell-return-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:16:\"sell-return-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:18:\"sell-return-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:61;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:17:\"expense-head-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:62;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:19:\"expense-head-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:63;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:17:\"expense-head-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:64;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:19:\"expense-head-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:65;a:4:{s:1:\"a\";i:66;s:1:\"b\";s:12:\"expense-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:66;a:4:{s:1:\"a\";i:67;s:1:\"b\";s:14:\"expense-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:67;a:4:{s:1:\"a\";i:68;s:1:\"b\";s:12:\"expense-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:68;a:4:{s:1:\"a\";i:69;s:1:\"b\";s:14:\"expense-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:69;a:4:{s:1:\"a\";i:70;s:1:\"b\";s:11:\"damage-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:70;a:4:{s:1:\"a\";i:71;s:1:\"b\";s:13:\"damage-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:71;a:4:{s:1:\"a\";i:72;s:1:\"b\";s:11:\"damage-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:72;a:4:{s:1:\"a\";i:73;s:1:\"b\";s:13:\"damage-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:73;a:4:{s:1:\"a\";i:74;s:1:\"b\";s:9:\"bank-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:74;a:4:{s:1:\"a\";i:75;s:1:\"b\";s:11:\"bank-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:75;a:4:{s:1:\"a\";i:76;s:1:\"b\";s:9:\"bank-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:76;a:4:{s:1:\"a\";i:77;s:1:\"b\";s:11:\"bank-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:77;a:4:{s:1:\"a\";i:78;s:1:\"b\";s:16:\"bank-transaction\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:78;a:4:{s:1:\"a\";i:79;s:1:\"b\";s:12:\"bank-diposit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:79;a:4:{s:1:\"a\";i:80;s:1:\"b\";s:13:\"bank-withdraw\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:80;a:4:{s:1:\"a\";i:81;s:1:\"b\";s:13:\"employee-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:81;a:4:{s:1:\"a\";i:82;s:1:\"b\";s:15:\"employee-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:82;a:4:{s:1:\"a\";i:83;s:1:\"b\";s:13:\"employee-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:83;a:4:{s:1:\"a\";i:84;s:1:\"b\";s:15:\"employee-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:84;a:4:{s:1:\"a\";i:85;s:1:\"b\";s:25:\"employee-transaction-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:85;a:4:{s:1:\"a\";i:86;s:1:\"b\";s:27:\"employee-transaction-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:86;a:4:{s:1:\"a\";i:87;s:1:\"b\";s:25:\"employee-transaction-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:87;a:4:{s:1:\"a\";i:88;s:1:\"b\";s:27:\"employee-transaction-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:3:\"ALL\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:8:\"Employee\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:4:\"some\";s:1:\"c\";s:3:\"web\";}}}', 1742974745);

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
(1, 'Home Appliances', 1, 0, '2025-03-17 07:21:38', '2025-03-17 07:21:38'),
(2, 'Consumer Electronics', 1, 0, '2025-03-17 07:21:47', '2025-03-17 07:21:47'),
(3, 'Computers & Accessories', 1, 0, '2025-03-17 07:22:06', '2025-03-17 07:22:06'),
(4, 'Mobile & Accessories', 1, 0, '2025-03-17 07:22:14', '2025-03-17 07:22:14'),
(5, 'Gaming & Entertainment', 1, 0, '2025-03-17 07:22:26', '2025-03-17 07:22:26'),
(6, 'Kitchen Electronics', 1, 0, '2025-03-17 07:22:55', '2025-03-17 07:22:55'),
(7, 'Networking & Smart Devices', 1, 0, '2025-03-17 07:23:04', '2025-03-17 07:23:04');

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
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `code`, `name`, `company`, `mobile`, `address`, `comments`, `email`, `status`, `type`, `entry_by`, `entry_date`, `advance`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'CUS1IMO01', 'Alex Johnson', 'GreenTech Solutions', '121212121212121', '45 Innovation Street', NULL, 'alex@greentech.com', 1, '10', 2, '2025-03-19 03:43:25', 0.00000000, 0, '2025-03-19 03:43:25', '2025-03-19 03:43:25'),
(2, 'CUSEK7B9Y', 'Sarah Lee', 'Smart Home Buyers', '442089412345', '78 Baker Street', NULL, 'sarah@smarthome.com', 1, '10', 2, '2025-03-19 03:48:36', 0.00000000, 0, '2025-03-19 03:48:36', '2025-03-19 03:48:36'),
(3, 'CUSLE5Y8J', 'James Carter', 'Future Vision Ltd.', '14159874567', 'james@futurevision.com', NULL, 'james@futurevision.com', 1, '9', 2, '2025-03-19 03:49:59', 0.00000000, 0, '2025-03-19 03:49:59', '2025-03-19 03:49:59'),
(4, 'CUSRCXHBJ', 'David Wilson', 'ABC', '423232323', '32 Market Plaza', NULL, 'david@premiumelec.com', 1, '7', 2, '2025-03-19 03:53:14', 0.00000000, 0, '2025-03-19 03:53:14', '2025-03-19 03:53:14'),
(5, 'CUS83DONA', 'Ahmed Raza', 'Mobile Mania', '234234234234234', '10 Orchard Tower', NULL, 'ahmed@mobilemania.sg', 1, '5', 2, '2025-03-19 03:55:50', 0.00000000, 0, '2025-03-19 03:55:50', '2025-03-19 03:55:50'),
(6, 'CUSROGTPQ', 'Maria Gonzalez', 'Computech Traders', '97141239876', 'Office 205, Tech Park', NULL, 'maria@computech.ae', 1, '7', 2, '2025-03-19 03:58:19', 0.00000000, 0, '2025-03-19 03:58:19', '2025-03-19 03:58:19'),
(7, 'CUSUKPQ8Q', 'Sophie Martin', 'Digital Store', '33187659876', '21 Rue Lafayette', NULL, 'sophie@digitalstore.fr', 1, '6', 2, '2025-03-19 04:00:32', 0.00000000, 0, '2025-03-19 04:00:32', '2025-03-19 04:00:32'),
(8, 'CUSYSISSK', 'Michael Adams', 'Global Tech Solutions', '12135552345', '99 Innovation Ave', NULL, 'michael@globaltech.com', 1, '6', 2, '2025-03-19 04:11:07', 0.00000000, 0, '2025-03-19 04:11:07', '2025-03-19 04:11:07'),
(9, 'CUSYPEF4D', 'Lisa Carter', 'Home Appliances Mart', '442089478956', '12 Oakwood Street', NULL, 'lisa@homeappliances.com', 1, '4', 2, '2025-03-19 04:12:22', 0.00000000, 0, '2025-03-19 04:12:22', '2025-03-19 04:12:22'),
(10, 'CUSUCY7IA', 'David Thompson', 'NextGen IT Solutions', '14159873456', '200 Tech Valley Drive', NULL, 'david@nextgenit.com', 1, '7', 2, '2025-03-19 04:13:11', 0.00000000, 0, '2025-03-19 04:13:11', '2025-03-19 04:13:11');

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

--
-- Dumping data for table `customer_returns`
--

INSERT INTO `customer_returns` (`id`, `customer_id`, `base_product_id`, `total_qty`, `total_return_price`, `entry_date`, `last_update`, `entry_by`, `update_by`, `is_deleted`, `status`, `created_at`, `updated_at`) VALUES
(1, 9, 9, 1, 1600.00000000, '2025-03-19', NULL, 2, NULL, 0, 0, '2025-03-19 05:18:44', '2025-03-19 05:18:44');

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

--
-- Dumping data for table `customer_return_items`
--

INSERT INTO `customer_return_items` (`id`, `customer_return_id`, `purchase_batch_id`, `purchase_id`, `sell_id`, `product_id`, `return_qty`, `avg_sell_price`, `retun_sell_price`, `retun_total_sell_price`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 4, 1, 9, 1, 1600.00000000, 1600.00000000, 1600.00000000, '2025-03-19 05:18:44', '2025-03-19 05:18:44');

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
(1, 'Local Business', 1, 0, '2025-03-19 03:40:28', '2025-03-19 03:40:28'),
(2, 'Institutional Buyer', 1, 0, '2025-03-19 03:40:33', '2025-03-19 03:40:33'),
(3, 'Government Client', 1, 0, '2025-03-19 03:40:41', '2025-03-19 03:40:50'),
(4, 'VIP Customer', 1, 0, '2025-03-19 03:40:57', '2025-03-19 03:40:57'),
(5, 'Distributor', 1, 0, '2025-03-19 03:41:04', '2025-03-19 03:41:04'),
(6, 'Online Buyer', 1, 0, '2025-03-19 03:41:12', '2025-03-19 03:41:12'),
(7, 'B2B Reseller', 1, 0, '2025-03-19 03:41:19', '2025-03-19 03:41:19'),
(8, 'Corporate Customer', 1, 0, '2025-03-19 03:41:27', '2025-03-19 03:41:27'),
(9, 'Wholesale Buyer', 1, 0, '2025-03-19 03:41:34', '2025-03-19 03:41:34'),
(10, 'Retail Customer', 1, 0, '2025-03-19 03:42:00', '2025-03-19 03:42:00');

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

--
-- Dumping data for table `damages`
--

INSERT INTO `damages` (`id`, `purchase_id`, `product_id`, `purchase_batch_id`, `supplier_id`, `price`, `total_damage_price`, `qty`, `total_qty`, `description`, `conversation`, `entry_date`, `replacement_repair_date`, `last_update`, `entry_by`, `update_by`, `is_deleted`, `status`, `damage_status`, `created_at`, `updated_at`) VALUES
(1, 4, 9, 4, 7, 1500.00000000, 3000.00000000, 2, 11, 'ABCD', NULL, '2025-03-19', NULL, NULL, 2, NULL, 0, 0, 1, '2025-03-19 06:02:51', '2025-03-19 06:02:51');

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

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`id`, `sell_id`, `customer_id`, `qty`, `entry_by`, `created_at`, `updated_at`) VALUES
(1, 2, 5, 3, 2, '2025-03-19 06:16:57', '2025-03-19 06:16:57');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nid` varchar(25) NOT NULL,
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
(1, 'Shakir', 'EMPSJCMK7', '26', 'A error amet amet', 'becanoxa@mailinator.com', '24', 'Amal Kelly', NULL, '2006-11-01', 900.00000000, 100.00000000, '1', 2, 0, '2025-03-23 06:37:25', NULL, '2025-03-23 06:37:25', '2025-03-23 06:37:25');

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
  `bs_due` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = calculated, 0 = not calculated',
  `bs_advance` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = calculated, 0 = not calculated	',
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_monthly_transactions`
--

INSERT INTO `employee_monthly_transactions` (`id`, `employee_id`, `month`, `net_salary`, `salary_amount`, `punishment`, `total_paid`, `due`, `advance`, `bs_due`, `bs_advance`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-03', 1000.00000000, 1000.00000000, 0.00000000, 1500.00000000, 500.00000000, 500.00000000, 0, 0, 0, '2025-03-23 06:37:51', '2025-03-23 06:38:42');

-- --------------------------------------------------------

--
-- Table structure for table `employee_transactions`
--

CREATE TABLE `employee_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `monthly_transaction_id` int(11) NOT NULL,
  `salary_date` timestamp NULL DEFAULT NULL,
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

--
-- Dumping data for table `employee_transactions`
--

INSERT INTO `employee_transactions` (`id`, `employee_id`, `monthly_transaction_id`, `salary_date`, `received_amount`, `punishment`, `description`, `account_id`, `entry_by`, `update_by`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-03-23 06:37:00', 500.00000000, 0.00000000, NULL, NULL, 2, NULL, 0, '2025-03-23 06:38:07', '2025-03-23 06:38:07'),
(2, 1, 1, '2025-03-23 06:38:00', 1000.00000000, 0.00000000, NULL, NULL, 2, NULL, 0, '2025-03-23 06:38:42', '2025-03-23 06:38:42');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_head_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `entry_date` date NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `pending_status` tinyint(4) NOT NULL DEFAULT 0,
  `entry_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expense_head_id`, `employee_id`, `entry_date`, `title`, `description`, `amount`, `pending_status`, `entry_by`, `update_by`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 7, 1, '2025-03-24', 'asdf', 'wfdsasd', 100.00000000, 0, 2, NULL, 0, '2025-03-24 05:16:41', '2025-03-24 05:16:41'),
(2, 6, 1, '2025-03-24', 'qwerqwer', 'asdf', 1000.00000000, 1, 2, NULL, 0, '2025-03-24 05:16:59', '2025-03-24 05:16:59'),
(3, 6, 1, '2025-03-24', 'qwerqwer', 'asdf', 1000.00000000, 1, 2, NULL, 0, '2025-03-24 05:18:31', '2025-03-24 05:18:31');

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

--
-- Dumping data for table `expense_heads`
--

INSERT INTO `expense_heads` (`id`, `name`, `details`, `entry_by`, `update_by`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Courier & Freight Charges', 'Expenses for shipping and delivery services', 2, NULL, 0, '2025-03-19 06:06:17', '2025-03-19 06:06:17'),
(2, 'Salaries & Wages', 'Employee salaries, wages, and benefits.', 2, 2, 0, '2025-03-19 06:09:05', '2025-03-19 06:10:13'),
(3, 'Rent & Utilities', 'Office/shop rent, electricity, water, and gas bills.', 2, NULL, 0, '2025-03-19 06:10:43', '2025-03-19 06:10:43'),
(4, 'Internet & Telephone Expenses', 'Monthly broadband and mobile expenses.', 2, NULL, 0, '2025-03-19 06:11:02', '2025-03-19 06:11:02'),
(5, 'Office Supplies', 'Stationery, printing, and office-related items.', 2, NULL, 0, '2025-03-19 06:11:19', '2025-03-19 06:11:19'),
(6, 'Repair & Maintenance', 'Maintenance of office equipment, machinery, and buildings.', 2, NULL, 0, '2025-03-19 06:13:46', '2025-03-19 06:13:46'),
(7, 'Lunch', 'adsf,', 2, NULL, 0, '2025-03-24 05:16:18', '2025-03-24 05:16:18');

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
  `favicon` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `primary_contact_number` varchar(255) DEFAULT NULL,
  `alternate_contact_number` varchar(255) DEFAULT NULL,
  `primary_email_address` varchar(255) DEFAULT NULL,
  `alternate_email_address` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `pagination` int(11) DEFAULT NULL,
  `subcategory_module` tinyint(1) NOT NULL COMMENT '1 = activated, 0 = deactivated	',
  `brand_module` tinyint(1) NOT NULL COMMENT '1 = activated, 0 = deactivated	',
  `barcode` tinyint(4) NOT NULL COMMENT '1 = activated, 0 = deactivated',
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `logo`, `favicon`, `company_name`, `primary_contact_number`, `alternate_contact_number`, `primary_email_address`, `alternate_email_address`, `website_url`, `pagination`, `subcategory_module`, `brand_module`, `barcode`, `address`, `created_at`, `updated_at`) VALUES
(1, '1738582058.jpg', '1735710929.jpg', 'Bangladesh Software Development', '313131', '1111111111', 'jyqyzolen@mailinator.com', 'lylyxacep@mailinator.com', 'http://youtube.com', 10, 1, 1, 1, 'Nikunja, Dhaka', '2024-12-31 04:28:37', '2025-02-25 06:33:05');

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `income_list_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `entry_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incomes`
--

INSERT INTO `incomes` (`id`, `income_list_id`, `description`, `amount`, `entry_by`, `update_by`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 6, 'Example Training Fees', 100.00000000, 2, NULL, 0, '2025-03-19 05:39:58', '2025-03-19 05:39:58');

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

--
-- Dumping data for table `income_lists`
--

INSERT INTO `income_lists` (`id`, `name`, `details`, `entry_by`, `update_by`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Training Fees', 'Income from providing training sessions or workshops.', 2, NULL, 0, '2025-03-19 05:23:48', '2025-03-19 05:23:48'),
(2, 'Rental Income', 'Earnings from leasing office space, equipment, or property.', 2, NULL, 0, '2025-03-19 05:24:38', '2025-03-19 05:24:38'),
(3, 'Commission Income', 'Revenue earned from commissions on sales or referrals.', 2, NULL, 0, '2025-03-19 05:31:08', '2025-03-19 05:31:08'),
(4, 'Consultancy Fees', 'Income from providing professional or technical consultancy services.', 2, NULL, 0, '2025-03-19 05:35:15', '2025-03-19 05:35:15'),
(5, 'Advertising Revenue', 'Earnings from selling advertising space (e.g., website ads, banners).', 2, NULL, 0, '2025-03-19 05:35:50', '2025-03-19 05:35:50'),
(6, 'Training Fees', 'Income from providing training sessions or workshops.', 2, NULL, 0, '2025-03-19 05:36:46', '2025-03-19 05:36:46');

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
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

INSERT INTO `investments` (`id`, `name`, `amount`, `entry_date`, `payment_method`, `description`, `entry_by`, `created_at`, `updated_at`) VALUES
(3, 'Tarikul', 1000000.00000000, '2025-03-19 10:21:00', 1, 'Tarikul Investment', 2, '2025-03-19 04:21:49', '2025-03-19 04:21:49'),
(4, 'Alamin', 1000000.00000000, '2025-03-19 10:39:00', 1, 'Alamin\'s Investmen\\t', 2, '2025-03-19 04:40:34', '2025-03-19 04:40:34');

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
  `description` text DEFAULT NULL,
  `entry_date` datetime DEFAULT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `entry_by` int(11) DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `description`, `entry_date`, `amount`, `entry_by`, `update_by`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Tarikul\'s investment', '2025-03-19 10:19:00', 1000000.00000000, 2, NULL, 0, '2025-03-19 04:19:34', '2025-03-19 04:19:34'),
(2, 'Opening Balance For Bank (Amount is 1000)', '2025-03-19 10:24:02', 1000.00000000, 2, NULL, 0, '2025-03-19 04:24:02', '2025-03-19 04:24:02'),
(3, 'Due242500 Tk for this purchase', '2025-03-19 10:40:49', 242500.00000000, 2, NULL, 0, '2025-03-19 04:40:49', '2025-03-19 04:40:49');

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
(32, '2025_01_05_040352_create_permission_tables', 1),
(33, '2025_01_11_091441_create_expense_heads_table', 1),
(34, '2025_01_29_053711_create_incomes_table', 1),
(35, '2025_01_29_053758_create_income_lists_table', 1),
(36, '2025_02_17_114510_create_employee_monthly_transactions_table', 2),
(41, '2025_02_08_063541_create_investments_table', 3),
(45, '2025_02_16_080430_create_bs_accounts_table', 4),
(46, '2025_02_16_081556_create_bs_types_table', 4),
(47, '2025_02_17_033739_create_journal_entries_table', 4),
(48, '2025_03_12_104700_create_payables_table', 5),
(49, '2025_03_12_105820_create_payable_heads_table', 5),
(53, '2025_03_12_104800_create_receivables_table', 7),
(54, '2025_03_12_110751_create_receivable_heads_table', 8),
(55, '2025_03_18_120357_create_asset_heads_table', 9),
(56, '2025_03_18_121820_create_assets_table', 9),
(57, '2025_01_01_105008_create_expenses_table', 10);

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
(1, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 5),
(4, 'App\\Models\\User', 6);

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
  `payable_amount` decimal(28,8) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payable_heads`
--

CREATE TABLE `payable_heads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL COMMENT 'autometic = 1, manual = 2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payable_heads`
--

INSERT INTO `payable_heads` (`id`, `name`, `description`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Supplier Payable', 'This is Supplier Due.', 1, '2025-03-12 05:24:17', '2025-03-13 06:47:57'),
(2, 'Customer Advance', 'This is Customer Due.', 1, '2025-03-12 05:24:53', '2025-03-13 06:48:17'),
(3, 'Employee Salary Payable', 'This is Employee Salary Due', 1, '2025-03-12 05:31:09', '2025-03-13 06:48:30'),
(4, 'Rent Payable', 'Laudantium non tene', 2, '2025-03-13 07:01:53', '2025-03-13 07:01:53'),
(5, 'Loan Payable', 'ddsdfasdf', 2, '2025-03-13 07:02:19', '2025-03-13 07:02:19'),
(6, 'Interest Payable', 'ads asdas', 2, '2025-03-13 07:02:34', '2025-03-13 07:02:34'),
(7, 'Other Liabilities Payable', 'sdfasdf', 2, '2025-03-13 07:02:49', '2025-03-13 07:02:49');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'role-list', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(2, 'role-create', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(3, 'role-edit', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(4, 'role-delete', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(5, 'user-list', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(6, 'user-create', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(7, 'user-edit', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(8, 'user-delete', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(9, 'product-list', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(10, 'product-create', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(11, 'product-edit', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(12, 'product-delete', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(13, 'category-list', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(14, 'category-create', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(15, 'category-edit', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(16, 'category-delete', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(17, 'sub-category-list', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(18, 'sub-category-create', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(19, 'sub-category-edit', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(20, 'sub-category-delete', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(21, 'brand-list', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(22, 'brand-create', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(23, 'brand-edit', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(24, 'brand-delete', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(25, 'customer-list', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(26, 'customer-create', 'web', '2025-02-03 09:41:02', '2025-02-03 09:41:02'),
(27, 'customer-edit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(28, 'customer-delete', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(29, 'customer-payment', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(30, 'supplier-list', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(31, 'supplier-create', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(32, 'supplier-edit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(33, 'supplier-delete', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(34, 'supplier-payment', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(35, 'purchase-list', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(36, 'purchase-create', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(37, 'purchase-edit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(38, 'purchase-delete', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(39, 'purchase-payment', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(40, 'purchase-return-list', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(41, 'purchase-return-create', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(42, 'purchase-return-edit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(43, 'purchase-return-delete', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(44, 'sell-list', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(45, 'sell-create', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(46, 'sell-edit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(47, 'sell-delete', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(48, 'sell-payment', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(49, 'sell-delivery', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(50, 'income-list', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(51, 'income-create', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(52, 'income-edit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(53, 'income-delete', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(54, 'income-list-list', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(55, 'income-list-create', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(56, 'income-list-edit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(57, 'income-list-delete', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(58, 'sell-return-list', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(59, 'sell-return-create', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(60, 'sell-return-edit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(61, 'sell-return-delete', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(62, 'expense-head-list', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(63, 'expense-head-create', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(64, 'expense-head-edit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(65, 'expense-head-delete', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(66, 'expense-list', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(67, 'expense-create', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(68, 'expense-edit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(69, 'expense-delete', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(70, 'damage-list', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(71, 'damage-create', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(72, 'damage-edit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(73, 'damage-delete', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(74, 'bank-list', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(75, 'bank-create', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(76, 'bank-edit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(77, 'bank-delete', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(78, 'bank-transaction', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(79, 'bank-diposit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(80, 'bank-withdraw', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(81, 'employee-list', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(82, 'employee-create', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(83, 'employee-edit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(84, 'employee-delete', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(85, 'employee-transaction-list', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(86, 'employee-transaction-create', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(87, 'employee-transaction-edit', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03'),
(88, 'employee-transaction-delete', 'web', '2025-02-03 09:41:03', '2025-02-03 09:41:03');

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
(1, 'Samsung 1.5 Ton Split AC', 1, 4, 16, 11, 'PRAMPGVZ', 65000.00000000, 'Energy-saving split AC with fast cooling technology.', NULL, '8806088146922', 1, 0, '2025-03-18 04:35:46', '2025-03-18 04:35:46'),
(2, 'LG 7kg Front Load Washing Machine', 1, 2, 15, 11, 'PRBFZBK0', 48500.00000000, 'Fully automatic washing machine with inverter motor.', NULL, '8808992195123', 1, 0, '2025-03-18 04:37:21', '2025-03-18 04:37:21'),
(3, 'Whirlpool 300L Double Door Refrigerator', 1, 1, 14, 11, 'PRWGNEAD', 52000.00000000, 'Frost-free refrigerator with energy efficiency.', NULL, '8901764352810', 1, 0, '2025-03-18 04:41:13', '2025-03-18 04:41:13'),
(4, 'Panasonic 20L Microwave Oven', 1, 3, 3, 11, 'PRFFJBNB', 12500.00000000, 'Compact microwave oven with auto-cook menu.', NULL, '8887549612581', 1, 0, '2025-03-18 04:45:45', '2025-03-18 04:45:45'),
(5, 'Philips 750W Mixer Grinder', 1, 2, 4, 11, 'PR4NDXCE', 7500.00000000, '3-jar mixer grinder with stainless steel blades.', NULL, '8710103810125', 1, 0, '2025-03-18 04:50:35', '2025-03-18 04:50:35'),
(6, 'Bose SoundLink Revolve+', 2, 7, 10, 11, 'PR5NZAAM', 28000.00000000, '360-degree Bluetooth speaker with deep bass.', NULL, '017817783621', 1, 0, '2025-03-18 05:04:06', '2025-03-18 05:04:06'),
(7, 'Sony WH-1000XM5', 2, 7, 9, 11, 'PRTQAXFX', 35000.00000000, 'Wireless noise-canceling over-ear headphones.', NULL, '027242923127', 1, 0, '2025-03-18 05:05:59', '2025-03-18 05:05:59'),
(8, 'Apple iPhone 15 Pro Max', 2, 8, 7, 11, 'PRTXJ7L4', 1750.00000000, 'Latest iPhone with A17 Bionic chip and Titanium body.', NULL, '194253408273', 1, 0, '2025-03-18 05:10:34', '2025-03-18 05:20:41'),
(9, 'Samsung Galaxy S24 Ultra', 2, 8, 16, 11, 'PRWMKVDE', 150000.00000000, 'High-end flagship smartphone with 200MP camera.', NULL, '8806095887654', 1, 0, '2025-03-18 05:11:32', '2025-03-18 05:11:32'),
(10, 'Apple MacBook Pro 14\" M3', 3, 16, 7, 11, 'PRLBCRFE', 210000.00000000, 'High-performance laptop with M3 Pro chip and Liquid Retina XDR display.', NULL, '194253498263', 1, 0, '2025-03-18 05:33:29', '2025-03-18 05:33:29'),
(11, 'Dell XPS 15', 3, 16, 8, 11, 'PREZLOZJ', 185000.00000000, 'Premium ultrabook with Intel Core i9 and OLED display.', NULL, '884116386735', 1, 0, '2025-03-18 05:40:28', '2025-03-18 05:40:28'),
(12, 'Logitech MX Keys', 3, 15, 10, 11, 'PRY10ESO', 12500.00000000, 'Wireless backlit keyboard with ergonomic design.', NULL, '097855157344', 1, 0, '2025-03-18 05:41:22', '2025-03-18 05:41:22'),
(13, 'Apple iPhone 15 128GB', 4, 24, 7, 11, 'PRLFF1K4', 80000.00000000, 'Latest iPhone with A16 Bionic chip, 6.1\" OLED display.', NULL, '097855157344', 1, 0, '2025-03-18 05:49:51', '2025-03-18 05:55:49'),
(14, 'Samsung Galaxy S23 Ultra', 4, 24, 16, 11, 'PRMJNHXF', 110000.00000000, '200MP camera, 12GB RAM, 6.8\" Dynamic AMOLED display.', NULL, '8806095886754', 1, 0, '2025-03-18 05:54:13', '2025-03-18 05:55:41'),
(15, 'OnePlus 11 5G', 4, 24, 5, 11, 'PRXSVNED', 55000.00000000, 'Snapdragon 8 Gen 2, 120Hz AMOLED display, 50MP camera.', NULL, '6921815601787', 1, 0, '2025-03-18 06:11:42', '2025-03-18 06:11:42'),
(16, 'Xiaomi Mi 11 5G', 4, 24, 6, 11, 'PRFBIJN9', 42000.00000000, 'Snapdragon 888, 108MP camera, 120Hz AMOLED display.', NULL, '6921815618369', 1, 0, '2025-03-18 06:12:49', '2025-03-18 06:12:49'),
(17, 'Philips Daily Collection 2-Slice Toaster', 6, 23, 4, 11, 'PRDSY6UR', 3500.00000000, 'Compact 2-slice toaster with auto pop-up and adjustable browning control.', NULL, '8710103660601', 1, 0, '2025-03-18 06:15:14', '2025-03-18 06:15:14'),
(18, 'Black+Decker 1.7L Electric Kettle', 6, 21, 3, 11, 'PRFOCP1Q', 2000.00000000, 'Cordless electric kettle with auto shut-off and boil-dry protection.', NULL, '5035048449127', 1, 0, '2025-03-18 06:16:32', '2025-03-18 06:16:32'),
(19, 'Panasonic 27L Microwave Oven', 6, 20, 3, 11, 'PREX0AEZ', 8000.00000000, '27L microwave with 1000W power and 5 auto-cook menus.', NULL, '888754967423', 1, 0, '2025-03-18 06:18:00', '2025-03-18 06:18:00'),
(20, 'Morphy Richards 2L Deep Fryer', 6, 22, 3, 11, 'PRJKRZVG', 5500.00000000, '2L capacity deep fryer with adjustable thermostat and easy clean basket.', NULL, '5011832044124', 1, 0, '2025-03-18 06:20:07', '2025-03-18 06:20:07'),
(21, 'TP-Link Archer AX50 Wi-Fi 6 Router', 7, 18, 1, 11, 'PRKADAFD', 8000.00000000, 'Dual-band Wi-Fi 6 router with 5GHz speed up to 3.0 Gbps.', NULL, '6935364094480', 1, 0, '2025-03-18 06:27:09', '2025-03-18 06:27:09'),
(22, 'TP-Link Deco X90 Wi-Fi 6 Mesh System', 7, 17, 1, 11, 'PRYJ2UPP', 22000.00000000, 'Whole-home mesh Wi-Fi system with tri-band Wi-Fi 6.', NULL, '6935364087666', 1, 0, '2025-03-18 06:28:41', '2025-03-18 06:28:41'),
(23, 'Ring Video Doorbell 4', 7, 18, 2, 11, 'PRRSQBPC', 15000.00000000, 'Whole-home mesh Wi-Fi system with tri-band Wi-Fi 6.', NULL, '852239005214', 1, 0, '2025-03-18 06:30:01', '2025-03-18 06:30:01'),
(24, 'Arlo Pro 4 Spotlight Camera', 7, 19, 3, 11, 'PRLB2KAA', 18000.00000000, 'Wireless HD security camera with 160-degree field of view and color night vision.', NULL, '196925492008', 1, 0, '2025-03-18 06:31:36', '2025-03-18 06:31:36'),
(25, 'Netgear Nighthawk X6S AC4000 Tri-Band Router', 7, 19, 4, 11, 'PRIALBDG', 15000.00000000, 'Tri-band router with advanced features for high-speed streaming.', NULL, '606449132732', 1, 0, '2025-03-18 06:32:40', '2025-03-18 06:32:40');

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
  `purchase_revision_advance` decimal(28,8) DEFAULT 0.00000000,
  `invoice_no` varchar(40) DEFAULT NULL,
  `entry_by` int(11) DEFAULT NULL,
  `entry_date` date NOT NULL,
  `update_by` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `supplier_name` varchar(255) NOT NULL,
  `total_amount` decimal(28,8) NOT NULL,
  `update_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `supplier_advance_amount` decimal(28,8) DEFAULT NULL,
  `employee_advance_amount` decimal(28,8) DEFAULT NULL,
  `due_amount` decimal(28,8) DEFAULT NULL,
  `receivable_amount` decimal(28,8) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receivables`
--

INSERT INTO `receivables` (`id`, `sell_id`, `customer_id`, `supplier_id`, `employee_id`, `receivable_head_id`, `invoice_no`, `supplier_advance_amount`, `employee_advance_amount`, `due_amount`, `receivable_amount`, `description`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, 1, 2, NULL, NULL, 500.00000000, NULL, NULL, NULL, '2025-03-23 06:37:25', '2025-03-23 06:38:42');

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
(1, 'Customer Due', 'This is Customer Due.', 1, '2025-03-17 06:07:34', '2025-03-17 06:07:34'),
(2, 'Employee Salary Receivable', 'This is Employee Salary Advance', 1, '2025-03-17 06:07:34', '2025-03-17 06:07:34'),
(3, 'Supplier Advance', 'This is Supplier Advance.', 1, '2025-03-17 06:07:34', '2025-03-17 06:07:34');

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
(1, 'Admin', 'web', '2025-02-03 09:41:07', '2025-02-03 09:41:07'),
(2, 'Employee', 'web', '2025-02-03 09:53:54', '2025-02-03 09:53:54'),
(3, 'ALL', 'web', '2025-03-02 08:04:39', '2025-03-02 08:04:39'),
(4, 'some', 'web', '2025-03-02 08:06:47', '2025-03-02 08:06:47');

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
(1, 3),
(2, 1),
(2, 3),
(3, 1),
(3, 3),
(4, 1),
(4, 3),
(5, 1),
(5, 3),
(6, 1),
(6, 3),
(7, 1),
(7, 3),
(8, 1),
(8, 3),
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(10, 1),
(10, 3),
(11, 1),
(11, 3),
(12, 1),
(12, 3),
(13, 1),
(13, 3),
(14, 1),
(14, 3),
(15, 1),
(15, 3),
(16, 1),
(16, 3),
(17, 1),
(17, 3),
(18, 1),
(18, 3),
(19, 1),
(19, 3),
(20, 1),
(20, 3),
(21, 1),
(21, 3),
(21, 4),
(22, 1),
(22, 3),
(23, 1),
(23, 3),
(24, 1),
(24, 3),
(25, 1),
(25, 3),
(26, 1),
(26, 3),
(27, 1),
(27, 3),
(28, 1),
(28, 3),
(29, 1),
(29, 3),
(30, 1),
(30, 3),
(31, 1),
(31, 3),
(32, 1),
(32, 3),
(33, 1),
(33, 3),
(34, 1),
(34, 3),
(35, 1),
(35, 3),
(36, 1),
(36, 3),
(37, 1),
(37, 3),
(38, 1),
(38, 3),
(39, 1),
(39, 3),
(40, 1),
(40, 3),
(41, 1),
(41, 3),
(42, 1),
(42, 3),
(43, 1),
(43, 3),
(44, 1),
(44, 3),
(45, 1),
(45, 3),
(46, 1),
(46, 3),
(47, 1),
(47, 3),
(48, 1),
(48, 3),
(49, 1),
(49, 3),
(50, 1),
(50, 3),
(51, 1),
(51, 3),
(52, 1),
(52, 3),
(53, 1),
(53, 3),
(54, 1),
(54, 3),
(55, 1),
(55, 3),
(56, 1),
(56, 3),
(57, 1),
(57, 3),
(58, 1),
(58, 3),
(59, 1),
(59, 3),
(60, 1),
(60, 3),
(61, 1),
(61, 3),
(62, 1),
(62, 3),
(63, 1),
(63, 3),
(64, 1),
(64, 3),
(65, 1),
(65, 3),
(66, 1),
(66, 3),
(67, 1),
(67, 3),
(68, 1),
(68, 3),
(69, 1),
(69, 3),
(70, 1),
(70, 3),
(71, 1),
(71, 3),
(72, 1),
(72, 3),
(73, 1),
(73, 3),
(74, 1),
(74, 3),
(75, 1),
(75, 3),
(76, 1),
(76, 3),
(77, 1),
(77, 3),
(78, 1),
(78, 3),
(79, 1),
(79, 3),
(80, 1),
(80, 3),
(81, 1),
(81, 3),
(82, 1),
(82, 3),
(83, 1),
(83, 3),
(84, 1),
(84, 3),
(85, 1),
(85, 3),
(86, 1),
(86, 3),
(87, 1),
(87, 3),
(88, 1),
(88, 3);

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

--
-- Dumping data for table `sells`
--

INSERT INTO `sells` (`id`, `customer_id`, `sell_date`, `total_price`, `profit`, `total_qty`, `payment_received`, `due_to_company`, `advance`, `sell_revision_advance`, `invoice_no`, `discount`, `transport_cost`, `labor_cost`, `labour_name`, `entry_date`, `last_update`, `delivery_status`, `delivery_date`, `remark`, `entry_by`, `update_by`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 9, '2025-03-19 05:18:44', 8000.00000000, 500.00000000, 4, 1600.00000000, 6400.00000000, NULL, NULL, 'sell-invoice-1', 0.00000000, 0, 0, NULL, '2025-03-19', NULL, 0, NULL, NULL, 2, NULL, 0, '2025-03-19 05:04:52', '2025-03-19 05:18:44'),
(2, 5, '2025-03-19 06:16:57', 25500.00000000, 1500.00000000, 3, 25500.00000000, 0.00000000, NULL, NULL, 'sell-invoice-2', 0.00000000, 0, 0, NULL, '2025-03-19', NULL, 1, '2025-03-19 06:16:57', NULL, 2, NULL, 0, '2025-03-19 05:11:33', '2025-03-19 06:16:57');

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
  `sell_qty` int(11) NOT NULL,
  `sell_price` decimal(28,8) NOT NULL,
  `avg_sell_price` decimal(28,8) NOT NULL,
  `total_amount` decimal(28,8) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sell_records`
--

INSERT INTO `sell_records` (`id`, `sell_id`, `purchase_batch_id`, `product_id`, `product_name`, `discount`, `avg_purchase_price`, `sell_qty`, `sell_price`, `avg_sell_price`, `total_amount`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 9, 'Samsung Galaxy S24 Ultra', 0.00000000, 1500.00000000, 4, 1600.00000000, 1600.00000000, 8000.00000000, '2025-03-19 05:04:52', '2025-03-19 05:18:44'),
(2, 2, 3, 21, 'TP-Link Archer AX50 Wi-Fi 6 Router', 0.00000000, 8000.00000000, 3, 8500.00000000, 8500.00000000, 25500.00000000, '2025-03-19 05:11:33', '2025-03-19 05:11:33');

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
('1yJZOFKuBCv8WczQFTl42PaMGENtjR6OUyRRUCwq', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiclNrT3E1THY1UzM0RTVCM2xJYzlhOXFhYnJQSXNqaTlrZUVxcHU4UiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hY2NvdW50L2Jhbmsvc3RhdGVtZW50cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1742888380),
('B4SlhJ4mRLSrVtWPeNp3A86y8m4lxDgFr69zs4W9', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUTIxMHoxRlU4TDBCdjh6VERjc3gxa1BibVZ4cWhnWTd6dll0M0JNVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9icy9hY2NvdW50L2NoYXJ0L2FjY291bnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1742879933),
('iCQbf9SbraKRjmLhOlrnuEH53MdlZ8ZzYupOS0aA', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaDN4STNsc3Q3NzYxQWJEcmpoMkVPallreWI2QUo0ZjNUYkxtaTZYNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hY2NvdW50cy9wYXlhYmxlL2luZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1742970977),
('vFJpd1c6F6jDU5pDIWLv7fuc3nDFwgTgr7fLZVcb', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUEhFRmRzVnZncXdDcmxGSXVMVGFpZ0hWZzJQVnZMTGZUWEd0QzdjSyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9icy9hY2NvdW50L2NoYXJ0L2FjY291bnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1742798600),
('WjoXJLL461qOVb49USxp2yFHMuCPOZoYawTdc4qq', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWXFyUXQxb3cwNmtqcUtqQWl1QWVOTnczTWdRRFhhbVFEc0Vubk9xZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9icy9hY2NvdW50L2NoYXJ0L2FjY291bnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1742804723);

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `total_delivered` int(11) NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 1, 'Refrigerator', 1, 0, '2025-03-17 07:24:17', '2025-03-17 07:24:17'),
(2, 1, 'Washing Machine', 1, 0, '2025-03-17 07:24:26', '2025-03-17 07:24:26'),
(3, 1, 'Microwave Oven', 1, 0, '2025-03-17 07:24:42', '2025-03-17 07:24:42'),
(4, 1, 'Air Conditioner', 1, 0, '2025-03-17 08:45:18', '2025-03-17 08:45:18'),
(5, 1, 'Vacuum Cleaner', 1, 0, '2025-03-17 08:45:50', '2025-03-17 08:45:50'),
(6, 2, 'Television', 1, 0, '2025-03-17 08:46:33', '2025-03-17 08:46:33'),
(7, 2, 'Sound System', 1, 0, '2025-03-17 08:47:18', '2025-03-17 08:47:18'),
(8, 2, 'Home Theater', 1, 0, '2025-03-17 08:47:40', '2025-03-17 08:47:40'),
(9, 4, 'Headphones & Earbuds', 1, 0, '2025-03-17 08:55:10', '2025-03-17 08:55:10'),
(10, 4, 'Power Bank', 1, 0, '2025-03-17 08:55:24', '2025-03-17 08:55:24'),
(14, 3, 'Printer & Scanner', 1, 0, '2025-03-17 09:22:01', '2025-03-17 09:22:01'),
(15, 3, 'Keyboard & Mouse', 1, 0, '2025-03-17 09:22:56', '2025-03-17 09:22:56'),
(16, 3, 'Monitor', 1, 0, '2025-03-17 09:23:12', '2025-03-17 09:23:12'),
(17, 7, 'Modem', 1, 0, '2025-03-18 03:43:00', '2025-03-18 03:43:00'),
(18, 7, 'Smart Home Devices', 1, 0, '2025-03-18 03:43:08', '2025-03-18 03:43:08'),
(19, 7, 'Security Camera', 1, 0, '2025-03-18 03:43:40', '2025-03-18 03:43:40'),
(20, 6, 'Rice Cooker', 1, 0, '2025-03-18 03:44:31', '2025-03-18 03:44:31'),
(21, 6, 'Electric Kettle', 1, 0, '2025-03-18 03:44:38', '2025-03-18 03:44:38'),
(22, 6, 'Coffee Maker', 1, 0, '2025-03-18 03:44:49', '2025-03-18 03:44:49'),
(23, 6, 'Blender & Mixer', 1, 0, '2025-03-18 03:45:01', '2025-03-18 03:45:01'),
(24, 4, 'Smartphone', 1, 0, '2025-03-18 05:55:30', '2025-03-18 05:55:30');

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
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `code`, `name`, `company`, `mobile`, `address`, `email`, `status`, `entry_by`, `entry_date`, `update_by`, `advance`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'SUP2OVRR6', 'John Smith', 'TechWorld Electronics', '12135557890', '1234 Silicon Ave, Suite 200', 'sales@techworld.com', 1, 2, '2025-03-19 03:20:19', NULL, 0.00000000, 0, '2025-03-19 03:20:19', '2025-03-19 03:20:19'),
(2, 'SUPCLKUT1', 'Maria Lopez', 'Home Essentials Ltd.', '1212121212', '1234 Silicon Ave, Suite 200', 'support@homeessentials.com', 1, 2, '2025-03-19 03:22:02', NULL, 0.00000000, 0, '2025-03-19 03:22:02', '2025-03-19 03:22:02'),
(3, 'SUP2JH9ML', 'Robert Johnson', '56 Queen Street', '14159876543', '56 Queen Street', 'info@netgearsolutions.com', 1, 2, '2025-03-19 03:22:53', NULL, 0.00000000, 0, '2025-03-19 03:22:53', '2025-03-19 03:22:53'),
(4, 'SUPK3JPMN', 'Emily Clarke', 'SmartHome Innovations', '12121212121212', '56 Queen Street', 'contact@smarthome.com.au', 1, 2, '2025-03-19 03:25:26', NULL, 0.00000000, 0, '2025-03-19 03:25:26', '2025-03-19 03:25:26'),
(5, 'SUPFJ9PHR', 'David Wong', 'KitchenTech Distributors', '6567785678', '100 Orchard Road', 'sales@kitchentech.sg', 1, 2, '2025-03-19 03:27:30', NULL, 0.00000000, 0, '2025-03-19 03:27:30', '2025-03-19 03:27:30'),
(6, 'SUP1IPTCC', 'Ahmed Khan', 'Mobile Hub Inc.', '1232323232', 'Burj Plaza, Sheikh Zayed Road', 'ahmed@mobilehub.ae', 1, 2, '2025-03-19 03:29:40', NULL, 0.00000000, 0, '2025-03-19 03:29:40', '2025-03-19 03:29:40'),
(7, 'SUPILCOB4', 'Jane Wilson', 'Computex Supplies', '232323232323', '10 Techno Park', 'sales@computex.de', 1, 2, '2025-03-19 03:30:58', NULL, 0.00000000, 0, '2025-03-19 03:30:58', '2025-03-19 03:30:58'),
(8, 'SUPDYSXHO', 'Rajesh Patel', 'Future Energy Solutions', '34344343434433', '56 Green Street', 'info@futureenergy.in', 1, 2, '2025-03-19 03:32:56', NULL, 0.00000000, 0, '2025-03-19 03:32:56', '2025-03-19 03:32:56'),
(9, 'SUPNXY8RV', 'Michael Brown', 'ProTech Security Systems', '13054567890', '405 Safehouse Lane', 'michael@protechsecurity.com', 1, 2, '2025-03-19 03:33:48', NULL, 0.00000000, 0, '2025-03-19 03:33:48', '2025-03-19 03:33:48'),
(10, 'SUPVCCRVK', 'Sophia Martinez', 'Digital Accessories Ltd.', '2323223', '90 Avenue des Champs', 'support@digitalacc.fr', 1, 2, '2025-03-19 03:34:46', NULL, 4000.00000000, 0, '2025-03-19 03:34:46', '2025-03-22 04:09:18'),
(11, 'SUPIWML2L', 'Shakir', 'Mosley Flores Co', '12345567', 'Et magni impedit su', 'venam@mailinator.com', 1, 2, '2025-03-22 04:21:03', 2, 1900.00000000, 0, '2025-03-22 04:21:03', '2025-03-22 05:19:35');

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
(1, 7, 4, 5, 7500.00000000, '2025-03-19', NULL, 2, NULL, 0, 0, '2025-03-19 05:15:08', '2025-03-19 05:15:08');

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
(1, 1, 4, 9, 5, 1500.00000000, 7500.00000000, '2025-03-19 05:15:08', '2025-03-19 05:15:08');

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
(1, 'Dozen', 1, 0, '2025-03-18 04:27:52', '2025-03-18 04:27:52'),
(2, 'Carton', 1, 0, '2025-03-18 04:27:57', '2025-03-18 04:27:57'),
(3, 'Ton', 1, 0, '2025-03-18 04:28:04', '2025-03-18 04:28:04'),
(4, 'Milligram (mg)', 1, 0, '2025-03-18 04:28:11', '2025-03-18 04:28:11'),
(5, 'Pound (lb)', 1, 0, '2025-03-18 04:28:40', '2025-03-18 04:28:40'),
(6, 'Gram (g)', 1, 0, '2025-03-18 04:30:02', '2025-03-18 04:30:02'),
(7, 'Box', 1, 0, '2025-03-18 04:30:33', '2025-03-18 04:30:33'),
(8, 'Pack', 1, 0, '2025-03-18 04:30:49', '2025-03-18 04:30:49'),
(9, 'Pair', 1, 0, '2025-03-18 04:31:03', '2025-03-18 04:31:03'),
(10, 'Set', 1, 0, '2025-03-18 04:32:14', '2025-03-18 04:32:14'),
(11, 'Piece (pcs)', 1, 0, '2025-03-18 04:32:38', '2025-03-18 04:32:38');

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
(1, 'UBold', 'demo@user.com', '2025-02-03 09:40:59', '$2y$12$ujVXpuk5vI8NkjsVgcKJvu.0OeN5XH.V8H9fzfMOSIfnAT1RgXYr.', 'QPippytFWx', '2025-02-03 09:41:00', '2025-02-03 09:41:00'),
(2, 'Hardik Savani', 'admin@gmail.com', NULL, '$2y$12$dXcaufy9T.y8Ud6vBlqLGOBf8bvZ3P/GyMAaFDZWda5mUeBeCqRNC', NULL, '2025-02-03 09:41:07', '2025-02-03 09:41:07'),
(3, 'Sadi', 'sadi@gmail.com', NULL, '$2y$12$YQ2GS3SJ7Hr126D/s4vEi.ceOoScngNVa2OGUy10NTFQfOdcA6aYG', NULL, '2025-02-03 09:59:36', '2025-02-03 09:59:36'),
(5, 'all', 'all@gmail.com', NULL, '$2y$12$QZL45pcZ9MduIyMIm/CowOgOQgHpTAbOhgLDHQz.malcfbpLyoEBe', NULL, '2025-03-02 08:05:30', '2025-03-02 08:05:30'),
(6, 'some', 'some@gmail.com', NULL, '$2y$12$s6nHYh11fPLgQpawWym3A.Fp/AW5JGukVwE/dJRnxJXDCz8WTqQNK', NULL, '2025-03-02 08:07:21', '2025-03-02 08:07:21');

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
  ADD UNIQUE KEY `products_code_unique` (`code`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_heads`
--
ALTER TABLE `asset_heads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `bs_accounts`
--
ALTER TABLE `bs_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bs_types`
--
ALTER TABLE `bs_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `customer_returns`
--
ALTER TABLE `customer_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_return_items`
--
ALTER TABLE `customer_return_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_types`
--
ALTER TABLE `customer_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `damages`
--
ALTER TABLE `damages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_monthly_transactions`
--
ALTER TABLE `employee_monthly_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_transactions`
--
ALTER TABLE `employee_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expense_heads`
--
ALTER TABLE `expense_heads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `payables`
--
ALTER TABLE `payables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payable_heads`
--
ALTER TABLE `payable_heads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_batches`
--
ALTER TABLE `purchase_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receivables`
--
ALTER TABLE `receivables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `receivable_heads`
--
ALTER TABLE `receivable_heads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sells`
--
ALTER TABLE `sells`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sell_records`
--
ALTER TABLE `sell_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_items`
--
ALTER TABLE `stock_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `supplier_returns`
--
ALTER TABLE `supplier_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier_return_items`
--
ALTER TABLE `supplier_return_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
