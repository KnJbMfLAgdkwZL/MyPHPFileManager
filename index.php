<?php
error_reporting(E_ERROR);

function GetDir($path, &$str)
{
    $mass = scandir($path);
    foreach ($mass as $v) {
        if (!strstr($v, ".")) {
            if (is_dir($path . DIRECTORY_SEPARATOR . $v)) {
                $str .= PATH_SEPARATOR . $path . DIRECTORY_SEPARATOR . $v;
                GetDir($path . DIRECTORY_SEPARATOR . $v, $str);
            }
        }
    }
}

$alldir = get_include_path();
GetDir('.', $alldir);
set_include_path($alldir);
function __autoload($class)
{
    require_once($class . '.php');
}

$rooth_path = __DIR__;

$application = new Application();
$application->run();