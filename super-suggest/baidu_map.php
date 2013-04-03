<?php

require_once('workflows.php');
$wf = new Workflows();

$query = trim($argv[1]);
$site = '百度地图';
$icon = 'A07BF7E2-9CC8-46D7-B21F-1823057D9C4D.png';

$url = sprintf("http://maps.baidu.com/su?wd=%s&cid=131&type=0&newmap=1&t=%s", urlencode($query), time());
$json = $wf->request($url);
$data = json_decode(trim($json));
$items = isset($data->s) ? $data->s : array();

// var_dump($json); print_r($items); exit();

foreach($items as $item) {
    if (strpos($item, '$$$') === 0) {
        continue;
    }

    $item = preg_replace(
        array('/\$/i', '/\s+/', '/\d+/'),
        array(' ', ' ', ''),
        $item
    );
    $item = trim($item);
    $tmp = split(' ', $item);
    $keyword = array_shift($tmp) . ' ' . array_pop($tmp);
    $url = sprintf('http://maps.baidu.com/?newmap=1&s=s%%26wd%%3D%s%%26c%%3D1', urlencode($keyword));
    $title = $item;
    $subtitle = $item;
    $wf->result("$url", "$title", "$subtitle", $icon);
}

$results = $wf->results();
if (count($results) == 0):
    $url = sprintf('http://maps.baidu.com/?newmap=1&s=s%%26wd%%3D%s%%26c%%3D1', urlencode($query));
    $wf->result($url, 'No Suggestions', 'No search suggestions found. Search 百度地图 for '.$query, $icon);
endif;

echo $wf->toxml();
