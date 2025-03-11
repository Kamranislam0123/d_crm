<div class="col-md-12" id="small-table">
	<div class="row">
      <h4 class="no-margin font-bold"><i class="fa fa-cart-plus" aria-hidden="true"></i> <?php echo _l('purchase_order'); ?></h4>
      <hr />
  	</div>  	
    <br><br>
        
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
