UPDATE `#__expautos_config` SET `version`='3.0' WHERE id=1;
ALTER TABLE `#__expautos_admanager` ADD `latitude` DOUBLE DEFAULT NULL;
ALTER TABLE `#__expautos_admanager` ADD `longitude` DOUBLE DEFAULT NULL;
ALTER TABLE `#__expautos_expuser` ADD `latitude` DOUBLE DEFAULT NULL;
ALTER TABLE `#__expautos_expuser` ADD `longitude` DOUBLE DEFAULT NULL;