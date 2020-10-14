function addDistrict() {
    $("#modalAddDistrict").modal('show');
}

function saveDistrict() {
    var name_ru = $( "#modalAddDistrict #name_ru" ).val();
    var region_id = $( "#region_id" ).val();
    var foundation = $( "#foundation" ).val();
    var abolition = $( "#abolition" ).val();
    var route = '/geo/district';
    var test_url = '?name_ru='+name_ru+'&region_id='+region_id+'&foundation='+foundation+'&abolition='+abolition+'&from_ajax=1';
//alert(route + test_url);    
    $.ajax({
        url: route, 
        data: {name_ru: name_ru, 
               region_id: region_id,
               foundation: foundation,
               abolition: abolition,
               from_ajax: 1
              },
        type: 'POST',
        success: function(district_id){       
/*console.log('qid: ' +qid);    */
            $("#modalAddDistrict").modal('hide');
            if (district_id) {
                var opt = new Option(name_ru, district_id);
                $("#district_id").append(opt);
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

function addPlace() {
    $("#modalAddPlace").modal('show');
}

function savePlace() {
/*    var name_ru = $( "#modalAddPlace #name_ru" ).val();
    var name_old_ru = $( "#name_old_ru" ).val();
    var name_krl = $( "#name_krl" ).val();
    var name_krl_ru = $( "#name_krl_ru" ).val();
    var district_id = $( "#districts_0__id_" ).val();
    var districts_0__from_ = $( "#districts_0__from_" ).val();
    var districts_0__to_ = $( "#districts_0__to_" ).val();
    var population = $( "#modalAddPlace #population" ).val();
    var latitude = $( "#latitude" ).val();
    var route = '/geo/place';
    var test_url = '?name_ru='+name_ru+'&region_id='+region_id+'&foundation='+foundation+'&abolition='+abolition+'&from_ajax=1';
//alert(route + test_url);    
    $.ajax({
        url: route, 
        data: {name_ru: name_ru, 
               region_id: region_id,
               foundation: foundation,
               abolition: abolition,
               from_ajax: 1
              },
        type: 'POST',
        success: function(place_id){       
console.log('qid: ' +qid);    
            $("#modalAddPlace").modal('hide');
            if (place_id) {
                var opt = new Option(name_ru, place_id);
                $("#place_id").append(opt);
                opt.setAttribute('selected','selected')
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+jqXHR.status + ', ' + jqXHR.statusText+'), ' + 
               	       'text status: ('+textStatus+'), error thrown: ('+errorThrown+'), route: ' + route + test_url;
            alert(text);
        }
    }); */
}
