<?php

class DocSearcher {

    public $name = null;

    public $fallback = 'http://www.baidu.com/s?wd=%s';

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
                $pos = stripos($entry['title'], $query);
                if ($pos === 0) {
                    $items[] = $entry;
                } else if ($pos > 0) {
                    $entry['weight'] = $entry['weight'] * 0.5;
                    $items[] = $entry;
                } else if (stripos($entry['subtitle'], $query) > 0) {
                    $entry['weight'] = $entry['weight'] * 0.1;
                    $items[] = $entry;
                }
            }
        }

        $fallbacks = $this->fallback($query, sizeof($items));
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
    protected function fallback($query, $hitDocCount = 0) {
        $items = array();
        $weight = 0;
        $fallbacks = (array)$this->fallback;

        foreach ($fallbacks as $fallback) {
            $url = sprintf($fallback, urlencode($query));
            $title = $hitDocCount ? 'Want more?' : 'No Suggestions';
            $subtitle = sprintf('Search %s for %s', parse_url($fallback, PHP_URL_HOST), $query);
            $icon = ICON . $this->name . '.png';
            if (!file_exists($icon)) {
                $icon = 'icon.png';
            }
            $items[] = compact('url', 'title', 'subtitle', 'icon', 'weight');
        }

        return $items;
    }

}
