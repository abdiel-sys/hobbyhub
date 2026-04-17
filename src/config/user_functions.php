<?php
/**
 * Funciones para gestionar usuarios en sesión
 */

require_once 'database.php';

/**
 * Obtiene el usuario actual almacenado en la sesión
 * 
 * @return array|null Datos del usuario en sesión o null si no hay usuario
 */
function getUser() {
    if (!isset($_SESSION['user'])) {
        return null;
    }
    return $_SESSION['user'];
}

/**
 * Obtiene un usuario por ID desde la base de datos
 * 
 * @param int $userId ID del usuario
 * @return array|null Datos del usuario o null si no existe
 */
function getUserById($userId) {
    global $pdo;
    
    if (!is_numeric($userId)) {
        return null;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT id, username, email, created_at, role FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching user by ID: " . $e->getMessage());
        return null;
    }
}

/**
 * Obtiene un usuario por nombre de usuario desde la base de datos
 * 
 * @param string $username Nombre de usuario
 * @return array|null Datos del usuario o null si no existe
 */
function getUserByUsername($username) {
    global $pdo;
    
    if (empty($username)) {
        return null;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT id, username, email, created_at, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching user by username: " . $e->getMessage());
        return null;
    }
}

/**
 * Guarda los datos del usuario en la sesión
 * 
 * @param array $userData Datos del usuario a guardar
 * @return bool true si se guardó correctamente, false si no
 */
function setUserSession($userData) {
    if (!is_array($userData) || empty($userData)) {
        return false;
    }
    
    $_SESSION['user'] = [
        'id' => $userData['id'] ?? null,
        'username' => $userData['username'] ?? null,
        'email' => $userData['email'] ?? null,
        'created_at' => $userData['created_at'] ?? null,
        'role' => $userData['role'] ?? 'user'
    ];
    
    return true;
}

/**
 * Actualiza los datos del usuario en sesión desde la base de datos
 * 
 * @param int $userId ID del usuario a actualizar
 * @return bool true si se actualizó correctamente, false si no
 */
function updateUserSession($userId) {
    $user = getUserById($userId);
    
    if ($user === null) {
        return false;
    }
    
    return setUserSession($user);
}

/**
 * Actualiza un campo específico del usuario en la base de datos
 * 
 * @param int $userId ID del usuario
 * @param string $field Campo a actualizar
 * @param mixed $value Nuevo valor
 * @return bool true si se actualizó correctamente, false si no
 */
function updateUserField($userId, $field, $value) {
    global $pdo;
    
    // Campos permitidos para actualizar
    $allowedFields = ['username', 'email'];
    
    if (!in_array($field, $allowedFields) || !is_numeric($userId)) {
        return false;
    }
    
    try {
        // Validar email si se actualiza
        if ($field === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        // Validar que el nuevo valor no esté duplicado
        $stmt = $pdo->prepare("SELECT id FROM users WHERE $field = ? AND id != ?");
        $stmt->execute([$value, $userId]);
        
        if ($stmt->fetch()) {
            return false;
        }
        
        // Actualizar en la base de datos
        $stmt = $pdo->prepare("UPDATE users SET $field = ? WHERE id = ?");
        $result = $stmt->execute([$value, $userId]);
        
        if ($result) {
            // Actualizar en la sesión si existe usuario en sesión
            if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $userId) {
                $_SESSION['user'][$field] = $value;
            }
            return true;
        }
        
        return false;
    } catch (PDOException $e) {
        error_log("Error updating user field: " . $e->getMessage());
        return false;
    }
}

/**
 * Verifica si el usuario actual está autenticado
 * 
 * @return bool true si hay un usuario en sesión, false si no
 */
function isUserLoggedIn() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']);
}

/**
 * Obtiene el rol del usuario actual en sesión
 * 
 * @return string|null Rol del usuario o null si no está autenticado
 */
function getUserRole() {
    $user = getUser();
    return $user['role'] ?? null;
}

/**
 * Verifica si el usuario tiene uno de los roles permitidos
 * 
 * @param string|array $roles Rol o lista de roles permitidos
 * @return bool true si el usuario tiene al menos un rol requerido
 */
function userHasRole($roles) {
    $userRole = getUserRole();
    if ($userRole === null) {
        return false;
    }

    if (is_array($roles)) {
        return in_array($userRole, $roles, true);
    }

    return $userRole === $roles;
}

/**
 * Cierra la sesión del usuario
 * 
 * @return bool true si se cerró correctamente
 */
function logoutUser() {
    unset($_SESSION['user']);
    unset($_SESSION['admin']);
    return true;
}

?>
