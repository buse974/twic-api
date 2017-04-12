UPDATE message, message_user, message_doc
SET message.user_id = message_user.from_id, message.library_id = message_doc.library_id
WHERE message_user.message_id = message.id AND message_user.user_id = message_user.from_id
    AND message_doc.message_id = message.id;