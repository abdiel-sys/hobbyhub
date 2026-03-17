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
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/style.css">
    <title>Perfil de Usuario - HobbyHub</title>
    <style>
        .profile-card {
            max-width: 500px;
            margin: 40px auto;
        }

        .profile-info {
            display: grid;
            gap: 15px;
        }

        .info-row {
            padding: 10px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.9em;
        }

        .info-value {
            margin-top: 5px;
            font-size: 1.1em;
        }

        .form-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid var(--border-color);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.show {
            display: block;
        }

        .alert.success {
            background-color: rgba(76, 175, 80, 0.1);
            border-left: 4px solid #4CAF50;
            color: #2e7d32;
        }

        .alert.error {
            background-color: rgba(244, 67, 54, 0.1);
            border-left: 4px solid #f44336;
            color: #c62828;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <section class="card profile-card">
            <h2>👤 Mi Perfil</h2>

            <div id="alertMessage" class="alert"></div>

            <div class="profile-info">
                <div class="info-row">
                    <div class="info-label">ID de Usuario</div>
                    <div class="info-value" id="userId">
                        <?php echo htmlspecialchars(getUser()['id'] ?? 'N/A'); ?>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Usuario</div>
                    <div class="info-value" id="userUsername">
                        <?php echo htmlspecialchars(getUser()['username'] ?? 'N/A'); ?>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Correo Electrónico</div>
                    <div class="info-value" id="userEmail">
                        <?php echo htmlspecialchars(getUser()['email'] ?? 'N/A'); ?>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Miembro desde</div>
                    <div class="info-value" id="userCreated">
                        <?php echo htmlspecialchars(getUser()['created_at'] ?? 'N/A'); ?>
                    </div>
                </div>
            </div>

            <div class="form-section">
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
                        <small>Solo letras, números, guiones y guiones bajos</small>
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
            </div>

            <div style="margin-top: 30px; text-align: center;">
                <a href="logout.php" class="btn">🚪 Cerrar Sesión</a>
            </div>
        </section>
    </div>

    <script>
        const form = document.getElementById('updateUserForm');
        const alertMessage = document.getElementById('alertMessage');
        const btnUpdate = document.getElementById('btnUpdate');

        /**
         * Muestra un mensaje de alerta
         */
        function showAlert(message, type = 'success') {
            alertMessage.textContent = message;
            alertMessage.className = `alert show ${type}`;
            setTimeout(() => {
                alertMessage.classList.remove('show');
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
</body>

</html>
