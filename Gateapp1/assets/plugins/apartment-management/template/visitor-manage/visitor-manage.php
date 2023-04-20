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
$obj_member=new Amgt_Member;
$obj_gate=new Amgt_gatekeeper;
$obj_units=new Amgt_ResidentialUnit;
$gatedata=$obj_gate->Amgt_get_all_gates();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'visitor-checkinlist';


if(isset($_POST['save_visitor_request']))		
{	

	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')//EDIT VISITOR CHECKIN
	{
		$result=$obj_gate->amgt_add_visitor_request($_POST);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=visitor-manage&tab=visiter_request_list&message=2');
		}
	}
	else
	{
		$result=$obj_gate->amgt_add_visitor_request($_POST);
		if($result)
		{
			
			wp_redirect ( home_url().'?apartment-dashboard=user&page=visitor-manage&tab=visiter_request_list&message=1');
		}
	}
}

if(isset($_POST['save_visitor_checkin']))		
{
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_visitor_checkin_nonce' ) )
	{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_gate->amgt_add_visitor_entry($_POST);
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=visitor-manage&tab=visitor-checkinlist&message=2');
			}
		}
		else
		{
			$result=$obj_gate->amgt_add_visitor_entry($_POST);
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=visitor-manage&tab=visitor-checkinlist&message=1');
			}
		}
	
	}
}
if(isset($_POST['save_staff_checkin']))	//SAVE_STAFF_CHECKIN	
{
	 $nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_staff_checkin_nonce' ) )
	{
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
	{
		$result=$obj_gate->amgt_add_visitor_entry($_POST);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=visitor-manage&tab=staff-checkinlist&message=7');
		}
	}
	else
	{
		$result=$obj_gate->amgt_add_visitor_entry($_POST);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=visitor-manage&tab=staff-checkinlist&message=6');
		}
	}
}
}
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='chekout')
{
	if(isset($_REQUEST['visitor_checkin_id']))
	{
		$result=$obj_gate->amgt_visitor_check_out_entry($_REQUEST['visitor_checkin_id']);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=visitor-manage&tab=visitor-checkinlist&message=4');
		}
	}
	if(isset($_REQUEST['staff_checkin_id']))
	{
		$result=$obj_gate->amgt_visitor_check_out_entry($_REQUEST['staff_checkin_id']);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=visitor-manage&tab=staff-checkinlist&message=4');
		}
	}
}	
		
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')//DELETE VISITOR MANAGE
{
	if(isset($_REQUEST['visitor_checkin_id']))
	{
		$result=$obj_gate->amgt_delete_visitor_checkin_entry($_REQUEST['visitor_checkin_id']);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=visitor-manage&tab=visitor-checkinlist&message=3');
		}
	}
	if(isset($_REQUEST['staff_checkin_id']))
	{
		$result=$obj_gate->amgt_delete_visitor_checkin_entry($_REQUEST['staff_checkin_id']);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=visitor-manage&tab=staff-checkinlist&message=3');
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
			wp_redirect ( home_url().'?apartment-dashboard=user&page=visitor-manage&tab=visitor-checkinlist&message=4');
		}
	}
	if(isset($_POST['checkout_type']) && $_POST['checkout_type']=='staff')
	{
		$result=$obj_gate->amgt_visitor_check_out_entry($_POST);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=visitor-manage&tab=staff-checkinlist&message=4');
		}
	}
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
			<?php esc_html_e('Visitor request Approved successfully','apartment_mgt');?></div></p>
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
}
  ?>
<!-- POP up code -->
<div class="popup-bg">	
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list"></div>
		    <div class="checkout_content"></div>    
		</div>
    </div> 
</div>

<div class="panel-body panel-white"><!--PANEL BODY DIV -->
	<ul class="nav nav-tabs panel_tabs" role="tablist"><!--PANEL_TABS -->
			<li class="<?php if($active_tab=='visitor-checkinlist'){?>active<?php }?>">
				<a href="?apartment-dashboard=user&page=visitor-manage&tab=visitor-checkinlist" class="tab <?php echo $active_tab == 'visitor-checkinlist' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php esc_html_e('Visitor Request List', 'apartment_mgt'); ?></a>
			    </a>
		    </li>
		   <?php if(($obj_apartment->role=='gatekeeper' || $obj_apartment->role=='staff_member' || $obj_apartment->role == 'member') && ($user_access['add']=='1')){?>
		    <li class="<?php if($active_tab=='visitor-checkin'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['visitor_checkin_id']))
				{ ?>
				<a href="?apartment-dashboard=user&page=visitor-manage&tab=visitor-checkin&action=edit&visitor_checkin_id=<?php echo $_REQUEST['visitor_checkin_id'];?>" class="nav-tab margin_top_10_res <?php echo $active_tab == 'visitor-checkin' ? 'nav-tab-active' : ''; ?>">
				 <i class="fa fa"></i> <?php esc_html_e('Edit Visitor Request', 'apartment_mgt'); ?></a>
				 <?php }
				else
				{ ?>
					<a href="?apartment-dashboard=user&page=visitor-manage&tab=visitor-checkin" class="tab margin_top_10_res <?php echo $active_tab == 'visitor-checkin' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_html_e('Visitor Request', 'apartment_mgt'); ?></a>
		     <?php } ?>
		    </li>
		   <?php } ?>
		   
		   <?php if($obj_apartment->role != 'member')
		   {?>
	   
			<li class="<?php if($active_tab=='staff-checkinlist'){?>active<?php }?>">
				<a href="?apartment-dashboard=user&page=visitor-manage&tab=staff-checkinlist" class="tab margin_top_10_res <?php echo $active_tab == 'staff-checkinlist' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php esc_html_e('Staff Checkin List', 'apartment_mgt'); ?></a>
			  </a>
		    </li>
	   
		   <?php
		   } ?>
		    
		   <?php if(($obj_apartment->role=='gatekeeper' || $obj_apartment->role=='staff_member') &&  $user_access['add']=='1')
			{?>
		    <li class="<?php if($active_tab=='staff-checkin'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['staff_checkin_id']))
				{ ?>
				<a href="?apartment-dashboard=user&page=visitor-manage&tab=staff-checkin&action=edit&staff_checkin_id=<?php echo $_REQUEST['staff_checkin_id'];?>" class="nav-tab margin_top_10_res <?php echo $active_tab == 'staff-checkin' ? 'nav-tab-active' : ''; ?>">
				 <i class="fa fa"></i> <?php esc_html_e('Edit Staff Checkin', 'apartment_mgt'); ?></a>
				 <?php }
				else
				{ ?>
					<a href="?apartment-dashboard=user&page=visitor-manage&tab=staff-checkin" class="tab margin_top_10_res <?php echo $active_tab == 'staff-checkin' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_html_e('Staff Checkin', 'apartment_mgt'); ?></a>
		   <?php } ?>
		  
		    </li>
		   <?php 
			} ?>
		  
	</ul><!--END PANEL_TABS -->
	<div class="tab-content">
		<?php if($active_tab == 'visitor-checkinlist')//VISITOR-CHECKINLIST TAB
		{ ?>
			<script type="text/javascript">
			jQuery(document).ready(function() {
				"use strict";
				
				jQuery('#visitor_checkin_list').DataTable({
					"responsive":true,
					"order": [[ 2, "desc" ]],
					"aoColumns":[
						{"bSortable": true},
						{"bSortable": true},						
						{"bSortable": true},
						{"bSortable": true},
						//{"bSortable": true},
						 {"bSortable": true},
						 {"bSortable": false},
						<?php if($obj_apartment->role=='gatekeeper' || $obj_apartment->role=='staff_member'){?>
						{"bSortable": false} <?php } ?>],
						language:<?php echo amgt_datatable_multi_language();?>
					});
			} );
			</script>
			<div class="panel-body"><!--PANEL-BODY--->
				<div class="table-responsive"><!--TABLE-RESPONSIVE--->
					<table id="visitor_checkin_list" class="display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th><?php esc_html_e('Visitor Name-ID Number-Vehicle Number', 'apartment_mgt' ) ;?></th>							
								<th><?php esc_html_e('Gate Name', 'apartment_mgt' ) ;?></th>
								<th><?php esc_html_e('Checked In Date', 'apartment_mgt' ) ;?></th>
								<th><?php esc_html_e('Checked In On Time', 'apartment_mgt' ) ;?></th>
								<th><?php esc_html_e('Checked Out Time', 'apartment_mgt' ) ;?></th>
								<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
								 <?php if($obj_apartment->role=='gatekeeper' || $obj_apartment->role=='staff_member'){?>
								<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
								 <?php } ?>
							</tr>
						</thead>	
						<tfoot>
							<tr>
								<th><?php esc_html_e('Visitor Name-ID Number-Vehicle Number', 'apartment_mgt' ) ;?></th>							
								<th><?php esc_html_e('Gate Name', 'apartment_mgt' ) ;?></th>
								<th><?php esc_html_e('Checked In Date', 'apartment_mgt' ) ;?></th>
								<th><?php esc_html_e('Checked In On Time', 'apartment_mgt' ) ;?></th>
								<th><?php esc_html_e('Checked Out Time', 'apartment_mgt' ) ;?></th>
								<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
								 <?php if($obj_apartment->role=='gatekeeper' || $obj_apartment->role=='staff_member'){?>
								<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
								 <?php } ?>
							</tr>
						   
						</tfoot>
						<tbody>
							<?php 
							//$visitor_checkindata=$obj_gate->Amgt_get_all_visitor_checkinentries();
								$user_id=get_current_user_id();
								//--- VISITOR DATA FOR MEMBER  ------//
								if($obj_apartment->role=='member')
								{
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{
										$building_id=get_user_meta( get_current_user_id(), 'building_id','true'); 
										$unit_cat_id=get_user_meta( get_current_user_id(), 'unit_cat_id','true'); 
										$unit_name=get_user_meta( get_current_user_id(), 'unit_name','true'); 
										$visitor_checkindata=$obj_gate->amgt_get_all_visitor_checkinentries_own($building_id,$unit_cat_id,$unit_name);
									}
									else
									{
										$visitor_checkindata=$obj_gate->Amgt_get_all_visitor_checkinentries();
									}
								} 
								//--- VISITOR DATA FOR STAFF MEMBER  ------//
								elseif($obj_apartment->role=='staff_member')
								{
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{  
										$visitor_checkindata=$obj_gate->amgt_get_all_visitor_checkinentries_owndata($user_id);
									}
									else
									{
										$visitor_checkindata=$obj_gate->Amgt_get_all_visitor_checkinentries();
									}
								}
								//--- VISITOR DATA FOR ACCOUNTANT  ------//
								elseif($obj_apartment->role=='accountant')
								{
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{ 
										$visitor_checkindata=$obj_gate->amgt_get_all_visitor_checkinentries_owndata($user_id);
									}
									else
									{
										$visitor_checkindata=$obj_gate->Amgt_get_all_visitor_checkinentries();
									}
								}
								//--- VISITOR DATA FOR GATEKEEPER  ------//
								else
								{
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{ 
										$visitor_checkindata=$obj_gate->amgt_get_all_visitor_checkinentries_owndata($user_id);
									}
									else
									{
										$visitor_checkindata=$obj_gate->Amgt_get_all_visitor_checkinentries();
									}
								}

							if(!empty($visitor_checkindata))
							{
							   foreach ($visitor_checkindata as $retrieved_data)
								{
									
								if($retrieved_data->status == '0')
								{
									$status=esc_html__('Processing', 'apartment_mgt' );
								}
								else
								{
									$status=esc_html__('Approved', 'apartment_mgt' );
								}
								$visitor_name_array=array();
						
								$all_visiter_entry=json_decode($retrieved_data->visiters_value);
								if(!empty($all_visiter_entry))
								{
									foreach($all_visiter_entry as $entry1)
									{
										$visitor_name_array[]=$entry1->visitor_name.'-'.$entry1->mobile.'-'.$entry1->vehicle_number;
									}	
								}
								else
								{							
									//$visitor_name_array[]='';
									$visitor_name_array=array($retrieved_data->visitor_name .'-'.$retrieved_data->mobile.'-'.$retrieved_data->vehicle_number);
								}
								   global $wpdb;		  
								   $table_name = $wpdb->prefix. 'amgt_gates';
								   $result = $wpdb->get_row("SELECT * FROM $table_name where id=".$retrieved_data->gate_id);				?>
									<tr>
										<td class="name"><?php echo implode(',<br>',$visitor_name_array);?></td>										
										<td class="gate_name"><?php echo  esc_html($result->gate_name);?></td>
										<td class="vehicle"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->checkin_date));?></td>
										<td class="vehicle"><?php echo esc_html($retrieved_data->checkin_time);?></td>
										<td class="checkout"><?php echo esc_html($retrieved_data->checkout_time); ?></td>
										<td class="vehicle"><?php echo esc_html($status);?></td>
										 <?php if($obj_apartment->role=='gatekeeper' || $obj_apartment->role=='staff_member')
										 {?>
										<td class="action">
										<?php
										if($user_access['edit']=='1')
										{  ?>
											<a href="?apartment-dashboard=user&page=visitor-manage&tab=visitor-checkin&action=edit&visitor_checkin_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
										<?php
										}
										if($user_access['delete']=='1')
										{
										?>
											<a href="?apartment-dashboard=user&page=visitor-manage&ab=manage-gates&action=delete&visitor_checkin_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
											<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
										<?php 
										}
										if($retrieved_data->status == '1')
							            { ?>
										<a href="?apartment-dashboard=user&page=visitor-manage&print=print&visitor_checkin_id=<?php echo esc_attr($retrieved_data->id);?>" target="_blank" class="btn btn-primary"> <?php esc_html_e('Print Details', 'apartment_mgt' ) ;?>
										 <?php 
										} 
										 ?>
										<?php if($retrieved_data->status == '1' && empty($retrieved_data->checkout_time)){ ?>
											<a checkin_id="<?php echo esc_attr($retrieved_data->id); ?>" checkout-type="visitor" class="btn btn-success check-out"> <?php esc_html_e('Check Out', 'apartment_mgt' ) ;?></a>
										<?php }
										?>
										</td>
										 <?php } ?>
									   
									</tr>
								<?php
								} 
								
							} ?>
					 
						</tbody>
				
					</table>
                </div><!--END TABLE-RESPONSIVE--->
            </div><!--END PANEL-BODY--->
		 <?php }
           if($active_tab == 'visitor-checkin')
			    { 
				  require_once AMS_PLUGIN_DIR.'/template/visitor-manage/visitor_checkin.php' ;
		        }
			 if($active_tab == 'staff-checkinlist')
			    { 
				  require_once AMS_PLUGIN_DIR.'/template/visitor-manage/staff_checkinlist.php' ;
		        }
			    if($active_tab == 'staff-checkin')
			    { 
				  require_once AMS_PLUGIN_DIR.'/template/visitor-manage/staff_checkin.php' ;
		        }
				
				if($active_tab == 'visiter_request_list')
			    { 
				  require_once AMS_PLUGIN_DIR.'/template/visitor-manage/visiter_request_list.php' ;
		        }
				
				if($active_tab == 'visiter_request')
			    { 
				  require_once AMS_PLUGIN_DIR.'/template/visitor-manage/visiter_request.php' ;
		        }

		 ?>
		
	
	
	</div>
</div><!-- END PANEL BODY DIV -->
<?php ?>