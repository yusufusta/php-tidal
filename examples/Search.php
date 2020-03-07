<?php
require_once "vendor/autoload.php";

use Tidal\TidalAPI;

$tidal = new TidalAPI();
$tidal->logIn("user", "pass");
$ara = $tidal->search("istanbul");

foreach ($ara as $sonuc) {
    echo $sonuc["artist"]["name"] . " - " . $sonuc["title"] . "\n";
}
