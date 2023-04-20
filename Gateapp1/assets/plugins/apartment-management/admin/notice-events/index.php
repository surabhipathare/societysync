<?php
$active_tab = isset($_GET['tab'])?$_GET['tab']:'notice_list';
$obj_notice=new Amgt_NoticeEvents;
?>
<!-- VIEW POP-UP CODE -->	
<div class="complaint-popup-bg">
     <div class="overlay-content">
       <div class="complaint_content"></div>    
     </div> 
</div>		

<!-- VIEW POP-UP CODE -->	
<div class="event-popup-bg">
     <div class="overlay-content">
       <div class="event_content"></div>    
     </div> 
</div>	
<div class="page-inner min_height_1088"><!---PAGE-INNER--->
	<div class="page-title"><!---PAGE TITLE--->
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>
<?php 
	if(isset($_POST['save_notice']))//SAVE NOTICE		
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_notice_nonce' ) )
		{
		if($_FILES['upload_file']['name'] != "" && $_FILES['upload_file']['size'] > 0)	
		{
			
			if($_FILES['upload_file']['size'] > 0)
			$file_name=amgt_amgt_load_documets($_FILES['upload_file'],'upload_file','upload_file');
			//$file_url=content_url().'/uploads/apartment_assets/'.$file_name;
		}
		else
		{
			
			if(isset($_REQUEST['hidden_upload_file']))
				$file_name=$_REQUEST['hidden_upload_file'];
				//$file_url=$file_name;
		}
		
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')
		{
			//$_POST['notice_doc']=$file_url;
			$result=$obj_notice->amgt_add_notice($_POST,$file_name);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-notice-event&tab=notice_list&message=2');
			}
		}
		else
		{
			//$_POST['notice_doc']=$file_url;
			$result=$obj_notice->amgt_add_notice($_POST,$file_name);
			if($result)
			{
				wp_redirect ( admin_url().'admin.php?page=amgt-notice-event&tab=notice_list&message=1');
			}
		}
	}
	}
if(isset($_POST['save_event']))	//SAVE EVENT	
{
	$nonce = $_POST['_wpnonce'];
	if (wp_verify_nonce( $nonce, 'save_event_nonce' ) )
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
				if(isset($_REQUEST['action'])&& $_REQUEST['action']=='edit')//EDIT NOTICE
				{
					$result=$obj_notice->amgt_add_event($_POST,$file_name);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=amgt-notice-event&tab=event_list&message=6');
					}
				}
				else
				{
					$result=$obj_notice->amgt_add_event($_POST,$file_name);
					if($result)
					{
						wp_redirect ( admin_url().'admin.php?page=amgt-notice-event&tab=event_list&message=5');
					}
				}
			}		
		}
		elseif($start_date > $end_date )//START DATE END DATE
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
					wp_redirect ( admin_url().'admin.php?page=amgt-notice-event&tab=event_list&message=2');
				}
			}
			else
			{
				$result=$obj_notice->amgt_add_event($_POST,$file_name);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-notice-event&tab=event_list&message=1');
				}
			}
		}	
	}
}
	if(isset($_REQUEST['action'])&& $_REQUEST['action']=='delete')//DELETE NOTICE
		{
			if(isset($_REQUEST['notice_id']))
			{
				$result=$obj_notice->amgt_delete_notice($_REQUEST['notice_id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-notice-event&tab=notice_list&message=3');
				}
			}
			if(isset($_REQUEST['event_id']))
			{
				$result=$obj_notice->amgt_delete_event($_REQUEST['event_id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-notice-event&tab=event_list&message=3');
				}
			}
	    }
		if(isset($_REQUEST['action'])&& $_REQUEST['action']=='approve_notice')
		{
			if(isset($_REQUEST['notice_id']))
			{
				$result=$obj_notice->amgt_approve_notice($_REQUEST['notice_id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-notice-event&tab=notice_list&message=4');
				}
			}
			if(isset($_REQUEST['event_id']))
			{
				$result=$obj_notice->amgt_approve_event($_REQUEST['event_id']);
				if($result)
				{
					wp_redirect ( admin_url().'admin.php?page=amgt-notice-event&tab=event_list&message=8');
				}
			}
			
		}
		if(isset($_REQUEST['message']))//MESSAGE
	     {
			$message =$_REQUEST['message'];
			if($message == 1)
			{?>
					<div id="message" class="updated below-h2 notice is-dismissible">
					<p>
					<?php 
						esc_html_e('Notice inserted successfully','apartment_mgt');
					?></p></div>
					<?php
				
			}
			elseif($message == 2)
			{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
						_e("Notice updated successfully.",'apartment_mgt');
						?></p>
						</div>
					<?php 
				
			}
			elseif($message == 3) 
			{?>
			<div id="message" class="updated below-h2 notice is-dismissible"><p>
			<?php 
				esc_html_e('Notice deleted successfully','apartment_mgt');
			?></div></p><?php
					
			}
			elseif($message == 4) 
			{?>
			<div id="message" class="updated below-h2 notice is-dismissible"><p>
			<?php 
				esc_html_e('Notice Approved successfully','apartment_mgt');
			?></div></p><?php
					
			}
			if($message == 5)
			{?>
					<div id="message" class="updated below-h2 notice is-dismissible">
					<p>
					<?php 
						esc_html_e('Event inserted successfully','apartment_mgt');
					?></p></div>
					<?php
				
			}
			elseif($message == 6)
			{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
						_e("Event updated successfully.",'apartment_mgt');
						?></p>
						</div>
					<?php 
				
			}
			elseif($message == 8)
			{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
						_e("Event Approved successfully.",'apartment_mgt');
						?></p>
						</div>
					<?php 
				
			}
	    }?>
		
	<div id="main-wrapper"><!---Main wrapper------>	
		<div class="row">	
			<div class="col-md-12">
				<div class="panel panel-white"><!---PANEL WHITE------>	
					<div class="panel-body"><!---PANEL BODY------>
                        <!---NAV TAB WRAPPER------>						
						<h2 class="nav-tab-wrapper">
							<a href="?page=amgt-notice-event&tab=notice_list" class="nav-tab <?php echo $active_tab == 'notice_list' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Notice List', 'apartment_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['notice_id']))
							{ ?>
							<a href="?page=amgt-notice-event&tab=add_notice&action=edit&notice_id=<?php echo $_REQUEST['notice_id'];?>" class="nav-tab <?php echo $active_tab == 'add_notice' ? 'nav-tab-active' : ''; ?>">
							<?php esc_html_e('Edit Notice', 'apartment_mgt'); ?></a>  
							<?php 
							}
							else 
							{ ?>
								<a href="?page=amgt-notice-event&tab=add_notice" class="nav-tab <?php echo $active_tab == 'add_notice' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Notice', 'apartment_mgt'); ?></a>
							<?php  } ?>
							
							<a href="?page=amgt-notice-event&tab=event_list" class="nav-tab <?php echo $active_tab == 'event_list' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-menu"></span> '.esc_html__('Event List', 'apartment_mgt'); ?></a>
							
							<?php  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && isset($_REQUEST['event_id']))
							{ ?>
							<a href="?page=amgt-notice-event&tab=add_event&action=edit&event_id=<?php echo $_REQUEST['event_id'];?>" class="nav-tab <?php echo $active_tab == 'add_event' ? 'nav-tab-active' : ''; ?>">
							<?php esc_html_e('Edit Event', 'apartment_mgt'); ?></a>  
							<?php 
							}
							else 
							{ ?>
								<a href="?page=amgt-notice-event&tab=add_event" class="nav-tab <?php echo $active_tab == 'add_event' ? 'nav-tab-active' : ''; ?>">
							<?php echo '<span class="dashicons dashicons-plus-alt"></span> '.esc_html__('Add Event', 'apartment_mgt'); ?></a>
							<?php  } ?>
						</h2><!---END NAV TAB WRAPPER------>	
					   <?php 
					    //NOTICE LIST TAB
						if($active_tab == 'notice_list')
						{ ?>
						<script type="text/javascript">
						  $(document).ready(function() {
							"use strict";
						  jQuery('#notice_list').DataTable({
							"responsive":true,
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
					<form name="activity_form" action="" method="post"><!---ACTIVITY FORM------>	
						<div class="panel-body"><!---PANEL BODY------>
							<div class="table-responsive"><!---TABLE RESPONSIVE----->
						<table id="notice_list" class="display" cellspacing="0" width="100%"<!---NOTICELIST TABLE------>
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
										$noticedata=$obj_notice->amgt_get_all_notice();
									 if(!empty($noticedata))
									 {
										 
										foreach ($noticedata as $retrieved_data){ ?>
										<tr>
											
											<td class="title"><a href="?page=amgt-notice-event&tab=add_notice&action=edit&notice_id=<?php echo esc_attr($retrieved_data->id);?>"><?php echo esc_html($retrieved_data->notice_title);?></a></td>
											<td class="valid date"><?php echo date(amgt_date_formate(),strtotime($retrieved_data->valid_date));?></td>
											<td class="type"><?php echo esc_html($retrieved_data->notice_type);?></td>
											<td class="status"><?php if($retrieved_data->status=='Open'){ esc_html_e('Open','apartment_mgt');}elseif($retrieved_data->status=='Not Approved'){  esc_html_e('Not Approved','apartment_mgt');}?></td>
											<td class="description"><?php echo wp_trim_words( $retrieved_data->description,5);?></td>
											<td class="action">
											<?php if($retrieved_data->status=='Not Approved'){ ?>
											<a href="?page=amgt-notice-event&tab=notice_list&action=approve_notice&notice_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-default"> <?php esc_html_e('Approve', 'apartment_mgt' );?></a>
											<?php } ?>
											<a href="#" class="btn btn-primary view-notice" id="<?php echo $retrieved_data->id;?>"> <?php esc_html_e('View','apartment_mgt');?></a>
										   <a href="?page=amgt-notice-event&tab=add_notice&action=edit&notice_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-info"> <?php esc_html_e('Edit', 'apartment_mgt' );?></a>
											<a href="?page=amgt-notice-event&tab=notice_list&action=delete&notice_id=<?php echo esc_attr($retrieved_data->id);?>" class="btn btn-danger" 
											onclick="return confirm('<?php esc_html_e('Do you really want to delete this record?','apartment_mgt');?>');">
											<?php esc_html_e('Delete', 'apartment_mgt' ) ;?> </a>
											<?php if($retrieved_data->notice_doc!='') { ?>
											<a target="blank" href="<?php echo content_url().'/uploads/apartment_assets/'.$retrieved_data->notice_doc;?>" class="btn btn-default"> <i class="fa fa-eye"></i> <?php esc_html_e('View Document', 'apartment_mgt' ) ;?> </a>
											<?php } ?>
											
											</td>
										   
										</tr>
										<?php } 
										
									}?>
								 
								</tbody>
						</table>
					</div>
			    </div>	   
		    </form>
          <?php }
				 if($active_tab == 'add_notice')
				 {
					require_once AMS_PLUGIN_DIR.'/admin/notice-events/add_notice.php';
				 }
				  if($active_tab == 'event_list')
				 {
					require_once AMS_PLUGIN_DIR.'/admin/notice-events/event_list.php';
				 }
				  if($active_tab == 'add_event')
				 {
					require_once AMS_PLUGIN_DIR.'/admin/notice-events/add_event.php';
				 }
				 ?>
            </div><!---END PANEL BODY------>
	    </div><!--END PANEL BODY-->
	 </div>
  </div>
 </div>
</div>