<?php

class JingdongSugFetcher extends SugFetcher {

    public $name = 'jingdong';
    public $site = '京东商城';

    public $fallback = 'http://search.jd.com/Search?keyword=%s&enc=utf-8&suggest=0&area=1';
    public $suggestAPI = 'http://dd.search.jd.com/?callback=jsonp&key=%s';

    protected function _fetchSuggest($query) {
        $data = array();
        $jsonp = $this->get(sprintf($this->suggestAPI, urlencode($query)));
        $jsonp = str_replace('jsonp(', '', $jsonp);
        $json = trim(trim($jsonp, ';'), ')');
        $items = json_decode($json);

        foreach($items as $item) {
            // category
            if (!empty($item->cid)) {
                $url = sprintf($this->fallbackAPI, urlencode($item->keyword));
                $title = $item->keyword;
                $subtitle = sprintf('与%s相关的商品约%d件商品', $item->keyword, $item->oamount);
                $data[] = compact('url', 'title', 'subtitle');

                $url = sprintf('http://search.jd.com/Search?keyword=%s&enc=utf-8&suggest=0&cid3=%d&area=1', $item->keyword, $item->cid);
                $title = sprintf('[分类]: %s', $item->cname);
                $subtitle = sprintf('与%s相关的商品约%d件商品', $item->keyword, $item->amount);
                $data[] = compact('url', 'title', 'subtitle');

            // keyword
            } else {
                $url = sprintf($this->fallbackAPI, urlencode($item->keyword));
                $title = $item->keyword;
                $subtitle = sprintf('与%s相关的商品约%d件商品', $item->keyword, $item->amount);
                $data[] = compact('url', 'title', 'subtitle');
            }
        }

        return $data;
    }

}
