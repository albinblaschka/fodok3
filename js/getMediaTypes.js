
fodok.getMediaTypes = (function(mtype) {
    $('#medium').html('');
    var mediaTypes = '<select id="item" class="form-control input-sm" name="item" required="required" autocomplete="off">';
    mediaTypes += '<option value="*">Bitte wählen!</option>';
    var cnt = 0;
    $.each(
        fodok.pubTypes[mtype][0], function(index,value ) {
            mediaTypes += '<option value="' + index + '">' + value + '</option>';
            cnt++;
        }
    );
    mediaTypes += '</select>';
    if (cnt > 1) {
        $('#medium').html(mediaTypes);
    }

    $('#item').change(
        function(){
            fodok.getColumns($(this).val());
        }
    );

});

fodok.getColumns = (function(which){

    // TODO: Check ob ok/erlaubt!?
    // TODO: Snippet für DOI/ISSN... - Buttons auslagern und einlesen?

    $.ajax(
        {
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
                $.each(
                    json[which], function( index, value ) {
                        columns += '<div class="row">\
					<div class="col-sm-2 text-right"><strong>' + json[which][index]['caption'] + '</strong></div>\
					<div class="col-sm-10"><input class="form-control input-sm" id="' + index + '" name="' + index + '" type="text" placeholder="' + json[which][index]['caption'] + '"\
					required="required" style="max-width:100%;" /></div>\
				</div>';
                        switch(index){
                        case 'doi':
                            $('#bttns').append('<button type="button" class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#doiModal">Import via DOI</button>');
                            $('#fetchDOIData').click(
                                function(){
                                    var fetchDOI = fodok.fetchWebData('DOI');
                                }
                            );
                          break;
                        case 'issn':
                            $('#bttns').append('<button type="button" class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#issnModal">Import via ISSN</button>');
                            $('#fetchISSNData').click(
                                function(){
                                    var fetchISSN = fodok.fetchWebData('ISSN');
                                }
                            );
                          break;
                        case 'isbn':
                            $('#bttns').append('<button type="button" class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#isbnModal">Import via ISBN</button>');
                            $('#fetchISBNData').click(
                                function(){
                                    var fetchISBN = fodok.fetchWebData('ISBN');
                                }
                            );
                          break;
                        }
                    }
                );
                $('#inputFields').html(columns);
            },
            error: function( xhr, status, errorThrown ) {
                alert("Sorry, there was a problem!");
                console.log("Error: " + errorThrown);
                console.log("Status: " + status);
                console.dir(xhr);
            }
        }
    );


});

fodok.fetchWebData = (function(which){

    var DOIURL = 'http://api.crossref.org/works/';

    var ISSNUrl = 'http://xissn.worldcat.org/webservices/xid/issn/';
    var ISSNParams = '?method=getHistory&format=json';

    //var ISSNUrl = 'http://api.crossref.org/works/journals/';
    // = 'http://xissn.worldcat.org/webservices/xid/issn/||ISSN||
    //var ISBNUrl = 'http://xisbn.worldcat.org/webservices/xid/isbn/978-0-19-958067-5?method=getMetadata&format=json&fl=*';
    //var convertISBNUrl = 'http://xisbn.worldcat.org/webservices/xid/isbn/978-3-902849-09-0?method=to10&format=json'

    switch(which){
    case 'DOI':
        // jQuery.get('http://api.crossref.org/works/10.1111/gfs.12063')
        var theDOI = $('#DOIInput').val();
        $('#doi').val(theDOI);
        var request = DOIURL + theDOI;
        var response = jQuery.get(
            request, function() {
                // Autoren auslesen, eintragen
                // TODO: Prüfen, ob Vorname als Initial vorhanden?
                var acnt = 1
                var ref = response.responseJSON.message;
                $.each(
                    ref.author, function(index, value){
                        var addedInputs = fodok.addAuthorInputs();
                        $('#lastName_' + acnt).val(value.family);
                        $('#initials_' + acnt).val(value.given);
                        acnt++;
                    }
                );
                // Jahr
                $('#year').val(ref['indexed']['date-parts'][0][0]);
                // Titel der Arbeit
                $('#titel').val(ref.title);
                // Zeitschriften-Titel
                $('#journal').val(ref['container-title'][0]);
                // Volume
                $('#volume').val(ref.volume);
                // Nummer
                $('#number').val(ref.issue);
                // Seitenzahlen
                var pages = ref.page;
                var splitted = pages.split('-');
                $('#pageFrom').val(splitted[0]);
                $('#pageTo').val(splitted[1]);
                $('#issn').val(ref.ISSN[0]);
            }
        )
               .done(
                   function() {
                        $('#doiModal').modal('hide');
                   }
               )
               .fail(
                   function() {
                        alert("error");
                   }
               );
               //.always(function() {
               //	alert( "finished" );
               //});


      break;
    case 'ISSN':
        var theISSN = $('#ISSNInput').val();
        var request = ISSNUrl + theISSN + ISSNParams;
        var data = {"type":'issn', "item":theISSN};
        $.ajax(
            {
                url: "backend/getWebData.php",
                type: "GET",
                data:{query: data},
                dataType : "json",
                success: function(json) {
                    $('#journal').val(json.group[0].list[0].title);
                    $('#issn').val(json.group[0].list[0].issn);
                    $('#issnModal').modal('hide');
                }
            }
        );

      break;
    default:
    }
});