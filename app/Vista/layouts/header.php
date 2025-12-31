<!DOCTYPE html>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>R E C I C L E D ☆</title>
    <link rel="shortcut icon" href="multimedia/img/logo_3d.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <?php if (isset($extraCss)): ?>
        <link rel="stylesheet" href="<?php echo $extraCss; ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Enlace a interactividad.css -->
    <link rel="stylesheet" href="css/interactividad.css">
    <!-- Enlace a busqueda.css -->
    <link rel="stylesheet" href="css/busqueda.css">
    <!-- Enlace a estilos-globales.css -->
    <link rel="stylesheet" href="css/estilos-globales.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/interacciones-formulario.js" defer></script>
</head>
<body>
    <header>
        <div class="contenedor-busqueda buscador">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="campo-busqueda" placeholder="Buscar artistas o canciones...">
            <div id="resultados-busqueda" class="oculto"></div>
        </div>
        <div class="img">
            <a href="index.php">
                <img src="multimedia/img/logo_White.png" alt="Music Logo">
            </a>
        </div>
        <div class="inicio-sesion">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <style>
                    /* Estilos para el Dropdown de Usuario */
                    .usuario-dropdown-contenedor {
                        position: relative;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        cursor: pointer;
                        padding: 5px 10px;
                        border-radius: 20px;
                        transition: background-color 0.2s;
                    }
                    .usuario-dropdown-contenedor:hover {
                        background-color: rgba(255, 255, 255, 0.1);
                    }
                    .avatar-mini {
                        width: 35px;
                        height: 35px;
                        border-radius: 50%;
                        object-fit: cover;
                        border: 2px solid white;
                    }
                    .nombre-usuario-header {
                        color: white;
                        font-weight: 600;
                        font-size: 0.95em;
                    }
                    .menu-desplegable {
                        display: none;
                        position: absolute;
                        top: 100%;
                        right: 0;
                        background-color: white;
                        min-width: 180px;
                        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
                        border-radius: 8px;
                        z-index: 1000;
                        overflow: hidden;
                        margin-top: 10px;
                    }
                    /* Puente invisible para evitar que el menú se cierre al mover el mouse */
                    .usuario-dropdown-contenedor::after {
                        content: '';
                        position: absolute;
                        top: 100%;
                        left: 0;
                        width: 100%;
                        height: 10px; /* Cubre el margin-top del menú */
                    }
                    .usuario-dropdown-contenedor:hover .menu-desplegable {
                        display: block;
                    }
                    .menu-desplegable a {
                        color: #333;
                        padding: 12px 16px;
                        text-decoration: none;
                        display: block;
                        font-size: 0.9em;
                        transition: background-color 0.2s;
                    }
                    .menu-desplegable a:hover {
                        background-color: #f1f1f1;
                        color: #6F00D0; /* Morado del proyecto */
                    }
                    .menu-desplegable a i {
                        margin-right: 8px;
                        width: 20px;
                        text-align: center;
                    }
                </style>
                <div class="usuario-dropdown-contenedor">
                    <!-- Avatar: Prioridad a la imagen de sesión, fallback directo al icono FA si falla o no existe -->
                    <?php if (isset($_SESSION['avatar']) && !empty($_SESSION['avatar'])): ?>
                        <img src="<?php echo $_SESSION['avatar']; ?>" 
                             alt="User" 
                             class="avatar-mini"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                        <i class="fa-solid fa-user" style="color: white; font-size: 1.5em; display: none;"></i>
                    <?php else: ?>
                        <i class="fa-solid fa-user" style="color: white; font-size: 1.5em;"></i>
                    <?php endif; ?>
                    
                    <span class="nombre-usuario-header"><?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></span>
                    <i class="fa-solid fa-chevron-down" style="color: white; font-size: 0.8em;"></i>

                    <div class="menu-desplegable">
                        <a href="index.php?action=perfil">
                            <i class="fa-solid fa-user"></i> Mi Perfil
                        </a>
                        <a href="index.php?action=logout">
                            <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="index.php?action=login">Inicia Sesión</a>
                <a href="index.php?action=register">Registrarse</a>
            <?php endif; ?>
        </div>
        
    </header>
        <div class="navegacion">
            <nav>
                <a href="#">LANZAMIENTOS</a>
                <a href="#">DESCUBRIMIENTOS</a>
                <a href="#">PARA TI</a>
            </nav>
        </div>
