INSERT IGNORE INTO `permission` (`libelle`) VALUES ('gradingpolicy.delete');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'gradingpolicy.delete'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('gradingpolicy.getListByCourse');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'gradingpolicy.getListByCourse'));

