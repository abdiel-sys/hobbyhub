<?php
require_once "../config/database.php";

header("Content-Type: application/json; charset=UTF-8");


if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
) {
    http_response_code(403);
    echo json_encode([
        "ok" => false,
        "error" => "Acceso no permitido"
    ]);
    exit;
}

$action = $_GET['action'] ?? "";

try {


    if ($action === "list") {

        $stmt = $pdo->query("
            SELECT 
                id,
                title,
                content,
                category,
                read_time,
                tags,
                created_at
            FROM posts
            ORDER BY created_at DESC
        ");

        $posts = $stmt->fetchAll();

        echo json_encode([
            "ok" => true,
            "data" => $posts
        ]);
        exit;
    }

  
    if ($action === "create") {

        $title     = trim($_POST['title'] ?? "");
        $content   = trim($_POST['content'] ?? "");
        $category  = $_POST['category'] ?? "";
        $read_time = (int)($_POST['read_time'] ?? 0);
        $tags      = trim($_POST['tags'] ?? "");

        if (strlen($title) < 5 || strlen($content) < 20) {
            throw new Exception("Datos inválidos");
        }

        $stmt = $pdo->prepare("
            INSERT INTO posts (title, content, category, read_time, tags)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $title,
            $content,
            $category,
            $read_time,
            $tags
        ]);

        echo json_encode([
            "ok" => true,
            "id" => $pdo->lastInsertId()
        ]);
        exit;
    }

    
    if ($action === "update") {

        $id        = (int)($_POST['id'] ?? 0);
        $title     = trim($_POST['title'] ?? "");
        $content   = trim($_POST['content'] ?? "");
        $category  = $_POST['category'] ?? "";
        $read_time = (int)($_POST['read_time'] ?? 0);
        $tags      = trim($_POST['tags'] ?? "");

        if ($id <= 0) {
            throw new Exception("ID inválido");
        }

        $stmt = $pdo->prepare("
            UPDATE posts
            SET title = ?, content = ?, category = ?, read_time = ?, tags = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $title,
            $content,
            $category,
            $read_time,
            $tags,
            $id
        ]);

        echo json_encode([
            "ok" => true
        ]);
        exit;
    }

 
    if ($action === "delete") {

        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            throw new Exception("ID inválido");
        }

        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode([
            "ok" => true
        ]);
        exit;
    }


    throw new Exception("Acción no válida");

} catch (Exception $e) {

    http_response_code(400);
    echo json_encode([
        "ok" => false,
        "error" => $e->getMessage()
    ]);
}