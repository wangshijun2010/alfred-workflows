<?php

class CssDocSearcher extends DocSearcher {

    public $name = 'css';

    public $fallbacks = array(
        'mdn' => 'https://developer.mozilla.org/en-US/search?q=%s',
        'wpf' => 'http://docs.webplatform.org/w/index.php?search=%s',
    );

}
