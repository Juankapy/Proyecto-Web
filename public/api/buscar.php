<?php
/**
 * API Endpoint para búsqueda en vivo
 * Busca artistas y canciones usando Spotify API
 */

header('Content-Type: application/json');

require_once '../../app/Services/SpotifyService.php';

$clientId = 'cf0a28b6c1c9425bbfb697f9a072afc8';
$clientSecret = '16c9bcf6476e47138c1adc87c82596ea';

$termino = $_GET['termino'] ?? '';

// Validar que el término no esté vacío
if (empty(trim($termino))) {
    echo json_encode([]);
    exit;
}

$spotify = new SpotifyService($clientId, $clientSecret);
$resultados = [];

try {
    // Buscar artistas
    $urlArtistas = "https://api.spotify.com/v1/search?q=" . urlencode($termino) . "&type=artist&limit=3";
    $artistasData = $spotify->searchArtist($termino);
    
    if ($artistasData) {
        $resultados[] = [
            'nombre' => $artistasData['name'],
            'tipo' => 'artista',
            'url' => 'test-artista.php?id=' . $artistasData['id'],
            'imagen' => $artistasData['images'][0]['url'] ?? 'multimedia/img/default-artist.jpg'
        ];
    }
    
    // Buscar canciones usando la API directamente
    $token = $spotify->getAccessToken();
    if ($token) {
        $urlCanciones = "https://api.spotify.com/v1/search?q=" . urlencode($termino) . "&type=track&limit=4";
        $ch = curl_init($urlCanciones);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 10
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 200) {
            $cancionesData = json_decode($response, true);
            if (isset($cancionesData['tracks']['items'])) {
                foreach (array_slice($cancionesData['tracks']['items'], 0, 4) as $track) {
                    $resultados[] = [
                        'nombre' => $track['name'],
                        'artista' => $track['artists'][0]['name'] ?? '',
                        'tipo' => 'cancion',
                        'url' => 'test-cancion.php?titulo=' . urlencode($track['name']) . 
                                '&artista=' . urlencode($track['artists'][0]['name'] ?? '') .
                                '&album=' . urlencode($track['album']['name'] ?? '') .
                                '&duracion=3:00',
                        'imagen' => $track['album']['images'][0]['url'] ?? 'multimedia/img/default-song.jpg'
                    ];
                }
            }
        }
    }
    
    // Limitar a 5 resultados totales
    $resultados = array_slice($resultados, 0, 5);
    
} catch (Exception $e) {
    error_log("Error en búsqueda: " . $e->getMessage());
    $resultados = [];
}

echo json_encode($resultados);
