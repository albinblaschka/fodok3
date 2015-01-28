
fodok.getMediaTypes = (function(mtype) {
	$('#medium').html('');
	var mediaTypes = '<select id="item" class="form-control input-sm" name="item" required="required" autocomplete="off">';
	mediaTypes += '<option value="*">Bitte wählen!</option>';
	var cnt = 0;
	$.each(fodok.pubTypes[mtype][0], function(index,value ) {
		mediaTypes += '<option value="' + index + '">' + value + '</option>';
		cnt++;
	});
	mediaTypes += '</select>';
	if (cnt > 1) {
		$('#medium').html(mediaTypes);
	}

	$('#item').change(function(){
		fodok.getColumns($(this).val());
	});

});

fodok.getColumns = (function(which){

	// TODO: Check ob ok/erlaubt!?
	// TODO: Snippet für DOI/ISSN... - Buttons auslagern und einlesen?

	$.ajax({
		url: "conf/" + which + ".json",
		type: "GET",
		dataType : "json",
		success: function(json) {

			var columns = '';
			/*
			 <div class="col-sm-2 text-right"><strong>Titel</strong></div>
		    <div class="col-sm-10">
				<input class="form-control input-sm" name="author[]" type="text" placeholder="Titel" required="required" style="max-width:100%;" />
		    </div>
			*/
			$.each(json[which], function( index, value ) {
				columns += '<div class="row">\
								<div class="col-sm-2 text-right"><strong>' + json[which][index]['caption'] + '</strong></div>\
								<div class="col-sm-10"><input class="form-control input-sm" name="' + index + '" type="text" placeholder="' + json[which][index]['caption'] + '"\
									required="required" style="max-width:100%;" /></div>\
							</div>';
				switch(index){
					case 'DOI':
						$('#bttns').append('<button type="button" class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#doiModal">Import via DOI</button>');
						$('#fetchDOIData').click(function(){
							var fetchDOI = fodok.fetchWebData('DOI');
						});
						break;
					case 'ISSN':
						$('#bttns').append('<button type="button" class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#issnModal">Import via ISSN</button>');
						$('#fetchISSNData').click(function(){
							var fetchISSN = fodok.fetchWebData('ISSN');
						});
						break;
					case 'ISBN':
						$('#bttns').append('<button type="button" class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#isbnModal">Import via ISBN</button>');
						$('#fetchISBNData').click(function(){
							var fetchISBN = fodok.fetchWebData('ISBN');
						});
						break;
				}
			});
			$('#inputFields').html(columns);
		},
		error: function( xhr, status, errorThrown ) {
			alert( "Sorry, there was a problem!" );
			console.log( "Error: " + errorThrown );
			console.log( "Status: " + status );
			console.dir( xhr );
		}
	});


});

fodok.fetchWebData = (function(which){
	var DOIURL = 'http://api.crossref.org/works/';
	switch(which){
		case 'DOI':
			// jQuery.get('http://api.crossref.org/works/10.1111/gfs.12063')
			var theDOI = $('#DOIInput').val();
			var request = DOIURL + theDOI;
			var response = jQuery.get(request, function() {
					var ref = response.responseJSON.message;
				})
				.done(function() {
					$('#doiModal').modal('hide');
				})
				.fail(function() {
					alert( "error" );
				});
				//.always(function() {
				//	alert( "finished" );
				//});


			break;
		case 'ISSN':
			break;
		default:
	}
	console.log(which);
});