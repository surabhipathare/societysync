<?php ?>
<script type="text/javascript">
$(document).ready(function() {
	 //SERVICE LIST
	 "use strict";
	jQuery('#service_list').DataTable({
		"responsive":true,
		"order": [[ 1, "asc" ]],
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},	                  
	                  {"bSortable": true},
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
    <form name="member_form" action="" method="post"><!--MEMBER_FORM-->
        <div class="panel-body"><!--PANEL BODY-->
        	<div class="table-responsive"><!---TABLE RESPONSIVE--->
				<table id="service_list" class="display" cellspacing="0" width="100%"><!---SERVICE LIST TABLE--->
					<thead>
						<tr>
							<th><?php  esc_html_e('Member Name', 'apartment_mgt' ) ;?></th>
							<th><?php  esc_html_e('Facility Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Activity Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Start Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('End Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Start Time', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('End Time', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Booking Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Total Charge', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
				    </thead>
					<tfoot>
						<tr>
							<th><?php  esc_html_e('Member Name', 'apartment_mgt' ) ;?></th>
							<th><?php  esc_html_e('Facility Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Activity Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Start Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('End Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Start Time', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('End Time', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Booking Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Total Charge', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
					</tfoot>
					<tbody>
						<?php 
						$facility_data= $obj_facility->amgt_get_all_booked_facility();
						if(!empty($facility_data))
						{
							foreach ($facility_data as $retrieved_data)
							{ ?>
							<tr>
								  <td class="member_name"><?php  $user_info = get_userdata($retrieved_data->book_on_behalf_of); echo esc_html($user_info->display_name);?></td>
								  <td class="facility_name"><?php echo amgt_get_facility_name($retrieved_data->facility_id);?></td>
								  <td class="booked_for"><?php echo get_the_title($retrieved_data->activity_id);?></td>
								  <td class="start_date"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->start_date));?></td>
								  <td class="end_date"><?php if($retrieved_data->end_date!="0000-00-00" && "00/00/0000" && "00/00/0000"){ echo date(amgt_date_formate(),strtotime($retrieved_data->end_date)); }else{ echo "-"; } ;?></td>
								  <td class="start_date"><?php if(!empty($retrieved_data->start_time))
								  {
									  echo esc_html($retrieved_data->start_time);
								  }
								  else
								  {
									 echo "-"; 
								  }
								  ?></td>
								  
								  <td class="start_date">
								  <?php
								  if(!empty($retrieved_data->end_time))
								  {
									echo esc_html($retrieved_data->end_time);
								  }
								  else
								  {
									echo "-";   
								  }
								  
								  ?></td>
								  <td class="booking_Date"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->created_date));?></td>
								  <td class="booking_charge"><?php echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo esc_html($retrieved_data->booking_cost);?></td>
								  <?php
									if($retrieved_data->status == '0')
									{
										$status=esc_html__('Processing', 'apartment_mgt' );
									}
									else
									{
										$status=esc_html__('Approved', 'apartment_mgt' );
									}
									?>
								<td class="vehicle"><?php echo esc_html($status);?></td>	
								<td class="action">
								<?php 
									if($retrieved_data->status == '0')
									{ ?>
									<a  href="?page=amgt-facility-mgt&tab=facility-booking-list&action=booking_approved&facility_booking_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-default" > <?php esc_html_e('Approve', 'apartment_mgt');?></a>
									<?php 
									} 
									?>
									<a href="?page=amgt-facility-mgt&tab=booking-facility&action=edit&facility_booking_id=<?php echo esc_attr($retrieved_data->id)?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
									<a href="?page=amgt-facility-mgt&tab=facility-list&action=delete&facility_booking_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
									onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
									<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
								</td>
							</tr>
							<?php 
							} 
						}?>
					</tbody>
				</table><!---END SERVICE LIST TABLE--->
            </div><!---TABLE RESPONSIVE END--->
        </div><!--END PANEL BODY-->
    </form>
	<!--End Facility Booking LIst-->
<?php ?>