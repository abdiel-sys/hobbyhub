<?php
require_once "../admin/auth.php";
require_once "../config/database.php";

header("Content-Type: application/json; charset=utf-8");

function json_ok($data = []) {
  echo json_encode(array_merge(["ok" => true], $data));
  exit;
}

function json_error($message, $code = 400) {
  http_response_code($code);
  echo json_encode(["ok" => false, "error" => $message]);
  exit;
}

$action = $_GET["action"] ?? "";

try {
  if ($_SERVER["REQUEST_METHOD"] === "GET" && $action === "list") {
    $posts = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC, id DESC")->fetchAll();
    json_ok(["data" => $posts]);
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST" && $action === "create") {
    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $category = $_POST["category"] ?? "";
    $read_time = $_POST["read_time"] ?? "";
    $tags = trim($_POST["tags"] ?? "");

    if ($title === "" || $content === "" || $category === "") {
      json_error("Datos incompletos", 422);
    }

    $stmt = $pdo->prepare("
      INSERT INTO posts (title, content, category, read_time, created_at, tags)
      VALUES (?, ?, ?, ?, CURDATE(), ?)
    ");

    $stmt->execute([$title, $content, $category, $read_time, $tags]);

    json_ok(["id" => $pdo->lastInsertId()]);
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST" && $action === "update") {
    $id = $_POST["id"] ?? "";
    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $category = $_POST["category"] ?? "";
    $read_time = $_POST["read_time"] ?? "";
    $tags = trim($_POST["tags"] ?? "");

    if ($id === "" || !is_numeric($id)) json_error("ID inválido", 422);
    if ($title === "" || $content === "" || $category === "") json_error("Datos incompletos", 422);

    $stmt = $pdo->prepare("
      UPDATE posts
      SET title = ?, content = ?, category = ?, read_time = ?, tags = ?
      WHERE id = ?
    ");
    $stmt->execute([$title, $content, $category, $read_time, $tags, $id]);

    json_ok();
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST" && $action === "delete") {
    $id = $_POST["id"] ?? "";
    if ($id === "" || !is_numeric($id)) json_error("ID inválido", 422);

    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);

    json_ok();
  }

  json_error("Acción inválida", 400);

} catch (Throwable $e) {
  json_error("Error del servidor", 500);
}