-- Fragment begins: 298 --
INSERT INTO changelog
                                (change_number, delta_set, start_dt, applied_by, description) VALUES (298, 'Main', NOW(), 'dbdeploy', '298 - Item conversation id.sql');
ALTER TABLE `item` 
ADD COLUMN `conversation_id` INT UNSIGNED NULL AFTER `is_grade_published`,
ADD INDEX `fk_item_5_idx` (`conversation_id` ASC);
ALTER TABLE `item` 
ADD CONSTRAINT `fk_item_5`
  FOREIGN KEY (`conversation_id`)
  REFERENCES `apilms`.`conversation` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

UPDATE changelog
	                         SET complete_dt = NOW()
	                         WHERE change_number = 298
	                         AND delta_set = 'Main';
-- Fragment ends: 298 --
