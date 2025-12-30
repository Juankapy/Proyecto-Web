<?php 
// Estilos específicos para la canción
$extraCss = 'css/estilo-cancion.css';
require __DIR__ . '/layouts/header.php'; 
?>


<main class="contenedor-cancion">
    
    <div class="diseño-desglose">
        <aside class="panel-informacion">
            <div class="portada-cancion-grande">
                <img src="<?php echo htmlspecialchars($cancion->portada_url); ?>" alt="<?php echo htmlspecialchars($cancion->titulo); ?>">
            </div>
            
            <div class="info-metadatos">
                <h1 class="titulo-metadatos"><?php echo htmlspecialchars($cancion->titulo); ?></h1>
                <h2 class="artista-metadatos"><?php echo htmlspecialchars($cancion->artista); ?></h2>
                <div class="detalles-metadatos">
                    <p><i class="fa-solid fa-record-vinyl"></i> <?php echo htmlspecialchars($cancion->album); ?></p>
                    <p><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($cancion->duracion); ?></p>
                </div>
            </div>

            <div class="creditos-metadatos">
                <h3>Créditos</h3>
                <ul class="lista-creditos-simple">
                    <?php foreach ($cancion->creditos as $credito): ?>
                    <li>
                        <span class="rol"><?php echo htmlspecialchars($credito['rol']); ?></span>
                        <span class="nombre"><?php echo htmlspecialchars($credito['nombres']); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>

        <!-- Panel Derecho: Letra Scrolleable -->
        <section class="panel-letra">
            <h3 class="encabezado-letra-minimal">Letra</h3>
            <div class="contenido-letra-limpio">
                <?php if (!empty($cancion->letra_html)): ?>
                    <div class="letra-real">
                        <?php echo $cancion->letra_html; ?>
                    </div>
                <?php else: ?>
                    <?php foreach ($cancion->letra_simulada as $parrafo): ?>
                    <p><?php echo htmlspecialchars($parrafo); ?></p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            </div>
        </section>
    </div>

</main>

<?php require __DIR__ . '/layouts/footer.php'; ?>
