<?php
namespace Tidal;
use GuzzleHttp\Client;

/**
 * Class Tidal
 * @package tidalapi
 */
class TidalAPI
{
    const TIDAL_API_URL = "https://api.tidalhifi.com/v1/";
    const TIDAL_API_KEY = "kgsOOmYk3zShYrNP";

    public $sessionId;
    public $countryCode;

    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function logIn($username, $password) {
        $url = $this->urlCreate("login/username" . "?token=kgsOOmYk3zShYrNP");
        $logIn = $this->client->post($url, [
            'form_params' => [
                'username' =>  $username,
                'password'   =>  $password
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
                ]        
        ]);

        $StatusCode = $logIn->getStatusCode();
        if ($StatusCode !== 400) {
            $logInJson = json_decode($logIn->getBody(), true);
            $this->sessionId = $logInJson["sessionId"];
            $this->countryCode = $logInJson["countryCode"];
            
            echo "[i] Succesfully Logined.\n";
        }
    }
 
    public function search($field = "track", $query = NULL) {
        return json_decode($this->sendRequest("search/" . $field, ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode, "limit" => 999, "query" => $query]), true)["items"];
    }

    public function getTrackUrl ($TrackId, $Quality = "HIGH") {
        return $this->sendRequest("tracks/" . $TrackId . "/streamUrl", ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode, "soundQuality" => $Quality]);
    }

    public function getVideoUrl ($VideoId, $Quality = "MEDIUM") {
        return $this->sendRequest("videos/" . $VideoId . "/urlpostpaywall", ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode, "urlusagemode" => "STREAM", "assetpresentation" => "FULL", "videoquality" => $Quality]);
    }

    public function getVideo ($VideoId) {
        return $this->sendRequest("videos/" . $VideoId, ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getTrack ($TrackId) {
        return $this->sendRequest("tracks/" . $TrackId, ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getTrackRadio ($TrackId, $Limit = 100) {
        return $this->sendRequest('tracks/' . $TrackId . '/radio', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode, "limit" => $Limit]);
    }

    public function getGenres () {
        return $this->sendRequest('genres', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getGenreItems ($GenreId, $ContentType) {
        return $this->sendRequest('genres/' . $GenreId . '/' . $ContentType, ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getMoods () {
        return $this->sendRequest('moods', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getMoodPlaylist ($MoodId) {
        return $this->sendRequest('moods/' . $MoodId . '/playlists', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getFeatured () {
        return json_decode($this->sendRequest('promotions', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]), true)["items"];
    }

    public function getFeaturedItems ($Group, $ContentType) {
        return $this->sendRequest('featured/' . $Group . '/' . $ContentType, ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }
    
    public function getUser ($UserId) {
        return $this->sendRequest('users/' . $UserId, ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getUserPlaylist ($UserId) {
        return $this->sendRequest('users/' . $UserId . '/playlists', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getPlaylist ($PlaylistId) {
        return $this->sendRequest('playlists/' . $PlaylistId, ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getPlaylistTracks ($PlaylistId) {
        // need parse because getting tracks and videos
        return $this->sendRequest('playlists/' . $PlaylistId . '/tracks', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getPlaylistVideos ($PlaylistId) {
        return $this->sendRequest('playlists/' . $PlaylistId . '/items', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getPlaylistItems ($PlaylistId) {
        return $this->sendRequest('playlists/' . $PlaylistId . '/items', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getAlbum ($AlbumId) {
        return $this->sendRequest('albums/' . $AlbumId, ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getAlbumTracks ($AlbumId) {
        return $this->sendRequest('albums/' . $AlbumId . '/tracks', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getAlbumVideos ($AlbumId) {
        // need parse because getting tracks and videos
        return $this->sendRequest('albums/' . $AlbumId . '/items', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getAlbumItems ($AlbumId) {
        return $this->sendRequest('albums/' . $AlbumId . '/items', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getArtist ($ArtistId) {
        return $this->sendRequest('artists/' . $ArtistId, ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getArtistAlbums ($ArtistId) {
        return $this->sendRequest('artists/' . $ArtistId . '/albums', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getArtistAlbums_Singles ($ArtistId) {
        return $this->sendRequest('artists/' . $ArtistId . '/albums', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode, "filter" => "EPSANDSINGLES"]);
    }

    public function getArtistAlbums_Other ($ArtistId) {
        return $this->sendRequest('artists/' . $ArtistId . '/albums', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode, "filter" => "COMPILATIONS"]);
    }

    public function getArtistTopTracks ($ArtistId) {
        return $this->sendRequest('artists/' . $ArtistId . '/toptracks', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getArtistVideos ($ArtistId) {
        return $this->sendRequest('artists/' . $ArtistId . '/videos', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getArtistBio ($ArtistId) {
        return $this->sendRequest('artists/' . $ArtistId . '/bio', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getArtistSimilar ($ArtistId) {
        return $this->sendRequest('artists/' . $ArtistId . '/similar', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getArtistRadio ($ArtistId) {
        return $this->sendRequest('artists/' . $ArtistId . '/radio', ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode]);
    }

    public function getAlbumPhoto ($AlbumPhotoId, $Size = 1080) {
        return "https://resources.tidal.com/images/" . $AlbumPhotoId . "/" . $Size . "x" . $Size . ".jpg";
    }



    private function sendRequest($Method, $Params, $Headers = NULL) {
        if (!isset($this->sessionId, $this->countryCode)) {
            echo "Please first log in, You Didnt Login\n";
        } else {
            $url = $this->urlCreate($Method);
            return $sendReq = $this->client->get($url, 
            [
                'query' => $Params,
                'headers' => $Headers
            ])->getBody();    
        }
    }

    private function urlCreate($method) {
        return self::TIDAL_API_URL . $method;
    }
}