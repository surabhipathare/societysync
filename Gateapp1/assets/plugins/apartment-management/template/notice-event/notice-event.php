<script type="text/javascript">
function fileCheck(obj)
{
	"use strict";
	var fileExtension = ['pdf','doc','jpg','jpeg','png'];
	if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
	{
		alert("Only '.pdf','.docx','.jpg','.jpeg','.png'  formats are allowed.");
		$(obj).val('');
	}	
}
</script>
<style>
.dropdown-menu {
    min-width: 240px;
}
</style>
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
$obj_units=new Amgt_ResidentialUnit;
$obj_notice=new Amgt_NoticeEvents;
$active_tab = isset($_GET['tab'])?$_GET['tab']:'notice_list';

	if(isset($_POST['save_notice']))//SAVE NOTICE		
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_notice_nonce' ) )
		{
		if($_FILES['upload_file']['name'] != "" && $_FILES['upload_file']['size'] > 0)	
		{
			if($_FILES['upload_file']['size'] > 0)
			{
			$file_name=amgt_amgt_load_documets($_FILES['upload_file'],'upload_file','upload_file');
			}
		}
		else
		{
			if(isset($_REQUEST['hidden_upload_file']))
			{
				$file_name=$_REQUEST['hidden_upload_file'];
			}
		}
		
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			$result=$obj_notice->amgt_add_notice($_POST,$file_name);
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=notice-event&tab=notice_list&message=2');
			}
		}
		else
		{
			$result=$obj_notice->amgt_add_notice($_POST,$file_name);
			if($result)
			{
				wp_redirect ( home_url().'?apartment-dashboard=user&page=notice-event&tab=notice_list&message=1');
			}
		}
	}
	}
	if(isset($_POST['save_event']))//SAVE EVENT 
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_event_nonce') )
		{
			if($_FILES['upload_file']['name'] != "" && $_FILES['upload_file']['size'] > 0)	
			{
				if($_FILES['upload_file']['size'] > 0)
				$file_name=amgt_amgt_load_documets($_FILES['upload_file'],'upload_file','upload_file');
			}
			else
			{
				if(isset($_REQUEST['hidden_upload_file']))
					$file_name=$_REQUEST['hidden_upload_file'];
			}
			
		$start_date=amgt_get_format_for_db($_POST['start_date']);
		$end_date=amgt_get_format_for_db($_POST['end_date']);
		$start_hour=date("H", strtotime($_POST['start_time']));
		$start_minute=date("i", strtotime($_POST['start_time']));
		$end_hour=date("H", strtotime($_POST['end_time']));
		$end_minute=date("i", strtotime($_POST['end_time']));
		if($start_date == $end_date )
		{				
			if($start_hour > $end_hour)
			{			
				echo '<script type="text/javascript">alert("End Time should be greater than Start Time");</script>';			
			}
			elseif($start_hour ==  $end_hour && $start_minute > $end_minute )
			{
				echo '<script type="text/javascript">alert("End Time should be greater than Start Time");</script>';		  
			}
			else
			{
				if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
				{
					$result=$obj_notice->amgt_add_event($_POST,$file_name);
					if($result)
					{
						wp_redirect ( home_url().'?apartment-dashboard=user&page=notice-event&tab=event_list&message=5');
					}
				}
				else
				{
					$result=$obj_notice->amgt_add_event($_POST,$file_name);
					if($result)
					{
						wp_redirect ( home_url().'?apartment-dashboard=user&page=notice-event&tab=event_list&message=4');
					}
				}
			}		
		}
		elseif($start_date > $end_date )
		{
			echo '<script type="text/javascript">alert("End Date should be greater than Start Date");</script>';	
		}
		else
		{
			if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
			{
				$result=$obj_notice->amgt_add_event($_POST,$file_name);
				if($result)
				{
					wp_redirect ( home_url().'?apartment-dashboard=user&page=notice-event&tab=event_list&message=2');
				}
			}
			else
			{
				$result=$obj_notice->amgt_add_event($_POST,$file_name);
				if($result)
				{
					wp_redirect ( home_url().'?apartment-dashboard=user&page=notice-event&tab=event_list&message=1');
				}
			}
		}	
	}
	}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')//DELETE NOTICE AND EVENT
		{
			if(isset($_REQUEST['notice_id']))
			{
				$result=$obj_notice->amgt_delete_notice($_REQUEST['notice_id']);
				if($result)
				{
					wp_redirect ( home_url().'?apartment-dashboard=user&page=notice-event&tab=notice_list&message=3');
				}
			}
			if(isset($_REQUEST['event_id']))
			{
				$result=$obj_notice->amgt_delete_event($_REQUEST['event_id']);
				if($result)
				{
					wp_redirect ( home_url().'?apartment-dashboard=user&page=notice-event&tab=event_list&message=3');
				}
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
					esc_html_e('Notice inserted successfully but display After Admin Approved','apartment_mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 "><p><?php
				_e("Notice updated successfully.",'apartment_mgt');
				?></p>
				</div>
			<?php 
		}
		elseif($message == 3) 
		{?>
		<div id="message" class="updated below-h2"><p>
		<?php 
			esc_html_e('Notice deleted successfully','apartment_mgt');
		?></div></p><?php
				
		}
		if($message == 4)
		{?>
				<div id="message" class="updated below-h2 ">
				<p>
				<?php 
					esc_html_e('Event inserted successfully but display After Admin Approved','apartment_mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 5)
		{?><div id="message" class="updated below-h2 "><p><?php
				_e("Event updated successfully.",'apartment_mgt');
				?></p>
				</div>
			<?php 
		}
		
	}
	?>
<!-- VIEW POPUP CODE -->	
<div class="popup-bg">
    <div class="overlay-content">
       <div class="notice_content"></div>    
    </div> 
</div>	
<!-- END POP-UP CODE -->
<div class="panel-body panel-white"><!--PANEL-WHITE DIV-->
    <ul class="nav nav-tabs panel_tabs" role="tablist"><!-- PANEL_TABS-->
	  	<li class="<?php if($active_tab=='notice_list'){?>active<?php }?>">
			<a href="?apartment-dashboard=user&page=notice-event&tab=notice_list" class="tab <?php echo $active_tab == 'notice_list' ? 'active' : ''; ?>">
             <i class="fa fa-align-justify"></i> <?php esc_html_e('Notice List', 'apartment_mgt'); ?></a>
          </a>
        </li>
       <li class="<?php if($active_tab=='add_notice'){?>active<?php }?>">
		  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['notice_id']))
			{ ?>
			<a href="?apartment-dashboard=user&page=notice-event&tab=add_notice&action=edit&notice_id=<?php echo $_REQUEST['notice_id'];?>" class="nav-tab <?php echo $active_tab == 'visitor-checkin' ? 'nav-tab-active' : ''; ?>">
             <i class="fa fa"></i> <?php esc_html_e('Edit Notice', 'apartment_mgt'); ?></a>
			 <?php }
			else
			{
				if($user_access['add']=='1')
				{ ?>
				<a href="?apartment-dashboard=user&page=notice-event&tab=add_notice" class="tab <?php echo $active_tab == 'add_notice' ? 'active' : ''; ?>">
				<i class="fa fa-plus-circle"></i> <?php esc_html_e('Add Notice', 'apartment_mgt'); ?></a>
	    <?php 
				} 
			}	?>
	   </li>
		<li class="<?php if($active_tab=='event_list'){?>active<?php }?>">
				<a href="?apartment-dashboard=user&page=notice-event&tab=event_list" class="tab <?php echo $active_tab == 'event_list' ? 'active' : ''; ?>">
				 <i class="fa fa-align-justify"></i> <?php esc_html_e('Event List', 'apartment_mgt'); ?></a>
			  </a>
		</li>
		
		   <li class="<?php if($active_tab=='add_event'){?>active<?php }?>">
			  <?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['event_id']))
				{ ?>
				<a href="?apartment-dashboard=user&page=notice-event&tab=add_event&action=edit&event_id=<?php echo $_REQUEST['event_id'];?>" class="nav-tab margin_top_10_res <?php echo $active_tab == 'add_event' ? 'nav-tab-active' : ''; ?>">
				 <i class="fa fa"></i> <?php esc_html_e('Edit Event', 'apartment_mgt'); ?></a>
				 <?php }
				else
				{ 
					if($user_access['add']=='1')
					{?>
						<a href="?apartment-dashboard=user&page=notice-event&tab=add_event" class="tab margin_top_10_res <?php echo $active_tab == 'add_event' ? 'active' : ''; ?>">
						<i class="fa fa-plus-circle"></i> <?php esc_html_e('Add Event', 'apartment_mgt'); ?></a>
		  <?php 	} 
				}?>
		</li>
    </ul>
	<div class="tab-content">
	 <!---NOTICE LIST TAB--->
	<?php if($active_tab == 'notice_list')
	{ ?>
		<script type="text/javascript">
		$(document).ready(function() {
			"use strict";
			jQuery('#notice_list').DataTable(
			{
				"responsive": true,
				"order": [[ 0, "asc" ]],
				"aoColumns":[
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
    	<div class="panel-body"><!--PANEL BODY DIV-->
        	<div class="table-responsive"><!---TABLE-RESPONSIVE DIV--->
				<table id="notice_list" class="display" cellspacing="0" width="100%"><!--NOTICE LIST TABLE-->
					<thead>
						<tr>
							<th><?php esc_html_e('Notice Title', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Valid Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Notice Type', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Description', 'apartment_mgt' ) ;?></th>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
				   </thead>
					<tfoot>
						<tr>
							<th><?php esc_html_e('Notice Title', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Valid Date', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Notice Type', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Status', 'apartment_mgt' ) ;?></th>
							<th><?php esc_html_e('Description', 'apartment_mgt' ) ;?></th>
							<th><?php  esc_html_e('Action', 'apartment_mgt' ) ;?></th>
						</tr>
					</tfoot>
					<tbody>
						<?php 
						$user_id=get_current_user_id();
						//--- NOTICE DATA FOR MEMBER  ------//
						if($obj_apartment->role=='member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{
								$noticedata=$obj_notice->amgt_get_own_notice($user_id);
							}
							else
							{
								$noticedata=$obj_notice->amgt_get_all_notice();
							}
						} 
						//--- NOTICE DATA FOR STAFF MEMBER  ------//
						elseif($obj_apartment->role=='staff_member')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{  
								$noticedata=$obj_notice->amgt_get_own_notice($user_id);
							}
							else
							{
								$noticedata=$obj_notice->amgt_get_all_notice();
							}
						}
						//--- NOTICE DATA FOR ACCOUNTANT  ------//
						elseif($obj_apartment->role=='accountant')
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$noticedata=$obj_notice->amgt_get_own_notice($user_id);
							}
							else
							{
								$noticedata=$obj_notice->amgt_get_all_notice();
							}
						}
						//--- NOTICE DATA FOR GATEKEEPER  ------//
						else
						{
							$own_data=$user_access['own_data'];
							if($own_data == '1')
							{ 
								$noticedata=$obj_notice->amgt_get_own_notice($user_id);
							}
							else
							{
								$noticedata=$obj_notice->amgt_get_all_notice();
							}
						}
						if(!empty($noticedata))
						{
							 
							foreach ($noticedata as $retrieved_data)
							{ ?>
								<tr>
									
									<td class="title"><!--TITLE-->
									<?php echo esc_html($retrieved_data->notice_title);?></td>
									<td class="valid date"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->valid_date));?></td>
									<td class="type"><?php echo esc_html($retrieved_data->notice_type);?></td>
									<td class="status"><?php if($retrieved_data->status=='Open'){ esc_html_e('Open','apartment_mgt');}elseif($retrieved_data->status=='Not Approved'){  esc_html_e('Not Approved','apartment_mgt');}?></td>
									<td class="description"><?php echo wp_trim_words( $retrieved_data->description,5);?></td>
									<td class="action">
										<a href="#" class="btn btn-primary view-notice" id="<?php echo $retrieved_data->id;?>"> <?php esc_html_e('View','apartment_mgt');?></a>
									<?php
									if($user_access['edit']=='1')
									{  ?>
									 
										<a href="?apartment-dashboard=user&page=notice-event&tab=add_notice&action=edit&notice_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' );?></a>
								<?php
									}
									if($user_access['delete']=='1')
									{
									?>
										<a href="?apartment-dashboard=user&page=notice-event&tab=notice_list&action=delete&notice_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
										onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
										<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
									<?php
									}
									?>
									 <?php if($retrieved_data->notice_doc!='') { ?>
									<a target="blank" href="<?php echo content_url().'/uploads/apartment_assets/'.$retrieved_data->notice_doc;?>" class="btn btn-default"> <i class="fa fa-eye"></i> <?php esc_html_e('View Document', 'apartment_mgt' ) ;?> </a>
									<?php } ?>
									</td>
								</tr>
							<?php
							} 
						}?>
					</tbody>
			    </table><!--END NOTICE LIST TABLE-->
            </div><!---END TABLE-RESPONSIVE DIV--->
        </div><!--END PANEL BODY DIV-->
	<?php 
	}
	if($active_tab == 'add_notice')
	{ 
	  require_once AMS_PLUGIN_DIR.'/template/notice-event/add_notice.php' ;
	}
	if($active_tab == 'event_list')
	{ 
	  require_once AMS_PLUGIN_DIR.'/template/notice-event/event_list.php' ;
	}
	if($active_tab == 'add_event')
	{ 
	  require_once AMS_PLUGIN_DIR.'/template/notice-event/add_event.php' ;
	}
	?>
	</div>
</div>
<?php ?>