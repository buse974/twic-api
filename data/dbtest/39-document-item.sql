CREATE TABLE IF NOT EXISTS `document` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NULL,
  `link` VARCHAR(45) NULL,
  `token` VARCHAR(80) NULL,
  `item_id` INT UNSIGNED NOT NULL,
  `created_date` DATETIME NULL,
  `deleted_date` DATETIME NULL,
  `updated_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_document_1_idx` (`item_id` ASC),
  CONSTRAINT `fk_document_1`
    FOREIGN KEY (`item_id`)
    REFERENCES `item` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

ALTER TABLE `item` CHANGE COLUMN `type` `type` ENUM('LC', 'WG', 'CP', 'IA', 'DOC', 'TXT', 'POLL') NOT NULL ;

