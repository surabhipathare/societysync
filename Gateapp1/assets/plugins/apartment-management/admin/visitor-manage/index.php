<?php
 error_reporting(0);
$active_tab = isset($_GET['tab'])?$_GET['tab']:'manage-gates';
$obj_gate=new Amgt_gatekeeper;
$obj_units=new Amgt_ResidentialUnit;
$gatedata=$obj_gate->amgt_get_all_entry_gates();
?>

<!-- POP UP CODE -->
<div class="popup-bg z_index_100000">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list"> </div>
			<div class="checkout_content"></div>    
		</div>
    </div>    
</div>
<!-- END POP-UP CODE -->

<div class="page-inner min_height_1088"><!--  PAGE INNER DIV -->
	<div class="page-title"><!--PAGE TITLE-->
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>
<?php 
if(isset($_POST['save_gate']))	//SAVE_GATE
{	
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
	{
		$result=$obj_gate->amgt_add_gate($_POST);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=amgt-visiter-manage&tab=manage-gates&message=9');
		}
	}
	else
	{
		$result=$obj_gate->amgt_add_gate($_POST);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=amgt-visiter-manage&tab=manage-gates&message=8');
		}
	}
}
if(isset($_POST['save_visitor_checkin']))//SAVE_VISITOR_CHECKIN		
{
	if(!empty($_POST['gate']))
	{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_gate->amgt_add_visitor_entry($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-visiter-manage&tab=visitor-checkinlist&message=2');
			}
		}
		else
		{
			$result=$obj_gate->amgt_add_visitor_entry($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-visiter-manage&tab=visitor-checkinlist&message=1');
			}
		}
	}
	else
	{ ?>

    <div id="message" class="updated below-h2 notice is-dismissible">
		<p><?php _e("Please Select Gate.",'apartment_mgt');?></p>
	</div>
		
	<?php }
}
if(isset($_POST['save_staff_checkin']))		
{	

    if(!empty($_POST['gate']))
	{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')//EDIT VISITOR CHECKIN
		{
			$result=$obj_gate->amgt_add_visitor_entry($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-visiter-manage&tab=staff-checkinlist&message=7');
			}
		}
		else
		{
			$result=$obj_gate->amgt_add_visitor_entry($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-visiter-manage&tab=staff-checkinlist&message=6');
			}
		}
	}
    else
	{ ?>

    <div id="message" class="updated below-h2 notice is-dismissible">
		<p><?php _e("Please Select Gate.",'apartment_mgt');?></p>
	</div>
		
	<?php }
}
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')//DELETE VISITOR
{
	if(isset($_REQUEST['visitor_checkin_id']))
	{
		$result=$obj_gate->amgt_delete_visitor_checkin_entry($_REQUEST['visitor_checkin_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=amgt-visiter-manage&tab=visitor-checkinlist&message=3');
		}
	}
	if(isset($_REQUEST['staff_checkin_id']))
	{
		$result=$obj_gate->amgt_delete_visitor_checkin_entry($_REQUEST['staff_checkin_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=amgt-visiter-manage&tab=staff-checkinlist&message=3');
		}
	}
	
	if(isset($_REQUEST['visitor_request_id']))
	{
		$result=$obj_gate->amgt_delete_visitor_request($_REQUEST['visitor_request_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=amgt-visiter-manage&tab=visiter_request_list&message=3');
		}
	}
}	
if(isset($_POST['save_checkout']))//SAVE CHECKOUT
{
	
	if(isset($_POST['checkout_type']) && $_POST['checkout_type']=='visitor')
	{
		$result=$obj_gate->amgt_visitor_check_out_entry($_POST);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=amgt-visiter-manage&tab=visitor-checkinlist&message=4');
		}
	}
	if(isset($_POST['checkout_type']) && $_POST['checkout_type']=='staff')
	{
		$result=$obj_gate->amgt_visitor_check_out_entry($_POST);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=amgt-visiter-manage&tab=staff-checkinlist&message=4');
		}
	}
}	


if(isset($_POST['save_visitor_request']))		
{	

	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')//EDIT VISITOR CHECKIN
	{
		$result=$obj_gate->amgt_add_visitor_request($_POST);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=amgt-visiter-manage&tab=visiter_request_list&message=2');
		}
	}
	else
	{
		$result=$obj_gate->amgt_add_visitor_request($_POST);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=amgt-visiter-manage&tab=visiter_request_list&message=1');
		}
	}
}
    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'aproved_visiter_request')
	{
		global $wpdb;
		$table_name = $wpdb->prefix. 'amgt_checkin_entry';
		$visitor_request_id = $_REQUEST['visitor_request_id'];
		$visitor_request_data = $obj_gate->amgt_get_single_checkin($visitor_request_id);
		$whereid['id']=$visitor_request_id;
		$request_data['status']='1';
		$result=$wpdb->update( $table_name, $request_data,$whereid );
		
		$user_query = new WP_User_Query(
		array(
			'meta_key'	  =>	'unit_name',
			'meta_value'	=>	$visitor_request_data->unit_name
		)
	    );
	    $allmembers = $user_query->get_results();
		
		$display_name=$allmembers[0]->display_name;
		$to = $allmembers[0]->user_email;
		//---------------- SEND  SMS ------------------//
		include_once(ABSPATH.'wp-admin/includes/plugin.php');
		if(is_plugin_active('sms-pack/sms-pack.php'))
		{
			if(!empty(get_user_meta($allmembers[0]->ID, 'phonecode',true))){ $phone_code=get_user_meta($allmembers[0]->ID, 'phonecode',true); }else{ $phone_code='+'.amgt_get_countery_phonecode(get_option( 'amgt_contry' )); }
							
			$user_number[] = $phone_code.get_user_meta($allmembers[0]->ID, 'mobile',true);
			$apartmentname=get_option('amgt_system_name');
			$message_content ="Your visitor request has been approved by admin From $apartmentname .";
			
			$current_sms_service 	= get_option( 'smgt_sms_service');
			$args = array();
			$args['mobile']=$user_number;
			$args['message_from']="visitor request";
			$args['message']=$message_content;					
			if($current_sms_service=='telerivet' || $current_sms_service ="MSG91" || $current_sms_service=='bulksmsgateway.in' || $current_sms_service=='textlocal.in' || $current_sms_service=='bulksmsnigeria' || $current_sms_service=='africastalking' || $current_sms_service == 'clickatell')
			{				
				$send = send_sms($args);							
			}
		}
		
		$apartmentname=get_option('amgt_system_name');
	    $subject =get_option('wp_amgt_visitor_request_aproved_subject');
		$subject_search=array('{{apartment_name}}');
		$subject_replace=array($apartmentname);
		$subject_replacement=str_replace($subject_search,$subject_replace,$subject);
		
		$message_content=get_option('wp_amgt_visitor_request_aproved_content');
		$search=array('{{member_name}}','{{apartment_name}}');
		$replace = array($display_name,$apartmentname);
		$message_content_replacement = str_replace($search, $replace, $message_content);
		amgtSendEmailNotification($to,$subject_replacement,$message_content_replacement);
		wp_redirect ( admin_url().'admin.php?page=amgt-visiter-manage&tab=visitor-checkinlist&message=5');
	}
	
if(isset($_REQUEST['message']))//MESSAGE
{
	$message =$_REQUEST['message'];
	if($message == 1){ ?>
		<div id="message" class="updated below-h2 notice is-dismissible">
		<p>	<?php 	esc_html_e('Visitor Request inserted successfully','apartment_mgt');	?>
		</p></div>
	<?php	}
	elseif($message == 2){?>
	<div id="message" class="updated below-h2 notice is-dismissible">
		<p><?php _e("Visitor Request updated successfully.",'apartment_mgt');?></p>
	</div>
	<?php 
	}
	elseif($message == 3) { ?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php esc_html_e('Request deleted successfully','apartment_mgt');	?>
		</div></p>
	<?php }
	elseif($message == 4) 
	{
	?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php esc_html_e('Checked Out successfully','apartment_mgt');?></div></p>
	<?php				
	} 
	elseif($message == 5) 
	{
	?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php esc_html_e('Visitor Request Approved Successfully','apartment_mgt');?></div></p>
	<?php				
	}
	elseif($message == 6) 
	{
	?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 	esc_html_e('Staff Request inserted successfully','apartment_mgt');	?></div></p>
	<?php				
	}
	elseif($message == 7) 
	{
	?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 	esc_html_e('Staff Request Updated successfully','apartment_mgt');	?></div></p>
	<?php				
	}
	if($message == 8){ ?>
		<div id="message" class="updated below-h2 notice is-dismissible">
		<p>	<?php 	esc_html_e('Gate inserted successfully','apartment_mgt');	?>
		</p></div>
	<?php	}
	elseif($message == 9){?>
	<div id="message" class="updated below-h2 notice is-dismissible">
		<p><?php _e("Gate updated successfully.",'apartment_mgt');?></p>
	</div>
	<?php 
	}
}
	?>

	<div id="main-wrapper"><!-----MAIN-WRAPPER----->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white"><!--PANEL-WHITE-->
					<div class="panel-body"><!--PANEL BODY-->
						<h2 class="nav-tab-wrapper"><!--NAV TAB WRAPPER----->
							<a href="?page=amgt-visiter-manage&ab=manage-gates" class="nav-tab <?php echo $active_tab == 'manage-gates' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Manage Gates', 'apartment_mgt'); ?></a>
							
							<a href="?page=amgt-visiter-manage&tab=visitor-checkinlist" class="nav-tab <?php echo $active_tab == 'visitor-checkinlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Visitor Request List', 'apartment_mgt'); ?></a>  
							
							 <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $active_tab == 'visitor-checkin')
							{ ?>
									<a href="?page=amgt-visiter-manage&tab=visitor-checkin&action=edit&visitor_checkin_id=<?php echo $_REQUEST['visitor_checkin_id']?>" class="nav-tab <?php echo $active_tab == 'visitor-checkin' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Edit Visitor Request', 'apartment_mgt'); ?></a>
							<?php } 
							else
							{ ?>
							<a href="?page=amgt-visiter-manage&tab=visitor-checkin" class="nav-tab <?php echo $active_tab == 'visitor-checkin' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Visitor Request', 'apartment_mgt'); ?></a>
							<?php } ?>
							
							<a href="?page=amgt-visiter-manage&tab=staff-checkinlist" class="nav-tab <?php echo $active_tab == 'staff-checkinlist' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Staff Checkin List', 'apartment_mgt'); ?></a>
							
							 <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $active_tab == 'staff-checkin')
							{ ?>
									<a href="?page=amgt-visiter-manage&tab=staff-checkin&action=edit&staff_checkin_id=<?php  $_REQUEST['staff_checkin_id']?>" class="nav-tab <?php echo $active_tab == 'staff-checkin' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Edit Staff Checkin', 'apartment_mgt'); ?></a>
							<?php } 
							else
							{?>
								<a href="?page=amgt-visiter-manage&tab=staff-checkin" class="nav-tab <?php echo $active_tab == 'staff-checkin' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Staff Checkin', 'apartment_mgt'); ?></a>
							<?php } ?>
							
							<!-- 
							<a href="?page=amgt-visiter-manage&tab=visiter_request_list" class="nav-tab <?php echo $active_tab == 'visiter_request_list' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Visitor Request List', 'apartment_mgt'); ?></a>
							
							 <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $active_tab == 'visiter_request')
							{ ?>
									<a href="?page=amgt-visiter-manage&tab=visiter_request&action=edit&request_id=<?php  $_REQUEST['request_id']?>" class="nav-tab <?php echo $active_tab == 'visiter_request' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Edit Visitor Request', 'apartment_mgt'); ?></a>
							<?php } 
							else
							{?>
								<a href="?page=amgt-visiter-manage&tab=visiter_request" class="nav-tab <?php echo $active_tab == 'visiter_request' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Visitor Request', 'apartment_mgt'); ?></a>
							<?php } ?> -->
							
							
						</h2><!--NAV TAB WRAPPER----->
						<?php 
                        //MANAGE-GATES TAB					
						if($active_tab == 'manage-gates')
						{ ?>
							<script type="text/javascript">
							$(document).ready(function() 
							{
								"use strict";
								$('#gate_form').validationEngine();								
							} );
							</script>
							<div class="panel-body"><!--PANEL BODY-->
							    <!--GATE FORM-->
								<form name="gate_form" action="" method="post" class="form-horizontal" id="gate_form">
										<div class="form-group">
											<div class="col-md-0 col-sm-0 col-xs-0">
											</div>
											<div class="col-md-3 col-sm-3 col-xs-4">
												<label class="control-label" for="gate_title"><?php _e("Gate Name","apartment_mgt");?></label>
											</div>
											<div class="col-md-3 col-sm-3 col-xs-4">
												<div class="col-md-6 col-sm-6 col-xs-6">
													<label class="control-label" for="entry"><?php _e("For Entry","apartment_mgt");?></label>
												</div>
												<div class="col-md-6 col-sm-6 col-xs-6">
													<label class="control-label" for="exit"><?php _e("For Exit","apartment_mgt");?></label>
												</div>
											</div>
										</div>
										<div id="gate_name_entry"><!--GATE_NAME_ENTRY-->
											<?php 
											if(!empty($gatedata))
											{	
												$i=0;?>
												<input id="counter" type="hidden" name="counter" value="<?php echo count($gatedata)-1;?>">
												<input id="action" type="hidden" name="action" value="edit">
												<?php 
												foreach($gatedata as $gate)
												{
												?>
												<div id="<?php echo $i;?>">
													<div class="form-group">
													<label class="col-md-0 col-sm-0 col-xs-0 control-label" for="gate_entry"></label>
													<div class="col-md-3 col-sm-3 col-xs-4">
														<input id="gate_name" maxlength="30" class="form-control validate[required] text-input gate_name onlyletter_number_space_validation" type="text" value="<?php echo esc_attr($gate->gate_name);?>" name="gate_name_<?php echo $i;?>"  placeholder="<?php esc_html_e('Gate Name','apartment_mgt');?>" >
													<input type="hidden" name="gate_id_<?php echo $i;?>" value="<?php echo $gate->id;?>">
													</div>
													<div class="col-md-3 col-sm-3 col-xs-4">
														<div class="col-md-6 col-sm-6 col-xs-6">
														<input id="forentry" class="form-control forentry" type="checkbox" <?php checked('yes',$gate->for_entry);?> value="yes" name="for_entry_<?php echo $i;?>" >
														</div>
														<div class="col-md-6 col-sm-6 col-xs-6">
														<input id="forexit" class="form-control forexit" type="checkbox" <?php checked('yes',$gate->for_exit);?> value="yes" name="for_exit_<?php echo $i;?>">
														</div>
													</div>
													
													
													<div class="col-sm-4 col-md-4 col-xs-3">
													<button id="del_curr" type="button" class="btn btn-default" test_id="<?php echo $i;?>" gate_id="<?php echo $gate->id;?>" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
													<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
													</button>
													</div>
													</div>	
												</div>	
													<?php 
														$i+=1;
												}
											}
											else
											{	
											$i=0;?>
											<input id="counter" type="hidden" name="counter" value="<?php echo $i;?>">	
											<div id="<?php echo $i;?>">
												<div class="form-group">
													<label class="col-sm-0 control-label" for="gate_entry"></label>
													<div class="col-sm-3">
														<input id="gate_name" maxlength="30" class="form-control validate[required] text-input gate_name onlyletter_number_space_validation" type="text" value="" name="gate_name_<?php echo $i;?>"  placeholder="<?php esc_html_e('Gate Name','apartment_mgt');?>">
													</div>
													<div class="col-sm-3">
														<div class="col-md-6">
														<input id="forentry" class="form-control forentry" type="checkbox" value="yes" name="for_entry_<?php echo $i;?>" >
														</div>
														<div class="col-md-6">
														<input id="forexit" class="form-control forexit" type="checkbox" value="yes" name="for_exit_<?php echo $i;?>">
														</div>
													</div>
													
													<div class="col-sm-1">
													<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
													<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
													</button>
													</div>
												</div>	
											</div>	
										<?php 
										    } ?>
										</div><!--END GATE_NAME_ENTRY-->
										<div class="form-group"><!---INCOME_ENTRY-->
											<label class="col-sm-0 control-label" for="income_entry"></label>
											<div class="col-sm-3">
												<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php esc_html_e('Add New Gate','apartment_mgt'); ?>
												</button>
											</div>
										</div>
								        <hr>
										<div class="col-sm-offset-0 col-sm-8 service_padding">
											<input type="submit" value="<?php  esc_html_e('Submit','apartment_mgt');?>" name="save_gate" class="btn btn-success"/>
										</div>
								</form>
							</div>
						<?php 
						}
					
						if($active_tab == 'visitor-checkinlist')
						 {
							require_once AMS_PLUGIN_DIR.'/admin/visitor-manage/visitor-checkin-list.php';
						 }
					
						 if($active_tab == 'visitor-checkin')
						 {
							require_once AMS_PLUGIN_DIR.'/admin/visitor-manage/visitor-checkin.php';
						 }
					
						 if($active_tab == 'staff-checkinlist')
						 {
							require_once AMS_PLUGIN_DIR.'/admin/visitor-manage/staff-checkin-list.php';
						 }
						 
						  if($active_tab == 'staff-checkin')
						 {
							require_once AMS_PLUGIN_DIR.'/admin/visitor-manage/staff-checkin.php';
						 }
						
						if($active_tab == 'visiter_request')
						{
							require_once AMS_PLUGIN_DIR.'/admin/visitor-manage/visiter_request.php';
						}
						 
						if($active_tab == 'visiter_request_list')
						{
							require_once AMS_PLUGIN_DIR.'/admin/visitor-manage/visiter_request_list.php';
						}
					 ?>
					</div>
	            </div>
	        </div>
        </div>
    </div><!-----END MAIN-WRAPPER----->
</div><!-- PAGE INNER DIV END   -->
<script>
   	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	$(document).ready(function()
	{ 
   		blank_income_entry = $('#gate_name_entry').html();		
   	}); 
	var counter=0;
   	function add_entry()
   	{
		counter=$("#counter").val();
		counter=parseInt(counter) + 1;	
		var data='';
		data +='<div id="'+counter+'">';
				data +='<div class="form-group">';
						data +='<label class="col-sm-0 control-label" for="gate_entry"></label>';
						data +='<div class="col-sm-3">';
						data +='<input id="gate_name" class="form-control validate[required] text-input gate_name onlyletter_number_space_validation"  maxlength="30"  type="text" value="" name="gate_name_'+counter+'" placeholder="<?php _e("Gate Name","apartment_mgt");?>"></div>';
				
						data +='<div class="col-sm-3">';
							data +='<div class="col-md-6">';
							data +='<input id="forentry" class="form-control forentry" type="checkbox" value="yes" name="for_entry_'+counter+'" >';
							data +='</div>';
							data +='<div class="col-md-6">';
							data +='<input id="forexit" class="form-control forexit" type="checkbox" value="yes" name="for_exit_'+counter+'">';
							data +='</div>';
						data +='</div>';
						data +='<div class="col-sm-1">';
						data +='<button id="del_curr" type="button" class="btn btn-default" test_id='+counter+'><i class="entypo-trash"><?php echo __("Delete","apartment_mgt")?>';
						data += '</i></button>';
						data += '</div>';
						data += '</div>';	
			data +='</div>';
   		$("#gate_name_entry").append(data);
		$("#counter").val(counter);
   	}
	$('body').on('click','#del_curr',function() {
		var id=$(this).attr('test_id');
   		$('#'+id).remove();
	});
</script> 
