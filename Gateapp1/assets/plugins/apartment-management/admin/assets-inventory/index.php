<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'assets_list';
$obj_assets=new Amgt_AssetsInventory;
?>
<!-- POP UP CODE -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list">
			</div>
	    </div>
    </div> 
</div>
<!-- END POP-UP CODE -->
<div class="page-inner min_height_1088">
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>
<?php 
	if(isset($_POST['save_assets']))//SAVE_ASSETS		
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_assets_nonce' ) )
		{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_assets->amgt_add_assets($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-assets-inventory&tab=assets_list&message=2');
			}
		}
		else
		{
			$result=$obj_assets->amgt_add_assets($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-assets-inventory&tab=assets_list&message=1');
			}
		}
	  }
	}
	if(isset($_POST['save_inventory']))//SAVE_INVENTORY		
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_inventory_nonce' ) )
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$result=$obj_assets->amgt_add_inventory($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-assets-inventory&tab=inventory_list&message=5');
				}
			}
			else
			{
				$result=$obj_assets->amgt_add_inventory($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-assets-inventory&tab=inventory_list&message=4');
				}
			}
	    }
	}
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			if(isset($_REQUEST['assets_id'])){
				$result=$obj_assets->amgt_delete_assets($_REQUEST['assets_id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-assets-inventory&tab=assets_list&message=3');
				}
			}
			if(isset($_REQUEST['inventory_id'])){
				$result=$obj_assets->amgt_delete_inventory($_REQUEST['inventory_id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-assets-inventory&tab=inventory_list&message=3');
				}
			}
		}
		//MESSAGES
		if(isset($_REQUEST['message']))
	    {
			$message =$_REQUEST['message'];
			if($message == 1)
			{?>
					<div id="message" class="updated below-h2 notice is-dismissible">
					<p>
					<?php 
						esc_html_e('Asset inserted successfully','apartment_mgt');
					?></p></div>
					<?php
			
		    }
			elseif($message == 2)
			{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
						_e("Asset updated successfully.",'apartment_mgt');
						?></p>
						</div>
					<?php 
				
			}
			elseif($message == 3) 
			{?>
			<div id="message" class="updated below-h2 notice is-dismissible"><p>
			<?php 
				esc_html_e('Record deleted successfully','apartment_mgt');
			?></div></p><?php
					
			}
			if($message == 4)
			{?>
					<div id="message" class="updated below-h2 notice is-dismissible">
					<p>
					<?php 
						esc_html_e('Inventory inserted successfully','apartment_mgt');
					?></p></div>
					<?php
			
		    }
			elseif($message == 5)
			{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
						_e("Inventory updated successfully.",'apartment_mgt');
						?></p>
						</div>
					<?php 
				
			}
	    }?>
	<div id="main-wrapper"><!--MAIN WRAPPER-->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white"><!--<!--PANEL-WHITE-->
					<div class="panel-body"><!--PANEL BODY-->
					    
						<h2 class="nav-tab-wrapper"> <!-----NAV-TAB-WRAPPER----->
							<a href="?page=amgt-assets-inventory&tab=assets_list" class="nav-tab <?php echo $active_tab == 'assets_list' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Asset List', 'apartment_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'  && $_REQUEST['tab'] == 'add_assets')
							{ ?>
							<a href="?page=amgt-assets-inventory&tab=add_assets&action=edit&assets_id=<?php echo $_REQUEST['assets_id'];?>" class="nav-tab <?php echo $active_tab == 'add_assets' ? 'nav-tab-active' : ''; ?>">
							<?php esc_html_e('Edit Asset', 'apartment_mgt'); ?></a>  
							<?php 
							}
							else 
							{ ?>
								<a href="?page=amgt-assets-inventory&tab=add_assets" class="nav-tab <?php echo $active_tab == 'add_assets' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Asset', 'apartment_mgt'); ?></a>
							<?php  } ?>
							
							<a href="?page=amgt-assets-inventory&tab=inventory_list" class="nav-tab <?php echo $active_tab == 'inventory_list' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Inventory List', 'apartment_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab'] == 'add_inventory')
							{ ?>
							<a href="?page=amgt-assets-inventory&tab=add_inventory&action=edit&inventory_id=<?php echo $_REQUEST['inventory_id'];?>" class="nav-tab <?php echo $active_tab == 'add_inventory' ? 'nav-tab-active' : ''; ?>">
							<?php esc_html_e('Edit Inventory', 'apartment_mgt'); ?></a>  
							<?php 
							}
							else 
							{ ?>
								<a href="?page=amgt-assets-inventory&tab=add_inventory" class="nav-tab <?php echo $active_tab == 'add_inventory' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Inventory', 'apartment_mgt'); ?></a>
							<?php  } ?>
						</h2> <!-----NAV-TAB-WRAPPER----->
						 <?php 
						//ASSETS LIST TAB
						if($active_tab == 'assets_list')
						{ ?>
						<script type="text/javascript">
					$(document).ready(function() {
						"use strict";
						jQuery('#assets_list').DataTable({
							"responsive": true,
							"order": [[ 0, "asc" ]],
							"aoColumns":[
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
					    <!--ASSETS INVENTORY LIST-->
						<form name="activity_form" action="" method="post"><!--ACTIVITY_FORM---->
							<div class="panel-body"><!--PANEL BODY-->
								<div class="table-responsive"><!---TABLE-RESPONSIVE--->
									<table id="assets_list" class="display" cellspacing="0" width="100%">
										<thead>
											<tr>
											    <th><?php esc_html_e('Asset No', 'apartment_mgt' ) ;?></th>
											    <th><?php  esc_html_e('Facility Name', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Asset Name', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Asset Category', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Asset Date', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Asset Cost','apartment_mgt'); echo ' '. '('.     amgt_get_currency_symbol(get_option( 'apartment_currency_code')).')';?></th>
												<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
											</tr>
										</thead>
										<tfoot>
											<tr>
											    <th><?php esc_html_e('Asset No', 'apartment_mgt' ) ;?></th>
											    <th><?php  esc_html_e('Facility Name', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Asset Name', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Asset Category', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Asset Date', 'apartment_mgt' ) ;?></th>
												<th><?php esc_html_e('Asset Cost','apartment_mgt'); echo ' '. '('. amgt_get_currency_symbol(get_option( 'apartment_currency_code')).')';?></th>
												<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
											</tr>
										</tfoot>
										<tbody>
											 <?php 
												$assetsdata=$obj_assets->amgt_get_all_assets();
											 if(!empty($assetsdata))
											 {
												foreach ($assetsdata as $retrieved_data){ ?>
												<tr>
                                                    <td class="name"><a href="?page=amgt-assets-inventory&tab=add_assets&action=edit&assets_id=<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_html($retrieved_data->assets_no);?></a></td>												
												    <td class="facility_name"><?php echo esc_html($retrieved_data->location);?></td>
													<td class="assets_name"><?php echo esc_html($retrieved_data->assets_name);?></td>
													<td class="assets_cat"><?php echo get_the_title($retrieved_data->assets_cat_id);?></td>
													<td class="assets_cat"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->purchage_date));?></td>
													<td class="assets_cat"><?php echo esc_html($retrieved_data->assets_cost);?></td>
													<td class="action">
												   <a href="?page=amgt-assets-inventory&tab=add_assets&action=edit&assets_id=<?php echo esc_attr($retrieved_data->id)?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
												 
													<a href="?page=amgt-assets-inventory&tab=assets_list&action=delete&assets_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
													onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
													<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
													
													</td>
												</tr>
												<?php } 
											
										}?>
									 
										</tbody>
									
									</table>
							    </div>
							</div><!--END PANEL BODY-->
						   
					    </form><!--END ACTIVITY_FORM---->
						<?php }
							if($active_tab == 'add_assets')
							 { 
								require_once AMS_PLUGIN_DIR.'/admin/assets-inventory/add-assets.php';
							 }
							if($active_tab == 'inventory_list')
							 { 
								require_once AMS_PLUGIN_DIR.'/admin/assets-inventory/inventory-list.php';
							 }						 
							if($active_tab == 'add_inventory')
							 { 
								require_once AMS_PLUGIN_DIR.'/admin/assets-inventory/add-inventory.php';
							 } 	 ?>
                    </div><!--END PANEL BODY-->
	            </div><!--<!--END PANEL-WHITE-->
	        </div>
        </div>
    </div><!--MAIN WRAPPER-->
</div><!-- END INNER PAGE DIV -->