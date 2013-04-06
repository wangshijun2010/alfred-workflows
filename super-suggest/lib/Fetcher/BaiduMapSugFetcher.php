<?php

class BaiduMapSugFetcher extends SugFetcher {

    public $name = 'baidumap';
    public $site = '百度地图';

    public $fallbackAPI = 'http://maps.baidu.com/?newmap=1&s=s%%26wd%%3D%s%%26c%%3D1';
    public $suggestAPI = 'http://maps.baidu.com/su?wd=%s&cid=131&type=0&newmap=1&t=%s';

    protected function _fetchSuggest($query) {
        $data = array();
        $json = $this->get(sprintf($this->suggestAPI, urlencode($query), time()));
        $respones = json_decode(trim($json));
        $items = isset($respones->s) ? $respones->s : array();

        // var_dump($json); print_r($items); exit();

        foreach($items as $item) {
            if (strpos($item, '$$$') === 0) {
                continue;
            }

            $item = preg_replace(
                array('/\$/i', '/\s+/', '/\d+/'),
                array(' ', ' ', ''),
                $item
            );
            $item = trim($item);
            $tmp = split(' ', $item);
            $keyword = array_shift($tmp) . ' ' . array_pop($tmp);
            $url = sprintf($this->fallbackAPI, urlencode($keyword));
            $title = $item;
            $subtitle = sprintf('Search %s for "%s"', $this->site, $keyword);
            $data[] = compact('url', 'title', 'subtitle');
        }

        return $data;
    }

}
