<?php
http_response_code(404);
require_once "../includes/header.php";
?>

<main>
  <section class="card" style="text-align:center;">
    <h1 style="font-size:48px;">404</h1>
    <p class="small">La página que buscas no existe o fue movida.</p>

    <div class="actions" style="justify-content:center;">
      <a class="btn primary" href="/">🏠 Volver al inicio</a>
      <a class="btn" href="/sitemap-tree.php">🗺️ Mapa del sitio</a>
    </div>
  </section>
</main>

<?php require_once "../includes/footer.php"; ?>
