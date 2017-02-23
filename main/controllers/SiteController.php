<?php

class SiteController extends Controller
{
    public $allowed = ['index', 'page404', 'paramhandler'];

    public function index()
    {
        $path = './';
        if (isset($_POST['cur_path'])) {
            $path = $_POST['cur_path'];
        }
        $path = realpath($path);

        $fm = new FileManager();
        $data = $fm->scan_dir($path);

        $this->render('index.php', ['data' => $data, 'path' => $path]);
    }

    public function page404()
    {
        $this->render('page404.php');
    }

    public function paramhandler()
    {
        echo 'paramhandler()';
        echo '<pre>';
        echo '$_GET', '<br>';
        print_r($_GET);
        echo '<br>';
        echo '$_POST', '<br>';
        print_r($_POST);
        echo '</pre>';
    }
}