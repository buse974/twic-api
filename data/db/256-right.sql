INSERT IGNORE INTO `permission` (`libelle`) VALUES ('user.sendPassword');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'user.sendPassword'));
