<?php 
$active_tab = isset($_GET['tab'])?$_GET['tab']:'gatekeeper_list';
$obj_member=new Amgt_Member;
$obj_gate=new Amgt_gatekeeper;
$gatedata=$obj_gate->Amgt_get_all_gates();
?>
<!-- POP UP CODE -->
<div class="popup-bg">
    <div class="overlay-content">
      <div class="modal-content">
        <div class="category_list"></div>
	  </div>
    </div> 
</div>
<!-- END POP-UP CODE -->
<div class="page-inner min_height_1088"><!-- INNER PAGE DIV -->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>
<?php 
	if(isset($_POST['save_gatekeeper']))		
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_gatekeeper_nonce' ) )
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$imagurl=$_POST['amgt_user_avatar'];
				$ext=amgt_check_valid_extension($imagurl);
				if(!$ext == 0)
				{
					$result=$obj_member->amgt_add_member($_POST);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=amgt-gatekeeper&tab=gatekeeper_list&message=2');
					}
				}
				else
					{ ?>
						<div id="message" class="updated below-h2 notice is-dismissible">
							<p><p><?php esc_html_e('Sorry, only JPG, JPEG, PNG & GIF files are allowed!.','apartment_mgt');?></p></p>
						</div>
			   <?php }
			}
			else
			{
				if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] )) {
					$imagurl=$_POST['amgt_user_avatar'];
					$ext=amgt_check_valid_extension($imagurl);
					if(!$ext == 0)
					{
					 $result=$obj_member->amgt_add_member($_POST);
						if($result)
						{
							wp_redirect ( admin_url().'admin.php?page=amgt-gatekeeper&tab=gatekeeper_list&message=1');
						}
					}
					else
					{ ?>
						<div id="message" class="updated below-h2 notice is-dismissible">
							<p><p><?php esc_html_e('Sorry, only JPG, JPEG, PNG & GIF files are allowed!.','apartment_mgt');?></p></p>
						</div>
			   <?php }
				}
				else
				{ ?>
					<div id="message" class="updated below-h2 notice is-dismissible">
					<p><p><?php esc_html_e('Username Or Emailid Already Exist.','apartment_mgt');?></p></p>
					</div>
							
		  <?php }		
			}
		}
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			
			$result=$obj_member->amgt_delete_usedata($_REQUEST['member_id']);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-gatekeeper&tab=gatekeeper_list&message=3');
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
	    }
	?>
	 
	<div id="main-wrapper"><!-----MAIN-WRAPPER-------->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
					<div class="panel-body"><!--PANEL BODY-->
						<h2 class="nav-tab-wrapper">
							<a href="?page=amgt-gatekeeper&tab=gatekeeper_list" class="nav-tab <?php echo $active_tab == 'gatekeeper_list' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Gatekeeper List', 'apartment_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
							{ ?>
							<a href="?page=amgt-gatekeeper&tab=add_gatekeeper&action=edit&member_id=<?php echo $_REQUEST['member_id'];?>" class="nav-tab <?php echo $active_tab == 'add_gatekeeper' ? 'nav-tab-active' : ''; ?>">
							<?php esc_html_e('Edit Gatekeeper', 'apartment_mgt'); ?></a>  
							<?php 
							}
							else 
							{ ?>
								<a href="?page=amgt-gatekeeper&tab=add_gatekeeper" class="nav-tab <?php echo $active_tab == 'add_gatekeeper' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Gatekeeper', 'apartment_mgt'); ?></a>
							<?php  }?>
						</h2>
						 <?php 
						//Report 1 
						if($active_tab == 'gatekeeper_list')
						{ ?>	
						<script type="text/javascript">
							$(document).ready(function() {
							"use strict";
							jQuery('#member_list').DataTable({
								"responsive":true,
								"order": [[ 1, "asc" ]],
								"aoColumns":[
											  {"bSortable": false},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": true},
											  {"bSortable": false}],
											  language:<?php echo amgt_datatable_multi_language();?>
								});
						} );
					</script>
					<form name="member_form" action="" method="post">

						<div class="panel-body"><!--PANEL BODY-->
							<div class="table-responsive"><!--TABLE RESPONSIVE---->
									<table id="member_list" class="display" cellspacing="0" width="100%">
										 <thead>
											<tr>
												  <th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
												  <th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
												  <th><?php esc_html_e('Assigned Gate', 'apartment_mgt' ) ;?></th>
												  <th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
												  <th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
												  <th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
											</tr>
										</thead>
										 <tfoot>
											<tr>
												  <th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
												  <th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
												  <th><?php esc_html_e('Assigned Gate', 'apartment_mgt' ) ;?></th>
												  <th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
												  <th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
												  <th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
											</tr>
										</tfoot>
										<tbody>
										 <?php 
											$get_members = array('role' => 'gatekeeper');
											$membersdata=get_users($get_members);
											 if(!empty($membersdata))
											 {
												foreach ($membersdata as $retrieved_data){ ?>
												 <tr>
														<td class="user_image"><?php $uid=$retrieved_data->ID;
																$userimage=get_user_meta($uid, 'amgt_user_avatar', true);
															if(empty($userimage))
															{
																echo '<img src='.get_option( 'amgt_system_logo' ).' height="50px" width="50px" class="img-circle" />';
															}
															else
																echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
													 ?></td>
														<td class="name"><a href="?page=amgt-gatekeeper&tab=add_gatekeeper&action=edit&member_id=<?php echo esc_attr($retrieved_data->ID);?>"><?php echo esc_html($retrieved_data->display_name);?></a></td>
														<td class="gate"><?php echo amgt_get_gate_name($retrieved_data->aasigned_gate);?></td>
														<td class="email"><?php echo esc_html($retrieved_data->user_email);?></td>
														<td class="mobile"><?php echo esc_html($retrieved_data->mobile);?></td>
														<td class="action">
															<a href="?page=amgt-gatekeeper&tab=add_gatekeeper&action=edit&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
															<a href="?page=amgt-gatekeeper&tab=gatekeeper_list&action=delete&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-danger" 
															onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
															<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
														</td>
											   
												</tr>
											<?php } 
											
											}?>
									 
										 </tbody>
									</table>
							</div><!--END TABLE RESPONSIVE-->
						</div><!--END PANEL BODY-->
					   
					</form>
					 <?php 
					}
					if($active_tab == 'add_gatekeeper')
					{
						require_once AMS_PLUGIN_DIR.'/admin/gatekeeper/add_gatekeeper.php';
					} ?>
                </div><!--END PANEL BODY-->
	        </div>
	    </div>
    </div>
  </div>
  <!-----End main-wrapper-------->
</div><!-- END INNER PAGE DIV -->