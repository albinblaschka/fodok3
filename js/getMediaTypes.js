
fodok.getMediaTypes = (function(mtype) {
	$('#medium').html('');
	var mediaTypes = '<select id="item" class="form-control input-sm" name="item" required="required" autocomplete="off">';
	mediaTypes += '<option value="*">Bitte wählen!</option>';
	var cnt = 0;
	$.each(fodok.pubTypes[mtype]['bibtex_de'], function( index, value ) {
		mediaTypes += '<option value="' + value + '">' + value + '</option>';
		cnt++;
	});
	mediaTypes += '</select>';
	if (cnt > 1) {
		$('#medium').html(mediaTypes);
	}

});