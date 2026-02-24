<?php
$host = "localhost";
$db   = "php_project";
$user = "root";     
$pass = "1234";        

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass,
        [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    //esto te mostrará el error exacto en pantalla
    die("Error de conexión BD: " . $e->getMessage());
}