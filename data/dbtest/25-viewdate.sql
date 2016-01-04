ALTER TABLE `event_user` 
ADD COLUMN `view_date` DATETIME NULL DEFAULT NULL AFTER `read_date`;

