<?php
define('DS', DIRECTORY_SEPARATOR);

require('workflows.php');
require('phpQuery.php');

$wf = new Workflows();
$icon = 'icon.png';

$dataDir = $wf->data();

$dataFile = $dataDir . DS . 'php_data.php';
$rawDataFile = $dataDir . DS . 'php_raw.html';

// echo $dataDir, PHP_EOL;
// echo $dataFile, PHP_EOL;
// echo $rawDataFile, PHP_EOL;

$baseUrl = 'http://www.php.net/manual/en/';
$rawDataUrl = $baseUrl . 'indexes.functions.php';

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
    $groups = pq('ul.index-for-refentry')->find('li.gen-index');
    foreach ($groups as $group) {
        $items = pq($group)->find('li');
        foreach ($items as $item) {
            $link = pq($item)->find('a');
            $url = $baseUrl . pq($link)->attr('href');
            $title = pq($link)->text();
            $subtitle = pq($item)->text();
            $subtitle = trim(str_replace($title, '', $subtitle), '- ');
            $type = 'function';
            if (strpos($title, '::')) {
                $type = 'method';
            }
            $data[] = compact('url', 'title', 'subtitle', 'type');
        }
    }

    file_put_contents($dataFile, sprintf("<?php\n return %s;", var_export($data, true)));
    // echo 'Get ', count($data), ' items', PHP_EOL;
}

// search for user query
$items = require($dataFile);
$query = trim($argv[1]);

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
    $searchUrl = sprintf('http://www.php.net/manual-lookup.php?pattern=%s&lang=en&scope=quickref', urlencode($query));
    $wf->result($searchUrl, 'No Suggestions', 'No search suggestions found. Search for ' . $query, 'icon.png');
}

echo $wf->toxml();

