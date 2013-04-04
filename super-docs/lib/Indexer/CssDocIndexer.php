<?php

class CssDocIndexer extends DocIndexer {
    public $name = 'css';

    public $config = array(
        'mdn' => array(
            'base' => 'https://developer.mozilla.org',
            'url' => 'https://developer.mozilla.org/en-US/docs/CSS/CSS_Reference',
            'weight' => 100,
        ),
        'wpf' => array(
            'base' => 'http://docs.webplatform.org',
            'url' => 'http://docs.webplatform.org/w/index.php?title=Special:Ask&offset=0&limit=500&q=%5B%5BCategory%3ACSS+Properties%5D%5D&p=mainlabel%3D%2Fformat%3Dtemplate%2Ftemplate%3DSummary_Table_Body%2Flink%3Dnone%2Fcolumns%3D1%2Fintrotemplate%3DSummary_Table_Header%2Foutrotemplate%3DSummary_Table_Footer&po=%3FPage+Title%0A%3FSummary%0A',
            'weight' => 100,
        ),
    );

    /**
     * Parse keyword from MDN
     * @param string $base
     * @param string $url
     * @param int $weight
     * @return array
     */
    public function mdnParser($baseUrl, $url, $weight) {
        $data = array();

        // css properties
        $items = pq('div.index')->find('a');
        foreach ($items as $prop) {
            $url = $baseUrl . pq($prop)->attr('href');
            $title = trim(pq($prop)->text());
            $subtitle = 'property:' . $title;
            $type = 'property';
            $data[] = compact('url', 'title', 'subtitle', 'type', 'weight');
        }

        // css selectors
        $items = pq('#Selectors')->next('ul');
        $items = pq($items)->find('li');
        foreach ($items as $item) {
            $url = $baseUrl . pq($item)->find('a')->attr('href');
            $title = trim(pq($item)->find('a')->text());
            $subtitle = $title;
            $type = 'section';
            $data[] = compact('url', 'title', 'subtitle', 'type', 'weight');
        }

        // css tutorials
        $items = pq('#CSS3_Tutorials')->next('p')->next('ul');
        $items = pq($items)->find('a');
        foreach ($items as $item) {
            $url = $baseUrl . pq($item)->attr('href');
            $title = trim(pq($item)->text());
            $subtitle = 'tutorial: ' . $title;
            $type = 'section';
            $data[] = compact('url', 'title', 'subtitle', 'type', 'weight');
        }

        // css concepts
        $items = pq('#Concepts')->next('ul');
        $items = pq($items)->find('a');
        foreach ($items as $item) {
            $url = $baseUrl . pq($item)->attr('href');
            $title = trim(pq($item)->text());
            $subtitle = 'concept: ' . $title;
            $type = 'define';
            $data[] = compact('url', 'title', 'subtitle', 'type', 'weight');
        }

        return $data;
    }

    /**
     * Parse keyword from WebPlatform
     * @param string $base
     * @param string $url
     * @param int $weight
     * @return array
     */
    public function wpfParser($baseUrl, $url, $weight) {
        $data = array();

        $items = pq('.wikitable')->find('tr');
        foreach ($items as $prop) {
            $link = pq($prop)->find('td:eq(0)')->find('a');
            $desc = pq($prop)->find('td:eq(1)');
            $url = $baseUrl . pq($link)->attr('href');
            $title = trim(pq($link)->text());
            $subtitle = trim(pq($desc)->text());
            if (empty($subtitle)) {
                $subtitle = $url;
            }
            $type = 'property';
            $data[] = compact('url', 'title', 'subtitle', 'type', 'weight');
        }

        return $data;
    }
}
