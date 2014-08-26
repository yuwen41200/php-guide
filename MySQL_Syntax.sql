-- Connect to MySQL Server
mysql -h host -u user -p

-- SHOW DATABASES Syntax
SHOW DATABASES;

-- CREATE DATABASE Syntax
CREATE DATABASE `database`;

-- USE Syntax
USE `database`;

-- SHOW TABLES Syntax
SHOW TABLES;

-- CREATE TABLE Syntax
CREATE TABLE `table` (
	`column1` INT(20) NOT NULL AUTO_INCREMENT,
	`column2` VARCHAR(20) NOT NULL,
	`column3` TEXT NOT NULL,
	`column4` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`column1`)
) ENGINE = MyISAM DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;

-- DESCRIBE Syntax
DESCRIBE `table`;

-- SELECT Syntax
SELECT `column` FROM `table`
WHERE `column` LIKE 'value_/%' AND `column` = 'value'
LIMIT start, number
ORDER BY `column` ASC/DESC;

-- INSERT Syntax
INSERT INTO `table` (`column1`, `column2`, `column3`)
VALUES ('value1', 'value2', 'value3');

-- UPDATE Syntax
UPDATE `table` SET `column1` = 'value1', `column2` = 'value2'
WHERE `column3` = 'value3'
LIMIT 1;

-- DELETE Syntax
DELETE FROM `table`
WHERE `column` = 'value'
LIMIT 1;

-- DROP TABLE Syntax
DROP TABLE `table`;

-- DROP DATABASE Syntax
DROP DATABASE `database`;