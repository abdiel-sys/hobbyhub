<?php
$host = "localhost";
$db   = "php_project"; // o project_php (pero que exista)
$user = "root";
$pass = "1234";            // o tu contraseña si tienes

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}