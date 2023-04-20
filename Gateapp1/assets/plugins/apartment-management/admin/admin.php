<?php
add_action( 'admin_menu', 'apartment_system_menu' );
function apartment_system_menu()
{
	if (function_exists('amgt_setup'))  
	{
		add_menu_page('Apartment Management', esc_html__('Apartment Management','apartment_mgt'),'manage_options','amgt-apartment_system','apartment_system_dashboard',plugins_url('apartment-management/assets/images/apartment-management-3.png' )); 
		if($_SESSION['amgt_verify'] == '')
		{
			add_submenu_page('amgt-apartment_system','Licence Settings',esc_html__('Licence Settings', 'apartment_mgt' ),'manage_options','amgt-amgt_setup','amgt_options_page');
		}
		add_submenu_page('amgt-apartment_system', 'Dashboard', esc_html__('Dashboard', 'apartment_mgt' ), 'administrator', 'amgt-apartment_system', 'apartment_system_dashboard');
		$unit_type=get_option( 'amgt_apartment_type' );
		
		if($unit_type == 'Residential')
		{	

			add_submenu_page('amgt-apartment_system', 'Residential Unit', esc_html__('Residential Unit ', 'apartment_mgt'), 'administrator', 'amgt-residential_unit', 'amgt_residential_unit');
		}
		else
		{
			
			add_submenu_page('amgt-apartment_system', 'Residential Unit', esc_html__('Commercial Unit ', 'apartment_mgt'), 'administrator', 'amgt-residential_unit', 'amgt_residential_unit');
		}
								
		add_submenu_page('amgt-apartment_system', 'All User', esc_html__('All User', 'apartment_mgt' ), 'administrator', 'amgt-member', 'amgt_member');
		
		add_submenu_page('amgt-apartment_system', 'Committee Members', esc_html__('Committee Members', 'apartment_mgt' ), 'administrator', 'amgt-committee-member', 'amgt_committee_member');
			
		add_submenu_page('amgt-apartment_system', 'Visitor Management', esc_html__('Visitor Management', 'apartment_mgt' ), 'administrator', 'amgt-visiter-manage', 'amgt_visiter_manage');
		
		add_submenu_page('amgt-apartment_system', 'Notice And Event', esc_html__('Notice And Event', 'apartment_mgt' ), 'administrator', 'amgt-notice-event', 'amgt_notice_event');
		
		add_submenu_page('amgt-apartment_system', 'Complain', esc_html__('Complain', 'apartment_mgt' ), 'administrator', 'amgt-complaint', 'amgt_complaint');
		
		add_submenu_page('amgt-apartment_system', 'Parking Manager', esc_html__('Parking Manager', 'apartment_mgt' ), 'administrator', 'amgt-parking-mgt', 'amgt_parking_mgt');
		
		add_submenu_page('amgt-apartment_system', 'Service', esc_html__('Services', 'apartment_mgt' ), 'administrator', 'amgt-service-mgt', 'amgt_service_mgt');
		
		add_submenu_page('amgt-apartment_system', 'Facility', esc_html__('Facility', 'apartment_mgt' ), 'administrator', 'amgt-facility-mgt', 'amgt_facility_mgt');
			
		add_submenu_page('amgt-apartment_system', 'Tax', esc_html__('Tax', 'apartment_mgt' ), 'administrator', 'amgt-tax', 'amgt_tax');
		
		add_submenu_page('amgt-apartment_system', 'Accounts', esc_html__('Accounts', 'apartment_mgt' ), 'administrator', 'amgt-accounts', 'amgt_accounts');
		
		add_submenu_page('amgt-apartment_system', 'Documents', esc_html__('Documents', 'apartment_mgt' ), 'administrator', 'amgt-legal-documents', 'amgt_legal_documents');
		
		add_submenu_page('amgt-apartment_system', 'Asset/Inventory Tracker', esc_html__('Asset/ Inventory Tracker', 'apartment_mgt' ), 'administrator', 'amgt-assets-inventory', 'amgt_assets_inventory');
		
		add_submenu_page('amgt-apartment_system', 'Mail Templates', esc_html__('Mail Templates', 'apartment_mgt' ), 'administrator', 'amgt-notification-templates', 'amgt_notification_templates');
		
		add_submenu_page('amgt-apartment_system', 'Message', esc_html__('Message', 'apartment_mgt' ), 'administrator', 'amgt-message', 'amgt_message');
		
		add_submenu_page('amgt-apartment_system', 'Report', esc_html__('Report', 'apartment_mgt' ), 'administrator', 'amgt-report', 'amgt_report');
		
		add_submenu_page('amgt-apartment_system', 'General Setting', esc_html__('General Setting', 'apartment_mgt' ), 'administrator', 'amgt-general_settings', 'amgt_general_settings_page');
		
		add_submenu_page('amgt-apartment_system', 'Access Right', esc_html__('Access Right', 'apartment_mgt' ), 'administrator', 'amgt-access_right', 'amgt_access_right');
	}  
	else
	{ 		      
		die;
	}
}
function amgt_options_page()
//PAGE SET UP FORM INDEX.PHP
{
	require_once AMS_PLUGIN_DIR. '/admin/setupform/index.php';
}
//PAGE DASBOARD.PHP
function apartment_system_dashboard()
{
	require_once AMS_PLUGIN_DIR. '/admin/dasboard.php';
}
//PAGE MEMBER INDEX.PHP
function amgt_member()
{
	require_once AMS_PLUGIN_DIR. '/admin/member/index.php';
}
//PAGE COMMITTEE-MEMBER INDEX.PHP
function amgt_committee_member()
{
	require_once AMS_PLUGIN_DIR. '/admin/committee-member/index.php';
}
//PAGE STAFF-MEMBER/INDEX.PHP
function amgt_staff_member()
{
	require_once AMS_PLUGIN_DIR. '/admin/staff-member/index.php';
}
// PAGE ACCOUNTANT INDEX.PHP
function amgt_accountant()
{
	require_once AMS_PLUGIN_DIR. '/admin/accountant/index.php';
}
//PAGE GATEKEEPER INDEX.PHP
function amgt_gatekeeper()
{
	require_once AMS_PLUGIN_DIR. '/admin/gatekeeper/index.php';
}
//VISITOR-MANAGE INDEX.PHP
function amgt_visiter_manage()
{
	require_once AMS_PLUGIN_DIR. '/admin/visitor-manage/index.php';
}
//RESIDENTIAL INDEX.PHP
function amgt_residential_unit()
{
	require_once AMS_PLUGIN_DIR. '/admin/residential-unit/index.php';
}
//COMPLAINT INDEX.PHP
function amgt_complaint()
{
	require_once AMS_PLUGIN_DIR. '/admin/complaint/index.php';
}
function amgt_notice_event()
{
	require_once AMS_PLUGIN_DIR. '/admin/notice-events/index.php';
}
function amgt_parking_mgt()
{
	require_once AMS_PLUGIN_DIR. '/admin/parking/index.php';
}
function amgt_service_mgt()
{
	require_once AMS_PLUGIN_DIR. '/admin/service/index.php';
}
function amgt_tax()
{
	require_once AMS_PLUGIN_DIR. '/admin/tax/index.php';
}
function amgt_facility_mgt()
{
	require_once AMS_PLUGIN_DIR. '/admin/facility/index.php';
}
function amgt_accounts()
{
	require_once AMS_PLUGIN_DIR. '/admin/accounts/index.php';
}
function amgt_legal_documents()
{
	require_once AMS_PLUGIN_DIR. '/admin/ducuments/index.php';
}

function amgt_assets_inventory()
{
	require_once AMS_PLUGIN_DIR. '/admin/assets-inventory/index.php';
}
function amgt_message()
{
	require_once AMS_PLUGIN_DIR. '/admin/message/index.php';
}
function amgt_report()
{
	require_once AMS_PLUGIN_DIR. '/admin/report/index.php';
}

function amgt_general_settings_page()
{
	require_once AMS_PLUGIN_DIR. '/admin/general-settings.php';
}
function amgt_access_right()
{
	require_once AMS_PLUGIN_DIR. '/admin/access_right/index.php';
}
function amgt_notification_templates()
{
	require_once AMS_PLUGIN_DIR. '/admin/notification-templates/index.php';
}
function amgt_maintenance_settings()
{
	require_once AMS_PLUGIN_DIR. '/admin/maintenance_settings/index.php';
}
?>