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

function selectQsection(section_var, placeholder='', allow_clear=false){
    $(".select-qsection").select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: "/ques/qsection/list",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              section_id: $( "#"+section_var+" option:selected" ).val(),
            };
          },
          processResults: function (data) {
            return {
              results: data
            };
          },          
          cache: true
        }
    });   
}

/**
 * choose anketa for copy answers
 * 
 * @param {int} without
 * @param {string} placeholder
 * @param {boolean} allow_clear
 * @returns {undefined}
 */
function selectAnketaForCopy(without, placeholder='', allow_clear=false){
    $(".select-anketa").select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: "/ques/anketa/list",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              without: without,
            };
          },
          processResults: function (data) {
            return {
              results: data
            };
          },          
          cache: true
        }
    });   
    
    $("#search_anketa_from")
        .change(function () {
            var anketa_from=$(".select-anketa option:selected").val();
console.log($(".select-anketa option:selected").text());    
            if (anketa_from != null) {
                var qid=$("#qid-for-copy").val();
                loadAnketaQuesForCopy(anketa_from, qid);
            }
        })
        .change();    
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


