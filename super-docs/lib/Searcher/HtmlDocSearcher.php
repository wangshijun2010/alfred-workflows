<?php

class HtmlDocSearcher extends DocSearcher {

    public $name = 'html';

    public $fallbacks = array(
        'mdn' => 'https://developer.mozilla.org/en-US/search?q=%s',
        'wpf' => 'http://docs.webplatform.org/w/index.php?search=%s',
    );

}
