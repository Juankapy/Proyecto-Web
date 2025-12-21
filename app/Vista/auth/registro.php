    <main>
        <h1>Crea tu cuenta</h1>
        <p style="text-align: center; margin-bottom: 20px; color: var(--dark-gray);">Únete a nuestra comunidad de música.</p>

        <div class="contenedor-autenticacion">
            <form action="index.php?action=auth_register" method="POST" onsubmit="return validarRegistro()">
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
                    <input type="password" id="password" name="password" placeholder="Introduce tu contraseña">
                    <i class="fa-regular fa-eye icono-derecha"></i>
                </div>

                <label for="confirm_password">Confirmar contraseña</label>
                <div class="grupo-entrada">
                    <i class="fa-solid fa-lock icono-izquierda"></i>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirma tu contraseña">
                    <i class="fa-regular fa-eye icono-derecha"></i>
                </div>

                <label for="tipo_usuario">Tipo de usuario</label>
                <div class="grupo-entrada">
                    <select id="tipo_usuario" name="tipo_usuario" style="padding-left: 1em;">
                        <option value="usuario_normal">Usuario Normal</option>
                        <option value="artista">Artista</option>
                    </select>
                </div>

                <button type="submit" class="boton-enviar">Registrarse</button>
            </form>
            
            <p style="font-size: 0.8em; text-align: center; margin-top: 15px; color: var(--dark-gray);">
                Al registrarte, aceptas nuestra <a href="#" style="color: var(--primary-purple);">Política de Privacidad</a> y <a href="#" style="color: var(--primary-purple);">Términos de Servicio</a>.
            </p>
        </div>
    </main>
