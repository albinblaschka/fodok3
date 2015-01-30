
// TODO: Datei umbenennen in InfobyAPK oder so...


fodok.insertNameByAPK = (function(APK) {
	if(!fodok.people[APK]) {
		$('#messages').html('\
				<div class="alert alert-danger alert-dismissible" role="alert">\
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\
					Diese APK-Nummer ist nicht vorhanden!\
				</div>\
			');
		$('#lastName_' + fodok.authorCnt).val(' ');
		$('#initials_' + fodok.authorCnt).val(' ');
	}else{
		$('#lastName_' + fodok.authorCnt).val(fodok.people[APK]['nachname']);
		$('#initials_' + fodok.authorCnt).val(fodok.people[APK]['initial']);
		$('#messages').html('');
	}
});

fodok.searchAPKByName = (function(lastname) {
	fnd = 0;
	fodok.nameFound = new Object;
	lastname = lastname.trim()
	$.each(fodok.people, function(apknr, record){
		if (record.nachname == lastname) {
			fodok.nameFound[fnd] = new Object;
			fodok.nameFound[fnd]['lastname'] = lastname;
			fodok.nameFound[fnd]['firstname'] = record.vorname;
			fodok.nameFound[fnd]['initials'] = record.initial;
			fodok.nameFound[fnd]['apk'] = apknr;
			fnd++;
		}
	});
});

fodok.insertAPK = (function(lastname) {
	fodok.searchAPKByName(lastname);
	if ($(fodok.nameFound).length == 1){
		$('#APK_' + fodok.authorCnt).val(fodok.nameFound[0]['apk']);
		$('#lastName_' + fodok.authorCnt).val(fodok.nameFound[0]['lastname']);
		$('#initials_' + fodok.authorCnt).val(fodok.nameFound[0]['initials']);
	}
});