INSERT IGNORE INTO `permission` (`libelle`) VALUES ('grading.getByProgram');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'grading.getByProgram'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('grading.updateProgram');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'grading.updateProgram'));
