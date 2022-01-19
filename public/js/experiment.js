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