<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

require_once "../config/database.php";

function json_ok($data = null) {
  echo json_encode(["ok" => true, "data" => $data], JSON_UNESCAPED_UNICODE);
  exit;
}

function json_err($msg, $code = 400) {
  http_response_code($code);
  echo json_encode(["ok" => false, "error" => $msg], JSON_UNESCAPED_UNICODE);
  exit;
}

// ✅ Proteger API: solo admin logueado
if (!isset($_SESSION['admin'])) {
  json_err("No autorizado", 401);
}

$action = $_GET["action"] ?? "";

try {
  if ($action === "list") {
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    json_ok($posts);
  }

  if ($action === "create") {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") json_err("Método no permitido", 405);

    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $category = $_POST["category"] ?? "cocina";
    $read_time = (int)($_POST["read_time"] ?? 0);
    $tags = trim($_POST["tags"] ?? "");

    if (strlen($title) < 5) json_err("Título muy corto");
    if (strlen($content) < 20) json_err("Contenido muy corto");
    if (!in_array($category, ["cocina", "viajes", "gaming"], true)) json_err("Categoría inválida");
    if ($read_time <= 0) json_err("Tiempo de lectura inválido");

    $stmt = $pdo->prepare("
      INSERT INTO posts (title, content, category, read_time, created_at, tags)
      VALUES (?, ?, ?, ?, CURDATE(), ?)
    ");
    $stmt->execute([$title, $content, $category, $read_time, $tags]);

    json_ok(["id" => $pdo->lastInsertId()]);
  }

  if ($action === "update") {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") json_err("Método no permitido", 405);

    $id = (int)($_POST["id"] ?? 0);
    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $category = $_POST["category"] ?? "cocina";
    $read_time = (int)($_POST["read_time"] ?? 0);
    $tags = trim($_POST["tags"] ?? "");

    if ($id <= 0) json_err("ID inválido");
    if (strlen($title) < 5) json_err("Título muy corto");
    if (strlen($content) < 20) json_err("Contenido muy corto");
    if (!in_array($category, ["cocina", "viajes", "gaming"], true)) json_err("Categoría inválida");
    if ($read_time <= 0) json_err("Tiempo de lectura inválido");

    $stmt = $pdo->prepare("
      UPDATE posts
      SET title = ?, content = ?, category = ?, read_time = ?, tags = ?
      WHERE id = ?
    ");
    $stmt->execute([$title, $content, $category, $read_time, $tags, $id]);

    json_ok(true);
  }

  if ($action === "delete") {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") json_err("Método no permitido", 405);

    $id = (int)($_POST["id"] ?? 0);
    if ($id <= 0) json_err("ID inválido");

    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);

    json_ok(true);
  }

  json_err("Acción inválida", 400);

} catch (PDOException $e) {
  json_err("Error BD: " . $e->getMessage(), 500);
}