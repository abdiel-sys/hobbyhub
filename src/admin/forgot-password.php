<?php
require_once "../config/database.php";
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $email = $_POST['email'] ?? '';

  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if ($user) {

    $token = bin2hex(random_bytes(32));
    $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $stmt = $pdo->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE email=?");
    $stmt->execute([$token, $expires, $email]);

    $appUrl = rtrim(getenv('APP_URL') ?: "http://localhost:8080", '/');
    $resetLink = "$appUrl/admin/reset-password.php?token=$token";

    $mail = new PHPMailer(true);

    try {

      $mail->isSMTP();
      $mail->Host = getenv('MAIL_HOST');
      $mail->SMTPAuth = true;
      $mail->Username = getenv('MAIL_USERNAME');
      $mail->Password = getenv('MAIL_PASSWORD');
      $mail->SMTPSecure = 'tls';
      $mail->Port = getenv('MAIL_PORT');

      $mail->setFrom(getenv('MAIL_FROM') ?: 'no-reply@example.com', getenv('MAIL_FROM_NAME') ?: 'HobbyHub');
      $mail->addAddress($email);

      $mail->isHTML(true);
      $mail->Subject = 'Recuperar contraseña';

      $mail->Body = "
      <h3>Recuperar contraseña</h3>
      <p>Haz clic en el siguiente enlace para cambiar tu contraseña:</p>
      <a href='$resetLink'>$resetLink</a>
      <p>Este enlace expirará en 1 hora.</p>
      ";

      $mail->send();

      $message = "Revisa tu correo para recuperar tu contraseña.";

    } catch (Exception $e) {
      $message = "Error enviando correo.";
    }

  } else {
    $message = "No existe una cuenta con ese correo.";
  }

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
  <h2>🔑 Recuperar contraseña</h2>
  <p>Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña</p>
</div>

<?php if (!empty($message)): ?>
  <div class="alert-success">
    <?= htmlspecialchars($message) ?>
  </div>
<?php endif; ?>

<form method="POST">

  <div class="form-group">
    <label for="email">Correo electrónico</label>
    <input
      class="input"
      type="email"
      id="email"
      name="email"
      placeholder="usuario@email.com"
      required
      autocomplete="email">
  </div>

  <div class="auth-actions">
    <button type="submit" class="btn primary">
      Enviar enlace de recuperación
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

