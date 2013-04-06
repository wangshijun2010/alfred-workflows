<?php

class HtmlDocIndexer extends DocIndexer {
    public $name = 'html';

    public $config = array(
        'mdn' => array(
            'base' => '',
            'url' => 'http://dochub.io/data/html-mdn.json',
            'weight' => 100,
            'type' => 'json',
            'proxy' => '127.0.0.1:8087',
        ),
    );

    /**
     * Parse keyword from MDN
     * @param string $base
     * @param string $url
     * @param int $weight
     * @return array
     */
    public function mdnParser($base, $url, $weight, $content) {
        $data = array();

        $items = json_decode($content);
        foreach ($items as $item) {
            $url = $item->url;
            $title = $item->title;
            phpQuery::newDocument($item->sectionHTMLs[0]);
            $subtitle = pq('p:first')->text();
            $subtitle = str_replace("Summary", "", $subtitle);
            $subtitle = preg_replace('/\s+/m', ' ', $subtitle);
            $subtitle = html_entity_decode($subtitle);
            $type = 'element';
            $data[] = compact('url', 'title', 'subtitle', 'type', 'weight');
        }

        return $data;
    }

}
