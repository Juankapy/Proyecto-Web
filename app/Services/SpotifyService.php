<?php

class SpotifyService {
    private $clientId;
    private $clientSecret;
    private $accessToken;

    public function __construct($clientId, $clientSecret) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->authenticate();
    }

    private function authenticate() {
        $auth_url = 'https://accounts.spotify.com/api/token';
        $ch = curl_init($auth_url);
        
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret)
            ],
            CURLOPT_POSTFIELDS     => 'grant_type=client_credentials',
            CURLOPT_SSL_VERIFYPEER => false, // Set to false for local dev if needed, ideally true
            CURLOPT_TIMEOUT        => 30
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 200) {
            $data = json_decode($response, true);
            $this->accessToken = $data['access_token'] ?? null;
        }
    }

    private function request($url) {
        if (!$this->accessToken) return null;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json'
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 30
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 200) {
            return json_decode($response, true);
        }
        return null;
    }

    public function searchArtist($name) {
        $query = urlencode($name);
        $url = "https://api.spotify.com/v1/search?q={$query}&type=artist&limit=1";
        $result = $this->request($url);
        return $result['artists']['items'][0] ?? null;
    }

    public function getArtistTopTracks($artistId, $market = 'ES') {
        $url = "https://api.spotify.com/v1/artists/{$artistId}/top-tracks?market={$market}";
        $result = $this->request($url);
        return $result['tracks'] ?? [];
    }

    public function getArtistAlbums($artistId, $limit = 5) {
        $url = "https://api.spotify.com/v1/artists/{$artistId}/albums?include_groups=album&limit={$limit}";
        $result = $this->request($url);
        return $result['items'] ?? [];
    }

    public function getAlbumTracks($albumId) {
        $url = "https://api.spotify.com/v1/albums/{$albumId}/tracks?limit=50";
        $result = $this->request($url);
        return $result['items'] ?? [];
    }

    public function getAlbum($albumId) {
        $url = "https://api.spotify.com/v1/albums/{$albumId}";
        return $this->request($url);
    }

    public function getTrack($trackId) {
        $url = "https://api.spotify.com/v1/tracks/{$trackId}";
        return $this->request($url);
    }

    public function getArtist($artistId) {
        $url = "https://api.spotify.com/v1/artists/{$artistId}";
        return $this->request($url);
    }

    public function getAccessToken() {
        return $this->accessToken;
    }
}
