<?php
// Mock Data for Design Verification
if (!isset($usuario)) {
    $usuario = (object) [
        'nombre' => 'Nombre de Usuario',
        'email' => 'usuario@email.com',
        'avatar' => 'multimedia/img/default-avatar.png' // Placeholder path
    ];
}

if (!isset($anotaciones)) {
    // Uncomment one of these to test different states
    
    // Case 1: With annotations
    $anotaciones = [
        (object) [
            'cancion_titulo' => 'Nombre de la canción',
            'artista_nombre' => 'Nombre del Artista',
            'texto' => 'Esta línea hace referencia a un evento histórico importante que marcó la vida del artista...'
        ],
        (object) [
            'cancion_titulo' => 'Otra Canción',
            'artista_nombre' => 'Otro Artista',
            'texto' => 'Aquí el compositor juega con un doble sentido, aludiendo tanto a una relación amorosa como a su pasión por la música.'
        ],
        (object) [
            'cancion_titulo' => 'Clásico Inmortal',
            'artista_nombre' => 'Banda Legendaria',
            'texto' => 'La métrica de este verso es particularmente compleja, utilizando un patrón poco común en el rock de la época.'
        ]
    ];

    // Case 2: Empty state
    // $anotaciones = [];
}

$extraCss = 'css/estilo-perfil.css';
require __DIR__ . '/../layouts/header.php';
?>

<div class="contenedor-perfil">
    <!-- Section: User Info -->
    <div class="tarjeta-perfil-header">
        <div class="info-usuario-izquierda">
            <div class="avatar-grande-contenedor">
                <img src="<?php echo htmlspecialchars($usuario->avatar); ?>" alt="Avatar" class="avatar-grande" onerror="this.src='https://via.placeholder.com/150'">
            </div>
            <div class="datos-texto-usuario">
                <h1><?php echo htmlspecialchars($usuario->nombre); ?></h1>
                <p class="email-usuario"><?php echo htmlspecialchars($usuario->email); ?></p>
                <?php if (!empty($usuario->ciudad) || !empty($usuario->pais)): ?>
                    <p class="email-usuario" style="margin-top: 5px; font-size: 0.9rem;">
                        <i class="fa-solid fa-location-dot" style="margin-right: 5px;"></i>
                        <?php echo htmlspecialchars(trim(($usuario->ciudad ? $usuario->ciudad : '') . ($usuario->ciudad && $usuario->pais ? ', ' : '') . ($usuario->pais ? $usuario->pais : ''))); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <div class="acciones-usuario-derecha">
            <a href="index.php?action=editar_perfil" class="btn-editar-perfil" style="text-decoration: none;">
                <i class="fa-solid fa-pen"></i> Editar Perfil
            </a>
            <div class="icono-favoritos-contenedor">
                <i class="fa-solid fa-heart icono-favoritos"></i>
            </div>
        </div>
    </div>

    <!-- Section: My Annotations -->
    <div class="seccion-anotaciones">
        <h2>Mis Anotaciones</h2>
        
        <?php if (empty($anotaciones)): ?>
            <div class="mensaje-vacio">
                <p>Aún no has hecho ninguna anotación. ¡Explora canciones para empezar!</p>
            </div>
        <?php else: ?>
            <div class="lista-anotaciones">
                <?php foreach ($anotaciones as $nota): ?>
                    <div class="tarjeta-anotacion">
                        <div class="borde-lateral-morado"></div>
                        <div class="contenido-anotacion">
                            <p class="meta-anotacion">
                                Anotación en <span class="resaltado-morado"><?php echo htmlspecialchars($nota->cancion_titulo); ?></span> de <span class="resaltado-morado"><?php echo htmlspecialchars($nota->artista_nombre); ?></span>
                            </p>
                            <blockquote class="texto-anotacion">
                                "<?php echo htmlspecialchars($nota->texto); ?>"
                            </blockquote>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="ver-todas-contenedor">
                <a href="#" class="enlace-ver-todas">Ver todas mis anotaciones</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Section: Account Settings -->
    <div class="seccion-configuracion">
        <h3>Configuración de la Cuenta</h3>
        <div class="grid-configuracion">
            <!-- Card 1: Change Password -->
            <div class="tarjeta-configuracion">
                <div class="icono-configuracion-contenedor">
                    <i class="fa-solid fa-key"></i>
                </div>
                <div class="texto-configuracion">
                    <h4>Cambiar Contraseña</h4>
                    <p>Actualiza la seguridad de tu cuenta.</p>
                </div>
            </div>

            <!-- Card 2: Notifications -->
            <div class="tarjeta-configuracion">
                <div class="icono-configuracion-contenedor">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div class="texto-configuracion">
                    <h4>Notificaciones</h4>
                    <p>Gestiona tus preferencias de avisos.</p>
                </div>
            </div>

            <!-- Card 3: Appearance -->
            <div class="tarjeta-configuracion">
                <div class="icono-configuracion-contenedor">
                    <i class="fa-solid fa-palette"></i>
                </div>
                <div class="texto-configuracion">
                    <h4>Apariencia</h4>
                    <p>Cambia entre modo claro y oscuro.</p>
                </div>
            </div>

            <!-- Card 4: Delete Account -->
            <div class="tarjeta-configuracion peligro">
                <div class="icono-configuracion-contenedor rojo">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <div class="texto-configuracion">
                    <h4 class="texto-rojo">Eliminar Cuenta</h4>
                    <p class="texto-rojo-suave">Esta acción es irreversible.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Verificar si hay parámetros de éxito en la URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success') && urlParams.get('success') === 'updated') {
        Swal.fire({
            title: '¡Guardado!',
            text: 'Los cambios se han guardado correctamente.',
            icon: 'success',
            confirmButtonColor: '#6F00D0',
            confirmButtonText: 'Genial'
        }).then(() => {
            // Limpiar la URL para que no salga la alerta al recargar
            window.history.replaceState(null, null, window.location.pathname + '?action=perfil');
        });
    }
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
