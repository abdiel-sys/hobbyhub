<?php
require_once "auth.php";
require_once "../config/database.php";

function isAjaxRequest(): bool {
  return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')
      || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
}

$id = null;


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = $_POST["id"] ?? null;
} else {
  $id = $_GET["id"] ?? null;
}

if (!$id || !is_numeric($id)) {
  if (isAjaxRequest()) {
    header("Content-Type: application/json; charset=utf-8");
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => "ID inválido"]);
    exit;
  }
  header("Location: /errors/400.php");
  exit;
}

$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$id]);

if (isAjaxRequest()) {
  header("Content-Type: application/json; charset=utf-8");
  echo json_encode(["ok" => true]);
  exit;
}

header("Location: dashboard.php");
exit;