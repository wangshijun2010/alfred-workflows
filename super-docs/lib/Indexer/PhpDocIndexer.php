<?php

class PhpDocIndexer extends DocIndexer {
    public $name = 'php';

    public $config = array(
        'default' => array(
            'base' => 'http://www.php.net/manual/en/',
            'url' => 'http://www.php.net/manual/en/indexes.functions.php',
            'weight' => 100,
        ),
    );

    /**
     * Parse keyword from php.net
     * @param string $base
     * @param string $url
     * @param int $weight
     * @return array
     */
    public function defaultParser($base, $url, $weight) {
        $data = array();

        $groups = pq('ul.index-for-refentry')->find('li.gen-index');
        foreach ($groups as $group) {
            $items = pq($group)->find('li');
            foreach ($items as $item) {
                $link = pq($item)->find('a');
                $url = $base . pq($link)->attr('href');
                $title = trim(pq($link)->text());
                $subtitle = trim(pq($item)->text());
                if (strpos($title, '::')) {
                    $type = 'method';
                } else {
                    $type = 'function';
                }
                $data[] = compact('url', 'title', 'subtitle', 'type', 'weight');
            }
        }

        return $data;
    }

}
