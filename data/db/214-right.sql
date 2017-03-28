INSERT IGNORE INTO `permission` (`libelle`) VALUES ('page.add');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (0, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'page.add'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('page.get');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (0, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'page.get'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('page.update');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (0, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'page.update'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('page.delete');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (0, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'page.delete'));

