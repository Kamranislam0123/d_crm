function new_job_position(){
"use strict";
    $('#job_position').modal('show');
    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');
    $('#additional').html('');
}
function edit_job_position(invoker,id){
    "use strict";
    $('#additional').append(hidden_input('id',id));
    $('#job_position input[name="position_name"]').val($(invoker).data('name'));
    $('#job_position textarea[name="position_description"]').val($(invoker).data('position_description'));
    $('#job_position').modal('show');
    $('.add-title').addClass('hide');
    $('.edit-title').removeClass('hide');
}
