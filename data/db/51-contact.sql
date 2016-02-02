ALTER TABLE `contact` 
ADD COLUMN `deleted` INT NOT NULL DEFAULT 0 AFTER `accepted_date`,
ADD COLUMN `accepted` INT NOT NULL DEFAULT 0 AFTER `deleted`,
ADD COLUMN `requested` INT NOT NULL DEFAULT 0 AFTER `accepted`;

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('user.getListContact');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'user.getListContact'));
