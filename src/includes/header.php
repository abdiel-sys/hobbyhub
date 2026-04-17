<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/user_functions.php';

/*
 * Detecta automáticamente la ruta base hasta /src
 * Ejemplo:
 *   /hobbyhub/src
 *   /mi_repo/src
 */
$uri = $_SERVER['REQUEST_URI']; // ej: /hobbyhub/src/admin/dashboard.php
$pos = strpos($uri, '/src/');
$BASE = ($pos !== false) ? substr($uri, 0, $pos) . '/src' : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>HobbyHub</title>
  <link rel="stylesheet" href="<?= $BASE ?>/public/css/style.css">
</head>
<body>
<div class="wrap">

<header>
  <a href="<?= $BASE ?>/" style="text-decoration: none">
    <div class="brand">
      <h1>HobbyHub</h1>
      <p>Un blog sencillo de hobbies 🎮🍳✈️</p>
    </div>
  </a>

  <nav class="top">
    <a class="pill" href="<?= $BASE ?>/pages/category.php?cat=cocina">Cocina</a>
    <a class="pill" href="<?= $BASE ?>/pages/category.php?cat=viajes">Viajes</a>
    <a class="pill" href="<?= $BASE ?>/pages/category.php?cat=gaming">Gaming</a>
    <a class="pill" href="<?= $BASE ?>/sitemap-tree.php">Mapa de sitio</a>

    <?php if (!isUserLoggedIn()): ?>
      <a class="pill" href="<?= $BASE ?>/admin/login.php">🔐 Login</a>
    <?php else: ?>
      <?php if (userHasRole('admin')): ?>
        <a class="pill" href="<?= $BASE ?>/admin/dashboard.php">🛠 Admin</a>
      <?php endif; ?>
      <a class="pill" href="<?= $BASE ?>/admin/logout.php">🚪 Logout</a>
    <?php endif; ?>
  </nav>
</header>