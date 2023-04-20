<?php 
$active_tab = isset($_GET['tab'])?$_GET['tab']:'staff-memberlist';
$obj_member=new Amgt_Member;
$obj_units=new Amgt_ResidentialUnit;
?>
<!-- POP up code -->
<div class="popup-bg">
    <div class="overlay-content">
		<div class="modal-content">
		  <div class="category_list"></div>
		</div>
    </div> 
</div>
<!-- End POP-UP Code -->
<div class="page-inner min_height_1088"><!-- PAGE INNER DIV -->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>
<?php 
//----------------- SAVE STAFF MEMBER -----------------------//
	if(isset($_POST['save_staff_member']))		
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_staff_member_nonce' ) )
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
						wp_redirect ( admin_url().'admin.php?page=amgt-staff_member&tab=staff-memberlist&message=2');
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
						wp_redirect ( admin_url().'admin.php?page=amgt-staff_member&tab=staff-memberlist&message=1');
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
				wp_redirect ( admin_url().'admin.php?page=amgt-staff_member&tab=staff-memberlist&message=3');
			}
		}
		if(isset($_REQUEST['message']))
	     {
		   $message =$_REQUEST['message'];
			if($message == 1)
			{ ?>
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
	
	<div id="main-wrapper">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white">
					<div class="panel-body">
							<h2 class="nav-tab-wrapper">
								<a href="?page=amgt-staff_member&tab=staff-memberlist" class="nav-tab <?php echo $active_tab == 'staff-memberlist' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Staff Member List', 'apartment_mgt'); ?></a>
								
								<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
								{ ?>
								<a href="?page=amgt-staff_member&tab=add_staffmember&action=edit&member_id=<?php echo $_REQUEST['member_id'];?>" class="nav-tab <?php echo $active_tab == 'add_staffmember' ? 'nav-tab-active' : ''; ?>">
								<?php esc_html_e('Edit Staff Member', 'apartment_mgt'); ?></a>  
								<?php 
								}
								else 
								{ ?>
									<a href="?page=amgt-staff_member&tab=add_staffmember" class="nav-tab <?php echo $active_tab == 'add_staffmember' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Staff Member', 'apartment_mgt'); ?></a>
								<?php  }?>
							</h2>
							 <?php 
							//Report 1 
							if($active_tab == 'staff-memberlist')
							 { ?>	
								<script type="text/javascript">
								   $(document).ready(function() {
									"use strict";
									jQuery('#member_list').DataTable({
										"responsive": true,
										"order": [[ 1, "asc" ]],
										"aoColumns":[
													  {"bSortable": false},
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
							<form name="member_form" action="" method="post">
								<div class="panel-body">
									<div class="table-responsive">
								<table id="member_list" class="display" cellspacing="0" width="100%">
									 <thead>
									   <tr>
										  <th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
										  <th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
										  <th><?php esc_html_e('Badge ID', 'apartment_mgt' ) ;?></th>
										  <th><?php esc_html_e('Staff Role', 'apartment_mgt' ) ;?></th>
										  <th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
										  <th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
										  <th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
									  </tr>
									</thead>
									<tfoot>
										<tr>
										  <th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
										  <th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
										  <th><?php esc_html_e('Badge ID', 'apartment_mgt' ) ;?></th>
										  <th><?php esc_html_e('Staff Role', 'apartment_mgt' ) ;?></th>
										  <th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
										  <th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
										  <th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
										</tr>
								   
								</tfoot>
								<tbody>
									<?php 
									$get_members = array('role' => 'staff_member');
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
									   
											<td class="name"><a href="?page=amgt-staff_member&tab=add_staffmember&action=edit&member_id=<?php echo esc_attr($retrieved_data->ID);?>"><?php echo esc_html($retrieved_data->display_name);?></a></td>
											<td class="bnumber"><?php echo esc_html($retrieved_data->badge_id);?></td>
											<td class="staff-cat"><?php $staff_cat = get_post($retrieved_data->staff_category); echo esc_html($staff_cat->post_title);?></td>
											<td class="email"><?php echo esc_html($retrieved_data->user_email);?></td>
											<td class="mobile"><?php echo esc_html($retrieved_data->mobile);?></td>
											<td class="action">
									   
												<a href="?page=amgt-staff_member&tab=add_staffmember&action=edit&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
												<a href="?page=amgt-staff_member&tab=staff-memberlist&action=delete&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-danger" 
												   onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
												  <?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
										   </td>
									   
										</tr>
									<?php } 
								}?>
							 
								 </tbody>
								  </table>
							    </div>
							  </div>
						    </form>
					   <?php 
						}
					if($active_tab == 'add_staffmember')
					{
						require_once AMS_PLUGIN_DIR.'/admin/staff-member/add_staff_member.php';
					} ?>
                    </div>
	            </div>
	        </div>
        </div>
	</div>
</div><!--END  PAGE INNER DIV -->