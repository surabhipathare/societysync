<?php 
//STAFF CHECKINLIST
if($active_tab == 'staff-checkinlist') { ?>
		<script type="text/javascript">
		$(document).ready(function() {
			"use strict";
			jQuery('#staff_checkin_list').DataTable({
				"order": [[ 0, "asc" ]],
				"responsive":true,
				"aoColumns":[
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							   <?php if($obj_apartment->role=='gatekeeper') { ?>
							   {"bSortable": false}<?php } ?> ],
							   language:<?php echo amgt_datatable_multi_language();?>
				});
		} );
		</script>
    	<div class="panel-body"><!--PANEL BODY DIV---->
        	<div class="table-responsive"><!--TABLE-RESPONSIVE DIV---->
				<table id="staff_checkin_list" class="display" cellspacing="0" width="100%"><!--STAFF_CHECKIN_LIST TABLE-->
					<thead>
						<tr>
							<th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Gate Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Checked In Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Checked in On Time', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Checked Out Time', 'apartment_mgt' ) ;?></th>
							 <?php if($obj_apartment->role=='gatekeeper' || $obj_apartment->role=='staff_member'){?>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
							 <?php } ?>
						</tr>
				    </thead>
				    <tfoot>
						<tr>
							<th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Gate Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Checked In Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Checked in On Time', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Checked Out Time', 'apartment_mgt' ) ;?></th>
							 <?php if($obj_apartment->role=='gatekeeper' || $obj_apartment->role=='staff_member'){?>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
							 <?php } ?>
						</tr>
				    </tfoot>
					<tbody>
					<?php 
						$visitor_checkindata=$obj_gate->amgt_get_all_staff_checkinentries();
					 if(!empty($visitor_checkindata))
					    {
						foreach ($visitor_checkindata as $retrieved_data){
						global $wpdb;		  
						$table_name = $wpdb->prefix. 'amgt_gates';
						$result = $wpdb->get_row("SELECT * FROM $table_name where id=".$retrieved_data->gate_id);				?>
						<tr>
							<td class="name">
							 <?php if($obj_apartment->role=='gatekeeper'){?>
							<a href="?apartment-dashboard=user&page=visitor-manage&tab=staff-checkin&action=edit&staff_checkin_id=<?php echo esc_attr($retrieved_data->id);?>">
							 <?php }
								else
								{?>
								<a href="#">
								<?php } echo amgt_get_display_name($retrieved_data->member_id);?></a></td>
								<td class="gate_name"><?php echo  $result->gate_name;?></td>
							<td class="vehicle"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->checkin_date));?></td>
							<td class="checkin"><?php echo esc_html($retrieved_data->checkin_time);?></td>
							<td class="checkout"><?php echo esc_html($retrieved_data->checkout_time);?></td>
							     <?php if($obj_apartment->role=='gatekeeper' || $obj_apartment->role=='staff_member'){?>
							<td class="action">
							<?php
							if($user_access['edit']=='1')
							{  ?>
						      <a href="?apartment-dashboard=user&page=visitor-manage&tab=staff-checkin&action=edit&staff_checkin_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
							  <?php
							}
							if($user_access['delete']=='1')
							{
							?>
							  <a href="?apartment-dashboard=user&page=visitor-manage&ab=manage-gates&action=delete&staff_checkin_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
							onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
							<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
							<?php 
							} ?>
							<?php if($retrieved_data->checkout_time==''){ ?>
								
								<a checkin_id="<?php echo esc_attr($retrieved_data->id); ?>" checkout-type="staff" class="btn btn-success check-out"> <?php esc_html_e('Check Out', 'apartment_mgt' ) ;?></a>
						  
						    <?php } ?>
							</td>
							 <?php } ?>
						   
						</tr>
						<?php } 
						
					} ?>
				 
					</tbody>
				
				</table>
             </div>
        </div><!--END PANEL BODY DIV---->
		<?php } ?>