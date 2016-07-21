ALTER TABLE `conversation_doc` 
DROP FOREIGN KEY `fk_conversation_doc_1`,
DROP FOREIGN KEY `fk_conversation_doc_2`;
ALTER TABLE `conversation_doc` 
ADD CONSTRAINT `fk_conversation_doc_1`
  FOREIGN KEY (`conversation_id`)
  REFERENCES `conversation` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_conversation_doc_2`
  FOREIGN KEY (`library_id`)
  REFERENCES `library` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

