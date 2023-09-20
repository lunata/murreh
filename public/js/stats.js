function totalAnketas() {
    $.ajax({
        url: 'ques/anketa/total', 
        type: 'GET',
        success: function(total){       
            $("#total-anketas").html(total);
        },
        error: function () {
            $("#total-anketas").html('Error');
        }
    }); 
}

function totalRecorders() {
    $.ajax({
        url: 'person/recorder/total', 
        type: 'GET',
        success: function(total){       
            $("#total-recorders").html(total);
        },
        error: function () {
            $("#total-recorders").html('Error');
        }
    }); 
}

function totalPlaces() {
    $.ajax({
        url: 'geo/place/total', 
        type: 'GET',
        success: function(total){       
            $("#total-places").html(total);
        },
        error: function () {
            $("#total-places").html('Error');
        }
    }); 
}

function totalAnswers(section) {
    $.ajax({
        url: 'ques/anketa_question/total/'+section, 
        type: 'GET',
        success: function(total){       
            $("#total-answers-"+section).html(total);
        },
        error: function () {
            $("#total-answers-"+section).html('Error');
        }
    }); 
}

