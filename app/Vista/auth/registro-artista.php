<?php
?>
    <main>
        <h1>Crea tu cuenta de Artista</h1>
        <p style="text-align: center; margin-bottom: 20px; color: var(--dark-gray);">Comparte tu música con el mundo.</p>

        <div class="contenedor-autenticacion">
            <form action="index.php?action=auth_register_artist" method="POST" onsubmit="return validarRegistroArtista()">
                <input type="hidden" name="tipo_usuario" value="artista">

                <label for="nombre_artistico">Nombre Artístico</label>
                <div class="grupo-entrada">
                    <i class="fa-solid fa-microphone icono-izquierda"></i>
                    <input type="text" id="nombre_artistico" name="nombre_artistico" placeholder="Tu nombre artístico" required>
                </div>

                <label for="nombre">Nombre de usuario (Login)</label>
                <div class="grupo-entrada">
                    <i class="fa-regular fa-user icono-izquierda"></i>
                    <input type="text" id="nombre" name="nombre" placeholder="Usuario para iniciar sesión" required>
                </div>

                <label for="email">Correo electrónico</label>
                <div class="grupo-entrada">
                    <i class="fa-regular fa-envelope icono-izquierda"></i>
                    <input type="email" id="email" name="email" placeholder="Introduce tu correo electrónico" required>
                </div>

                <label for="password">Contraseña</label>
                <div class="grupo-entrada">
                    <i class="fa-solid fa-lock icono-izquierda"></i>
                    <input type="password" id="password" name="password" placeholder="Introduce tu contraseña" required>
                    <i class="fa-regular fa-eye icono-derecha"></i>
                </div>

                <label for="confirm_password">Confirmar contraseña</label>
                <div class="grupo-entrada">
                    <i class="fa-solid fa-lock icono-izquierda"></i>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirma tu contraseña" required>
                    <i class="fa-regular fa-eye icono-derecha"></i>
                </div>
                
                <label for="genero_musical">Género Principal</label>
                <div class="grupo-entrada">
                    <i class="fa-solid fa-music icono-izquierda "></i>
                    <select id="genero_musical" name="genero_musical" style="padding-left: 1em; width: 100%;" required>
                        <option value="" disabled selected></option>
                        <option value="urbano">Urbano</option>
                        <option value="pop">Pop</option>
                        <option value="rock">Rock</option>
                        <option value="hiphop">Hip Hop</option>
                        <option value="electronica">Electrónica</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <button type="submit" class="boton-enviar" style="background-color: var(--primary-purple);">Registrarse como Artista</button>
                
                <div style="text-align: center; margin-top: 15px;">
                    <span style="color: var(--dark-gray); font-size: 0.9em;">¿Eres un usuario normal?</span>
                    <a href="index.php?action=registro" style="color: var(--primary-purple); font-weight: bold; font-size: 0.9em; text-decoration: none;">Regístrate aquí</a>
                </div>
            </form>
        </div>
    </main>

<script>
    // Reutilizar validación básica o añadir específica
    function validarRegistroArtista() {
        const pass = document.getElementById('password').value;
        const confirm = document.getElementById('confirm_password').value;
        if (pass !== confirm) {
            alert('Las contraseñas no coinciden');
            return false;
        }
        return true;
    }
</script>
