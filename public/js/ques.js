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

 * @param int qid
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
                    $("#anketa-ques-copy-"+qid).show();                
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
        $("#anketa-ques-copy-"+qid).hide();                
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
    
    function copyAnswers(from_anketa, qid) {
        var to_anketa = $("#anketa-for-copy").val();
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
    
    function callCopyAnswerText($answer_text) {
        $("#modalCopyAnswerText").modal('show');
        $("#answer_text_for_copy").val($answer_text);
    }
    
    function copyAnswerText(from_question) {
        var to_qsection=$("#qsection_id option:selected").val();
        var to_question=$("#question_id option:selected").val();
        var to_answer=$("#answer_id option:selected").val();
        var answer_text=$("#answer_text_for_copy").val();
        var url='/ques/question/copy/' + from_question + '_' + to_qsection 
                + '?to_question=' + (to_question ? to_question : 0) 
                + '&to_answer=' + (to_answer ? to_answer : 0) 
                + '&answer_text=' + (answer_text ? answer_text : '');
        if (to_qsection) {
//            alert(url);
            $.ajax({
                url: url, 
                type: 'GET',
                success: function(result){
                    alert(result);
                    $("#modalCopyAnswerText").modal('hide');
                },
                error: function() {
                }
            });   
        }
    }
