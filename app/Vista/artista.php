<?php 
/**
 * Vista de Página del Artista - Diseño Minimalista
 * Enfoque limpio y moderno sin filtros pesados
 */

$extraCss = 'css/estilo-artista.css';
require __DIR__ . '/layouts/header.php'; 

// Manejo de imágenes
$imagenes = $artista->imagenes ?? [];
$imagenPerfil = !empty($imagenes[0]['url']) ? $imagenes[0]['url'] : ($artista->foto_url ?? 'multimedia/img/default-artist.jpg');

// Extraer géneros
$generos = $artista->generos ?? [];
?>

<main class="pagina-artista">
    <!-- Header Superior Simple -->
    <div class="artista-header">
        <div class="header-contenedor">
            <!-- Imagen de Perfil Grande -->
            <div class="artista-avatar">
                <img src="<?php echo htmlspecialchars($imagenPerfil); ?>" alt="<?php echo htmlspecialchars($artista->nombre_artistico); ?>">
            </div>
            
            <!-- Información del Artista -->
            <div class="artista-datos">
                <div class="verificado-badge">
                    <i class="fa-solid fa-badge-check"></i> Artista Verificado
                </div>
                <h1 class="artista-nombre"><?php echo htmlspecialchars($artista->nombre_artistico); ?></h1>
                
                <p class="artista-stats">
                    <?php echo htmlspecialchars($artista->seguidores_formateados ?? '0'); ?> oyentes mensuales
                </p>
                
                <?php if (!empty($generos)): ?>
                <div class="generos-tags">
                    <?php foreach (array_slice($generos, 0, 4) as $genero): ?>
                        <span class="genero-tag"><?php echo htmlspecialchars(ucfirst($genero)); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="artista-contenido">
        <div class="contenido-wrapper">
            <!-- Sección Principal -->
            <div class="seccion-principal">
                
                <!-- Populares -->
                <section class="bloque-seccion">
                    <h2 class="titulo-bloque">Populares</h2>
                    
                    <?php if (isset($artista->canciones_populares) && count($artista->canciones_populares) > 0): ?>
                        <div class="lista-tracks">
                            <?php foreach (array_slice($artista->canciones_populares, 0, 5) as $index => $cancion): ?>
                                <a href="test-cancion.php?titulo=<?php echo urlencode($cancion->titulo); ?>&artista=<?php echo urlencode($artista->nombre_artistico); ?>" class="track-row">
                                    <div class="track-numero"><?php echo $index + 1; ?></div>
                                    <div class="track-detalles">
                                        <div class="track-titulo"><?php echo htmlspecialchars($cancion->titulo); ?></div>
                                        <div class="track-info"><?php echo htmlspecialchars($cancion->album_nombre ?? 'Sencillo'); ?></div>
                                    </div>
                                    <div class="track-duracion"><?php echo htmlspecialchars($cancion->duracion_formateada ?? '-'); ?></div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="mensaje-vacio">No hay canciones disponibles</p>
                    <?php endif; ?>
                </section>

                <!-- Discografía -->
                <section class="bloque-seccion">
                    <h2 class="titulo-bloque">Discografía</h2>
                    
                    <?php if (isset($artista->albumes) && count($artista->albumes) > 0): ?>
                        <div class="grid-discografia">
                            <?php foreach ($artista->albumes as $album): ?>
                                <a href="test-album.php?id=<?php echo htmlspecialchars($album->id); ?>" class="disco-card">
                                    <div class="disco-cover">
                                        <img src="<?php echo htmlspecialchars($album->portada_url ?? 'multimedia/img/default-album.jpg'); ?>" alt="<?php echo htmlspecialchars($album->nombre_album); ?>">
                                        <div class="play-hover">
                                            <i class="fa-solid fa-circle-play"></i>
                                        </div>
                                    </div>
                                    <div class="disco-meta">
                                        <h3 class="disco-nombre"><?php echo htmlspecialchars($album->nombre_album); ?></h3>
                                        <p class="disco-año"><?php echo htmlspecialchars($album->anio ?? ''); ?></p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="mensaje-vacio">No hay álbumes disponibles</p>
                    <?php endif; ?>
                </section>
            </div>

            <!-- Barra Lateral -->
            <aside class="barra-lateral">
                <div class="bio-panel">
                    <h3 class="bio-titulo">Acerca del artista</h3>
                    <div class="bio-texto">
                        <?php 
                        $biografia = htmlspecialchars($artista->biografia ?? '');
                        if (empty($biografia)) {
                            $biografia = 'Información biográfica no disponible en este momento.';
                        }
                        
                        $limite = 350;
                        if (strlen($biografia) > $limite) {
                            $corte = strpos($biografia, ' ', $limite);
                            if ($corte === false) $corte = $limite;
                            $visible = substr($biografia, 0, $corte);
                            $resto = substr($biografia, $corte);
                            ?>
                            <p><?php echo nl2br($visible); ?><span id="dots">...</span><span id="more-bio" style="display:none;"><?php echo nl2br($resto); ?></span></p>
                            <button onclick="var dots = document.getElementById('dots'); var more = document.getElementById('more-bio'); var btn = this; if (more.style.display === 'none') { dots.style.display = 'none'; more.style.display = 'inline'; btn.innerHTML = 'Ver menos'; } else { dots.style.display = 'inline'; more.style.display = 'none'; btn.innerHTML = 'Ver más'; } return false;" class="btn-leer-mas">Ver más</button>
                            <?php
                        } else {
                            echo '<p>' . nl2br($biografia) . '</p>';
                        }
                        ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>

<?php require __DIR__ . '/layouts/footer.php'; ?>
