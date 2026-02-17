<?php
http_response_code(405);
require_once "../includes/header.php";
?>

<main>
<section class="card" style="text-align:center;">
  <h1>405</h1>
  <p class="small">Método HTTP no permitido.</p>
  <a class="btn" href="/index.php">Inicio</a>
</section>
</main>

<?php require_once "../includes/footer.php"; ?>
