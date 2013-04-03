<?php

require_once('workflows.php');
$wf = new Workflows();

$query = trim($argv[1]);
$site = '知乎';
$icon = 'BCC41292-4B26-4C89-A63B-FB47EB21F0D8.png';

$json = $wf->request("http://www.zhihu.com/autocomplete?max_matches=10&use_similar=0&token=".urlencode($query));
$items = json_decode($json);

$items = array_shift($items);
array_shift($items);

foreach($items as $item) {
    $type = array_shift($item);

    // topic
    if ($type === 'topic') {
        list($title, , , , , $answerCount) = $item;
        $url = sprintf('http://www.zhihu.com/topic/%d', $uuid);
        $subtitle = sprintf('话题: %s, %d个热门问答', $title, $answerCount);
        $wf->result("$url", "$title", "$subtitle", $icon);

    // question
    } else if ($type === 'question') {
        list($title, , , $answerCount, $isGood) = $item;
        $url = sprintf('http://www.zhihu.com/question/%d', $uuid);
        if ($isGood) {
            $subtitle = sprintf('问答[★]: %s, %d个回答', $title, $answerCount);
        } else {
            $subtitle = sprintf('问答: %s, %d个回答', $title, $answerCount);
        }
        $wf->result("$url", "$title", "$subtitle", $icon);

    // fall back search
    } else if ($type === 'search_link') {
        list($keyword) = $item;
        $url = sprintf('http://www.zhihu.com/search?q=%s&type=question', $keyword);
        $subtitle = sprintf('查看全部与%s有关的搜索结果', $keyword);
        $wf->result("$url", "在知乎上搜索: $keyword", "$subtitle", $icon);
    }
}

$results = $wf->results();
if (count($results) == 0):
    $url = sprintf('http://www.zhihu.com/search?q=%s&type=question', $query);
    $wf->result($url, 'No Suggestions', 'No search suggestions found. Search 知乎 for '.$query, 'icon.png');
endif;

echo $wf->toxml();
