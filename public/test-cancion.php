<?php
/**
 * Archivo de prueba para visualizar la vista de canción
 * Accede a: http://localhost/Proyecto/public/test-cancion.php?titulo={TITULO}&artista={ARTISTA}&album={ALBUM}
 */

require_once '../app/Controlador/GeniusControlador.php';

$titulo = $_GET['titulo'] ?? 'Bohemian Rhapsody';
$artistaNombre = $_GET['artista'] ?? 'Queen';
$albumNombre = $_GET['album'] ?? 'A Night at the Opera';
$duracion = $_GET['duracion'] ?? '5:55';

// Instanciar controlador
$genius = new GeniusControlador();

// Buscar información en Genius
$busqueda = $titulo . ' ' . $artistaNombre;
$datosGenius = $genius->buscarCancion($busqueda);

$letraReal = null;
if (isset($datosGenius['url'])) {
    $letraReal = $genius->obtenerLetra($datosGenius['url']);
}

$cancion = (object)[
    'titulo' => $datosGenius['title_with_featured'] ?? $titulo,
    'artista' => $datosGenius['primary_artist']['name'] ?? $artistaNombre,
    'album' => $albumNombre, // Genius doesn't easily give album in search hit, sticking to passed param
    'portada_url' => $datosGenius['song_art_image_url'] ?? 'multimedia/img/default-song.jpg',
    'duracion' => $duracion,
    'contexto' => 'Esta canción es interpretada por ' . ($datosGenius['primary_artist']['name'] ?? $artistaNombre) . '. La letra a continuación es obtenida directamente de Genius. Las partes resaltadas indican anotaciones de la comunidad.',
    'letra_html' => $letraReal, // Nueva propiedad para la letra real
    'letra_simulada' => [ // Fallback por si falla el scraping
        "No se pudo cargar la letra real desde Genius.",
        "Por favor intenta de nuevo más tarde."
    ], 
    'creditos' => [
        ['rol' => 'Escrita por', 'nombres' => $artistaNombre],
    ],
    'url_genius' => $datosGenius['url'] ?? '#'
];

// Si Genius devuelve path para letras, podríamos intentar scraping (fuera del alcance simple),
// por ahora simulamos con el texto de placeholder de la imagen

// Incluir la vista
require_once '../app/Vista/cancion.php';
