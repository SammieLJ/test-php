DROP DATABASE IF EXISTS `bof_test`;
CREATE DATABASE IF NOT EXISTS `bof_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP USER IF EXISTS 'bof-test'@'localhost';
CREATE USER 'bof-test'@'localhost' IDENTIFIED BY 'bof-test';

GRANT USAGE ON * . * TO 'bof-test'@'localhost' IDENTIFIED BY 'bof-test' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

GRANT ALL PRIVILEGES ON `bof\_test` . * TO 'bof-test'@'localhost' WITH GRANT OPTION ;

DROP TABLE IF EXISTS `bof_test`.`profiles`;
CREATE TABLE `bof_test`.`profiles` (
`profile_id` INT NOT NULL AUTO_INCREMENT ,
`profile_name` VARCHAR( 100 ) NOT NULL
) ENGINE = InnoDB AUTO_INCREMENT=1 CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS `bof_test`.`views`;
CREATE TABLE `bof_test`.`views` (
`profile_id` INT NOT NULL ,
`date` DATE NOT NULL ,
`views` INT NOT NULL
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

INSERT INTO `bof_test`.`profiles` VALUES(1, 'Karl Lagerfeld'), (2, 'Anna Wintour'), (3, 'Tom Ford'), (4, 'Pierre Alexis Dumas'), (5, 'Sandra Choi');

DROP TABLE IF EXISTS `bof_test`.`reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `Profile year` varchar(255) NOT NULL,
  `Jan` varchar(120) NOT NULL,
  `Feb` varchar(120) NOT NULL,
  `Mar` varchar(120) NOT NULL,
  `Apr` varchar(120) NOT NULL,
  `May` varchar(120) NOT NULL,
  `Jun` varchar(120) NOT NULL,
  `Jul` varchar(120) NOT NULL,
  `Avg` varchar(120) NOT NULL,
  `Sep` varchar(120) NOT NULL,
  `Oct` varchar(120) NOT NULL,
  `Nov` varchar(120) NOT NULL,
  `Dec` varchar(120) NOT NULL
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT='Yearly reports';