<script>

function removeCommas(str) {
  "use strict";
  return(str.replace(/,/g,''));
}

function dc_percent_change(invoker){
  "use strict";
  var total_mn = $('input[name="total_mn"]').val();
  var t_mn = parseInt(removeCommas(total_mn));
  var rs = (t_mn*invoker.value)/100;

  $('input[name="dc_total"]').val(numberWithCommas(rs));
  $('input[name="after_discount"]').val(numberWithCommas(t_mn - rs));

}

function dc_total_change(invoker){
  "use strict";
  var total_mn = $('input[name="total_mn"]').val();
  var t_mn = parseInt(removeCommas(total_mn));
  var rs = t_mn - parseInt(removeCommas(invoker.value));

   $('input[name="after_discount"]').val(numberWithCommas(rs));
}

$(function(){
  "use strict";
		validate_purorder_form();
    function validate_purorder_form(selector) {

        selector = typeof(selector) == 'undefined' ? '#pur_order-form' : selector;

        appValidateForm($(selector), {
            pur_order_name: 'required',
            pur_order_number: 'required',
            order_date: 'required',
            vendor: 'required',
        });
    }


});
function estimate_by_vendor(invoker){
  "use strict";
  if(invoker.value != 0){
    $.post(admin_url + 'purchase/estimate_by_vendor/'+invoker.value).done(function(response){
      response = JSON.parse(response);
      $('select[name="estimate"]').html('');
      $('select[name="estimate"]').append(response.result);
      $('select[name="estimate"]').selectpicker('refresh');
    });

  }
}

<?php if(!isset($pur_order)){
 ?>	
function numberWithCommas(x) {
  "use strict";
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
var dataObject = [
      
    ];
  var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;
    var hotSettings = {
      data: dataObject,
      columns: [
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
          readOnly: true
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
          	  multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
      
        }
      
      ],
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      width: '100%',
      autoWrapRow: true,
      rowHeights: 30,
      columnHeaderHeight: 40,
      minRows: 10,
      maxRows: 40,
      rowHeaders: true,
      colWidths: [200,10,100,50,100,50,100,50,100,100],
      colHeaders: [
        '<?php echo _l('items'); ?>',
        '<?php echo _l('unit'); ?>',
        '<?php echo _l('unit_price'); ?>',
        '<?php echo _l('quantity'); ?>',
        '<?php echo _l('subtotal'); ?>',
        '<?php echo _l('tax'); ?>',
        '<?php echo _l('subtotal_after_tax'); ?>',
        '<?php echo _l('discount(%)').'(%)'; ?>',
        '<?php echo _l('discount(money)'); ?>',
        '<?php echo _l('total'); ?>',
      ],
       columnSorting: {
        indicator: true
      },
      autoColumnSize: {
        samplingRatio: 23
      },
      dropdownMenu: true,
      mergeCells: true,
      contextMenu: true,
      manualRowMove: true,
      manualColumnMove: true,
      multiColumnSorting: {
        indicator: true
      },
      filters: true,
      manualRowResize: true,
      manualColumnResize: true
    };


var hot = new Handsontable(hotElement, hotSettings);
hot.addHook('afterChange', function(changes, src) {
	if(changes !== null){
	    changes.forEach(([row, prop, oldValue, newValue]) => {
        if(newValue != ''){
  	      if(prop == 'item_code'){
  	        $.post(admin_url + 'purchase/items_change/'+newValue).done(function(response){
  	          response = JSON.parse(response);

  	          hot.setDataAtCell(row,1, response.value.unit_id);
  	          hot.setDataAtCell(row,2, response.value.purchase_price);
  	          hot.setDataAtCell(row,4, response.value.purchase_price*hot.getDataAtCell(row,3));
  	        });
  	      }else if(prop == 'quantity'){
            hot.setDataAtCell(row,4, newValue*hot.getDataAtCell(row,2));
  	        hot.setDataAtCell(row,6, newValue*hot.getDataAtCell(row,2));
  	        hot.setDataAtCell(row,9, newValue*hot.getDataAtCell(row,2));
  	      }else if(prop == 'tax'){
  	      	$.post(admin_url + 'purchase/tax_change/'+newValue).done(function(response){
  	          response = JSON.parse(response);
  	          hot.setDataAtCell(row,6, (response.total_tax*parseInt(hot.getDataAtCell(row,4)))/100 + parseInt(hot.getDataAtCell(row,4)));
              hot.setDataAtCell(row,9, (response.total_tax*parseInt(hot.getDataAtCell(row,4)))/100 + parseInt(hot.getDataAtCell(row,4)));
  	      	});
  	      }else if(prop == 'discount_%'){
            hot.setDataAtCell(row,8, (newValue*parseInt(hot.getDataAtCell(row,6)))/100 );

          }else if(prop == 'discount_money'){
             hot.setDataAtCell(row,9, (parseInt(hot.getDataAtCell(row,6)) - newValue));
          }else if(prop == 'total_money'){
           var total_money = 0;
            for (var row_index = 0; row_index <= 40; row_index++) {
              if(parseInt(hot.getDataAtCell(row_index, 9)) > 0){
                total_money += (parseInt(hot.getDataAtCell(row_index, 9)));
              }
            }
             $('input[name="total_mn"]').val(numberWithCommas(total_money));
          }
        }
	    });
	}
  });
$('.save_detail').on('click', function() {
  $('input[name="pur_order_detail"]').val(hot.getData());   
});

function coppy_pur_estimate(){
  "use strict";
  var pur_estimate = $('select[name="estimate"]').val();
  if(pur_estimate != ''){
     hot.alter('remove_row',0,hot.countRows ());
    $.post(admin_url + 'purchase/coppy_pur_estimate/'+pur_estimate).done(function(response){
          response = JSON.parse(response);
          hot.updateSettings({
        data: response.result,
        });

          var total_money = 0;
          for (var row_index = 0; row_index <= 40; row_index++) {
            if(parseInt(hot.getDataAtCell(row_index, 9)) > 0){
              total_money += (parseInt(hot.getDataAtCell(row_index, 9)));
            }
          }
          $('input[name="total_mn"]').val(numberWithCommas(total_money));
          $('input[name="dc_percent"]').val(numberWithCommas(response.dc_percent));
          $('input[name="dc_total"]').val(numberWithCommas(response.dc_total));
          $('input[name="after_discount"]').val(numberWithCommas(total_money - response.dc_total));
    });

    
  }else{
    alert_float('warning', '<?php echo _l('please_chose_pur_estimate'); ?>')
  }
}


<?php } else{ ?>

function numberWithCommas(x) {
  "use strict";
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

	var dataObject = <?php echo html_entity_decode($pur_order_detail); ?>;
  var hotElement = document.querySelector('#example');
    var hotElementContainer = hotElement.parentNode;
    var hotSettings = {
      data: dataObject,
      columns: [
      	{
          data: 'id',
          type: 'numeric',
      
        },
        {
          data: 'pur_order',
          type: 'numeric',
      
        },
        {
          data: 'item_code',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 150,
          chosenOptions: {
              data: <?php echo json_encode($items); ?>
          }
        },
        {
          data: 'unit_id',
          renderer: customDropdownRenderer,
          editor: "chosen",
          width: 50,
          chosenOptions: {
              data: <?php echo json_encode($units); ?>
          },
          readOnly: true
     
        },
        {
          data: 'unit_price',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
          readOnly: true
        },
        {
          data: 'quantity',
          type: 'numeric',
      
        },
        {
          data: 'into_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
          readOnly: true
        },
        {
          data: 'tax',
          renderer: customDropdownRenderer,
          editor: "chosen",
          multiSelect:true,
          width: 50,
          chosenOptions: {
          	  multiple: true,
              data: <?php echo json_encode($taxes); ?>
          }
        },
        {
          data: 'total',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          },
          readOnly: true
        },{
          data: 'discount_%',
          type: 'numeric',
          renderer: customRenderer
        },
        {
          data: 'discount_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
        },
        {
          data: 'total_money',
          type: 'numeric',
          numericFormat: {
            pattern: '0,0'
          }
      
        }
      
      ],
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      width: '100%',
      autoWrapRow: true,
      rowHeights: 30,
      columnHeaderHeight: 40,
      minRows: 10,
      maxRows: 40,
      rowHeaders: true,
      colWidths: [0,0,200,50,100,50,100,50,100,50,100,100],
      colHeaders: [
      	'',
        '',
        '<?php echo _l('items'); ?>',
        '<?php echo _l('unit'); ?>',
        '<?php echo _l('unit_price'); ?>',
        '<?php echo _l('quantity'); ?>',
        '<?php echo _l('subtotal'); ?>',
        '<?php echo _l('tax'); ?>',
        '<?php echo _l('subtotal_after_tax'); ?>',
        '<?php echo _l('discount(%)').'(%)'; ?>',
        '<?php echo _l('discount(money)'); ?>',
        '<?php echo _l('total'); ?>',
      ],
       columnSorting: {
        indicator: true
      },
      autoColumnSize: {
        samplingRatio: 23
      },
      dropdownMenu: true,
      mergeCells: true,
      contextMenu: true,
      manualRowMove: true,
      manualColumnMove: true,
      multiColumnSorting: {
        indicator: true
      },
      hiddenColumns: {
        columns: [0,1],
        indicators: true
      },
      filters: true,
      manualRowResize: true,
      manualColumnResize: true
    };


var hot = new Handsontable(hotElement, hotSettings);
hot.addHook('afterChange', function(changes, src) {
	if(changes !== null){
	    changes.forEach(([row, prop, oldValue, newValue]) => {
        if(newValue != ''){
	      if(prop == 'item_code'){
	        $.post(admin_url + 'purchase/items_change/'+newValue).done(function(response){
	          response = JSON.parse(response);

	          hot.setDataAtCell(row,3, response.value.unit_id);
	          hot.setDataAtCell(row,4, response.value.purchase_price);
	          hot.setDataAtCell(row,6, response.value.purchase_price*hot.getDataAtCell(row,5));
	        });
	      }else if(prop == 'quantity'){
          hot.setDataAtCell(row,6, newValue*hot.getDataAtCell(row,4));
	        hot.setDataAtCell(row,8, newValue*hot.getDataAtCell(row,4));
	        hot.setDataAtCell(row,11, newValue*hot.getDataAtCell(row,4));
	      }else if(prop == 'tax'){
	      	$.post(admin_url + 'purchase/tax_change/'+newValue).done(function(response){
	          response = JSON.parse(response);
	          hot.setDataAtCell(row,8, (response.total_tax*parseInt(hot.getDataAtCell(row,6)))/100 + parseInt(hot.getDataAtCell(row,6)));
             hot.setDataAtCell(row,11, (response.total_tax*parseInt(hot.getDataAtCell(row,6)))/100 + parseInt(hot.getDataAtCell(row,6)));
	      	});
	      }else if(prop == 'discount_%'){
          hot.setDataAtCell(row,10, (newValue*parseInt(hot.getDataAtCell(row,8)))/100 );

        }else if(prop == 'discount_money'){
           hot.setDataAtCell(row,11, (parseInt(hot.getDataAtCell(row,8)) - newValue));
        }else if(prop == 'total_money'){
         var total_money = 0;
          for (var row_index = 0; row_index <= 40; row_index++) {
            if(parseInt(hot.getDataAtCell(row_index, 11)) > 0){
              total_money += (parseInt(hot.getDataAtCell(row_index, 11)));
            }
          }
          $('input[name="total_mn"]').val(numberWithCommas(total_money));
        }
      }
	    });
	}
  });
$('.save_detail').on('click', function() {
  $('input[name="pur_order_detail"]').val(hot.getData());   
});

id = $('select[name="vendor"]').val();
$.post(admin_url + 'purchase/estimate_by_vendor/'+id).done(function(response){
  response = JSON.parse(response);
  $('select[name="estimate"]').html('');
  $('select[name="estimate"]').append(response.result);
  $('select[name="estimate"]').val(<?php echo html_entity_decode($pur_order->estimate); ?>).change();
  $('select[name="estimate"]').selectpicker('refresh');
});

var total_money = 0;
for (var row_index = 0; row_index <= 40; row_index++) {
  if(parseInt(hot.getDataAtCell(row_index, 11)) > 0){
    total_money += (parseInt(hot.getDataAtCell(row_index, 11)));
  }
  
 
}
$('input[name="total_mn"]').val(numberWithCommas(total_money));

function coppy_pur_estimate(){
  "use strict";
  var pur_estimate = $('select[name="estimate"]').val();
  if(pur_estimate != ''){
     hot.alter('remove_row',0,hot.countRows ());
    $.post(admin_url + 'purchase/coppy_pur_estimate/'+pur_estimate).done(function(response){
          response = JSON.parse(response);
          hot.updateSettings({
        data: response.result,
        });
        var total_money = 0;
        for (var row_index = 0; row_index <= 40; row_index++) {
          if(parseInt(hot.getDataAtCell(row_index, 11)) > 0){
            total_money += (parseInt(hot.getDataAtCell(row_index, 11)));
          }
        }
        $('input[name="total_mn"]').val(numberWithCommas(total_money));
        $('input[name="dc_percent"]').val(numberWithCommas(response.dc_percent));
        $('input[name="dc_total"]').val(numberWithCommas(response.dc_total));
        $('input[name="after_discount"]').val(numberWithCommas(total_money - response.dc_total));  
    });
  }else{
    alert_float('warning', '<?php echo _l('please_chose_pur_estimate'); ?>')
  }
}

<?php } ?>
function customRenderer(instance, td, row, col, prop, value, cellProperties) {
  "use strict";
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    if(td.innerHTML != ''){
      td.innerHTML = td.innerHTML + '%'
      td.className = 'htRight';
    }
}

function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
  "use strict";
	  var selectedId;
	  var optionsList = cellProperties.chosenOptions.data;
	  
	  if(typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList.length) {
	      Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
	      return td;
	  }

	  var values = (value + "").split("|");
	  value = [];
	  for (var index = 0; index < optionsList.length; index++) {

	      if (values.indexOf(optionsList[index].id + "") > -1) {
	          selectedId = optionsList[index].id;
	          value.push(optionsList[index].label);
	      }
	  }
	  value = value.join(", ");

	  Handsontable.cellTypes.text.renderer(instance, td, row, col, prop, value, cellProperties);
	  return td;
}

</script>