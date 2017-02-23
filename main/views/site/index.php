<form method="post" action="./index.php">
    <input style="width: 400px" type="text" name="cur_path" id="cur_path" value="<?= $path ?>"/>
    <input type="submit" name="go" id="go" value="go"/>
</form>

<?php
$str = '<table cellspacing="0" cellpadding="0" border="1" >';

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

    $str .= '<tr class="file">';
    $str .= "<td>{$name}</td>";
    $str .= "<td>{$type}</td>";
    $str .= "<td>{$size}</td>";
    $str .= "<td>{$rights}</td>";
    $str .= "<td>{$date_create}</td>";
    $str .= "<td>{$date_modify}</td>";
    $str .= "<td>{$date_open}</td>";
    $str .= '</tr>';
}

$str .= '</table>';
echo $str;

$str = system("wmic logicaldisk get caption");//windows
print_r($str);

?>
<script src="/main/views/site/index.js"></script>
<link rel="stylesheet" type="text/css" href="/main/views/site/index.css"/>