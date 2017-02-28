<?php

class SiteController extends Controller
{
    public $allowed = ['index', 'page404'];

    public function index()
    {
        $path = './';
        if (isset($_POST['cur_path'])) {
            $path = $_POST['cur_path'];
        }

        $path = iconv("UTF-8", "cp1251", $path);
        $path = realpath($path);

        $fm = new FileManager();
        if (is_dir($path)) {
            $data = $fm->scan_dir($path);

            $path = $fm->change_encoding($path);
            $this->render('index.php', ['data' => $data, 'path' => $path]);
        } else if (is_file($path)) {
            echo 'File';
        }
    }

    public function page404()
    {
        $this->render('page404.php');
    }
}