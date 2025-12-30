<?php 

$extraCss = 'css/estilo-artista.css';
require __DIR__ . '/layouts/header.php'; 
?>


<div class="contenedor-hero-artista">
    <div class="imagen-hero" style="background-image: url('<?php echo htmlspecialchars($artista->imagen_hero); ?>');">
        <div class="superposicion-hero"></div>
    </div>
</div>

<div class="contenedor-principal-artista">
    
    <aside class="barra-lateral-artista">
        <div class="contenedor-imagen-perfil">
            <img src="<?php echo htmlspecialchars($artista->imagen_perfil); ?>" alt="<?php echo htmlspecialchars($artista->nombre_artistico); ?>" class="imagen-perfil">
        </div>
        
        <h1 class="nombre-artista"><?php echo htmlspecialchars($artista->nombre_artistico); ?></h1>
        
        <div class="seccion-sobre-artista">
            <h3 class="titulo-sobre">Biografía</h3>
            
            <div class="texto-biografia-artista">
                <?php 
                $biografia = $artista->biografia;
                $limite = 200;
                if (strlen($biografia) > $limite) {
                    $corte = strpos($biografia, ' ', $limite);
                    if ($corte === false) $corte = $limite;
                    $visible = substr($biografia, 0, $corte);
                    $resto = substr($biografia, $corte);
                    ?>
                    <span class="biografia-visible"><?php echo nl2br($visible); ?>...</span>
                    <span class="biografia-oculta" style="display:none;"><?php echo nl2br($resto); ?></span>
                    <a href="#" class="enlace-leer-mas" onclick="toggleBiografia(event)">seguir leyendo</a>
                    <?php
                } else {
                    echo nl2br($biografia);
                }
                ?>
            </div>
            
            <?php if (!empty($artista->generos)): ?>
            <div class="generos-artista">
                <?php foreach (array_slice($artista->generos, 0, 5) as $genero): ?>
                    <span class="etiqueta-genero"><?php echo htmlspecialchars(ucfirst($genero)); ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </aside>
    
    <main class="contenido-artista">
        
        <section class="seccion-contenido">
            <h2 class="titulo-seccion" style="color: #6F00D0; font-size: 1.5em;">CANCIONES POPULARES DE <?php echo strtoupper(htmlspecialchars($artista->nombre_artistico)); ?></h2>
            
            <div class="cuadricula-canciones">
                <?php foreach (array_slice($artista->canciones_populares, 0, 8) as $cancion): ?>
                <a href="index.php?action=cancion&id=<?php echo $cancion->id; ?>" class="tarjeta-cancion">
                    <div class="imagen-tarjeta-cancion">
                        <img src="<?php echo htmlspecialchars($cancion->miniatura_url); ?>" alt="Cover">
                    </div>
                    <div class="info-tarjeta-cancion">
                        <h3 class="titulo-tarjeta-cancion"><?php echo htmlspecialchars($cancion->titulo); ?></h3>
                        <p class="artista-tarjeta-cancion"><?php echo htmlspecialchars($cancion->artista_nombre); ?></p>
                        <div class="vistas-tarjeta-cancion">
                            <i class="fa-regular fa-eye"></i> <?php echo $cancion->vistas_formateadas; ?>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        
        <section class="seccion-contenido">
            <div class="contenedor-busqueda-completo">
                <i class="fa-solid fa-magnifying-glass icono-busqueda"></i>
                <input type="text" id="buscador-canciones-artista" placeholder="Ver todas las canciones de <?php echo htmlspecialchars($artista->nombre_artistico); ?>" style="color: #6F00D0;">
            </div>
            <div id="resultados-busqueda-artista" class="resultados-busqueda"></div>
        </section>
        
        <section class="seccion-contenido">
            <h2 class="titulo-seccion" style="color: #6F00D0; font-size: 1.5em;">ÁLBUMES</h2>
            <div class="cuadricula-albumes-creativa">
                <?php foreach ($artista->albumes as $index => $album): ?>
                    <a href="index.php?action=album&id=<?php echo $album->id; ?>" class="elemento-album item-<?php echo ($index % 6) + 1; ?>">
                        <div class="envoltura-portada-album">
                            <img src="<?php echo htmlspecialchars($album->portada_url); ?>" alt="<?php echo htmlspecialchars($album->nombre_album); ?>">
                            <div class="info-superpuesta-album">
                                <span class="anio-album"><?php echo $album->anio; ?></span>
                            </div>
                        </div>
                        <div class="titulo-album-simple"><?php echo htmlspecialchars($album->nombre_album); ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
        
    </main>
</div>

<script type="application/json" id="datos-canciones-artista">
<?php 
    echo json_encode($artista->todas_las_canciones); 
?>
</script>

<?php require __DIR__ . '/layouts/footer.php'; ?>
