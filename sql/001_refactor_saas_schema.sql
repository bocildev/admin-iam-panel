-- ========================================================
-- Multi-Tenant SaaS & IAM Platform Clean Database Setup
-- Database: saas_iam_db
-- Target Schema: PRD Standardized Architecture
-- ========================================================

CREATE DATABASE IF NOT EXISTS `saas_iam_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `saas_iam_db`;

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Create admins table (IAM Platform Administrators)
CREATE TABLE IF NOT EXISTS `admins` (
  `id` varchar(36) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `avatar` text,
  `role` enum('super_admin','admin') NOT NULL DEFAULT 'admin',
  `status` enum('active','suspended','inactive') NOT NULL DEFAULT 'active',
  `is_mfa_enabled` tinyint(1) DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT '127.0.0.1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_admin_username` (`username`),
  UNIQUE KEY `idx_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default Super Admin account (Password: AdminSaaS2026!)
INSERT INTO `admins` (`id`, `username`, `email`, `full_name`, `password_hash`, `avatar`, `role`, `status`, `created_at`)
VALUES (
  'ADM-0001',
  'superadmin',
  'admin@saas.local',
  'Super Administrator',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Default bcrypt hash for password: secret
  'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
  'super_admin',
  'active',
  NOW()
) ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 2. Create applications table (Multi-Tenant Applications & Portfolio Projects)
CREATE TABLE IF NOT EXISTS `applications` (
  `id` varchar(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text,
  `category` varchar(50) DEFAULT 'general',
  `status` enum('active','maintenance','suspended','draft') DEFAULT 'active',
  `meta_data` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_app_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Create app_databases table (Isolated Database Provisioning Metadata)
CREATE TABLE IF NOT EXISTS `app_databases` (
  `id` varchar(36) NOT NULL,
  `application_id` varchar(36) NOT NULL,
  `db_host` varchar(100) DEFAULT '127.0.0.1',
  `db_port` int(11) DEFAULT 3306,
  `db_name` varchar(100) NOT NULL,
  `db_user` varchar(100) NOT NULL,
  `db_password_encrypted` text NOT NULL,
  `db_driver` enum('mysql','postgresql') DEFAULT 'mysql',
  `status` enum('provisioned','failed','migrating') DEFAULT 'provisioned',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_app_db_name` (`db_name`),
  KEY `fk_app_db_application` (`application_id`),
  CONSTRAINT `fk_app_db_application` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Create app_contents table (Dynamic Content Store per Application)
CREATE TABLE IF NOT EXISTS `app_contents` (
  `id` varchar(36) NOT NULL,
  `application_id` varchar(36) NOT NULL,
  `content_key` varchar(100) NOT NULL,
  `content_value` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_app_content_key` (`application_id`, `content_key`),
  CONSTRAINT `fk_app_content_application` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Create app_user_access table (IAM App Level User Access Mapping)
CREATE TABLE IF NOT EXISTS `app_user_access` (
  `id` varchar(36) NOT NULL,
  `admin_id` varchar(36) NOT NULL,
  `application_id` varchar(36) NOT NULL,
  `app_role` varchar(50) DEFAULT 'editor',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_admin_app` (`admin_id`, `application_id`),
  CONSTRAINT `fk_user_access_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_access_app` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Audit Logs Table
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` varchar(36) NOT NULL,
  `actor_id` varchar(36) DEFAULT NULL,
  `actor_name` varchar(100) NOT NULL,
  `actor_email` varchar(100) NOT NULL,
  `action` varchar(50) NOT NULL,
  `resource` varchar(100) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text,
  `risk_level` enum('low','medium','high','critical') DEFAULT 'low',
  `status` enum('success','blocked','warning') DEFAULT 'success',
  `details` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_audit_actor` (`actor_email`),
  KEY `idx_audit_action` (`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. API Keys Table
CREATE TABLE IF NOT EXISTS `api_keys` (
  `id` varchar(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `prefix` varchar(30) NOT NULL,
  `secret_masked` varchar(50) NOT NULL,
  `owner_email` varchar(100) NOT NULL,
  `scopes` text NOT NULL,
  `rate_limit` int(11) DEFAULT 120,
  `status` enum('active','revoked','expired') DEFAULT 'active',
  `last_used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_api_key_prefix` (`prefix`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
