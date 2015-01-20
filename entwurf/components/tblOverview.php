<?php

$tblOverview = '<table class="table table-condensed table-striped"><caption>Anzahl Publikationen per Publikationstyp</caption>';

$data = fodokAnalyze::countItems('*','*');
$types = fodok_utils::types();

while (list($key,$val) = each($data)) {
    $tblOverview .= '<tr><td>'.$types[$key].'</td><td class="right">'.$val.'</td></tr>';
}
$tblOverview .= '</table>';

?>