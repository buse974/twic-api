INSERT INTO `permission` (`libelle`) VALUES ('materialdocument.nbrView');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);
