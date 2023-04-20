<?php
$obj_assets=new Amgt_AssetsInventory;
?>
<script type="text/javascript">
$(document).ready(function() {
	 //INVENTORY_LIST DATATABLE
	 "use strict";
	jQuery('#inventory_list').DataTable({
		"responsive": true,
		"order": [[ 0, "asc" ]],
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": false}],
					  language:<?php echo amgt_datatable_multi_language();?>
		});
} );
</script>
    <form name="inventory_form" action="" method="post"><!--INVENTORY FORM-->
        <div class="panel-body"><!-- PANEL BODY-->
        	<div class="table-responsive"><!---TABLE-RESPONSIVE--->
				<table id="inventory_list" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Unit', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Quantity', 'apartment_mgt' ) ;?></th>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
				    </thead>
					<tfoot>
						<tr>
							<th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Unit', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Quantity', 'apartment_mgt' ) ;?></th>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
					</tfoot>
					<tbody>
						 <?php 
						$inventorydata=$obj_assets->amgt_get_all_inventory();
						if(!empty($inventorydata))
						{
							foreach ($inventorydata as $retrieved_data)
							{ ?>
							<tr>
								<td class="name"><a href="?page=amgt-assets-inventory&tab=add_inventory&action=edit&inventory_id=<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_html($retrieved_data->inventory_name);?></a></td>
								<td class="inventory_uni"><?php echo get_the_title($retrieved_data->inventory_unit_cat);?></td>
								<td class="quentity"><?php echo esc_html($retrieved_data->quentity);?></td>
								<td class="action">
								   <a href="?page=amgt-assets-inventory&tab=add_inventory&action=edit&inventory_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
								 
									<a href="?page=amgt-assets-inventory&tab=inventory_list&action=delete&inventory_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
									onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
									<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
								
								</td>
							   
							</tr>
							<?php 
							} 
						}  ?>
					</tbody>
				
				</table>
            </div><!--END INVENTORY FORM-->
        </div><!--END PANEL BODY-->
    </form><!--END INVENTORY FORM-->