function addInformant() {
    $("#modalAddInformant").modal('show');
}

function saveInformant() {
    var name_ru = $( "#modalAddInformant #name_ru" ).val();
    var pol = $('#modalAddInformant input[name="pol"]:checked').val();
    var nationality_id = $( "#modalAddInformant #nationality_id" ).val();
    var occupation_id = $( "#modalAddInformant #occupation_id" ).val();
    var place_id = $( "#modalAddInformant #place_id" ).val();
    var birth_place_id = $( "#modalAddInformant #birth_place_id" ).val();
    var birth_date = $( "#modalAddInformant #birth_date" ).val();
    var route = '/person/informant';
    var test_url = '?name_ru='+name_ru+'&pol='+pol+'&nationality_id='+nationality_id
            +'&pol='+occupation_id+'&occupation_id='+occupation_id+'&place_id='+place_id
            +'&birth_place_id='+birth_place_id+'&birth_date='+birth_date+'&from_ajax=1';
//alert(route + test_url);    
    $.ajax({
        url: route, 
        data: {name_ru: name_ru, 
               pol: pol,
               nationality_id: nationality_id,
               occupation_id: occupation_id,
               place_id: place_id,
               birth_place_id: birth_place_id,
               birth_date: birth_date,
               from_ajax: 1
              },
        type: 'POST',
        success: function(informant_id){       
/*console.log('qid: ' +qid);    */
            $("#modalAddInformant").modal('hide');
            if (informant_id) {
                var opt = new Option(name_ru, informant_id);
                $("#informant_id").append(opt);
                opt.setAttribute('selected','selected')
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+jqXHR.status + ', ' + jqXHR.statusText+'), ' + 
               	       'text status: ('+textStatus+'), error thrown: ('+errorThrown+'), route: ' + route + test_url;
            alert(text);
        }
    }); 
}

function addRecorder() {
    $("#modalAddRecorder").modal('show');
}

function saveRecorder() {
    var name_ru = $( "#modalAddRecorder #name_ru" ).val();
    var pol = $('#modalAddRecorder input[name="pol"]:checked').val();
    var nationality_id = $( "#modalAddRecorder #nationality_id" ).val();
    var occupation_id = $( "#modalAddRecorder #occupation_id" ).val();
    var route = '/person/recorder';
    var test_url = '?name_ru='+name_ru+'&pol='+pol+'&nationality_id='+nationality_id+'&pol='+occupation_id+'&occupation_id='+occupation_id+'&from_ajax=1';
//alert(route + test_url);    
    $.ajax({
        url: route, 
        data: {name_ru: name_ru, 
               pol: pol,
               nationality_id: nationality_id,
               occupation_id: occupation_id,
               from_ajax: 1
              },
        type: 'POST',
        success: function(recorder_id){       
/*console.log('qid: ' +qid);    */
            $("#modalAddRecorder").modal('hide');
            if (recorder_id) {
                var opt = new Option(name_ru, recorder_id);
                $("#recorder_id").append(opt);
                opt.setAttribute('selected','selected')
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+jqXHR.status + ', ' + jqXHR.statusText+'), ' + 
               	       'text status: ('+textStatus+'), error thrown: ('+errorThrown+'), route: ' + route + test_url;
            alert(text);
        }
    }); 
}

