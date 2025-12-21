<?php
/**
 * Archivo de prueba para visualizar la vista de album
 * Accede a: http://localhost/Proyecto/public/test-album.php?id={ID_DEL_ALBUM}
 */

require_once '../app/Services/SpotifyService.php';

$clientId = 'cf0a28b6c1c9425bbfb697f9a072afc8';
$clientSecret = '16c9bcf6476e47138c1adc87c82596ea';

$spotify = new SpotifyService($clientId, $clientSecret);

$albumId = $_GET['id'] ?? '4yP0hdKOZPNshxUOjY0cZj'; // Default to "After Hours" by The Weeknd if no ID provided

$albumData = $spotify->getAlbum($albumId);

if ($albumData) {
    $album = (object)[
        'id' => $albumData['id'],
        'nombre' => $albumData['name'],
        'artista' => $albumData['artists'][0]['name'],
        'artista_id' => $albumData['artists'][0]['id'], // Artist ID for linking
        'portada_url' => $albumData['images'][0]['url'] ?? 'multimedia/img/default-album.jpg',
        'anio' => substr($albumData['release_date'], 0, 4),
        'total_canciones' => $albumData['total_tracks'],
        'copyright' => $albumData['copyrights'][0]['text'] ?? '',
        'descripcion' => "\"{$albumData['name']}\" es un álbum de estudio de {$albumData['artists'][0]['name']}. Lanzado en " . substr($albumData['release_date'], 0, 4) . ".", // Simple generated description as API doesn't provide it directly in this endpoint
        'canciones' => []
    ];

    foreach ($albumData['tracks']['items'] as $track) {
        $minutes = floor($track['duration_ms'] / 60000);
        $seconds = ($track['duration_ms'] % 60000) / 1000;

        $album->canciones[] = (object)[
            'titulo' => $track['name'],
            'artista' => $track['artists'][0]['name'], // Some tracks might have featuring artists
            'duracion' => sprintf("%d:%02d", $minutes, $seconds),
            'url_preview' => $track['preview_url'] // In case we want to use it later, though user stripped playback from artist page
        ];
    }

} else {
    // Fallback data
    $album = (object)[
        'nombre' => 'Album no encontrado',
        'artista' => 'Desconocido',
        'portada_url' => 'multimedia/img/default-album.jpg',
        'anio' => '----',
        'descripcion' => 'No se pudo cargar la información del álbum.',
        'canciones' => []
    ];
}

// Incluir la vista del album
require_once '../app/Vista/album.php';
