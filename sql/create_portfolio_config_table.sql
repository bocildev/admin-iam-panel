-- Create portfolio_config table
USE `toonhub_iam`;

CREATE TABLE IF NOT EXISTS `portfolio_config` (
  `key` VARCHAR(100) PRIMARY KEY,
  `value` TEXT NOT NULL,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default settings
INSERT INTO `portfolio_config` (`key`, `value`) VALUES
('splash_letters', 'STUDIO'),
('showcase_count', '4'),
('star_count', '24'),
('autoplay_interval', '5000'),
('transition_speed', '800'),
('brand_label', 'SHOWCASE'),
('featured_title_en', 'FEATURED APPS'),
('featured_title_id', 'APLIKASI UNGGULAN'),
('featured_desc_en', 'Discover a curated collection of innovative applications. From powerful dashboards to seamless mobile experiences, explore tools built for the future.'),
('featured_desc_id', 'Temukan koleksi aplikasi inovatif terkurasi. Dari dashboard analitik canggih hingga pengalaman seluler tanpa hambatan, jelajahi produk teknologi masa depan.'),
('page_order', '["splash","hero","discover"]'),
('section_layout', '{"splash":{"enabled":true,"components":{"letters_animation":{"enabled":true,"text":"STUDIO"},"progress_bar":{"enabled":true},"loading_text":{"enabled":true}}},"hero":{"enabled":true,"components":{"ghost_text":{"enabled":true},"brand_label":{"enabled":true,"text":"SHOWCASE"},"carousel":{"enabled":true,"card_count":4,"aspect_ratio":"16/9","height_dvh":48},"nav_arrows":{"enabled":true},"nav_dots":{"enabled":true},"explore_button":{"enabled":true},"featured_title":{"enabled":true,"en":"FEATURED APPS","id":"APLIKASI UNGGULAN"},"featured_desc":{"enabled":true,"en":"Discover a curated collection of innovative applications.","id":"Temukan koleksi aplikasi inovatif terkurasi."}}},"discover":{"enabled":true,"components":{"section_header":{"enabled":true},"project_count_badge":{"enabled":true},"grid":{"enabled":true,"columns":4,"gap":8},"card_tech_badges":{"enabled":true,"max_badges":3},"card_description":{"enabled":true},"card_index_badge":{"enabled":true}}},"background":{"nebula_glow":{"enabled":true,"opacity":65},"star_particles":{"enabled":true,"count":24},"vignette":{"enabled":true}}}')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
