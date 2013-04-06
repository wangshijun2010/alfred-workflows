<?php

class JqueryDocSearcher extends DocSearcher {

    public $name = 'jquery';

    public $fallbacks = array(
        'jquery' => 'http://api.jquery.com/?s=%s',
    );

}
