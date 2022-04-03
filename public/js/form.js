function submitByButton(form_id, action) {
    $("#"+form_id).attr('action', action);
    $("#"+form_id).submit();
}