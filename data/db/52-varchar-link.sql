ALTER TABLE `material_document` 
CHANGE COLUMN `link` `link` VARCHAR(512) NULL DEFAULT NULL ;

ALTER TABLE `item_assigment_document` 
CHANGE COLUMN `link` `link` VARCHAR(512) NULL DEFAULT NULL ;

ALTER TABLE `feed` 
CHANGE COLUMN `link_title` `link_title` VARCHAR(512) NULL DEFAULT NULL ;

