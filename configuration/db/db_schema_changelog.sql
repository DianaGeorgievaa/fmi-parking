SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

SET
    AUTOCOMMIT = 0;

START TRANSACTION;

SET
    time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `fmi_parking` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

USE `fmi_parking`;

CREATE TABLE `users` (
    `user_id` int NOT NULL AUTO_INCREMENT,
    `first_name` varchar(256) NOT NULL,
    `last_name` varchar(256) NOT NULL,
    `email` varchar(256) NOT NULL UNIQUE,
    `password` varchar(256) NOT NULL,
    `status` enum('ADMIN', 'PERMANENT', 'TEMPORARY', 'BLOCKED') NOT NULL DEFAULT 'BLOCKED',
    `photo_name` varchar(256) NOT NULL,
    `points` int(11) NOT NULL,
    `qr_code` varchar(256),
    PRIMARY KEY (user_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `courses` (
    `course_id` int NOT NULL AUTO_INCREMENT,
    `course_title` varchar(255) NOT NULL,
    `course_day` enum(
        'MONDAY',
        'TUESDAY',
        'WEDNESDAY',
        'THURSDAY',
        'FRIDAY',
        'SATURDAY',
        'SUNDAY'
    ) NOT NULL,
    `start_time` time NOT NULL,
    `end_time` time NOT NULL,
    PRIMARY KEY (course_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `users_courses`(
    `course_id` int NOT NULL,
    `user_id` int NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    UNIQUE (course_id, user_id)
);

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


ALTER TABLE `parking_spot` ADD CONSTRAINT UC_Parking_Spot UNIQUE(`number`, `zone`);


CREATE TABLE `user_parking_info` (
    `user_parking_info_id` int NOT NULL AUTO_INCREMENT,
    `parking_date_in` datetime NOT NULL,
    `end_time_lecture` time,
    `has_lectures` boolean,
    PRIMARY KEY (user_parking_info_id),
    `user_id` int NOT NULL REFERENCES `users`(`user_id`),
    CONSTRAINT user_id UNIQUE (user_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE `profile_viewer` (
    `profile_viewer_id` int NOT NULL AUTO_INCREMENT,
    `first_name` varchar(256) NOT NULL,
    `last_name` varchar(256) NOT NULL,
    `email` VARCHAR(256) NOT NULL,
    `view_time` datetime NOT NULL,
    PRIMARY KEY(profile_viewer_id),
    `user_id` int NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
