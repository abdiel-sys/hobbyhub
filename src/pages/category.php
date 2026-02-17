<?php
require_once "../config/database.php";
require_once "../includes/header.php";

$category = $_GET['cat'] ?? '';

// Security: allow only valid categories
$allowed = ['cocina', 'viajes', 'gaming'];
if (!in_array($category, $allowed)) {
    die("Categoría no válida");
}

// Fetch posts by category
$stmt = $pdo->prepare("
  SELECT * FROM posts
  WHERE category = ?
  ORDER BY created_at DESC
");
$stmt->execute([$category]);
$posts = $stmt->fetchAll();

// Breadcrumbs
$breadcrumbs = [
  ['label' => 'Inicio', 'url' => '/index.php'],
  ['label' => ucfirst($category)]
];
?>

<?php require_once "../includes/breadcrumbs.php"; ?>

<main>
  <section class="card">
    <h2><?= ucfirst($category) ?></h2>

    <?php if (empty($posts)): ?>
      <p class="small">No hay publicaciones en esta categoría.</p>
    <?php endif; ?>

    <?php foreach ($posts as $post): ?>
      <article class="post">
        <div class="meta">
          <span class="badge"><?= ucfirst($post['category']) ?></span>
          <span>⏱ <?= $post['read_time'] ?> min</span>
          <span>📅 <?= $post['created_at'] ?></span>
        </div>

        <h3><?= htmlspecialchars($post['title']) ?></h3>
        <p><?= substr($post['content'], 0, 140) ?>...</p>

        <a class="btn primary"
           href="/pages/post.php?id=<?= $post['id'] ?>">
          Leer artículo
        </a>
      </article>
    <?php endforeach; ?>
  </section>
  <?php include "../includes/sidebar.php"; ?>
</main>

<?php require_once "../includes/footer.php"; ?>
