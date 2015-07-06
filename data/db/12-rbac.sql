INSERT INTO `role_relation` (`role_id`, `parent_id`) VALUES ('0', '4');
INSERT INTO `role_relation` (`role_id`, `parent_id`) VALUES ('2', '1');
INSERT INTO `role_relation` (`role_id`, `parent_id`) VALUES ('3', '2');
INSERT INTO `role_relation` (`role_id`, `parent_id`) VALUES ('5', '3');
INSERT INTO `role_relation` (`role_id`, `parent_id`) VALUES ('4', '5');

/*
INSERT INTO `role` (`id`, `name`) VALUES ('1', 'super-admin');
INSERT INTO `role` (`id`, `name`) VALUES ('2', 'admin');
INSERT INTO `role` (`id`, `name`) VALUES ('3', 'academic');
INSERT INTO `role` (`id`, `name`) VALUES ('4', 'student');
INSERT INTO `role` (`id`, `name`) VALUES ('5', 'instructor');
INSERT INTO `role` (`id`, `name`) VALUES ('6', 'recruiter');
*/

INSERT INTO `permission` (`libelle`) VALUES ('mail.getTpl');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('mail.addTpl');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('mail.getListTpl');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.login');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconfinvitation.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconfinvitation.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.join');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.validTransfertVideo');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.record');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.implode');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.getRoom');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.getListVideoUpload');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);



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
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('program.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('program.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('program.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('message.send');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.getIdentity');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.deleteProgram');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('course.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.addCourse');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.deleteCourse');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('course.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('course.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('course.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('course.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('module.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('gradingpolicy.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('module.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('module.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('module.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('gradingpolicy.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('materialdocument.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('materialdocument.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('materialdocument.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.getListGrade');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemassignment.addComment');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemassignment.setGrade');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('gradingpolicygrade.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.getListGradeDetail');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('gradingpolicygradecomment.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemprog.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemprog.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemprog.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.getListByModule');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemassignment.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('grading.getBySchool');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.addConversation');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);
