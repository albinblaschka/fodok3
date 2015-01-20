<?php

if (empty($_GET['error']) == FALSE and $_GET['error'] == 'nologin'){

  $message = '<div class="alert alert-danger" role="alert">Fehler beim Passwort oder der APK-Nummer!</div>';
}else{
  $message = '';
}

?>
<!DOCTYPE html>
<html lang="de">

<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="http://getbootstrap.com/favicon.ico">

    <title>Forschungsdokumentation :: Anmeldung</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/flatly.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
	  
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

<body>

    <div class="container">
	<div class="row">
	    <div class="col-md-10">
		<h2 style="margin-bottom: -10px;padding-top: 0px;"><small>HBLFA Raumberg-Gumpenstein</small></h2>
		<h1>Forschungs- und Service Dokumentation</h1>
		<h2 style="margin-top: -10px;padding-top: 0px;"><small>Version 3 | Gallifrey</small></h2>
	    </div>
	    
	</div>
        
        <div class="row" style="min-height: 250px;">&nbsp;</div>
        
        <div class="row">
	    <div class="col-md-5"></div>
	    <div class="col-md-7">
		<h2>Anmeldung</h2>
		<?php echo $message;?>
		<form class="form-signin form-inline" action="backend/fodokAuthenticate.php" method="POST">
		    <div class="form-group">
			<label class="sr-only" for="inputAPK">APK-Nummer</label>
			<input type="number" class="form-control" id="inputAPK" placeholder="APK-Nummer" name="apk" autocomplete="off" required="required">
		    </div>
		    <div class="form-group">
			<label class="sr-only" for="inputPwd">Password</label>
			<input type="password" class="form-control" id="inputPwd" placeholder="Passwort" name="pwd" required="required">
		    </div>
		    <button type="submit" class="btn btn-default">Anmelden</button>
		</form>
	    </div>
	</div>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  

</body>
</html>