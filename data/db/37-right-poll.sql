INSERT IGNORE INTO `permission` (`libelle`) VALUES ('poll.add');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'poll.add'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('poll.delete');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'poll.delete'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('poll.get');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'poll.get'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('poll.vote');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'poll.vote'));

