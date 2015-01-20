<?php
error_reporting('E_ALL | E_STRICT');
session_start();
$_SESSION['fdksettings'] = parse_ini_file('../conf/fodok.ini', TRUE);
function __autoload($class_name) {
    $absPath = $_SESSION['fdksettings']['general']['absolute'];
    require_once $absPath.'/lib/' .$class_name. '.inc.php';
}

$credentials = new FoDok_HandleCredential();
$credentials->authenticate($_POST['apk'], $_POST['pwd']);
$url = "http://". $_SESSION['fdksettings']['general']['server']. "/". $_SESSION['fdksettings']['general']['path'];

if ($credentials->GetStatus() == TRUE) {
	$_SESSION['fdk']['state']['credentials'] = serialize($credentials);
	$_SESSION['fdk']['state']['who'] = $credentials->persnr;
	session_write_close();
	header("Location: ".$url."fodokInput.html");
}else{
	session_write_close();
	header("Location: ".$url."index.php?error=nologin");
}
