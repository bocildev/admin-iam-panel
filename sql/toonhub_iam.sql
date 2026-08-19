-- ToonHub IAM Admin Panel Database Dump
-- Compatible with MySQL / MariaDB & CodeIgniter 3

CREATE DATABASE IF NOT EXISTS `toonhub_iam` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `toonhub_iam`;

-- --------------------------------------------------------
-- Table structure for `toon_roles`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `toon_roles` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `description` text,
  `color` varchar(20) DEFAULT 'cyan',
  `is_system_role` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data for `toon_roles`
INSERT INTO `toon_roles` (`id`, `name`, `display_name`, `description`, `color`, `is_system_role`) VALUES
('ROLE-01', 'SuperAdmin', 'Super Administrator', 'Full root access to all security scopes, API keys, and database clusters.', 'red', 1),
('ROLE-02', 'ContentManager', 'Content Manager', 'Publishes webtoons, moderates episodes, manages banner campaigns.', 'cyan', 1),
('ROLE-03', 'ComicCreator', 'Comic Creator / Author', 'Uploads draft chapters, views monetization stats, responds to comments.', 'emerald', 0),
('ROLE-04', 'CommunityModerator', 'Community Moderator', 'Flags spam comments, manages user bans, handles user reports.', 'amber', 0),
('ROLE-05', 'VipSubscriber', 'VIP Subscriber', 'Access to early-access fast pass episodes and ad-free reading.', 'purple', 0),
('ROLE-06', 'ApiPartner', 'External API Partner', 'Restricted machine-to-machine REST API access for content syndication.', 'blue', 0),
('ROLE-07', 'GuestReader', 'Guest Reader', 'Read-only access to public webtoon catalog.', 'slate', 1);

-- --------------------------------------------------------
-- Table structure for `toon_permissions`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `toon_permissions` (
  `key` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data for `toon_permissions`
INSERT INTO `toon_permissions` (`key`, `category`, `label`, `description`) VALUES
('comics.create', 'comics', 'Create Webtoon Series', 'Allows creating new webtoon title entries'),
('comics.edit', 'comics', 'Edit Series Metadata', 'Modify titles, tags, banners, and age ratings'),
('comics.delete', 'comics', 'Delete Webtoon Series', 'Permanently remove a webtoon series and all chapters'),
('episodes.upload', 'episodes', 'Upload Episodes', 'Upload images, set release schedules, and fast-pass prices'),
('episodes.publish', 'episodes', 'Publish / Unpublish', 'Toggle chapter visibility on ToonHub reader apps'),
('users.view', 'users', 'View User List', 'Search and filter registered readers and creators'),
('users.manage', 'users', 'Manage User Accounts', 'Edit user roles, toggle MFA, and suspend accounts'),
('users.delete', 'users', 'Delete User Accounts', 'Hard delete user records and clear stored sessions'),
('api.keys_manage', 'api', 'Manage API Keys', 'Generate, revoke, and set rate limits on partner tokens'),
('api.telemetry_view', 'api', 'View API Telemetry', 'Monitor API request velocity and error rates'),
('financial.payouts', 'financial', 'Process Creator Payouts', 'Approve monthly ad-revenue sharing payouts'),
('system.logs', 'system', 'View Security Audit Logs', 'Inspect system logs, IP tracking, and risk alerts'),
('system.config', 'system', 'CI3 Core Config', 'Modify database sync settings and hook parameters');

-- --------------------------------------------------------
-- Table structure for `toon_role_permissions`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `toon_role_permissions` (
  `role_id` varchar(20) NOT NULL,
  `permission_key` varchar(50) NOT NULL,
  PRIMARY KEY (`role_id`, `permission_key`),
  CONSTRAINT `fk_rp_role` FOREIGN KEY (`role_id`) REFERENCES `toon_roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rp_perm` FOREIGN KEY (`permission_key`) REFERENCES `toon_permissions` (`key`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data for `toon_role_permissions`
INSERT INTO `toon_role_permissions` (`role_id`, `permission_key`) VALUES
('ROLE-01', 'comics.create'), ('ROLE-01', 'comics.edit'), ('ROLE-01', 'comics.delete'),
('ROLE-01', 'episodes.upload'), ('ROLE-01', 'episodes.publish'), ('ROLE-01', 'users.view'),
('ROLE-01', 'users.manage'), ('ROLE-01', 'users.delete'), ('ROLE-01', 'api.keys_manage'),
('ROLE-01', 'api.telemetry_view'), ('ROLE-01', 'financial.payouts'), ('ROLE-01', 'system.logs'),
('ROLE-01', 'system.config'),
('ROLE-02', 'comics.create'), ('ROLE-02', 'comics.edit'), ('ROLE-02', 'episodes.upload'),
('ROLE-02', 'episodes.publish'), ('ROLE-02', 'users.view'),
('ROLE-03', 'comics.create'), ('ROLE-03', 'comics.edit'), ('ROLE-03', 'episodes.upload'),
('ROLE-04', 'users.view'),
('ROLE-06', 'api.telemetry_view');

-- --------------------------------------------------------
-- Table structure for `toon_users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `toon_users` (
  `id` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `avatar` text,
  `role` varchar(50) NOT NULL,
  `status` enum('active','suspended','pending','mfa_required') DEFAULT 'active',
  `ci3_password_hash` varchar(255) NOT NULL,
  `is_mfa_enabled` tinyint(1) DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT '127.0.0.1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data for `toon_users`
INSERT INTO `toon_users` (`id`, `username`, `email`, `full_name`, `avatar`, `role`, `status`, `ci3_password_hash`, `is_mfa_enabled`, `last_login`, `ip_address`, `created_at`) VALUES
('USR-1092', 'athallah_root', 'athallah@toonhub.id', 'Athallah Rizq', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80', 'SuperAdmin', 'active', '$2y$10$e8Z.rJ4S4xP0K/7Pz9O8XeYvH01j.qK8b7H1u6f7W8i9O0P1Q2R3S', 1, '2026-07-26 18:42:10', '180.252.120.88', '2025-01-15 08:30:00'),
('USR-1093', 'bocildev', 'bocildev@toonhub.id', 'Bocil Developer', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80', 'ContentManager', 'active', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2026-07-26 17:15:00', '180.252.120.90', '2025-02-01 10:12:00'),
('USR-1094', 'mangaka_rio', 'rio@studiotoon.id', 'Rio Studio Head', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80', 'ComicCreator', 'active', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, '2026-07-25 21:05:40', '114.124.180.12', '2025-02-14 14:20:00'),
('USR-1095', 'siti_moderator', 'siti@toonhub.id', 'Siti Rahma', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80', 'CommunityModerator', 'active', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, '2026-07-26 14:10:00', '180.252.121.05', '2025-03-01 09:00:00'),
('USR-1096', 'vip_kevin', 'kevin.reader@gmail.com', 'Kevin Sanjaya', 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?w=150&auto=format&fit=crop&q=80', 'VipSubscriber', 'mfa_required', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2026-07-20 11:30:15', '36.85.12.44', '2025-03-10 16:45:00'),
('USR-1097', 'syndicate_bot', 'api@kakao-webtoon.partner', 'Kakao Syndication Bot', 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=150&auto=format&fit=crop&q=80', 'ApiPartner', 'active', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, '2026-07-26 20:00:00', '203.0.113.195', '2025-04-01 11:11:11'),
('USR-1098', 'suspicious_actor', 'hacker@darknet.io', 'Unknown Entity', 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80', 'GuestReader', 'suspended', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, '2026-07-24 03:12:09', '185.220.101.5', '2025-05-12 02:00:00');

-- --------------------------------------------------------
-- Table structure for `toon_api_keys`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `toon_api_keys` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `prefix` varchar(30) NOT NULL,
  `secret_masked` varchar(50) NOT NULL,
  `owner_id` varchar(20) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `scopes` text NOT NULL,
  `rate_limit` int(11) DEFAULT 120,
  `status` enum('active','revoked','expired') DEFAULT 'active',
  `last_used` datetime DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data for `toon_api_keys`
INSERT INTO `toon_api_keys` (`id`, `name`, `prefix`, `secret_masked`, `owner_id`, `owner_name`, `scopes`, `rate_limit`, `status`, `last_used`, `created_at`) VALUES
('KEY-01', 'Kakao Content Syndication Sync', 'th_live_9f82...', 'th_live_9f82********************3a1e', 'USR-1097', 'Kakao Syndication Bot', '["comics.read","episodes.read"]', 300, 'active', '2026-07-26 20:00:00', '2025-04-01'),
('KEY-02', 'Mobile Android Production App', 'th_live_12c4...', 'th_live_12c4********************88bc', 'USR-1092', 'Athallah Rizq', '["users.read","comics.read","episodes.read","auth.verify"]', 600, 'active', '2026-07-26 20:25:00', '2025-01-20'),
('KEY-03', 'Legacy Webhook Aggregator', 'th_test_77d1...', 'th_test_77d1********************90aa', 'USR-1093', 'Bocil Developer', '["system.logs"]', 60, 'revoked', '2026-06-15 10:00:00', '2025-02-10');

-- --------------------------------------------------------
-- Table structure for `toon_audit_logs`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `toon_audit_logs` (
  `id` varchar(20) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actor` varchar(50) NOT NULL,
  `actor_email` varchar(100) NOT NULL,
  `action` varchar(50) NOT NULL,
  `resource` varchar(100) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text,
  `risk_level` enum('low','medium','high','critical') DEFAULT 'low',
  `status` enum('success','blocked','warning') DEFAULT 'success',
  `details` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data for `toon_audit_logs`
INSERT INTO `toon_audit_logs` (`id`, `timestamp`, `actor`, `actor_email`, `action`, `resource`, `ip_address`, `user_agent`, `risk_level`, `status`, `details`) VALUES
('LOG-9821', '2026-07-26 20:15:33', 'athallah_root', 'athallah@toonhub.id', 'ROLE_PERMISSIONS_UPDATE', 'ToonHub IAM Control Panel', '180.252.120.88', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/126.0', 'warning', 'success', 'Memberikan izin [api.keys_manage] kepada peran [ContentManager].'),
('LOG-9820', '2026-07-26 19:40:12', 'syndicate_bot', 'api@kakao-webtoon.partner', 'REST_API_BEARER_AUTH', 'ToonHub REST API /v1/episodes', '203.0.113.195', 'KakaoSyndicateBot/2.1', 'low', 'success', 'Otentikasi API Key [KEY-01] berhasil. Rate limit 42/300 req/min.'),
('LOG-9819', '2026-07-26 18:02:00', 'suspicious_actor', 'hacker@darknet.io', 'BRUTE_FORCE_PREVENTION', 'CI3 Auth Controller /login', '185.220.101.5', 'Python-urllib/3.10', 'critical', 'blocked', 'Deteksi 15 percobaan login gagal berturut-turut. IP otomatis dilarang oleh IAM_Hook.php.'),
('LOG-9818', '2026-07-26 17:15:05', 'bocildev', 'bocildev@toonhub.id', 'USER_REGISTRATION', 'ToonHub User DB', '180.252.120.90', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)', 'medium', 'success', 'Berhasil mendaftarkan user baru @mangaka_rio (ComicCreator).'),
('LOG-9817', '2026-07-26 14:00:22', 'athallah_root', 'athallah@toonhub.id', 'CI3_HOOK_DEPLOYMENT', 'application/hooks/IAM_Hook.php', '180.252.120.88', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 'low', 'success', 'Memperbarui file hook IAM_Hook.php untuk verifikasi sesi CI3 post_controller_constructor.');

-- --------------------------------------------------------
-- Table structure for `ci_sessions`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `user_agent` text,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data for `ci_sessions`
INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`, `user_id`, `username`, `role`, `user_agent`) VALUES
('ci_sess_90a1b2c3d4e5f67890123456789abcdef', '180.252.120.88', 1785072000, '__ci_last_regenerate|i:1785072000;user_id|s:8:"USR-1092";username|s:13:"athallah_root";role|s:10:"SuperAdmin";', 'USR-1092', 'athallah_root', 'SuperAdmin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/126.0'),
('ci_sess_11223344556677889900aabbccddeeff', '180.252.120.90', 1785071800, '__ci_last_regenerate|i:1785071800;user_id|s:8:"USR-1093";username|s:8:"bocildev";role|s:14:"ContentManager";', 'USR-1093', 'bocildev', 'ContentManager', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)'),
('ci_sess_ffee0099887766554433221100aabbcc', '203.0.113.195', 1785071950, '__ci_last_regenerate|i:1785071950;user_id|s:8:"USR-1097";username|s:14:"syndicate_bot";role|s:10:"ApiPartner";', 'USR-1097', 'syndicate_bot', 'ApiPartner', 'KakaoSyndicateBot/2.1');
