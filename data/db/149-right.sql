INSERT IGNORE INTO `permission` (`libelle`) VALUES ('m_getListRequestByContact');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'm_getListRequestByContact'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('m_getListRequestByUser');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'm_getListRequestByUser'));