(function($) {
"use strict";
	initDataTable('.table-table_rec_candidate', admin_url+'recruitment/table_candidates');	
})(jQuery);
function send_mail_candidate(){
"use strict";
  $('#mail_modal').modal('show');
  appValidateForm($('#mail_candidate-form'), {
           content: 'required', subject:'required',email:'required'});
}