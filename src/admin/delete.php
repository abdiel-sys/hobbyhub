<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /errors/400.php");
    exit;
}
require_once "auth.php";
require_once "../config/database.php";

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$id]);

header("Location: dashboard.php");
exit;
