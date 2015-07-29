<?php

error_reporting('E_ALL | E_STRICT');
session_start();

// TODO: Session überprüfen!

require '../lib/simpleQuery.php';
$_SESSION['fdksettings'] = parse_ini_file('../conf/fodok.ini', true);

function __autoload($class_name) 
{
    $absPath = $_SESSION['fdksettings']['general']['absolute'];
    include_once $absPath.'/lib/' .$class_name. '.inc.php';
}

$permitted = array('people','types','projects');

if (in_array($_GET['which'], $permitted) == false) {
    $response['status'] = 'Illegal';
    $response['message'] = 'Illegal Method, go away!';
    echo json_encode($response);
    exit();
}else{
    $dispatch = $_GET['which'];
}


switch($dispatch) {
    // TODO: Verwendung/ Anpassung fodok_utils.inc.php ??
case 'people':
    $query = "SELECT persnr, nachname, vorname FROM ".$_SESSION['fdksettings']['postgres']['schema'].".personen
					ORDER BY nachname";
    $result = doQuery($query);
    if (empty($result) == false) {
        foreach ($result as $ds) {
            $initial = '';
            $ds['vorname'] = trim($ds['vorname']);
            $dummy = split(' ', $ds['vorname']);
            while (list($key, $val) = each($dummy)) {
                $initial .= substr($val, 0, 1). '. ';
            }
            $initial = trim($initial);
                
            $dataset[$ds['persnr']]['nachname'] = trim($ds['nachname']);
            $dataset[$ds['persnr']]['vorname'] = trim($ds['vorname']);
            $dataset[$ds['persnr']]['initial'] = $initial;
            $dataset[$ds['persnr']]['apk'] = $ds['persnr'];
            $initial = '';
        }
            $response['data'] = $dataset;
            $response['status'] = 'Success';
    }else{
        $response['status'] = 'Error';
        $response['message'] = 'The Database did not talk to me';      
    }
    break;
case 'types':
    // TODO: in DB Kategorie ("grob") "Berichte" zu Publikationen umändern?
    // TODO: Spalte "sort" aktualisieren/anpassen...
    $query = "SELECT DISTINCT sort, spezi FROM ".$_SESSION['fdksettings']['postgres']['schema'].".spezi_publikation
					WHERE grob = 'Publikationen' OR grob = 'Berichte' ORDER BY sort";
    $result = doQuery($query);
    if (empty($result) == false) {
        $response['data'] = $result;
        $response['status'] = 'Success';
    }else{
        $response['status'] = 'Error';
        $response['message'] = 'The Database did not talk to me'; 
    }  
    break;
case 'projects':
    $projects = fodok_utils::projects();
    if(empty($projects) == false) {
        $response['data'] = $projects;
        $response['status'] = 'Success';
    }else{
        $response['status'] = 'Error';
        $response['message'] = 'The Database did not talk to me';      
    }
    break;
default:
    $response['status'] = 'Error';
    $response['message'] = 'The Dispatcher said "Something strange..."';
}    




echo json_encode($response);


?>
