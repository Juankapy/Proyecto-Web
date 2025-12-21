<?php 
$extraCss = 'css/estilo-album.css';
require __DIR__ . '/layouts/header.php'; 
?>

<main class="contenedor-album">
    <!-- Header Banner similar to design -->
    <div class="album-header-banner">
        <h1 class="album-title-banner"><?php echo htmlspecialchars($album->nombre); ?></h1>
    </div>

    <div class="album-content-grid">
        <!-- Columna Izquierda: Portada e Info -->
        <aside class="album-sidebar">
            <div class="album-cover-container">
                <img src="<?php echo htmlspecialchars($album->portada_url); ?>" alt="<?php echo htmlspecialchars($album->nombre); ?>" class="album-cover-img">
            </div>
            
            <div class="album-details">
                <h2 class="album-name"><?php echo htmlspecialchars($album->nombre); ?></h2>
                <h3 class="album-artist">
                    <a href="test-artista.php?id=<?php echo htmlspecialchars($album->artista_id ?? ''); ?>" style="text-decoration: none; color: inherit;">
                        <?php echo htmlspecialchars($album->artista); ?>
                    </a>
                </h3>
                
                <p class="album-description">
                    <?php echo htmlspecialchars($album->descripcion); ?>
                </p>

                <div class="album-actions">
                    <button class="btn-icon"><i class="fa-solid fa-heart"></i></button>
                    <button class="btn-icon"><i class="fa-solid fa-share"></i></button>
                </div>
            </div>
        </aside>

        <!-- Columna Derecha: Lista de Canciones -->
        <section class="album-tracks">
            <div class="tracks-header">
                <span class="col-title">Lista de Canciones</span>
                <span class="col-heading-title">TÍTULO</span>
                <span class="col-clock"><i class="fa-regular fa-clock"></i></span>
            </div>

            <div class="tracks-list">
                <?php foreach ($album->canciones as $index => $cancion): ?>
                <a href="test-cancion.php?titulo=<?php echo urlencode($cancion->titulo); ?>&artista=<?php echo urlencode($cancion->artista); ?>&album=<?php echo urlencode($album->nombre); ?>&duracion=<?php echo urlencode($cancion->duracion); ?>" class="track-item" style="text-decoration:none; color:inherit;">
                    <span class="track-number"><?php echo $index + 1; ?></span>
                    <div class="track-info">
                        <span class="track-title"><?php echo htmlspecialchars($cancion->titulo); ?></span>
                        <span class="track-artist"><?php echo htmlspecialchars($cancion->artista); ?></span>
                    </div>
                    <span class="track-duration"><?php echo htmlspecialchars($cancion->duracion); ?></span>
                    <span class="track-options"><i class="fa-solid fa-ellipsis-vertical"></i></span>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</main>

<?php require __DIR__ . '/layouts/footer.php'; ?>
