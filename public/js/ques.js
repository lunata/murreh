/**
 * Sends data to server for saving of an answer.
 * Calls changeWordBlock.
 * Closes the window.
 * 
 * @param {Integer} qid
 * @param {Integer} code
 * @param {String} answer 
 * @returns {undefined}
 */
function saveAnswer() {
    var code = $( "#code" ).val();
    var answer = $( "#answer" ).val();
    var qid = $( "#qid" ).val();
console.log('qid: ' +qid);    
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
console.log('qid: ' +qid);    
            $("#modalAddAnswer").modal('hide');
            clearAnswerModal();
            if (answer_id) {
                var opt = new Option(answer, answer_id);
                $("#answers_"+qid+"__id_").append(opt);
                opt.setAttribute('selected','selected')
            }
console.log('qid: ' +qid);    
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

/**
 * Adds answers for the given question.
 * Opens a window after clicking on the unmarked (black) word.
 * Calls saveAnswer().
 * 
 * @param integer anketa_id 
 * @param integer qid
 * @returns NULL
 */    
function addAnswer(qid) {
console.log('qid: ' +qid);    
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