<?php

class file_manager
{
    function scan_dir($path)
    {
        $directory = array();
        $files = array();
        $all_files = scandir($path);
        sort($all_files);
        foreach ($all_files as $f) {
            if ($f != '.' && $f != '..') {
                $item = array();
                $item['name'] = $f;
                $item['path'] = $path . DIRECTORY_SEPARATOR . $f;
                $item['type'] = '';
                $item['size'] = '';
                $item['rights'] = '';
                $item['date_create'] = '';
                $item['date_modify'] = '';

                $item['rights'] = $this->get_rights($item['path']);

                $fi = stat($item['path']);
                $item['date_create'] = date("d.m.Y H:i:s", $fi['ctime']);
                $item['date_modify'] = date("d.m.Y H:i:s", $fi['mtime']);
                $item['date_open'] = date("d.m.Y H:i:s", $fi['atime']);

                //$item['path'] = realpath($item['path']);
                if (is_dir($item['path'])) {
                    $item['type'] = 'folder';
                    $item['date_open'] = '';
                    $directory[] = $item;
                } else if (is_file($item['path'])) {
                    $item['type'] = $this->get_extension($item['path']);
                    $item['size'] = $this->get_file_size($item['path']);
                    $item['size'] = $this->human_filesize($item['size']);
                    $item['rights'] = $this->get_rights($item['path']);
                    $files[] = $item;
                }
            }
        }
        $all = array_merge($directory, $files);
        return $all;
    }

    function get_rights($path)
    {
        //clearstatcache();
        //return substr(sprintf('%o', fileperms($path)), -3);
        clearstatcache(null, $path);
        return decoct(fileperms($path) & 0777);
    }

    function print_all($data)
    {
        $str = '<table border="1" style="border-collapse: collapse">';
        $str .= '<tr>';
        $str .= '<th>Name</th>';
        $str .= '<th>Type</th>';
        $str .= '<th>Size</th>';
        $str .= '<th>Rights</th>';
        $str .= '<th>Date create</th>';
        $str .= '<th>Date modify</th>';
        $str .= '<th>Date open</th>';
        $str .= '</tr>';
        foreach ($data as $v) {
            extract($v, EXTR_OVERWRITE);
            $access = '';
            $create = '';
            $modify = '';

            $str .= '<tr>';
            $str .= "<td>{$name}</td>";
            $str .= "<td>{$type}</td>";
            $str .= "<td>{$size}</td>";
            $str .= "<td>{$rights}</td>";
            $str .= "<td>{$date_create}</td>";
            $str .= "<td>{$date_modify}</td>";
            $str .= "<td>{$date_open}</td>";
            $str .= '<tr>';
        }
        $str .= '</table>';
        echo $str;
    }

    function get_extension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
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
        $files = $this->get_sub_files($path);
        foreach ($files as $k => $v) {
            //$file_path = realpath($file_path);
            if (is_file($v)) {
                $size += $this->get_file_size($v);
            }
        }
        return $size;
    }

    function get_sub_files($path)
    {
        $sub_files = array();
        $files = scandir($path);
        foreach ($files as $f) {
            if ($f != '.' && $f != '..') {
                $file_path = $path . DIRECTORY_SEPARATOR . $f;
                if (is_dir($file_path)) {
                    $sub_f_tmp = $this->get_sub_files($file_path);
                    $sub_files = array_merge($sub_files, $sub_f_tmp);
                } else if (is_file($file_path)) {
                    $sub_files[] = $file_path;
                }
            }
        }
        return $sub_files;
    }

    function human_filesize($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$sz[$factor];
    }
}

echo "<pre>";
$path = 'C:\Users\Zippocat\Downloads';
$path = 'C:\OpenServer\domains\MyPHPFileManager';
$fm = new file_manager();
$data = $fm->scan_dir($path);
$fm->print_all($data);