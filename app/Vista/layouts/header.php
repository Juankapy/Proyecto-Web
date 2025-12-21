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
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="color: white; font-weight: bold;"><?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></span>
                    <i class="fa-solid fa-user" style="color: white; font-size: 1.2em;"></i>
                    <a href="index.php?action=logout" style="font-size: 0.9em; margin-left: 10px;">Cerrar Sesión</a>
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
