<?php

class PhpDocSearcher extends DocSearcher {

    public $name = 'php';

    public $fallback = 'http://www.php.net/manual-lookup.php?pattern=%s&lang=en&scope=quickref';

}
