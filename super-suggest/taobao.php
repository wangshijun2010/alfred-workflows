<?php

require_once('workflows.php');
$wf = new Workflows();

$query = trim($argv[1]);
$site = '淘宝';
$icon = '4B45080B-A891-42BB-815F-4ECEFA99B859.png';

$jsonp = $wf->request("http://suggest.taobao.com/sug?code=utf-8&callback=jsonp&q=".urlencode($query));
$jsonp = str_replace('jsonp(', '', trim($jsonp));
$json = trim(trim($jsonp, ';'), ')');
$data = json_decode($json);
$items = isset($data->result) ? $data->result : array();

// print_r($items); exit();

foreach($items as $item) {
    list($keyword, $amount) = $item;
    $url = sprintf('http://s.taobao.com/search?q=%s', $keyword);
    $title = $keyword;
    $subtitle = sprintf('与%s相关的商品约%d件商品', $keyword, $amount);
    $wf->result("$url", "$title", "$subtitle", $icon);
}

$results = $wf->results();
if (count($results) == 0):
    $wf->result($query, 'No Suggestions', 'No search suggestions found. Search 淘宝 for '.$query, $icon);
endif;

echo $wf->toxml();
