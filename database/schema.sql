
DROP TABLE IF EXISTS `task_comments`;
DROP TABLE IF EXISTS `task_files`;
DROP TABLE IF EXISTS `task_tags`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `tags`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `display_name` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `display_name`) VALUES
(1, 'admin', 'Administrator'),
(2, 'video_editor', 'Video Editor'),
(3, 'designer', 'Designer'),
(4, 'socmed', 'Social Media Specialist');

CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role_id` INT(11) NOT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_email` (`email`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `is_active`) VALUES
(1, 'Administrator', 'admin@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 1, 1);

INSERT INTO `users` (`name`, `email`, `password`, `role_id`, `is_active`) VALUES
('Video Editor User', 'editor@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 2, 1),
('Designer User', 'designer@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 3, 1),
('Social Media User', 'socmed@test.com', '$2y$10$kjEZifq0qFXOmw1H.7eex.OZDfLrpRKWUesi/0.f4dQiYl7KsvS7i', 4, 1);

CREATE TABLE `projects` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT,
  `type` ENUM('weekly', 'monthly') NOT NULL DEFAULT 'weekly',
  `periode_start` DATE DEFAULT NULL,
  `periode_end` DATE DEFAULT NULL,
  `deadline` DATE DEFAULT NULL,
  `status` ENUM('active', 'completed', 'archived') NOT NULL DEFAULT 'active',
  `created_by` INT(11) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_status` (`status`),
  KEY `idx_created_by` (`created_by`),
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tags` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `color` VARCHAR(7) NOT NULL DEFAULT '#007bff',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tags` (`name`, `color`) VALUES
('Urgent', '#dc3545'),
('High Priority', '#fd7e14'),
('Medium Priority', '#ffc107'),
('Low Priority', '#28a745'),
('Content Creation', '#6f42c1'),
('Review Required', '#17a2b8');

CREATE TABLE `tasks` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `project_id` INT(11) DEFAULT NULL,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT,
  `assigned_to` INT(11) NOT NULL,
  `deadline` DATE DEFAULT NULL,
  `status` ENUM('pending', 'on_progress', 'done') NOT NULL DEFAULT 'pending',
  `priority` ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
  `created_by` INT(11) NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_project_id` (`project_id`),
  KEY `idx_assigned_to` (`assigned_to`),
  KEY `idx_status` (`status`),
  KEY `idx_deadline` (`deadline`),
  FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `task_tags` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `task_id` INT(11) NOT NULL,
  `tag_id` INT(11) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_task_tag` (`task_id`, `tag_id`),
  FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`tag_id`) REFERENCES `tags`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `task_files` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `task_id` INT(11) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `file_type` VARCHAR(50) DEFAULT NULL,
  `file_size` INT(11) DEFAULT NULL,
  `uploaded_by` INT(11) NOT NULL,
  `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_task_id` (`task_id`),
  FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `task_comments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `task_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `comment` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_task_id` (`task_id`),
  KEY `idx_user_id` (`user_id`),
  FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `notifications` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `task_id` INT(11) DEFAULT NULL,
  `message` VARCHAR(500) NOT NULL,
  `type` ENUM('task_assigned', 'deadline_reminder', 'new_comment', 'status_update') NOT NULL DEFAULT 'task_assigned',
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_is_read` (`is_read`),
  KEY `idx_task_id` (`task_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX `idx_periode` ON `projects`(`periode_start`, `periode_end`);
CREATE INDEX `idx_task_status_assigned` ON `tasks`(`status`, `assigned_to`);

INSERT INTO `projects` (`title`, `description`, `type`, `periode_start`, `periode_end`, `deadline`, `status`, `created_by`) VALUES
('Weekly Content - May Week 3', 'Social media content for third week of May 2026', 'weekly', '2026-05-18', '2026-05-24', '2026-05-24', 'active', 1);

INSERT INTO `tasks` (`project_id`, `title`, `description`, `assigned_to`, `deadline`, `status`, `priority`, `created_by`) VALUES
(1, 'Create Instagram Reel - Product Showcase', 'Create 15-second reel showcasing new product', 2, '2026-05-22', 'pending', 'high', 1),
(1, 'Design Facebook Post Template', 'Design branded template for daily posts', 3, '2026-05-21', 'on_progress', 'medium', 1),
(1, 'Schedule LinkedIn Posts', 'Schedule 5 posts for next week', 4, '2026-05-23', 'pending', 'medium', 1);

INSERT INTO `task_tags` (`task_id`, `tag_id`) VALUES
(1, 1), (1, 5),
(2, 2), (2, 5),
(3, 3);

INSERT INTO `notifications` (`user_id`, `task_id`, `message`, `type`) VALUES
(2, 1, 'New task assigned: Create Instagram Reel - Product Showcase', 'task_assigned'),
(3, 2, 'New task assigned: Design Facebook Post Template', 'task_assigned'),
(4, 3, 'New task assigned: Schedule LinkedIn Posts', 'task_assigned');
