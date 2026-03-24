<?php
// One-off helper to ensure products table has product_feature and how_it_works columns.

$mysqli = new mysqli('localhost', 'root', '', 'bytemarket');
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error . PHP_EOL);
}

$columns = [];
$result = $mysqli->query("SHOW COLUMNS FROM products");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
}

if (!in_array('product_feature', $columns, true)) {
    if (!$mysqli->query("ALTER TABLE products ADD COLUMN product_feature TEXT NULL AFTER description")) {
        echo 'ERROR adding product_feature: ' . $mysqli->error . PHP_EOL;
    } else {
        echo 'Added product_feature column.' . PHP_EOL;
    }
} else {
    echo 'product_feature column already exists.' . PHP_EOL;
}

if (!in_array('how_it_works', $columns, true)) {
    if (!$mysqli->query("ALTER TABLE products ADD COLUMN how_it_works TEXT NULL AFTER product_feature")) {
        echo 'ERROR adding how_it_works: ' . $mysqli->error . PHP_EOL;
    } else {
        echo 'Added how_it_works column.' . PHP_EOL;
    }
} else {
    echo 'how_it_works column already exists.' . PHP_EOL;
}

$mysqli->close();
