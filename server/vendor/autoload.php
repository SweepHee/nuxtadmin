<?php

spl_autoload_register(function ($path) {
$path = str_replace('\\','/',$path);
$paths = explode('/', $path);
if (preg_match('/model/', strtolower($paths[1]))) {
    $className = 'model';
} else if (preg_match('/controller/',strtolower($paths[1]))) {
    $className = 'controller';
} else {
    $className = $paths[1];
}

$loadpath =  $paths[0].'/'.$className.'/'.$paths[2].'.php';

if (!file_exists($loadpath)) {
    echo " --- autoload : file not found. ($loadpath) ";
    exit();
}

require_once $loadpath;

});