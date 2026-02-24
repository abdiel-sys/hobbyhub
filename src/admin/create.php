<?php
require_once "auth.php";
require_once "../config/database.php";

function isAjaxRequest(): bool {
  return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')
      || (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  try {
    $stmt = $pdo->prepare("
      INSERT INTO posts (title, content, category, read_time, created_at, tags)
      VALUES (?, ?, ?, ?, CURDATE(), ?)
    ");

    $stmt->execute([
      $_POST['title'] ?? '',
      $_POST['content'] ?? '',
      $_POST['category'] ?? '',
      $_POST['read_time'] ?? '',
      $_POST['tags'] ?? ''
    ]);

    // Si es AJAX, responde JSON
    if (isAjaxRequest()) {
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode(["ok" => true, "id" => $pdo->lastInsertId()]);
      exit;
    }

    // Si NO es AJAX, se comporta como antes
    header("Location: dashboard.php");
    exit;

  } catch (Throwable $e) {
    if (isAjaxRequest()) {
      header("Content-Type: application/json; charset=utf-8");
      http_response_code(500);
      echo json_encode(["ok" => false, "error" => "Error al crear post"]);
      exit;
    }
    die("Error al crear post");
  }
}
?>

<?php require_once "../includes/header.php"; ?>

<section class="card">
  <h2>Nuevo post</h2>

  <form id="formCreate" method="POST">
    <input class="input" name="title" placeholder="Título" required><br><br>

    <textarea class="input" name="content" rows="6" placeholder="Contenido" required minlength="5"></textarea><br><br>

    <select class="input" name="category">
      <option value="cocina">Cocina</option>
      <option value="viajes">Viajes</option>
      <option value="gaming">Gaming</option>
    </select><br><br>

    <input class="input" name="read_time" placeholder="Tiempo de lectura (min)" required><br><br>

    <input class="input" name="tags" placeholder="Tags (coma separadas)" required><br><br>

    <button class="btn primary" type="submit">Guardar</button>
  </form>
</section>

<script>
const formCreate = document.getElementById("formCreate");

formCreate.addEventListener("submit", async (e) => {
  e.preventDefault();

  const fd = new FormData(formCreate);

  const res = await fetch("/admin/create.php", {
    method: "POST",
    body: fd,
    headers: {
      "X-Requested-With": "XMLHttpRequest",
      "Accept": "application/json"
    }
  });

  const data = await res.json().catch(() => ({}));

  if (!res.ok || data.ok === false) {
    alert(data.error || "Error al crear");
    return;
  }

  // Se creó por AJAX y ahora redirigimos manualmente
  window.location.href = "/admin/dashboard.php";
});
</script>

<?php require_once "../includes/footer.php"; ?>