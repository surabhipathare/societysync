<?php $role='accountant';?>
<script type="text/javascript">
	$(document).ready(function()
	{
		"use strict";
		$('#staffmember_form').validationEngine();
		$.fn.datepicker.defaults.format =" <?php  echo amgt_dateformat_PHP_to_jQueryUI(amgt_date_formate()); ?>";
        $('#birth_date').datepicker({
          endDate: '+0d',
          autoclose: true
        }); 
		//user name not  allow space validation
		$('#username').keypress(function( e ) {
		   if(e.which === 32) 
			 return false;
		});
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
	   if($active_tab == 'add_accountant')
	    {
        	$accountant_id=0;
			if(isset($_REQUEST['accountant_id']))
				$accountant_id=$_REQUEST['accountant_id'];
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
				{
					$edit=1;
					$result = get_userdata($accountant_id);
				} ?>
				
		<div class="panel-body"><!--PANEL BODY-->
		    <!--STAFF MEMBER FORM-->
			<form name="staffmember_form" action="" method="post" class="form-horizontal" id="staffmember_form">
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="user_id" value="<?php echo esc_attr($accountant_id);?>"  />
				<input type="hidden" name="role" value="<?php echo esc_attr($role);?>"  />
				
					<div class="form-group">
						<label class="col-sm-2 control-label" for="first_name"><?php esc_html_e('First Name','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="first_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo esc_attr($result->first_name);}elseif(isset($_POST['first_name'])) echo esc_attr($_POST['first_name']);?>" name="first_name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="middle_name"><?php esc_html_e('Middle Name','apartment_mgt');?></label>
						<div class="col-sm-8">
							<input id="middle_name" class="form-control validate[custom[onlyLetterSp]]" type="text" maxlength="50" value="<?php if($edit){ echo esc_attr($result->middle_name);}elseif(isset($_POST['middle_name'])) echo esc_attr($_POST['middle_name']);?>" name="middle_name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="last_name"><?php esc_html_e('Last Name','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="last_name" class="form-control validate[required,custom[onlyLetterSp]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo esc_attr($result->last_name);}elseif(isset($_POST['last_name'])) echo esc_attr($_POST['last_name']);?>" name="last_name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="gender"><?php esc_html_e('Gender','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
						<?php $genderval = "male"; if($edit){ $genderval=$result->gender; }elseif(isset($_POST['gender'])) {$genderval=$_POST['gender'];}?>
							<label class="radio-inline">
							 <input type="radio" value="male" class="tog validate[required]" name="gender"  <?php  checked( 'male', $genderval);  ?>/><?php esc_html_e('Male','apartment_mgt');?>
							</label>
							<label class="radio-inline">
							  <input type="radio" value="female" class="tog validate[required]" name="gender"  <?php  checked( 'female', $genderval);  ?>/><?php esc_html_e('Female','apartment_mgt');?> 
							</label>
						</div>
					</div>
					<style>
							.dropdown-menu 
							{
								min-width: 240px;
							}
                    </style>
					<!--ADD ACCOUNTANT INFORMATION-->
					<div class="form-group">
						<label class="col-sm-2 control-label" for="birth_date"><?php esc_html_e('Date of birth','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="birth_date" class="form-control validate[required]" autocomplete="off" type="text"  name="birth_date" 
							value="<?php if($edit){ echo date(amgt_date_formate(),strtotime($result->birth_date));}elseif(isset($_POST['birth_date'])) echo esc_attr($_POST['birth_date']);?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label " for="badge_id"><?php esc_html_e('Badge ID','apartment_mgt');?></label>
						<div class="col-sm-8">
							<input id="text" class="form-control text-input" type="number" onKeyPress="if(this.value.length==10) return false;" name="badge_id" 
							value="<?php if($edit){ echo esc_attr($result->badge_id);}elseif(isset($_POST['badge_id'])) echo esc_attr($_POST['badge_id']);?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="Qualification"><?php esc_html_e('Qualification','apartment_mgt');?></label>
						<div class="col-sm-8">
							<input id="qualification" class="form-control onlyletter_number_space_validation"  maxlength="50" type="text"  value="<?php if($edit){ echo esc_attr($result->qualification);}elseif(isset($_POST['qualification'])) echo esc_attr($_POST['qualification']);?>" name="qualification">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label " for="email"><?php esc_html_e('Email','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="email" class="form-control validate[required,custom[email]] text-input" type="text" maxlength="50" name="email" 
							value="<?php if($edit){ echo esc_attr($result->user_email);}elseif(isset($_POST['email'])) echo esc_attr($_POST['email']);?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label " for="mobile"><?php esc_html_e('Mobile Number','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-1">
						<input type="text" readonly value="+<?php echo amgt_get_countery_phonecode(get_option( 'amgt_contry' ));?>"  class="form-control" name="phonecode">
						</div>
						<div class="col-sm-7">
							<input id="mobile" class="form-control validate[required,custom[phone]] text-input" type="number" min="0" onKeyPress="if(this.value.length==15) return false;" name="mobile" 
							value="<?php if($edit){ echo esc_attr($result->mobile);}elseif(isset($_POST['mobile'])) echo esc_attr($_POST['mobile']);?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="address"><?php esc_html_e('Address','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="address" class="form-control validate[required]" type="text" maxlength="150" name="address" 
							value="<?php if($edit){ echo esc_attr($result->address);}elseif(isset($_POST['address'])) echo esc_attr($_POST['address']);?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="city_name"><?php esc_html_e('City','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input id="city_name" class="form-control validate[required,custom[onlyLetterSp]]" maxlength="50" type="text"  name="city_name" 
							value="<?php if($edit){ echo esc_attr($result->city_name);}elseif(isset($_POST['city_name'])) echo esc_attr($_POST['city_name']);?>">
						</div>
						<label class="col-sm-2 control-label"><?php esc_html_e('State','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input class="form-control validate[required,custom[onlyLetterSp]]" maxlength="50" type="text"  name="state_name" 
							value="<?php if($edit){ echo esc_attr($result->state_name);}elseif(isset($_POST['state_name'])) echo esc_attr($_POST['state_name']);?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php esc_html_e('Country','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input class="form-control validate[required,custom[onlyLetterSp]]" maxlength="50" type="text"  name="country_name" 
							value="<?php if($edit){ echo esc_attr($result->country_name);}elseif(isset($_POST['country_name'])) echo esc_attr($_POST['country_name']);?>">
						</div>
						<label class="col-sm-2 control-label"><?php esc_html_e('Zip Code','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-3">
							<input class="form-control validate[required,custom[onlyLetterNumber]]" maxlength="10" type="text"  name="zipcode" 
							value="<?php if($edit){ echo esc_attr($result->zipcode);}elseif(isset($_POST['zipcode'])) echo esc_attr($_POST['zipcode']);?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="username"><?php esc_html_e('User Name','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="username" class="form-control validate[required]" maxlength="30" type="text"  name="username" 
							value="<?php if($edit){ echo esc_attr($result->user_login);}elseif(isset($_POST['username'])) echo esc_attr($_POST['username']);?>" <?php if($edit) echo "readonly";?>>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="password"><?php esc_html_e('Password','apartment_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
						<div class="col-sm-8">
							<input id="password" class="form-control <?php if(!$edit) echo 'validate[required]';?>" type="password"  name="password" minlength="8" maxlength="12" value="">
						</div>
					</div>
					
					
					<div class="form-group">
						<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Image','apartment_mgt');?></label>
						<div class="col-sm-2">
							<input type="text" id="amgt_user_avatar_url" class="form-control" name="amgt_user_avatar"  
							value="<?php if($edit)echo esc_url( $result->amgt_user_avatar );elseif(isset($_POST['amgt_user_avatar'])) echo $_POST['amgt_user_avatar']; ?>" readonly />
						</div>	
						<div class="col-sm-3">
							 <input id="upload_user_avatar_button" type="button" class="button" value="<?php esc_html_e('Upload image', 'apartment_mgt' ); ?>" />
							 <span class="description"><?php esc_html_e('Upload image', 'apartment_mgt' ); ?></span>
					
						 </div>
						<div class="clearfix"></div>
						
						<div class="col-sm-offset-2 col-sm-8">
							<div id="upload_user_avatar_preview" >
								 <?php if($edit) 
									{
										if($result->amgt_user_avatar == "")
										{?>
											<img class="user_image" alt="" src="<?php echo get_option( 'amgt_system_logo' ); ?>">
										<?php
										}
										else
										{
										?>
										    <img class="user_image" src="<?php if($edit)echo esc_url( $result->amgt_user_avatar ); ?>" />
										<?php 
										}
									}
									else 
									{
									?>
										<img class="user_image" alt="" src="<?php echo get_option( 'amgt_system_logo' ); ?>">
									<?php 
									}?>
							</div>
					 </div>
					</div>
					<?php wp_nonce_field('save_accountant_nonce'); ?>
					<div class="col-sm-offset-2 col-sm-8">
						<input type="submit" value="<?php if($edit){ esc_html_e('Save','apartment_mgt'); }else{ esc_html_e('Add Accountant','apartment_mgt');}?>" name="save_accountant" class="btn btn-success"/>
					</div>
            </form><!--END STAFF MEMBER FORM-->
			<!--End Accountant Information---->
        </div><!--END PANEL BODY-->
  <?php } ?>