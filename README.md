# php-tidal

Unofficial PHP API for TIDAL music streaming service.

## Installation

Install from [Composer](https://getcomposer.org/) using `composer`:

``` bash
$ composer require quiec/php-tidal
```

## Example usage

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
