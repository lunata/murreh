function loadConceptPlaceForm(place_id, category_id) {
    $("#concept-place-edit-"+category_id).hide();    // pencil icon            
    $("#concept-place-"+category_id).empty();
    $("#loading-form-"+category_id).show();
    $.ajax({
        url: '/sosd/concept_place/' + place_id + '_' + category_id + '/edit', 
        type: 'GET',
        success: function(result){
            $("#concept-place-"+category_id).html(result);
            $("#loading-form-"+category_id).hide();                
        },
        error: function() {
            $("#concept-place-"+category_id).html('ERROR'); 
/*        error: function(jqXHR, textStatus, errorThrown) {
            var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+jqXHR.status + ', ' + jqXHR.statusText+'), ' + 
                       'text status: ('+textStatus+'), error thrown: ('+errorThrown+')'; 
            $("#anketa-ques-"+qid).html(text);*/
            $("#loading-form-"+category_id).hide();                
        }
    });         
}

function addWord(concept_id) {
    count = $("#concept-voc-"+concept_id).data('next-count');
    $.ajax({
        url: '/sosd/concept_place/' + concept_id + '_' + count + '/edit_voc', 
        type: 'GET',
        success: function(result){
            $("#concept-voc-"+concept_id).append(result);
        },
        error: function() {
            $("#concept-voc-"+concept_id).append('ERROR');
/*        error: function(jqXHR, textStatus, errorThrown) {
            var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+jqXHR.status + ', ' + jqXHR.statusText+'), ' + 
                       'text status: ('+textStatus+'), error thrown: ('+errorThrown+')'; 
            $("#anketa-ques-"+qid).html(text);*/
        }
    });         
}

/**
 * @param {type} category_id
 * @returns {undefined}
 */
function saveVocs(category_id) {
    var form = $('#change-concepts-'+category_id);
    $(form).submit(function(event) {
        event.preventDefault();
        var formData = $(form).serialize();

        $.ajax({
            type: 'PUT',
            url: $(form).attr('action'),
            data: formData})
         .done(function(response) {
                $("#concept-place-"+category_id).html(response);
                $("#loading-form-"+category_id).hide();                
                $("#concept-place-edit-"+category_id).show();  // pencil icon              
        })
    });
}
    
function saveVoc() {
    var code = $( "#code" ).val();
    var answer = $( "#answer" ).val();
    var qid = $( "#qid" ).val();
    var route = '/ques/answer';
    var test_url = '?qid='+qid+'&code='+code+'&answer='+answer;
    
    $.ajax({
        url: route, 
        data: {question_id: qid, 
               code: code,
               answer: answer
              },
        type: 'POST',
        success: function(answer_id){       
/*console.log('qid: ' +qid);    */
            $("#modalAddAnswer").modal('hide');
            clearAnswerModal();
            if (answer_id) {
                var opt = new Option(answer, answer_id);
                $("#answers_"+qid+"__id_").append(opt);
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

    function fillAnswer(el, qid) {
        var answer_field = '#answers_'+qid+'__text_';
    
        if ($(answer_field).val() == '') {
            var a=$(el).find('option:selected').text(); 
            $(answer_field).val(a);
        }
    }
