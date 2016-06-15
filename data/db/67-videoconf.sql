DROP TABLE `videoconf_invitation`;
DROP TABLE `videoconf_entity`;
ALTER TABLE `videoconf_admin` RENAME TO  `videoconf_user` ;

ALTER TABLE `videoconf` 
DROP FOREIGN KEY `fk_videoconf_3`;
ALTER TABLE `videoconf` 
DROP COLUMN `videoconf_opt`,
DROP COLUMN `archive_status`,
DROP COLUMN `archive_link`,
DROP COLUMN `archive_token`,
DROP INDEX `fk_videoconf_3_idx` ;

CREATE TABLE IF NOT EXISTS `videoconf_submission` (
  `videoconf_id` INT(10) UNSIGNED NOT NULL DEFAULT NULL,
  `submission_id` INT(11) UNSIGNED NOT NULL DEFAULT NULL,
  PRIMARY KEY (`videoconf_id`, `submission_id`),
  CONSTRAINT `fk_videoconf_submission_0`
    FOREIGN KEY (`videoconf_id` , `submission_id`)
    REFERENCES `videoconf` (`id` , `id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_videoconf_submission_1`
    FOREIGN KEY ()
    REFERENCES `submission` ()
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8