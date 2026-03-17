<?php
/**
 * Página de ejemplo para mostrar uso de funciones de usuario
 * 
 * Esta página demuestra cómo:
 * 1. Obtener datos del usuario actual en sesión
 * 2. Mostrar información del usuario
 * 3. Actualizar datos del usuario
 */

require_once "auth.php";

$breadcrumbs = [
  ['label' => 'Admin', 'url' => 'dashboard.php'],
  ['label' => 'Mi Perfil']
];
require_once "../includes/breadcrumbs.php";
?>

<?php require_once "../includes/header.php"; ?>

<style>
  @media (max-width: 900px) {
    main {
      grid-template-columns: 1fr !important;
    }
  }
</style>

<main style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 18px; margin-top: 18px;">
    <!-- COLUMNA IZQUIERDA: INFORMACIÓN DEL USUARIO -->
    <aside class="card">
        <div style="text-align: center; padding: 12px 0;">
            <h2 style="margin: 0; font-size: 32px;">👤</h2>
        </div>

        <h3 style="margin: 16px 0 8px; text-align: center; color: var(--text);" id="userUsername">
            <?php echo htmlspecialchars(getUser()['username'] ?? 'N/A'); ?>
        </h3>
        
        <p style="margin: 0 0 24px; text-align: center; color: var(--muted); font-size: 12px;">
            ID: <strong id="userId"><?php echo htmlspecialchars(getUser()['id'] ?? 'N/A'); ?></strong>
        </p>

        <div style="border-top: 1px solid var(--line); padding-top: 18px;">
            <div style="margin-bottom: 18px;">
                <div style="color: var(--muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">📧 Email</div>
                <div style="font-size: 13px; word-break: break-all;" id="userEmail">
                    <?php echo htmlspecialchars(getUser()['email'] ?? 'N/A'); ?>
                </div>
            </div>

            <div>
                <div style="color: var(--muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">📅 Miembro desde</div>
                <div style="font-size: 13px;" id="userCreated">
                    <?php echo htmlspecialchars(getUser()['created_at'] ?? 'N/A'); ?>
                </div>
            </div>
        </div>

        <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--line);">
            <a href="logout.php" class="btn" style="width: 100%; justify-content: center;">🚪 Cerrar Sesión</a>
        </div>
    </aside>

    <!-- COLUMNA DERECHA: FORMULARIOS -->
    <aside>
        <div id="alertMessage" class="alert" style="display: none; margin-bottom: 18px;"></div>

        <!-- ACTUALIZAR DATOS -->
        <section class="card">
            <h3>✏️ Actualizar Datos</h3>

            <form id="updateUserForm">
                <div class="form-group">
                    <label for="newUsername">Nuevo Usuario</label>
                    <input
                        class="input"
                        type="text"
                        id="newUsername"
                        name="username"
                        placeholder="Dejar vacío para no cambiar"
                        minlength="3"
                        pattern="[a-zA-Z0-9_-]{3,50}">
                    <small style="color: var(--muted); font-size: 11px;">Solo letras, números, guiones y guiones bajos</small>
                </div>

                <div class="form-group">
                    <label for="newEmail">Nuevo Email</label>
                    <input
                        class="input"
                        type="email"
                        id="newEmail"
                        name="email"
                        placeholder="Dejar vacío para no cambiar">
                </div>

                <div class="actions">
                    <button type="submit" class="btn primary" id="btnUpdate">Guardar Cambios</button>
                    <button type="reset" class="btn">Limpiar</button>
                </div>
            </form>
        </section>

        <!-- CAMBIAR CONTRASEÑA -->
        <section class="card" style="margin-top: 18px;">
            <h3>🔐 Cambiar Contraseña</h3>

            <form id="changePasswordForm">
        <div class="form-group">
            <label for="currentPassword">Contraseña Actual</label>
            <input
                class="input"
                type="password"
                id="currentPassword"
                name="current_password"
                required>
            <small style="color: var(--muted); font-size: 11px;">Debes ingresar tu contraseña actual para cambiarla</small>
        </div>

        <div class="form-group">
            <label for="newPassword">Nueva Contraseña</label>
            <input
                class="input"
                type="password"
                id="newPassword"
                name="new_password"
                minlength="8"
                required>
            <small style="color: var(--muted); font-size: 11px;">Mínimo 8 caracteres</small>
        </div>

        <div class="form-group">
            <label for="confirmPassword">Confirmar Nueva Contraseña</label>
            <input
                class="input"
                type="password"
                id="confirmPassword"
                name="confirm_password"
                minlength="8"
                required>
            <small style="color: var(--muted); font-size: 11px;">Debe coincidir con la nueva contraseña</small>
        </div>

        <div class="actions">
            <button type="submit" class="btn primary" id="btnChangePassword">Cambiar Contraseña</button>
            <button type="reset" class="btn">Limpiar</button>
        </div>
    </form>
        </section>
    </aside>
</main>

<?php require_once "../includes/footer.php"; ?>

<script>
    const form = document.getElementById('updateUserForm');
    const changePasswordForm = document.getElementById('changePasswordForm');
    const alertMessage = document.getElementById('alertMessage');
    const btnUpdate = document.getElementById('btnUpdate');
    const btnChangePassword = document.getElementById('btnChangePassword');

    /**
     * Muestra un mensaje de alerta
     */
    function showAlert(message, type = 'success') {
        alertMessage.textContent = message;
        alertMessage.className = `alert show ${type}`;
        alertMessage.style.display = 'block';
        
        if (type === 'success') {
            alertMessage.style.background = 'rgba(94, 240, 194, 0.08)';
            alertMessage.style.border = '1px solid rgba(94, 240, 194, 0.3)';
            alertMessage.style.color = '#5ef0c2';
        } else {
            alertMessage.style.background = 'rgba(255, 107, 107, 0.08)';
            alertMessage.style.border = '1px solid rgba(255, 107, 107, 0.3)';
            alertMessage.style.color = '#ff9999';
        }
        
        alertMessage.style.padding = '12px';
        alertMessage.style.borderRadius = '12px';
        alertMessage.style.marginBottom = '20px';
        alertMessage.style.fontSize = '13px';
        
        setTimeout(() => {
            alertMessage.style.display = 'none';
        }, 5000);
    }

    /**
     * Actualiza la información del usuario en la página
     */
    function updateUserDisplay(userData) {
        document.getElementById('userUsername').textContent = userData.username;
        document.getElementById('userEmail').textContent = userData.email;
    }

    /**
     * Manejador del formulario de actualización
     */
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const username = document.getElementById('newUsername').value.trim();
        const email = document.getElementById('newEmail').value.trim();

        // Validar que al menos un campo sea ingresado
        if (!username && !email) {
            showAlert('Ingresa al menos un campo para actualizar', 'error');
            return;
        }

        // Deshabilitar botón
        btnUpdate.disabled = true;
        const originalText = btnUpdate.innerHTML;
        btnUpdate.innerHTML = 'Guardando...';

        const formData = new FormData();
        if (username) formData.append('username', username);
        if (email) formData.append('email', email);

        try {
            const response = await fetch('../api/user.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.ok) {
                showAlert(data.message, 'success');
                updateUserDisplay(data.user);
                form.reset();
            } else {
                showAlert(data.error, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Error en la conexión. Intenta de nuevo.', 'error');
        } finally {
            // Restaurar botón
            btnUpdate.disabled = false;
            btnUpdate.innerHTML = originalText;
        }
    });

    /**
     * Manejador del formulario de cambio de contraseña
     */
    changePasswordForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        // Validar que las contraseñas coincidan
        if (newPassword !== confirmPassword) {
            showAlert('Las contraseñas no coinciden', 'error');
            return;
        }

        // Validar que la nueva contraseña sea diferente
        if (currentPassword === newPassword) {
            showAlert('La nueva contraseña debe ser diferente a la actual', 'error');
            return;
        }

        // Deshabilitar botón
        btnChangePassword.disabled = true;
        const originalText = btnChangePassword.innerHTML;
        btnChangePassword.innerHTML = 'Cambiando...';

        const formData = new FormData();
        formData.append('current_password', currentPassword);
        formData.append('new_password', newPassword);
        formData.append('action', 'change_password');

        try {
            const response = await fetch('../api/user.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.ok) {
                showAlert(data.message, 'success');
                changePasswordForm.reset();
            } else {
                showAlert(data.error, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Error en la conexión. Intenta de nuevo.', 'error');
        } finally {
            // Restaurar botón
            btnChangePassword.disabled = false;
            btnChangePassword.innerHTML = originalText;
        }
    });

    /**
     * Cargar datos del usuario al cargar la página
     */
    async function loadUserData() {
        try {
            const response = await fetch('../api/user.php', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.ok) {
                updateUserDisplay(data.user);
            }
        } catch (error) {
            console.error('Error loading user data:', error);
        }
    }

    // Cargar datos al iniciar
    loadUserData();
</script>
