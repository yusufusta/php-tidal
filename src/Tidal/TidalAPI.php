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
        require 'vendor/autoload.php';
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
 
    public function search($query = NULL) {
        return json_decode($this->sendRequest("search/tracks", ["sessionId" => $this->sessionId, "countryCode" => $this->countryCode, "limit" => 999, "query" => $query]), true)["items"];
    }

    private function sendRequest($Method, $Params, $Headers = NULL) {
        $url = $this->urlCreate($Method);
        return $sendReq = $this->client->get($url, 
        [
            'query' => $Params,
            'headers' => $Headers
        ])->getBody();
    }

    private function urlCreate($method) {
        return self::TIDAL_API_URL . $method;
    }
}