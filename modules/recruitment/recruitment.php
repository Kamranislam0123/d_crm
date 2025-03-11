<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Recruitment
Description: Recruitment Management module for Perfex CRM
Version: 1.0.0
Requires at least: 2.3.*
Author: Hung Tran
Author URI: https://codecanyon.net/user/hungtran118
 */

define('RECRUITMENT_MODULE_NAME', 'recruitment');
define('RECRUITMENT_MODULE_UPLOAD_FOLDER', module_dir_path(RECRUITMENT_MODULE_NAME, 'uploads'));
define('RECRUITMENT_PATH', 'modules/recruitment/uploads/');

hooks()->add_action('admin_init', 'recruitment_permissions');
hooks()->add_action('app_admin_footer', 'recruitment_head_components');
hooks()->add_action('app_admin_footer', 'recruitment_add_footer_components');
hooks()->add_action('admin_init', 'recruitment_module_init_menu_items');

/**
 * Register activation module hook
 */
register_activation_hook(RECRUITMENT_MODULE_NAME, 'recruitment_module_activation_hook');
/**
 * Load the module helper
 */
$CI = &get_instance();
$CI->load->helper(RECRUITMENT_MODULE_NAME . '/recruitment');

function recruitment_module_activation_hook() {
	$CI = &get_instance();
	require_once __DIR__ . '/install.php';
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(RECRUITMENT_MODULE_NAME, [RECRUITMENT_MODULE_NAME]);

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function recruitment_module_init_menu_items() {

	$CI = &get_instance();
	if (has_permission('recruitment', '', 'view')) {
		$CI->app_menu->add_sidebar_menu_item('recruitment', [
			'name' => _l('recruitment'),
			'icon' => 'fa fa-address-book',
			'position' => 60,
		]);
		$CI->app_menu->add_sidebar_children_item('recruitment', [
			'slug' => 'recruitment_dashboard',
			'name' => _l('dashboard'),
			'icon' => 'fa fa-home',
			'href' => admin_url('recruitment/dashboard'),
			'position' => 1,
		]);

		$CI->app_menu->add_sidebar_children_item('recruitment', [
			'slug' => 'recruitment-proposal',
			'name' => _l('proposal'),
			'icon' => 'fa fa-address-card-o',
			'href' => admin_url('recruitment/recruitment_proposal'),
			'position' => 2,
		]);

		$CI->app_menu->add_sidebar_children_item('recruitment', [
			'slug' => 'recruitment-campaign',
			'name' => _l('campaign'),
			'icon' => 'fa fa-sitemap',
			'href' => admin_url('recruitment/recruitment_campaign'),
			'position' => 3,
		]);

		$CI->app_menu->add_sidebar_children_item('recruitment', [
			'slug' => 'candidate-profile',
			'name' => _l('candidate_profile'),
			'icon' => 'fa fa-user-o',
			'href' => admin_url('recruitment/candidate_profile'),
			'position' => 4,
		]);

		$CI->app_menu->add_sidebar_children_item('recruitment', [
			'slug' => 'interview-schedule',
			'name' => _l('interview_schedule'),
			'icon' => 'fa fa-calendar',
			'href' => admin_url('recruitment/interview_schedule'),
			'position' => 5,
		]);

		$CI->app_menu->add_sidebar_children_item('recruitment', [
			'slug' => 'recruitment-channel',
			'name' => _l('_recruitment_channel'),
			'icon' => 'fa fa-feed',
			'href' => admin_url('recruitment/recruitment_channel'),
			'position' => 6,
		]);

		$CI->app_menu->add_sidebar_children_item('recruitment', [
			'slug' => 'rec_settings',
			'name' => _l('setting'),
			'icon' => 'fa fa-gears',
			'href' => admin_url('recruitment/setting'),
			'position' => 7,
		]);
	}

}

/**
 * recruitment permissions
 * @return
 */
function recruitment_permissions() {
	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('recruitment', $capabilities, _l('recruitment'));
}

/**
 * add head components
 */
function recruitment_head_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/styles.css') . '"  rel="stylesheet" type="text/css" />';
	if ($viewuri == '/admin/recruitment/dashboard') {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/dashboard.css') . '"  rel="stylesheet" type="text/css" />';
	}
	if ($viewuri == '/admin/recruitment/candidates') {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/candidate.css') . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/recruitment/candidate') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/candidate_detail.css') . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/recruitment/setting') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/setting.css') . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/recruitment/interview_schedule') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/interview_schedule_preview.css') . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/recruitment/recruitment_campaign') === false)) {
		echo '<link href="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/css/campaign_preview.css') . '"  rel="stylesheet" type="text/css" />';
	}
}

/**
 * add footer_components
 * @return
 */
function recruitment_add_footer_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if ($viewuri == '/admin/recruitment/dashboard') {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>';
	}
	if ($viewuri == '/admin/recruitment/recruitment_proposal') {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/proposal.js') . '"></script>';
	}
	if ($viewuri == '/admin/recruitment/candidates') {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/candidate.js') . '"></script>';
	}
	if ($viewuri == '/admin/recruitment/candidate_profile') {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/candidate_profile.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/transfer_to_hr') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/transferhr.js') . '"></script>';
	}
	if ($viewuri == '/admin/recruitment/setting?group=evaluation_criteria') {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/evaluation_criteria.js') . '"></script>';
	}
	if ($viewuri == '/admin/recruitment/setting?group=evaluation_form') {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/evaluation_form.js') . '"></script>';
	}
	if ($viewuri == '/admin/recruitment/setting?group=job_position' || $viewuri == '/admin/recruitment/setting') {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/job_position.js') . '"></script>';
	}
	if ($viewuri == '/admin/recruitment/setting?group=tranfer_personnel') {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/tranfer_personnel.js') . '"></script>';
	}
	if ($viewuri == '/admin/recruitment/interview_schedule') {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/interview_schedule.js') . '"></script>';
	}
	if ($viewuri == '/admin/recruitment/recruitment_campaign') {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/campaign.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/recruitment/recruitment_campaign') === false)) {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/campaign_preview.js') . '"></script>';
	}
	if ($viewuri == '/admin/recruitment/recruitment_channel') {
		echo '<script src="' . module_dir_url(RECRUITMENT_MODULE_NAME, 'assets/js/channel.js') . '"></script>';
	}	
}