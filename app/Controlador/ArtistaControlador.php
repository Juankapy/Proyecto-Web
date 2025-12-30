<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Services/SpotifyService.php';
require_once __DIR__ . '/GeniusControlador.php';

class ArtistaControlador extends Controller {
    
    private $spotify;
    private $genius;
    
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
                'generos' => $artistData['genres'] ?? [],
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

            // --- NUEVO: Obtener TODAS las canciones (de los álbumes) ---
            $todasLasCanciones = [];
            $trackIds = []; // Para evitar duplicados

            // Agregar primero las populares
            foreach ($artista->canciones_populares as $popSong) {
                $todasLasCanciones[] = $popSong;
                $trackIds[$popSong->id] = true;
            }

            // Recorrer los primeros 10 álbumes para obtener más canciones
            $albumsLimitados = array_slice($albums, 0, 10);
            
            foreach ($albumsLimitados as $album) {
                $albumTracks = $this->spotify->getAlbumTracks($album['id']);
                
                foreach ($albumTracks as $track) {
                    if (!isset($trackIds[$track['id']])) {
                        $todasLasCanciones[] = (object)[
                            'id' => $track['id'],
                            'titulo' => $track['name'],
                            'miniatura_url' => $album['images'][2]['url'] ?? ($album['images'][0]['url'] ?? 'multimedia/img/default-song.jpg'),
                            'artista_nombre' => $track['artists'][0]['name'],
                            'album_nombre' => $album['name'],
                            'vistas_formateadas' => '', // No disponible en endpoint de album-tracks
                            'duracion_formateada' => '' // No necesario para búsqueda simple
                        ];
                        $trackIds[$track['id']] = true;
                    }
                }
            }
            
            $artista->todas_las_canciones = $todasLasCanciones;
            
            // Cargar vista
            require __DIR__ . '/../Vista/artista.php';
            
        } else {
            // Manejar error 404 o artista no encontrado
            echo "Artista no encontrado";
        }
    }
    
    private function generarBiografia($data) {
        // Fetch Real Biography from Genius
        if (!isset($this->genius)) {
            $this->genius = new GeniusControlador();
        }

        $nombreArtista = $data['name'];
        $geniusId = $this->genius->obtenerIdArtista($nombreArtista);
        
        if ($geniusId) {
            $bio = $this->genius->obtenerBiografia($geniusId);
            if ($bio) {
                // Genius 'plain' description might still contain some markdown, but should be mostly text.
                // We'll trust it's what we want. 
                return $bio;
            }
        }
        
        // Fallback if Genius fails
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
