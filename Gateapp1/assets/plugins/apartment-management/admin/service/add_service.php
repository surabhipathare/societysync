<?php $role='staff-member';?>
<script type="text/javascript">
$(document).ready(function() {
	 //SERVICE FORM VALIDATIONENGINE
	"use strict";
	$('#service_form').validationEngine({promptPosition : "topRight",maxErrorsPerField: 1});
	$('.onlyletter_number_space_validation').keypress(function( e ) 
		{     
			var regex = new RegExp("^[0-9a-zA-Z \b]+$");
			var key = String.fromCharCode(!event.charCode ? event.which: event.charCode);
			if (!regex.test(key)) 
			{
				event.preventDefault();
				return false;
			} 
	   });  
} );
</script>
     <?php 	

        	$service_id=0;
			if(isset($_REQUEST['service_id']))
				$service_id=$_REQUEST['service_id'];
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{
					$edit=1;
					$result = $obj_service->amgt_get_single_service($service_id);
				} ?>
		<div class="panel-body"> <!--  PANEL BODY DIV-->
             <!-----ADD SERVICE FORM----->  		
			<form name="service_form" action="" method="post" class="form-horizontal" id="service_form">
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="service_id" value="<?php echo esc_attr($service_id);?>"  />
				<div class="form-group">
					<label class="col-sm-2 control-label" for="service_name">
					<?php esc_html_e('Service Name','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="service_name" maxlength="50" class="form-control text-input validate[required,custom[onlyLetter_specialcharacter]]" 
						type="text" value="<?php if($edit){ echo esc_attr($result->service_name);}
						elseif(isset($_POST['service_name'])) echo esc_attr($_POST['service_name']);?>" name="service_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="service_provider">
					<?php esc_html_e('Service Provider Name','apartment_mgt');?> <span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="service_provider" maxlength="50" class="form-control validate[required,custom[onlyLetter_specialcharacter]]" type="text"  
						value="<?php if($edit){ echo esc_attr($result->service_provider);}
						elseif(isset($_POST['service_provider'])) echo esc_attr($_POST['service_provider']);?>" name="service_provider">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="contact_number">
					<?php esc_html_e('Contact Number','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="contact_number" class="form-control validate[required,custom[phone_number]] text-input" 
						type="number" min="0" onKeyPress="if(this.value.length==15) return false;"  value="<?php if($edit){ echo esc_attr($result->contact_number);}elseif(isset($_POST['contact_number'])) 
							echo esc_attr($_POST['contact_number']);?>" name="contact_number">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="mobile_number"><?php esc_html_e('Mobile Number','apartment_mgt');?></label>
					<div class="col-sm-8">
						<input id="mobile_number" class="form-control validate[custom[phone_number]] text-input" 
						type="number" min="0" onKeyPress="if(this.value.length==15) return false;"  value="<?php if($edit){ echo esc_attr($result->mobile_number);}elseif(isset($_POST['mobile_number'])) 
							echo esc_attr($_POST['mobile_number']);?>" name="mobile_number">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label " for="email"><?php esc_html_e('Email','apartment_mgt');?></label>
					<div class="col-sm-8">
						<input id="email" maxlength="50" class="form-control validate[custom[email]] text-input" type="text"  name="email" 
						value="<?php if($edit){ echo esc_attr($result->email);}elseif(isset($_POST['email'])) echo esc_attr($_POST['email']);?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="address"><?php esc_html_e('Address','apartment_mgt');?></label>
					<div class="col-sm-8">
						<textarea id="address" maxlength="150" class="form-control validate[custom[address_description_validation]]" name="address" ><?php if($edit){ echo esc_textarea($result->address);}elseif(isset($_POST['address'])) echo esc_textarea($_POST['address']);?></textarea>
					</div>
				</div>
	            <?php wp_nonce_field( 'save_service_nonce' ); ?>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" 
					value="<?php if($edit){ esc_html_e('Save Service','apartment_mgt'); }else{ esc_html_e('Add Service','apartment_mgt');}?>" 
					name="save_service" 
					class="btn btn-success"/>
				</div>
            </form><!-----END ADD SERVICE FORM----->
        </div> <!--  END PANEL BODY DIV     --> 