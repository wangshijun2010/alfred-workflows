<?php
require_once('workflows.php');
$wf = new Workflows();
$icon = '1BF8E751-B99A-4F82-AF60-0C67BB0EDB88.png';

$query = trim($argv[1]);
$xml = $wf->request("http://google.com/complete/search?output=toolbar&q=".urlencode($query));
$xml = simplexml_load_string(utf8_encode($xml));

foreach($xml as $sugg) {
    $data = $sugg->suggestion->attributes()->data;
    $wf->result("$data", "$data", 'Search Google for ' . $data, $icon);
}

$results = $wf->results();
if (count($results) == 0):
    $wf->result($query, 'No Suggestions', 'No search suggestions found. Search Google for '.$query, $icon);
endif;

echo $wf->toxml();
