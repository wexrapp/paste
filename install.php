<?php
// Import config.php
require_once("config.php");

// Set content-type to raw text
header("Content-Type: text/plain");

// Create pastes table
if ($result = $mysqli->query("CREATE TABLE `pastes` (
    `id` varchar(16) NOT NULL,
    `title` varchar(255) NOT NULL,
    `content` longtext NOT NULL,
    `password` varchar(255) NOT NULL,
    `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
    `user_ip` varchar(45) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX (`user_ip`)
    );")) {
    echo (INSTALL_PASTES_TABLE_CREATED . "\n");
}

// Create users table
if ($result = $mysqli->query("CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
    );")) {
    echo (INSTALL_USERS_TABLE_CREATED . "\n");
}

// Add a foreign key 'written_by' to pastes table, linking to the 'id' column in users table
if ($result = $mysqli->query("ALTER TABLE `pastes` ADD COLUMN `written_by` int(11) NOT NULL, ADD CONSTRAINT `fk_pastes_users` FOREIGN KEY (`written_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;")) {
    echo (INSTALL_FOREIGN_KEY_ADDED . "\n");
}
?>
