<?php
error_reporting(0);
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
$obj_facility =new Amgt_Facility;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'facility-list';
		
if(isset($_POST['save_facility']))	//SAVE_FACILITY	
{
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_facility_nonce' ) )
	{
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
	{
		$result=$obj_facility->amgt_add_facility($_POST);
	
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=facility&tab=facility-list&tab=facility-list&message=2');
		}
	}
	else
	{
		$result=$obj_facility->amgt_add_facility($_POST);
		if($result)
		{
			wp_redirect ( home_url().'?apartment-dashboard=user&page=facility&tab=facility-list&tab=facility-list&message=1');
		}
	}
	}
}
if(isset($_POST['save_book_facility']))//SAVE_BOOK_FACILITY		
{ 
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_book_facility_nonce' ) )
	{
	$start_date=amgt_get_format_for_db($_POST['start_date']);
	if(isset($_POST['end_date']))
	{
		$end_date=amgt_get_format_for_db($_POST['end_date']);		
	}
	else
	{
		$end_date=$start_date;
	}
	$start_hour=date("H", strtotime($_POST['start_time']));
	$start_minute=date("i", strtotime($_POST['start_time']));
	$end_hour=date("H", strtotime($_POST['end_time']));
	$end_minute=date("i", strtotime($_POST['end_time']));
	
	if($start_date == $end_date )
	{	
		if($_POST['period_type'] == 'hour_type')
		{	
			if($start_hour > $end_hour)
			{			
				echo '<script type="text/javascript">alert("End Time should be greater than Start Time");</script>';			
			}
			elseif($start_hour ==  $end_hour && $start_minute > $end_minute )
			{
				echo '<script type="text/javascript">alert("End Time should be greater than Start Time");</script>';		  
			}
			else
			{
				$facility_id=$_POST['facility_id'];
				if($_POST['period_type'] == 'hour_type')
				{
					 $start_time=$_POST['start_time'];
					 $end_time=$_POST['end_time'];
					 global $wpdb;
					 $table_amgt_facility = $wpdb->prefix. 'amgt_facility_booking';
					 $facility_booking_data = $wpdb->get_results("SELECT * FROM $table_amgt_facility where start_date='$start_date' 
								AND start_time='$start_time' AND end_time='$end_time' AND facility_id=$facility_id");
				} 
				else
				{
					  $end_date=amgt_get_format_for_db($_POST['end_date']);
					  global $wpdb;
					  $table_amgt_facility = $wpdb->prefix. 'amgt_facility_booking';
					  $facility_booking_data = $wpdb->get_results("SELECT * FROM $table_amgt_facility where start_date BETWEEN '$start_date' AND '$end_date' AND end_date BETWEEN '$start_date' AND '$end_date' AND facility_id=$facility_id AND period_type='date_type'");
				}
				if(empty($facility_booking_data))
				{			
					if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
					{
							
						$result=$obj_facility->amgt_book_facility($_POST);
						if($result)
						{
							wp_redirect ( home_url().'?apartment-dashboard=user&page=facility&tab=facility-booking-list&message=5');
						}
					}
					else
					{
						$result=$obj_facility->amgt_book_facility($_POST);
						if($result)
						{
							wp_redirect ( home_url().'?apartment-dashboard=user&page=facility&tab=facility-booking-list&message=4');
						}
					}
					
				}
				else
				{ ?>
				 <div id="message" class="updated below-h2"><p> <?php
					_e("Already booking this facility.",'apartment_mgt');
					?></p>
					</div>
								
				<?php 
				}
			}	
		}		
	}
	elseif($start_date > $end_date )
	{
		echo '<script type="text/javascript">alert("End Date should be greater than Start Date");</script>';
	}	
	else
	{
		$facility_id=$_POST['facility_id'];
		if($_POST['period_type'] == 'hour_type')
		{
			 $start_time=$_POST['start_time'];
			 $end_time=$_POST['end_time'];
			 global $wpdb;
			 $table_amgt_facility = $wpdb->prefix. 'amgt_facility_booking';
			 $facility_booking_data = $wpdb->get_results("SELECT * FROM $table_amgt_facility where start_date='$start_date' 
			  AND start_time='$start_time' AND end_time='$end_time' AND facility_id=$facility_id");
		} 
		else
		{
			  $end_date=amgt_get_format_for_db($_POST['end_date']);
			  global $wpdb;
			  $table_amgt_facility = $wpdb->prefix. 'amgt_facility_booking';
			  $facility_booking_data = $wpdb->get_results("SELECT * FROM $table_amgt_facility where start_date BETWEEN '$start_date' AND '$end_date' AND end_date BETWEEN '$start_date' AND '$end_date' AND facility_id=$facility_id AND period_type='date_type'");
		}
		if(empty($facility_booking_data))
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$result=$obj_facility->amgt_book_facility($_POST);
				if($result)
				{
					wp_redirect ( home_url().'?apartment-dashboard=user&page=facility&tab=facility-booking-list&message=5');
				}
			}
			else
			{
				$result=$obj_facility->amgt_book_facility($_POST);
				if($result)
				{
					wp_redirect ( home_url().'?apartment-dashboard=user&page=facility&tab=facility-booking-list&message=4');
				}
			}
		}
		else
		{ ?>
			<div id="message" class="updated below-h2"><p> <?php
				_e("Already booking this facility.",'apartment_mgt');
				?></p>
			</div>						
		<?php 
		}
	}	
}
}	
if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')//DELETE_BOOK_FACILITY
	{
		if(isset($_REQUEST['facility_id']))
		{
			$result=$obj_facility->amgt_delete_facility($_REQUEST['facility_id']);
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=facility&tab=facility-list&tab=facility-list&message=3');
			}
		}
		if(isset($_REQUEST['facility_booking_id']))
		{
			$result=$obj_facility->amgt_delete_booked_facility($_REQUEST['facility_booking_id']);
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=facility&tab=facility-list&tab=facility-booking-list&message=3');
			}
		}
	}
	
if(isset($_REQUEST['message']))//MESSAGES
{
	$message =$_REQUEST['message'];
	if($message == 1)
			{?>
					<div id="message" class="updated below-h2 notice is-dismissible">
					<p>
					<?php 
						esc_html_e('Facility inserted successfully','apartment_mgt');
					?></p></div>
					<?php 
				
			}
			elseif($message == 2)
			{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
						_e("Facility updated successfully.",'apartment_mgt');
						?></p>
						</div>
					<?php 
				
			}
			elseif($message == 3) 
			{?>
				<div id="message" class="updated below-h2 notice is-dismissible"><p>
				<?php 
					esc_html_e('Facility deleted successfully','apartment_mgt');
				?></div></p><?php
					
			}
			elseif($message == 4) 
			{?>
				<div id="message" class="updated below-h2 notice is-dismissible"><p>
				<?php 
					esc_html_e('Facility Booking successfully','apartment_mgt');
				?></div></p><?php
					
			}
			elseif($message == 5) 
			{?>
				<div id="message" class="updated below-h2 notice is-dismissible"><p>
				<?php 
					esc_html_e('Facility Booking Updated successfully','apartment_mgt');
				?></div></p><?php
					
			}
}
?>
<!-- POP UP CODE -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list"></div>
		</div>
    </div> 
</div>
<!-- END POP-UP CODE -->
<div class="panel-body panel-white"><!-- PANEL WHITE DIV -->
    <ul class="nav nav-tabs panel_tabs" role="tablist"><!--PANEL_TABS-->
	  	<li class="<?php if($active_tab=='facility-list'){?>active<?php }?>">
			<a href="?apartment-dashboard=user&page=facility&tab=facility-list" class="tab <?php echo $active_tab == 'facility-list' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php esc_html_e('Facility List', 'apartment_mgt'); ?></a>
          </a>
       </li>
		<?php if($obj_apartment->role=='staff_member' && $user_access['add']=='1')
		{?>
		   <li class="<?php if($active_tab=='add_facility'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['facility_id']))
				{ ?>
				<a href="?apartment-dashboard=user&page=facility&tab=add_facility&action=edit&facility_id=<?php echo $_REQUEST['facility_id'];?>" class="nav-tab <?php echo $active_tab == 'visitor-checkin' ? 'nav-tab-active' : ''; ?>">
				 <i class="fa fa"></i> <?php esc_html_e('Edit Facility', 'apartment_mgt'); ?></a>
				 <?php }
				else
				{ ?>
					<a href="?apartment-dashboard=user&page=facility&tab=add_facility" class="tab <?php echo $active_tab == 'add_facility' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_html_e('Add Facility', 'apartment_mgt'); ?></a>
		  <?php } ?>
		  
		</li>
		 <?php 
		} ?>
		<li class="<?php if($active_tab=='facility-booking-list'){?>active<?php }?>">
				<a href="?apartment-dashboard=user&page=facility&tab=facility-booking-list" class="tab margin_top_10_res <?php echo $active_tab == 'facility-booking-list' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php esc_html_e('Booking  Facility List', 'apartment_mgt'); ?></a>
			  </a>
		</li>
		<?php if(($obj_apartment->role=='staff_member' || $obj_apartment->role=='member') && ($user_access['add']=='1'))
		{?>
		   <li class="<?php if($active_tab=='booking-facility'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['facility_booking_id']))
				{ ?>
				<a href="?apartment-dashboard=user&page=facility&tab=booking-facility&action=edit&facility_booking_id=<?php echo $_REQUEST['facility_booking_id'];?>" class="nav-tab margin_top_10_res <?php echo $active_tab == 'booking-facility' ? 'nav-tab-active' : ''; ?>">
				 <i class="fa fa"></i> <?php esc_html_e('Edit Booked Facility', 'apartment_mgt'); ?></a>
				 <?php }
				else
				{ ?>
					<a href="?apartment-dashboard=user&page=facility&tab=booking-facility" class="tab margin_top_10_res <?php echo $active_tab == 'booking-facility' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_html_e('Book Facility', 'apartment_mgt'); ?></a>
		  <?php } ?>
		    </li>
		 <?php
		} ?>
    </ul>
	<div class="tab-content">
	<?php if($active_tab == 'facility-list')//FACILITY-LIST TAB
	{ ?>
		<script type="text/javascript">
		$(document).ready(function() {
			"use strict";
			jQuery('#service_list').DataTable({
				"responsive": true,
				"order": [[ 0, "asc" ]],
				"aoColumns":[
							  {"bSortable": false},
							  {"bSortable": true},
							  {"bSortable": true},	                  
							  {"bSortable": true},	                  
							  {"bSortable": true},
							  <?php if($obj_apartment->role=='staff_member')
							  {?>
							  {"bSortable": false}<?php 
							  } ?>],
							  language:<?php echo amgt_datatable_multi_language();?>
				});
		} );
		</script>
    	<div class="panel-body"><!--PANEL BODY-->
        	<div class="table-responsive"><!---TABLE-RESPONSIVE--->
			    <table id="service_list" class="display" cellspacing="0" width="100%"><!---SERVICE LIST TABLE--->
					<thead>
						<tr>
							  <th><?php  esc_html_e('Facility Name', 'apartment_mgt' ) ;?></th>
							  <th><?php esc_html_e('Facility Charge', 'apartment_mgt' ) ;?></th>
							  <th><?php esc_html_e('charge Per', 'apartment_mgt' ) ;?></th>
							   <th><?php esc_html_e('Is Multiple Days', 'apartment_mgt' ) ;?></th>
							   <th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
							   <?php if($obj_apartment->role=='staff_member')
							   {?>
							   <th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
							   <?php
							   } ?>
						</tr>
				    </thead>
					<tfoot>
						<tr>
							<th><?php  esc_html_e('Facility Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Facility Charge', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('charge Per', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Is Multiple Days', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
							<?php if($obj_apartment->role=='staff_member')
							{?>
							 <th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
							<?php 
							} ?>
						</tr>
					</tfoot>
					<tbody>
						<?php 
						$facility_data= $obj_facility->amgt_get_all_facility();
						if(!empty($facility_data))
						{
							foreach ($facility_data as $retrieved_data)
							{ ?>
								<tr>
									  <td class="service_name"><?php echo esc_html($retrieved_data->facility_name);?></td>
									  <td class="service_name"><?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo esc_html($retrieved_data->facility_charge);?></td>
									  <td class="service_name">
									  <?php 
									  if($retrieved_data->charge_per == 'hour')
										 esc_html_e('Hour','apartment_mgt');
										else
											 esc_html_e('Date','apartment_mgt');?>
									  </td>
									  <td class="service_name"><?php 
									  if($retrieved_data->allow_booking_multiple_base)
									  esc_html_e('Yes','apartment_mgt');
										else
											esc_html_e('No','apartment_mgt');?></td>
									 
									 <td class="status"><?php $statusdata=amgt_check_facility_availability($retrieved_data->facility_id);
									 if(!empty($statusdata))
										 esc_html_e('Booked','apartment_mgt');
									else
										esc_html_e('Available','apartment_mgt');		
									 ?></td> 
									 <?php if($obj_apartment->role=='staff_member'){?>
									<td class="action">
									   <?php
									if($user_access['edit']=='1')
									{  ?>
										<a href="?apartment-dashboard=user&page=facility&tab=add_facility&action=edit&facility_id=<?php echo esc_attr($retrieved_data->facility_id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
									<?php
									}
									if($user_access['delete']=='1')
									{
									?>
										<a href="?apartment-dashboard=user&page=facility&tab=facility-list&action=delete&facility_id=<?php echo esc_attr($retrieved_data->facility_id);?>" class="btn btn-danger" 
										onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
										<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
									<?php
									} ?>
									</td>
									 <?php 
									} ?>
								   
								</tr>
							<?php
							} 
						}?>
					</tbody>
			    </table><!---END SERVICE LIST TABLE--->
            </div><!---END TABLE-RESPONSIVE--->
        </div><!--END PANEL BODY-->
		<?php }
            if($active_tab == 'add_facility')
			{ 
			  require_once AMS_PLUGIN_DIR.'/template/facility/add_facility.php' ;
			}
			if($active_tab == 'facility-booking-list')
			{ 
			  require_once AMS_PLUGIN_DIR.'/template/facility/facility-booking-list.php' ;
			}
			if($active_tab == 'booking-facility')
			{ 
			  require_once AMS_PLUGIN_DIR.'/template/facility/booking-facility.php' ;
			}
		?>
	   <!--Function-->
	
	
    </div><!-- PANEL WHITE DIV -->
</div><!-- END PANEL WHITE DIV -->
<?php ?>