<?php
$host = 'localhost';
$dbname = 'wishme15august';
$username = 'root';
$password = '';

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    echo "Database created or already exists.\n";
    
    // Connect to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL to create table
    $sql = "CREATE TABLE IF NOT EXISTS wishes (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        unique_id VARCHAR(50) NOT NULL UNIQUE,
        user_name VARCHAR(100) NOT NULL,
        user_image VARCHAR(255) NOT NULL,
        language VARCHAR(50) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    // use exec() because no results are returned
    $pdo->exec($sql);
    echo "Table wishes created successfully.\n";
} catch(PDOException $e) {
    echo $sql . "\n" . $e->getMessage();
}
?>
