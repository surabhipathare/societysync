<?php 		
if(isset($_POST['save_setting']))//SAVE SETTING
{
	$optionval=amgt_option();
	foreach($optionval as $key=>$val)
	{
		if(isset($_POST[$key]))
		{
			$result=update_option( $key, $_POST[$key] );			
		}
	}
	//	UPDATE GENERAL SETTINGS OPTION
	if(isset($_REQUEST['amgt_paymaster_pack']))
	{
		update_option( 'amgt_paymaster_pack', 'yes' );
	}
	else
	{
		update_option( 'amgt_paymaster_pack', 'no' );
	}
	
	if(isset($_REQUEST['apartment_enable_maintenance']))
			update_option( 'apartment_enable_maintenance', 'yes' );
		else 
			update_option( 'apartment_enable_maintenance', 'no' );
		
	if(isset($_REQUEST['apartment_enable_chargis']))
			update_option( 'apartment_enable_chargis', 'yes' );
		else 
			update_option( 'apartment_enable_chargis', 'no' );
		
	if(isset($_REQUEST['apartment_enable_notifications']))
			update_option( 'apartment_enable_notifications', 'yes' );
		else 
			update_option( 'apartment_enable_notifications', 'no' );
	if(get_option('apartment_enable_maintenance')=='yes')
	{
		$postavailable=post_exists('Apartment Maintenance Charge');//APARTMENT MAINTENACE CHARGE
		if(!$postavailable)
		{
			$result = wp_insert_post( array(

			'post_status' => 'publish',

			'post_type' => 'income_type',

			'post_title' => 'Apartment Maintenance Charge') );
		}
	}
	
	if(isset($_POST['amgt_login_page']))//AMGT_LOGIN_PAGE
	{
		$result=update_option( 'amgt_login_page', $_POST['amgt_login_page'] );
	}
	if(isset($_REQUEST['apartment_enable_sandbox']))
			update_option( 'apartment_enable_sandbox', 'yes' );
		else 
			update_option( 'apartment_enable_sandbox', 'no' );
		
	if(isset($result))
	{?>
					<div id="message" class="updated below-h2 notice is-dismissible">
						<p><?php esc_html_e('Record updated successfully','apartment_mgt');?></p>
					</div>
		<?php 
	}
}

?>
<script type="text/javascript">
$(document).ready(function()
{   //SETTING FORM VALIDATIONENGINE
	"use strict";
	$('#setting_form').validationEngine();
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
   //NOT  ALLOW SPACE VALIDATION
	$('.space_not_allow').keypress(function( e ) {
	   if(e.which === 32) 
		 return false;
	});
} );
</script>
<div class="page-inner min_height_1631"><!-- PAGE INNER DIV -->
	<div class="page-title"><!-- PAGE TITLE -->
			<h3><img src="<?php echo get_option( 'amgt_system_logo' ) ?>" class="img-circle head_logo" width="40" height="40" />
			<?php echo get_option( 'amgt_system_name' );?></h3>
	</div>
	<div id="main-wrapper"><!-- MAIN WRAPPER DIV -->
		<div class="panel panel-white"><!-- PANEL WHITE-->
			<div class="panel-body"><!-- PANEL BODY-->
				<h3 class="gen_span"><?php  echo esc_html( esc_html__('General Settings', 'apartment_mgt')); ?></h3>
					<div class="panel-body"><!-- PANEL BODY-->
					        <!-- SETTING FORM-->
							<form name="setting_form" action="" method="post" class="form-horizontal" id="setting_form">								
								<div class="form-group">
									<label class="col-sm-2 control-label" for="amgt_system_name"><?php esc_html_e('Apartment Name','apartment_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-8">
										<input id="amgt_system_name" maxlength="50"  class="form-control validate[required,custom[onlyLetter_specialcharacter]]" type="text" value="<?php echo get_option( 'amgt_system_name' );?>"  name="amgt_system_name">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php esc_html_e('Apartment Type','apartment_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-8">									
										<select name="amgt_apartment_type" class="form-control">
											<option><?php esc_html_e('Select Apartment Type','apartment_mgt');?></option>
											<option value="Residential" <?php echo selected(get_option( 'amgt_apartment_type' ),'Residential');?>><?php esc_html_e('Residential','apartment_mgt');?></option>
											<option value="Commercial" <?php echo selected(get_option( 'amgt_apartment_type' ),'Commercial');?>><?php esc_html_e('Commercial','apartment_mgt');?></option>				
										</select> 
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="amgt_staring_year"><?php esc_html_e('Starting Year','apartment_mgt');?></label>
									<div class="col-sm-8">
										<input id="amgt_staring_year" class="form-control" type="number" min="0" onKeyPress="if(this.value.length==4) return false;" value="<?php echo get_option( 'amgt_staring_year' );?>"  name="amgt_staring_year">
									</div>
								</div>
								<!-- ADDRESS INFORMATION-->
								<div class="form-group">
									<label class="col-sm-2 control-label" for="amgt_apartment_address"><?php esc_html_e('Apartment Address','apartment_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-8">
										<input id="amgt_apartment_address"  maxlength="150" class="form-control validate[required,custom[address_description_validation]]" type="text" value="<?php echo get_option( 'amgt_apartment_address' );?>"  name="amgt_apartment_address">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="city_name"><?php esc_html_e('City','apartment_mgt');?></label>
									<div class="col-sm-8 has-feedback">
										<input  class="form-control validate[custom[city_state_country_validation]]" type="text" maxlength="50" name="amgt_city"  placeholder="<?php esc_html_e('Enter City Name','apartment_mgt');?>"
										value="<?php echo get_option( 'amgt_city' );?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="state_name"><?php esc_html_e('State','apartment_mgt');?></label>
									<div class="col-sm-8 has-feedback">
										<input id="state_name" class="form-control validate[custom[city_state_country_validation]]" maxlength="50" type="text"  name="amgt_state" placeholder="<?php esc_html_e('Enter State Name','apartment_mgt');?>"
										value="<?php echo get_option( 'amgt_state' );?>">
									</div>
								</div>								
								<div class="form-group" class="form-control" id="">
									<label class="col-sm-2 control-label" for="cmgt_contry"><?php esc_html_e('Country','apartment_mgt');?></label>
									<div class="col-sm-8">
										<?php 
										
										$url=content_url( ).'/plugins/apartment-management/lib/countrylist/countrylist.xml';
										$xml =simplexml_load_string(amgt_get_remote_file($url));
										?>
										 <select name="amgt_contry" class="form-control validate[custom[city_state_country_validation]]" id="cmgt_contry">
											<option value=""><?php esc_html_e('Select Country','apartment_mgt');?></option>
											<?php
												foreach($xml as $country)
												{  
												?>
												 <option value="<?php echo esc_attr($country->name);?>" <?php selected(get_option( 'amgt_contry' ), $country->name);  ?>><?php echo esc_html($country->name);?></option>
											<?php } ?>
										</select> 
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="amgt_contact_number"><?php esc_html_e('Official Phone Number','apartment_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-8">
										<input id="amgt_contact_number" class="form-control validate[required]" type="number" min="0" onKeyPress="if(this.value.length==15) return false;" value="<?php echo get_option( 'amgt_contact_number' );?>"  name="amgt_contact_number">
									</div>
								</div>
								<!-- END ADDRESS INFORMATION---->
								<!--CONTACT INFORMATION---->
								<div class="form-group">
									<label class="col-sm-2 control-label" for="amgt_email"><?php esc_html_e('Email','apartment_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-8">
										<input id="amgt_email" class="form-control validate[required,custom[email]] text-input" maxlength="50" type="text" value="<?php echo get_option( 'amgt_email' );?>"  name="amgt_email">
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-2 control-label" for="amgt_logo"><?php esc_html_e('Apartment Logo','apartment_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-8">
										<input type="text" id="amgt_user_avatar_url" name="amgt_system_logo" class="validate[required]" value="<?php  echo get_option( 'amgt_system_logo' ); ?>" />
												 <input id="upload_user_avatar_button" type="button" class="button margin_top_10_res" value="<?php esc_html_e('Upload image', 'apartment_mgt' ); ?>" />
												 <span class="description"><?php esc_html_e('Upload image.', 'apartment_mgt' ); ?></span>
												 
										<div id="upload_user_avatar_preview" class="min_height_100">
										 <img class="max_width_100" src="<?php  echo get_option( 'amgt_system_logo' ); ?>" />
										</div>
									</div>
								</div>
								<div class="form-group"><!-- PROFILE COVER-->
									<label class="col-sm-2 control-label" for="hmgt_cover_image"><?php esc_html_e('Profile Cover Image','apartment_mgt');?></label>
									<div class="col-sm-8">
										<input type="text" id="amgt_apartment_background_image" name="amgt_apartment_background_image" value="<?php  echo get_option( 'amgt_apartment_background_image' ); ?>" />	
												  <input id="upload_image_button" type="button" class="button upload_user_cover_button margin_top_10_res" value="<?php esc_html_e('Upload Cover Image', 'apartment_mgt' ); ?>" />
												 <span class="description"><?php esc_html_e('Upload Cover Image', 'apartment_mgt' ); ?></span>
												 
										<div id="upload_apartment_cover_preview min_height_100">
										  <img class="max_width_100" src="<?php  echo get_option( 'amgt_apartment_background_image' ); ?>" />
										</div>
									 </div>
								</div>
								<!-- LOGIN PAGE-->
								<div class="form-group">
									<label class="col-sm-2 control-label" for="amgt_email"><?php esc_html_e('Login Page','apartment_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-8">
												<select name="amgt_login_page" class="form-control" id="cmgt_contry">
													<option value=""><?php esc_html_e('Select Login Page','cmgt_contry');?></option>
													<?php
														foreach(amgt_get_all_category('page') as $page)	
														{  ?>
														 <option value="<?php echo esc_attr($page->ID);?>" <?php selected(get_option( 'amgt_login_page' ), $page->ID);  ?>><?php echo esc_html($page->post_title);?></option>
													<?php } ?>
												</select> 
									</div>
								</div>
								<!-- ENABLE NOTIFICATION-->
								<div class="form-group">
									<label class="col-sm-2 control-label" for="apartment_enable_notifications"><?php esc_html_e('Enable Notifications','apartment_mgt');?></label>
									<div class="col-sm-8">
										<div class="checkbox">
											<label>
												<input type="checkbox" name="apartment_enable_notifications"  value="1" <?php echo checked(get_option('apartment_enable_notifications'),'yes');?>/><?php esc_html_e('Enable','apartment_mgt');?>
										  </label>
									  </div>
									</div>
								</div><!--DATE FORMATE-->
								<div class="form-group">
									<label class="col-sm-2 control-label" for="amgt_currency_code"><?php esc_html_e('Date Format','apartment_mgt');?><span class="require-field">*</span></label>
			                        <div class="col-sm-8">
		                                <select name="amgt_date_formate" class="form-control validate[required] text-input">
											  <option value=""> <?php esc_html_e('Select Date Format','hospital_mgt');?></option>
											  <option value="Y-m-d" <?php echo selected(get_option( 'amgt_date_formate' ),'Y-m-d');?>>
											  <?php esc_html_e('2017-12-12','hospital_mgt');?></option>
											  <option value="m/d/Y" <?php echo selected(get_option( 'amgt_date_formate' ),'m/d/Y');?>>
											  <?php esc_html_e('12/31/2017','hospital_mgt');?></option>
											   <option value="d/m/Y" <?php echo selected(get_option( 'amgt_date_formate' ),'d/m/Y');?>>
											  <?php esc_html_e('31/12/2017','hospital_mgt');?></option>  
											  <option value="F j, Y" <?php echo selected(get_option( 'amgt_date_formate' ),'F j, Y');?>>
											  <?php esc_html_e('December 12, 2017','hospital_mgt');?></option>
		                                </select>
			                        </div>
		                        </div>
								<?php if(is_plugin_active('paymaster/paymaster.php')) 
								{ ?> 
								<div class="form-group">
									<label for="amgt_paymaster_pack" class="col-sm-2 control-label"><?php esc_html_e('Use Paymaster Payment Gateways','apartment_mgt');?></label>
									<div class="col-sm-4">
										<div class="checkbox">
										<label><input type="checkbox" value="yes" <?php echo checked(get_option('amgt_paymaster_pack'),'yes');?> name="amgt_paymaster_pack"><?php esc_html_e('Enable','apartment_mgt') ?> </label>
									  </div>
									</div>
								</div>
								<?php } ?>
								<!-- PAYPAL SETTING-->
								<div class="header">	<hr>
									<h3><?php esc_html_e('Paypal Setting','apartment_mgt');?></h3>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="apartment_enable_sandbox"><?php esc_html_e('Enable Sandbox','apartment_mgt');?></label>
									<div class="col-sm-8">
										<div class="checkbox">
											<label>
												<input type="checkbox" name="apartment_enable_sandbox"  value="1" <?php echo checked(get_option('apartment_enable_sandbox'),'yes');?>/><?php esc_html_e('Enable','apartment_mgt');?>
										  </label>
									  </div>
									</div>
								</div>
								<!--PAYPAL IMAIL-->
								<div class="form-group">
									<label class="col-sm-2 control-label" for="apartment_paypal_email"><?php esc_html_e('Paypal Email Id','apartment_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-8">
										<input id="apartment_paypal_email" class="form-control validate[required,custom[email]] text-input" maxlength="50" type="text" value="<?php echo get_option( 'apartment_paypal_email' );?>"  name="apartment_paypal_email">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="apartment_currency_code"><?php esc_html_e('Select Currency','apartment_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-8">
										<select name="apartment_currency_code" class="form-control validate[required] text-input">
											  <option value=""> <?php esc_html_e('Select Currency','apartment_mgt');?></option>
											  <option value="AUD" <?php echo selected(get_option( 'apartment_currency_code' ),'AUD');?>>
											  <?php esc_html_e('Australian Dollar','apartment_mgt');?></option>
											  <option value="BRL" <?php echo selected(get_option( 'apartment_currency_code' ),'BRL');?>>
											  <?php esc_html_e('Brazilian Real','apartment_mgt');?> </option>
											  <option value="INR" <?php echo selected(get_option( 'apartment_currency_code' ),'INR');?>>
											  <?php esc_html_e('Indian Rupee','apartment_mgt');?></option>
											  <option value="CAD" <?php echo selected(get_option( 'apartment_currency_code' ),'CAD');?>>
											  <?php esc_html_e('Canadian Dollar','apartment_mgt');?></option>
											  <option value="CZK" <?php echo selected(get_option( 'apartment_currency_code' ),'CZK');?>>
											  <?php esc_html_e('Czech Koruna','apartment_mgt');?></option>
											  <option value="DKK" <?php echo selected(get_option( 'apartment_currency_code' ),'DKK');?>>
											  <?php esc_html_e('Danish Krone','apartment_mgt');?></option>
											  <option value="EUR" <?php echo selected(get_option( 'apartment_currency_code' ),'EUR');?>>
											  <?php esc_html_e('Euro','apartment_mgt');?></option>
											  <option value="HKD" <?php echo selected(get_option( 'apartment_currency_code' ),'HKD');?>>
											  <?php esc_html_e('Hong Kong Dollar','apartment_mgt');?></option>
											  <option value="HUF" <?php echo selected(get_option( 'apartment_currency_code' ),'HUF');?>>
											  <?php esc_html_e('Hungarian Forint','apartment_mgt');?> </option>
											  <option value="ILS" <?php echo selected(get_option( 'apartment_currency_code' ),'ILS');?>>
											  <?php esc_html_e('Israeli New Sheqel','apartment_mgt');?></option>
											  <option value="JPY" <?php echo selected(get_option( 'apartment_currency_code' ),'JPY');?>>
											  <?php esc_html_e('Japanese Yen','apartment_mgt');?></option>
											  <option value="MYR" <?php echo selected(get_option( 'apartment_currency_code' ),'MYR');?>>
											  <?php esc_html_e('Malaysian Ringgit','apartment_mgt');?></option>
											  <option value="MXN" <?php echo selected(get_option( 'apartment_currency_code' ),'MXN');?>>
											  <?php esc_html_e('Mexican Peso','apartment_mgt');?></option>
											  <option value="NOK" <?php echo selected(get_option( 'apartment_currency_code' ),'NOK');?>>
											  <?php esc_html_e('Norwegian Krone','apartment_mgt');?></option>
											  <option value="NZD" <?php echo selected(get_option( 'apartment_currency_code' ),'NZD');?>>
											  <?php esc_html_e('New Zealand Dollar','apartment_mgt');?></option>
											  <option value="NGN" <?php echo selected(get_option( 'apartment_currency_code' ),'NGN');?>>
		                                       <?php esc_html_e('Nigerian Naira','apartment_mgt');?></option>
											  <option value="PHP" <?php echo selected(get_option( 'apartment_currency_code' ),'PHP');?>>
											  <?php esc_html_e('Philippine Peso','apartment_mgt');?></option>
											  <option value="PLN" <?php echo selected(get_option( 'apartment_currency_code' ),'PLN');?>>
											  <?php esc_html_e('Polish Zloty','apartment_mgt');?></option>
											  <option value="GBP" <?php echo selected(get_option( 'apartment_currency_code' ),'GBP');?>>
											  <?php esc_html_e('Pound Sterling','apartment_mgt');?></option>
											  <option value="SGD" <?php echo selected(get_option( 'apartment_currency_code' ),'SGD');?>>
											  <?php esc_html_e('Singapore Dollar','apartment_mgt');?></option>
											  <option value="SEK" <?php echo selected(get_option( 'apartment_currency_code' ),'SEK');?>>
											  <?php esc_html_e('Swedish Krona','apartment_mgt');?></option>
											  <option value="CHF" <?php echo selected(get_option( 'apartment_currency_code' ),'CHF');?>>
											  <?php esc_html_e('Swiss Franc','apartment_mgt');?></option>
											  <option value="TWD" <?php echo selected(get_option( 'apartment_currency_code' ),'TWD');?>>
											  <?php esc_html_e('Taiwan New Dollar','apartment_mgt');?></option>
											  <option value="THB" <?php echo selected(get_option( 'apartment_currency_code' ),'THB');?>>
											  <?php esc_html_e('Thai Baht','apartment_mgt');?></option>
											  <option value="TRY" <?php echo selected(get_option( 'apartment_currency_code' ),'TRY');?>>
											  <?php esc_html_e('Turkish Lira','apartment_mgt');?></option>
											  <option value="USD" <?php echo selected(get_option( 'apartment_currency_code' ),'USD');?>>
											  <?php esc_html_e('U.S. Dollar','apartment_mgt');?></option>
										</select>
									</div>
								</div>
								 <!--BUILDING UNITS SETTINGS-->
								<div class="header">
								<hr>
									<h3><?php esc_html_e('Building Units settings','apartment_mgt');?></h3>
								</div>								
								<div class="form-group">
									<label class="col-sm-2 control-label" for="Measurement"><?php esc_html_e('Select Unit Measurement','apartment_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-8">
									<?php $measerment_type = "square_feet"; if(get_option( 'amgt_unit_measerment_type' )!=""){ $measerment_type=get_option( 'amgt_unit_measerment_type' ); } ?>
										<label class="radio-inline">
										 <input type="radio" value="square_feet" class="tog validate[required]" name="amgt_unit_measerment_type"  <?php  checked( 'square_feet', $measerment_type);  ?>/><?php esc_html_e('Square Feet','apartment_mgt');?>
										</label>
										<label class="radio-inline">
										  <input type="radio" value="square_meter" class="tog validate[required]" name="amgt_unit_measerment_type"  <?php  checked( 'square_meter', $measerment_type);  ?>/><?php esc_html_e('Square Meter','apartment_mgt');?> 
										</label>
										 <label class="radio-inline margin_left_0_res">
										  <input type="radio" value="square_yards" class="tog validate[required] " name="amgt_unit_measerment_type"  <?php  checked( 'square_yards', $measerment_type);  ?>/><?php esc_html_e('Square Yards','apartment_mgt');?> 
										</label>
									</div>
								</div>
								 <div class="form-group">
									<label class="col-sm-2 control-label" for="apartment_enable_chargis"><?php _e("Enable Charges","apartment_mgt");?></label>
									<div class="col-sm-8">
										<div class="checkbox">
											<label>
												<input type="checkbox" name="apartment_enable_chargis"  value="yes" <?php echo checked(get_option('apartment_enable_chargis'),'yes');?>/><?php esc_html_e('Enable','apartment_mgt');?>
										  </label>
									  </div>
									</div>
								</div> 
								<!---INVOICE INFORMATION-->
								<div class="header">
								<hr>
									<h3><?php esc_html_e('Invoice Setting','apartment_mgt');?></h3>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php esc_html_e('Invoice Prefix','apartment_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-8">
										<input class="form-control validate[required,custom[onlyLetter_specialcharacter]]" maxlength="15" type="text" value="<?php echo get_option( 'invoice_prefix' );?>"  name="invoice_prefix">
									</div>
								</div>								
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php esc_html_e('GST Number / Vat Number / Tax number','apartment_mgt');?></label>
									<div class="col-sm-8">
										<input class="form-control validate[custom[phone_number]] space_not_allow" maxlength="15" type="text" value="<?php echo get_option( 'amgt_gst_number' );?>"  name="amgt_gst_number">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php esc_html_e('TAX ID','apartment_mgt');?></label>
									<div class="col-sm-8">
										<input class="form-control onlyletter_number_space_validation space_not_allow" maxlength="15" type="text" value="<?php echo get_option( 'amgt_tax_id' );?>"  name="amgt_tax_id">										
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php esc_html_e('Corporate ID','apartment_mgt');?></label>
									<div class="col-sm-8">										
										<input class="form-control onlyletter_number_space_validation space_not_allow" maxlength="15" type="text" value="<?php echo get_option( 'amgt_corporate_id' );?>"  name="amgt_corporate_id">
									</div>
								</div>
								<!-----BANK DETAILS----->
								<div class="header">
								<hr>
									<h3><?php esc_html_e('Bank Details','apartment_mgt');?></h3>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php esc_html_e('Bank Name','apartment_mgt');?></label>
									<div class="col-sm-8">
										<input class="form-control validate[custom[onlyLetterSp]]" type="text" value="<?php echo get_option( 'amgt_bank_name' );?>"  name="amgt_bank_name" maxlength="50">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php esc_html_e('Account Holder Name','apartment_mgt');?></label>
									<div class="col-sm-8">
										<input class="form-control validate[custom[onlyLetterSp]]" type="text" value="<?php echo get_option( 'amgt_account_holder_name' );?>"  name="amgt_account_holder_name" maxlength="50">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php esc_html_e('Account Number','apartment_mgt');?></label>
									<div class="col-sm-8">
										<input class="form-control" type="number" min="0" onKeyPress="if(this.value.length==30) return false;" value="<?php echo get_option( 'amgt_account_number' );?>"  name="amgt_account_number">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php esc_html_e('Account Type','apartment_mgt');?></label>
									<div class="col-sm-8">
										<input class="form-control validate[custom[onlyLetterSp]]" type="text" value="<?php echo get_option( 'amgt_account_type' );?>"  name="amgt_account_type" maxlength="30">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php esc_html_e('IFSC Code','apartment_mgt');?></label>
									<div class="col-sm-8">
										<input class="form-control validate[custom[onlyLetter_specialcharacter]] space_not_allow" maxlength="11" type="text" value="<?php echo get_option( 'amgt_ifsc_code' );?>"  name="amgt_ifsc_code">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"><?php esc_html_e('Swift Code','apartment_mgt');?></label>
									<div class="col-sm-8">
										<input class="form-control validate[custom[onlyLetter_specialcharacter]] space_not_allow" maxlength="11" type="text" value="<?php echo get_option( 'amgt_swift_code' );?>"  name="amgt_swift_code">
									</div>
								</div>
								<div class="col-sm-offset-2 col-sm-8">
									<input type="submit" value="<?php esc_html_e('Save', 'apartment_mgt' ); ?>" name="save_setting" class="btn btn-success"/>
								</div>
							</form><!-- SETTING FORM-->
					</div><!-- END PANEL BODY-->
			</div>
		</div>
    </div>
	<!-- MAIN WRAPPER DIV -->
</div><!-- Page-inner -->
 <?php ?>