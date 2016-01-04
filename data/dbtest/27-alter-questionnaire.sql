ALTER TABLE `questionnaire_user` 
ADD COLUMN `item_prog_user_id` INT UNSIGNED NOT NULL AFTER `questionnaire_id`,
ADD INDEX `fk_questionnaire_user_3_idx` (`item_prog_user_id` ASC);

ALTER TABLE `questionnaire_user` 
ADD CONSTRAINT `fk_questionnaire_user_3`
  FOREIGN KEY (`item_prog_user_id`)
  REFERENCES `item_prog_user` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `answer` 
DROP FOREIGN KEY `fk_answer_1`;

ALTER TABLE `answer` 
ADD CONSTRAINT `fk_answer_1`
  FOREIGN KEY (`questionnaire_user_id`)
  REFERENCES `questionnaire_user` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;


UPDATE `questionnaire_user` toto 
SET 
    `item_prog_user_id` = (SELECT 
            item_prog_user.id
        FROM
            item_prog_user
                INNER JOIN
            item_prog ON item_prog_user.item_prog_id = item_prog.id
                INNER JOIN
            questionnaire ON questionnaire.item_id = item_prog.item_id
        WHERE
            item_prog_user.user_id = toto.`user_id`
                AND questionnaire.id = toto.`questionnaire_id`)
WHERE
    `item_prog_user_id` IS NULL OR `item_prog_user_id`=0;

