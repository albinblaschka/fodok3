<?php

$ISBNUrl = 'http://www.worldcat.org/webservices/catalog/content/isbn/';
$ISSNUrl = 'http://xissn.worldcat.org/webservices/xid/issn/';
// http://www.worldcat.org/webservices/catalog/content/issn/ ??
$params = '?format=json';


switch($_GET['query']['type']){
        case 'issn':
            $requestURL = $ISSNUrl. $_GET['query']['item'].$params;
            $response = file_get_contents($requestURL);
            break;
        case 'isbn':
            $requestURL = $ISBNUrl. $_GET['query']['item'].$params;
            $response = file_get_contents($requestURL);
            break;
        default:
}

echo $response;

?>