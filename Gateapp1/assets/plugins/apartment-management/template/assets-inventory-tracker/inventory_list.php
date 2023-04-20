<?php 
     //INVENTORY_LIST TAB 
    if($active_tab == 'inventory_list') { ?>
	<script type="text/javascript">
	$(document).ready(function() {
		"use strict";
		jQuery('#inventory_list').DataTable({
			"responsive": true,
			"order": [[ 0, "asc" ]],
			"aoColumns":[
						  {"bSortable": true},
						  {"bSortable": true},
						  {"bSortable": true},
						  <?php if($obj_apartment->role=='staff_member'){?>
						  {"bSortable": false}<?php } ?>],
						  language:<?php echo amgt_datatable_multi_language();?>
			});
	} );
	</script>
			 <div class="panel-body"><!--PANEL BODY-->
				<div class="table-responsive"><!---TABLE-RESPONSIVE--->
			  <!---INVENTORY_LIST--->
			  <table id="inventory_list" class="display" cellspacing="0" width="100%">
				 <thead>
				<tr>
					<th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
					<th><?php esc_html_e('Quantity', 'apartment_mgt' ) ;?></th>
					<th><?php esc_html_e('Unit', 'apartment_mgt' ) ;?></th>
					<?php if($obj_apartment->role=='staff_member'){?>
					<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
					<?php } ?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
					<th><?php esc_html_e('Quantity', 'apartment_mgt' ) ;?></th>
					<th><?php esc_html_e('Unit', 'apartment_mgt' ) ;?></th>
					<?php if($obj_apartment->role=='staff_member'){?>
					<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
					<?php } ?>
				</tr>
		   </tfoot>
	 
			<tbody>
			 <?php 
			 $user_id=get_current_user_id();
			$own_data=$user_access['own_data'];
			if($own_data == '1')
			{ 
				$inventorydata=$obj_assets->amgt_get_own_inventory($user_id);
			}
			else
			{
				$inventorydata=$obj_assets->amgt_get_all_inventory();
			}
				
			 if(!empty($inventorydata))
			 {
				foreach ($inventorydata as $retrieved_data){ ?>
				<tr>
					<td class="name">
					<?php if($obj_apartment->role=='staff_member'){?><!---ROLE STAFF MEMBER--->
					<a href="?apartment-dashboard=user&page=assets-inventory-tracker&tab=add_inventory&action=edit&inventory_id=<?php echo esc_attr($retrieved_data->id);?>">
					<?php }
						else { ?>
						<a href="#">
						<?php } echo esc_html($retrieved_data->inventory_name);?></a></td>
					<td class="inventory_uni"><?php echo get_the_title($retrieved_data->inventory_unit_cat);?></td>
					<td class="quentity"><?php echo esc_html($retrieved_data->quentity);?></td>
					<?php if($obj_apartment->role=='staff_member'){?>
					
					<td class="action">
						<a href="?apartment-dashboard=user&page=assets-inventory-tracker&tab=add_inventory&action=edit&inventory_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
					 
						<a href="?apartment-dashboard=user&page=assets-inventory-tracker&tab=inventory_list&action=delete&inventory_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
						onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
						<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
						
					</td>
					<?php } ?>
				   
				</tr>
				<?php } 
				
			} ?>
		 
			</tbody>
			
			</table><!---END INVENTORY_LIST--->
		 </div><!---END TABLE-RESPONSIVE--->
	 </div><!--END PANEL BODY-->
			<?php } ?>