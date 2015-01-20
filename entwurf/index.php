<?php

/**********************************************************************

	Forschungsdokumentation Raumberg - Gumpenstein, Version 3

**********************************************************************/

error_reporting(E_ALL | E_STRICT);

session_start();
$_SESSION['fdksettings'] = array();
$_SESSION['fdksettings'] = parse_ini_file('conf/fodok.ini', TRUE);

function __autoload($class_name) {
    $absPath = $_SESSION['fdksettings']['general']['absolute'];
    require_once $absPath.'/lib/' .$class_name. '.inc.php';
}
include('components/tblOverview.php');
$today = Date("d. m. Y");
session_write_close();

?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <title>Forschungsdokumentation RaGu | Start</title>
    <meta name="description" content="Forschungsdokumentation am LFZ Raumberg-Gumpenstein" />
    <meta name="author" content="Albin Blaschka" />

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/bootstrap-responsive.css" rel="stylesheet" />
	<link href="css/fodok.css" rel="stylesheet" />

    <!--<link rel="shortcut icon" href="images/favicon.ico" />-->

    <script src="js/jquery.js"></script>
	<script src="js/bootstrap.js"></script>
  </head>

  <body>

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
						<li class="active"><a href="#">Home</a></li>
						<li><a href="/dokumentation">Dokumentation FoDok</a></li>
						<li><a href="http://apk">APK - Intranet</a></li>
						<li><a href="http://baldbserver">BALDBServer</a></li>
					</ul>
					<p class="navbar-text pull-right">
						<a href="http://www.raumberg-gumpenstein.at/c/index.php?option=com_fodok&amp;Itemid=100033">www.raumberg-gumpenstein.at</a>
					</p>
				</div><!--/.nav-collapse -->
			</div>
		</div>
    </div>

    <div id="content" class="container-fluid">

		<?php include('components/emptySide.htm');?>
		
        <div class="span12">
			<div class="hero-unit callo">
	            <h1 id="callout">Forschungsdokumentation RaGu | FoDok</h1>
				<p>Version <?php echo $_SESSION['fdksettings']['general']['version'];?> - "<?php echo $_SESSION['fdksettings']['general']['nick'];?>"</p>
			</div>
			<div class="row-fluid">
				<div class="span3">&nbsp;</div>
				<div class="span6">
					<h3><i class="icon-info-sign"></i>Forschungsdokumentation - Übersicht, Stand: <?php echo $today;?></h3>
					<?php echo $tblOverview;?>
					<a class="btn btn-primary btn-large" href="#myModal" data-toggle="modal">FoDok starten - Login</a>
				</div>
			</div>

			<div id="myModal" class="modal hide fade">
				<div class="modal-header">
					<a class="close" data-dismiss="modal" >&times;</a>
					<h3>FoDok - Login</h3>
	            </div>
				<form id="fdLogon" action="backend/fodokAuthenticate.php" method="post">
					<div class="modal-body">
						<h4><i class="icon-user"></i> Bitte mit APK-Nummer und Passwort anmelden</h4>
						<input type="text" placeholder="APK-Nr." required name= "apk" />
						<input type="password" placeholder='*****' required name= "pwd" />
						<input type="hidden" name="fdform" value="fdLogon" />
					</div>
		            <div class="modal-footer">
						<input type="submit" class="btn btn-primary btn-large" value="Anmelden" />
						<a href="index.php" class="btn btn-primary btn-large" data-dismiss="modal" >Abbrechen</a>
					</div>
				</form>
	        </div>

			<?php include('components/footer.htm');?>
		</div>
    </div><!--/.fluid-container-->
	
  </body>
</html>
