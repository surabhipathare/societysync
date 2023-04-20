<?php 
$active_tab = isset($_GET['tab'])?$_GET['tab']:'tax-list';
$obj_tax =new Amgt_Tax;
?>
<div class="page-inner min_height_1088"><!-- INNER PAGE DIV -->
	<div class="page-title"><!-- PAGE TITLE -->
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
    </div>
<?php 
	if(isset($_POST['save_tax']))//SAVE TAX 		
	{	
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_tax_nonce' ))
		{	
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{				
				$result=$obj_tax->amgt_add_tax($_POST);		
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-tax&tab=tax-list&message=2');
				}
			}
			else
			{			
				$result=$obj_tax->amgt_add_tax($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-tax&tab=tax-list&message=1');
				}
			}
		}
	}
	
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')//DELETE TAX
	{
				
		$result=$obj_tax->amgt_delete_tax($_REQUEST['tax_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=amgt-tax&tab=tax-list&message=3');
		}
	}
	
	if(isset($_REQUEST['message']))//MESSAGE
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{ ?>
			<div id="message" class="updated below-h2 notice is-dismissible">
			<p>
				<?php esc_html_e('Tax inserted successfully','apartment_mgt'); ?>
			</p>
			</div>
		<?php 			
		}
		elseif($message == 2){ ?>
			<div id="message" class="updated below-h2 notice is-dismissible"><p>
				<?php _e("Tax updated successfully.",'apartment_mgt');?></p>
			</div>
		<?php 			
		}
		elseif($message == 3) { ?>
			<div id="message" class="updated below-h2 notice is-dismissible"><p>
				<?php 	esc_html_e('Tax deleted successfully','apartment_mgt');?>
			</div></p>
		<?php				
		}
	}
	?>
	
	<div id="main-wrapper"><!--MAIN WRAPPER DIV-->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white"><!--PANEL WHITE -->
					<div class="panel-body"><!-- PANEL BODY -->
						<h2 class="nav-tab-wrapper"><!--NAV TAB WRAPPER -->
							<a href="?page=amgt-tax&tab=tax-list" 
							class="nav-tab <?php echo $active_tab == 'tax-list' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Tax List', 'apartment_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{ ?>
							<a href="?page=amgt-tax&tab=add_tax&action=edit&tax_id=<?php echo $_REQUEST['tax_id'];?>" class="nav-tab <?php echo $active_tab == 'add_tax' ? 'nav-tab-active' : ''; ?>">
							<?php esc_html_e('Edit Tax', 'apartment_mgt'); ?></a>  
							<?php 
							}
							else 
							{ ?>
								<a href="?page=amgt-tax&tab=add_tax" class="nav-tab <?php echo $active_tab == 'add_tax' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Tax', 'apartment_mgt'); ?></a>
							<?php  }?>
						</h2><!--END NAV TAB WRAPPER -->
							 <?php 
					//TAX LIST TAB
					if($active_tab == 'tax-list')
						{ ?>	
							<script type="text/javascript">
							$(document).ready(function() {
								//TAX LIST
								"use strict";
								jQuery('#tax_list').DataTable({
									"responsive":true,
									"order": [[ 0, "asc" ]],
									"aoColumns":[
										{"bSortable": true},
										{"bSortable": true},
										{"bSortable": false}],
										language:<?php echo amgt_datatable_multi_language();?>
									});
							});
							</script>
							<form name="member_form" action="" method="post"><!--MEMBER FORM -->
							
								<div class="panel-body"><!--PANEL BODY -->
									<div class="table-responsive"><!--TABLE RESPONSIVE -->
										<table id="tax_list" class="display" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th><?php  esc_html_e('Tax Title', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Tax(%)', 'apartment_mgt' ) ;?></th>
													<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th><?php  esc_html_e('Tax Title', 'apartment_mgt' ) ;?></th>
													<th><?php esc_html_e('Tax(%)', 'apartment_mgt' ) ;?></th>
													<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
												</tr>
											   
											</tfoot>
											<tbody>
											 <?php 
												$tax_data= $obj_tax->Amgt_get_all_tax();
												if(!empty($tax_data))
												   {
														foreach ($tax_data as $retrieved_data)
														{ ?>
															<tr>
																  <td class="service_name"><?php echo esc_html($retrieved_data->tax_title);?></td>
																  <td class="service_name"><?php echo esc_html($retrieved_data->tax);?></td>
																  <td class="action">
																	<a href="?page=amgt-tax&tab=add_tax&action=edit&tax_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
																	<a href="?page=amgt-tax&tab=tax-list&action=delete&tax_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
																	onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
																	<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
																   </td>
															   
															</tr>
														<?php } 
												
													}?>
										 
											</tbody>
										
										</table>
								    </div><!--END TABLE RESPONSIVE -->
								</div>
							   
						    </form><!--MEMBER FORM END-->
							 <?php 
					    }
						if($active_tab == 'add_tax')
							{		
								require_once AMS_PLUGIN_DIR.'/admin/tax/add_tax.php';
							} ?>
					</div><!-- END PANEL BODY -->
									
				</div><!--END PANEL WHITE -->
			</div>
		</div>
	</div>
</div><!-- END INNER PAGE DIV -->