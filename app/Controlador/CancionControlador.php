<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Services/SpotifyService.php';
require_once __DIR__ . '/GeniusControlador.php';

class CancionControlador extends Controller {
    
    private $spotify;
    private $genius;
    
    public function __construct() {
        // Credenciales (Idealmente en config)
        $clientId = 'cf0a28b6c1c9425bbfb697f9a072afc8';
        $clientSecret = '16c9bcf6476e47138c1adc87c82596ea';
        $this->spotify = new SpotifyService($clientId, $clientSecret);
        $this->genius = new GeniusControlador();
    }
    
    public function mostrar($id) {
        // Fetch track data
        $trackData = $this->spotify->getTrack($id);
        
        if ($trackData && !isset($trackData['error'])) {
            $minutes = floor($trackData['duration_ms'] / 60000);
            $seconds = ($trackData['duration_ms'] % 60000) / 1000;
            
            // Genius Lyrics Fetching
            $letraHtml = '';
            $geniusUrl = '';
            
            // Search Genius
            $query = $trackData['name'] . ' ' . $trackData['artists'][0]['name'];
            $geniusData = $this->genius->buscarCancion($query);
            
            if ($geniusData && isset($geniusData['url'])) {
                $geniusUrl = $geniusData['url'];
                $letraHtml = $this->genius->obtenerLetra($geniusUrl);
            }

            // Build View Object
            $cancion = (object)[
                'id' => $trackData['id'],
                'titulo' => $trackData['name'],
                'portada_url' => $trackData['album']['images'][0]['url'] ?? 'multimedia/img/default-song.jpg',
                'artista' => $trackData['artists'][0]['name'],
                'album' => $trackData['album']['name'],
                'duracion' => sprintf("%d:%02d", $minutes, $seconds),
                // Mock Data for Genius Style
                'creditos' => [
                    ['rol' => 'Written By', 'nombres' => $trackData['artists'][0]['name']],
                    ['rol' => 'Produced By', 'nombres' => 'Producer Name'],
                    ['rol' => 'Label', 'nombres' => 'Record Label']
                ],
                'letra_html' => $letraHtml, // Real lyrics if found
                'url_genius' => $geniusUrl,
                'letra_simulada' => [ // Fallback
                    "No se pudo cargar la letra automáticamente desde Genius.",
                    "Intenta visitar el enlace oficial: " . ($geniusUrl ?: "No disponible")
                ]
            ];
            
            require __DIR__ . '/../Vista/cancion.php';
        } else {
            echo "Canción no encontrada";
        }
    }
}
