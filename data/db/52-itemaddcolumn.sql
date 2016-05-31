ALTER TABLE `item` 
ADD COLUMN `is_grouped` TINYINT(1) false AFTER `is_graded`,
ADD COLUMN `has_all_student` TINYINT(1) true AFTER `is_grouped`;
