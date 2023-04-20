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
$obj_message = new Amgt_message();

$active_tab = isset($_GET['tab'])?$_GET['tab']:'inbox';
if(isset($result))
{
	wp_redirect ( home_url() . '?apartment-dashboard=user&page=message&tab=inbox&message=1');
}
	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
				<div id="message" class="updated below-h2 ">
				<p>
				<?php 
					esc_html_e('Message sent successfully','apartment_mgt');
				?>
				</p></div>
				<?php 
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 "><p><?php
					_e("Message deleted successfully",'apartment_mgt');
					?></p>
					</div>
				<?php 
			
		}
	}		
	?>
<div id="main-wrapper">
<div class="row mailbox-header">
                                <div class="col-md-2 col-sm-3 col-xs-4">
                                <?php if($user_access['add']=='1')
									{?>
                                    <a class="btn btn-success" href="?apartment-dashboard=user&page=message&tab=compose">
                                    <?php _e("Compose","apartment_mgt");?></a>
                                   <?php }?>
                                </div>
                                <div class="col-md-10 col-sm-9 col-xs-8">
                                    <h2>
                                    <?php
									if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
                                    echo esc_html( esc_html__('Inbox', 'apartment_mgt' ) );
									else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'sentbox')
									echo esc_html( esc_html__('Sent Item', 'apartment_mgt' ) );
									else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'compose')
										echo esc_html( esc_html__('Compose', 'apartment_mgt' ) );
									else if(isset($_REQUEST['page']) && $_REQUEST['tab'] == 'view_message')
										echo esc_html( esc_html__('View Message', 'apartment_mgt' ) );
									?>
								
                                    
                                    </h2>
                                </div>
                             </div>
							<div class="col-md-2 col-sm-3 col-xs-12">
                            <ul class="list-unstyled mailbox-nav">
 								<li <?php if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox')){?>class="active"<?php }?>>
 									<a href="?apartment-dashboard=user&page=message&tab=inbox">
 										<i class="fa fa-inbox"></i><?php _e("Inbox","apartment_mgt");?> <span class="badge badge-success pull-right">
 										<?php echo count($obj_message->amgt_count_inbox_item(get_current_user_id()));?></span>
 									</a>
 								</li>
 								
                                <li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox'){?>class="active"<?php }?>><a href="?apartment-dashboard=user&page=message&tab=sentbox"><i class="fa fa-sign-out"></i><?php _e("Sent","apartment_mgt");?></a></li>
                                                          
                            </ul>
                        </div>
 <div class="col-md-10 col-sm-9 col-xs-12">
 <?php  
 	if($active_tab == 'sentbox')
 		require_once AMS_PLUGIN_DIR. '/template/message/sendbox.php';
 	if($active_tab == 'inbox')
 		require_once AMS_PLUGIN_DIR. '/template/message/inbox.php';
 	if($active_tab == 'compose')
 		require_once AMS_PLUGIN_DIR. '/template/message/composemail.php';
 	if($active_tab == 'view_message')
 		require_once AMS_PLUGIN_DIR. '/template/message/view_message.php';
 	
 	?>
 </div>
</div><!-- Main-wrapper -->
<?php ?>