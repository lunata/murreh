function saveAnswer() {
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

function clearAnswerModal() {
    $("#addAnswerQuestion").html(null);
    $('#code').val(null);
    $('#answer').val(null).trigger('change');    
}

function addAnswer(qid) {
    var answer_text = $("#answers_"+qid+"__text_").val();   
    var question = $("#question-"+qid).html();   
    $("#modalAddAnswer").modal('show');
    $("#addAnswerQuestion").html(question);               
    $("#answer").val(answer_text);               
    $("#qid").val(qid);               
    
    $("#modalAddAnswer .close, #modalAddAnswer .cancel").on('click', function() {
        clearAnswerModal();
    });
}


/**
 * Эти 2 функции были в anketa.show в headExtra вместе с     <script></script>

 * @param {type} qid
 * @returns {undefined}
 */
    function saveAnswers(qid) {
        var form = $('#change-answers-'+qid);
        $(form).submit(function(event) {
            event.preventDefault();
            var formData = $(form).serialize();

            $.ajax({
                type: 'PUT',
                url: $(form).attr('action'),
                data: formData})
             .done(function(response) {
                    $("#anketa-ques-"+qid).html(response);
                    $("#loading-questions-"+qid).hide();                
                    $("#anketa-ques-edit-"+qid).show();                
            })
        });
    }
    
    function fillAnswer(el, qid) {
        var answer_field = '#answers_'+qid+'__text_';
    
        if ($(answer_field).val() == '') {
            var a=$(el).find('option:selected').text(); 
            $(answer_field).val(a);
        }
    }
    
    function loadAnketaQuestionForm(anketa_id, qid) {
        $("#anketa-ques-edit-"+qid).hide();                
        $("#anketa-ques-"+qid).empty();
        $("#loading-questions-"+qid).show();
        $.ajax({
            url: '/ques/anketa_question/' + anketa_id + '_' + qid + '/edit', 
            type: 'GET',
            success: function(result){
                $("#anketa-ques-"+qid).html(result);
                $("#loading-questions-"+qid).hide();                
            },
            error: function() {
                $("#anketa-ques-"+qid).html('ERROR'); 
    /*        error: function(jqXHR, textStatus, errorThrown) {
                var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+jqXHR.status + ', ' + jqXHR.statusText+'), ' + 
                           'text status: ('+textStatus+'), error thrown: ('+errorThrown+')'; 
                $("#anketa-ques-"+qid).html(text);*/
                $("#loading-questions-"+qid).hide();                
            }
        });         
    }

    function loadAnketaQuesForCopy(anketa_id, qid) {
        $.ajax({
            url: '/ques/anketa_question/list_for_copy/' + anketa_id + '_' + qid, 
            type: 'GET',
            success: function(result){
                $("#anketas-for-copy").html(result);
            },
            error: function() {
                $("#anketas-for-copy").html('ERROR'); 
    /*        error: function(jqXHR, textStatus, errorThrown) {
                var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+jqXHR.status + ', ' + jqXHR.statusText+'), ' + 
                           'text status: ('+textStatus+'), error thrown: ('+errorThrown+')'; 
                $("#anketa-ques-"+qid).html(text);*/
            }
        });         
    }
    
    function copyAnswers(from_anketa, to_anketa, qid) {
        $.ajax({
            url: '/ques/anketa_question/copy/' + from_anketa + '_' + to_anketa + '_' + qid, 
            type: 'GET',
            success: function(result){
                $("#modalCopyAnswers").modal('hide');
                $("#anketa-ques-"+qid).html(result);
            },
            error: function() {
                $("#anketas-for-copy").html('ERROR'); 
    /*        error: function(jqXHR, textStatus, errorThrown) {
                var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+jqXHR.status + ', ' + jqXHR.statusText+'), ' + 
                           'text status: ('+textStatus+'), error thrown: ('+errorThrown+')'; 
                $("#anketa-ques-"+qid).html(text);*/
            }
        });   
        
    }
