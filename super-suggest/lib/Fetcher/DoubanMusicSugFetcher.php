<?php

class DoubanMusicSugFetcher extends SugFetcher {

    public $name = 'doubanmusic';
    public $site = '豆瓣音乐';

    public $fallbackAPI = 'http://music.douban.com/subject_search?search_text=%s';
    public $suggestAPI = 'https://api.douban.com/v2/music/search?count=20&q=%s&apikey=%s';
    public $suggestAPIKey = '01068bdd0c3168a70313a397249439f5';

    protected function _fetchSuggest($query) {
        function get_name($o) {
            return $o->name;
        }
        $suggestions = $this->get(sprintf($this->suggestAPI, urlencode($query), $this->suggestAPIKey));
        $suggestions = json_decode($suggestions);
        // print_r($suggestions);
        foreach ($suggestions->musics as $suggest) {
            $singers = isset($suggest->attrs->singer) ? $suggest->attrs->singer : array('未知');
            $tags = isset($suggest->tags) ? array_map('get_name', $suggest->tags) : array('未知');
            $url = $suggest->alt;
            $title = $suggest->title;
            $subtitle = '表演者: '. implode(",", $singers) .', 评分: '. $suggest->rating->average .'/'. $suggest->rating->numRaters .', 标签: '. implode(",", $tags);
            $data[] = compact('url', 'title', 'subtitle');
        }

        return $data;
    }

}
