<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>HobbyHub</title>
  <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<div class="wrap">

<header>
  <a href="/" style="text-decoration: none">
  <div class="brand">
    <h1>HobbyHub</h1>
    <p>Un blog sencillo de hobbies 🎮🍳✈️</p>
  </div>
  </a>

  <nav class="top">
    <!-- <a class="pill" href="/index.php">Inicio</a> -->
    <a class="pill" href="/pages/category.php?cat=cocina">Cocina</a>
    <a class="pill" href="/pages/category.php?cat=viajes">Viajes</a>
    <a class="pill" href="/pages/category.php?cat=gaming">Gaming</a>
    <a class="pill" href="../sitemap-tree.php">Mapa de sitio</a>
    <?php if (!isset($_SESSION['admin'])): ?>
  <a class="pill" href="/admin/login.php">🔐 Login</a>
<?php else: ?>
  <a class="pill" href="/admin/dashboard.php">🛠 Admin</a>
  <a class="pill" href="/admin/logout.php">🚪 Logout</a>
<?php endif; ?>

  </nav>
</header>
