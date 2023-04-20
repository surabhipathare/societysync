<!DOCTYPE html>
<?php
//-------------- Paytm Success -----------------//
if(isset($_REQUEST['STATUS']) && $_REQUEST['STATUS'] == 'TXN_SUCCESS')
{
	$paymentdata['invoice_id']=$_REQUEST['MERC_UNQ_REF'];
	$paymentdata['amount']=$_REQUEST['TXNAMOUNT'];
	$paymentdata['payment_method']='Paytm';	
	$paymentdata['member_id']=get_current_user_id();
	$paymentdata['created_by']=get_current_user_id();
	 
	$obj_account =new Amgt_Accounts;
	$result = $obj_account->amgt_add_own_payment($paymentdata);	
	
	if($result)
	{ 
		wp_redirect ( home_url() . '?apartment-dashboard=user&page=accounts&tab=invoice-list&action=success');
		exit;
	}		
}
//------------------PAYPAL SUCCESS -------------------//
if(isset($_POST['payer_status']) && $_POST['payer_status'] == 'VERIFIED' && (isset($_POST['payment_status'])) && $_POST['payment_status']=='Completed' && isset($_REQUEST['action']) && $_REQUEST['action']=='success' )
{		
	$custom_array = explode("_",$_POST['custom']);
	$paymentdata['invoice_id']=$custom_array[1];

	$paymentdata['amount']=$_POST['mc_gross_1'];
	$paymentdata['payment_method']='paypal';	
	$paymentdata['member_id']=$custom_array[0];

	$paymentdata['created_by']=$custom_array[0];
	$obj_account =new Amgt_Accounts;
	$result = $obj_account->amgt_add_own_payment($paymentdata);	
	
	if($result)
	{ 
		wp_redirect ( home_url() . '?apartment-dashboard=user&page=accounts&tab=invoice-list&action=success');
		exit;
	}		
}
?>
<!-- COMPLAIN VIEW POPUP CODE -->	
<div class="complaint-popup-bg">
     <div class="overlay-content">
       <div class="complaint_content"></div>    
     </div> 
</div>

 <!-- CLASS BOOK IN CALANDER POPUP HTML CODE -->
<div id="eventContent" class="modal-body" style="display:none;"><!--MODAL BODY DIV START-->
	<style>
	   .ui-dialog .ui-dialog-titlebar-close
	   {
		  margin: -15px -4px 0px 0px !important;
	   }
	</style>
			<p><b><?php esc_html_e('Event Title','apartment_mgt');?></b> <span id="event_title"></span></p><br>
			<p><b><?php esc_html_e('Start Date','apartment_mgt');?> </b> <span id="startdate"></span></p><br>
			<p><b><?php esc_html_e('End Date','apartment_mgt');?></b> <span id="enddate"></span></p><br>
			<p><b><?php esc_html_e('Start Time','apartment_mgt');?></b> <span id="starttime"></span></p><br>
			<p><b><?php esc_html_e('End Time','apartment_mgt');?></b> <span id="endtime"></span></p><br>
			<p><b><?php esc_html_e('Description','apartment_mgt');?></b> <span id="description"></span></p><br>
			<p><b><?php esc_html_e('Documents','apartment_mgt');?></b> <span id="document"></span></p><br>
			 
</div><!--MODAL BODY DIV END-->
<?php //======Front end template=========
require_once(ABSPATH.'wp-admin/includes/user.php' );
if (! is_user_logged_in ())
{
	$page_id = get_option ( 'amgt_login_page' );
	wp_redirect ( home_url () . "?page_id=" . $page_id );
}
if (is_super_admin ())
{
	wp_redirect ( admin_url () . 'admin.php?page=amgt-apartment_system' );
}

$user = wp_get_current_user ();
$curr_user_id = get_current_user_id();
$role = amgt_get_user_role(get_current_user_id());

$obj_units=new Amgt_ResidentialUnit;
$obj_notice=new Amgt_NoticeEvents;
$obj_apartment=new Apartment_management(get_current_user_id());
$noticedata=$obj_notice->amgt_get_notice_list_ondashboard();
$eventdata=$obj_notice->amgt_get_all_events();
$obj_member=new Amgt_Member;
$obj_service =new Amgt_Service;
$obj_complaint=new Amgt_Complaint;
$obj_account =new Amgt_Accounts;

$cal_array=array();
if(!empty($eventdata))
{
	foreach ( $eventdata as $retrieved_data ) 
	{
		$start=date('Y-m-d',strtotime($retrieved_data->start_date ))." ".date('h:i A', strtotime($retrieved_data->start_time));
		$end=date('Y-m-d',strtotime($retrieved_data->end_date))." ".date('h:i A', strtotime($retrieved_data->end_time));
		
		$cal_array [] = array (
				'type' =>  'eventdata',
				'title' => $retrieved_data->event_title,
				'description' => $retrieved_data->description,
				'document' =>'<a target="blank" href="'.content_url().'/uploads/apartment_assets/'.$retrieved_data->event_doc.'" class="btn btn-default"><i class="fa fa-eye"></i>'.__(" View Document","apartment_mgt").'</a>',
				'start' =>$start,
				'end' =>$end,
				'starttime' =>$retrieved_data->start_time,
				'endtime' =>$retrieved_data->end_time,
				'backgroundColor' => '#5FCE9B'
		);
	}
}		
?>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/dataTables.css'; ?>">
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/dataTables-editor.css'; ?>">
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/dataTables-tableTools.css'; ?>">
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/dataTables-responsive.css'; ?>">
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/jquery-ui.css'; ?>">
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/font-awesome-dafault.css'; ?>">
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/popup.css'; ?>">
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/style.css'; ?>">
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/custom.css'; ?>">
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/fullcalendar.css'; ?>">
<!-- <link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/bootstrap-timepicker.css'; ?>">
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/datepicker-default.css'; ?>">  -->
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/time.css'; ?>"> 
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/bootstrap-multiselect.css'; ?>">	
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/white.css'; ?>">
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/jquery-fancybox.css'; ?>">
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/bootstrap.css'; ?>">	
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/apartment.css'; ?>">
<!-- <link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/lib/select2-3.5.3/select2.css'; ?>"> -->
<?php  if (is_rtl())
		 {?>
			<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/bootstrap-rtl.css'; ?>">
		<?php
		} ?>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/jquery-1-11-1.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/jquery-timeago.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/jquery-fancybox.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/jquery-fancybox-media.js'; ?>"></script>
<!-- <script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/lib/select2-3.5.3/select2-default.js'; ?>"></script> -->
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/lib/validationEngine/css/validationEngine-jquery.css'; ?>">
<link rel="stylesheet"	href="<?php echo AMS_PLUGIN_URL.'/assets/css/apart-responsive.css'; ?>">
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/jquery-1-11-1.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/jquery-ui.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/moment.js'; ?>"></script>
<!--<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/popup.js'; ?>"></script>-->
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/fullcalendar.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/jquery-dataTables.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/dataTables-tableTools.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/dataTables-editor.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/dataTables-responsive.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/bootstrap.js'; ?>"></script>
<!-- <script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/bootstrap-timepicker.js'; ?>"></script> 
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/bootstrap-datepicker.js'; ?>"></script> 
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/bootstrap-datepicker.js'; ?>"></script> -->
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/time.js'; ?>"></script>
<?php
	$lancode=get_locale();
	$code=substr($lancode,0,2);
?>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/assets/js/calendar-lang/'.$code.'.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/lib/validationEngine/js/languages/jquery.validationEngine-'.$code.'.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/lib/validationEngine/js/jquery-validationEngine.js'; ?>"></script>
<script type="text/javascript"	src="<?php echo AMS_PLUGIN_URL.'/lib/select2-3.5.3/select2-default.js'; ?>"></script>
<script>
 jQuery(document).ready(function() {
	jQuery('#calendar').fullCalendar({
		header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay'
				},
				defaultView: 'month',
				editable: false,
				timeFormat: 'h(:mm)a',
			eventLimit: true, 
			events: <?php echo json_encode($cal_array);  ?>,
			//Event pop up//
	     eventClick:  function(event, jsEvent, view) {
			 
	 	<?php $dformate=get_option('amgt_date_formate'); ?>
	 
				var dateformate_value='<?php echo esc_attr($dformate);?>';
				if(dateformate_value == 'Y-m-d')
				{	
					var dateformate='YYYY-MM-DD';
				}
				if(dateformate_value == 'd/m/Y')
				{	
					var dateformate='DD-MM-YYYY';
				}
				if(dateformate_value == 'm/d/Y')
				{	
					var dateformate='MM-DD-YYYY';
				}	
				if(dateformate_value == 'F j, Y')
				{	
					var dateformate='MM-DD-YYYY';
				}
				
				$("#eventContent #event_title").html(event.title);
				$("#eventContent #startdate").html(moment(event.start).format(dateformate));
				$("#eventContent #enddate").html(moment(event.end).format(dateformate)); 
				$("#eventContent #starttime ").html(event.starttime);
				$("#eventContent #endtime ").html(event.endtime);
				$("#eventContent #description ").html(event.description);
				$("#eventContent #document ").html(event.document);

				var type = event.type;
				if(type == 'eventdata')
				{
					$("#eventLink").attr('href', event.url);
					$("#eventContent").dialog({ modal: true, title:'<?php _e("Event Details","apartment_mgt");?>',width:350, height:450 });
					$(".ui-dialog-titlebar-close").text('x');
					$(".ui-dialog-titlebar-close").css('height',30);
				}
		    }  
		}); 
	});
</script>
</head>
<body class="apart-management-content"><!---APART-MANAGEMENT-CONTENT---->
	<div class="container-fluid mainpage">
        <div class="navbar"><!---NAVBAR---->
		   <div class="col-md-8 col-sm-8 col-xs-6">
				<h3 class="logo-image"><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" />
				<span class="system-name"><?php echo get_option( 'amgt_system_name' );?> </span>
				</h3>
			</div>
			<ul class="nav navbar-right col-md-4 col-sm-4 col-xs-6">
					
					<!-- BEGIN USER LOGIN DROPDOWN -->
					<li class="dropdown">
						<a data-toggle="dropdown"
							class="dropdown-toggle" href="javascript:;">
								<?php
								$userimage = get_user_meta( $user->ID,'amgt_user_avatar',true );	
								if (empty ( $userimage )){
									echo '<img src='.get_option( 'amgt_system_logo' ).' height="40px" width="40px" class="img-circle" />';
								}
								else	
									echo '<img src=' . $userimage . ' height="40px" width="40px" class="img-circle"/>';
								?>
									<span>	<?php echo esc_html($user->display_name);?> </span> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu extended logout">
							<li>
								 <a href="?apartment-dashboard=user&page=profile"><i class="fa fa-user"></i>
										<?php esc_html_e('My Profile','apartment_mgt');?>
								</a>
							</li>
							<li>
								<a href="<?php echo wp_logout_url(home_url()); ?>"><i
										class="fa fa-sign-out m-r-xs"></i><?php esc_html_e('Log Out','apartment_mgt');?>
								</a>
						   </li>
						</ul>
					</li>
			</ul>
	    </div>
	</div>
    <div class="container-fluid"><!---CONTAINER-FLUID--->
	    <div class="row">
		    <div class="col-sm-2 nopadding apartment_left nav-side-menu">	<!--  Left Side -->
		        <div class="brand"><?php esc_html_e('Menu',''); ?>    
					<i data-target="#menu-content" data-toggle="collapse" 
					class="fa fa-bars fa-2x toggle-btn collapsed" aria-expanded="false">
					</i>
		        </div>
				 <?php
					$role = amgt_get_user_role(get_current_user_id());
					if($role=='member')
					{
						$menu = get_option( 'amgt_access_right_member');
					}
					elseif($role=='staff_member')
					{
						$menu = get_option( 'amgt_access_right_staff_member');
					}
					elseif($role=='accountant')
					{
						$menu = get_option( 'amgt_access_right_accountant');
					}
					elseif($role=='gatekeeper')
					{
						$menu = get_option( 'amgt_access_right_gatekeeper');
					}
				
					$class = "";
					if (! isset ( $_REQUEST ['page'] ))	
						$class = 'class = "active"';
						 //print_r($menu); 	?>
				   <ul class="nav nav-pills nav-stacked collapse menu-sec " id="menu-content">
								<li><a href="<?php echo site_url();?>"><span class="icone"><img src="<?php echo plugins_url( 'apartment-management/assets/images/icon/home.png' )?>"/></span><span class="title"><?php esc_html_e('Home','apartment_mgt');?></span></a></li>
								<li <?php echo esc_attr($class);?>><a href="?apartment-dashboard=user"><span class="icone"><img src="<?php echo plugins_url('apartment-management/assets/images/icon/dashboard.png' )?>"/></span><span
										class="title"><?php esc_html_e('Dashboard','apartment_mgt');?></span></a></li>
											<?php
											$access_page_view_array=array();	
												foreach ($menu as $key1=>$value1) 
												{
													foreach ( $value1 as $key=>$value ) 
													{
														if($value['view']=='1')
														{
															$access_page_view_array[]=$value ['page_link'];
															 
															if (isset ( $_REQUEST ['page'] ) && $_REQUEST ['page'] == $value ['page_link'])
																$class = 'class = "active"';
															else
																$class = "";
																echo '<li ' . $class . '><a href="?apartment-dashboard=user&page=' . $value ['page_link'] . '" ><span class="icone"> <img src="' .$value ['menu_icone'].'" /></span><span class="title">'.amgt_change_menutitle($key).'</span></a></li>'; 	
														} 
													}
												}
											?>
					</ul>
		    </div>
			<div class="page-inner innerpage_div" >
				<div class="right_side <?php if(isset($_REQUEST['page']))echo esc_attr($_REQUEST['page']);?>">
					<?php 
					if (isset ( $_REQUEST ['page'] )) 
					{
						if(in_array($_REQUEST ['page'],$access_page_view_array))
						{	
							require_once AMS_PLUGIN_DIR . '/template/'.$_REQUEST['page']. '/' . $_REQUEST['page'] . '.php';
							return false;
						} 
						else
						{
							?><h2><?php print "404 ! Page did not found."; die;?></h2><?php
						}
					}
					
						?>
						
		<!---start new dashboard------>
		<div class="row ">
		  <div class="row left_section col-md-8 col-sm-8 col-xs-12 row_left_section">
		  <?php
			$page='member';
			$member=amgt_page_access_rolewise_accessright_dashboard($page);
			if($member==1)
			{
			?>
				<div class="col-lg-3 col-md-2 col-xs-6 col-sm-3">
				  <a href="<?php echo home_url().'?apartment-dashboard=user&page=member';?>">
					<div class="panel info-box panel-white">
						<div class="panel-body doctor">
							
							<div class="info-box-stats committee_span member_margin">
									<span class="dash_p_span"><p class="counter member_span"><?php 
									$user_id=get_current_user_id();
									$user_member_access=amgt_get_userrole_wise_filter_access_right_array('member');	
									//--- MEMBER DATA FOR MEMBER  ------//
									if($obj_apartment->role=='member')
									{
										$own_data=$user_member_access['own_data'];
										if($own_data == '1')
										{
											$unit_name=get_user_meta($user_id,'unit_name',true);
											$building_id=get_user_meta($user_id,'building_id',true);
											$user_query = new WP_User_Query( 
																array(
																	'meta_query'    => array(
																		'relation'  => 'AND',
																		array( 
																			'key'     => 'unit_name',
																			'value'   => $unit_name,
																		),
																		array(
																			'key'     => 'building_id',
																			'value'   => $building_id,
																			'compare' => '='
																		)
																	)
																));
											$membersdata = $user_query->get_results();
											$members_count =count($membersdata);
											//$membersdata[]=get_userdata($user_id);
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'member' ) );
											$members_count = (int) $user_query->get_total();
										}
									} 
									//--- MEMBER DATA FOR STAFF MEMBER  ------//
									elseif($obj_apartment->role=='staff_member')
									{
										$own_data=$user_member_access['own_data'];
										if($own_data == '1')
										{  
											$get_members = array('role' => 'member','meta_key'  => 'created_by','meta_value' =>$user_id);
											$membersdata=get_users($get_members);
											$members_count =count($membersdata);
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'member' ) );
											$members_count = (int) $user_query->get_total();
										}
									}
									//--- MEMBER DATA FOR ACCOUNTANT  ------//
									elseif($obj_apartment->role=='accountant')
									{
										$own_data=$user_member_access['own_data'];
										if($own_data == '1')
										{ 
											$get_members = array('role' => 'member','meta_key'  => 'created_by','meta_value' =>$user_id);
											$membersdata=get_users($get_members);
											$members_count =count($membersdata);
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'member' ) );
											$members_count = (int) $user_query->get_total();
										}
									}
									//--- MEMBER DATA FOR GATEKEEPER  ------//
									else
									{
										$own_data=$user_member_access['own_data'];
										if($own_data == '1')
										{ 
											$get_members = array('role' => 'member','meta_key'  => 'created_by','meta_value' =>$user_id);
											$membersdata=get_users($get_members);
											$members_count =count($membersdata);
										}
										else
										{
											$user_query = new WP_User_Query( array( 'role' => 'member' ) );
											$members_count = (int) $user_query->get_total();
										}
									}
									
									echo esc_html($members_count);?></p></span>
									<span class="info-box-title dash_member_span member_span_member member_color"><?php echo esc_html( esc_html__('Members', 'apartment_mgt' ) );?></span>
							</div>
							<img src="<?php echo AMS_PLUGIN_URL."/assets/images/dashboard/member.png"?>" class="dashboard_background">
						</div>
					</div>
				  </a>
				</div>
			<?php
			}
			$page='committee-member';
			$committee=amgt_page_access_rolewise_accessright_dashboard($page);
			if($committee==1)
			{
			?>			
			<div class="col-lg-3 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo home_url().'?apartment-dashboard=user&page=committee-member';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body nurse">
						<div class="info-box-stats">
							<span class="dash_p_span"><p class="counter commit_member_color"><?php echo count(get_users(array('role' => 'member','meta_key' => 'committee_member','meta_value'=> 'yes')));?></p></span>
								<span class="info-box-title dash_member_span padding_top_0 commit_member_color">
								<?php 
									esc_html_e('Committee',"apartment_mgt");
								?>
								<br>
								<?php 
									esc_html_e('Members',"apartment_mgt");
								?>
								</span>
							</div>
						<img src="<?php echo AMS_PLUGIN_URL."/assets/images/dashboard/Committee-member.png"?>" class="dashboard_background">
                        
					</div>
				</div>
			  </a>
			</div><!-- END COMMITTEE MEMBER BOX DIV -->
			<?php
			}

			$page='resident_unit';
			$resident_unit=amgt_page_access_rolewise_accessright_dashboard($page);
			if($resident_unit==1)
			{
			?>
			<div class="col-lg-3 col-md-2 col-xs-6 col-sm-3">
			<a href="<?php echo home_url().'?apartment-dashboard=user&page=resident_unit';?>">
				<div class="panel info-box panel-white">
					<div class="panel-body receptionist">
						<div class="info-box-stats">
						    
								<span class="dash_p_span"><p class="counter Compounds_color"><?php 
								$user_member_access=amgt_get_userrole_wise_filter_access_right_array('resident_unit');	
								$own_data=$user_member_access['own_data'];
								if($own_data == '1')
								{
									$unit_count =1;
										//$membersdata[]=get_userdata($user_id);
								}
								else
								{
									$user_query =amgt_count_units();
									$unit_count = (int)$user_query;
								}
								echo esc_html($unit_count);?></p></span>
								
								<span class="info-box-title dash_member_span build Compounds_color"><?php echo esc_html( esc_html__('Buildings', 'apartment_mgt' ) );?></span>
						</div>
						<img src="<?php echo AMS_PLUGIN_URL."/assets/images/dashboard/building.png"?>" class="dashboard_background">
                        
					</div>
				</div>
			  </a>
			</div>
			<?php
			}
			$page='message';
			$message=amgt_page_access_rolewise_accessright_dashboard($page);
			if($message==1)
			{
			?>
			<!-- END RESIDENTIAL UNIT BOX DIV -->
			<div class="col-lg-3 col-md-2 col-xs-6 col-sm-3">
				<a href="<?php echo home_url().'?apartment-dashboard=user&page=message';?>">
					<div class="panel info-box panel-white">
						<div class="panel-body setting">
							<div class="info-box-stats">
								
									<span class="dash_p_span"><p class="counter Message_color"><?php echo count(amgt_count_inbox_item(get_current_user_id()));?></p></span>
									
									<span class="info-box-title dash_member_span Message_color"><?php echo esc_html( esc_html__('Message', 'apartment_mgt' ) );?></span>
								</div>
							<img src="<?php echo AMS_PLUGIN_URL."/assets/images/dashboard/message.png"?>" class="dashboard_background">
							
						</div>
					</div>
				</a>
			</div>
			<?php
			}
			?>
		    </div>
	     </div>
		 
		<div class="row dashboard_top_border">
			<div class="col-sm-6 no-paddingR">
				<?php  
				$page='notice-event';
				$notice_event=amgt_page_access_rolewise_accessright_dashboard($page);
				if($notice_event==1)
				{
				?>
					<div class="panel panel-white event operation dasboard_notice">
						<div class="panel-heading ">
						<img class="dashboard_icons" src="<?php echo AMS_PLUGIN_URL."/assets/images/icon/Notice-And-Event.png"?>">
						<h3 class="panel-title notice_event_flot"><?php esc_html_e('Notice','apartment_mgt');?><span class="float_right" ><a href="<?php echo home_url().'?apartment-dashboard=user&page=notice-event';?>"><i class="fa fa-align-justify" aria-hidden="true"></i></a></span></h3>						
						</div>
						<div class="panel-body">
							<div class="events">
								<?php
								$user_notice_access=amgt_get_userrole_wise_filter_access_right_array('notice-event');	
								$obj_notice=new Amgt_NoticeEvents;
									$user_id=get_current_user_id();
									//--- NOTICE DATA FOR MEMBER  ------//
									if($obj_apartment->role=='member')
									{
										$own_data=$user_notice_access['own_data'];
										if($own_data == '1')
										{
											$noticedata=$obj_notice->amgt_get_own_notice_dashboard($user_id);
										}
										else
										{
											$noticedata=$obj_notice->amgt_get_all_notice_dashboard();
										}
									} 
									//--- NOTICE DATA FOR STAFF MEMBER  ------//
									elseif($obj_apartment->role=='staff_member')
									{
										$own_data=$user_notice_access['own_data'];
										if($own_data == '1')
										{  
											$noticedata=$obj_notice->amgt_get_own_notice_dashboard($user_id);
										}
										else
										{
											$noticedata=$obj_notice->amgt_get_all_notice_dashboard();
										}
									}
									//--- NOTICE DATA FOR ACCOUNTANT  ------//
									elseif($obj_apartment->role=='accountant')
									{
										$own_data=$user_notice_access['own_data'];
										if($own_data == '1')
										{ 
											$noticedata=$obj_notice->amgt_get_own_notice_dashboard($user_id);
										}
										else
										{
											$noticedata=$obj_notice->amgt_get_all_notice_dashboard();
										}
									}
									//--- NOTICE DATA FOR GATEKEEPER  ------//
									else
									{
										$own_data=$user_notice_access['own_data'];
										if($own_data == '1')
										{ 
											$noticedata=$obj_notice->amgt_get_own_notice_dashboard($user_id);
										}
										else
										{
											$noticedata=$obj_notice->amgt_get_all_notice_dashboard();
										}
									}								
								//$noticedata=$obj_notice->amgt_get_notice_list_ondashboard();								
								if(!empty($noticedata))
								{
									foreach ($noticedata as $retrieved_data)
									{
									?>			
										<div class="calendar-event view-notice" id="<?php echo esc_attr($retrieved_data->id);?>"> 
											<p class="remainder_title_pr Bold viewpriscription show_task_event" id="" model="Prescription Details" >  <?php esc_html_e('Notice Title','apartment_mgt');?> : 
											<?php echo esc_html($retrieved_data->notice_title); ?>
											</p>
											<p class="remainder_date_pr"><?php  echo date(amgt_date_formate(),strtotime($retrieved_data->valid_date)); ?></p>
											<p class="remainder_title_pr  viewpriscription" id="22" data-toggle="modal" data-target="#myModal1">
											<?php esc_html_e('Description','apartment_mgt');?> : 
											<?php echo esc_html($retrieved_data->description); ?>
											</p>
										</div>	
									<?php 
									}
								} 
								else 
								{ ?>
									<div class="calendar-event"> 
										<p class="remainder_title_pr Bold viewpriscription show_task_event" id="" model="Prescription Details"> <?php esc_html_e('No Notice Found','apartment_mgt');?>
										</p>
									</div>	
								<?php 
								} ?>		
							</div>                        
						</div>
					</div>
				<?php
				}
				$page='complaint';
				$complaint=amgt_page_access_rolewise_accessright_dashboard($page);
				if($complaint==1)
				{
				?>
				 <div class="panel panel-white Appoinment dasboard_complain">
					<div class="panel-heading">
						<img class="dashboard_icons" src="<?php echo AMS_PLUGIN_URL."/assets/images/icon/Complaint.png"?>">
						<h3 class="panel-title notice_event_flot"><?php esc_html_e('Complain','apartment_mgt');?><span class="float_right" ><a href="<?php echo home_url().'?apartment-dashboard=user&page=complaint';?>"><i class="fa fa-align-justify" aria-hidden="true"></i></a></span></h3>						
					</div>
					<div class="panel-body">
						<div class="events">
							<?php
							$obj_complaint=new Amgt_Complaint;
							$user_complaint_access=amgt_get_userrole_wise_filter_access_right_array('complaint');
							$user_id=get_current_user_id();
							//--- Complain DATA FOR MEMBER  ------//
							if($obj_apartment->role=='member')
							{
								$own_data=$user_complaint_access['own_data'];
								if($own_data == '1')
								{
									$complaintsdata=$obj_complaint->amgt_get_own_created_complaints_dashboard($user_id);
								}
								else
								{
									$complaintsdata=$obj_complaint->amgt_get_all_complaints_dashboard();
								}
							} 
							//--- Complain DATA FOR STAFF MEMBER  ------//
							elseif($obj_apartment->role=='staff_member')
							{
								$own_data=$user_complaint_access['own_data'];
								if($own_data == '1')
								{  
									$complaintsdata=$obj_complaint->amgt_get_own_created_complaints_dashboard($user_id);
								}
								else
								{
									$complaintsdata=$obj_complaint->amgt_get_all_complaints_dashboard();
								}
							}
							//--- Complain DATA FOR ACCOUNTANT  ------//
							elseif($obj_apartment->role=='accountant')
							{
								$own_data=$user_complaint_access['own_data'];
								if($own_data == '1')
								{ 
									$complaintsdata=$obj_complaint->amgt_get_own_created_complaints_dashboard($user_id);
								}
								else
								{
									$complaintsdata=$obj_complaint->amgt_get_all_complaints_dashboard();
								}
							}
							//--- Complain DATA FOR GATEKEEPER  ------//
							else
							{
								$own_data=$user_complaint_access['own_data'];
								if($own_data == '1')
								{ 
									$complaintsdata=$obj_complaint->amgt_get_own_created_complaints_dashboard($user_id);
								}
								else
								{
									$complaintsdata=$obj_complaint->amgt_get_all_complaints_dashboard();
								}
							}
							
						    if(!empty($complaintsdata))
						    {
							foreach ($complaintsdata as $retrieved_data){
							$user=get_userdata($retrieved_data->created_by);
							?>				
									<div class="calendar-event view-complaint" id="<?php echo esc_attr($retrieved_data->id);?>"> 
									
									<p class="remainder_title_pr Bold viewpriscription show_task_event" model="Prescription Details" >  <?php esc_html_e('Complain Title','apartment_mgt');?> : 
									<?php echo esc_html($retrieved_data->complain_title); ?></p>
									
									<p class="remainder_date_pr"><?php if($retrieved_data->complain_date!="0000-00-00" && "00/00/0000" && "00/00/0000"){ echo date(amgt_date_formate(),strtotime($retrieved_data->complain_date)); }else{ echo "-"; } ;?></p>
									
									<p class="remainder_title_pr  viewpriscription" id="22" data-toggle="modal" data-target="#myModal1">
									<?php esc_html_e('Description','apartment_mgt');?> : 
									<?php echo esc_html($retrieved_data->complaint_description); ?>
									</p>
									
									
									</div>	
							<?php } }else 
							{ ?>
							<div class="calendar-event"> 
									
									<p class="remainder_title_pr Bold viewpriscription show_task_event" id="" model="Prescription Details" >  <?php esc_html_e('No Complains Found','apartment_mgt');?>
										</p>
									
									
									</div>	
							<?php } ?>							
							</div>    				
					</div>
				</div>
				<?php
				}
				?>
				<div class="panel panel-white">
				   <div class="panel-heading margin_bottom_15">
						<img class="dashboard_icons" src="<?php echo AMS_PLUGIN_URL."/assets/images/icon/calender.png"?>">
						<h3 class="panel-title notice_event_flot"><?php esc_html_e('Calendar','apartment_mgt');?></h3>			
					</div>
					<div class="panel-body dasboard_calander">
						<div id="calendar"></div>
					</div>
				</div>
		    </div>
				 
		    <div class="col-sm-6">
			<?php
				$page='resident_unit';
				$resident_unit=amgt_page_access_rolewise_accessright_dashboard($page);
				if($resident_unit==1)
				{
				?>
			    <div class="panel panel-white event priscription dashboard_bulding_list_scroll">
					<div class="panel-heading ">					
					<img class="dashboard_icons" src="<?php echo AMS_PLUGIN_URL."/assets/images/icon/Assets--Inventory-Tracker.png"?>">
						<h3 class="panel-title notice_event_flot"><?php esc_html_e('Buildings Units','apartment_mgt');?><span class="float_right" ><a href="<?php echo home_url().'?apartment-dashboard=user&page=resident_unit';?>"><i class="fa fa-align-justify" aria-hidden="true"></i></a></span></h3>						
					</div>
					<div class="panel-body">
						<table class="table table-borderless">
							<thead>
								<tr>
								  <th scope="col compound_unit_dash"><?php esc_html_e('Unit Name','apartment_mgt');?></th>
								  <th scope="col compound_unit_dash"><?php esc_html_e('Unit Category','apartment_mgt');?></th>
								  <th scope="col compound_unit_dash"><?php esc_html_e('Building Name','apartment_mgt');?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$obj_units=new Amgt_ResidentialUnit;
								$user_resident_unit_access=amgt_get_userrole_wise_filter_access_right_array('resident_unit');
								$user_id=get_current_user_id();
								//--- RESIDENT DATA FOR MEMBER  ------//
								if($obj_apartment->role=='member')
								{
									$own_data=$user_resident_unit_access['own_data'];
									if($own_data == '1')
									{
										$residentialdata=$obj_units->amgt_get_all_residentials();
									}
									else
									{
										$residentialdata=$obj_units->amgt_get_all_residentials();
									}
								} 
								//--- RESIDENT DATA FOR STAFF MEMBER  ------//
								elseif($obj_apartment->role=='staff_member')
								{
									$own_data=$user_resident_unit_access['own_data'];
									if($own_data == '1')
									{ 
										$residentialdata=$obj_units->amgt_get_all_residentials_created_by($user_id);
									}
									else
									{
										$residentialdata=$obj_units->amgt_get_all_residentials();
									}
								}
								//--- RESIDENT DATA FOR ACCOUNTANT  ------//
								elseif($obj_apartment->role=='accountant')
								{
									$own_data=$user_resident_unit_access['own_data'];
									if($own_data == '1')
									{ 
										$residentialdata=$obj_units->amgt_get_all_residentials_created_by($user_id);
									}
									else
									{
										$residentialdata=$obj_units->amgt_get_all_residentials();
									}
								}
								//--- RESIDENT DATA FOR GATEKEEPER  ------//
								else
								{
									$own_data=$user_resident_unit_access['own_data'];
									if($own_data == '1')
									{ 
										$residentialdata=$obj_units->amgt_get_all_residentials_created_by($user_id);
									}
									else
									{
										$residentialdata=$obj_units->amgt_get_all_residentials();
									}
								}
								
								if(!empty($residentialdata))
								{
									foreach ($residentialdata as $retrieved_data)
									{
										$units_data=array();
										$units_data=json_decode($retrieved_data->units);
										$i = 0;
										foreach($units_data as $unit)
										{
											
											?>
												<tr>
												<?php
													if($obj_apartment->role=='member')
													{
														$own_data=$user_resident_unit_access['own_data'];
														if($own_data == '1')
														{
															$unit_name=get_user_meta($user_id,'unit_name',true);
															$building_id=get_user_meta($user_id,'building_id',true);
															if($unit->entry == $unit_name && $retrieved_data->building_id == $building_id)
															{ 
														?>
															  <td class="border_bottom_1_dash"><?php echo esc_html($unit->entry);?></td>
															  <td class="unit border_bottom_1_dash"><?php $unit_cat=get_post($retrieved_data->unit_cat_id); echo $unit_cat->post_title;?></td>
															  <td class="building_id border_bottom_1_dash"><span class="btn btn-success btn-xs"><?php $building = get_post($retrieved_data->building_id); echo esc_html($building->post_title);?></span></td>
														  <?php
															}
														}
														else
														{ ?>
															<td class="border_bottom_1_dash"><?php echo $unit->entry;?></td>
															  <td class="unit  border_bottom_1_dash"><?php $unit_cat=get_post($retrieved_data->unit_cat_id); echo esc_html($unit_cat->post_title);?></td>
															  <td class="building_id border_bottom_1_dash"><span class="btn btn-success btn-xs"><?php $building = get_post($retrieved_data->building_id); echo esc_html($building->post_title);?></span></td>
														<?php
														}
													}
													else
													{
														?>
															<td class="border_bottom_1_dash"><?php echo $unit->entry;?></td>
															<td class="unit border_bottom_1_dash"><?php $unit_cat=get_post($retrieved_data->unit_cat_id); echo esc_html($unit_cat->post_title);?></td>
															<td class="building_id border_bottom_1_dash"><span class="btn btn-success btn-xs"><?php $building = get_post($retrieved_data->building_id); echo esc_html($building->post_title);?></span></td>
													<?php
													}
													?>
												</tr>
												<?php 
										}
									} 
								}
							 else 
								{ ?>
									<div class="calendar-event"> 	
										<p class="remainder_title_pr Bold" id="" model="Prescription Details" >  <?php esc_html_e('No Building Units Found','apartment_mgt');?>
										</p>					
									</div>	
							 <?php } ?>	
							</tbody>
						</table>               
					</div>
				</div>
				<?php
				}
				$page='services';
				$services=amgt_page_access_rolewise_accessright_dashboard($page);
				if($services==1)
				{
				?>
				<div class="panel panel-white Appoinment dasboard_services">
					<div class="panel-heading">
					<img class="dashboard_icons" src="<?php echo AMS_PLUGIN_URL."/assets/images/icon/services.png"?>">
					<h3 class="panel-title notice_event_flot"><?php esc_html_e('Service','apartment_mgt');?><span class="float_right" ><a href="<?php echo home_url().'?apartment-dashboard=user&page=services';?>"><i class="fa fa-align-justify" aria-hidden="true"></i></a></span></h3>					
					</div>
					<div class="panel-body">
						<div class="events">
							<?php	
								$user_id=get_current_user_id();
								$user_services_access=amgt_get_userrole_wise_filter_access_right_array('services');
								$obj_service =new Amgt_Service;
								//--- SERVICES DATA FOR MEMBER  ------//
								if($obj_apartment->role=='member')
								{
									$own_data=$user_services_access['own_data'];
									if($own_data == '1')
									{
										$service_data= $obj_service->amgt_get_own_service_dashboard($user_id);
									}
									else
									{
										$service_data= $obj_service->amgt_get_all_dashboard_service();
									}
								} 
								//--- SERVICES DATA FOR STAFF MEMBER  ------//
								elseif($obj_apartment->role=='staff_member')
								{
									$own_data=$user_services_access['own_data'];
									if($own_data == '1')
									{  
										$service_data= $obj_service->amgt_get_own_service_dashboard($user_id);
									}
									else
									{
										$service_data= $obj_service->amgt_get_all_dashboard_service();
									}
								}
								//--- SERVICES DATA FOR ACCOUNTANT  ------//
								elseif($obj_apartment->role=='accountant')
								{
									$own_data=$user_services_access['own_data'];
									if($own_data == '1')
									{ 
										$service_data= $obj_service->amgt_get_own_service_dashboard($user_id);
									}
									else
									{
										$service_data= $obj_service->amgt_get_all_dashboard_service();
									}
								}
								//--- SERVICES DATA FOR GATEKEEPER  ------//
								else
								{
									$own_data=$user_services_access['own_data'];
									if($own_data == '1')
									{ 
										$service_data= $obj_service->amgt_get_own_service_dashboard($user_id);
									}
									else
									{
										$service_data= $obj_service->amgt_get_all_dashboard_service();
									}
								}							
							  // $service_data= $obj_service->amgt_get_all_dashboard_service();
								if(!empty($service_data))
								{
									foreach ($service_data as $retrieved_data)
									{ ?>			
										<div class="calendar-event view-service" id="<?php echo esc_attr($retrieved_data->service_id);?>"> 
											<p class="remainder_title_pr Bold"  model="Prescription Details" >  <?php esc_html_e('Service Name','apartment_mgt');?> : 
											<?php echo esc_html($retrieved_data->service_name); ?></p>
											
											<p class="remainder_date_pr"><?php if($retrieved_data->created_date!="0000-00-00" && "00/00/0000" && "00/00/0000"){ echo date(amgt_date_formate(),strtotime($retrieved_data->created_date)); }else{ echo "-"; } ;?></p>
											
											<p class="remainder_title_pr  viewpriscription" id="22" data-toggle="modal" data-target="#myModal1">
											<?php esc_html_e('Service Provider','apartment_mgt');?> : 
											<?php echo esc_html($retrieved_data->service_provider); ?>
											</p>
										</div>	
									<?php 
									} 
								} 
								else 
								{ ?>
									<div class="calendar-event"> 	
										<p class="remainder_title_pr Bold" model="Prescription Details" >  <?php esc_html_e('No Services Found','apartment_mgt');?>
										</p>					
									</div>	
							 <?php 
								} ?>
						</div>    				
					</div>
				</div>
				<?php
				}
				$page='gatekeeper';
				$gatekeeper=amgt_page_access_rolewise_accessright_dashboard($page);
				if($gatekeeper==1)
				{
				?>
				<div class="panel panel-white event assignbed dashboard_gatekeeper_list_scroll">
					<div class="panel-heading">
						<img class="dashboard_icons" src="<?php echo AMS_PLUGIN_URL."/assets/images/icon/Gatekeeper.png"?>">
						<h3 class="panel-title notice_event_flot"><?php esc_html_e('Gatekeeper ','apartment_mgt');?></h3>						
					</div>
					<div class="panel-body">
						<div class="events">
							<?php 
							$user_id=get_current_user_id();
							$user_gatekeeper_access=amgt_get_userrole_wise_filter_access_right_array('services');
							//--- MEMBER DATA FOR MEMBER  ------//
							if($obj_apartment->role=='member')
							{
								$own_data=$user_gatekeeper_access['own_data'];
								if($own_data == '1')
								{
									$get_members = array('role' => 'gatekeeper','meta_key'  => 'created_by','meta_value' =>$user_id);
									$membersdata=get_users($get_members);
								}
								else
								{
									$get_members = array('role' => 'gatekeeper');
									$membersdata=get_users($get_members);
								}
							} 
							//--- MEMBER DATA FOR STAFF MEMBER  ------//
							elseif($obj_apartment->role=='staff_member')
							{
								$own_data=$user_gatekeeper_access['own_data'];
								if($own_data == '1')
								{  
									$get_members = array('role' => 'gatekeeper','meta_key'  => 'created_by','meta_value' =>$user_id);
									$membersdata=get_users($get_members);
								}
								else
								{
									$get_members = array('role' => 'gatekeeper');
									$membersdata=get_users($get_members);
								}
							}
							//--- MEMBER DATA FOR ACCOUNTANT  ------//
							elseif($obj_apartment->role=='accountant')
							{
								$own_data=$user_gatekeeper_access['own_data'];
								if($own_data == '1')
								{ 
									$get_members = array('role' => 'gatekeeper','meta_key'  => 'created_by','meta_value' =>$user_id);
									$membersdata=get_users($get_members);
								}
								else
								{
									$get_members = array('role' => 'gatekeeper');
									$membersdata=get_users($get_members);
								}
							}
							//--- MEMBER DATA FOR GATEKEEPER  ------//
							else
							{
								$own_data=$user_gatekeeper_access['own_data'];
								if($own_data == '1')
								{ 
									$membersdata[]=get_userdata($user_id);
								}
								else
								{
									$get_members = array('role' => 'gatekeeper');
									$membersdata=get_users($get_members);
								}
							}
							if(!empty($membersdata))
							{
								foreach ($membersdata as $retrieved_data)
								{		
									global $wpdb;
									$table_amgt_gates = $wpdb->prefix. 'amgt_gates';
									$gatedata = $wpdb->get_row("SELECT gate_name FROM $table_amgt_gates where id=".$retrieved_data->aasigned_gate);
								?>
								
									<div class="calendar-event"> 
										<p class="remainder_title_pr Bold viewpriscription show_task_event" id="" model="Prescription Details" >  <?php esc_html_e('Gatekeeper Name','apartment_mgt');?> : 
										<?php echo esc_html($retrieved_data->display_name); ?></p>
										<p class="remainder_date_pr"><?php echo esc_html($gatedata->gate_name); ?></p>
									</div>	
							 <?php 
								} 
							}
							else 
							{ ?>
								<div class="calendar-event"> 	
									<p class="remainder_title_pr Bold viewpriscription" model="Prescription Details" >  <?php esc_html_e('No Gatekeeper Found','apartment_mgt');?>
									</p>					
								</div>	
						 <?php 
							} ?>											
						</div>                       
					</div>
				</div>
				<?php
				}
				$page='accounts';
				$accounts=amgt_page_access_rolewise_accessright_dashboard($page);
				if($accounts==1)
				{
				?>

				<div class="panel panel-white event assignbed dasboard_invoice">
					<div class="panel-heading">
						<img class="dashboard_icons" src="<?php echo AMS_PLUGIN_URL."/assets/images/icon/document.png"?>">
						<h3 class="panel-title notice_event_flot"><?php esc_html_e('Invoice ','apartment_mgt');?><span class="float_right" ><a href="<?php echo home_url().'?apartment-dashboard=user&page=accounts';?>"><i class="fa fa-align-justify" aria-hidden="true"></i></a></span></h3>					
					</div>
					<div class="panel-body">
						<div class="events overflow_auto_res">
							<table class="table table-borderless">
								<thead>
									<tr>
									  <th scope="col compound_unit_dash"><?php esc_html_e('Invoice No','apartment_mgt');?></th>
									  <th scope="col compound_unit_dash"><?php esc_html_e('Member Name','apartment_mgt');?></th>
									  <th scope="col compound_unit_dash"><?php esc_html_e('Total Amount','apartment_mgt');?></th>
									  <th scope="col compound_unit_dash"><?php esc_html_e('Payment Status','apartment_mgt');?></th>
									  
									</tr>
								</thead>
								<tbody>
									<?php 
									$user_id=get_current_user_id();
									$user_accounts_access=amgt_get_userrole_wise_filter_access_right_array('accounts');
									//--- INVOICE DATA FOR MEMBER  ------//
									if($obj_apartment->role=='member')
									{
										$own_data=$user_accounts_access['own_data'];
										if($own_data == '1')
										{
											$invoice_data= $obj_account->amgt_get_member_all_invoice_dashboard();		
										}
										else
										{
											$invoice_data= $obj_account->amgt_get_all_invoice_dashboard();		
										}
									} 
									//--- INVOICE DATA FOR STAFF MEMBER  ------//
									elseif($obj_apartment->role=='staff_member')
									{
										$own_data=$user_accounts_access['own_data'];
										if($own_data == '1')
										{  
											$invoice_data= $obj_account->amgt_get_own_invoice_dashboard($user_id);	
										}
										else
										{
											$invoice_data= $obj_account->amgt_get_all_invoice_dashboard();	
										}
									}
									//--- INVOICE DATA FOR ACCOUNTANT  ------//
									elseif($obj_apartment->role=='accountant')
									{
										$own_data=$user_accounts_access['own_data'];
										if($own_data == '1')
										{ 
											$invoice_data= $obj_account->amgt_get_own_invoice_dashboard($user_id);	
										}
										else
										{
											$invoice_data= $obj_account->amgt_get_all_invoice_dashboard();	
										}
									}
									//--- INVOICE DATA FOR GATEKEEPER  ------//
									else
									{
										$own_data=$user_accounts_access['own_data'];
										if($own_data == '1')
										{ 
											$invoice_data= $obj_account->amgt_get_own_invoice_dashboard($user_id);	
										}
										else
										{
											$invoice_data= $obj_account->amgt_get_all_invoice_dashboard();	
										}
									}
									
									if(!empty($invoice_data))
									{
										foreach ($invoice_data as $retrieved_data)
										{	
											$member_id=$retrieved_data->member_id;
											$chargedata=amgt_get_invoice_charges_calculate_by($retrieved_data->charges_id);
											if(empty($retrieved_data->invoice_no))
											{
												$invoice_no='-';
												$charge_cal_by='Fix Charges';
												$charge_type=get_the_title($retrieved_data->charges_type_id);
											}
											else
											{
												$invoice_no=$retrieved_data->invoice_no;
												if($chargedata->charges_calculate_by=='fix_charge')
												{
													$charge_cal_by='Fix Charges';
												}
												else
												{
													$charge_cal_by='Measurement Charge';
												}
												if($retrieved_data->charges_type_id=='0')
												{
													$charge_type='Maintenance Charges';
												}
												else
												{
													$charge_type=get_the_title($retrieved_data->charges_type_id);
												}
											}
											
											$userdata=get_userdata($member_id);
												
											?>
										<tr>
										  <td class="border_bottom_1_dash"><?php echo esc_html(get_option('invoice_prefix').''.$invoice_no);?></td>
										  <td class="border_bottom_1_dash"><?php echo esc_html($userdata->display_name);?></td>
										  <?php
											if(empty($retrieved_data->invoice_no))
											{
												$invoice_no='-';
												$charge_cal_by='Fix Charges';
												$entry=json_decode($retrieved_data->charges_payment);
												$entry_amount='0';
												foreach($entry as $entry_data)
												{
													$entry_amount+=$entry_data->amount;
												}
												$discount_amount=$retrieved_data->discount_amount;
												$after_discount_amount=$entry_amount-$discount_amount;
												$total_amount=round($after_discount_amount);
												$due_amount='0';
												$paid_amount=round($after_discount_amount);
												$payment_status=$retrieved_data->payment_status;
											}
											else
											{													  
												$invoice_length=strlen($retrieved_data->invoice_no);
												if($invoice_length == '9')
												{
													$total_amount=round($retrieved_data->invoice_amount);
													$due_amount=round($retrieved_data->invoice_amount) - round($retrieved_data->paid_amount);
													if($retrieved_data->payment_status=='Unpaid')
													{
														$payment_status= esc_html__('Unpaid','apartment_mgt');
													}
													elseif($retrieved_data->payment_status=='Paid' || $retrieved_data->	payment_status=='Fully Paid')
													{																
														$payment_status= esc_html__('Fully Paid','apartment_mgt');
													}
													elseif($retrieved_data->payment_status=='Partially Paid')
													{
														$payment_status= esc_html__('Partially Paid','apartment_mgt');
													}			
												}													    
												else
												{
													$total_amount=round($retrieved_data->total_amount);
													$due_amount=round($retrieved_data->due_amount);
													if($retrieved_data->payment_status=='Unpaid')
													{
														$payment_status= esc_html__('Unpaid','apartment_mgt');
													}
													elseif($retrieved_data->payment_status=='Paid' || $retrieved_data->	payment_status=='Fully Paid')
													{																
														$payment_status= esc_html__('Fully Paid','apartment_mgt');
													}
													elseif($retrieved_data->payment_status=='Partially Paid')
													{
														$payment_status= esc_html__('Partially Paid','apartment_mgt');
													}
													//$payment_status=$retrieved_data->payment_status;
												}
												$paid_amount=$retrieved_data->paid_amount;
											}
											?>
										  <td class="building_id border_bottom_1_dash"><?php   echo amgt_get_currency_symbol(get_option( 'apartment_currency_code' )); echo esc_html($total_amount);?></td>
										  <td class="building_id border_bottom_1_dash"><span class="btn btn-success btn-xs"><?php _e("$payment_status","apartment_mgt");?></span></td>
										  
										</tr>
								<?php 
										} 
									}
									else 
									{ ?>
										<div class="calendar-event"> 	
												
											<tr>
											  <td colspan="4" class="border_bottom_1_dash text_align_center"><?php esc_html_e('No Invoice Found','apartment_mgt');?></td>
											  
											</tr>													
										</div>	
								 <?php } ?>		
								</tbody>
							</table>							
						</div>                      
					</div>
			    </div>
				<?php
				}
				?>
		    </div>
        </div>
				</div>
			</div>
	    </div>
    </div>
</body>
</html>