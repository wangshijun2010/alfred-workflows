<?php

class DocSearcher {

    public $name = null;

    public $defaultFallbacks = array(
        'baidu' => 'http://www.baidu.com/s?wd=%s',
        'google' => 'https://www.google.com/search?q=%s',
        'stackoverflow' => 'http://stackoverflow.com/search?q=%s',
    );

    /**
     * Search the offline index for similar entries
     *
     * @param string $query
     * @param int $limit
     * @return array
     */
    public function search($query = '', $limit = -1) {
        $items = array();

        if (!empty($query)) {
            $query = strtolower($query);
            $data = DocIndexer::read($this->name);

            foreach ($data as $key=>$entry) {
                $entry['icon'] = ICON . $entry['type'] . '.png';
                // 标题完全匹配的权重最大
                if (strtolower($entry['title']) === $query) {
                    $entry['weight'] = $entry['weight'] * 9999;
                    $items[] = $entry;
                } else {
                    $pos = stripos($entry['title'], $query);
                    // 标题开头匹配权重次之
                    if ($pos === 0) {
                        $entry['weight'] = $entry['weight'] * 10 / strlen($entry['title']);
                        $items[] = $entry;
                    // 标题后面匹配权重次之
                    } else if ($pos > 0) {
                        $entry['weight'] = $entry['weight'] * 1 / strlen($entry['title']);
                        $items[] = $entry;
                    // 副标题匹配权重最次
                    } else if ($entry['subtitle'] && stripos($entry['subtitle'], $query) > 0) {
                        $entry['weight'] = $entry['weight'] * 0.02;
                        $items[] = $entry;
                    }
                }
            }
        }

        $fallbacks = $this->getFallbacks($query, sizeof($items));
        $items = array_merge($items, $fallbacks);
        usort($items, array($this, 'compareByWeight'));

        // cut off the result
        if ($limit > 0) {
            $items = array_slice($items, 0, $limit);
        }

        return $items;
    }

    /**
     * Sort documents based on weight
     *
     * @param array $doc1
     * @param array $doc2
     * @return int
     */
    public function compareByWeight($doc1, $doc2) {
        if ($doc1['weight'] === $doc2['weight']) {
            return 0;
        } else {
            return $doc1['weight'] > $doc2['weight'] ? -1 : 1;
        }
    }

    /**
     * Get fallback search
     *
     * @param string $query
     * @return array
     */
    protected function getFallbacks($query, $hitDocCount = 0) {
        $items = array();
        $fallbacks = (array)$this->fallbacks;
        $fallbacks = array_merge($fallbacks, $this->defaultFallbacks);

        foreach ($fallbacks as $key=>$fallback) {
            if (array_key_exists($key, $this->defaultFallbacks)) {
                $url = sprintf($fallback, urlencode($this->name . ' ' . $query));
                $weight = 0;
            } else {
                $url = sprintf($fallback, urlencode($query));
                $weight = 1;
            }
            $title = sprintf('Search %s for \'%s\'', parse_url($fallback, PHP_URL_HOST), $query);
            $subtitle = '';
            $icon = ICON . $key . '.png';
            if (!file_exists($icon)) {
                $icon = 'icon.png';
            }
            $items[] = compact('url', 'title', 'subtitle', 'icon', 'weight');
        }

        return $items;
    }

}
