ALTER TABLE `tags` 
    ADD `requester_name` VARCHAR(150) NULL DEFAULT NULL AFTER `ref_link`, 
    ADD `requester_email` VARCHAR(150) NULL DEFAULT NULL AFTER `requester_name`, 
    ADD `requester_organization` TINYINT(4) NOT NULL DEFAULT '1' 
    AFTER `requester_email`;

