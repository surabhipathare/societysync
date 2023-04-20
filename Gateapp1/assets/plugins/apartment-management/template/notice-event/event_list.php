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
//EVENT LIST TAB
if($active_tab == 'event_list') { ?>
		<script type="text/javascript">
		$(document).ready(function() {
			"use strict";
			jQuery('#event_list').DataTable({
				"responsive": true,
				"order": [[ 0, "asc" ]],
				"aoColumns":[
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": false}],
							  language:<?php echo amgt_datatable_multi_language();?>
				});
		} );
		</script>
    	<div class="panel-body"><!--PANEL BODY-->
        	<div class="table-responsive"><!---TABLE-RESPONSIVE--->
			    <table id="event_list" class="display" cellspacing="0" width="100%"><!---EVENT LIST TABLE--->
					<thead>
						<tr>
							<th><?php esc_html_e('Event Title', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Start Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Start Time', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('End Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('End Time', 'apartment_mgt' ) ;?></th>
							<!--<th><?php esc_html_e('Visibility For', 'apartment_mgt' ) ;?></th>-->
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php esc_html_e('Event Title', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Start Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Start Time', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('End Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('End Time', 'apartment_mgt' ) ;?></th>
							<!--<th><?php esc_html_e('Visibility For', 'apartment_mgt' ) ;?></th>-->
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
					</tfoot>
					<tbody>
						<?php 
						$user_id=get_current_user_id();
						//--- EVENT DATA FOR MEMBER  ------//
						if($obj_apartment->role=='member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$eventdata=$obj_notice->amgt_get_own_events($user_id);
							}
							else
							{
								$eventdata=$obj_notice->amgt_get_all_events();
							}
						} 
						//--- EVENT DATA FOR STAFF MEMBER  ------//
						elseif($obj_apartment->role=='staff_member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{  
								$eventdata=$obj_notice->amgt_get_own_events($user_id);
							}
							else
							{
								$eventdata=$obj_notice->amgt_get_all_events();
							}
						}
						//--- EVENT DATA FOR ACCOUNTANT  ------//
						elseif($obj_apartment->role=='accountant')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$eventdata=$obj_notice->amgt_get_own_events($user_id);
							}
							else
							{
								$eventdata=$obj_notice->amgt_get_all_events();
							}
						}
						//--- EVENT DATA FOR GATEKEEPER  ------//
						else
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$eventdata=$obj_notice->amgt_get_own_events($user_id);
							}
							else
							{
								$eventdata=$obj_notice->amgt_get_all_events();
							}
						}
						if(!empty($eventdata))
						{
							foreach ($eventdata as $retrieved_data)
							{ ?>
								<tr>
									<td class="title">
									<?php echo esc_html($retrieved_data->event_title);?></td>
									<td class="start date"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->start_date));?></td>
									<td class=""><?php echo esc_html($retrieved_data->start_time);?></td>
									<td class="end date"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->end_date));?></td>
									<td class=""><?php echo esc_html($retrieved_data->end_time);?></td>
									<td class="action">
										<a href="#" class="btn btn-primary view-event" id="<?php echo esc_attr( $retrieved_data->id);?>"> <?php esc_html_e('View','apartment_mgt');?></a>
										<?php
										if($user_access['edit']=='1')
										{  ?>
											<a href="?apartment-dashboard=user&page=notice-event&tab=add_event&action=edit&event_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' );?></a>
										<?php
										}
										if($user_access['delete']=='1')
										{
										?>
											<a href="?apartment-dashboard=user&page=notice-event&tab=notice_list&action=delete&event_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
											onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
											<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
										<?php 
										}if($retrieved_data->event_doc!='')
										{ ?>
										 <a target="blank" href="<?php echo content_url().'/uploads/apartment_assets/'.$retrieved_data->event_doc;?>" class="btn btn-default"> <i class="fa fa-eye"></i> <?php esc_html_e('View Document', 'apartment_mgt' ) ;?> 
										</a>
										<?php
										} ?>
									</td>
								</tr>
							<?php 
							} 
						}?>
					</tbody>
				</table><!---END EVENT LIST TABLE--->
            </div><!--- END TABLE-RESPONSIVE--->
        </div><!--END PANEL BODY-->
		<?php } ?>