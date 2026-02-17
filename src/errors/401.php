<?php
http_response_code(401);
require_once "../includes/header.php";
?>

<main>
<section class="card" style="text-align:center;">
  <h1>401</h1>
  <p class="small">Debes iniciar sesión para acceder.</p>

  <div class="actions" style="justify-content:center;">
    <a class="btn primary" href="/admin/login.php">Iniciar sesión</a>
  </div>
</section>
</main>

<?php require_once "../includes/footer.php"; ?>
