ALTER TABLE `message_user` 
ADD UNIQUE INDEX `unique_message_user` (`message_id` ASC, `user_id` ASC);

