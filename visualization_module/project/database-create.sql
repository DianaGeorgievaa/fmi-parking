SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

SET
    AUTOCOMMIT = 0;

START TRANSACTION;

SET
    time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `fmi_parking` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

USE `fmi_parking`;

CREATE TABLE `parking_spot` (
    `parking_spot_id` int NOT NULL AUTO_INCREMENT,
    `number` int NOT NULL,
    `zone` VARCHAR(10) NOT NULL,
    `is_free` boolean NOT NULL,
    `type` VARCHAR(20) NOT NULL,
    `user_in_spot` VARCHAR(30),
    `car_in_spot` VARCHAR(30),

    PRIMARY KEY (`parking_spot_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

Alter table `parking_spot` add constraint  UC_Parking_Spot UNIQUE(`number`, `zone`);
