<?php 
  //-------- CHECK BROWSER JAVA SCRIPT ----------//
MJamgt_browser_javascript_check(); 
//--------------- ACCESS WISE ROLE -----------//
$user_access=amgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJamgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
$curr_user_id=get_current_user_id();
$obj_apartment=new Apartment_management($curr_user_id);
$obj_units=new Amgt_ResidentialUnit;
$obj_parking=new Amgt_Parking;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'sloat-list';
	if(isset($_POST['save_sloat']))		
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_sloat_nonce' ) )
		{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_parking->amgt_add_sloat($_POST);
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=parking-manager&tab=sloat-list&message=2');
			}
		}
		else
		{
			$result=$obj_parking->amgt_add_sloat($_POST);
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=parking-manager&tab=sloat-list&message=1');
			}
		}
	}
	}
	if(isset($_POST['assign_sloat']))//ASSIGN SLOT 
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'assign_sloat_nonce' ) )
		{
		$slaot_id = $_POST['sloat_id'];
		$from_date =amgt_get_format_for_db($_POST['from_date']);
		$to_date = amgt_get_format_for_db($_POST['to_date']);	
		
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			global $wpdb;
			$table_name = $wpdb->prefix. 'amgt_parking';
			
			$sloat_assign_id = $_POST['sloat_assign_id'];
			
			$result_allready_assigned = $wpdb->get_results("SELECT * FROM $table_name where (sloat_id=$slaot_id) AND (((from_date BETWEEN '$from_date' AND '$to_date') AND (to_date BETWEEN '$from_date' AND '$to_date')) OR (('$from_date' BETWEEN from_date AND to_date) OR ('$to_date' BETWEEN from_date AND to_date))) AND (id<>$sloat_assign_id)");		
			
			if(!empty($result_allready_assigned))
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=parking-manager&tab=assign_sloat&action=edit&sloat_assign_id='.$_REQUEST['sloat_assign_id'].'&message=4');
			}
			else
			{
				$result=$obj_parking->amgt_assign_sloat($_POST);
				if($result)
				{
					wp_redirect ( home_url().'?apartment-dashboard=user&page=parking-manager&tab=assigned-sloat-list&message=2');
				}
			}	
		}
		else
		{
			global $wpdb;
			$table_name = $wpdb->prefix. 'amgt_parking';
			
			$result_allready_assigned = $wpdb->get_results("SELECT * FROM $table_name where (sloat_id=$slaot_id) AND (((from_date BETWEEN '$from_date' AND '$to_date') AND (to_date BETWEEN '$from_date' AND '$to_date')) OR (('$from_date' BETWEEN from_date AND to_date) OR ('$to_date' BETWEEN from_date AND to_date)))");
		
			if(!empty($result_allready_assigned))
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=parking-manager&tab=assign_sloat&message=4');
			}
			else
			{
				$result=$obj_parking->amgt_assign_sloat($_POST);
				if($result)
				{
					wp_redirect ( home_url().'?apartment-dashboard=user&page=parking-manager&tab=assigned-sloat-list&message=1');
				}
			}	
		}
		
	}
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')//DELETE SLOT
	{
			if(isset($_REQUEST['sloat_id']))
			{
				$result=$obj_parking->amgt_delete_sloat($_REQUEST['sloat_id']);
				if($result)
				{
					wp_redirect ( home_url().'?apartment-dashboard=user&page=parking-manager&tab=sloat-list&message=3');
				}
			}
			if(isset($_REQUEST['sloat_assign_id']))
			{
				$result=$obj_parking->amgt_delete_assigned_sloat($_REQUEST['sloat_assign_id']);
				if($result)
				{
					wp_redirect ( home_url().'?apartment-dashboard=user&page=parking-manager&tab=assigned-sloat-list&message=3');
				}
			}
			
	}	
	if(isset($_REQUEST['message']))//MESSAGES
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
				<div id="message" class="updated below-h2 ">
				<p>
				<?php 
					esc_html_e('Record inserted successfully','apartment_mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 2)
		{ ?><div id="message" class="updated below-h2 "><p><?php
					_e("Record updated successfully.",'apartment_mgt');
					?></p>
					</div>
				<?php 
			
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			esc_html_e('Record deleted successfully','apartment_mgt');
		?></div></p><?php
				
		}
		elseif($message == 4) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			esc_html_e('This Parking Slot Allready Assigned','apartment_mgt');?>
		</div></p>
		<?php
				
		}
	}
	?>

<!-- VIEW POPUP CODE -->	
<div class="popup-bg">
    <div class="overlay-content">
        <div class="notice_content"></div>    
    </div> 
</div>	
<!-- END POP-UP CODE -->
<div class="panel-body panel-white"><!-- PANEL WHITE DIV -->
    <ul class="nav nav-tabs panel_tabs" role="tablist"><!-- PANEL_TABS -->
	  	<li class="<?php if($active_tab=='sloat-list'){?>active<?php }?>">
			<a href="?apartment-dashboard=user&page=parking-manager&tab=sloat-list" class="tab <?php echo $active_tab == 'sloat-list' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php esc_html_e('Slot List', 'apartment_mgt'); ?></a>
          </a>
        </li>
	 <?php if(($obj_apartment->role=='staff_member' || $obj_apartment->role=='gatekeeper') && ($user_access['add']=='1')){?>
        <li class="<?php if($active_tab=='add_sloat'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['notice_id']))
			{ ?>
			<a href="?apartment-dashboard=user&page=parking-manager&tab=add_sloat&action=edit&notice_id=<?php echo $_REQUEST['notice_id'];?>" class="nav-tab <?php echo $active_tab == 'visitor-checkin' ? 'nav-tab-active' : ''; ?>">
             <i class="fa fa"></i> <?php esc_html_e('Edit Slot', 'apartment_mgt'); ?></a>
			 <?php }
			else
			{ ?>
				<a href="?apartment-dashboard=user&page=parking-manager&tab=add_sloat" class="tab <?php echo $active_tab == 'add_sloat' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php esc_html_e('Add Slot', 'apartment_mgt'); ?></a>
	    <?php } ?>
	    </li>
		 <?php } ?>
		<li class="<?php if($active_tab=='assigned-sloat-list'){?>active<?php }?>">
				<a href="?apartment-dashboard=user&page=parking-manager&tab=assigned-sloat-list" class="tab margin_top_10_res <?php echo $active_tab == 'assigned-sloat-list' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php esc_html_e('Assigned Slot List', 'apartment_mgt'); ?></a>
			  </a>
		  </li>
		<?php  if(($obj_apartment->role=='staff_member' || $obj_apartment->role=='gatekeeper') && ($user_access['add']=='1')){?> 
		   <li class="<?php if($active_tab=='assign_sloat'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab']=='assign_sloat')
				{ ?>
				<a href="?apartment-dashboard=user&page=parking-manager&tab=assign_sloat&action=edit&sloat_assign_id=<?php echo $_REQUEST['sloat_assign_id'];?>" class="nav-tab margin_top_10_res <?php echo $active_tab == 'assign_sloat' ? 'nav-tab-active' : ''; ?>">
				 <i class="fa fa"></i> <?php esc_html_e('Edit Slot Assigned', 'apartment_mgt'); ?></a>
				 <?php }
				else
				{ ?>
					<a href="?apartment-dashboard=user&page=parking-manager&tab=assign_sloat" class="tab margin_top_10_res <?php echo $active_tab == 'assign_sloat' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_html_e('Assign Slot', 'apartment_mgt'); ?></a>
		  <?php } ?>
		  
		</li>
	<?php } ?>
	 
    </ul>
	<div class="tab-content">
	<!--SLOT-LIST-->
	<?php if($active_tab == 'sloat-list')
	{ ?>
		<script type="text/javascript">
		$(document).ready(function() {
			"use strict";
			jQuery('#sloat_list').DataTable({
				"responsive": true,
				"order": [[ 0, "asc" ]],
				"aoColumns":[
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  <?php if($obj_apartment->role=='staff_member' || $obj_apartment->role=='gatekeeper'){?>
							  {"bSortable": false}<?php } ?>],
							  language:<?php echo amgt_datatable_multi_language();?>
				});
		} );
		</script>
    	<div class="panel-body"><!--PANEL BODY DIV-->
        	<div class="table-responsive"><!---TABLE-RESPONSIVE--->
				<table id="sloat_list" class="display" cellspacing="0" width="100%"><!---SLOT LIST TABLE--->
					<thead>
						<tr>
							<th><?php esc_html_e('Slot No', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Slot Type', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Comment', 'apartment_mgt' ) ;?></th>
							<?php if($obj_apartment->role=='staff_member' || $obj_apartment->role=='gatekeeper'){?>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
							<?php } ?>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php esc_html_e('Slot No', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Slot Type', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Comment', 'apartment_mgt' ) ;?></th>
							<?php if($obj_apartment->role=='staff_member' || $obj_apartment->role=='gatekeeper'){?>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
							<?php } ?>
						</tr>
					 </tfoot>
					<tbody>
						<?php 
						$user_id=get_current_user_id();
						//--- PARKING DATA FOR MEMBER  ------//
						if($obj_apartment->role=='member')
						{
							
							$sloatdata=$obj_parking->amgt_get_all_sloats();
						
						} 
						//--- PARKING DATA FOR STAFF MEMBER  ------//
						elseif($obj_apartment->role=='staff_member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{  
								$sloatdata=$obj_parking->amgt_get_own_sloats($user_id);
							}
							else
							{
								$sloatdata=$obj_parking->amgt_get_all_sloats();
							}
						}
						//--- PARKING DATA FOR ACCOUNTANT  ------//
						elseif($obj_apartment->role=='accountant')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$sloatdata=$obj_parking->amgt_get_own_sloats($user_id);
							}
							else
							{
								$sloatdata=$obj_parking->amgt_get_all_sloats();
							}
						}
						//--- PARKING DATA FOR GATEKEEPER  ------//
						else
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$sloatdata=$obj_parking->amgt_get_own_sloats($user_id);
							}
							else
							{
								$sloatdata=$obj_parking->amgt_get_all_sloats();
							}
						}
						
						if(!empty($sloatdata))
						{
							foreach ($sloatdata as $retrieved_data)
							{?>
								<tr>
									<td class="sloatname"><!--SLOT NAME---->
									<?php 
									echo esc_html($retrieved_data->sloat_name);?></td>
									<td class="sloattype"><?php if($retrieved_data->sloat_type=='guest') echo esc_html__('Guest','apartment_mgt'); else echo esc_html__('Member','apartment_mgt');?></td>
									<td class="comment"><?php echo esc_html($retrieved_data->comment);?></td>
									<?php if($obj_apartment->role=='staff_member' || $obj_apartment->role=='gatekeeper'){?>
									<td class="action">
								  <a href="?apartment-dashboard=user&page=parking-manager&tab=add_sloat&action=edit&sloat_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
									<a href="?apartment-dashboard=user&page=parking-manager&ab=sloat-list&action=delete&sloat_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
									onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
									<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
								   
									</td>
									<?php } ?>
								</tr>
							<?php 
							} 
						} ?>
					</tbody>
			    </table><!---END SLOT LIST TABLE--->
            </div><!---END TABLE-RESPONSIVE--->
        </div><!--END PANEL BODY DIV-->
		<?php }
      
	     if($active_tab == 'add_sloat')
			    { 
				  require_once AMS_PLUGIN_DIR.'/template/parking-manager/add_sloat.php' ;
		        }
			 if($active_tab == 'assigned-sloat-list')
			    { 
				  require_once AMS_PLUGIN_DIR.'/template/parking-manager/assigned-sloat-list.php' ;
		        }
			 if($active_tab == 'assign_sloat')
			    { 
				  require_once AMS_PLUGIN_DIR.'/template/parking-manager/assign_sloat.php' ;
		        }

        ?>
	</div><!--TAB CONENT  DIV -->
</div><!-- END PANEL WHITE BODY DIV -->
<?php ?>
<script type="text/javascript">
function member_imgefileCheck(obj)
{
	"use strict";
	var fileExtension = ['jpg','jpeg','png'];
	if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
	{
		alert("Only '.jpg','.jpeg','.png'  formats are allowed.");
		$(obj).val('');
	}	
}
function fileCheck(obj)
{
	var fileExtension = ['pdf','doc','jpg','jpeg','png'];
	if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
	{
		alert("Only '.pdf','.docx','.jpg','.jpeg','.png'  formats are allowed.");
		$(obj).val('');
	}	
}
</script>
<!----------ADD_MEMBER_FORM---------------------->
<script type="text/javascript">
$(document).ready(function() {
	"use strict";
	$('#member_form').validationEngine();
	jQuery('.birth_date').datepicker({
			dateFormat: "yy-mm-dd",
			maxDate : 0,
			changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+25',
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			},    
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "/" + year);
	        }                    
		}); 
	$('#occupied_date').datepicker({	
	dateFormat: "yy-mm-dd",  
	  autoclose: true
	}); 
	//username not  allow space validation
	$('#username').keypress(function( e ) {
       if(e.which === 32) 
         return false;
    });
	}); 
</script>
<div class="modal fade overflow_scroll" id="myModal_add_member" role="dialog">
    <div class="modal-dialog modal-lg"><!--MODAL-DIALOG---->
        <div class="modal-content"><!--MODAL-CONTENT---->
			<div class="modal-header"><!--MODAL-HEADER---->
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h3 class="modal-title"><?php esc_html_e('Add Member','apartment_mgt');?></h3>
			</div>
			<div class="modal-body"><!--MODAL BODY---->
			<?php $role='member';?>
			   <!--MEMBER_FORM---->
				<form name="member_form" action="<?php echo admin_url('admin-ajax.php'); ?>"  method="post" class="form-horizontal" id="member_form">
					<input type="hidden" name="action" value="amgt_add_member_popup">
					<input type="hidden" name="role" value="<?php echo esc_attr($role);?>"  />
					<form name="member_form" action="" method="post" class="form-horizontal" id="member_form" enctype="multipart/form-data">
					<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
					<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
					<input type="hidden" name="user_id" value="<?php echo esc_attr($member_id);?>"  />
					<input type="hidden" name="role" value="<?php echo esc_attr($role);?>"  />
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<select class="form-control validate[required] popup_member_building_category" name="building_id" >
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
						<div class="col-sm-2">
						
						<a href="#" class="btn btn-default" data-toggle="modal" id="add_bulding" data-target="#myModal_add_building"> <?php esc_html_e('Add Building','apartment_mgt');?></a>
						
						</div>
					</div>
					
					<div id="hello"></div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">			
							<select class="form-control validate[required] popup_member_unit_category" name="unit_cat_id">
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
							<select class="form-control validate[required] popup_member_unit_name" name="unit_name" >
							<option value=""><?php esc_html_e('Select Unit Name','apartment_mgt');?></option>
							<?php 
							if($edit)
							{
								$unitname =$result->unit_name;
								$building_id=$result->building_id;
								$unit_category=$result->unit_cat_id;
								$unitsarray=$obj_units->amgt_get_single_cat_units($building_id,$unit_category);
								$all_entry=json_decode($unitsarray);
								
								$i=0;
								
								foreach ($all_entry as $key => $value) 
								{
									$unit_value[] = $value;
									
								}
								
								if(!empty($unit_value))
								{
									foreach ($all_entry as $key1 => $value1) 
									{?>
										<option value="<?php echo esc_attr($value1->value); ?>" <?php selected($unitname,$value1->value);?>><?php echo esc_html($value1->value);?> </option>
									<?php }
								}					
							} ?>
							</select>
						</div>			
					</div>
					<!--GENERAL INFORMATION---->
					<div class="form-group">
						<label class="col-sm-2 control-label" for="first_name"><?php esc_html_e('First Name','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="first_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo esc_attr($result->first_name);}elseif(isset($_POST['first_name'])) echo esc_attr($_POST['first_name']);?>" name="first_name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="middle_name"><?php esc_html_e('Middle Name','apartment_mgt');?></label>
						<div class="col-sm-8">
							<input id="middle_name" class="form-control validate[custom[onlyLetterSp]]" maxlength="50" type="text"  value="<?php if($edit){ echo esc_attr($result->middle_name);}elseif(isset($_POST['middle_name'])) echo esc_attr($_POST['middle_name']);?>" name="middle_name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="last_name"><?php esc_html_e('Last Name','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="last_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo esc_attr($result->last_name);}elseif(isset($_POST['last_name'])) echo $_POST['last_name'];?>" name="last_name">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="gender"><?php esc_html_e('Gender','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
						<?php $genderval = "male"; if($edit){ $genderval=$result->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
							<label class="radio-inline front_radio">
							 <input type="radio" value="male" class="tog validate[required] radio_border_radius" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php esc_html_e('Male','apartment_mgt');?>
							</label>
							<label class="radio-inline front_radio">
							  <input type="radio" value="female" class="tog validate[required] radio_border_radius" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php esc_html_e('Female','apartment_mgt');?> 
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="birth_date"><?php esc_html_e('Date of birth','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="birth_date" class="form-control validate[required] birth_date" autocomplete="off" type="text"  name="birth_date" 
							value="<?php if($edit){ echo date("Y-m-d",strtotime($result->birth_date));}elseif(isset($_POST['birth_date'])) echo $_POST['birth_date'];?>">
						</div>
					</div>
					<!--END GENERAL INFORMATION---->
					
					<div class="form-group"><!--MEMBER TYPE---->
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
					<?php
					if($edit)		 
					{
						if(!empty($result->occupied_by))
						{
						?>
							<div class="occupied_div_edit">
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
									value="<?php if($edit){ echo date("Y-m-d",strtotime($result->occupied_date));}elseif(isset($_POST['occupied_date'])) echo $_POST['occupied_date'];?>">
								</div>
							</div>
							</div>
						<?php
						}
						else
						{	
						?>
						<div class="occupied_div">
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php esc_html_e('Occupied By','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<select class="form-control validate[required] allready_occupied" name="occupied_by">
								<option value=""><?php esc_html_e('Select Occupied By','apartment_mgt');?></option>
								
								<option value="Owner"><?php esc_html_e('Owner','apartment_mgt');?></option>
								<option value="tenant"><?php esc_html_e('Tenant','apartment_mgt');?></option>			
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php esc_html_e('Occupied Date','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="occupied_date" class="form-control validate[required]" type="text"  name="occupied_date" 
								value="">
							</div>
						</div>
						</div>
						<?php
						}						
					}
					else
					{	
					?>
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
							<input id="occupied_date" class="form-control validate[required]" type="text" autocomplete="off" name="occupied_date" 
							value="<?php if($edit){ echo date(amgt_date_formate(),strtotime($result->occupied_date));}elseif(isset($_POST['occupied_date'])) echo esc_attr($_POST['occupied_date']);?>">
						</div>
					</div>
					</div>
					<?php
					}
					?>
					<div class="form-group">
						<label class="col-sm-2 control-label " for="committee_member"><?php esc_html_e('Commitee Member','apartment_mgt');?></label>
						<div class="col-sm-1">
							<div class="col-sm-1">
							<input id="committee_member" class="form-control text-input" type="checkbox" <?php if($edit==1 && $result->committee_member=='yes'){ echo "checked";}?> name="committee_member" 
							value="yes"></div>	
						</div>	
						<?php if($edit==1 && $result->committee_member=='yes'){ ?>
						<div class="col-sm-9" id="designaion_area">
							<div class="col-sm-6">
							<select class="form-control validate[required] designation_cat" name="designation_id">
							<option value=""><?php esc_html_e('Select Designation','apartment_mgt');?></option>
							<?php 
							if($edit)
								$category =$result->designation_id;
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
						</div>
						<?php }
							else
							{?>
								<div class="col-sm-9" id="designaion_area">
								</div>
							<?php }	?>
					
					</div>	
                     <!--ADDRESS INFORMATION---->					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="address"><?php esc_html_e('Correspondence Address','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="address" class="form-control validate[required]" type="text" maxlength="150" name="address" 
							value="<?php if($edit){ echo esc_attr($result->address);}elseif(isset($_POST['address'])) echo esc_attr($_POST['address']);?>">
						</div>
					</div>
					<div class="form-group">
									<label class="col-sm-2 control-label" for="city_name"><?php esc_html_e('City','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input id="city_name" class="form-control validate[required,custom[onlyLetterSp]]" maxlength="50" type="text"  name="city_name" 
							value="<?php if($edit){ echo esc_attr($result->city_name);}elseif(isset($_POST['city_name'])) echo esc_attr($_POST['city_name']);?>">
						</div>
						<label class="col-sm-2 control-label"><?php esc_html_e('State','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input class="form-control validate[required,custom[onlyLetterSp]]" maxlength="50" type="text"  name="state_name" 
							value="<?php if($edit){ echo esc_attr($result->state_name);}elseif(isset($_POST['state_name'])) echo esc_attr($_POST['state_name']);?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php esc_html_e('Country','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input class="form-control validate[required,custom[onlyLetterSp]]" maxlength="50" type="text"  name="country_name" 
							value="<?php if($edit){ echo esc_attr($result->country_name);}elseif(isset($_POST['country_name'])) echo esc_attr($_POST['country_name']);?>">
						</div>
						<label class="col-sm-2 control-label"><?php esc_html_e('Zip Code','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input class="form-control validate[required,custom[onlyLetterNumber]]" maxlength="10" type="text"  name="zipcode" 
							value="<?php if($edit){ echo esc_attr($result->zipcode);}elseif(isset($_POST['zipcode'])) echo esc_attr($_POST['zipcode']);?>">
						</div>
					</div>
					<!--END ADDRESS INFORMATION---->
					<!--CONTACT INFORMATION---->
					<div class="form-group">
						<label class="col-sm-2 control-label " for="email"><?php esc_html_e('Email','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="email" class="form-control validate[required,custom[email]] text-input" maxlength="50" type="text"  name="email" 
							value="<?php if($edit){ echo esc_attr($result->user_email);}elseif(isset($_POST['email'])) echo esc_attr($_POST['email']);?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label " for="mobile"><?php esc_html_e('Mobile Number','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-1">
						
						<input type="text" readonly value="+<?php echo amgt_get_countery_phonecode(get_option( 'amgt_contry' ));?>"  class="form-control" name="phonecode">
						</div>
						<div class="col-sm-7">
							<input id="mobile" class="form-control validate[required,custom[phone]] text-input" type="number" min="0" onKeyPress="if(this.value.length==15) return false;"  name="mobile" value="<?php if($edit){ echo esc_attr($result->mobile);}elseif(isset($_POST['mobile'])) echo esc_attr($_POST['mobile']);?>">
						</div>
					</div>
					<!--END CONTACT INFORMATION---->
					<!--LOGIN INFORMATION---->
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
							<input id="password" class="form-control <?php if(!$edit) echo 'validate[required]';?>" type="password"  name="password" minlength="8" maxlength="12" value="">
						</div>
					</div>
                    <!--END LOGIN INFORMATION---->					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Member Image','apartment_mgt');?></label>
						<div class="col-sm-2">
							<input type="text" id="amgt_user_avatar_url" class="form-control" name="amgt_user_avatar"  
							value="<?php if($edit)echo esc_url( $result->amgt_user_avatar );elseif(isset($_POST['amgt_user_avatar'])) echo $_POST['amgt_user_avatar']; ?>" />
							<input type="hidden" class="form-control" name="hidden_upload_user_avatar_image"  onchange="member_imgefileCheck(this);" 
							value="<?php if($edit)echo esc_url( $result->amgt_user_avatar );elseif(isset($_POST['hidden_upload_user_avatar_image'])) echo $_POST['hidden_upload_user_avatar_image']; ?>" />
						</div>	
							<div class="col-sm-3">
								 <input id="upload_user_avatar" name="upload_user_avatar_image"  onchange="member_imgefileCheck(this);" type="file" />
							</div>
						<div class="clearfix"></div>
						
						<div class="col-sm-offset-2 col-sm-8">
								<div id="upload_user_avatar_preview" >
									 <?php if($edit) 
										{
										if($result->amgt_user_avatar == "")
										{?>
										<img alt="" src="<?php echo get_option( 'amgt_system_logo' ); ?>">
										<?php }
										else {
											?>
										<img class="max_width_100" src="<?php if($edit)echo esc_url( $result->amgt_user_avatar ); ?>" />
										<?php 
										}
										}
										else {
											?>
											<img alt="" src="<?php echo get_option( 'amgt_system_logo' ); ?>">
											<?php 
										} ?>
								</div>
						</div>
					</div>
					<?php 
					if($edit) 
					{
					?>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Member ID Proof-1','apartment_mgt');?></label>
						<div class="col-sm-4">
							<input type="hidden" name="hidden_id_proof_1" value="<?php if($edit){ echo $result->id_proof_1;}elseif(isset($_POST['id_proof_1'])) echo $_POST['id_proof_1'];?>">
							<input id="upload_file" onchange="fileCheck(this);" name="id_proof_1"  value="" type="file"/>
						</div>
						<div class="col-sm-2">
							<?php if(isset($result->id_proof_1) && $result->id_proof_1 != ""){?>
							<a href="<?php echo content_url().'/uploads/apartment_assets/'.$result->id_proof_1;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php esc_html_e('Member ID Proof-1','apartment_mgt');?></a>
							<?php } ?>			
						</div>
					</div>	
					<?php	
					}
					else
					{		
					?>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Member ID Proof-1','apartment_mgt');?></label>
						<div class="col-sm-4">
							<input id="upload_file" onchange="fileCheck(this);" name="id_proof_1"  type="file"/>
						</div>
					</div>
					<?php
					}
					if($edit) 
					{
					?>	
					<div class="form-group">
						<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Lease Agreement','apartment_mgt');?></label>
						<div class="col-sm-4">
							<input type="hidden" name="hidden_id_proof_2" value="<?php if($edit){ echo $result->id_proof_2;}elseif(isset($_POST['id_proof_2'])) echo $_POST['id_proof_2'];?>">
							<input id="upload_file" onchange="fileCheck(this);" name="id_proof_2"  value="" type="file"/>
						</div>
						<div class="col-sm-2">				
							<?php if(isset($result->id_proof_2) && $result->id_proof_2 != ""){?>
							<a href="<?php echo content_url().'/uploads/apartment_assets/'.$result->id_proof_2;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php esc_html_e('Lease Agreement','apartment_mgt');?></a>
							<?php } ?>
						</div>
					</div>
					<?php
					}
					else
					{
					?>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Lease Agreement','apartment_mgt');?></label>
						<div class="col-sm-4">
							<input id="upload_file" onchange="fileCheck(this);" name="id_proof_2"  type="file"/>
						</div>
					</div>
					<?php
					}	
					?>		
					<div class="col-sm-offset-2 col-sm-8">
						<input type="submit" value="<?php if($edit){ esc_html_e('Save','apartment_mgt'); }else{ esc_html_e('Add Member','apartment_mgt');}?>" name="save_member" class="btn btn-success"/>
					</div>		
				</form>		
			</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close','apartment_mgt');?></button>
		</div>
    </div><!--END MODAL-CONTENT---->
 </div>
</div><!-- END modal DIV -->
  
  <!----------Add Building pop up form---------------------->
<div class="modal fade overflow_scroll" id="myModal_add_building" role="dialog">
    <div class="modal-dialog modal-lg"><!--MODAL-DIALOG---->
        <div class="modal-content"><!--MODAL CONTENT---->
			<div class="modal-header"><!--MODAL-HEADER---->
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h3 class="modal-title"><?php esc_html_e('Add Building','apartment_mgt');?></h3>
			</div>
			<div class="modal-body"><!--MODAL-BODY---->
				<script type="text/javascript">
				$(document).ready(function() {
					"use strict";
					$('#unit_form').validationEngine();
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

				});

				</script> 
				 <!--UNIT FORM--->
				<form name="unit_form"  method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" class="form-horizontal" id="unit_form">
					<input id="" type="hidden" name="action" value="amgt_add_unit_popup">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<select class="form-control validate[required] building_category" name="building_id" id="">
								<option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
								<?php 
								$activity_category=amgt_get_all_category('building_category');
								if(!empty($activity_category))
								{
									foreach ($activity_category as $retrive_data)
									{
										echo '<option value="'.$retrive_data->ID.'">'.$retrive_data->post_title.'</option>';
									}
								} ?>
								</select>
						</div>
						<div class="col-sm-2"><button id="addremove" model="building_category"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<select class="form-control validate[required] unit_category"  name="unit_cat_id" id="">
								<option value=""><?php esc_html_e('Select Unit Category','apartment_mgt');?></option>
								<?php
								$activity_category=amgt_get_all_category('unit_category');
								if(!empty($activity_category))
								{
									foreach ($activity_category as $retrive_data)
									{
										echo '<option value="'.$retrive_data->ID.'">'.$retrive_data->post_title.'</option>';
									}
								} ?>
							</select>
						</div>
						<div class="col-sm-2"><button id="addremove" model="unit_category"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
					</div>
					<?php 
						   if(isset($_POST['unit_names'])){
								$all_data=$obj_units->amgt_get_entry_records($_POST);
								$all_entry=json_decode($all_data);
							}
						   ?>
							<div id="unit_name_entry">
									<div class="form-group">
									<label class="col-sm-2 control-label" for="unit_entry"><?php esc_html_e('Unit Name','apartment_mgt');?><span class="require-field">*</span></label>
								
									<div class="col-sm-2">
										<input class="form-control validate[required] text-input onlyletter_number_space_validation unit_name" type="text" value="" name="unit_names[]" placeholder="<?php esc_html_e('Unit Name','apartment_mgt');?>">
									</div>	
									<?php $unit_measerment_type=get_option( 'amgt_unit_measerment_type' );?>						
									<label class="col-sm-3 control-label" for="unit_entry"><?php esc_html_e('Unit Size','apartment_mgt');?>(<?php if($unit_measerment_type =='square_meter'){
									echo esc_html_e('square meter','apartment_mgt');
									}
									else{
										echo $unit_measerment_type;
									}
									
								?>)<span class="require-field">*</span></label>
									<div class="col-sm-2">
										<input  class="form-control validate[required] text-input" type="number" onKeyPress="if(this.value.length==6) return false;"  min="0" value="" name="unit_size[]" placeholder="<?php esc_html_e('Unit Size','apartment_mgt');?>">
									</div>
									<div class="col-sm-2">
									<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
									<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
									</button>
									</div>
									</div>	
							</div>		   
							<div class="form-group">
								<label class="col-sm-2 control-label" for="unit_entry"></label>
								<div class="col-sm-3">
									<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php esc_html_e('Add More Unit','apartment_mgt'); ?>
									</button>
								</div>
							</div>
					<hr>			
					<div class="col-sm-offset-2 col-sm-8">
					<?php $unit_type=get_option( 'amgt_apartment_type' ); ?>
						<input type="submit" value="<?php  esc_html_e('Add '.$unit_type.' Unit','apartment_mgt'); ?>" name="save_residential_unit" class="btn btn-success"/>
					</div>
				</form>
            </div>
           <div class="modal-footer"><!--MODAL-FOOTER---->
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close','apartment_mgt'); ?></button>
		</div>
      </div>
    </div>
</div><!-- END modal DIV -->
<script>
	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	$(document).ready(function() { 
   		blank_expense_entry = $('#unit_name_entry').html();
   		//alert("hello" + blank_invoice_entry);
   	}); 

   	function add_entry()
   	{
   		$("#unit_name_entry").append(blank_expense_entry);
   		//alert("hellooo");
   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n)
	{
		if(confirm("Are you sure want to delete this record?"))
		{
			n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		}	
   	}
</script>