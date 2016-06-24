ALTER TABLE `school` 
ADD COLUMN `url_sso_cas` VARCHAR(512) NULL DEFAULT NULL AFTER `deleted_date`;

ALTER TABLE `school` 
DROP COLUMN `programme`;

