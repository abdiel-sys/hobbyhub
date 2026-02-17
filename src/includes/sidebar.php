<aside class="card">

  <h3 style="margin:0;">Buscar</h3>

  <div class="searchWrap" aria-label="Buscador">
    <form action="/pages/search.php" method="GET">
      <input
        class="input"
        type="search"
        name="q"
        placeholder="Buscar: pasta, viajes, rutina..."
        required
        value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
      >
    </form>

    <p class="hint">
      Busca por categoría o palabras clave.
    </p>
  </div>

  <div class="sep"></div>

  <h3 style="margin:0;">Categorías</h3>
  <div class="list">
    <a class="btn" href="/pages/category.php?cat=cocina">🍳 Cocina</a>
    <a class="btn" href="/pages/category.php?cat=viajes">✈️ Viajes</a>
    <a class="btn" href="/pages/category.php?cat=gaming">🎮 Gaming</a>
  </div>

</aside>
