-- ============================================================================
-- AiScalers Database Schema (MySQL 8.0+)
-- ============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ============================================================================
-- USERS TABLE
-- ============================================================================

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `photo_url` varchar(500) DEFAULT NULL,
  `role` enum('member','admin') DEFAULT 'member',
  
  -- Subscription
  `subscription_status` enum('active','inactive','trial','cancelled') DEFAULT 'inactive',
  `subscription_plan` enum('monthly','annual','lifetime') DEFAULT NULL,
  `stripe_customer_id` varchar(255) DEFAULT NULL,
  `stripe_subscription_id` varchar(255) DEFAULT NULL,
  `current_period_end` datetime DEFAULT NULL,
  
  -- Metadata
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  `onboarding_completed` tinyint(1) DEFAULT 0,
  
  -- Preferences
  `notifications_enabled` tinyint(1) DEFAULT 1,
  `theme` enum('dark','light') DEFAULT 'dark',
  
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_subscription` (`subscription_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- BLUEPRINTS TABLE
-- ============================================================================

CREATE TABLE `blueprints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `video_url` varchar(500) NOT NULL,
  `json_code` mediumtext NOT NULL,
  `platform` enum('n8n','make') NOT NULL,
  `difficulty` enum('beginner','intermediate','advanced') NOT NULL,
  `estimated_time` int(11) NOT NULL COMMENT 'Minutes',
  `featured` tinyint(1) DEFAULT 0,
  
  -- Metadata
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `views` int(11) DEFAULT 0,
  `copies` int(11) DEFAULT 0,
  
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_featured` (`featured`),
  KEY `fk_blueprints_user` (`created_by`),
  FULLTEXT KEY `ft_search` (`title`,`description`),
  CONSTRAINT `fk_blueprints_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- BLUEPRINT TAGS
-- ============================================================================

CREATE TABLE `blueprint_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blueprint_id` int(11) NOT NULL,
  `tag` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tag` (`tag`),
  KEY `fk_btags_blueprint` (`blueprint_id`),
  CONSTRAINT `fk_btags_blueprint` FOREIGN KEY (`blueprint_id`) REFERENCES `blueprints` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- PROMPTS TABLE
-- ============================================================================

CREATE TABLE `prompts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `template` text NOT NULL,
  `example_output` text,
  `featured` tinyint(1) DEFAULT 0,
  
  -- Metadata
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `uses` int(11) DEFAULT 0,
  
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_featured` (`featured`),
  KEY `fk_prompts_user` (`created_by`),
  FULLTEXT KEY `ft_search` (`title`,`description`),
  CONSTRAINT `fk_prompts_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- PROMPT INPUTS
-- ============================================================================

CREATE TABLE `prompt_inputs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prompt_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` enum('text','textarea','select') NOT NULL,
  `placeholder` varchar(255) DEFAULT NULL,
  `required` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  
  PRIMARY KEY (`id`),
  KEY `fk_inputs_prompt` (`prompt_id`),
  CONSTRAINT `fk_inputs_prompt` FOREIGN KEY (`prompt_id`) REFERENCES `prompts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- PROMPT TAGS
-- ============================================================================

CREATE TABLE `prompt_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prompt_id` int(11) NOT NULL,
  `tag` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tag` (`tag`),
  KEY `fk_ptags_prompt` (`prompt_id`),
  CONSTRAINT `fk_ptags_prompt` FOREIGN KEY (`prompt_id`) REFERENCES `prompts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- LIBRARY (BOOKS) TABLE
-- ============================================================================

CREATE TABLE `library` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `cover_url` varchar(500) NOT NULL,
  `pdf_url` varchar(500) NOT NULL,
  `audio_url` varchar(500) NOT NULL,
  `audio_duration` int(11) NOT NULL COMMENT 'Seconds',
  `category` varchar(100) NOT NULL,
  `featured` tinyint(1) DEFAULT 0,
  
  -- Metadata
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `added_by` int(11) NOT NULL,
  `downloads` int(11) DEFAULT 0,
  `audio_plays` int(11) DEFAULT 0,
  
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_featured` (`featured`),
  KEY `fk_library_user` (`added_by`),
  FULLTEXT KEY `ft_search` (`title`,`description`,`author`),
  CONSTRAINT `fk_library_user` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- BOOK TAGS
-- ============================================================================

CREATE TABLE `book_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `tag` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tag` (`tag`),
  KEY `fk_btags_book` (`book_id`),
  CONSTRAINT `fk_btags_book` FOREIGN KEY (`book_id`) REFERENCES `library` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- BOOK KEY TAKEAWAYS
-- ============================================================================

CREATE TABLE `book_takeaways` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `takeaway` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_takeaways_book` (`book_id`),
  CONSTRAINT `fk_takeaways_book` FOREIGN KEY (`book_id`) REFERENCES `library` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- SESSIONS TABLE
-- ============================================================================

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_expires` (`expires_at`),
  KEY `fk_sessions_user` (`user_id`),
  CONSTRAINT `fk_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- INSERT DEFAULT ADMIN USER
-- ============================================================================
-- Password: Admin123! (CHANGE THIS IN PRODUCTION!)

INSERT INTO `users` (`uid`, `email`, `password_hash`, `display_name`, `role`, `subscription_status`, `subscription_plan`, `onboarding_completed`) VALUES
(UUID(), 'admin@aiscalers.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin', 'active', 'lifetime', 1);

COMMIT;
