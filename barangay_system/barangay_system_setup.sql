-- ============================================================
-- Barangay Service Management System
-- Database Setup Script
-- Run this in phpMyAdmin > barangay_system > SQL tab
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ------------------------------------------------------------
-- Make sure you are using the correct database
-- ------------------------------------------------------------
-- CREATE DATABASE IF NOT EXISTS `barangay_system`;
-- USE `barangay_system`;

-- ------------------------------------------------------------
-- Add missing columns if they don't exist yet
-- ------------------------------------------------------------
ALTER TABLE `complaints`
  ADD COLUMN IF NOT EXISTS `category` varchar(100) DEFAULT 'Others' AFTER `user_id`;

ALTER TABLE `appointments`
  ADD COLUMN IF NOT EXISTS `purpose` varchar(255) DEFAULT NULL AFTER `id`,
  ADD COLUMN IF NOT EXISTS `service` varchar(100) DEFAULT NULL AFTER `purpose`;

ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `fname` varchar(100) DEFAULT NULL AFTER `id`,
  ADD COLUMN IF NOT EXISTS `lname` varchar(100) DEFAULT NULL AFTER `fname`;

-- ------------------------------------------------------------
-- Default Admin Account
-- Username: admin
-- Password: password
-- Change the password after first login!
-- ------------------------------------------------------------
INSERT IGNORE INTO `users` (`fname`, `lname`, `username`, `email`, `password`, `role`)
VALUES (
  'Barangay',
  'Admin',
  'admin',
  'admin@barangay.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'admin'
);

-- ------------------------------------------------------------
-- Reset admin password (run this if you forgot your password)
-- ------------------------------------------------------------
-- UPDATE `users`
-- SET `password` = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
-- WHERE `username` = 'admin';

COMMIT;
