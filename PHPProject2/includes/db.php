<?php
$host = 'localhost';  // Your host name
$db = 'news_website'; // Your database name
$user = 'root';       // Your database username (default is root for XAMPP)
$pass = 'Eduv4821047';           // Your database password (default is empty for XAMPP)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}
