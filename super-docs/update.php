<?php
require('common.php');

$type = 'php';

if (!empty($argv[1])) {
    $type = trim($argv[1]);
}

$name = ucwords($type) . 'DocIndexer';
$indexer = new $name();
$items = $indexer->index();
print_r($items);
echo sprintf('Get %d items for %s', count($items), $type), PHP_EOL;
