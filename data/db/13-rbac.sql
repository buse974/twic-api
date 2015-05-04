INSERT INTO `role_relation` (`role_id`, `parent_id`) VALUES ('2', '1');
INSERT INTO `role_relation` (`role_id`, `parent_id`) VALUES ('3', '2');
INSERT INTO `role_relation` (`role_id`, `parent_id`) VALUES ('5', '3');
INSERT INTO `role_relation` (`role_id`, `parent_id`) VALUES ('4', '5');


INSERT INTO `permission` (`libelle`) VALUES ('user.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('4', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('program.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('program.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.addProgram');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.removeProgram');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.logout');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

