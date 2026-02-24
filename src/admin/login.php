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
  <style>
    .login-container {
      max-width: 400px;
      margin: 60px auto;
    }

    .login-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .login-header h2 {
      margin: 0 0 10px 0;
      font-size: 28px;
    }

    .login-header p {
      margin: 0;
      color: #666;
      font-size: 14px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
      box-sizing: border-box;
    }

    .form-group input:focus {
      outline: none;
      border-color: #007bff;
      box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
    }

    .error-message {
      background-color: #f8d7da;
      color: #721c24;
      padding: 12px;
      border-radius: 4px;
      margin-bottom: 20px;
      font-size: 14px;
      border: 1px solid #f5c6cb;
    }

    .login-actions {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }

    .login-actions button {
      flex: 1;
    }

    .divider {
      text-align: center;
      margin: 20px 0;
      position: relative;
    }

    .divider::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 1px;
      background: #ddd;
    }

    .divider span {
      background: white;
      padding: 0 10px;
      color: #999;
      font-size: 12px;
      position: relative;
    }

    .register-link {
      text-align: center;
    }

    .register-link a {
      display: block;
      padding: 12px;
      background-color: #f8f9fa;
      border: 1px solid #ddd;
      border-radius: 4px;
      color: #007bff;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .register-link a:hover {
      background-color: #e7f3ff;
      border-color: #007bff;
    }

    .back-home {
      text-align: center;
      margin-top: 20px;
    }

    .back-home a {
      color: #666;
      text-decoration: none;
      font-size: 13px;
    }

    .back-home a:hover {
      color: #007bff;
      text-decoration: underline;
    }
  </style>
</head>
<body>
<div class="wrap">
  <section class="card login-container">
    <div class="login-header">
      <h2>🔐 Admin Login</h2>
      <p>Accede a tu cuenta de administrador</p>
    </div>

    <?php if ($error): ?>
      <div class="error-message">
        ⚠️ <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <input class="input" type="text" name="username" placeholder="Usuario" required autofocus>
      </div>
      <div class="form-group">
        <input class="input" type="password" name="password" placeholder="Contraseña" required>
      </div>

      <div class="login-actions">
        <button type="submit" class="btn primary">Entrar</button>
        <button type="reset" class="btn">Limpiar</button>
      </div>
    </form>

    <div class="divider">
      <span>¿Primera vez?</span>
    </div>

    <div class="register-link">
      <a href="register.php">📝 Crear una nueva cuenta</a>
    </div>

    <div class="back-home">
      <a href="../">← Volver al inicio</a>
    </div>
  </section>
</div>
</body>
</html>