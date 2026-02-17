<?php
http_response_code(400);
require_once "../includes/header.php";
?>

<main>
<section class="card" style="text-align:center;">
  <h1>400</h1>
  <p class="small">La solicitud es inválida o está incompleta.</p>
  <a class="btn primary" href="/index.php">Volver al inicio</a>
</section>
</main>

<?php require_once "../includes/footer.php"; ?>
