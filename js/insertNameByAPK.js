

fodok.insertNameByAPK = (function(APK) {
	if(!fodok.people[APK]) {
		$('#messages').html('\
				<div class="alert alert-danger alert-dismissible" role="alert">\
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\
					Diese APK-Nummer ist nicht vorhanden!\
				</div>\
			');
		$('#lastName_'+fodok.authorCnt).val(' ');
		$('#initials_'+fodok.authorCnt).val(' ');
	}else{
		$('#lastName_'+fodok.authorCnt).val(fodok.people[APK]['nachname']);
		$('#initials_'+fodok.authorCnt).val(fodok.people[APK]['initial']);
		$('#messages').html('');
	}
});
