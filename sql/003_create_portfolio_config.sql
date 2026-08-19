-- Create portfolio_config table for storing layout & settings data
USE saas_iam_db;

CREATE TABLE IF NOT EXISTS `portfolio_config` (
    `id`         INT AUTO_INCREMENT PRIMARY KEY,
    `key`        VARCHAR(100) NOT NULL UNIQUE,
    `value`      LONGTEXT     NOT NULL,
    `created_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default section_layout
INSERT IGNORE INTO `portfolio_config` (`key`, `value`) VALUES (
    'section_layout',
    '{"splash":{"enabled":true,"components":{"letters_animation":{"enabled":true,"text":"STUDIO"},"progress_bar":{"enabled":true},"loading_text":{"enabled":true}}},"hero":{"enabled":true,"components":{"ghost_text":{"enabled":true},"brand_label":{"enabled":true,"text":"SHOWCASE"},"carousel":{"enabled":true,"card_count":4,"aspect_ratio":"16/9","height_dvh":48},"nav_arrows":{"enabled":true},"nav_dots":{"enabled":true},"explore_button":{"enabled":true},"featured_title":{"enabled":true,"en":"FEATURED APPS","id":"APLIKASI UNGGULAN"},"featured_desc":{"enabled":true,"en":"Discover a curated collection of innovative applications.","id":"Temukan koleksi aplikasi inovatif terkurasi."}}},"discover":{"enabled":true,"components":{"section_header":{"enabled":true},"project_count_badge":{"enabled":true},"grid":{"enabled":true,"columns":4,"gap":8},"card_tech_badges":{"enabled":true,"max_badges":3},"card_description":{"enabled":true},"card_index_badge":{"enabled":true}}},"background":{"nebula_glow":{"enabled":true,"opacity":65},"star_particles":{"enabled":true,"count":24},"vignette":{"enabled":true}}}'
);
