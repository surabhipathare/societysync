<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'rule_list';
$obj_complaint=new Amgt_Complaint;
?>

<div class="page-inner min_height_1088">
	<div class="page-title"><!----PAGE TITLE-----> 	
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>
<?php 
//---------------- SAVE COMPLAINT --------------------//
	if(isset($_POST['save_complaint']))		
	{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_complaint->amgt_add_complaint($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-member-corner&tab=rule_list&message=2');
			}
		}
		else
		{
			$result=$obj_complaint->amgt_add_complaint($_POST);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-member-corner&tab=rule_list&message=1');
			}
		}
	}
	
		
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			$result=$obj_complaint->amgt_delete_comlaint($_REQUEST['complaint_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-member-corner&tab=rule_list&message=3');
			}
		}
	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
				<div id="message" class="updated below-h2 notice is-dismissible">
				<p>
				<?php 
					esc_html_e('Record inserted successfully','apartment_mgt');
				?></p></div>
				<?php
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
					_e("Record updated successfully.",'apartment_mgt');
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
	}?>
	
	<div id="main-wrapper"><!--MAIN WRAPPER-->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white"><!--PANEL WHITE-->
					<div class="panel-body"><!--PANEL BODY-->
					     <!--NAV TAB WRAPPER---->
						<h2 class="nav-tab-wrapper">
							<a href="?page=amgt-member-corner&tab=rule_list" class="nav-tab <?php echo $active_tab == 'rule_list' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Society Rules List', 'apartment_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{ ?>
							<a href="?page=amgt-member-corner&tab=add_rule&action=edit&complaint_id=<?php echo $_REQUEST['complaint_id'];?>" class="nav-tab <?php echo $active_tab == 'add_rule' ? 'nav-tab-active' : ''; ?>">
							<?php esc_html_e('Edit Rule', 'apartment_mgt'); ?></a>  
							<?php 
							}
							else 
							{ ?>
								<a href="?page=amgt-member-corner&tab=add_rule" class="nav-tab <?php echo $active_tab == 'add_rule' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Rule', 'apartment_mgt'); ?></a>
							<?php  }?>
						</h2>
						 <!--END NAV TAB WRAPPER---->
     <?php              
    //RULE LIST	TAB
	if($active_tab == 'rule_list')
	{ ?>
    <script type="text/javascript">
$(document).ready(function() {
	"use strict";
	jQuery('#complaint_list').DataTable({
		"order": [[ 0, "asc" ]],
		"aoColumns":[
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": true},
	                  {"bSortable": false}],
					  language:<?php echo amgt_datatable_multi_language();?>
		});
} );
</script>
    <form name="activity_form" action="" method="post"> <!--ACTIVITY FORM---->
       <div class="panel-body"><!--PANEL BODY-->
        	<div class="table-responsive"><!---TABLE-RESPONSIVE--->
        <table id="complaint_list" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>
				<th><?php esc_html_e('Nature', 'apartment_mgt' ) ;?></th>
				<th><?php esc_html_e('Category', 'apartment_mgt' ) ;?></th>
				<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
				<th><?php esc_html_e('Description', 'apartment_mgt' ) ;?></th>
				<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
            </tr>
        </thead>
		<tfoot>
            <tr>
				<th><?php esc_html_e('Nature', 'apartment_mgt' ) ;?></th>
				<th><?php esc_html_e('Category', 'apartment_mgt' ) ;?></th>
				<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
				<th><?php esc_html_e('Description', 'apartment_mgt' ) ;?></th>
				<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
            </tr>
           
        </tfoot>
 
        <tbody>
         <?php 
		
			$complaintsdata=$obj_complaint->amgt_get_all_complaints();
		 if(!empty($complaintsdata))
		 {
			 
		 	foreach ($complaintsdata as $retrieved_data){ ?>
            <tr>
				
                <td class="nature"><a href="?page=amgt-member-corner&tab=add_rule&action=edit&complaint_id=<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_html($retrieved_data->complaint_nature);?></a></td>
				<td class="complaint cat"><?php echo esc_html($retrieved_data->complaint_cat);?></td>
				<td class="status"><?php echo esc_html($retrieved_data->complaint_status);?></td>
				<td class="description"><?php echo wp_trim_words( $retrieved_data->complaint_description,5);?></td>
				<td class="action">
					<a href="?page=amgt-member-corner&tab=add_rule&action=edit&complaint_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
					<a href="?page=amgt-member-corner&tab=Activitylist&action=delete&complaint_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
					onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
					<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
                
                </td>
               
            </tr>
            <?php } 
		}?>
     
        </tbody>
        
        </table>
        </div><!---END TABLE-RESPONSIVE--->
        </div><!---END PANEL BODY--->
       
</form><!--END ACTIVITY FORM---->
<?php 
	}
	if($active_tab == 'add_rule')
	{
		require_once AMS_PLUGIN_DIR.'/admin/member-corner/add_rule.php';
	}
?>
</div><!--END PANEL BODY-->		
	</div>
	</div>
</div>
</div><!--END MAIN WRAPPER-->