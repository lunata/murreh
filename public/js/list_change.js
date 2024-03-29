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

function checkedValuesToURL(varname) {
    var forURL = [];
    $( varname + ":checked" ).each(function( index, element ){
        forURL.push($(this).val());
    });
    return forURL;
}

function selectQsection(section_var='', placeholder='', allow_clear=false){
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
              section_id: section_var ? $( "#"+section_var+" option:selected" ).val() : '',
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

function selectQuestion(qsection_var='', placeholder='', allow_clear=false){
//console.log(getMultiValues(qsection_var));    
    $(".select-question").select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: "/ques/question/list",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              'qsection_ids[]': getMultiValues(qsection_var),
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

function selectConceptCategory(section_var='', placeholder='', allow_clear=false){
    $(".select-category").select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: "/sosd/concept_category/list",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              section_id: section_var ? $( "#"+section_var+" option:selected" ).val() : '',
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

function selectConcept(category_selector='#search_category option:selected', placeholder='', allow_clear=false){
    $(".select-concept").select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: "/sosd/concept/list",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              'category_id[]': getMultiValues(category_selector),
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

function getMultiValues(selector) {
//console.log(selector);    
//console.log($(selector));    
    if (selector === '') {
        return '';
    }
    return $(selector).map(function() {
                    return this.value;
                }).get();
}

function selectConceptCategory(section_var, placeholder='', allow_clear=false){
    $(".select-category").select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: "/sosd/concept_category/list",
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
/*
function selectConcept(category_var, placeholder='', allow_clear=false){
    $(".select-concept").select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: "/sosd/concept/list",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              category_id: $( "#"+category_var+" option:selected" ).val(),
//              category_id: checkedValuesToURL("."+category_var),
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
*/
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

function selectPlace(district_var='', placeholder='', allow_clear=false){
    $(".select-place").select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: "/geo/place/list",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              method_count: 'anketas',
              district_id: district_var ? $( "#"+district_var+" option:selected" ).val() : '',
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

function selectDialect(lang_var, placeholder='', allow_clear=false){
    $('.select-dialect').select2({
        allowClear: allow_clear,
        placeholder: placeholder,
        width: '100%',
        ajax: {
          url: "/dict/dialect/list",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              lang_id: selectedValuesToURL("#"+lang_var)
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

