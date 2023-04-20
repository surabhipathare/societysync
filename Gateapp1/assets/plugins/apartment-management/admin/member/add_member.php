<?php $role='member';
?>
<style>
.dropdown-menu {
    min-width: 240px;
}
</style>
<script type="text/javascript">
function fileCheck(obj)
{   //FILE VALIDATION
	"use strict";
	var fileExtension = ['pdf','doc','jpg','jpeg','png'];
	if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1)
	{
		alert("Only '.pdf','.docx','.jpg','.jpeg','.png'  formats are allowed.");
		$(obj).val('');
	}	
}
</script>
<script type="text/javascript">
$(document).ready(function() 
{   //MEMBER FORM VALIDATIONENGINE
	"use strict";
	$('#member_form').validationEngine({promptPosition : "topRight",maxErrorsPerField: 1});
	jQuery('#birth_date').datepicker({
			dateFormat: "yy-mm-dd",
			maxDate : 0,
			changeMonth: true,
	        changeYear: true,
	        yearRange:'-65:+25',
			beforeShow: function (textbox, instance) 
			{
				instance.dpDiv.css({
					marginTop: (-textbox.offsetHeight) + 'px'                   
				});
			},    
	        onChangeMonthYear: function(year, month, inst) {
	            jQuery(this).val(month + "/" + year);
	        }                    
		}); 
	$('#occupied_date').datepicker({
	dateFormat: "yy-mm-dd",	  
	  autoclose: true
	}); 
   	 <!-----MAIN-WRAPPER-------->
	$('#unit_form').on('submit', function(e)
	{
		e.preventDefault();
		var form = $(this).serialize(); 
		var valid = $('#unit_form').validationEngine('validate');
		if (valid == true)
		{
			$.ajax({
				type:"POST",
				url: $(this).attr('action'),
				data:form,
				success: function(data)
				{
					 if(data!="")
					 { 
						$('#unit_form').trigger("reset");
						$('.modal').modal('hide');
					 } 
				},
				error: function(data)
				{
				}
		    })
		}
	});
	//USERNAME NOT  ALLOW SPACE VALIDATION
	$('#username').keypress(function( e ) {
       if(e.which === 32) 
         return false;
    });
} );
</script>
    <?php 	
        $member_id=0;
		if(isset($_REQUEST['member_id']))
			$member_id=$_REQUEST['member_id'];
		$edit=0;
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
				$edit=1;
				$result = get_userdata($member_id);
			} ?>
			
			<div class="page-title user_header"><!---TITLE--->	
		      <h3><?php esc_html_e('Add Member','apartment_mgt');?></h3>
	        </div>
		
		<div class="panel-body"><!--PANEL BODY-->
		    <!---ADD MEMBER FORM--->	
			<form name="member_form" action="" method="post" class="form-horizontal" id="member_form" enctype="multipart/form-data">
			<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
			<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
			<input type="hidden" name="user_id" value="<?php echo esc_attr($member_id);?>"  />
			<input type="hidden" name="role" value="<?php echo esc_attr($role);?>"  />
			<div class="form-group">
			     <!---BUILDING INFORMATION--->	
				<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select class="form-control validate[required] building_category" name="building_id">
						<option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
						<?php 
						if($edit)
						{
						  $category =$result->building_id;
						}
						elseif(isset($_REQUEST['building_id']))
						{
						  $category =$_REQUEST['building_id'];
						}
						else
						{
						 $category = "";
						}
						$activity_category=amgt_get_all_category('building_category');
						if(!empty($activity_category))
						{
							foreach ($activity_category as $retrive_data)
							{
								echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
							}
						} ?>
					</select>
				</div>
				<div class="col-sm-2">
				 <a href="#" class="btn btn-default margin_top_10_res" data-toggle="modal" id="add_bulding" data-target="#myModal_add_building"> <?php esc_html_e('Add Building','apartment_mgt');?></a>
				</div>
			</div>
			<div id="hello"></div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">			
					<select class="form-control validate[required] unit_categorys" name="unit_cat_id">
						<option value=""><?php esc_html_e('Select Unit Category','apartment_mgt');?></option>
						<?php 
							if($edit)
								$category =$result->unit_cat_id;
							elseif(isset($_REQUEST['unit_cat_id']))
								$category =$_REQUEST['unit_cat_id'];  
							else 
								$category = "";
							
							$activity_category=amgt_get_all_category('unit_category');
							if(!empty($activity_category))
							{
								foreach ($activity_category as $retrive_data)
								{
									echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
								}
							} 	
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="unit_name"><?php esc_html_e('Unit','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<select class="form-control validate[required] unit_name" name="unit_name">
						<option value=""><?php esc_html_e('Select Unit Name','apartment_mgt');?></option>
						<?php 
						if($edit)
						{
							$unitname =$result->unit_name;
							$building_id=$result->building_id;
							$unit_category=$result->unit_cat_id;
							$unitsarray=$obj_units->amgt_get_single_cat_units($building_id,$unit_category);
							$all_entry=json_decode($unitsarray);
							
							$i=0;
							
							foreach ($all_entry as $key => $value) 
							{
								$unit_value[] = $value;								
							}
							
							if(!empty($unit_value))
							{
								foreach ($all_entry as $key1 => $value1) 
								{?>
									<option value="<?php echo esc_attr($value1->value); ?>" <?php selected($unitname,$value1->value);?>><?php echo esc_attr($value1->value);?> </option>
								<?php }
							}					
						} ?>
					</select>
				</div>			
			</div>
			 <!---BUILDING INFORMATION--->	
			<div class="form-group">  <!---GENERAL INFORMATION--->	
				<label class="col-sm-2 control-label" for="first_name"><?php esc_html_e('First Name','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="first_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo esc_attr($result->first_name);}elseif(isset($_POST['first_name'])) echo esc_attr($_POST['first_name']);?>" name="first_name">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="middle_name"><?php esc_html_e('Middle Name','apartment_mgt');?></label>
				<div class="col-sm-8">
					<input id="middle_name" class="form-control validate[custom[onlyLetter_specialcharacter]]" maxlength="50" type="text"  value="<?php if($edit){ echo esc_attr($result->middle_name);}elseif(isset($_POST['middle_name'])) echo esc_attr($_POST['middle_name']);?>" name="middle_name">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="last_name"><?php esc_html_e('Last Name','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="last_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo esc_attr($result->last_name);}elseif(isset($_POST['last_name'])) echo esc_attr($_POST['last_name']);?>" name="last_name">
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
			<div class="form-group">
				<label class="col-sm-2 control-label" for="birth_date"><?php esc_html_e('Date of birth','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-3">
					<input id="birth_date" class="form-control validate[required]" autocomplete="off" type="text"  name="birth_date" 
					value="<?php if($edit){ echo date("Y-m-d",strtotime($result->birth_date));}elseif(isset($_POST['birth_date'])) echo esc_attr($_POST['birth_date']);?>">
				</div>
				<label class="col-sm-2 control-label margin_top_10_res_10" for="unit_name"><?php esc_html_e('Member Type','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-3">
					<select class="form-control validate[required]" name="member_type" id="member_type">
						<option value=""><?php esc_html_e('Select Member Type','apartment_mgt');?></option>
						<?php 
						if($edit)
						{
						$category =$result->member_type;
						}
						elseif(isset($_POST['member_type']))
						{
						$category =$_POST['member_type'];
						}
						else
						{
						$category ="";
						}?>
						<option value="Owner" <?php selected($category,'Owner');?>><?php esc_html_e('Owner','apartment_mgt');?></option>
						<option value="tenant" <?php selected($category,'tenant');?>><?php esc_html_e('Tenant','apartment_mgt');?></option>
						<option value="owner_family" <?php selected($category,'owner_family');?>><?php esc_html_e('Owner Family','apartment_mgt');?></option>
						<option value="tenant_family" <?php selected($category,'tenant_family');?>><?php esc_html_e('Tenant Family','apartment_mgt');?></option>
						<option value="care_taker" <?php selected($category,'care_taker');?>><?php esc_html_e('Care Taker','apartment_mgt');?></option>
					</select>
				</div>
			</div>
			
			<?php
			if($edit)		 
			{
				//var_dump($result->occupied_by);
				if(!empty($result->occupied_by))
				{
					
				?>
					<div class="occupied_div_edit">
						<div class="form-group">
							<label class="col-sm-2 control-label"><?php esc_html_e('Occupied By','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<select class="form-control validate[required] allready_occupied" name="occupied_by">
								<option value=""><?php esc_html_e('Select Occupied By','apartment_mgt');?></option>
								<?php 
								if($edit)
								$occupied_by =$result->occupied_by;
							
								elseif(isset($_POST['occupied_by']))
									$occupied_by =$_POST['occupied_by'];
								else
									$occupied_by ="";
								?>
								 <?php $activity_category=amgt_get_all_category('member_category');
								if(!empty($activity_category))
								{
									foreach ($activity_category as $retrive_data)
									{
										echo '<option value="'.$retrive_data->post_title.'" '.selected($occupied_by,$retrive_data->post_title).'>'.$retrive_data->post_title.'</option>';
									}
								} ?>		
								</select>
							</div>
							<div class="col-sm-2">
							<button id="addremove" model="member_category"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button>
					</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" ><?php esc_html_e('Occupied Date','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input id="occupied_date" class="form-control validate[required]" autocomplete="off" type="text"  name="occupied_date" 
								value="<?php if($edit){ echo date("Y-m-d",strtotime($result->occupied_date));}elseif(isset($_POST['occupied_date'])) echo esc_attr($_POST['occupied_date']);?>">
							</div>
						</div>
					</div>
				<?php
				}
				else
				{	
				?>
				<div class="occupied_div"><!-- OCCUPIED BY DROPDOWN LIST DIV-->
					<div class="form-group">
						<label class="col-sm-2 control-label"><?php esc_html_e('Occupied By','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<select class="form-control validate[required] allready_occupied" name="occupied_by">
							<option value=""><?php esc_html_e('Select Occupied By','apartment_mgt');?></option>
							
							 <?php $activity_category=amgt_get_all_category('member_category');
								if(!empty($activity_category))
								{
									foreach ($activity_category as $retrive_data)
									{
										echo '<option value="'.$retrive_data->post_title.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
									}
								} ?>			
							</select>
						</div>
						<div class="col-sm-2">
							<button id="addremove" model="member_category"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button>
					</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" ><?php esc_html_e('Occupied Date','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-8">
							<input id="occupied_date" class="form-control validate[required]" type="text"  name="occupied_date" 
							value="">
						</div>
					</div>
				</div>
				<?php
				}						
			}
			else
			{	
			?>
			<div class="occupied_div">
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php esc_html_e('Occupied By','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] allready_occupied" name="occupied_by">
						<option value=""><?php esc_html_e('Select Occupied By','apartment_mgt');?></option>
						<?php 
						if($edit)
							$occupied_by =$result->occupied_by;
						elseif(isset($_POST['occupied_by']))
							$occupied_by =$_POST['occupied_by'];
						else
							$occupied_by ="";
						   $activity_category=amgt_get_all_category('member_category');
								if(!empty($activity_category))
								{
									foreach ($activity_category as $retrive_data)
									{
										echo '<option value="'.$retrive_data->post_title.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
									}
								} ?>		
						</select>
					</div>
					<div class="col-sm-2">
							<button id="addremove" model="member_category"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label" ><?php esc_html_e('Occupied Date','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<input id="occupied_date" class="form-control validate[required]" type="text" autocomplete="off" name="occupied_date" 
						value="<?php if($edit){ echo date(amgt_date_formate(),strtotime($result->occupied_date));}elseif(isset($_POST['occupied_date'])) echo esc_attr($_POST['occupied_date']);?>">
					</div>
				</div>
			</div>
			<?php
			}
			?>
			<div class="form-group">
				<label class="col-sm-2 control-label " for="committee_member"><?php esc_html_e('Committee Member','apartment_mgt');?></label>
				<div class="col-sm-1">
					<div class="col-sm-1">
					<input id="committee_member" class="form-control text-input" type="checkbox" <?php if($edit==1 && $result->committee_member=='yes'){ echo "checked";}?> name="committee_member" 
					value="yes"></div>	
				</div>	
				<?php if($edit==1 && $result->committee_member=='yes')
				{ ?>
				<div class="col-sm-9" id="designaion_area">
					<div class="col-sm-6">
						<select class="form-control validate[required] designation_cat" name="designation_id">
							<option value=""><?php esc_html_e('Select Designation','apartment_mgt');?></option>
							<?php 
							if($edit)
								$category =$result->designation_id;
							else 
								$category = "";
							
							$activity_category=amgt_get_all_category('designation_cat');
							if(!empty($activity_category))
							{
								foreach ($activity_category as $retrive_data)
								{
									echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';
								}
							} ?>
						</select>
				    </div>
				<div class="col-sm-3"><button id="addremove" model="designation_cat"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
				</div>
				<?php
				}
					else
					{?>
						<div class="col-sm-9" id="designaion_area">
						</div>
					<?php 
					}	?>
			</div>
              <div class="form-group">
				<label class="col-sm-2 control-label" for="gst_no"><?php esc_html_e('Add GST Number','apartment_mgt');?></label>
				<div class="col-sm-8">
					<input id="gst_no" class="form-control validate[custom[onlyLetterNumber]] text-input" maxlength="50" type="text"  value="<?php if($edit){ echo esc_attr($result->gst_no);}elseif(isset($_POST['gst_no'])) echo esc_attr($_POST['gst_no']);?>" name="gst_no">
				</div>
			</div>		
            <!-----ADDRESS INFORMATION------->			
			<div class="form-group">
				<label class="col-sm-2 control-label" for="address"><?php esc_html_e('Correspondence Address','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="address" class="form-control validate[required,custom[address_description_validation]]" type="text" maxlength="150" name="address" 
					value="<?php if($edit){ echo esc_attr($result->address);}elseif(isset($_POST['address'])) echo esc_attr($_POST['address']);?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="city_name"><?php esc_html_e('City','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-3">
					<input id="city_name" class="form-control validate[required,custom[city_state_country_validation]]" maxlength="50" type="text"  name="city_name" 
					value="<?php if($edit){ echo esc_attr($result->city_name);}elseif(isset($_POST['city_name'])) echo esc_attr($_POST['city_name']);?>">
				</div>
				<label class="col-sm-2 control-label margin_top_10_res_10"><?php esc_html_e('State','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-3">
					<input class="form-control validate[required,custom[city_state_country_validation]]" type="text" maxlength="50" name="state_name" 
					value="<?php if($edit){ echo esc_attr($result->state_name);}elseif(isset($_POST['state_name'])) echo esc_attr($_POST['state_name']);?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"><?php esc_html_e('Country','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-3">
					<input class="form-control validate[required,custom[city_state_country_validation]]" type="text" maxlength="50" name="country_name" 
					value="<?php if($edit){ echo esc_attr($result->country_name);}elseif(isset($_POST['country_name'])) echo esc_attr($_POST['country_name']);?>">
				</div>
				<label class="col-sm-2 control-label margin_top_10_res_10"><?php esc_html_e('Zip Code','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-3">
					<input class="form-control validate[required,custom[onlyLetterNumber]]" maxlength="15" type="text"  name="zipcode" 
					value="<?php if($edit){ echo esc_attr($result->zipcode);}elseif(isset($_POST['zipcode'])) echo esc_attr($_POST['zipcode']);?>">
				</div>
				  <!---END ADDRESS INFORMATION--->	
			</div>
			  <!---CONTACT INFORMATION--->	
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
				<div class="col-sm-7 margin_top_10_res">
					<input id="mobile" class="form-control validate[required,custom[phone_number]] text-input" type="number" min="0" onKeyPress="if(this.value.length==15) return false;"  name="mobile" 
					value="<?php if($edit){ echo $result->mobile;}elseif(isset($_POST['mobile'])) echo esc_attr($_POST['mobile']);?>">
				</div>
			</div>
			   <!---LOGIN INFORMATION--->	
			<div class="form-group">
				<label class="col-sm-2 control-label" for="username"><?php esc_html_e('User Name','apartment_mgt');?><span class="require-field">*</span></label>
				<div class="col-sm-8">
					<input id="username" class="form-control validate[required,custom[username_validation]]" type="text"  maxlength="50" name="username" 
					value="<?php if($edit){ echo esc_attr($result->user_login);}elseif(isset($_POST['username'])) echo $_POST['username'];?>" <?php if($edit) echo "readonly";?>>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="password"><?php esc_html_e('Password','apartment_mgt');?><?php if(!$edit) {?><span class="require-field">*</span><?php }?></label>
				<div class="col-sm-8">
					<input id="password" class="form-control <?php if(!$edit) echo 'validate[required]';?>" type="password"  name="password" minlength="8" maxlength="12" value="">
				</div>
			</div>			
              <!---END LOGIN INFORMATION--->				
			<div class="form-group">
				<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Member Image','apartment_mgt');?></label>
				<div class="col-sm-2">
					<input type="text" id="amgt_user_avatar_url" class="form-control" name="amgt_user_avatar"  
					value="<?php if($edit)echo esc_url( $result->amgt_user_avatar );elseif(isset($_POST['amgt_user_avatar'])) echo $_POST['amgt_user_avatar']; ?>" readonly />
				</div>	
					<div class="col-sm-3">
						 <input id="upload_user_avatar_button" type="button" class="button margin_top_10_res" value="<?php esc_html_e('Upload image', 'apartment_mgt' ); ?>" />
						 <span class="description"><?php esc_html_e('Upload image', 'apartment_mgt' ); ?></span>
				
				</div>
				<div class="clearfix"></div>
				
				<div class="col-sm-offset-2 col-sm-8">
					<div id="upload_user_avatar_preview" >
						 <?php 
						 if($edit) 
							{
							if($result->amgt_user_avatar == "")
							{?>
							<img class="user_image" alt="" src="<?php echo get_option( 'amgt_system_logo' ); ?>">
							<?php }
							else {
								?>
							<img class="user_image" src="<?php if($edit)echo esc_url( $result->amgt_user_avatar ); ?>" />
							<?php 
							}
							}
							else {
								?>
								<img class="user_image" alt="" src="<?php echo get_option( 'amgt_system_logo' ); ?>">
								<?php 
							}?>
					</div>
			    </div>
		    </div>
			<div class="header">	
				<h3 class="first_hed"><?php _e('Proof Documents','apartment_mgt');?></h3>
				<hr>
			</div>
			<?php 
			if($edit) 
			{
			?>
			 <!---DOCUMENT INFORMATION--->	
			<div class="form-group">
				<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Member ID Proof-1','apartment_mgt');?></label>
				<div class="col-sm-4">
					<input type="hidden" name="hidden_id_proof_1" value="<?php if($edit){ echo $result->id_proof_1;}elseif(isset($_POST['id_proof_1'])) echo $_POST['id_proof_1'];?>">
					<input id="upload_file" onchange="fileCheck(this);" name="id_proof_1"  value="" type="file"/>
				</div>
				<div class="col-sm-2">
					<?php if(isset($result->id_proof_1) && $result->id_proof_1 != ""){?>
					<a href="<?php echo content_url().'/uploads/apartment_assets/'.$result->id_proof_1;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php esc_html_e('Member ID Proof-1','apartment_mgt');?></a>
					<?php } ?>			
				</div>
			</div>	
			<?php	
			}
			else
			{		
			?>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Member ID Proof-1','apartment_mgt');?></label>
				<div class="col-sm-4">
					<input id="upload_file" onchange="fileCheck(this);" name="id_proof_1"  type="file"/>
				</div>
			</div>
			<?php
			}
			if($edit) 
			{
			?>	
			<div class="form-group">
				<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Lease Agreement','apartment_mgt');?></label>
				<div class="col-sm-4">
					<input type="hidden" name="hidden_id_proof_2" value="<?php if($edit){ echo $result->id_proof_2;}elseif(isset($_POST['id_proof_2'])) echo $_POST['id_proof_2'];?>">
					<input id="upload_file" onchange="fileCheck(this);" name="id_proof_2"  value="" type="file"/>
				</div>
				<div class="col-sm-2">				
					<?php if(isset($result->id_proof_2) && $result->id_proof_2 != ""){?>
					<a href="<?php echo content_url().'/uploads/apartment_assets/'.$result->id_proof_2;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php esc_html_e('Lease Agreement','apartment_mgt');?></a>
					<?php } ?>
				</div>
			</div>
			<?php
			}
			else
			{
			?>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="photo"><?php esc_html_e('Lease Agreement','apartment_mgt');?></label>
				<div class="col-sm-4">
					<input id="upload_file" onchange="fileCheck(this);" name="id_proof_2"  type="file"/>
				</div>
			</div>
			<?php
			}	
			?>	
			<?php 

			if($edit)
			{ 
				$doc_data=get_user_meta( $member_id, 'document' , true );
				$data_new=json_decode($doc_data);
				if(!empty($data_new))
				{
					foreach($data_new as $data)
					{
				?>

						<div class="form-group">

							<label class="col-sm-2 control-label" for="doc_title"><?php esc_html_e('Upload Document','apartment_mgt');?></label>
							
							<div class="col-sm-2">
								<input type="hidden" name="hidden_upload_file[]" value="<?php if($edit){ echo $data->value;}elseif(isset($_POST['upload_file'])) echo $_POST['upload_file'];?>">
								
								<input id="doc_title" maxlength="50" class="form-control  text-input onlyletter_number_space_validation" type="text" placeholder="<?php esc_html_e('Title','apartment_mgt');?>" value="<?php if($edit) echo $data->title;?>" name="doc_title[]">

							</div>

							<div class="col-sm-2 width_220">

									 <input id="upload_file" onchange="fileCheck(this);" name="upload_file[]"  class="" type="file" />

							</div>
							<div class="col-sm-2">				

								<?php if(isset($data->value) && $data->value != ""){?>

								<a target="blank" href="<?php echo content_url().'/uploads/apartment_assets/'.$data->value;?>" class="btn btn-default"><i class="fa fa-download"></i> <?php esc_html_e('Download','apartment_mgt');?></a>

								<?php } ?>

							</div>

						</div>

				 <?php 
					}
				}
				else{ ?>
					<div class="form-group">

					<label class="col-sm-2 control-label" for="doc_title"><?php esc_html_e('Upload Document','apartment_mgt');?></label>

					<div class="col-sm-2">

						<input id="doc_title" maxlength="50" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" type="text" placeholder="<?php esc_html_e('Title','apartment_mgt');?>"  value="<?php if($edit) echo $result->doc_title;?>" name="doc_title[]">

					</div>

					<div class="col-sm-2 member_doc">

						 <input id="upload_file" onchange="fileCheck(this);" name="upload_file[]"  type="file" <?php if($edit){ ?>class="margin_left_15_res" <?php }else{ ?>class="margin_left_15_res"<?php } ?>  />

					</div>
					
				</div>
					
				<div id="document_entry_member">

				</div>

			

			<div class="form-group">

				<label class="col-sm-2 control-label" for="invoice_entry"></label>

				<div class="col-sm-3">				

					<p  id="add_new_document_entry_member" class="btn btn-default btn-sm btn-icon icon-left"   name="add_new_entry" >

					<?php esc_html_e('Add More Document','apartment_mgt'); ?>

					</p>

				</div>

			</div>

				<?php
				}
			} 

			else

			{ ?>

				<div class="form-group">

					<label class="col-sm-2 control-label" for="doc_title"><?php esc_html_e('Upload Document','apartment_mgt');?></label>

					<div class="col-sm-2">

						<input id="doc_title" maxlength="50" class="form-control validate[custom[onlyLetter_specialcharacter]] text-input" type="text" placeholder="<?php esc_html_e('Title','apartment_mgt');?>"  value="<?php if($edit) echo $result->doc_title;?>" name="doc_title[]">

					</div>

					<div class="col-sm-2 member_doc">

						 <input id="upload_file" onchange="fileCheck(this);" name="upload_file[]"  type="file" <?php if($edit){ ?>class="" <?php }else{ ?>class="margin_left_15_res"<?php } ?>  />

					</div>
					
				</div>

			

				<div id="document_entry_member">

				</div>

			

			<div class="form-group">

				<label class="col-sm-2 control-label" for="invoice_entry"></label>

				<div class="col-sm-3">				

					<p  id="add_new_document_entry_member" class="btn btn-default btn-sm btn-icon icon-left"   name="add_new_entry" >

					<?php esc_html_e('Add More Document','apartment_mgt'); ?>

					</p>

				</div>

			</div>

			 <?php 

			} ?>

           <?php wp_nonce_field( 'save_member_nonce' ); ?>			
			<div class="col-sm-offset-2 col-sm-8">
				<input type="submit" value="<?php if($edit){ esc_html_e('Save','apartment_mgt'); }else{ esc_html_e('Add Member','apartment_mgt');}?>" name="save_member" class="btn btn-success"/>
			</div>
		    </form><!---END ADD MEMBER FORM--->
        </div><!--END PANEL BODY-->
    <?php 
 //} ?>
<!----------Add_unit_form---------------------->
<div class="modal fade overflow_scroll" id="myModal_add_building" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h3 class="modal-title"><?php esc_html_e('Add Building','apartment_mgt');?></h3>
			</div>
            <div class="modal-body"><!--MODAL BODY-->
				<script type="text/javascript">
				$(document).ready(function() {
					"use strict";
					$('#unit_form').validationEngine();
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

				});
				</script>
				<form name="unit_form"  method="post" action="<?php echo admin_url('admin-ajax.php'); ?>" class="form-horizontal" id="unit_form">
				<input id="" type="hidden" name="action" value="amgt_add_unit_popup">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Building','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] building_category" name="building_id" id="">
							<option value=""><?php esc_html_e('Select Building','apartment_mgt');?></option>
							<?php 
							$activity_category=amgt_get_all_category('building_category');
							if(!empty($activity_category))
							{
								foreach ($activity_category as $retrive_data)
								{
									echo '<option value="'.$retrive_data->ID.'">'.$retrive_data->post_title.'</option>';
								}
							} ?>
							</select>
					</div>
					<div class="col-sm-2"><button id="addremove" model="building_category"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control validate[required] unit_category"  name="unit_cat_id" id="">
							<option value=""><?php esc_html_e('Select Unit Category','apartment_mgt');?></option>
							<?php
							$activity_category=amgt_get_all_category('unit_category');
							if(!empty($activity_category))
							{
								foreach ($activity_category as $retrive_data)
								{
									echo '<option value="'.$retrive_data->ID.'">'.$retrive_data->post_title.'</option>';
								}
							} 
							?>
						</select>
					</div>
					<div class="col-sm-2"><button id="addremove" model="unit_category"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
				</div>
				<?php 
			   if(isset($_POST['unit_names'])){
					$all_data=$obj_units->amgt_get_entry_records($_POST);
					$all_entry=json_decode($all_data);
				}
				?>
				<div id="unit_name_entry">
						<div class="form-group">
						<label class="col-sm-2 control-label" for="unit_entry"><?php esc_html_e('Unit Name','apartment_mgt');?><span class="require-field">*</span></label>
						<div class="col-sm-2">
							<input class="form-control validate[required] text-input onlyletter_number_space_validation unit_name" type="text" value="" name="unit_names[]" placeholder="<?php esc_html_e('Unit Name','apartment_mgt');?>">
						</div>
						<?php $unit_measerment_type=get_option( 'amgt_unit_measerment_type' );?>
						<label class="col-sm-3 control-label" for="unit_entry"><?php esc_html_e('Unit Size','apartment_mgt');?>(
						
						<?php if($unit_measerment_type =='square_meter'){
						echo esc_html_e('square meter','apartment_mgt');
						}
						else{
							echo $unit_measerment_type;
						}
						
						?>)<span class="require-field">*</span></label>
												
						<div class="col-sm-2">
							<input  class="form-control validate[required] text-input" type="number" onKeyPress="if(this.value.length==6) return false;"  min="0" value="" name="unit_size[]" placeholder="<?php esc_html_e('Unit Size','apartment_mgt');?>">
						</div>
						<div class="col-sm-2">
						<button type="button" class="btn btn-default" onclick="deleteParentElement(this)">
						<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
						</button>
						</div>
						</div>	
				</div>
				   
				<div class="form-group">
					<label class="col-sm-2 control-label" for="unit_entry"></label>
					<div class="col-sm-3">
						
						<button id="add_new_entry" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_new_entry" onclick="add_entry()"><?php esc_html_e('Add More Unit','apartment_mgt'); ?>
						</button>
					</div>
				</div>
				<hr>			
				<div class="col-sm-offset-2 col-sm-8">
				<?php $unit_type=get_option( 'amgt_apartment_type' ); ?>
					<input type="submit" value="<?php  esc_html_e('Add '.$unit_type.' Unit','apartment_mgt'); ?>" name="save_residential_unit" class="btn btn-success"/>
				</div>
				</form>
            </div>
			<div class="modal-footer"><!---MODAL FOOTER--->
			  <button type="button" class="btn btn-default" data-dismiss="modal"><?php  esc_html_e('Close','apartment_mgt'); ?></button>
			</div>
        </div>
    </div>
</div>
<script>
	// CREATING BLANK INVOICE ENTRY
   	var blank_income_entry ='';
   	$(document).ready(function() { 
   		blank_expense_entry = $('#unit_name_entry').html();
   		//alert("hello" + blank_invoice_entry);
   	}); 

   	function add_entry()
   	{
   		$("#unit_name_entry").append(blank_expense_entry);
   		//alert("hellooo");
   	}
   	
   	// REMOVING INVOICE ENTRY
   	function deleteParentElement(n)
	{
		//if(confirm("Are you sure want to delete this record?"))
			if(confirm(language_translate.add_remove))
		{
			n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		}
   	}
</script>