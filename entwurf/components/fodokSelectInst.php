<?php

$inst = fodok_utils::getStructure();
$instSelect = '<select class="inst" name="institut"><option value="">nach Institut filtern</option><option value="0">Alle Institute</option>';
while (list($key, $val) = each($inst)) {
    $instSelect .='<option value="'.$key.'">'.trim($val).'</option>';
}
$instSelect .= '</select>';
?>