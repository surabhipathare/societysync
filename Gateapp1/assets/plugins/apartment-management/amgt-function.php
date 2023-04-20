<?php 
add_filter( 'login_redirect', 'amgt_login_redirect',10, 3 );
// user login redirect to dashboard
function amgt_login_redirect($redirect_to, $request, $user )
{
	if (isset($user->roles) && is_array($user->roles)) 
	{
		$roles = ['Staff-Member','accountant','member','gatekeeper'];
		foreach($roles as $role)
		{
			if (in_array($role, $user->roles))
			{ 
				$redirect_to =  home_url('?apartment-dashboard=user');
				break;
			}
		}
		$roles1 = ['administrator'];
		foreach($roles1 as $role1)
		{
			if (in_array($role1, $user->roles))
			{ 
				$redirect_to =  admin_url () . 'admin.php?page=amgt-apartment_system';	
				break;
			}
		}
	}
	return $redirect_to;
}
//-- Fronted menu titles in Multi language formate
function amgt_change_menutitle($key)
{
	$unit_type=get_option( 'amgt_apartment_type' );
	$menu_titlearray=array(
	//'resident_unit'=>esc_html__('Resident Unit','apartment_mgt'),
	'resident_unit'=>esc_html__(''.$unit_type.' Unit ', 'apartment_mgt'),
	'member'=>esc_html__('Member','apartment_mgt'),
	'committee-member'=>esc_html__('Committee Member','apartment_mgt'),
	'accountant'=>esc_html__('Accountant','apartment_mgt'),
	'staff-members'=>esc_html__('Staff Member','apartment_mgt'),
	'gatekeeper'=>esc_html__('Gatekeeper','apartment_mgt'),
	'visitor-manage'=>esc_html__('Visitor Management','apartment_mgt'),
	'notice-event'=>esc_html__('Notice And Event','apartment_mgt'),
	'complaint'=>esc_html__('Complain','apartment_mgt'),
	'parking-manager'=>esc_html__('Parking Manager','apartment_mgt'),
	'services'=>esc_html__('Services','apartment_mgt'),
	'facility'=>esc_html__('Facility','apartment_mgt'),
	'accounts'=>esc_html__('Accounts','apartment_mgt'),
	'message'=>esc_html__('Message','apartment_mgt'),
	'documents'=>esc_html__('Documents','apartment_mgt'),
	'assets-inventory-tracker'=>esc_html__('Assets / Inventory Tracker','apartment_mgt'),
	'report'=>esc_html__('Report','apartment_mgt'),
	'profile'=>esc_html__('Profile','apartment_mgt'),
	'faq'=>esc_html__('FAQ','apartment_mgt'),
	'society_rules'=>esc_html__('Society Rules','apartment_mgt'),
	'society_rules'=>esc_html__('Society Rules','apartment_mgt'),
	'gallery'=>esc_html__('Gallery','apartment_mgt'),
	);
	return $menu_titlearray[$key];
}
//-- Get Static User Type
function amgt_get_user_roles($user_id)
{
	$user = new WP_User( $user_id );
	if ( !empty( $user->roles ) && is_array( $user->roles ) ) 
	{
	    foreach ( $user->roles as $role )
		{
	      if($role == 'member')
		  {
		    //return  "Member";
			$user_type=esc_html__('Member','apartment_mgt');
		  }
	      else if($role == 'accountant')
		  {
		    //return "Accountant";
			$user_type=esc_html__('Accountant','apartment_mgt');
		  }
	      else if($role == 'staff_member')
		  {
		    //return "Staff Member";
			$user_type=esc_html__('Staff Member','apartment_mgt');
		  }
		  else if($role == 'gatekeeper')
		  {
		    //return "Gatekeeper";
			$user_type=esc_html__('Gatekeeper','apartment_mgt');
		  }
		}
		
		return $user_type;
	}
}
//-- End Get Static User Type

//-- User Roles In Multi language Formate 
function amgt_get_role_name($role)
{
	if($role=='all')
		$role_title=esc_html__('All','apartment_mgt');
	if($role=='staff_member')
		$role_title=esc_html__('Staff Member','apartment_mgt');
	if($role=='committee_member')
		$role_title=esc_html__('Committee Member','apartment_mgt');
	if($role=='member')	
		$role_title=esc_html__('Member','apartment_mgt');
	if($role=='gatekeeper')	
		$role_title=esc_html__('Gatekeeper','apartment_mgt');	
	if($role=='accountant')	
		$role_title=esc_html__('Accountant','apartment_mgt');
	return $role_title;
}
//-- Get Member Type Labels In Multi language 
function amgt_get_member_status_label($status)
{
	if($status=='Owner')
		$status_title=esc_html__('Owner','apartment_mgt');
	if($status=='tenant')
		$status_title=esc_html__('Tenant','apartment_mgt');
	if($status=='owner_family')
		$status_title=esc_html__('Owner Family','apartment_mgt');
	if($status=='tenant_family')	
		$status_title=esc_html__('Tenant Family','apartment_mgt');
	if($status=='care_taker')	
		$status_title=esc_html__('Care Taker','apartment_mgt');
	return $status_title;
}
//-- Get Time Frequency Labels In Multi language  
function amgt_get_frequency_label($frequency)
{
	if($frequency=='monthly')
		$frequency_title=esc_html__('Monthly','apartment_mgt');
	if($frequency=='quarterly')
		$frequency_title=esc_html__('Quarterly','apartment_mgt');
	if($frequency=='half_yearly')
		$frequency_title=esc_html__('Half Yearly','apartment_mgt');
	if($frequency=='yearly')	
		$frequency_title=esc_html__('Yearly','apartment_mgt');
	
	return $frequency_title;
}
//-- Get Sloat Name
function amgt_get_sloat_name($id)
{
	global $wpdb;
	$table_name = $wpdb->prefix. 'amgt_sloats';
	$result = $wpdb->get_row("select *from $table_name where id=".$id);
	if(!empty($result))
		return $result;
}
//-- Get FACILITY Name
function amgt_get_facility_name($id)
{
	global $wpdb;
	$table_name = $wpdb->prefix. 'amgt_facility';
	$result = $wpdb->get_row("select *from $table_name where facility_id=".$id);
	if(!empty($result))
		return $result->facility_name;
}
//-- Get Apartment Gate Name
function amgt_get_gate_name($id)
{
	global $wpdb;
	$table_name = $wpdb->prefix. 'amgt_gates';
	$result = $wpdb->get_row("select *from $table_name where id=".$id);
	if(!empty($result))
		return $result->gate_name;
}
//-- Get Invoice Title
function amgt_get_invoice_title($id)
{
	global $wpdb;
	$table_name = $wpdb->prefix. 'amgt_generat_invoice';
	$result = $wpdb->get_row("select *from $table_name where id=".$id);
	if(!empty($result))
		return $result->title;
}
//-- Get User Display Name
function amgt_get_display_name($id)
{
	if($id)
	{
		$user=get_userdata($id);
		return $user->display_name;
	}
}
//-- Get User Email By ID
function amgt_get_emailid_byuser_id($id)
{
	if (!$user = get_userdata($id))
		return false;
	return $user->data->user_email;
}
//-- Count Committee Members
function amgt_count_committee_members()
{
	$get_members = array('role' => 'member','meta_key' => 'committee_member','meta_value'=> 'yes');
	$users=get_users($get_members);
	if(!empty($users))
	{
		$user=count($users);
		return $user;
	}
	else
	{
		return 0;
	}
}
//-- Count Building Units
function amgt_count_units()
{
	$buildings=amgt_get_all_category('building_category');
	return count($buildings);
}
//-- Count In box Items
function amgt_count_inbox_item($id)
{
	global $wpdb;
	$tbl_name = $wpdb->prefix .'amgt_message';
	$inbox =$wpdb->get_results("SELECT *  FROM $tbl_name where receiver = $id");
	return $inbox;
}
//-- Change Message Read Status
function amgt_change_read_status($id)
{
	global $wpdb;
	$table_name = $wpdb->prefix . "amgt_message";
	$data['msg_status']=1;
	$whereid['message_id']=$id;
	return $retrieve_subject = $wpdb->update($table_name,$data,$whereid);
}
//-- Change Date Formate 
function amgt_change_dateformat($date)
{
	return mysql2date(get_option('date_format'),$date);
}
//-- Get Dynamic Categories
function amgt_get_all_category($model){

	$args= array('post_type'=> $model,'posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');

	$cat_result = get_posts( $args );

	return $cat_result;

}
//-- Get Dynamic Categories by building id
function amgt_get_all_category_by_building_id($building_id)
{

	global $wpdb;
	$amgt_residential_units = $wpdb->prefix. 'amgt_residential_units';
	$unit_catdata = $wpdb->get_results("select DISTINCT(unit_cat_id)  from $amgt_residential_units where building_id=".$building_id);	
	return $unit_catdata;
}
//-- Add Dynamic Category
function amgt_add_categorytype($data)
{
	
	global $wpdb;
	$result = wp_insert_post( array(

			'post_status' => 'publish',

			'post_type' => $data['category_type'],

			'post_title' => $data['category_name']) );

	 $id = $wpdb->insert_id;

	return $id;

}
//-- Get country phone Code
function amgt_get_countery_phonecode($country_name)
{
	//$url = plugins_url( 'countrylist.xml', __FILE__ );
	//$xml=simplexml_load_file(plugins_url( 'countrylist.xml', __FILE__ )) or die("Error: Cannot create object");
	$url=content_url( ).'/plugins/apartment-management/lib/countrylist/countrylist.xml';
	$xml =simplexml_load_string(amgt_get_remote_file($url));
	foreach($xml as $country)
	{
		if($country_name == $country->name)
			return $country->phoneCode;

	}
}
//-- Load All Users in Message Module
function amgt_get_all_user_in_message()
{
	$member=get_users(array('role'=>'member'));
	$accountant = get_users(array('role'=>'accountant'));
	$staff_member = get_users(array('role'=>'staff_member'));
	$committee_member = get_users(array('role'=>'committee_member'));
	$gatekeeper = get_users(array('role'=>'gatekeeper'));
	$admin = get_users(array('role'=>'administrator'));
	
	$all_user = array('member'=>$member,
					'accountant'=>$accountant,
					'staff_member'=>$staff_member,	
					'committee_member'=>$committee_member,
					'gatekeeper'=>$gatekeeper,
					'administrator'=>$admin,);	
	$return_array = array();
	
	foreach($all_user as $key => $value)
	{ 
		if(!empty($value))
		{
		  //echo '<optgroup label="'.$key.'" style = "text-transform: capitalize;">';
		 echo '<optgroup label='. __("$key",'apartment_mgt') .' style = "text-transform: capitalize;">';
			foreach($value as $user)
			{
				
				echo '<option value="'.$user->ID.'">'.$user->display_name.'</option>';
			}
		}
	}	

	$activity_category=amgt_get_all_category('building_category');
	if(!empty($activity_category))
	{
		echo '<optgroup label='.__("Building/Group","apartment_mgt"). ' style = "text-transform: capitalize;">';
		foreach ($activity_category as $retrive_data)
		{
			echo '<option value="grp_'.$retrive_data->ID.'">'.$retrive_data->post_title.'</option>';
		}
	} 
}
//-- Create Login Page For Front-end Users
function amgt_login_link()
{
	$args = array( 'redirect' => site_url() );
	if(isset($_GET['login']) && $_GET['login'] == 'failed')
	{?>
		<div id="login-error" class="login_css">
		  <p><?php esc_html_e('Login failed: You have entered an incorrect Username or password, please try again.','apartment_mgt');?></p>
		</div>
<?php
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
	 $args = array('redirect' => site_url('/?amgt-dashboard=user') );
	 
	 if ( is_user_logged_in() )
	 {
	 	?>
		<a href="<?php echo home_url('/')."?amgt-dashboard=user"; ?>"><i
										class="fa fa-sign-out m-r-xs"></i>
		  <?php esc_html_e('Dashboard','apartment_mgt');?>
		</a>
		<br />
		<a href="<?php echo wp_logout_url(); ?>"><i class="fa fa-sign-out m-r-xs" /><?php esc_html_e('Logout','apartment_mgt');?>
		</a> 
		<?php 
	 }
	 else 
	 {
		wp_login_form( $args );
	 }
}
//-- Get User Role by User ID
function amgt_get_user_role($user_id)
{
	$user = new WP_User( $user_id );
	if ( !empty( $user->roles ) && is_array( $user->roles ) ) 
	{
	foreach ( $user->roles as $role )
		return $role;
	}
}
//-- Check Facility Available OR Not
function amgt_check_facility_availability($facility_id)
{
	global $wpdb;
	$curr_date=date('Y-m-d');
	//echo $curr_time=date('H:ia');
	$table_name = $wpdb->prefix. 'amgt_facility_booking';
	$result=$wpdb->get_results("SELECT * FROM $table_name where end_date>='".$curr_date."' AND facility_id=".$facility_id);	
	return $result;
}
//-- Get Period Title For Generate Invoice 
function amgt_get_period_title($frequency,$period)
{
	$period_title='';
	if($frequency=='monthly')
	{
		
		$period_title=date('F',strtotime("first day of -$period month"));
	
	}
	if($frequency=='quarterly')
	{ 
		if($period=='quirter1')
			$period_title=esc_html__('Quarter I','apartment_mgt');
		if($period=='quirter2')
			$period_title=esc_html__('Quarter II','apartment_mgt');
		if($period=='quirter3')
			$period_title=esc_html__('Quarter III','apartment_mgt');
		if($period=='quirter4')
			$period_title=esc_html__('Quarter IV','apartment_mgt');
		
	}
	if($frequency=='half_yearly')
	{ 	
			if($period=='half1')
			$period_title=esc_html__('I Half','apartment_mgt');
			if($period=='half2')
			$period_title=esc_html__('II Half','apartment_mgt');

	}
	if($frequency=='yearly')
	{  
		$period_title=esc_html__('Yearly','apartment_mgt');
	}
	return $period_title;
}
//-- Get Member List of Building By Passing Building ID 
function amgt_get_building_members($id)
{
	
	$user_query = new WP_User_Query(
		array(
			'meta_key'	  =>	'building_id',
			'meta_value'	=>	$id
		)
	);
	$users = $user_query->get_results();
	if(!empty($users))
		return $users;
}
//-- Get Member List of Unit By Passing Unit Name 
function amgt_get_unit_members($unitname)
{
	$user_query = new WP_User_Query(
		array(
			'meta_key'	  =>	'unit_name',
			'meta_value'	=>	$unitname
		)
	);
	$users = $user_query->get_results();
	if(!empty($users))
		return $users;
}
//-- Get Member List of Unit By Passing Unit Name  AND Building_id
function amgt_get_building_unit_cat_members($building_id,$unit_cat)
{
	
	$user_query = new WP_User_Query(
		array(
			'meta_key'	  =>	'building_id',
			'meta_value'	=>	$building_id,
		),
		array(
			'meta_key'	  =>	'unit_cat_id',
			'meta_value'	=>	$unit_cat,
		)
	);
	$users = $user_query->get_results();
	if(!empty($users))
		return $users;
}
//-- Register Custome Post Type For Apartment Rules
function amgt_register_rules_post_type() {
		
			$labels = array(
				'name'               => _x( 'Society Rules', 'Post Type General Name', 'apartment_mgt' ),
				'singular_name'      => _x( 'Society Rules', 'Post Type Singular Name', 'apartment_mgt' ),
				'menu_name'          => esc_html__('Society Rules', 'apartment_mgt' ),
				'parent_item_colon'  => esc_html__('Parent Item:', 'apartment_mgt' ),
				'all_items'          => esc_html__('Society Rules', 'apartment_mgt' ),
				'view_item'          => esc_html__('View Rules', 'apartment_mgt' ),
				'add_new_item'       => esc_html__('Add New Rules', 'apartment_mgt' ),
				'add_new'            => esc_html__('Add New', 'apartment_mgt' ),
				'edit_item'          => esc_html__('Edit Rules', 'apartment_mgt' ),
				'update_item'        => esc_html__('Update Rules', 'apartment_mgt' ),
				'search_items'       => esc_html__('Search Rules', 'apartment_mgt' ),
				'not_found'          => esc_html__('No rules found', 'apartment_mgt' ),
				'not_found_in_trash' => esc_html__('No rules found in Trash', 'apartment_mgt' ),
			);
		
		$args = array(
				'labels'             => $labels,
				'public'             => true,
                'show_ui'            => true,
				'map_meta_cap'       => true,
				//'show_in_menu'       => 'amgt-apartment_system',
				'show_in_admin_bar'  => true,
				'show_in_nav_menus'  => true,
				'menu_icon'          => 'dashicons-hammer',
			     'supports'           => array( 'title', 'editor', 'thumbnail', 'revisions', 'comments', 'author' ),
				'hierarchical'       => true,
				'rewrite'            => array( 'slug' => 'rules', 'hierarchical' => true, 'with_front' => false )
				//"register_meta_box_cb" => array(__CLASS__,"meta_boxes")
			);
			register_post_type( 'amgt_society_rules', $args );
				
		
	}
//-- Register Custome Post Type For FAQ
function amgt_register_faq_post_type() {
		
			$labels = array(
				'name'               => _x( 'FAQ', 'Post Type General Name', 'apartment_mgt' ),
				'singular_name'      => _x( 'FAQ', 'Post Type Singular Name', 'apartment_mgt' ),
				'menu_name'          => esc_html__('FAQ', 'apartment_mgt' ),
				
				'parent_item_colon'  => esc_html__('Parent Item:', 'apartment_mgt' ),
				'all_items'          => esc_html__('All FAQ', 'apartment_mgt' ),
				'view_item'          => esc_html__('View FAQ', 'apartment_mgt' ),
				'add_new_item'       => esc_html__('Add New FAQ', 'apartment_mgt' ),	
				'add_new'            => esc_html__('Add New', 'apartment_mgt' ),
				'edit_item'          => esc_html__('Edit FAQ', 'apartment_mgt' ),
				'update_item'        => esc_html__('Update FAQ', 'apartment_mgt' ),
				'search_items'       => esc_html__('Search FAQ', 'apartment_mgt' ),
				'not_found'          => esc_html__('No FAQ found', 'apartment_mgt' ),
				'not_found_in_trash' => esc_html__('No FAQ found in Trash', 'apartment_mgt' ),
			);
		
		$args = array(
				'labels'             => $labels,
				'public'             => true,
                'show_ui'            => true,
				'map_meta_cap'       => true,
				//'show_in_menu'       => 'amgt-apartment_system',
				'show_in_admin_bar'  => true,
				'show_in_nav_menus'  => true,
				'menu_icon'          => 'dashicons-format-status',
			     'supports'           => array( 'title', 'editor', 'thumbnail', 'revisions', 'comments', 'author' ),
				'hierarchical'       => true,
				'rewrite'            => array( 'slug' => 'faq', 'hierarchical' => true, 'with_front' => false )
				//"register_meta_box_cb" => array(__CLASS__,"meta_boxes")
			);
			register_post_type( 'amgt_FAQ', $args );
				
		
	}
//-- Register Custome Post Type For Photo Gallery
function amgt_register_photogallary_post_type() {
		
			$labels = array(
				'name'               => _x( 'Photo Gallery', 'Post Type General Name', 'apartment_mgt' ),
				'singular_name'      => _x( 'Photo Gallery', 'Post Type Singular Name', 'apartment_mgt' ),
				'menu_name'          => esc_html__('Photo Gallery', 'apartment_mgt' ),
				'parent_item_colon'  => esc_html__('Parent Item:', 'apartment_mgt' ),
				'all_items'          => esc_html__('Photo Gallery', 'apartment_mgt' ),
				'view_item'          => esc_html__('View Photo Gallery', 'apartment_mgt' ),
				'add_new_item'       => esc_html__('Add New Photo Gallery', 'apartment_mgt' ),
				'add_new'            => esc_html__('Add New', 'apartment_mgt' ),
				'edit_item'          => esc_html__('Edit Photo Gallery', 'apartment_mgt' ),
				'update_item'        => esc_html__('Update Photo Gallery', 'apartment_mgt' ),
				'search_items'       => esc_html__('Search Photo Gallery', 'apartment_mgt' ),
				'not_found'          => esc_html__('No Photo Gallery found', 'apartment_mgt' ),
				'not_found_in_trash' => esc_html__('No Photo Gallery found in Trash', 'apartment_mgt' ),
			);
		
		$args = array(
				'labels'             => $labels,
				'public'             => true,
                'show_ui'            => true,
				'map_meta_cap'       => true,
				
				//'show_in_menu'       => 'amgt-apartment_system',
				'show_in_admin_bar'  => true,
				'show_in_nav_menus'  => true,
				'menu_icon'          => 'dashicons-format-gallery',
			     'supports'           => array( 'title', 'editor', 'thumbnail', 'revisions', 'comments', 'author' ),
				'hierarchical'       => true,
				'rewrite'            => array( 'slug' => 'photo-Gallery', 'hierarchical' => true, 'with_front' => false )
				//"register_meta_box_cb" => array(__CLASS__,"meta_boxes")
			);
			register_post_type( 'amgt_photo_gallery', $args );
				
		
	}
//-- Get Columns of Photo Gallery Post Type
function amgt_Gallery_columns_head($defaults)
 {
    $defaults['Gallery_shortcode'] = 'Shortcode';
    return $defaults;
 }
// SHOW THE FEATURED IMAGE
function amgt_gallery_columns_content($column_name, $post_ID) {
    if ($column_name == 'Gallery_shortcode') {
        echo '[photoGalleryCode id='.$post_ID.']';
    }
}

add_filter('manage_amgt_photo_gallery_posts_columns', 'amgt_gallery_columns_head');
add_action('manage_amgt_photo_gallery_posts_custom_column', 'amgt_gallery_columns_content', 10, 2);	

/*--------Add post metabox-------------------*/
add_action("add_meta_boxes_amgt_photo_gallery","add_meta_gallery",2);	
add_action('save_post_amgt_photo_gallery', 'amgt_save_posts',2);
function add_meta_gallery()
{
	add_meta_box('amgt_meta_gallery','Gallery','amgt_meta_callback_gallery','amgt_photo_gallery','normal','default');			
}
// ADD METABOX FOR GALLERY CUSTOM POST TYPE 
function amgt_meta_callback_gallery()
{ 
 global $post;
	$gallery_data = get_post_meta( $post->ID, 'amgtfld_gallery', true );
 
	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'noncename_so_14445904' );
	?> 
<div id="dynamic_form"> <!--  Dynamic form div  -->
    <div id="field_wrap_1">
    <?php 
    if ( isset( $gallery_data['image_url'] ) && !empty($gallery_data['image_url'][0]) ) 
    { 		
        // for( $i = 0; $i < count( $gallery_data['image_url'] ); $i++ ) 		 
			foreach($gallery_data['image_url'] as $img)
			{
				if($img != "")
				{ ?>				 
					<div class="field_row">
			 
					  <div class="field_left">
						<div class="form_field">
						  <label><?php _e("Image URL","apartment_mgt");?></label>
						  <input type="text"
								 class="meta_image_url"
								 name="amgtfld_gallery[image_url][]"
								 value="<?php esc_html_e($img);//esc_html_e( $gallery_data['image_url'][$i] ); ?>"/>
						</div>
					  </div>
			 
					  <div class="field_right image_wrap">
						<img src="<?php esc_html_e($img);//esc_html_e( $gallery_data['image_url'][$i] ); ?>" height="65" width="65" />
					  </div>
			 
					  <div class="field_right">
						<input class="button" type="button" value="<?php _e("Select Image","apartment_mgt");?>" onclick="add_image(this)" /><br />
						<input class="button" type="button" value="<?php _e("Remove","apartment_mgt");?>" onclick="remove_field(this)" />
					  </div>
			 
					  <div class="clear" /></div> 
	</div>
			<?php
				} 
			}
    } 
    ?>
</div><!--  Enc Dynamic form div  -->
    <div class="display_none" id="master-row-1">
		<div class="field_row">
			<div class="field_left">
				<div class="form_field">
					<label><?php _e("Image URL","apartment_mgt");?></label>
					<input class="meta_image_url" value="" type="text" name="amgtfld_gallery[image_url][]" />
				</div>
			</div>
			<div class="field_right image_wrap">
			</div> 
			<div class="field_right"> 
				<input type="button" class="button" value="<?php _e("Select Image","apartment_mgt");?>" onclick="add_image(this)" />
				<br />
				<input class="button" type="button" value="<?php _e("Remove","apartment_mgt");?>" onclick="remove_field(this)" /> 
			</div>
			<div class="clear"></div>
		</div>
    </div>
    <div id="add_field_row">
      <input class="button" type="button" value="<?php _e("Add Image","apartment_mgt"); ?>" onclick="add_field_row(1)" />
    </div>
  <?php
  echo "<input type='hidden' name='amgtfld_amgt_meta_nonce' value='".wp_create_nonce(basename(__FILE__))."'>";
}	
// SAVE GALLERY CUSTOM POST TYPE 
function amgt_save_posts($post_id)
{
	
	if(@!wp_verify_nonce($_POST['amgtfld_amgt_meta_nonce'], basename(__FILE__)))
	{
		return;
	}	
  else if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
     }
	else if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return false;
		}
	else{
	
		foreach($_POST as $form_field => $value)
		{
			$fld = "";
			$fld = substr($form_field,0,8);
			if($fld == "amgtfld_")
			{				
				update_post_meta($post_id,$form_field,$value);
			}			
		}

	}
	
}
// LOAD APARTMENT DOCUMENTS  
function amgt_amgt_load_documets($file,$type,$nm) 
{
	$parts = pathinfo($_FILES[$type]['name']);
	$inventoryimagename = time()."-".$nm."-"."in".".".$parts['extension'];
	$document_dir = WP_CONTENT_DIR ;
	$document_dir .= '/uploads/apartment_assets/';
	$document_path = $document_dir;
	if (!file_exists($document_path))
	{
		mkdir($document_path, 0777, true);
	}	
    if (move_uploaded_file($_FILES[$type]['tmp_name'], $document_path.$inventoryimagename))
    {
	  $imagepath= $inventoryimagename;	
    }
    return $imagepath;
}
// LOAD DOCUMENTS  FUNCTION
function amgt_load_documets($file,$type,$nm)
{	
$parts = pathinfo($type['name']);

$inventoryimagename = time()."-".rand().".".$parts['extension'];
$document_dir = WP_CONTENT_DIR;
$document_dir .= '/uploads/apartment_assets/';
$document_path = $document_dir;
if (!file_exists($document_path)) {
mkdir($document_path, 0777, true);	
}
$imagepath="";	
if (move_uploaded_file($type['tmp_name'], $document_path.$inventoryimagename)) 
{
$imagepath= $inventoryimagename; 
}
return $imagepath;
}

// DEFINED PRINT FUNCTION ON INIT EVENT
function amgt_print_init()
{
	if(isset($_REQUEST['print']) && $_REQUEST['print'] == 'print' && $_REQUEST['page'] == 'invoice')
	{ ?>
<script>window.onload = function(){ window.print(); };</script>
<?php 
				amgt_invoice_print($_REQUEST['invoice_id'],$_REQUEST['invoice_type']);
				exit;
	}
	elseif(isset($_REQUEST['print']) && $_REQUEST['print'] == 'print' && $_REQUEST['page'] == 'amgt-visiter-manage')
	{ ?>
			<script>window.onload = function(){ window.print(); };</script>
			<?php 
			amgt_visiter_checkin_details_print($_REQUEST['visitor_checkin_id']);
			exit;
	}
	elseif(isset($_REQUEST['print']) && $_REQUEST['print'] == 'print' && $_REQUEST['page'] == 'visitor-manage')
	{ ?>
			<script>window.onload = function(){ window.print(); };</script>
			<?php 
			amgt_visiter_checkin_details_print($_REQUEST['visitor_checkin_id']);
			exit;
	}
	elseif(isset($_REQUEST['print']) && $_REQUEST['print'] == 'print' && $_REQUEST['page'] == 'payment_receipt')
	{ ?>
	<script>window.onload = function(){ window.print(); };</script>
	<?php 
		amgt_invoice_payment_receipt_print($_REQUEST['invoice_id'],$_REQUEST['member_id']);
		exit;
	}	
}
add_action('init','amgt_print_init');
//GEENERATE  UINVOICE FOR PDF FUNCTION CALL FOR INIT
function amgt_pdf_init()
{
	if (is_user_logged_in ()) 
	{
		if(isset($_REQUEST['invoicepdf']) && $_REQUEST['invoicepdf'] == 'invoicepdf')
		{			
			amgt_invoice_pdf($_REQUEST['invoice_id'],$_REQUEST['invoice_type']);
			exit;
		}	
	}
}
add_action('init','amgt_pdf_init');

/* Setup form submit*/
function amgt_submit_setupform($data)
{
	$domain_name= $data['domain_name'];
	$licence_key = $data['licence_key'];
	$email = $data['enter_email'];		
	$result = amgt_check_productkey($domain_name,$licence_key,$email);	
	if($result == '1')
	{
		$message = 'Please provide correct Envato purchase key.';
			$_SESSION['amgt_verify'] = '1';
	}
	elseif($result == '2')
	{
		$message = 'This purchase key is already registered with the different domain. If have any issue please contact us at sales@dasinfomedia.com';
			$_SESSION['amgt_verify'] = '2';
	}
	elseif($result == '3')
	{
		$message = 'There seems to be some problem please try after sometime or contact us on sales@dasinfomedia.com';
			$_SESSION['amgt_verify'] = '3';
	}
	elseif($result == '4')
	{
		$message = 'Please provide correct Envato purchase key for this plugin.';
			$_SESSION['amgt_verify'] = '1';
	}
	else
	{
		update_option('domain_name',$domain_name,true);
		update_option('licence_key',$licence_key,true);
		update_option('amgt_setup_email',$email,true);
		$message = 'Success fully register';
			$_SESSION['amgt_verify'] = '0';
			
	}		
	$result_array = array('message'=>$message,'amgt_verify'=>$_SESSION['amgt_verify']);
	return $result_array;
}
// CHECK PRODUCT KEY FOR REGISTER LICENCE PRODUCT
function amgt_check_productkey($domain_name,$licence_key,$email)
{
	//$api_server = 'http://license.dasinfomedia.com';
	$api_server = 'license.dasinfomedia.com';
	//$api_server = '192.168.1.22';
	$fp = @fsockopen($api_server,80, $errno, $errstr, 2);
	$location_url = admin_url().'admin.php?page=amgt-apartment_system';
	if (!$fp)
        $server_rerror = 'Down';
    else
        $server_rerror = "up";
	
	if($server_rerror == "up")
	{
		//$url = 'http://192.168.1.22/php/test/index.php';
		$url = 'http://license.dasinfomedia.com/index.php';
		$fields = 'result=2&domain='.$domain_name.'&licence_key='.$licence_key.'&email='.$email.'&item_name=apartment';
		//open connection
		$ch = curl_init();
		
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);

		//execute post
		$result = curl_exec($ch);		
		curl_close($ch);
		return $result;
	}
	else
	{
		return '3';
	}		
}

// CHECK PAGE IS APARTMENT PAGE OR NOT
function amgt_is_amgtpage()
{
	$current_page = isset($_REQUEST['page'])?$_REQUEST['page']:'';
	$pos = strrpos($current_page, "amgt-");	
	if($pos !== false)			
	{
		return true;
	}
	return false;
}

// CHECK LOCAL SERVER OR NOT
function amgt_chekserver($server_name)
{
	if($server_name == 'localhost')
	{
		return true;
	}		
}
// CHECK LIVE SERVER(license.dasinfomedia.com) IS DOWN OR NOT
function amgt_check_ourserver()
{
	//$api_server = 'http://license.dasinfomedia.com';
	$api_server = 'license.dasinfomedia.com';
	//$api_server = '192.168.1.22';
	$fp = @fsockopen($api_server,80, $errno, $errstr, 2);
	$location_url = admin_url().'admin.php?page=amgt-amgt_setup';
	if (!$fp)
        return false; /*server down*/
    else
        return true; /*Server up*/
}

// CHECK PAGE IS APARTMENT OR NOT
function amgt_check_verify_or_not($result)
{	
	$server_name = $_SERVER['SERVER_NAME'];
	$current_page = isset($_REQUEST['page'])?$_REQUEST['page']:'';
	$pos = strrpos($current_page, "amgt-");	
	if($pos !== false)			
	{
		if($server_name == 'localhost')
		{
			return true;
		}
		else
		{
			if($result == '0')
			{
				return true;
			}
		}
		return false;
	}	
}
// GET MEASUREMENT TYPE TITLE IN MULTILANGUAGE FORMATE
function amgtGetMeasurementTypeText($measurement)
{
	$measuremetn_array=array('square_feet'=>esc_html__('Square Feet','apartment_mgt'),
							'square_meter'=>esc_html__('Square Meter','apartment_mgt'),
							'square_yards'=>esc_html__('Square Yards','apartment_mgt'));
		return $measuremetn_array[$measurement];					
}
// SEND EMAIL FOR NOTIFICATIONS WITH HTML CONTENT 
function amgtSendEmailNotificationWithHTML($to, $subject, $message_content)
{
	$apartment=get_option('amgt_system_name');
	$headers="";
	$headers .= 'From: '.$apartment.' <noreplay@gmail.com>' . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";
	$enable_notofication=get_option('apartment_enable_notifications');
	if($enable_notofication=='yes')
	{
		wp_mail($to, $subject, $message_content,$headers); 
	}
	
}
// SEND EMAIL FOR NOTIFICATIONS WITH TEXT CONTENT  
function amgtSendEmailNotification($to, $subject, $message_content)
{
	$apartment=get_option('amgt_system_name');
	$headers="";
	$headers .= 'From: '.$apartment.' <noreplay@gmail.com>' . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/plain; charset=iso-8859-1\r\n";
	$enable_notofication=get_option('apartment_enable_notifications');
	if($enable_notofication=='yes')
	{
         wp_mail($to, $subject, $message_content,$headers);  
	}
}
//GET CURENCY FOR COUNTARY FUNCTION
function amgt_get_currency_symbol( $currency = '' )
{			
	switch ( $currency ) {
	case 'AED' :
	$currency_symbol = 'د.إ';
	break;
	case 'AUD' :
	$currency_symbol = '&#36;';
	break;
	case 'CAD' :
	$currency_symbol = 'C&#36;';
	break;
	case 'CLP' :
	case 'COP' :
	case 'HKD' :
	$currency_symbol = '&#36';
	break;
	case 'MXN' :
	$currency_symbol = '&#36';
	break;
	case 'NZD' :
	$currency_symbol = '&#36';
	break;
	case 'SGD' :
	case 'USD' :
	$currency_symbol = '&#36;';
	break;
	case 'BDT':
	$currency_symbol = '&#2547;&nbsp;';
	break;
	case 'BGN' :
	$currency_symbol = '&#1083;&#1074;.';
	break;
	case 'BRL' :
	$currency_symbol = '&#82;&#36;';
	break;
	case 'CHF' :
	$currency_symbol = '&#67;&#72;&#70;';
	break;
	case 'CNY' :
	case 'JPY' :
	case 'RMB' :
	$currency_symbol = '&yen;';
	break;
	case 'CZK' :
	$currency_symbol = '&#75;&#269;';
	break;
	case 'DKK' :
	$currency_symbol = 'kr.';
	break;
	case 'DOP' :
	$currency_symbol = 'RD&#36;';
	break;
	case 'EGP' :
	$currency_symbol = 'EGP';
	break;
	case 'EUR' :
	$currency_symbol = '&euro;';
	break;
	case 'GBP' :
	$currency_symbol = '&pound;';
	break;
	case 'HRK' :
	$currency_symbol = 'Kn';
	break;
	case 'HUF' :
	$currency_symbol = '&#70;&#116;';
	break;
	case 'IDR' :
	$currency_symbol = 'Rp';
	break;
	case 'ILS' :
	$currency_symbol = '&#8362;';
	break;
	case 'INR' :
	$currency_symbol = 'Rs.';
	break;
	case 'ISK' :
	$currency_symbol = 'Kr.';
	break;
	case 'KIP' :
	$currency_symbol = '&#8365;';
	break;
	case 'KRW' :
	$currency_symbol = '&#8361;';
	break;
	case 'MYR' :
	$currency_symbol = '&#82;&#77;';
	break;
	case 'NGN' :
	$currency_symbol = '&#8358;';
	break;
	case 'NOK' :
	$currency_symbol = '&#107;&#114;';
	break;
	case 'NPR' :
	$currency_symbol = 'Rs.';
	break;
	case 'PHP' :
	$currency_symbol = '&#8369;';
	break;
	case 'PLN' :
	$currency_symbol = '&#122;&#322;';
	break;
	case 'PYG' :
	$currency_symbol = '&#8370;';
	break;
	case 'RON' :
	$currency_symbol = 'lei';
	break;
	case 'RUB' :
	$currency_symbol = '&#1088;&#1091;&#1073;.';
	break;
	case 'SEK' :
	$currency_symbol = '&#107;&#114;';
	break;
	case 'THB' :
	$currency_symbol = '&#3647;';
	break;
	case 'TRY' :
	$currency_symbol = '&#8378;';
	break;
	case 'TWD' :
	$currency_symbol = '&#78;&#84;&#36;';
	break;
	case 'UAH' :
	$currency_symbol = '&#8372;';
	break;
	case 'VND' :
	$currency_symbol = '&#8363;';
	break;
	case 'ZAR' :
	$currency_symbol = '&#82;';
	break;
	default :
	$currency_symbol = $currency;
	break;
	}
	return $currency_symbol;

  }
  //IMAGE VALIDATION FUNCTION
  function amgt_check_valid_extension($filename)
  {
	$flag = 2; 
	if($filename != '')
	{
		$flag = 0;
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$valid_extension = ['gif','png','jpg','jpeg',""];
		if(in_array($ext,$valid_extension) )
		{
		$flag = 1;
		}
	}
	return $flag;
  }

function amgt_convert_time($time) 
{
	$timestamp = strtotime( $time ); // Converting time to Unix timestamp
	$offset = get_option( 'gmt_offset' ) * 60 * 60; // Time offset in seconds
	$local_timestamp = $timestamp + $offset;
	$local_time = date_i18n('Y-m-d H:i:s', $local_timestamp);
	return $local_time;
}
  //GET DATA FORMATE FUNCTION
    function amgt_date_formate()
    {
	  $dateFormat=get_option( 'amgt_date_formate' );
	 return $dateFormat;
    }
	
	//GET PHP DATA FORMATE FUNCTION
	function amgt_dateformat_PHP_to_jQueryUI($php_format)
    {
		
	
			$SYMBOLS_MATCHING = array(
			// Day
			'd' => 'dd',
			'D' => 'D',
			'j' => 'd',
			'l' => 'DD',
			'N' => '',
			'S' => '',
			'w' => '',
			'z' => 'o',
			// Week
			'W' => '',
			// Month
			'F' => 'MM',
			'm' => 'mm',
			'M' => 'M',
			'n' => 'm',
			't' => '',
			// Year
			'L' => '',
			'o' => '',
			'Y' => 'yyyy',
			'y' => 'y',
			// Time
			'a' => '',
			'A' => '',
			'B' => '',
			'g' => '',
			'G' => '',
			'h' => '',
			'H' => '',
			'i' => '',
			's' => '',
			'u' => ''
			);
			$jqueryui_format = "";
			$escaping = false;
			for($i = 0; $i < strlen($php_format); $i++)
			{
				$char = $php_format[$i];
				if($char === '\\') // PHP date format escaping character
				{
					$i++;
					if($escaping) $jqueryui_format .= $php_format[$i];
					else $jqueryui_format .= '\'' . $php_format[$i];
					$escaping = true;
				}
				else
				{
					if($escaping) { $jqueryui_format .= "'"; $escaping = false; }
					if(isset($SYMBOLS_MATCHING[$char]))
					$jqueryui_format .= $SYMBOLS_MATCHING[$char];
					else
					$jqueryui_format .= $char;
				}
			}
        return $jqueryui_format;
    }
 //GET DATA FORMATE FOR DATABASE FUNCTION
function amgt_get_format_for_db($date)
{
	if(!empty($date))
	{
		$date = trim($date);
		$new_date = DateTime::createFromFormat("Y-m-d", $date);
		$new_date=$new_date->format('Y-m-d');
	}
	else
	{
		$new_date=null;
	}
	return $new_date;
}
//GET DATA FORMATE  FOR VIEW TIME FUNCTION
function amgt_get_format_for_display($date)
{
	 $date = trim($date);
	 $new_date = DateTime::createFromFormat(amgt_date_formate(), $date);
	 $new_date=$new_date->format(amgt_date_formate());
	 return $new_date;
}

//GET DATA FOR ALL MEMBER FUNCTION
function amgt_get_all_member_data()
{
	 $get_members = array(
						'role' => 'member',
						'meta_query'=>
						 array(
							array(
								'relation' => 'OR',
							array(
								'key'	  =>'occupied_by',
								'value'	=>	'Owner',
								'compare' => '=',
							),
							array(
								'key'	  =>'occupied_by',
								'value'	=>	'Tenant',
								'compare' => '=',
							)
						  )
					   )
					);
	 $membersdata=get_users($get_members);
	 return $membersdata;
}

//GET MEMBER DATA FOR BULDING WISE FUNCTION
function amgt_get_all_member_data_by_building_id($building_id)
{
	$membersdata = get_users(
					array(
						'role' => 'member',						
						'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'building_id',
							'value' =>$building_id,
							'compare' => '='
							),
						array(
							'relation' => 'OR',
						array(
							'key'	  =>'occupied_by',
							'value'	=>	'Owner',
							'compare' => '=',
						),
						array(
							'key'	  =>'occupied_by',
							'value'	=>	'Tenant',
							'compare' => '=',
						)
					  )
					)
				));
	 return $membersdata;
}

//GET MEMBER DATA FOR UNIT WISE FUNCTION
function amgt_get_all_member_data_by_building_unit_id($building_id,$unit_id)
{
	$args = array(
		'role' => 'member',
        'meta_query'=>
         array(
			'relation' => 'AND',
            array(
                'relation' => 'AND',
			array(
				'key'	  =>'building_id',
				'value'	=>	$building_id,
				'compare' => '=',
			),
			array(
				'key'	  =>'unit_cat_id',
				'value'	=>	$unit_id,
				'compare' => '=',
			)
          ),
		  array(
				'relation' => 'OR',
			array(
				'key'	  =>'occupied_by',
				'value'	=>	'Owner',
				'compare' => '=',
			),
			array(
				'key'	  =>'occupied_by',
				'value'	=>	'Tenant',
				'compare' => '=',
			)
		  )
       )
    );

    $membersdata = get_users($args);
	return $membersdata;
}

function amgt_get_single_member_unit_size($member_id)
{
	global $wpdb;
	$table_amgt_residential_units = $wpdb->prefix. 'amgt_residential_units';
	$userdata=get_userdata($member_id);
	
	$building_id=$userdata->building_id;
	$unit_cat_id=$userdata->unit_cat_id;
	$unit_name=$userdata->unit_name;
	$result_unit = $wpdb->get_results("SELECT units FROM $table_amgt_residential_units where building_id=$building_id AND unit_cat_id=$unit_cat_id");
	
	$units_data=array();
	foreach ($result_unit as $unit1)
	{
		$units_data[]=json_decode($unit1->units);
	}
/* 	var_dump($units_data);
	die; */
	if(!empty($units_data))
	{	
		foreach ($units_data as $unit)
		{
			if($unit['0']->entry==$unit_name)
			{
				$memberunitdata=$unit['0']->measurement;
			}
		}
	}	
	return $memberunitdata;
}
//GET MEASURMENT CHARGIS FOR MEMBERWISE FUNCTION
function amgt_get_single_member_measurment_charge($invoice_id)
{
	global $wpdb;
	$table_amgt_generat_invoice= $wpdb->prefix. 'amgt_generat_invoice';
	
	$result_charge = $wpdb->get_row("SELECT charges_payment FROM $table_amgt_generat_invoice where id=$invoice_id");
	
	$charge=array();
	$charge=json_decode($result_charge->charges_payment);
	
	if(!empty($charge))
	{	
		foreach ($charge as $data)
		{			
			$charge=$data->amount;			
		}
	}	
	return $charge;
}

//GETINVOICE CHARGIS FUNCTION
function amgt_get_invoice_charges_calculate_by($invoiceid)
{
	global $wpdb;
	$table_amgt_generat_invoice = $wpdb->prefix. 'amgt_generat_invoice';
	$result = $wpdb->get_row("SELECT charges_calculate_by FROM $table_amgt_generat_invoice where id=$invoiceid");
	return $result;
}

//GET CHARGIS Period BY ID FUNCTION
function amgt_get_charge_period_by_id($invoiceid)
{
	global $wpdb;
	$table_amgt_generat_invoice = $wpdb->prefix. 'amgt_generat_invoice';
	$result = $wpdb->get_row("SELECT amgt_charge_period FROM $table_amgt_generat_invoice where id=$invoiceid");
	return $result->amgt_charge_period;
}

//GET MEMBER UNIT NAME  WISE FUNCTION
function amgt_get_member_id_by_unit_name($unitname)
{
	$args = array(
		'role' => 'member',
        'meta_query'=>
         array(                   
			array(
				'key'	  =>'unit_name',
				'value'	=>	$unitname,
				'compare' => '=',
			)			
          )       
    );

    $membersdata = get_users($args);
	if(!empty($membersdata))
	{	
		foreach ($membersdata as $data)
		{			
			$member_id=$data->ID;			
		}
	}	
	
	return $member_id;
}
//GET REMOTE FILE FUNCTION
function amgt_get_remote_file($url, $timeout = 30)
{
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	curl_close($ch);
	return ($file_contents) ? $file_contents : FALSE;
}

//-------DATA TABLE MULTILANGUAGE-----------
function amgt_datatable_multi_language()
{
$datatable_attr=array("sEmptyTable"=> __("No data available in table","apartment_mgt"),
"sInfo"=>__("Showing _START_ to _END_ of _TOTAL_ entries","apartment_mgt"),
"sInfoEmpty"=>__("Showing 0 to 0 of 0 entries","apartment_mgt"),
"sInfoFiltered"=>__("(filtered from _MAX_ total entries)","apartment_mgt"),
"sInfoPostFix"=> "",
"sInfoThousands"=>",",
"sLengthMenu"=>__("Show _MENU_ entries","apartment_mgt"),
"sLoadingRecords"=>__("Loading...","apartment_mgt"),
"sProcessing"=>__("Processing...","apartment_mgt"),
"sSearch"=>__("Search:","apartment_mgt"),
"sZeroRecords"=>__("No matching records found","apartment_mgt"),
"oPaginate"=>array(
"sFirst"=>__("First","apartment_mgt"),
"sLast"=>__("Last","apartment_mgt"),
"sNext"=>__("Next","apartment_mgt"),
"sPrevious"=>__("Previous","apartment_mgt")
),
"oAria"=>array(
"sSortAscending"=>__(": activate to sort column ascending","apartment_mgt"),
"sSortDescending"=>__(": activate to sort column descending","apartment_mgt")
)
);

return $data=json_encode( $datatable_attr);
}
//strip tags and slashes
function MJamgt_strip_tags_and_stripslashes($post_string)
{
$string = str_replace('&nbsp;', ' ', $post_string);
   $string = html_entity_decode($string, ENT_QUOTES | ENT_COMPAT , 'UTF-8');
   $string = html_entity_decode($string, ENT_HTML5, 'UTF-8');
   $string = html_entity_decode($string);
   $string = htmlspecialchars_decode($string);
   $string = strip_tags($string);
$replace_string=preg_replace('/[^\x00-\x80]|[^0-9a-zA-Z\ \_\,\`\.\'\^\-\&\@\()\{}\|\|\=\%\*\#\!\~\$\+\n]/s', '', $string);
return $replace_string;
}

function MJamgt_browser_javascript_check()
{
	$plugins_url = plugins_url( 'apartment-management/ShowErrorPage.php' );
?>
	<noscript><meta http-equiv="refresh" content="0;URL=<?php echo $plugins_url;?>"></noscript> 
<?php
}
//user role wise access right array
function amgt_get_userrole_wise_access_right_array()
{
	$role = amgt_get_user_role(get_current_user_id());
	if($role=='member')
	{
		$menu = get_option( 'amgt_access_right_member');
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'amgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'amgt_access_right_accountant');
	}
	elseif($role=='gatekeeper')
	{
		$menu = get_option( 'amgt_access_right_gatekeeper');
	}
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{				
			if ($_REQUEST ['page'] == $value['page_link'])
			{				
				return $value;
			}
		}
	}
}
//access right page not access message
function MJamgt_access_right_page_not_access_message()
{
	?>
	<script type="text/javascript">
		$(document).ready(function() 
		{
			"use strict";
			alert('<?php esc_html_e('You do not have permission to perform this operation.','apartment_mgt');?>');
			window.location.href='?dashboard=user';
		});
	</script>
<?php
}
//user role wise access right array In Filter Data
function amgt_get_userrole_wise_filter_access_right_array($page_name)
{
	$role = amgt_get_user_role(get_current_user_id());
	if($role=='member')
	{
		$menu = get_option( 'amgt_access_right_member');
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'amgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'amgt_access_right_accountant');
	}
	elseif($role=='gatekeeper')
	{
		$menu = get_option( 'amgt_access_right_gatekeeper');
	}
		
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{				
			if ($page_name == $value['page_link'])
			{				
				return $value;
			}
		}
	}	
} 
//dashboard page access right
function amgt_page_access_rolewise_accessright_dashboard($page)
{$role = amgt_get_user_role(get_current_user_id());
	if($role=='member')
	{
		$menu = get_option( 'amgt_access_right_member');
	}
	elseif($role=='staff_member')
	{
		$menu = get_option( 'amgt_access_right_staff_member');
	}
	elseif($role=='accountant')
	{
		$menu = get_option( 'amgt_access_right_accountant');
	}
	elseif($role=='gatekeeper')
	{
		$menu = get_option( 'amgt_access_right_gatekeeper');
	}
	
	foreach ( $menu as $key1=>$value1 ) 
	{									
		foreach ( $value1 as $key=>$value ) 
		{	
			if ($page == $value['page_link'])
			{				
				if($value['view']=='0')
				{			
					$flage=0;
				}
				else
				{
					$flage=1;
				}
			}
		}
	}	
	
	return $flage;
}
//-------- GET DISPLAY NAME  FUNCTION -----//
function apartment_get_display_name($id)
{
	$result=get_userdata($id);
	return $result->display_name;
}
//-------- GET AMOUNT  FUNCTION -----//
function get_amount($id)
{
	global $wpdb;
	$table_ministry = $wpdb->prefix. 'amgt_invoice_payment_history';
	$result = $wpdb->get_row("SELECT * FROM $table_ministry where member_id=".$id);
	return $result->amount;
}
?>