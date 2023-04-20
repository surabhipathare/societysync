<?php if($active_tab == 'visitor-checkin') { ?>
			<script type="text/javascript">
			$(document).ready(function() {
				"use strict";
				$('#visitor_checkin_form').validationEngine();
				//$('.timepicker').timepicker();
				$('.timepicker').timepicki();
				var date = new Date();
				date.setDate(date.getDate()-0);
				jQuery('#checkin_date').datepicker({
					dateFormat: "yy-mm-dd",
					minDate:'today',
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
				//username not  allow space validation
				$('#username').keypress(function( e ) 
				{
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
			   $("body").on("click","#add_new_visitor",function()
				{
					$("#add_visiter_entry_div").append('<div class="row padding_left_10"><div class="form-group"><label class="col-sm-2 control-label" for="name"><?php esc_html_e('Visitor Name','apartment_mgt');?><span class="require-field">*</span></label><div class="col-sm-8"><input type="text" value="" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input onlyletter_number_space_validation" maxlength="50" name="visitor_name[]"/></div></div><div class="form-group"><label class="col-sm-2 control-label " for="mobile"><?php esc_html_e('ID Number','apartment_mgt');?><span class="require-field">*</span></label><div class="col-sm-3">								<input id="mobile" class="form-control validate[required,custom[onlyLetterNumber]] text-input" type="text" maxlength="15"  name="mobile[]" value=""></div><label class="col-sm-2 control-label margin_top_10_res" for="vehicle"><?php esc_html_e('Vehicle Number','apartment_mgt');?></label><div class="col-sm-3"><input type="text" value="" maxlength="20" class="form-control text-input" name="vehicle_number[]"/></div></div><div class="form-group"><div class="col-sm-offset-2 col-sm-2"><button type="button" class="btn btn-default" onclick="deletevisiterentry(this)"><i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i></button></div></div></div>');
				});			
		   });
		</script>
		<script>
		/* function add_visiter_entry()
		{
			
		} */
		function deletevisiterentry(n)
		{
			n.parentNode.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode.parentNode);				
		}
		</script>
		<style>
		.dropdown-menu {
			min-width: 240px;
		}
		</style>		
        	<?php 
			$vcheckin_id=0;
			$status=0;
			if(isset($_REQUEST['visitor_checkin_id']))
				$vcheckin_id=$_REQUEST['visitor_checkin_id'];
			$edit=0;
				if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit'){
					$edit=1;
					$result = $obj_gate->amgt_get_single_checkin($vcheckin_id);
					$status=$result->status;
				} ?>
		<div class="panel-body"><!--PANEL BODY DIV--->
		     <!--VISITOR_CHECKIN_FORM-->
            <form name="visitor_checkin_form" action="" method="post" class="form-horizontal" id="visitor_checkin_form">
				<?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
				<input id="action" type="hidden" name="action" value="<?php echo esc_attr($action);?>">
				<input type="hidden" name="vcheckin_id" value="<?php echo esc_attr($vcheckin_id);?>"  />
				<input type="hidden" name="checkin_type" value="visitor_checkin"  />
				 <input type="hidden" name="status" value="<?php echo esc_attr($status);?>"  />
		        <div class="form-group margin_top_3o clear_both">
			        <label class="col-sm-2 control-label" for="gate"><?php esc_html_e('Choose Gate','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
					<?php $gateval = "0"; if($edit){ $gateval=$result->gate_id; }elseif(isset($_POST['gate'])) {$gateval=$_POST['gate'];}
					if(!empty($gatedata))
						{
							$i=1;
							foreach($gatedata as $gate){
								if($edit){
							?>
							<label class="radio-inline front_radio">
							<input type="radio" value="<?php echo esc_attr($gate->id);?>" class="tog validate[required] radio_border_radius" name="gate"  <?php  echo checked( $gate->id, $gateval);  ?>/><?php echo esc_html($gate->gate_name);?>
							</label>
					
								<?php }
								else
								{?>
									<label class="radio-inline front_radio">
							<input type="radio" value="<?php echo $gate->id;?>" class="tog validate[required] radio_border_radius" name="gate"  <?php  if($i==1) echo "checked"; ?>/><?php echo esc_attr($gate->gate_name);?>
							</label>
								<?php }
						$i+=1;
								
								
						}
						}
						else
						{ ?>
							<label class="radio-inline front_radio">
							<?php esc_html_e('No Any Gates.','apartment_mgt');
							echo "</label>";
						}
					?>
						
					</div>
			    </div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="visitreason_category"><?php esc_html_e('Reason For Visit','apartment_mgt');?></label>
					<div class="col-sm-8">
						<select class="form-control onlyletter_number_space_validation visit_reason_cat visit_reason_append" name="reason_id" maxlength="50">
						<option value=""><?php esc_html_e('Select Reason','apartment_mgt');?></option>
						<?php 
						if($edit)
							$category =$result->reason_id;
						elseif(isset($_REQUEST['reason_id']))
							$category =$_REQUEST['reason_id'];  
						else 
							$category = "";
						
						$activity_category=amgt_get_all_category('visit_reason_cat');
						if(!empty($activity_category))
						{
							foreach ($activity_category as $retrive_data)
							{
								echo '<option value="'.$retrive_data->ID.'" '.selected($category,$retrive_data->ID).'>'.$retrive_data->post_title.'</option>';	
							}
						} ?>
						</select>
					</div>
					<div class="col-sm-2 margin_top_10_res"><button id="addremove" model="visit_reason_cat"><?php esc_html_e('Add Or Remove','apartment_mgt');?></button></div>
				</div>
				<?php
				if($obj_apartment->role == 'member')
				{
					$member_id=get_current_user_id();
					$building =get_user_meta($member_id,'building_id',true);
					$unit_cat_id =get_user_meta($member_id,'unit_cat_id',true);
					$unit_name =get_user_meta($member_id,'unit_name',true);
				?>
				<input type="hidden" name="building_id" value="<?php echo esc_attr($building);?>"  />	
				<input type="hidden" name="unit_cat_id" value="<?php echo esc_attr($unit_cat_id);?>"  />	
				<input type="hidden" name="unit_name" value="<?php echo esc_attr($unit_name);?>"  />	
				<?php
				}
				else{
				?>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Compound','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control building_category validate[required] visitor_compound_append" name="building_id">
						<option value=""><?php esc_html_e('Select Compound','apartment_mgt');?></option>
						<?php 
						if($edit)
							$category =$result->building_id;
						elseif(isset($_REQUEST['building_id']))
							$category =$_REQUEST['building_id'];  
						else 
							$category = "";
						
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
					
				</div>
				
				<div class="form-group"><!--UNIT CATEGORY-->
					<label class="col-sm-2 control-label" for="activity_category"><?php esc_html_e('Unit Category','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control unit_categorys validate[required] visitor_unit_cat_append" name="unit_cat_id">
						<option value=""><?php esc_html_e('Select Unit Category','apartment_mgt');?></option>
						<?php 
							if($edit)
								$category =$result->unit_cat;
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
							} ?>
						</select>
					</div>
				</div><!--END UNIT CATEGORY-->
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="unit_name"><?php esc_html_e('Unit','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-8">
						<select class="form-control unit_name validate[required] visitor_unit_name_append" name="unit_name">
						<option value=""><?php esc_html_e('Select Unit Name','apartment_mgt');?></option>
						<?php 
						if($edit)
						{
							$unitname =$result->unit_name;
							$building_id=$result->building_id;
							 $unitsarray=$obj_units->amgt_get_single_cat_units($building_id,$result->unit_cat);
							 $all_entry=json_decode($unitsarray);
							
							if(!empty($all_entry))
							{
								foreach($all_entry as $unit)
								{ ?>
									<option value="<?php echo esc_attr($unit->value); ?>" <?php selected($unitname,$unit->value);?>><?php echo esc_html($unit->value);?> </option>
								<?php 
								}
							}
					
						} ?>
						</select>
					</div>
				</div>
				<?php
				}
				?>
				<div class="form-group"><!--CHECK IN DATE---->
					<label class="col-sm-2 control-label" for="vehicle"><?php esc_html_e('Check In Date','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-3">
						<input id="checkin_date" class="form-control validate[required]" type="text" autocomplete="off" name="checkin_date" 
						value="<?php if($edit){ echo date("Y-m-d",strtotime($result->checkin_date));}elseif(isset($_POST['checkin_date'])) echo $_POST['checkin_date']; else echo date("Y-m-d");?>">
					 </div>
					 <label class="col-sm-2 control-label margin_top_10_res" for="vehicle"><?php esc_html_e('Check In Time','apartment_mgt');?><span class="require-field">*</span></label>
					<div class="col-sm-3">
						<input type="text" value="<?php if($edit){ echo esc_attr($result->checkin_time);}elseif(isset($_POST['checkintime'])) echo esc_attr($_POST['checkintime']);?>" class="form-control timepicker validate[required]" name="checkintime"/>
					 </div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label" for="desc"><?php esc_html_e('Description','apartment_mgt');?></label>
					<div class="col-sm-8">
						 <textarea name="description" id="description" maxlength="150" class="form-control visitor_des_append validate[custom[address_description_validation]] text-input"><?php if($edit) echo esc_textarea($result->description);?></textarea>
					</div>
				</div>
							<?php 
			if($edit)
			{
				$all_visiter_entry=json_decode($result->visiters_value);
				?>
				<div id="add_visiter_entry_div">
				<?php
				$v=0;
				if(!empty($all_visiter_entry))
				{
					foreach($all_visiter_entry as $entry1)
					{
						?>	
							<div class="row padding_left_10">
								<div class="form-group">
									<label class="col-sm-2 control-label" for="name"><?php esc_html_e('Visitor Name','apartment_mgt');?><span class="require-field">*</span></label>
									<div class="col-sm-8">
										<input type="text" value="<?php echo esc_attr($entry1->visitor_name);?>" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input onlyletter_number_space_validation" maxlength="50" name="visitor_name[]"/>
									 </div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label " for="mobile"><?php esc_html_e('ID Number','apartment_mgt');?><span class="require-field">*</span></label>
									
									<div class="col-sm-3">
										<input id="mobile" cclass="form-control validate[required,custom[onlyLetterNumber]] text-input" type="text" maxlength="15"  name="mobile[]" 
										value="<?php echo esc_attr($entry1->mobile);?>">
									</div>
									<label class="col-sm-2 control-label margin_top_10_res" for="vehicle"><?php esc_html_e('Vehicle Number','apartment_mgt');?></label>
									<div class="col-sm-3">
										<input type="text" value="<?php echo esc_attr($entry1->vehicle_number);?>" maxlength="20" class="form-control text-input" name="vehicle_number[]"/>
									 </div>
								</div>
								<?php
								if($v > 0)
								{
									?>	
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-2">
											<button type="button" class="btn btn-default" onclick="deletevisiterentry(this)">
											<i class="entypo-trash"><?php esc_html_e('Delete','apartment_mgt');?></i>
											</button>
										</div>
									</div>
									<?php
								}
								?>
							</div>
						
						<?php
						$v=$v+1;
					}
				}
				else
				{
					?>
					<div class="row padding_left_10">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="name"><?php esc_html_e('Visitor Name','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-8">
								<input type="text" value="" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input onlyletter_number_space_validation" maxlength="50" name="visitor_name[]"/>
							 </div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label " for="mobile"><?php esc_html_e('ID Number','apartment_mgt');?><span class="require-field">*</span></label>
							
							<div class="col-sm-3">
								<input id="mobile" class="form-control validate[required,custom[onlyLetterNumber]] text-input" type="text" maxlength="15"  name="mobile[]" 
								value="">
							</div>
							<label class="col-sm-2 control-label margin_top_10_res" for="vehicle"><?php esc_html_e('Vehicle Number','apartment_mgt');?></label>
							<div class="col-sm-3">
								<input type="text" value="" maxlength="20" class="form-control text-input" name="vehicle_number[]"/>
							 </div>
						</div>	
					</div>
					<?php
				}
				?>
				</div>				
				<?php
			}
			else
			{
				?>	
				<div id="add_visiter_entry_div">
					<div class="row padding_left_10">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="name"><?php esc_html_e('Visitor Name','apartment_mgt');?><span class="require-field">*</span></label>
							<div class="col-sm-6">
								<input type="text" value="" class="visitor_name form-control validate[required,custom[onlyLetter_specialcharacter]] text-input onlyletter_number_space_validation" maxlength="50" name="visitor_name[]"/>
							 </div>
							 <div class="col-sm-2">
							    <button type="button" class="btn btn-info margin_top_10_res visitor_details_search"><?php esc_html_e('Search','apartment_mgt');?></button>
							 </div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label " for="mobile"><?php esc_html_e('ID Number','apartment_mgt');?><span class="require-field">*</span></label>
							
							<div class="col-sm-3">
								<input id="mobile" class="form-control validate[required,custom[onlyLetterNumber]] text-input" type="text" maxlength="15"  name="mobile[]" 
								value="">
							</div>
							<label class="col-sm-2 control-label margin_top_10_res" for="vehicle"><?php esc_html_e('Vehicle Number','apartment_mgt');?></label>
							<div class="col-sm-3">
								<input type="text" value="" maxlength="20" class="form-control text-input visitor_vehicle_number" name="vehicle_number[]"/>
							 </div>
						</div>	
					</div>
				</div>
				<?php
			}
			?>	
			<div class="form-group">
				<label class="col-sm-2 control-label" for="unit_entry"></label>
				<div class="col-sm-3">
					<button id="add_new_visitor" class="btn btn-default btn-sm btn-icon icon-left" type="button"   name="add_visiter_entry"><?php esc_html_e('Add More Entry','apartment_mgt'); ?>
					</button>
				</div>
			</div>
				<?php wp_nonce_field( 'save_visitor_checkin_nonce' ); ?>
				<div class="col-sm-offset-2 col-sm-8">
					<input type="submit" value="<?php if($edit){ esc_html_e('Checkin','apartment_mgt'); }else{ esc_html_e('Checkin','apartment_mgt');}?>" name="save_visitor_checkin" class="btn btn-success"/>
				</div>
		
            </form><!--END VISITOR_CHECKIN_FORM-->
        </div><!--END PANEL BODY DIV--->
     <?php } ?>