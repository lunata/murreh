function selectAllFields(button_id, select_fields) {
    $('#'+button_id).on('change', function(){
        if ($('#'+button_id).prop('checked')){
//console.log($(select_fields));            
            $(select_fields).prop('checked', true);
        } else {
//console.log($(select_fields));            
            $(select_fields).prop('checked', false);
        }
    });
}

function showFull(varName) {
console.log('brief-'+varName);    
    $('#brief-'+varName).hide();
    $('#full-'+varName).show();
}

function hideFull(varName) {
console.log('full-'+varName);    
    $('#full-'+varName).hide();
    $('#brief-'+varName).show();
}