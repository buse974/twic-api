ALTER TABLE `post` 
DROP COLUMN `video`,
DROP COLUMN `key`,
CHANGE COLUMN `parent_id` `parent_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
CHANGE COLUMN `t_organization_id` `t_organization_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
CHANGE COLUMN `t_user_id` `t_user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
CHANGE COLUMN `t_course_id` `t_course_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
ADD COLUMN `origin_id` INT(10) UNSIGNED NULL AFTER `updated_date`,
ADD COLUMN `lat` DOUBLE NULL AFTER `t_course_id`,
ADD COLUMN `lng` DOUBLE NULL AFTER `lat`;

CREATE TABLE IF NOT EXISTS `inv`.`subscription` (
  `libelle` VARCHAR(80) NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`libelle`, `user_id`),
  INDEX `fk_subscription_user_1_idx` (`user_id` ASC),
  INDEX `idx_subscription_user_1` (`libelle` ASC),
  CONSTRAINT `fk_subscription_user_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `inv`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `post_subscription` (
  `libelle` VARCHAR(80) NOT NULL,
  `post_id` INT UNSIGNED NOT NULL,
  `last_date` DATETIME NULL,
  PRIMARY KEY (`libelle`, `post_id`),
  INDEX `fk_post_subscription_1_idx` (`post_id` ASC),
  INDEX `idx_post_subscription` (`libelle` ASC),
  CONSTRAINT `fk_post_subscription_1`
    FOREIGN KEY (`post_id`)
    REFERENCES `post` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

ALTER TABLE `post` 
ADD COLUMN `organization_id` INT UNSIGNED NULL AFTER `user_id`,
ADD COLUMN `page_id` INT NULL AFTER `organization_id`;


ALTER TABLE `post` 
DROP FOREIGN KEY `fk_feed_10`;
ALTER TABLE `post` 
CHANGE COLUMN `page_id` `page_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
ADD INDEX `fk_post_2_idx` (`organization_id` ASC),
ADD INDEX `fk_post_3_idx` (`page_id` ASC);
ALTER TABLE `post` 
ADD CONSTRAINT `fk_post_1`
  FOREIGN KEY (`user_id`)
  REFERENCES `user` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_post_2`
  FOREIGN KEY (`organization_id`)
  REFERENCES `school` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_post_3`
  FOREIGN KEY (`page_id`)
  REFERENCES `page` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `post` 
ADD CONSTRAINT `fk_post_4`
  FOREIGN KEY (`origin_id`)
  REFERENCES `post` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_post_5`
  FOREIGN KEY (`parent_id`)
  REFERENCES `post` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `post` 
ADD INDEX `fk_post_8_idx` (`t_user_id` ASC),
ADD INDEX `fk_post_9_idx` (`t_course_id` ASC);
ALTER TABLE `post` 
ADD CONSTRAINT `fk_post_6`
  FOREIGN KEY (`t_page_id`)
  REFERENCES `page` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_post_7`
  FOREIGN KEY (`t_organization_id`)
  REFERENCES `school` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_post_8`
  FOREIGN KEY (`t_user_id`)
  REFERENCES `user` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_post_9`
  FOREIGN KEY (`t_course_id`)
  REFERENCES `course` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

