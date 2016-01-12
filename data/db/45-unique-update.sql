ALTER TABLE `answer`
DROP INDEX `uq_answer` ,
ADD UNIQUE INDEX `uq_answer` (`questionnaire_user_id` ASC, `questionnaire_question_id` ASC, `peer_id` ASC);
