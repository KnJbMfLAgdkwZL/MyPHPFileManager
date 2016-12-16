<?php

$path = '/';
$files = scandir($path);

echo '<pre>';
print_r($files);
echo '</pre>';

foreach ($files as $k => $v) {
    echo $v, '<br/>';

}

