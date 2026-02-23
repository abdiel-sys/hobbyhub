<?php
require_once "../config/database.php";

header("Content-Type: application/json; charset=UTF-8");

$q = trim($_GET['q'] ?? '');
$cat = trim($_GET['cat'] ?? ''); // opcional

if ($q === '' || mb_strlen($q, 'UTF-8') < 2) {
  echo json_encode(["count" => 0, "posts" => []]);
  exit;
}

// Filtro opcional por categoría
$allowed = ['cocina', 'viajes', 'gaming'];
$useCat = ($cat !== '' && in_array($cat, $allowed, true));

$sql = "
  SELECT id, category, title, content, tags, read_time, created_at
  FROM posts
  WHERE (title LIKE :q OR content LIKE :q OR tags LIKE :q OR category LIKE :q)
";
if ($useCat) $sql .= " AND category = :cat ";
$sql .= " ORDER BY created_at DESC LIMIT 10";

$stmt = $pdo->prepare($sql);

$params = [':q' => "%$q%"];
if ($useCat) $params[':cat'] = $cat;

$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

function excerpt($text, $limit = 120) {
  $clean = strip_tags($text);
  if (mb_strlen($clean, 'UTF-8') <= $limit) return $clean;
  return mb_substr($clean, 0, $limit, 'UTF-8') . '...';
}

$posts = array_map(function($p){
  return [
    "id" => (int)$p["id"],
    "category" => $p["category"],
    "category_label" => ucfirst($p["category"]),
    "title" => $p["title"],
    "excerpt" => excerpt($p["content"], 120),
    "read_time" => (int)$p["read_time"],
    "created_at" => $p["created_at"]
  ];
}, $rows);

echo json_encode([
  "count" => count($posts),
  "posts" => $posts
]);