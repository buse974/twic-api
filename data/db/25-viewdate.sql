ALTER TABLE `event_user` 
ADD COLUMN `view_date` DATETIME NULL DEFAULT NULL AFTER `read_date`;

INSERT INTO `permission` (`libelle`) VALUES ('eventuser.view');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);
