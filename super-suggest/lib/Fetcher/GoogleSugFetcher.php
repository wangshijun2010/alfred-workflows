<?php

class GoogleSugFetcher extends SugFetcher {

    public $name = 'google';
    public $site = 'Google';

    public $fallbackAPI = 'http://www.google.com/search?q=%s';
    public $suggestAPI = 'http://google.com/complete/search?output=toolbar&q=%s';

    protected function _fetchSuggest($query) {
        $data = array();
        $xml = $this->get(sprintf($this->suggestAPI, urlencode($query)));
        $items = simplexml_load_string(utf8_encode($xml));

        foreach($items as $item) {
            $keyword = $item->suggestion->attributes()->data;
            $url = sprintf($this->fallbackAPI, urlencode($keyword));
            $title = $keyword;
            $subtitle = sprintf('Search %s for "%s"', $this->site, $keyword);
            $data[] = compact('url', 'title', 'subtitle');
        }

        return $data;
    }

}
