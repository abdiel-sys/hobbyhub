<?php
require_once "config/database.php";

header("Content-Type: application/xml; charset=UTF-8");

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

  <!-- Home -->
  <url>
    <loc>http://localhost/</loc>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>

  <!-- Categories -->
  <?php
  $categories = ['cocina', 'viajes', 'gaming'];
  foreach ($categories as $cat):
  ?>
  <url>
    <loc>http://localhost/pages/category.php?cat=<?= $cat ?></loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <?php endforeach; ?>

  <!-- Posts -->
  <?php
  $stmt = $pdo->query("SELECT id, created_at FROM posts");
  while ($post = $stmt->fetch()):
  ?>
  <url>
    <loc>http://localhost/pages/post.php?id=<?= $post['id'] ?></loc>
    <lastmod><?= date('Y-m-d', strtotime($post['created_at'])) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
  </url>
  <?php endwhile; ?>

</urlset>
