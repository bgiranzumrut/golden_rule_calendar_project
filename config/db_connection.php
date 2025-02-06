<?php
// 数据库配置
$host = 'localhost'; // Database host
$dbname = 'golden_rules_calendar'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    echo "Connection failed: " . $e->getMessage();
    exit(); // Stop execution if connection fails
}

// Example of updating a reference
// require 'index.php'; // Change this line to point to the correct location

return [
    'host' => $host,
    'dbname' => $dbname,
    'username' => $username,
    'password' => $password,
    'charset' => 'utf8mb4'
];