    <main>
        <h1>Crea tu cuenta</h1>
        <p style="text-align: center; margin-bottom: 20px; color: var(--dark-gray);">Únete a nuestra comunidad de música.</p>

        <div class="contenedor-autenticacion">
            <form action="index.php?action=auth_register" method="POST" onsubmit="return validarRegistro()">
                <input type="hidden" name="tipo_usuario" value="usuario">
                <label for="nombre">Nombre de usuario</label>
                <div class="grupo-entrada">
                    <i class="fa-regular fa-user icono-izquierda"></i>
                    <input type="text" id="nombre" name="nombre" placeholder="Introduce tu nombre de usuario">
                </div>

                <label for="email">Correo electrónico</label>
                <div class="grupo-entrada">
                    <i class="fa-regular fa-envelope icono-izquierda"></i>
                    <input type="email" id="email" name="email" placeholder="Introduce tu correo electrónico">
                </div>

                <label for="password">Contraseña</label>
                <div class="grupo-entrada">
                    <i class="fa-solid fa-lock icono-izquierda"></i>
                    <div class="contenedor-input-icono">
                        <input type="password" id="password" name="password" placeholder="Introduce tu contraseña">
                        <i class="fa-solid fa-eye alternar-contrasena"></i>
                    </div>
                </div>

                <label for="confirm_password">Confirmar contraseña</label>
                <div class="grupo-entrada">
                    <i class="fa-solid fa-lock icono-izquierda"></i>
                    <div class="contenedor-input-icono">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirma tu contraseña">
                        <i class="fa-solid fa-eye alternar-contrasena"></i>
                    </div>
                </div>

                <button type="submit" class="boton-enviar">Registrarse</button>
                
                <div style="text-align: center; margin-top: 15px;">
                    <span style="color: var(--dark-gray); font-size: 0.9em;">¿Eres un artista?</span>
                    <a href="index.php?action=registro_artista" style="color: var(--primary-purple); font-weight: bold; font-size: 0.9em; text-decoration: none;">Crea tu cuenta de artista aquí</a>
                </div>
            </form>
            
            <p style="font-size: 0.8em; text-align: center; margin-top: 15px; color: var(--dark-gray);">
                Al registrarte, aceptas nuestra <a href="" style="color: var(--primary-purple);">Política de Privacidad</a> y <a href="#" style="color: var(--primary-purple);">Términos de Servicio</a>.
            </p>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);

            // --- ALERTAS DE ERROR ---
            if (urlParams.has('error')) {
                const errorType = urlParams.get('error');
                let title = 'Error';
                let text = 'Ha ocurrido un error inesperado.';

                switch(errorType) {
                    case 'empty_fields':
                        title = 'Campos Vacíos';
                        text = 'Por favor, rellena todos los campos del formulario.';
                        break;
                    case 'password_mismatch':
                        title = 'Contraseñas no coinciden';
                        text = 'Las contraseñas introducidas no son iguales.';
                        break;
                    case 'email_exists':
                        title = 'Correo ya registrado';
                        text = 'Este correo electrónico ya está en uso. Por favor, inicia sesión.';
                        break;
                    case 'stmtfailed':
                        title = 'Error del Sistema';
                        text = 'Hubo un fallo interno. Inténtalo de nuevo más tarde.';
                        break;
                    case 'db_connection':
                        title = 'Error de Conexión';
                        text = 'No se pudo conectar a la base de datos.';
                        break;
                }

                Swal.fire({
                    icon: 'error',
                    title: title,
                    text: text,
                    confirmButtonColor: '#d33'
                }).then(() => {
                    // Limpiar URL
                    window.history.replaceState(null, null, window.location.pathname + '?action=register');
                });
            }
        });

        function validarRegistro() {
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            if (pass !== confirm) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Las contraseñas no coinciden',
                    confirmButtonColor: '#d33'
                });
                return false;
            }
            return true;
        }
    </script>
