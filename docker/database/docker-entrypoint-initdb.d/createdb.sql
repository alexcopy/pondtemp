CREATE USER IF NOT EXISTS 'admin'@'localhost' IDENTIFIED  BY 'secret';
GRANT ALL PRIVILEGES ON *.* TO 'admin'@'localhost' WITH GRANT OPTION;
CREATE USER IF NOT EXISTS 'admin'@'%' IDENTIFIED  BY 'secret';
GRANT ALL ON `homestead`.* TO 'admin'@'%' ;
#
CREATE DATABASE IF NOT EXISTS `homestead` COLLATE 'utf8_general_ci' ;
GRANT ALL ON `homestead`.* TO 'admin'@'%' ;
FLUSH PRIVILEGES ;