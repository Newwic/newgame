-- Run this in your 'newgame' database (phpMyAdmin or mysql CLI)
CREATE DATABASE IF NOT EXISTS `newgame` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `newgame`;

-- Users table for authentication and profiles
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(255) NOT NULL,
  `avatar_url` VARCHAR(512) DEFAULT NULL,
  `password_hash` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tables for seating and orders
CREATE TABLE IF NOT EXISTS `tables` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `occupied` TINYINT(1) NOT NULL DEFAULT 0,
  `last_order_id` INT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `table_id` INT DEFAULT NULL,
  `total` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `payment_method` VARCHAR(32) DEFAULT NULL,
  `status` VARCHAR(32) NOT NULL DEFAULT 'pending',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY (`table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `product_id` INT DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `price` DECIMAL(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY (`order_id`),
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed 10 table rows (1..10) if they don't exist. This helps the API update table status reliably.
INSERT INTO `tables` (`id`, `occupied`) VALUES
(1,0),(2,0),(3,0),(4,0),(5,0),(6,0),(7,0),(8,0),(9,0),(10,0)
ON DUPLICATE KEY UPDATE `occupied` = VALUES(`occupied`);

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `product` VARCHAR(255) NOT NULL,
  `name` VARCHAR(100) DEFAULT NULL,
  `rating` TINYINT NOT NULL,
  `comment` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Messages table for contact form
CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
