<?php

class AmazonSugFetcher extends SugFetcher {

    public $name = 'amazon';
    public $site = 'Amazon';

    public $fallbackAPI = 'http://www.amazon.com/s?url=search-alias=aps&field-keywords=%s&tag=a2appu-20';
    public $suggestAPI = "http://completion.amazon.com/search/complete?method=completion&q=%s&search-alias=aps&mkt=1&x=updateISSCompletion&noCacheIE=1295031912518";

    protected function _fetchSuggest($query) {
        $data = array();
        $str = $this->get(sprintf($this->suggestAPI, urlencode($query)));
        $str = substr($str, strlen('completion = ["'.$query.'",['));
        $str = substr($str, 0, strpos($str, ']'));

        if ($str == "") {
        } else {
            $str = str_replace('"', '', $str);

            $items = explode(',', $str);
            foreach($items as $item) {
                $url = sprintf($this->fallbackAPI, urlencode($item));
                $title = $item;
                $subtitle = sprintf('Search %s for "%s"', $this->site, $item);
                $data[] = compact('url', 'title', 'subtitle');
            }
        }

        return $data;
    }

}
