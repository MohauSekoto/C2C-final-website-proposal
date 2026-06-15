<?php
require_once 'c:\Users\Makro\Documents\ITECA3-12\Final Website\Gemini version clone\app\Core\Database.php';
$db = new \App\Core\Database();
$stmt = $db->query('SHOW TABLES');
print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
