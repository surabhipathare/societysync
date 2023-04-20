<?php 
$active_tab = isset($_GET['tab'])?$_GET['tab']:'setup';
?>
<div id="cmgt_imgSpinner1"></div><!-----CMGT_IMGSPINNER----->
<div class="gmgt_ajax-ani"></div>
<div class="gmgt_ajax-img"><img src="<?php echo AMS_PLUGIN_URL.'/assets/images/loading.gif';?>" height="50px" width="50px"></div>
<div class="page-inner min_height_1088">
	<div class="page-title"><!-----PAGE TITLE----->
		<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" /><?php echo get_option( 'amgt_system_name' );?>
		</h3>
	</div>
	 
<?php 
if(isset($_REQUEST['varify_key'])){	//VARIFY_KEY
	$verify_result = amgt_submit_setupform($_POST);	
	if($verify_result['amgt_verify'] != '0'){
		echo '<div id="message" class="updated notice notice-success is-dismissible"><p>'.$verify_result['message'].'</p>
		<button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';			
	}	
}
?>
<script type="text/javascript">
$(document).ready(function() {
	//VARIFICATION FORM VALIDATIONENGINE
	"use strict";
	$('#verification_form').validationEngine();
});
</script>
<?php 
if(isset($_SESSION['amgt_verify']) && $_SESSION['amgt_verify'] == '3')
{	?>
	<div id="message" class="updated notice notice-success">
		<?php esc_html_e('There seems to be some problem please try after sometime or contact us on sales@dasinfomeida.com','apartment_mgt');?>
	</div>
<?php 
}
else { ?>
	<div id="message" class="updated notice notice-success display_none">xxgfxs</div>
<?php } ?>

	<div id="main-wrapper"><!--MAIN WRAPPER--->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-white"><!--PANEL WHITE--->
					<div class="panel-body"><!--PANEL BODY--->
	<!--VARIFICATION FORM--->
   <form name="verification_form" action="" method="post" class="form-horizontal" id="verification_form">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="domain_name">
			<?php esc_html_e('Domain','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="server_name" class="form-control validate[required]" type="text" 
				value="<?php echo $_SERVER['SERVER_NAME'];?>" name="domain_name" readonly>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="licence_key"><?php esc_html_e('Envato License key','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="licence_key" class="form-control validate[required]" type="text"  value="" name="licence_key">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="enter_email"><?php esc_html_e('Email','apartment_mgt');?> <span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="enter_email" class="form-control validate[required,custom[email]]" type="text"  value="" name="enter_email">
			</div>
		</div>
		<div class="col-sm-offset-2 col-sm-8">
        	<input type="submit" value="<?php esc_html_e('Submit','apartment_mgt');?>" name="varify_key" id="varify_key" class="btn btn-success"/>
        </div>
	</form><!--END VARIFICATION FORM--->
	
</div><!--END PANEL BODY--->
			
	</div>
	</div>
</div>
<div>
