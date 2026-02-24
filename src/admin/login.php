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
  <link rel="stylesheet" href="../public/css/style.css">
  <title>Admin Login</title>
</head>
<body>
<div class="wrap">
  <section class="card" style="max-width:400px;margin:auto;">
    <h2>Admin Login</h2>

    <?php if ($error): ?>
      <p class="small"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
      <input class="input" name="username" placeholder="Usuario" required>
      <br><br>
      <input class="input" type="password" name="password" placeholder="Contraseña" required>
      <br><br>
      <button class="btn primary">Entrar</button>
    </form>
  </section>
</div>
</body>
</html>