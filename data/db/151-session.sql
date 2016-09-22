CREATE TABLE IF NOT EXISTS `session` (
  `token` VARCHAR(255) NOT NULL,
  `data` TEXT NULL,
  `uid` INT NULL,
  PRIMARY KEY (`token`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
