ALTER TABLE `thread` 
DROP FOREIGN KEY `fk_thread_1`,
DROP FOREIGN KEY `fk_thread_3`;
ALTER TABLE `thread` 
ADD CONSTRAINT `fk_thread_1`
  FOREIGN KEY (`course_id`)
  REFERENCES `course` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_thread_3`
  FOREIGN KEY (`item_id`)
  REFERENCES `item` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

