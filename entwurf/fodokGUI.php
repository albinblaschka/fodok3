<?php

error_reporting(E_ALL | E_STRICT);
session_start();

if (isset($_SESSION['fdksettings']) == FALSE) {
    $_SESSION['fdksettings'] = parse_ini_file('../conf/fodok.ini', TRUE);
    header("Location: http://". $_SESSION['fdksettings']['general']['server']. "/". $_SESSION['fdksettings']['general']['path']. "index.php?flag=timeout");
    session_write_close();
    exit();
}
$credentials = unserialize($_SESSION['fdk']['state']['credentials']);

function __autoload($class_name) {
    $absPath = $_SESSION['fdksettings']['general']['absolute'];
    require_once $absPath.'/lib/' .$class_name. '.inc.php';
}
$home = "http://". $_SESSION['fdksettings']['general']['server']. "/". $_SESSION['fdksettings']['general']['path']. "index.php";
$currInst = FoDok_HandleCredential::GetDep($_SESSION['fdk']['state']['who']);
$currInst = 2;

include('components/menuBar.php');
include('components/forms/authorship.php');
include('components/forms/authorInput.php');
include('components/forms/pubType.php');
include('components/forms/projectSelect.php');
include('components/forms/checkPeople.php');
include('components/fodokSelectInst.php');

// TODO: Alle Autor/Autorenschaft - Komponenten in einer zusammenfassen...?
$btnAdd = ' <button class="btn btn-info" value="addAuthor">Autor hinzufügen</button>';
$btnRemove = '<button class="btn btn-danger" value="removeAuthor">Autor entfernen</button>';
$authorInput = '<div class="ff">'.$authorInput.$authorship.'</div>';
$authorshipFirst = str_replace('erst">', 'erst" selected="selected">', $authorship);
$kwButton = '<div class="ff"><label class="span3">Stichwort:</label><input class="kw longi" type="text" name="keyword[]" value="" data-provide="typeahead" /></div>';

$project1 = str_replace('°','1',$projectSelect[$currInst]);
$project2 = str_replace('°','2',$projectSelect[$currInst]);
$allProjectsJSON = json_encode($projectSelect);
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
					<h1>Eingabe</h1>
						<form id="publikation" class="form-inline" method="post" action="backend/fodokDispatch.php">
							<input type="hidden" name="formular" value="publikation" />
							<fieldset>
								<legend>Autorenschaft</legend>
								<div class="well">
									<div id="authors" class="ff">
										<label><strong>Autor:</strong> Nachname</label>
										<input class="theName focused long" name="author[]" type="text" placeholder="Nachname" required="required" onchange="javascript:handleAuthors(this)" />
										<label>Initiale(n)</label>
										<input class="shorty ins" name="authorFirst[]" type="text" placeholder="Initialen" required="required" />
										<input class="shorty apk" name="apk[]" type="text" placeholder="APK" onchange="javascript:handleAPK(this)" />
										<?php echo $authorshipFirst;?>
									</div>
									<div style="margin-top:15px;">
										<?php	echo '<span class="span2">'.$btnAdd.'</span>';
												echo $btnRemove;
										?>
									</div>
								</div>
								<div id="synonyms"></div>
							</fieldset>
							<fieldset>
								<legend>Zitat</legend>
								<div class="ff">
									<label class="span3" for="year">Jahr des Erscheinens:</label>
									<input id="year"  name="year" class="shorty" type="text" value="<?php echo Date('Y');?>" required="required" />
								</div>
								<div class="ff">
									<label class="span3" for="title">Titel:</label>
									<input id="title" type="text" name="title"  class="supi" value="" required="required" />
								</div>
								<div class="ff">
									<label class="span3" for="citation">Erschienen in, Zitat:</label>
									<input id="citation" type="text" name="citation" class="supi" value="" required="required" />
								</div>
							</fieldset>
							<fieldset>
								<legend>Nähere Angaben, Typ</legend>
								<div class="ff"><?php echo $pubType;?></div>
								<div class="ff">
									<label class="span3" for="menge">Anzahl Seiten/Stunden/Tage:</label>
									<input id="menge" name="menge" class="shorty" type="text" />
								</div>
								<div class="ff"><span id="p1"><?php echo $project1.'</span> '.$instSelect;?></div>
								<div class="ff"><span id="p2"><?php echo $project2.'</span> '.$instSelect;?></div>
							</fieldset>
							<fieldset>
								<legend>Zusatzdaten, Datenfreigabe</legend>
								<div class="ff">
									<label class="span3" for="thefile">Belegdatei hochladen:</label>
									<button class="btn btn-info" id="thefile" type="button" name="thefile" value="upload">Datei hochladen</button>
								</div>
								<div class="ff">
									<label class="span3" for="thefile">Zusammenfassung (Abstract) eingeben:</label>
									<button class="btn btn-info" id="btnAbstract" type="button" name="btnAbstract" value="abstract">Abstract eingeben</button>
								</div>
								<div id="kws" class="ff">
									<label class="span3">Stichwort:</label>
									<input class="kw longi" type="text" name="keyword[]" value="" data-provide="typeahead" />
									<button class="btn btn-info" id="btnKw" type="button" name="btnKw" value="keyword">weiteres Stichwort</button>
								</div>
								<div class="ff">
									<label class="span3" for="langEng">Sprache des Eintrages Englisch:</label>
									<input id="langEng" type="radio" name="language" value="englisch" required="required" />
								</div>
								<div class="ff">
									<label class="span3" for="langDt">Oder: Sprache des Eintrages Deutsch:</label>
									<input id="langDt" type="radio" name="language" value="deutsch" required="required" />
								</div>
								<div class="ff">
									<label class="span3" for="quoteweb">Zitat für das Web freigeben:</label>
									<input id="quoteweb" class="radio" type="checkbox" name="quoteweb" value="TRUE" />
								</div>
								<div class="ff">
									<label class="span3" for="fileweb">Datei für das Web freigeben:</label>
									<input id="fileweb" class="radio" type="checkbox" name="fileweb" value="TRUE" />
								</div>
								<div class="ff">
									<label class="span3" for="plist">Zitat in Auswahl-Publikationsliste aufnehmen:</label>
									<input id="plist" class="radio" type="checkbox" name="fileweb" value="TRUE" />
								</div>
								<div class="ff"><p>&nbsp;</p></div>
								<div id="preview" class="well">
									<h3>
										<span class="span3"><i class="icon-info-sign"></i>Voransicht</span>
										<button class="btn btn-info" id="btnRefresh" type="button" name="btnRefresh" value="refresh">Aktualisieren</button>
									</h3>
									<div id="quote"></div>
									<div id="meta"></div> 
								</div>
								<div class="form-actions">
									<button class="btn btn-success" type="submit">Daten speichern</button>
									<button class="btn btn-danger">Daten verwerfen</button>
								</div>
							</fieldset>
						</form>

					<?php include('components/footer.htm');?>
				</div>
			</div>

	    </div><!--/.fluid-container-->

		<div id="modals">
			<div id="mdUpload" class="modal hide fade">
				<div class="modal-header well">
					<a class="close" data-dismiss="modal" >&times;</a>
					<h3>Datei-Upload</h3>
	            </div>
				<form id="fdUpload" enctype="multipart/form-data" action="backend/fodokDispatch.php" method="post">
					<input type="hidden" name="fdUpload" value="fdUpload" />
					<div class="modal-body">
						<h4><i class="icon-file"></i> Datei Hochladen</h4>
						<input type="file" name="fileToUpload" id="fileToUpload"/>
					</div>
		            <div class="modal-footer">
						<input type="submit" class="btn btn-success" value="Abschicken" />
						<a href="index.php" class="btn btn-danger" data-dismiss="modal">Abbrechen</a>
					</div>
				</form>
	        </div>

			<div id="mdAbstract" class="modal hide fade" style="width: 750px;max-height: 650px;">
				<div class="modal-header well">
					<a class="close" data-dismiss="modal" >&times;</a>
					<h3>Zusammenfassung/Abstract-Eingabe</h3>
	            </div>
				<form class="form-inline" id="fdAbstract" enctype="multipart/form-data" action="backend/fodokDispatch.php" method="post">
					<input type="hidden" name="fdAbstract" value="fdabstract" />
					<div class="modal-body">
						<h4><i class="icon-align-justify"></i> Zusammenfassung/Abstract eingeben</h4>
						<textarea id="textarea" style="width: 700px;" rows="20" name="theAbstract"></textarea>
					</div>
		            <div class="modal-footer">
						<input type="submit" class="btn btn-success" value="Abstract speichern" />
						<a href="index.php" class="btn btn-danger" data-dismiss="modal">Abstract verwerfen</a>
					</div>
				</form>
	        </div>
		</div>

		<div id="scripting">
			<script>
				var fodok = new Object;
				fodok.const = Array;
				fodok.tmp = Array;

				fodok.const.authTmpl = '<?php echo $authorInput;?>';
				fodok.const.ship = Array('erst','zweit','dritt');
				fodok.const.people = <?php echo $people; ?>;
				fodok.const.qTmpl = '#author# #year#: #title#. #citation#';
				fodok.const.metaTmpl = 'Typ: #type# Menge: #menge# Projekt 1: #project1# Projekt 2: #project2# Zitat freigeben: #openquote# Datei freigeben: #openfile# in Auswahl-Publikationsliste: #selection# Zusammenfassung: #abstract# Keywords: #keywords#';
				fodok.const.metaXtra = Array('#openquote#', '#openfile#', '#abstract#', '#selection#');
				fodok.const.prjLists = <?php echo $allProjectsJSON;?>;
				fodok.const.kwBtn = '<?php echo $kwButton;?>';
				fodok.const.kws = Array('Alpha', 'Beta', 'Gamma');

				fodok.tmp.cntAuth = 0;
				fodok.tmp.auth = String;
				$('.kw').typeahead({source: fodok.const.kws})

				$('button').bind('click', function() {
					fodok.tmp.btnVal = $(this).val();
					switch (fodok.tmp.btnVal) {
						case 'addAuthor':
							fodok.tmp.auth = '';
							fodok.tmp.cntAuth++;
							fodok.tmp.auth = fodok.const.authTmpl.replace(fodok.const.ship[fodok.tmp.cntAuth]+'">',
														fodok.const.ship[fodok.tmp.cntAuth]+'" selected="selected">');
							$('#authors').append(fodok.tmp.auth);
							break;
						case 'removeAuthor':
							if ($('#authors .ff').length > 0) {
								$('#authors .ff').last().remove();
								fodok.tmp.cntAuth--
							}
							break;
						case 'abstract':
							$('#mdAbstract').modal();
							break;
						case 'upload':
							$('#mdUpload').modal();
							break;
						case 'refresh':
							refreshPreview();
							break;
						case 'keyword':
							$('#kws').append(fodok.const.kwBtn);
							$('.kw').typeahead({source: fodok.const.kws});
					}
				});

				$('#title').bind('change', function() {
					refreshPreview();
				});

				$('#citation').bind('change', function() {
					refreshPreview();
				});

				$('.inst').bind('change', function() {
					fodok.tmp.inst = $(this).val();
					var par = $(this).prev();
					var pid = $(par).attr("id");
					pid = pid.replace('p','');
					var replaceIt = fodok.const.prjLists[fodok.tmp.inst].replace(/°/g,pid);
					$(par).empty();
					$(par).append(replaceIt);
				});

				function refreshPreview() {
					fodok.tmp.fields = $(":input").serializeArray();
					fodok.tmp.quote = fodok.const.qTmpl;
					fodok.tmp.meta = fodok.const.metaTmpl;
					fodok.tmp.as = '';
					fodok.tmp.ks = '';
					var i;
					for(i=0;i<fodok.tmp.fields.length;i++) {
						switch (fodok.tmp.fields[i].name) {
							case 'author[]':
								fodok.tmp.as = fodok.tmp.as + fodok.tmp.fields[i].value + ', ' + fodok.tmp.fields[i+1].value + ', ';
								break;
							case 'keyword[]':
								fodok.tmp.ks = fodok.tmp.ks + fodok.tmp.fields[i].value + ' ';
								break;
						}
						if ($.trim(fodok.tmp.fields[i].value).length == 0) {
							fodok.tmp.quote = fodok.tmp.quote.replace('#'+fodok.tmp.fields[i].name+'#',' - ');
							fodok.tmp.meta = fodok.tmp.meta.replace('#'+ fodok.tmp.fields[i].name+'#',' - ');
						}else{
							fodok.tmp.quote = fodok.tmp.quote.replace('#'+fodok.tmp.fields[i].name+'#',fodok.tmp.fields[i].value);
							fodok.tmp.meta = fodok.tmp.meta.replace('#'+ fodok.tmp.fields[i].name+'#',fodok.tmp.fields[i].value);
						}
					}
					for(var j=0;j<fodok.const.metaXtra.length;j++) {
						fodok.tmp.meta = fodok.tmp.meta.replace(fodok.const.metaXtra[j],' - ');
					}
					if (fodok.tmp.as == ', , ') {
						fodok.tmp.quote = fodok.tmp.quote.replace('#author#',' - ');
					}
					if (fodok.tmp.ks == ' ') {
						fodok.tmp.meta = fodok.tmp.meta.replace('#keywords#',' - ');
					}
					fodok.tmp.quote = fodok.tmp.quote.replace('#author#',fodok.tmp.as);
					fodok.tmp.meta = fodok.tmp.meta.replace('#keywords#',fodok.tmp.ks);
					$('#quote').empty();
					$('#meta').empty();
					$('#quote').append(fodok.tmp.quote);
					$('#meta').append(fodok.tmp.meta);
				}

				function handleAuthors(theNameObj) {
					var theName = theNameObj.value;
					var syn = Array;
					var cnt = 0;
					for (var key in fodok.const.people) {
						if (theName == fodok.const.people[key][0]) {
							syn[cnt] = [theName,fodok.const.people[key][1],key]
							cnt++;
						}
					}
					if (cnt == 1) {
						var dummy0 = $(theNameObj).next();
						var dummy1 = $(dummy0).next();
						var dummy2 = $(dummy1).next();
						$(dummy1).val(syn[0][1].substring(0, 1) + '.');
						$(dummy2).val(syn[0][2]);
					}
					if (cnt == 0) {
						var dummy0 = $(theNameObj).next();
						var dummy1 = $(dummy0).next();
						var dummy2 = $(dummy1).next();
						var dummy3 = $(dummy2).next();
						var dummy4 = $(dummy3).next();
						$(dummy4).children('option#first').prop('selected',false);
						$(dummy4).children('option#second').prop('selected',false);
						$(dummy4).children('option#third').prop('selected',false);
						$(dummy4).children('option#not').prop('selected','selected');
					}
					if (cnt > 1) {
						var synStr = '<table class="table table-condensed table-striped">';
						for (var i=0;i<cnt;i++) {
							synStr = synStr + '<tr id="A' + syn[i][2] + '"><td>' + syn[i][0] + '</td><td>' + syn[i][1] + '</td><td>(APKNr. ' + syn[i][2] + ')</td></tr>';
						}
						synStr = synStr + '</table>';
						$('#synonyms').empty();
						$('#synonyms').append('<h3>Name nicht eindeutig!</h3>'+synStr);
					}
				}

				function handleAPK(APKObj) {
					var apkNr = APKObj.value;
					var dummy0 = $(APKObj).prev();
					var dummy1 = $(dummy0).prev();
					var dummy2 = $(dummy1).prev();
					$(dummy0).val(fodok.const.people[apkNr][1].substring(0, 1) + '.');
					$(dummy2).val(fodok.const.people[apkNr][0]);
					$('#synonyms').empty();
				}

			</script>
		</div>
	</body>
</html>






