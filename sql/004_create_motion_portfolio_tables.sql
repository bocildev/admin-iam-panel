-- Migration: Dedicated Schema & Initial Seed for Moch. Adam Athallahrich Motion Portfolio
USE `saas_iam_db`;

CREATE TABLE IF NOT EXISTS `motion_projects` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `project_key` VARCHAR(100) NOT NULL,
  `number` VARCHAR(10) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `subtitle` VARCHAR(255) NOT NULL,
  `company` VARCHAR(255) NOT NULL,
  `period` VARCHAR(100) NOT NULL,
  `category` ENUM('enterprise', 'personal') NOT NULL DEFAULT 'enterprise',
  `roles` LONGTEXT NOT NULL,
  `cover_image` VARCHAR(500) NOT NULL,
  `logo` VARCHAR(500) DEFAULT NULL,
  `description` TEXT NOT NULL,
  `impact_highlights` LONGTEXT NOT NULL,
  `tech_stack` LONGTEXT NOT NULL,
  `team` LONGTEXT DEFAULT NULL,
  `live_url` VARCHAR(500) DEFAULT NULL,
  `repo_url` VARCHAR(500) DEFAULT NULL,
  `demo_credentials` TEXT DEFAULT NULL,
  `gallery` LONGTEXT NOT NULL,
  `display_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `motion_config` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `config_key` VARCHAR(100) NOT NULL UNIQUE,
  `config_value` LONGTEXT NOT NULL,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
