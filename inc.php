<?php



require_once(__DIR__ . "/vendor/autoload.php");


    
$directory = __DIR__ . "/autoinc/";

foreach (glob($directory . "*.php") as $filename) {
    echo $filename . PHP_EOL;
    require_once $filename;
}