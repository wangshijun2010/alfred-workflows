<?php

class DocFetcher {
    /**
     * Description:
     * Read data from a remote file/url, essentially a shortcut for curl
     *
     * @param $url - URL to request
     * @param $options - Array of curl options
     * @return result from curl_exec
     */
    public static function get($url=null, $options=null) {
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

        if ($options) {
            $defaults = array_merge($defaults, $options);
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
