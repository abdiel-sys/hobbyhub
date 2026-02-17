<?php
require_once "auth.php";
require_once "../config/database.php";

$posts = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll();
?>

<?php require_once "../includes/header.php"; ?>

<main>
<section class="card">
  <h2>Admin — Posts</h2>

  <a class="btn primary" href="/admin/create.php">+ Nuevo post</a>
  <a class="btn" href="/admin/logout.php">Cerrar sesión</a>

  <div class="sep"></div>

  <?php foreach ($posts as $post): ?>
    <article class="post">
      <h3><?= htmlspecialchars($post['title']) ?></h3>
      <p class="small"><?= ucfirst($post['category']) ?></p>

      <div class="actions">
        <a class="btn" href="/admin/edit.php?id=<?= $post['id'] ?>">Editar</a>
        <a class="btn" href="/admin/delete.php?id=<?= $post['id'] ?>"
           onclick="return confirm('¿Eliminar este post?')">
           Eliminar
        </a>
      </div>
    </article>
  <?php endforeach; ?>
</section>
</main>

<?php require_once "../includes/footer.php"; ?>
