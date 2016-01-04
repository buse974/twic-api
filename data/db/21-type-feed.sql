ALTER TABLE `feed` ADD COLUMN `type` ENUM('social', 'academic') NOT NULL DEFAULT 'social' AFTER `deleted_date`;

