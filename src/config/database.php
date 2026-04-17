<?php

$DB_HOST = getenv('DB_HOST') ?: "mysql";
$DB_NAME = getenv('DB_NAME') ?: "project_php";
$DB_USER = getenv('DB_USER') ?: "root";
$DB_PASS = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "root";


try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {


    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        http_response_code(500);
        echo json_encode([
            "ok" => false,
            "error" => "Error de conexión a la base de datos"
        ]);
        exit;
    }


    header("Location: /errors/500.php");
    exit;
}
