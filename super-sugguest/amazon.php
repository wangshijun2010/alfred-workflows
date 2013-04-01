<?php
require('workflows.php');
$w = new Workflows();

$site = 'Amazon';
$icon = '2558AB62-161A-453C-BB59-40619C85E8DB.png';

$query = trim($argv[1]);
$url = "http://completion.amazon.com/search/complete?method=completion&q=".urlencode($query)."&search-alias=aps&mkt=1&x=updateISSCompletion&noCacheIE=1295031912518";
$str = $w->request($url);

// Strip off the "header" data
$str = substr($str, strlen('completion = ["'.$query.'",['));

// Remove the node info
$str = substr($str, 0, strpos($str, ']'));

//  Check to see if results were found
if ($str == "") {
    $w->result($query, 'No Suggestions', 'No search suggestions found. Search Amazon for '.$query, $icon, 'yes');
} else {
    // Remove the double quotes around all the strings,
    $str = str_replace('"', '', $str);

    // Split into an array using a comma as the delimiter
    $options = explode(',', $str);

    // Loop through each result and make a feedback item
    foreach($options as $option) {
        $w->result($option, $option, 'Find '.$option.' on Amazon', $icon, 'yes', $option);
    }
}

echo $w->toxml();
