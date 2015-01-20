<?php

error_reporting(E_ALL | E_STRICT);
session_start();

if (empty($_SESSION['fdksettings']) == TRUE) {
    $_SESSION['fdksettings'] = parse_ini_file('../conf/fodok.ini', TRUE);
    header("Location: http://". $_SESSION['fdksettings']['general']['server']. "/". $_SESSION['fdksettings']['general']['path']. "index.php?flag=timeout");
    session_write_close();
    exit();
}

function __autoload($class_name) {
    $absPath = $_SESSION['fdksettings']['general']['absolute'];
    require_once $absPath.'/lib/' .$class_name. '.inc.php';
}
$home = "http://". $_SESSION['fdksettings']['general']['server']. "/". $_SESSION['fdksettings']['general']['path']. "index.php";

include('components/fodokSelectTypes.php');
include('components/menuBar.php');

session_write_close();
?>


<!DOCTYPE html>
<html lang="de">
	<head>
	    <meta charset="utf-8">
	    <title>Forschungsdokumentation RaGu | Eingabe</title>
	    <meta name="description" content="Forschungsdokumentation am LFZ Raumberg-Gumpenstein" />
	    <meta name="author" content="Albin Blaschka" />

	    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
	    <!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	    <![endif]-->

		<link href="css/bootstrap.css" rel="stylesheet" />
	    <link href="css/bootstrap-responsive.css" rel="stylesheet" />
		<link href="css/fodok.css" rel="stylesheet" />
    
	    <!--<link rel="shortcut icon" href="images/favicon.ico" />-->

		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.js"></script>

	</head>

	<body>

		<?php echo $menubar; ?>

		<div id="content" class="container-fluid">
			<div class="row-fluid">

			    <?php include('components/emptySide.htm');?>

			    <div class="span8">
					<h1>Auswahl Publikationstyp</h1>
					<?php echo $accordion;?>
					<?php include('components/footer.htm');?>
				</div>
			</div>

	    </div><!--/.fluid-container-->
	
		<div id="scripting">
			<script>
				$("button").bind("click", function(){
					var typeChosen = $(this).val();
					$.post("backend/fodokDispatch.php", { fdtype: typeChosen });
					return false;
				});
			</script>
		</div>
	</body>
</html>






