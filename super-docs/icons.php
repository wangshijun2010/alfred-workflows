<?php
define('DS', DIRECTORY_SEPARATOR);

require('workflows.php');
require('phpQuery.php');

$wf = new Workflows();
$url = 'http://kapeli.com/types/';
$dir = __DIR__ . DS . 'icons' . DS;
$ext = '.png';

if (!is_dir($dir)) {
    mkdir($dir);
}

phpQuery::newDocumentFile($url);
$types = pq('td');
foreach ($types as $type) {
    $img = str_replace('..', 'http://kapeli.com', pq($type)->find('img')->attr('src'));
    $name = trim(strtolower(pq($type)->text()));
    echo $name, ': ', $img, PHP_EOL;
    file_put_contents($dir . $name . $ext, file_get_contents($img));
}
