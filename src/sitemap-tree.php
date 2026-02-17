<?php
require_once "config/database.php";

$stmt = $pdo->query("
  SELECT id, title, category
  FROM posts
  ORDER BY category, title
");

$schema = [];
while ($row = $stmt->fetch()) {
  $schema[$row['category']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Visual Sitemap</title>
<link rel="stylesheet" href="/public/css/style.css">
<style>

/* ===== SCHEMA LAYOUT ===== */
.schema {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 24px;
}

.node {
  padding: 12px 18px;
  border: 1px solid var(--line);
  border-radius: 12px;
  background: rgba(17,27,46,.8);
  font-weight: 600;
  cursor: pointer;
  text-align: center;
}

.node a {
  text-decoration: none;
}

.children {
  display: none;
  gap: 18px;
}

.children.active {
  display: flex;
}

.level {
  display: flex;
  gap: 30px;
  align-items: flex-start;
}

.column {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
}

/* lines */
.line-vertical {
  width: 2px;
  height: 20px;
  background: var(--line);
}

.line-horizontal {
  width: 40px;
  height: 2px;
  background: var(--line);
}

.post {
  font-weight: normal;
  font-size: 13px;
  opacity: .9;
}

</style>
</head>

<body>
<div class="wrap">

<section class="card">
<h1>🗺️ Sitemap (Schema View)</h1>
<p class="small">Click categories to expand</p>

<div class="schema">

  <!-- HOME -->
  <div class="node">
    <a href="/">🏠 Home</a>
  </div>

  <div class="line-vertical"></div>

  <!-- CATEGORIES -->
  <div class="level">

    <?php foreach ($schema as $category => $posts): ?>
    <div class="column">

      <div class="node" onclick="toggleSchema(this)">
        📁 <?= ucfirst($category) ?>
      </div>

      <div class="line-vertical"></div>

      <div class="children">
        <?php foreach ($posts as $post): ?>
          <div class="node post">
            <a href="/pages/post.php?id=<?= $post['id'] ?>">
              📝 <?= htmlspecialchars($post['title']) ?>
            </a>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
    <?php endforeach; ?>

  </div>
</div>
</section>

</div>

<script>
function toggleSchema(el) {
  const children = el.nextElementSibling.nextElementSibling;
  children.classList.toggle("active");
}
</script>

</body>
</html>
