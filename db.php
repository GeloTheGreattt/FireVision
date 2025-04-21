<?php

$host = 'localhost'; // Database host
$dbname = 'firevision'; // Database name
$username = 'firevision'; // Database username
$password = 'NIAsdZKB1234@'; // Database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>?>
