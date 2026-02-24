<?php
require_once "../config/database.php";
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin'] = $user['username'];

       
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../public/css/style.css">
  <title>Admin Login - HobbyHub</title>
</head>
<body>
<div class="wrap">
  <section class="card auth-container">
    <div class="auth-header">
      <h2>🔐 Admin Login</h2>
      <p>Accede a tu cuenta de administrador</p>
    </div>

    <?php if ($error): ?>
      <div class="alert-error">
        ⚠️ <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label for="username">Usuario</label>
        <input class="input" type="text" id="username" name="username" placeholder="Ingresa tu usuario" required autofocus>
      </div>
      <div class="form-group">
        <label for="password">Contraseña</label>
        <input class="input" type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
      </div>

      <div class="auth-actions">
        <button type="submit" class="btn primary">Entrar</button>
        <button type="reset" class="btn">Limpiar</button>
      </div>
    </form>

    <div class="divider">
      <span>¿Primera vez?</span>
    </div>

    <div class="auth-link">
      <a href="register.php">📝 Crear una nueva cuenta</a>
    </div>

    <div class="back-link">
      <a href="../">← Volver al inicio</a>
    </div>
  </section>
</div>
</body>
</html>