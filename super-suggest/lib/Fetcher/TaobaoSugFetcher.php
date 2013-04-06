<?php

class TaobaoSugFetcher extends SugFetcher {

    public $name = 'taobao';
    public $site = '淘宝';

    public $fallbackAPI = 'http://s.taobao.com/search?q=%s';
    public $suggestAPI = 'http://suggest.taobao.com/sug?code=utf-8&callback=jsonp&q=%s';

    protected function _fetchSuggest($query) {
        $data = array();
        $jsonp = $this->get(sprintf($this->suggestAPI, urlencode($query)));
        $jsonp = str_replace('jsonp(', '', trim($jsonp));
        $json = trim(trim($jsonp, ';'), ')');
        $json = json_decode($json);
        $items = isset($json->result) ? $json->result : array();

        foreach($items as $item) {
            list($keyword, $amount) = $item;
            $url = sprintf($this->fallbackAPI, urlencode($keyword));
            $title = $keyword;
            $subtitle = sprintf('与%s相关的商品约%d件商品', $keyword, $amount);
            $data[] = compact('url', 'title', 'subtitle');
        }

        return $data;
    }

}
