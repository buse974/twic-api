INSERT INTO `user` (`firstname`, `lastname`, `email`, `password`) VALUES ('Paul', 'Boussekey', 'pboussekey@thestudnet.com', '4ac91ac4cb1614b368e3dff3ac718f1d');
INSERT INTO `user` (`firstname`, `lastname`, `email`, `password`) VALUES ('Xuan-Anh', 'Hoang', 'xhoang@thestudnet.com', '4ac91ac4cb1614b368e3dff3ac718f1d');
INSERT INTO `user` (`firstname`, `lastname`, `email`, `password`) VALUES ('Christophe', 'Robert', 'crobert@thestudnet.com', '4ac91ac4cb1614b368e3dff3ac718f1d');
INSERT INTO `user` (`firstname`, `lastname`, `email`, `password`) VALUES ('Salim', 'Bendacha', 'sbendacha@thestudnet.com', '4ac91ac4cb1614b368e3dff3ac718f1d');
INSERT INTO `user` (`firstname`, `lastname`, `email`, `password`) VALUES ('Sébastien', 'Sayegh', 'ssayegh@thestudnet.com', '4ac91ac4cb1614b368e3dff3ac718f1d');
INSERT INTO `user` (`firstname`, `lastname`, `email`, `password`) VALUES ('Guillaume', 'Masmejean', 'gmasmejean@thestudnet.com', '4ac91ac4cb1614b368e3dff3ac718f1d');
INSERT INTO `user` (`firstname`, `lastname`, `email`, `password`) VALUES ('Arthur', 'Flachs', 'aflachs@thestudnet.com', '4ac91ac4cb1614b368e3dff3ac718f1d');

INSERT INTO `language_level` (`level`) VALUES ('Elementary proficiency');
INSERT INTO `language_level` (`level`) VALUES ('Limited working proficiency');
INSERT INTO `language_level` (`level`) VALUES ('Professional working proficiency');
INSERT INTO `language_level` (`level`) VALUES ('Full professional proficiency');
INSERT INTO `language_level` (`level`) VALUES ('Native or bilingual proficiency');

INSERT INTO `grading` (`letter`, `min`, `max`, `grade`, `description`, `tpl`) VALUES ('A', 95, 100, 4.0, 'Outstanding performance, works shows superior command of the subject.', true);
INSERT INTO `grading` (`letter`, `min`, `max`, `grade`, `description`, `tpl`) VALUES ('A-', 90, 94, 3.7, 'Very good work showing understanding and mastery of all concepts.', true);
INSERT INTO `grading` (`letter`, `min`, `max`, `grade`, `description`, `tpl`) VALUES ('B+', 87, 89, 3.3, 'Good work showing understanding and mastery of most concepts.', true);
INSERT INTO `grading` (`letter`, `min`, `max`, `grade`, `description`, `tpl`) VALUES ('B', 83, 86, 3.0, 'Fairly good work that shows an understanding of the main concepts.', true);
INSERT INTO `grading` (`letter`, `min`, `max`, `grade`, `description`, `tpl`) VALUES ('B-', 80, 82, 2.7, 'Fairly good work showing understanding of several important concepts.', true);
INSERT INTO `grading` (`letter`, `min`, `max`, `grade`, `description`, `tpl`) VALUES ('C+', 77, 79, 2.3, 'Uneven understanding of the concepts with occasional lack of clarity', true);
INSERT INTO `grading` (`letter`, `min`, `max`, `grade`, `description`, `tpl`) VALUES ('C', 73, 76, 2.0, 'Work that barely meets modest expectations for the class', true);
INSERT INTO `grading` (`letter`, `min`, `max`, `grade`, `description`, `tpl`) VALUES ('C-', 70, 72, 1.7, 'Work that is below modest expectations for the class', true);
INSERT INTO `grading` (`letter`, `min`, `max`, `grade`, `description`, `tpl`) VALUES ('D+', 67, 69, 1.3, 'Poor performance with lack of understanding of several important concepts', true);
INSERT INTO `grading` (`letter`, `min`, `max`, `grade`, `description`, `tpl`) VALUES ('D', 63, 66, 1.0, 'Work that is marginally above the minimum expectations for the class', true);
INSERT INTO `grading` (`letter`, `min`, `max`, `grade`, `description`, `tpl`) VALUES ('D-', 60, 62, 0.7, 'Work that barely meets the minimum expectations for the class', true);
INSERT INTO `grading` (`letter`, `min`, `max`, `grade`, `description`, `tpl`) VALUES ('F', 0, 59, 0, 'Work does not meet the minimum expectations for the class', true);

INSERT INTO `grading_policy` (`name`, `tpl`, `mandatory`, `type`,`grade`) VALUES ('Individual assignment', true, true, 'IA', 20);
INSERT INTO `grading_policy` (`name`, `tpl`, `mandatory`, `type`, `grade`) VALUES ('Group work', true, true, 'WG', 20);
INSERT INTO `grading_policy` (`name`, `tpl`, `mandatory`, `type`, `grade`) VALUES ('Live class', true, true, 'LC', 20);
INSERT INTO `grading_policy` (`name`, `tpl`, `mandatory`, `type`, `grade`) VALUES ('Capstone project', true, true, 'CP', 20);
INSERT INTO `grading_policy` (`name`, `tpl`, `mandatory`, `grade`) VALUES ('Attendance and participation', true, true, 20);

SET SESSION sql_mode='NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `role` (`id`, `name`) VALUES (0, 'guest');
INSERT INTO `role` (`id`, `name`) VALUES ('1', 'super_admin');
INSERT INTO `role` (`id`, `name`) VALUES ('2', 'admin');
INSERT INTO `role` (`id`, `name`) VALUES ('3', 'academic');
INSERT INTO `role` (`id`, `name`) VALUES ('4', 'student');
INSERT INTO `role` (`id`, `name`) VALUES ('5', 'instructor');
INSERT INTO `role` (`id`, `name`) VALUES ('6', 'recruiter');

INSERT INTO `role_relation` (`role_id`, `parent_id`) VALUES ('0', '4');
INSERT INTO `role_relation` (`role_id`, `parent_id`) VALUES ('2', '1');
INSERT INTO `role_relation` (`role_id`, `parent_id`) VALUES ('3', '2');
INSERT INTO `role_relation` (`role_id`, `parent_id`) VALUES ('5', '3');
INSERT INTO `role_relation` (`role_id`, `parent_id`) VALUES ('4', '5');

INSERT INTO `permission` (`libelle`) VALUES ('mail.getTpl');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('mail.addTpl');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('mail.getListTpl');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('0', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.lostPassword');
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

INSERT INTO `permission` (`libelle`) VALUES ('user.getListByItemProgWithInstrutor');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES ('5', @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('program.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('program.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.getListLite');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

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
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.deleteCourse');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('course.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('course.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('course.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('course.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('module.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('gradingpolicy.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('module.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('module.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('module.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('gradingpolicy.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('materialdocument.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('materialdocument.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('materialdocument.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.getListGrade');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemassignment.addComment');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemassignment.setGrade');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('gradingpolicygrade.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.getListGradeDetail');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('gradingpolicygradecomment.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemprog.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemprog.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemprog.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.getListByModule');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemassignment.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('grading.getBySchool');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('task.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('task.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('task.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemprog.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemprog.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('task.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.getListForItemProg');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5 , @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.getStudentList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemassignment.getSubmission');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemassignment.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemassignment.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemassignmentcomment.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('course.getListRecord');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('school.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('school.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('school.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('school.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('contact.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('contact.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('contact.accept');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('contact.remove');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('contact.getListRequest');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('research.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.joinUser');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconfdoc.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconfdoc.getListByVideoconf');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.addConversation');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('conversation.getConversation');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('conversationuser.getConversationByUser');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('message.send');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('message.sendVideoConf');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('permission.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('permission.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('permission.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('permission.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('rolepermission.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('rolepermission.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.startRecord');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('videoconf.stopRecord');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('country.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('message.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('conversation.read');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('message.read');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('message.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('conversation.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('conversation.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('message.sendMail');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('message.getListConversation');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemassignment.removeDocument');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemassignment.addDocument');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('conversation.unRead');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('message.unRead');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('feed.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('feed.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('feed.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('feed.addComment');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('feed.deleteComment');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('feed.getListComment');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('like.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('like.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('like.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('feed.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('resume.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('resume.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('resume.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('resume.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('user.updatePassword');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('feed.linkPreview');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('address.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('address.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('address.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('address.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('city.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('city.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('city.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('city.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('division.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('division.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('division.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('division.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('questionnaire.getByItemProg');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('questionnaire.answer');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('answer.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('questionnaire.getAnswer');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('thread.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('thread.getNbrMessage');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('message.getNbrMessage');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('thread.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('thread.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('thread.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('threadmessage.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('threadmessage.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('threadmessage.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('threadmessage.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('component.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('component.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('component.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('component.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.getByItemProg');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemproguser.start');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemproguser.end');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemproguser.getStartedConference');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('thread.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('school.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('event.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('userlanguage.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('userlanguage.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('userlanguage.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('userlanguage.get');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('dimension.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('dimension.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('dimension.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('dimension.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('question.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('question.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('question.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('question.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('component.getListWithScale');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('gradingpolicygrade.setGrade');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('language.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('languagelevel.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('eventuser.read');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('activity.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('activity.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('activity.aggregate');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('course.getListDetail');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('scale.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('scale.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('scale.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('scale.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('dimensionscale.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('dimensionscale.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('dimensionscale.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('dimensionscale.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('component.getEqCq');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('componentscale.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('componentscale.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('componentscale.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('componentscale.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('guidelines.add');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('guidelines.update');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('guidelines.delete');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('guidelines.getList');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('guidelines.isViewed');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('item.getListGradeItem');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('materialdocument.getListByItem');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, @rbac_permission_id);

INSERT INTO `contact` (`accepted_date`, `contact_id`, `user_id`) VALUES 
('2015-08-11 13:17:25', 1,2),
('2015-08-11 13:17:25', 2,1);

INSERT INTO `language` (`libelle`) VALUES ('Afar'); 
INSERT INTO `language` (`libelle`) VALUES ('Abkhazian'); 
INSERT INTO `language` (`libelle`) VALUES ('Achinese'); 
INSERT INTO `language` (`libelle`) VALUES ('Acoli'); 
INSERT INTO `language` (`libelle`) VALUES ('Adangme'); 
INSERT INTO `language` (`libelle`) VALUES ('Adyghe'); 
INSERT INTO `language` (`libelle`) VALUES ('Afro-Asiatic languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Afrihili'); 
INSERT INTO `language` (`libelle`) VALUES ('Afrikaans'); 
INSERT INTO `language` (`libelle`) VALUES ('Ainu'); 
INSERT INTO `language` (`libelle`) VALUES ('Akan'); 
INSERT INTO `language` (`libelle`) VALUES ('Akkadian'); 
INSERT INTO `language` (`libelle`) VALUES ('Albanian'); 
INSERT INTO `language` (`libelle`) VALUES ('Aleut'); 
INSERT INTO `language` (`libelle`) VALUES ('Algonquian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Southern Altai'); 
INSERT INTO `language` (`libelle`) VALUES ('Amharic'); 
INSERT INTO `language` (`libelle`) VALUES ('English, Old (ca.450-1100)'); 
INSERT INTO `language` (`libelle`) VALUES ('Angika'); 
INSERT INTO `language` (`libelle`) VALUES ('Apache languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Arabic'); 
INSERT INTO `language` (`libelle`) VALUES ('Official Aramaic (700-300 BCE)'); 
INSERT INTO `language` (`libelle`) VALUES ('Aragonese'); 
INSERT INTO `language` (`libelle`) VALUES ('Armenian'); 
INSERT INTO `language` (`libelle`) VALUES ('Mapudungun'); 
INSERT INTO `language` (`libelle`) VALUES ('Arapaho'); 
INSERT INTO `language` (`libelle`) VALUES ('Artificial languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Arawak'); 
INSERT INTO `language` (`libelle`) VALUES ('Assamese'); 
INSERT INTO `language` (`libelle`) VALUES ('Asturian'); 
INSERT INTO `language` (`libelle`) VALUES ('Athapascan languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Australian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Avaric'); 
INSERT INTO `language` (`libelle`) VALUES ('Avestan'); 
INSERT INTO `language` (`libelle`) VALUES ('Awadhi'); 
INSERT INTO `language` (`libelle`) VALUES ('Aymara'); 
INSERT INTO `language` (`libelle`) VALUES ('Azerbaijani'); 
INSERT INTO `language` (`libelle`) VALUES ('Banda languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Bamileke languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Bashkir'); 
INSERT INTO `language` (`libelle`) VALUES ('Baluchi'); 
INSERT INTO `language` (`libelle`) VALUES ('Bambara'); 
INSERT INTO `language` (`libelle`) VALUES ('Balinese'); 
INSERT INTO `language` (`libelle`) VALUES ('Basque'); 
INSERT INTO `language` (`libelle`) VALUES ('Basa'); 
INSERT INTO `language` (`libelle`) VALUES ('Baltic languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Beja'); 
INSERT INTO `language` (`libelle`) VALUES ('Belarusian'); 
INSERT INTO `language` (`libelle`) VALUES ('Bemba'); 
INSERT INTO `language` (`libelle`) VALUES ('Bengali'); 
INSERT INTO `language` (`libelle`) VALUES ('Berber languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Bhojpuri'); 
INSERT INTO `language` (`libelle`) VALUES ('Bihari languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Bikol'); 
INSERT INTO `language` (`libelle`) VALUES ('Bini'); 
INSERT INTO `language` (`libelle`) VALUES ('Bislama'); 
INSERT INTO `language` (`libelle`) VALUES ('Siksika'); 
INSERT INTO `language` (`libelle`) VALUES ('Bantu languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Tibetan'); 
INSERT INTO `language` (`libelle`) VALUES ('Bosnian'); 
INSERT INTO `language` (`libelle`) VALUES ('Braj'); 
INSERT INTO `language` (`libelle`) VALUES ('Breton'); 
INSERT INTO `language` (`libelle`) VALUES ('Batak languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Buriat'); 
INSERT INTO `language` (`libelle`) VALUES ('Buginese'); 
INSERT INTO `language` (`libelle`) VALUES ('Bulgarian'); 
INSERT INTO `language` (`libelle`) VALUES ('Burmese'); 
INSERT INTO `language` (`libelle`) VALUES ('Blin'); 
INSERT INTO `language` (`libelle`) VALUES ('Caddo'); 
INSERT INTO `language` (`libelle`) VALUES ('Central American Indian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Galibi Carib'); 
INSERT INTO `language` (`libelle`) VALUES ('Catalan'); 
INSERT INTO `language` (`libelle`) VALUES ('Caucasian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Cebuano'); 
INSERT INTO `language` (`libelle`) VALUES ('Celtic languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Czech'); 
INSERT INTO `language` (`libelle`) VALUES ('Chamorro'); 
INSERT INTO `language` (`libelle`) VALUES ('Chibcha'); 
INSERT INTO `language` (`libelle`) VALUES ('Chechen'); 
INSERT INTO `language` (`libelle`) VALUES ('Chagatai'); 
INSERT INTO `language` (`libelle`) VALUES ('Chinese'); 
INSERT INTO `language` (`libelle`) VALUES ('Chuukese'); 
INSERT INTO `language` (`libelle`) VALUES ('Mari'); 
INSERT INTO `language` (`libelle`) VALUES ('Chinook jargon'); 
INSERT INTO `language` (`libelle`) VALUES ('Choctaw'); 
INSERT INTO `language` (`libelle`) VALUES ('Dene Suline'); 
INSERT INTO `language` (`libelle`) VALUES ('Cherokee'); 
INSERT INTO `language` (`libelle`) VALUES ('Church Slavonic'); 
INSERT INTO `language` (`libelle`) VALUES ('Chuvash'); 
INSERT INTO `language` (`libelle`) VALUES ('Cheyenne'); 
INSERT INTO `language` (`libelle`) VALUES ('Chamic languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Coptic'); 
INSERT INTO `language` (`libelle`) VALUES ('Cornish'); 
INSERT INTO `language` (`libelle`) VALUES ('Corsican'); 
INSERT INTO `language` (`libelle`) VALUES ('Creoles and pidgins, English-based'); 
INSERT INTO `language` (`libelle`) VALUES ('Creoles and pidgins, French-based'); 
INSERT INTO `language` (`libelle`) VALUES ('Creoles and pidgins, Portuguese-based'); 
INSERT INTO `language` (`libelle`) VALUES ('Cree'); 
INSERT INTO `language` (`libelle`) VALUES ('Crimean Tatar'); 
INSERT INTO `language` (`libelle`) VALUES ('Creoles and pidgins'); 
INSERT INTO `language` (`libelle`) VALUES ('Kashubian'); 
INSERT INTO `language` (`libelle`) VALUES ('Cushitic languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Welsh'); 
INSERT INTO `language` (`libelle`) VALUES ('Czech'); 
INSERT INTO `language` (`libelle`) VALUES ('Dakota'); 
INSERT INTO `language` (`libelle`) VALUES ('Danish'); 
INSERT INTO `language` (`libelle`) VALUES ('Dargwa'); 
INSERT INTO `language` (`libelle`) VALUES ('Land Dayak languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Delaware'); 
INSERT INTO `language` (`libelle`) VALUES ('Slave (Athapascan)'); 
INSERT INTO `language` (`libelle`) VALUES ('German'); 
INSERT INTO `language` (`libelle`) VALUES ('Dogrib'); 
INSERT INTO `language` (`libelle`) VALUES ('Dinka'); 
INSERT INTO `language` (`libelle`) VALUES ('Dhivehi'); 
INSERT INTO `language` (`libelle`) VALUES ('Dogri'); 
INSERT INTO `language` (`libelle`) VALUES ('Dravidian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Lower Sorbian'); 
INSERT INTO `language` (`libelle`) VALUES ('Duala'); 
INSERT INTO `language` (`libelle`) VALUES ('Dutch, Middle (ca.1050-1350)'); 
INSERT INTO `language` (`libelle`) VALUES ('Dutch'); 
INSERT INTO `language` (`libelle`) VALUES ('Dyula'); 
INSERT INTO `language` (`libelle`) VALUES ('Dzongkha'); 
INSERT INTO `language` (`libelle`) VALUES ('Efik'); 
INSERT INTO `language` (`libelle`) VALUES ('Egyptian (Ancient)'); 
INSERT INTO `language` (`libelle`) VALUES ('Ekajuk'); 
INSERT INTO `language` (`libelle`) VALUES ('Greek'); 
INSERT INTO `language` (`libelle`) VALUES ('Elamite'); 
INSERT INTO `language` (`libelle`) VALUES ('English'); 
INSERT INTO `language` (`libelle`) VALUES ('English, Middle (1100-1500)'); 
INSERT INTO `language` (`libelle`) VALUES ('Esperanto'); 
INSERT INTO `language` (`libelle`) VALUES ('Estonian'); 
INSERT INTO `language` (`libelle`) VALUES ('Basque'); 
INSERT INTO `language` (`libelle`) VALUES ('Ewe'); 
INSERT INTO `language` (`libelle`) VALUES ('Ewondo'); 
INSERT INTO `language` (`libelle`) VALUES ('Fang'); 
INSERT INTO `language` (`libelle`) VALUES ('Faroese'); 
INSERT INTO `language` (`libelle`) VALUES ('Persian'); 
INSERT INTO `language` (`libelle`) VALUES ('Fanti'); 
INSERT INTO `language` (`libelle`) VALUES ('Fijian'); 
INSERT INTO `language` (`libelle`) VALUES ('Filipino'); 
INSERT INTO `language` (`libelle`) VALUES ('Finnish'); 
INSERT INTO `language` (`libelle`) VALUES ('Finno-Ugrian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Fon'); 
INSERT INTO `language` (`libelle`) VALUES ('French'); 
INSERT INTO `language` (`libelle`) VALUES ('French, Middle (ca.1400-1600)'); 
INSERT INTO `language` (`libelle`) VALUES ('French, Old (842-ca.1400)'); 
INSERT INTO `language` (`libelle`) VALUES ('Northern Frisian'); 
INSERT INTO `language` (`libelle`) VALUES ('Eastern Frisian'); 
INSERT INTO `language` (`libelle`) VALUES ('Western Frisian'); 
INSERT INTO `language` (`libelle`) VALUES ('Fulah'); 
INSERT INTO `language` (`libelle`) VALUES ('Friulian'); 
INSERT INTO `language` (`libelle`) VALUES ('Ga'); 
INSERT INTO `language` (`libelle`) VALUES ('Gayo'); 
INSERT INTO `language` (`libelle`) VALUES ('Gbaya'); 
INSERT INTO `language` (`libelle`) VALUES ('Germanic languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Georgian'); 
INSERT INTO `language` (`libelle`) VALUES ('German'); 
INSERT INTO `language` (`libelle`) VALUES ('Geez'); 
INSERT INTO `language` (`libelle`) VALUES ('Gilbertese'); 
INSERT INTO `language` (`libelle`) VALUES ('Gaelic'); 
INSERT INTO `language` (`libelle`) VALUES ('Irish'); 
INSERT INTO `language` (`libelle`) VALUES ('Galician'); 
INSERT INTO `language` (`libelle`) VALUES ('Manx'); 
INSERT INTO `language` (`libelle`) VALUES ('German, Middle High (ca.1050-1500)'); 
INSERT INTO `language` (`libelle`) VALUES ('German, Old High (ca.750-1050)'); 
INSERT INTO `language` (`libelle`) VALUES ('Gondi'); 
INSERT INTO `language` (`libelle`) VALUES ('Gorontalo'); 
INSERT INTO `language` (`libelle`) VALUES ('Gothic'); 
INSERT INTO `language` (`libelle`) VALUES ('Grebo'); 
INSERT INTO `language` (`libelle`) VALUES ('Greek, Ancient (to 1453)'); 
INSERT INTO `language` (`libelle`) VALUES ('Greek'); 
INSERT INTO `language` (`libelle`) VALUES ('Guarani'); 
INSERT INTO `language` (`libelle`) VALUES ('Alsatian'); 
INSERT INTO `language` (`libelle`) VALUES ('Gujarati'); 
INSERT INTO `language` (`libelle`) VALUES ('Gwich&#39;in'); 
INSERT INTO `language` (`libelle`) VALUES ('Haida'); 
INSERT INTO `language` (`libelle`) VALUES ('Haitian Creole'); 
INSERT INTO `language` (`libelle`) VALUES ('Hausa'); 
INSERT INTO `language` (`libelle`) VALUES ('Hawaiian'); 
INSERT INTO `language` (`libelle`) VALUES ('Hebrew'); 
INSERT INTO `language` (`libelle`) VALUES ('Herero'); 
INSERT INTO `language` (`libelle`) VALUES ('Hiligaynon'); 
INSERT INTO `language` (`libelle`) VALUES ('Himachali languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Hindi'); 
INSERT INTO `language` (`libelle`) VALUES ('Hittite'); 
INSERT INTO `language` (`libelle`) VALUES ('Hmong'); 
INSERT INTO `language` (`libelle`) VALUES ('Hiri Motu'); 
INSERT INTO `language` (`libelle`) VALUES ('Croatian'); 
INSERT INTO `language` (`libelle`) VALUES ('Upper Sorbian'); 
INSERT INTO `language` (`libelle`) VALUES ('Hungarian'); 
INSERT INTO `language` (`libelle`) VALUES ('Hupa'); 
INSERT INTO `language` (`libelle`) VALUES ('Armenian'); 
INSERT INTO `language` (`libelle`) VALUES ('Iban'); 
INSERT INTO `language` (`libelle`) VALUES ('Igbo'); 
INSERT INTO `language` (`libelle`) VALUES ('Icelandic'); 
INSERT INTO `language` (`libelle`) VALUES ('Ido'); 
INSERT INTO `language` (`libelle`) VALUES ('Nuosu'); 
INSERT INTO `language` (`libelle`) VALUES ('Ijo languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Inuktitut'); 
INSERT INTO `language` (`libelle`) VALUES ('Occidental'); 
INSERT INTO `language` (`libelle`) VALUES ('Iloko'); 
INSERT INTO `language` (`libelle`) VALUES ('Interlingua (International Auxiliary Language Association)'); 
INSERT INTO `language` (`libelle`) VALUES ('Indic languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Indonesian'); 
INSERT INTO `language` (`libelle`) VALUES ('Indo-European languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Ingush'); 
INSERT INTO `language` (`libelle`) VALUES ('Inupiaq'); 
INSERT INTO `language` (`libelle`) VALUES ('Iranian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Iroquoian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Icelandic'); 
INSERT INTO `language` (`libelle`) VALUES ('Italian'); 
INSERT INTO `language` (`libelle`) VALUES ('Javanese'); 
INSERT INTO `language` (`libelle`) VALUES ('Lojban'); 
INSERT INTO `language` (`libelle`) VALUES ('Japanese'); 
INSERT INTO `language` (`libelle`) VALUES ('Judeo-Persian'); 
INSERT INTO `language` (`libelle`) VALUES ('Judeo-Arabic'); 
INSERT INTO `language` (`libelle`) VALUES ('Kara-Kalpak'); 
INSERT INTO `language` (`libelle`) VALUES ('Kabyle'); 
INSERT INTO `language` (`libelle`) VALUES ('Jingpho'); 
INSERT INTO `language` (`libelle`) VALUES ('Greenlandic'); 
INSERT INTO `language` (`libelle`) VALUES ('Kamba'); 
INSERT INTO `language` (`libelle`) VALUES ('Kannada'); 
INSERT INTO `language` (`libelle`) VALUES ('Karen languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Kashmiri'); 
INSERT INTO `language` (`libelle`) VALUES ('Georgian'); 
INSERT INTO `language` (`libelle`) VALUES ('Kanuri'); 
INSERT INTO `language` (`libelle`) VALUES ('Kawi'); 
INSERT INTO `language` (`libelle`) VALUES ('Kazakh'); 
INSERT INTO `language` (`libelle`) VALUES ('Kabardian'); 
INSERT INTO `language` (`libelle`) VALUES ('Khasi'); 
INSERT INTO `language` (`libelle`) VALUES ('Khoisan languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Central Khmer'); 
INSERT INTO `language` (`libelle`) VALUES ('Khotanese'); 
INSERT INTO `language` (`libelle`) VALUES ('Kikuyu'); 
INSERT INTO `language` (`libelle`) VALUES ('Kinyarwanda'); 
INSERT INTO `language` (`libelle`) VALUES ('Kyrgyz'); 
INSERT INTO `language` (`libelle`) VALUES ('Kimbundu'); 
INSERT INTO `language` (`libelle`) VALUES ('Konkani'); 
INSERT INTO `language` (`libelle`) VALUES ('Komi'); 
INSERT INTO `language` (`libelle`) VALUES ('Kongo'); 
INSERT INTO `language` (`libelle`) VALUES ('Korean'); 
INSERT INTO `language` (`libelle`) VALUES ('Kosraean'); 
INSERT INTO `language` (`libelle`) VALUES ('Kpelle'); 
INSERT INTO `language` (`libelle`) VALUES ('Karachay-Balkar'); 
INSERT INTO `language` (`libelle`) VALUES ('Karelian'); 
INSERT INTO `language` (`libelle`) VALUES ('Kru languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Kurukh'); 
INSERT INTO `language` (`libelle`) VALUES ('Kwanyama'); 
INSERT INTO `language` (`libelle`) VALUES ('Kumyk'); 
INSERT INTO `language` (`libelle`) VALUES ('Kurdish'); 
INSERT INTO `language` (`libelle`) VALUES ('Kutenai'); 
INSERT INTO `language` (`libelle`) VALUES ('Ladino'); 
INSERT INTO `language` (`libelle`) VALUES ('Lahnda'); 
INSERT INTO `language` (`libelle`) VALUES ('Lamba'); 
INSERT INTO `language` (`libelle`) VALUES ('Lao'); 
INSERT INTO `language` (`libelle`) VALUES ('Latin'); 
INSERT INTO `language` (`libelle`) VALUES ('Latvian'); 
INSERT INTO `language` (`libelle`) VALUES ('Lezghian'); 
INSERT INTO `language` (`libelle`) VALUES ('Limburgish'); 
INSERT INTO `language` (`libelle`) VALUES ('Lingala'); 
INSERT INTO `language` (`libelle`) VALUES ('Lithuanian'); 
INSERT INTO `language` (`libelle`) VALUES ('Mongo'); 
INSERT INTO `language` (`libelle`) VALUES ('Lozi'); 
INSERT INTO `language` (`libelle`) VALUES ('Luxembourgish'); 
INSERT INTO `language` (`libelle`) VALUES ('Luba-Lulua'); 
INSERT INTO `language` (`libelle`) VALUES ('Luba-Katanga'); 
INSERT INTO `language` (`libelle`) VALUES ('Ganda'); 
INSERT INTO `language` (`libelle`) VALUES ('Luiseno'); 
INSERT INTO `language` (`libelle`) VALUES ('Lunda'); 
INSERT INTO `language` (`libelle`) VALUES ('Luo (Kenya and Tanzania)'); 
INSERT INTO `language` (`libelle`) VALUES ('Lushai'); 
INSERT INTO `language` (`libelle`) VALUES ('Macedonian'); 
INSERT INTO `language` (`libelle`) VALUES ('Madurese'); 
INSERT INTO `language` (`libelle`) VALUES ('Magahi'); 
INSERT INTO `language` (`libelle`) VALUES ('Marshallese'); 
INSERT INTO `language` (`libelle`) VALUES ('Maithili'); 
INSERT INTO `language` (`libelle`) VALUES ('Makasar'); 
INSERT INTO `language` (`libelle`) VALUES ('Malayalam'); 
INSERT INTO `language` (`libelle`) VALUES ('Mandingo'); 
INSERT INTO `language` (`libelle`) VALUES ('Maori'); 
INSERT INTO `language` (`libelle`) VALUES ('Austronesian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Marathi'); 
INSERT INTO `language` (`libelle`) VALUES ('Masai'); 
INSERT INTO `language` (`libelle`) VALUES ('Malay'); 
INSERT INTO `language` (`libelle`) VALUES ('Moksha'); 
INSERT INTO `language` (`libelle`) VALUES ('Mandar'); 
INSERT INTO `language` (`libelle`) VALUES ('Mende'); 
INSERT INTO `language` (`libelle`) VALUES ('Irish, Middle (900-1200)'); 
INSERT INTO `language` (`libelle`) VALUES ('Mi&#39;kmaq'); 
INSERT INTO `language` (`libelle`) VALUES ('Minangkabau'); 
INSERT INTO `language` (`libelle`) VALUES ('Macedonian'); 
INSERT INTO `language` (`libelle`) VALUES ('Mon-Khmer languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Malagasy'); 
INSERT INTO `language` (`libelle`) VALUES ('Maltese'); 
INSERT INTO `language` (`libelle`) VALUES ('Manchu'); 
INSERT INTO `language` (`libelle`) VALUES ('Manipuri'); 
INSERT INTO `language` (`libelle`) VALUES ('Manobo languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Mohawk'); 
INSERT INTO `language` (`libelle`) VALUES ('Mongolian'); 
INSERT INTO `language` (`libelle`) VALUES ('Mossi'); 
INSERT INTO `language` (`libelle`) VALUES ('Maori'); 
INSERT INTO `language` (`libelle`) VALUES ('Malay'); 
INSERT INTO `language` (`libelle`) VALUES ('Munda languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Creek'); 
INSERT INTO `language` (`libelle`) VALUES ('Mirandese'); 
INSERT INTO `language` (`libelle`) VALUES ('Marwari'); 
INSERT INTO `language` (`libelle`) VALUES ('Burmese'); 
INSERT INTO `language` (`libelle`) VALUES ('Mayan languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Erzya'); 
INSERT INTO `language` (`libelle`) VALUES ('Nahuatl languages'); 
INSERT INTO `language` (`libelle`) VALUES ('North American Indian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Neapolitan'); 
INSERT INTO `language` (`libelle`) VALUES ('Nauru'); 
INSERT INTO `language` (`libelle`) VALUES ('Navajo'); 
INSERT INTO `language` (`libelle`) VALUES ('Ndebele, South'); 
INSERT INTO `language` (`libelle`) VALUES ('Ndebele, North'); 
INSERT INTO `language` (`libelle`) VALUES ('Ndonga'); 
INSERT INTO `language` (`libelle`) VALUES ('Saxon, Low'); 
INSERT INTO `language` (`libelle`) VALUES ('Nepali'); 
INSERT INTO `language` (`libelle`) VALUES ('Nepal Bhasa'); 
INSERT INTO `language` (`libelle`) VALUES ('Nias'); 
INSERT INTO `language` (`libelle`) VALUES ('Niger-Kordofanian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Niuean'); 
INSERT INTO `language` (`libelle`) VALUES ('Dutch'); 
INSERT INTO `language` (`libelle`) VALUES ('Nynorsk, Norwegian'); 
INSERT INTO `language` (`libelle`) VALUES ('Bokm&#xe5;l, Norwegian'); 
INSERT INTO `language` (`libelle`) VALUES ('Nogai'); 
INSERT INTO `language` (`libelle`) VALUES ('Norse, Old'); 
INSERT INTO `language` (`libelle`) VALUES ('Norwegian'); 
INSERT INTO `language` (`libelle`) VALUES ('N&#39;Ko'); 
INSERT INTO `language` (`libelle`) VALUES ('Northern Sotho'); 
INSERT INTO `language` (`libelle`) VALUES ('Nubian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Old Newari'); 
INSERT INTO `language` (`libelle`) VALUES ('Nyanja'); 
INSERT INTO `language` (`libelle`) VALUES ('Nyamwezi'); 
INSERT INTO `language` (`libelle`) VALUES ('Nyankole'); 
INSERT INTO `language` (`libelle`) VALUES ('Nyoro'); 
INSERT INTO `language` (`libelle`) VALUES ('Nzima'); 
INSERT INTO `language` (`libelle`) VALUES ('Occitan'); 
INSERT INTO `language` (`libelle`) VALUES ('Ojibwa'); 
INSERT INTO `language` (`libelle`) VALUES ('Oriya'); 
INSERT INTO `language` (`libelle`) VALUES ('Oromo'); 
INSERT INTO `language` (`libelle`) VALUES ('Osage'); 
INSERT INTO `language` (`libelle`) VALUES ('Ossetic'); 
INSERT INTO `language` (`libelle`) VALUES ('Turkish, Ottoman (1500-1928)'); 
INSERT INTO `language` (`libelle`) VALUES ('Otomian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Papuan languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Pangasinan'); 
INSERT INTO `language` (`libelle`) VALUES ('Pahlavi'); 
INSERT INTO `language` (`libelle`) VALUES ('Kapampangan'); 
INSERT INTO `language` (`libelle`) VALUES ('Punjabi'); 
INSERT INTO `language` (`libelle`) VALUES ('Papiamento'); 
INSERT INTO `language` (`libelle`) VALUES ('Palauan'); 
INSERT INTO `language` (`libelle`) VALUES ('Persian, Old (ca.600-400 B.C.)'); 
INSERT INTO `language` (`libelle`) VALUES ('Persian'); 
INSERT INTO `language` (`libelle`) VALUES ('Philippine languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Phoenician'); 
INSERT INTO `language` (`libelle`) VALUES ('Pali'); 
INSERT INTO `language` (`libelle`) VALUES ('Polish'); 
INSERT INTO `language` (`libelle`) VALUES ('Pohnpeian'); 
INSERT INTO `language` (`libelle`) VALUES ('Portuguese'); 
INSERT INTO `language` (`libelle`) VALUES ('Prakrit languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Occitan, Old (to 1500)'); 
INSERT INTO `language` (`libelle`) VALUES ('Pashto'); 
INSERT INTO `language` (`libelle`) VALUES ('Quechua'); 
INSERT INTO `language` (`libelle`) VALUES ('Rajasthani'); 
INSERT INTO `language` (`libelle`) VALUES ('Rapanui'); 
INSERT INTO `language` (`libelle`) VALUES ('Cook Islands Maori'); 
INSERT INTO `language` (`libelle`) VALUES ('Romance languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Romansh'); 
INSERT INTO `language` (`libelle`) VALUES ('Romany'); 
INSERT INTO `language` (`libelle`) VALUES ('Romanian'); 
INSERT INTO `language` (`libelle`) VALUES ('Romanian'); 
INSERT INTO `language` (`libelle`) VALUES ('Rundi'); 
INSERT INTO `language` (`libelle`) VALUES ('Aromanian'); 
INSERT INTO `language` (`libelle`) VALUES ('Russian'); 
INSERT INTO `language` (`libelle`) VALUES ('Sandawe'); 
INSERT INTO `language` (`libelle`) VALUES ('Sango'); 
INSERT INTO `language` (`libelle`) VALUES ('Yakut'); 
INSERT INTO `language` (`libelle`) VALUES ('South American Indian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Salishan languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Samaritan Aramaic'); 
INSERT INTO `language` (`libelle`) VALUES ('Sanskrit'); 
INSERT INTO `language` (`libelle`) VALUES ('Sasak'); 
INSERT INTO `language` (`libelle`) VALUES ('Santali'); 
INSERT INTO `language` (`libelle`) VALUES ('Sicilian'); 
INSERT INTO `language` (`libelle`) VALUES ('Scots'); 
INSERT INTO `language` (`libelle`) VALUES ('Selkup'); 
INSERT INTO `language` (`libelle`) VALUES ('Semitic languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Irish, Old (to 900)'); 
INSERT INTO `language` (`libelle`) VALUES ('Sign Languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Shan'); 
INSERT INTO `language` (`libelle`) VALUES ('Sidamo'); 
INSERT INTO `language` (`libelle`) VALUES ('Sinhalese'); 
INSERT INTO `language` (`libelle`) VALUES ('Siouan languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Sino-Tibetan languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Slavic languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Slovak'); 
INSERT INTO `language` (`libelle`) VALUES ('Slovak'); 
INSERT INTO `language` (`libelle`) VALUES ('Slovenian'); 
INSERT INTO `language` (`libelle`) VALUES ('Southern Sami'); 
INSERT INTO `language` (`libelle`) VALUES ('Northern Sami'); 
INSERT INTO `language` (`libelle`) VALUES ('Sami languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Lule Sami'); 
INSERT INTO `language` (`libelle`) VALUES ('Inari Sami'); 
INSERT INTO `language` (`libelle`) VALUES ('Samoan'); 
INSERT INTO `language` (`libelle`) VALUES ('Skolt Sami'); 
INSERT INTO `language` (`libelle`) VALUES ('Shona'); 
INSERT INTO `language` (`libelle`) VALUES ('Sindhi'); 
INSERT INTO `language` (`libelle`) VALUES ('Soninke'); 
INSERT INTO `language` (`libelle`) VALUES ('Sogdian'); 
INSERT INTO `language` (`libelle`) VALUES ('Somali'); 
INSERT INTO `language` (`libelle`) VALUES ('Songhai languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Sotho, Southern'); 
INSERT INTO `language` (`libelle`) VALUES ('Spanish'); 
INSERT INTO `language` (`libelle`) VALUES ('Albanian'); 
INSERT INTO `language` (`libelle`) VALUES ('Sardinian'); 
INSERT INTO `language` (`libelle`) VALUES ('Sranan Tongo'); 
INSERT INTO `language` (`libelle`) VALUES ('Serbian'); 
INSERT INTO `language` (`libelle`) VALUES ('Serer'); 
INSERT INTO `language` (`libelle`) VALUES ('Nilo-Saharan languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Swati'); 
INSERT INTO `language` (`libelle`) VALUES ('Sukuma'); 
INSERT INTO `language` (`libelle`) VALUES ('Sundanese'); 
INSERT INTO `language` (`libelle`) VALUES ('Susu'); 
INSERT INTO `language` (`libelle`) VALUES ('Sumerian'); 
INSERT INTO `language` (`libelle`) VALUES ('Swahili'); 
INSERT INTO `language` (`libelle`) VALUES ('Swedish'); 
INSERT INTO `language` (`libelle`) VALUES ('Classical Syriac'); 
INSERT INTO `language` (`libelle`) VALUES ('Syriac'); 
INSERT INTO `language` (`libelle`) VALUES ('Tahitian'); 
INSERT INTO `language` (`libelle`) VALUES ('Tai languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Tamil'); 
INSERT INTO `language` (`libelle`) VALUES ('Tatar'); 
INSERT INTO `language` (`libelle`) VALUES ('Telugu'); 
INSERT INTO `language` (`libelle`) VALUES ('Timne'); 
INSERT INTO `language` (`libelle`) VALUES ('Tereno'); 
INSERT INTO `language` (`libelle`) VALUES ('Tetum'); 
INSERT INTO `language` (`libelle`) VALUES ('Tajik'); 
INSERT INTO `language` (`libelle`) VALUES ('Tagalog'); 
INSERT INTO `language` (`libelle`) VALUES ('Thai'); 
INSERT INTO `language` (`libelle`) VALUES ('Tibetan'); 
INSERT INTO `language` (`libelle`) VALUES ('Tigre'); 
INSERT INTO `language` (`libelle`) VALUES ('Tigrinya'); 
INSERT INTO `language` (`libelle`) VALUES ('Tiv'); 
INSERT INTO `language` (`libelle`) VALUES ('Tokelau'); 
INSERT INTO `language` (`libelle`) VALUES ('Klingon'); 
INSERT INTO `language` (`libelle`) VALUES ('Tlingit'); 
INSERT INTO `language` (`libelle`) VALUES ('Tamashek'); 
INSERT INTO `language` (`libelle`) VALUES ('Tonga (Nyasa)'); 
INSERT INTO `language` (`libelle`) VALUES ('Tonga (Tonga Islands)'); 
INSERT INTO `language` (`libelle`) VALUES ('Tok Pisin'); 
INSERT INTO `language` (`libelle`) VALUES ('Tsimshian'); 
INSERT INTO `language` (`libelle`) VALUES ('Tswana'); 
INSERT INTO `language` (`libelle`) VALUES ('Tsonga'); 
INSERT INTO `language` (`libelle`) VALUES ('Turkmen'); 
INSERT INTO `language` (`libelle`) VALUES ('Tumbuka'); 
INSERT INTO `language` (`libelle`) VALUES ('Tupi languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Turkish'); 
INSERT INTO `language` (`libelle`) VALUES ('Altaic languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Tuvalu'); 
INSERT INTO `language` (`libelle`) VALUES ('Twi'); 
INSERT INTO `language` (`libelle`) VALUES ('Tuvinian'); 
INSERT INTO `language` (`libelle`) VALUES ('Udmurt'); 
INSERT INTO `language` (`libelle`) VALUES ('Ugaritic'); 
INSERT INTO `language` (`libelle`) VALUES ('Uyghur'); 
INSERT INTO `language` (`libelle`) VALUES ('Ukrainian'); 
INSERT INTO `language` (`libelle`) VALUES ('Umbundu'); 
INSERT INTO `language` (`libelle`) VALUES ('Urdu'); 
INSERT INTO `language` (`libelle`) VALUES ('Uzbek'); 
INSERT INTO `language` (`libelle`) VALUES ('Vai'); 
INSERT INTO `language` (`libelle`) VALUES ('Venda'); 
INSERT INTO `language` (`libelle`) VALUES ('Vietnamese'); 
INSERT INTO `language` (`libelle`) VALUES ('Volap&#xfc;k'); 
INSERT INTO `language` (`libelle`) VALUES ('Votic'); 
INSERT INTO `language` (`libelle`) VALUES ('Wakashan languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Wolaytta'); 
INSERT INTO `language` (`libelle`) VALUES ('Waray'); 
INSERT INTO `language` (`libelle`) VALUES ('Washo'); 
INSERT INTO `language` (`libelle`) VALUES ('Welsh'); 
INSERT INTO `language` (`libelle`) VALUES ('Sorbian languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Walloon'); 
INSERT INTO `language` (`libelle`) VALUES ('Wolof'); 
INSERT INTO `language` (`libelle`) VALUES ('Kalmyk'); 
INSERT INTO `language` (`libelle`) VALUES ('Xhosa'); 
INSERT INTO `language` (`libelle`) VALUES ('Yao'); 
INSERT INTO `language` (`libelle`) VALUES ('Yapese'); 
INSERT INTO `language` (`libelle`) VALUES ('Yiddish'); 
INSERT INTO `language` (`libelle`) VALUES ('Yoruba'); 
INSERT INTO `language` (`libelle`) VALUES ('Yupik languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Zapotec'); 
INSERT INTO `language` (`libelle`) VALUES ('Blissymbols'); 
INSERT INTO `language` (`libelle`) VALUES ('Zenaga'); 
INSERT INTO `language` (`libelle`) VALUES ('Zhuang'); 
INSERT INTO `language` (`libelle`) VALUES ('Chinese'); 
INSERT INTO `language` (`libelle`) VALUES ('Zande languages'); 
INSERT INTO `language` (`libelle`) VALUES ('Zulu'); 
INSERT INTO `language` (`libelle`) VALUES ('Zuni'); 
INSERT INTO `language` (`libelle`) VALUES ('Zazaki'); 

INSERT INTO `scale` (`name`, `value`) VALUES ('Strongly disagree', '1');
INSERT INTO `scale` (`name`, `value`) VALUES ('Disagree', '2');
INSERT INTO `scale` (`name`, `value`) VALUES ('Neither agree, nor disagree', '3');
INSERT INTO `scale` (`name`, `value`) VALUES ('Agree', '4');
INSERT INTO `scale` (`name`, `value`) VALUES ('Strongly agree', '5');
INSERT INTO `scale` (`name`, `value`) VALUES ('I don\'t Know', '0');

INSERT INTO `permission` (`libelle`) VALUES ('materialdocument.nbrView');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('itemprog.nbStart');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('eventcomment.add');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'eventcomment.add'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('eventcomment.update');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'eventcomment.update'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('eventcomment.delete');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'eventcomment.delete'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('component.getListEqCq');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'component.getListEqCq'));

INSERT INTO `permission` (`libelle`) VALUES ('connection.getAvg');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

INSERT INTO `permission` (`libelle`) VALUES ('eventuser.view');
SELECT LAST_INSERT_ID() INTO @rbac_permission_id;
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, @rbac_permission_id);

DELETE rp FROM role_permission rp, role_permission rp2 
WHERE rp.id > rp2.id AND rp.role_id = rp2.role_id AND rp.permission_id = rp2.permission_id;

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('message.getListTag');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'message.getListTag'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('conversation.join');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'conversation.join'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('videoconf.getByItemProg');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'videoconf.getByItemProg'));

UPDATE question SET text = 'When working with others in a multicultural group, $subject like(s) to know about the historical, legal, political and economic conditions of their different countries.' WHERE id = 1;
UPDATE question SET text = 'In group meetings $subject assume(s) that cultural values will differ amongst participants.' WHERE id = 2;
UPDATE question SET deleted_date = CURRENT_TIMESTAMP WHERE id = 3;
UPDATE question SET text = '$subject believe(s) that gender roles may vary amongst people from different countries and/or cultures.' WHERE id = 4;
UPDATE question SET text = '$subject realize(s) that because of different cultural backgrounds in our team, our perspectives may not be the same as those of team members from other regions.' WHERE id = 5;
UPDATE question SET text = '$subject believe(s)  that different cultural norms and values can affect business decisions in group work.' WHERE id = 6;
UPDATE question SET text = '$subject adapt(s) my/his/her communication style to the cultural sensitivities in the group.' WHERE id = 7;
UPDATE question SET text = 'When interacting with team members from different countries, $subject ask(s) questions to avoid misunderstanding.' WHERE id = 8;
UPDATE question SET text = 'In group meetings with people from different countries, $subject never lose(s) patience and stop listening.' WHERE id = 9;
UPDATE question SET text = '$subject am/is a good listener and pay(s) attention to what is being said by others.' WHERE id = 10;
UPDATE question SET text = '$subject am/is able to easily persuade other team members and win them over to my/his/her perspective.' WHERE id = 11;
UPDATE question SET text = 'Whenever there is conflict in the group, $subject seek(s) feedback from others regarding his/her/my ability to try to manage it.' WHERE id = 12;
UPDATE question SET text = 'When other team members become stressed or confrontational, $subject make(s) an effort to keep the conversation calm.' WHERE id = 13;
UPDATE question SET text = '$subject understand(s) how his/her/my behavior can affect others’ work and emotions in group work.' WHERE id = 14;
UPDATE question SET text = 'Even during emotionally charged arguments, $subject remain(s) patient.' WHERE id = 15;
UPDATE question SET text = 'In stressful group work situations, $subject never respond(s) too quickly or sharply.' WHERE id = 16;
UPDATE question SET text = '$subject am/is aware of my/his/her verbal outbursts during the team discussions.' WHERE id = 17;
UPDATE question SET text = '$subject adapt(s) my/his/her communication style to how others in the group are feeling.' WHERE id = 18;
UPDATE question SET text = '$subject always try/tries to find the best way to communicate with other team members, even when there is resistance, confusion or conflict.' WHERE id = 19;
UPDATE question SET text = '$subject like(s) to express opinions and share(s) ideas with others in the team.' WHERE id = 20;
UPDATE question SET text = '$subject enjoy(s) being on stage and presenting the recommendations of our group work to others.' WHERE id = 21;
UPDATE question SET text = 'During group discussions, $subject frequently use(s) stories and metaphors to get my/his/her ideas and recommendations across more persuasively.' WHERE id = 22;
UPDATE question SET text = '$subject incorporate(s) other team members’ viewpoints into our group presentations.' WHERE id = 23;
UPDATE question SET text = '$subject never become(s) impatient when listening to team members.' WHERE id = 24;
UPDATE question SET text = 'During tense group discussions, $subject never project(s) my/his/her stress and sense of urgency onto other people.' WHERE id = 25;
UPDATE question SET text = '$subject am/is an easy going and sociable person, and want(s) the team to work well together.' WHERE id = 26;
UPDATE question SET text = 'Whenever $subject have/has a problem with the group assignment, $subject will contact the other team members to find solutions.' WHERE id = 27;
UPDATE question SET text = 'Even when $subject am/is angry or frustrated with the pace or quality of his/her/my group’s work, I/he/she remain(s) cool, calm and collected.' WHERE id = 28;
UPDATE question SET text = '$subject know(s) that I/he/she don’t/doesn’t work well under tight deadlines, so I/he/she get my/their part of the group work done well in advance.' WHERE id = 29;
UPDATE question SET text = 'When $subject believe(s) that team members didn’t do a good job, I/he/she try/tries to understand the reasons for the failure and whether my/his/her actions or decisions had something to do with it.' WHERE id = 30;
UPDATE question SET text = 'During group work discussions, $subject respectfully listen(s) to team members and then offer my/his/her opinions.' WHERE id = 31;
UPDATE question SET text = '$subject am/is interested in understanding team members’ points of view, and then offering some suggestions based on what they’re saying.' WHERE id = 32;
UPDATE question SET text = 'Even if $subject thinks I/he/she have/has a better plan, I/he/she let others feel good about their ideas too' WHERE id = 33;
UPDATE question SET text = 'When our group project is criticized by the instructor, $subject don’t/doesn’t blame others and instead try/tries to understand what we did wrong and how we can improve for the next round.' WHERE id = 34;
UPDATE question SET text = '$subject am/is able to manage my/his/her emotions in confrontational group work situations.' WHERE id = 35;
UPDATE question SET text = '$subject encourage(s) the participation of everyone on the team.' WHERE id = 36;
UPDATE question SET text = '$subject am/is able to build group pride, foster a positive emotional tone, and bring out the best in our team members.' WHERE id = 37;
UPDATE question SET text = '$subject always notice(s) when team members appear annoyed, frustrated or overwhelmed by my/his/her personality' WHERE id = 38;
UPDATE question SET text = 'Whenever team members don’t do what they were expected to do, $subject deal(s) with it professionally and politely and explain(s) what had to be done differently.' WHERE id = 39;
UPDATE question SET text = '$subject can lead group discussions without team members feeling that they are being controlled.' WHERE id = 40;
UPDATE question SET text = '$subject always congratulate(s) team members for a job well-done.' WHERE id = 41;
UPDATE question SET text = 'Even if $subject don’t/doesn’t like a team member, I/he/she will still work proactively with that person to get the job done.' WHERE id = 42;
UPDATE question SET text = '$subject take(s) other team members’ opinions into consideration when making decisions.' WHERE id = 43;
UPDATE question SET text = '$subject never blame(s) the team members when things go wrong.' WHERE id = 44;
UPDATE question SET text = '$subject use(s) “we” rather than “I” when describing our team’s accomplishments.' WHERE id = 45;
UPDATE question SET text = 'When discussing the problem to be solved within the group, $subject am/is aware of how others perceive me/him/her.' WHERE id = 46;
UPDATE question SET text = 'In case work discussions on which managerial option to follow, $subject am/is able to see the big picture and am/is not too narrowly focused.' WHERE id = 47;
UPDATE question SET text = 'During debate on which group decision to take, $subject am/is able to consider issues from other team members’ perspectives.' WHERE id = 48;
UPDATE question SET text = 'In case study discussions, $subject am/is easily able to identify the major issues confronting the organization and/or protagonists in the case.' WHERE id = 49;
UPDATE question SET text = 'When other team members suggest an alternative solution or recommendation to mine/his/her, $subject listen(s) to them and weigh the pros and cons of the different options with all the team members.' WHERE id = 50;
UPDATE question SET text = '$subject am/is able to read the emotions of the team members.' WHERE id = 51;
UPDATE question SET text = '$subject easily build(s) relationships with almost everyone on the team.' WHERE id = 52;
UPDATE question SET text = 'In group-work, $subject care about how others may feel when someone has been rude or condescending to them.' WHERE id = 53;
UPDATE question SET text = '$subject understand(s) what motivates our team members, even those from different backgrounds.' WHERE id = 54;
UPDATE question SET text = '$subject am/is able to read other team members’ emotions and use that insight to create a positive and participative forum for group work discussions.' WHERE id = 55;
UPDATE question SET text = '$subject always approach(es) a new group task, even if it means added work for me/him/her, enthusiastically.' WHERE id = 56;
UPDATE question SET text = '$subject really enjoy(s) it when the problem or issue that has to be solved as a team is a challenging and unexpected one.' WHERE id = 57;
UPDATE question SET text = 'Even when the instructor seemed harsh in the criticism of our group work, $subject see(s) it as a way to improve for the next round.' WHERE id = 58;

INSERT INTO `poll_question_type` VALUES (1,'text'),(2,'multiple_choice'),(3,'checkbox'),(4,'date'),(5,'time');
UPDATE question SET text = 'In group-work, $subject care(s) about how others may feel when someone has been rude or condescending to them.' WHERE id = 53;

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

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('gradingpolicy.delete');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'gradingpolicy.delete'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('gradingpolicy.getListByCourse');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (5, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'gradingpolicy.getListByCourse'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('itemassignment.isGroupWorkSubmitted');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'itemassignment.isGroupWorkSubmitted'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('document.delete');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'document.delete'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('document.update');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'document.update'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('document.add');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'document.update'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('grading.getByProgram');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'grading.getByProgram'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('grading.updateProgram');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'grading.updateProgram'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('activities.getListWithUser');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'activities.getListWithUser'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('user.getListContact');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'user.getListContact'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('activity.getListWithUser');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'activity.getListWithUser'));
