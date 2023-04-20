<?php 
$active_tab = isset($_GET['tab'])?$_GET['tab']:'accountantlist';
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
	<div class="page-title"><!-- PAGE TITLE DIV -->
		 <h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>
<?php 
    //SAVE ACOOUNTANT DATA //
	if(isset($_POST['save_accountant']))		
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_accountant_nonce' ) )
		{
		//EDIT  ACOOUNTANT DATA //
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
		$imagurl=$_POST['amgt_user_avatar'];
			  $ext=amgt_check_valid_extension($imagurl);
			    if(!$ext == 0)
			    {	
					$result=$obj_member->amgt_add_member($_POST);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=amgt-accountant&tab=accountantlist&message=2');
					}
				}
				else
				{ ?>
					<div id="message" class="updated below-h2 notice is-dismissible">
						<p><p><?php esc_html_e('Sorry, only JPG, JPEG, PNG & GIF files are allowed!.','apartment_mgt');?></p></p>
					</div>
			   <?php 
			   }
		}
		else
		{
			//ADD ACOOUNTANT DATA //
			if( !email_exists( $_POST['email'] ) && !username_exists( $_POST['username'] ))
			{
				$imagurl=$_POST['amgt_user_avatar'];
			    $ext=amgt_check_valid_extension($imagurl);
			    if(!$ext == 0)
				{
					$result=$obj_member->amgt_add_member($_POST);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=amgt-accountant&tab=accountantlist&message=1');
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
	//----DELETE  ACOOUNTANT----//
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
	{
		
		$result=$obj_member->amgt_delete_usedata($_REQUEST['accountant_id']);
		if($result)
		{
			wp_redirect ( admin_url().'admin.php?page=amgt-accountant&tab=accountantlist&message=3');
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
					?></p>
				</div>
				<?php 
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 notice is-dismissible">
				   <p><?php
					_e("Record updated successfully.",'apartment_mgt');
					?>
					</p>
			</div>
		<?php 
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible">
			<p>
			<?php 
				esc_html_e('Record deleted successfully','apartment_mgt');
			?>
			</p>
		</div><?php
			
		}
	} ?>
	<!--MAIN WRAPPER-->
	<div id="main-wrapper">
		<div class="row">
			<div class="col-md-12"><!--COL MD 12 DIV-->
				<div class="panel panel-white"><!--PANEL WHITE DIV-->
					<div class="panel-body"><!--PANEL BODY-->
							<h2 class="nav-tab-wrapper">
								<a href="?page=amgt-accountant&tab=accountantlist" class="nav-tab <?php echo $active_tab == 'accountantlist' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Accountant List', 'apartment_mgt'); ?></a>
								
								<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
								{ ?>
								<a href="?page=amgt-accountant&tab=add_accountant&action=edit&accountant_id=<?php echo $_REQUEST['accountant_id'];?>" class="nav-tab <?php echo $active_tab == 'add_accountant' ? 'nav-tab-active' : ''; ?>">
								<?php esc_html_e('Edit Accountant', 'apartment_mgt'); ?></a>  
								<?php 
								}
								else 
								{ ?>
									<a href="?page=amgt-accountant&tab=add_accountant" class="nav-tab <?php echo $active_tab == 'add_accountant' ? 'nav-tab-active' : ''; ?>">
								<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Accountant', 'apartment_mgt'); ?></a>
								<?php  } ?>
							</h2>
							<?php 
                            //----ACCOUNTANT LIST DATA---//							
							if($active_tab == 'accountantlist')
							{ ?>	
								<script type="text/javascript">
									$(document).ready(function() {
									"use strict";
									jQuery('#accountant_list').DataTable({
										"responsive":true,
										"order": [[ 1, "asc" ]],
										"aoColumns":[
													  {"bSortable": false},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": true},
													  {"bSortable": false}]
										});
								    } );
							    </script>
								
								<form name="accountant_form" action="" method="post">
									<div class="panel-body">
										<div class="table-responsive">
											<table id="accountant_list" class="display" cellspacing="0" width="100%">
												 <thead>
													<tr>
													  <th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
													  <th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
													  <th><?php esc_html_e('Badge ID', 'apartment_mgt' ) ;?></th>
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
													  <th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
													  <th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
													  <th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
													</tr>
												</tfoot>
									 
												<tbody>
												   <?php 
													$get_members = array('role' => 'accountant');
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
																{
																	echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
																}?>
														</td>
														 <td class="name"><a href="?page=amgt-accountant&tab=add_accountant&action=edit&accountant_id=<?php echo esc_attr($retrieved_data->ID);?>"><?php echo esc_html($retrieved_data->display_name);?></a></td>
														 <td class="bnumber"><?php echo esc_html($retrieved_data->badge_id);?></td>
														
														 <td class="email"><?php echo esc_html($retrieved_data->user_email);?></td>
														 <td class="mobile"><?php echo esc_html($retrieved_data->mobile);?></td>
														 <td class="action">
															<a href="?page=amgt-accountant&tab=add_accountant&action=edit&accountant_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
															<a href="?page=amgt-accountant&tab=accountantlist&action=delete&accountant_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-danger" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');"><?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
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
							if($active_tab == 'add_accountant')
							{
								require_once AMS_PLUGIN_DIR.'/admin/accountant/add_accountant.php';
							} ?>
                    </div><!--END PANEL BODY-->
	           </div>
	        </div>
        </div>
    </div><!--END MAIN WRAPPER-->
</div><!--  END PAGE INNER DIV -->