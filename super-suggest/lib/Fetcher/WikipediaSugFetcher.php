<?php

class WikipediaSugFetcher extends SugFetcher {

    public $name = 'wikipedia';
    public $site = 'WikiPedia';

    public $fallbackAPI = 'http://en.wikipedia.org/wiki/%s';
    public $entryAPI = 'http://en.wikipedia.org/wiki/%s';
    public $suggestAPI = 'http://en.wikipedia.org/w/api.php?format=json&action=opensearch&search=%s';

    protected function _fetchSuggest($query) {
        $data = array();
        $json = $this->get(sprintf($this->suggestAPI, urlencode($query)));
        $items = json_decode($json);
        if (!empty($items)) {
            array_shift($items);
            $items = !empty($items[0]) ? $items[0] : array();
        } else {
            $items = array();
        }

        foreach($items as $item) {
            $slug = preg_replace('/\s+/', '_', $item);
            $url = sprintf($this->entryAPI, $slug);
            $title = $item;
            $subtitle = sprintf('查看词条: %s', $title);
            $data[] = compact('url', 'title', 'subtitle');
        }

        return $data;
    }

}
