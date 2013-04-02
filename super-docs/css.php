<?php
define('DS', DIRECTORY_SEPARATOR);

require('workflows.php');
require('phpQuery.php');

$wf = new Workflows();
$icon = 'icon.png';
$type = 'css';
$query = trim($argv[1]);

$dataDir = $wf->data();

$dataFile = $dataDir . DS . $type . '_data.php';
$rawDataFile = $dataDir . DS . $type . '_raw.html';

// echo $dataDir, PHP_EOL;
// echo $dataFile, PHP_EOL;
// echo $rawDataFile, PHP_EOL;

$baseUrl = 'https://developer.mozilla.org';
$rawDataUrl = $baseUrl . '/en-US/docs/CSS/CSS_Reference';

// get raw data
if (!file_exists($rawDataFile)) {
    echo 'Fetch raw data file', PHP_EOL;
    $content = $wf->request($rawDataUrl, array(CURLOPT_TIMEOUT => 10));
    file_put_contents($rawDataFile, $content);
}

// parse data for search
if (!file_exists($dataFile)) {
    echo 'Parse data file', PHP_EOL;
    $data = array();
    phpQuery::newDocumentFile($rawDataFile);

    // css properties
    echo 'Parse css properties', PHP_EOL;
    $items = pq('div.index')->find('a');
    foreach ($items as $prop) {
        $url = $baseUrl . pq($prop)->attr('href');
        $title = trim(pq($prop)->text());
        $subtitle = 'property:' . $title;
        $type = 'property';
        $data[] = compact('url', 'title', 'subtitle', 'type');
    }

    // css selectors
    echo 'Parse css selector', PHP_EOL;
    $items = pq('#Selectors')->next('ul');
    $items = pq($items)->find('li');
    foreach ($items as $item) {
        $url = $baseUrl . pq($item)->find('a')->attr('href');
        $title = trim(pq($item)->find('a')->text());
        $subtitle = $title;
        $type = 'section';
        $data[] = compact('url', 'title', 'subtitle', 'type');
    }

    // css tutorials
    echo 'Parse css tutorial', PHP_EOL;
    $items = pq('#CSS3_Tutorials')->next('p')->next('ul');
    $items = pq($items)->find('a');
    foreach ($items as $item) {
        $url = $baseUrl . pq($item)->attr('href');
        $title = trim(pq($item)->text());
        $subtitle = 'tutorial: ' . $title;
        $type = 'section';
        $data[] = compact('url', 'title', 'subtitle', 'type');
    }

    // css concepts
    echo 'Parse css concepts', PHP_EOL;
    $items = pq('#Concepts')->next('ul');
    $items = pq($items)->find('a');
    foreach ($items as $item) {
        $url = $baseUrl . pq($item)->attr('href');
        $title = trim(pq($item)->text());
        $subtitle = 'concept: ' . $title;
        $type = 'define';
        $data[] = compact('url', 'title', 'subtitle', 'type');
    }

    file_put_contents($dataFile, sprintf("<?php\n return %s;", var_export($data, true)));
    echo 'Get ', count($data), ' items', PHP_EOL;
}

// search for user query
$items = require($dataFile);

foreach ($items as $item) {
    extract($item);
    if (stripos($title, $query) !== false) {
        $icon = sprintf('./icons/%s.png', $type);
        $wf->result("$url", "$title", "$subtitle", $icon);
    }
}

$results = $wf->results();
if (count($results) == 0) {
    $searchUrl = sprintf('https://developer.mozilla.org/en-US/search?q=%s', urlencode($query));
    $wf->result($searchUrl, 'No Suggestions', 'No search suggestions found. Search for ' . $query, 'icon.png');
}

echo $wf->toxml();

