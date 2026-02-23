<?php
// BasePath para que funcione con /src (ej: /HobbyHub/src)
$basePath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($basePath === '') $basePath = '/';

// Valor actual de q
$currentQ = $_GET['q'] ?? '';
?>

<aside class="card">

  <h3 style="margin:0;">Buscar</h3>

  <div class="searchWrap" aria-label="Buscador">
    <!-- BÚSQUEDA NORMAL (fallback) -->
    <form action="<?= htmlspecialchars($basePath) ?>/pages/search.php" method="GET" id="searchForm">
      <input
        id="searchInput"
        class="input"
        type="search"
        name="q"
        placeholder="Buscar: pasta, viajes, rutina..."
        required
        minlength="2"
        autocomplete="off"
        value="<?= htmlspecialchars($currentQ) ?>"
      >
    </form>

    <p class="hint">
      Busca por categoría o palabras clave.
    </p>

    <!-- RESULTADOS AJAX -->
    <div id="ajaxResults" style="margin-top:10px;"></div>
    <p id="ajaxMsg" class="small" style="margin-top:8px;"></p>
  </div>

  <div class="sep"></div>

  <h3 style="margin:0;">Categorías</h3>
  <div class="list">
    <a class="btn" href="<?= htmlspecialchars($basePath) ?>/pages/category.php?cat=cocina">🍳 Cocina</a>
    <a class="btn" href="<?= htmlspecialchars($basePath) ?>/pages/category.php?cat=viajes">✈️ Viajes</a>
    <a class="btn" href="<?= htmlspecialchars($basePath) ?>/pages/category.php?cat=gaming">🎮 Gaming</a>
  </div>

</aside>

<script>
  // ===== AJAX LIVE SEARCH =====
  const BASE = <?= json_encode($basePath) ?>;
  const input = document.getElementById("searchInput");
  const results = document.getElementById("ajaxResults");
  const msg = document.getElementById("ajaxMsg");

  let timer = null;

  function escapeHTML(text){
    return String(text)
      .replaceAll("&","&amp;")
      .replaceAll("<","&lt;")
      .replaceAll(">","&gt;")
      .replaceAll('"',"&quot;")
      .replaceAll("'","&#039;");
  }

  function render(posts){
    results.innerHTML = "";

    if (!posts || posts.length === 0){
      msg.textContent = "Sin resultados (AJAX).";
      return;
    }

    msg.textContent = `Resultados (AJAX): ${posts.length}`;

    results.innerHTML = posts.map(p => `
      <a class="btn" style="display:block;margin-top:6px"
         href="${BASE}/pages/post.php?id=${p.id}">
        ${escapeHTML(p.category_label)} — ${escapeHTML(p.title)}
        <div style="font-size:12px; opacity:.75; margin-top:4px;">
          ${escapeHTML(p.excerpt)}
        </div>
      </a>
    `).join("");
  }

  async function ajaxSearch(q){
    const res = await fetch(`${BASE}/api/search_ajax.php?q=${encodeURIComponent(q)}`, {
      headers: { "Accept": "application/json" }
    });
    if (!res.ok){
      msg.textContent = "Error en búsqueda AJAX.";
      return;
    }
    const data = await res.json();
    render(data.posts);
  }

  input.addEventListener("input", () => {
    clearTimeout(timer);
    const q = input.value.trim();

    if (q.length < 2){
      results.innerHTML = "";
      msg.textContent = "";
      return;
    }

    timer = setTimeout(() => ajaxSearch(q), 300);
  });

  // Si quieres que al cargar la página con ?q=... haga AJAX automático:
  // if (input.value.trim().length >= 2) ajaxSearch(input.value.trim());
</script>
