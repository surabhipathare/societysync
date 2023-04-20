<script type="text/javascript">
$(document).ready(function() {
	//EVENT LIST
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

    <form name="event_form" action="" method="post"><!------EVENT LIST FORM------->
        <div class="panel-body"><!------PANEL BODY------->
        	<div class="table-responsive"><!---TABLE-RESPONSIVE--->
				<table id="event_list" class="display" cellspacing="0" width="100%"><!---EVENT LIST TABLE--->
					<thead>
						<tr>
							<th><?php esc_html_e('Event Title', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Start Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('End Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Start Time', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('End Time', 'apartment_mgt' ) ;?></th>
							<!--<th><?php esc_html_e('Visibility For', 'apartment_mgt' ) ;?></th>-->
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php esc_html_e('Event Title', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Start Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('End Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Start Time', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('End Time', 'apartment_mgt' ) ;?></th>
							<!--<th><?php esc_html_e('Visibility For', 'apartment_mgt' ) ;?></th>-->
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
					</tfoot>
					<tbody>
					 <?php 
					  $eventdata=$obj_notice->amgt_get_all_events();
					  
						if(!empty($eventdata))
						{
						    foreach ($eventdata as $retrieved_data)
						    {
							  ?>
							<tr>
								<td class="title"><a href="?page=amgt-notice-event&tab=add_event&action=edit&event_id=<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_html($retrieved_data->event_title);?></a></td>
								<td class="start date"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->start_date));?></td>
								<td class="end date"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->end_date));?></td>
								<td class=""><?php echo esc_html($retrieved_data->start_time);?></td>
								
								<td class=""><?php echo esc_html($retrieved_data->end_time);?></td>
								
								<td class="action">
									<?php if($retrieved_data->publish_status=='no'){ ?>
									<a href="?page=amgt-notice-event&tab=notice_list&action=approve_notice&event_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-default"> <?php esc_html_e('Approve', 'apartment_mgt' );?></a>
									<?php } ?>
									 <a href="#" class="btn btn-primary view-event" id="<?php echo esc_attr($retrieved_data->id);?>"> <?php esc_html_e('View','apartment_mgt');?></a>
								   <a href="?page=amgt-notice-event&tab=add_event&action=edit&event_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' );?></a>
									<a href="?page=amgt-notice-event&tab=notice_list&action=delete&event_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
									onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
									<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
									<?php if($retrieved_data->event_doc!='')
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
            </div><!---TABLE-RESPONSIVE--->
        </div><!------END PANEL BODY------->
    </form>
	<!------End Event List------->