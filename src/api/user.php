<?php
/**
 * API para gestionar usuarios
 * 
 * Métodos:
 * GET /api/user.php - Obtiene los datos del usuario actual en sesión
 * POST /api/user.php - Actualiza los datos del usuario actual (email,username)
 */

require_once "../config/database.php";
require_once "../config/user_functions.php";
session_start();

header("Content-Type: application/json; charset=UTF-8");

// Verificar que es una petición AJAX
if (
    !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
) {
    http_response_code(403);
    echo json_encode([
        "ok" => false,
        "error" => "Acceso no permitido"
    ]);
    exit;
}

// Verificar autenticación
if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode([
        "ok" => false,
        "error" => "No autenticado"
    ]);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Obtener datos del usuario actual
        $user = getUser();
        
        if ($user === null) {
            http_response_code(404);
            echo json_encode([
                "ok" => false,
                "error" => "Usuario no encontrado"
            ]);
            exit;
        }
        
        echo json_encode([
            "ok" => true,
            "user" => $user
        ]);
        exit;
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Actualizar datos del usuario
        $email = trim($_POST['email'] ?? "");
        $username = trim($_POST['username'] ?? "");
        
        $user = getUser();
        $updated = false;
        
        // Validar y actualizar email
        if (!empty($email) && $email !== $user['email']) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El correo electrónico no es válido");
            }
            
            if (updateUserField($user['id'], 'email', $email)) {
                $updated = true;
            } else {
                throw new Exception("El correo electrónico ya está registrado");
            }
        }
        
        // Validar y actualizar username
        if (!empty($username) && $username !== $user['username']) {
            if (strlen($username) < 3) {
                throw new Exception("El usuario debe tener al menos 3 caracteres");
            }
            
            if (updateUserField($user['id'], 'username', $username)) {
                $updated = true;
            } else {
                throw new Exception("El usuario ya está registrado");
            }
        }
        
        // Actualizar sesión desde la base de datos
        if ($updated) {
            updateUserSession($user['id']);
            $_SESSION['admin'] = getUser()['username'];
        }
        
        echo json_encode([
            "ok" => true,
            "message" => "Datos actualizados correctamente",
            "user" => getUser()
        ]);
        exit;
        
    } else {
        http_response_code(405);
        echo json_encode([
            "ok" => false,
            "error" => "Método no permitido"
        ]);
        exit;
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => $e->getMessage()
    ]);
    exit;
}

?>
