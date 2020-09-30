function chooseList(list_name, div_name, url) {
    $("#"+list1_name)
        .change(function () {
            var selected_val=$( "#"+ list_name +" option:selected" ).val();
            $("#"+div_name).load(url+selected_val);
        })
        .change();    
}

function selectedValuesToURL(varname) {
    var forURL = [];
    $( varname + " option:selected" ).each(function( index, element ){
        forURL.push($(this).val());
    });
    return forURL;
}

function langSelect(lang_var="lang_id") {
    $("#"+lang_var)
        .change(function () {
            //$('.select-dialect').val(null).trigger('change');    
/*
            var lang = $( "#lang_id option:selected" ).val();
            if (lang==5) { // livvic
                $("#wordforms-field").show().prop("disabled", false);
            } else {
                $("#wordforms-field").hide().attr('checked',false).prop("disabled", true);
            } */
          })
        .change();    
}


