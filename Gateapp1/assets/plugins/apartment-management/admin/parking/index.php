<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'sloat-list';
$obj_parking=new Amgt_Parking;
$obj_units=new Amgt_ResidentialUnit;
?>
<script type="text/javascript">
$(document).ready(function() {
	//SLOT LIST DATATABLE
	"use strict";
	jQuery('#sloat_list').DataTable({
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

<div class="page-inner min_height_1088"><!---PAGE INNER---->		
	<div class="page-title"><!---PAGE TITLE---->		
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>
<?php 
	if(isset($_POST['save_sloat']))	//SAVE SLOT	
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_sloat_nonce' ) )
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$result=$obj_parking->amgt_add_sloat($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-parking-mgt&tab=sloat-list&message=2');
				}
			}
			else
			{
				$result=$obj_parking->amgt_add_sloat($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-parking-mgt&tab=sloat-list&message=1');
				}
			}
		}
	}
	if(isset($_POST['assign_sloat']))//ASSIGN SLOT	
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'assign_sloat_nonce' ) )
		{
		$slaot_id = $_POST['sloat_id'];
		$from_date =amgt_get_format_for_db($_POST['from_date']);
		$to_date = amgt_get_format_for_db($_POST['to_date']);
		
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			global $wpdb;
			$table_name = $wpdb->prefix. 'amgt_parking';
			
			$sloat_assign_id = $_POST['sloat_assign_id'];
			
			$result_allready_assigned = $wpdb->get_results("SELECT * FROM $table_name where (sloat_id=$slaot_id) AND (((from_date BETWEEN '$from_date' AND '$to_date') AND (to_date BETWEEN '$from_date' AND '$to_date')) OR (('$from_date' BETWEEN from_date AND to_date) OR ('$to_date' BETWEEN from_date AND to_date))) AND (id<>$sloat_assign_id)");		
			
			if(!empty($result_allready_assigned))
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-parking-mgt&tab=assign_sloat&action=edit&sloat_assign_id='.$_REQUEST['sloat_assign_id'].'&message=4');
			}
			else
			{
				$result=$obj_parking->amgt_assign_sloat($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-parking-mgt&tab=assigned-sloat-list&message=6');
				}
			}
		}
		else
		{
			global $wpdb;
			$table_name = $wpdb->prefix. 'amgt_parking';
			
			$result_allready_assigned = $wpdb->get_results("SELECT * FROM $table_name where (sloat_id=$slaot_id) AND (((from_date BETWEEN '$from_date' AND '$to_date') AND (to_date BETWEEN '$from_date' AND '$to_date')) OR (('$from_date' BETWEEN from_date AND to_date) OR ('$to_date' BETWEEN from_date AND to_date)))");		
		
			if(!empty($result_allready_assigned))
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-parking-mgt&tab=assign_sloat&message=4');
			}
			else
			{
				$result=$obj_parking->amgt_assign_sloat($_POST);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-parking-mgt&tab=assigned-sloat-list&message=5');
				}
			}	
		}	
	}
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')//DELETE SLOT
	{
			if(isset($_REQUEST['sloat_id']))
			{
				$result=$obj_parking->amgt_delete_sloat($_REQUEST['sloat_id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-parking-mgt&tab=sloat-list&message=3');
				}
			}
			if(isset($_REQUEST['sloat_assign_id']))
			{
				$result=$obj_parking->amgt_delete_assigned_sloat($_REQUEST['sloat_assign_id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-parking-mgt&tab=assigned-sloat-list&message=3');
				}
			}
			
	}	
	
		
	if(isset($_REQUEST['message']))//MESSAGE
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
				<div id="message" class="updated below-h2 notice is-dismissible">
				<p>
				<?php 
					esc_html_e('Slot inserted successfully','apartment_mgt');
				?></p></div>
				<?php
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
					_e("Slot updated successfully.",'apartment_mgt');
					?></p>
					</div>
				<?php 
			
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Slot deleted successfully','apartment_mgt');?>
		</div></p>
		<?php
				
		}
		elseif($message == 4) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('This Parking Slot Already Assigned','apartment_mgt');?>
		</div></p>
		<?php
				
		}
		if($message == 5)
		{?>
				<div id="message" class="updated below-h2 notice is-dismissible">
				<p>
				<?php 
					esc_html_e('Slot Assign inserted successfully','apartment_mgt');
				?></p></div>
				<?php
			
		}
		elseif($message == 6)
		{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
					_e("Slot Assign updated successfully.",'apartment_mgt');
					?></p>
					</div>
				<?php 
			
		}
		
	}?>
	<div id="main-wrapper"><!--MAIN WRAPPER-->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white"><!--PANEL-WHITE-->
					<div class="panel-body"><!--PANEL BODY-->
						<h2 class="nav-tab-wrapper"><!--NAV TAB WRAPPER---->
							<a href="?page=amgt-parking-mgt&tab=sloat-list" class="nav-tab <?php echo $active_tab == 'sloat-list' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Slot List', 'apartment_mgt'); ?></a>
							
							
							 <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['sloat_id']))
							{ ?>
									<a href="?page=amgt-parking-mgt&tab=add_sloat&action=edit&sloat_id=<?php echo $_REQUEST['sloat_id']?>" class="nav-tab <?php echo $active_tab == 'add_sloat' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Edit Slot', 'apartment_mgt'); ?></a>
							<?php } 
							else
							{ ?>
							<a href="?page=amgt-parking-mgt&tab=add_sloat" class="nav-tab <?php echo $active_tab == 'add_sloat' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Slot', 'apartment_mgt'); ?></a>  
							<?php } ?>
							<a href="?page=amgt-parking-mgt&tab=assigned-sloat-list" class="nav-tab <?php echo $active_tab == 'assigned-sloat-list' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Assigned Slot List', 'apartment_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && $_REQUEST['tab']=='assign_sloat')
							{ ?>
									<a href="?page=amgt-parking-mgt&tab=assign_sloat&action=edit&sloat_assign_id=<?php echo $_REQUEST['sloat_assign_id']?>" class="nav-tab <?php echo $active_tab == 'assign_sloat' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Edit Sloat Assigned', 'apartment_mgt'); ?></a>
							<?php } 
							else
							{ ?>
							<a href="?page=amgt-parking-mgt&tab=assign_sloat" class="nav-tab <?php echo $active_tab == 'assign_sloat' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Assign Slot', 'apartment_mgt'); ?></a>  
							<?php } ?>
							
							
						</h2>
						<!--END NAV TAB WRAPPER-->
     <?php 
    //SLOT LIST TAB
	if($active_tab == 'sloat-list')
	{ ?>


		<div class="panel-body"><!--PANEL BODY-->
        	<div class="table-responsive"><!---TABLE-RESPONSIVE--->
        <table id="sloat_list" class="display" cellspacing="0" width="100%"><!---SLOT LIST TABLE--->
        	 <thead>
            <tr>
				<th><?php esc_html_e('Slot No', 'apartment_mgt' ) ;?></th>
				<th><?php esc_html_e('Slot Type', 'apartment_mgt' ) ;?></th>
				<th><?php esc_html_e('Comment', 'apartment_mgt' ) ;?></th>
				<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
				<th><?php esc_html_e('Slot No', 'apartment_mgt' ) ;?></th>
				<th><?php esc_html_e('Slot Type', 'apartment_mgt' ) ;?></th>
				<th><?php esc_html_e('Comment', 'apartment_mgt' ) ;?></th>
				<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
            </tr>
           
        </tfoot>
 
        <tbody>
         <?php 
		
			$sloatdata=$obj_parking->amgt_get_all_sloats();
		 if(!empty($sloatdata))
		 {
			foreach ($sloatdata as $retrieved_data){ ?>
            <tr>
				<td class="sloatname"><a href="?page=amgt-parking-mgt&tab=add_sloat&action=edit&sloat_id=<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_html($retrieved_data->sloat_name);?></a></td>
				<td class="sloattype"><?php if($retrieved_data->sloat_type=='guest') echo esc_html__('Guest','apartment_mgt'); else echo esc_html__('Member','apartment_mgt');?></td>
				<td class="comment"><?php echo esc_html($retrieved_data->comment);?></td>
				<td class="action">
					<a href="?page=amgt-parking-mgt&tab=add_sloat&action=edit&sloat_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
					<a href="?page=amgt-parking-mgt&ab=sloat-list&action=delete&sloat_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
					onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
					<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
               
                </td>
               
            </tr>
            <?php 
			} 
			
		} ?>
     
        </tbody>
        
        </table><!---END SLOT LIST TABLE--->
        </div>
        </div><!---END PANEL BODY--->
<?php }

	if($active_tab == 'add_sloat')
	 {
		require_once AMS_PLUGIN_DIR.'/admin/parking/add-sloat.php';
	 }
	
	 if($active_tab == 'assigned-sloat-list')
	 {
		require_once AMS_PLUGIN_DIR.'/admin/parking/assigned-sloat-list.php';
	 }

	 if($active_tab == 'assign_sloat')
	 {
		require_once AMS_PLUGIN_DIR.'/admin/parking/assign-sloat.php';
	 }?>
</div>
			
	</div><!--END PANEL WHITE-->
	</div>
</div>
</div><!--END MAIN WRAPPER-->
</div><!---END PAGE INNER---->	