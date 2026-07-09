
ALTER TABLE `tasks`
ADD COLUMN `completed_at` DATETIME NULL AFTER `updated_at`,
ADD COLUMN `quality_score` INT DEFAULT NULL COMMENT 'Quality rating 1-5' AFTER `completed_at`,
ADD COLUMN `revision_count` INT DEFAULT 0 AFTER `quality_score`,
ADD INDEX `idx_completed_at` (`completed_at`);

CREATE TABLE IF NOT EXISTS `task_revisions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `task_id` INT(11) NOT NULL,
  `revised_by` INT(11) NOT NULL,
  `revision_reason` TEXT,
  `revision_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_task_id` (`task_id`),
  KEY `idx_revised_by` (`revised_by`),
  KEY `idx_revision_date` (`revision_date`),
  FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`revised_by`) REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `kpis` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `period_type` ENUM('daily', 'weekly', 'monthly') NOT NULL DEFAULT 'weekly',
  `period_start` DATE NOT NULL,
  `period_end` DATE NOT NULL,
  `tasks_assigned` INT DEFAULT 0,
  `tasks_done` INT DEFAULT 0,
  `tasks_on_time` INT DEFAULT 0,
  `tasks_overdue` INT DEFAULT 0,
  `tasks_revised` INT DEFAULT 0,
  `completion_rate` DECIMAL(5,2) DEFAULT 0.00,
  `ontime_rate` DECIMAL(5,2) DEFAULT 0.00,
  `quality_avg` DECIMAL(3,2) DEFAULT 0.00,
  `performance_score` DECIMAL(5,2) DEFAULT 0.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_period` (`user_id`, `period_type`, `period_start`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_period_type` (`period_type`),
  KEY `idx_period_start` (`period_start`),
  KEY `idx_performance_score` (`performance_score`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `kpi_settings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT,
  `description` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kpi_settings` (`setting_key`, `setting_value`, `description`) VALUES
('completion_weight', '30', 'Weight for completion rate in performance score (%)'),
('ontime_weight', '40', 'Weight for on-time rate in performance score (%)'),
('quality_weight', '30', 'Weight for quality score in performance score (%)'),
('auto_calculate', '1', 'Auto-calculate KPI daily (1=enabled, 0=disabled)'),
('min_quality_score', '1', 'Minimum quality rating'),
('max_quality_score', '5', 'Maximum quality rating');

CREATE OR REPLACE VIEW `v_user_task_summary` AS
SELECT
    u.id AS user_id,
    u.name AS user_name,
    r.name AS role_name,
    r.display_name AS role_display,
    COUNT(t.id) AS total_tasks,
    SUM(CASE WHEN t.status = 'done' THEN 1 ELSE 0 END) AS tasks_done,
    SUM(CASE WHEN t.status = 'pending' THEN 1 ELSE 0 END) AS tasks_pending,
    SUM(CASE WHEN t.status = 'on_progress' THEN 1 ELSE 0 END) AS tasks_progress,
    SUM(CASE WHEN t.status = 'done' AND DATE(t.completed_at) <= t.deadline THEN 1 ELSE 0 END) AS tasks_on_time,
    SUM(CASE WHEN t.status = 'done' AND DATE(t.completed_at) > t.deadline THEN 1 ELSE 0 END) AS tasks_late,
    SUM(CASE WHEN t.deadline < CURDATE() AND t.status != 'done' THEN 1 ELSE 0 END) AS tasks_overdue,
    AVG(CASE WHEN t.quality_score IS NOT NULL THEN t.quality_score ELSE NULL END) AS avg_quality,
    SUM(t.revision_count) AS total_revisions
FROM `users` u
LEFT JOIN `roles` r ON r.id = u.role_id
LEFT JOIN `tasks` t ON t.assigned_to = u.id
WHERE u.is_active = 1
GROUP BY u.id, u.name, r.name, r.display_name;

DELIMITER $$

DROP TRIGGER IF EXISTS `tr_task_completed_at`$$

CREATE TRIGGER `tr_task_completed_at`
BEFORE UPDATE ON `tasks`
FOR EACH ROW
BEGIN
    IF NEW.status = 'done' AND OLD.status != 'done' THEN
        SET NEW.completed_at = NOW();
    END IF;

    IF NEW.status != 'done' AND OLD.status = 'done' THEN
        SET NEW.completed_at = NULL;
    END IF;
END$$

DELIMITER ;

