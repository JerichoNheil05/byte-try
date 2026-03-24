<?php
// Quick script to create payment_transactions table

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'bytemarket';

$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS `payment_transactions` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `paymongo_source_id` VARCHAR(255) NULL,
  `paymongo_payment_id` VARCHAR(255) NULL,
  `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `currency` VARCHAR(3) NOT NULL DEFAULT 'PHP',
  `payment_method` VARCHAR(50) NOT NULL DEFAULT 'gcash',
  `status` ENUM('pending', 'paid', 'failed', 'expired', 'cancelled') NOT NULL DEFAULT 'pending',
  `redirect_url` TEXT NULL,
  `webhook_data` JSON NULL,
  `metadata` JSON NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `paymongo_source_id` (`paymongo_source_id`),
  KEY `status` (`status`),
  CONSTRAINT `payment_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($mysqli->query($sql)) {
    echo "✅ Table 'payment_transactions' created successfully!\n";
} else {
    echo "❌ Error creating table: " . $mysqli->error . "\n";
}

$mysqli->close();
?>
