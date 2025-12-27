<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Services/SpotifyService.php';

class ArtistaControlador extends Controller {
    
    private $spotify;
    
    public function __construct() {
        // Credenciales deberían estar en config, pero las mantenemos aquí por consistencia con el proyecto actual
        $clientId = 'cf0a28b6c1c9425bbfb697f9a072afc8';
        $clientSecret = '16c9bcf6476e47138c1adc87c82596ea';
        $this->spotify = new SpotifyService($clientId, $clientSecret);
    }
    
    public function mostrar($id) {
        $artistData = null;
        
        // Determinar si es ID o búsqueda por texto
        if (preg_match('/^[a-zA-Z0-9]{22}$/', $id)) {
            $artistData = $this->spotify->getArtist($id);
        }
        
        // Fallback or search if not found/valid ID
        if (!$artistData) {
            $artistData = $this->spotify->searchArtist($id);
            if ($artistData) $id = $artistData['id'];
        }
        
        if ($artistData) {
            $topTracks = $this->spotify->getArtistTopTracks($artistData['id']);
            $albums = $this->spotify->getArtistAlbums($artistData['id']);
            
            // Construir objeto de vista optimizado
            $artista = (object)[
                'id' => $artistData['id'],
                'nombre_artistico' => $artistData['name'],
                // Imagen Hero: La más grande (0)
                'imagen_hero' => $artistData['images'][0]['url'] ?? 'multimedia/img/default-bg.jpg',
                // Imagen Perfil: La segunda más grande (1) si existe, sino la primera
                'imagen_perfil' => $artistData['images'][1]['url'] ?? ($artistData['images'][0]['url'] ?? 'multimedia/img/default-artist.jpg'),
                'seguidores' => number_format($artistData['followers']['total']),
                'biografia' => $this->generarBiografia($artistData),
                'albumes' => [],
                'canciones_populares' => [],
                // Datos Mock para estilo Genius
                'aka' => 'Artist', // Placeholder para "AKA"
                'contributors' => rand(2, 15),
                'is_verified' => true
            ];
            
            // Procesar Álbumes
            foreach ($albums as $album) {
                $artista->albumes[] = (object)[
                    'id' => $album['id'],
                    'nombre_album' => $album['name'],
                    'portada_url' => $album['images'][0]['url'] ?? 'multimedia/img/default-album.jpg',
                    'anio' => substr($album['release_date'], 0, 4)
                ];
            }
            
            // Procesar Canciones
            foreach ($topTracks as $track) {
                $minutes = floor($track['duration_ms'] / 60000);
                $seconds = ($track['duration_ms'] % 60000) / 1000;
                
                $artista->canciones_populares[] = (object)[
                    'id' => $track['id'],
                    'titulo' => $track['name'],
                    'miniatura_url' => $track['album']['images'][0]['url'] ?? 'multimedia/img/default-song.jpg', // Para las cards estilo Genius
                    'artista_nombre' => $track['artists'][0]['name'],
                    'album_nombre' => $track['album']['name'] ?? '',
                    'vistas_formateadas' => $this->formatearVistas($track['popularity']), // Simulado basado en popularidad
                    'duracion_formateada' => sprintf("%d:%02d", $minutes, $seconds)
                ];
            }
            
            // Cargar vista
            require __DIR__ . '/../Vista/artista.php';
            
        } else {
            // Manejar error 404 o artista no encontrado
            echo "Artista no encontrado";
        }
    }
    
    private function generarBiografia($data) {
        // En un caso real, esto vendría de la BD o API de Genius
        // Por ahora mantenemos la lógica simulada pero limpia
         return "Perfil oficial de {$data['name']} en nuestra plataforma. " . 
               "Con {$data['followers']['total']} seguidores en Spotify, " . 
               "se posiciona como uno de los artistas destacados del género " . 
               (isset($data['genres'][0]) ? ucwords($data['genres'][0]) : 'Musical') . ".";
    }
    
    private function formatearVistas($popularity) {
        // Simular un número de vistas estilo "5.2M" basado en la popularidad de Spotify (0-100)
        // Solo para efectos visuales de la maqueta Genius
        $base = $popularity * 10000;
        if ($base > 1000000) {
            return round($base / 1000000, 1) . 'M';
        } elseif ($base > 1000) {
            return round($base / 1000, 1) . 'K';
        }
        return $base;
    }
}
