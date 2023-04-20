<?php 
 //-------- CHECK BROWSER JAVA SCRIPT ----------//
MJamgt_browser_javascript_check(); 
//--------------- ACCESS WISE ROLE -----------//
$user_access=amgt_get_userrole_wise_access_right_array();
if (isset ( $_REQUEST ['page'] ))
{	
	if($user_access['view']=='0')
	{	
		MJamgt_access_right_page_not_access_message();
		die;
	}
	if(!empty($_REQUEST['action']))
	{
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='edit'))
		{
			if($user_access['edit']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}			
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='delete'))
		{
			if($user_access['delete']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}	
		}
		if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $user_access['page_link'] && ($_REQUEST['action']=='insert'))
		{
			if($user_access['add']=='0')
			{	
				MJamgt_access_right_page_not_access_message();
				die;
			}	
		} 
	}
}
$curr_user_id=get_current_user_id();
$obj_apartment=new Apartment_management($curr_user_id);
$obj_service =new Amgt_Service;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'service-list';
if(isset($_POST['save_service']))	//SAVE SERVICE	
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_service_nonce' ) )
		{
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
				
			$result=$obj_service->amgt_add_service($_POST);
		
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=services&tab=service-list&message=2');
			}
		}
		else
		{
			
			$result=$obj_service->amgt_add_service($_POST);
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=services&tab=service-list&message=1');
			}
		}
	}
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			
			$result=$obj_service->amgt_delete_service($_REQUEST['service_id']);
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=services&tab=service-list&message=3');
			}
		}

	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
				<div id="message" class="updated below-h2 ">
				<p>
				<?php 
					esc_html_e('Service inserted successfully','apartment_mgt');
				?></p></div>
				<?php 
			
	    }
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 "><p><?php
					_e("Service updated successfully.",'apartment_mgt');
					?></p>
					</div>
				<?php 
			
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			esc_html_e('Service deleted successfully','apartment_mgt');
		?></div></p><?php
				
		}
	}
	?>
<!-- POP UP CODE -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
		    <div class="category_list"></div>
	   </div>
    </div> 
</div>
<!-- End POP UP CODE -->
<div class="panel-body panel-white"><!-- PANEL BODY DIV -->
    <ul class="nav nav-tabs panel_tabs" role="tablist"><!--TABLIST-->
		<li class="<?php if($active_tab=='service-list'){?>active<?php }?>">
			<a href="?apartment-dashboard=user&page=services&tab=service-list" class="tab <?php echo $active_tab == 'service-list' ? 'active' : ''; ?>">
			 <i class="fa fa-align-justify"></i> <?php esc_html_e('Service List', 'apartment_mgt'); ?></a>
		  </a>
		</li>
	    <li class="<?php if($active_tab=='add_service'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['service_id']))
			{ ?>
				<a href="?apartment-dashboard=user&page=services&tab=add_service&action=edit&service_id=<?php echo $_REQUEST['service_id'];?>" class="nav-tab <?php echo $active_tab == 'add_service' ? 'nav-tab-active' : ''; ?>">
				<i class="fa fa"></i> <?php esc_html_e('Edit Service', 'apartment_mgt'); ?></a>
			 <?php 
			}
			else
			{ 
				if($user_access['add']=='1')
				{ ?>
					<a href="?apartment-dashboard=user&page=services&tab=add_service" class="tab <?php echo $active_tab == 'add_service' ? 'active' : ''; ?>">
					<i class="fa fa-plus-circle"></i> <?php esc_html_e('Add Service', 'apartment_mgt'); ?></a>
	    <?php 
				}
			} ?>
	   </li>
	</ul><!--END TABLIST-->
	<div class="tab-content"><!--TAB CONTENT-->
	<?php if($active_tab == 'service-list')
		//SERVICE LIST TAB
	{ ?>
		<script type="text/javascript">
		$(document).ready(function() {
			"use strict";
			jQuery('#service_list').DataTable({
				"responsive":true,
				"order": [[ 1, "asc" ]],
				"aoColumns":[
							  {"bSortable": false},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							   <?php  
								if($obj_apartment->role !=='member' AND $obj_apartment->role !=='accountant' AND $obj_apartment->role !=='gatekeeper')
									{  
									?>
								  ,{"bSortable": false}
								 <?php  
								 } 
								 ?> 												  
							   ],	
							  language:<?php echo amgt_datatable_multi_language();?>
				});
		} );
		</script>
    	<div class="panel-body"><!--PANEL BODY DIV--> 
        	<div class="table-responsive"><!--TABLE RESPONSIVE--> 
				<table id="service_list" class="display" cellspacing="0" width="100%"><!--SERVICE_LIST TABLE-->
					<thead>
						<tr>
							<th><?php  esc_html_e('Service Name', 'apartment_mgt' ) ;?></th>
							  <th><?php esc_html_e('Service Provider', 'apartment_mgt' ) ;?></th>
							  <th><?php esc_html_e('Contact Number', 'apartment_mgt' ) ;?></th>
							   <th><?php esc_html_e('Mobile Number', 'apartment_mgt' ) ;?></th>
							  <th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
							  <th> <?php esc_html_e('Address', 'apartment_mgt' ) ;?></th>
							<?php 
							if($obj_apartment->role !=='member' AND $obj_apartment->role !=='accountant'  AND $obj_apartment->role !=='gatekeeper')
									{  ?>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
							<?php
							}
							?>
						</tr>
				    </thead>
					<tfoot>
						<tr>
							<th><?php  esc_html_e('Service Name', 'apartment_mgt' ) ;?></th>
						  <th><?php esc_html_e('Service Provider', 'apartment_mgt' ) ;?></th>
						  <th><?php esc_html_e('Contact Number', 'apartment_mgt' ) ;?></th>
						   <th><?php esc_html_e('Mobile Number', 'apartment_mgt' ) ;?></th>
						  <th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
						  <th> <?php esc_html_e('Address', 'apartment_mgt' ) ;?></th>
						<?php 
						if($obj_apartment->role !=='member' AND $obj_apartment->role !=='accountant'  AND $obj_apartment->role !=='gatekeeper')
									{ ?>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						<?php
						}
						?>
						</tr>
					</tfoot>
					<tbody>
					<?php 
					$user_id=get_current_user_id();
					//--- SERVICES DATA FOR MEMBER  ------//
					if($obj_apartment->role=='member')
					{
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{
							$service_data= $obj_service->amgt_get_own_service($user_id);
						}
						else
						{
							$service_data= $obj_service->amgt_get_all_service();
						}
					} 
					//--- SERVICES DATA FOR STAFF MEMBER  ------//
					elseif($obj_apartment->role=='staff_member')
					{
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{  
							$service_data= $obj_service->amgt_get_own_service($user_id);
						}
						else
						{
							$service_data= $obj_service->amgt_get_all_service();
						}
					}
					//--- SERVICES DATA FOR ACCOUNTANT  ------//
					elseif($obj_apartment->role=='accountant')
					{
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{ 
							$service_data= $obj_service->amgt_get_own_service($user_id);
						}
						else
						{
							$service_data= $obj_service->amgt_get_all_service();
						}
					}
					//--- SERVICES DATA FOR GATEKEEPER  ------//
					else
					{
						$own_data=$user_access['own_data'];
						if($own_data == '1')
						{ 
							$service_data= $obj_service->amgt_get_own_service($user_id);
						}
						else
						{
							$service_data= $obj_service->amgt_get_all_service();
						}
					}
					
					if(!empty($service_data))
					{
						foreach ($service_data as $retrieved_data)
						{ ?>
						<tr>
							  <td class="service_name"><?php echo $retrieved_data->service_name;?></td>
							  <td class="service_name"><?php echo $retrieved_data->service_provider;?></td>
							  <td class="service_name"><?php echo $retrieved_data->contact_number;?></td>
							  <td class="service_name"><?php echo $retrieved_data->mobile_number;?></td>
							  <td class="service_name"><?php echo $retrieved_data->email;?></td>
							  <td class="service_name"><?php echo wp_trim_words($retrieved_data->address,5);?></td>
							<?php 
						if($obj_apartment->role !=='member' AND $obj_apartment->role !=='accountant'  AND $obj_apartment->role !=='gatekeeper')
						{  ?>
							<td class="action">
								<?php
								if($user_access['edit']=='1')
								{  ?>
									<a href="?apartment-dashboard=user&page=services&tab=add_service&action=edit&service_id=<?php echo $retrieved_data->service_id?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
									<?php
								}
								if($user_access['delete']=='1')
								{
								?>
									<a href="?apartment-dashboard=user&page=services&tab=service-list&action=delete&service_id=<?php echo $retrieved_data->service_id;?>" class="btn btn-danger" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
							<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
								<?php
								}
								?>
							</td>
						<?php
						}?>
						</tr>
						<?php
						} 

					}?>
					</tbody>
			    </table><!--END SERVICE_LIST TABLE-->
            </div><!--END TABLE RESPONSIVE-->
        </div>	
		<?php 
	}
	if($active_tab == 'add_service')
	{ 
	  require_once AMS_PLUGIN_DIR.'/template/services/add_services.php' ;
	}
		?>
	
	</div>
</div><!-- END PANEL BODY DIV -->
<?php ?>