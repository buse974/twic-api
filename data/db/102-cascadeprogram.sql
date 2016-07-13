ALTER TABLE `apilms`.`thread` 
DROP FOREIGN KEY `fk_thread_1`,
DROP FOREIGN KEY `fk_thread_3`;
ALTER TABLE `apilms`.`thread` 
ADD CONSTRAINT `fk_thread_1`
  FOREIGN KEY (`course_id`)
  REFERENCES `apilms`.`course` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_thread_3`
  FOREIGN KEY (`item_id`)
  REFERENCES `apilms`.`item` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

