ALTER TABLE `preregistration` 
DROP FOREIGN KEY `fk_preregistration_1`;
ALTER TABLE `preregistration` 
CHANGE COLUMN `email` `email` VARCHAR(128) NULL ,
CHANGE COLUMN `firstname` `firstname` VARCHAR(128) NULL ,
CHANGE COLUMN `lastname` `lastname` VARCHAR(128) NULL ,
DROP INDEX `fk_preregistration_1_idx` ;
ALTER TABLE `preregistration` 
ADD CONSTRAINT `fk_preregistration_1`
  FOREIGN KEY ()
  REFERENCES `page` ()
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `preregistration` 
ADD INDEX `fk_preregistration_1_idx` (`organization_id` ASC);
ALTER TABLE `preregistration` 
ADD CONSTRAINT `fk_preregistration_1`
  FOREIGN KEY (`organization_id`)
  REFERENCES `page` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

