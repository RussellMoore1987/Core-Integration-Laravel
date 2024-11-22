-- create databases
CREATE DATABASE IF NOT EXISTS `coreintegrationdb`;
CREATE DATABASE IF NOT EXISTS `testing`;

-- create root user and grant rights
GRANT ALL ON *.* TO 'root'@'%';