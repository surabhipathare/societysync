<?php 
/* AJAX FUNCTION*/
add_action( 'wp_ajax_amgt_add_or_remove_category', 'amgt_add_or_remove_category');
add_action( 'wp_ajax_nopriv_amgt_add_or_remove_category', 'amgt_add_or_remove_category');
add_action( 'wp_ajax_amgt_add_category', 'amgt_add_category');
add_action( 'wp_ajax_amgt_remove_category', 'amgt_remove_category');
add_action( 'wp_ajax_amgt_load_unit_cat', 'amgt_load_unit_cat');
add_action( 'wp_ajax_nopriv_amgt_load_unit_cat', 'amgt_load_unit_cat');
add_action( 'wp_ajax_amgt_load_units', 'amgt_load_units');
add_action( 'wp_ajax_nopriv_amgt_load_units', 'amgt_load_units');
add_action( 'wp_ajax_amgt_delete_gate', 'amgt_delete_gate');
add_action( 'wp_ajax_amgt_load_staff_checkin_data', 'amgt_load_staff_checkin_data');
add_action( 'wp_ajax_amgt_view_unit', 'amgt_view_unit');
add_action( 'wp_ajax_amgt_add_unitname', 'amgt_add_unitname');
add_action( 'wp_ajax_amgt_delete_units', 'amgt_delete_units');
add_action( 'wp_ajax_amgt_view_event',  'amgt_view_event');
add_action( 'wp_ajax_amgt_view_notice',  'amgt_view_notice');
add_action( 'wp_ajax_amgt_facility_booking_period',  'amgt_facility_booking_period');
add_action( 'wp_ajax_nopriv_amgt_facility_booking_period',  'amgt_facility_booking_period');
add_action( 'wp_ajax_amgt_count_facility_charge',  'amgt_count_facility_charge');
add_action( 'wp_ajax_amgt_load_period',  'amgt_load_period');
add_action( 'wp_ajax_amgt_generate_invoice_form',  'amgt_generate_invoice_form');
add_action( 'wp_ajax_amgt_unit_member_view',  'amgt_unit_member_view');
add_action( 'wp_ajax_amgt_unit_member_history_view',  'amgt_unit_member_history_view');
add_action( 'wp_ajax_amgt_unit_document_view',  'amgt_unit_document_view');
add_action( 'wp_ajax_amgt_remove_unit_member',  'amgt_remove_unit_member');
add_action( 'wp_ajax_amgt_remove_allready_occupied_member',  'amgt_remove_allready_occupied_member');
add_action( 'wp_ajax_amgt_load_member_designation',  'amgt_load_member_designation');
add_action( 'wp_ajax_amgt_view_complaint',  'amgt_view_complaint');
add_action( 'wp_ajax_amgt_service_complaint',  'amgt_service_complaint');
add_action( 'wp_ajax_amgt_checkout_popup','amgt_checkout_popup');
add_action( 'wp_ajax_amgt_load_invoice_amount',  'amgt_load_invoice_amount');
add_action( 'wp_ajax_amgt_invoice_view',  'amgt_invoice_view');
add_action( 'wp_ajax_amgt_member_add_payment',  'amgt_member_add_payment');
add_action( 'wp_ajax_amgt_verify_pkey', 'amgt_verify_pkey');
add_action( 'wp_ajax_nopriv_amgt_checkout_popup','amgt_checkout_popup');
add_action( 'wp_ajax_nopriv_amgt_load_unit_measurements','amgt_load_unit_measurements');
add_action( 'wp_ajax_amgt_load_unit_measurements','amgt_load_unit_measurements');
add_action( 'wp_ajax_amgt_load_tax_amount','amgt_load_tax_amount');
add_action( 'wp_ajax_amgt_unit_wise_view_member','amgt_unit_wise_view_member');
add_action( 'wp_ajax_amgt_account_unit_wise_view_member','amgt_account_unit_wise_view_member');
add_action( 'wp_ajax_amgt_add_unit_popup','amgt_add_unit_popup');
add_action( 'wp_ajax_amgt_add_member_popup','amgt_add_member_popup');
add_action( 'wp_ajax_amgt_load_document_html','amgt_load_document_html');
add_action( 'wp_ajax_amgt_load_document_html_frontend','amgt_load_document_html_frontend');
add_action( 'wp_ajax_amgt_generate_invoice_form_allmember','amgt_generate_invoice_form_allmember');
add_action( 'wp_ajax_amgt_member_wise_view_invoice','amgt_member_wise_view_invoice');
add_action( 'wp_ajax_amgt_invoice_option_html','amgt_invoice_option_html');
add_action( 'wp_ajax_amgt_invoice_option_maintance','amgt_invoice_option_maintance');
add_action( 'wp_ajax_amgt_unit_allready_occupied','amgt_unit_allready_occupied');
add_action( 'wp_ajax_amgt_charge_cal_option_html','amgt_charge_cal_option_html');
add_action( 'wp_ajax_amgt_tax_div_html','amgt_tax_div_html');
add_action( 'wp_ajax_amgt_change_profile_photo', 'amgt_change_profile_photo');
add_action( 'wp_ajax_amgt_load_visitor_data_by_id', 'amgt_load_visitor_data_by_id');
add_action( 'wp_ajax_nopriv_amgt_load_visitor_data_by_id', 'amgt_load_visitor_data_by_id');
add_action( 'wp_ajax_amgt_load_document_html_member','amgt_load_document_html_member');
add_action( 'wp_ajax_nopriv_amgt_load_document_html_member','amgt_load_document_html_member');

// CHANGE PROFILE PHOTO
function amgt_change_profile_photo()
{
	
	echo '<script  rel="javascript" src="'.plugins_url( '/assets/js/bootstrap.min.js', __FILE__).'"></script>';
	?>
	
	<script type="text/javascript">
	jQuery("body").on("change", ".input-file[type=file]", function ()
	{ 
		"use strict";
		var file = this.files[0]; 
		var file_id = jQuery(this).attr('id'); 
		var ext = $(this).val().split('.').pop().toLowerCase(); 
		//Extension Check 
		if($.inArray(ext, ['jpg','jpeg','png']) == -1)
		{
			alert('jpg,jpeg,png File allowed ,'+ext+' file not allowed');
			$(this).replaceWith('<input id="input-1" name="profile" type="file" class="form-control file file_border_css input-file">');
			return false; 
		} 
		 //File Size Check 
		 if (file.size > 20480000) 
		 {
			alert("Too large file Size. Only file smaller than 10MB can be uploaded.");
			$(this).replaceWith('<input id="input-1" name="profile" type="file" class="form-control file file_border_css input-file">'); 
			return false; 
		 }
	 });
</script>
	<div class="modal-header model_header_border"><!--MODEL_HEADER_BORDER---> 
	<a href="#" class="close-btn badge badge-success pull-right">X</a>
	</div>
	<form class="form-horizontal" action="#" method="post" enctype="multipart/form-data">
		<div class="form-group"><!--SELECT PROFILE---> 
		<label for="inputEmail" class="control-label col-sm-3"><?php esc_html_e('Select Profile Picture','apartment_mgt');?></label>
			<div class="col-xs-8">	
				<input id="input-1" name="profile" type="file" class="form-control file file_border_css input-file">
			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-offset-2 col-sm-10">
					<button type="submit" class="btn btn-success" name="save_profile_pic"><?php esc_html_e('Save','apartment_mgt');?></button>
			</div>
		</div>
	</form>
    <?php 
	die();	
}
//----------------- ALL READY OCCPIED --------------//
function amgt_unit_allready_occupied()
{
	$building_category = $_REQUEST['building_category'];
	$unit_category = $_REQUEST['unit_category'];
	$unit_name = $_REQUEST['unit_name'];
	$array_var=array();
	 $args = array(
        'meta_query'=>
         array(
			'relation' => 'AND',
            array(
                'relation' => 'AND',
			array(
				'key'	  =>	'building_id',
				'value'	=>	$building_category,
				'compare' => '=',
			),
			array(
				'key'	  =>	'unit_cat_id',
				'value'	=>	$unit_category,
				'compare' => '=',
			),
			array(
				'key'	  =>	'unit_name',
				'value'	=>	$unit_name,
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
				'value'	=>	'tenant',
				'compare' => '=',
			)
       )
    ));
	
    $users = get_users($args);
	
	if(!empty($users))
	{
		$allready_occupied='1';
	}
	if(empty($users))
	{
		$allready_occupied='0';
	}		
	
	$form='<div class="form-group"> 
		<a href="#" class="close-btn-occupied-popup badge badge-success pull-right">X</a>
		<h4 class="modal-title" id="myLargeModalLabel">	
		</h4>
	</div>
	<hr>
	<div class="panel-body">
		<div class="slimScrollDiv">
			<div class="inbox-widget slimscroll">
			<h3 class="color_red">This unit already occupied by the below member. First You want to delete that member and after occupied this unit to another member.</h3>';
			
			if(!empty($users))
			{
				foreach ($users as $retrieved_data)
				{
				
					$form.='<div class="inbox-item">
						<div class="inbox-item-img">';
				
					$uid=$retrieved_data->ID;
					$userimage=get_user_meta($uid, 'amgt_user_avatar', true);
					if($userimage=='')
					{
						$form.='<img src='.get_option( 'amgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
					}
					else
					{
						$form.='<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';	
					}
				
				$form.='</div>
					<p class="col-sm-6 inbox-item-author">'.amgt_get_display_name($retrieved_data->ID).' ('.amgt_get_member_status_label($retrieved_data->member_type).')
					</p>				
					<p class="col-sm-2"><button id="delete_occupied_unitmember" class="btn btn-default delete_group_member"  mem_id="'.$retrieved_data->ID.'" type="button">
					<i class="fa fa-trash"></i>Delete</button></p>			 
				</div>';				
				}
			}
		$form.='</div>		
	</div>';
	
	$array_var['form'][]=$form;
	$array_var['allready_occupied'][]=$allready_occupied;
	echo json_encode($array_var);
	die();
}
//--------------  ADD or REMOVE Category -----------//
function amgt_add_or_remove_category()//AMGT_ADD_OR_REMOVE_CATEGORY
{
	$model = $_REQUEST['model'];
	
		$title = __("title",'apartment_mgt');

		$table_header_title =  __("header",'apartment_mgt');

		$button_text=  __("Add category",'apartment_mgt');

		$label_text =  __("category Name",'apartment_mgt');

	
	if($model == 'building_category')//BUILDING_CATEGORY
	{
		

		$title = __("Add Building Category",'apartment_mgt');

		$table_header_title =  __("Building Name",'apartment_mgt');

		$button_text=  __("Add Building Category",'apartment_mgt');

		$label_text =  __("Building Name",'apartment_mgt');	

	}
	if($model == 'unit_category')//UNIT_CATEGORY
	{

		$title = __("Add Unit Category",'apartment_mgt');

		$table_header_title =  __("Unit Category Name",'apartment_mgt');

		$button_text=  __("Add Unit Category",'apartment_mgt');

		$label_text =  __("Unit Category Name",'apartment_mgt');	

	}
	if($model == 'staff_category')//STAFF_CATEGORY
	{

		$title = __("Add Staff Category",'apartment_mgt');

		$table_header_title =  __("Staff Category Name",'apartment_mgt');

		$button_text=  __("Add Staff Category",'apartment_mgt');

		$label_text =  __("Staff Category Name",'apartment_mgt');	

	}
	if($model == 'visit_reason_cat')//VISIT_REASON_CAT
	{

		$title = __("Add visit Reason",'apartment_mgt');

		$table_header_title =  __("Reason Name",'apartment_mgt');

		$button_text=  __("Add visit Reason",'apartment_mgt');

		$label_text =  __("Reason Name",'apartment_mgt');	

	}
	if($model == 'assets_category')//ASSETS_CATEGORY
	{

		$title = __("Add Assets Category",'apartment_mgt');

		$table_header_title =  __("Category Name",'apartment_mgt');

		$button_text=  __("Add Assets Category",'apartment_mgt');

		$label_text =  __("Category Name",'apartment_mgt');	

	}
	if($model == 'inventory_unit_cat')//INVENTORY_UNIT_CAT
	{

		$title = __("Add Inventory Unit Category",'apartment_mgt');

		$table_header_title =  __("Unit Category Name",'apartment_mgt');

		$button_text=  __("Add Inventory Unit Category",'apartment_mgt');

		$label_text =  __("Unit Category Name",'apartment_mgt');	

	}
	if($model == 'facility_booking_for')//FACILITY_BOOKING_FOR
	{

		$title = __("Add Activity For Facility Booking",'apartment_mgt');

		$table_header_title =  __("Activity Name",'apartment_mgt');

		$button_text=  __("Add Activity",'apartment_mgt');

		$label_text =  __("Activity Name",'apartment_mgt');	

	}
	if($model == 'income_types')//INCOME_TYPES
	{

		$title = __("Add Income Types",'apartment_mgt');

		$table_header_title =  __("Types Name",'apartment_mgt');

		$button_text=  __("Add Types",'apartment_mgt');

		$label_text =  __("Types Name",'apartment_mgt');	

	}
	if($model == 'expense_types')//EXPENSE_TYPES
	{

		$title = __("Add Expense Types",'apartment_mgt');

		$table_header_title =  __("Types Name",'apartment_mgt');

		$button_text=  __("Add Types",'apartment_mgt');

		$label_text =  __("Types Name",'apartment_mgt');	

	}
	if($model == 'charges_category')//CHARGES_CATEGORY
	{

		$title = __("Add Charges Types",'apartment_mgt');

		$table_header_title =  __("Charges Name",'apartment_mgt');

		$button_text=  __("Add Charges",'apartment_mgt');

		$label_text =  __("Charges Name",'apartment_mgt');	

	}
	if($model == 'designation_cat')
	{

		$title = __("Add Designation",'apartment_mgt');

		$table_header_title =  __("Designation Name",'apartment_mgt');

		$button_text=  __("Add Designation",'apartment_mgt');

		$label_text =  __("Designation Name",'apartment_mgt');	

	}
	if($model == 'complaint_category')
	{

		$title = __("Add Category",'apartment_mgt');

		$table_header_title =  __("Category Name",'apartment_mgt');

		$button_text=  __("Add Category",'apartment_mgt');

		$label_text =  __("Category Name",'apartment_mgt');	

	}
	if($model == 'member_category')
	{

		$title = __("Add Member Category",'apartment_mgt');

		$table_header_title =  __("Member Category Name",'apartment_mgt');

		$button_text=  __("Add Member Category",'apartment_mgt');

		$label_text =  __("Member Category Name",'apartment_mgt');	

	}
	 $cat_result = amgt_get_all_category( $model );	
	 
	?>
	<script type="text/javascript">
	$('.onlyletter_number_space_validation').keypress(function( e ) 
	{  
		"use strict";
		var regex = new RegExp("^[0-9a-zA-Z \b]+$");
		var key = String.fromCharCode(!event.charCode ? event.which: event.charCode);
		if (!regex.test(key)) 
		{
			event.preventDefault();
			return false;
		} 
   });  
	</script>
	<div class="modal-header padding_15"> <a href="#" class="close-btn badge badge-success pull-right">X</a><!--MODAL-HEADER--->

  		<h4 id="myLargeModalLabel" class="modal-title"><?php echo esc_html($title);?></h4>

	</div>

	<div class="panel panel-white"><!---PANEL-WHITE--->

  		<div class="category_listbox"><!---CATEGORY_LISTBOX----->

  			<div class="table-responsive"><!---TABLE-RESPONSIVE----->

		  		<table class="table">

			  		<thead>

			  			<tr>

			                <!--  <th>#</th> -->

			                <th><?php echo esc_html($table_header_title);?></th>

			                <th><?php esc_html_e('Action','apartment_mgt');?></th>

			            </tr>

			        </thead>
					<?php 
					$i = 1;

					if(!empty($cat_result))
					{
						foreach ($cat_result as $retrieved_data)
						{

							echo '<tr id="cat-'.$retrieved_data->ID.'">';

							//echo '<td>'.$i.'</td>';

							echo '<td>'.$retrieved_data->post_title.'</td>';
							if( $retrieved_data->post_title == 'Owner' || $retrieved_data->post_title == 'Tenant')
							{
								//echo '<td </td>';
								echo '<td id='.$retrieved_data->ID.'><a class="btn-delete-cat badge badge-delete" model='.$model.' href="#" id='.$retrieved_data->ID.'>X</a></td>';
							}
							else
							{
								echo '<td id='.$retrieved_data->ID.'><a class="btn-delete-cat badge badge-delete" model='.$model.' href="#" id='.$retrieved_data->ID.'>X</a></td>';
							}
							echo '</tr>';
							$i++;		

						}

					} ?>
		        </table>
			</div><!---END TABLE-RESPONSIVE----->
		</div><!---END CATEGORY_LISTBOX----->	
		<form name="category_form" action="" method="post" class="form-horizontal" id="category_form"><!---CATEGORY_FORM----->

	  	 	<div class="form-group">

				<label class="col-sm-4 control-label" for="category_name"><?php echo esc_html($label_text);?><span class="require-field">*</span></label>

				<div class="col-sm-4 padding_bottom_10">

					<input id="category_name" class="form-control text-input onlyletter_number_space_validation" maxlength="50" value="" name="category_name" <?php if(isset($placeholder_text)){?> type="number" placeholder="<?php  echo esc_attr($placeholder_text);}else{?>" type="text" <?php }?>>

				</div>
				<div class="col-sm-4 padding_bottom_10">
					<input type="button" value="<?php echo esc_attr($button_text);?>" name="save_category" class="btn btn-success" model="<?php echo esc_attr($model);?>" id="btn-add-cat"/>
				</div>

			</div>
		</form>
	</div><!---END PANEL-WHITE--->
	<?php 
	die();	
}
//------------- ADD CATEGORY --------------------//
function amgt_add_category($data)
{

	global $wpdb;
	$model = $_REQUEST['model'];
	$status=1;
	$status_msg= esc_html__('You have entered value already exists. Please enter some other value.','apartment_mgt');
	$array_var = array();
	$data = array();
	$data['category_name'] = $_POST['category_name'];
	$data['category_type'] = $_POST['model'];
    $posttitle =$_REQUEST['category_name'];
    $post = $wpdb->get_row( "SELECT * FROM $wpdb->posts WHERE post_title = '" . $posttitle . "' AND  post_type ='". $model."'" );
    $postname=$post->post_title;
	
   if($postname == $posttitle )
   {
	   $status=0;
   }
   else
   { 
	$id = amgt_add_categorytype($data);
	$row1 = '<tr id="cat-'.$id.'"><td>'.$_REQUEST['category_name'].'</td><td><a class="btn-delete-cat badge badge-delete" href="#" id='.$id.' model="'.$model.'">X</a></td></tr>';

	$option = "<option value='$id'>".$_REQUEST['category_name']."</option>";

	$array_var[] = $row1;

	$array_var[] = $option;
   }
    $array_var[2]=$status;
    $array_var[3]=$status_msg;
	echo json_encode($array_var);

	die();
}
//----------- REMOVE  CATEGORY -------------//
function amgt_remove_category()
{
	wp_delete_post($_REQUEST['cat_id']);
	
	die();
}
//----------------- LOAD UNIT CAT -------------//
function amgt_load_unit_cat()
{
	
	global $wpdb;
	
	$amgt_residential_units = $wpdb->prefix. 'amgt_residential_units';
	$unit_catdata = $wpdb->get_results("select DISTINCT(unit_cat_id) from $amgt_residential_units where building_id=".$_REQUEST['building_id']);	
	
		$defaultmsg=esc_html__('Select Unit Category', 'apartment_mgt');
		echo "<option value=''>".$defaultmsg."</option>";	
		if(!empty($unit_catdata))
		{
			foreach($unit_catdata as $unit_cat)
			{			
				$post=get_post($unit_cat->unit_cat_id);
				echo "<option value=".$unit_cat->unit_cat_id.">".$post->post_title."</option>";
			}
		}
		else{
			echo "<option>".esc_html__('Records Not Found', 'apartment_mgt')."</option>";
		}
		die();
}
//------------------- LOAD UNITS --------------//
function amgt_load_units()
{
	
	global $wpdb;

		$table_residential = $wpdb->prefix. 'amgt_residential_units';
		$unit_catdata = $wpdb->get_results("select units from $table_residential where building_id=".$_REQUEST['building_id']." AND unit_cat_id=".$_REQUEST['unit_cat_id']);
		
		$all_entry=array();
		if(!empty($unit_catdata))
		{
			foreach($unit_catdata as $unit)
			{
				$all_entry[]=json_decode($unit->units);
			}
		}
		
		$array_var =array();
		
		if(!empty($all_entry))
		{
			foreach($all_entry as $key=>$val)
			{			
		
				foreach($val as $key1=>$val1)
				{					
					$array_var[]= array("value" => "$val1->entry");
				}
			}
		}
		
	 echo json_encode($array_var);		
		die();
}
//--------------- DELETE GATE ----------------//
function amgt_delete_gate()
{
	$obj_gate=new Amgt_gatekeeper;
	if(isset($_REQUEST['gate_id']))
	$obj_gate->amgt_delete_gate($_REQUEST['gate_id']);
	die();
}
//---------------- LOAD INVOICE AMOUNT -------------//
function amgt_load_invoice_amount()
{
	$_REQUEST['invoice_id'];
	$obj_account =new Amgt_Accounts;
	$account_result=$obj_account->amgt_get_single_invoice($_REQUEST['invoice_id']);
	echo $account_result->amount;
	die();
}
//--------------- LOAD STAFF CHECKIN DATA ---------------//
function amgt_load_staff_checkin_data()
{
	
	$users = get_users(array(
		'meta_key'     => 'badge_id',
		'meta_value'   => $_REQUEST['badge_id'],
		'meta_compare' => '==',
	));
	$user=$users[0];
	
	if(!empty($user)){ ?>
	     <input type="hidden" name="member_id" value="<?php echo esc_attr($user->ID);?>">
		 <div class="form-group">
					<label class="col-sm-2 control-label" for="name"><?php esc_html_e('Name','apartment_mgt');?></label>
					<div class="col-sm-8">
						<label class="radio-inline" for="name"><?php echo esc_html($user->display_name);?></label>
					</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="gender"><?php esc_html_e('Gender','apartment_mgt');?></label>
			<div class="col-sm-8">
				<label class="radio-inline" for="gender"><?php $gender=get_user_meta($user->ID,'gender',true);
				if($gender=='male')
					echo esc_html__('Male','apartment_mgt');
				if($gender=='female')
					echo esc_html__('Female','apartment_mgt');
				?></label>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="dob"><?php esc_html_e('DOB','apartment_mgt');?></label>
			<div class="col-sm-8">
				<label class="radio-inline" for="dob"><?php echo amgt_change_dateformat(get_user_meta($user->ID,'birth_date',true)); ?></label>
			</div>
		</div>
		<div class="form-group">	
			<label class="col-sm-2 control-label" for="mobile"><?php esc_html_e('Mobile','apartment_mgt');?></label>
			<div class="col-sm-8">
				<label class="radio-inline" for="mobile"><?php echo get_user_meta($user->ID,'mobile',true); ?></label>
			</div>
		</div>
	<?php }
	die();
}
//------------- VIEW UNIT ---------------//
function amgt_view_unit()
{
	$building_id = $_REQUEST['building_id'];
	$unit_cat_id = $_REQUEST['unit_cat_id'];
	global $wpdb;
		$table_residential = $wpdb->prefix. 'amgt_residential_units';
		$unit_catdata = $wpdb->get_row("select units from $table_residential where unit_cat_id=".$_REQUEST['unit_cat_id']);
		$all_entry=json_decode($unit_catdata->units);
	?>
	<div class="modal-header padding_15"> <a href="#" class="close-btn badge badge-success pull-right">X</a>

  		<h4 id="myLargeModalLabel" class="modal-title">
			<?php 
			echo esc_html__('Building Name','apartment_mgt').' - '.get_the_title($building_id).' , ' ;
			echo esc_html__('Unit Category','apartment_mgt').' - '.get_the_title($unit_cat_id);
			?>
		</h4>
		
	</div>

	<div class="panel panel-white"><!--PANEL WHITE---->
        <form name="category_form" action="" method="post" class="form-horizontal" id="category_form">
			<div class="category_listbox">
				<div class="table-responsive"><!--TABLE RESPONSIVE---->
					<table class="table unit_list">
						<thead>
							<tr>
								<th><?php echo esc_html__('Unit Name','apartment_mgt');?></th>
								<th><?php echo esc_html__('Action','apartment_mgt');?></th>
							</tr>
						</thead>
						<?php 
						$i = 1;
						if(!empty($all_entry))
						{
							foreach ($all_entry as $unit)
							{
							echo '<tr id="cat-'.$unit->entry.'">';
							//echo '<td>'.$i.'</td>';
							echo '<td>'.$unit->entry.'</td>';
							echo '<td><span class="badge badge-danger delete-units" data-unit-name = "'.$unit->entry.'" onclick="return confirm(\' Are you sure you want to delete this ?\')">X</span></td>';
							echo '</tr>';
							$i++;		
							}
						} ?>

				   </table>
				</div><!--END TABLE RESPONSIVE---->
			</div>	
			<div class="form-group">

				<label class="col-sm-4 control-label" for="category_name"><?php echo esc_html__('Enter Unit Name','apartment_mgt');?><span class="require-field">*</span></label>

				<div class="col-sm-4">

					<input id="category_name" class="form-control text-input"  value="" name="category_name" <?php if(isset($placeholder_text)){?> type="number" placeholder="<?php  echo esc_attr($placeholder_text);}else{?>" type="text" <?php }?>>

				</div>

				<div class="col-sm-4">

					<input type="button" value="<?php echo esc_html__('Add Unit','apartment_mgt');?>" name="save_category" class="btn btn-success" model="unitname" id="btn-add-unit"  data-unit_cat_id="<?php echo esc_attr($unit_cat_id);?>" data-building_id="<?php echo esc_attr($building_id);?>"/>
						
				</div>

			</div>
				<input type="hidden" name="unit_cat_id" id="unit_cat_id" value="<?php echo esc_attr($unit_cat_id);?>">
				<input type="hidden" name="building_id"  id="building_id" value="<?php echo esc_attr($building_id);?>">
  	    </form>
  	</div>
	<?php
	die();
}
//--------------- ADD UNIT NAME -------------------//
function amgt_add_unitname()
{
	//echo "success";
	$building_id = $_REQUEST['building_id'];
	$unit_cat_id = $_REQUEST['unit_cat_id'];
	$unit_name = $_REQUEST['unit_name'];
	$obj_unit = new Amgt_ResidentialUnit;
	$units = $obj_unit->amgt_get_single_cat_units($unit_cat_id);
	
	$data = json_decode($units, true);
	$data[] = array('entry'=>$unit_name );
	
	$update_units =  json_encode($data);
	$unitsupdates = $obj_unit->amgt_update_unit($building_id,$unit_cat_id,$update_units);
	 echo '<tr>';
	echo '<td>';	
	echo $unit_name.'</td>';
	echo '<td>';	
	echo '<span onclick="return confirm(\' Are you sure you want to delete this ?\')" data-unit-name="'.$unit_name.'" class="badge badge-danger delete-units">X</span>';
	echo '</td>';
	echo '</tr>'; 
	die();
}
//-------------- DALETE UNITS ----------------//
function amgt_delete_units()
{
	$building_id = $_REQUEST['building_id'];
	$unit_cat_id = $_REQUEST['unit_cat_id'];
	$unit_name = $_REQUEST['unit_name'];
	$obj_unit = new Amgt_ResidentialUnit;
	$units = $obj_unit->amgt_get_single_cat_units($unit_cat_id);
	$data = json_decode($units, true);
	
	foreach($data as $key => $value)
	{	
		
		if($value['entry'] == $unit_name)
		{
			unset($data[$key]);
		}
		
	}

	$update_units =  json_encode($data);
	$unitsupdates = $obj_unit->amgt_update_unit($building_id,$unit_cat_id,$update_units);
	die();
}
//------ VIEW EVENT ------------//
function amgt_view_event()
{
	 $obj_notice=new Amgt_NoticeEvents;
	 $eventdata= $obj_notice->amgt_get_single_event($_REQUEST['evnet_id']);
	 ?>
	 
	 <!---Notice Pop Up -->

  <div class="overlay-content content_width">
    <div class="modal-content new_css">
    <div class="task_event_list">
     <div class="form-group notice_popup"> 	<a href="#" class="complaint-close-btn badge badge-success pull-right">X</a>
       <h4 class="modal-title" id="myLargeModalLabel">
       <?php esc_html_e('Event Detail','apartment_mgt'); ?>
      </h4>
     </div>
	<div class="panel panel-white">
			<div class="modal-body view_details_body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td class="popup_font"><?php esc_html_e('Title', 'apartment_mgt' ) ;?></td>
						<td><?php echo esc_html($eventdata->event_title);?></td>
					</tr>
					<tr>
						<td class="popup_font"><?php esc_html_e('Comment', 'apartment_mgt' ) ;?></td>
						<td> <?php echo esc_html($eventdata->description);?></td>
					</tr>
					<tr>
						<td class="popup_font"><?php esc_html_e('Start Date','apartment_mgt');?></td>
						<td><?php echo date(amgt_date_formate(),strtotime($eventdata->start_date));?></td>
					</tr>			
					<tr>
						<td class="popup_font"><?php esc_html_e('Start Time', 'apartment_mgt' ) ;?></td>
						<td><?php echo esc_html($eventdata->start_time);?></td>
					</tr>
                     <tr>
						<td class="popup_font"><?php esc_html_e('End Date','apartment_mgt');?></td>
						<td><?php echo date(amgt_date_formate(),strtotime($eventdata->end_date));?></td>
					</tr>
                    <tr>
						<td class="popup_font"><?php esc_html_e('End Time', 'apartment_mgt' ) ;?></td>
						<td><?php echo esc_html($eventdata->end_time);?></td>
					</tr>					
				</tbody>
			</table>
        </div>  		
		
  	</div>
	</div>     
		</div>
    </div>
	<!---End Notice Pop up--->
<?php 
	die();
}
//----------------- VIEW NOTICE -----------------//
function amgt_view_notice()
{

	 $obj_notice=new Amgt_NoticeEvents;
	 $noticedata= $obj_notice->amgt_get_single_notice($_REQUEST['notice_id']);
	?>
<!---Notice Pop Up -->
  <div class="overlay-content content_width">
    <div class="modal-content new_css">
    <div class="task_event_list">
     <div class="form-group notice_popup"> 	<a href="#" class="complaint-close-btn badge badge-success pull-right">X</a>
       <h4 class="modal-title" id="myLargeModalLabel">
       <?php esc_html_e('Notice Details','apartment_mgt'); ?>
      </h4>
     </div>
	<div class="panel panel-white">
			<div class="modal-body view_details_body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td class="popup_font"><?php esc_html_e('Notice Title:', 'apartment_mgt' ) ;?></td>
						<td><?php echo esc_html($noticedata->notice_title);?></td>
					</tr>
					<tr>
						<td class="popup_font"><?php esc_html_e('Valid Date:', 'apartment_mgt' ) ;?></td>
						<td> <?php echo date(amgt_date_formate(),strtotime($noticedata->valid_date));?></td>
					</tr>
					<tr>
						<td class="popup_font"><?php esc_html_e('Notice Type:', 'apartment_mgt' ) ;?></td>
						<td><?php echo esc_html($noticedata->notice_type);?></td>
					</tr>			
					<tr>
						<td class="popup_font"><?php esc_html_e('Status:', 'apartment_mgt' ) ;?></td>
						<td><?php if($noticedata->status=='Open'){ esc_html_e('Open','apartment_mgt');}elseif($noticedata->status=='Not Approved'){  esc_html_e('Not Approved','apartment_mgt');}?></td>
					</tr>
                     <tr>
						<td class="popup_font"><?php esc_html_e('Description:', 'apartment_mgt' ) ;?></td>
						<td><?php echo wp_trim_words( $noticedata->description,5);?></td>
					</tr>					
				</tbody>
			</table>
        </div>  		
		
  	</div>
	</div>     
		</div>
    </div>
	<!---End Notice Pop up--->
 <?php 
	die();
}
//----------------- VIEW COMPLAINT ----------------//
function amgt_view_complaint()
{
	$obj_complaint=new Amgt_Complaint;
	$complaintdata= $obj_complaint->amgt_get_single_complaint($_REQUEST['complaint_id']);
	
?>
<div class="overlay-content content_width">
    <div class="modal-content new_css">
    <div class="task_event_list">
     <div class="form-group notice_popup"> 	<a href="#" class="complaint-close-btn badge badge-success pull-right">X</a>
       <h4 class="modal-title" id="myLargeModalLabel">
       <?php esc_html_e('Complain Details','apartment_mgt'); ?>
      </h4>
     </div>
	<div class="panel panel-white">
			<div class="modal-body view_details_body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td class="popup_font"><?php esc_html_e(' Nature:','apartment_mgt');?></td>
						<td><?php echo ucfirst($complaintdata->complaint_nature);?></td>
					</tr>
					<tr>
						<td class="popup_font"><?php esc_html_e('Type:','apartment_mgt');?></td>
						<td><?php echo ucfirst($complaintdata->complaint_type);?></td>
					</tr>
					<?php
					if($complaintdata->complaint_nature != "Maintenance Request")
					{
					?>
						<tr>
							<td class="popup_font"><?php esc_html_e('Category:','apartment_mgt');?></td>
							<td><?php echo get_the_title($complaintdata->complaint_cat);?> </td>
						</tr>	
					<?php
					}
					?>
					<tr>
						<td class="popup_font"><?php esc_html_e('Created By:','apartment_mgt');?></td>
						<td><?php $user=get_userdata($complaintdata->created_by); echo ucfirst($user->display_name);?></td>
					</tr>
                     <tr>
						<td class="popup_font"><?php esc_html_e('Status:','apartment_mgt');?></td>
						<td><?php 
						if($complaintdata->complaint_status == 'open')
						{
							$status=esc_html__('Open', 'apartment_mgt' );
						}
						else if($complaintdata->complaint_status == 'close')
						{
							$status=esc_html__('Closed', 'apartment_mgt' );
						}
						else if($complaintdata->complaint_status == 'on_hold')
						{
							$status=esc_html__('On Hold', 'apartment_mgt' );
						}
						elseif($complaintdata->complaint_status == 'scheduled')
						{
							$status=esc_html__('Scheduled', 'apartment_mgt' );
						} 
						else
						{
							$status="-";
						}
						echo $status;?></td>
					</tr>
                     <tr>
						<td class="popup_font"><?php esc_html_e('Description:','apartment_mgt');?></td>
						<td><?php echo esc_html($complaintdata->complaint_description);?> </td>
					</tr>					
				</tbody>
			</table>
        </div>  		
		
  	</div>
	</div>     
		</div>
    </div>
<?php 
	die();
}
//Add-service 
function amgt_service_complaint()
{
	 $obj_service=new Amgt_Service;
	 $service_data= $obj_service->amgt_get_single_service($_REQUEST['service_id']);
	 ?>
<div class="overlay-content content_width">
    <div class="modal-content new_css">
    <div class="task_event_list">
     <div class="form-group notice_popup"> 	<a href="#" class="complaint-close-btn badge badge-success pull-right">X</a>
       <h4 class="modal-title" id="myLargeModalLabel">
       <?php esc_html_e('Service Details','apartment_mgt'); ?>
      </h4>
     </div>
	<div class="panel panel-white">
			<div class="modal-body view_details_body">
			<table id="examlist" class="table table-striped" cellspacing="0" width="100%" align="center">
				<tbody>
					<tr>
						<td class="popup_font"><?php esc_html_e(' Service Name:','apartment_mgt');?></td>
						<td><?php echo esc_html($service_data->service_name);?></td>
					</tr>
					<tr>
						<td class="popup_font"><?php esc_html_e(' Service Provider:	','apartment_mgt');?></td>
						<td><?php echo esc_html($service_data->service_provider);?></td>
					</tr>
					<tr>
						<td class="popup_font"><?php esc_html_e('Contact Number:','apartment_mgt');?></td>
						<td><?php echo esc_html($service_data->contact_number);?> </td>
					</tr>			
					<tr>
						<td class="popup_font"><?php esc_html_e('Mobile Number:','apartment_mgt');?></td>
						<td><?php echo esc_html($service_data->mobile_number);?></td>
					</tr>
                     <tr>
						<td class="popup_font"><?php esc_html_e('Email:','apartment_mgt');?></td>
						<td><?php echo esc_html($service_data->email);?></td>
					</tr>
                     <tr>
						<td class="popup_font"><?php esc_html_e('Address:','apartment_mgt');?></td>
						<td><?php echo wp_trim_words($service_data->address,5);?></td>
					</tr>					
				</tbody>
			</table>
        </div>  		
		
  	</div>
	</div>     
		</div>
    </div>
<?php 
	die();
}

//Add-service 
function amgt_checkout_popup()
{    $curr_user_id=get_current_user_id();
     $obj_apartment=new Apartment_management($curr_user_id);
     $obj_member=new Amgt_Member;
     $obj_units=new Amgt_ResidentialUnit;
	 $obj_gates=new Amgt_gatekeeper;
	 $exit_gates=$obj_gates->amgt_get_all_exit_gates();		
?>	
<div class="form-group"> 	<a href="#" class="close-btn badge badge-success pull-right">X</a>
  <h4 class="modal-title" id="myLargeModalLabel"> <?php esc_html_e('Checkout','apartment_mgt'); ?> </h4>
</div>
<hr>

<div class="panel panel-white form-horizontal">
	<form name="checkout_form" action="" method="post" class="form-horizontal checkout-form">
	
		<input type="hidden" class="form-horizontal" name="checkin_id" value="<?php echo esc_attr($_REQUEST['checkin_id']);?>">
		<input type="hidden" class="form-horizontal" name="checkout_type" value="<?php echo esc_attr($_REQUEST['checkout_type']);?>">
		<h4 class="margin_left_13" id=""> <?php esc_html_e('Gate','apartment_mgt'); ?> </h4>
		
		<?php $role=amgt_get_user_role($retrieved_data->ID); 
		if($obj_apartment->role=='administrator') {
		?>
		<?php foreach($exit_gates as $gate){ ?>
		<div class="form-group checkout">		   
			<div class="col-sm-5 checkout margin_left_13"> <input type="radio" class="form-control radio_border_radius" name="gate_name" value="<?php echo esc_attr($gate->id);?>"><label class="padding_top_10_left_5"><?php echo esc_html($gate->gate_name);?></label></div>
		</div>
		<?php }} else { ?>
		 
		 <?php foreach($exit_gates as $gate){ ?>
		<div class="form-group checkout">		   
			<div class="col-sm-5 checkout front_radio margin_left_13"> <input type="radio" class="form-control radio_border_radius" name="gate_name" value="<?php echo esc_attr($gate->id);?>"><label class="padding_top_10_left_5"><?php echo esc_html($gate->gate_name);?></label></div>
		</div>
		<?php }} ?>
		
		  
		<div class="col-sm-offset-1 col-sm-2 margin_left_0">
			<input type="submit" value="<?php esc_html_e('Checkout','apartment_mgt');?>" name="save_checkout" class="btn btn-success"/>
		</div>
		
	</form>
</div>
<?php die();
}
//------------- FACILITY BOOKING PERIOD ------------------//
function amgt_facility_booking_period()
{ ?>
<script type="text/javascript">
$(document).ready(function() {	
	"use strict";
	var date = new Date();
    var start = new Date();
		var end = new Date(new Date().setYear(start.getFullYear()+1));
		 $(".datepicker1").datepicker({
       dateFormat: "yy-mm-dd",
		minDate:0,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 0);
            $(".datepicker2").datepicker("option", "minDate", dt);
        }
	    });
	    $(".datepicker2").datepicker({
	      dateFormat: "yy-mm-dd",
	        onSelect: function (selected) {
	            var dt = new Date(selected);
	            dt.setDate(dt.getDate() - 0);
	            $(".datepicker1").datepicker("option", "maxDate", dt);
	        }
	    });	
	//$('.timepicker').timepicker();	
	$('.timepicker').timepicki();
} );
</script>
<style>
.dropdown-menu {
    min-width: 240px;
}
</style>
	<?php $obj_facility =new Amgt_Facility;
	if(isset($_REQUEST['facility_id']) && $_REQUEST['facility_id']!="")
	{
	$result = $obj_facility->amgt_get_single_facility($_REQUEST['facility_id']);
	if($result->charge_per=='date')
	{ 
	?>
	<input id="period_type"  type="hidden" value="date_type" name="period_type">
	<div class="form-group">
			<label class="col-sm-2 control-label" for="facility_start_date">
			<?php esc_html_e('Start Date','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="start_date" class="datepicker1 form-control validate[required] start" type="text"  
				value="" name="start_date" autocomplete="off">
			</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="end_date">
		<?php esc_html_e('End Date','apartment_mgt');?> <span class="require-field">*</span></label>
		<div class="col-sm-8">
			<input id="end_date" class="datepicker2 form-control validate[required] end" type="text"  
			value="" name="end_date" autocomplete="off">
		</div>
	</div>
<?php }	
	if($result->charge_per=='hour')
	{ 
	?>
	<input id="period_type"  type="hidden" value="hour_type" name="period_type">
	<div class="form-group">
			<label class="col-sm-2 control-label" for="facility_start_date">
			<?php esc_html_e('Booking Date','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="start_date" class="datepicker1 form-control validate[required]" type="text"  
				value="" name="start_date" autocomplete="off">			
			</div>
	</div>
	<div class="form-group">
			<label class="col-sm-2 control-label " for="facility_start_date">
			<?php esc_html_e('Start Time','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input type="text" value="<?php if(isset($_POST['start_time'])) echo esc_attr($_POST['start_time']);?>" class="form-control timepicker start start_time" name="start_time" autocomplete="off"/>
			</div>
	</div>
	<div class="form-group">
			<label class="col-sm-2 control-label" for="end_date">
			<?php esc_html_e('End Time','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="end_time" type="text" value="<?php if(isset($_POST['end_time'])) echo esc_attr($_POST['end_time']);?>" class="form-control timepicker end end_time" name="end_time" autocomplete="off" />
			</div>
	</div>
	<?php }	
	
	}
	die();
}
//-------------------- COUNT FACILITY CHARGE  ----------------//
function amgt_count_facility_charge()
{ 
	$charges=0;
	$obj_facility =new Amgt_Facility;
	if(isset($_REQUEST['facility_id']) && $_REQUEST['facility_id']!="")
	{
	 $result = $obj_facility->amgt_get_single_facility($_REQUEST['facility_id']);
	 $result->facility_charge;
	}
	if(isset($_REQUEST['period_type'])){
		if($_REQUEST['period_type']=='hour_type'){
			
			
			$date_a  = date("H", strtotime($_REQUEST['start']));	
			$date_b  = date("H", strtotime($_REQUEST['end']));
			
			$diff = $date_b - $date_a;
		
			$charges=$result->facility_charge*$diff;
			
		}
		
		if($_REQUEST['period_type']=='date_type'){
			
			 //count date wise facility chargis function
			  $dStart = new DateTime(amgt_get_format_for_db($_REQUEST['start']));
			  $date=amgt_get_format_for_db($_REQUEST['end']);
			  $new_end_dat=date('Y-m-d', strtotime("+1 day", strtotime($date)));
			  
			  $dEnd  = new DateTime($new_end_dat);
			  $dDiff = $dStart->diff($dEnd);
			  $charges=$result->facility_charge*$dDiff->days;
		}
	}
	echo $charges;
	die();
}
//----------------- LOAD PERIOD  ------------------//
function amgt_load_period()
{
	
	if($_REQUEST['selected']=='monthly')
	{
		for($i=0;$i<=11;$i++){
		$month=date('F',strtotime("first day of -$i month"));?>
		<option value="<?php echo $i+1;?>" ><?php echo $month;?></option>
		<?php }
	}
	if($_REQUEST['selected']=='quarterly')
	{ ?>
		<option value="quirter1" ><?php echo esc_html__('Quarter I','apartment_mgt');?></option>
		<option value="quirter2" ><?php echo esc_html__('Quarter II','apartment_mgt');?></option>
		<option value="quirter3" ><?php echo esc_html__('Quarter III','apartment_mgt');?></option>
		<option value="quirter4" ><?php echo esc_html__('Quarter IV','apartment_mgt');?></option>
		<?php 
	}
	if($_REQUEST['selected']=='half_yearly')
	{ ?>
		<option value="half1" ><?php echo esc_html__('I Half','apartment_mgt');?></option>
		<option value="half2" ><?php echo esc_html__('II Half','apartment_mgt');?></option>
		<?php 
	}
	if($_REQUEST['selected']=='yearly')
	{ ?>
		<option value="yearly" ><?php echo esc_html__('Yearly','apartment_mgt');?></option>
		
		<?php 
	}
	die();	
}
//---------------- GANARATE INVOICE FORM ---------------------//
function amgt_generate_invoice_form()
{ 
$obj_units=new Amgt_ResidentialUnit;
	$invoice_id=0;
			if(isset($_REQUEST['invoice_id']))
				$invoice_id=$_REQUEST['invoice_id'];
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					$edit=1;
					$result = $obj_account->amgt_get_single_invoice($invoice_id);
				} 
?>
<script type="text/javascript">
$(document).ready(function() {
	"use strict";
	$('#invoice_form').validationEngine();
	
	/*----------- Select invoice option for maintenance invoice module------------- */
	$('.select_serveice_invoice').change(function(){
		
		 var invoice_option = $(this).val();

		 var curr_data = {
						action: 'amgt_invoice_option_maintance',
						invoice_option: invoice_option,		
						dataType: 'json'
						};					
						$.post(amgt.ajax, curr_data, function(response) {
							
					    $('#invoice_setting_block_invoice').html(response);
						}); 
    });
	
	var date = new Date();
            date.setDate(date.getDate()-0);
	        $.fn.datepicker.defaults.format =" <?php  echo amgt_dateformat_PHP_to_jQueryUI(amgt_date_formate()); ?>";
             $('#from_date').datepicker({
	         startDate: date,
             autoclose: true
            }); 	
             $('#invoice_date').datepicker({
	         startDate: date,
             autoclose: true
           }); 	
		    $('#to_date').datepicker({
	         startDate: date,
             autoclose: true
           }); 	
		   $('#due_date').datepicker({
	         startDate: date,
             autoclose: true
           }); 
} );
</script>
<style>
.dropdown-menu {
    min-width: 240px;
}
.overlay-content {
    width: 65%;
}
</style>
	<div class="form-group"> 	<a href="#" class="invoice-close-btn badge badge-success pull-right">X</a>
		<h4 class="modal-title" id="myLargeModalLabel">
				<?php echo  esc_html__('Generate Invoice','apartment_mgt'); ?>
			  </h4>
	</div>
	<div class="modal-body height_450_auto">
		<div class="panel panel-white form-horizontal">
	<div class="panel-body">
        <form name="invoice_form" action="" method="post" class="form-horizontal" id="invoice_form">
         <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
		<input type="hidden" name="invoice_id" value="<?php echo esc_attr($invoice_id);?>"  />
		
		<div class="form-group">
			<label class="col-sm-2 control-label " for="enable"><?php esc_html_e('Invoice Option','hospital_mgt');?></label>
			<div class="col-sm-8">
				 <div class="radio">
				 
				 <label>
  						<input  type="radio" class="select_serveice_invoice" checked="checked" name="select_serveice_invoice" value="all_member"> <?php esc_html_e('All Member','hospital_mgt');?> 
  					</label> 
  					&nbsp;&nbsp;&nbsp;&nbsp;
  					<label>
  						<input  type="radio" class="select_serveice_invoice" name="select_serveice_invoice" value="Building_member"> <?php esc_html_e('Building Member','hospital_mgt');?> 
  					</label> 
  					&nbsp;&nbsp;&nbsp;&nbsp;
  					<label>
  						<input type="radio" class="select_serveice_invoice"  name="select_serveice_invoice" value="Unit_Category_member">  <?php esc_html_e('Unit Category Member','hospital_mgt');?>
  					</label>
					&nbsp;&nbsp;&nbsp;&nbsp;
					
					<label> 
  						<input type="radio" class="select_serveice_invoice"  name="select_serveice_invoice" value="one_member">  <?php esc_html_e('One Member','hospital_mgt');?>
  					</label>
					&nbsp;&nbsp;&nbsp;&nbsp;
  				</div>
				 
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="income_type">
			<?php esc_html_e('Income Type','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
			<select class="form-control validate[required]" name="income_type" id="income_types">
				<option value=""><?php esc_html_e('Select Income Type','apartment_mgt');?></option>
				<?php 
				if($edit)
					$category =$result->income_type_id;
				elseif(isset($_REQUEST['income_type']))
					$category =$_REQUEST['income_type'];  
				else 
					$category = "";
				
				$activity_category=amgt_get_all_category('income_types');
				if(!empty($activity_category))
				{
					foreach ($activity_category as $retrive_data)
					{
						echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
					}
				} ?>
				</select>
			</div>
			<div class="col-sm-2"><button id="addremove" model="income_types"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label " for="title"><?php esc_html_e('Title','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="title" class="form-control validate[required] text-input" type="text"  name="title" 
				value="<?php if($edit){ echo esc_attr($result->title);}elseif(isset($_POST['title'])) echo esc_attr($_POST['title']);?>">
			</div>
		</div>
		
		<div id="invoice_setting_block_invoice"></div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="unit_name"><?php esc_html_e('Year','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select class="form-control validate[required]" name="year" id="year">
				<option value=""><?php esc_html_e('Select Year','apartment_mgt');?></option>
				<?php 
				if($edit)
					 $yeardata =$result->year;
				elseif(isset($_REQUEST['year']))
					$yeardata =$_REQUEST['year'];  
				else 
					$yeardata = "";	
					
						foreach(range(date('Y'), 1980) as $year)
						{ ?>
							<option value="<?php echo esc_attr($year); ?>" <?php selected($yeardata,$year);?>><?php echo esc_html($year);?> </option>
						<?php }
				?>
				</select>
			</div>
		</div>
	
		<div class="form-group">
			<label class="col-sm-2 control-label" for="from_date">
			<?php esc_html_e('Charge From Date','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="from_date" class="form-control validate[required]" type="text"  
				value="<?php if($edit){ echo esc_attr($result->from_date);}
				elseif(isset($_POST['from_date'])) echo esc_attr($_POST['from_date']);?>" name="from_date">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="to_date">
			<?php esc_html_e('Charge To Date','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="to_date" class="form-control validate[required]" type="text"  
				value="<?php if($edit){ echo esc_attr($result->to_date);}
				elseif(isset($_POST['to_date'])) echo esc_attr($_POST['to_date']);?>" name="to_date">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="invoice_date">
			<?php esc_html_e('Invoice Date','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				
				<input id="invoice_date" class="form-control validate[required]" type="text"  
				value="<?php if($edit){ echo esc_attr($result->invoice_date);}
				elseif(isset($_POST['invoice_date'])) echo esc_attr($_POST['invoice_date']);?>" name="invoice_date">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="due_date">
			<?php esc_html_e('Due Date','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				
				<input id="due_date" class="form-control validate[required]" type="text"  
				value="<?php if($edit){ echo esc_attr($result->due_date);}
				elseif(isset($_POST['due_date'])) echo esc_attr($_POST['due_date']);?>" name="due_date">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="amount">
			<?php esc_html_e('Amount','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="amount" class="form-control validate[required,custom[number]]" type="text"  
				value="<?php if(isset($_POST['amount'])) echo esc_attr($_POST['amount']); ?>" name="amount">
			</div>
		</div>
		<div id="charges_entry">
			<div class="form-group">
				<input type="hidden" id="increament_val" name="increament_val" value="1">
				<label class="col-sm-2 control-label" for="income_entry"><?php esc_html_e('Tax','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-4">
				<select name="tax_title[]" id="1" class="form-control valid tax_selection">
					<option value=""><?php esc_html_e('Select Tax','apartment_mgt');?></option>
					<?php $obj_tax =new Amgt_Tax;
					$tax_data= $obj_tax->Amgt_get_all_tax();
						 if(!empty($tax_data))
						 {
							foreach ($tax_data as $retrieved_data){ ?>
								<option value="<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_html($retrieved_data->tax_title);?></option>
						<?php }
						 }	?>
				</select>
			</div>
				<div class="col-sm-2">
					<input id="tax_entry_1" class="form-control validate[required] text-input" type="text" value="" name="tax_entry[]" placeholder="Tax">
				</div>
				<div class="col-sm-2">
					<input id="tax_amount_1" class="form-control validate[required] text-input" type="text" value="" name="tax_amount_entry[]" placeholder="Tax Amount">
				</div>
				<div class="col-sm-2">
				<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
				<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
				</button>
				</div>
			</div>	
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="expense_entry"></label>
			<div class="col-sm-3">
				<button id="add_new_entry" class="btn btn-defaultbtn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php esc_html_e('Add More Tax','apartment_mgt'); ?>
				</button>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="taxamount">
			<?php esc_html_e('Tax Amount','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="taxamount" value="0" class="form-control validate" type="text"  
				 name="tax_amount">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="taxamount">
			<?php esc_html_e('Total Amount','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="totalamount" value="0" class="form-control validate" type="text"  
				 name="total_amount">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="desc"><?php esc_html_e('Description','apartment_mgt');?></label>
			<div class="col-sm-8">
				 <textarea name="description" class="form-control text-input"><?php if($edit) echo esc_textarea($result->description);?></textarea>
			</div>
		</div>
		
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" 
			value="<?php if($edit){ esc_html_e('Generate Invoice','apartment_mgt'); }else{ esc_html_e('Generate Invoice','apartment_mgt');}?>" 
			name="generate_invoice" 
			class="btn btn-success"/>
        </div>
		
        </form>
        </div>
        </div>
        </div>
		
<script>
	// CREATING BLANK INVOICE ENTRY
   	var blank_custom_label ='';
   	function add_entry()
   	{
		increament_val = $('#increament_val').val();
		
		increamentval= parseInt(increament_val) + 1;
		$('#increament_val').val(increamentval);
		blank_custom_label='<div class="form-group">';
		blank_custom_label+='<label class="col-sm-2 control-label" for="income_entry"><?php esc_html_e('Tax','apartment_mgt');?><span class="require-field">*</span></label>';
		blank_custom_label+='<div class="col-sm-4">';
		blank_custom_label+='<select name="tax_title[]" id="'+increamentval+'" class="form-control valid tax_selection">';
		blank_custom_label+='<option value=""><?php esc_html_e('Select Tax','apartment_mgt');?></option>';
		<?php $obj_tax =new Amgt_Tax;
					$tax_data= $obj_tax->Amgt_get_all_tax();
						 if(!empty($tax_data))
						 {
							foreach ($tax_data as $retrieved_data){ ?>
		blank_custom_label+='<option value="<?php echo $retrieved_data->id;?>"><?php echo $retrieved_data->tax_title;?></option>';
							<?php }
						 } ?>
		blank_custom_label+='</select>';
		blank_custom_label+='</div>';
		blank_custom_label+='<div class="col-sm-2">';
		blank_custom_label+='<input id="tax_entry_'+increamentval+'" class="form-control validate[required] text-input" type="text" value="" name="tax_entry[]" placeholder="Tax">';
		blank_custom_label+='</div>';
		blank_custom_label+='<div class="col-sm-2">';
		blank_custom_label+='<input id="tax_amount_'+increamentval+'" class="form-control validate[required] text-input" type="text" value="" name="tax_amount_entry[]" placeholder="Tax Amount">';
		blank_custom_label+='</div>';
		blank_custom_label+='<div class="col-sm-2">';
		blank_custom_label+='<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">';
		blank_custom_label+='<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>';
		blank_custom_label+='</button>';
		blank_custom_label+='</div>';
		blank_custom_label+='</div>';
   		$("#charges_entry").append(blank_custom_label);
   		//alert("hellooo");
   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n)
	{
		//if(confirm("Are you sure want to delete this record?"))
			if(confirm(language_translate.add_remove))
		{
			n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		}	
   	}
</script>
<?php 
		die();
}
//----------------------- UNIT MEMBER VIEW ----------------//
function amgt_unit_member_view()
{
	$unit_name = $_REQUEST['unit_name'];
	$building_id = $_REQUEST['building_id'];
	
	$user_query = new WP_User_Query( 
    array(
        'meta_query'    => array(
            'relation'  => 'AND',
            array( 
                'key'     => 'unit_name',
                'value'   => $unit_name,
            ),
            array(
                'key'     => 'building_id',
                'value'   => $building_id,
                'compare' => '='
            )
        )
    ));
	$allmembers = $user_query->get_results();
		
	?>
	<div class="form-group"> 	<a href="#" class="close-btn badge badge-success pull-right">X</a>
		<h4 class="modal-title" id="myLargeModalLabel">
			<?php echo  esc_html__('Unit Members','apartment_mgt');?>
		</h4>
	</div>
	<hr>
	<div class="panel-body">
		<div class="slimScrollDiv">
			<div class="inbox-widget slimscroll">
			<?php 
			if(!empty($allmembers))
			foreach ($allmembers as $retrieved_data)
			{
			?>
				<div class="inbox-item" id="cat-<?php echo $retrieved_data->ID;?>">
					<div class="inbox-item-img">
			<?php 
				$uid=$retrieved_data->ID;
				$userimage=get_user_meta($uid, 'amgt_user_avatar', true);
				$member_type=get_user_meta($uid, 'member_type', true);
				if($userimage=='')
				{
					echo '<img src='.get_option( 'amgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
				}
				else
				 {
					echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';	
				 }
			?>
				</div>
				
				
				<p class="col-sm-3 inbox-item-author ">
				<a  class="margin_left_50" href="?page=amgt-member&tab=viewmember&action=view&member_id=<?php echo esc_attr($retrieved_data->ID);?>"><?php echo amgt_get_display_name($retrieved_data->ID); ?></a>				
				</p>
				<p class="col-sm-3 inbox-item-author txt_color_frontend">			
				<span class="txt_color txt_color_frontend">(<?php echo amgt_get_member_status_label($member_type);?>)</span> 
				</p>
				<?php if (is_super_admin ())
					{?>
				        <div class="col-sm-3"><a href="?page=amgt-member&tab=viewmember&action=view&member_id=<?php echo esc_attr($retrieved_data->ID);?>"  class="btn btn-default"><i class="fa fa-eye"></i><?php esc_html_e('View Detail','apartment_mgt');?></a></div>
						<p class="col-sm-2"><button id="delete_unitmember" class="btn btn-default margin_top_10_res delete_group_member"  mem_id="<?php echo esc_attr($retrieved_data->ID);?>" type="button">
						<i class="fa fa-trash"></i> <?php esc_html_e('Delete','apartment_mgt');?></button></p>
			  <?php } 
			  else
			  { ?>
				<div class="col-sm-3"><a href="?apartment-dashboard=user&page=member&tab=viewmember&action=view&member_id=<?php echo esc_attr($retrieved_data->ID);?>"  class="btn btn-default"><i class="fa fa-eye view_eye"></i><?php esc_html_e('View Detail','apartment_mgt');?></a></div>  
			 <?php  } ?>
				</div>
				
			<?php 
			}
			else 
			{
				?>
			<p><?php esc_html_e('No any member yet.','apartment_mgt');?></p>
			<?php
			} 
			?>
			</div>
		</div>
		
	</div>
	
	
<?php 
	die();
} 
//------------------------ UNIT MEMBER HISTORY VIEW -------------------//
function amgt_unit_member_history_view()
{
	global $wpdb;
	$unit_name = $_REQUEST['unit_name'];	
	$building_id = $_REQUEST['building_id'];	
	$table_amgt_unit_occupied_history = $wpdb->prefix . 'amgt_unit_occupied_history';
	$allmembers = $wpdb->get_results("SELECT * FROM $table_amgt_unit_occupied_history where unit_name='$unit_name' AND building_id='$building_id' ORDER BY id DESC");		
	?>
	<div class="form-group"> 	<a href="#" class="close-btn badge badge-success pull-right">X</a>
		<h4 class="modal-title" id="myLargeModalLabel">
			<?php echo  esc_html__('Unit Members ','apartment_mgt');?>
		</h4>
	</div>
	<hr>
	<div class="panel-body">
		<div class="slimScrollDiv">
			<div class="inbox-widget slimscroll">	
				<?php
				if(!empty($allmembers))
				{
				?>
				<table class="table table-bordered border_colleps" width="100%" border="1">
						<thead>
							<tr>								
								<th class="text-center"> <?php esc_html_e('Building Name','apartment_mgt');?></th>
								<!--<th align="center"><?php esc_html_e('Unit Category Name','apartment_mgt');?> </th>-->								
								<th align="center"><?php esc_html_e('Unit Name','apartment_mgt');?> </th>								
								<th align="center"><?php esc_html_e('Member Name','apartment_mgt');?> </th>								
								<th align="center"><?php esc_html_e('Email','apartment_mgt');?> </th>								
								<th align="center"><?php esc_html_e('Mobile Number','apartment_mgt');?> </th>								
								<th align="center"><?php esc_html_e('Address','apartment_mgt');?> </th>								
								<th align="center"><?php esc_html_e('Occupied From Date','apartment_mgt');?> </th>								
								<th align="center"><?php esc_html_e('Occupied To Date','apartment_mgt');?> </th>								
							</tr>
						</thead>
						<tbody>
						<?php 
						if(!empty($allmembers))
						{	
							foreach ($allmembers as $retrieved_data)
							{
								$member_contact_details=array();
							    $member_contact_details=json_decode($retrieved_data->member_contact_details);
								if(!empty($member_contact_details))
								{	
									foreach ($member_contact_details as $data)
									{
										$email=$data->email;
										$mobile=$data->mobile;
										$address=$data->address;
									}
								}
								
							?>
									<tr>
										<td class="text-center"><?php echo get_the_title($retrieved_data->building_id);?></td>
										
										<td class="text-center"><?php echo esc_html($retrieved_data->unit_name);?></td>
										<td class="text-center"><?php echo esc_html($retrieved_data->member_name);?></td>
										<td class="text-center"><?php echo esc_html($email);?></td>
										<td class="text-center"><?php echo esc_html($mobile);?></td>
										<td class="text-center"><?php echo esc_html($address);?></td>
										<td class="text-center"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->occupied_from_date));?></td>
										<td class="text-center"><?php if(!empty($retrieved_data->occupied_to_date)){echo date(amgt_date_formate(),strtotime($retrieved_data->occupied_to_date));}?></td>
									</tr>
							<?php
							}
						}
						?>	
						</tbody>
				</table>
			<?php
			}
			else
			{
				echo esc_html_e('No any member yet.','apartment_mgt');
			}		
			?>	
			</div>
		</div>
		
	</div>
	
<?php 
	die();
} 
//--------------- UNIT DOCUMENT VIEW ------------------//
function amgt_unit_document_view()
{
	$curr_user_id=get_current_user_id();
	$obj_apartment=new Apartment_management($curr_user_id);
	$obj_doc = new Amgt_Document;
	$unit_name = $_REQUEST['unit_name'];	
	$building_id = $_REQUEST['building_id'];	
	$alldocuments = $obj_doc->amgt_get_units_all_documents_new($_REQUEST['unit_name'],$_REQUEST['building_id']);
		
	?>
	<div class="form-group"> 	<a href="#" class="close-btn badge badge-success pull-right">X</a>
		<h4 class="modal-title" id="myLargeModalLabel">
			<?php echo  esc_html__('Unit Documents','apartment_mgt');?>
		</h4>
	</div>
	<hr>
	<div class="panel-body">
		<div class="slimScrollDiv">
			<table class="table">
			
			<?php 
			if(!empty($alldocuments))
			{?>
				<tr>
					<th><?php esc_html_e('Document Title','apartment_mgt');?></th>
					<th><?php esc_html_e('Unit Name','apartment_mgt');?></th>
					<th><?php esc_html_e('Document Submitted Date','apartment_mgt');?></th>
					<th><?php esc_html_e('Action','apartment_mgt');?></th>
				</tr>
				<?php foreach ($alldocuments as $retrieved_data)
				{
					//$document=$obj_doc->amgt_get_member_document($retrieved_data->ID);
					if(!empty($retrieved_data))
					{
					?>
						<tr>			
							<td><?php echo esc_html($retrieved_data->doc_title);?></td>
							<td><?php echo esc_html($retrieved_data->unit_name);?></td>
							<td><?php echo date(amgt_date_formate(),strtotime($retrieved_data->created_date));?></td>
							<td class="view_document_eye"><a target="_blank" href="<?php echo $retrieved_data->document_content; ?>"><button class="btn btn-default margin_top_5" type="button">
							<i class="fa fa-eye view_eye"></i> <?php esc_html_e('View','apartment_mgt');?></button></a></td>
						</tr>
						<?php	
					} 
					else { ?> 
						<p><?php esc_html_e('No any Documents yet.','apartment_mgt');?></p>
					<?php } 
				} 
			}
			else 
			{
				?>
			<p><?php esc_html_e('No any Documents yet.','apartment_mgt');?></p>
			<?php
			} 
			?>
			</table>
			</div>
		</div>
	</div>
<?php 
	die();
} 
//----------------- REMOVE UNIT MEMBER -------------------//
function amgt_remove_unit_member()
{
	$user_id=$_REQUEST['member_id'];
	delete_user_meta( $user_id, 'unit_name');
	die();
}
//------------------- REMOVE ALREADY OCCUPIED MEMBER ----------------//
function amgt_remove_allready_occupied_member()
{
	$user_id=$_REQUEST['member_id'];
	$user_data = get_userdata($user_id);
	$member_name=$user_data->display_name;
	global $wpdb;
	 	
	$table_name = $wpdb->prefix . 'usermeta';
	$table_amgt_unit_occupied_history = $wpdb->prefix . 'amgt_unit_occupied_history';
 	$result=$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE user_id= %d",$user_id));
	$retuenval=wp_delete_user( $user_id );
	
	$history_result = $wpdb->get_row("SELECT id FROM $table_amgt_unit_occupied_history where member_name='$member_name'");
	
	$whereid['id']=$history_result->id;
	$historydata['occupied_to_date']=date('Y-m-d');
	$result_update_history=$wpdb->update( $table_amgt_unit_occupied_history, $historydata ,$whereid);
	
	die();
}
//---------------- LOAD MEMBER Designation --------------//
function amgt_load_member_designation()
{
	$status=$_REQUEST['check_status'];
	if($status=='checked')
	{  ?>
		
			<div class="col-sm-6">
				<select class="form-control validate[required] designation_cat" name="designation_id">
				<option value=""><?php esc_html_e('Select Designation','apartment_mgt');?></option>
				<?php 
				if($edit)
					$category =$result->designation_id;
				elseif(isset($_REQUEST['designation_id']))
					$category =$_REQUEST['designation_id'];  
				else 
					$category = "";
				
				$activity_category=amgt_get_all_category('designation_cat');
				if(!empty($activity_category))
				{
					foreach ($activity_category as $retrive_data)
					{
						echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
					}
				} ?>
				</select>
			</div>
			<div class="col-sm-3"><button id="addremove" model="designation_cat"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
		
		
	<?php }
	die();
}
 
//VIEW INVOICE  FUNCTION BY INVOICE TYPE
function amgt_invoice_view()
{
	
	$obj_payment= new Amgt_Accounts();
	$obj_tax =new Amgt_Tax;
	if($_REQUEST['invoice_type']=='invoice')
	{	

		$invoice_data=$obj_payment->amgt_get_single_invoice_by_id($_REQUEST['idtest']);	
		$old_invoice_id=$invoice_data->invoice_id;
		if(isset($old_invoice_id))
		{
		  $invoice_old_data=$obj_payment->amgt_get_single_old_invoice_by_id($old_invoice_id);
		  $invoice_title=$invoice_old_data->title;
		}
		else
		{
			$invoice_title='';
		}
		
	}	
	if($_POST['invoice_type']=='expense')
	{
		$expense_data=$obj_payment->amgt_get_single_expense($_POST['idtest']);
	}
	
	?>	
	<style>
	.color_white  {
    font-weight: normal !important;
    padding: 10px !important;
	color: #f9fbfc !important;
	
	</style>
	
	<div class="modal-header padding_15">
		<a href="#" class="bill-close-btn badge badge-success pull-right">X</a>		
	</div>
	<div class="modal-body invoice_body">
		<div id="invoice_print">
			<img class="invoicefont1"  src="<?php echo plugins_url('/apartment-management/assets/images/invoice.jpg'); ?>" width="100%">
			<div class="main_div">					
				<table class="width_100" border="0">					
					<tbody>
						<tr>
							<td class="width_1">
								<img class="system_logo"  src="<?php echo get_option( 'amgt_system_logo' ); ?>">
							</td>							
							<td class="only_width_20">
								<?php
								 echo "A. ".chunk_split(get_option( 'amgt_apartment_address' ),30,"<BR>")."<br>"; 
								 echo "E. ".get_option( 'amgt_email' )."<br>"; 
								 echo "P. " .get_option( 'amgt_contact_number' )."<br>"; 
								?> 
							</td>
							<td align="right" class="width_24">
							</td>
						</tr>
					</tbody>
				</table>
				<table class="width_50" border="0">
					<tbody>				
						<tr>
							<td colspan="2" class="billed_to" align="center">								
								<h3 class="billed_to_lable" ><?php esc_html_e('| Bill To.','apartment_mgt');?> </h3>
							</td>
							<td class="width_40">								
							<?php 
								if(!empty($expense_data))
								{
								   echo "<h3 class='display_name'>".chunk_split(ucwords($expense_data->vender_name),30,"<BR>"). "</h3>"; 
								}
								else
								{
									if(!empty($invoice_data))
										$member_id=$invoice_data->member_id;
									 
									$patient=get_userdata($member_id);
									echo "<h3 class='display_name'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>";
									$address=get_user_meta( $member_id,'address',true);								
									echo chunk_split($address,30,"<BR>"); 	
									echo get_user_meta( $member_id,'city_name',true ).","; 
									echo get_user_meta( $member_id,'zip_code',true )."<br>"; 
									echo get_user_meta( $member_id,'mobile',true )."<br>"; 
								}
							?>			
							</td>
						</tr>									
					</tbody>
				</table>
					<?php 					
					if(!empty($invoice_data))
					{
						$issue_date=date(amgt_date_formate(),strtotime($invoice_data->created_date));						
						$payment_status=$invoice_data->payment_status;
						$invoice_no=$invoice_data->invoice_no;
					}					
					if(!empty($expense_data))
					{						
						$issue_date=date(amgt_date_formate(),strtotime($expense_data->payment_date));						
						$payment_status="Paid";						
					}	
					
					?>
				<table class="width_50" border="0">
					<tbody>				
						<tr>	
							<td class="width_30">
							</td>
							<td class="width_20" align="left">
								<?php
								if($_POST['invoice_type']=='invoice')
								{
								?>	
									<h3 class="invoice_lable"><?php echo esc_html__('INVOICE','apartment_mgt')." </br>".get_option('invoice_prefix').''.$invoice_no;?></h3>								
								<?php
								}								
								?>								
								<h5> <?php   echo esc_html__('Date','apartment_mgt')." : ".$issue_date; ?></h5>
								<h5><?php echo esc_html__('Status','apartment_mgt')." : ". __($payment_status,'apartment_mgt');?></h5>
									<?php
									if(!empty($invoice_data->charges_id))
									{ 
										$charge_period=amgt_get_charge_period_by_id($invoice_data->charges_id);
									}
									if($_POST['invoice_type']=='invoice')
									{
									   $invoice_length=strlen($invoice_data->invoice_no);
										if($invoice_length == '5')
										{ 
											if($charge_period!='0')
											{
											?>
												<h4><?php esc_html_e('Billing Period','apartment_mgt');?></h4>
												<h5><?php echo esc_html__('From','apartment_mgt')." : ". __(date(amgt_date_formate(),strtotime($invoice_data->start_date)),'apartment_mgt');?></h5>				
												<h5><?php echo esc_html__('To','apartment_mgt')." : ". __(date(amgt_date_formate(),strtotime($invoice_data->end_date)),'apartment_mgt');?></h5>				
												<!-- <h5> <?php echo esc_html__('From','apartment_mgt')." (".date(amgt_date_formate(),strtotime($invoice_data->start_date)); echo esc_html__(')'); ?> </br> <?php echo esc_html__(' To','apartment_mgt')." (".date(amgt_date_formate(),strtotime($invoice_data->end_date));?>)</h5>	 -->										
											<?php		
											}
										}
									}
									?>
							</td>							
						</tr>									
					</tbody>
				</table>						
				<?php
				
				$invoice_length=strlen($invoice_data->invoice_no);
				if($_POST['invoice_type']=='invoice')
				{					
					if(empty($invoice_data->invoice_no))
					{
						$charge_type=get_the_title($invoice_data->charges_type_id);
					}
					else
					{
						if($invoice_length == '9')
						{
							
						   $charge_type=$invoice_title;
						}
						elseif($invoice_length == '5')
						{	
							if($invoice_data->charges_type_id=='0')
							{							
								$charge_type='Maintenance Charges';
							}
							else
							{
								$charge_type=get_the_title($invoice_data->charges_type_id);
							}					
						}
					}	
					
				?>	
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable"><?php _e("$charge_type","apartment_mgt");?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>	
				<?php 	
				}				
				else
				{ ?>
					<table class="width_100">	
						<tbody>		
							<tr>
								<td>						
									<h3 class="entry_lable"><?php esc_html_e('Expense Entries','apartment_mgt');?></h3>
								</td>
							</tr>	
						</tbody>	
					</table>	
				<?php 	
				}
			   ?>					
				<table class="table table-bordered" class="width_93" border="1">
					<thead class="entry_heading">					
							<tr>
								<th class="color_white align_center">#</th>
								<th class="color_white align_center"> <?php esc_html_e('Date','apartment_mgt');?></th>

								<th class="color_white"><?php esc_html_e('Entry','apartment_mgt');?> </th>
								<?php
								if(!empty($invoice_data->charges_id))
								{
									$charge_cal_by=amgt_get_invoice_charges_calculate_by($invoice_data->charges_id);
								}
								$unit_measerment_type=get_option( 'amgt_unit_measerment_type' );
								if($charge_cal_by->charges_calculate_by=='measurement_charge')
								{									
								?>
								<th class="width_3 color_white"><?php esc_html_e('Measurement','apartment_mgt');?>(<?php  _e($unit_measerment_type,'apartment_mgt'); ?>)</th>
								
								<th class="color_white"><?php esc_html_e('Charge Per','apartment_mgt');?>(<?php  _e($unit_measerment_type,'apartment_mgt'); ?>)</th>
								<?php
								}
								?>
								<th class="color_white align_right"><?php esc_html_e('Amount','apartment_mgt');?></th>														
							</tr>						
					</thead>
					<tbody>
					<?php
						$id=1;
						if(!empty($invoice_data))
						{
								
							$invoice_id=$invoice_data->charges_id;
							if(empty($invoice_data->invoice_no))
							{
								$all_entry=json_decode($invoice_data->charges_payment);
								$entry_total_amount='0';	
								foreach($all_entry as $entry)
								{									
								 ?>
									<tr class="entry_list">
										<td class="align_center"><?php echo esc_html($id);?></td>
										<td class="align_center"><?php echo date(amgt_date_formate(),strtotime($invoice_data->created_date));?></td>
										<td><?php echo esc_html($entry->entry);?></td>
										<td class="align_right"> <?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round($entry->amount); ?></td>
									</tr>
								<?php
								$entry_total_amount+=$entry->amount;
								$id=$id+1;
								}
							}
							else	
							{	
								if($invoice_length == '9')
								{
									?>
									<tr class="entry_list">
										<td class="align_center"><?php echo $id;?></td>
										<td class="align_center"><?php echo date(amgt_date_formate(),strtotime($invoice_data->created_date));?></td>
										<td><?php echo $invoice_title;?></td>
										<td class="align_right"> <?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round($invoice_data->invoice_amount); ?></td>
									</tr>
									<?php
								}
								else	
								{
									$charge_cal_by=amgt_get_invoice_charges_calculate_by($invoice_id);
									
									if($charge_cal_by->charges_calculate_by=='fix_charge')
									{
										$all_entry=json_decode($invoice_data->charges_payment);
										
										foreach($all_entry as $entry)
										{									
										 ?>
										<tr class="entry_list">
											<td class="align_center"><?php echo esc_html($id);?></td>
											<td class="align_center"><?php echo date(amgt_date_formate(),strtotime($invoice_data->created_date));?></td>
											<td><?php echo esc_html($entry->entry);?></td>
											<td class="align_right"> <?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round($entry->amount); ?></td>
										</tr>
										<?php
										$id=$id+1;
										}
									}
									else
									{
									?>
										<tr class="entry_list">
											<td class="align_center"><?php echo esc_html($id);?></td>
											<td class="align_center"><?php echo date(amgt_date_formate(),strtotime($invoice_data->created_date));?></td>
											<td><?php echo esc_html($charge_type);?></td>
											<?php									
											$measurement=amgt_get_single_member_unit_size($member_id);
											$charge=amgt_get_single_member_measurment_charge($invoice_data->charges_id);
											?>
											<td><?php echo esc_html($measurement);?></td>
											<td><?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo esc_html($charge);?></td>
											<?php
											$amount=$invoice_data->amount+$invoice_data->discount_amount;
											?>
											<td class="align_right"> <?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round($amount); ?></td>
										</tr>
									<?php							
									}
								}	
							}		
						}
					    if(!empty($expense_data))
					    { 
						?>
							<tr class="entry_list">
								<td class="align_center"><?php echo esc_html($id);?></td>
								<td class="align_center"><?php echo date(amgt_date_formate(),strtotime($expense_data->payment_date));?></td>
								<td><?php echo get_the_title($expense_data->type_id); ?> </td>
								<td class="align_right"> <?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round($expense_data->amount); ?></td>								
							</tr>
					  <?php 
						} 
						?>						
					</tbody>
				</table>
				<table class="width_54" border="0">
					<tbody>
						<?php						
						if(!empty($expense_data))
						{							
							$subtotal_amount=$expense_data->amount;
							$grand_total=$subtotal_amount;
						}
						if(!empty($invoice_data))
						{								
							$bank_name=get_option( 'amgt_bank_name' );
							$account_holder_name=get_option( 'amgt_account_holder_name' );
							$account_number=get_option( 'amgt_account_number' );
							$account_type=get_option( 'amgt_account_type' );
							$ifsc_code=get_option( 'amgt_ifsc_code' );
							$swift_code=get_option( 'amgt_swift_code' );
							
							if(empty($invoice_data->invoice_no))
							{
								  $discount=$invoice_data->discount_amount;
								  $total_amount=round($entry_total_amount);
								  $due_amount='0';
								  $subtotal_amount=round($entry_total_amount);
								 
								  $tax_amount='0';
								  $grand_total=round($entry_total_amount)-round($discount);
								  $paid_amount=round($entry_total_amount)-round($discount);						
							}	
							else
							{		
								if($invoice_length == '9')
								{
								  $total_amount=round($invoice_data->invoice_amount);
								  $due_amount=round($invoice_data->invoice_amount) - round($invoice_data->paid_amount);
								  $subtotal_amount=round($invoice_data->invoice_amount);
								  $discount='0';
								  $tax_amount='0';
								  $grand_total=round($invoice_data->invoice_amount);
								  $paid_amount=round($invoice_data->paid_amount);
								}
								else
								{
									$due_amount=$invoice_data->due_amount;
									$total_amount=$invoice_data->total_amount;
									$after_sub_discount_amount=$invoice_data->amount;
									$discount=$invoice_data->discount_amount;
									$subtotal_amount=$after_sub_discount_amount+$discount;
									$tax_amount=$invoice_data->tax_amount;
									$paid_amount=$invoice_data->paid_amount;
									$grand_total=$total_amount;
								}
							}	
						}
						?>
						<tr>
							<td class="width_70 align_right"><h4 class="margin"><?php esc_html_e('Subtotal :','apartment_mgt');?></h4></td>
							<td class="align_right"> <h4 class="margin margin_right_14"><span><?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?></span><?php echo round($subtotal_amount);?></h4></td>
						</tr>
						<?php
						if($_POST['invoice_type']!='expense')
						{							
							?>	
							<tr>
								<td class="width_70 align_right"><h4 class="margin"><?php esc_html_e('Discount Amount :','apartment_mgt');?></h4></td>
								<td class="align_right"> <h4 class="margin margin_right_14"><span ><?php  echo "-";echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?></span><?php if(!empty($discount)){ echo round($discount); }else{ echo '0'; } ?></h4></td>
							</tr>
							<tr>
								<td class="width_70 align_right"><h4 class="margin"><?php esc_html_e('Tax Amount :','apartment_mgt');?></h4></td>
								<td class="align_right"><h4 class="margin margin_right_14"> <span ><?php  echo "+";echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?></span><?php if(!empty($tax_amount)){ echo round($tax_amount); }else{ echo '0'; }?></h4></td>
							</tr>							
							<tr>
								<td class="width_70 align_right"><h4 class="margin"><?php esc_html_e('Due Amount :','apartment_mgt');?></h4></td>
								<td class="align_right"> <h4 class="margin margin_right_14"><span ><?php  echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?></span><?php echo round(abs($due_amount)); ?></h4></td>
							</tr>
							<tr>
								<td class="width_70 align_right"><h4 class="margin"><?php esc_html_e('Paid Amount :','apartment_mgt');?></h4></td>
								<td class="align_right"> <h4 class="margin margin_right_14"><span ><?php  echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?></span><?php if(!empty($paid_amount)){ echo round($paid_amount); }else{ echo '0'; }?></h4></td>
							</tr>
						<?php
						}
						?>
						<tr>							
							<td class="width_70 align_right grand_total_lable"><h3 class="color_white margin"><?php esc_html_e('Grand Total :','apartment_mgt');?></h3></td>
							<td class="align_right grand_total_amount"><h3 class="color_white margin">  <span><?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>  <?php echo round($grand_total); ?> </span></h3></td>
						</tr>							
					</tbody>
				</table>
				<?php
				if($_POST['invoice_type']!='expense')
				{
					if(!empty($bank_name))
					{
				?>		
					<table class="width_46" border="0">
						<tbody>						
							<tr>
								<td colspan="2">
									<h3 class="payment_method_lable"><?php esc_html_e('Payment Method','apartment_mgt');?>
									</h3>
								</td>								
							</tr>							
							<tr>
								<td class="width_31 font_12"><?php esc_html_e('Bank Name ','apartment_mgt');  ?></td>
								<td class="font_12">: <?php echo $bank_name;?></td>
							</tr>
							<tr>
								<td class="width_31 font_12"><?php esc_html_e('A/C Holder Name ','apartment_mgt'); ?></td>
								<td class="font_12">: <?php echo esc_html($account_holder_name);?></td>
							</tr>
							<tr>
								<td class="width_31 font_12"><?php esc_html_e('Account No ','apartment_mgt'); ?></td>
								<td class="font_12">: <?php echo esc_html($account_number);?></td>
							</tr>
							<tr>
								<td class="width_31 font_12"><?php esc_html_e('Account Type ','apartment_mgt'); ?></td>
								<td class="font_12">: <?php echo esc_html($account_type);?></td>
							</tr>
							<tr>
								<td class="width_31 font_12"><?php esc_html_e('IFSC Code ','apartment_mgt'); ?></td>
								<td class="font_12">: <?php echo esc_html($ifsc_code);?></td>
							</tr>
							
							<tr>
								<td class="width_31 font_12"> <?php esc_html_e('Paypal ','apartment_mgt'); ?></td>
								<td class="font_12">: <?php echo get_option( 'apartment_paypal_email' );?></td>
							</tr>				
						</tbody>
					</table>
					<?php
					}					
					$gst_number=get_option( 'amgt_gst_number' );
					$tax_id=get_option( 'amgt_tax_id' );
					$corporate_id=get_option( 'amgt_corporate_id' );
					if(!empty($gst_number) || !empty($tax_id) || !empty($corporate_id))
					{
					?>							
					<table class="table_invoice gst_details" border="0">
					<thead>
						<tr>								
							<th class="align_center"> <?php esc_html_e('GST Number','apartment_mgt');?></th>
							<th class="align_center"> <?php esc_html_e('TAX ID','apartment_mgt');?></th>
							<th class="align_center"> <?php esc_html_e('Corporate ID','apartment_mgt');?></th>
						</tr>	
					</thead>
					<tbody>
						<tr>								
							<td class="align_center"><?php echo esc_html($gst_number);?></td>
							<td class="align_center"><?php echo esc_html($tax_id);?></td>
							<td class="align_center"><?php echo esc_html($corporate_id);?></td>
						</tr>	
					</tbody>
					</table>							
				<?php
					}
				}
				?>				
			</div>
		</div>
		<div class="print-button pull-left">
			<a  href="?page=invoice&print=print&invoice_id=<?php echo $_REQUEST['idtest'];?>&invoice_type=<?php echo $_POST['invoice_type'];?>" target="_blank"class="btn btn-success"><?php esc_html_e('Print','apartment_mgt');?></a>
			<?php
			if($_POST['invoice_type']!='expense')
			{
			?>	
				<a href="?page=invoinvoiceice&invoicepdf=invoicepdf&invoice_id=<?php echo $_REQUEST['idtest'];?>&invoice_type=<?php echo $_POST['invoice_type'];?>" target="_blank" class="btn btn-success"><?php esc_html_e('PDF','apartment_mgt');?></a>
			<?php
			}
			?>
		</div>
	</div>
		
	<?php 
	die();
}
//---------------- INVOICE PRINT ----------------//
function amgt_invoice_print($id,$type)
{
	$obj_payment= new Amgt_Accounts();
	$obj_tax =new Amgt_Tax;
	if($type=='invoice')
	{		
		$invoice_data=$obj_payment->amgt_get_single_invoice_by_id($id);
		if(isset($old_invoice_id))
		{
        $old_invoice_id=$invoice_data->invoice_id;
		$invoice_old_data=$obj_payment->amgt_get_single_old_invoice_by_id($old_invoice_id);
		$invoice_title=$invoice_old_data->title;
		}		
	}	
	if($type=='expense')
	{
		$expense_data=$obj_payment->amgt_get_single_expense($id);
	}
	  echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/style.css', __FILE__).'"></link>';
	?>	
	<style type="text/css" media="print">
@media print {
   .footer,
   #non-printable {
       display: none !important;
   }
   #printable {
       display: block;
   }
}
</style>	
	<div class="modal-header padding_15">		
	</div>
	<div class="modal-body invoice_body">
		<div id="invoice_print1">
			<img class="invoicefont1"  src="<?php echo plugins_url('/apartment-management/assets/images/invoice.jpg'); ?>" width="100%">
			<div class="main_div">					
				<table class="width_100" border="0">					
					<tbody>
						<tr>
							<td class="width_1">
								<img class="system_logo_print"  src="<?php echo get_option( 'amgt_system_logo' ); ?>">
							</td>							
							<td class="only_width_20">
								<?php
								 echo "A. ".chunk_split(get_option( 'amgt_apartment_address' ),40,"<BR>").""; 
								 echo "E. ".get_option( 'amgt_email' )."<br>"; 
								 echo "P. " .get_option( 'amgt_contact_number' )."<br>"; 
								?> 
							</td>
							<td align="right" class="width_24">
							</td>
						</tr>
					</tbody>
				</table>
				<table class="width_50" border="0">
					<tbody>				
						<tr>
							<td colspan="2" class="billed_to" align="center">								
								<h3 class="billed_to_lable" ><?php esc_html_e('| Bill To.','apartment_mgt');?> </h3>
							</td>
							<td class="width_40">								
							<?php 
								if(!empty($expense_data))
								{
								   echo "<h3 class='display_name'>".chunk_split(ucwords($expense_data->vender_name),30,"<BR>"). "</h3>"; 
								}
								else
								{
									if(!empty($invoice_data))
										$member_id=$invoice_data->member_id;
									 
									$patient=get_userdata($member_id);
									echo "<h3 class='display_name'>".chunk_split(ucwords($patient->display_name),30,"<BR>"). "</h3>"; 
									$address=get_user_meta( $member_id,'address',true);								
									echo chunk_split($address,30,"<BR>"); 	
									echo get_user_meta( $member_id,'city_name',true ).","; 
									echo get_user_meta( $member_id,'zip_code',true )."<br>"; 
									echo get_user_meta( $member_id,'mobile',true )."<br>"; 
								}
							?>			
							</td>
						</tr>									
					</tbody>
				</table>
					<?php 					
					if(!empty($invoice_data))
					{
						$issue_date=date(amgt_date_formate(),strtotime($invoice_data->created_date));						
						$payment_status=$invoice_data->payment_status;
						$invoice_no=$invoice_data->invoice_no;
					}					
					if(!empty($expense_data))
					{						
						$issue_date=date(amgt_date_formate(),strtotime($expense_data->payment_date));						
						$payment_status="Paid";						
					}	
					
					?>
				<table class="width_50" border="0">
					<tbody>				
						<tr>	
							<td class="width_30">
							</td>
							<td class="width_20 padding_right_30" align="left">
								<?php
								if($type=='invoice')
								{
								?>	
									<h3 class="invoice_color"><?php echo esc_html__('INVOICE','apartment_mgt')." </br> ".get_option('invoice_prefix').''.$invoice_no;?></h3>								
								<?php
								}
								?>								
								<h5 class="invoice_date_status"> <?php   echo esc_html__('Date','apartment_mgt')." : ".$issue_date; ?></h5>
								<h5 class="invoice_date_status"><?php echo esc_html__('Status','apartment_mgt')." : ". __($payment_status,'apartment_mgt');?></h5>									
								<?php
								if(!empty($invoice_data->charges_id))
								{
									$charge_period=amgt_get_charge_period_by_id($invoice_data->charges_id);
									
									if($type=='invoice')
									{
										$invoice_length=strlen($invoice_data->invoice_no);
										if($invoice_length == '5')
										{ 
											if($charge_period!='0')
											{						
												?>
												<h4><?php esc_html_e('Billing Period','apartment_mgt');?></h4>
												<h5><?php echo esc_html__('From','apartment_mgt')." : ". __(date(amgt_date_formate(),strtotime($invoice_data->start_date)),'apartment_mgt');?></h5>				
												<h5><?php echo esc_html__('To','apartment_mgt')." : ". __(date(amgt_date_formate(),strtotime($invoice_data->end_date)),'apartment_mgt');?></h5>											
											<?php		
											}
										}
									}
								}	?>
							</td>							
						</tr>									
					</tbody>
				</table>						
				<?php
				if($type=='invoice')
				{ 
					if(empty($invoice_data->invoice_no))
					{
						$charge_type=get_the_title($invoice_data->charges_type_id);
					}
					else
					{
						if($invoice_length == '9')
						{
						   $charge_type=$invoice_title;
						}
						elseif($invoice_length == '5')
						{
							if($invoice_data->charges_type_id=='0')
							{							
								$charge_type='Maintenance Charges';
							}
							else
							{
								$charge_type=get_the_title($invoice_data->charges_type_id);
							}	
						}
					}	
				?>	
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3  class="entry_lable"><?php _e("$charge_type","apartment_mgt");?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>
				<?php 	
				}				
				else
				{ ?>
					<table class="width_100">	
						<tbody>	
							<tr>
								<td>
									<h3 class="entry_lable"><?php esc_html_e('Expense Entries','apartment_mgt');?></h3>
								</td>	
							</tr>	
						</tbody>
					</table>				
				<?php 	
				}
			   ?>					
				<table class="table table-bordered width_100 table_new" class="width_93" border="1">
					<thead class="entry_heading">					
							<tr>
								<th class="color_white align_center padding_color">#</th>
								<th class="color_white align_center padding_color"> <?php esc_html_e('Date','apartment_mgt');?></th>
								<th class="color_white align_left padding_color"><?php esc_html_e('Entry','apartment_mgt');?> </th>
								<?php
								if(!empty($invoice_data->charges_id))
								{
									$charge_cal_by=amgt_get_invoice_charges_calculate_by($invoice_data->charges_id);
									$unit_measerment_type=get_option( 'amgt_unit_measerment_type' );
									
									if($charge_cal_by->charges_calculate_by=='measurement_charge')
									{									
										?>
										<th class="width_3 color_white padding_color"><?php esc_html_e('Measurement','apartment_mgt');?>(<?php  _e($unit_measerment_type,'apartment_mgt'); ?>)</th>
									
										<th class="color_white padding_color"><?php esc_html_e('Charge Per','apartment_mgt');?>(<?php  _e($unit_measerment_type,'apartment_mgt'); ?>)</th>
										<?php
									}
								}
								?>
								<th class="color_white align_right padding_color"><?php esc_html_e('Amount','apartment_mgt');?></th>														
							</tr>						
					</thead>
					<tbody>
					<?php
						$id=1;
						if(!empty($invoice_data))
						{	
							$invoice_id=$invoice_data->charges_id;
							if(empty($invoice_data->invoice_no))
							{
								$all_entry=json_decode($invoice_data->charges_payment);
								$entry_total_amount='0';	
								foreach($all_entry as $entry)
								{									
								 ?>
									<tr class="entry_list">
										<td class="align_center padding_color_black"><?php echo esc_html($id);?></td>
										<td class="align_center padding_color_black"><?php echo date(amgt_date_formate(),strtotime($invoice_data->created_date));?></td>
										<td class="padding_color_black"><?php echo esc_html($entry->entry);?></td>
										<td class="align_right padding_color_black"> <?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round($entry->amount); ?></td>
									</tr>
								<?php
								$entry_total_amount+=$entry->amount;
								$id=$id+1;
								}
							}
							else	
							{
								if($invoice_length == '9')
								{
									?>
									<tr class="entry_list">
										<td class="align_center padding_color_black"><?php echo esc_html($id);?></td>
										<td class="align_center padding_color_black"><?php echo date(amgt_date_formate(),strtotime($invoice_data->created_date));?></td>
										<td class="padding_color_black"><?php echo esc_html($invoice_title);?></td>
										<td class="align_right padding_color_black"> <?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round($invoice_data->invoice_amount); ?></td>
									</tr>
									<?php
								}
								else	
								{
									$charge_cal_by=amgt_get_invoice_charges_calculate_by($invoice_id);
									
									if($charge_cal_by->charges_calculate_by=='fix_charge')
									{
										$all_entry=json_decode($invoice_data->charges_payment);
										
										foreach($all_entry as $entry)
										{									
										 ?>
										<tr class="entry_list">
											<td class="align_center padding_color_black"><?php echo esc_html($id);?></td>
											<td class="align_center padding_color_black"><?php echo date(amgt_date_formate(),strtotime($invoice_data->created_date));?></td>
											<td class="padding_color_black"><?php echo esc_html($entry->entry);?></td>
											<td class="align_right padding_color_black"> <?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round($entry->amount); ?></td>
										</tr>
										<?php
										$id=$id+1;
										}
									}
									else
									{
									?>
										<tr class="entry_list">
											<td class="align_center padding_color_blackpadding_color_black"><?php echo esc_html($id);?></td>
											<td class="align_center padding_color_black"><?php echo date(amgt_date_formate(),strtotime($invoice_data->created_date));?></td>
											<td class="padding_color_black"><?php echo esc_html($charge_type);?></td>
											<?php									
											$measurement=amgt_get_single_member_unit_size($member_id);
											$charge=amgt_get_single_member_measurment_charge($invoice_data->charges_id);
											?>
											<td class="padding_color_black"><?php echo esc_html($measurement);?></td>
											<td class="padding_color_black"><?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo esc_html($charge);?></td>
											<?php
											$amount=$invoice_data->amount+$invoice_data->discount_amount;
											?>
											<td class="align_right padding_color_black"> <?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round($amount); ?></td>
										</tr>
									<?php							
									}
								}		
							}		
						}
					    if(!empty($expense_data))
					    { 
						?>
							<tr class="entry_list">
								<td class="align_center padding_color_black"><?php echo esc_html($id);?></td>
								<td class="align_center padding_color_black"><?php echo date(amgt_date_formate(),strtotime($expense_data->payment_date));?></td>
								<td class="padding_color_black"><?php echo get_the_title($expense_data->type_id); ?> </td>
								<td class="align_right padding_color_black"> <?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round($expense_data->amount); ?></td>								
							</tr>
					  <?php 
						} 
						?>						
					</tbody>
				</table>
				<table class="width_54" border="0">
					<tbody>
						<?php						
						if(!empty($expense_data))
						{							
							$subtotal_amount=$expense_data->amount;
							$grand_total=$subtotal_amount;
						}
						if(!empty($invoice_data))
						{								
							$bank_name=get_option( 'amgt_bank_name' );
							$account_holder_name=get_option( 'amgt_account_holder_name' );
							$account_number=get_option( 'amgt_account_number' );
							$account_type=get_option( 'amgt_account_type' );
							$ifsc_code=get_option( 'amgt_ifsc_code' );
							$swift_code=get_option( 'amgt_swift_code' );
							
							if(empty($invoice_data->invoice_no))
							{
							    $discount=$invoice_data->discount_amount;
								$total_amount=round($entry_total_amount);
								$due_amount='0';
								$subtotal_amount=round($entry_total_amount);
								 
								$tax_amount='0';
								$grand_total=round($entry_total_amount)-round($discount);
								$paid_amount=round($entry_total_amount)-round($discount);						
							}	
							else
							{							
								if($invoice_length == '9')
								{
									$total_amount=round($invoice_data->invoice_amount);
									$due_amount=round($invoice_data->invoice_amount) - round($invoice_data->paid_amount);
									$subtotal_amount=round($invoice_data->invoice_amount);
									$discount='0';
									$tax_amount='0';
									$grand_total=round($invoice_data->invoice_amount);
									$paid_amount=round($invoice_data->paid_amount);
								}
								else
								{
									$due_amount=$invoice_data->due_amount;
									$total_amount=$invoice_data->total_amount;
									$after_sub_discount_amount=$invoice_data->amount;
									if(!empty($invoice_data->discount_amount))
									{
										$discount=$invoice_data->discount_amount;
									}
									else
									{
										$discount=0;
									}
									$subtotal_amount=$after_sub_discount_amount+$discount;
									
									$tax_amount=$invoice_data->tax_amount;
									$paid_amount=$invoice_data->paid_amount;
									$grand_total=$total_amount;
								}
							}	
						}
						?>
						<tr>
							<h4><td class="width_70 align_right"><h4 class="margin"><?php esc_html_e('Subtotal :','apartment_mgt');?></h4></td>
							<td class="align_right"> <h4 class="margin"><span><?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?></span><?php echo round($subtotal_amount);?></h4></td>
						</tr>
						<?php
						if($type!='expense')
						{							
							?>	
							<tr>
								<td class="width_70 align_right"><h4 class="margin"><?php esc_html_e('Discount Amount :','apartment_mgt');?></h4></td>
								<td class="align_right"> <h4 class="margin"><span ><?php  echo "-";echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?></span><?php if(!empty($discount)){ echo round($discount); }else{ echo '0'; } ?></h4></td>
							</tr>
							<tr>
								<td class="width_70 align_right"><h4 class="margin"><?php esc_html_e('Tax Amount :','apartment_mgt');?></h4></td>
								<td class="align_right"><h4 class="margin"> <span ><?php  echo "+";echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?></span><?php if(!empty($tax_amount)){ echo round($tax_amount); }else{ echo '0'; }?></h4></td>
							</tr>							
							<tr>
								<td class="width_70 align_right"><h4 class="margin"><?php esc_html_e('Due Amount :','apartment_mgt');?></h4></td>
								<td class="align_right"> <h4 class="margin"><span ><?php  echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?></span><?php echo round(abs($due_amount)); ?></h4></td>
							</tr>
							<tr>
								<td class="width_70 align_right"><h4 class="margin"><?php esc_html_e('Paid Amount :','apartment_mgt');?></h4></td>
								<td class="align_right"> <h4 class="margin"><span ><?php  echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?></span><?php if(!empty($paid_amount)){ echo round($paid_amount); }else{ echo '0'; }?></h4></td>
							</tr>
						<?php
						}
						?>
						<tr>							
							<td class="width_70 align_right grand_total_lable1"><h3 class="color_white margin"><?php esc_html_e('Grand Total :','apartment_mgt');?></h3></td>
							<td class="align_right grand_total_amount1"><h3 class="color_white margin">  <span><?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>  <?php echo round($grand_total); ?> </span></h3></td>
						</tr>							
					</tbody>
				</table>
				<?php
				if($type!='expense')
				{
					if(!empty($bank_name))
					{
				?>		
					<table class="width_46" border="0">
						<tbody>						
							<tr>
								<td colspan="2">
									<h3 class="payment_method_lable"><?php esc_html_e('Payment Method','apartment_mgt');?>
									</h3>
								</td>								
							</tr>							
							<tr>
								<td class="width_31 font_12"><?php esc_html_e('Bank Name ','apartment_mgt');  ?></td>
								<td class="font_12">: <?php echo esc_html($bank_name);?></td>
							</tr>
							<tr>
								<td class="width_31 font_12"><?php esc_html_e('A/C Holder Name ','apartment_mgt'); ?></td>
								<td class="font_12">: <?php echo esc_html($account_holder_name);?></td>
							</tr>
							<tr>
								<td class="width_31 font_12"><?php esc_html_e('Account No ','apartment_mgt'); ?></td>
								<td class="font_12">: <?php echo esc_html($account_number);?></td>
							</tr>
							<tr>
								<td class="width_31 font_12"><?php esc_html_e('Account Type ','apartment_mgt'); ?></td>
								<td class="font_12">: <?php echo esc_html($account_type);?></td>
							</tr>
							<tr>
								<td class="width_31 font_12"><?php esc_html_e('IFSC Code ','apartment_mgt'); ?></td>
								<td class="font_12">: <?php echo esc_html($ifsc_code);?></td>
							</tr>
							
							<tr>
								<td class="width_31 font_12"> <?php esc_html_e('Paypal ','apartment_mgt'); ?></td>
								<td class="font_12">: <?php echo get_option( 'apartment_paypal_email' );?></td>
							</tr>				
						</tbody>
					</table>
					<?php
					}					
					$gst_number=get_option( 'amgt_gst_number' );
					$tax_id=get_option( 'amgt_tax_id' );
					$corporate_id=get_option( 'amgt_corporate_id' );
					if(!empty($gst_number) || !empty($tax_id) || !empty($corporate_id))
					{
					?>							
					<table class="table_invoice gst_details" border="0">
					<thead>
						<tr>								
							<th class="align_center"> <?php esc_html_e('GST Number','apartment_mgt');?></th>
							<th class="align_center"> <?php esc_html_e('TAX ID','apartment_mgt');?></th>
							<th class="align_center"> <?php esc_html_e('Corporate ID','apartment_mgt');?></th>
						</tr>	
					</thead>
					<tbody>
						<tr>								
							<td class="align_center"><?php echo esc_html($gst_number);?></td>
							<td class="align_center"><?php echo esc_html($tax_id);?></td>
							<td class="align_center"><?php echo esc_html($corporate_id);?></td>
						</tr>	
					</tbody>
					</table>							
				<?php
					}
				}
				?>				
			</div>
		</div>
	</div>
		
	<?php 
	die();		
}
//----------------- INVOICE PAYMENT RECEIPT PRINT --------------//
function amgt_invoice_payment_receipt_print($invoice_id,$member_id)
{
	$obj_tax =new Amgt_Tax;
	$obj_payment= new Amgt_Accounts();
	$invoice_data=$obj_payment->amgt_get_single_invoice_by_id($invoice_id);
	$payment_data=$obj_payment->amgt_invoice_payment_by_member($invoice_id,$member_id);
	echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/style.css', __FILE__).'"></link>';
	?>
<style type="text/css" media="print">
@media print {
   .footer,
   #non-printable {
       display: none !important;
   }
   #printable {
       display: block;
   }
}
</style>
	<div class="modal-header padding_17">	
		<h3 class="modal-title align_center payment_receipt"><?php esc_html_e('Invoice Payment Receipt','apartment_mgt');?></h3>
		
	</div>
	<div class="modal-body invoice_body">
		<div id="invoice_print1">
			<img class="invoicefont1"  src="<?php echo plugins_url('/apartment-management/assets/images/invoice.jpg'); ?>" width="100%">
			<div class="main_div">					
				<table class="width_100" border="0">					
					<tbody>
						<tr>
							<td class="width_1">
								<img class="system_logo"  src="<?php echo get_option( 'amgt_system_logo' ); ?>">
							</td>							
							<td class="only_width_20">
								<?php
								 echo esc_html__('Address','apartment_mgt') ."<br>".get_option( 'amgt_apartment_address' )."<br>"; 
								 echo esc_html__('Email','apartment_mgt')."<br>".get_option( 'amgt_email' )."<br>"; 
								 echo esc_html__('Phonenumber','apartment_mgt')."<br>".get_option( 'amgt_contact_number' )."<br>"; 
								?> 
							</td>
							<td align="right" class="width_24">
							</td>
						</tr>
					</tbody>
				</table>
				<table class="width_50" border="0">
					<tbody>				
						<tr>
							<td colspan="2" class="billed_to" align="center">								
								<h3 class="billed_to_lable" ><?php esc_html_e('| Bill To.','apartment_mgt');?> </h3>
							</td>
							<td class="width_40">								
							<?php 
								if(!empty($invoice_data))
									$member_id=$invoice_data->member_id;										
									
									$member=get_userdata($member_id);																	
									
									echo "<h3 class='display_name'>".ucwords($member->display_name). "</h3>"; 
									echo get_user_meta( $member_id,'address',true )."<br>"; 
									echo get_user_meta( $member_id,'city_name',true ).","; 
									echo get_user_meta( $member_id,'zip_code',true )."<br>"; 
									echo get_user_meta( $member_id,'mobile',true )."<br>"; 
								
							?>			
							</td>
						</tr>									
					</tbody>
				</table>
					<?php 					
					if(!empty($invoice_data))
					{
						$issue_date=date(amgt_date_formate(),strtotime($invoice_data->created_date));						
						$payment_status=$invoice_data->payment_status;
						$invoice_no=$invoice_data->invoice_no;
					}					
					if(!empty($expense_data))
					{						
						$issue_date=date(amgt_date_formate(),strtotime($expense_data->payment_date));						
						$payment_status="Paid";						
					}					
					?>
				<table class="width_50" border="0">
					<tbody>				
						<tr>	
							<td class="width_30">
							</td>
							<td class="width_20" align="left">									
									<?php
									if(!empty($invoice_data))
									{
										$invoice_no=$invoice_data->invoice_no;
									}
									?>	
								<h3 class="invoice_color"><?php echo esc_html__('INVOICE','apartment_mgt')." </br>".get_option('invoice_prefix').''.$invoice_no;?></h3>								
								<h5 class="invoice_date_status"> <?php   echo esc_html__('Date','apartment_mgt')." : ".$issue_date; ?></h5>								
							</td>							
						</tr>									
					</tbody>
				</table>
				<table class="width_100">	
					<tbody>	
						<tr>
							<td>
								<h3  class="entry_lable"><?php echo @$charge_type;?></h3>
							</td>	
						</tr>	
					</tbody>
				</table>			
				<table class="table table-bordered width_100 table_new" class="width_93" border="1">
					<thead class="entry_heading">					
							<tr>
								<th class=" align_center padding_color" style="width: 2%;"><span class="">#</span></th>
								<th class=" align_center padding_color" style="width: 13%;"> <span class=""><?php esc_html_e('Date','apartment_mgt');?></span></th>
								<th class=" align_center padding_color" style="width: 18%;"><span class=""><?php esc_html_e('Charge Type','apartment_mgt');?></span> </th>
								<th class=" align_center padding_color" style="width: 20%;"><span class=""><?php esc_html_e('Payment Method','apartment_mgt');?></span></th>
								<th class=" align_right padding_color" style="width: 17%;"><span class=""><?php esc_html_e('Paid Amount','apartment_mgt');?></span></th>	
								<th class=" align_center padding_color" style="width: 25%;"><span class=""><?php esc_html_e('Notes','apartment_mgt');?></span> </th>													
							</tr>						
					</thead>
					<tbody>
						<?php 
						$id=1;
						$total_paid_amount=0;
						if(!empty($payment_data))
						{
							foreach($payment_data as $data)
							{	
								if($invoice_data->charges_type_id=='0')
								{
									$charge_type='Karbantartási díjak';
								}
								else
								{
									$charge_type=get_the_title($invoice_data->charges_type_id);
								}								
							 ?>
						
							 
							<tr>
								<td class="align_center padding_color_black"><?php echo esc_html_e($id);?></td>
								<td class="align_center padding_color_black"><?php echo date(amgt_date_formate(),strtotime($data->date));?></td>	
								<td class="align_center padding_color_black"><?php echo esc_html_e($charge_type);?></td>
					             <td class="align_center padding_color_black"><?php 
					             if($data->payment_method == "Bank Transfer")
					             {
					             	esc_html_e('Bank Transfer','apartment_mgt');
					             }
					             elseif ($data->payment_method == "Cash") 
					             {
					             	esc_html_e('Cash','apartment_mgt');
					             }
					             elseif ($data->payment_method == "Cheque") 
					             {
					             	esc_html_e('Cheque','apartment_mgt');
					             }
					            ?></td>
					         
							    <td class="align_center padding_color_black"> <?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo round($data->amount); ?></td>
								<td class="align_center padding_color_black"><?php echo esc_html_e($data->description);?></td>
							</tr>
							<?php							
							$total_paid_amount+=$data->amount;
							
							$id=$id+1;
							}
						}	
						?>			
					</tbody>
				</table>
				<table class="width_40_right margin_total_paid_amount" border="0">
						<tbody>												
							<tr>
								<td class="width_70 align_right color"><?php esc_html_e('Total Paid Amount : ','apartment_mgt');?></td>
								<td class="width_20_reciept color margin_right_14"><h4 class="total_paid_amount align_right"><?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));  echo round($total_paid_amount); ?></h4></td>
							</tr>
						</tbody>
					</table>				
			</div>
		</div>
	</div>
	<?php	
 die();
}
// invoice pdf
function amgt_invoice_pdf($id,$type)
{
	error_reporting(0);
	$obj_payment= new Amgt_Accounts();	
	
	if($type=='invoice')
	{
		$invoice_data=$obj_payment->amgt_get_single_invoice_by_id($id);
		$old_invoice_id=$invoice_data->invoice_id;
		$invoice_old_data=$obj_payment->amgt_get_single_old_invoice_by_id($old_invoice_id);
		$invoice_title=$invoice_old_data->title;
	}
	
   echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/bootstrap.min.css', __FILE__).'"></link>';
  
  echo '<script  rel="javascript" src="'.plugins_url( '/assets/js/bootstrap.min.js', __FILE__).'"></script>'; 

ob_clean();
header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="invoice.pdf"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');	
require AMS_PLUGIN_DIR. '/lib/mpdf/mpdf.php';	
$stylesheet = file_get_contents(AMS_PLUGIN_DIR. '/assets/css/style.css'); // Get css content
$mpdf	=	new mPDF('c','A4','','' , 5 , 5 , 5 , 0 , 0 , 0); 
$mpdf = new mPDF();
	$mpdf->debug = true;
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-4" />');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('<style></style>');
	$mpdf->WriteHTML($stylesheet,1); // Writing style to pdf
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');		
	$mpdf->SetTitle('Invoice');
	
	

	$mpdf->WriteHTML('<div id="invoice_print">');
	
			$mpdf->WriteHTML('<img class="invoicefont1"  src="'.plugins_url('/apartment-management/assets/images/invoice.jpg').'" width="100%">');
			$mpdf->WriteHTML('<div class="main_div">');					
				$mpdf->WriteHTML('<table class="width_100_print" border="0">');					
					$mpdf->WriteHTML('<tbody>');
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="width_1_print">');
								$mpdf->WriteHTML('<img class="system_logo"  src="'.get_option( 'amgt_system_logo' ).'">');
							$mpdf->WriteHTML('</td>');							
							$mpdf->WriteHTML('<td class="only_width_20_print">');								
								$mpdf->WriteHTML('A. '.chunk_split(get_option( 'amgt_apartment_address' ),30,"<BR>").'<br>'); 
								 $mpdf->WriteHTML('E. '.get_option( 'amgt_email' ).'<br>'); 
								 $mpdf->WriteHTML('P. '.get_option( 'amgt_contact_number' ).'<br>'); 
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td align="right" class="width_24">');
							$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('<table>');
				$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td>');
				
				$mpdf->WriteHTML('<table class="width_50_print" border="0">');
					$mpdf->WriteHTML('<tbody>');				
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td colspan="2" class="billed_to_print" align="center">');								
								$mpdf->WriteHTML('<h3 class="billed_to_lable" >'.esc_html__('| Bill To.','apartment_mgt').'</h3>');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td class="width_40_print">');							
							
									if(!empty($invoice_data))
										$member_id=$invoice_data->member_id;
									 
									$patient=get_userdata($member_id);
								
									$mpdf->WriteHTML('<h3 class="display_name">'.chunk_split(ucwords($patient->display_name),30,"<BR>").'</h3>'); 
									$address=get_user_meta( $member_id,'address',true);									
									$mpdf->WriteHTML(''.chunk_split($address,30,"<BR>").'');  
									$mpdf->WriteHTML(''.get_user_meta( $member_id,'city_name',true ).','); 
									$mpdf->WriteHTML(''.get_user_meta( $member_id,'zip_code',true ).'<br>'); 
									$mpdf->WriteHTML(''.get_user_meta( $member_id,'mobile',true ).'<br>'); 
							$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('</tr>');									
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');	

				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td>');
				
					if(!empty($invoice_data))
					{
						$issue_date=date(amgt_date_formate(),strtotime($invoice_data->created_date));						
						$payment_status=$invoice_data->payment_status;
						$invoice_no=$invoice_data->invoice_no;
					}
				$mpdf->WriteHTML('<table class="width_50_print" border="0">');
					$mpdf->WriteHTML('<tbody>');				
						$mpdf->WriteHTML('<tr>');	
							$mpdf->WriteHTML('<td class="width_30_print">');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td class="width_20_print invoice_lable padding_right_30" align="left">');
								$mpdf->WriteHTML('<h3>'.esc_html__('INVOICE','apartment_mgt').'<br>'.get_option('invoice_prefix').''.$invoice_no.'</h3>');								
							$mpdf->WriteHTML('</td>');							
						$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('<tr>');	
							$mpdf->WriteHTML('<td class="width_30_print">');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td class="width_20_print" align="left">');
								$mpdf->WriteHTML('<h5>'.esc_html__('Date','apartment_mgt').':'.$issue_date.'</h5>');
								$mpdf->WriteHTML('<h5>'.esc_html__('Status','apartment_mgt').': '.esc_html__(''.$payment_status.'','apartment_mgt').'</h5>');									
							$mpdf->WriteHTML('</td>');							
						$mpdf->WriteHTML('</tr>');	
						
						$charge_period=amgt_get_charge_period_by_id($invoice_data->charges_id);
						
						$mpdf->WriteHTML('<tr>');	
							$mpdf->WriteHTML('<td class="width_30_print">');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td class="width_20_print" align="left">');
							if($type=='invoice')
							{
								$invoice_length=strlen($invoice_data->invoice_no);
								if($invoice_length == '5')
								{ 
									if($charge_period!='0')
									{	
										$mpdf->WriteHTML('<h4>'.esc_html__('Billing Period','apartment_mgt').'</h4>');
										$mpdf->WriteHTML('<h5>'.esc_html__('From (','apartment_mgt').''.date(amgt_date_formate(),strtotime($invoice_data->start_date)).') <br>'.esc_html__('To (','apartment_mgt').' '.date(amgt_date_formate(),strtotime($invoice_data->end_date)).' )</h5>');	
									}
								}	
							}								
							$mpdf->WriteHTML('</td>');							
						$mpdf->WriteHTML('</tr>');												
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');						
				
				$mpdf->WriteHTML('</td>');
			  $mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</table>');
				
				if(empty($invoice_data->invoice_no))
				{
					$charge_type=get_the_title($invoice_data->charges_type_id);
				}
				else
				{
					if($invoice_length == '9')
					{
					   $charge_type=$invoice_title;
					}
					elseif($invoice_length == '5')
					{
						if($invoice_data->charges_type_id=='0')
						{							
							$charge_type='Maintenance Charges';
						}
						else
						{
							$charge_type=get_the_title($invoice_data->charges_type_id);
						}	
					}	
				}	
				
				$mpdf->WriteHTML('<table class="width_100_print">');	
					$mpdf->WriteHTML('<tbody>');	
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="">');
								$mpdf->WriteHTML('<h3 class="entry_lable">'.esc_html__(''.$charge_type.'','apartment_mgt').'</h3>');
							$mpdf->WriteHTML('</td>');	
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');								
							
				$mpdf->WriteHTML('<table class="table width_100 table-bordered table_new" border="1">');
					$mpdf->WriteHTML('<thead class="entry_heading_print">');					
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_color">#</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center padding_color">'.esc_html__('Date','apartment_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left padding_color">'.esc_html__('Entry','apartment_mgt').'</th>');
								
								$charge_cal_by=amgt_get_invoice_charges_calculate_by($invoice_data->charges_id);
								$unit_measerment_type=get_option( 'amgt_unit_measerment_type' );		
								
								if($charge_cal_by->charges_calculate_by=='measurement_charge')
								{								
									$mpdf->WriteHTML('<th class="width_3_print color_white entry_heading align_left padding_color">'.esc_html__('Measurement','apartment_mgt').'('.$unit_measerment_type.')</th>');
									$mpdf->WriteHTML('<th class="color_white entry_heading align_left padding_color">'.esc_html__('Charge Per','apartment_mgt').'('.$unit_measerment_type.')</th>');
								}								
								$mpdf->WriteHTML('<th class="color_white align_right entry_heading padding_color">'.esc_html__('Amount','apartment_mgt').'</th>');														
							$mpdf->WriteHTML('</tr>');						
					$mpdf->WriteHTML('</thead>');
					$mpdf->WriteHTML('<tbody>');
				
					 	$id=1;
						if(!empty($invoice_data))
						{	
							$invoice_id=$invoice_data->charges_id;
							if(empty($invoice_data->invoice_no))
							{
								$all_entry=json_decode($invoice_data->charges_payment);
								$entry_total_amount='0';	
								foreach($all_entry as $entry)
								{
									 $mpdf->WriteHTML('<tr class="entry_list">');
										$mpdf->WriteHTML('<td class="align_center padding_color_black">'.$id.'</td>');
										$mpdf->WriteHTML('<td class="align_center padding_color_black">'.date(amgt_date_formate(),strtotime($invoice_data->created_date)).'</td>');
										$mpdf->WriteHTML('<td class="padding_color_black">'.$entry->entry.'</td>');
										$mpdf->WriteHTML('<td class="align_right padding_color_black">'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).''.round($entry->amount).'</td>');
									$mpdf->WriteHTML('</tr>');								
									$entry_total_amount+=$entry->amount;
									$id=$id+1;
								}
							}
							else	
							{
								if($invoice_length == '9')
								{						
									
									$mpdf->WriteHTML('<tr class="entry_list">');
										$mpdf->WriteHTML('<td class="align_center padding_color_black">'.$id.'</td>');
										$mpdf->WriteHTML('<td class="align_center padding_color_black">'.date(amgt_date_formate(),strtotime($invoice_data->created_date)).'</td>');
										$mpdf->WriteHTML('<td class="padding_color_black">'.$invoice_title.'</td>');
										$mpdf->WriteHTML('<td class="align_right padding_color_black">'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).''.round($invoice_data->invoice_amount).'</td>');
									$mpdf->WriteHTML('</tr>');
								
								}
								else	
								{
									
									$charge_cal_by=amgt_get_invoice_charges_calculate_by($invoice_id);
									
									if($charge_cal_by->charges_calculate_by=='fix_charge')
									{
										$all_entry=json_decode($invoice_data->charges_payment);
										
										foreach($all_entry as $entry)
										{
											$mpdf->WriteHTML('<tr class="entry_list">');
												$mpdf->WriteHTML('<td class="align_center padding_color_black">'.$id.'</td>');
												$mpdf->WriteHTML('<td class="align_center padding_color_black">'.date(amgt_date_formate(),strtotime($invoice_data->created_date)).'</td>');
												$mpdf->WriteHTML('<td class="padding_color_black">'.$entry->entry.'</td>');
												$mpdf->WriteHTML('<td class="align_right padding_color_black">'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).''.round($entry->amount).'</td>');
											$mpdf->WriteHTML('</tr>');
											
											$id=$id+1;
										}
									}
									else
									{
									
										$mpdf->WriteHTML('<tr class="entry_list">');
											$mpdf->WriteHTML('<td class="align_center padding_color_black">'.$id.'</td>');
											$mpdf->WriteHTML('<td class="align_center padding_color_black">'.date(amgt_date_formate(),strtotime($invoice_data->created_date)).'</td>');
											$mpdf->WriteHTML('<td class="padding_color_black">'.$charge_type.'</td>');
																		
											$measurement=amgt_get_single_member_unit_size($member_id);
											$charge=amgt_get_single_member_measurment_charge($invoice_data->charges_id);
											
											$mpdf->WriteHTML('<td class="padding_color_black">'.$measurement.'</td>');
											$mpdf->WriteHTML('<td class="padding_color_black">'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).''.$charge.'</td>');
											
											$amount=$invoice_data->amount+$invoice_data->discount_amount;
											
											$mpdf->WriteHTML('<td class="align_right padding_color_black">'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).''.round($amount).'</td>');
										$mpdf->WriteHTML('</tr>');
															
									}												
								}												
							}												
						}
					   				
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
					
						if(!empty($invoice_data))
						{	
							
							$bank_name=get_option( 'amgt_bank_name' );
							$account_holder_name=get_option( 'amgt_account_holder_name' );
							$account_number=get_option( 'amgt_account_number' );
							$account_type=get_option( 'amgt_account_type' );
							$ifsc_code=get_option( 'amgt_ifsc_code' );
							$swift_code=get_option( 'amgt_swift_code' );
							
							if(empty($invoice_data->invoice_no))
							{
							    $discount=$invoice_data->discount_amount;
								$total_amount=round($entry_total_amount);
								$due_amount='0';
								$subtotal_amount=round($entry_total_amount);
								 
								$tax_amount='0';
								$grand_total=round($entry_total_amount)-round($discount);
								$paid_amount=round($entry_total_amount)-round($discount);						
							}	
							else
							{		
								if($invoice_length == '9')
								{
								  $total_amount=round($invoice_data->invoice_amount);
								  $due_amount=round($invoice_data->invoice_amount) - round($invoice_data->paid_amount);
								  $subtotal_amount=round($invoice_data->invoice_amount);
								  $discount='0';
								  $tax_amount='0';
								  $grand_total=round($invoice_data->invoice_amount);
								  $paid_amount=round($invoice_data->paid_amount);
								}
								else
								{
									$due_amount=$invoice_data->due_amount;
									$total_amount=$invoice_data->total_amount;
									$after_sub_discount_amount=$invoice_data->amount;
									$discount=$invoice_data->discount_amount;
									$subtotal_amount=$after_sub_discount_amount+$discount;
									$tax_amount=$invoice_data->tax_amount;
									$paid_amount=$invoice_data->paid_amount;
									$grand_total=$total_amount;
								}
							}	
						}
				
					$mpdf->WriteHTML('<table>');
				 $mpdf->WriteHTML('<tr>');
				 $mpdf->WriteHTML('<td>');
				
					$mpdf->WriteHTML('<table class="" border="0">');
						$mpdf->WriteHTML('<tbody>');						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td colspan="2" class="padding_left_15">');
									$mpdf->WriteHTML('<h3 class="payment_method_lable" >'.esc_html__('Payment Method','apartment_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');								
							$mpdf->WriteHTML('</tr>');							
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_31 font_12">'.esc_html__('Bank Name','apartment_mgt').'</td>');
								$mpdf->WriteHTML('<td class="font_12">: '.$bank_name.'</td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_31 font_12">'.esc_html__('A/C Holder Name ','apartment_mgt').'</td>');
								$mpdf->WriteHTML('<td class="font_12">: '.$account_holder_name.'</td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_31 font_12">'.esc_html__('Account No','apartment_mgt').'</td>');
								$mpdf->WriteHTML('<td class="font_12">: '.$account_number.'</td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_31 font_12">'.esc_html__('Account Type','apartment_mgt').'</td>');
								$mpdf->WriteHTML('<td class="font_12">: '.$account_type.'</td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_31 font_12">'.esc_html__('IFSC Code','apartment_mgt').'</td>');
								$mpdf->WriteHTML('<td class="font_12">: '.$ifsc_code.'</td>');
							$mpdf->WriteHTML('</tr>');
							
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_31 font_12">'.esc_html__('Paypal','apartment_mgt').'</td>');
								$mpdf->WriteHTML('<td class="font_12">: '.get_option( 'apartment_paypal_email' ).'</td>');
							$mpdf->WriteHTML('</tr>');				
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');
		
					$mpdf->WriteHTML('</td>');
					$mpdf->WriteHTML('<td>');
					
					$mpdf->WriteHTML('<table class="width_54_print" border="0">');
					$mpdf->WriteHTML('<tbody>');
					
						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<h4><td class="width_70 align_right"><h4 class="margin">'.esc_html__('Subtotal :','apartment_mgt').'</h4></td>');
								$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span>'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).'</span>'.round($subtotal_amount).'</h4></td>');
							$mpdf->WriteHTML('</tr>');	
							if(!empty($discount))
							{	
								$discount=$discount;
							}
							else
							{
								$discount=0;
							}	
							
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.esc_html__('Discount Amount :','apartment_mgt').'</h4></td>');
								$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span >-'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).'</span>'.round($discount).'</h4></td>');
							$mpdf->WriteHTML('</tr>');
							if(!empty($tax_amount))
							{	
								$tax_amount=$tax_amount;
							}
							else
							{
								$tax_amount='0';
							}		
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.esc_html__('Tax Amount :','apartment_mgt').'</h4></td>');
								$mpdf->WriteHTML('<td class="align_right"><h4 class="margin"> <span >+'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).'</span>'.round($tax_amount).'</h4></td>');
							$mpdf->WriteHTML('</tr>');	
							
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.esc_html__('Due Amount :','apartment_mgt').'</h4></td>');
								$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span >'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).'</span>'.round(abs($due_amount)).'</h4></td>');
							$mpdf->WriteHTML('</tr>');
							if(!empty($paid_amount))
							{	
								$paid_amount=$paid_amount;
							}
							else
							{
								$paid_amount='0';
							}		
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.esc_html__('Paid Amount :','apartment_mgt').'</h4></td>');
								$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span >'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).'</span>'.round($paid_amount).'</h4></td>');
							$mpdf->WriteHTML('</tr>');					
						$mpdf->WriteHTML('<tr>');							
							$mpdf->WriteHTML('<td class="width_70 align_right grand_total_lable padding_10"><h3 class="color_white margin">'.esc_html__('Grand Total :','apartment_mgt').'</h3></td>');
							$mpdf->WriteHTML('<td class="align_right grand_total_amount padding_10"><h3 class="color_white margin">  <span>'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).''.round($grand_total).'</span></h3></td>');
						$mpdf->WriteHTML('</tr>');							
					$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');
					
					$mpdf->WriteHTML('</td>');					
					$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</table>');
					$gst_number=get_option( 'amgt_gst_number' );
					$tax_id=get_option( 'amgt_tax_id' );
					$corporate_id=get_option( 'amgt_corporate_id' );
					if(!empty($gst_number) || !empty($tax_id) || !empty($corporate_id))	
					{						
					$mpdf->WriteHTML('<table class="table_invoice gst_details" border="0">');
					$mpdf->WriteHTML('<thead>');
						$mpdf->WriteHTML('<tr>');								
							$mpdf->WriteHTML('<th class="align_center">'.esc_html__('GST Number','apartment_mgt').' </th>');
							$mpdf->WriteHTML('<th class="align_center">'.esc_html__('TAX ID','apartment_mgt').' </th>');
							$mpdf->WriteHTML('<th class="align_center">'.esc_html__('Corporate ID','apartment_mgt').'</th>');
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</thead>');
					$mpdf->WriteHTML('<tbody>');
						$mpdf->WriteHTML('<tr>');								
							$mpdf->WriteHTML('<td class="align_center">'.$gst_number.'</td>');
							$mpdf->WriteHTML('<td class="align_center">'.$tax_id.'</td>');
							$mpdf->WriteHTML('<td class="align_center">'.$corporate_id.'</td>');
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>'); 
					}
			$mpdf->WriteHTML('</div>');
		$mpdf->WriteHTML('</div>');
						
	$mpdf->WriteHTML('</body>');
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output();	
	ob_end_flush();
	unset($mpdf);	
}

//send invoice generated pdf in mail
function amgt_send_invoice_generate_mail($emails,$subject,$message,$invoiceid)
{	
	$document_dir = WP_CONTENT_DIR;
	$document_dir .= '/uploads/invoice/';
	$document_path = $document_dir;
	if (!file_exists($document_path))
	{
		mkdir($document_path, 0777, true);		
	}
	$obj_payment= new Amgt_Accounts();
	
	$invoice_data=$obj_payment->amgt_get_single_invoice_by_id($invoiceid);
	$history_detail_result=$obj_payment->amgt_get_single_invoice_payment_history_by_id($invoiceid);
	$mpdf=new mPDF();
	echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/bootstrap.min.css', __FILE__).'"></link>';
  
	echo '<script  rel="javascript" src="'.plugins_url( '/assets/js/bootstrap.min.js', __FILE__).'"></script>'; 

		
	$stylesheet = file_get_contents(AMS_PLUGIN_DIR. '/assets/css/style.css'); // Get css content
	$mpdf->WriteHTML('<html>');
	$mpdf->WriteHTML('<head>');
	$mpdf->WriteHTML('<style></style>');
	$mpdf->WriteHTML($stylesheet,1); // Writing style to pdf
	$mpdf->WriteHTML('</head>');
	$mpdf->WriteHTML('<body>');		
	$mpdf->SetTitle('Invoice');
		$mpdf->WriteHTML('<div');				
				$mpdf->WriteHTML('<h4 class="modal-title">'.get_option('amgt_system_name').'</h4>');				
		$mpdf->WriteHTML('</div>');
		
		$mpdf->WriteHTML('<div id="invoice_print">');
			$mpdf->WriteHTML('<img class="invoicefont1"  src="'.plugins_url('/apartment-management/assets/images/invoice.jpg').'" width="100%">');
			$mpdf->WriteHTML('<div class="main_div">');					
				$mpdf->WriteHTML('<table class="width_100_print" border="0">');					
					$mpdf->WriteHTML('<tbody>');
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="width_1_print">');
								$mpdf->WriteHTML('<img class="system_logo"  src="'.get_option( 'amgt_system_logo' ).'">');
							$mpdf->WriteHTML('</td>');							
							$mpdf->WriteHTML('<td class="only_width_20_print">');								
								$mpdf->WriteHTML('A. '.chunk_split(get_option('amgt_apartment_address'),30,"<BR>").'<br>'); 
								 $mpdf->WriteHTML('E. '.get_option( 'amgt_email' ).'<br>'); 
								 $mpdf->WriteHTML('P. '.get_option( 'amgt_contact_number' ).'<br>'); 
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td align="right" class="width_24">');
							$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
				$mpdf->WriteHTML('<table>');
				$mpdf->WriteHTML('<tr>');
				$mpdf->WriteHTML('<td>');
				
				$mpdf->WriteHTML('<table class="width_50_print" border="0">');
					$mpdf->WriteHTML('<tbody>');				
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td colspan="2" class="billed_to_print" align="center">');								
								$mpdf->WriteHTML('<h3 class="billed_to_lable" >'.esc_html__('| Bill To.','apartment_mgt').'</h3>');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td class="width_40_print">');							
							
									if(!empty($invoice_data))
										$member_id=$invoice_data->member_id;
									 
									$patient=get_userdata($member_id);
								
									$mpdf->WriteHTML('<h3 class="display_name">'.chunk_split(ucwords($patient->display_name),30,"<BR>").'</h3>'); 
									$mpdf->WriteHTML(''.chunk_split($address,30,"<BR>").'');  
									$mpdf->WriteHTML(''.get_user_meta( $member_id,'city_name',true ).','); 
									$mpdf->WriteHTML(''.get_user_meta( $member_id,'zip_code',true ).'<br>'); 
									$mpdf->WriteHTML(''.get_user_meta( $member_id,'mobile',true ).'<br>'); 
							$mpdf->WriteHTML('</td>');
						$mpdf->WriteHTML('</tr>');									
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');	

				$mpdf->WriteHTML('</td>');
				$mpdf->WriteHTML('<td>');
				
					if(!empty($invoice_data))
					{
						$issue_date=date(amgt_date_formate(),strtotime($invoice_data->created_date));						
						$payment_status=$invoice_data->payment_status;
						$invoice_no=$invoice_data->invoice_no;
					}
				$mpdf->WriteHTML('<table class="width_50_print" border="0">');
					$mpdf->WriteHTML('<tbody>');				
						$mpdf->WriteHTML('<tr>');	
							$mpdf->WriteHTML('<td class="width_30_print">');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td class="width_20_print invoice_lable padding_right_30" align="left">');
								$mpdf->WriteHTML('<h3>'.esc_html__('INVOICE','apartment_mgt').' <br>'.get_option('invoice_prefix').''.$invoice_no.'</h3>');								
							$mpdf->WriteHTML('</td>');							
						$mpdf->WriteHTML('</tr>');	
						$mpdf->WriteHTML('<tr>');	
							$mpdf->WriteHTML('<td class="width_30_print">');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td class="width_20_print padding_right_30" align="left">');
								$mpdf->WriteHTML('<h5>'.esc_html__('Date','apartment_mgt').':'.$issue_date.'</h5>');
								$mpdf->WriteHTML('<h5>'.esc_html__('Status','apartment_mgt').':'.esc_html__(''.$payment_status.'','apartment_mgt').'</h5>');									
							$mpdf->WriteHTML('</td>');							
						$mpdf->WriteHTML('</tr>');	
						
						$charge_period=amgt_get_charge_period_by_id($invoice_data->charges_id);
						
						$mpdf->WriteHTML('<tr>');	
							$mpdf->WriteHTML('<td class="width_30_print">');
							$mpdf->WriteHTML('</td>');
							$mpdf->WriteHTML('<td class="width_20_print padding_right_30" align="left">');
							if($type=='invoice')
							{
								if($charge_period!='0')
								{	
									$mpdf->WriteHTML('<h4>'.esc_html__('Billing Period','apartment_mgt').'</h4>');
									$mpdf->WriteHTML('<h5>From ('.date(amgt_date_formate(),strtotime($invoice_data->start_date)).') <br> To ('.date(amgt_date_formate(),strtotime($invoice_data->end_date)).' )</h5>');	
								}
							}								
							$mpdf->WriteHTML('</td>');							
						$mpdf->WriteHTML('</tr>');												
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');						
				
				$mpdf->WriteHTML('</td>');
			  $mpdf->WriteHTML('</tr>');
			$mpdf->WriteHTML('</table>');
			
				
				if($invoice_data->charges_type_id=='0')
				{							
					$charge_type='Maintenance Charges';
				}
				else
				{
					$charge_type=get_the_title($invoice_data->charges_type_id);
				}	
				
				$mpdf->WriteHTML('<table class="width_100_print">');	
					$mpdf->WriteHTML('<tbody>');	
						$mpdf->WriteHTML('<tr>');
							$mpdf->WriteHTML('<td class="padding_left_20">');
								$mpdf->WriteHTML('<h3  class="entry_lable">'.esc_html__(''.$charge_type.'','apartment_mgt').'</h3>');
							$mpdf->WriteHTML('</td>');	
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');								
							
				$mpdf->WriteHTML('<table class="table table-bordered" class="width_100" border="1">');
					$mpdf->WriteHTML('<thead>');					
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">#</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.esc_html__('Date','apartment_mgt').'</th>');
								$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.esc_html__('Entry','apartment_mgt').'</th>');
								
								$charge_cal_by=amgt_get_invoice_charges_calculate_by($invoice_data->charges_id);
								$unit_measerment_type=get_option( 'amgt_unit_measerment_type' );								
								if($charge_cal_by->charges_calculate_by=='measurement_charge')
								{								
									$mpdf->WriteHTML('<th class="width_3_print color_white entry_heading align_left">'.esc_html__('Measurement','apartment_mgt').'('.$unit_measerment_type.')</th>');
									$mpdf->WriteHTML('<th class="color_white entry_heading align_left">'.esc_html__('Charge Per','apartment_mgt').'('.$unit_measerment_type.')</th>');
								}								
								$mpdf->WriteHTML('<th class="color_white align_right entry_heading">'.esc_html__('Amount','apartment_mgt').'</th>');														
							$mpdf->WriteHTML('</tr>');						
					$mpdf->WriteHTML('</thead>');
					$mpdf->WriteHTML('<tbody>');
				
					 	$id=1;
						if(!empty($invoice_data))
						{	
							$invoice_id=$invoice_data->charges_id;
							
							$charge_cal_by=amgt_get_invoice_charges_calculate_by($invoice_id);
							
							if($charge_cal_by->charges_calculate_by=='fix_charge')
							{
								$all_entry=json_decode($invoice_data->charges_payment);
								
								foreach($all_entry as $entry)
								{
									$mpdf->WriteHTML('<tr class="entry_list">');
										$mpdf->WriteHTML('<td class="align_center">'.$id.'</td>');
										$mpdf->WriteHTML('<td class="align_center">'.date(amgt_date_formate(),strtotime($invoice_data->created_date)).'</td>');
										$mpdf->WriteHTML('<td>'.$entry->entry.'</td>');
										$mpdf->WriteHTML('<td class="align_right">'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).''.round($entry->amount).'</td>');
									$mpdf->WriteHTML('</tr>');
									
									$id=$id+1;
								}
							}
							else
							{
							
								$mpdf->WriteHTML('<tr class="entry_list">');
									$mpdf->WriteHTML('<td class="align_center">'.$id.'</td>');
									$mpdf->WriteHTML('<td class="align_center">'.date(amgt_date_formate(),strtotime($invoice_data->created_date)).'</td>');
									$mpdf->WriteHTML('<td>'.$charge_type.'</td>');
																
									$measurement=amgt_get_single_member_unit_size($member_id);
									$charge=amgt_get_single_member_measurment_charge($invoice_data->charges_id);
									
									$mpdf->WriteHTML('<td>'.$measurement.'</td>');
									$mpdf->WriteHTML('<td>'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).''.$charge.'</td>');
									
									$amount=$invoice_data->amount+$invoice_data->discount_amount;
									
									$mpdf->WriteHTML('<td class="align_right">'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).''.round($amount).'</td>');
								$mpdf->WriteHTML('</tr>');
													
							}												
						}
					   				
					$mpdf->WriteHTML('</tbody>');
				$mpdf->WriteHTML('</table>');
				
					
						if(!empty($invoice_data))
						{								
							$bank_name=get_option( 'amgt_bank_name' );
							$account_holder_name=get_option( 'amgt_account_holder_name' );
							$account_number=get_option( 'amgt_account_number' );
							$account_type=get_option( 'amgt_account_type' );
							$ifsc_code=get_option( 'amgt_ifsc_code' );
							$swift_code=get_option( 'amgt_swift_code' );
									
							$after_sub_discount_amount=$invoice_data->amount;
							$discount=$invoice_data->discount_amount;
							$subtotal_amount=$after_sub_discount_amount+$discount;
							$tax_amount=$invoice_data->tax_amount;
							$due_amount=$invoice_data->due_amount;
							$paid_amount=$invoice_data->paid_amount;
							$total_amount=$invoice_data->total_amount;
							$grand_total=$total_amount;
						}
				
					$mpdf->WriteHTML('<table>');
				 $mpdf->WriteHTML('<tr>');
				 $mpdf->WriteHTML('<td>');
				 
					$mpdf->WriteHTML('<table class="" border="0">');
						$mpdf->WriteHTML('<tbody>');						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td colspan="2" class="padding_left_15">');
									$mpdf->WriteHTML('<h3 class="payment_method_lable" >'.esc_html__('Payment Method','apartment_mgt').'</h3>');
								$mpdf->WriteHTML('</td>');								
							$mpdf->WriteHTML('</tr>');							
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_31 font_12">'.esc_html__('Bank Name','apartment_mgt').'</td>');
								$mpdf->WriteHTML('<td class="font_12">: '.$bank_name.'</td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_31 font_12">'.esc_html__('A/C Holder Name ','apartment_mgt').' </td>');
								$mpdf->WriteHTML('<td class="font_12">: '.$account_holder_name.'</td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_31 font_12">'.esc_html__('Account No','apartment_mgt').'</td>');
								$mpdf->WriteHTML('<td class="font_12">: '.$account_number.'</td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_31 font_12">'.esc_html__('Account Type','apartment_mgt').'</td>');
								$mpdf->WriteHTML('<td class="font_12">: '.$account_type.'</td>');
							$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_31 font_12">'.esc_html__('IFSC Code','apartment_mgt').'</td>');
								$mpdf->WriteHTML('<td class="font_12">: '.$ifsc_code.'</td>');
							$mpdf->WriteHTML('</tr>');
							
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_31 font_12">'.esc_html__('Paypal','apartment_mgt').'</td>');
								$mpdf->WriteHTML('<td class="font_12">: '.get_option( 'apartment_paypal_email' ).'</td>');
							$mpdf->WriteHTML('</tr>');				
						$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');
					
					$mpdf->WriteHTML('</td>');
					$mpdf->WriteHTML('<td>');
					
					$mpdf->WriteHTML('<table class="width_54_print" border="0">');
					$mpdf->WriteHTML('<tbody>');
					
						
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<h4><td class="width_70 align_right"><h4 class="margin">'.esc_html__('Subtotal :','apartment_mgt').'</h4></td>');
								$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span>'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).'</span>'.round($subtotal_amount).'</h4></td>');
							$mpdf->WriteHTML('</tr>');	
							if(!empty($discount))
							{	
								$discount=$discount;
							}
							else
							{
								$discount='0';
							}	
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.esc_html__('Discount Amount :','apartment_mgt').'</h4></td>');
								$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span >-'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).'</span>'.round($discount).'</h4></td>');
							$mpdf->WriteHTML('</tr>');
							if(!empty($tax_amount))
							{	
								$tax_amount=$tax_amount;
							}
							else
							{
								$tax_amount='0';
							}		
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.esc_html__('Tax Amount :','apartment_mgt').'</h4></td>');
								$mpdf->WriteHTML('<td class="align_right"><h4 class="margin"> <span >+'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).'</span>'.round($tax_amount).'</h4></td>');
							$mpdf->WriteHTML('</tr>');							
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.esc_html__('Due Amount :','apartment_mgt').'</h4></td>');
								$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span >'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).'</span>'.round(abs($due_amount)).'</h4></td>');
							$mpdf->WriteHTML('</tr>');
							if(!empty($paid_amount))
							{	
								$paid_amount=$paid_amount;
							}
							else
							{
								$paid_amount='0';
							}		
							$mpdf->WriteHTML('<tr>');
								$mpdf->WriteHTML('<td class="width_70 align_right"><h4 class="margin">'.esc_html__('Paid Amount :','apartment_mgt').'</h4></td>');
								$mpdf->WriteHTML('<td class="align_right"> <h4 class="margin"><span >'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).'</span>'.round($paid_amount).'</h4></td>');
							$mpdf->WriteHTML('</tr>');					
						$mpdf->WriteHTML('<tr>');							
							$mpdf->WriteHTML('<td class="width_70 align_right grand_total_lable"><h3 class="color_white margin">'.esc_html__('Grand Total :','apartment_mgt').'</h3></td>');
							$mpdf->WriteHTML('<td class="align_right grand_total_amount"><h3 class="color_white margin">  <span>'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).''.round($grand_total).'</span></h3></td>');
						$mpdf->WriteHTML('</tr>');							
					$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>');
					
					$mpdf->WriteHTML('</td>');					
					$mpdf->WriteHTML('</tr>');
					$mpdf->WriteHTML('</table>');
					$gst_number=get_option( 'amgt_gst_number' );
					$tax_id=get_option( 'amgt_tax_id' );
					$corporate_id=get_option( 'amgt_corporate_id' );
											
					$mpdf->WriteHTML('<table class="table_invoice gst_details" border="0">');
					$mpdf->WriteHTML('<thead>');
						$mpdf->WriteHTML('<tr>');								
							$mpdf->WriteHTML('<th class="align_center">'.esc_html__('GST Number','apartment_mgt').' </th>');
							$mpdf->WriteHTML('<th class="align_center">'.esc_html__('TAX ID','apartment_mgt').' </th>');
							$mpdf->WriteHTML('<th class="align_center">'.esc_html__('Corporate ID','apartment_mgt').'</th>');
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</thead>');
					$mpdf->WriteHTML('<tbody>');
						$mpdf->WriteHTML('<tr>');								
							$mpdf->WriteHTML('<td class="align_center">'.$gst_number.'</td>');
							$mpdf->WriteHTML('<td class="align_center">'.$tax_id.'</td>');
							$mpdf->WriteHTML('<td class="align_center">'.$corporate_id.'</td>');
						$mpdf->WriteHTML('</tr>');	
					$mpdf->WriteHTML('</tbody>');
					$mpdf->WriteHTML('</table>'); 
					
					if(!empty($history_detail_result))
					{
						$mpdf->WriteHTML('<hr>');
						$mpdf->WriteHTML('<h4>'.esc_html__('Amount','apartment_mgt').' Payment History</h4>');
						$mpdf->WriteHTML('<table class="table table-bordered border_colleps" width="100%" border="1">');
						$mpdf->WriteHTML('<thead>');
								$mpdf->WriteHTML('<tr>');
									$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.esc_html__('Date','apartment_mgt').'</th>');
									$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.esc_html__('Amount','apartment_mgt').'</th>');
									$mpdf->WriteHTML('<th class="color_white entry_heading align_center">'.esc_html__('Method','apartment_mgt').'</th>');						
								$mpdf->WriteHTML('</tr>');
							$mpdf->WriteHTML('</thead>');
							$mpdf->WriteHTML('<tbody>');
								
								foreach($history_detail_result as  $retrive_date)
								{					
									$mpdf->WriteHTML('<tr>');
									$mpdf->WriteHTML('<td class="align_center">'.date(amgt_date_formate(),strtotime($retrive_date->date)).'</td>');
									$mpdf->WriteHTML('<td class="align_center">'.amgt_get_currency_symbol(get_option( 'apartment_currency_code' )).''.$retrive_date->amount.'</td>');
									$mpdf->WriteHTML('<td class="align_center">'.$retrive_date->payment_method.'</td>');
									$mpdf->WriteHTML('</tr>');
								}
							$mpdf->WriteHTML('</tbody>');
						$mpdf->WriteHTML('</table>');
					}
			$mpdf->WriteHTML('</div>');
		$mpdf->WriteHTML('</div>');
			
	$mpdf->WriteHTML('</body>');
	$mpdf->WriteHTML('</html>');	
	$mpdf->Output($document_path.''.$invoiceid.'.pdf','F');
	$mail_attachment = array($document_path.''.$invoiceid.'.pdf'); 
	
	$apartment=get_option('amgt_system_name');
	$headers="";
	$headers .= 'From: '.$apartment.' <noreplay@gmail.com>' . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";
	$enable_notofication=get_option('apartment_enable_notifications');
	if($enable_notofication=='yes')
	{	
		wp_mail($emails,$subject,$message,$headers,$mail_attachment); 		
	}	
	unlink($document_path.''.$invoiceid.'.pdf');
}
//----------------- MEMBER ADD PAYMENT ----------------//
function amgt_member_add_payment()
{
	$mp_id = $_POST['idtest'];
	$invoice_id = $_POST['invoice_id'];
	$member_id = $_POST['member_id'];
	$due_amount = $_POST['due_amount'];
?>
<div class="modal-header padding_15">
	<a href="#" class="bill-close-btn badge badge-success pull-right">X</a>
	<h4 class="modal-title"><?php echo get_option('amgt_system_name');?></h4>
</div>
<div class="modal-body account_popup_padding">
	<form name="payment_form" action="" method="post" class="form-horizontal" id="payment_form">
        <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo esc_attr($action);?>" >
		<input type="hidden" name="invoice_id" value="<?php echo esc_attr($invoice_id);?>">
		<input type="hidden" name="member_id" value="<?php echo esc_attr($member_id);?>">
		<input type="hidden" name="mp_id" value="<?php echo esc_attr($mp_id);?>">
		<input type="hidden" name="created_by" value="<?php echo get_current_user_id();?>">
		<div class="form-group">
			<label class="col-sm-3 control-label" for="amount"><?php esc_html_e('Paid Amount','apartment_mgt');?> (<?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>)<span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="amount" class="form-control validate[required] text-input" type="number" value="<?php echo esc_attr($due_amount);?>" name="amount" min="0" max="<?php echo esc_attr($due_amount);?>">
			</div>
		</div>
		<div class="form-group">
			<input type="hidden" name="payment_status" value="paid">
			<label class="col-sm-3 control-label" for="payment_method"><?php esc_html_e('Payment By','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<?php global $current_user;
				 $user_roles = $current_user->roles;
				  $user_role = array_shift($user_roles);?>
					<select name="payment_method" id="payment_method" class="form-control">
						<?php if($user_role != 'member'){?>
						<option value="Cash"><?php esc_html_e('Cash','apartment_mgt');?></option>
						<option value="Cheque"><?php esc_html_e('Cheque','apartment_mgt');?></option>
						<option value="Bank Transfer"><?php esc_html_e('Banki átutalás','apartment_mgt');?></option>		
						<?php } else 
						{
							include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
							if(is_plugin_active('paymaster/paymaster.php') && get_option('amgt_paymaster_pack')=="yes")
							//if(is_plugin_active('paymaster/paymaster.php'))
							{ 
								$payment_method = get_option('pm_payment_method');
								print '<option value="'.$payment_method.'">'.$payment_method.'</option>';
							} 
							else
							{
								print '<option value="Paypal">Paypal</option>';
							}	  	
						}
						?>
				   </select>
				   </div>
				</div>
				<?php if($user_role != 'member'){?>
				   <div class="form-group">
						<label class="col-sm-3 control-label" for="amount"><?php esc_html_e('Description','apartment_mgt');?></label>
						<div class="col-sm-8">
							<textarea name="description" maxlength="150" class="form-control text-input"></textarea>
						</div>
					</div>
				<?php } ?>
		<div class="col-sm-offset-2 col-sm-8 add_payment">
        	 <input type="submit" value="<?php esc_html_e('Add Payment','apartment_mgt');?>" name="add_own_payment" class="btn btn-success"/>
        </div> 
	</form>
</div>
<?php
	die();
}
//----------LICENCE KEY REGISTRAION CODE-------------
function amgt_verify_pkey()
{
	
	$api_server = 'license.dasinfomedia.com';
	$fp = fsockopen($api_server,80, $errno, $errstr, 2);
	$location_url = admin_url().'admin.php?page=amgt-apartment_system';
	if (!$fp)
        $server_rerror = 'Down';
    else
        $server_rerror = "up";
	
	if($server_rerror == "up")
	{
		$domain_name= $_SERVER['SERVER_NAME'];
		$licence_key = $_REQUEST['licence_key'];
		$email = $_REQUEST['enter_email'];
		$data['domain_name']= $domain_name;
		$data['licence_key']= $licence_key;
		$data['enter_email']= $email;

		//$verify_result = amgt_submit_setupform($data);
		$result = amgt_check_productkey($domain_name,$licence_key,$email);
		
		if($result == '1')
		{
			$message = 'Please provide correct Envato purchase key.';
				$_SESSION['amgt_verify'] = '1';
		}
		elseif($result == '2')
		{
			$message = 'This purchase key is already registered with the different domain. If have any issue please contact us at sales@dasinfomedia.com ';
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
				$_SESSION['amgt_verify'] = '4';
		}
		else{
			update_option('domain_name',$domain_name,true);
			update_option('licence_key',$licence_key,true);
			update_option('amgt_setup_email',$email,true);
			$message = 'Success fully register';
				$_SESSION['amgt_verify'] = '0';
		}	
	
	
		$result_array = array('message'=>$message,'amgt_verify'=>$_SESSION['amgt_verify'],'location_url'=>$location_url);
		
		echo json_encode($result_array);
	}
	else
	{
		$message = 'Server is down Please wait some time';
		$_SESSION['amgt_verify'] = '3';
		$result_array = array('message'=>$message,'amgt_verify'=>$_SESSION['amgt_verify'],'location_url'=>$location_url);
		echo json_encode($result_array);
	}
	die(); 
}
//------------------ LOAD UNIT Measurement ------------------------//
function amgt_load_unit_measurements()
{
	$_REQUEST['building_id'];
	$_REQUEST['unit_name'];
	$_REQUEST['unit_cat_id'];
	global $wpdb;
		$table_residential = $wpdb->prefix. 'amgt_residential_units';
		$unit_catdata = $wpdb->get_row("select units from $table_residential where building_id=".$_REQUEST['building_id']." AND unit_cat_id=".$_REQUEST['unit_cat_id']);
		$all_entry=json_decode($unit_catdata->units);
		
		$unit_measurement=0;
		$unit_charges=0;
		$maitenance_charge=get_option( 'maitenance_charge' );;
		if(!empty($all_entry))
		{
			foreach($all_entry as $unit)
			{
				if($unit->entry==$_REQUEST['unit_name'])
				{
					$unit_measurement=$unit->measurement;
				}
			}
		}
		$unit_charges=$unit_measurement*$maitenance_charge;
		$result[]=$unit_measurement;
		$result[]=$unit_charges;
		echo json_encode($result);
		die();
	
}
//-------load dynamic tax amount------
function amgt_load_tax_amount()
{
	$tax_id=0;
	$taxpercentage=0;
	if($_REQUEST['tax_id']!='')
		$tax_id=$_REQUEST['tax_id'];
	$obj_tax =new Amgt_Tax;
	$result=$obj_tax->amgt_get_single_tax($tax_id);
	if(!empty($result))
		$taxpercentage=$result->tax;
	echo $taxpercentage;
	die();
}
/* ------- view member list by unit name-----*/
function amgt_unit_wise_view_member()
{
	  $unit_name = $_REQUEST['unit_name'];
	  $building_category = $_REQUEST['building_category'];
	  $unit_category = $_REQUEST['unit_category'];

	  $args = array(
		'role' => 'member',
        'meta_query'=>
         array(
			'relation' => 'AND',
            array(
                'relation' => 'AND',
			array(
				'key'	  =>'building_id',
				'value'	=>	$building_category,
				'compare' => '=',
			),
			array(
				'key'	  =>'unit_cat_id',
				'value'	=>	$unit_category,
				'compare' => '=',
			),
			array(
				'key'	  =>'unit_name',
				'value'	=>	$unit_name,
				'compare' => '=',
			)
          )
       )
    );

	 $allmembers = get_users($args);
	  $array_var=array();
	  $array_var[]="<option value=''>Select Member</option>";
	  foreach($allmembers as $allmembers_data)
	  {
		$option = "<option value='$allmembers_data->ID'>".$allmembers_data->display_name."</option>";
	    $array_var[] = $option;
	  }
	  echo json_encode($array_var);
	  die;
} 
//------------------ ACCOUNT UNIT WISE VIEW MEMBER ---------------//
function amgt_account_unit_wise_view_member()
{
	  $unit_name = $_REQUEST['unit_name'];
	  $building_category = $_REQUEST['building_category'];
	  $unit_category = $_REQUEST['unit_category'];

	  $args = array(
		'role' => 'member',
        'meta_query'=>
         array(
			'relation' => 'AND',
            array(
                'relation' => 'AND',
			array(
				'key'	  =>'building_id',
				'value'	=>	$building_category,
				'compare' => '=',
			),
			array(
				'key'	  =>'unit_cat_id',
				'value'	=>	$unit_category,
				'compare' => '=',
			),
			array(
				'key'	  =>'unit_name',
				'value'	=>	$unit_name,
				'compare' => '=',
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
				'value'	=>	'tenant',
				'compare' => '=',
			)
		  )
          )
       )
    );

	 $allmembers = get_users($args);
	  $array_var=array();
	  $array_var[]="<option value=''>Select Member</option>";
	  foreach($allmembers as $allmembers_data)
	  {
		$option = "<option value='$allmembers_data->ID'>".$allmembers_data->display_name."</option>";
	    $array_var[] = $option;
	  }
	  echo json_encode($array_var);
	  die;
} 

 // amgt add unit in pop-up function
 function amgt_add_unit_popup()
 {
	  global $wpdb;
	  $obj_units=new Amgt_ResidentialUnit;
	  echo $result=$obj_units->amgt_add_residential_unit($_POST);
	  die(); 
 }
  // amgt add member in pop-up function
function amgt_add_member_popup()
 {
		if($_FILES['id_proof_1']['name'] != "" && $_FILES['id_proof_1']['size'] > 0)
		{
			$id_proof_1=amgt_load_documets($_FILES['id_proof_1'],$_FILES['id_proof_1'],'id_proof_1');
		}
		else
		{
			$id_proof_1=$_REQUEST['hidden_id_proof_1'];
		} 
		
		if($_FILES['id_proof_2']['name'] != "" && $_FILES['id_proof_2']['size'] > 0)
		{
			$id_proof_2=amgt_load_documets($_FILES['id_proof_2'],$_FILES['id_proof_2'],'id_proof_2');
		}
		else
		{
			$id_proof_2=$_REQUEST['hidden_id_proof_2'];
		} 
		
	$obj_member=new Amgt_Member;
	$result=$obj_member->amgt_add_member($_POST);
	$obj_member->amgt_upload_documents($id_proof_1,$id_proof_2,$result);
	$user_info = get_userdata($result);
	$option ="";
	if(!empty($user_info))
	{
		$option = "<option value='".$user_info->ID ."'>".$user_info->display_name ."</option>";
	}
	echo $option;
	die();
 }
//------------- LOAD DOCUMENT HTML -------------------// 
function amgt_load_document_html()
{ ?>
   <script>
   //use in add more document with class
   var file_frame;
		  jQuery('.upload_user_avatar_button_add_more').on("click",function( event ){
		    event.preventDefault();

		    // If the media frame already exists, reopen it.
		    if ( file_frame ) {
		      file_frame.open();
		      return;
		    }

		    // Create the media frame.
		    file_frame = wp.media.frames.file_frame = wp.media({
		      title: jQuery( this ).data( 'uploader_title' ),
		      button: {
		        text: jQuery( this ).data( 'uploader_button_text' ),
		      },
		      multiple: false  // Set to true to allow multiple files to be selected
		    });

		    // When an image is selected, run a callback.
		    file_frame.on( 'select', function() {
		      // We set multiple to false so only get one image from the uploader
		      attachment = file_frame.state().get('selection').first().toJSON();
		    // alert(attachment.url);
		      jQuery(".amgt_user_avatar_url").val(attachment.url);
		      // $('#upload_user_avatar_preview img').attr('src',attachment.url);
		      // Do something with attachment.id and/or attachment.url here
		    });

		    // Finally, open the modal
		    file_frame.open();
		  });
		$('.onlyletter_number_space_validation').keypress(function( e ) 
		{     
			var regex = new RegExp("^[0-9a-zA-Z \b]+$");
			var key = String.fromCharCode(!event.charCode ? event.which: event.charCode);
			if (!regex.test(key)) 
			{
				event.preventDefault();
				return false;
			} 
	   });  
   </script>
	   <div class="form-group">
		
		    <label class="col-sm-2 control-label" for="doc_title"><?php esc_html_e('Upload Document','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-2">
				<input class="form-control validate[required] text-input" maxlength="50" type="text" placeholder="Title"  value="" name="doc_title[]">
			</div>
			<div class="col-sm-2">
				<input type="text" class="form-control amgt_user_avatar_url  validate[required] onlyletter_number_space_validation" name="amgt_user_avatar[]"  
				value=" " />
			</div>	
				<div class="col-sm-2">
       				 <input type="button" class="button upload_user_avatar_button_add_more" value="<?php esc_html_e('Upload Document', 'apartment_mgt' ); ?>" />
			</div>
			<div class="col-sm-2">
				 <textarea name="description[]" maxlength="150" placeholder="Description" class="form-control text-input resize"><?php if($edit) echo esc_textarea($result->description);?></textarea>
			</div>
			
			<div class="col-sm-1">
				<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
				<i class="entypo-trash"><?php esc_html_e('Delete','hospital_mgt');?></i>
				</button>
			</div>
		</div>
    <?php die();
} 
//--------------- LOAD DOCUMENT HTML FRONTEND --------------------//	
function amgt_load_document_html_frontend()
{  ?>
	<script type="text/javascript">
		$('.onlyletter_number_space_validation').keypress(function( e ) 
		{ 
			"use strict";
			var regex = new RegExp("^[0-9a-zA-Z \b]+$");
			var key = String.fromCharCode(!event.charCode ? event.which: event.charCode);
			if (!regex.test(key)) 
			{
				event.preventDefault();
				return false;
			} 
	   });  
	</script>
	   <div class="form-group">
		    <label class="col-sm-2 control-label" for="doc_title"><?php esc_html_e('Upload Document','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-2">
				<input id="doc_title" class="form-control validate[required] text-input onlyletter_number_space_validation" type="text" placeholder="<?php esc_html_e('Title','apartment_mgt');?>"  value="<?php if($edit) echo esc_attr($result->doc_title);?>" name="doc_title[]">
			</div>
			<div class="col-sm-1">
				<input type="text" id="amgt_user_avatar_url" maxlength="50" class="form-control margin_top_10_res" name="amgt_user_avatar[]"    
				value="" readonly />
			</div>	
				<div class="col-sm-3 member_doc">
       				 <input id="upload_file" name="upload_file[]" onchange="fileCheck(this);" type="file" <?php if($edit){ ?>class="margin_top_5_res margin_left_15_res" <?php }else{ ?>class="validate[required] margin_top_5_res margin_left_15_res"<?php } ?>  />
			</div>
			<div class="col-sm-2">
				 <textarea name="description[]" maxlength="150" placeholder="<?php esc_html_e('Description','apartment_mgt');?>" class="form-control text-input resize"><?php if($edit) echo esc_textarea($result->description);?></textarea>
			</div>
			
			<div class="col-sm-1">
				<button type="button" class="btn btn-default margin_top_10_res" onclick="deleteParentElement(this)">
				<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
				</button>
			</div>
		</div>
    <?php die(); 
} 
//----------------- GENERATE INVOICE FROM ALL MEMBER -----------------//	
function amgt_generate_invoice_form_allmember()
{ 
 $val = $_POST['val'];
	 if($val == 'Maintenance Charge')
{  ?>
      <div class="header">	<hr>
		  <h4><?php esc_html_e('Maintenance Settings','apartment_mgt');?></h4>
	   </div>
	 <div class="form-group">
		<label class="col-sm-2 control-label" for="Measurement"><?php esc_html_e('Select Unit Measurement','apartment_mgt');?><span class="require-field">*</span></label>
		<div class="col-sm-8">
		<?php  $measerment_type="square_feet"; ?>
			<label class="radio-inline">
			 <input type="radio" value="square_feet" class="tog validate[required]" name="amgt_unit_measerment_type"  <?php  checked( 'square_feet', $measerment_type);  ?>/><?php esc_html_e('Square Feet','apartment_mgt');?>
			</label>
			<label class="radio-inline">
			  <input type="radio" value="square_meter" class="tog validate[required]" name="amgt_unit_measerment_type"  <?php  checked( 'square_meter', $measerment_type);  ?>/><?php esc_html_e('Square Meter','apartment_mgt');?> 
			</label>
			 <label class="radio-inline">
			  <input type="radio" value="square_yards" class="tog validate[required]" name="amgt_unit_measerment_type"  <?php  checked( 'square_yards', $measerment_type);  ?>/><?php esc_html_e('Square Yards','apartment_mgt');?> 
			</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label" for="amgt_email"><?php esc_html_e('Maintenance Charge','apartment_mgt');?> <span class="require-field">*</span></label>
		<div class="col-sm-8">
			<input id="maitenance_charge" class="form-control validate[required] text-input" type="text" value=""  name="maitenance_charge">
		</div>
		<div class="col-sm-1">
				<span class="font_size_20"><?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?></span>
			</div>
		
	</div>
	<hr>
	<?php
} 
else
{
}
	die();
}
/* ------- view member wise invoice-----*/
function amgt_member_wise_view_invoice()
{      $obj=new Amgt_Accounts;
	  $member_id=$_REQUEST['member_id'];
	  $invoice_list= $obj->amgt_get_all_unpaid_crated_invoice_memberid($member_id);
	  $array_var=array();
	  $array_var[]="<option value=''>Select Invoice</option>";
	  foreach($invoice_list as $invoice_list_data)
	  {
		$option = "<option value='".$invoice_list_data->invoice_id ."'>".$invoice_list_data->invoice_no ." - " . amgt_get_invoice_title($invoice_list_data->invoice_id) ."</option>";
	    $array_var[] = $option;
		
	  }
	  echo json_encode($array_var);
	  die;
} 

/* ------- view member wise invoice-----*/
function amgt_invoice_option_html()
{ 
    $invoice_option = $_POST['invoice_option'];
	
	if($invoice_option == 'one_member')
	 { 
 		?>
		 
		<div class="form-group">
					<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] building_category" name="building_id">
						  <option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
						<?php 
						if($edit)
							$category =$result->building_id;
						elseif(isset($_REQUEST['building_id']))
							$category =$_REQUEST['building_id'];  
						else 
							$category = "";
						
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
							} ?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="unit_name"><?php esc_html_e('Unit','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] account_unit_name" name="unit_name">
						<option value=""><?php esc_html_e('Select Unit Name','apartment_mgt');?></option>
						<?php 
						if($edit){
							 $unitname =$result->unit_name;
							 $unitsarray=$obj_units->amgt_get_single_cat_units($result->unit_cat_id);
							 $all_entry=json_decode($unitsarray);
							
							if(!empty($all_entry)){
								foreach($all_entry as $unit)
								{ ?>
									<option value="<?php echo esc_attr($unit->entry); ?>" <?php selected($unitname,$unit->entry);?>><?php echo esc_html($unit->entry);?> </option>
								<?php }
							}
							
						} ?>
						</select>
					</div>
				</div>
				
				 
				<div class="form-group">
					<label class="col-sm-2 control-label"  for="member"><?php esc_html_e('Member','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] member_id" id="member_id" name="member_id">
						<option value=""><?php esc_html_e('Select Member','apartment_mgt');?></option>
							<?php if($edit)
							{
								$memberid =$result->member_id;
								$unitname =$result->unit_name;
								$category =$result->unit_cat_id;
								$building =$result->building_id;
								
							  $user_query = new WP_User_Query(
								 array(
								'meta_key'	  =>	'unit_name',
								'meta_value'	=>	$unitname
								 ),
								array( 'meta_key'	  =>	'building_id',
								'meta_value'	=>	$building ),
								array( 'meta_key'	  =>	'unit_cat_id',
								'meta_value'	=>	$category )
									 ); 
								  $allmembers = $user_query->get_results();
								   foreach($allmembers as $allmembers_data)
								  {
									 echo '<option value="'.$allmembers_data->ID.'" '.selected($memberid,$allmembers_data->ID).'>'.$allmembers_data->display_name.'</option>';
								  }
							}
							 ?>
						</select>
					</div>
					
				</div>
		
	 <?php  }
	elseif($invoice_option == 'Building')
	{?>
			<div class="form-group">
					<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] building_category" name="building_id" id="">
						  <option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
						<?php 
						if($edit)
							$category =$result->building_id;
						elseif(isset($_REQUEST['building_id']))
							$category =$_REQUEST['building_id'];  
						else 
							$category = "";
						
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
		<?php 
		}
		
		elseif($invoice_option == 'Unit Category')
	{?>
			<div class="form-group">
					<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] building_category" name="building_id" id="">
						  <option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
						<?php 
						if($edit)
							$category =$result->building_id;
						elseif(isset($_REQUEST['building_id']))
							$category =$_REQUEST['building_id'];  
						else 
							$category = "";
						
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
				<div class="form-group">
					<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] unit_categorys" name="unit_cat_id" id="">
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
							} ?>
						</select>
					</div>
				</div>
		<?php 
		}
		
		elseif ($invoice_option == 'all_member')
		{	 
        }
		die();
} 
/* ------- view Charge Calculate wise html-----*/
function amgt_charge_cal_option_html()
{ 
    $charge_cal_option = $_POST['charge_cal_option'];
	
	if($charge_cal_option == 'fix_charge')
	 { 
 	?>
		<script type="text/javascript">
		$(document).ready(function() {
			"use strict";
			$('.onlyletter_number_space_validation').keypress(function( e ) 
			{     
				var regex = new RegExp("^[0-9a-zA-Z \b]+$");
				var key = String.fromCharCode(!event.charCode ? event.which: event.charCode);
				if (!regex.test(key)) 
				{
					event.preventDefault();
					return false;
				} 
		   });  
		} );
		</script>
		 <div class="form-group">
			<label class="col-sm-2 control-label" for="income_entry"><?php esc_html_e('Charges Payment','apartment_mgt');?> (<?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>)<span class="require-field">*</span></label>
			<div class="col-sm-2">
				<input id="income_amount" class="form-control validate[required] text-input income_amount" type="number" min="0" value="" name="income_amount[]" placeholder="Charges Amount">
			</div>			
			<div class="col-sm-4">
				<input id="income_entry" maxlength="50" class="form-control validate[required] margin_top_10_res text-input onlyletter_number_space_validation" type="text" value="" name="income_entry[]" placeholder="Charges Entry Label">
			</div>			
			<div class="col-sm-2">
			<button type="button" class="btn btn-default margin_top_10_res" onclick="deleteParentElement(this)">
			<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
			</button>
			</div>
		</div>
	 <?php  
	}
	elseif($charge_cal_option == 'measurement_charge')
	{
		$unit_measerment_type=get_option( 'amgt_unit_measerment_type' );						
	?>
			<div class="form-group">
					<label class="col-sm-2 control-label" for="income_entry"><?php esc_html_e('Charges Payment','apartment_mgt');?> (<?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' ));?>)<span class="require-field">*</span></label>
					<div class="col-sm-2">
						<input id="income_amount" class="form-control validate[required] text-input income_amount" type="number" min="0" value="" name="income_amount[]" placeholder="<?php esc_html_e('Charges Amount','apartment_mgt');?>">
					</div>	
					<div class="float_left_top_font_size_13">
						/ per <?php echo esc_html($unit_measerment_type);?>
					</div>					
					
				</div>
			</div>		
	<?php 
	}
	die();
} 
//-------------------------- TAX DIV HTML -------------------//
function amgt_tax_div_html()
{ 
    $charge_cal_option = $_POST['charge_cal_option'];
	
	if($charge_cal_option == 'fix_charge')
	{ 
 	?>
		<div class="form-group">
				<input type="hidden" id="increament_val" name="increament_val" value="1">
				<label class="col-sm-2 control-label" for="income_entry"><?php esc_html_e('Tax','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-4">
				<select name="tax_title[]" id="1" class="form-control valid tax_selection">
					<option value=""><?php esc_html_e('Select Tax','apartment_mgt');?></option>
					<?php $obj_tax =new Amgt_Tax;
					$tax_data= $obj_tax->Amgt_get_all_tax();
						 if(!empty($tax_data))
						 {
							foreach ($tax_data as $retrieved_data){ ?>
								<option value="<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_html($retrieved_data->tax_title);?></option>
						<?php }
						 }	?>
				</select>
			</div>
				<div class="col-sm-2">
					<input id="tax_entry_1" class="form-control validate[required] text-input" type="text" value="" name="tax_entry[]" placeholder="Tax" readonly>
				</div>
				<div class="col-sm-2 measurement_hide_div">
					<input id="tax_amount_1" class="form-control validate[required] text-input get_tax_amount" type="text" value="" name="tax_amount_entry[]" placeholder="Tax Amount" readonly>
				</div>
				<div class="col-sm-1">
				<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
				<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
				</button>
				</div>
			</div>
	 <?php  
	}
	elseif($charge_cal_option == 'measurement_charge')
	{?>
			<div class="form-group">
				<input type="hidden" id="increament_val" name="increament_val" value="1">
				<label class="col-sm-2 control-label" for="income_entry"><?php esc_html_e('Tax','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-4">
				<select name="tax_title[]" id="1" class="form-control valid tax_selection">
					<option value=""><?php esc_html_e('Select Tax','apartment_mgt');?></option>
					<?php $obj_tax =new Amgt_Tax;
					$tax_data= $obj_tax->Amgt_get_all_tax();
						 if(!empty($tax_data))
						 {
							foreach ($tax_data as $retrieved_data){ ?>
								<option value="<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_html($retrieved_data->tax_title);?></option>
						<?php }
						 }	?>
				</select>
			</div>
				<div class="col-sm-2">
					<input id="tax_entry_1" class="form-control validate[required] text-input" type="text" value="" name="tax_entry[]" placeholder="Tax" readonly>
				</div>				
				<div class="col-sm-2">
				<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
				<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
				</button>
				</div>
			</div>
	<?php 
	}
	die();
} 
//----------------- INVOICE OPTION MAINATANCE --------------------------//
 function amgt_invoice_option_maintance()
 {
	 $invoice_option = $_POST['invoice_option'];
	 if($invoice_option == 'one_member')
	 { 
 		?>
		 
		<div class="form-group">
					<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] building_category" name="building_id" id="building_category">
						  <option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
						<?php 
						if($edit)
							$category =$result->building_id;
						elseif(isset($_REQUEST['building_id']))
							$category =$_REQUEST['building_id'];  
						else 
							$category = "";
						
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
				<div class="form-group">
					<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] unit_category" name="unit_cat_id" id="unit_category">
						<option value=""><?php esc_html_e('Select Unit Category','apartment_mgt');?></option>
						<?php 
						if($edit)
							$category =$result->building_id;
						elseif(isset($_REQUEST['building_id']))
							$category =$_REQUEST['building_id'];  
						else 
							$category = "";
						
						$activity_category=amgt_get_all_category('building_category');
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
						<select class="form-control validate[required] unit_name" name="unit_name" id="unit_name">
						<option value=""><?php esc_html_e('Select Unit Name','apartment_mgt');?></option>
						<?php 
						if($edit){
							 $unitname =$result->unit_name;
							 $unitsarray=$obj_units->amgt_get_single_cat_units($result->unit_cat_id);
							 $all_entry=json_decode($unitsarray);
							
							if(!empty($all_entry)){
								foreach($all_entry as $unit)
								{ ?>
									<option value="<?php echo esc_attr($unit->entry); ?>" <?php selected($unitname,$unit->entry);?>><?php echo esc_html($unit->entry);?> </option>
								<?php }
							}
							
						} ?>
						</select>
					</div>
				</div>
				
				 
				<div class="form-group">
					<label class="col-sm-2 control-label"  for="member"><?php esc_html_e('Member','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] member_id" id="member_id" name="member_id">
						<option value=""><?php esc_html_e('Select Member','apartment_mgt');?></option>
							<?php if($edit)
							{
								$memberid =$result->member_id;
								$unitname =$result->unit_name;
								$category =$result->unit_cat_id;
								$building =$result->building_id;
								
							  $user_query = new WP_User_Query(
								 array(
								'meta_key'	  =>	'unit_name',
								'meta_value'	=>	$unitname
								 ),
								array( 'meta_key'	  =>	'building_id',
								'meta_value'	=>	$building ),
								array( 'meta_key'	  =>	'unit_cat_id',
								'meta_value'	=>	$category )
									 ); 
								  $allmembers = $user_query->get_results();
								   foreach($allmembers as $allmembers_data)
								  {
									 echo '<option value="'.$allmembers_data->ID.'" '.selected($memberid,$allmembers_data->ID).'>'.$allmembers_data->display_name.'</option>';
								  }
							}
							 ?>
						</select>
					</div>
					
				</div>
		
	 <?php  }
	 elseif($invoice_option == 'Building_member')
	 { ?>
		 <div class="form-group">
			<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select class="form-control validate[required]" name="building_id" id="building_category">
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
						echo '<option value="'.$retrive_data->ID .'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
					}
				} ?>
				</select>
			</div>
			
		</div>
	<?php  }
	
	elseif ($invoice_option == 'Unit_Category_member')
	 { ?>
		 <div class="form-group">
			<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select class="form-control validate[required]" name="building_id" id="building_category">
				<option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
				<?php 
				if($edit)
					$category =$result->building_id;
				elseif(isset($_REQUEST['building_id']))
					$category =$_REQUEST['building_id'];  
				else 
					$category = "";
				
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
		
		<div class="form-group">
			<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?></label>
			<div class="col-sm-8">
				<select class="form-control" name="unit_cat_id" id="unit_category">
				<option value=""><?php esc_html_e('Select Unit Category','apartment_mgt');?></option>
				<?php 
				if($edit){	
					$category =$result->unit_cat_id;
					$activity_category=amgt_get_all_category('unit_category');
				if(!empty($activity_category))
				{
					foreach ($activity_category as $retrive_data)
					{
						echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
					}	
				} 
				} ?>
				</select>
			</div>
		</div>
	<?php
 } 
 elseif ($invoice_option == 'all_member'){	 
 }
 die();
 }
//----------------- VISITOR CHECKIN DETAILS PRINT -----------------//
function amgt_visiter_checkin_details_print($visitor_checkin_id)
{
	$obj_gate=new Amgt_gatekeeper;
	$result_visitor_checkin = $obj_gate->amgt_get_single_checkin($visitor_checkin_id);
	global $wpdb;	
	$table_name = $wpdb->prefix. 'amgt_gates';
	 $result = $wpdb->get_row("SELECT * FROM $table_name where id=".$result_visitor_checkin->gate_id);
	echo '<link rel="stylesheet" href="'.plugins_url( '/assets/css/style.css', __FILE__).'"></link>';
	?>	
<style type="text/css" media="print">
@media print {
   .footer,
   #non-printable {
       display: none !important;
   }
   #printable {
       display: block;
   }
}
</style>	
	<div class="modal-body invoice_body">
		<div id="invoice_print1">			
			<div class="">				
				<br>
				<p><label><b> <?php esc_html_e('GATE: ','apartment_mgt');?></b><?php echo esc_html($result->gate_name); ?></label></p>
				<p><label><b> <?php esc_html_e('COMPOUND: ','apartment_mgt');?></b><?php echo get_the_title($result_visitor_checkin->building_id); ?></label></p>
				<p><label><b> <?php esc_html_e('UNIT CATEGORY: ','apartment_mgt');?></b><?php echo get_the_title($result_visitor_checkin->unit_cat); ?></label></p>
				<p><label><b> <?php esc_html_e('UNIT NUMBER: ','apartment_mgt');?></b><?php echo esc_html($result_visitor_checkin->unit_name); ?></label></p>
				<p><label><b> <?php esc_html_e('REASON FOR VISIT: ','apartment_mgt');?></b><?php echo get_the_title($result_visitor_checkin->reason_id); ?></label></p>
				<p><label class="margin_left_20"><b> <?php esc_html_e('VISIT DATE: ','apartment_mgt');?></b><?php echo date(amgt_date_formate(),strtotime($result_visitor_checkin->checkin_date));?></label>
				<label><b> <?php esc_html_e('VISIT TIME: ','apartment_mgt');?> : </b><?php echo esc_html($result_visitor_checkin->checkin_time);?></label></p>
				<table class="table table-bordered width_100 table_new" class="width_93" border="1">
					<thead class="entry_heading">				
						<tr>
							<th class="color_black align_center padding_color">#</th>
							<th class="color_black align_center padding_color"> <?php esc_html_e('VISITOR NAME','apartment_mgt');?></th>
							<th class="color_black align_center padding_color"><?php esc_html_e('ID NUMBER','apartment_mgt');?> </th>
							<th class="color_black align_center padding_color"><?php esc_html_e('VEHICLE NUMBER','apartment_mgt');?> </th>
						</tr>						 
					</thead>
					<tbody>
						<?php
						$id=1;
						$all_visiter_entry=json_decode($result_visitor_checkin->visiters_value);
				
						if(!empty($all_visiter_entry))
						{
							foreach($all_visiter_entry as $entry1)
							{						
								?>
								<tr>
									<td class="align_center padding_color_black"><?php echo esc_html($id); ?></td>
									<td class="align_center padding_color_black"><?php echo esc_html($entry1->visitor_name); ?></td>
									<td class="align_center padding_color_black"><?php echo esc_html($entry1->mobile); ?></td>
									<td class="align_center padding_color_black"><?php echo esc_html($entry1->vehicle_number); ?></td>
								</tr>
								<?php
								$id=$id+1;
							}
						}
						?>
					</tbody>	
				</table>

					<table class="width_100">
						<tbody >
							<tr>
								<td align="center">
									<img src="<?php echo AMS_PLUGIN_URL;?>/assets/images/approved.png" alt="top view" />
								</td>
							</tr>
						</tbody>
					</table>				
			</div>
		</div>
	</div>
	<?php	
 die();
}
//------------------ LOAD VISITOR DATA BY ID -------------------//
function amgt_load_visitor_data_by_id()
{
	$bulding_data=array();
	$visitor_name=$_REQUEST['visitor_name'];
	$data='"visitor_name":"'.$visitor_name.'"';
	global $wpdb;
	$table_name = $wpdb->prefix. 'amgt_checkin_entry';
    $result = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE visiters_value REGEXP '".$data."' ORDER BY id DESC LIMIT 1");
	if(!empty($result))
	{ 
		$gate_id=$result[0]->gate_id;
		$reason_id=$result[0]->reason_id;
		$building_id=$result[0]->building_id;
		$unit_cat=$result[0]->unit_cat;
		$unit_name=$result[0]->unit_name;
		$description=$result[0]->description;
		$visiters_value=$result[0]->visiters_value;
		
		$someArray = json_decode($visiters_value, true);
		
		foreach($someArray as $visitor_data)
		{
			if($visitor_data['visitor_name'] == $visitor_name)
			{
				$visitor_id=$visitor_data['mobile'];
				$vehicle_number=$visitor_data['vehicle_number'];
			}
		}
		$table_residential = $wpdb->prefix. 'amgt_residential_units';
		$unit_catdata = $wpdb->get_results("select units from $table_residential where building_id=".$building_id." AND unit_cat_id=".$unit_cat);
		$all_entry=array();
		 $unit_var[]="<option value=''><?php esc_html_e('Select Unit Name','apartment_mgt');?></option>";
		 
			if(!empty($unit_catdata))
			{
				foreach($unit_catdata as $unit)
				{
					$all_entry[]=json_decode($unit->units);
				}
			}
			$unit_var =array();
			if(!empty($all_entry))
			{
				foreach($all_entry as $key=>$val)
				{			
					foreach($val as $key1=>$val1)
					{					
						$option = '<option value="'.$val1->entry.'">'.$val1->entry .'</option>';
						$unit_var[] = $option;
					}
				}
			}
		$bulding_data[] ='1';
		$bulding_data[] = $gate_id;
		$bulding_data[] = $reason_id;
		$bulding_data[] = $building_id;
		$bulding_data[] = $unit_cat;
		$bulding_data[] = $unit_name;
		$bulding_data[] = $description;
		$bulding_data[] = $unit_var;
		$bulding_data[] = $visitor_id;
		$bulding_data[] = $vehicle_number;
	}
	else
	{
		$bulding_data[]='0';
	} 
	
	echo json_encode($bulding_data);
	die();
 }
//-------------------- LOAD DOCUMENT HTML MEMBER -----------------// 	
function amgt_load_document_html_member()
{  ?>

	<script type="text/javascript">

		$('.onlyletter_number_space_validation').keypress(function( e ) 

		{     
			"use strict";
			var regex = new RegExp("^[0-9a-zA-Z \b]+$");

			var key = String.fromCharCode(!event.charCode ? event.which: event.charCode);

			if (!regex.test(key)) 

			{

				event.preventDefault();

				return false;

			} 

	   });  

	</script>

	   <div class="form-group">

		    <label class="col-sm-2 control-label" for="doc_title"><?php esc_html_e('Upload Document','apartment_mgt');?><span class="require-field">*</span></label>

			<div class="col-sm-2">

				<input id="doc_title" class="form-control validate[required] text-input onlyletter_number_space_validation" type="text" placeholder="<?php esc_html_e('Title','apartment_mgt');?>"  value="<?php if($edit) echo esc_attr($result->doc_title);?>" name="doc_title[]">

			</div>

			<div class="col-sm-2 member_doc margin_left_15_res">

       			<input id="upload_file" name="upload_file[]" onchange="fileCheck(this);" type="file" <?php if($edit){ ?>class="" <?php }else{ ?>class="validate[required]"<?php } ?>  />

			</div>		

			<div class="col-sm-1">

				<button type="button" class="btn btn-default margin_top_10_res" onclick="deleteParentElement(this)">

					<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>

				</button>

			</div>

		</div>

    <?php die(); 
}
?>