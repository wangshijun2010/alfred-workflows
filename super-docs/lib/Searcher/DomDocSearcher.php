<?php

class DomDocSearcher extends DocSearcher {

    public $name = 'dom';

    public $fallbacks = array(
        'mdn' => 'https://developer.mozilla.org/en-US/search?q=%s',
        'wpf' => 'http://docs.webplatform.org/w/index.php?search=%s',
    );

}
