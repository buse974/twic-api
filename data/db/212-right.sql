INSERT IGNORE INTO `permission` (`libelle`) VALUES ('user.add');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (0, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'user.add'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('contact.add');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (0, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'contact.add'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('contact.getListRequestId');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (0, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'contact.getListRequestId'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('contact.accept');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (0, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'contact.accept'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('contact.remove');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (0, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'contact.remove'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('contact.getListId');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (0, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'contact.getListId'));
