<?php
require_once 'c:\Users\Makro\Documents\ITECA3-12\Final Website\Gemini version clone\app\Core\Database.php';
$db = new \App\Core\Database();
print_r($db->query('DESCRIBE orders')->fetchAll());
