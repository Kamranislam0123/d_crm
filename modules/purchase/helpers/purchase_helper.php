<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * Check whether column exists in a table
 * Custom function because Codeigniter is caching the tables and this is causing issues in migrations
 * @param  string $column column name to check
 * @param  string $table table name to check
 * @return boolean
 */
/**
 * Determines whether the specified identifier is empty vendor company.
 *
 * @param      <type>   $id     The identifier
 *
 * @return     boolean  True if the specified identifier is empty vendor company, False otherwise.
 */
function is_empty_vendor_company($id)
{
    $CI = & get_instance();
    $CI->db->select('company');
    $CI->db->from(db_prefix() . 'pur_vendor');
    $CI->db->where('userid', $id);
    $row = $CI->db->get()->row();
    if ($row) {
        if ($row->company == '') {
            return true;
        }

        return false;
    }

    return true;
}

/**
 * Gets the sql select vendor company.
 *
 * @return     string  The sql select vendor company.
 */
function get_sql_select_vendor_company()
{
    return 'CASE company WHEN "" THEN (SELECT CONCAT(firstname, " ", lastname) FROM ' . db_prefix() . 'pur_contacts WHERE ' . db_prefix() . 'pur_contacts.userid = ' . db_prefix() . 'pur_vendor.userid and is_primary = 1) ELSE company END as company';
}

/**
 * Determines if vendor admin.
 *
 * @param      string   $id        The identifier
 * @param      string   $staff_id  The staff identifier
 *
 * @return     integer  True if vendor admin, False otherwise.
 */
function is_vendor_admin($id, $staff_id = '')
{
    $staff_id = is_numeric($staff_id) ? $staff_id : get_staff_user_id();
    $CI       = &get_instance();
    $cache    = $CI->app_object_cache->get($id . '-is-vendor-admin-' . $staff_id);

    if ($cache) {
        return $cache['retval'];
    }

    $total = total_rows(db_prefix() . 'pur_vendor_admin', [
        'vendor_id' => $id,
        'staff_id'    => $staff_id,
    ]);

    $retval = $total > 0 ? true : false;
    $CI->app_object_cache->add($id . '-is-vendor-admin-' . $staff_id, ['retval' => $retval]);

    return $retval;
}

/**
 * Gets the vendor company name.
 *
 * @param      string   $userid                 The userid
 * @param      boolean  $prevent_empty_company  The prevent empty company
 *
 * @return     string   The vendor company name.
 */
function get_vendor_company_name($userid, $prevent_empty_company = false)
{
    if ($userid !== '') {
        $_userid = $userid;
    }
    $CI = & get_instance();

    $client = $CI->db->select('company')
    ->where('userid', $_userid)
    ->from(db_prefix() . 'pur_vendor')
    ->get()
    ->row();
    if ($client) {
        return $client->company;
    }

    return '';
}

/**
 * Gets the status approve.
 *
 * @param      integer|string  $status  The status
 *
 * @return     string          The status approve.
 */
function get_status_approve($status){
    $result = '';
    if($status == 1){
        $result = '<span class="label label inline-block project-status-'.$status.' status-approve-1"> '._l('not_yet_approve').' </span>';
    }elseif($status == 2){
        $result = '<span class="label label inline-block project-status-'.$status.' status-approve-2"> '._l('approved').' </span>';
    }elseif($status == 3){
        $result = '<span class="label label inline-block project-status-'.$status.' status-approve-3"> '._l('reject').' </span>';
    }elseif($status == 4){
        $result = '<span class="label label inline-block project-status-'.$status.' status-approve-4"> '._l('cancelled').' </span>';
    }

    return $result;

}

/**
 * Gets the status pur order.
 *
 * @param      integer|string  $status  The status
 *
 * @return     string          The status pur order.
 */
function get_status_pur_order($status){
    $result = '';
    if($status == 1){
        $result = '<span class="label label inline-block project-status-'.$status.' status-pur-order-1"> '._l('not_start').' </span>';
    }elseif($status == 2){
        $result = '<span class="label label inline-block project-status-'.$status.' status-pur-order-2"> '._l('in_proccess').' </span>';
    }elseif($status == 3){
        $result = '<span class="label label inline-block project-status-'.$status.' status-pur-order-3"> '._l('complete').' </span>';
    }elseif($status == 4){
        $result = '<span class="label label inline-block project-status-'.$status.' status-pur-order-4"> '._l('cancel').' </span>';
    }

    return $result;
}

/**
 * { format pur estimate number }
 *
 * @param      <type>  $id     The identifier
 *
 * @return     string  ( estimate number )
 */
function format_pur_estimate_number($id)
{
    $CI = & get_instance();
    $CI->db->select('date,number,prefix,number_format')->from(db_prefix().'pur_estimates')->where('id', $id);
    $estimate = $CI->db->get()->row();

    if (!$estimate) {
        return '';
    }

    $number = sales_number_format($estimate->number, $estimate->number_format, $estimate->prefix, $estimate->date);

    return hooks()->apply_filters('format_estimate_number', $number, [
        'id'       => $id,
        'estimate' => $estimate,
    ]);
}

/**
 * Gets the item hp.
 *
 * @param      string  $id     The identifier
 *
 * @return     <type>  a item or list item.
 */
function get_item_hp($id = ''){
    $CI           = & get_instance();
    if($id != ''){
        $CI->db->where('id', $id);
        return $CI->db->get(db_prefix().'items')->row();
    }elseif ($id == '') {
        return $CI->db->get(db_prefix().'items')->result_array();
    }
}

/**
 * Gets the status modules pur.
 *
 * @param      string   $module_name  The module name
 *
 * @return     boolean  The status modules pur.
 */
function get_status_modules_pur($module_name){
    $CI             = &get_instance();
    $sql = 'select * from '.db_prefix().'modules where module_name = "'.$module_name.'" AND active =1 ';
    $module = $CI->db->query($sql)->row();
    if($module){
        return true;
    }else{
        return false;
    }
}

/**
 * { reformat currency pur }
 *
 * @param      <string>  $value  The value
 *
 * @return     <string>  ( string replace ',' )
 */
function reformat_currency_pur($value)
{
    return str_replace(',','', $value);
}

/**
 * { pur contract pdf }
 *
 * @param      <type>  $contract  The contract
 *
 * @return     <type>  ( pdf )
 */
function pur_contract_pdf($contract)
{
    return app_pdf('contract',  module_dir_path(PURCHASE_MODULE_NAME, 'libraries/pdf/Pur_contract_pdf'), $contract);
}

/**
 * { purchase process digital signature image }
 *
 * @param      <type>   $partBase64  The part base 64
 * @param      <type>   $path        The path
 * @param      string   $image_name  The image name
 *
 * @return     boolean  
 */
function purchase_process_digital_signature_image($partBase64, $path, $image_name)
{
    if (empty($partBase64)) {
        return false;
    }

    _maybe_create_upload_path($path);
    $filename = unique_filename($path, $image_name.'.png');

    $decoded_image = base64_decode($partBase64);

    $retval = false;

    $path = rtrim($path, '/') . '/' . $filename;

    $fp = fopen($path, 'w+');

    if (fwrite($fp, $decoded_image)) {
        $retval                                 = true;
        $GLOBALS['processed_digital_signature'] = $filename;
    }

    fclose($fp);

    return $retval;
}

/**
 * { handle request quotation upload file quotation }
 *
 * @param      string   $id     The identifier
 *
 * @return     boolean   
 */
function handle_request_quotation($id){
     if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '') {

        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = PURCHASE_MODULE_UPLOAD_FOLDER .'/request_quotation/'. $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['attachment']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['attachment']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Gets the staff email by identifier pur.
 *
 * @param      <type>   $id     The identifier
 *
 * @return     boolean  The staff email by identifier pur.
 */
function get_staff_email_by_id_pur($id)
{
    $CI = & get_instance();

    $staff = $CI->app_object_cache->get('staff-email-by-id-' . $id);

    if (!$staff) {
        $CI->db->where('staffid', $id);
        $staff = $CI->db->select('email')->from(db_prefix() . 'staff')->get()->row();
        $CI->app_object_cache->add('staff-email-by-id-' . $id, $staff);
    }

    return ($staff ? $staff->email : '');
}

/**
 * Gets the purchase option.
 *
 * @param      <type>        $name   The name
 *
 * @return     array|string  The purchase option.
 */
function get_purchase_option($name)
{
    $CI = & get_instance();
    $options = [];
    $val  = '';
    $name = trim($name);
    

    if (!isset($options[$name])) {
        // is not auto loaded
        $CI->db->select('option_val');
        $CI->db->where('option_name', $name);
        $row = $CI->db->get(db_prefix() . 'purchase_option')->row();
        if ($row) {
            $val = $row->option_val;
        }
    } else {
        $val = $options[$name];
    }

    return $val;
}

/**
 * { row purchase options exist }
 *
 * @param      <type>   $name   The name
 *
 * @return     integer  ( 1 or 0 )
 */
function row_purchase_options_exist($name){
    $CI = & get_instance();
    $i = count($CI->db->query('Select * from '.db_prefix().'purchase_option where option_name = '.$name)->result_array());
    if($i == 0){
        return 0;
    }
    if($i > 0){
        return 1;
    }
}

/**
 * { handle purchase order file }
 *
 * @param      string   $id     The identifier
 *
 * @return     boolean  
 */
function handle_purchase_order_file($id)
{
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = PURCHASE_MODULE_UPLOAD_FOLDER .'/pur_order/'. $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['file']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI           = & get_instance();
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'],
                    ];
                $CI->misc_model->add_attachment_to_database($id, 'pur_order', $attachment);

                return true;
            }
        }
    }

    return false;
}

/**
 * { purorder left to pay }
 *
 * @param      <type>   $id     The purchase order
 *
 * @return     integer  ( purchase order left to pay )
 */
function purorder_left_to_pay($id)
{
    $CI = & get_instance();

    
        $CI->db->select('total')
        ->where('id', $id);
        $invoice_total = $CI->db->get(db_prefix() . 'pur_orders')->row()->total;


    
    $CI->load->model('purchase_model');
    
    $payments = $CI->purchase_model->get_payment_purchase_order($id);

    $totalPayments = 0;

    

    foreach ($payments as $payment) {
        
        $totalPayments += $payment['amount'];
        
    }

    return ($invoice_total - $totalPayments);
}

/**
 * Gets the payment mode by identifier.
 *
 * @param      <type>  $id     The identifier
 *
 * @return     string  The payment mode by identifier.
 */
function get_payment_mode_by_id($id){
    $CI = & get_instance();
    $CI->db->where('id',$id);
    $mode = $CI->db->get(db_prefix().'payment_modes')->row();
    if($mode){
        return $mode->name;
    }else{
        return '';
    }
}

/**
 * get unit type
 * @param  integer $id
 * @return array or row
 */
 function get_unit_type_item($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
    $CI->db->where('unit_type_id', $id);
        return $CI->db->get(db_prefix() . 'ware_unit_type')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblware_unit_type')->result_array();
    }

}


/**
 * handle commodity attchment
 * @param  integer $id
 * @return array or row
 */
function handle_item_attachments($id)
{

    if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES['file']['error']);
        die;
    }
    $path = PURCHASE_MODULE_ITEM_UPLOAD_FOLDER . $id . '/';
    $CI   = & get_instance();

    if (isset($_FILES['file']['name'])) {

        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {

            _maybe_create_upload_path($path);
            $filename    = $_FILES['file']['name'];
            $newFilePath = $path . $filename;
            // Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {

                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'],
                    ];

                $CI->misc_model->add_attachment_to_database($id, 'commodity_item_file', $attachment);
            }
        }
    }

}


/**
 * get tax rate
 * @param  integer $id
 * @return array or row
 */
 function get_tax_rate_item($id = false)
    {
        $CI           = & get_instance();

        if (is_numeric($id)) {
        $CI->db->where('id', $id);

            return $CI->db->get(db_prefix() . 'taxes')->row();
        }
        if ($id == false) {
            return $CI->db->query('select * from tbltaxes')->result_array();
        }

    }


/**
 * get group name
 * @param  integer $id
 * @return array or row
 */
function get_group_name_item($id = false)
{
    $CI           = & get_instance();

    if (is_numeric($id)) {
    $CI->db->where('id', $id);

        return $CI->db->get(db_prefix() . 'items_groups')->row();
    }
    if ($id == false) {
        return $CI->db->query('select * from tblitems_groups')->result_array();
    }

}
