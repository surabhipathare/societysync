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
$obj_apartment=new Apartment_management(get_current_user_id());
$active_tab = isset($_GET['tab'])?$_GET['tab']:'commitee_memberlist';
?>
<div class="panel-body panel-white"><!--  PANEL BODY DIV   -->
    <ul class="nav nav-tabs panel_tabs" role="tablist"><!--NAV-TABS LIST-->
	  	<li class="<?php if($active_tab=='commitee_memberlist'){?>active<?php }?>">
			<a href="?apartment-dashboard=user&page=committee-member" class="tab <?php echo $active_tab == 'commitee_memberlist' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php esc_html_e('Committee Member List', 'apartment_mgt'); ?></a>
          </a>
       </li>
    </ul>
	<div class="tab-content">
	<?php if($active_tab == 'commitee_memberlist')//COMMITEE_MEMBERLIST TAB
	{ ?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
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
							  {"bSortable": true},
							  <?php if($obj_apartment->role=='staff_member')
								{ ?>	
							  {"bSortable": true}							  
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
				<table id="member_list" class="display" cellspacing="0" width="100%"><!---MEMBER_LIST--->
					<thead>
						<tr>
						  <th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
						  <th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
						  <th><?php esc_html_e('Designation', 'apartment_mgt' ) ;?></th>
						  <th><?php esc_html_e('Unit Name', 'apartment_mgt' ) ;?></th>
						  <th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
						  <th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
						<?php if($obj_apartment->role=='staff_member' || $obj_apartment->role=='member' || $obj_apartment->role=='accountant' || $obj_apartment->role=='gatekeeper')
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
							<th><?php esc_html_e('Designation', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Unit Name', 'apartment_mgt' ) ;?></th>
							<th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
							<th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
							<?php if($obj_apartment->role=='staff_member' || $obj_apartment->role=='member' || $obj_apartment->role=='accountant' || $obj_apartment->role=='gatekeeper')
							{ ?>
							<th> <?php esc_html_e('Action', 'apartment_mgt' ) ;?></th>
							<?php
							}
							?>
						</tr>
					</tfoot>
					<tbody>
					<?php 
					$get_members = array('role' => 'member','meta_key' => 'committee_member','meta_value'=> 'yes');
					$membersdata=get_users($get_members);
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
								<td class="designation"><?php echo get_the_title($retrieved_data->designation_id);?></td>
								<td class="activitydate"><?php echo esc_html($retrieved_data->unit_name);?></td>
								<td class=""><?php echo esc_html($retrieved_data->user_email);?></td>
								<td class=""><?php echo esc_html($retrieved_data->mobile);?></td>
								<?php if($obj_apartment->role=='staff_member' || $obj_apartment->role=='member' || $obj_apartment->role=='accountant' || $obj_apartment->role=='gatekeeper')
								{ ?><td class="action">
									   <a href="?apartment-dashboard=user&page=member&tab=viewmember&action=view&member_id=<?php echo esc_attr($retrieved_data->ID);?>" class="btn btn-success"> <?php esc_html_e('View Details', 'apartment_mgt' ) ;?></a>
									 </td>
								<?php 
								} 
							} ?>
							</tr>
						<?php  
					}?>
					</tbody>
				</table><!---END MEMBER_LIST--->
            </div><!---END TABLE-RESPONSIVE--->
        </div><!--END ANEL BODY-->
		<?php 
	}
	?>
	</div>
</div><!--  END PANEL BODY DIV   -->
<?php ?>