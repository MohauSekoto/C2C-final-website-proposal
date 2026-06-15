<?php
require_once 'c:\Users\Makro\Documents\ITECA3-12\Final Website\Gemini version clone\app\Core\Database.php';
$db = new \App\Core\Database();
$stmt = $db->query("
    SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE REFERENCED_TABLE_SCHEMA = 'defaultdb' AND REFERENCED_TABLE_NAME = 'users';
");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
