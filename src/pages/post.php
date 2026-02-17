<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /errors/400.php");
    exit;
}
require_once "../config/database.php";
require_once "../includes/header.php";

$id = $_GET['id'] ?? null;



$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
  die("Post no encontrado");
}

$breadcrumbs = [
  ['label' => 'Inicio', 'url' => '/index.php'],
  [
    'label' => ucfirst($post['category']),
    'url' => '/pages/category.php?cat=' . $post['category']
  ],
  ['label' => $post['title']]
];
?>

<?php require_once "../includes/breadcrumbs.php"; ?>

<section class="card">
  <span class="badge"><?= ucfirst($post['category']) ?></span>
  <h2><?= htmlspecialchars($post['title']) ?></h2>
  <p><?= nl2br($post['content']) ?></p>

  <a class="btn" href="/index.php">← Volver</a>
</section>
<?php require_once "../includes/footer.php"; ?>