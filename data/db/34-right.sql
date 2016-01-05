INSERT INTO `permission` (`libelle`) VALUES ('gradingpolicy.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('gradingpolicy.getListByCourse');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);
