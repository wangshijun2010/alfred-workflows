<?php

require_once('workflows.php');
$wf = new Workflows();

$query = trim($argv[1]);
$site = '京东商城';
$icon = '769951D2-209E-4EF1-8665-6025558F8EDE.png';

$jsonp = $wf->request("http://dd.search.jd.com/?callback=jsonp&key=".urlencode($query));
$jsonp = str_replace('jsonp(', '', $jsonp);
$json = trim(trim($jsonp, ';'), ')');
$items = json_decode($json);

foreach($items as $item) {
    // category
    if (!empty($item->cid)) {
        $url = sprintf('http://search.jd.com/Search?keyword=%s&enc=utf-8&suggest=0&area=1', $item->keyword);
        $title = $item->keyword;
        $subtitle = sprintf('与%s相关的商品约%d件商品', $item->keyword, $item->oamount);
        $wf->result("$url", "$title", "$subtitle", $icon);

        $url = sprintf('http://search.jd.com/Search?keyword=%s&enc=utf-8&suggest=0&cid3=%d&area=1', $item->keyword, $item->cid);
        $title = sprintf('[分类]: %s', $item->cname);
        $subtitle = sprintf('与%s相关的商品约%d件商品', $item->keyword, $item->amount);
        $wf->result("$url", "$title", "$subtitle", $icon);

    // keyword
    } else {
        $url = sprintf('http://search.jd.com/Search?keyword=%s&enc=utf-8&suggest=0&area=1', $item->keyword);
        $title = $item->keyword;
        $subtitle = sprintf('与%s相关的商品约%d件商品', $item->keyword, $item->amount);
        $wf->result("$url", "$title", "$subtitle", $icon);
    }
}

$results = $wf->results();
if (count($results) == 0):
    $url = sprintf('http://search.jd.com/Search?keyword=%s&enc=utf-8&suggest=0&area=1', $query);
    $wf->result($url, 'No Suggestions', 'No search suggestions found. Search 京东商城 for '.$query, $icon);
endif;

echo $wf->toxml();
