<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$extraCss = 'css/estilo-editar-perfil.css';
require __DIR__ . '/../layouts/header.php';

// Valores por defecto si no existen en la variable $usuario
$nombre = isset($usuario['nombre_usuario']) ? $usuario['nombre_usuario'] : '';
$nombre_real = isset($usuario['nombre_real']) ? $usuario['nombre_real'] : ''; // Campo corregido
$email = isset($usuario['email']) ? $usuario['email'] : '';
$apellidos = isset($usuario['apellidos']) ? $usuario['apellidos'] : ''; // Campo nuevo
$calle = isset($usuario['calle']) ? $usuario['calle'] : ''; // Campo nuevo
$codigo_postal = isset($usuario['codigo_postal']) ? $usuario['codigo_postal'] : ''; // Campo nuevo
$ciudad = isset($usuario['ciudad']) ? $usuario['ciudad'] : ''; // Campo nuevo
$pais = isset($usuario['pais']) ? $usuario['pais'] : ''; // Campo nuevo
$avatar = isset($usuario['avatar']) ? $usuario['avatar'] : 'multimedia/img/default-avatar.png';
?>

<div class="contenedor-editar-perfil">
    <div class="header-formulario">
        <h1>Editar Perfil</h1>
    </div>

    <form action="index.php?action=actualizar_perfil" method="POST" enctype="multipart/form-data" class="grid-formulario">
        
        <!-- Columna Izquierda: Foto -->
        <div class="columna-foto">
            <h3 class="titulo-seccion-foto">Foto de Perfil <i class="fa-solid fa-pen icono-titulo"></i></h3>
            <div class="contenedor-avatar-edit">
                <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar Actual" id="preview-avatar">
                <label for="input-avatar" class="btn-cambiar-foto">
                    <i class="fa-solid fa-camera"></i>
                </label>
                <input type="file" name="avatar" id="input-avatar" accept="image/*" onchange="previewImage(this)">
            </div>
        </div>

        <!-- Columna Derecha: Campos -->
        <div class="columna-campos">
            
            <!-- Usuario y Apellidos -->
            <div class="fila-doble">
                <div class="grupo-input">
                    <label>Nombre de usuario <i class="fa-solid fa-pen"></i></label>
                    <input type="text" name="nombre_usuario" value="<?php echo htmlspecialchars($nombre); ?>" class="input-redondeado">
                </div>
            </div>

            <div class="fila-doble">
                <div class="grupo-input">
                    <label>Nombre <i class="fa-solid fa-pen"></i></label>
                    <input type="text" name="nombre_real" value="<?php echo htmlspecialchars($nombre_real); ?>" placeholder="Tu nombre" class="input-redondeado">
                </div>
                <div class="grupo-input">
                    <label>Apellidos <i class="fa-solid fa-pen"></i></label>
                    <input type="text" name="apellidos" value="<?php echo htmlspecialchars($apellidos); ?>" class="input-redondeado">
                </div>
            </div>

            <!-- Email (Solo lectura o editable según política, editable aquí) -->
            <div class="grupo-input ancho-completo">
                <label><i class="fa-regular fa-envelope"></i> Correo electrónico <i class="fa-solid fa-pen icon-right"></i></label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="input-redondeado">
            </div>

            <!-- Contraseña -->
            <div class="grupo-input ancho-completo">
                <label><i class="fa-regular fa-eye"></i> Contraseña <i class="fa-solid fa-pen icon-right"></i></label>
                <div class="contenedor-input-icono">
                    <input type="password" name="password" placeholder="Nueva contraseña (dejar vacío para no cambiar)" class="input-redondeado">
                     <i class="fa-solid fa-eye alternar-contrasena"></i>
                </div>
            </div>

            <!-- Nueva Sección: Dirección -->
            <h3 class="titulo-seccion-direccion">Dirección y Localización</h3>
            
            <div class="fila-doble">
                <div class="grupo-input">
                    <label>Calle</label>
                    <input type="text" name="calle" value="<?php echo htmlspecialchars($calle); ?>" placeholder="Ej. Av. Principal 123" class="input-redondeado">
                </div>
                <div class="grupo-input">
                    <label>Código Postal</label>
                    <input type="text" name="codigo_postal" value="<?php echo htmlspecialchars($codigo_postal); ?>" placeholder="00000" class="input-redondeado">
                </div>
            </div>

            <div class="fila-doble">
                <div class="grupo-input">
                    <label>Ciudad</label>
                    <input type="text" name="ciudad" value="<?php echo htmlspecialchars($ciudad); ?>" class="input-redondeado">
                </div>
                <div class="grupo-input">
                    <label>País</label>
                    <input type="text" name="pais" value="<?php echo htmlspecialchars($pais); ?>" class="input-redondeado">
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="acciones-formulario">
                <a href="index.php?action=perfil" class="btn-cancelar">Cancelar</a>
                <button type="submit" class="btn-guardar">Guardar Cambios</button>
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-avatar').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Lógica de Alertas y Validación
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    let hasChanged = false;

    // 1. Alerta "al cambiar un parámetro" (Toast no intrusivo)
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            if (!hasChanged) {
                hasChanged = true;
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                
                Toast.fire({
                    icon: 'info',
                    title: 'Editando información...'
                });
            }
        });
    });

    // 2. Validación antes de enviar
    form.addEventListener('submit', function(e) {
        // Validación simple ejemplo
        const nombreuser = form.querySelector('input[name="nombre_usuario"]').value;
        const email = form.querySelector('input[name="email"]').value;
        
        if (!nombreuser || !email) {
            e.preventDefault(); // Detener envío
            Swal.fire({
                icon: 'error',
                title: 'Campos vacíos',
                text: 'Por favor, asegúrate de llenar el usuario y el email.',
                confirmButtonColor: '#6F00D0'
            });
        } else {
            // Opcional: Mostrar "Guardando..." antes de que recargue la página
            // No detenemos el evento, dejamos que envíe
        }
    });
    // 3. Verificar errores de Base de Datos en URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error') && urlParams.get('error') === 'db_error') {
        const msg = urlParams.get('message');
        Swal.fire({
            icon: 'error',
            title: 'Error de Base de Datos',
            text: msg ? decodeURIComponent(msg) : 'No se pudo actualizar el perfil due a un error en la base de datos.',
            footer: 'Es probable que falten columnas en la tabla usuario (ej. nombre_real).',
            confirmButtonColor: '#d33'
        }).then(() => {
            // Limpiar URL
             window.history.replaceState(null, null, window.location.pathname + '?action=editar_perfil');
        });
    }
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
