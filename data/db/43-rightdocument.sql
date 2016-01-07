INSERT IGNORE INTO `permission` (`libelle`) VALUES ('document.delete');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'document.delete'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('document.update');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'document.update'));

INSERT IGNORE INTO `permission` (`libelle`) VALUES ('document.add');
INSERT IGNORE INTO `role_permission` (`role_id`, `permission_id`) VALUES (4, 
(SELECT `id` FROM `permission` WHERE `libelle`= 'document.update'));

ALTER TABLE `document` 
DROP FOREIGN KEY `fk_document_1`;

ALTER TABLE `document` 
ADD UNIQUE INDEX `item_id_UNIQUE` (`item_id` ASC);

ALTER TABLE `document` 
ADD CONSTRAINT `fk_document_1`
  FOREIGN KEY (`item_id`)
  REFERENCES `item` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;
