<?php
error_reporting('E_ALL | E_STRICT');
session_start();
function __autoload($class_name) {
    $absPath = $_SESSION['fdksettings']['general']['absolute'];
    require_once $absPath.'/lib/' .$class_name. '.inc.php';
}
$url = "http://". $_SESSION['fdksettings']['general']['server']. "/". $_SESSION['fdksettings']['general']['path'];
$credentials = @unserialize($_SESSION['fdk']['state']['credentials']);

if ($credentials->GetStatus() == FALSE) {
	session_write_close();
    header("Location: ".$url."index.php?error=nologin");
	exit();
}
$permission = $credentials->GetCredential();

switch ($_POST['fdtype']) {
	case 'publikation':
		header("Location: ".$url."fodokGUI.php");
		exit();
		break;
	default:
		header("Location: ".$url."index.php?error=notype");
		exit();
}

?>
