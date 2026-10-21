-- ========================================================
-- Migration: Add API Token Authentication & Application Mapping
-- Compatible with MySQL 5.7+, MySQL 8.0+, MariaDB 10.1+
-- ========================================================

-- 1. Tambahkan kolom ke tabel api_keys (hapus IF NOT EXISTS agar kompatibel MySQL/MariaDB standar)
ALTER TABLE `api_keys`
  ADD `application_id` varchar(36) DEFAULT NULL AFTER `id`,
  ADD `token_hash` varchar(64) DEFAULT NULL AFTER `secret_masked`,
  ADD `secret_key` text DEFAULT NULL AFTER `token_hash`;

-- 2. Tambahkan index untuk pencarian cepat token_hash
ALTER TABLE `api_keys` ADD INDEX `idx_token_hash` (`token_hash`);

-- 3. Daftarkan Aplikasi Portofolio Motion
INSERT INTO `applications` (`id`, `name`, `slug`, `description`, `category`, `status`, `meta_data`, `created_at`)
VALUES (
  'APP-0001',
  'Moch. Adam Athallahrich Motion Portfolio',
  'motion-portfolio',
  'Interactive Kinetic Motion Portfolio (Nuxt 3 + Vue 3 + Tailwind + GSAP)',
  'portfolio_motion',
  'active',
  '{"framework":"Nuxt 3","port":3000,"client":"Motion Portfolio"}',
  NOW()
) ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- 4. Buat Default Active API Key untuk Portofolio Motion
INSERT INTO `api_keys` (`id`, `application_id`, `name`, `prefix`, `secret_masked`, `token_hash`, `secret_key`, `owner_email`, `scopes`, `rate_limit`, `status`, `created_at`)
VALUES (
  'KEY-PORTO-01',
  'APP-0001',
  'Motion Portfolio Nuxt 3 Client Key',
  'iam_live_1f3...',
  'iam_live_1f3a****************1a3a',
  'c91de9d8867aea58f573e88593a84e80ff55db97d0925bcc5ca2b9e530618381',
  'iam_live_1f3afb885bd73175ce8ae51518a71a3a',
  'athallahrich@gmail.com',
  '["portfolio:read","portfolio:write","*"]',
  600,
  'active',
  NOW()
) ON DUPLICATE KEY UPDATE `status` = 'active', `token_hash` = VALUES(`token_hash`), `secret_key` = VALUES(`secret_key`);
