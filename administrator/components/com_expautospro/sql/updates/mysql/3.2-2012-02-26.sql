UPDATE `#__expautos_config` SET `version`='3.2' WHERE id=1;
ALTER TABLE `#__expautos_admanager` ADD `zipcode` VARCHAR(20) NOT NULL;
ALTER TABLE `#__expautos_payment` ADD `paynotice` TEXT DEFAULT NULL;
ALTER TABLE `#__expautos_payment` ADD `payid` INT(11) DEFAULT NULL;
ALTER TABLE `#__expautos_payment` ADD `paysysval` INT(11) DEFAULT NULL;
ALTER TABLE `#__expautos_payment` ADD `state` TINYINT(1) NOT NULL DEFAULT 0;