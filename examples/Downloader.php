<?php
require_once "vendor/autoload.php";

use Tidal\TidalAPI;
use GuzzleHttp\Client;

$client = new Client();
$tidal = new TidalAPI();

function convertToReadableSize($size){
    $base = log($size) / log(1024);
    $suffix = array("", "KB", "MB", "GB", "TB");
    $f_base = floor($base);
    return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
  }
  

$TrackQuality = "HIGH";

if ($TrackQuality == "LOSSLESS") {
    $Tag = "flac";
} else {
    $Tag = "mp3";
}

system("clear");

echo " PHP-TIDAL DOWNLOADER \n\n";
echo " By Quiec \n";

echo " Login \n";

$UserName = readline("Username: ");
$Password = readline("Password: ");

$tidal->logIn($UserName, $Password);

$SongID = readline("Type Track ID: ");
$TrackInfo = json_decode($tidal->getTrack($SongID), true);

$Prefix = $TrackInfo["artist"]["name"] . " - " . $TrackInfo["title"] . "." . $Tag;
$Url = json_decode($tidal->getTrackUrl($SongID, $TrackQuality), true)["url"];
$befson = 0;

$client->request(
    'GET',
    $Url,
    [
        'sink' => $Prefix,
        'progress' => function($dl_total_size, $dl_size_so_far, $ul_total_size, $ul_size_so_far) use ($Prefix){
            system("clear");

            echo " PHP-TIDAL DOWNLOADER \n\n";
            echo " By Quiec \n";

            $Yuzde = $dl_size_so_far / $dl_total_size  * 100;
            echo "Downloading: " . $Prefix . "\n\n";
            echo "[" . round($Yuzde, 1) . "] " . convertToReadableSize($dl_size_so_far) . " / " . convertToReadableSize($dl_total_size) . "\n";
        },
    ]
);

echo "✅ Downloaded";
