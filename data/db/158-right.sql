INSERT IGNORE INTO `permission` (`libelle`) VALUES ('post.getList');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'post.getList'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('post.add');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'post.add'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('post.delete');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'post.delete'));
