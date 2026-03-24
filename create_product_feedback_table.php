<?php
// One-off helper to create product_feedback table without running full migration chain.

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'bytemarket';

$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error . PHP_EOL);
}

$sql = "CREATE TABLE IF NOT EXISTS `product_feedback` (
  `feedback_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) UNSIGNED NOT NULL,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `rating` TINYINT(1) UNSIGNED NOT NULL DEFAULT 5,
  `comment` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`feedback_id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  UNIQUE KEY `product_user_unique` (`product_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($mysqli->query($sql)) {
    echo "OK: product_feedback table is ready." . PHP_EOL;
} else {
    echo "ERROR creating product_feedback table: " . $mysqli->error . PHP_EOL;
}

$mysqli->close();
