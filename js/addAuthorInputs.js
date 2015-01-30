fodok.addAuthorInputs = (function (){

	fodok.authorCnt++;
	var aship = fodok.addAuthorSnippet.replace(/°/g, fodok.authorCnt);
	$('#authors').before(aship);
	$('#aship_'+fodok.authorCnt).val('zweit');
	$('#APK_'+fodok.authorCnt).change(function() {
		fodok.insertNameByAPK($(this).val());
	});

	$('#lastName_' + fodok.authorCnt).change(function() {
		if($('#APK_' + fodok.authorCnt).val() == '') {
			fodok.insertAPK($(this).val());
		}
	});

});
