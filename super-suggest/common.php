<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS);
define('LIB', ROOT . 'lib' . DS);
define('VENDOR', ROOT . 'vendor' . DS);
define('FETCHER', LIB . 'Fetcher' . DS);
define('ICON', 'icons' . DS);

spl_autoload_register(function($class) {
    $ext = '.php';
    $filePaths = array(
        LIB . $class . $ext,
        FETCHER . $class . $ext,
        VENDOR . $class . $ext,
    );
    foreach ($filePaths as $filePath) {
        if (file_exists($filePath)) {
            include($filePath);
            return true;
        }
    }
    return false;
});

