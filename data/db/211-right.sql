INSERT IGNORE INTO `permission` (`libelle`) VALUES ('user.get');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (0, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'user.get'));


