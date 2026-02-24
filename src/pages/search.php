<?php
require_once "../config/database.php";
require_once "../includes/header.php";

$breadcrumbs = [
  ['label' => 'Inicio', 'url' => '/index.php'],
  ['label' => 'Buscar'],
];
$q = trim($_GET['q'] ?? '');
$breadcrumbs[1]['label'] = 'Buscar: "' . $q . '"';


$search = "%{$q}%";

$stmt = $pdo->prepare("
  SELECT * FROM posts
  WHERE title LIKE :q1
     OR content LIKE :q2
     OR tags LIKE :q3
     OR category LIKE :q4
  ORDER BY created_at DESC
");

$stmt->execute([
  ':q1' => $search,
  ':q2' => $search,
  ':q3' => $search,
  ':q4' => $search
]);

$posts = $stmt->fetchAll();
echo $q;
?>
<?php require_once "../includes/breadcrumbs.php"; ?>
<main>
  <section class="card">
    <h2>Resultados para: "<?= htmlspecialchars($q) ?>"</h2>

    <?php if (empty($posts)): ?>
      <div class="resultsBar">
        <span>No se encontraron resultados.</span>
      </div>
    <?php endif; ?>

    <?php foreach ($posts as $post): ?>
      <article class="post">
        <div class="meta">
          <span class="badge"><?= ucfirst($post['category']) ?></span>
          <span>⏱ <?= $post['read_time'] ?> min</span>
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

