<?php

require_once('workflows.php');
$wf = new Workflows();

$query = trim($argv[1]);
$site = '百度';
$icon = 'A07BF7E2-9CC8-46D7-B21F-1823057D9C4D.png';

$url = sprintf("http://suggestion.baidu.com/su?wd=%s&p=3&cb=callback&from=superpage&t=%d", urlencode($query), time());
$response = $wf->request($url);
$response = iconv('GBK', 'UTF-8', $response);
$response = str_replace('callback(', '', trim($response));
$json = trim(trim($response, ';'), ')}');
$json = substr($json, strpos($json, 's:') + 2);
$items = json_decode($json);

// var_dump($json); var_dump($items); exit();

foreach($items as $item) {
    $url = sprintf('http://www.baidu.com/s?wd=%s&&ie=utf-8', urlencode($item));
    $title = $item;
    $subtitle = $item;
    $wf->result("$url", "$title", "$subtitle", $icon);
}

$results = $wf->results();
if (count($results) == 0):
    $url = sprintf('http://www.baidu.com/s?wd=%s&&ie=utf-8', urlencode($query));
    $wf->result($url, 'No Suggestions', 'No search suggestions found. Search 百度 for '.$query, $icon);
endif;

echo $wf->toxml();
