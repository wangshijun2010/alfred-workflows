<?php

class ZhihuSugFetcher extends SugFetcher {

    public $name = 'zhihu';
    public $site = '知乎';

    public $fallbackAPI = 'http://search.jd.com/Search?keyword=%s&enc=utf-8&suggest=0&area=1';
    public $suggestAPI = 'http://www.zhihu.com/autocomplete?max_matches=10&use_similar=0&token=%s';

    protected function _fetchSuggest($query) {
        $data = array();
        $json = $this->get(sprintf($this->suggestAPI, urlencode($query)));

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
                $data[] = compact('url', 'title', 'subtitle');

            // question
            } else if ($type === 'question') {
                list($title, , , $answerCount, $isGood) = $item;
                $url = sprintf('http://www.zhihu.com/question/%d', $uuid);
                if ($isGood) {
                    $subtitle = sprintf('问答[★]: %s, %d个回答', $title, $answerCount);
                } else {
                    $subtitle = sprintf('问答: %s, %d个回答', $title, $answerCount);
                }
                $data[] = compact('url', 'title', 'subtitle');
            }
        }

        return $data;
    }

}
