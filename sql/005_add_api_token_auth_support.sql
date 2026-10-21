-- Migration: Add application_id, token_hash, secret_key to api_keys
USE `saas_iam_db`;

-- Add columns if not exist (handled via procedure or alter)
ALTER TABLE `api_keys`
  ADD COLUMN IF NOT EXISTS `application_id` varchar(36) DEFAULT NULL AFTER `id`,
  ADD COLUMN IF NOT EXISTS `token_hash` varchar(64) DEFAULT NULL AFTER `secret_masked`,
  ADD COLUMN IF NOT EXISTS `secret_key` text DEFAULT NULL AFTER `token_hash`;

-- Ensure index exists
ALTER TABLE `api_keys` ADD INDEX IF NOT EXISTS `idx_token_hash` (`token_hash`);

-- Ensure Moch. Adam Athallahrich Motion Portfolio is registered as client application
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
