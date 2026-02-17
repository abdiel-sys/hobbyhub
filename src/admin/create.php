<?php
require_once "auth.php";
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("
      INSERT INTO posts (title, content, category, read_time, created_at, tags)
      VALUES (?, ?, ?, ?, CURDATE(), ?)
    ");
    

    $stmt->execute([
      $_POST['title'],
      $_POST['content'],
      $_POST['category'],
      $_POST['read_time'],
      $_POST['tags']
    ]);

    header("Location: dashboard.php");
    exit;
}
?>

<?php require_once "../includes/header.php"; ?>

<section class="card">
<h2>Nuevo post</h2>

<form method="POST">
  <input class="input" name="title" placeholder="Título" required><br><br>

  <textarea class="input" name="content" rows="6" placeholder="Contenido" required minlength="5"></textarea><br><br>

  <select class="input" name="category">
    <option value="cocina">Cocina</option>
    <option value="viajes">Viajes</option>
    <option value="gaming">Gaming</option>
  </select><br><br>

  <input class="input" name="read_time" placeholder="Tiempo de lectura (min)" required minlength="20"><br><br>

  <input class="input" name="tags" placeholder="Tags (coma separadas)" required><br><br>

  <button class="btn primary">Guardar</button>
</form>
</section>

<?php require_once "../includes/footer.php"; ?>
