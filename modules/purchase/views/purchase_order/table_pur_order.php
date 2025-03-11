<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
     
    
    'pur_order_name',
    'total',
    'total_tax',
    'vendor', 
    'order_date',
    'subtotal',
    'approve_status',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix().'pur_orders';
$join         = ['LEFT JOIN '.db_prefix().'pur_vendor ON '.db_prefix().'pur_vendor.userid = '.db_prefix().'pur_orders.vendor'];
$where = [];

if(isset($vendor)){
    array_push($where, ' AND '.db_prefix().'pur_orders.vendor = '.$vendor);
}

if ($this->ci->input->post('from_date')
    && $this->ci->input->post('from_date') != '') {
    array_push($where, 'AND order_date >= "'.$this->ci->input->post('from_date').'"');
}

if ($this->ci->input->post('to_date')
    && $this->ci->input->post('to_date') != '') {
    array_push($where, 'AND order_date <= "'.$this->ci->input->post('to_date').'"');
}


if ($this->ci->input->post('status')
    && count($this->ci->input->post('status')) > 0) {
    array_push($where, 'AND status IN (' . implode(',', $this->ci->input->post('status')) . ')');
}

if ($this->ci->input->post('vendor')
    && count($this->ci->input->post('vendor')) > 0) {
    array_push($where, 'AND vendor IN (' . implode(',', $this->ci->input->post('vendor')) . ')');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id','company','pur_order_number']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

   for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        if($aColumns[$i] == 'total'){
            $_data = app_format_money($aRow['total'], '');
        }elseif($aColumns[$i] == 'pur_order_name'){

            $numberOutput = '';
    
            $numberOutput = '<a href="' . admin_url('purchase/purchase_order/' . $aRow['id']) . '"  onclick="init_pur_order(' . $aRow['id'] . '); return false;" >'.$aRow['pur_order_number'].' - ' . $aRow['pur_order_name'] . '</a>';
            
            $numberOutput .= '<div class="row-options">';

            if (has_permission('purchase', '', 'view')) {
                $numberOutput .= ' <a href="' . admin_url('purchase/purchase_order/' . $aRow['id']) . '" onclick="init_pur_order(' . $aRow['id'] . '); return false;" >' . _l('view') . '</a>';
            }
            if (has_permission('purchase', '', 'edit')) {
                $numberOutput .= ' | <a href="' . admin_url('purchase/pur_order/' . $aRow['id']) . '">' . _l('edit') . '</a>';
            }
            if (has_permission('purchase', '', 'delete')) {
                $numberOutput .= ' | <a href="' . admin_url('purchase/delete_pur_order/' . $aRow['id']) . '" class="text-danger">' . _l('delete') . '</a>';
            }
            $numberOutput .= '</div>';

            $_data = $numberOutput;

        }elseif($aColumns[$i] == 'vendor'){
            $_data = '<a href="' . admin_url('purchase/vendor/' . $aRow['vendor']) . '" >' .  $aRow['company'] . '</a>';
        }elseif ($aColumns[$i] == 'order_date') {
            $_data = _d($aRow['order_date']);
        }elseif($aColumns[$i] == 'approve_status'){
            $_data = get_status_approve($aRow['approve_status']);
        }elseif($aColumns[$i] == 'total_tax'){
            $_data = app_format_money($aRow['total_tax'], '');
        }elseif($aColumns[$i] == 'subtotal'){
            $paid = $aRow['total'] - purorder_left_to_pay($aRow['id']);
            $percent = 0;
            if($aRow['total'] > 0){
                $percent = ($paid / $aRow['total'] ) * 100;
            }
            
            $_data = '<div class="progress">
                          <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                          aria-valuemin="0" aria-valuemax="100" style="width:'.round($percent).'%">
                           ' .round($percent).' % 
                          </div>
                        </div>';
        }

        $row[] = $_data;
    }
    $output['aaData'][] = $row;

}
