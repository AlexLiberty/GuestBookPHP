<?php
$host = 'localhost';
$db = 'guestbook';
$user = 'root';
$pass = '';

try
{
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        CREATE TABLE IF NOT EXISTS messages (
            id_msg INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            city VARCHAR(100),
            email VARCHAR(100),
            url VARCHAR(255),
            msg TEXT NOT NULL,
            answer TEXT,
            puttime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            hide ENUM('show', 'hide') DEFAULT 'show'
        )
    ";
    $pdo->exec($sql);
}
catch (PDOException $e)
{
    die("Помилка підключення до бази даних: " . $e->getMessage());
}
?>
