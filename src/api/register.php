<?php
require_once "../config/database.php";

header("Content-Type: application/json; charset=UTF-8");

// Validar que es una petición AJAX POST
if (
    $_SERVER['REQUEST_METHOD'] !== 'POST' ||
    (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
) {
    http_response_code(403);
    echo json_encode([
        "ok" => false,
        "error" => "Acceso no permitido"
    ]);
    exit;
}

try {
    // Obtener datos del formulario
    $username = trim($_POST['username'] ?? "");
    $password = trim($_POST['password'] ?? "");
    $password_confirm = trim($_POST['password_confirm'] ?? "");

    // Validaciones
    if (strlen($username) < 3) {
        throw new Exception("El usuario debe tener al menos 3 caracteres");
    }

    if (strlen($password) < 6) {
        throw new Exception("La contraseña debe tener al menos 6 caracteres");
    }

    if ($password !== $password_confirm) {
        throw new Exception("Las contraseñas no coinciden");
    }

    // Validar que el usuario no exista
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        throw new Exception("El usuario ya está registrado");
    }

    // Hashear la contraseña
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Insertar nuevo usuario
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password_hashed]);

    echo json_encode([
        "ok" => true,
        "message" => "Registro exitoso. Redirigiendo al login...",
        "id" => $pdo->lastInsertId()
    ]);
    exit;

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => $e->getMessage()
    ]);
    exit;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "ok" => false,
        "error" => "Error en la base de datos"
    ]);
    exit;
}
?>
