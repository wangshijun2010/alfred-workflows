<?php

class DoubanMovieSugFetcher extends SugFetcher {

    public $name = 'doubanmovie';
    public $site = '豆瓣电影';

    public $fallbackAPI = 'http://movie.douban.com/subject_search?search_text=%s';
    public $suggestAPI = 'https://api.douban.com/v2/movie/search?count=20&q=%s&apikey=%s';
    public $suggestAPIKey = '01068bdd0c3168a70313a397249439f5';

    protected function _fetchSuggest($query) {
        $suggestions = $this->get(sprintf($this->suggestAPI, urlencode($query), $this->suggestAPIKey));
        $suggestions = json_decode($suggestions);
        // print_r($suggestions);
        foreach ($suggestions->subjects as $suggest) {
            $url = $suggest->alt;
            $title = $suggest->title;
            $subtitle = '年份: '. $suggest->year .', 评分: '. $suggest->rating->average .', 类型: '. $suggest->subtype .' 别名: '. $suggest->original_title;
            $data[] = compact('url', 'title', 'subtitle');
        }

        return $data;
    }

}
