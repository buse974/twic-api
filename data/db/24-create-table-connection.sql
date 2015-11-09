CREATE TABLE IF NOT EXISTS `connection` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `token` VARCHAR(80) NOT NULL,
  `diff` INT NOT NULL,
  `start` DATETIME NOT NULL,
  `total` INT NOT NULL,
  `parent_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_connection_1_idx` (`parent_id` ASC),
  INDEX `fk_connection_2_idx` (`user_id` ASC),
  CONSTRAINT `fk_connection_1`
    FOREIGN KEY (`parent_id`)
    REFERENCES `connection` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_connection_2`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
