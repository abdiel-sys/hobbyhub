<?php
http_response_code(403);
require_once "../includes/header.php";
?>

<main>
  <section class="card" style="text-align:center;">
    <h1 style="font-size:48px;">403</h1>
    <p class="small">No tienes permiso para acceder a esta página.</p>

    <div class="actions" style="justify-content:center;">
      <a class="btn primary" href="/admin/login.php">🔐 Iniciar sesión</a>
      <a class="btn" href="/index.php">🏠 Volver al inicio</a>
    </div>
  </section>
</main>

<?php require_once "../includes/footer.php"; ?>
