<?php 
$extraCss = 'css/estilo-album.css';
require __DIR__ . '/layouts/header.php'; 
?>


<main class="contenedor-album">
    <!-- Header Banner similar to design -->
    <div class="banner-cabecera-album">
        <h1 class="titulo-banner-album"><?php echo htmlspecialchars($album->nombre); ?></h1>
    </div>

    <div class="cuadricula-contenido-album">
        <!-- Columna Izquierda: Portada e Info -->
        <aside class="barra-lateral-album">
            <div class="contenedor-portada-album">
                <img src="<?php echo htmlspecialchars($album->portada_url); ?>" alt="<?php echo htmlspecialchars($album->nombre); ?>" class="img-portada-album">
            </div>
            
            <div class="detalles-album">
                <h2 class="nombre-album"><?php echo htmlspecialchars($album->nombre); ?></h2>
                <h3 class="artista-album">
                    <a href="test-artista.php?id=<?php echo htmlspecialchars($album->artista_id ?? ''); ?>" style="text-decoration: none; color: inherit;">
                        <?php echo htmlspecialchars($album->artista); ?>
                    </a>
                </h3>
                
                <p class="descripcion-album">
                    <?php echo htmlspecialchars($album->descripcion); ?>
                </p>

                <div class="acciones-album">
                    <button class="boton-icono"><i class="fa-solid fa-heart"></i></button>
                    <button class="boton-icono"><i class="fa-solid fa-share"></i></button>
                </div>
            </div>
        </aside>

        <!-- Columna Derecha: Lista de Canciones -->
        <section class="canciones-album">
            <div class="cabecera-canciones">
                <span class="col-titulo">Lista de Canciones</span>
                <span class="col-encabezado-titulo">TÍTULO</span>
                <span class="col-reloj"><i class="fa-regular fa-clock"></i></span>
            </div>

            <div class="lista-canciones">
                <?php foreach ($album->canciones as $index => $cancion): ?>
                <a href="test-cancion.php?titulo=<?php echo urlencode($cancion->titulo); ?>&artista=<?php echo urlencode($cancion->artista); ?>&album=<?php echo urlencode($album->nombre); ?>&duracion=<?php echo urlencode($cancion->duracion); ?>" class="elemento-cancion" style="text-decoration:none; color:inherit;">
                    <span class="numero-cancion-lista"><?php echo $index + 1; ?></span>
                    <div class="info-cancion-lista">
                        <span class="titulo-cancion-lista"><?php echo htmlspecialchars($cancion->titulo); ?></span>
                        <span class="artista-cancion-lista"><?php echo htmlspecialchars($cancion->artista); ?></span>
                    </div>
                    <span class="duracion-cancion-lista"><?php echo htmlspecialchars($cancion->duracion); ?></span>
                    <span class="opciones-cancion"><i class="fa-solid fa-ellipsis-vertical"></i></span>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</main>

<?php require __DIR__ . '/layouts/footer.php'; ?>
