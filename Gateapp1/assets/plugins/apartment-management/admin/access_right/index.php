<?php 
	$active_tab = isset($_GET['tab'])?$_GET['tab']:'Member';
?>
<!-- View Popup Code start -->	
<div class="popup-bg">
    <div class="overlay-content">
    	<div class="notice_content"></div>    
    </div> 
</div>	
<!-- View Popup Code end -->
	
<!-- page inner div start-->
<div class="page-inner min_height_1631">
	<!-- Page Title div start -->
	<div class="page-title">
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?></h3>
	</div>
	<!-- Page Title div end -->
	<!--  main-wrapper div start  -->
	<div  id="main-wrapper" class="notice_page font_size_access">
	<?php
	$message = isset($_REQUEST['message'])?$_REQUEST['message']:'0';
	switch($message)
	{
		case '1':
			$message_string = esc_html__('Record Updated Successfully.','apartment_mgt');
			break;		
	}
	if($message)
	{ ?>
		<div id="message" class="updated below-h2 notice is-dismissible">
			<p><?php echo $message_string;?></p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
<?php } ?>
		<!-- panel-white div start  -->
		<div class="panel panel-white">
			<!-- panel-body div start  -->
			<div class="panel-body">
				<h2 class="nav-tab-wrapper">
					<a href="?page=amgt-access_right&tab=Member" class="nav-tab <?php echo $active_tab == 'Member' ? 'nav-tab-active' : ''; ?>">
					<?php echo '<span class="dashicons dashicons-menu"></span>'.esc_html__('Member', 'apartment_mgt'); ?></a>

					<a href="?page=amgt-access_right&tab=Staff_Member" class="nav-tab <?php echo $active_tab == 'Staff_Member' ? 'nav-tab-active' : ''; ?>">
					<?php echo '<span class="dashicons dashicons-menu"></span>'.esc_html__('Staff Member', 'apartment_mgt'); ?></a> 
			 
					<a href="?page=amgt-access_right&tab=Accountant" class="nav-tab <?php echo $active_tab == 'Accountant' ? 'nav-tab-active' : ''; ?>">
					<?php echo '<span class="dashicons dashicons-menu"></span>'.esc_html__('Accountant', 'apartment_mgt'); ?></a> 
			  
					<a href="?page=amgt-access_right&tab=Gatekeeper" class="nav-tab <?php echo $active_tab == 'Gatekeeper' ? 'nav-tab-active' : ''; ?>">
					<?php echo '<span class="dashicons dashicons-menu"></span>'.esc_html__('Gatekeeper', 'apartment_mgt'); ?></a> 
					
				</h2>
				<div class="clearfix"></div>
				<?php
				if($active_tab == 'Member')
				 {
					require_once AMS_PLUGIN_DIR. '/admin/access_right/Member.php';					
				 }
				 
				 elseif($active_tab == 'Staff_Member')
				 {
					require_once AMS_PLUGIN_DIR. '/admin/access_right/Staff_Member.php';
				 }
				 
				 elseif($active_tab == 'Accountant')
				 {
					require_once AMS_PLUGIN_DIR. '/admin/access_right/Accountant.php';
				 }
				 
				 elseif($active_tab == 'Gatekeeper')
				 {
					require_once AMS_PLUGIN_DIR. '/admin/access_right/Gatekeeper.php';
				 }	
				 ?> 
			</div>
			<!-- panel-body div end -->
	 	</div>
		<!-- panel-white div end -->
	</div>
	<!--  main-wrapper div end -->
</div>
<!-- page inner div end -->
<?php ?>