<?php 
// THIS IS CLASS AT ADMIN SIDE!
$obj_message = new Amgt_message();
$active_tab = isset($_GET['tab'])?$_GET['tab']:'inbox';
?>
<div class="page-inner min_height_1631">
	<div class="page-title"><!--PAGE TITLE---->
			<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?></h3>
	</div><!--END PAGE TITLE---->
	<?php 
	if(isset($_POST['save_message']))//SAVE MESSAGE
	{
		$nonce = $_POST['_wpnonce'];
		if (wp_verify_nonce( $nonce, 'save_message_nonce' ) )
		{
			$result = $obj_message->amgt_add_message($_POST);
		}
	}
	
	if(isset($result))
	{
		wp_redirect ( admin_url() . 'admin.php?page=amgt-message&tab=inbox&message=1');
	}
	if(isset($_REQUEST['message']))
	{
		$message =$_REQUEST['message'];
		if($message == 1)
		{?>
				<div id="message" class="updated below-h2 notice is-dismissible">
				<p>
				<?php 
					esc_html_e('Message sent successfully','apartment_mgt');
				?></p></div>
				<?php 
			
		}
		elseif($message == 2)
		{?><div id="message" class="updated below-h2 notice is-dismissible"><p><?php
					_e("Message deleted successfully",'apartment_mgt');
					?></p>
					</div>
				<?php 
		}
	}	
	?>
	
	<div id="main-wrapper"><!--MAIN WRAPPER-->	
		<div class="row mailbox-header"><!--MAILBOX HEADER -->	
			<div class="col-md-2 col-sm-3 col-xs-4">
				<a class="btn btn-success btn-block" href="?page=amgt-message&tab=compose"><?php esc_html_e('Compose','apartment_mgt');?></a>
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
				?>
			</h2>
			</div>
								   
		</div>
		<div class="col-md-2 col-sm-3 col-xs-12">
			<ul class="list-unstyled mailbox-nav">
				<li <?php if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox')){?>class="active"<?php }?>>
				<a href="?page=amgt-message&tab=inbox"><i class="fa fa-inbox"></i> <?php esc_html_e('Inbox','apartment_mgt');?><span class="badge badge-success pull-right"><?php echo count($obj_message->amgt_count_inbox_item(get_current_user_id()));?></span></a></li>
				<li <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox'){?>class="active"<?php }?>><a href="?page=amgt-message&tab=sentbox"><i class="fa fa-sign-out"></i><?php esc_html_e('Sent','apartment_mgt');?></a></li>                                
			</ul>
		</div>
		<div class="col-md-10 col-sm-9 col-xs-12">
		 <?php  
			if(isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'sentbox')
				require_once AMS_PLUGIN_DIR. '/admin/message/sendbox.php';
			if(!isset($_REQUEST['tab']) || ($_REQUEST['tab'] == 'inbox'))
				require_once AMS_PLUGIN_DIR. '/admin/message/inbox.php';
			if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'compose'))
				require_once AMS_PLUGIN_DIR. '/admin/message/composemail.php';
			if(isset($_REQUEST['tab']) && ($_REQUEST['tab'] == 'view_message'))
				require_once AMS_PLUGIN_DIR. '/admin/message/view_message.php';
			?>
		</div>
    </div><!-- END Main-wrapper -->
</div><!-- Page-inner -->
<?php ?>