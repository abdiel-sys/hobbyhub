<?php

require_once "config/database.php";

// posts
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 3");
$posts = $stmt->fetchAll();


$breadcrumbs = [
  ['label' => 'Inicio']
];
require_once "includes/breadcrumbs.php";
?>

<main>
<section class="card">
  <h2>Últimas publicaciones</h2>

  <?php foreach ($posts as $post): ?>
    <article class="post">
      <div class="meta">
        <span class="badge">
          <?= ucfirst(htmlspecialchars($post['category'])) ?>
        </span>
        <span>⏱ <?= (int)$post['read_time'] ?> min</span>
        <span>📅 <?= htmlspecialchars($post['created_at']) ?></span>
      </div>

      <h3><?= htmlspecialchars($post['title']) ?></h3>
      <p><?= htmlspecialchars(substr($post['content'], 0, 120)) ?>...</p>


      <a class="btn primary"
         href="post.php?id=<?= (int)$post['id'] ?>">
         Leer artículo
      </a>
    </article>
  <?php endforeach; ?>
</section>

<?php include "includes/sidebar.php"; ?>
</main>
