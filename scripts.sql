ALTER TABLE `tags` 
    ADD `requester_name` VARCHAR(150) NULL DEFAULT NULL AFTER `ref_link`, 
    ADD `requester_email` VARCHAR(150) NULL DEFAULT NULL AFTER `requester_name`, 
    ADD `requester_organization` TINYINT(4) NOT NULL DEFAULT '1' 
    AFTER `requester_email`;

ALTER TABLE `jira_history` 
    ADD `property_id` INT NOT NULL DEFAULT '0' 
    AFTER `tag_id`;

ALTER TABLE `vendors` 
    ADD `reason` VARCHAR(500) NULL DEFAULT NULL 
    AFTER `about`;

CREATE TABLE `tag_monitoring`.`organizations` (
    `id` INT NOT NULL AUTO_INCREMENT, 
    `name` VARCHAR(150) NOT NULL , 
    `updated_by` TINYINT(4) NOT NULL DEFAULT '0' , 
    `updated_at` DATETIME NULL , 
    `created_by` TINYINT(4) NOT NULL DEFAULT '0' , 
    `created_at` DATETIME NULL , 
    `deleted_by` TINYINT(4) NULL DEFAULT NULL , 
    `deleted_at` DATETIME NULL , PRIMARY KEY (`id`)
) ENGINE = InnoDB;
