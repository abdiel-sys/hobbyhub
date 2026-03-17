<?php
require_once __DIR__ . '/../config/user_functions.php';

session_start();

// Verificar que el usuario está autenticado
if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}
