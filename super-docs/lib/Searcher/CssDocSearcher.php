<?php

class CssDocSearcher extends DocSearcher {

    public $name = 'css';

    public $fallback = array(
        'https://developer.mozilla.org/en-US/search?q=%s',
        'http://docs.webplatform.org/w/index.php?search=%s',
    );

}
