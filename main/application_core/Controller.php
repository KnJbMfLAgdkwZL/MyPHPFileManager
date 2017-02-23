<?php

class Controller
{
    public $title;

    public function __construct()
    {
        $config = Config::get();
        $this->title = $config['html']['headTitle'];
    }

    public function render($file, $var = [])
    {
        $clasname = get_class($this);
        $clasname = str_replace(__CLASS__, "", $clasname);
        $clasname = mb_strtolower($clasname);
        ob_start();
        extract($var);
        include_once($clasname . DIRECTORY_SEPARATOR . "{$file}");
        $headTitle = $this->title;
        $bodyMain = ob_get_contents();
        ob_end_clean();
        include_once('layouts' . DIRECTORY_SEPARATOR . 'main.php');
        ActiveRecord::disconnect();
    }
}