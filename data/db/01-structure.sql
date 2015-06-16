-- MySQL Script generated by MySQL Workbench
-- mar. 16 juin 2015 11:35:31 CEST
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema apiwow
-- -----------------------------------------------------
-- apiwow

-- -----------------------------------------------------
-- Table `videoconf`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `videoconf` ;

CREATE TABLE IF NOT EXISTS `videoconf` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` TEXT NULL,
  `start_date` DATETIME NULL,
  `duration` INT NULL,
  `archive_token` TEXT NULL,
  `archive_link` TEXT NULL,
  `archive_status` VARCHAR(128) NULL,
  `status` VARCHAR(45) NULL,
  `title` VARCHAR(256) NULL,
  `description` TEXT NULL,
  `created_date` DATETIME NULL,
  `deleted_date` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `videoconf_entity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `videoconf_entity` ;

CREATE TABLE IF NOT EXISTS `videoconf_entity` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `videoconf_id` INT UNSIGNED NULL,
  `name` VARCHAR(128) NULL,
  `avatar` VARCHAR(128) NULL,
  `token` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_videoconf_entity_videoconf_idx` (`videoconf_id` ASC),
  CONSTRAINT `fk_videoconf_entity_videoconf`
    FOREIGN KEY (`videoconf_id`)
    REFERENCES `videoconf` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `videoconf_invitation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `videoconf_invitation` ;

CREATE TABLE IF NOT EXISTS `videoconf_invitation` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `videoconf_entity_id` INT UNSIGNED NULL,
  `firstname` VARCHAR(128) NULL,
  `lastname` VARCHAR(128) NULL,
  `avatar` VARCHAR(128) NULL,
  `email` VARCHAR(128) NULL,
  `utc` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_videoconf_invitation_videoconf_entity_idx` (`videoconf_entity_id` ASC),
  CONSTRAINT `fk_videoconf_invitation_videoconf_entity`
    FOREIGN KEY (`videoconf_entity_id`)
    REFERENCES `videoconf_entity` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `school`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `school` ;

CREATE TABLE IF NOT EXISTS `school` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `next_name` VARCHAR(45) NULL,
  `short_name` TEXT NULL,
  `logo` VARCHAR(80) NULL,
  `describe` TEXT NULL,
  `website` TEXT NULL,
  `programme` TEXT NULL,
  `backroung` VARCHAR(80) NULL,
  `phone` VARCHAR(45) NULL,
  `contact` VARCHAR(45) NULL,
  `contact_id` INT UNSIGNED NULL,
  `address_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_school_1_idx` (`contact_id` ASC),
  CONSTRAINT `fk_school_1`
    FOREIGN KEY (`contact_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(128) NULL,
  `lastname` VARCHAR(128) NULL,
  `status` TEXT NULL,
  `email` VARCHAR(128) NULL,
  `password` VARCHAR(128) NULL,
  `birth_date` DATETIME NULL,
  `position` VARCHAR(255) NULL,
  `school_id` INT UNSIGNED NULL,
  `interest` TEXT NULL,
  `avatar` VARCHAR(255) NULL,
  `deleted_date` DATETIME NULL,
  `sis` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_1_idx` (`school_id` ASC),
  CONSTRAINT `fk_user_1`
    FOREIGN KEY (`school_id`)
    REFERENCES `school` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `videoconf_admin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `videoconf_admin` ;

CREATE TABLE IF NOT EXISTS `videoconf_admin` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `videoconf_id` INT UNSIGNED NULL,
  `user_id` INT NULL,
  `token` TEXT NULL,
  `created_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_videoconf_admin_vodeoconf_idx` (`videoconf_id` ASC),
  CONSTRAINT `fk_videoconf_admin_vodeoconf`
    FOREIGN KEY (`videoconf_id`)
    REFERENCES `videoconf` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `program`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `program` ;

CREATE TABLE IF NOT EXISTS `program` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `school_id` INT UNSIGNED NOT NULL,
  `level` ENUM('emba', 'mba') NULL,
  `sis` VARCHAR(45) NULL,
  `deleted_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_program_1_idx` (`school_id` ASC),
  CONSTRAINT `fk_program_1`
    FOREIGN KEY (`school_id`)
    REFERENCES `school` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `course`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `course` ;

CREATE TABLE IF NOT EXISTS `course` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NULL DEFAULT NULL,
  `creator_id` INT UNSIGNED NOT NULL,
  `nb_module` INT NULL DEFAULT NULL,
  `abstract` TEXT NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `objectives` TEXT NULL DEFAULT NULL,
  `teaching` TEXT NULL DEFAULT NULL,
  `attendance` TEXT NULL DEFAULT NULL,
  `duration` INT NULL,
  `notes` TEXT NULL,
  `learning_outcomes` TEXT NULL,
  `deleted_date` DATETIME NULL,
  `updated_date` DATETIME NULL,
  `created_date` DATETIME NULL,
  `version` INT NULL,
  `video_link` TEXT NULL,
  `video_token` TEXT NULL,
  `program_id` INT UNSIGNED NOT NULL,
  `sis` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_course_1_idx` (`creator_id` ASC),
  INDEX `fk_course_2_idx` (`program_id` ASC),
  CONSTRAINT `fk_course_1`
    FOREIGN KEY (`creator_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_course_2`
    FOREIGN KEY (`program_id`)
    REFERENCES `program` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `education`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `education` ;

CREATE TABLE IF NOT EXISTS `education` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` DATETIME NULL DEFAULT NULL,
  `address` VARCHAR(255) NULL DEFAULT NULL,
  `logo` VARCHAR(255) NULL DEFAULT NULL,
  `title` VARCHAR(255) NULL DEFAULT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_education_1_idx` (`user_id` ASC),
  CONSTRAINT `fk_education_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `professional_experience`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `professional_experience` ;

CREATE TABLE IF NOT EXISTS `professional_experience` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` DATETIME NULL,
  `address` VARCHAR(255) NULL,
  `logo` VARCHAR(255) NULL,
  `title` VARCHAR(255) NULL,
  `description` TEXT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_professional_experience_1_idx` (`user_id` ASC),
  CONSTRAINT `fk_professional_experience_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `role` ;

CREATE TABLE IF NOT EXISTS `role` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 0
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
PACK_KEYS = DEFAULT;


-- -----------------------------------------------------
-- Table `user_role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_role` ;

CREATE TABLE IF NOT EXISTS `user_role` (
  `user_id` INT UNSIGNED NOT NULL,
  `role_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `role_id`),
  INDEX `fk_user_role_2_idx` (`role_id` ASC),
  CONSTRAINT `fk_user_role_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_role_2`
    FOREIGN KEY (`role_id`)
    REFERENCES `role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `material_document`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `material_document` ;

CREATE TABLE IF NOT EXISTS `material_document` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT UNSIGNED NOT NULL,
  `type` VARCHAR(100) NULL,
  `title` VARCHAR(45) NULL,
  `author` VARCHAR(45) NULL,
  `link` VARCHAR(45) NULL,
  `source` TEXT NULL,
  `token` VARCHAR(80) NULL,
  `date` DATE NULL,
  `created_date` DATETIME NULL,
  `deleted_date` DATETIME NULL,
  `updated_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_mterials_documents_1_idx` (`course_id` ASC),
  CONSTRAINT `fk_mterials_documents_1`
    FOREIGN KEY (`course_id`)
    REFERENCES `course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `module`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `module` ;

CREATE TABLE IF NOT EXISTS `module` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(250) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_workshop_1_idx` (`course_id` ASC),
  CONSTRAINT `fk_workshop_10`
    FOREIGN KEY (`course_id`)
    REFERENCES `course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `grading_policy`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grading_policy` ;

CREATE TABLE IF NOT EXISTS `grading_policy` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` TEXT NULL,
  `grade` DOUBLE NULL,
  `type` ENUM('LC', 'WG', 'CP', 'IA') NULL,
  `tpl` TINYINT(1) NOT NULL DEFAULT 0,
  `course_id` INT UNSIGNED NULL,
  `mandatory` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_grading_policy_1_idx` (`course_id` ASC),
  CONSTRAINT `fk_grading_policy_1`
    FOREIGN KEY (`course_id`)
    REFERENCES `course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `item` ;

CREATE TABLE IF NOT EXISTS `item` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(512) NULL,
  `describe` TEXT NULL,
  `duration` VARCHAR(512) NULL,
  `type` ENUM('LC', 'WG', 'CP', 'IA') NOT NULL,
  `weight` INT NOT NULL DEFAULT 1,
  `course_id` INT UNSIGNED NOT NULL,
  `parent_id` INT UNSIGNED NULL,
  `grading_policy_id` INT UNSIGNED NULL,
  `module_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_item_1_idx` (`course_id` ASC),
  INDEX `fk_item_3_idx` (`module_id` ASC),
  INDEX `fk_item_2_idx` (`parent_id` ASC),
  INDEX `fk_item_4_idx` (`grading_policy_id` ASC),
  CONSTRAINT `fk_item_1`
    FOREIGN KEY (`course_id`)
    REFERENCES `course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_2`
    FOREIGN KEY (`parent_id`)
    REFERENCES `item` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_3`
    FOREIGN KEY (`module_id`)
    REFERENCES `module` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_4`
    FOREIGN KEY (`grading_policy_id`)
    REFERENCES `grading_policy` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `item_material_document_relation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `item_material_document_relation` ;

CREATE TABLE IF NOT EXISTS `item_material_document_relation` (
  `item_id` INT UNSIGNED NOT NULL,
  `material_document_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`item_id`, `material_document_id`),
  INDEX `fk_item_material_document_relation_2_idx` (`material_document_id` ASC),
  CONSTRAINT `fk_item_material_document_relation_1`
    FOREIGN KEY (`item_id`)
    REFERENCES `item` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_material_document_relation_2`
    FOREIGN KEY (`material_document_id`)
    REFERENCES `material_document` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `item_prog`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `item_prog` ;

CREATE TABLE IF NOT EXISTS `item_prog` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` INT UNSIGNED NOT NULL,
  `start_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_item_prog_1_idx` (`item_id` ASC),
  CONSTRAINT `fk_item_prog_1`
    FOREIGN KEY (`item_id`)
    REFERENCES `item` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `item_assignment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `item_assignment` ;

CREATE TABLE IF NOT EXISTS `item_assignment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `response` TEXT NULL,
  `item_prog_id` INT UNSIGNED NULL,
  `submit_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_item_assignment_1_idx` (`item_prog_id` ASC),
  CONSTRAINT `fk_item_assignment_1`
    FOREIGN KEY (`item_prog_id`)
    REFERENCES `item_prog` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `language`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `language` ;

CREATE TABLE IF NOT EXISTS `language` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(128) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `language_level`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `language_level` ;

CREATE TABLE IF NOT EXISTS `language_level` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `level` VARCHAR(256) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user_language`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_language` ;

CREATE TABLE IF NOT EXISTS `user_language` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `language_id` INT UNSIGNED NOT NULL,
  `language_level_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_language_2_idx` (`language_id` ASC),
  INDEX `fk_user_language_3_idx` (`language_level_id` ASC),
  CONSTRAINT `fk_user_language_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_language_2`
    FOREIGN KEY (`language_id`)
    REFERENCES `language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_language_3`
    FOREIGN KEY (`language_level_id`)
    REFERENCES `language_level` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `grading`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grading` ;

CREATE TABLE IF NOT EXISTS `grading` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `letter` VARCHAR(3) NULL,
  `min` INT NULL,
  `max` INT NULL,
  `grade` DOUBLE NULL,
  `description` TEXT NULL,
  `tpl` TINYINT(1) NOT NULL DEFAULT 0,
  `course_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_grading_1_idx` (`course_id` ASC),
  CONSTRAINT `fk_grading_1`
    FOREIGN KEY (`course_id`)
    REFERENCES `course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `faq`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `faq` ;

CREATE TABLE IF NOT EXISTS `faq` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ask` TEXT NULL,
  `answer` TEXT NULL,
  `course_id` INT UNSIGNED NOT NULL,
  `created_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_faq_1_idx` (`course_id` ASC),
  CONSTRAINT `fk_faq_1`
    FOREIGN KEY (`course_id`)
    REFERENCES `course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `thread`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `thread` ;

CREATE TABLE IF NOT EXISTS `thread` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(512) NULL,
  `user_id` INT UNSIGNED NULL,
  `course_id` INT UNSIGNED NULL,
  `created_date` DATETIME NULL,
  `deleted_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_thread_1_idx` (`course_id` ASC),
  INDEX `fk_thread_2_idx` (`user_id` ASC),
  CONSTRAINT `fk_thread_1`
    FOREIGN KEY (`course_id`)
    REFERENCES `course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_thread_2`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `thread_message`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `thread_message` ;

CREATE TABLE IF NOT EXISTS `thread_message` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `message` TEXT NULL,
  `user_id` INT UNSIGNED NULL,
  `thread_id` INT UNSIGNED NULL,
  `created_date` DATETIME NULL,
  `deleted_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_thread_message_1_idx` (`user_id` ASC),
  INDEX `fk_thread_message_2_idx` (`thread_id` ASC),
  CONSTRAINT `fk_thread_message_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_thread_message_2`
    FOREIGN KEY (`thread_id`)
    REFERENCES `thread` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `message`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `message` ;

CREATE TABLE IF NOT EXISTS `message` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `message_group_id` VARCHAR(255) NULL,
  `suject` VARCHAR(512) NULL,
  `content` TEXT NULL,
  `created_date` DATETIME NOT NULL,
  `draft` TINYINT(1) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `message_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `message_user` ;

CREATE TABLE IF NOT EXISTS `message_user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `message_id` INT UNSIGNED NULL,
  `user_id` INT UNSIGNED NULL,
  `message_group_id` VARCHAR(255) NULL,
  `created_date` DATETIME NOT NULL,
  `deleted_date` DATETIME NULL,
  `read_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_message_user_1_idx` (`message_id` ASC),
  INDEX `fk_message_user_2_idx` (`user_id` ASC),
  CONSTRAINT `fk_message_user_1`
    FOREIGN KEY (`message_id`)
    REFERENCES `message` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_message_user_2`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `message_receiver`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `message_receiver` ;

CREATE TABLE IF NOT EXISTS `message_receiver` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` ENUM('cc','cci','to','from') NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `message_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_message_receiver_1_idx` (`user_id` ASC),
  INDEX `fk_message_receiver_2_idx` (`message_id` ASC),
  CONSTRAINT `fk_message_receiver_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_message_receiver_2`
    FOREIGN KEY (`message_id`)
    REFERENCES `message` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `message_doc`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `message_doc` ;

CREATE TABLE IF NOT EXISTS `message_doc` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(100) NOT NULL,
  `message_id` INT UNSIGNED NOT NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_message_doc_1_idx` (`message_id` ASC),
  CONSTRAINT `fk_message_doc_1`
    FOREIGN KEY (`message_id`)
    REFERENCES `message` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `course_user_relation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `course_user_relation` ;

CREATE TABLE IF NOT EXISTS `course_user_relation` (
  `user_id` INT UNSIGNED NOT NULL,
  `course_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `course_id`),
  INDEX `fk_course_user_relation_1_idx` (`course_id` ASC),
  CONSTRAINT `fk_course_user_relation_1`
    FOREIGN KEY (`course_id`)
    REFERENCES `course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_course_user_relation_2`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `item_prog_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `item_prog_user` ;

CREATE TABLE IF NOT EXISTS `item_prog_user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `item_prog_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`, `user_id`, `item_prog_id`),
  INDEX `fk_item_prog_user_relation_1_idx` (`item_prog_id` ASC),
  CONSTRAINT `fk_item_prog_user_relation_1`
    FOREIGN KEY (`item_prog_id`)
    REFERENCES `item_prog` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_prog_user_relation_2`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `item_grading`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `item_grading` ;

CREATE TABLE IF NOT EXISTS `item_grading` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_prog_user_id` INT UNSIGNED NULL,
  `grade` INT UNSIGNED NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_item_grading_2_idx` (`item_prog_user_id` ASC),
  CONSTRAINT `fk_item_grading_2`
    FOREIGN KEY (`item_prog_user_id`)
    REFERENCES `item_prog_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `intructor_course`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `intructor_course` ;

CREATE TABLE IF NOT EXISTS `intructor_course` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT UNSIGNED NULL,
  `intructor_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_intructor_course_1_idx` (`course_id` ASC),
  INDEX `fk_intructor_course_2_idx` (`intructor_id` ASC),
  CONSTRAINT `fk_intructor_course_1`
    FOREIGN KEY (`course_id`)
    REFERENCES `course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_intructor_course_2`
    FOREIGN KEY (`intructor_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `calendar`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `calendar` ;

CREATE TABLE IF NOT EXISTS `calendar` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NULL,
  `content` TEXT NULL,
  `start` DATETIME NULL,
  `end` DATETIME NULL,
  `creator_id` INT UNSIGNED NULL,
  `created_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_calendar_1_idx` (`creator_id` ASC),
  CONSTRAINT `fk_calendar_1`
    FOREIGN KEY (`creator_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `calendar_share`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `calendar_share` ;

CREATE TABLE IF NOT EXISTS `calendar_share` (
  `calendar_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`calendar_id`, `user_id`),
  INDEX `fk_calendar_share_2_idx` (`user_id` ASC),
  CONSTRAINT `fk_calendar_share_1`
    FOREIGN KEY (`calendar_id`)
    REFERENCES `calendar` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_calendar_share_2`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `school_picture`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `school_picture` ;

CREATE TABLE IF NOT EXISTS `school_picture` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` INT UNSIGNED NULL,
  `token` VARCHAR(80) NULL,
  `name` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_school_picture_1_idx` (`school_id` ASC),
  CONSTRAINT `fk_school_picture_1`
    FOREIGN KEY (`school_id`)
    REFERENCES `school` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `school_video`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `school_video` ;

CREATE TABLE IF NOT EXISTS `school_video` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(80) NULL,
  `url` TEXT NULL,
  `name` VARCHAR(255) NULL,
  `school_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_school_video_1_idx` (`school_id` ASC),
  CONSTRAINT `fk_school_video_1`
    FOREIGN KEY (`school_id`)
    REFERENCES `school` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `item_assigment_document`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `item_assigment_document` ;

CREATE TABLE IF NOT EXISTS `item_assigment_document` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_assigment_id` INT UNSIGNED NOT NULL,
  `type` VARCHAR(100) NULL,
  `title` VARCHAR(45) NULL,
  `author` VARCHAR(45) NULL,
  `link` VARCHAR(45) NULL,
  `source` TEXT NULL,
  `token` VARCHAR(80) NULL,
  `date` DATE NULL,
  `created_date` DATETIME NULL,
  `deleted_date` DATETIME NULL,
  `updated_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_individual_assigment_document_user_1_idx` (`item_assigment_id` ASC),
  CONSTRAINT `fk_individual_assigment_document_user_1`
    FOREIGN KEY (`item_assigment_id`)
    REFERENCES `item_assignment` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `feed`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `feed` ;

CREATE TABLE IF NOT EXISTS `feed` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` TEXT NULL,
  `user_id` INT UNSIGNED NULL,
  `link` TEXT NULL,
  `video` VARCHAR(80) NULL,
  `picture` VARCHAR(80) NULL,
  `document` VARCHAR(80) NULL,
  `created_date` DATETIME NULL,
  `deleted_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_feed_1_idx` (`user_id` ASC),
  CONSTRAINT `fk_feed_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `feed_comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `feed_comment` ;

CREATE TABLE IF NOT EXISTS `feed_comment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` TEXT NULL,
  `user_id` INT UNSIGNED NULL,
  `feed_id` INT UNSIGNED NULL,
  `created_date` DATETIME NULL,
  `deleted_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_feed_comment_1_idx` (`user_id` ASC),
  INDEX `fk_feed_comment_2_idx` (`feed_id` ASC),
  CONSTRAINT `fk_feed_comment_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_feed_comment_2`
    FOREIGN KEY (`feed_id`)
    REFERENCES `feed` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contact`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contact` ;

CREATE TABLE IF NOT EXISTS `contact` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NULL,
  `contact_id` INT UNSIGNED NULL,
  `request_date` DATETIME NULL,
  `deleted_date` DATETIME NULL,
  `acepted_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_contact_1_idx` (`user_id` ASC),
  INDEX `fk_contact_2_idx` (`contact_id` ASC),
  CONSTRAINT `fk_contact_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contact_2`
    FOREIGN KEY (`contact_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `program_user_relation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `program_user_relation` ;

CREATE TABLE IF NOT EXISTS `program_user_relation` (
  `program_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`program_id`, `user_id`),
  INDEX `fk_program_user_relation_2_idx` (`user_id` ASC),
  CONSTRAINT `fk_program_user_relation_2`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_program_user_relation_1`
    FOREIGN KEY (`program_id`)
    REFERENCES `program` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `permission` ;

CREATE TABLE IF NOT EXISTS `permission` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `libelle` VARCHAR(512) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `role_permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `role_permission` ;

CREATE TABLE IF NOT EXISTS `role_permission` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` INT UNSIGNED NOT NULL,
  `permission_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_role_permission_1_idx` (`permission_id` ASC),
  INDEX `fk_role_permission_2_idx` (`role_id` ASC),
  CONSTRAINT `fk_role_permission_1`
    FOREIGN KEY (`permission_id`)
    REFERENCES `permission` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_role_permission_2`
    FOREIGN KEY (`role_id`)
    REFERENCES `role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `role_relation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `role_relation` ;

CREATE TABLE IF NOT EXISTS `role_relation` (
  `role_id` INT UNSIGNED NOT NULL,
  `parent_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `parent_id`),
  INDEX `fk_role_relation_2_idx` (`parent_id` ASC),
  CONSTRAINT `fk_role_relation_1`
    FOREIGN KEY (`role_id`)
    REFERENCES `role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_role_relation_2`
    FOREIGN KEY (`parent_id`)
    REFERENCES `role` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `item_assignment_comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `item_assignment_comment` ;

CREATE TABLE IF NOT EXISTS `item_assignment_comment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `text` TEXT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `item_assignment_id` INT UNSIGNED NOT NULL,
  `created_date` DATETIME NULL,
  `read_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_item_assignment_10_idx` (`item_assignment_id` ASC),
  INDEX `fk_item_assignment_comment_1_idx` (`user_id` ASC),
  CONSTRAINT `fk_item_assignment_10`
    FOREIGN KEY (`item_assignment_id`)
    REFERENCES `item_assignment` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_assignment_comment_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `grading_policy_grade`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grading_policy_grade` ;

CREATE TABLE IF NOT EXISTS `grading_policy_grade` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `grading_policy_id` INT UNSIGNED NULL,
  `grade` INT UNSIGNED NULL,
  `created_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_item_grading_20_idx` (`grading_policy_id` ASC),
  CONSTRAINT `fk_item_grading_20`
    FOREIGN KEY (`grading_policy_id`)
    REFERENCES `grading_policy` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `grading_policy_grade_comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `grading_policy_grade_comment` ;

CREATE TABLE IF NOT EXISTS `grading_policy_grade_comment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `text` TEXT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `grading_policy_grade_id` INT UNSIGNED NOT NULL,
  `created_date` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_item_assignment_comment_1_idx` (`user_id` ASC),
  INDEX `fk_item_assignment_100_idx` (`grading_policy_grade_id` ASC),
  CONSTRAINT `fk_item_assignment_100`
    FOREIGN KEY (`grading_policy_grade_id`)
    REFERENCES `grading_policy_grade` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_item_assignment_comment_10`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
