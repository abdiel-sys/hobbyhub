<?php
require_once __DIR__ . '/../config/user_functions.php';

session_start();

// Requiere que el usuario esté autenticado
// Es la página principal para usuarios autenticados
requireLogin();
