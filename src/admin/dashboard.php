<?php
require_once "auth.php";
?>

<?php require_once "../includes/header.php"; ?>

<main>
  <section class="card">
    <h2>Admin — Posts</h2>

    <button class="btn primary" id="btnNew">+ Nuevo post</button>
    <a class="btn" href="logout.php">Cerrar sesión</a>

    <div class="sep"></div>

    <div id="postsWrap"></div>
  </section>
</main>

<!-- Modal simple -->
<div id="modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); padding:24px; overflow:auto;">
  <div class="card" style="max-width:720px; margin:40px auto;">
    <h2 id="modalTitle">Nuevo post</h2>

    <form id="postForm">
      <input type="hidden" name="id" id="postId">

      <label class="small">Título</label>
      <input class="input" name="title" id="title" required minlength="5"><br><br>

      <label class="small">Contenido</label>
      <textarea class="input" name="content" id="content" rows="8" required minlength="20"></textarea><br><br>

      <label class="small">Categoría</label>
      <select class="input" name="category" id="category" required>
        <option value="cocina">Cocina</option>
        <option value="viajes">Viajes</option>
        <option value="gaming">Gaming</option>
      </select><br><br>

      <label class="small">Tiempo de lectura (min)</label>
      <input class="input" type="number" name="read_time" id="read_time" required min="1"><br><br>

      <label class="small">Tags (coma separadas)</label>
      <input class="input" name="tags" id="tags" placeholder="pasta,recetas,rápidas"><br><br>

      <div class="actions">
        <button class="btn primary" type="submit" id="btnSave">Guardar</button>
        <button class="btn" type="button" id="btnCancel">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<script>
  // ✅ Desde /admin -> ../api
  const API_BASE = "../api/posts.php";

  const postsWrap = document.getElementById("postsWrap");
  const btnNew = document.getElementById("btnNew");

  const modal = document.getElementById("modal");
  const modalTitle = document.getElementById("modalTitle");
  const postForm = document.getElementById("postForm");

  const postId = document.getElementById("postId");
  const title = document.getElementById("title");
  const content = document.getElementById("content");
  const category = document.getElementById("category");
  const read_time = document.getElementById("read_time");
  const tags = document.getElementById("tags");

  document.getElementById("btnCancel").addEventListener("click", closeModal);

  function openModal(mode, post = null) {
    modal.style.display = "block";

    if (mode === "create") {
      modalTitle.textContent = "Nuevo post";
      postId.value = "";
      title.value = "";
      content.value = "";
      category.value = "cocina";
      read_time.value = "";
      tags.value = "";
    } else {
      modalTitle.textContent = "Editar post";
      postId.value = post.id;
      title.value = post.title ?? "";
      content.value = post.content ?? "";
      category.value = post.category ?? "cocina";
      read_time.value = post.read_time ?? "";
      tags.value = post.tags ?? "";
    }

    setTimeout(() => title.focus(), 50);
  }

  function closeModal() {
    modal.style.display = "none";
  }

  btnNew.addEventListener("click", () => openModal("create"));

  async function apiGet(action) {
    const res = await fetch(`${API_BASE}?action=${encodeURIComponent(action)}`, {
      headers: { "Accept": "application/json" }
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok || data.ok === false) throw new Error(data.error || "Error");
    return data;
  }

  async function apiPost(action, formData) {
    const res = await fetch(`${API_BASE}?action=${encodeURIComponent(action)}`, {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Accept": "application/json"
      }
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok || data.ok === false) throw new Error(data.error || "Error");
    return data;
  }

  function escapeHtml(str) {
    return String(str ?? "")
      .replaceAll("&","&amp;")
      .replaceAll("<","&lt;")
      .replaceAll(">","&gt;")
      .replaceAll('"',"&quot;")
      .replaceAll("'","&#039;");
  }

  function renderPosts(posts) {
    if (!posts || !posts.length) {
      postsWrap.innerHTML = `<p class="small">No hay posts todavía.</p>`;
      return;
    }

    postsWrap.innerHTML = posts.map(p => `
      <article class="post" data-id="${p.id}">
        <h3>${escapeHtml(p.title)}</h3>
        <p class="small">
          ${escapeHtml((p.category ?? "").charAt(0).toUpperCase() + (p.category ?? "").slice(1))}
          • ${escapeHtml(p.created_at)} • ${escapeHtml(p.read_time)} min
        </p>
        <p class="small">Tags: ${escapeHtml(p.tags || "-")}</p>

        <div class="actions">
          <button class="btn btn-edit" type="button">Editar</button>
          <button class="btn btn-delete" type="button">Eliminar</button>
        </div>

        <hr style="opacity:.2">
      </article>
    `).join("");

    postsWrap.querySelectorAll(".post").forEach(article => {
      const id = article.dataset.id;
      const post = posts.find(x => String(x.id) === String(id));

      article.querySelector(".btn-edit").addEventListener("click", () => {
        openModal("edit", post);
      });

      article.querySelector(".btn-delete").addEventListener("click", async () => {
        if (!confirm("¿Eliminar este post?")) return;
        try {
          const fd = new FormData();
          fd.append("id", id);
          await apiPost("delete", fd);
          await loadPosts();
        } catch (e) {
          alert(e.message);
        }
      });
    });
  }

  async function loadPosts() {
    const { data } = await apiGet("list");
    renderPosts(data);
  }

  // Guardar (create o update)
  postForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    try {
      const fd = new FormData(postForm);

      if (!postId.value) {
        await apiPost("create", fd);
      } else {
        await apiPost("update", fd);
      }

      closeModal();
      await loadPosts();
    } catch (e) {
      alert(e.message);
    }
  });

  // Cierra modal si das click fuera
  modal.addEventListener("click", (e) => {
    if (e.target === modal) closeModal();
  });

  // ESC para cerrar modal
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && modal.style.display === "block") closeModal();
  });

  loadPosts().catch(err => alert(err.message));
</script>

<?php require_once "../includes/footer.php"; ?>