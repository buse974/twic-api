INSERT INTO `circle` (`id`, `name`) VALUES ('1', 'twic');
INSERT INTO `circle` (`id`, `name`) VALUES ('2', 'gnam');
INSERT INTO `circle_organization` (`circle_id`,`organization_id`)  SELECT 2 as c, id FROM school;

INSERT INTO `school` (`id`, `name`, `next_name`) VALUES (2, 'A NE PAS VOIR', 'A NE PAS VOIR');

INSERT INTO `user` (`id`,`firstname`, `lastname`, `school_id`) VALUES (8, 'NONSTUDENT',   'NONSTUDENT', '2');
INSERT INTO `user` (`id`,`firstname`, `lastname`, `school_id`) VALUES (9, 'NONACADEMIC',  'NONACADEMIC', '2');
INSERT INTO `user` (`id`,`firstname`, `lastname`, `school_id`) VALUES (10,'NONINTRUCTOR', 'NONINTRUCTOR', '2');

INSERT INTO `organization_user` (`organization_id`, `user_id`) VALUES ('2', '8');
INSERT INTO `organization_user` (`organization_id`, `user_id`) VALUES ('2', '9');
INSERT INTO `organization_user` (`organization_id`, `user_id`) VALUES ('2', '10');

INSERT INTO `program` (`id`, `name`, `school_id`) VALUES ('1', 'PROGRAM A NE PAS VOIR', 2);