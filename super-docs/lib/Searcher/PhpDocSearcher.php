<?php

class PhpDocSearcher extends DocSearcher {

    public $name = 'php';

    public $fallbacks = array(
        'php' => 'http://www.php.net/manual-lookup.php?pattern=%s&lang=en&scope=quickref',
    );

}
