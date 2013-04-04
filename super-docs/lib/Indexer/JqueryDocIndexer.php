<?php

class JqueryDocIndexer extends DocIndexer {
    public $name = 'jquery';

    public $config = array(
        'default' => array(
            'base' => 'http://api.jquery.com/',
            'url' => 'http://api.jquery.com/',
            'weight' => 100,
        ),
    );

    /**
     * Parse keyword from api.jquery.com
     * @param string $base
     * @param string $url
     * @param int $weight
     * @return array
     */
    public function defaultParser($base, $url, $weight) {
        $data = array();

        $items = pq('article.post');
        foreach ($items as $item) {
            $link = pq($item)->find('.entry-title a');
            $url = pq($link)->attr('href');
            $title = pq($link)->text();
            $subtitle = trim(pq($item)->find('.entry-summary')->text());
            $type = 'function';
            $data[] = compact('url', 'title', 'subtitle', 'type', 'weight');
        }

        return $data;
    }

}
