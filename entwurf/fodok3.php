<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <title>Forschungsdokumentation RaGu | </title>
    <meta name="description" content="Forschungsdokumentation am LFZ Raumberg-Gumpenstein" />
    <meta name="author" content="Albin Blaschka" />

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet" />
	
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet" />
	<link href="css/fodok.css" rel="stylesheet" />
    
	<!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico" />
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png" />

    <script src="js/jquery.js"></script>
	<script src="js/bootstrap.js"></script>

<!--	<script>
		$(document).ready(function () {
			$('#myModal').modal()
		});
	</script>-->

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
						<a href="http://www.raumberg-gumpenstein.at/c/index.php?option=com_fodok&Itemid=100033">www.raumberg-gumpenstein.at</a>
					</p>
				</div><!--/.nav-collapse -->
			</div>
		</div>
    </div>

    <div id="content" class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<div class="sidebar-nav">
				<!-- Content Sidebar -->
		        </div><!--/.sidebar-nav -->
	        </div>

	        <div class="span8">
				<div class="hero-unit callo">
		            <h1 id="callout">Forschungsdokumentation RaGu | FoDok</h1>
					<p>Version 3 - "πάντα ῥεῖ - Panta rhei"</p>
				</div>
	
		<div class="row-fluid">
			<div class="span8">
				<h3>Forschungsdokumentation - Übersicht</h3>
					<p><strong>Datensätze insgesamt: XXXX</strong><br />
					<h4>Nach Publikationstypen</h4>
						Publikationen: XXXX<br />
						Vorträge: XXXX<br />
						Forschungsberichte XXXX<br />
				</p>
				<a class="btn btn-primary btn-large" href="#myModal" data-toggle="modal">FoDok starten - Login</a>
			</div>
		</div>

		<div id="myModal" class="modal hide fade">
			<div class="modal-header">
				<a class="close" data-dismiss="modal" >&times;</a>
				<h3>FoDok - Login</h3>
            </div>
			<form id="fdLogon" action="fodokAuthenticate.php">
				<div class="modal-body">
					<h4>Bitte mit APK-Nummer und Passwort anmelden</h4>
					<input type="text" placeholder="APK-Nr." required name= "apk" />
					<input type="password" placeholder='*****' required name= "pwd" />
					<input type="hidden" name="fdform" value="fdLogon" />
				</div>
	            <div class="modal-footer">
					<input type="submit" class="btn btn-primary btn-large" value="Anmelden" />
					<a href="index.php" class="btn btn-primary btn-large" data-dismiss="modal" >Abbrechen</a>
			</form>
        </div>
	</div>


	<footer>
	<hr />
		<table>
			<tr><td><img src="img/logolfz.jpg" alt="Logo LFZ" width="250px" /></td>
				<td style="padding-left:20px;"><strong>Forschungsdokumentation Raumberg - Gumpenstein, Version 3 "Panta rhei"</strong></td>
			</tr>
		</table>
	<hr />
	</footer>

    </div><!--/.fluid-container-->
	
<div id="scripting">
	<script>
		$('#fdLogon').submit(function() {
			$.post("backend/fodokAuthenticate.php", $("#fdLogon").serialize(),
				function(data) {
					$('#myModal').modal('hide');
					$( "#content" ).empty().append( data );
				});
			return false;
		});
	</script>
	</div>
  </body>
</html>
