<?php

class SugFetcher {
    public $name = null;
    public $site = null;

    public $fallbackAPI = null;
    public $suggestAPI = null;

    public function fetch($query) {
        $items = $this->_fetchSuggest($query);
        $items[] = $this->_fetchFallback($query, sizeof($items));
        foreach ($items as &$item) {
            $item['icon'] = ICON . $this->name . '.png';
        }
        return $items;
    }

    protected function _fetchFallback($query, $suggestCount) {
        $url = sprintf($this->fallbackAPI, urlencode($query));
        $title = 'No Suggestions';
        $subtitle = sprintf('No search suggestions found, search %s for "%s"', $this->site, $query);
        $icon = $this->name . '.png';
        return compact('url', 'title', 'subtitle', 'icon');
    }

    /**
     * Description:
     * Read data from a remote file/url, essentially a shortcut for curl
     *
     * @param $url - URL to request
     * @param $options - Array of curl options
     * @return result from curl_exec
     */
    public function get($url = null, $options = null) {
        if (is_null($url)) {
            return false;
        }

        $defaults = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31',
        );

        // caution: donot use array_merge
        if ($options) {
            foreach ($options as $key=>$value) {
                $defaults[$key] = $value;
            }
        }

        array_filter($defaults);

        $ch  = curl_init();
        curl_setopt_array($ch, $defaults);
        $out = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            // print_r($url);
            // print_r($defaults);
            trigger_error($err, E_USER_ERROR);
        } else {
            return $out;
        }
    }

}
