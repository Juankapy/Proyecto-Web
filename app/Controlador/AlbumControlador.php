<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Services/SpotifyService.php';

class AlbumControlador extends Controller {
    
    private $spotify;
    
    public function __construct() {
        $clientId = 'cf0a28b6c1c9425bbfb697f9a072afc8';
        $clientSecret = '16c9bcf6476e47138c1adc87c82596ea';
        $this->spotify = new SpotifyService($clientId, $clientSecret);
    }
    
    public function mostrar($id) {
        $albumData = $this->spotify->getAlbum($id);
        
        if ($albumData && !isset($albumData['error'])) {
            
            $album = (object)[
                'id' => $albumData['id'],
                'nombre' => $albumData['name'],
                'portada_url' => $albumData['images'][0]['url'] ?? 'multimedia/img/default-album.jpg',
                'artista' => $albumData['artists'][0]['name'],
                'artista_id' => $albumData['artists'][0]['id'],
                'descripcion' => "Álbum lanzado en " . substr($albumData['release_date'], 0, 4) . ". " . ucfirst($albumData['album_type']) . " oficial.",
                'canciones' => []
            ];
            
            if (isset($albumData['tracks']['items'])) {
                foreach ($albumData['tracks']['items'] as $track) {
                    $minutes = floor($track['duration_ms'] / 60000);
                    $seconds = ($track['duration_ms'] % 60000) / 1000;
                    
                    $album->canciones[] = (object)[
                        'id' => $track['id'],
                        'titulo' => $track['name'],
                        'artista' => $track['artists'][0]['name'],
                        'duracion' => sprintf("%d:%02d", $minutes, $seconds)
                    ];
                }
            }
            
            require __DIR__ . '/../Vista/album.php';
        } else {
            echo "Álbum no encontrado";
        }
    }
}
