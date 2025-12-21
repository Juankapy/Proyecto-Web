<?php

class GeniusControlador {
    
    private $tokenAcceso;

    public function __construct() {
        // Token proporcionado por el usuario
        $this->tokenAcceso = 'aZwJbhSeaNw7Lb0mYVgla6egpl-fbWHf5tyFKSOGfUVj0jItjyxKE4AJT_dDpXIT';
    }

    /**
     * Realiza una petición GET a la API de Genius
     * @param string $url La URL completa del endpoint
     * @return array|null La respuesta decodificada o null si falla
     */
    private function realizarPeticion($url) {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->tokenAcceso
            ],
            CURLOPT_SSL_VERIFYPEER => false, // Desactivar verificación SSL para entorno local si es necesario
            CURLOPT_TIMEOUT => 30
        ]);

        $respuesta = curl_exec($ch);
        $codigoHttp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            echo 'Error cURL: ' . curl_error($ch) . PHP_EOL;
        }

        curl_close($ch);

        if ($codigoHttp === 200) {
            return json_decode($respuesta, true);
        }

        return null;
    }

    /**
     * Verifica la conexión con la API realizando una búsqueda de prueba
     */
    public function verificarConexion() {
        echo "Iniciando verificación de conexión con Genius API..." . PHP_EOL;

        $terminoBusqueda = "Queen Bohemian Rhapsody";
        $url = "https://api.genius.com/search?q=" . urlencode($terminoBusqueda);

        $datos = $this->realizarPeticion($url);

        if ($datos && isset($datos['response']['hits']) && count($datos['response']['hits']) > 0) {
            $primerResultado = $datos['response']['hits'][0]['result'];
            echo "¡Éxito! Conexión establecida correctamente." . PHP_EOL;
            echo "Primera canción encontrada: " . $primerResultado['full_title'] . PHP_EOL;
            echo "Artista: " . $primerResultado['artist_names'] . PHP_EOL;
        } else {
            echo "Error: No se pudo conectar a la API o no se encontraron resultados." . PHP_EOL;
            if (!$datos) {
                echo "La respuesta de la API fue nula." . PHP_EOL;
            }
        }
    }

    /**
     * Busca una canción en la API de Genius y devuelve el primer resultado
     * @param string $termino Término de búsqueda (ej: "Queen Bohemian Rhapsody")
     * @return array|null Datos de la canción o null si no se encuentra
     */
    public function buscarCancion($termino) {
        $url = "https://api.genius.com/search?q=" . urlencode($termino);
        $datos = $this->realizarPeticion($url);

        if ($datos && isset($datos['response']['hits']) && count($datos['response']['hits']) > 0) {
            return $datos['response']['hits'][0]['result'];
        }

        return null;
    }

    /**
     * Obtiene la letra real de la canción haciendo scraping a la URL de Genius
     * @param string $url URL de la canción en Genius
     * @return string|null HTML de la letra o null si falla
     */
    public function obtenerLetra($url) {
        // Simular navegador real para evitar bloqueos simples
        $user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $user_agent,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true
        ]);

        $html = curl_exec($ch);
        curl_close($ch);

        if (!$html) return null;

        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // Suprimir advertencias de HTML mal formado
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        // Buscar contenedores de letras (modern Genius frontend)
        $nodos = $xpath->query('//div[@data-lyrics-container="true"]');

        if ($nodos->length === 0) return null;

        $htmlLetra = '';
        foreach ($nodos as $nodo) {
            // Obtener el HTML interno
            $innerHtml = $dom->saveHTML($nodo);
            
            // Limpiar etiquetas no deseadas, dejando solo saltos de línea para mantener estructura
            $textoLimpio = strip_tags($innerHtml, '<br>');
            
            $htmlLetra .= $textoLimpio . '<br>';
        }

        // Limpieza específica de metadatos de Genius (encabezados de colaboradores, etc.)
        // Patrón: Elimina desde el inicio hasta encontrar "Lyrics" o "[Letra" si va precedido de Contributors, etc.
        $htmlLetra = preg_replace('/^\s*\d+\s*Contributors.*?[\r\n]+.*?(?=\[)/s', '', $htmlLetra);
        
        // Limpiar también el "Read More" si queda suelto o cualquier texto antes del primer corchete si parece basura
        // A veces Genius pone "X Contributors ... Lyrics [Intro]"
        // Vamos a intentar quitar todo antes del primer bloque entre corchetes si detectamos "Contributors"
        // Vamos a intentar quitar todo antes del primer bloque entre corchetes si detectamos "Contributors"
        if (stripos($htmlLetra, 'Contributors') !== false) {
             $htmlLetra = preg_replace('/^.*?Contributors.*?((?=\[)|$)/s', '', $htmlLetra);
        }

        // Eliminar específicamente etiqueta inicial tipo [Letra de "Moscow Mule"] o similar
        $htmlLetra = preg_replace('/^\s*\[(Letra|Lyrics)[^\]]*\]\s*/i', '', $htmlLetra);

        // Envolver etiquetas como [Intro], [Coro], [Verso] en un span para estilizarlas
        $htmlLetra = preg_replace('/(\[.*?\])/', '<span class="etiqueta-cancion">$1</span>', $htmlLetra);

        // Limpiar saltos de línea iniciales acumulados
        $htmlLetra = preg_replace('/^(\s*<br\s*\/?>\s*)+/i', '', $htmlLetra);

        return $htmlLetra;
    }
}
