mysql -h host -u user -p
SHOW DATABASES;
CREATE DATABASE `database`;
USE `database`;
SHOW TABLES;
CREATE TABLE `table` (
	`column1` INT(20) NOT NULL AUTO_INCREMENT,
	`column2` VARCHAR(20) NOT NULL,
	`column3` TEXT NOT NULL,
	`column4` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`column1`)
) ENGINE = MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;
DESCRIBE `table`;
SELECT `column` FROM `table` LIMIT start, number WHERE `column` LIKE 'value_/%' AND `column` = 'value' ORDER BY `column` ASC/DESC;
INSERT INTO `table` (`column1`, `column2`, `column3`) VALUES ('value1', 'value2', 'value3');
UPDATE `table` SET `column1` = 'value1', `column2` = 'value2' WHERE `column3` = 'value3' LIMIT 1;
DELETE FROM `table` WHERE `column` = 'value' LIMIT 1;
DROP TABLE `table`;
DROP DATABASE `database`;