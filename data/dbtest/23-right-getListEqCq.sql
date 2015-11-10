INSERT IGNORE INTO `permission` (`libelle`) VALUES ('component.getListEqCq');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'component.getListEqCq'));
