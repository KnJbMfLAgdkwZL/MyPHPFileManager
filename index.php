<?php

class file_manager
{
    function print_dir($path)
    {
        $files = scandir($path);

        $str = '<table border="1" style="border-collapse: collapse"><tr><th>Name</th><th>Type</th><th>Size</th><th>Access</th><th>Date create</th><th>Date modify</th></tr>';

        foreach ($files as $k => $v) {
            $file_path = $path . DIRECTORY_SEPARATOR . $v;
            //$file_path = realpath($file_path);

            //echo $file_path, '<br>';
            $name = $v;
            $type = $this->get_extension($file_path);

            $size = 0;
            if (is_file($file_path)) {
                $size = $this->get_file_size($file_path);
            } else if (is_dir($file_path)) {
                if ($v != '.' && $v != '..') {
                    $size = $this->get_dir_size($file_path);
                }
            }
            $size = $this->human_filesize($size);

            $access = '';
            $create = '';
            $modify = '';

            $str .= '<tr>';
            $str .= "<td>{$name}</td>";
            $str .= "<td>{$type}</td>";

            $str .= "<td>{$size}</td>";
            $str .= "<td>Access</td>";
            $str .= "<td>create</td>";
            $str .= "<td>modify</td>";
            $str .= '<tr>';
        }
        $str .= '</table>';
        echo $str;
    }

    function get_extension($path)
    {
        $info = pathinfo($path);
        return $info['extension'];
    }

    function get_file_size($path)
    {
        if (substr(PHP_OS, 0, 3) == "WIN") {
            exec('for %I in ("' . $path . '") do @echo %~zI', $output);
            $return = $output[0];
        } else {
            $return = filesize($path);
        }
        return $return;
    }

    function get_dir_size($path)
    {
        $size = 0;
        $files = scandir($path);
        foreach ($files as $k => $v) {
            $file_path = $path . DIRECTORY_SEPARATOR . $v;
            //$file_path = realpath($file_path);
            if (is_file($file_path)) {
                $size += $this->get_file_size($file_path);
            } else if (is_dir($file_path)) {
                if ($v != '.' && $v != '..') {
                    $size += $this->get_dir_size($file_path);
                }
            }
        }
        return $size;
    }

    function human_filesize($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$sz[$factor];
    }
}

$path = 'C:\Users\Zippocat\Downloads';
$fm = new file_manager();
$fm->print_dir($path);