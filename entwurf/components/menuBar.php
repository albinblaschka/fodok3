<?php
/*

Für das Einbinden dieser Komponente muss die Variable $home definiert sein, mit dem Pfad zur Startseite, also die 
Adresse von index.php

*/

$permission = 3;
$query0 = 'select submenuid, menutitle from applications.webmenutitles where permission <= '.$permission. ' order by submenuid';
try{
    $DBHandle = new DB_Connect('postgres');
    $resultset0 = $DBHandle->simpleQuery($query0);
    $DBHandle->close();
}
catch(DB_Exception $e){
    echo $e;
    exit();
}
catch(Exception $e){
    echo '<pre>';
    print_r($e);
    echo '<br></pre>'.$e->getMessage();
    exit();
}

$items ='';
foreach ($resultset0 as $entry) {
	$query1 = 'select submenuid, entry, link from applications.webmenu
				where permission <= '.$permission. ' and submenuid = '.$entry['submenuid'].'
                order by submenuid, itemid';
	try{
	    $DBHandle = new DB_Connect('postgres');
	    $resultset1 = $DBHandle->simpleQuery($query1);
	    $DBHandle->close();
	}
	catch(DB_Exception $e){
	    echo $e;
	    exit();
	}
	catch(Exception $e){
	    echo '<pre>';
	    print_r($e);
	    echo '<br></pre>'.$e->getMessage();
	    exit();
	}
	$sub = '<ul class="dropdown-menu">';
	foreach($resultset1 as $subentry) {
		$url = dirname($home).'/'.$subentry['link'];
		$sub .= '<li><a href="'.$url.'">'.$subentry['entry'].'</a></li>';
    }
	$items .= '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">'.$entry['menutitle'].'<b class="caret"></b></a>';
	$items .= $sub;
	$items .= '</ul>';
	$items .= '</li>';

}

$DBHandle->close();

$menubar = '
<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
        <div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="#">Forschungsdokumentation</a>
			<div class="nav-collapse">
				<ul class="nav">
					<li class="active"><a href="'.$home.'">Home</a></li>'
					.$items.'
				</ul>
				<p class="navbar-text pull-right">
					<a href="http://www.raumberg-gumpenstein.at/c/index.php?option=com_fodok&amp;Itemid=100033">www.raumberg-gumpenstein.at</a>
				</p>
			</div>
		</div>
	</div>
</div>';

?>