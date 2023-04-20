<?php ?>
<script type="text/javascript">
$(document).ready(function() {
	//ASSIGNED SLOT LIST
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
	        {"bSortable": false}],
			language:<?php echo amgt_datatable_multi_language();?>
		});
} );
</script>
<?php 
//ASSIGNED SLOT LIST TAB	
if($active_tab == 'assigned-sloat-list')
{
	$assignedsloatdata=$obj_parking->amgt_get_all_assigned_sloats();
?>
<div class="panel-body"><!--PANEL BODY-->
  	<div class="table-responsive"><!---TABLE-RESPONSIVE--->
		<table id="assigned_sloat_list" class="display" cellspacing="0" width="100%">
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
					<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
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
					<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
				</tr>			   
			</tfoot>
 
			<tbody>
			<?php 
			$assignedsloatdata=$obj_parking->amgt_get_all_assigned_sloats();
			if(!empty($assignedsloatdata))
			{
				foreach ($assignedsloatdata as $retrieved_data){ 
					$sloat=amgt_get_sloat_name($retrieved_data->sloat_id);
			?>
				<tr>
					<td class="sloatname"><a href="?page=amgt-parking-mgt&tab=assign_sloat&action=edit&sloat_assign_id=<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_html($sloat->sloat_name); ?></a></td>
					<td class="sloattype"><?php if($sloat->sloat_type=='guest') echo esc_html__('Guest','apartment_mgt'); else echo esc_html__('Member','apartment_mgt');?></td>
					<td class="building"><?php echo get_the_title($retrieved_data->building_id);?></td>
					<td class="member"><?php echo amgt_get_display_name($retrieved_data->member_id);?></td>
					<td class="vehicle"><?php echo esc_html($retrieved_data->vehicle_number);?></td>
					<td class="from"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->from_date));?></td>
					<td class="to"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->to_date));?></td>
					<td class="to"><?php if($retrieved_data->status=='alloted'){ echo esc_html__('Allotted','apartment_mgt');}else{ echo esc_html__('Unallocated','apartment_mgt');}?></td>
					<td class="action">
						<a href="?page=amgt-parking-mgt&tab=assign_sloat&action=edit&sloat_assign_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
						<a href="?page=amgt-parking-mgt&ab=sloat-list&action=delete&sloat_assign_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
						onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
						<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
				   
					</td>				   
				</tr>
				<?php } 				
			} ?>     
			</tbody>        
        </table>
    </div><!---END TABLE-RESPONSIVE--->
</div><!--END PANEL BODY-->
        
     <?php 
	 } ?>