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
//ASSIGNED-SLOAT-LIST
if($active_tab == 'assigned-sloat-list') { ?>
		<script type="text/javascript">
		$(document).ready(function() {
			//ASSIGNED-SLOAT-LIST
			"use strict";
			jQuery('#assigned_sloat_list').DataTable({
				"responsive": true,
				"order": [[ 0, "asc" ]],
				"aoColumns":[
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  <?php if($obj_apartment->role=='staff_member' || $obj_apartment->role=='gatekeeper'){?>
							  {"bSortable": false} <?php } ?>],
							  language:<?php echo amgt_datatable_multi_language();?>
				});
		} );
		</script>
    	<div class="panel-body"><!--PANEL BODY DIV-->
        	<div class="table-responsive"><!---TABLE-RESPONSIVE--->
				<table id="assigned_sloat_list" class="display" cellspacing="0" width="100%"><!--ASSIGNED-SLOT-LIST TABLE--->
					<thead>
						<tr>
							<th><?php esc_html_e('Slot No', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Slot Type', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Building Block', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Member Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Vehicle Number', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('From Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('To Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
							<?php if($obj_apartment->role=='staff_member' || $obj_apartment->role=='gatekeeper'){?>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
							<?php } ?>
						</tr>
				    </thead>
					<tfoot>
						<tr>
							<th><?php esc_html_e('Slot No', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Slot Type', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Building Block', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Member Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Vehicle Number', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('From Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('To Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
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
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$assignedsloatdata=$obj_parking->amgt_get_own_assigned_sloats($user_id);
							}
							else
							{
								$assignedsloatdata=$obj_parking->amgt_get_all_assigned_sloats();
							}
						} 
						//--- PARKING DATA FOR STAFF MEMBER  ------//
						elseif($obj_apartment->role=='staff_member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{  
								$assignedsloatdata=$obj_parking->amgt_get_own_assigned_sloats($user_id);
							}
							else
							{
								$assignedsloatdata=$obj_parking->amgt_get_all_assigned_sloats();
							}
						}
						//--- PARKING DATA FOR ACCOUNTANT  ------//
						elseif($obj_apartment->role=='accountant')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$assignedsloatdata=$obj_parking->amgt_get_own_assigned_sloats($user_id);
							}
							else
							{
								$assignedsloatdata=$obj_parking->amgt_get_all_assigned_sloats();
							}
						}
						//--- PARKING DATA FOR GATEKEEPER  ------//
						else
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$assignedsloatdata=$obj_parking->amgt_get_own_assigned_sloats($user_id);
							}
							else
							{
								$assignedsloatdata=$obj_parking->amgt_get_all_assigned_sloats();
							}
						}
						if(!empty($assignedsloatdata))
						{
							foreach ($assignedsloatdata as $retrieved_data){ 
							 
							$sloat=amgt_get_sloat_name($retrieved_data->sloat_id);
						   ?>
							<tr>
								<td class="sloatname"><!--SLOT NAME --->
									<?php  echo esc_html($sloat->sloat_name); ?></td>
								<td class="sloattype"><?php if($sloat->sloat_type=='guest') echo esc_html__('Guest','apartment_mgt'); else echo esc_html__('Member','apartment_mgt');?></td>
								<td class="building"><?php echo get_the_title($retrieved_data->building_id);?></td>
								<td class="member"><?php echo amgt_get_display_name($retrieved_data->member_id);?></td>
								<td class="vehicle"><?php echo esc_html($retrieved_data->vehicle_number);?></td>
								<td class="from"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->from_date));?></td>
								<td class="to"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->to_date));?></td>
								<td class="to"><?php if($retrieved_data->status=='alloted'){ echo esc_html__('Allotted','apartment_mgt');}else{ echo esc_html__('Unallocated','apartment_mgt');}?></td>
								<?php if($obj_apartment->role=='staff_member' || $obj_apartment->role=='gatekeeper'){?>
								<td class="action">
								<?php
								if($user_access['edit']=='1')
								{  ?>
									<a href="?apartment-dashboard=user&page=parking-manager&tab=assign_sloat&action=edit&sloat_assign_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
								<?php
								}
								if($user_access['delete']=='1')
								{
								?>
									<a href="?apartment-dashboard=user&page=parking-manager&tab=assigned-sloat-list&action=delete&sloat_assign_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
									onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
									<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
								  <?php
								}
									?> 
								</td>
								<?php } ?>
							   
							</tr>
							<?php 
							} 
							
						} ?>
					</tbody>
				</table><!--END ASSIGNED-SLOT-LIST TABLE--->
            </div><!---END TABLE-RESPONSIVE--->
        </div><!--END PANEL BODY DIV-->
		<?php } ?>