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
