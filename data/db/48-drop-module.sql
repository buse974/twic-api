ALTER TABLE `item` 
DROP FOREIGN KEY `fk_item_3`;
ALTER TABLE `item` 
DROP COLUMN `module_id`,
ADD COLUMN `parent_id` INT UNSIGNED NULL AFTER `grading_policy_id`,
DROP INDEX `fk_item_3_idx` ;


DROP TABLE `module`;
