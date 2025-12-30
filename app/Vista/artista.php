<?php 

$extraCss = 'css/estilo-artista.css';
require __DIR__ . '/layouts/header.php'; 
?>

<div class="artist-hero-container">
    <div class="hero-image" style="background-image: url('<?php echo htmlspecialchars($artista->imagen_hero); ?>');">
        <div class="hero-overlay"></div>
    </div>
</div>

<div class="artist-main-container">
    
    <aside class="artist-sidebar">
        <div class="profile-image-container">
            <img src="<?php echo htmlspecialchars($artista->imagen_perfil); ?>" alt="<?php echo htmlspecialchars($artista->nombre_artistico); ?>" class="profile-image">
        </div>
        
        <h1 class="artist-name"><?php echo htmlspecialchars($artista->nombre_artistico); ?></h1>
        
        <div class="artist-about-section">
            <h3 class="about-title">Biografía</h3>
            
            <div class="artist-bio-text">
                <?php 
                $biografia = $artista->biografia;
                $limite = 200;
                if (strlen($biografia) > $limite) {
                    $corte = strpos($biografia, ' ', $limite);
                    if ($corte === false) $corte = $limite;
                    $visible = substr($biografia, 0, $corte);
                    $resto = substr($biografia, $corte);
                    ?>
                    <span class="bio-visible"><?php echo nl2br($visible); ?>...</span>
                    <span class="bio-oculta" style="display:none;"><?php echo nl2br($resto); ?></span>
                    <a href="#" class="read-more-link" onclick="toggleBiografia(event)">seguir leyendo</a>
                    <?php
                } else {
                    echo nl2br($biografia);
                }
                ?>
            </div>
            
            <?php if (!empty($artista->generos)): ?>
            <div class="artist-genres">
                <?php foreach (array_slice($artista->generos, 0, 5) as $genero): ?>
                    <span class="genre-tag"><?php echo htmlspecialchars(ucfirst($genero)); ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </aside>
    
    <main class="artist-content">
        
        <section class="content-section">
            <h2 class="section-title" style="color: #6F00D0; font-size: 1.5em;">CANCIONES POPULARES DE <?php echo strtoupper(htmlspecialchars($artista->nombre_artistico)); ?></h2>
            
            <div class="songs-grid">
                <?php foreach (array_slice($artista->canciones_populares, 0, 8) as $cancion): ?>
                <a href="index.php?action=cancion&id=<?php echo $cancion->id; ?>" class="song-card">
                    <div class="song-card-image">
                        <img src="<?php echo htmlspecialchars($cancion->miniatura_url); ?>" alt="Cover">
                    </div>
                    <div class="song-card-info">
                        <h3 class="song-card-title"><?php echo htmlspecialchars($cancion->titulo); ?></h3>
                        <p class="song-card-artist"><?php echo htmlspecialchars($cancion->artista_nombre); ?></p>
                        <div class="song-card-views">
                            <i class="fa-regular fa-eye"></i> <?php echo $cancion->vistas_formateadas; ?>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        
        <section class="content-section">
            <div class="search-container-full">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="buscador-canciones-artista" placeholder="Ver todas las canciones de <?php echo htmlspecialchars($artista->nombre_artistico); ?>" style="color: #6F00D0;">
            </div>
            <div id="resultados-busqueda-artista" class="resultados-busqueda"></div>
        </section>
        
        <section class="content-section">
            <h2 class="section-title" style="color: #6F00D0; font-size: 1.5em;">ÁLBUMES</h2>
            <div class="albums-grid-creative">
                <?php foreach ($artista->albumes as $index => $album): ?>
                    <a href="index.php?action=album&id=<?php echo $album->id; ?>" class="album-item item-<?php echo ($index % 6) + 1; ?>">
                        <div class="album-cover-wrapper">
                            <img src="<?php echo htmlspecialchars($album->portada_url); ?>" alt="<?php echo htmlspecialchars($album->nombre_album); ?>">
                            <div class="album-overlay-info">
                                <span class="album-year"><?php echo $album->anio; ?></span>
                            </div>
                        </div>
                        <div class="album-title-simple"><?php echo htmlspecialchars($album->nombre_album); ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
        
    </main>
</div>

<script type="application/json" id="artist-songs-data">
<?php 
    echo json_encode($artista->todas_las_canciones); 
?>
</script>

<?php require __DIR__ . '/layouts/footer.php'; ?>
