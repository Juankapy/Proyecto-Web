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
                    <input type="password" id="password" name="password" placeholder="Introduce tu contraseña">
                    <i class="fa-regular fa-eye icono-derecha"></i>
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
