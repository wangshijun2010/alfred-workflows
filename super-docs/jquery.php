<?php
define('DS', DIRECTORY_SEPARATOR);

require('workflows.php');
require('phpQuery.php');

$wf = new Workflows();
$icon = 'icon.png';
$type = 'jquery';
$query = trim($argv[1]);

$dataDir = $wf->data();

$dataFile = $dataDir . DS . $type . '_data.php';
$rawDataFile = $dataDir . DS . $type . '_raw.html';

// echo $dataDir, PHP_EOL;
// echo $dataFile, PHP_EOL;
// echo $rawDataFile, PHP_EOL;

$baseUrl = 'http://api.jquery.com/';
$rawDataUrl = $baseUrl;

// get raw data
if (!file_exists($rawDataFile)) {
    // echo 'Fetch raw data file', PHP_EOL;
    $content = $wf->request($rawDataUrl, array(CURLOPT_TIMEOUT => 10));
    file_put_contents($rawDataFile, $content);
}

// parse data for search
if (!file_exists($dataFile)) {
    // echo 'Parse data file', PHP_EOL;
    $data = array();
    phpQuery::newDocumentFile($rawDataFile);
    $items = pq('article.post');
    foreach ($items as $item) {
        $link = pq($item)->find('.entry-title a');
        $url = pq($link)->attr('href');
        $title = pq($link)->text();
        $subtitle = trim(pq($item)->find('.entry-summary')->text());
        $type = 'function';
        $data[] = compact('url', 'title', 'subtitle', 'type');
    }

    file_put_contents($dataFile, sprintf("<?php\n return %s;", var_export($data, true)));
    // echo 'Get ', count($data), ' items', PHP_EOL;
}

// search for user query
$items = require($dataFile);

// print_r($items); exit();

foreach ($items as $item) {
    extract($item);
    if (stripos($title, $query) !== false) {
        $icon = sprintf('./icons/%s.png', $type);
        $wf->result("$url", "$title", "$subtitle", $icon);
    }
}

$results = $wf->results();
if (count($results) == 0) {
    $searchUrl = sprintf('http://api.jquery.com/?s=%s', urlencode($query));
    $wf->result($searchUrl, 'No Suggestions', 'No search suggestions found. Search for ' . $query, 'icon.png');
}

echo $wf->toxml();

