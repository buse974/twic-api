INSERT IGNORE INTO `subscription`
(`libelle`,
`user_id`)
SELECT CONCAT('PU',contact_id), user_id FROM contact
WHERE contact.accepted_date IS NOT NULL 
AND contact.deleted_date IS NULL
