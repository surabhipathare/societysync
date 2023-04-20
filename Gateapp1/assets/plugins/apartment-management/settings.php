<?php 
require_once AMS_PLUGIN_DIR. '/amgt-function.php';
require_once AMS_PLUGIN_DIR. '/amgt-ajax-functions.php';
require_once AMS_PLUGIN_DIR. '/class/apartment-management.php';
require_once AMS_PLUGIN_DIR. '/class/residential-unit.php';
require_once AMS_PLUGIN_DIR. '/class/member.php';
require_once AMS_PLUGIN_DIR. '/class/complaint.php';
require_once AMS_PLUGIN_DIR. '/class/visiter-manage-class.php';
require_once AMS_PLUGIN_DIR. '/class/parking.php';
require_once AMS_PLUGIN_DIR. '/class/document.php';
require_once AMS_PLUGIN_DIR. '/class/service.php';
require_once AMS_PLUGIN_DIR. '/class/facility.php';
require_once AMS_PLUGIN_DIR. '/class/assets-inventory.php';
require_once AMS_PLUGIN_DIR. '/class/message.php';
require_once AMS_PLUGIN_DIR. '/class/notice-events.php';
require_once AMS_PLUGIN_DIR. '/class/account.php';
require_once AMS_PLUGIN_DIR. '/class/tax.php';
require_once AMS_PLUGIN_DIR. '/class/maintance_setting.php';
require_once AMS_PLUGIN_DIR. '/lib/paypal/paypal_class.php';
add_action( 'admin_head', 'amgt_admin_css' );
function amgt_admin_css(){
?>
<style>
a.toplevel_page_amgt-apartment_system:hover,  a.toplevel_page_amgt-apartment_system:focus,.toplevel_page_amgt-apartment_system.opensub a.wp-has-submenu{
  background: url("<?php echo AMS_PLUGIN_URL;?>/assets/images/apartment-management-2.png") no-repeat scroll 8px 9px rgba(0, 0, 0, 0) !important;
  
}
.toplevel_page_amgt-apartment_system:hover .wp-menu-image.dashicons-before img {
  display: none;
}

.toplevel_page_amgt-apartment_system:hover .wp-menu-image.dashicons-before {
  min-width: 23px !important;
}
    
</style>
<?php
}	
if ( is_admin() )
{
	require_once AMS_PLUGIN_DIR. '/admin/admin.php';
	function apartment_install()
	{
		add_role('accountant', esc_html__('Accountant' ,'apartment_mgt'),array( 'read' => true, 'level_1' => true ));
		add_role('member', esc_html__('Member' ,'apartment_mgt'),array( 'read' => true, 'level_0' => true ));
		add_role('staff_member', esc_html__('Staff-Member' ,'apartment_mgt'),array( 'read' => true, 'level_0' => true ));
		add_role('committee_member', esc_html__('Committee Member' ,'apartment_mgt'),array( 'read' => true, 'level_0' => true ));
		add_role('gatekeeper', esc_html__('Gatekeeper' ,'apartment_mgt'),array( 'read' => true, 'level_0' => true ));
		amgt_install_tables();
		
        amgt_install_unit_category_post();	
		
		
	}
	register_activation_hook(AMS_PLUGIN_BASENAME, 'apartment_install' );
	function amgt_option(){
		$role_access_right_member= array();
		$role_access_right_member['member'] = [
									"resident_unit"=>["menu_icone"=>plugins_url( 'apartment-management/assets/images/icon/resident-unit.png' ),
												'menu_title'=>'Resident Unit',
											   "page_link"=>'resident_unit',
											   "own_data" =>isset($_REQUEST['resident_unit_own_data'])?$_REQUEST['resident_unit_own_data']:1,
											   "add" =>isset($_REQUEST['resident_unit_add'])?$_REQUEST['resident_unit_add']:0,
												"edit"=>isset($_REQUEST['resident_unit_edit'])?$_REQUEST['resident_unit_edit']:0,
												"view"=>isset($_REQUEST['resident_unit_view'])?$_REQUEST['resident_unit_view']:1,
												"delete"=>isset($_REQUEST['resident_unit_delete'])?$_REQUEST['resident_unit_delete']:0
												],
														
								   "member"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/member.png' ),
												'menu_title'=>'All User',
											  "page_link"=>'member',
											 "own_data" => isset($_REQUEST['member_own_data'])?$_REQUEST['member_own_data']:1,
											 "add" => isset($_REQUEST['member_add'])?$_REQUEST['member_add']:0,
											 "edit"=>isset($_REQUEST['member_edit'])?$_REQUEST['member_edit']:0,
											 "view"=>isset($_REQUEST['member_view'])?$_REQUEST['member_view']:1,
											 "delete"=>isset($_REQUEST['member_delete'])?$_REQUEST['member_delete']:0
								  ],
											  
									"committee-member"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Committee-Member.png' ),			
											'menu_title'=>'Committee Member',
											"page_link"=>'committee-member',
											 "own_data" => isset($_REQUEST['committee-member_own_data'])?$_REQUEST['committee-member_own_data']:0,
											 "add" => isset($_REQUEST['committee-member_add'])?$_REQUEST['committee-member_add']:0,
											"edit"=>isset($_REQUEST['committee-member_edit'])?$_REQUEST['committee-member_edit']:0,
											"view"=>isset($_REQUEST['committee-member_view'])?$_REQUEST['committee-member_view']:1,
											"delete"=>isset($_REQUEST['committee-member_delete'])?$_REQUEST['committee-member_delete']:0
								  ],
											  
									  "accountant"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Accountant.png' ),
												'menu_title'=>'Accountant',
												"page_link"=>'accountant',
												"own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:0,
												 "add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
												 "edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
												"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:1,
												"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
									  ],
									  
									  "staff-members"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Staff-Management.png' ),			
												'menu_title'=>'Staff Management',
												 "page_link"=>'staff-members',
												 "own_data" => isset($_REQUEST['staff-members_own_data'])?$_REQUEST['staff-members_own_data']:0,
												 "add" => isset($_REQUEST['staff-members_add'])?$_REQUEST['staff-members_add']:0,
												"edit"=>isset($_REQUEST['staff-members_edit'])?$_REQUEST['staff-members_edit']:0,
												"view"=>isset($_REQUEST['staff-members_view'])?$_REQUEST['staff-members_view']:1,
												"delete"=>isset($_REQUEST['staff-members_delete'])?$_REQUEST['staff-members_delete']:0
									  ],
									  "gatekeeper"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Gatekeeper.png' ),
												'menu_title'=>'Gatekeeper',
												  "page_link"=>'gatekeeper',
												 "own_data" => isset($_REQUEST['gatekeeper_own_data'])?$_REQUEST['gatekeeper_own_data']:0,
												 "add" => isset($_REQUEST['gatekeeper_add'])?$_REQUEST['gatekeeper_add']:0,
												"edit"=>isset($_REQUEST['gatekeeper_edit'])?$_REQUEST['gatekeeper_edit']:0,
												"view"=>isset($_REQUEST['gatekeeper_view'])?$_REQUEST['gatekeeper_view']:1,
												"delete"=>isset($_REQUEST['gatekeeper_delete'])?$_REQUEST['gatekeeper_delete']:0
									  ],

									  "report"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Report.png'),
							           "menu_title"=>'Report',
									   "page_link"=>'report',
									    "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
										 "add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
										"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
										"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:1,
										"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
							  			],
									  
										"visitor-manage"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Visitor-Manage.png' ),			
												'menu_title'=>'Visitor Management',
												 "page_link"=>'visitor-manage',
												 "own_data" => isset($_REQUEST['visitor-manage_own_data'])?$_REQUEST['visitor-manage_own_data']:1,
												 "add" => isset($_REQUEST['visitor-manage_add'])?$_REQUEST['visitor-manage_add']:1,
												"edit"=>isset($_REQUEST['visitor-manage_edit'])?$_REQUEST['visitor-manage_edit']:0,
												"view"=>isset($_REQUEST['visitor-manage_view'])?$_REQUEST['visitor-manage_view']:1,
												"delete"=>isset($_REQUEST['visitor-manage_delete'])?$_REQUEST['visitor-manage_delete']:0
									  ],
									  
										"notice-event"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Notice-And-Event.png' ),			'menu_title'=>'Notice And Event',
												 "page_link"=>'notice-event',
												 "own_data" => isset($_REQUEST['notice-event_own_data'])?$_REQUEST['notice-event_own_data']:0,
												 "add" => isset($_REQUEST['notice-event_add'])?$_REQUEST['notice-event_add']:0,
												"edit"=>isset($_REQUEST['notice-event_edit'])?$_REQUEST['notice-event_edit']:0,
												"view"=>isset($_REQUEST['notice-event_view'])?$_REQUEST['notice-event_view']:1,
												"delete"=>isset($_REQUEST['notice-event_delete'])?$_REQUEST['notice-event_delete']:0
									  ],
										"complaint"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Complaint.png' ),
												'menu_title'=>'Complain',
												 "page_link"=>'complaint',
												 "own_data" => isset($_REQUEST['complaint_own_data'])?$_REQUEST['complaint_own_data']:1,
												 "add" => isset($_REQUEST['complaint_add'])?$_REQUEST['complaint_add']:1,
												"edit"=>isset($_REQUEST['complaint_edit'])?$_REQUEST['complaint_edit']:1,
												"view"=>isset($_REQUEST['complaint_view'])?$_REQUEST['complaint_view']:1,
												"delete"=>isset($_REQUEST['complaint_delete'])?$_REQUEST['complaint_delete']:0
									  ],
										"parking-manager"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Parking-Manager.png' ),			'menu_title'=>'Parking Manager',
												  "page_link"=>'parking-manager',
												 "own_data" => isset($_REQUEST['parking-manager_own_data'])?$_REQUEST['parking-manager_own_data']:1,
												 "add" => isset($_REQUEST['parking-manager_add'])?$_REQUEST['parking-manager_add']:0,
												"edit"=>isset($_REQUEST['parking-manager_edit'])?$_REQUEST['parking-manager_edit']:0,
												"view"=>isset($_REQUEST['parking-manager_view'])?$_REQUEST['parking-manager_view']:1,
												"delete"=>isset($_REQUEST['parking-manager_delete'])?$_REQUEST['parking-manager_delete']:0
									  ],
									  
									  "services"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/services.png' ),
												'menu_title'=>'Services',
												 "page_link"=>'services',
												 "own_data" => isset($_REQUEST['services_own_data'])?$_REQUEST['services_own_data']:0,
												 "add" => isset($_REQUEST['services_add'])?$_REQUEST['services_add']:0,
												"edit"=>isset($_REQUEST['services_edit'])?$_REQUEST['services_edit']:0,
												"view"=>isset($_REQUEST['services_view'])?$_REQUEST['services_view']:1,
												"delete"=>isset($_REQUEST['services_delete'])?$_REQUEST['services_delete']:0
									  ],
									  
									  "facility"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Facility.png' ),
												'menu_title'=>'Facility',
												 "page_link"=>'facility',
												 "own_data" => isset($_REQUEST['facility_own_data'])?$_REQUEST['facility_own_data']:1,
												 "add" => isset($_REQUEST['facility_add'])?$_REQUEST['facility_add']:1,
												"edit"=>isset($_REQUEST['facility_edit'])?$_REQUEST['facility_edit']:0,
												"view"=>isset($_REQUEST['facility_view'])?$_REQUEST['facility_view']:1,
												"delete"=>isset($_REQUEST['facility_delete'])?$_REQUEST['facility_delete']:0
									  ],
									  "accounts"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Accounts.png' ),
												'menu_title'=>'Accounts',
											   "page_link"=>'accounts',
												 "own_data" => isset($_REQUEST['accounts_own_data'])?$_REQUEST['accounts_own_data']:1,
												 "add" => isset($_REQUEST['accounts_add'])?$_REQUEST['accounts_add']:0,
												"edit"=>isset($_REQUEST['accounts_edit'])?$_REQUEST['accounts_edit']:0,
												"view"=>isset($_REQUEST['accounts_view'])?$_REQUEST['accounts_view']:1,
												"delete"=>isset($_REQUEST['accounts_delete'])?$_REQUEST['accounts_delete']:0
									  ],
									  "documents"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/document.png' ),
												'menu_title'=>'Documents',
												  "page_link"=>'documents',
												 "own_data" => isset($_REQUEST['documents_own_data'])?$_REQUEST['documents_own_data']:1,
												 "add" => isset($_REQUEST['documents_add'])?$_REQUEST['documents_add']:1,
												"edit"=>isset($_REQUEST['documents_edit'])?$_REQUEST['documents_edit']:0,
												"view"=>isset($_REQUEST['documents_view'])?$_REQUEST['documents_view']:1,
												"delete"=>isset($_REQUEST['documents_delete'])?$_REQUEST['documents_delete']:0
									  ],
									  "assets-inventory-tracker"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Assets--Inventory-Tracker.png' ),
												'menu_title'=>'Assets / Inventory Tracker',
												 "page_link"=>'assets-inventory-tracker',
												 "own_data" => isset($_REQUEST['assets-inventory-tracker_own_data'])?$_REQUEST['assets-inventory-tracker_own_data']:0,
												 "add" => isset($_REQUEST['assets-inventory-tracker_add'])?$_REQUEST['assets-inventory-tracker_add']:0,
												"edit"=>isset($_REQUEST['assets-inventory-tracker_edit'])?$_REQUEST['assets-inventory-tracker_edit']:0,
												"view"=>isset($_REQUEST['assets-inventory-tracker_view'])?$_REQUEST['assets-inventory-tracker_view']:1,
												"delete"=>isset($_REQUEST['assets-inventory-tracker_delete'])?$_REQUEST['assets-inventory-tracker_delete']:0
									  ],
									  
									  "message"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/message.png'),
												"menu_title"=>'Message',
												"page_link"=>'message',
												 "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
												 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
												"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
												"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
												"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
									  ],
									  
									   "profile"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/account.png' ),
											'menu_title'=>'Profile',
											   "page_link"=>'profile',
												 "own_data" => isset($_REQUEST['profile_own_data'])?$_REQUEST['profile_own_data']:1,
												 "add" => isset($_REQUEST['profile_add'])?$_REQUEST['profile_add']:0,
												"edit"=>isset($_REQUEST['profile_edit'])?$_REQUEST['profile_edit']:0,
												"view"=>isset($_REQUEST['profile_view'])?$_REQUEST['profile_view']:1,
												"delete"=>isset($_REQUEST['profile_delete'])?$_REQUEST['profile_delete']:0
									  ],
									  
									   "faq"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/faq.png' ),
												'menu_title'=>'FAQ',
												"page_link"=>'faq',
												 "own_data" => isset($_REQUEST['faq_own_data'])?$_REQUEST['faq_own_data']:0,
												 "add" => isset($_REQUEST['faq_add'])?$_REQUEST['faq_add']:0,
												"edit"=>isset($_REQUEST['faq_edit'])?$_REQUEST['faq_edit']:0,
												"view"=>isset($_REQUEST['faq_view'])?$_REQUEST['faq_view']:1,
												"delete"=>isset($_REQUEST['faq_delete'])?$_REQUEST['faq_delete']:0
									  ],
									  
									   "society_rules"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Society-Rules.png' ),
												'menu_title'=>'Rules',
												 "page_link"=>'society_rules',
												 "own_data" => isset($_REQUEST['society_rules_own_data'])?$_REQUEST['society_rules_own_data']:0,
												 "add" => isset($_REQUEST['society_rules_add'])?$_REQUEST['society_rules_add']:0,
												"edit"=>isset($_REQUEST['society_rules_edit'])?$_REQUEST['society_rules_edit']:0,
												"view"=>isset($_REQUEST['society_rules_view'])?$_REQUEST['society_rules_view']:1,
												"delete"=>isset($_REQUEST['society_rules_delete'])?$_REQUEST['society_rules_delete']:0
									  ],
									  "gallery"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/gallery.png' ),
												'menu_title'=>'Gallery',
												 "page_link"=>'gallery',
												 "own_data" => isset($_REQUEST['gallery_own_data'])?$_REQUEST['gallery_own_data']:0,
												 "add" => isset($_REQUEST['gallery_add'])?$_REQUEST['gallery_add']:0,
												"edit"=>isset($_REQUEST['gallery_edit'])?$_REQUEST['gallery_edit']:0,
												"view"=>isset($_REQUEST['gallery_view'])?$_REQUEST['gallery_view']:1,
												"delete"=>isset($_REQUEST['gallery_delete'])?$_REQUEST['gallery_delete']:0
									  ]
									];
			$role_access_right_staff_member= array();
			$role_access_right_staff_member['staff_member'] = [
									"resident_unit"=>["menu_icone"=>plugins_url( 'apartment-management/assets/images/icon/resident-unit.png' ),
												'menu_title'=>'Resident Unit',
											   "page_link"=>'resident_unit',
											   "own_data" =>isset($_REQUEST['resident_unit_own_data'])?$_REQUEST['resident_unit_own_data']:1,
											   "add" =>isset($_REQUEST['resident_unit_add'])?$_REQUEST['resident_unit_add']:1,
												"edit"=>isset($_REQUEST['resident_unit_edit'])?$_REQUEST['resident_unit_edit']:1,
												"view"=>isset($_REQUEST['resident_unit_view'])?$_REQUEST['resident_unit_view']:1,
												"delete"=>isset($_REQUEST['resident_unit_delete'])?$_REQUEST['resident_unit_delete']:1
												],
														
								   "member"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/member.png' ),
												'menu_title'=>'All User',
											  "page_link"=>'member',
											 "own_data" => isset($_REQUEST['member_own_data'])?$_REQUEST['member_own_data']:0,
											 "add" => isset($_REQUEST['member_add'])?$_REQUEST['member_add']:1,
											 "edit"=>isset($_REQUEST['member_edit'])?$_REQUEST['member_edit']:1,
											 "view"=>isset($_REQUEST['member_view'])?$_REQUEST['member_view']:1,
											 "delete"=>isset($_REQUEST['member_delete'])?$_REQUEST['member_delete']:1
								  ],
											  
									"committee-member"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Committee-Member.png' ),			
											'menu_title'=>'Committee Member',
											"page_link"=>'committee-member',
											 "own_data" => isset($_REQUEST['committee-member_own_data'])?$_REQUEST['committee-member_own_data']:0,
											 "add" => isset($_REQUEST['committee-member_add'])?$_REQUEST['committee-member_add']:0,
											"edit"=>isset($_REQUEST['committee-member_edit'])?$_REQUEST['committee-member_edit']:0,
											"view"=>isset($_REQUEST['committee-member_view'])?$_REQUEST['committee-member_view']:1,
											"delete"=>isset($_REQUEST['committee-member_delete'])?$_REQUEST['committee-member_delete']:0
								  ],
											  
									  "accountant"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Accountant.png' ),
												'menu_title'=>'Accountant',
												"page_link"=>'accountant',
												"own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:0,
												 "add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:1,
												 "edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:1,
												"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:1,
												"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:1
									  ],
									  
									  "staff-members"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Staff-Management.png' ),			
												'menu_title'=>'Staff Management',
												 "page_link"=>'staff-members',
												 "own_data" => isset($_REQUEST['staff-members_own_data'])?$_REQUEST['staff-members_own_data']:1,
												 "add" => isset($_REQUEST['staff-members_add'])?$_REQUEST['staff-members_add']:0,
												"edit"=>isset($_REQUEST['staff-members_edit'])?$_REQUEST['staff-members_edit']:0,
												"view"=>isset($_REQUEST['staff-members_view'])?$_REQUEST['staff-members_view']:1,
												"delete"=>isset($_REQUEST['staff-members_delete'])?$_REQUEST['staff-members_delete']:0
									  ],
									  "gatekeeper"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Gatekeeper.png' ),
												'menu_title'=>'Gatekeeper',
												  "page_link"=>'gatekeeper',
												 "own_data" => isset($_REQUEST['gatekeeper_own_data'])?$_REQUEST['gatekeeper_own_data']:0,
												 "add" => isset($_REQUEST['gatekeeper_add'])?$_REQUEST['gatekeeper_add']:1,
												"edit"=>isset($_REQUEST['gatekeeper_edit'])?$_REQUEST['gatekeeper_edit']:1,
												"view"=>isset($_REQUEST['gatekeeper_view'])?$_REQUEST['gatekeeper_view']:1,
												"delete"=>isset($_REQUEST['gatekeeper_delete'])?$_REQUEST['gatekeeper_delete']:1
									  ],

									  "report"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Report.png'),
							           "menu_title"=>'Report',
									   "page_link"=>'report',
									    "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
										 "add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
										"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
										"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:1,
										"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
							  			],
									  
										"visitor-manage"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Visitor-Manage.png' ),			
												'menu_title'=>'Visitor Management',
												 "page_link"=>'visitor-manage',
												 "own_data" => isset($_REQUEST['visitor-manage_own_data'])?$_REQUEST['visitor-manage_own_data']:0,
												 "add" => isset($_REQUEST['visitor-manage_add'])?$_REQUEST['visitor-manage_add']:1,
												"edit"=>isset($_REQUEST['visitor-manage_edit'])?$_REQUEST['visitor-manage_edit']:1,
												"view"=>isset($_REQUEST['visitor-manage_view'])?$_REQUEST['visitor-manage_view']:1,
												"delete"=>isset($_REQUEST['visitor-manage_delete'])?$_REQUEST['visitor-manage_delete']:1
									  ],
									  
									  
										"notice-event"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Notice-And-Event.png' ),			
										'menu_title'=>'Notice And Event',
												 "page_link"=>'notice-event',
												 "own_data" => isset($_REQUEST['notice-event_own_data'])?$_REQUEST['notice-event_own_data']:0,
												 "add" => isset($_REQUEST['notice-event_add'])?$_REQUEST['notice-event_add']:1,
												"edit"=>isset($_REQUEST['notice-event_edit'])?$_REQUEST['notice-event_edit']:1,
												"view"=>isset($_REQUEST['notice-event_view'])?$_REQUEST['notice-event_view']:1,
												"delete"=>isset($_REQUEST['notice-event_delete'])?$_REQUEST['notice-event_delete']:1
									  ],
										"complaint"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Complaint.png' ),
												'menu_title'=>'Complain',
												 "page_link"=>'complaint',
												 "own_data" => isset($_REQUEST['complaint_own_data'])?$_REQUEST['complaint_own_data']:0,
												 "add" => isset($_REQUEST['complaint_add'])?$_REQUEST['complaint_add']:1,
												"edit"=>isset($_REQUEST['complaint_edit'])?$_REQUEST['complaint_edit']:1,
												"view"=>isset($_REQUEST['complaint_view'])?$_REQUEST['complaint_view']:1,
												"delete"=>isset($_REQUEST['complaint_delete'])?$_REQUEST['complaint_delete']:1
									  ],
										"parking-manager"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Parking-Manager.png' ),			
												'menu_title'=>'Parking Manager',
												  "page_link"=>'parking-manager',
												 "own_data" => isset($_REQUEST['parking-manager_own_data'])?$_REQUEST['parking-manager_own_data']:0,
												 "add" => isset($_REQUEST['parking-manager_add'])?$_REQUEST['parking-manager_add']:1,
												"edit"=>isset($_REQUEST['parking-manager_edit'])?$_REQUEST['parking-manager_edit']:1,
												"view"=>isset($_REQUEST['parking-manager_view'])?$_REQUEST['parking-manager_view']:1,
												"delete"=>isset($_REQUEST['parking-manager_delete'])?$_REQUEST['parking-manager_delete']:1
									  ],
									  
									  "services"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/services.png' ),
												'menu_title'=>'Services',
												 "page_link"=>'services',
												 "own_data" => isset($_REQUEST['services_own_data'])?$_REQUEST['services_own_data']:0,
												 "add" => isset($_REQUEST['services_add'])?$_REQUEST['services_add']:1,
												"edit"=>isset($_REQUEST['services_edit'])?$_REQUEST['services_edit']:1,
												"view"=>isset($_REQUEST['services_view'])?$_REQUEST['services_view']:1,
												"delete"=>isset($_REQUEST['services_delete'])?$_REQUEST['services_delete']:1
									  ],
									  
									  "facility"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Facility.png' ),
												'menu_title'=>'Facility',
												 "page_link"=>'facility',
												 "own_data" => isset($_REQUEST['facility_own_data'])?$_REQUEST['facility_own_data']:0,
												 "add" => isset($_REQUEST['facility_add'])?$_REQUEST['facility_add']:1,
												"edit"=>isset($_REQUEST['facility_edit'])?$_REQUEST['facility_edit']:1,
												"view"=>isset($_REQUEST['facility_view'])?$_REQUEST['facility_view']:1,
												"delete"=>isset($_REQUEST['facility_delete'])?$_REQUEST['facility_delete']:1
									  ],
									  "accounts"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Accounts.png' ),
												'menu_title'=>'Accounts',
											   "page_link"=>'accounts',
												 "own_data" => isset($_REQUEST['accounts_own_data'])?$_REQUEST['accounts_own_data']:0,
												 "add" => isset($_REQUEST['accounts_add'])?$_REQUEST['accounts_add']:1,
												"edit"=>isset($_REQUEST['accounts_edit'])?$_REQUEST['accounts_edit']:1,
												"view"=>isset($_REQUEST['accounts_view'])?$_REQUEST['accounts_view']:1,
												"delete"=>isset($_REQUEST['accounts_delete'])?$_REQUEST['accounts_delete']:1
									  ],
									  "documents"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/document.png' ),
												'menu_title'=>'Documents',
												  "page_link"=>'documents',
												 "own_data" => isset($_REQUEST['documents_own_data'])?$_REQUEST['documents_own_data']:0,
												 "add" => isset($_REQUEST['documents_add'])?$_REQUEST['documents_add']:1,
												"edit"=>isset($_REQUEST['documents_edit'])?$_REQUEST['documents_edit']:1,
												"view"=>isset($_REQUEST['documents_view'])?$_REQUEST['documents_view']:1,
												"delete"=>isset($_REQUEST['documents_delete'])?$_REQUEST['documents_delete']:1
									  ],
									  "assets-inventory-tracker"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Assets--Inventory-Tracker.png' ),
												'menu_title'=>'Assets / Inventory Tracker',
												 "page_link"=>'assets-inventory-tracker',
												 "own_data" => isset($_REQUEST['assets-inventory-tracker_own_data'])?$_REQUEST['assets-inventory-tracker_own_data']:0,
												 "add" => isset($_REQUEST['assets-inventory-tracker_add'])?$_REQUEST['assets-inventory-tracker_add']:1,
												"edit"=>isset($_REQUEST['assets-inventory-tracker_edit'])?$_REQUEST['assets-inventory-tracker_edit']:1,
												"view"=>isset($_REQUEST['assets-inventory-tracker_view'])?$_REQUEST['assets-inventory-tracker_view']:1,
												"delete"=>isset($_REQUEST['assets-inventory-tracker_delete'])?$_REQUEST['assets-inventory-tracker_delete']:1
									  ],
									  
									  "message"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/message.png'),
												"menu_title"=>'Message',
												"page_link"=>'message',
												 "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
												 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
												"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
												"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
												"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
									  ],
									  
									   "profile"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/account.png' ),
											'menu_title'=>'Profile',
											   "page_link"=>'profile',
												 "own_data" => isset($_REQUEST['profile_own_data'])?$_REQUEST['profile_own_data']:1,
												 "add" => isset($_REQUEST['profile_add'])?$_REQUEST['profile_add']:0,
												"edit"=>isset($_REQUEST['profile_edit'])?$_REQUEST['profile_edit']:0,
												"view"=>isset($_REQUEST['profile_view'])?$_REQUEST['profile_view']:1,
												"delete"=>isset($_REQUEST['profile_delete'])?$_REQUEST['profile_delete']:0
									  ],
									  
									   "faq"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/faq.png' ),
												'menu_title'=>'FAQ',
												"page_link"=>'faq',
												 "own_data" => isset($_REQUEST['faq_own_data'])?$_REQUEST['faq_own_data']:0,
												 "add" => isset($_REQUEST['faq_add'])?$_REQUEST['faq_add']:0,
												"edit"=>isset($_REQUEST['faq_edit'])?$_REQUEST['faq_edit']:0,
												"view"=>isset($_REQUEST['faq_view'])?$_REQUEST['faq_view']:1,
												"delete"=>isset($_REQUEST['faq_delete'])?$_REQUEST['faq_delete']:0
									  ],
									  
									   "society_rules"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Society-Rules.png' ),
												'menu_title'=>'Rules',
												 "page_link"=>'society_rules',
												 "own_data" => isset($_REQUEST['society_rules_own_data'])?$_REQUEST['society_rules_own_data']:0,
												 "add" => isset($_REQUEST['society_rules_add'])?$_REQUEST['society_rules_add']:0,
												"edit"=>isset($_REQUEST['society_rules_edit'])?$_REQUEST['society_rules_edit']:0,
												"view"=>isset($_REQUEST['society_rules_view'])?$_REQUEST['society_rules_view']:1,
												"delete"=>isset($_REQUEST['society_rules_delete'])?$_REQUEST['society_rules_delete']:0
									  ],
									  "gallery"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/gallery.png' ),
												'menu_title'=>'Gallery',
												 "page_link"=>'gallery',
												 "own_data" => isset($_REQUEST['gallery_own_data'])?$_REQUEST['gallery_own_data']:0,
												 "add" => isset($_REQUEST['gallery_add'])?$_REQUEST['gallery_add']:0,
												"edit"=>isset($_REQUEST['gallery_edit'])?$_REQUEST['gallery_edit']:0,
												"view"=>isset($_REQUEST['gallery_view'])?$_REQUEST['gallery_view']:1,
												"delete"=>isset($_REQUEST['gallery_delete'])?$_REQUEST['gallery_delete']:0
									  ]
									];
			$role_access_right_accountant= array();
			$role_access_right_accountant['accountant'] = [
									"resident_unit"=>["menu_icone"=>plugins_url( 'apartment-management/assets/images/icon/resident-unit.png' ),
												'menu_title'=>'Resident Unit',
											   "page_link"=>'resident_unit',
											   "own_data" =>isset($_REQUEST['resident_unit_own_data'])?$_REQUEST['resident_unit_own_data']:0,
											   "add" =>isset($_REQUEST['resident_unit_add'])?$_REQUEST['resident_unit_add']:0,
												"edit"=>isset($_REQUEST['resident_unit_edit'])?$_REQUEST['resident_unit_edit']:0,
												"view"=>isset($_REQUEST['resident_unit_view'])?$_REQUEST['resident_unit_view']:1,
												"delete"=>isset($_REQUEST['resident_unit_delete'])?$_REQUEST['resident_unit_delete']:0
												],
														
								   "member"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/member.png' ),
												'menu_title'=>'All User',
											  "page_link"=>'member',
											 "own_data" => isset($_REQUEST['member_own_data'])?$_REQUEST['member_own_data']:0,
											 "add" => isset($_REQUEST['member_add'])?$_REQUEST['member_add']:0,
											 "edit"=>isset($_REQUEST['member_edit'])?$_REQUEST['member_edit']:0,
											 "view"=>isset($_REQUEST['member_view'])?$_REQUEST['member_view']:1,
											 "delete"=>isset($_REQUEST['member_delete'])?$_REQUEST['member_delete']:0
								  ],
											  
									"committee-member"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Committee-Member.png' ),			
									'menu_title'=>'Committee Member',
											"page_link"=>'committee-member',
											 "own_data" => isset($_REQUEST['committee-member_own_data'])?$_REQUEST['committee-member_own_data']:0,
											 "add" => isset($_REQUEST['committee-member_add'])?$_REQUEST['committee-member_add']:0,
											"edit"=>isset($_REQUEST['committee-member_edit'])?$_REQUEST['committee-member_edit']:0,
											"view"=>isset($_REQUEST['committee-member_view'])?$_REQUEST['committee-member_view']:1,
											"delete"=>isset($_REQUEST['committee-member_delete'])?$_REQUEST['committee-member_delete']:0
								  ],
											  
									  "accountant"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Accountant.png' ),
												'menu_title'=>'Accountant',
												"page_link"=>'accountant',
												"own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:0,
												 "add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
												 "edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
												"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:1,
												"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
									  ],
									  
									  "staff-members"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Staff-Management.png' ),			
									  'menu_title'=>'Staff Management',
												 "page_link"=>'staff-members',
												 "own_data" => isset($_REQUEST['staff-members_own_data'])?$_REQUEST['staff-members_own_data']:0,
												 "add" => isset($_REQUEST['staff-members_add'])?$_REQUEST['staff-members_add']:0,
												"edit"=>isset($_REQUEST['staff-members_edit'])?$_REQUEST['staff-members_edit']:0,
												"view"=>isset($_REQUEST['staff-members_view'])?$_REQUEST['staff-members_view']:1,
												"delete"=>isset($_REQUEST['staff-members_delete'])?$_REQUEST['staff-members_delete']:0
									  ],
									  "gatekeeper"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Gatekeeper.png' ),
												'menu_title'=>'Gatekeeper',
												  "page_link"=>'gatekeeper',
												 "own_data" => isset($_REQUEST['gatekeeper_own_data'])?$_REQUEST['gatekeeper_own_data']:0,
												 "add" => isset($_REQUEST['gatekeeper_add'])?$_REQUEST['gatekeeper_add']:0,
												"edit"=>isset($_REQUEST['gatekeeper_edit'])?$_REQUEST['gatekeeper_edit']:0,
												"view"=>isset($_REQUEST['gatekeeper_view'])?$_REQUEST['gatekeeper_view']:1,
												"delete"=>isset($_REQUEST['gatekeeper_delete'])?$_REQUEST['gatekeeper_delete']:0
									  ],

									  "report"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Report.png'),
							           "menu_title"=>'Report',
									   "page_link"=>'report',
									    "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
										 "add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
										"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
										"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:1,
										"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
							  			],
									  
										"visitor-manage"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Visitor-Manage.png' ),			
										'menu_title'=>'Visitor Management',
												 "page_link"=>'visitor-manage',
												 "own_data" => isset($_REQUEST['visitor-manage_own_data'])?$_REQUEST['visitor-manage_own_data']:1,
												 "add" => isset($_REQUEST['visitor-manage_add'])?$_REQUEST['visitor-manage_add']:0,
												"edit"=>isset($_REQUEST['visitor-manage_edit'])?$_REQUEST['visitor-manage_edit']:0,
												"view"=>isset($_REQUEST['visitor-manage_view'])?$_REQUEST['visitor-manage_view']:1,
												"delete"=>isset($_REQUEST['visitor-manage_delete'])?$_REQUEST['visitor-manage_delete']:0
									  ],
									  
									  
										"notice-event"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Notice-And-Event.png' ),			
											'menu_title'=>'Notice And Event',
												 "page_link"=>'notice-event',
												 "own_data" => isset($_REQUEST['notice-event_own_data'])?$_REQUEST['notice-event_own_data']:0,
												 "add" => isset($_REQUEST['notice-event_add'])?$_REQUEST['notice-event_add']:0,
												"edit"=>isset($_REQUEST['notice-event_edit'])?$_REQUEST['notice-event_edit']:0,
												"view"=>isset($_REQUEST['notice-event_view'])?$_REQUEST['notice-event_view']:1,
												"delete"=>isset($_REQUEST['notice-event_delete'])?$_REQUEST['notice-event_delete']:0
									  ],
										"complaint"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Complaint.png' ),
												'menu_title'=>'Complain',
												 "page_link"=>'complaint',
												 "own_data" => isset($_REQUEST['complaint_own_data'])?$_REQUEST['complaint_own_data']:0,
												 "add" => isset($_REQUEST['complaint_add'])?$_REQUEST['complaint_add']:0,
												"edit"=>isset($_REQUEST['complaint_edit'])?$_REQUEST['complaint_edit']:0,
												"view"=>isset($_REQUEST['complaint_view'])?$_REQUEST['complaint_view']:1,
												"delete"=>isset($_REQUEST['complaint_delete'])?$_REQUEST['complaint_delete']:0
									  ],
										"parking-manager"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Parking-Manager.png' ),			
											'menu_title'=>'Parking Manager',
												  "page_link"=>'parking-manager',
												 "own_data" => isset($_REQUEST['parking-manager_own_data'])?$_REQUEST['parking-manager_own_data']:1,
												 "add" => isset($_REQUEST['parking-manager_add'])?$_REQUEST['parking-manager_add']:0,
												"edit"=>isset($_REQUEST['parking-manager_edit'])?$_REQUEST['parking-manager_edit']:0,
												"view"=>isset($_REQUEST['parking-manager_view'])?$_REQUEST['parking-manager_view']:1,
												"delete"=>isset($_REQUEST['parking-manager_delete'])?$_REQUEST['parking-manager_delete']:0
									  ],
									  
									  "services"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/services.png' ),
												'menu_title'=>'Services',
												 "page_link"=>'services',
												 "own_data" => isset($_REQUEST['services_own_data'])?$_REQUEST['services_own_data']:0,
												 "add" => isset($_REQUEST['services_add'])?$_REQUEST['services_add']:0,
												"edit"=>isset($_REQUEST['services_edit'])?$_REQUEST['services_edit']:0,
												"view"=>isset($_REQUEST['services_view'])?$_REQUEST['services_view']:1,
												"delete"=>isset($_REQUEST['services_delete'])?$_REQUEST['services_delete']:0
									  ],
									  
									  "facility"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Facility.png' ),
												'menu_title'=>'Facility',
												 "page_link"=>'facility',
												 "own_data" => isset($_REQUEST['facility_own_data'])?$_REQUEST['facility_own_data']:1,
												 "add" => isset($_REQUEST['facility_add'])?$_REQUEST['facility_add']:0,
												"edit"=>isset($_REQUEST['facility_edit'])?$_REQUEST['facility_edit']:0,
												"view"=>isset($_REQUEST['facility_view'])?$_REQUEST['facility_view']:1,
												"delete"=>isset($_REQUEST['facility_delete'])?$_REQUEST['facility_delete']:0
									  ],
									  "accounts"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Accounts.png' ),
												'menu_title'=>'Accounts',
											   "page_link"=>'accounts',
												 "own_data" => isset($_REQUEST['accounts_own_data'])?$_REQUEST['accounts_own_data']:0,
												 "add" => isset($_REQUEST['accounts_add'])?$_REQUEST['accounts_add']:1,
												"edit"=>isset($_REQUEST['accounts_edit'])?$_REQUEST['accounts_edit']:1,
												"view"=>isset($_REQUEST['accounts_view'])?$_REQUEST['accounts_view']:1,
												"delete"=>isset($_REQUEST['accounts_delete'])?$_REQUEST['accounts_delete']:1
									  ],
									  "documents"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/document.png' ),
												'menu_title'=>'Documents',
												  "page_link"=>'documents',
												 "own_data" => isset($_REQUEST['documents_own_data'])?$_REQUEST['documents_own_data']:1,
												 "add" => isset($_REQUEST['documents_add'])?$_REQUEST['documents_add']:0,
												"edit"=>isset($_REQUEST['documents_edit'])?$_REQUEST['documents_edit']:0,
												"view"=>isset($_REQUEST['documents_view'])?$_REQUEST['documents_view']:1,
												"delete"=>isset($_REQUEST['documents_delete'])?$_REQUEST['documents_delete']:0
									  ],
									  "assets-inventory-tracker"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Assets--Inventory-Tracker.png' ),
												'menu_title'=>'Assets / Inventory Tracker',
												 "page_link"=>'assets-inventory-tracker',
												 "own_data" => isset($_REQUEST['assets-inventory-tracker_own_data'])?$_REQUEST['assets-inventory-tracker_own_data']:0,
												 "add" => isset($_REQUEST['assets-inventory-tracker_add'])?$_REQUEST['assets-inventory-tracker_add']:0,
												"edit"=>isset($_REQUEST['assets-inventory-tracker_edit'])?$_REQUEST['assets-inventory-tracker_edit']:0,
												"view"=>isset($_REQUEST['assets-inventory-tracker_view'])?$_REQUEST['assets-inventory-tracker_view']:1,
												"delete"=>isset($_REQUEST['assets-inventory-tracker_delete'])?$_REQUEST['assets-inventory-tracker_delete']:0
									  ],
									  
									  "message"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/message.png'),
												"menu_title"=>'Message',
												"page_link"=>'message',
												 "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
												 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
												"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
												"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
												"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
									  ],
									  
									   "profile"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/account.png' ),
											'menu_title'=>'Profile',
											   "page_link"=>'profile',
												 "own_data" => isset($_REQUEST['profile_own_data'])?$_REQUEST['profile_own_data']:1,
												 "add" => isset($_REQUEST['profile_add'])?$_REQUEST['profile_add']:0,
												"edit"=>isset($_REQUEST['profile_edit'])?$_REQUEST['profile_edit']:0,
												"view"=>isset($_REQUEST['profile_view'])?$_REQUEST['profile_view']:1,
												"delete"=>isset($_REQUEST['profile_delete'])?$_REQUEST['profile_delete']:0
									  ],
									  
									   "faq"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/faq.png' ),
												'menu_title'=>'FAQ',
												"page_link"=>'faq',
												 "own_data" => isset($_REQUEST['faq_own_data'])?$_REQUEST['faq_own_data']:0,
												 "add" => isset($_REQUEST['faq_add'])?$_REQUEST['faq_add']:0,
												"edit"=>isset($_REQUEST['faq_edit'])?$_REQUEST['faq_edit']:0,
												"view"=>isset($_REQUEST['faq_view'])?$_REQUEST['faq_view']:1,
												"delete"=>isset($_REQUEST['faq_delete'])?$_REQUEST['faq_delete']:0
									  ],
									  
									   "society_rules"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Society-Rules.png' ),
												'menu_title'=>'Rules',
												 "page_link"=>'society_rules',
												 "own_data" => isset($_REQUEST['society_rules_own_data'])?$_REQUEST['society_rules_own_data']:0,
												 "add" => isset($_REQUEST['society_rules_add'])?$_REQUEST['society_rules_add']:0,
												"edit"=>isset($_REQUEST['society_rules_edit'])?$_REQUEST['society_rules_edit']:0,
												"view"=>isset($_REQUEST['society_rules_view'])?$_REQUEST['society_rules_view']:1,
												"delete"=>isset($_REQUEST['society_rules_delete'])?$_REQUEST['society_rules_delete']:0
									  ],
									  "gallery"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/gallery.png' ),
												'menu_title'=>'Gallery',
												 "page_link"=>'gallery',
												 "own_data" => isset($_REQUEST['gallery_own_data'])?$_REQUEST['gallery_own_data']:0,
												 "add" => isset($_REQUEST['gallery_add'])?$_REQUEST['gallery_add']:0,
												"edit"=>isset($_REQUEST['gallery_edit'])?$_REQUEST['gallery_edit']:0,
												"view"=>isset($_REQUEST['gallery_view'])?$_REQUEST['gallery_view']:1,
												"delete"=>isset($_REQUEST['gallery_delete'])?$_REQUEST['gallery_delete']:0
									  ]
									];
		$role_access_right_gatekeeper= array();
		$role_access_right_gatekeeper['gatekeeper'] = [
									"resident_unit"=>["menu_icone"=>plugins_url( 'apartment-management/assets/images/icon/resident-unit.png' ),
												'menu_title'=>'Resident Unit',
											   "page_link"=>'resident_unit',
											   "own_data" =>isset($_REQUEST['resident_unit_own_data'])?$_REQUEST['resident_unit_own_data']:0,
											   "add" =>isset($_REQUEST['resident_unit_add'])?$_REQUEST['resident_unit_add']:0,
												"edit"=>isset($_REQUEST['resident_unit_edit'])?$_REQUEST['resident_unit_edit']:0,
												"view"=>isset($_REQUEST['resident_unit_view'])?$_REQUEST['resident_unit_view']:1,
												"delete"=>isset($_REQUEST['resident_unit_delete'])?$_REQUEST['resident_unit_delete']:0
												],
														
								   "member"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/member.png' ),
												'menu_title'=>'All User',
											  "page_link"=>'member',
											 "own_data" => isset($_REQUEST['member_own_data'])?$_REQUEST['member_own_data']:0,
											 "add" => isset($_REQUEST['member_add'])?$_REQUEST['member_add']:0,
											 "edit"=>isset($_REQUEST['member_edit'])?$_REQUEST['member_edit']:0,
											 "view"=>isset($_REQUEST['member_view'])?$_REQUEST['member_view']:1,
											 "delete"=>isset($_REQUEST['member_delete'])?$_REQUEST['member_delete']:0
								  ],
											  
									"committee-member"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Committee-Member.png' ),			
									'menu_title'=>'Committee Member',
											"page_link"=>'committee-member',
											 "own_data" => isset($_REQUEST['committee-member_own_data'])?$_REQUEST['committee-member_own_data']:0,
											 "add" => isset($_REQUEST['committee-member_add'])?$_REQUEST['committee-member_add']:0,
											"edit"=>isset($_REQUEST['committee-member_edit'])?$_REQUEST['committee-member_edit']:0,
											"view"=>isset($_REQUEST['committee-member_view'])?$_REQUEST['committee-member_view']:1,
											"delete"=>isset($_REQUEST['committee-member_delete'])?$_REQUEST['committee-member_delete']:0
								  ],
											  
									  "accountant"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Accountant.png' ),
												'menu_title'=>'Accountant',
												"page_link"=>'accountant',
												"own_data" => isset($_REQUEST['accountant_own_data'])?$_REQUEST['accountant_own_data']:0,
												 "add" => isset($_REQUEST['accountant_add'])?$_REQUEST['accountant_add']:0,
												 "edit"=>isset($_REQUEST['accountant_edit'])?$_REQUEST['accountant_edit']:0,
												"view"=>isset($_REQUEST['accountant_view'])?$_REQUEST['accountant_view']:1,
												"delete"=>isset($_REQUEST['accountant_delete'])?$_REQUEST['accountant_delete']:0
									  ],
									  
									  "staff-members"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Staff-Management.png' ),			'menu_title'=>'Staff Management',
												 "page_link"=>'staff-members',
												 "own_data" => isset($_REQUEST['staff-members_own_data'])?$_REQUEST['staff-members_own_data']:0,
												 "add" => isset($_REQUEST['staff-members_add'])?$_REQUEST['staff-members_add']:0,
												"edit"=>isset($_REQUEST['staff-members_edit'])?$_REQUEST['staff-members_edit']:0,
												"view"=>isset($_REQUEST['staff-members_view'])?$_REQUEST['staff-members_view']:1,
												"delete"=>isset($_REQUEST['staff-members_delete'])?$_REQUEST['staff-members_delete']:0
									  ],
									  "gatekeeper"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Gatekeeper.png' ),
												'menu_title'=>'Gatekeeper',
												  "page_link"=>'gatekeeper',
												 "own_data" => isset($_REQUEST['gatekeeper_own_data'])?$_REQUEST['gatekeeper_own_data']:0,
												 "add" => isset($_REQUEST['gatekeeper_add'])?$_REQUEST['gatekeeper_add']:0,
												"edit"=>isset($_REQUEST['gatekeeper_edit'])?$_REQUEST['gatekeeper_edit']:0,
												"view"=>isset($_REQUEST['gatekeeper_view'])?$_REQUEST['gatekeeper_view']:1,
												"delete"=>isset($_REQUEST['gatekeeper_delete'])?$_REQUEST['gatekeeper_delete']:0
									  ],

									  "report"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Report.png'),
							           "menu_title"=>'Report',
									   "page_link"=>'report',
									    "own_data" => isset($_REQUEST['report_own_data'])?$_REQUEST['report_own_data']:0,
										 "add" => isset($_REQUEST['report_add'])?$_REQUEST['report_add']:0,
										"edit"=>isset($_REQUEST['report_edit'])?$_REQUEST['report_edit']:0,
										"view"=>isset($_REQUEST['report_view'])?$_REQUEST['report_view']:1,
										"delete"=>isset($_REQUEST['report_delete'])?$_REQUEST['report_delete']:0
							  			],
									  
										"visitor-manage"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Visitor-Manage.png' ),			
										'menu_title'=>'Visitor Management',
												 "page_link"=>'visitor-manage',
												 "own_data" => isset($_REQUEST['visitor-manage_own_data'])?$_REQUEST['visitor-manage_own_data']:0,
												 "add" => isset($_REQUEST['visitor-manage_add'])?$_REQUEST['visitor-manage_add']:1,
												"edit"=>isset($_REQUEST['visitor-manage_edit'])?$_REQUEST['visitor-manage_edit']:0,
												"view"=>isset($_REQUEST['visitor-manage_view'])?$_REQUEST['visitor-manage_view']:1,
												"delete"=>isset($_REQUEST['visitor-manage_delete'])?$_REQUEST['visitor-manage_delete']:0
									  ],
									  
									  
										"notice-event"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Notice-And-Event.png' ),			
										'menu_title'=>'Notice And Event',
												 "page_link"=>'notice-event',
												 "own_data" => isset($_REQUEST['notice-event_own_data'])?$_REQUEST['notice-event_own_data']:0,
												 "add" => isset($_REQUEST['notice-event_add'])?$_REQUEST['notice-event_add']:0,
												"edit"=>isset($_REQUEST['notice-event_edit'])?$_REQUEST['notice-event_edit']:0,
												"view"=>isset($_REQUEST['notice-event_view'])?$_REQUEST['notice-event_view']:1,
												"delete"=>isset($_REQUEST['notice-event_delete'])?$_REQUEST['notice-event_delete']:0
									  ],
										"complaint"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Complaint.png' ),
												'menu_title'=>'Complain',
												 "page_link"=>'complaint',
												 "own_data" => isset($_REQUEST['complaint_own_data'])?$_REQUEST['complaint_own_data']:0,
												 "add" => isset($_REQUEST['complaint_add'])?$_REQUEST['complaint_add']:0,
												"edit"=>isset($_REQUEST['complaint_edit'])?$_REQUEST['complaint_edit']:0,
												"view"=>isset($_REQUEST['complaint_view'])?$_REQUEST['complaint_view']:1,
												"delete"=>isset($_REQUEST['complaint_delete'])?$_REQUEST['complaint_delete']:0
									  ],
										"parking-manager"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Parking-Manager.png' ),			
										'menu_title'=>'Parking Manager',
												  "page_link"=>'parking-manager',
												 "own_data" => isset($_REQUEST['parking-manager_own_data'])?$_REQUEST['parking-manager_own_data']:0,
												 "add" => isset($_REQUEST['parking-manager_add'])?$_REQUEST['parking-manager_add']:1,
												"edit"=>isset($_REQUEST['parking-manager_edit'])?$_REQUEST['parking-manager_edit']:1,
												"view"=>isset($_REQUEST['parking-manager_view'])?$_REQUEST['parking-manager_view']:1,
												"delete"=>isset($_REQUEST['parking-manager_delete'])?$_REQUEST['parking-manager_delete']:1
									  ],
									  
									  "services"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/services.png' ),
												'menu_title'=>'Services',
												 "page_link"=>'services',
												 "own_data" => isset($_REQUEST['services_own_data'])?$_REQUEST['services_own_data']:0,
												 "add" => isset($_REQUEST['services_add'])?$_REQUEST['services_add']:0,
												"edit"=>isset($_REQUEST['services_edit'])?$_REQUEST['services_edit']:0,
												"view"=>isset($_REQUEST['services_view'])?$_REQUEST['services_view']:1,
												"delete"=>isset($_REQUEST['services_delete'])?$_REQUEST['services_delete']:0
									  ],
									  
									  "facility"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Facility.png' ),
												'menu_title'=>'Facility',
												 "page_link"=>'facility',
												 "own_data" => isset($_REQUEST['facility_own_data'])?$_REQUEST['facility_own_data']:1,
												 "add" => isset($_REQUEST['facility_add'])?$_REQUEST['facility_add']:0,
												"edit"=>isset($_REQUEST['facility_edit'])?$_REQUEST['facility_edit']:0,
												"view"=>isset($_REQUEST['facility_view'])?$_REQUEST['facility_view']:1,
												"delete"=>isset($_REQUEST['facility_delete'])?$_REQUEST['facility_delete']:0
									  ],
									  "accounts"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Accounts.png' ),
												'menu_title'=>'Accounts',
											   "page_link"=>'accounts',
												 "own_data" => isset($_REQUEST['accounts_own_data'])?$_REQUEST['accounts_own_data']:0,
												 "add" => isset($_REQUEST['accounts_add'])?$_REQUEST['accounts_add']:0,
												"edit"=>isset($_REQUEST['accounts_edit'])?$_REQUEST['accounts_edit']:0,
												"view"=>isset($_REQUEST['accounts_view'])?$_REQUEST['accounts_view']:1,
												"delete"=>isset($_REQUEST['accounts_delete'])?$_REQUEST['accounts_delete']:0
									  ],
									  "documents"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/document.png' ),
												'menu_title'=>'Documents',
												  "page_link"=>'documents',
												 "own_data" => isset($_REQUEST['documents_own_data'])?$_REQUEST['documents_own_data']:0,
												 "add" => isset($_REQUEST['documents_add'])?$_REQUEST['documents_add']:0,
												"edit"=>isset($_REQUEST['documents_edit'])?$_REQUEST['documents_edit']:0,
												"view"=>isset($_REQUEST['documents_view'])?$_REQUEST['documents_view']:0,
												"delete"=>isset($_REQUEST['documents_delete'])?$_REQUEST['documents_delete']:0
									  ],
									  "assets-inventory-tracker"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Assets--Inventory-Tracker.png' ),
												'menu_title'=>'Assets / Inventory Tracker',
												 "page_link"=>'assets-inventory-tracker',
												 "own_data" => isset($_REQUEST['assets-inventory-tracker_own_data'])?$_REQUEST['assets-inventory-tracker_own_data']:0,
												 "add" => isset($_REQUEST['assets-inventory-tracker_add'])?$_REQUEST['assets-inventory-tracker_add']:0,
												"edit"=>isset($_REQUEST['assets-inventory-tracker_edit'])?$_REQUEST['assets-inventory-tracker_edit']:0,
												"view"=>isset($_REQUEST['assets-inventory-tracker_view'])?$_REQUEST['assets-inventory-tracker_view']:1,
												"delete"=>isset($_REQUEST['assets-inventory-tracker_delete'])?$_REQUEST['assets-inventory-tracker_delete']:0
									  ],
									  
									  "message"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/message.png'),
												"menu_title"=>'Message',
												"page_link"=>'message',
												 "own_data" => isset($_REQUEST['message_own_data'])?$_REQUEST['message_own_data']:1,
												 "add" => isset($_REQUEST['message_add'])?$_REQUEST['message_add']:1,
												"edit"=>isset($_REQUEST['message_edit'])?$_REQUEST['message_edit']:0,
												"view"=>isset($_REQUEST['message_view'])?$_REQUEST['message_view']:1,
												"delete"=>isset($_REQUEST['message_delete'])?$_REQUEST['message_delete']:1
									  ],
									  
									   "profile"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/account.png' ),
											'menu_title'=>'Profile',
											   "page_link"=>'profile',
												 "own_data" => isset($_REQUEST['profile_own_data'])?$_REQUEST['profile_own_data']:1,
												 "add" => isset($_REQUEST['profile_add'])?$_REQUEST['profile_add']:0,
												"edit"=>isset($_REQUEST['profile_edit'])?$_REQUEST['profile_edit']:0,
												"view"=>isset($_REQUEST['profile_view'])?$_REQUEST['profile_view']:1,
												"delete"=>isset($_REQUEST['profile_delete'])?$_REQUEST['profile_delete']:0
									  ],
									  
									   "faq"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/faq.png' ),
												'menu_title'=>'FAQ',
												"page_link"=>'faq',
												 "own_data" => isset($_REQUEST['faq_own_data'])?$_REQUEST['faq_own_data']:0,
												 "add" => isset($_REQUEST['faq_add'])?$_REQUEST['faq_add']:0,
												"edit"=>isset($_REQUEST['faq_edit'])?$_REQUEST['faq_edit']:0,
												"view"=>isset($_REQUEST['faq_view'])?$_REQUEST['faq_view']:1,
												"delete"=>isset($_REQUEST['faq_delete'])?$_REQUEST['faq_delete']:0
									  ],
									  
									   "society_rules"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/Society-Rules.png' ),
												'menu_title'=>'Rules',
												 "page_link"=>'society_rules',
												 "own_data" => isset($_REQUEST['society_rules_own_data'])?$_REQUEST['society_rules_own_data']:0,
												 "add" => isset($_REQUEST['society_rules_add'])?$_REQUEST['society_rules_add']:0,
												"edit"=>isset($_REQUEST['society_rules_edit'])?$_REQUEST['society_rules_edit']:0,
												"view"=>isset($_REQUEST['society_rules_view'])?$_REQUEST['society_rules_view']:1,
												"delete"=>isset($_REQUEST['society_rules_delete'])?$_REQUEST['society_rules_delete']:0
									  ],
									  "gallery"=>['menu_icone'=>plugins_url( 'apartment-management/assets/images/icon/gallery.png' ),
												'menu_title'=>'Gallery',
												 "page_link"=>'gallery',
												 "own_data" => isset($_REQUEST['gallery_own_data'])?$_REQUEST['gallery_own_data']:0,
												 "add" => isset($_REQUEST['gallery_add'])?$_REQUEST['gallery_add']:0,
												"edit"=>isset($_REQUEST['gallery_edit'])?$_REQUEST['gallery_edit']:0,
												"view"=>isset($_REQUEST['gallery_view'])?$_REQUEST['gallery_view']:1,
												"delete"=>isset($_REQUEST['gallery_delete'])?$_REQUEST['gallery_delete']:0
									  ]
									];
		$options=array("amgt_system_name"=> esc_html__('Apartment Management System' ,'apartment_mgt'),
					"amgt_staring_year"=>"2001",
					"amgt_apartment_address"=>"Near cross road-5",
					"amgt_contact_number"=>"9999999999",
					"amgt_contry"=>"United States",
					"amgt_state"=>'',
					"amgt_city"=>'',
					"amgt_email"=>get_option('admin_email'),
					"amgt_system_logo"=>AMS_PLUGIN_URL.'/assets/images/Thumbnail-img.png',
					"amgt_apartment_background_image"=>AMS_PLUGIN_URL.'/assets/images/apartment-background.png',
					"amgt_member_thumb"=>AMS_PLUGIN_URL.'/assets/images/Thumbnail-img.png',
					"apartment_enable_complaintlist_for_allmember"=>'yes',
					"apartment_paypal_email"=>'',
					"apartment_enable_sandbox"=>'yes',
					"apartment_currency_code" => 'USD',
				//PAY MASTER OPTION//
					"amgt_paymaster_pack"=>"no",
					"amgt_apartment_type" => 'Residential',					
					"amgt_unit_measerment_type" => 'square_meter',
					"maitenance_charge" => '',
					"amgt_access_right_member"=>$role_access_right_member,
					"amgt_access_right_staff_member"=>$role_access_right_staff_member,
					"amgt_access_right_accountant"=>$role_access_right_accountant,
					"amgt_access_right_gatekeeper"=>$role_access_right_gatekeeper,
					"amgt_maintenance_charge_period" => '1',
					"apartment_enable_maintenance" => 'no',
					"apartment_enable_chargis" => 'no',
					"apartment_enable_notifications" => 'yes',
					"amgt_date_formate"=>'Y-m-d',
					"invoice_prefix" => 'DAS',
					"amgt_gst_number" => '',
					"amgt_tax_id" => '',
					"amgt_corporate_id" => '',
					"amgt_bank_name" => '',
					"amgt_account_holder_name" => '',
					"amgt_account_number" => '',
					"amgt_account_type" => '',
					"amgt_ifsc_code" => '',					
					"amgt_swift_code" => '',					
					
					'wp_amgt_Member_Registration'=>'You are successfully registered at {{apartment_name}}',
					'wp_amgt_registration_email_template'=>'Dear {{member_name}},
	    You are successfully registered at {{apartment_name}}. Your have register for property at {{unit_name}} for {{building_name}}.
		Your account active after Once you confirm your email.
	
 Regards From {{apartment_name}}.',
			   
			        'wp_amgt_Member_approve_subject'=>'You profile has been approved by admin at {{apartment_name}}',
					'wp_amgt_Member_approve_email_template'=>'Hello {{member_name}},
        You are successfully registered at Apartment Name. You profile has been approved by admin. and you can signin this link {{loginlink}}
	
 Regards From {{apartment_name}}.',
					'wp_amgt_Member_Become_committee_subject'=>'You have been appointed as committee member of Committee, {{apartment_name}}',
					'wp_amgt_Member_Become_committee_email_template'=>'Dear {{member_name}},
        Congratulations!! You have been appointed as committee member of Committee, {{apartment_name}}.click on this link {{loginlink}}
		
 Regards From {{apartment_name}}.',
			 
			        'wp_amgt_Member_removed_committee_subject'=>'You have been removed from Commitee in {{apartment_name}}',
					'wp_amgt_Member_removed_committee_email_template'=>'Dear {{member_name}},
        You have been removes from Committee in {{apartment_name}}.
		
 Regards From {{apartment_name}}.',
 
					'wp_amgt_add_user_subject'=>'Your have been assigned role of {{rolename}} in {{apartment_name}}',
					'wp_amgt_add_user_email_template'=>'Dear {{member_name}},
					
        You are Added by admin of {{apartment_name}} . Your have been assigned role of {{rolename}} in {{apartment_name}}. You can access system using your username and password.  You can signin using this link. {{loginlink}}
 UserName : {{username}}
 Password : {{password}}
 
 Regards From {{apartment_name}}.',
			  
			        'wp_amgt_add_notice_subject'=>'new Notice from {{member_name}} From {{apartment_name}}',
					'wp_amgt_add_notice_email_template'=>'Dear {{member_name}},
        Title : {{notice_title}}.
        Type :  {{notice_type}}.
        Notice Valid upto : {{notice_valid_date}}.
        Description : {{notice_content}}.
	{{Notice_Link}}
 
 Regards From {{apartment_name}}.',
			
			        'wp_amgt_add_event_subject'=>'New Event From {{apartment_name}}',
					'wp_amgt_add_event_email_template'=>'Dear {{member_name}},
        Title : {{event_title}}.
        Event Start Date : {{event_start_date}}.
        Event End Date : {{event_end_date}}.
        Event Start Time: {{event_start_time}}.
        Event End Time: {{event_end_time}}.
        Description : {{event_description}}.
	{{Event_Link}}
 
 Regards From {{apartment_name}}.',
				  
				  'wp_amgt_add_complaint_subject'=>'New Complain From {{member_name}} at {{apartment_name}}',
				  'wp_amgt_add_complaint_email_template'=>'Dear {{member_name}},
        Nature: {{nature}}.
        Type : {{noticetype}}.
        Category  : {{noticecategory}} .
        Status : {{complaintstatus}}.
        Description : {{description}}.
        Apartment Number: {{apartmentnumber}}
        Complain From :{{complainfrom}}.
	{{Complain_Link}}
	
Regards From {{apartment_name}}.',
				  
				  'wp_amgt_Admin_Complain'=>'New Complain From {{member_name}} at {{apartment_name}}',
				  'wp_amgt_admin_complain_email_template'=>'Dear {{admin_name}},
        Nature: {{nature}}.
        Type : {{noticetype}}.
        Category  : {{noticecategory}} .
        Status : {{complaintstatus}}.
        Description : {{description}}.
        Apartment Number: {{apartmentnumber}}
        Complain From :{{complainfrom}}.
		Complain To :{{complainto}}.
	   {{Admin_Complain_Link}}	
	
 Regards From {{apartment_name}}.',
				  
				  'wp_amgt_add_assign_sloat_subject'=>'Parking sloat has been assigned to you in {{apartment_name}}',
				  'wp_amgt_add_assign_sloat_email_template'=>'Dear {{member_name}},
        Parking slot has been assigned to you in {{apartment_name}}. Your parking slot is {{slotname}}. You have assigned this slot from {{startdate}}  to {{enddate}}.
        Vehicle Number : {{vehiclenumber}} 
        Vehicle Model : {{vehiclemodel}}
        Vehicle Type : {{vehicletype}}
        RFID :  {{RFID}}.
	{{Sloat_Link}}
 
 Regards From {{member_name}}.',
			 
			  'wp_amgt_book_facility_subject'=>'Facility Successfully booked by {{booked_user_name}} for {{activity_name}} on {{from_date}} And {{from_time}}',
			  'wp_amgt_book_facility_email_template'=>'Dear {{member_name}},
        {{facility_name}} has been successfully booked for you. This facility booked by {{booked_user_name}} for {{activity_name}} on {{from_date}}. Charges of this facility is {{facility_charge}}. 
        From Date : {{from_date}}
        To Date : {{to_date}}
        From Time: {{from_time}}
        To Time: {{to_time}}.
	{{facility_link}}
 
 Regards From {{apartment_name}}.',
 
  'wp_amgt_book_facility_subject_admin'=>'New Facility Booking Request From {{member_name}} at {{apartment_name}} .',
			  'wp_amgt_book_facility_email_template_admin'=>'Dear {{admin_name}},

       New  {{facility_name}} Facility Booking Request Form {{member_name}} at {{apartment_name}}.

      Activity Name: {{activity_name}}
    
      Facility Charge: {{facility_charge}}

      From Date : {{from_date}}

      To Date : {{to_date}}

      From Time: {{from_time}}

      To Time: {{to_time}}.

	{{facility_link}}
 
 Regards From {{apartment_name}}.',
				 
			'wp_amgt_generate_invoice_subject'=>'Your have a new invoice from {{apartment_name}}',
			'wp_amgt_generate_invoice_email_template'=>'Dear {{member_name}},
        Your have a new invoice. You can check the invoice attached here.{{Payment Link}}',
		
			'wp_amgt_Message_Received_subject'=>'You have received new message from {{Sender Name}} at {{Apartment Name}}',
			'wp_amgt_Message_Received_Template'=>'Dear {{Receiver Name}},
        You have received new message from {{Sender Name}}.{{Message Content}}.
	{{Message_Link}}
		
 Regards From {{Apartment Name}}.',
			   
			'wp_amgt_paid_invoice_subject'=>'Your have successfully paid your invoice {{invoiceno}}',
			'wp_amgt_paid_invoice_email_template'=>'Dear {{member_name}},
        Your have successfully paid your invoice {{invoiceno}}. You can check the invoice attached here.',
				
			'wp_amgt_add_charges_subject'=>'Your have a new Charges Invoice raised by Admin',
		    'wp_amgt_add_charges_email_template'=>'Dear {{member_name}},
        Your have a new Charges Invoice raised by Admin. You can check the Charges Invoice attached here.
		
 Regards From {{apartment_name}}.',
 
 'wp_amgt_visitor_request_aproved_subject'=>'You visitor request has been approved by admin at {{apartment_name}}',
		    'wp_amgt_visitor_request_aproved_content'=>'Hello {{member_name}},
			
       Your visitor request has been approved by admin.
	   
       Regards From {{apartment_name}} .',
	   
	   'wp_amgt_visitor_request_subject'=>'You visitor request has been approved by admin at {{apartment_name}}',
		    'wp_amgt_visitor_request_content'=>'Hello {{admin_name}}
		
        New Visitor Request From {{member_name}} at {{apartment_name}} .
		
         Reson For Visit : {{visit_reson}}.

         Visit Time : {{visit_time}}.
		
         Visit Date : {{visit_date}}.

        Regards From {{apartment_name}} .',
		
		 'wp_amgt_approved_facility_subject'=>'Your facility booking request has been approved by {{admin_name}} at {{apartment_name}}.',
		    'wp_amgt_approved_facility_email_template'=>'Hello {{member_name}},

                 Your facility booking request has been approved by {{admin_name}}.

                Regards From {{apartment_name}}.'
		);
		return $options;
	}
	add_action( 'init',"amgt_register_rules_post_type");
	add_action( 'init',"amgt_register_faq_post_type");
	add_action( 'init',"amgt_register_photogallary_post_type");
	add_action('admin_init','amgt_general_setting');	
	function amgt_general_setting()
	{
		$options=amgt_option();
		foreach($options as $key=>$val)
		{
			add_option($key,$val); 
		}
	}
	 //ADMIN BAR CSS FUNCTION
	function amgt_change_adminbar_css($hook)
	{	
		$current_page = $_REQUEST['page'];
		$pos = strrpos($current_page, "amgt-");
		if($pos !== false)			
		{
				wp_enqueue_style( 'accordian-jquery-ui-css', plugins_url( '/assets/accordian/jquery-ui.css', __FILE__) );
				wp_enqueue_script('accordian-jquery-ui', plugins_url( '/assets/accordian/jquery-ui.js',__FILE__ ));
				wp_enqueue_style( 'amgt-calender-css', plugins_url( '/assets/css/fullcalendar.css', __FILE__) );
				wp_enqueue_style( 'amgt-datatable-css', plugins_url( '/assets/css/dataTables.css', __FILE__) );
				wp_enqueue_style( 'amgt-datatable-select-css', plugins_url( '/assets/css/select-dataTables.css', __FILE__) );
				wp_enqueue_style( 'amgt-dataTables-responsive-css', plugins_url( '/assets/css/dataTables-responsive.css', __FILE__) );
				wp_enqueue_style( 'amgt-style-css', plugins_url( '/assets/css/style.css', __FILE__) );
				wp_enqueue_style( 'amgt-popup-css', plugins_url( '/assets/css/popup.css', __FILE__) );
				wp_enqueue_style( 'amgt-custom-css', plugins_url( '/assets/css/custom.css', __FILE__) );
				wp_enqueue_style( 'amgt-select2-css', plugins_url( '/lib/select2-3.5.3/select2.css', __FILE__) );
				wp_enqueue_script('amgt-select2', plugins_url( '/lib/select2-3.5.3/select2-default.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
				wp_enqueue_script('amgt-calender_moment', plugins_url( '/assets/js/moment.js', __FILE__ ));
				wp_enqueue_script('amgt-fullcalendar', plugins_url( '/assets/js/fullcalendar.js',__FILE__ ));
				wp_enqueue_script('amgt-datatable', plugins_url( '/assets/js/jquery-dataTables.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);
				wp_enqueue_script('amgt-datatable-tools', plugins_url( '/assets/js/dataTables-tableTools.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);
				wp_enqueue_script('amgt-datatable-editor', plugins_url( '/assets/js/dataTables-tableTools.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);	
				wp_enqueue_script('amgt-dataTables-responsive', plugins_url( '/assets/js/dataTables-responsive.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);	
				//wp_enqueue_script('amgt-datatable-select', plugins_url( '/assets/js/dataTables.select.min.js',__FILE__ ), array( 'jquery' ), '4.1.1', true);	
				//wp_enqueue_script('amgt-customjs', plugins_url( '/assets/js/amgt_custom.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
				wp_enqueue_script('amgt-popup', plugins_url( '/assets/js/popup.js', __FILE__ ), array( 'jquery' ), '4.1.1', false );
				
				//popup file alert msg languages translation				
				wp_localize_script('amgt-popup', 'language_translate', array(
						'select_unit_name' => esc_html__('Select Unit Name','apartment_mgt'),
						'Select_Member' => esc_html__('Select Member','apartment_mgt'),
						'count_facility_popup' => esc_html__('End Time should be greater than Start Time','apartment_mgt'),
						'end_time_facility' => esc_html__('End Time should be greater than Start Time', 'apartment_mgt' ),
						'enter_category_alert' => esc_html__('Please enter Category Name.', 'apartment_mgt' ),
						'discount_amount__alert' => esc_html__('discount amount can not greater than total amount', 'apartment_mgt' ),
						'add_remove' => esc_html__('Are you sure want to delete this record?', 'apartment_mgt' ),
						
					)
				);
				wp_localize_script( 'amgt-popup', 'amgt  ', array( 'ajax' => admin_url( 'admin-ajax.php' ) ) );
			 	wp_enqueue_script('jquery');
			 	wp_enqueue_media();
		       	wp_enqueue_script('thickbox');
		       	wp_enqueue_style('thickbox');
			 	wp_enqueue_script('amgt-image-upload', plugins_url( '/assets/js/image-upload.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
			    wp_enqueue_style( 'amgt-bootstrap-css', plugins_url( '/assets/css/bootstrap.css', __FILE__) );
				wp_enqueue_style( 'amgt-bootstrap-multiselect-css', plugins_url( '/assets/css/bootstrap-multiselect.css', __FILE__) );
				//wp_enqueue_style( 'amgt-bootstrap-timepicker-css', plugins_url( '/assets/css/bootstrap-timepicker.min.css', __FILE__) );
				//wp_enqueue_style( 'amgt-datepicker-min-css', plugins_url( '/assets/css/datepicker-default.css', __FILE__) );
			    wp_enqueue_style( 'amgt-time-css', plugins_url( '/assets/css/time.css', __FILE__) );
			 	wp_enqueue_style( 'amgt-font-awesome-css', plugins_url( '/assets/css/font-awesome-dafault.css', __FILE__) );
			 	wp_enqueue_style( 'amgt-white-css', plugins_url( '/assets/css/white.css', __FILE__) );
			 	wp_enqueue_style( 'amgt-apartment-min-css', plugins_url( '/assets/css/apartment.css', __FILE__) );
				wp_enqueue_style( 'newversion', plugins_url( '/assets/css/newversion.css', __FILE__) );
				if (is_rtl())
				{
					wp_enqueue_style( 'amgt-bootstrap-rtl-css', plugins_url( '/assets/css/bootstrap-rtl.css', __FILE__) );
				}
				
				$lancode=get_locale();
				$code=substr($lancode,0,2);
		
				wp_enqueue_script('amgt-calender-'.$code.'', plugins_url( '/assets/js/calendar-lang/'.$code.'.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
		
			    wp_enqueue_style( 'amgt-apart-responsive-css', plugins_url( '/assets/css/apart-responsive.css', __FILE__) );
			 	wp_enqueue_script('amgt-bootstrap-js', plugins_url( '/assets/js/bootstrap.js', __FILE__ ) );
			 	wp_enqueue_script('amgt-bootstrap-multiselect-js', plugins_url( '/assets/js/bootstrap-multiselect.js', __FILE__ ) );
			 	//wp_enqueue_script('amgt-bootstrap-timepicker-js', plugins_url( '/assets/js/bootstrap-timepicker.min.js', __FILE__ ) );
				//wp_enqueue_script('amgt-bootstrap-datepicker-js', plugins_url( '/assets/js/bootstrap-datepicker.js', __FILE__ ) );
				wp_enqueue_script('amgt-time-js', plugins_url( '/assets/js/time.js', __FILE__ ) );
			 	wp_enqueue_script('amgt-timeago-js', plugins_url( '/assets/js/jquery-timeago.js', __FILE__ ) );
			 	//Validation style And Script CALL
			 	//validation lib CALL
			 	wp_enqueue_style( 'amgt-validate-css', plugins_url( '/lib/validationEngine/css/validationEngine-jquery.css', __FILE__) );	 	
			 	wp_register_script( 'jquery-validationEngine-'.$code.'', plugins_url( '/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js', __FILE__), array( 'jquery' ) );
			 	wp_enqueue_script( 'jquery-validationEngine-'.$code.'' );
			 	wp_register_script( 'jquery-validationEngine', plugins_url( '/lib/validationEngine/js/jquery-validationEngine.js', __FILE__), array( 'jquery' ) );
			 	wp_enqueue_script( 'jquery-validationEngine' );
			    wp_enqueue_script('apart_custom_confilict_obj', plugins_url( '/assets/js/apart_custom_confilict_obj.js', __FILE__ ), array( 'jquery' ), '4.1.1', false );
				

		}
	}
	if(isset($_REQUEST['page']))
			add_action( 'admin_enqueue_scripts', 'amgt_change_adminbar_css' );
    }
	//INSTALL LOGIN PAGE FOR FRONTEN SIDE //
function amgt_install_login_page()
{
	if ( !get_option('amgt_login_page') ) {
		$curr_page = array(
				'post_title' => esc_html__('Apartment Management Login Page', 'apartment_mgt'),
				'post_content' => '[amgt_login]',
				'post_status' => 'publish',
				'post_type' => 'page',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_category' => array(1),
				'post_parent' => 0 );
		$curr_created = wp_insert_post( $curr_page );
		update_option( 'amgt_login_page', $curr_created );
	}
}
//user dashboard function
function amgt_user_dashboard()
{
	if(isset($_REQUEST['apartment-dashboard']))
	{
		require_once AMS_PLUGIN_DIR. '/fronted_template.php';
		exit;
	}
	
}
//REMOVE ALL THEAME STYLES //
function amgt_remove_all_theme_styles()
 {
	global $wp_styles;
	$wp_styles->queue = array();
  }
if(isset($_REQUEST['apartment-dashboard']) && $_REQUEST['apartment-dashboard'] == 'user')
{
  add_action('wp_print_styles', 'amgt_remove_all_theme_styles', 100);
}

//FRONTEN SIDE CALL SCRIPT FUNCTION //
function amgt_load_script1()
{
	if(isset($_REQUEST['apartment-dashboard']) && $_REQUEST['apartment-dashboard'] == 'user')
	{
		
	    wp_register_script('amgt-popup-front', plugins_url( 'assets/js/popup.js', __FILE__ ), array( 'jquery' ));
		
		wp_enqueue_script('amgt-popup-front');
		wp_localize_script( 'amgt-popup-front', 'amgt  ', array( 'ajax' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script('jquery');
		wp_enqueue_script('apart_custom_confilict_obj', plugins_url( '/assets/js/apart_custom_confilict_obj.js', __FILE__ ), array( 'jquery' ), '4.1.1', false );
	}
		wp_register_script('amgt-fancybox', plugins_url( 'assets/js/jquery-fancybox.js', __FILE__ ), array( 'jquery' ));
		wp_enqueue_script('amgt-fancybox');
		wp_register_script('amgt-fancybox-media', plugins_url( 'assets/js/jquery-fancybox-media.js', __FILE__ ), array( 'jquery' ));
		wp_enqueue_script('amgt-fancybox-media');
		wp_enqueue_style( 'amgt-fancybox-css', plugins_url( '/assets/css/jquery-fancybox.css', __FILE__) );
		//popup file alert msg languages translation				
		wp_localize_script('amgt-popup-front', 'language_translate', array(
				'count_facility_popup' => esc_html__('End Time should be greater than Start Time','apartment_mgt'),
				'end_time_facility' => esc_html__('End Time should be greater than Start Time', 'apartment_mgt' ),
				'enter_category_alert' => esc_html__('Please enter Category Name.', 'apartment_mgt' ),
				'discount_amount__alert' => esc_html__('discount amount can not greater than total amount', 'apartment_mgt' ),
				'add_remove' => esc_html__('Are you sure want to delete this record?', 'apartment_mgt' ),
				
			)
		);
		
}
//LOAD DOMAI NAMAE FUNCTION //
function amgt_domain_load(){
	load_plugin_textdomain( 'apartment_mgt', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );
}
add_action( 'plugins_loaded', 'amgt_domain_load' );
add_action('wp_enqueue_scripts','amgt_load_script1');
add_action('wp_head','amgt_user_dashboard');
add_action( 'wp_login_failed', 'amgt_login_failed' ); // hook failed login //
// HANDLE LOGIN FAILD ACTIONS//
function amgt_login_failed( $user )
{
	// check what page the login attempt is coming from
	$referrer = $_SERVER['HTTP_REFERER'];
	
	 $curr_args = array(
				'page_id' => get_option('amgt_login_page'),
				'login' => 'failed'
				);
				print_r($curr_args);
				$referrer_faild = add_query_arg( $curr_args, get_permalink( get_option('amgt_login_page') ) );
	// check that were not on the default login page
	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $user!=null ) {
		// make sure we don't already have a failed login attempt
		if ( !strstr($referrer, 'login=failed' )) {
			// Redirect to the login page and append a query string of login failed
			wp_redirect( $referrer_faild);
		} else {
			wp_redirect( $referrer );
		}
		exit;
	}
}

/*function verify_username_password( $user, $username, $password ) {
 $login_page  = home_url();
   if( $username == "" || $password == "" ) {
       wp_redirect( $login_page . "?login=empty" );
       exit;
   }
}
add_filter( 'authenticate', 'verify_username_password', 1, 3);*/
if(isset($_GET['login']) && $_GET['login'] == 'empty')
{?>

<div id="login-error" class="login-error" style="background-color: #FFEBE8;border:1px solid #C00;padding:5px;" >
	  <p><?php _e('Login Failed: Username and/or Password is empty, please try again.','apartment_mgt');?></p>
	</div>
<?php
}
//FRONTEND SIDE LOGIN PAGE VALIDATION FUNCTION //
function amgt_login_form()
{
	$args = array( 'redirect' => site_url() );
	if(isset($_GET['login']) && $_GET['login'] == 'failed')
	{
	?>
		<div id="login-error" class="login_css">
		  <p><?php esc_html_e('Login failed: You have entered an incorrect Username or password, please try again.','apartment_mgt');?></p>
		</div>
		<?php
	}
	
	if(isset($_GET['login']) && $_GET['login'] == 'empty')
	{ ?>

	<div id="login-error" class="login-error" style="background-color: #FFEBE8;border:1px solid #C00;padding:5px;" >
	  <p><?php _e('Login Failed: Username and/or Password is empty, please try again.','apartment_mgt');?></p>
	</div>
    <?php	
	}
	global $reg_errors;
	$reg_errors = new WP_Error;
		if ( is_wp_error( $reg_errors ) ) 
		{
			foreach ( $reg_errors->get_error_messages() as $error )
			{
				echo '<div>';
				echo '<strong>'.esc_html__('ERROR','apartment_mgt').'</strong>:';
				echo $error . '<br/>';
				echo '</div>';
			}
		}
	$args = array(
			'echo' => true,
			'redirect' => site_url( $_SERVER['REQUEST_URI'] ),
			'form_id' => 'loginform',
			'label_username' => esc_html__('Username' , 'apartment_mgt'),
			'label_password' => esc_html__('Password', 'apartment_mgt' ),
			'label_remember' => esc_html__('Remember Me' , 'apartment_mgt'),
			'label_log_in' => esc_html__('Log In' , 'apartment_mgt'),
			'id_username' => 'user_login',
			'id_password' => 'user_pass',
			'id_remember' => 'rememberme',
			'id_submit' => 'wp-submit',
			'remember' => true,
			'value_username' => NULL,
	        'value_remember' => false ); 

	$args = array('redirect' => site_url('/?apartment-dashboard=user') );
	if (is_user_logged_in())
	{
		// add_filter('wp_authenticate_user', 'amgt_authenticate_user',10,2);
		?>
		<a href="<?php echo home_url('/')."?apartment-dashboard=user"; ?>">
		<?php esc_html_e('Dashboard','apartment_mgt');?>
		</a>
		<br/><a href="<?php echo wp_logout_url(); ?>"><?php esc_html_e('Logout','apartment_mgt');?></a> 
	  <?php
	}
	else 
	{
		wp_login_form( $args );
		echo '<a style="margin-left: 28%;" href="'.wp_lostpassword_url().'" title="Lost Password">'.esc_html__('Forgot your password?','apartment_mgt').'</a> ';
	}
}
// function amgt_authenticate_user($user)
// {
// 	$userdata=$user->data;
// 	$havemeta = get_user_meta($userdata->ID, 'amgt_hash', true);
// 	if($havemeta)
// 	{
// 		global $reg_errors;
// 		$reg_errors = new WP_Error;
// 		return $reg_errors->add( 'not_active', 'Please active account' );
// 	}
// 	return $user;
// }
//add user authenticate filter
add_filter('wp_authenticate_user', function($user)
{
$havemeta = get_user_meta($user->ID, 'amgt_hash', true);
if($havemeta)
{
$WP_Error = new WP_Error();
$WP_Error->add('my_error', '<strong>Error</strong>: Your account is inactive. Contact your administrator to activate it.');
return $WP_Error;

//global $reg_errors;
//$reg_errors = new WP_Error;
//return $reg_errors->add( 'not_active', 'Please active account' );

}
return $user;
}, 10, 2);
add_action( 'admin_print_scripts-post-new.php', 'amgt_post_admin_script');
add_action( 'admin_print_scripts-post.php', 'amgt_post_admin_script' );

function amgt_post_admin_script()
{		global $post_type;
		if( 'amgt_photo_gallery' == $post_type ) 	
		{
			wp_enqueue_style( 'amgt-custom-image-uploader-css', plugins_url( '/assets/css/custom-image-uploader.css', __FILE__) );
			wp_enqueue_script('amgt-custom-image-upload-js', plugins_url( '/assets/js/custom-image-uploader.js', __FILE__ ), array( 'jquery' ), '4.1.1', true );
		}
}
//INSTALL MEMERRAGISTATION PAGE FOR FRONTEN SIDE
function amgt_install_member_registration_page()
{
	if ( !get_option('amgt_member_registration_page') ) {
		$curr_page = array(
				'post_title' => esc_html__('Apartment Management Member Registration Page', 'apartment_mgt'),
				'post_content' => '[amgt_member_registration]',
				'post_status' => 'publish',
				'post_type' => 'page',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_category' => array(1),
				'post_parent' => 0 );
		$curr_created = wp_insert_post( $curr_page );
		update_option( 'amgt_member_registration_page', $curr_created );
	}
}
//member registration short code
function amgt_member_registration_shortcode() {
    ob_start();
    amgt_member_registration_function();
    return ob_get_clean();
}
//member registration function
function amgt_member_registration_function()
{
	global $building_id,$unit_cat_id,$unit_name,$member_type,$committee_member,$first_name,$middle_name,$last_name,$gender,$birth_date,$address,$city_name,$mobile,$email,$username,$password,$amgt_user_avatar;
    if ( isset($_POST['registration_front_member'] ) )
	{
        amgt_registration_validation(
		$_POST['building_id'],
		$_POST['unit_cat_id'],
		$_POST['unit_name'],
		$_POST['member_type'],
		$_POST['first_name'],
		$_POST['last_name'],
		$_POST['gender'],
		$_POST['birth_date'],
		$_POST['address'],
		$_POST['mobile'],		
		$_POST['email'],
        $_POST['username'],
        $_POST['password']);
        // sanitize user form input
        global $building_id,$unit_cat_id,$unit_name,$member_type,$committee_member,$first_name,$middle_name,$last_name,$gender,$birth_date,$address,$mobile,$email,$username,$password,$amgt_user_avatar;
        $building_id =    $_POST['building_id'] ;		
        $unit_cat_id =    $_POST['unit_cat_id'] ;		
        $unit_name =    $_POST['unit_name'] ;		
        $member_type =    $_POST['member_type'] ;	
		if(isset($_POST['committee_member']))
		{
		 $committee_member =    $_POST['committee_member'] ;	
		}		
		$first_name =    $_POST['first_name'] ;
		$middle_name =   $_POST['middle_name'] ;
		$last_name =  $_POST['last_name'] ;
		$gender =   $_POST['gender'] ;
		$birth_date =   $_POST['birth_date'] ;
		$address =   $_POST['address'] ;
		$mobile = $_POST['mobile'] ;		
		$username = $_POST['username'] ;
        $password = $_POST['password'] ;
        $email = $_POST['email'] ;
        // call @function complete_registration to create the user
        // only when no WP_error is found
        amgt_complete_registration($building_id,$unit_cat_id,$unit_name,$member_type,$committee_member,$first_name,$middle_name,$last_name,$gender,$birth_date,$address,$mobile,$email,$username,$password,$amgt_user_avatar);
    }
    amgt_registration_form($building_id,$unit_cat_id,$unit_name,$member_type,$committee_member,$first_name,$middle_name,$last_name,$gender,$birth_date,$address,$mobile,$email,$username,$password,$amgt_user_avatar);
}
function amgt_complete_registration($building_id,$unit_cat_id,$unit_name,$member_type,$committee_member,$first_name,$middle_name,$last_name,$gender,$birth_date,$address,$mobile,$email,$username,$password,$amgt_user_avatar) 
{
    global $reg_errors;
	 global $building_id,$unit_cat_id,$unit_name,$member_type,$committee_member,$first_name,$middle_name,$last_name,$gender,$birth_date,$address,$mobile,$email,$username,$password,$amgt_user_avatar;
	 $smgt_avatar = '';	
		
     if ( 1 > count( $reg_errors->get_error_messages() ) ) 
	{
        $userdata = array(
        'user_login'    =>   $username,
        'user_email'    =>   $email,
        'user_pass'     =>   $password,
        'user_url'      =>   NULL,
        'first_name'    =>   $first_name,
        'last_name'     =>   $last_name,
        'nickname'      =>   NULL
        );
		
		$user_id = wp_insert_user( $userdata );
		 $member_image_url = '';
	      if($_FILES['amgt_user_avatar']['size'] > 0)
			{
			 $member_image=amgt_amgt_load_documets($_FILES['amgt_user_avatar'],'amgt_user_avatar','pimg');
			  $member_image_url=content_url().'/uploads/apartment_assets/'.$member_image;
			}
 		$user = new WP_User($user_id);
	    $user->set_role('member');
		$usermetadata=array('building_id' => $building_id,	
						'unit_cat_id' => $unit_cat_id,
						'unit_name' => $unit_name,
						'member_type' => $member_type,
						'committee_member' => $committee_member,
						'middle_name'=>$middle_name,
						'gender'=>$gender,
						'birth_date'=>$birth_date,
						'address'=>$address,
						'mobile'=>$mobile,
						'amgt_user_avatar'=>$member_image_url);
		
		
		foreach($usermetadata as $key=>$val)
		{		
			update_user_meta( $user_id, $key,$val );	
		}
		$returnans=update_user_meta( $user_id, 'first_name',$first_name );
		$returnans=update_user_meta( $user_id, 'last_name',$last_name );
		$hash = md5( rand(0,1000) );
		update_user_meta( $user_id, 'amgt_hash', $hash );
		//---------------- SEND  SMS ------------------//
		include_once(ABSPATH.'wp-admin/includes/plugin.php');
		if(is_plugin_active('sms-pack/sms-pack.php'))
		{
			if(!empty(get_user_meta($user_id, 'phonecode',true))){ $phone_code=get_user_meta($user_id, 'phonecode',true); }else{ $phone_code='+'.amgt_get_countery_phonecode(get_option( 'amgt_contry' )); }
							
			$user_number[] = $phone_code.get_user_meta($user_id, 'mobile',true);
			
			$apartmentname=get_option('amgt_system_name');
			$message_content ="You are successfully registered at $apartmentname .";
			$current_sms_service 	= get_option( 'smgt_sms_service');
			$args = array();
			$args['mobile']=$user_number;
			$args['message_from']="Registration";
			$args['message']=$message_content;					
			if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
			{				
				$send = send_sms($args);							
			}
		}
		
		$user_info = get_userdata($user_id);
		$to = $user_info->user_email; 
		$subject =get_option('wp_amgt_Member_Registration');
		$apartmentname=get_option('amgt_system_name');
		$message_content=get_option('wp_amgt_registration_email_template');
		$loginlink=home_url().'/apartment-management/';
		$building = get_post($building_id); 
		$buildingname="";
		if(!empty($building))
			$buildingname=$building->post_title;
		$subject_search=array('{{apartment_name}}');
		$subject_replace=array($apartmentname);
		$search=array('{{member_name}}','{{apartment_name}}','{{unit_name}}','{{building_name}}','{{loginlink}}');
		$replace = array($user_info->display_name,$apartmentname,$unit_name,$buildingname,$loginlink);
		$message_content = str_replace($search, $replace, $message_content);
		$subject=str_replace($subject_search,$subject_replace,$subject);
		$apartment=get_option('amgt_system_name');
		$headers="";
		$headers .= 'From: '.$apartment.' <noreplay@gmail.com>' . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/plain; charset=iso-8859-1\r\n";
		$enable_notofication=get_option('apartment_enable_notifications');
		{
			wp_mail($to, $subject, $message_content,$headers); 
		}
		 echo 'Your Registration is complete. Your account active after Once you confirm your email.';
	}
}
function amgt_registration_form($building_id,$unit_cat_id,$unit_name,$member_type,$committee_member,$first_name,$middle_name,$last_name,$gender,$birth_date,$address,$mobile,$email,$username,$password,$amgt_user_avatar) 
{
	$edit = 0;
	$obj_units=new Amgt_ResidentialUnit;
	$role='member';
	$lancode=get_locale();
	$code=substr($lancode,0,2);		
	wp_enqueue_style( 'amgt-validate-css', plugins_url( '/lib/validationEngine/css/validationEngine-jquery.css', __FILE__) );	 	
	wp_register_script( 'jquery-validationEngine-'.$code.'', plugins_url( '/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js', __FILE__), array( 'jquery' ) );
	wp_enqueue_script( 'jquery-validationEngine-'.$code.'' );
	wp_register_script( 'jquery-validationEngine', plugins_url( '/lib/validationEngine/js/jquery-validationEngine.js', __FILE__), array( 'jquery' ) );
	wp_enqueue_script( 'jquery-validationEngine' );
    wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    wp_enqueue_script('amgt-popup', plugins_url( '/assets/js/popup.js', __FILE__ ), array( 'jquery' ), '4.1.1', false );
	wp_localize_script( 'amgt-popup', 'amgt  ', array( 'ajax' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_style( 'amgt-bootstrap-css', plugins_url( '/assets/css/bootstrap.css', __FILE__) );
	wp_enqueue_style( 'amgt-datepicker1-min-css', plugins_url( '/assets/css/datepicker-default.css', __FILE__) );
	//wp_enqueue_script('amgt-bootstrap-datepicker-js', plugins_url( '/assets/js/bootstrap-datepicker.js', __FILE__ ) );
	?>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/jquery-1-11-1.js'; ?>"></script>
	<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/bootstrap-datepicker.js'; ?>"></script>
	<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/datepicker-default.css'; ?>">
<?php	
 echo '
    <style>
		
		.amgt_registraion_form .form-group .form-control {
		  font-size: 16px;
		}
			.amgt_registraion_form .form-group,.amgt_registraion_form .form-group .form-control{float:left;width:100%}
			.amgt_registraion_form .form-group .require-field{color:red;}
			.amgt_registraion_form select.form-control,.amgt_registraion_form input[type="file"] {
		  padding: 0.5278em;
		   margin-bottom: 5px;
		}
		.amgt_registraion_form  .radio-inline {
			float: left;
			margin-bottom: 10px;
			margin-top: 10px;
			 margin-right: 15px;
		}
		.amgt_registraion_form .form-control.checkbox-input {
		  margin-top: 15px;
		}
		.amgt_registraion_form  .radio-inline .tog {
			margin-right: 5px;
		}
		.amgt_registraion_form .col-sm-2.control-label {
		  text-align: right;
		}
			.amgt_registraion_form .form-group .col-sm-2 {width: 32.667%;}
			.amgt_registraion_form .form-group .col-sm-8 {     width: 66.66666667%;}
			.amgt_registraion_form .form-group .col-sm-7{  width: 53.33333333%;}
			.amgt_registraion_form .form-group .col-sm-1{  width: 13.33333333%;}
			.amgt_registraion_form .form-group .col-sm-8, .amgt_registraion_form .form-group .col-sm-2,.amgt_registraion_form .form-group .col-sm-7,.amgt_registraion_form .form-group .col-sm-1{      
			padding-left: 15px;
			 padding-right: 15px;
			float:left;}
			.amgt_registraion_form .form-group .col-sm-8, .amgt_registraion_form .form-group .col-sm-2,.amgt_registraion_form .amgt_registraion_form .form-group .col-sm-7
			{
				position: relative;
				min-height: 1px;   
			}
			.amgt_registraion_form div {
				margin-bottom:6px;
			}
			 
			.amgt_registraion_form input{
				margin-bottom:4px;
			}
			.amgt_registraion_form .col-sm-offset-2.col-sm-8 {
		  float: left;
		  margin-left: 35%;
		  margin-top: 15px;
		}
		.amgt_registraion_form .form-control {
		  line-height: 30px;
		}
			.student_reg_error .error{color:red;}
		.amgt_registraion_form .occupied_div
		{
			display:none;
		}
		.amgt_registraion_form .menu,.amgt_registraion_form .entry-title,.amgt_registraion_form .submit_btn
		{
			font-size: 14px!important;
		}
		.amgt_registraion_form select 
		{
			height: 40px;
		}
		.width_auto
		{
			width: auto !important;
		}
		.width_48
		{
			width: 48% !important;
		}
		.width_100px
		{
			width:100px !important;
		}
		.height_45
		{
			height:45px !important;
			line-height: 25px !important;
		}
	</style>'; ?>
	<div class="amgt_registraion_form">
	<script type="text/javascript">
	jQuery(document).ready(function($)
	{
		"use strict";
		$('#member_form').validationEngine({promptPosition : "bottomRight",maxErrorsPerField: 1});
		$.fn.datepicker.defaults.format =" <?php  echo amgt_dateformat_PHP_to_jQueryUI(amgt_date_formate()); ?>";
		$('#birth_date').datepicker({
		endDate: '+0d',
		autoclose: true
		});
		$('#occupied_date').datepicker({	  
		  autoclose: true
		});  
	//username not  allow space validation
		$('#username').keypress(function( e ) {
		   if(e.which === 32) 
			 return false;
		});
	});
	</script>
	<style>
.dropdown-menu {
    min-width: 265px!important;
}
</style>
	    <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo esc_attr($action);?>">
		<input type="hidden" name="role" value="<?php echo esc_attr($role);?>"  />
		    <div class="panel-body">
                <form name="member_form" action="" method="post" class="form-horizontal" id="member_form">
					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
					<input type="hidden" name="user_id" value="<?php echo isset($member_id)?member_id:'';?>"  />
					<input type="hidden" name="role" value="<?php echo esc_attr($role);?>"  />
					<div class="form-group">
				<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select class="form-control validate[required] building_category" name="building_id">
						<option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
						<?php 
						if($edit)
						{
						  $category =$result->building_id;
						}
						elseif(isset($_REQUEST['building_id']))
						{
						  $category =$_REQUEST['building_id'];
						}
						else
						{
						  $category = "";
						}
						$activity_category=amgt_get_all_category('building_category');
						if(!empty($activity_category))
						{
							foreach ($activity_category as $retrive_data)
							{
								echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
							}
						} ?>
					</select>
				</div>				
			</div>
			<div id="hello"></div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">			
					<select class="form-control validate[required] unit_categorys" name="unit_cat_id">
						<option value=""><?php esc_html_e('Select Unit Category','apartment_mgt');?></option>
						<?php 
							if($edit)
								$category =$result->unit_cat_id;
							elseif(isset($_REQUEST['unit_cat_id']))
								$category =$_REQUEST['unit_cat_id'];  
							else 
								$category = "";
							
							$activity_category=amgt_get_all_category('unit_category');
							if(!empty($activity_category))
							{
								foreach ($activity_category as $retrive_data)
								{
									echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
								}
							} 	
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="unit_name"><?php esc_html_e('Unit','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select class="form-control validate[required] unit_name" name="unit_name">
						<option value=""><?php esc_html_e('Select Unit Name','apartment_mgt');?></option>
						
					</select>
				</div>			
			</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="first_name"><?php esc_html_e('First Name','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="first_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo esc_attr($result->first_name);}elseif(isset($_POST['first_name'])) echo esc_attr($_POST['first_name']);?>" name="first_name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="middle_name"><?php esc_html_e('Middle Name','apartment_mgt');?></label>
						<div class="col-sm-8">
							<input id="middle_name" class="form-control validate[custom[onlyLetterSp]]" type="text" maxlength="50" value="<?php if($edit){ echo esc_attr($result->middle_name);}elseif(isset($_POST['middle_name'])) echo esc_attr($_POST['middle_name']);?>" name="middle_name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="last_name"><?php esc_html_e('Last Name','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="last_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo esc_attr($result->last_name);}elseif(isset($_POST['last_name'])) echo esc_attr($_POST['last_name']);?>" name="last_name">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="gender"><?php esc_html_e('Gender','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
						<?php $genderval = "male"; if($edit){ $genderval=$result->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
							<label class="radio-inline">
							 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php esc_html_e('Male','apartment_mgt');?>
							</label>
							<label class="radio-inline margin_top_10">
							  <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php esc_html_e('Female','apartment_mgt');?> 
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="birth_date"><?php esc_html_e('Date of birth','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="birth_date" class="form-control validate[required]" autocomplete="off" type="text"  name="birth_date" 
							value="<?php if($edit){ echo esc_attr($result->birth_date);}elseif(isset($_POST['birth_date'])) echo esc_attr($_POST['birth_date']);?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="unit_name"><?php esc_html_e('Member Type','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<select class="form-control validate[required]" name="member_type" id="member_type">
							<option value=""><?php esc_html_e('Select Member Type','apartment_mgt');?></option>
							<?php 
							if($edit)
								$category =$result->member_type;
							elseif(isset($_POST['member_type']))
								$category =$_POST['member_type'];
							else
								$category ="";?>
							<option value="Owner" <?php selected($category,'Owner');?>><?php esc_html_e('Owner','apartment_mgt');?></option>
							<option value="tenant" <?php selected($category,'tenant');?>><?php esc_html_e('Tenant','apartment_mgt');?></option>
							<option value="owner_family" <?php selected($category,'owner_family');?>><?php esc_html_e('Owner Family','apartment_mgt');?></option>
							<option value="tenant_family" <?php selected($category,'tenant_family');?>><?php esc_html_e('Tenant Family','apartment_mgt');?></option>
							<option value="care_taker" <?php selected($category,'care_taker');?>><?php esc_html_e('Care Taker','apartment_mgt');?></option>
							</select>
						</div>
					</div>
					<div class="occupied_div">
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php esc_html_e('Occupied By','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<select class="form-control validate[required] allready_occupied" name="occupied_by">
							<option value=""><?php esc_html_e('Select Occupied By','apartment_mgt');?></option>
							<?php 
							if($edit)
								$occupied_by =$result->occupied_by;
							elseif(isset($_POST['occupied_by']))
								$occupied_by =$_POST['occupied_by'];
							else
								$occupied_by ="";?>
							<option value="Owner" <?php selected($occupied_by,'Owner');?>><?php esc_html_e('Owner','apartment_mgt');?></option>
							<option value="tenant" <?php selected($occupied_by,'tenant');?>><?php esc_html_e('Tenant','apartment_mgt');?></option>			
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" ><?php esc_html_e('Occupied Date','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="occupied_date" class="form-control validate[required]" autocomplete="off" type="text"  name="occupied_date" 
							value="<?php if($edit){ echo date(amgt_date_formate(),strtotime($result->occupied_date));}elseif(isset($_POST['occupied_date'])) echo esc_attr($_POST['occupied_date']);?>">
						</div>
					</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="address"><?php esc_html_e('Address','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="address" class="form-control validate[required]" type="text" maxlength="150" name="address" 
							value="<?php if($edit){ echo esc_attr($result->address);}elseif(isset($_POST['address'])) echo esc_attr($_POST['address']);?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label " for="email"><?php esc_html_e('Email','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="50" type="text"  name="email" 
							value="<?php if($edit){ echo esc_attr($result->user_email);}elseif(isset($_POST['email'])) echo esc_attr($_POST['email']);?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label " for="mobile"><?php esc_html_e('Mobile Number','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-1 width_100px padding_right_0">
						
						<input type="text" readonly value="+<?php echo amgt_get_countery_phonecode(get_option( 'amgt_contry' ));?>"  class="form-control" name="phonecode">
						</div>
						<div class="col-sm-6 width_48">
							<input id="mobile" class="form-control validate[required,custom[phone]] text-input" type="number" min="0" onKeyPress="if(this.value.length==15) return false;"  name="mobile" 
							value="<?php if($edit){ echo esc_attr($result->mobile);}elseif(isset($_POST['mobile'])) echo esc_attr($_POST['mobile']);?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="username"><?php esc_html_e('User Name','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="username" class="form-control validate[required]" type="text" maxlength="30" name="username" 
							value="<?php if($edit){ echo esc_attr($result->user_login);}elseif(isset($_POST['username'])) echo esc_attr($_POST['username']);?>" <?php if($edit) echo "readonly";?>>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="password"><?php esc_html_e('Password','apartment_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
						<div class="col-sm-8">
							<input id="password" class="form-control <?php if(!$edit) echo 'validate[required]';?>" type="password" minlength="8" maxlength="12" name="password" value="">
						</div>
					</div>
				
						<div class="form-group">	
						<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Image','apartment_mgt');?></label>
						
							<div class="col-sm-8">
							<input type="file" class="form-control file height_45" name="amgt_user_avatar">
						</div>
						
						</div>	
						<div class="col-sm-offset-2 col-sm-8">
						
						<input type="submit" value="<?php  esc_html_e('Member Registration','apartment_mgt'); ?>" name="registration_front_member" class="submit_btn btn btn-success"/>
					</div>
                </form>
	        </div>
<?php
}
function amgt_registration_validation($building_id,$unit_cat_id,$unit_name,$member_type, $first_name,$last_name,$gender,$birth_date,$address,$mobile,$email,$username,$password)  
{
	global $reg_errors;
	$reg_errors = new WP_Error;
	if ( empty( $building_id )  || empty( $unit_cat_id )  || empty( $unit_name ) || empty( $member_type )  || empty( $first_name ) || empty( $last_name ) || empty( $birth_date )  || empty( $address ) || empty( $email ) || empty( $username ) || 	empty( $password )	) 
	{
    $reg_errors->add('field', 'Required form field is missing');
	}
	if ( 4 > strlen( $username ) ) {
    $reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
	}
	if ( username_exists( $username ) )
		$reg_errors->add('user_name', 'Sorry, that username already exists!');
	if ( ! validate_username( $username ) ) {
    $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
	}
	
	if ( !is_email( $email ) ) {
    $reg_errors->add( 'email_invalid', 'Email is not valid' );
	}
	if ( email_exists( $email ) ) {
    $reg_errors->add( 'email', 'Email Already in use' );
	}
	
	if ( is_wp_error( $reg_errors ) )
	{
 
			foreach ( $reg_errors->get_error_messages() as $error )
			{
				echo '<div class="student_reg_error">';
				echo '<strong>ERROR</strong> : ';
				echo '<span class="error"> '.$error . ' </span><br/>';
				echo '</div>';
			}
    }	
}	
add_shortcode( 'amgt_faq_list','amgt_faq_list_page' );	
add_action('init','amgt_install_faq_page' );
add_shortcode( 'amgt_society_rules','amgt_society_rules_page' );	
add_action('init','amgt_install_society_rules_page' );
add_shortcode( 'photogallaryCode','amgt_photo_gallary_page' );	
//add_action('init','amgt_install_gallary_page' );		
add_shortcode( 'amgt_login','amgt_login_form' );
add_action('init','amgt_install_login_page');
add_action('init','amgt_output_ob_start');
add_action('init','amgt_install_member_registration_page');
add_shortcode( 'amgt_member_registration', 'amgt_member_registration_shortcode' );
function amgt_output_ob_start()
{
	ob_start();
}
//GET FAQ LIST FUNCTION //
function amgt_faq_list_page()
{
	$args = array('post_type' => 'amgt_FAQ','post_status' => 'publish' ); 
	$faq_array = get_posts( $args );
	//var_dump($posts_array);
	foreach($faq_array as $faq)
	{?>
		<div class="col-sm-10">
			<h3><?php echo esc_attr($faq->post_title);?></h3>
			<p><?php echo esc_attr($faq->post_content);?></p>
		</div>
	<?php }
 }
 //INSTAL FAQ PAGE FUNCTION
 function amgt_install_faq_page()
{
	
	if ( !get_option('amgt_faq_page') )
	{
		$curr_page = array(
				'post_title' => esc_html__('Apartment FAQ Page', 'apartment_mgt'),
				'post_content' => '[amgt_faq_list]',
				'post_status' => 'publish',
				'post_type' => 'page',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_category' => array(1),
				'post_parent' => 0 );
		
		$curr_created = wp_insert_post( $curr_page );
		update_option( 'amgt_faq_page', $curr_created );
	}
}
//INSTALL Society RULES FUNCTION //
function amgt_install_society_rules_page()
{
	if ( !get_option('amgt_rules_page') ) {
		
		$curr_page = array(
				'post_title' => esc_html__('Apartment Society Rules', 'apartment_mgt'),
				'post_content' => '[amgt_society_rules]',
				'post_status' => 'publish',
				'post_type' => 'page',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_category' => array(1),
				'post_parent' => 0 );
		

		$curr_created = wp_insert_post( $curr_page );
		update_option( 'amgt_rules_page', $curr_created );

	}
}

//GET SOCITY RULES PAGE FUNCTION //
function amgt_society_rules_page()
{
	$args = array('post_type' => 'amgt_society_rules','post_status' => 'publish' ); 
	$rules_array = get_posts( $args );
	
	?>
	<ol>
	<?php foreach($rules_array as $rule)
	{?>
		<div class="col-sm-10">
			<li>
			<h4><?php echo esc_attr($rule->post_title);?></h4>
			<p><?php echo esc_attr($rule->post_content);?></p>
			</li>
			
		</div>
	<?php }?>
	</ol>
<?php  
}
//INSTALL GALLARY PAGE FUNCTION//
function amgt_install_gallary_page()
{
	if ( !get_option('amgt_gallary_page') ) {
		
		$curr_page = array(
				'post_title' => esc_html__('Apartment Gallery', 'apartment_mgt'),
				'post_content' => '[amgt_photo_gallary]',
				'post_status' => 'publish',
				'post_type' => 'page',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_category' => array(1),
				'post_parent' => 0 );
		

		$curr_created = wp_insert_post( $curr_page );
		update_option( '	', $curr_created );

	}
}

function amgt_photo_gallary_page($atts)
{	
?>
	<style>
	.fancybox.col-md-4.amgt-gallary-images {
	  border-bottom: 0 none !important;
	}
	</style>
	<script>	
	jQuery(document).ready(function($){
          $('a.fancybox').fancybox();
		  /* Apply fancybox to multiple items */
      });
</script>	
	<?php if(!empty($atts))
	{
	$atts = shortcode_atts( array(
		'id' => $atts['id']
		), $atts, 'photogallaryCode' );
		$gallary = get_post( $atts['id'] );
	?>
	<ol>
		<div class="col-md-10">
			<h4><?php //echo $gallary->post_title;?></h4>
			<p><?php $gallary_photos=get_post_meta($gallary->ID,'amgtfld_gallery',true); ?> 
			<?php array_pop($gallary_photos['image_url']);?>
			<?php $i=0;
			foreach($gallary_photos['image_url'] as $photo_url){ ?>
				
					<a href="<?php echo esc_attr($photo_url);?>" class="fancybox col-md-4 amgt-gallary-images" rel="group_1" >
						<img src="<?php echo esc_attr($photo_url);?>">
					</a>
			<?php 
			$i+=1;
			} ?>
			</p>
			
		</div>
	</ol>
<?php  
    }
	else
	{ 
		$args = array('post_type' => 'amgt_photo_gallary','post_status' => 'publish' ); 
		$gallary_array=get_posts($args);
	?>
			<ol>
				<?php foreach($gallary_array as $gallary)
				{ ?>
					<div class="col-sm-10">
						<li>
						<h4><?php echo esc_attr($gallary->post_title);?></h4>
						<p><?php $gallary_photos=get_post_meta($gallary->ID,'amgtfld_gallery',true); 
						
						?> 
						<table><tr>
						<?php 
						foreach($gallary_photos['image_url'] as $photo_url){?>
							<td><img src="<?php echo esc_attr($photo_url);?>"></td>
						<?php } ?>
						</tr>
						</table>
						</p>
						</li>
						
					</div>
					<?php
				} ?>
	
	        </ol>
		<?php
	}
}
add_action('init', 'amgt_session_manager'); 
function amgt_session_manager() {
	if (!session_id())
	{
		session_start();		
		if(!isset($_SESSION['amgt_verify']))
		{			
			$_SESSION['amgt_verify'] = '';
		}		
	}
	
}

function amgt_logout(){
if(isset($_SESSION['amgt_verify']))
{ 
   unset($_SESSION['amgt_verify']);}
}
add_action('wp_logout','amgt_logout');
add_action('init','amgt_setup');
function amgt_setup()
{
	$is_cmgt_pluginpage = amgt_is_amgtpage();
	$is_verify = false;
	if(!isset($_SESSION['amgt_verify']))
		$_SESSION['amgt_verify'] = '';
	$server_name = $_SERVER['SERVER_NAME'];
	$is_localserver = amgt_chekserver($server_name);
	if($is_localserver)
	{		
		return true;
	}
	if($is_cmgt_pluginpage)
	{	
		if($_SESSION['amgt_verify'] == '')
		{		
			if( get_option('licence_key') && get_option('amgt_setup_email'))
			{
				$domain_name = $_SERVER['SERVER_NAME'];
				$licence_key = get_option('licence_key');
				$email = get_option('amgt_setup_email');
				$result = amgt_check_productkey($domain_name,$licence_key,$email);
				$is_server_running = amgt_check_ourserver();
				if($is_server_running)
					$_SESSION['amgt_verify'] =$result;
				else
					$_SESSION['amgt_verify'] = '0';
				$is_verify = amgt_check_verify_or_not($result);			
			}
		}
	}
	$is_verify = amgt_check_verify_or_not($_SESSION['amgt_verify']);
	if($is_cmgt_pluginpage)
		if(!$is_verify)
		{			
			if($_REQUEST['page'] != 'amgt-amgt_setup')
				wp_redirect(admin_url().'admin.php?page=amgt-amgt_setup');				
		}
}
//-------------- UNIT CATEGORY POST -------------------//
function amgt_install_unit_category_post()
{
	global $wpdb;
    $post_office = $wpdb->get_var("SELECT count(post_title) FROM $wpdb->posts WHERE post_title like 'office' AND post_type like 'unit_category'");
    if($post_office == '0'){
	$result = wp_insert_post( array(

			'post_status' => 'publish',

			'post_type' => 'unit_category',

			'post_title' => 'Office') );
	}		
    $post_residential = $wpdb->get_var("SELECT count(post_title) FROM $wpdb->posts WHERE post_title like 'residential' AND post_type like 'unit_category'");	
	if($post_residential == '0'){
	$result = wp_insert_post( array(

			'post_status' => 'publish',

			'post_type' => 'unit_category',

			'post_title' => 'Residential') );
	}
	$post_unit_member = $wpdb->get_var("SELECT count(post_title) FROM $wpdb->posts WHERE post_title like 'Owner' AND post_type like 'member_category'");
    if($post_unit_member == '0'){
	$result = wp_insert_post( array(

			'post_status' => 'publish',

			'post_type' => 'member_category',

			'post_title' => 'Owner') );
	}		
    $post_member = $wpdb->get_var("SELECT count(post_title) FROM $wpdb->posts WHERE post_title like 'Tenant' AND post_type like 'member_category'");	
	if($post_member == '0'){
	$result = wp_insert_post( array(

			'post_status' => 'publish',

			'post_type' => 'member_category',

			'post_title' => 'Tenant') );
	}
	 
	
}

//Inatall Table FUNCTION 
function amgt_install_tables()
{
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	global $wpdb;	
	$table_amgt_assets = $wpdb->prefix . 'amgt_assets';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_assets ." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `assets_no` varchar(100) NOT NULL,
				  `assets_name` varchar(100) NOT NULL,
				  `vender_name` varchar(100) NOT NULL,
				  `assets_cat_id` int(11) NOT NULL,
				  `location` varchar(255) NOT NULL,
				  `purchage_date` date NOT NULL,
				  `assets_cost` varchar(20) NOT NULL,
				  `status` varchar(20) NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";
	
		$wpdb->query($sql);
		
	$table_amgt_charges_payments = $wpdb->prefix . 'amgt_charges_payments';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_charges_payments ." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `recuring_charges_id` int(11) NOT NULL,
				  `building_id` int(11) NOT NULL,
				  `unit_cat_id` int(11) NOT NULL,
				  `unit_name` varchar(100) NOT NULL,
				  `member_id` int(11) NOT NULL,
				  `charges_type_id` int(11) NOT NULL,
				  `charges_payment` text NOT NULL,
				  `description` text NOT NULL,
				  `discount_amount` varchar(10) NOT NULL,
				  `amount` varchar(10) NOT NULL,
				  `tax_amount` varchar(10) NOT NULL,
				  `total_amount` varchar(10) NOT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `chargis_status` varchar(20),
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
		
		
		$amgt_recuring_charges_payments = $wpdb->prefix . 'amgt_recuring_charges_payments';
		$sql = "CREATE TABLE IF NOT EXISTS ".$amgt_recuring_charges_payments ." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `building_id` int(11) NOT NULL,
				  `unit_cat_id` int(11) NOT NULL,
				  `unit_name` varchar(100) NOT NULL,
				  `member_id` int(11) NOT NULL,
				  `invoice_options` varchar(100) NOT NULL,
				  `charges_type_id` int(11) NOT NULL,
				  `charges_payment` text NOT NULL,
				  `description` text NOT NULL,
				  `amount` varchar(10) NOT NULL,
				  `discount_amount` varchar(10) NOT NULL,
				  `tax_amount` varchar(10) NOT NULL,
				  `total_amount` varchar(10) NOT NULL,
				  `amgt_charge_period` varchar(10) NOT NULL,
				  `start_date` date NOT NULL,
				  `end_date` date NOT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
		
	$table_amgt_checkin_entry = $wpdb->prefix . 'amgt_checkin_entry';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_checkin_entry ." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `gate_id` int(11) NOT NULL,
				  `exit_gate_id` int(11) NOT NULL,
				  `checkin_type` varchar(50) NOT NULL,
				  `visitor_name` varchar(255) NOT NULL,
				  `member_id` int(11) NOT NULL,
				  `badge_id` varchar(100) NOT NULL,
				  `mobile` int(11) NOT NULL,
				  `vehicle_number` varchar(100) NOT NULL,
				  `reason_id` int(11) NOT NULL,
				  `building_id` int(11) NOT NULL,
				  `unit_cat` int(11) NOT NULL,
				  `unit_name` varchar(50) NOT NULL,
				  `checkin_date` date NOT NULL,
				  `checkin_time` varchar(50) NOT NULL,
				  `checkout_time` varchar(100) NOT NULL,
				  `description` text NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
		
		
	
		
	$table_amgt_complaints = $wpdb->prefix . 'amgt_complaints';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_complaints." (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				  `complaint_nature` varchar(50) NOT NULL,
				  `complaint_member_id` int(11) NOT NULL,
				  `complaint_type` varchar(50) NOT NULL,
				  `complaint_cat` varchar(50) NOT NULL,
				  `complaint_status` varchar(50) NOT NULL,
				  `complaint_description` text NOT NULL,
				  `time` varchar(50),
				  `resolution` text NOT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
	 
	  $table_amgt_created_invoice_list = $wpdb->prefix . 'amgt_created_invoice_list';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_created_invoice_list ." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `charges_id` int(11) NOT NULL,				  
				  `member_id` int(11) NOT NULL,
				  `invoice_no` varchar(10) NOT NULL,
				  `charges_type_id` int(11) NOT NULL,				  
				  `description` text NOT NULL,
				  `discount_amount` varchar(10) NOT NULL,
				  `amount` varchar(10) NOT NULL,
				  `tax_amount` varchar(10) NOT NULL,
				  `total_amount` varchar(10) NOT NULL,
				  `due_amount` varchar(10) NOT NULL,
				  `paid_amount` varchar(10) NOT NULL,
				  `payment_status` varchar(10) NOT NULL,				 
				  `amgt_charge_period` varchar(10) NOT NULL,
				  `charges_payment` text NOT NULL,
				  `start_date` date NOT NULL,
				  `end_date` date NOT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `status` varchar(20) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql); 
		
		 $table_amgt_invoice_payment_history = $wpdb->prefix . 'amgt_invoice_payment_history';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_invoice_payment_history." (
				 `id` int(11) NOT NULL AUTO_INCREMENT,		
				 `invoice_id` int(11),	
				  `member_id` int(11) NOT NULL,
				  `date` date,	
				 `amount` double,				 
				 `payment_method` varchar(255),
				  PRIMARY KEY (`id`)
				)DEFAULT CHARSET=utf8";					
			$wpdb->query($sql);
		
	$table_amgt_document = $wpdb->prefix . 'amgt_document';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_document ." (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				  `doc_title` varchar(255) NOT NULL,
				  `building_id` int(11) NOT NULL,
				  `unit_cat_id` int(11) NOT NULL,
				  `unit_name` varchar(100) NOT NULL,
				  `member_id` int(11) NOT NULL,
				  `document_content` varchar(255) NOT NULL,
				  `visibility` varchar(100) NOT NULL,
				  `description` text NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql); 
		
	$table_amgt_events = $wpdb->prefix . 'amgt_events';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_events ." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `event_title` varchar(100) NOT NULL,
				  `description` text NOT NULL,
				  `start_date` date NOT NULL,
				  `start_time` varchar(100) NOT NULL,
				  `end_date` date NOT NULL,
				  `end_time` varchar(100) NOT NULL,
				  `visibility` varchar(100) NOT NULL,
				  `event_doc` varchar(255) NOT NULL,
				  `publish_status` varchar(20) NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,				  
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
		
	$table_amgt_facility = $wpdb->prefix . 'amgt_facility';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_facility ." (
				 `facility_id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `facility_name` varchar(500) NOT NULL,
				  `facility_charge` double NOT NULL,
				  `charge_per` varchar(20) NOT NULL,
				  `allow_booking_multiple_base` int(11) NOT NULL,
				  `created_date` datetime NOT NULL,
				  `created_by` bigint(20) NOT NULL,
				  PRIMARY KEY (`facility_id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
	
	$table_amgt_facility_booking = $wpdb->prefix . 'amgt_facility_booking';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_facility_booking ." (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				  `facility_id` int(11) NOT NULL,
				  `activity_id` int(11) NOT NULL,
				  `period_type` varchar(20) NOT NULL,
				  `start_date` date NOT NULL,
				  `end_date` date NOT NULL,
				  `start_time` varchar(20) NOT NULL,
				  `end_time` varchar(20) NOT NULL,
				  `booking_cost` varchar(20) NOT NULL,
				  `book_on_behalf_of` varchar(255) NOT NULL,
				  `status` int(11) NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
	$table_amgt_gates = $wpdb->prefix . 'amgt_gates';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_gates ." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `gate_name` varchar(100) NOT NULL,
				  `for_entry` varchar(50) DEFAULT NULL,
				  `for_exit` varchar(50) DEFAULT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
	
	
		$table_amgt_generat_invoice = $wpdb->prefix . 'amgt_generat_invoice';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_generat_invoice ." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `building_id` int(11) NOT NULL,
				  `unit_cat_id` int(11) NOT NULL,
				  `unit_name` varchar(100) NOT NULL,
				  `member_id` int(11) NOT NULL,
				  `invoice_options` varchar(100) NOT NULL,
				  `charges_type_id` int(11) NOT NULL,
				  `charges_calculate_by` text NOT NULL,
				  `charges_payment` text NOT NULL,
				  `description` text NOT NULL,
				  `amount` varchar(10) NOT NULL,
				  `discount_amount` varchar(10) NOT NULL,
				  `tax_amount` varchar(10) NOT NULL,
				  `total_amount` varchar(10) NOT NULL,
				  `amgt_charge_period` varchar(10) NOT NULL,				 
				  `delete_status` varchar(10) NOT NULL,				 
				  `created_date` date NOT NULL,
				  `invoice_start_date` date NOT NULL,
				  `invoice_end_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
		
		
	$table_amgt_income_expense = $wpdb->prefix . 'amgt_income_expense';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_income_expense ." (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				  `type` varchar(10) NOT NULL,
				  `type_id` int(11) NOT NULL,
				  `bill_date` date NOT NULL,
				  `payment_date` date NOT NULL,
				  `amount` varchar(10) NOT NULL,
				  `vender_name` varchar(255) NOT NULL,
				  `invoice_id` int(11) NOT NULL,
				  `member_id` int(11) NOT NULL,
				  `description` text NOT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
		
	$table_amgt_inventory = $wpdb->prefix . 'amgt_inventory';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_inventory ." (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				  `inventory_name` varchar(100) NOT NULL,
				  `inventory_unit_cat` int(11) NOT NULL,
				  `quentity` varchar(20) NOT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
	
	$table_amgt_message = $wpdb->prefix . 'amgt_message';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_message ." (
				 `message_id` int(11) NOT NULL AUTO_INCREMENT,
				  `sender` int(11) NOT NULL,
				  `receiver` int(11) NOT NULL,
				  `msg_date` datetime NOT NULL,
				  `msg_subject` varchar(150) NOT NULL,
				  `message_body` text NOT NULL,
				  `post_id` int(11) NOT NULL,
				  `msg_status` int(11) NOT NULL,
				  PRIMARY KEY (`message_id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
		
	$table_amgt_message_replies = $wpdb->prefix . 'amgt_message_replies';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_message_replies ." (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				  `message_id` int(11) NOT NULL,
				  `sender_id` int(11) NOT NULL,
				  `receiver_id` int(11) NOT NULL,
				  `message_comment` text NOT NULL,
				  `created_date` datetime NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
		
	$table_amgt_notice = $wpdb->prefix . 'amgt_notice';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_notice ." (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				  `notice_title` varchar(255) NOT NULL,
				  `notice_type` varchar(50) NOT NULL,
				  `notice_doc` varchar(255) NOT NULL,
				  `description` text NOT NULL,
				  `valid_date` date NOT NULL,
				  `status` varchar(50) NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
		
	$table_amgt_parking = $wpdb->prefix . 'amgt_parking';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_parking ." (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				  `sloat_id` int(11) NOT NULL,
				  `vehicle_number` varchar(100) NOT NULL,
				  `vehicle_model` varchar(100) NOT NULL,
				  `RFID` varchar(100) NOT NULL,
				  `vehicle_type` int(11) NOT NULL,
				  `building_id` int(11) NOT NULL,
				  `unit_cat_id` int(11) NOT NULL,
				  `unit_name` varchar(100) NOT NULL,
				  `member_id` int(11) NOT NULL,
				  `from_date` date NOT NULL,
				  `to_date` date NOT NULL,
				  `status` varchar(20) NOT NULL,
				  `description` text NOT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
		
	$table_amgt_residential_units = $wpdb->prefix . 'amgt_residential_units';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_residential_units ." (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				  `building_id` int(11) NOT NULL,
				  `unit_cat_id` int(11) NOT NULL,				  
				  `units` text NOT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
		
	$table_amgt_serivce = $wpdb->prefix . 'amgt_serivce';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_serivce ." (
				 `service_id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `service_name` varchar(500) NOT NULL,
				  `service_provider` varchar(500) NOT NULL,
				  `contact_number` varchar(50) NOT NULL,
				  `mobile_number` varchar(50) NOT NULL,
				  `email` varchar(200) NOT NULL,
				  `address` text NOT NULL,
				  `created_date` datetime NOT NULL,
				  `created_by` bigint(20) NOT NULL,
				  `status` int(11) NOT NULL,
				  PRIMARY KEY (`service_id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
	
	$table_amgt_sloats = $wpdb->prefix . 'amgt_sloats';
	
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_sloats ." (
				`id` int(11) NOT NULL AUTO_INCREMENT,
			  `sloat_name` varchar(100) NOT NULL,
			  `sloat_type` varchar(20) NOT NULL,
			  `comment` text NOT NULL,
			  `created_date` date NOT NULL,
			  `created_by` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
				
		$wpdb->query($sql);
		
		
		$table_amgt_taxes = $wpdb->prefix . 'amgt_taxes';	
	
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_taxes." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `tax_title` varchar(255) NOT NULL,
				  `tax` int(11) NOT NULL,
				  `created_at` int(11) NOT NULL,
				  `is_deleted` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
				
		$wpdb->query($sql);
		
	$table_amgt_invoice_tax = $wpdb->prefix . 'amgt_invoice_tax';	
	
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_invoice_tax." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `invoice_id` int(11) NOT NULL,
				  `member_id` int(11) NOT NULL,
				  `tax_id` int(11) NOT NULL,
				  `tax` float NOT NULL,
				  `tax_amount` double NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
				
		$wpdb->query($sql);	
		
		
	$table_amgt_building_cat = $wpdb->prefix . 'amgt_building_cat';	
	
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_building_cat." (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `building_id` int(11) NOT NULL,
			  `building_cat_id` int(11) NOT NULL,
			  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `created_by` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
				
		$wpdb->query($sql);	
		
		
		$table_maintenance_settings = $wpdb->prefix . 'maintenance_settings';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_maintenance_settings ." (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				  `building_id` int(11) NOT NULL,
				  `maintenance_title` varchar(100) NOT NULL,
				  `maintenance_charges` varchar(100) NOT NULL,
				  `maintenance_charge_period` varchar(100) NOT NULL,
				  `created_date` date NOT NULL,
				  `created_by` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
		$wpdb->query($sql);
		
		$table_amgt_maintence_tax = $wpdb->prefix . 'amgt_maintence_tax';	
	
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_maintence_tax." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `maintence_setings_id` int(11) NOT NULL,
				  `building_id` int(11) NOT NULL,
				  `tax_id` int(11) NOT NULL,
				  `tax` float NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
				
		$wpdb->query($sql);	
		$table_amgt_unit_occupied_history = $wpdb->prefix . 'amgt_unit_occupied_history';	
	
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_amgt_unit_occupied_history." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `building_id` int(11) NOT NULL,
				  `unit_cat_id` int(11) NOT NULL,
				  `unit_name` varchar(50) NOT NULL,
				  `member_name` varchar(50) NOT NULL,
				  `member_contact_details` varchar(255) NOT NULL,
				  `occupied_from_date` date NOT NULL,
				  `occupied_to_date` date,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8";	
				
		$wpdb->query($sql);	
		
	 $amgt_building_cat = $wpdb->get_results("SELECT *from $table_amgt_building_cat");
		if(empty($amgt_building_cat))
		{
			$table_postmeta = $wpdb->prefix. 'postmeta';
			$unit_catdatalist = $wpdb->get_results("select *from $table_postmeta where meta_key='related_building_id'");	
			if(!empty($unit_catdatalist))
			{
				foreach($unit_catdatalist as $retrieve_data)
				{
					$created_by = get_current_user_id();
									
					$success = $wpdb->insert($table_amgt_building_cat,array('building_id'=>$retrieve_data->meta_value,
												'building_cat_id'=>$retrieve_data->post_id,
												'created_by'=>$created_by));
				}
			}		
		}
		$table_amgt_checkin_entry = $wpdb->prefix . 'amgt_checkin_entry';		
		
		$sql="ALTER TABLE ".$table_amgt_checkin_entry." CHANGE `mobile` `mobile` VARCHAR(20) NOT NULL";
		$wpdb->query($sql);
		
		//aded new filed in add visitor//
		$visiters_value='visiters_value';
		if (!in_array($visiters_value, $wpdb->get_col( "DESC " . $table_amgt_checkin_entry, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_checkin_entry  ADD   $visiters_value  text");}
			
        $entery_status='status';
		if (!in_array($entery_status, $wpdb->get_col( "DESC " . $table_amgt_checkin_entry, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_checkin_entry  ADD   $entery_status  int(11)");}	
			
		//end//
		$table_amgt_created_invoice_list = $wpdb->prefix . 'amgt_created_invoice_list';
		$sql="ALTER TABLE " . $table_amgt_created_invoice_list." CHANGE `payment_status` `payment_status` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
		$wpdb->query($sql);
		$table_amgt_invoice_payment_history = $wpdb->prefix . 'amgt_invoice_payment_history';
		$description_history='description';
		if (!in_array($description_history, $wpdb->get_col( "DESC " . $table_amgt_invoice_payment_history, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_invoice_payment_history  ADD   $description_history  text");}	

		$table_amgt_created_invoice_list = $wpdb->prefix . 'amgt_created_invoice_list';
		$transaction_id='transaction_id';
		$charges_id='charges_id';
		$payment_method='payment_method';
		
		$charges_type_id='charges_type_id';
		$description='description';
		$discount_amount='discount_amount';
		$amount='amount';
		$tax_amount='tax_amount';
		$total_amount='total_amount';
		$due_amount='due_amount';
		$amgt_charge_period='amgt_charge_period';
		$charges_payment='charges_payment';
		$start_date='start_date';
		$end_date='end_date';
			
		if (!in_array($charges_type_id, $wpdb->get_col( "DESC " . $table_amgt_created_invoice_list, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_created_invoice_list  ADD   $charges_type_id  int(11)");}	
			
		if (!in_array($description, $wpdb->get_col( "DESC " . $table_amgt_created_invoice_list, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_created_invoice_list  ADD   $description   text");}	
			
		if (!in_array($discount_amount, $wpdb->get_col( "DESC " . $table_amgt_created_invoice_list, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_created_invoice_list  ADD   $discount_amount   varchar(10)");}	
			
		if (!in_array($amount, $wpdb->get_col( "DESC " . $table_amgt_created_invoice_list, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_created_invoice_list  ADD   $amount   varchar(10)");}		
		
		if (!in_array($tax_amount, $wpdb->get_col( "DESC " . $table_amgt_created_invoice_list, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_created_invoice_list  ADD   $tax_amount   varchar(10)");}	
			
		if (!in_array($total_amount, $wpdb->get_col( "DESC " . $table_amgt_created_invoice_list, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_created_invoice_list  ADD   $total_amount  varchar(10)");}	
		
		if (!in_array($due_amount, $wpdb->get_col( "DESC " . $table_amgt_created_invoice_list, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_created_invoice_list  ADD   $due_amount   varchar(10)");}		
			
		if (!in_array($amgt_charge_period, $wpdb->get_col( "DESC " . $table_amgt_created_invoice_list, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_created_invoice_list  ADD   $amgt_charge_period   varchar(10)");}			
		
		if (!in_array($charges_payment, $wpdb->get_col( "DESC " . $table_amgt_created_invoice_list, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_created_invoice_list  ADD   $charges_payment text");}	

		if (!in_array($start_date, $wpdb->get_col( "DESC " . $table_amgt_created_invoice_list, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_created_invoice_list  ADD   $start_date   date");}	

		if (!in_array($end_date, $wpdb->get_col( "DESC " . $table_amgt_created_invoice_list, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_created_invoice_list  ADD   $end_date  date");}		
		
		
		if (!in_array($transaction_id, $wpdb->get_col( "DESC " . $table_amgt_created_invoice_list, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_created_invoice_list  ADD   $transaction_id   varchar(255)");}	
		
		if (!in_array($payment_method, $wpdb->get_col( "DESC " . $table_amgt_created_invoice_list, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_created_invoice_list  ADD   $payment_method   varchar(255)");}
	    
		if (!in_array($charges_id, $wpdb->get_col( "DESC " . $table_amgt_created_invoice_list, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_created_invoice_list  ADD   $charges_id   int(11)");}
		
	   //add fild inganrate invoice_amount
	   $table_amgt_generat_invoice = $wpdb->prefix . 'amgt_generat_invoice';
	    $tax_amount='tax_amount';
	    $total_amount='total_amount';
	    $tax='tax';
	    $member_id='member_id';
	    $invoice_options='invoice_options';
	    $charges_type_id='charges_type_id';
	    $charges_calculate_by='charges_calculate_by';
	    $charges_payment='charges_payment';
	    $discount_amount='discount_amount';
		$amgt_charge_period='amgt_charge_period';
		$delete_status='delete_status';
		$invoice_start_date='invoice_start_date';
		$invoice_end_date='invoice_end_date';
		
		if (!in_array($invoice_start_date, $wpdb->get_col( "DESC " . $table_amgt_generat_invoice, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_generat_invoice  ADD   $invoice_start_date   date");}
		
		if (!in_array($invoice_end_date, $wpdb->get_col( "DESC " . $table_amgt_generat_invoice, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_generat_invoice  ADD   $invoice_end_date   date");}
		
		if (!in_array($delete_status, $wpdb->get_col( "DESC " . $table_amgt_generat_invoice, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_generat_invoice  ADD   $delete_status   varchar(10)");}
		
		if (!in_array($amgt_charge_period, $wpdb->get_col( "DESC " . $table_amgt_generat_invoice, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_generat_invoice  ADD   $amgt_charge_period   varchar(10)");}
		
		if (!in_array($discount_amount, $wpdb->get_col( "DESC " . $table_amgt_generat_invoice, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_generat_invoice  ADD   $discount_amount  varchar(10)");}
		
		if (!in_array($member_id, $wpdb->get_col( "DESC " . $table_amgt_generat_invoice, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_generat_invoice  ADD   $member_id   int(11)");}
		
		if (!in_array($invoice_options, $wpdb->get_col( "DESC " . $table_amgt_generat_invoice, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_generat_invoice  ADD   $invoice_options   varchar(100)");}
		
		if (!in_array($charges_type_id, $wpdb->get_col( "DESC " . $table_amgt_generat_invoice, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_generat_invoice  ADD   $charges_type_id   int(11)");}
		
		if (!in_array($charges_calculate_by, $wpdb->get_col( "DESC " . $table_amgt_generat_invoice, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_generat_invoice  ADD   $charges_calculate_by   text");}
		
		if (!in_array($charges_payment, $wpdb->get_col( "DESC " . $table_amgt_generat_invoice, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_generat_invoice  ADD   $charges_payment   text");}
		
		if (!in_array($tax_amount, $wpdb->get_col( "DESC " . $table_amgt_generat_invoice, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_generat_invoice  ADD   $tax_amount   float(6)");}
		
		if (!in_array($total_amount, $wpdb->get_col( "DESC " . $table_amgt_generat_invoice, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_generat_invoice  ADD   $total_amount   float(6)");}
		if (!in_array($tax, $wpdb->get_col( "DESC " . $table_amgt_generat_invoice, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_generat_invoice  ADD   $tax   varchar(255)");}
			
		$complaint_member_id_field='complaint_member_id';
		$table_amgt_complaints = $wpdb->prefix . 'amgt_complaints';
		if (!in_array($complaint_member_id_field, $wpdb->get_col( "DESC " . $table_amgt_complaints, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_complaints  ADD   $complaint_member_id_field   int(11)");}
		 
		$complaint_time='time';
	
		if (!in_array($complaint_time, $wpdb->get_col( "DESC " . $table_amgt_complaints, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_complaints  ADD   $complaint_time   varchar(50)");}
			
     $complain_date='complain_date';
		if (!in_array($complain_date, $wpdb->get_col( "DESC " . $table_amgt_complaints, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_complaints  ADD   $complain_date  date NOT NULL");}
	 $resolution='resolution';
		if (!in_array($resolution, $wpdb->get_col( "DESC " . $table_amgt_complaints, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_complaints  ADD   $resolution  text NOT NULL");}
			
	$unit_name='unit_name';
	$table_amgt_charges_payments = $wpdb->prefix . 'amgt_charges_payments';
	if (!in_array($unit_name, $wpdb->get_col( "DESC " . $table_amgt_charges_payments, 0 ) )){  $result= $wpdb->query(
	"ALTER     TABLE $table_amgt_charges_payments  ADD   $unit_name  varchar(100)");}
	
	$recuring_charges_id='recuring_charges_id';
	if (!in_array($recuring_charges_id, $wpdb->get_col( "DESC " . $table_amgt_charges_payments, 0 ) )){  $result= $wpdb->query(
	"ALTER     TABLE $table_amgt_charges_payments  ADD   $recuring_charges_id  int(11)");}
	
	$member_id='member_id';
		$table_amgt_invoice_tax = $wpdb->prefix . 'amgt_invoice_tax';	
		if (!in_array($member_id, $wpdb->get_col( "DESC " . $table_amgt_invoice_tax, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_invoice_tax  ADD   $member_id   int(11)");}
		
     $complain_title='complain_title';
		$table_amgt_complain = $wpdb->prefix . 'amgt_complaints';	
		if (!in_array($complain_title, $wpdb->get_col( "DESC " . $table_amgt_complain, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_complain  ADD   $complain_title   varchar(255)");}
			
    $gst_no='gst_no';
		$table_amgt_gst = $wpdb->prefix. 'amgt_unit_occupied_history';	
		if (!in_array($gst_no, $wpdb->get_col( "DESC " . $table_amgt_gst, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_gst  ADD   $gst_no   varchar(255)");}
			
			
	$table_amgt_events = $wpdb->prefix . 'amgt_events';
	$event_doc='event_doc';		
	
	if (!in_array($event_doc, $wpdb->get_col( "DESC " . $table_amgt_events, 0 ) )){  $result= $wpdb->query(
		"ALTER     TABLE $table_amgt_events  ADD   $event_doc  varchar(255)");}	
		
		
		$facility_status='status';
		if (!in_array($facility_status, $wpdb->get_col( "DESC " . $table_amgt_facility_booking, 0 ) )){  $result= $wpdb->query(
			"ALTER     TABLE $table_amgt_facility_booking  ADD   $facility_status  int(11)");}	
}
//---------create cronjob  FUNCTION code----------------
add_filter( 'cron_schedules', 'amgt_minute_remainder' );
function amgt_minute_remainder( $schedules ) 
{
    $schedules['every_minute'] = array(
            'interval'  => 60,
            'display'   => esc_html__('Every minute', 'textdomain' )
    );
    return $schedules;
} 
if ( ! wp_next_scheduled( 'amgt_minute_remainder' ) )
{
    wp_schedule_event( time(), 'every_minute', 'amgt_minute_remainder' );
}  
//add_action( 'amgt_minute_remainder', 'amgt_member_charges_invoice_regenerate' );
add_action( 'init', 'amgt_member_charges_invoice_regenerate' );
function amgt_member_charges_invoice_regenerate()
{
    set_time_limit(0);
    $chargis_option=get_option('apartment_enable_chargis');
    if($chargis_option == 'yes')
    {
		$obj_accounts=new Amgt_Accounts;
		$current = strtotime(date('Y-m-d'));
		$time_minus_1day  = $current - (3600*24);
		$curent_1day_minus = date("Y-m-d", $time_minus_1day);
		$monthaly=$obj_accounts->amgt_get_chargis_monthaly('1',$curent_1day_minus);
		$quarterly=$obj_accounts->amgt_get_chargis_quarterly('3',$curent_1day_minus);
		$yearly=$obj_accounts->amgt_get_chargis_yearly('12',$curent_1day_minus);
		// REGENGENERATE CHARGIS INVOICE FOR MONTHALY//
		if(!empty($monthaly))
		{
			//$invoice_generate_date=array();
			foreach ($monthaly as $recuring_chargis_data)
			{	  			
					global $wpdb;
					$amgt_amgt_created_invoice_list = $wpdb->prefix. 'amgt_created_invoice_list';
					$amgt_generat_invoice = $wpdb->prefix. 'amgt_generat_invoice';
					$amgt_amgt_invoice_tax = $wpdb->prefix. 'amgt_invoice_tax';
					$obj_account=new Amgt_Accounts;
					
					if($recuring_chargis_data->invoice_options=='all_member')
					{
						$member_data=amgt_get_all_member_data();
					}
					elseif($recuring_chargis_data->invoice_options=='Building')	
					{
						$building_id=$recuring_chargis_data->building_id;
						
						$member_data=amgt_get_all_member_data_by_building_id($building_id);
					}
					elseif($recuring_chargis_data->invoice_options=='Unit Category')	
					{
						$building_id=$recuring_chargis_data->building_id;
						$unit_id=$recuring_chargis_data->unit_cat_id;
						
						$member_data=amgt_get_all_member_data_by_building_unit_id($building_id,$unit_id);
					}
					elseif($recuring_chargis_data->invoice_options=='one_member')
					{
						$member_data=array();
						$member_id_by_unit_name=amgt_get_member_id_by_unit_name($recuring_chargis_data->unit_name);
						$member_data[]=$member_id_by_unit_name;			
					}					
					
					if(!empty($member_data))
					{
						require AMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';
						
						//$invoice_data=array();
						foreach ($member_data as $retrieved_data)
						{							
							$invoice_data['charges_id']=$recuring_chargis_data->id;
							$result_invoice_no=$wpdb->get_results("SELECT * FROM $amgt_amgt_created_invoice_list");
							
							if(empty($result_invoice_no))
							{							
								$invoice_no='00001';
							}
							else
							{							
								$result_no=$wpdb->get_row("SELECT invoice_no FROM $amgt_amgt_created_invoice_list where id=(SELECT max(id) FROM $amgt_amgt_created_invoice_list)");
								
								$last_invoice_number=$result_no->invoice_no;
								
								$invoice_length=strlen($result_no->invoice_no);
								if($invoice_length == '9')
								{
									$invoice_no='00001';
								}
								else
								{								
									$invoice_no = str_pad($last_invoice_number+1, 5, 0, STR_PAD_LEFT);
								}	
							} 
							
							if($recuring_chargis_data->invoice_options=='one_member')
							{	
								$invoice_data['member_id']=$retrieved_data;	
							}
							else
							{
								$invoice_data['member_id']=$retrieved_data->ID;	
							}	
							
							$invoice_data['charges_type_id']=$recuring_chargis_data->charges_type_id;
							$invoice_data['invoice_no']=$invoice_no;							
							$invoice_data['description']=$recuring_chargis_data->description;
							$invoice_data['discount_amount']=$recuring_chargis_data->discount_amount;
							$invoice_data['charges_payment']=$recuring_chargis_data->charges_payment;
							$invoice_data['paid_amount']=0;
							$invoice_data['payment_status']='Unpaid';							
							$invoice_data['created_date']=date('Y-m-d');
							$invoice_data['amgt_charge_period']=$recuring_chargis_data->amgt_charge_period;
							
							$start_date=date('Y-m-d');
							$invoice_data['start_date']=$start_date;
							$add_month_to_date=date('Y-m-d', strtotime("+1 months", strtotime($start_date)));	
							$end_date = date('Y-m-d', strtotime("-1 days", strtotime($add_month_to_date)));
							$invoice_data['end_date']=$end_date;
							if($recuring_chargis_data->charges_calculate_by=='fix_charge')
							{	
								$invoice_data['amount']=$recuring_chargis_data->amount;					
								$invoice_data['tax_amount']=$recuring_chargis_data->tax_amount;							
								$invoice_data['total_amount']=$recuring_chargis_data->total_amount;
								$invoice_data['due_amount']=$recuring_chargis_data->total_amount;
							}
							elseif($recuring_chargis_data->charges_calculate_by=='measurement_charge')
							{

								$income_amount=json_decode($recuring_chargis_data->charges_payment);
								$amount=0;
								$member_id=$invoice_data['member_id'];
								$unit=amgt_get_single_member_unit_size($member_id);
								
								foreach ($income_amount as $retrieved_data)
								{								
									$amount=$retrieved_data->amount*$unit;
								}
								
								$amount_after_discount=$amount-$recuring_chargis_data->discount_amount;
								$id_invoice=$recuring_chargis_data->id;
								$tax_entry=$wpdb->get_results("SELECT tax FROM $amgt_amgt_invoice_tax where invoice_id=$id_invoice");								
								
								$tax_amount=0;
								
								foreach ($tax_entry as $tax_data)
								{	
									$tax_amount+=$amount_after_discount/100*$tax_data->tax;
								}						
								$total_amount=$amount_after_discount+$tax_amount;
								$invoice_data['amount']=$amount_after_discount;							
								$invoice_data['tax_amount']=$tax_amount;							
								$invoice_data['total_amount']=$total_amount;
								$invoice_data['due_amount']=$total_amount;								
							}
							
							//Check All ready Generated//
							$check_allready_generated=$obj_accounts->amgt_member_invoice_allready_generated($invoice_data['member_id'],date('Y-m-d'),$end_date);
							
							if($check_allready_generated == '0')
							{								
								$result=$wpdb->insert( $amgt_amgt_created_invoice_list, $invoice_data );
								
								global $wpdb;
								$user_invoiceid = $wpdb->insert_id;	
								//---------Notification send mail code---------------------
								$payment_link='<a href='.home_url().'?apartment-dashboard=user&page=accounts>Payment</a>';
								$retrieved_data=get_userdata($invoice_data['member_id']);
								$to = $retrieved_data->user_email; 
								$subject =get_option('wp_amgt_generate_invoice_subject');
								$apartmentname=get_option('amgt_system_name');
								$subject_search=array('{{apartment_name}}');
								$subject_replace=array($apartmentname);
								$subject=str_replace($subject_search,$subject_replace,$subject);
								$message_content=get_option('wp_amgt_generate_invoice_email_template');
								$search=array('{{member_name}}','{{apartment_name}}','{{Payment Link}}');
								$replace = array($retrieved_data->display_name,$apartmentname,$payment_link);
								$message_content = str_replace($search, $replace, $message_content);
								
								$enable_notofication=get_option('apartment_enable_notifications');
								if($enable_notofication=='yes')
								{
									amgt_send_invoice_generate_mail($to,$subject,$message_content,$user_invoiceid);
								}
							
							}								
						}	
					}	
					if($result)
					{						
						//insert start and end date
						$whereid['id']=$recuring_chargis_data->id;
						$invoice_generate_date['invoice_start_date']=date('Y-m-d');
					    $invoice_generate_date['invoice_end_date']=$end_date;
						$result_update_generate_date=$wpdb->update( $amgt_generat_invoice,$invoice_generate_date ,$whereid);	
					}
			}
		}
		// REGENGENERATE CHARGIS INVOICE FOR QUARTERLY
		if(!empty($quarterly))
		{
			foreach ($quarterly as $recuring_chargis_data)
			{	  			
					global $wpdb;
					$amgt_amgt_created_invoice_list = $wpdb->prefix. 'amgt_created_invoice_list';
					$amgt_generat_invoice = $wpdb->prefix. 'amgt_generat_invoice';
					$amgt_amgt_invoice_tax = $wpdb->prefix. 'amgt_invoice_tax';
					$obj_account=new Amgt_Accounts;
					
					if($recuring_chargis_data->invoice_options=='all_member')
					{
						$member_data=amgt_get_all_member_data();
					}
					elseif($recuring_chargis_data->invoice_options=='Building')	
					{
						$building_id=$recuring_chargis_data->building_id;
						
						$member_data=amgt_get_all_member_data_by_building_id($building_id);
					}
					elseif($recuring_chargis_data->invoice_options=='Unit Category')	
					{
						$building_id=$recuring_chargis_data->building_id;
						$unit_id=$recuring_chargis_data->unit_cat_id;
						
						$member_data=amgt_get_all_member_data_by_building_unit_id($building_id,$unit_id);
					}
					elseif($recuring_chargis_data->invoice_options=='one_member')
					{
						$member_data=array();
						$member_id_by_unit_name=amgt_get_member_id_by_unit_name($recuring_chargis_data->unit_name);
						$member_data[]=$member_id_by_unit_name;				
					}					
					
					if(!empty($member_data))
					{
						require AMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';
						foreach ($member_data as $retrieved_data)
						{							
							$invoice_data['charges_id']=$recuring_chargis_data->id;
							$result_invoice_no=$wpdb->get_results("SELECT * FROM $amgt_amgt_created_invoice_list");
							
							if(empty($result_invoice_no))
							{							
								$invoice_no='00001';
							}
							else
							{							
								$result_no=$wpdb->get_row("SELECT invoice_no FROM $amgt_amgt_created_invoice_list where id=(SELECT max(id) FROM $amgt_amgt_created_invoice_list)");
								
								$last_invoice_number=$result_no->invoice_no;
								
								$invoice_length=strlen($result_no->invoice_no);
								if($invoice_length == '9')
								{
									$invoice_no='00001';
								}
								else
								{								
									$invoice_no = str_pad($last_invoice_number+1, 5, 0, STR_PAD_LEFT);
								}	
							} 
							
							if($recuring_chargis_data->invoice_options=='one_member')
							{	
								$invoice_data['member_id']=$retrieved_data;	
							}
							else
							{
								$invoice_data['member_id']=$retrieved_data->ID;	
							}	
							
							$invoice_data['charges_type_id']=$recuring_chargis_data->charges_type_id;
							$invoice_data['invoice_no']=$invoice_no;							
							$invoice_data['description']=$recuring_chargis_data->description;
							$invoice_data['discount_amount']=$recuring_chargis_data->discount_amount;
							$invoice_data['charges_payment']=$recuring_chargis_data->charges_payment;
							$invoice_data['paid_amount']=0;
							$invoice_data['payment_status']='Unpaid';							
							$invoice_data['created_date']=date('Y-m-d');
							$invoice_data['amgt_charge_period']=$recuring_chargis_data->amgt_charge_period;
							
							$start_date=date('Y-m-d');
							$invoice_data['start_date']=$start_date;
							$add_month_to_date=date('Y-m-d', strtotime("+3 months", strtotime($start_date)));	
							$end_date = date('Y-m-d', strtotime("-1 days", strtotime($add_month_to_date)));	
							$invoice_data['end_date']=$end_date;												
							
							if($recuring_chargis_data->charges_calculate_by=='fix_charge')
							{	
								$invoice_data['amount']=$recuring_chargis_data->amount;					
								$invoice_data['tax_amount']=$recuring_chargis_data->tax_amount;							
								$invoice_data['total_amount']=$recuring_chargis_data->total_amount;
								$invoice_data['due_amount']=$recuring_chargis_data->total_amount;
							}
							elseif($recuring_chargis_data->charges_calculate_by=='measurement_charge')
							{
								$income_amount=json_decode($recuring_chargis_data->charges_payment);
								$amount=0;
								$member_id=$invoice_data['member_id'];
								$unit=amgt_get_single_member_unit_size($member_id);
								
								foreach ($income_amount as $retrieved_data)
								{								
									$amount=$retrieved_data->amount*$unit;
								}
								
								$amount_after_discount=$amount-$recuring_chargis_data->discount_amount;
								$id_invoice=$recuring_chargis_data->id;
								$tax_entry=$wpdb->get_results("SELECT tax FROM $amgt_amgt_invoice_tax where invoice_id=$id_invoice");								
								
								$tax_amount=0;
								
								foreach ($tax_entry as $tax_data)
								{	
									$tax_amount+=$amount_after_discount/100*$tax_data->tax;
								}						
								$total_amount=$amount_after_discount+$tax_amount;
								$invoice_data['amount']=$amount_after_discount;							
								$invoice_data['tax_amount']=$tax_amount;							
								$invoice_data['total_amount']=$total_amount;
								$invoice_data['due_amount']=$total_amount;								
							}
							//Check All ready Generated//
							$check_allready_generated=$obj_accounts->amgt_member_invoice_allready_generated($invoice_data['member_id'],date('Y-m-d'),$end_date);
							
							if($check_allready_generated == '0')
							{
								$result=$wpdb->insert( $amgt_amgt_created_invoice_list, $invoice_data );
									
								global $wpdb;
								$user_invoiceid = $wpdb->insert_id;	
								//---------Notification send mail code---------------------
								$payment_link='<a href='.home_url().'?apartment-dashboard=user&page=accounts>Payment</a>';
								$retrieved_data=get_userdata($invoice_data['member_id']);
								$to = $retrieved_data->user_email; 
								$subject =get_option('wp_amgt_generate_invoice_subject');
								$apartmentname=get_option('amgt_system_name');
								$subject_search=array('{{apartment_name}}');
								$subject_replace=array($apartmentname);
								$subject=str_replace($subject_search,$subject_replace,$subject);
								$message_content=get_option('wp_amgt_generate_invoice_email_template');
								$search=array('{{member_name}}','{{apartment_name}}','{{Payment Link}}');
								$replace = array($retrieved_data->display_name,$apartmentname,$payment_link);
								$message_content = str_replace($search, $replace, $message_content);
								
								$enable_notofication=get_option('apartment_enable_notifications');
								if($enable_notofication=='yes')
								{
									amgt_send_invoice_generate_mail($to,$subject,$message_content,$user_invoiceid);
								}
								
							}
						}	
					}	
					if($result)
					{						
						//insert start and end date
						$whereid['id']=$recuring_chargis_data->id;
						$invoice_generate_date['invoice_start_date']=date('Y-m-d');
						
						$invoice_generate_date['invoice_end_date']=$end_date;
						
						$result_update_generate_date=$wpdb->update( $amgt_generat_invoice,$invoice_generate_date ,$whereid);	
					}
			}
		}
		// REGENGENERATE CHARGIS INVOICE FOR YEARLY
		if(!empty($yearly))
		{
			foreach ($yearly as $recuring_chargis_data)
			{	  			
					global $wpdb;
					$amgt_amgt_created_invoice_list = $wpdb->prefix. 'amgt_created_invoice_list';
					$amgt_generat_invoice = $wpdb->prefix. 'amgt_generat_invoice';
					$amgt_amgt_invoice_tax = $wpdb->prefix. 'amgt_invoice_tax';
					$obj_account=new Amgt_Accounts;
					
					if($recuring_chargis_data->invoice_options=='all_member')
					{
						$member_data=amgt_get_all_member_data();
					}
					elseif($recuring_chargis_data->invoice_options=='Building')	
					{
						$building_id=$recuring_chargis_data->building_id;
						
						$member_data=amgt_get_all_member_data_by_building_id($building_id);
					}
					elseif($recuring_chargis_data->invoice_options=='Unit Category')	
					{
						$building_id=$recuring_chargis_data->building_id;
						$unit_id=$recuring_chargis_data->unit_cat_id;
						
						$member_data=amgt_get_all_member_data_by_building_unit_id($building_id,$unit_id);
					}
					elseif($recuring_chargis_data->invoice_options=='one_member')
					{
						$member_data=array();
						$member_data[]=$recuring_chargis_data->member_id;	
						$member_id_by_unit_name=amgt_get_member_id_by_unit_name($recuring_chargis_data->unit_name);
						$member_data[]=$member_id_by_unit_name;									
					}					
					
					if(!empty($member_data))
					{
						require AMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';
						foreach ($member_data as $retrieved_data)
						{							
							$invoice_data['charges_id']=$recuring_chargis_data->id;
							$result_invoice_no=$wpdb->get_results("SELECT * FROM $amgt_amgt_created_invoice_list");
							
							if(empty($result_invoice_no))
							{							
								$invoice_no='00001';
							}
							else
							{							
								$result_no=$wpdb->get_row("SELECT invoice_no FROM $amgt_amgt_created_invoice_list where id=(SELECT max(id) FROM $amgt_amgt_created_invoice_list)");
								
								$last_invoice_number=$result_no->invoice_no;
								
								$invoice_length=strlen($result_no->invoice_no);
								if($invoice_length == '9')
								{
									$invoice_no='00001';
								}
								else
								{								
									$invoice_no = str_pad($last_invoice_number+1, 5, 0, STR_PAD_LEFT);
								}	
							} 
							
							if($recuring_chargis_data->invoice_options=='one_member')
							{	
								$invoice_data['member_id']=$retrieved_data;	
							}
							else
							{
								$invoice_data['member_id']=$retrieved_data->ID;	
							}	
							
							$invoice_data['charges_type_id']=$recuring_chargis_data->charges_type_id;
							$invoice_data['invoice_no']=$invoice_no;							
							$invoice_data['description']=$recuring_chargis_data->description;
							$invoice_data['discount_amount']=$recuring_chargis_data->discount_amount;
							$invoice_data['charges_payment']=$recuring_chargis_data->charges_payment;
							$invoice_data['paid_amount']=0;
							$invoice_data['payment_status']='Unpaid';							
							$invoice_data['created_date']=date('Y-m-d');
							$invoice_data['amgt_charge_period']=$recuring_chargis_data->amgt_charge_period;
							
							$start_date=date('Y-m-d');
							$invoice_data['start_date']=$start_date;
							$add_month_to_date=date('Y-m-d', strtotime("+12 months", strtotime($start_date)));	
							$end_date = date('Y-m-d', strtotime("-1 days", strtotime($add_month_to_date)));	
							$invoice_data['end_date']=$end_date;
							
							if($recuring_chargis_data->charges_calculate_by=='fix_charge')
							{	
								$invoice_data['amount']=$recuring_chargis_data->amount;					
								$invoice_data['tax_amount']=$recuring_chargis_data->tax_amount;							
								$invoice_data['total_amount']=$recuring_chargis_data->total_amount;
								$invoice_data['due_amount']=$recuring_chargis_data->total_amount;
							}
							elseif($recuring_chargis_data->charges_calculate_by=='measurement_charge')
							{

								$income_amount=json_decode($recuring_chargis_data->charges_payment);
								$amount=0;
								$member_id=$invoice_data['member_id'];
								$unit=amgt_get_single_member_unit_size($member_id);
								
								foreach ($income_amount as $retrieved_data)
								{								
									$amount=$retrieved_data->amount*$unit;
								}
								
								$amount_after_discount=$amount-$recuring_chargis_data->discount_amount;
								$id_invoice=$recuring_chargis_data->id;
								$tax_entry=$wpdb->get_results("SELECT tax FROM $amgt_amgt_invoice_tax where invoice_id=$id_invoice");								
								
								$tax_amount=0;
								
								foreach ($tax_entry as $tax_data)
								{	
									$tax_amount+=$amount_after_discount/100*$tax_data->tax;
								}						
								$total_amount=$amount_after_discount+$tax_amount;
								$invoice_data['amount']=$amount_after_discount;							
								$invoice_data['tax_amount']=$tax_amount;							
								$invoice_data['total_amount']=$total_amount;
								$invoice_data['due_amount']=$total_amount;								
							}
							//Check All ready Generated//
							$check_allready_generated=$obj_accounts->amgt_member_invoice_allready_generated($invoice_data['member_id'],date('Y-m-d'),$end_date);
							
							if($check_allready_generated == '0')
							{
								$result=$wpdb->insert( $amgt_amgt_created_invoice_list, $invoice_data );
									
								global $wpdb;
								$user_invoiceid = $wpdb->insert_id;	
								//---------Notification send mail code---------------------
								$payment_link='<a href='.home_url().'?apartment-dashboard=user&page=accounts>Payment</a>';
								$retrieved_data=get_userdata($invoice_data['member_id']);
								$to = $retrieved_data->user_email; 
								$subject =get_option('wp_amgt_generate_invoice_subject');
								$apartmentname=get_option('amgt_system_name');
								$subject_search=array('{{apartment_name}}');
								$subject_replace=array($apartmentname);
								$subject=str_replace($subject_search,$subject_replace,$subject);
								$message_content=get_option('wp_amgt_generate_invoice_email_template');
								$search=array('{{member_name}}','{{apartment_name}}','{{Payment Link}}');
								$replace = array($retrieved_data->display_name,$apartmentname,$payment_link);
								$message_content = str_replace($search, $replace, $message_content);
								
								$enable_notofication=get_option('apartment_enable_notifications');
								if($enable_notofication=='yes')
								{
									amgt_send_invoice_generate_mail($to,$subject,$message_content,$user_invoiceid);
								}
								
							}
						}	
					}	
					if($result)
					{						
						//insert start and end date
						$whereid['id']=$recuring_chargis_data->id;
						$invoice_generate_date['invoice_start_date']=date('Y-m-d');
						$invoice_generate_date['invoice_end_date']=$end_date;
						$result_update_generate_date=$wpdb->update( $amgt_generat_invoice,$invoice_generate_date ,$whereid);	
					}
			}
		}		
   }
}
add_action('init','amgt_old_user_occupied_by');
function amgt_old_user_occupied_by()
{
	$get_members = array('role' => 'member');
	$membersdata=get_users($get_members);
	
	if(!empty($membersdata))
	{
		foreach ($membersdata as $retrieved_data)
		{			
			
			if(empty($retrieved_data->occupied_by))
			{
				$user_id=$retrieved_data->ID;
				$member_type=$retrieved_data->member_type;
		
				if($member_type=='Owner' || $member_type=='tenant')
				{	
					update_user_meta( $user_id, 'occupied_by',$member_type);
				}					
			}
		}
	}		
	
}
add_action('init','amgt_old_chargis_add_into_invoice_table');
function amgt_old_chargis_add_into_invoice_table()
{
	global $wpdb;
	$table_amgt_created_invoice_list = $wpdb->prefix. 'amgt_created_invoice_list';	
	$table_amgt_charges_payments = $wpdb->prefix. 'amgt_charges_payments';	
	
	$chargis_status='chargis_status';
		
	if (!in_array($chargis_status, $wpdb->get_col( "DESC " . $table_amgt_charges_payments, 0 ) )){  $result= $wpdb->query(
	"ALTER TABLE $table_amgt_charges_payments  ADD   $chargis_status  varchar(20)");} 
	
	$query="INSERT INTO $table_amgt_created_invoice_list (member_id,charges_payment,payment_status,description,discount_amount,charges_type_id,created_date)
	SELECT member_id,charges_payment,'Fully Paid',description,discount_amount,charges_type_id,created_date FROM $table_amgt_charges_payments where chargis_status IS NULL";
	
	$result=$wpdb->query($query);
	
	if($result)
	{
		$sql = "UPDATE $table_amgt_charges_payments	SET chargis_status='1'";
		$wpdb->query($sql);
	}	
	
}
//add_action( 'init', 'the_dramatist_fire_on_wp_initialization' );
 //// Header Not Set﻿//
function amgt_block_frames() { 
header( 'X-FRAME-OPTIONS: SAMEORIGIN' );
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
}
add_action( 'send_headers', 'amgt_block_frames', 10 );
//-------------------- REMOVE HEADER -----------------//
function amgt_header_remove_function()
{
	
	header_remove("X-Powered-By");
}
/**
 * Authenticate a user, confirming the username and password are valid.
 *
 * @since 2.8.0
 *
 * @param WP_User|WP_Error|null $user     WP_User or WP_Error object from a previous callback. Default null.
 * @param string                $username Username for authentication.
 * @param string                $password Password for authentication.
 * @return WP_User|WP_Error WP_User on success, WP_Error on failure.
 */
add_filter( 'authenticate', 'wp_authenticate_username_password_new', 20, 3 );

function wp_authenticate_username_password_new( $user, $username, $password )
{
	if ( $user instanceof WP_User ) {
		return $user;
	}

	if ( empty( $username ) || empty( $password ) ) {
		if ( is_wp_error( $user ) ) {
			return $user;
		}

		$error = new WP_Error();

		if ( empty( $username ) ) {
			$error->add( 'empty_username', _e('<strong>ERROR</strong>: The username field is empty.' ) );
		}

		if ( empty( $password ) ) {
			$error->add( 'empty_password', _e('<strong>ERROR</strong>: The password field is empty.' ) );
		}

		return $error;
	}

	$user = get_user_by( 'login', $username );

	if ( ! $user ) {
		return new WP_Error(
			'invalid_username',
			_e('<strong>ERROR</strong>: Invalid username.' ) .
			' <a href="' . wp_lostpassword_url() . '">' .
			_e('Lost your password?' ) .
			'</a>'
		);
	}

	/**
	 * Filters whether the given user can be authenticated with the provided $password.
	 *
	 * @since 2.5.0
	 *
	 * @param WP_User|WP_Error $user     WP_User or WP_Error object if a previous
	 *                                   callback failed authentication.
	 * @param string           $password Password to check against the user.
	 */
	$user = apply_filters( 'wp_authenticate_user', $user, $password );
	if ( is_wp_error( $user ) ) {
		return $user;
	}

	if ( ! wp_check_password( $password, $user->user_pass, $user->ID ) ) {
		return new WP_Error(
			'incorrect_password',
			sprintf(
				/* translators: %s: user name */
				_e('<strong>ERROR</strong>: No such username or password.' ),
				'<strong>' . $username . '</strong>'
			) .
			' <a href="' . wp_lostpassword_url() . '">' .
			_e('Lost your password?' ) .
			'</a>'
		);
	}

	return $user;
}

add_filter( 'auth_cookie_expiration', 'amgt_keep_me_logged_in_60_minutes' );
function amgt_keep_me_logged_in_60_minutes( $expirein ) {
    return 7200; // 1 hours
}
//Auto Fill Feature is Enabled  wp login page//
add_action('login_form', function($args) {
  $login = ob_get_contents();
  ob_clean();
  $login = str_replace('id="user_pass"', 'id="user_pass" autocomplete="off"', $login);
  $login = str_replace('id="user_login"', 'id="user_login" autocomplete="off"', $login);
  echo $login; 
}, 9999);
if (!empty($_SERVER['HTTPS'])) {
  function amgt_add_hsts_header($headers) {
    $headers['strict-transport-security'] = 'max-age=31536000; includeSubDomains';
    return $headers;
  }
add_filter('wp_headers', 'amgt_add_hsts_header');
}
?>