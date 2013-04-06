<?php
require('common.php');

if (!empty($argv[1])) {
    list($type, $query) = explode(':', trim($argv[1]));
}

$FetcherName = ucwords($type) . 'SugFetcher';
$fetcher = new $FetcherName();
$workflow = new Workflows();

$items = $fetcher->fetch($query);
// print_r($items); exit();
foreach ($items as $item) {
    extract($item);
    $workflow->result($url, $title, $subtitle, $icon);
}

echo $workflow->toxml();

