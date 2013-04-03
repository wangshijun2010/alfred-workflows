<?php
require('workflows.php');
$w = new Workflows();

$site = 'Amazon';
$icon = '2558AB62-161A-453C-BB59-40619C85E8DB.png';

$query = trim($argv[1]);
$url = "http://completion.amazon.com/search/complete?method=completion&q=".urlencode($query)."&search-alias=aps&mkt=1&x=updateISSCompletion&noCacheIE=1295031912518";
$str = $w->request($url);

$str = substr($str, strlen('completion = ["'.$query.'",['));
$str = substr($str, 0, strpos($str, ']'));

if ($str == "") {
    $w->result($query, 'No Suggestions', 'No search suggestions found. Search Amazon for '.$query, $icon, 'yes');
} else {
    $str = str_replace('"', '', $str);

    $options = explode(',', $str);
    foreach($options as $option) {
        $w->result($option, $option, 'Find '.$option.' on Amazon', $icon, 'yes', $option);
    }
}

echo $w->toxml();
