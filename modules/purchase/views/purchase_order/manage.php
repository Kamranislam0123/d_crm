<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="panel_s mbot10">
				<div class="panel-body">
	              	<div class="row">    
	                    <div class="_buttons col-md-3">
	                    	<?php if (has_permission('purchase', '', 'create') || is_admin()) { ?>
	                        <a href="<?php echo admin_url('purchase/pur_order'); ?>"class="btn btn-info pull-left mright10 display-block">
	                            <?php echo _l('new_pur_order'); ?>
	                        </a>
	                        <?php } ?>
	                    </div>
	                    
	                    <div class="_buttons col-md-1 pull-right">
	                    <a href="#" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs pull-right" onclick="toggle_small_pur_order_view('.table-table_pur_order','#pur_order'); return false;" data-toggle="tooltip" title="<?php echo _l('estimates_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
	                	</div>
	            	</div>
	              	<div class="row">
	              		<hr>
	              		<div class="col-md-3">
	                        <?php echo render_date_input('from_date',_l('from_date'),''); ?>
	                    </div>
	                    <div class="col-md-3">
	                        <?php echo render_date_input('to_date',_l('to_date'),''); ?>
	                    </div>
	                    <div class="col-md-3">
	                        <?php 
	                        $statuses = [0 => ['id' => '1', 'name' => _l('not_yet_approve')],
	                    	1 => ['id' => '2', 'name' => _l('approved')],
	                		2 => ['id' => '3', 'name' => _l('reject')],
	                		3 => ['id' => '4', 'name' => _l('canclled')],];

	                        echo render_select('status[]',$statuses,array('id','name'),'status','',array('data-width'=>'100%','data-none-selected-text'=>_l('leads_all'),'multiple'=>true,'data-actions-box'=>true),array(),'no-mbot','',false); ?>
	                    </div>
	                    <div class="col-md-3">
	                        <?php echo render_select('vendor[]',$vendors,array('userid','company'),'vendor','',array('data-width'=>'100%','data-none-selected-text'=>_l('leads_all'),'multiple'=>true,'data-actions-box'=>true),array(),'no-mbot','',false); ?>
	                    </div>
	                    
	              	</div>
	            </div>
            </div>
            <div class="row">
				<div class="col-md-12" id="small-table">
					<div class="panel_s">
						<div class="panel-body">
	                    <?php echo form_hidden('pur_orderid',$pur_orderid); ?>
	                    <?php render_datatable(array(
	    
	                        _l('purchase_order'),
	                        _l('total'),
	                        _l('estimates_total_tax'),
	                        _l('vendor'),
	                        _l('order_date'),
	                        _l('payment_status'),
	                        _l('status'),
	                        ),'table_pur_order'); ?>
							
						</div>
					</div>
				</div>
            	
			<div class="col-md-7 small-table-right-col">
			    <div id="pur_order" class="hide">
			    </div>
			 </div>
            </div>
		</div>
	</div>
</div>

<?php init_tail(); ?>
</body>
</html>