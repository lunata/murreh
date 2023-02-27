function submitByButton(form_id, action) {
    $("#"+form_id).attr('action', action);
    $("#"+form_id).submit();
}

function callQsectionCreateForm() {
    $("#qsectionCreation").modal('show');  
    $("#qsectionCreation input").prop( "disabled", false );
}
function saveQuestion() {
    $("#cluster_form").attr('action', '/ques/question/store_from_cluster');
//console.log($("#cluster_form").attr('action'));    
    $("#cluster_form").submit();
/*
    var data = {
            qsection_id: $('#qsectionCreationForm #qsection_id').val(),
            question: $('#qsectionCreationForm #question').val()
        };*/
/*        $('#qsectionCreationForm').serialize();*/
/*console.log('data: ');
console.log(data);    
    $.ajax({
        url: '/ques/question/', 
        data: data,
        type: 'POST',
        success: function(answer){       
            alert(answer);
        },
        error: function () {
            alert('ERROR');
        }
    }); 
*/    
}