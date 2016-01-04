CREATE TABLE IF NOT EXISTS `connection` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `token` VARCHAR(80) NOT NULL,
  `end` DATETIME NOT NULL,
  `start` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_connection_2_idx` (`user_id` ASC),
  CONSTRAINT `fk_connection_2`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
