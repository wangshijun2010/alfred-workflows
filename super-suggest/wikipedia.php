<?php
require_once('workflows.php');
$wf = new Workflows();
$icon = 'DEF530A1-7787-47C3-8E18-0123C9550184.png';

$query = trim($argv[1]);
$json = $wf->request("http://en.wikipedia.org/w/api.php?format=json&action=opensearch&search=".urlencode($query));
$items = json_decode($json);
if (!empty($items)) {
    array_shift($items);
    $items = !empty($items[0]) ? $items[0] : array();
} else {
    $items = array();
}

// var_dump($json); var_dump($items); exit();

foreach($items as $item) {
    $slug = preg_replace('/\s+/', '_', $item);
    $url = sprintf('http://en.wikipedia.org/wiki/%s', $slug);
    $title = $item;
    $subtitle = sprintf('查看词条: %s', $title);
    $wf->result("$url", "$title", "$subtitle", $icon);
}

// Fallback search
$uuid = uniqid();
$title = sprintf('在Wikipedia上搜索:%s', $query);
$subtitle = sprintf('查看与%s有关的所有结果', $query);
$url = sprintf('http://en.wikipedia.org/w/index.php?search=%s&fulltext=1', $query);
$wf->result("$url", "$title", "$subtitle", $icon);

$results = $wf->results();
if (count($results) == 0):
    $wf->result($query, 'No Suggestions', 'No search suggestions found. Search Wikipedia for '.$query, $icon);
endif;

echo $wf->toxml();
