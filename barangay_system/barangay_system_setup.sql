USERS TABLE
CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `fname`      VARCHAR(100) DEFAULT NULL,
  `lname`      VARCHAR(100) DEFAULT NULL,
  `username`   VARCHAR(100) DEFAULT NULL,
  `email`      VARCHAR(100) DEFAULT NULL,
  `password`   VARCHAR(255) DEFAULT NULL,
  `role`       ENUM('admin','user') DEFAULT 'user',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMPLAINTS TABLE
CREATE TABLE IF NOT EXISTS `complaints` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `user_id`     INT(11) DEFAULT NULL,
  `category`    VARCHAR(100) DEFAULT 'Others',
  `description` TEXT DEFAULT NULL,
  `status`      ENUM('Pending','Resolved') DEFAULT 'Pending',
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

APPOINTMENTS TABLE
CREATE TABLE IF NOT EXISTS `appointments` (
  `id`               INT(11) NOT NULL AUTO_INCREMENT,
  `user_id`          INT(11) DEFAULT NULL,
  `purpose`          VARCHAR(255) DEFAULT NULL,
  `service`          VARCHAR(100) DEFAULT NULL,
  `appointment_date` DATE DEFAULT NULL,
  `appointment_time` TIME DEFAULT NULL,
  `status`           ENUM('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

EMERGENCIES TABLE
CREATE TABLE IF NOT EXISTS `emergencies` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `user_id`     INT(11) DEFAULT NULL,
  `type`        VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `location`    VARCHAR(255) DEFAULT NULL,
  `status`      ENUM('Urgent','Responding','Resolved') DEFAULT 'Urgent',
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ANNOUNCEMENTS TABLE
CREATE TABLE IF NOT EXISTS `announcements` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `title`      VARCHAR(255) DEFAULT NULL,
  `content`    TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;