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

CREATE TABLE `tag_monitoring`.`tag_requests` (
    `id` INT NOT NULL AUTO_INCREMENT , 
    `ticket_id` VARCHAR(150) NULL DEFAULT NULL , 
    `tags` VARCHAR(200) NOT NULL , 
    `property_id` TINYINT(4) NOT NULL , 
    `vendor_id` TINYINT(4) NOT NULL , 
    `status` TINYINT(4) NOT NULL , 
    `type` TINYINT(4) NOT NULL COMMENT 'New Tag or New Rule' , 
    `counterpart_ticket` TINYINT(4) NULL DEFAULT NULL , 
    `requester_name` VARCHAR(200) NULL DEFAULT NULL , 
    `requester_email` VARCHAR(200) NULL DEFAULT NULL , 
    `requester_organization` TINYINT(4) NOT NULL , 
    `updated_by` TINYINT(4) NOT NULL DEFAULT '0' , 
    `updated_at` DATETIME NULL DEFAULT NULL , 
    `created_by` TINYINT(4) NOT NULL DEFAULT '0' , 
    `created_at` DATETIME NULL DEFAULT NULL , 
    `deleted_by` TINYINT(4) NOT NULL DEFAULT '0' , 
    `deleted_at` DATETIME NULL DEFAULT NULL , PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `tags` 
    ADD `new_tag_request` TINYINT(4) NULL DEFAULT NULL AFTER `requester_organization`, 
    ADD `new_rule_request` TINYINT(4) NULL DEFAULT NULL AFTER `new_tag_request`;

CREATE TABLE `tag_monitoring`.`tag_request_files` (
    `id` INT NOT NULL AUTO_INCREMENT , 
    `file_name` VARCHAR(200) NOT NULL , 
    `file_path` VARCHAR(500) NOT NULL , 
    `type` TINYINT(4) NOT NULL , 
    `request_id` TINYINT(4) NOT NULL , 
    `created_by` TINYINT NOT NULL , 
    `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `tags` CHANGE `conditions` `conditions` VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '(.*)';
/** [27/04] DB updated on Samsung machine **/

/** [29/04] Updates from Samsung machine **/
ALTER TABLE `tag_request_files` ADD `updated_at` DATETIME NULL AFTER `created_at`;

