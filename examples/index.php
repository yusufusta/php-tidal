<?php
require __DIR__.'/src/Tidal/TidalAPI.php';

use Tidal\TidalAPI;

$tidal = new TidalAPI();
$tidal->logIn("user", "pass");
$ara = $tidal->search("istanbul");

foreach ($ara as $sonuc) {
    echo $sonuc["artist"]["name"] . " - " . $sonuc["title"] . "\n";
}
