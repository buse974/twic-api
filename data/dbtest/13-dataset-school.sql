INSERT INTO `school` (`name`, `next_name`, `short_name`, `describe`, `website`, `programme`, `phone`, `contact`, `contact_id`) 
VALUES 
('Morbi Corporation', 'Dolor Dolor Foundation', 'turpis', 'vel, mauris. Integer sem elit, pharetra ut, pharetra sed, hendrerit a, arcu. Sed et libero. Proin mi. Aliquam gravida mauris ut mi. Duis risus odio, auctor vitae, aliquet nec, imperdiet nec, leo. Morbi neque tellus, imperdiet non,', 'http://', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Curabitur', '04 17 21 41 32', 'Dane Z. Shepard', '1');
UPDATE `user` SET `school_id` = 1;
UPDATE `grading` SET `school_id` = 1;

INSERT INTO `contact` (`accepted_date`, `contact_id`, `user_id`)
VALUES 
('2015-08-11 13:17:25', 1,2),
('2015-08-11 13:17:25', 2,1);


-- INSERT INTO `contact` (`accepted_date`, `contact_id`, `user_id`) SELECT UTC_TIMESTAMP() AS `accepted_date`, `user`.`id` AS `contact_id`, `uu`.`id` AS `user_id` FROM `user` INNER JOIN `user` AS `uu` ON `uu`.`school_id` = `user`.`school_id` AND `uu`.`id` <> `user`.`id` LEFT JOIN `contact` ON `contact`.`user_id` = `uu`.`id` AND `contact`.`contact_id` = `user`.`id` WHERE contact.id IS NULL AND `user`.`school_id` = 1;