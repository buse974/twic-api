ALTER TABLE `answer` ADD UNIQUE INDEX `uq_answer` (`questionnaire_user_id` ASC, `questionnaire_question_id` ASC);

