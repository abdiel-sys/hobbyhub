<?php
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /errors/400.php");
    exit;
}
require_once "auth.php";
require_once "../config/database.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID no proporcionado");
}

/* Fetch existing post */
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post no encontrado");
}

/* Handle form submission */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title     = $_POST['title'];
    $content   = $_POST['content'];
    $category  = $_POST['category'];
    $read_time = $_POST['read_time'];
    $tags      = $_POST['tags'];

    $update = $pdo->prepare("
        UPDATE posts
        SET title = ?, content = ?, category = ?, read_time = ?, tags = ?
        WHERE id = ?
    ");

    $update->execute([
        $title,
        $content,
        $category,
        $read_time,
        $tags,
        $id
    ]);

    header("Location: dashboard.php");
    exit;
}
?>

<?php require_once "../includes/header.php"; ?>

<main>
<section class="card">
  <h2>Editar post</h2>

  <form method="POST">

    <label class="small">Título</label>
    <input
      class="input"
      type="text"
      name="title"
      required
      value="<?= htmlspecialchars($post['title']) ?>"
      minlength="5"
    >
    <br><br>

    <label class="small">Contenido</label>
    <textarea
      class="input"
      name="content"
      rows="8"
      required
      minlength="20"
    ><?= htmlspecialchars($post['content']) ?></textarea>
    <br><br>

    <label class="small">Categoría</label>
    <select class="input" name="category">
      <option value="cocina" <?= $post['category'] === 'cocina' ? 'selected' : '' ?>>
        Cocina
      </option>
      <option value="viajes" <?= $post['category'] === 'viajes' ? 'selected' : '' ?>>
        Viajes
      </option>
      <option value="gaming" <?= $post['category'] === 'gaming' ? 'selected' : '' ?>>
        Gaming
      </option>
    </select>
    <br><br>

    <label class="small">Tiempo de lectura (min)</label>
    <input
      class="input"
      type="number"
      name="read_time"
      required
      value="<?= $post['read_time'] ?>"
    >
    <br><br>

    <label class="small">Tags (separadas por coma)</label>
    <input
      class="input"
      type="text"
      name="tags"
      value="<?= htmlspecialchars($post['tags']) ?>"
    >
    <br><br>

    <div class="actions">
      <button class="btn primary" type="submit">Guardar cambios</button>
      <a class="btn" href="dashboard.php">Cancelar</a>
    </div>

  </form>
</section>
</main>

<?php require_once "../includes/footer.php"; ?>
