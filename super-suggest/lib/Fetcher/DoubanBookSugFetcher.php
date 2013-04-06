<?php

class DoubanBookSugFetcher extends SugFetcher {

    public $name = 'doubanbook';
    public $site = '豆瓣读书';

    public $fallbackAPI = 'http://book.douban.com/subject_search?search_text=%s';
    public $suggestAPI = 'https://api.douban.com/v2/book/search?count=20&q=%s&apikey=%s';
    public $suggestAPIKey = '01068bdd0c3168a70313a397249439f5';

    protected function _fetchSuggest($query) {
        function get_name($o) {
            return $o->name;
        }
        $suggestions = $this->get(sprintf($this->suggestAPI, urlencode($query), $this->suggestAPIKey));
        $suggestions = json_decode($suggestions);
        foreach ($suggestions->books as $suggest) {
            $url = $suggest->alt;
            $title = $suggest->title;
            $subtitle = '作者: '. implode(",", $suggest->author) .', 评分: '. $suggest->rating->average .'/'. $suggest->rating->numRaters .', 标签: '. implode(",", array_map('get_name', $suggest->tags));
            $data[] = compact('url', 'title', 'subtitle');
        }

        return $data;
    }

}
