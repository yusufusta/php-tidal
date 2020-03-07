# php-tidal

Unofficial PHP API for TIDAL music streaming service.

## Installation

Install from [Composer](https://getcomposer.org/) using `composer`:

``` bash
$ composer require quiec/php-tidal dev-master
```
Also you can install without composer (for shared hosts). [Click here](https://github.com/Quiec/php-tidal/releases/download/1.0.0/tidalphp.zip) for download zip.

## Example usage

[+ Simple Downloader](https://github.com/Quiec/php-tidal/blob/master/examples/Downloader.php)

``` php
<?php
require_once "vendor/autoload.php";

use Tidal\TidalAPI;

$tidal = new TidalAPI();
$tidal->logIn("user", "pass");
$ara = $tidal->search("istanbul");

foreach ($ara as $sonuc) {
    echo $sonuc["artist"]["name"] . " - " . $sonuc["title"] . "\n";
}
```

## Documentation

I will add.
