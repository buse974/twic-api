ALTER TABLE `item` 
ADD COLUMN `is_grouped` TINYINT(1) 0 AFTER `is_graded`,
ADD COLUMN `has_all_student` TINYINT(1) 1 AFTER `is_grouped`;
