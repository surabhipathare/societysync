<?php 
 $active_tab = isset($_GET['tab'])?$_GET['tab']:'accountantlist'; //ACCOUNTANT LIST
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
 //SAVE ACOOUNTANT DATA //
	if(isset($_POST['save_accountant']))		
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_accountant_nonce_frontend' ) )
		{
			if(isset($_FILES['upload_user_avatar_image']) && !empty($_FILES['upload_user_avatar_image']) && $_FILES['upload_user_avatar_image']['size'] !=0)
			{
				if($_FILES['upload_user_avatar_image']['size'] > 0)
				$member_image=amgt_amgt_load_documets($_FILES['upload_user_avatar_image'],'upload_user_avatar_image','pimg');
				$member_image_url=content_url().'/uploads/apartment_assets/'.$member_image;
			}
			else
			{
				if(isset($_REQUEST['hidden_upload_user_avatar_image']))
				$member_image=$_REQUEST['hidden_upload_user_avatar_image'];
				$member_image_url=$member_image;
			}
			//EDIT  ACOOUNTANT DATA //
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				//$imagurl=$_POST['amgt_user_avatar'];
				  $ext=amgt_check_valid_extension($member_image_url);
					if(!$ext == 0)
					{	
						$result=$obj_member->amgt_add_member($_POST);
						$returnans=update_user_meta( $result,'amgt_user_avatar',$member_image_url);
						if($result)
						{
							wp_redirect ( home_url().'?apartment-dashboard=user&page=accountant&tab=accountantlist&message=2');
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
					//$imagurl=$_POST['amgt_user_avatar'];
					$ext=amgt_check_valid_extension($member_image_url);
					if(!$ext == 0)
					{
						$result=$obj_member->amgt_add_member($_POST);
						$returnans=update_user_meta( $result,'amgt_user_avatar',$member_image_url);
						if($result)
						{
							wp_redirect ( home_url().'?apartment-dashboard=user&page=accountant&tab=accountantlist&message=1');
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
			wp_redirect ( home_url().'?apartment-dashboard=user&page=accountant&tab=accountantlist&message=3');
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
					esc_html_e('Accountant inserted successfully','apartment_mgt');
				?></p>
			</div>
			<?php 
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 notice is-dismissible">
			   <p><?php
				_e("Accountant updated successfully.",'apartment_mgt');
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
					esc_html_e('Accountant deleted successfully','apartment_mgt');
				?>
				</p>
			</div><?php
		}
	}
?>
<div class="panel-body panel-white"><!--PANEL BODY DIV--> 
	<ul class="nav nav-tabs panel_tabs" role="tablist"><!--TABLIST--> 
			<li class="<?php if($active_tab=='accountantlist'){?>active<?php }?>">
				<a href="?apartment-dashboard=user&page=accountant&tab=accountantlist" class="tab <?php echo $active_tab == 'accountantlist' ? 'active' : ''; ?>">
					<i class="fa fa-align-justify"></i> <?php esc_html_e('Accountant List', 'apartment_mgt'); ?>
				</a>
			    </a>
			</li>
			<li class="<?php if($active_tab=='add_accountant'){?>active<?php }?>">
				  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
					{ ?>
					<a href="?apartment-dashboard=user&page=accountant&tab=add_accountant&action=edit&accountant_id=<?php echo $_REQUEST['accountant_id'];?>" class="nav-tab margin_top_10_res <?php echo $active_tab == 'add_accountant' ? 'nav-tab-active' : ''; ?>">
					 <i class="fa fa"></i> <?php esc_html_e('Edit Accountant', 'apartment_mgt'); ?></a>
					 <?php 
					}
					else
					{
						if($user_access['add']=='1')
						{ ?>
							<a href="?apartment-dashboard=user&page=accountant&tab=add_accountant" class="tab margin_top_10_res <?php echo $active_tab == 'add_accountant' ? 'active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php esc_html_e('Add Accountant', 'apartment_mgt'); ?></a>
			  <?php 	} 	
					}?>
			  
			</li>
	</ul>
	    <div class="tab-content"><!--  TAB CONTENT DIV   --> 
			<?php if($active_tab == 'accountantlist')//<!--  ACCOUNTANT LIST TAB --> 
			{ ?>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						"use strict";
						jQuery('#accountant_list').DataTable({
							"responsive":true,
							"order": [[ 1, "asc" ]],
							 "aoColumns":[
												
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true},
												  {"bSortable": true}
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
				 <div class="panel-body"><!--PANEL BODY-->
					<div class="table-responsive"><!---TABLE-RESPONSIVE--->
						<table id="accountant_list" class="display" cellspacing="0" width="100%"><!--ACCOUNTANT_LIST TABLE---->
							<thead>
								<tr>
									<th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
									<th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
									<th><?php esc_html_e('Badge ID', 'apartment_mgt' ) ;?></th>
									<th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
									<th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
								<?php 
									if($obj_apartment->role !=='member' AND $obj_apartment->role !=='accountant' AND $obj_apartment->role !=='gatekeeper')
									{ ?>
										<th> <?php esc_html_e('Action', 'apartment_mgt' ) ;?></th>
									<?php
									}
									?>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
									<th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
									<th><?php esc_html_e('Badge ID', 'apartment_mgt' ) ;?></th>
									<th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
									<th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
								<?php 
									if($obj_apartment->role !=='member' AND $obj_apartment->role !=='accountant' AND $obj_apartment->role !=='gatekeeper')
									{ ?>
										<th> <?php esc_html_e('Action', 'apartment_mgt' ) ;?></th>
									<?php
									}
									?>
								</tr>
							</tfoot>
							<tbody>
								<?php
								$user_id=get_current_user_id();
								//--- ACCOUNTANT DATA FOR MEMBER  ------//
								if($obj_apartment->role=='member')
								{
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{
										$get_members = array('role' => 'accountant','meta_key'  => 'created_by','meta_value' =>$user_id);
										$membersdata=get_users($get_members);
									}
									else
									{
										$get_members = array('role' => 'accountant');
										$membersdata=get_users($get_members);
									}
								} 
								//--- ACCOUNTANT DATA FOR STAFF MEMBER  ------//
								elseif($obj_apartment->role=='staff_member')
								{
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{ 
										$get_members = array('role' => 'accountant','meta_key'  => 'created_by','meta_value' =>$user_id);
										$membersdata=get_users($get_members);
									}
									else
									{
										$get_members = array('role' => 'accountant');
										$membersdata=get_users($get_members);
									}
								}
								//--- ACCOUNTANT DATA FOR ACCOUNTANT  ------//
								elseif($obj_apartment->role=='accountant')
								{
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{ 
										$membersdata[]=get_userdata($user_id);
									}
									else
									{
										$get_members = array('role' => 'accountant');
										$membersdata=get_users($get_members);
									}
								}
								//--- ACCOUNTANT DATA FOR GATEKEEPER  ------//
								else
								{
									$own_data=$user_access['own_data'];
									if($own_data == '1')
									{ 
										$get_members = array('role' => 'accountant','meta_key'  => 'created_by','meta_value' =>$user_id);
										$membersdata=get_users($get_members);
									}
									else
									{
										$get_members = array('role' => 'accountant');
										$membersdata=get_users($get_members);
									}
								}								
								if(!empty($membersdata))
								{
									foreach ($membersdata as $retrieved_data)
									{ ?>
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
												}
											?></td>
											<td class="name"><?php echo esc_html($retrieved_data->display_name);?></td>
											<td class="bnumber"><?php echo esc_html($retrieved_data->badge_id);?></td>
											<td class="email"><?php echo esc_html($retrieved_data->user_email);?></td>
											<td class="mobile"><?php echo esc_html($retrieved_data->mobile);?></td>
											<?php 
									if($obj_apartment->role !=='member' AND $obj_apartment->role !=='accountant' AND $obj_apartment->role !=='gatekeeper')
									{ ?>
											<td class="action">
											<?php
												if($user_access['edit']=='1')
												{  ?>
													<a href="?apartment-dashboard=user&page=accountant&tab=add_accountant&action=edit&accountant_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
												<?php
												}
												if($user_access['delete']=='1')
												{
												?>
													<a href="?apartment-dashboard=user&page=accountant&tab=add_accountant&action=delete&accountant_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-danger" onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');"><?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
												<?php
												}
												?>
											</td>
											<?php
										}
									?>
										</tr>
						  <?php 	} 
								}	
							?>
							</tbody>
						</table><!--ACCOUNTANT_LIST TABLE---->
                    </div>
                </div><!--PANEL BODY-->
		     <?php 
	        }
			if($active_tab == 'add_accountant')//<!--  ACCOUNTANT LIST TAB --> 
			{   
				require_once AMS_PLUGIN_DIR.'/template/accountant/add_accountant.php';
			}
			?>
	    </div><!--  END TAB CONTENT DIV   --> 
</div><!--  END PANEL BODY DIV   --> 
<?php ?>