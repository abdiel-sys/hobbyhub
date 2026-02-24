<?php
require_once "../config/database.php";
session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../public/css/style.css">
  <title>Registro - HobbyHub</title>
  <style>
    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      font-size: 14px;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
    }

    .form-group input:focus {
      outline: none;
      border-color: #007bff;
      box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
    }

    .alert {
      padding: 12px;
      border-radius: 4px;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .alert-error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }

    .alert-hidden {
      display: none;
    }

    .button-group {
      display: flex;
      gap: 10px;
    }

    .button-group button {
      flex: 1;
    }

    .login-link {
      margin-top: 20px;
      text-align: center;
      font-size: 14px;
    }

    .login-link a {
      color: #007bff;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    .spinner {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 2px solid #f3f3f3;
      border-top: 2px solid #007bff;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-right: 8px;
      vertical-align: middle;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
<div class="wrap">
  <section class="card" style="max-width:450px;margin:50px auto;">
    <h2>📝 Crear Cuenta</h2>

    <div id="alertBox" class="alert alert-hidden"></div>

    <form id="registerForm" method="POST">
      <div class="form-group">
        <label for="username">Usuario</label>
        <input 
          class="input" 
          type="text"
          id="username" 
          name="username" 
          placeholder="Ej: mi_usuario"
          required
          minlength="3"
          pattern="[a-zA-Z0-9_-]{3,50}"
          title="Solo letras, números, guiones y guiones bajos"
        >
      </div>

      <div class="form-group">
        <label for="password">Contraseña</label>
        <input 
          class="input" 
          type="password" 
          id="password" 
          name="password" 
          placeholder="Mínimo 6 caracteres"
          required
          minlength="6"
        >
      </div>

      <div class="form-group">
        <label for="password_confirm">Confirmar Contraseña</label>
        <input 
          class="input" 
          type="password" 
          id="password_confirm" 
          name="password_confirm" 
          placeholder="Repite tu contraseña"
          required
          minlength="6"
        >
      </div>

      <div class="button-group">
        <button type="submit" class="btn primary" id="btnRegister">
          Registrarse
        </button>
        <button type="reset" class="btn">Limpiar</button>
      </div>
    </form>

    <div class="login-link">
      ¿Ya tienes cuenta? 
      <a href="login.php">Inicia sesión aquí</a>
    </div>
  </section>
</div>

<script>
const form = document.getElementById('registerForm');
const alertBox = document.getElementById('alertBox');
const btnRegister = document.getElementById('btnRegister');

form.addEventListener('submit', async (e) => {
  e.preventDefault();

  // Limpiar alerta
  alertBox.classList.add('alert-hidden');
  alertBox.className = 'alert alert-hidden';

  // Validar que las contraseñas coincidan
  const password = document.getElementById('password').value;
  const passwordConfirm = document.getElementById('password_confirm').value;

  if (password !== passwordConfirm) {
    showAlert('Las contraseñas no coinciden', 'error');
    return;
  }

  // Deshabilitar botón y mostrar spinner
  const originalText = btnRegister.innerHTML;
  btnRegister.disabled = true;
  btnRegister.innerHTML = '<span class="spinner"></span>Registrando...';

  const formData = new FormData(form);

  try {
    const response = await fetch('../api/register.php', {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    const data = await response.json();

    if (data.ok) {
      showAlert(data.message, 'success');
      form.reset();
      
      // Redirigir al login después de 2 segundos
      setTimeout(() => {
        window.location.href = 'login.php';
      }, 2000);
    } else {
      showAlert(data.error, 'error');
    }
  } catch (error) {
    console.error('Error:', error);
    showAlert('Error en la conexión. Intenta de nuevo.', 'error');
  } finally {
    // Restaurar botón
    btnRegister.disabled = false;
    btnRegister.innerHTML = originalText;
  }
});

function showAlert(message, type) {
  alertBox.textContent = message;
  alertBox.className = `alert alert-${type}`;
}
</script>
</body>
</html>
