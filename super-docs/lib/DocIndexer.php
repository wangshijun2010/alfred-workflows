<?php

class DocIndexer {
    /**
     * name
     */
    public $name = null;

    /**
     * config
     */
    public $config = array();

    /**
     * Build keyword index
     * @param array $urls
     * @param array $callbacks
     * @return void
     */
    public function index() {
        $data = array();
        foreach ($this->config as $key=>$config) {
            extract($config);
            $content = DocFetcher::get($url);
            $parser = array($this, $key . 'Parser');
            phpQuery::newDocument($content);
            $tmpdata = call_user_func($parser, $base, $url, $weight);
            $data = array_merge($data, $tmpdata);
        }

        $this->save($data);

        return $data;
    }

    /**
     * Save keyword data to disk
     * @param array $data (url, title, subtitle, type, icon)
     * @return boolean
     */
    public function save($data) {
        $template = "<?php\nreturn %s;";
        $content = sprintf($template, var_export($data, true));
        $filepath = DATA . $this->name . '.php';
        return file_put_contents($filepath, $content);
    }

    /**
     * Read index data for search
     * @param string $name index type
     * @return array $data (url, title, subtitle, type, icon)
     */
    public static function read($name) {
        $file= DATA . $name . '.php';
        $data = array();
        if (file_exists($file)) {
            $data = include($file);
        }
        return $data;
    }

}
