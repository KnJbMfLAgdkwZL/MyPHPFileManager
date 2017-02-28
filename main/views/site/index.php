<form method="post" action="./index.php" class="form-cur_path">
    <input type="text" name="cur_path" id="cur_path" value="<?= $path ?>"/>
    <input type="submit" name="go" id="go" value="go"/>
</form>

<?php
$disc = system("wmic logicaldisk get caption");//windows
print_r($disc);

$str = '<table class="list" cellspacing="0" cellpadding="0" border="1">';

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

    $str .= '<tr class="list-item">';
    $str .= "<td class='list-item-name'>{$name}</td>";
    $str .= "<td class='list-item-type'>{$type}</td>";
    $str .= "<td class='list-item-size'>{$size}</td>";
    $str .= "<td class='list-item-rights'>{$rights}</td>";
    $str .= "<td class='list-item-date_create'>{$date_create}</td>";
    $str .= "<td class='list-item-date_modify'>{$date_modify}</td>";
    $str .= "<td class='list-item-date_open'>{$date_open}</td>";
    $str .= '</tr>';
}

$str .= '</table>';
echo $str;
?>
<script src="/main/views/site/index.js"></script>
<link rel="stylesheet" type="text/css" href="/main/views/site/index.css"/>