<?php

class BaiduSugFetcher extends SugFetcher {

    public $name = 'baidu';
    public $site = '百度';

    public $fallbackAPI = 'http://www.baidu.com/s?wd=%s&&ie=utf-8';
    public $suggestAPI = 'http://suggestion.baidu.com/su?wd=%s&p=3&cb=callback&from=superpage&t=%d';

    protected function _fetchSuggest($query) {
        $data = array();
        $response = $this->get(sprintf($this->suggestAPI, urlencode($query), time()));
        $response = iconv('GBK', 'UTF-8', $response);
        $response = str_replace('callback(', '', trim($response));
        $json = trim(trim($response, ';'), ')}');
        $json = substr($json, strpos($json, 's:') + 2);
        $items = json_decode($json);

        foreach($items as $item) {
            $url = sprintf($this->fallbackAPI, urlencode($item));
            $title = $item;
            $subtitle = sprintf('Search %s for "%s"', $this->site, $item);
            $data[] = compact('url', 'title', 'subtitle');
        }

        return $data;
    }

}
