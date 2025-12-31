    <main>
        <h1>Inicia Sesión</h1>

        <div class="contenedor-autenticacion">
            <form action="index.php?action=auth_login" method="POST" onsubmit="return validarLogin()">
                <label for="email">Correo Electrónico</label>
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

                <a href="#" class="olvido-contrasena">¿Olvidaste tu contraseña?</a>

                <button type="submit" class="boton-enviar">Iniciar Sesión</button>

                <div class="separador">
                    <span>O inicia sesión con</span>
                </div>

                <div class="inicio-social">
                    <button type="button" class="boton-social"><i class="fa-brands fa-google"></i></button>
                    <button type="button" class="boton-social"><i class="fa-brands fa-x-twitter"></i></button>
                </div>
            </form>
        </div>

        <p class="enlace-registro">
            ¿Aún no tienes una cuenta? <a href="index.php?action=register">Regístrate aquí</a>
        </p>
    </main>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            
            // --- ALERTAS DE ÉXITO ---
            if (urlParams.has('success')) {
                const successType = urlParams.get('success');
                
                if (successType === 'registered') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cuenta creada!',
                        text: 'Te has registrado correctamente. Por favor, inicia sesión.',
                        confirmButtonColor: '#6F00D0'
                    });
                } else if (successType === 'artist_registered') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Bienvenido Artista!',
                        text: 'Tu perfil de artista ha sido creado. Inicia sesión para empezar.',
                        confirmButtonColor: '#6F00D0'
                    });
                } else if (successType === 'logout') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'success',
                        title: 'Has cerrado sesión correctamente'
                    });
                }
                
                // Limpiar URL
                window.history.replaceState(null, null, window.location.pathname + '?action=login');
            }

            // --- ALERTAS DE ERROR ---
            if (urlParams.has('error')) {
                const errorType = urlParams.get('error');
                let title = 'Error';
                let text = 'Ha ocurrido un error inesperado.';

                switch(errorType) {
                    case 'wrong_password':
                        title = 'Contraseña Incorrecta';
                        text = 'La contraseña que ingresaste no es válida.';
                        break;
                    case 'user_not_found':
                        title = 'Usuario no encontrado';
                        text = 'No existe una cuenta con ese correo electrónico.';
                        break;
                    case 'empty_fields':
                        title = 'Campos Vacíos';
                        text = 'Por favor, rellena todos los campos.';
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
                    window.history.replaceState(null, null, window.location.pathname + '?action=login');
                });
            }
        });

        function validarLogin() {
            // Validación front-end adicional si se desea
            return true;
        }
    </script>
