
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
