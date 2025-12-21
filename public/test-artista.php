<?php
/**
 * Archivo de prueba temporal para visualizar la vista del artista
 * Accede a: http://localhost/Proyecto/public/test-artista.php
 */

require_once '../app/Services/SpotifyService.php';

$clientId = 'cf0a28b6c1c9425bbfb697f9a072afc8';
$clientSecret = '16c9bcf6476e47138c1adc87c82596ea';

$spotify = new SpotifyService($clientId, $clientSecret);
$artistId = $_GET['id'] ?? '4q3ewBCX7sLwd24euuV69X'; // Default to Bad Bunny

if ($artistId) {
    // Intentar obtener artista por ID
    $artistData = $spotify->getArtist($artistId);
    
    // Si falla por ID (o no es ID válido), intentar búsqueda por texto (retrocompatibilidad o fallback)
    if (!$artistData && !preg_match('/^[a-zA-Z0-9]{22}$/', $artistId)) {
       $artistData = $spotify->searchArtist($artistId);
       if ($artistData) $artistId = $artistData['id'];
    }
}

if ($artistData) {
    $topTracks = $spotify->getArtistTopTracks($artistData['id']);
    $albums = $spotify->getArtistAlbums($artistData['id']);

    $artista = (object)[
        'nombre_artistico' => $artistData['name'],
        'imagenes' => $artistData['images'] ?? [], // Array completo de imágenes
        'foto_url' => $artistData['images'][0]['url'] ?? 'multimedia/img/default-artist.jpg',
        'imagen_fondo_url' => ($artistData['name'] === 'Bad Bunny') ? 'multimedia/img/header_badbunny.jpg' : 'multimedia/img/default-bg.jpg',
        'generos' => $artistData['genres'] ?? [],
        // Generar biografía básica
        'biografia' => "Perfil oficial de {$artistData['name']} en nuestra plataforma. " . 
                       "Con {$artistData['followers']['total']} seguidores en Spotify, " . 
                       "se posiciona como uno de los artistas destacados del género " . 
                       (isset($artistData['genres'][0]) ? ucwords($artistData['genres'][0]) : 'Musical') . ".",
        'seguidores_formateados' => number_format($artistData['followers']['total']),
        'albumes' => [],
        'canciones_populares' => []
    ];

    // Mantener biografía detallada SOLO para Bad Bunny por si acaso el usuario quiere eso especifico
    if ($artistData['name'] === 'Bad Bunny') {
        $artista->biografia = 'Benito Antonio Martínez Ocasio, mejor conocido como Bad Bunny, es un cantante, compositor y productor puertorriqueño de trap y reggaetón. Como productor utiliza el seudónimo de San Benito, habiendo trabajado varias de sus canciones, además del álbum Viva El Perreo del dúo puertorriqueño Jowell Y Randy.
        El nombre Bad Bunny (Conejo Malo), viene de una foto de su infancia donde aparecía con un disfraz de conejo y con cara de molesto. Tomando inspiración de esa imagen, decidió crearse una cuenta de Twitter con ese seudónimo, el cual decidió mantener como nombre artístico.

Su carrera artística comenzó a principios del 2016. Mientras trabajaba como empacador en un supermercado, Bad Bunny era un artista independiente que compartía sus temas en su cuenta de Soundcloud. Su canción “Diles” fue reconocida por DJ Luian, quien lo firmó para ser el primer artista de su sello discográfico Hear This Music. Luego Bad Bunny lanzó varios temas como “Diles (Remix)” y “Tú No Vive Así”, los cuales ayudaron a despegar su carrera.';
    }

    foreach ($albums as $album) {
        $artista->albumes[] = (object)[
            'id' => $album['id'],
            'nombre_album' => $album['name'],
            'portada_url' => $album['images'][0]['url'] ?? 'multimedia/img/default-album.jpg',
            'anio' => substr($album['release_date'], 0, 4)
        ];
    }

    // Mapear canciones
    foreach ($topTracks as $track) {
        $minutes = floor($track['duration_ms'] / 60000);
        $seconds = ($track['duration_ms'] % 60000) / 1000;
        
        $artista->canciones_populares[] = (object)[
            'titulo' => $track['name'],
            'miniatura_url' => $track['album']['images'][0]['url'] ?? 'multimedia/img/default-song.jpg',
            'artista_nombre' => $track['artists'][0]['name'],
            'album_nombre' => $track['album']['name'] ?? '',
            'reproducciones_formateadas' => $track['popularity'] . ' Pop', 
            'duracion_formateada' => sprintf("%d:%02d", $minutes, $seconds)
        ];
    }
} else {
    // Fallback si falla la API
    $artista = (object)[
        'nombre_artistico' => 'Artista no encontrado',
        'biografia' => 'No se pudo conectar con Spotify.',
        'foto_url' => 'multimedia/img/default-artist.jpg',
        'imagen_fondo_url' => 'multimedia/img/default-bg.jpg',
        'seguidores_formateados' => '0',
        'albumes' => [],
        'canciones_populares' => []
    ];
}

// Incluir la vista del artista
require_once '../app/Vista/artista.php';
