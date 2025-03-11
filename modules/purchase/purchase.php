<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Purchase Management
Description: Purchase management is a business discipline that enables companies to manage the activities and relationships that make up the purchasing functions necessary to do business. At its core purchase management is all about saving money, increasing profits and it is an important function for any wholesale, distribution or manufacturing business.
Version: 1.0.0
Requires at least: 2.3.*
Author: Hung Tran
Author URI: https://codecanyon.net/user/hungtran118
*/

define('PURCHASE_MODULE_NAME', 'purchase');
define('PURCHASE_MODULE_UPLOAD_FOLDER', module_dir_path(PURCHASE_MODULE_NAME, 'uploads'));

hooks()->add_action('admin_init', 'purchase_permissions');
hooks()->add_action('app_admin_footer', 'purchase_head_components');
hooks()->add_action('app_admin_footer', 'purchase_add_footer_components');
hooks()->add_action('app_admin_head', 'purchase_add_head_components');
hooks()->add_action('admin_init', 'purchase_module_init_menu_items');
define('PURCHASE_PATH', 'modules/purchase/uploads/');
define('PURCHASE_MODULE_ITEM_UPLOAD_FOLDER', 'modules/purchase/uploads/item_img/');


/**
* Register activation module hook
*/
register_activation_hook(PURCHASE_MODULE_NAME, 'purchase_module_activation_hook');
/**
* Load the module helper
*/
$CI = & get_instance();
$CI->load->helper(PURCHASE_MODULE_NAME . '/purchase');

function purchase_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(PURCHASE_MODULE_NAME, [PURCHASE_MODULE_NAME]);

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function purchase_module_init_menu_items()
{
    
    $CI = &get_instance();
    if (has_permission('purchase', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('purchase', [
            'name'     => _l('purchase'),
            'icon'     => 'fa fa-shopping-cart',
            'position' => 70,
        ]);

        $CI->db->where('module_name','warehouse');
        $module = $CI->db->get(db_prefix().'modules')->row();

       
           $CI->app_menu->add_sidebar_children_item('purchase', [
            'slug'     => 'purchase-items',
            'name'     => _l('items'),
            'icon'     => 'fa fa-clone menu-icon',
            'href'     => admin_url('purchase/items'),
            'position' => 1,
            ]);
      
        

        $CI->app_menu->add_sidebar_children_item('purchase', [
            'slug'     => 'vendors',
            'name'     => _l('vendor'),
            'icon'     => 'fa fa-user-o',
            'href'     => admin_url('purchase/vendors'),
            'position' => 2,
        ]);

        $CI->app_menu->add_sidebar_children_item('purchase', [
            'slug'     => 'purchase-request',
            'name'     => _l('purchase_request'),
            'icon'     => 'fa fa-shopping-basket',
            'href'     => admin_url('purchase/purchase_request'),
            'position' => 3,
        ]);

        $CI->app_menu->add_sidebar_children_item('purchase', [
            'slug'     => 'quotation',
            'name'     => _l('quotations'),
            'icon'     => 'fa fa-file-powerpoint-o',
            'href'     => admin_url('purchase/quotations'),
            'position' => 4,
        ]);

        $CI->app_menu->add_sidebar_children_item('purchase', [
            'slug'     => 'purchase-order',
            'name'     => _l('purchase_order'),
            'icon'     => 'fa fa-cart-plus',
            'href'     => admin_url('purchase/purchase_order'),
            'position' => 5,
        ]);

        $CI->app_menu->add_sidebar_children_item('purchase', [
            'slug'     => 'contract',
            'name'     => _l('contracts'),
            'icon'     => 'fa fa-file-text-o',
            'href'     => admin_url('purchase/contracts'),
            'position' => 6,
        ]);

        $CI->app_menu->add_sidebar_children_item('purchase', [
            'slug'     => 'reports',
            'name'     => _l('reports'),
            'icon'     => 'fa fa-bar-chart',
            'href'     => admin_url('purchase/reports'),
            'position' => 7,
        ]);

        $CI->app_menu->add_sidebar_children_item('purchase', [
            'slug'     => 'settings',
            'name'     => _l('setting'),
            'icon'     => 'fa fa-gears',
            'href'     => admin_url('purchase/setting'),
            'position' => 8,
        ]);
    }
    
}

/**
 * { purchase permissions }
 */
function purchase_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('purchase', $capabilities, _l('purchase'));
}

/**
 * purchase add footer components
 * @return
 */
function purchase_add_footer_components() {
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];
    if(!(strpos($viewuri, '/admin/purchase/vendors') === false)){
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/js/vendor_manage.js') . '"></script>';
    }
    if(!(strpos($viewuri, '/admin/purchase/purchase_request') === false)){    
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/js/pur_request_manage.js') . '"></script>';
    }
    if(!(strpos($viewuri, '/admin/purchase/quotations') === false)){
        echo '<script src="'. base_url('assets/plugins/signature-pad/signature_pad.min.js').'"></script>';
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/js/quotation_manage.js') . '"></script>';
    }
    if(!(strpos($viewuri, '/admin/purchase/pur_request') === false)){
        echo '<link rel="stylesheet prefetch" href="'.base_url('modules/purchase/assets/plugins/handsontable/chosen.css').'">';
        echo '<script src="'.base_url('modules/purchase/assets/plugins/handsontable/chosen.jquery.js').'"></script>';
        echo '<script src="'.base_url('modules/purchase/assets/plugins/handsontable/handsontable-chosen-editor.js').'"></script>'; 
    }
    if(!(strpos($viewuri, '/admin/purchase/view_pur_request') === false)){
        echo '<link rel="stylesheet prefetch" href="'.base_url('modules/purchase/assets/plugins/handsontable/chosen.css').'">';
        echo '<script src="'. base_url('assets/plugins/signature-pad/signature_pad.min.js').'"></script>';
        echo '<script src="'.base_url('modules/purchase/assets/plugins/handsontable/chosen.jquery.js').'"></script>';
        echo '<script src="'.base_url('modules/purchase/assets/plugins/handsontable/handsontable-chosen-editor.js').'"></script>'; 
    }
    if(!(strpos($viewuri, '/admin/purchase/purchase_order') === false)){
       
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/js/purchase_order_manage.js') . '"></script>';
    }
    if(!(strpos($viewuri, '/admin/purchase/contracts') === false)){
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/js/contract_manage.js') . '"></script>';
    }
    if(!(strpos($viewuri, '/admin/purchase/contract') === false)){
       
        echo '<script src="'.base_url('assets/plugins/signature-pad/signature_pad.min.js').'"></script>';
    }
    if (!(strpos($viewuri, '/admin/purchase/setting') === false)) {
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/js/manage_setting.js') . '"></script>';
    }
    if (!(strpos($viewuri, '/admin/purchase/pur_order') === false)) {
        echo '<link rel="stylesheet prefetch" href="'.base_url('modules/purchase/assets/plugins/handsontable/chosen.css').'">';
        echo '<script src="'.base_url('modules/purchase/assets/plugins/handsontable/chosen.jquery.js').'"></script>';
        echo '<script src="'.base_url('modules/purchase/assets/plugins/handsontable/handsontable-chosen-editor.js').'"></script>'; 
    }
    if (!(strpos($viewuri, '/admin/purchase/estimate') === false)) {
        echo '<link rel="stylesheet prefetch" href="'.base_url('modules/purchase/assets/plugins/handsontable/chosen.css').'">';
        echo '<script src="'.base_url('modules/purchase/assets/plugins/handsontable/chosen.jquery.js').'"></script>';
        echo '<script src="'.base_url('modules/purchase/assets/plugins/handsontable/handsontable-chosen-editor.js').'"></script>'; 
    }

    if (!(strpos($viewuri, '/admin/purchase/reports') === false)) {
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>'; 
    }

    if (!(strpos($viewuri, '/admin/purchase/items') === false)) {
         echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.min.js') . '"></script>';
         echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.jquery.min.js') . '"></script>';
         echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/simplelightbox/masonry-layout-vanilla.min.js') . '"></script>';
         
    }
    
}

/**
 * add purchase add head components
 * @return
 */
function purchase_add_head_components() {
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];
    if(!(strpos($viewuri, '/admin/purchase/pur_request') === false)){
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if(!(strpos($viewuri, '/admin/purchase/view_pur_request') === false)){
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if(!(strpos($viewuri, '/admin/purchase/estimate') === false)){
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if(!(strpos($viewuri, '/admin/purchase/pur_order') === false)){
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
    }

    if(!(strpos($viewuri, '/admin/purchase/setting?group=units') === false)){
        echo '<script src="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.js') . '"></script>';
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/handsontable/handsontable.full.min.css') . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/setting.css') . '"  rel="stylesheet" type="text/css" />';
    }
}

/**
 * purchase head components
 * @return
 */
function purchase_head_components() {
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];
    
    if(!(strpos($viewuri, '/admin/purchase') === false)){
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/style.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if(!(strpos($viewuri, '/admin/purchase/contract') === false)){
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/contract.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if (!(strpos($viewuri, '/admin/purchase/setting') === false)) {
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/setting.css') . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/commodity_list.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if(!(strpos($viewuri, '/admin/purchase/purchase_order') === false)){
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/pur_order_manage.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if(!(strpos($viewuri, '/admin/purchase/pur_order') === false)){
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/pur_order.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if(!(strpos($viewuri, '/admin/purchase/pur_request') === false)){
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/pur_request.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if(!(strpos($viewuri, '/admin/purchase/purchase_request') === false)){    
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/pur_request_manage.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if(!(strpos($viewuri, '/admin/purchase/view_pur_request') === false)){
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/view_pur_request.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if(!(strpos($viewuri, '/admin/purchase/estimate') === false)){
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/estimate_template.css') . '"  rel="stylesheet" type="text/css" />';
    }
    if(!(strpos($viewuri, '/admin/purchase/quotations') === false)){
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/estimate_preview_template.css') . '"  rel="stylesheet" type="text/css" />';
    }

    if(!(strpos($viewuri, '/admin/purchase/items') === false)){
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/css/commodity_list.css') . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/simplelightbox/simple-lightbox.min.css') . '"  rel="stylesheet" type="text/css" />';
        echo '<link href="' . module_dir_url(PURCHASE_MODULE_NAME, 'assets/plugins/simplelightbox/masonry-layout-vanilla.min.css') . '"  rel="stylesheet" type="text/css" />';
        
    }
}   