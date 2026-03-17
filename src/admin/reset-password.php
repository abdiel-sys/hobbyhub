<?php
require_once "../config/database.php";

$token = $_GET['token'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token=? AND reset_expires > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
  die("Token inválido o expirado");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $stmt = $pdo->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE id=?");
  $stmt->execute([$password, $user['id']]);

  echo "Contraseña actualizada. <a href='login.php'>Iniciar sesión</a>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../public/css/style.css">
  <title> Login - HobbyHub</title>
</head>

<body>

<div class="wrap">
  <section class="card auth-container">

<div class="auth-header">
  <h2>🔐 Nueva contraseña</h2>
  <p>Introduce una nueva contraseña para tu cuenta</p>
</div>

<form method="POST">

  <div class="form-group">
    <label for="password">Nueva contraseña</label>
    <input
      class="input"
      type="password"
      id="password"
      name="password"
      placeholder="Mínimo 6 caracteres"
      minlength="6"
      required
      autocomplete="new-password">
  </div>

  <div class="auth-actions">
    <button type="submit" class="btn primary">
      Cambiar contraseña
    </button>
  </div>

</form>

<div class="divider">
  <span>¿Recordaste tu contraseña?</span>
</div>

<div class="auth-link">
  <a href="login.php">🔐 Volver al login</a>
</div>
</body>
