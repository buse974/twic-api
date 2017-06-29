ALTER TABLE `submission` 
DROP COLUMN `group_name`,
DROP COLUMN `group_id`;

CREATE TABLE IF NOT EXISTS `item_user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NULL,
  `item_id` INT UNSIGNED NULL,
  `rate` VARCHAR(45) NULL,
  `group_id` VARCHAR(45) NULL,
  `submission_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_item_user_1_idx` (`user_id` ASC),
  INDEX `fk_item_user_2_idx` (`item_id` ASC),
  INDEX `fk_item_user_3_idx` (`submission_id` ASC),
  CONSTRAINT `fk_item_user_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_user_2`
    FOREIGN KEY (`item_id`)
    REFERENCES `item` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_user_3`
    FOREIGN KEY (`submission_id`)
    REFERENCES `submission` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

ALTER TABLE `item`
ADD COLUMN `participants` ENUM('user', 'group', 'all') NOT NULL DEFAULT 'all' AFTER `points`;

