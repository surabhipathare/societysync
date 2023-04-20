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
$active_tab = isset($_GET['tab'])?$_GET['tab']:'staff-memberlist';
?>
<div class="panel-body panel-white"><!--  PANEL BODY DIV -->
	<ul class="nav nav-tabs panel_tabs" role="tablist">
		 <li class="<?php if($active_tab=='staff-memberlist'){?>active<?php }?>">
				<a href="?apartment-dashboard=user&page=staff-members&tab=staff-memberlist" class="tab <?php echo $active_tab == 'staff-memberlist' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php esc_html_e('Staff Member List', 'apartment_mgt'); ?></a>
			  </a>
		  </li>
	</ul>
	<div class="tab-content">
	<!--MEMBERLIST TAB-->
	<?php if($active_tab == 'staff-memberlist')
	{ ?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
			"use strict";
			//MEMBER LIST FUNCTION
			jQuery('#member_list').DataTable({
				"responsive":true,
				"order": [[ 1, "asc" ]],
				"aoColumns":[
							  {"bSortable": false},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true},
							  {"bSortable": true}],
							  language:<?php echo amgt_datatable_multi_language();?>
				});
		} );
		</script>
    	<div class="panel-body"><!--PANEL BODY DIV-->
        	<div class="table-responsive">
			    <table id="member_list" class="display" cellspacing="0" width="100%"><!--MEMBER LIST TABLE-->
					<thead>
						<tr>
							<th><?php  esc_html_e('Photo', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Name', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Badge ID', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Staff Role', 'apartment_mgt' ) ;?></th>
							<th> <?php esc_html_e('Email', 'apartment_mgt' ) ;?></th>
							<th> <?php esc_html_e('Mobile', 'apartment_mgt' ) ;?></th>
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
							</tr>
					</tfoot>
					<tbody>
						<?php 
						$user_id=get_current_user_id();
					
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
										{
											echo '<img src='.$userimage.' height="50px" width="50px" class="img-circle"/>';
										}
								?></td>
								<td class="name"><?php echo esc_html($retrieved_data->display_name);?></td>
								<td class="bnumber"><?php echo esc_html($retrieved_data->badge_id);?></td>
								<td class="staff-cat"><?php $staff_cat = get_post($retrieved_data->staff_category); echo esc_html($staff_cat->post_title);?></td>
								<td class="email"><?php echo esc_html($retrieved_data->user_email);?></td>
								<td class="mobile"><?php echo esc_html($retrieved_data->mobile);?></td>
							</tr>
							<?php 
							} 
							
						} ?>
					</tbody>
			    </table><!--END MEMBER LIST TABLE-->
            </div>
        </div><!--END PANEL BODY DIV-->
		<?php 
	}
	?>
	</div>
</div><!-- END PANEL BODY DIV -->
<?php ?>