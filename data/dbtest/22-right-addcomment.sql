INSERT IGNORE INTO `permission` (`libelle`) VALUES ('eventcomment.add');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'eventcomment.add'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('eventcomment.update');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'eventcomment.update'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('eventcomment.delete');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'eventcomment.delete'));
