<?php
$host = "mysql";
$db   = "php_project";
$user = "admin";
$pass = "admin";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    header("Location: /errors/500.php");
    exit;
}
