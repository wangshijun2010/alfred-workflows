<?php
require('common.php');

$type = 'php';
$query = 'ab';

if (!empty($argv[1])) {
    list($type, $query) = explode(':', trim($argv[1]));
}

$SearchName = $type . 'DocSearcher';
$searcher = new $SearchName ();
$workflow = new Workflows();

$items = $searcher->search($query);
// print_r($items); exit();
foreach ($items as $item) {
    extract($item);
    $workflow->result($url, $title, $subtitle, $icon);
}

echo $workflow->toxml();

