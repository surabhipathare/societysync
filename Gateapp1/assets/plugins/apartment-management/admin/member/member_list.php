<?php 
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'memberlist';
	$obj_member=new Amgt_Member;
	$obj_units=new Amgt_ResidentialUnit;
?>
<!-- POP UP CODE -->
<div class="popup-bg z_index_100000">
    <div class="overlay-content">
		<div class="modal-content">
			<div class="category_list"> </div>
		</div>
    </div>    
</div>
<!-- END POP-UP CODE -->
<!-- PAGE INNER DIV -->
	
<?php 
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')
		{
			
			$result=$obj_member->amgt_delete_usedata($_REQUEST['member_id']);
			if($result)
			{ 
				wp_redirect ( admin_url().'admin.php?page=amgt-member&tab=memberlist&message=3');
			}
		}
	if(isset($_REQUEST['message']))//MESSAGES
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
				<div id="message" class="updated below-h2 notice is-dismissible">
				<p>
				<?php 
					esc_html_e('Member inserted successfully','apartment_mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
					_e("Member updated successfully.",'apartment_mgt');
					?></p>
					</div>
				<?php 
			
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Member deleted successfully','apartment_mgt');
		?></div></p><?php
				
		}
		elseif($message == 4) 
		{?>
		<div id="message" class="updated below-h2 notice is-dismissible"><p>
		<?php 
			esc_html_e('Member Active Successfully','apartment_mgt');
		?></div></p><?php
				
		}
	}
	?>

			<?php 
			//MEMBERLIST TAB
			if($active_tab == 'memberlist')
			{ ?>	
			<script type="text/javascript">
			$(document).ready(function() 
			{
				"use strict";
				jQuery('#member_list').DataTable(
				{
					"responsive":true,
					"order": [[ 1, "asc" ]],
					"aoColumns":[
								  {"bSortable": false},
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
				<form name="member_form" action="" method="post"><!--MEMBER FORM-->
					<div class="panel-body"><!--PANEL BODY-->
						<div class="table-responsive"><!---TABLE-RESPONSIVE--->
							<table id="member_list" class="display" cellspacing="0" width="100%">
								<thead>
									<tr>
									  <th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
									  <th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
									  <th><?php esc_html_e('Member Type', 'apartment_mgt' ) ;?></th>
									  <th><?php esc_html_e('Building Name', 'apartment_mgt' ) ;?></th>
									  <th><?php esc_html_e('Unit Name', 'apartment_mgt' ) ;?></th>
									  <th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
									  <th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
									  <th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th> 
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
										<th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
										<th><?php esc_html_e('Member Type', 'apartment_mgt' ) ;?></th>
										<th><?php esc_html_e('Building Name', 'apartment_mgt') ;?></th>
										<th><?php esc_html_e('Unit Name', 'apartment_mgt' ) ;?></th>
										<th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
										<th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>  
										<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
									</tr>
								   
								</tfoot>
								<tbody>
									<?php 
									$get_members = array('role' => 'member');
									$membersdata=get_users($get_members);
									if(!empty($membersdata))
									{
										foreach ($membersdata as $retrieved_data)
										{
											$building_name=get_the_title($retrieved_data->building_id);
											$role=amgt_get_user_role($retrieved_data->ID);
											//var_dump($role);
											if($role == 'member')
											{
												$page='member';
											}
											
											?>
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
										   
											<td class="name"><?php echo esc_html($retrieved_data->display_name);?></td>
											<td class="bnumber"><?php echo amgt_get_member_status_label($retrieved_data->member_type);?></td> 
											<td class="activitydate"><?php echo esc_html($building_name);?></td>
											<td class="activitydate"><?php echo esc_html($retrieved_data->unit_name);?></td>
											<td class=""><?php echo esc_html($retrieved_data->user_email);?></td>
											<td class=""><?php echo esc_html($retrieved_data->mobile);?></td>  
											
											<td class="action">
											 <?php 
												   if( get_user_meta($retrieved_data->ID, 'amgt_hash', true))
													{ ?>
													<a  href="?page=amgt-member&action=active_member&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-default" > <?php esc_html_e('Active', 'apartment_mgt');?></a>
													<?php } ?>
											<a href="?page=amgt-member&tab=viewmember&action=view&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-success"> <?php esc_html_e('View', 'apartment_mgt' ) ;?></a>
											<a href="?page=amgt-member&tab=adduser&action=edit&user_type=<?php echo $page; ?>&member_id=<?php echo esc_attr($retrieved_data->ID);?>"  class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' ) ;?></a>
											<a href="?page=amgt-member&tab=memberlist&action=delete&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-danger" 
											onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
											<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
											</td>
										   
										</tr>
										<?php
										} 
									}?>
								</tbody>
							</table>
						</div>
					</div><!--END PANEL BODY-->
				</form><!--END MEMBER FORM-->
			  <?php 
			}
			if($active_tab == 'addmember')
			{
				require_once AMS_PLUGIN_DIR.'/admin/member/add_member.php';
			} 
			if($active_tab == 'viewmember')
			{
				require_once AMS_PLUGIN_DIR.'/admin/member/view_member.php';
			}?>
			<!-- END PAGE INNER DIV -->