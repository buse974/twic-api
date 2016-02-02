ALTER TABLE `contact` 
ADD COLUMN `deleted` INT NOT NULL DEFAULT 0 AFTER `accepted_date`,
ADD COLUMN `accepted` INT NOT NULL DEFAULT 0 AFTER `deleted`,
ADD COLUMN `requested` INT NOT NULL DEFAULT 0 AFTER `accepted`;

