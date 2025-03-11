<?php init_head();?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12" id="small-table">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-12">
                      <h4 class="no-margin font-bold"><i class="fa fa-user-o" aria-hidden="true"></i> <?php echo _l($title); ?></h4>
                      <hr />
                    </div>
                  </div>
                  <a href="<?php echo admin_url('recruitment/candidates'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_candidate'); ?></a>
                  <a href="#" onclick="send_mail_candidate(); return false;" class="btn btn-success pull-left display-block mleft5" ><i class="fa fa-envelope"></i><?php echo ' ' . _l('send_mail'); ?></a>
                    <br><br><br>
                  <?php render_datatable(array(
	_l('candidate_code'),
	_l('candidate_name'),
	_l('tranfer_personnel'),
	_l('status'),
	_l('email'),
	_l('phonenumber'),
	_l('birthday'),
	_l('campaign'),
), 'table_rec_candidate');?>
               </div>
            </div>
         </div>

      </div>
   </div>
</div>
<div class="modal fade" id="mail_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
      <?php echo form_open_multipart(admin_url('recruitment/send_mail_list_candidate'), array('id' => 'mail_candidate-form')); ?>
      <div class="modal-content width-100">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">
                  <span><?php echo _l('send_mail'); ?></span>
              </h4>
          </div>
          <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <label for="candidate"><?php echo _l('send_to'); ?></label>
                    <select name="candidate[]" id="candidate" class="selectpicker" multiple="true"  data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >

                        <?php foreach ($candidates as $s) {?>
                        <option value="<?php echo html_entity_decode($s['id']); ?>"><?php echo html_entity_decode($s['candidate_code'] . ' ' . $s['candidate_name']); ?></option>
                          <?php }?>
                    </select>
                    <br><br>
                </div>
                <div class="col-md-12">

                </div>

                <div class="col-md-12">
                  <?php echo render_input('subject', 'subject'); ?>
                </div>

                <div class="col-md-12">
                  <?php echo render_textarea('content', 'content', '', array(), array(), '', 'tinymce') ?>
                </div>
                <div id="type_care">

                </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type=""class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
              <button id="sm_btn" type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
          </div>
      </div><!-- /.modal-content -->
          <?php echo form_close(); ?>
      </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

<?php init_tail();?>
</body>
</html>