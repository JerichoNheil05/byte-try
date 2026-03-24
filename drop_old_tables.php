<?php
// Temporary script to drop conflicting tables
defined('BASEPATH') || define('BASEPATH', __DIR__);

require BASEPATH . '/vendor/autoload.php';

$app = require BASEPATH . '/system/bootstrap.php';

$config = new \Config\Database();
$db = $config->getConnection();

echo "Dropping conflicting tables...\n";

try {
    $db->query('DROP TABLE IF EXISTS `orders` CASCADE');
    echo "✓ Dropped orders table\n";
} catch (\Exception $e) {
    echo "⚠ Could not drop orders: " . $e->getMessage() . "\n";
}

try {
    $db->query('DROP TABLE IF EXISTS `payments` CASCADE');
    echo "✓ Dropped payments table\n";
} catch (\Exception $e) {
    echo "⚠ Could not drop payments: " . $e->getMessage() . "\n";
}

echo "\nTables dropped. Ready for fresh migrations.\n";
?>
